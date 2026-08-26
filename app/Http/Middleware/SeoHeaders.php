<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $path = trim($request->path(), '/');

        if ($request->is('admin', 'admin/*', 'analyze', 'download-file', 'prepare-plugin-download')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        } else {
            $response->headers->set('X-Robots-Tag', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
        }

        if ($path === 'sitemap.xml') {
            $response->headers->set('Cache-Control', 'public, max-age=3600');
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
