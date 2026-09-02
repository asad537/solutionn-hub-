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
        $xml = Http::timeout(20)->get('https://trends.google.com/trending/rss?geo=US')->body();
        preg_match_all('/<item>.*?<title>(?:<!\[CDATA\[)?(.*?)(?:\]\]>)?<\/title>.*?<\/item>/s', $xml, $m);
        $topics = array_values(array_filter(array_map('trim', $m[1] ?? [])));
        $allowed = ['video', 'download', 'reel', 'tiktok', 'instagram', 'youtube', 'facebook', 'pinterest', 'whatsapp', 'vimeo', 'dailymotion', 'media', 'mp4', 'audio', 'streaming', 'watermark'];
        $topic = collect($topics)->first(function ($t) use ($allowed) {
            $t = strtolower($t);
            return strlen($t) > 3 && collect($allowed)->contains(fn ($word) => str_contains($t, $word)) && !BlogPost::where('title', 'like', "%{$t}%")->exists();
        });
        if (!$topic) {
            $fallbacks = [
                'how to download YouTube videos safely',
                'how to download TikTok videos without watermark',
                'how to download Instagram Reels',
                'how to download Facebook videos',
                'best video format for WhatsApp Status',
            ];
            $topic = collect($fallbacks)->first(fn ($t) => !BlogPost::where('title', 'like', "%{$t}%")->exists());
        }
        if (!$topic) return self::FAILURE;

        $prompt = "Write a helpful, original 1000-word SEO blog about the public-media topic: {$topic}. Return ONLY valid JSON with keys title, excerpt, meta_title, meta_description, category, content, image_alt. Content must be safe, factual, HTML with h2/p/ul, and mention permission/copyright. Add 1-3 natural internal links in the HTML content to relevant Solution Hub platform pages using these exact URLs: https://solutionhub.digital/youtube-video-downloader, https://solutionhub.digital/tiktok-video-downloader, https://solutionhub.digital/instagram-video-downloader, https://solutionhub.digital/facebook-video-downloader, https://solutionhub.digital/pinterest-video-downloader, and https://solutionhub.digital/supported-platforms. Do not invent statistics or news.";
        $response = Http::timeout(90)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key='.urlencode((string) config('services.gemini.key')), ['contents'=>[['parts'=>[['text'=>$prompt]]]]]);
        if (!$response->successful()) { $this->error('Gemini request failed'); return self::FAILURE; }
        $text = $response->json('candidates.0.content.parts.0.text', '');
        $text = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($text)));
        $start = strpos($text, '{'); $end = strrpos($text, '}');
        $data = ($start !== false && $end !== false) ? json_decode(substr($text, $start, $end - $start + 1), true) : null;
        if (!is_array($data) || empty($data['title']) || empty($data['content'])) return self::FAILURE;
        $slug = Str::slug($data['title']);
        if (BlogPost::where('slug', $slug)->exists()) return self::FAILURE;
        $image = '/images/blog/generated/'.$slug.'.svg';
        File::ensureDirectoryExists(public_path('images/blog/generated'));
        File::put(public_path($image), '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675"><rect width="1200" height="675" fill="#101827"/><circle cx="980" cy="120" r="300" fill="#3668de" opacity=".5"/><text x="70" y="180" fill="#39e1b6" font-family="Arial" font-size="28">SOLUTION HUB GUIDE</text><text x="70" y="270" fill="white" font-family="Arial" font-size="48" font-weight="700">'.e(Str::limit($data['title'], 42)).'</text></svg>');
        BlogPost::create(['title'=>$data['title'],'slug'=>$slug,'category'=>$data['category'] ?? 'Guide','excerpt'=>$data['excerpt'] ?? Str::limit(strip_tags($data['content']), 180),'meta_title'=>$data['meta_title'] ?? $data['title'],'meta_description'=>$data['meta_description'] ?? Str::limit(strip_tags($data['excerpt'] ?? ''),155),'content'=>$data['content'],'image'=>$image,'image_alt'=>$data['image_alt'] ?? $data['title'],'read_minutes'=>5,'is_published'=>(bool)$this->option('publish'),'published_at'=>$this->option('publish') ? now() : null]);
        $this->info("Created: {$slug}"); return self::SUCCESS;
    }
}
