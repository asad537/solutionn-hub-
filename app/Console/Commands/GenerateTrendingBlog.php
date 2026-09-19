<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GenerateTrendingBlog extends Command
{
    protected $signature = 'blog:generate-trending {--publish : Publish immediately}';
    protected $description = 'Generate one SEO blog from a Google Trends topic using Gemini';

    public function handle()
    {
        $topics = [];
        foreach (['US', 'PK'] as $geo) {
            try {
                $response = Http::timeout(20)->get('https://trends.google.com/trending/rss', ['geo' => $geo]);
                if (!$response->successful()) continue;
                preg_match_all('/<item>.*?<title>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/title>.*?<\/item>/s', $response->body(), $matches);
                $topics = array_merge($topics, array_map('trim', $matches[1] ?? []));
            } catch (\Throwable $e) {
                report($e);
            }
        }
        $allowed = ['video', 'download', 'reel', 'tiktok', 'instagram', 'youtube', 'facebook', 'pinterest', 'whatsapp', 'vimeo', 'dailymotion', 'media', 'mp4', 'audio', 'streaming', 'watermark'];
        $topic = $this->firstUncoveredTopic($topics, $allowed);

        // General daily Trends often has no media-related searches. Ask Google's
        // live autocomplete for related searches instead of inventing a topic.
        if (!$topic) {
            $suggestions = [];
            $queries = [
                'youtube video save', 'youtube shorts save', 'instagram reels video',
                'facebook reels video', 'tiktok video quality', 'pinterest video pin',
                'whatsapp status video', 'dailymotion video', 'twitter video save',
            ];
            foreach ($queries as $query) {
                try {
                    $response = Http::timeout(12)->get('https://suggestqueries.google.com/complete/search', [
                        'client' => 'firefox', 'q' => $query,
                    ]);
                    if (!$response->successful()) continue;
                    $items = $response->json('1', []);
                    if (is_array($items)) $suggestions = array_merge($suggestions, $items);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
            $topic = $this->firstUncoveredTopic($suggestions, $allowed);
            if (!$topic) {
                $this->error('Google Trends had no relevant media trend, and Google suggestions returned no uncovered media topic. No blog was generated.');
                return self::FAILURE;
            }
            $this->line('Google Trends had no matching topic; selected an uncovered query from Google Search Suggestions.');
        }

        $prompt = "Write a helpful, original 1000-word SEO blog about the public-media topic: {$topic}. Return ONLY valid JSON with keys title, excerpt, meta_title, meta_description, category, content, image_alt. Content must be safe, factual, HTML with h2/p/ul, and mention permission/copyright. Add 1-3 natural internal links in the HTML content to relevant Solution Hub platform pages using these exact URLs: https://solutionhub.digital/youtube-video-downloader, https://solutionhub.digital/tiktok-video-downloader, https://solutionhub.digital/instagram-video-downloader, https://solutionhub.digital/facebook-video-downloader, https://solutionhub.digital/pinterest-video-downloader, and https://solutionhub.digital/supported-platforms. Do not invent statistics or news.";
        $response = null;
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                $response = Http::timeout(90)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key='.urlencode((string) config('services.gemini.key')), ['contents'=>[['parts'=>[['text'=>$prompt]]]]]);
            } catch (\Throwable $e) {
                if ($attempt === 3) {
                    $this->error('Gemini connection failed after 3 attempts: '.$e->getMessage());
                    return self::FAILURE;
                }
                sleep($attempt);
                continue;
            }

            if ($response->successful() || ($response->status() < 500 && $response->status() !== 429)) break;
            if ($attempt < 3) sleep($attempt);
        }
        if (!$response || !$response->successful()) {
            $reason = $response ? $response->json('error.message', 'No error detail returned') : 'No response received';
            $this->error('Gemini request failed'.($response ? ' (HTTP '.$response->status().')' : '').': '.Str::limit($reason, 400));
            return self::FAILURE;
        }
        $text = $response->json('candidates.0.content.parts.0.text', '');
        $text = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($text)));
        $start = strpos($text, '{'); $end = strrpos($text, '}');
        $data = ($start !== false && $end !== false) ? json_decode(substr($text, $start, $end - $start + 1), true) : null;
        if (!is_array($data) || empty($data['title']) || empty($data['content'])) {
            $this->error('Gemini responded, but its content was not valid blog JSON.');
            return self::FAILURE;
        }
        $data['title'] = Str::limit(trim($data['title']), 240, '');
        $slug = Str::slug($data['title']);
        if (BlogPost::where('slug', $slug)->exists()) {
            $this->error('Gemini suggested a title already used by another blog; skipped to prevent a duplicate.');
            return self::FAILURE;
        }
        $image = '/images/blog/generated/'.$slug.'.svg';
        File::ensureDirectoryExists(public_path('images/blog/generated'));
        File::put(public_path($image), $this->makeThumbnailSvg($data['title'], $slug));
        BlogPost::create(['title'=>$data['title'],'slug'=>$slug,'category'=>$data['category'] ?? 'Guide','excerpt'=>$data['excerpt'] ?? Str::limit(strip_tags($data['content']), 180),'meta_title'=>$data['meta_title'] ?? $data['title'],'meta_description'=>$data['meta_description'] ?? Str::limit(strip_tags($data['excerpt'] ?? ''),155),'content'=>$data['content'],'image'=>$image,'image_alt'=>$data['image_alt'] ?? $data['title'],'read_minutes'=>5,'is_published'=>(bool)$this->option('publish'),'published_at'=>$this->option('publish') ? now() : null]);
        $this->info("Created: {$slug}"); return self::SUCCESS;
    }

    private function firstUncoveredTopic(array $topics, array $allowed): ?string
    {
        $existingTitles = BlogPost::pluck('title')->map(function ($title) {
            return preg_replace('/[^a-z0-9]+/i', ' ', strtolower($title));
        });

        foreach ($topics as $candidate) {
            $topic = trim(html_entity_decode(strip_tags((string) $candidate), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $normalized = preg_replace('/[^a-z0-9]+/i', ' ', strtolower($topic));
            $words = array_values(array_unique(array_filter(explode(' ', $normalized), function ($word) {
                return strlen($word) > 2 && !in_array($word, ['how', 'the', 'for', 'and', 'with', 'from', 'your', 'into'], true);
            })));

            if (strlen($topic) < 8 || count($words) < 2) continue;
            if (!collect($allowed)->contains(fn ($word) => str_contains($normalized, $word))) continue;

            $covered = $existingTitles->contains(function ($title) use ($normalized, $words) {
                if (str_contains($title, $normalized) || str_contains($normalized, $title)) return true;
                $titleWords = array_unique(array_filter(explode(' ', $title), fn ($word) => strlen($word) > 2));
                $coverage = count(array_intersect($words, $titleWords)) / max(1, count($words));
                return $coverage >= 0.75;
            });

            if (!$covered) return $topic;
        }

        return null;
    }

    private function makeThumbnailSvg(string $title, string $slug): string
    {
        $safeTitle = htmlspecialchars($title, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $words = preg_split('/\s+/', trim($safeTitle));
        $lines = [];
        $line = '';
        foreach ($words as $word) {
            if (mb_strlen(trim($line.' '.$word)) > 22 && $line !== '') {
                $lines[] = trim($line);
                $line = '';
            }
            $line .= ' '.$word;
        }
        if (trim($line) !== '') $lines[] = trim($line);
        $lines = array_slice($lines, 0, 4);

        $titleSvg = '';
        foreach ($lines as $index => $text) {
            $titleSvg .= '<text x="74" y="'.(245 + $index * 61).'" class="title">'.$text.'</text>';
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1200" height="675" viewBox="0 0 1200 675" role="img" aria-labelledby="title desc"><title id="title">'.$safeTitle.'</title><desc id="desc">A modern illustration of a video player, download button, and safety shield.</desc><defs><linearGradient id="bg" x2="1" y2="1"><stop stop-color="#081329"/><stop offset="1" stop-color="#111d43"/></linearGradient><linearGradient id="panel" x2="0" y2="1"><stop stop-color="#203b71"/><stop offset="1" stop-color="#101b35"/></linearGradient><linearGradient id="accent"><stop stop-color="#35e7c3"/><stop offset="1" stop-color="#378dff"/></linearGradient><linearGradient id="fade" x2="1"><stop stop-color="#081329" stop-opacity=".97"/><stop offset=".56" stop-color="#081329" stop-opacity=".88"/><stop offset="1" stop-color="#081329" stop-opacity=".08"/></linearGradient><filter id="glow"><feGaussianBlur stdDeviation="14"/></filter><style>.title{font:700 43px Arial,sans-serif;fill:#fff;letter-spacing:-.7px}.label{font:700 16px Arial,sans-serif;letter-spacing:2px;fill:#65f1d2}.small{font:600 13px Arial,sans-serif;fill:#d7e4ff}</style></defs><rect width="1200" height="675" fill="url(#bg)"/><circle cx="955" cy="330" r="255" fill="#1c55c5" opacity=".22"/><circle cx="992" cy="329" r="130" fill="#24ddca" opacity=".12" filter="url(#glow)"/><g opacity=".28" stroke="#3b70c6"><path d="M745 0v675M825 0v675M905 0v675M985 0v675M1065 0v675M1145 0v675"/><path d="M690 120h510M690 200h510M690 280h510M690 360h510M690 440h510M690 520h510"/></g><g transform="translate(690 175)"><rect x="22" y="10" width="388" height="262" rx="22" fill="#061126" stroke="#5784dc" stroke-width="3" transform="rotate(-5 216 141)"/><rect x="0" y="54" width="410" height="263" rx="22" fill="url(#panel)" stroke="#7d9ee8" stroke-width="3"/><rect x="0" y="54" width="410" height="35" rx="20" fill="#172746"/><circle cx="24" cy="72" r="5" fill="#ff6b81"/><circle cx="43" cy="72" r="5" fill="#ffd36b"/><circle cx="62" cy="72" r="5" fill="#45e4b5"/><rect x="22" y="108" width="248" height="151" rx="12" fill="#0a1530" stroke="#37568f"/><path d="M25 211l49-46 34 28 47-58 49 54 35-32 31 27v69H25z" fill="#1b3872"/><path d="M24 224l58-33 36 20 42-40 39 33 43-28 30 19v64H24z" fill="#243e74" opacity=".9"/><circle cx="145" cy="178" r="24" fill="#fff" opacity=".95"/><path d="M139 164l21 14-21 14z" fill="#2167e8"/><rect x="288" y="109" width="98" height="19" rx="6" fill="#5785dd"/><rect x="288" y="141" width="78" height="11" rx="5" fill="#45649e"/><rect x="288" y="165" width="92" height="11" rx="5" fill="#45649e"/><rect x="288" y="201" width="84" height="43" rx="9" fill="#192d55" stroke="#36598f"/><rect x="22" y="277" width="366" height="6" rx="3" fill="#18325f"/><rect x="22" y="277" width="221" height="6" rx="3" fill="url(#accent)"/><circle cx="244" cy="280" r="8" fill="#fff"/></g><g transform="translate(900 77)"><circle cx="111" cy="111" r="105" fill="#0d244d" stroke="#47ddec" stroke-width="5"/><path d="M111 43v100m-43-41l43 43 43-43" fill="none" stroke="url(#accent)" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/><path d="M58 151v31a14 14 0 0014 14h78a14 14 0 0014-14v-31" fill="none" stroke="#3be6ce" stroke-width="13" stroke-linecap="round"/></g><g transform="translate(1020 420)"><path d="M95 0l76 28v63c0 60-42 98-76 117-35-19-77-57-77-117V28z" fill="#0b1c3c" stroke="#bdeaff" stroke-width="8"/><path d="M60 91l27 29 49-57" fill="none" stroke="#32e3c6" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/></g><rect width="1200" height="675" fill="url(#fade)"/><rect x="69" y="82" width="7" height="35" rx="3.5" fill="url(#accent)"/><text x="95" y="107" class="label">SOLUTION HUB  /  '.strtoupper(substr($slug, 0, 24)).'</text>'.$titleSvg.'<rect x="74" y="532" width="128" height="4" rx="2" fill="url(#accent)"/><text x="74" y="570" class="small">PRACTICAL GUIDE  ·  SAFE &amp; SIMPLE</text></svg>';
    }
}
