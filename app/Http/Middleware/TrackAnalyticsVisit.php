<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrackAnalyticsVisit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$request->isMethod('GET') || $request->is('admin*') || $request->is('assets/*') || $request->is('ckeditor/*')) {
            return $response;
        }

        try {
            if (!Schema::hasTable('analytics_visits')) return $response;

            $ua = (string) $request->userAgent();
            if (preg_match('/bot|crawl|spider|slurp|preview/i', $ua)) return $response;

            $referrer = (string) $request->headers->get('referer', '');
            $refHost = strtolower((string) parse_url($referrer, PHP_URL_HOST));
            $ownHost = strtolower((string) $request->getHost());
            $source = 'Direct';
            if ($refHost && $refHost !== $ownHost) {
                if (preg_match('/google\.|bing\.|yahoo\.|duckduckgo\./', $refHost)) $source = 'Organic Search';
                elseif (preg_match('/facebook\.|instagram\.|tiktok\.|twitter\.|x\.com|pinterest\./', $refHost)) $source = 'Social';
                else $source = preg_replace('/^www\./', '', $refHost);
            } elseif ($refHost === $ownHost) {
                $source = 'Internal';
            }

            $device = preg_match('/tablet|ipad/i', $ua) ? 'Tablet' : (preg_match('/mobile|iphone|android/i', $ua) ? 'Mobile' : 'Desktop');
            $sessionId = $request->session()->getId();
            $countryCode = strtoupper((string) ($request->headers->get('CF-IPCountry') ?: $request->headers->get('X-Country-Code')));
            $country = (string) ($request->headers->get('CF-IPCountry-Name') ?: $countryCode ?: 'Unknown');

            DB::table('analytics_visits')->insert([
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
                'country_code' => $countryCode ?: null,
                'country' => $country,
                'city' => $request->headers->get('CF-IPCity') ?: $request->headers->get('X-City'),
                'path' => '/' . ltrim($request->path(), '/'),
                'referrer' => $referrer ?: null,
                'source' => $source,
                'device' => $device,
                'user_agent' => substr($ua, 0, 1000),
                'last_seen_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }
}
