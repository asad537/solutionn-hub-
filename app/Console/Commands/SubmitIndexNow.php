<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Platform;
use App\Models\Blog;
use App\Models\BlogPost;
use App\Models\Guide;

class SubmitIndexNow extends Command
{
    protected $signature = 'indexnow:submit';
    protected $description = 'Submit all indexable site URLs to IndexNow (Bing, Yandex, etc)';

    public function handle()
    {
        $apiKey = '1c14967383b74e45b029f4c1d8950911';
        $host = 'solutionhub.digital';
        $baseUrl = 'https://solutionhub.digital';

        $urls = [
            $baseUrl . '/',
            $baseUrl . '/supported-platforms',
            $baseUrl . '/blog',
            $baseUrl . '/faqs',
            $baseUrl . '/privacy',
            $baseUrl . '/terms',
            $baseUrl . '/disclaimer',
            $baseUrl . '/about',
            $baseUrl . '/contact',
            $baseUrl . '/dmca',
        ];

        // Add Platforms
        $platforms = Platform::where('status', 'active')->get();
        foreach ($platforms as $p) {
            $urls[] = $baseUrl . '/' . $p->slug;
        }

        // Add Blogs
        $blogs = Blog::where('status', 1)->get();
        foreach ($blogs as $b) {
            $urls[] = $baseUrl . '/blog/' . $b->slug;
        }

        // Add Legacy Blogs
        $existingSlugs = $blogs->pluck('slug')->all();
        $legacyBlogs = BlogPost::published()->whereNotIn('slug', $existingSlugs)->get();
        foreach ($legacyBlogs as $lb) {
            $urls[] = $baseUrl . '/blog/' . $lb->slug;
        }

        // Add Guides
        $guides = Guide::where('status', 1)->get();
        foreach ($guides as $g) {
            $urls[] = $baseUrl . '/guide/' . $g->slug;
        }

        $urls = array_values(array_unique($urls));

        $payload = [
            'host' => $host,
            'key' => $apiKey,
            'keyLocation' => $baseUrl . '/' . $apiKey . '.txt',
            'urlList' => $urls,
        ];

        $endpoints = [
            'https://api.indexnow.org/indexnow',
            'https://www.bing.com/indexnow',
        ];

        $this->info("Submitting " . count($urls) . " URLs to IndexNow...");

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json; charset=utf-8',
                ])->post($endpoint, $payload);

                $status = $response->status();
                if ($status >= 200 && $status < 300) {
                    $this->info("SUCCESS [{$status}] from {$endpoint}");
                } else {
                    $this->warn("HTTP {$status} response from {$endpoint}: " . $response->body());
                }
            } catch (\Exception $e) {
                $this->error("Failed to post to {$endpoint}: " . $e->getMessage());
            }
        }

        $this->info("IndexNow submission completed.");
        return 0;
    }
}
