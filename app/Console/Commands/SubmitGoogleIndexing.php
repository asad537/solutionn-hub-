<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Platform;
use App\Models\Blog;
use App\Models\BlogPost;
use App\Models\Guide;

class SubmitGoogleIndexing extends Command
{
    protected $signature = 'google:index';
    protected $description = 'Submit all site URLs to Google Indexing API';

    public function handle()
    {
        $jsonPath = storage_path('app/google_service_account.json');
        if (!file_exists($jsonPath)) {
            $this->error("Google Service Account file not found at {$jsonPath}");
            return 1;
        }

        $config = json_decode(file_get_contents($jsonPath), true);
        if (empty($config['client_email']) || empty($config['private_key'])) {
            $this->error("Invalid Google Service Account JSON config.");
            return 1;
        }

        $clientEmail = $config['client_email'];
        $rawKey = $config['private_key'];
        $privateKeyStr = str_replace(['\n', "\\n", "\r"], ["\n", "\n", ""], $rawKey);
        $pkey = openssl_pkey_get_private($privateKeyStr);

        if (!$pkey) {
            $this->error("Failed to parse private key: " . openssl_error_string());
            return 1;
        }

        $this->info("Service Account Email: {$clientEmail}");

        // 1. Generate JWT Token
        $now = time();
        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/indexing',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]));

        $header = str_replace(['+', '/', '='], ['-', '_', ''], $header);
        $payload = str_replace(['+', '/', '='], ['-', '_', ''], $payload);

        $toSign = $header . '.' . $payload;
        $signature = '';
        if (!openssl_sign($toSign, $signature, $pkey, OPENSSL_ALGO_SHA256)) {
            $this->error("Failed to sign JWT with private key: " . openssl_error_string());
            return 1;
        }
        $jwtSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $toSign . '.' . $jwtSignature;

        // 2. Obtain OAuth2 Access Token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (!$tokenResponse->successful()) {
            $this->error("Failed to get Google Access Token: " . $tokenResponse->body());
            return 1;
        }

        $accessToken = $tokenResponse->json('access_token');
        $this->info("Successfully retrieved Google OAuth2 Access Token.");

        // 3. Collect URLs
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
        $this->info("Submitting " . count($urls) . " URLs to Google Indexing API...");

        $successCount = 0;
        $failCount = 0;

        foreach ($urls as $url) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                'url' => $url,
                'type' => 'URL_UPDATED',
            ]);

            if ($response->successful()) {
                $successCount++;
                $this->info("✔ Submitted: {$url}");
            } else {
                $failCount++;
                $this->warn("✖ Failed ({$response->status()}): {$url} -> " . $response->body());
            }
            // Small delay to avoid API rate limit throttling
            usleep(100000);
        }

        $this->info("Finished! Success: {$successCount}, Failed: {$failCount}");
        return 0;
    }
}
