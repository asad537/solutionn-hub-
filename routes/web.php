<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\PlatformController;
use App\Services\PlatformDetector;
use App\Models\BlogPost;

$platforms = [
    ['name' => 'YouTube', 'domain' => 'youtube.com', 'accent' => '#ff3b30', 'icon' => 'youtube', 'slug' => 'youtube-video-downloader'],
    ['name' => 'Facebook', 'domain' => 'facebook.com', 'accent' => '#1877f2', 'icon' => 'facebook', 'slug' => 'facebook-video-downloader'],
    ['name' => 'Instagram', 'domain' => 'instagram.com', 'accent' => '#e1306c', 'icon' => 'instagram', 'slug' => 'instagram-video-downloader'],
    ['name' => 'TikTok', 'domain' => 'tiktok.com', 'accent' => '#00b9d8', 'icon' => 'tiktok', 'slug' => 'tiktok-video-downloader'],
    ['name' => 'Twitter / X', 'domain' => 'x.com', 'accent' => '#111827', 'icon' => 'x', 'slug' => 'twitter-video-downloader'],
    ['name' => 'Vimeo', 'domain' => 'vimeo.com', 'accent' => '#1ab7ea', 'icon' => 'vimeo', 'slug' => 'vimeo-video-downloader'],
    ['name' => 'Dailymotion', 'domain' => 'dailymotion.com', 'accent' => '#00aaff', 'icon' => 'dailymotion', 'slug' => 'dailymotion-video-downloader'],
    ['name' => 'Pinterest', 'domain' => 'pinterest.com', 'accent' => '#e60023', 'icon' => 'pinterest', 'slug' => 'pinterest-video-downloader'],
];

$softenSeoCopy = function (?string $text): string {
    if ($text === null || $text === '') {
        return '';
    }

    $replacements = [
        'Best Free Instagram Reels Downloader' => 'Instagram Reels Format Guide',
        'Top 5 Ways to Download Facebook Private Videos' => 'Facebook Video Privacy and Public Link Guide',
        'Save Password Protected Videos' => 'Understand Creator-Controlled Video Availability',
        'Without Watermark' => 'With Permission and Source Awareness',
        'without watermark' => 'with permission and source awareness',
        'Download YouTube Videos in 4K Quality for Free' => 'Review YouTube Video Quality and 4K Format Options',
        'Download Twitter / X Videos in HD' => 'Review Twitter and X Video Format Options',
        'Convert YouTube to MP3' => 'Review YouTube Audio Format Options',
        'video downloader' => 'media link analyzer',
        'Video Downloader' => 'Media Link Analyzer',
        'downloader' => 'link analyzer',
        'Downloader' => 'Link Analyzer',
        'download videos' => 'review available media formats',
        'Download videos' => 'Review available media formats',
        'downloaded videos' => 'saved media files',
        'Downloaded videos' => 'Saved media files',
        'downloaded video' => 'saved media file',
        'Downloaded Video' => 'Saved Media File',
        'download' => 'review',
        'Download' => 'Review',
        'save videos' => 'review media links',
        'Save videos' => 'Review media links',
        'save media' => 'review media',
        'Save media' => 'Review media',
        'save' => 'review',
        'Save' => 'Review',
        'private videos' => 'private-video limitations',
        'Private Videos' => 'Private-Video Limitations',
        'password protected videos' => 'access-controlled videos',
        'Password Protected Videos' => 'Access-Controlled Videos',
        'free of charge' => 'available in the browser',
        'Free' => 'Online',
        'free' => 'online',
        'massive 2000-word' => 'detailed',
        '2000-word' => 'detailed',
        'ultimate' => 'practical',
        'Ultimate' => 'Practical',
        'one-click' => 'simple',
        'lightning-fast' => 'quick',
        'flawless' => 'reliable',
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $text);
};

$loadPosts = function () use ($softenSeoCopy) {
    try {
        return BlogPost::published()->latest('published_at')->get()->map(function ($post) use ($softenSeoCopy) {
            return [
                'id' => $post->id,
                'title' => $softenSeoCopy($post->title),
                'meta_title' => $softenSeoCopy($post->meta_title),
                'slug' => $post->slug,
                'category' => $post->category,
                'excerpt' => $softenSeoCopy($post->excerpt),
                'description' => $softenSeoCopy($post->meta_description ?: $post->excerpt),
                'read' => $post->read_minutes . ' min read',
                'published' => optional($post->published_at)->format('M j, Y'),
                'image' => $post->image,
                'image_alt' => $softenSeoCopy($post->image_alt ?: $post->title),
                'content' => $softenSeoCopy($post->content),
            ];
        })->all();
    } catch (\Throwable $exception) {
        return [];
    }
};

// ── Admin Routes ──────────────────────────────────────────────────────────────
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'doLogin'])->name('admin.login.post');
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/dashboard-data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');
Route::get('/admin/homepage', [AdminController::class, 'homepageEdit'])->name('admin.homepage');
Route::post('/admin/homepage', [AdminController::class, 'homepageSave'])->name('admin.homepage.save');

// SEO Admin Routes
Route::get('/admin/seo-settings', [AdminController::class, 'seoSettings'])->name('admin.seo_settings');
Route::post('/admin/seo-settings', [AdminController::class, 'seoSettingsUpdate'])->name('admin.seo_settings.update');

// FAQ Admin Routes
Route::get('/admin/faqs', [AdminController::class, 'faqIndex'])->name('admin.faqs');
Route::post('/admin/faqs', [AdminController::class, 'faqStore'])->name('admin.faqs.store');
Route::get('/admin/faqs/{id}/edit', [AdminController::class, 'faqEdit'])->name('admin.faqs.edit');
Route::post('/admin/faqs/{id}/edit', [AdminController::class, 'faqUpdate'])->name('admin.faqs.update');
Route::delete('/admin/faqs/{id}', [AdminController::class, 'faqDelete'])->name('admin.faqs.delete');

// FAQ Page (Dedicated)
Route::get('/admin/faq-page', [AdminController::class, 'faqPageSettings'])->name('admin.faq_page');
Route::post('/admin/faq-page', [AdminController::class, 'faqPageStore'])->name('admin.faq_page.store');
Route::post('/admin/faq-page/seo', [AdminController::class, 'faqPageSeoSave'])->name('admin.faq_page.seo.save');
Route::delete('/admin/faq-page/{id}', [AdminController::class, 'faqPageDelete'])->name('admin.faq_page.delete');

// Download Page (Dedicated)
Route::get('/admin/download-page', [AdminController::class, 'downloadPage'])->name('admin.download_page');
Route::post('/admin/download-page', [AdminController::class, 'downloadPageSave'])->name('admin.download_page.save');

// Footer Settings
Route::get('/admin/footer-settings', [AdminController::class, 'footerSettings'])->name('admin.footer_settings');
Route::post('/admin/footer-settings', [AdminController::class, 'footerSettingsSave'])->name('admin.footer_settings.save');

// Blog Admin Routes
Route::get('/admin/blogs', [BlogController::class, 'index'])->name('admin.blogs.index');
Route::get('/admin/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/admin/blogs', [BlogController::class, 'store'])->name('admin.blogs.store');
Route::get('/admin/blogs/{id}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
Route::post('/admin/blogs/{id}', [BlogController::class, 'update'])->name('admin.blogs.update');
Route::delete('/admin/blogs/{id}', [BlogController::class, 'destroy'])->name('admin.blogs.delete');

// Legacy BlogPost edit routes
Route::get('/admin/legacy-blogs/{id}/edit', [BlogController::class, 'legacyEdit'])->name('admin.legacy_blogs.edit');
Route::post('/admin/legacy-blogs/{id}', [BlogController::class, 'legacyUpdate'])->name('admin.legacy_blogs.update');


// Guide Admin Routes
Route::get('/admin/guides', [GuideController::class, 'index'])->name('admin.guides.index');
Route::get('/admin/guides/create', [GuideController::class, 'create'])->name('admin.guides.create');
Route::post('/admin/guides', [GuideController::class, 'store'])->name('admin.guides.store');
Route::get('/admin/guides/{id}/edit', [GuideController::class, 'edit'])->name('admin.guides.edit');
Route::post('/admin/guides/{id}', [GuideController::class, 'update'])->name('admin.guides.update');
Route::delete('/admin/guides/{id}', [GuideController::class, 'destroy'])->name('admin.guides.delete');
Route::get('/guide/{slug}', [GuideController::class, 'publicShow'])->name('guide.show');

// Platform Admin Routes
Route::get('/admin/platforms', [PlatformController::class, 'index'])->name('admin.platforms.index');
Route::get('/admin/platforms/create', [PlatformController::class, 'create'])->name('admin.platforms.create');
Route::post('/admin/platforms', [PlatformController::class, 'store'])->name('admin.platforms.store');
Route::get('/admin/platforms/{id}/edit', [PlatformController::class, 'edit'])->name('admin.platforms.edit');
Route::post('/admin/platforms/{id}', [PlatformController::class, 'update'])->name('admin.platforms.update');
Route::delete('/admin/platforms/{id}', [PlatformController::class, 'destroy'])->name('admin.platforms.delete');
Route::post('/admin/platforms/{id}/faqs', [PlatformController::class, 'faqStore'])->name('admin.platforms.faqs.store');
Route::delete('/admin/platforms/faqs/{faq_id}', [PlatformController::class, 'faqDelete'])->name('admin.platforms.faqs.delete');

// Public FAQs
Route::get('/faqs', [AdminController::class, 'publicFaqs'])->name('public.faqs');

// CKEditor image upload
Route::post('/admin/cms/upload-editor-image', [AdminController::class, 'uploadEditorImage'])->name('admin.cms.upload-editor-image');

// ── Original Frontend Routes ──────────────────────────────────────────────────

Route::get('/', function () use ($platforms, $loadPosts) {
    try {
        $homeSettings = DB::table('homepage_settings')->first();
    } catch (\Throwable $exception) {
        $homeSettings = null;
    }
    try {
        $homeSeo = \App\Models\PageSeo::where('page_name', 'home')->first();
    } catch (\Throwable $exception) {
        $homeSeo = null;
    }
    try {
        $faqs = DB::table('faqs')->where('page', 'home')->where('is_active', true)->orderBy('sort_order')->get();
    } catch (\Throwable $exception) {
        $faqs = collect();
    }

    return view('welcome', [
        'page'         => 'home',
        'platforms'    => $platforms,
        'posts'        => $loadPosts(),
        'result'       => session('result'),
        'homeSettings' => $homeSettings,
        'homeSeo'      => $homeSeo,
        'faqs'         => $faqs,
    ]);
})->name('home');

Route::get('/supported-platforms', function () use ($platforms, $loadPosts) {
    return view('welcome', [
        'page'      => 'platforms',
        'platforms' => $platforms,
        'posts'     => $loadPosts(),
        'result'    => null,
    ]);
})->name('platforms');

Route::get('/blog', function () use ($platforms, $loadPosts) {
    return view('welcome', [
        'page'      => 'blog',
        'platforms' => $platforms,
        'posts'     => $loadPosts(),
        'result'    => null,
    ]);
})->name('blog');

// Replaced blog slug route to work with both models on the original welcome view
Route::get('/blog/{slug}', function ($slug) use ($platforms, $loadPosts, $softenSeoCopy) {
    $posts = $loadPosts();
    
    // Also include new blogs in the view if they want to access them
    $newBlog = \App\Models\Blog::where('slug', $slug)->first();
    
    if ($newBlog) {
        $post = [
            'id'          => 'new_' . $newBlog->id,
            'title'       => $newBlog->title,
            'slug'        => $newBlog->slug,
            'category'    => $newBlog->tags ?? 'WhatsApp',
            'excerpt'     => $newBlog->meta_description ?? '',
            'description' => $newBlog->meta_description ?? '',
            'meta_title'  => $newBlog->meta_title ?: $newBlog->title . ' | Solution Hub',
            'read'        => $newBlog->reading_time ?: '6 min read',
            'published'   => $newBlog->created_at ? $newBlog->created_at->format('M j, Y') : now()->format('M j, Y'),
            'image'       => $newBlog->featured_image ?: '/images/custom_blogs/img_1.png',
            'image_alt'   => $newBlog->image_alt ?: $newBlog->title,
            'content'     => $newBlog->renderContent(),
        ];
    } else {
        $post = collect($posts)->firstWhere('slug', $slug);
        abort_unless($post, 404);
    }

    return view('welcome', [
        'page'         => 'blog-post',
        'platforms'    => $platforms,
        'posts'        => $posts,
        'post'         => $post,
        'relatedPosts' => collect($posts)
            ->where('slug', '!=', $slug)
            ->sortByDesc(function ($item) use ($post) {
                return $item['category'] === $post['category'];
            })
            ->take(3),
        'result'       => null,
    ]);
})->name('blog.show');

Route::get('/privacy', function () use ($platforms, $loadPosts) {
    return view('welcome', [
        'page'      => 'privacy',
        'platforms' => $platforms,
        'posts'     => $loadPosts(),
        'result'    => null,
    ]);
})->name('privacy');

Route::get('/terms', function () use ($platforms, $loadPosts) {
    return view('welcome', [
        'page'      => 'terms',
        'platforms' => $platforms,
        'posts'     => $loadPosts(),
        'result'    => null,
    ]);
})->name('terms');

Route::get('/disclaimer', function () use ($platforms, $loadPosts) {
    return view('welcome', [
        'page'      => 'disclaimer',
        'platforms' => $platforms,
        'posts'     => $loadPosts(),
        'result'    => null,
    ]);
})->name('disclaimer');

foreach ([
    'about' => 'about',
    'contact' => 'contact',
    'dmca' => 'dmca',
] as $path => $page) {
    Route::get('/' . $path, function () use ($platforms, $loadPosts, $page) {
        return view('welcome', [
            'page' => $page,
            'platforms' => $platforms,
            'posts' => $loadPosts(),
            'result' => null,
        ]);
    })->name($page);
}

// Preserve old public URLs and consolidate every legal page on one canonical URL.
Route::permanentRedirect('/privacy-policy', '/privacy');
Route::permanentRedirect('/privacy-policy/', '/privacy');
Route::permanentRedirect('/terms-of-service', '/terms');
Route::permanentRedirect('/terms-of-service/', '/terms');

// ── Analyze / Download (existing hd-video-downloadr logic) ───────────────────
Route::post('/analyze', function (Request $request) {
    $platformsList = [
        ['name' => 'YouTube', 'domain' => 'youtube.com'],
        ['name' => 'Facebook', 'domain' => 'facebook.com'],
        ['name' => 'Instagram', 'domain' => 'instagram.com'],
        ['name' => 'TikTok', 'domain' => 'tiktok.com'],
        ['name' => 'Twitter / X', 'domain' => 'x.com'],
        ['name' => 'Vimeo', 'domain' => 'vimeo.com'],
        ['name' => 'Dailymotion', 'domain' => 'dailymotion.com'],
        ['name' => 'Pinterest', 'domain' => 'pinterest.com'],
    ];

    $data = $request->validate([
        'video_url' => ['required', 'url', 'max:2048'],
    ]);

    $host = parse_url($data['video_url'], PHP_URL_HOST) ?: 'public video link';
    $cleanHost = preg_replace('/^www\./', '', strtolower($host));
    $platform = collect($platformsList)->first(function ($item) use ($cleanHost) {
        return strpos($cleanHost, $item['domain']) !== false;
    });

    $pluginEndpoint = 'https://api.vidssave.com/api/contentsite_api/media/parse';
    $pluginToken = base64_encode('vidssave_brower_plugin_' . round(microtime(true) * 1000));

    try {
        $pluginData = [];
        foreach (['cache', 'source'] as $origin) {
            try {
                $response = Http::asForm()->retry(1, 500)->timeout(15)->post($pluginEndpoint, [
                    'auth' => '20250901majwlqo',
                    'domain' => 'api-ak.vidssave.com',
                    'origin' => $origin,
                    'link' => $data['video_url'],
                    'plugin_token' => $pluginToken,
                ]);
                $body = $response->json();
                if ($response->successful() && ($body['status'] ?? null) === 1 && !empty($body['data'])) {
                    $pluginData[$origin] = $body['data'];
                }
            } catch (\Throwable $exception) {
                continue;
            }
        }

        $videoData = $pluginData['source'] ?? $pluginData['cache'] ?? null;
        $allowPreparedFormats = !($platform && $platform['domain'] === 'youtube.com');
        $rawResources = array_merge(
            $pluginData['cache']['resources'] ?? [],
            $pluginData['source']['resources'] ?? []
        );

        if ($videoData) {
            $resources = collect($rawResources)->filter(function ($resource) {
                return is_array($resource);
            })->filter(function ($resource) use ($allowPreparedFormats) {
                return !empty($resource['download_url'])
                    || ($allowPreparedFormats && !empty($resource['resource_content']));
            })->unique(function ($resource) {
                return strtolower(implode('|', [
                    $resource['type'] ?? '',
                    $resource['quality'] ?? '',
                    $resource['format'] ?? '',
                ]));
            })->map(function ($resource) use ($allowPreparedFormats) {
                $rawType = strtolower((string) ($resource['type'] ?? ''));
                $rawFormat = $resource['format'] ?? $resource['ext'] ?? null;
                if (!$rawFormat && in_array($rawType, ['audio', 'music'], true)) {
                    $rawFormat = 'MP3';
                } elseif (!$rawFormat && in_array($rawType, ['video', 'media'], true)) {
                    $rawFormat = 'MP4';
                }
                $format = strtoupper((string) ($rawFormat ?? $resource['type'] ?? 'MP4'));
                $quality = (string) ($resource['quality'] ?? $resource['resolution'] ?? $resource['label'] ?? $resource['bitrate'] ?? 'Original');
                $typeText = strtolower(implode(' ', [
                    $resource['type'] ?? '',
                    $format,
                    $quality,
                ]));
                $isAudio = strpos($typeText, 'audio') !== false
                    || strpos($typeText, 'mp3') !== false
                    || strpos($typeText, 'm4a') !== false
                    || strpos($typeText, 'kbps') !== false;

                $rawSize = $resource['size'] ?? $resource['filesize'] ?? $resource['file_size'] ?? null;
                if (is_numeric($rawSize)) {
                    $bytes = (float) $rawSize;
                    if ($bytes >= 1073741824) {
                        $size = number_format($bytes / 1073741824, 2) . ' GB';
                    } elseif ($bytes >= 1048576) {
                        $size = number_format($bytes / 1048576, 2) . ' MB';
                    } elseif ($bytes >= 1024) {
                        $size = number_format($bytes / 1024, 2) . ' KB';
                    } else {
                        $size = number_format($bytes, 0) . ' B';
                    }
                } else {
                    $size = $rawSize ?: 'Size varies';
                }

                $downloadUrl = $resource['download_url']
                    ?? $resource['downloadUrl']
                    ?? $resource['download']
                    ?? $resource['url']
                    ?? $resource['link']
                    ?? $resource['src']
                    ?? null;

                $prepareToken = null;
                if ($allowPreparedFormats && !$downloadUrl && !empty($resource['resource_content'])) {
                    $prepareToken = \Illuminate\Support\Str::random(48);
                    Cache::put('plugin_prepare:' . $prepareToken, [
                        'request' => $resource['resource_content'],
                    ], now()->addMinutes(30));
                }

                return [
                    'category' => $isAudio ? 'audio' : 'video',
                    'format' => $isAudio && $format === 'AUDIO' ? 'MP3' : $format,
                    'quality' => $quality,
                    'size' => $size,
                    'download_url' => $downloadUrl,
                    'prepare_token' => $prepareToken,
                ];
            })->values()->all();

            $resultData = [
                'url' => $data['video_url'],
                'host' => $cleanHost,
                'platform' => $platform['name'] ?? 'Supported public source',
                'title' => $videoData['title'] ?? 'Unknown Title',
                'thumbnail' => $videoData['thumbnail'] ?? null,
                'duration' => $videoData['duration'] ?? 0,
                'resources' => $resources,
            ];

            DB::table('download_logs')->insert([
                'platform' => $platform['name'] ?? ucfirst(explode('.', $cleanHost)[0] ?: 'Unknown'),
                'format' => '—', 'quality' => '—', 'ip_address' => $request->ip(),
                'type' => 'extraction', 'status' => true,
                'title' => substr((string) $resultData['title'], 0, 255),
                'created_at' => now(), 'updated_at' => now(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'html' => view('partials.result', ['result' => $resultData])->render(),
                ]);
            }

            return redirect()->route('home')->with('result', $resultData);
        }
    } catch (\Exception $e) {
        // Log or handle exception
    }

    DB::table('download_logs')->insert([
        'platform' => $platform['name'] ?? ucfirst(explode('.', $cleanHost)[0] ?: 'Unknown'),
        'format' => '—', 'quality' => '—', 'ip_address' => $request->ip(),
        'type' => 'extraction', 'status' => false, 'title' => null,
        'created_at' => now(), 'updated_at' => now(),
    ]);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to retrieve video data. It might be private or unsupported.'
        ]);
    }

    return redirect()->route('home')->withErrors(['video_url' => 'Failed to retrieve video data. It might be private or unsupported.']);
})->name('analyze');

Route::get('/prepare-plugin-download', function (Request $request) {
    $token = (string) $request->query('token', '');
    $cached = Cache::get('plugin_prepare:' . $token);
    abort_unless(is_array($cached) && !empty($cached['request']), 410);

    $response = Http::retry(2, 500)->timeout(300)->get(
        'https://plugin.vidssave.com/api/sse',
        ['request' => $cached['request']]
    );
    abort_unless($response->successful(), 502);

    preg_match('/event:\s*success\s*[\r\n]+data:\s*(\{[^\r\n]+\})/', $response->body(), $matches);
    $event = isset($matches[1]) ? json_decode($matches[1], true) : null;
    $downloadLink = $event['download_link'] ?? null;
    abort_unless($downloadLink && filter_var($downloadLink, FILTER_VALIDATE_URL), 502);

    Cache::forget('plugin_prepare:' . $token);
    $encodedSource = rtrim(strtr(base64_encode($downloadLink), '+/', '-_'), '=');
    $filename = basename((string) $request->query('name', 'video.mp4'));

    return response()->json([
        'download_url' => URL::temporarySignedRoute('media.download', now()->addMinutes(20), [
            'source' => $encodedSource,
            'name' => $filename,
        ]),
    ]);
})->name('plugin.prepare')->middleware('signed');

Route::get('/download-file', function (Request $request) {
    $encodedSource = (string) $request->query('source', '');
    $padding = (4 - (strlen($encodedSource) % 4)) % 4;
    $source = base64_decode(strtr($encodedSource, '-_', '+/') . str_repeat('=', $padding), true);

    if (!$source || !filter_var($source, FILTER_VALIDATE_URL)) {
        return response('', 204);
    }
    if (strtolower((string) parse_url($source, PHP_URL_SCHEME)) !== 'https') {
        return response('', 204);
    }

    $host = (string) parse_url($source, PHP_URL_HOST);
    $resolvedIp = gethostbyname($host);
    $isPublicIp = filter_var(
        $resolvedIp,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    );
    if (!$host || $resolvedIp === $host || !$isPublicIp) {
        return response('', 204);
    }

    $filename = basename((string) $request->query('name', 'video.mp4'));
    $filename = preg_replace('/[^A-Za-z0-9._-]+/', '-', $filename) ?: 'video.mp4';

    $client = new \GuzzleHttp\Client([
        'connect_timeout' => 15,
        'timeout' => 0,
        'http_errors' => false,
        'allow_redirects' => [
            'max' => 3,
            'strict' => true,
        ],
    ]);
    // Many CDNs (TikTok, Instagram, Facebook) return an HTML challenge/error page
    // instead of the media bytes unless a matching Referer is sent.
    $referer = PlatformDetector::detect($source)['referer'] ?? null;
    $requestHeaders = [
        'Accept' => '*/*',
        'User-Agent' => $request->userAgent() ?: 'Mozilla/5.0',
    ];
    if ($referer) {
        $requestHeaders['Referer'] = $referer;
    }

    // Some upstreams (e.g. vidssave's download_redirect → short-lived /tmp/recycle files)
    // intermittently return an HTML error page with a 200 status when the temporary file
    // has already expired or the token was rate-limited. Streaming that as "video.mp4" is
    // exactly the "downloads as HTML" bug. Re-requesting the source regenerates a fresh
    // temp file, so retry a couple of times before giving up.
    $body = null;
    $firstChunk = '';
    $upstreamType = '';
    $looksHtml = false;
    $maxAttempts = 3;

    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        try {
            $upstream = $client->request('GET', $source, [
                'stream' => true,
                'headers' => $requestHeaders,
            ]);
        } catch (\Throwable $exception) {
            if ($attempt < $maxAttempts) { usleep(500000); continue; }
            return response('', 204);
        }

        if ($upstream->getStatusCode() < 200 || $upstream->getStatusCode() >= 300) {
            if ($attempt < $maxAttempts) { usleep(500000); continue; }
            DB::table('download_logs')->insert([
                'platform' => 'Direct link', 'format' => strtoupper(pathinfo($filename, PATHINFO_EXTENSION) ?: 'MP4'),
                'quality' => '—', 'ip_address' => $request->ip(), 'type' => 'download',
                'status' => false, 'title' => $filename, 'created_at' => now(), 'updated_at' => now(),
            ]);
            return response('', 204);
        }

        $upstreamType = strtolower($upstream->getHeaderLine('Content-Type'));
        $body = $upstream->getBody();

        // Peek the first bytes to detect an HTML error/challenge page served with a 200.
        $firstChunk = $body->read(1024 * 512);
        $looksHtml = strpos($upstreamType, 'text/html') !== false
            || preg_match('/^\xEF\xBB\xBF?\s*(<!doctype html|<html|<\?xml|<head)/i', $firstChunk);

        if ($looksHtml && $attempt < $maxAttempts) {
            usleep(500000);
            continue;
        }
        break;
    }

    if ($looksHtml) {
        DB::table('download_logs')->insert([
            'platform' => 'Direct link', 'format' => strtoupper(pathinfo($filename, PATHINFO_EXTENSION) ?: 'MP4'),
            'quality' => '—', 'ip_address' => $request->ip(), 'type' => 'download',
            'status' => false, 'title' => $filename, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return response('This media link has expired or is no longer available. Please analyze the link again.', 502)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    DB::table('download_logs')->insert([
        'platform' => 'Direct link', 'format' => strtoupper(pathinfo($filename, PATHINFO_EXTENSION) ?: 'MP4'),
        'quality' => '—', 'ip_address' => $request->ip(), 'type' => 'download',
        'status' => true, 'title' => $filename, 'created_at' => now(), 'updated_at' => now(),
    ]);

    $headers = [
        'Content-Type' => $upstream->getHeaderLine('Content-Type') ?: 'application/octet-stream',
        'Cache-Control' => 'private, no-store',
    ];
    if ($upstream->hasHeader('Content-Length')) {
        $headers['Content-Length'] = $upstream->getHeaderLine('Content-Length');
    }

    return response()->streamDownload(function () use ($body, $firstChunk) {
        // Emit the already-consumed peek buffer first, then stream the remainder.
        echo $firstChunk;
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
        while (!$body->eof()) {
            echo $body->read(1024 * 1024);
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        }
    }, $filename, $headers);
})->name('media.download')->middleware('signed');

// ── Sitemap ───────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', function () {
    $platforms = \App\Models\Platform::where('status', 'active')->get();
    $blogs = \App\Models\Blog::where('status', 1)->get();
    $legacyBlogs = \App\Models\BlogPost::published()
        ->whereNotIn('slug', $blogs->pluck('slug'))
        ->get();
    $guides = \App\Models\Guide::where('status', 1)->get();
    $staticUrls = [
        ['loc' => route('home'), 'lastmod' => null, 'changefreq' => 'daily', 'priority' => '1.0'],
        ['loc' => route('platforms'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['loc' => route('blog'), 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['loc' => route('public.faqs'), 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.6'],
        ['loc' => route('privacy'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.3'],
        ['loc' => route('terms'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.3'],
        ['loc' => route('disclaimer'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.3'],
        ['loc' => route('about'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.4'],
        ['loc' => route('contact'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.4'],
        ['loc' => route('dmca'), 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.3'],
    ];

    return response()->view('sitemap', [
        'staticUrls' => $staticUrls,
        'platforms' => $platforms,
        'blogs' => $blogs,
        'legacyBlogs' => $legacyBlogs,
        'guides' => $guides,
    ])->header('Content-Type', 'application/xml');
});

Route::get('/llms.txt', function () {
    $lines = [
        '# Solution Hub',
        '',
        '> Solution Hub is a browser-based utility for analyzing supported public media links and displaying formats made available by the source.',
        '',
        'Users should save or reuse only content they own or have permission to save. Solution Hub does not provide access to private, paid, login-only, or restricted media.',
        '',
        'Available formats and quality depend on the public source response. The service is independent and is not affiliated with supported third-party platforms.',
        '',
        'Content is provided in English. The language selector is a browser translation aid and does not represent separate canonical translated URLs. Public availability is not permission to copy, republish, or redistribute third-party media.',
        '',
        '## Main Pages',
        '',
        '- [Homepage](https://solutionhub.digital/): Public-link media analyzer, supported sources, features, FAQs, and guides.',
        '- [Supported Platforms](https://solutionhub.digital/supported-platforms): Directory of supported public media sources.',
        '- [Blog and Guides](https://solutionhub.digital/blog): Guides about media formats, quality, compatibility, and responsible use.',
        '- [Frequently Asked Questions](https://solutionhub.digital/faqs): Answers about supported links, devices, privacy, and troubleshooting.',
        '',
        '## Platform Tools',
        '',
        '- [YouTube Public Link Guide](https://solutionhub.digital/youtube-video-downloader): Information about supported public YouTube videos and Shorts.',
        '- [Facebook Public Link Guide](https://solutionhub.digital/facebook-video-downloader): Information about public Facebook videos and Reels.',
        '- [Instagram Public Link Guide](https://solutionhub.digital/instagram-video-downloader): Information about supported public Instagram Reels and video posts.',
        '- [TikTok Public Link Guide](https://solutionhub.digital/tiktok-video-downloader): Information about supported public TikTok videos and share links.',
        '- [Twitter/X Public Link Guide](https://solutionhub.digital/twitter-video-downloader): Information about supported public X and Twitter status videos.',
        '- [Vimeo Public Link Guide](https://solutionhub.digital/vimeo-video-downloader): Information about supported public Vimeo videos and creator-authorized formats.',
        '- [Dailymotion Public Link Guide](https://solutionhub.digital/dailymotion-video-downloader): Information about supported public Dailymotion video URLs.',
        '- [Pinterest Public Link Guide](https://solutionhub.digital/pinterest-video-downloader): Information about supported public Pinterest video Pins.',
        '',
        '## Trust and Legal',
        '',
        '- [About](https://solutionhub.digital/about): Purpose, responsible-use principles, and website standards.',
        '- [Contact](https://solutionhub.digital/contact): Verified support contact information and enquiry guidance.',
        '- [Privacy Policy](https://solutionhub.digital/privacy): Information about submitted URLs, data handling, and privacy.',
        '- [Terms of Service](https://solutionhub.digital/terms): Conditions for responsible use of the service.',
        '- [DMCA and Copyright Policy](https://solutionhub.digital/dmca): Requirements for copyright notices and rights-holder requests.',
        '- [Disclaimer](https://solutionhub.digital/disclaimer): Service scope, third-party independence, and limitations.',
        '',
        '## Discovery',
        '',
        '- [XML Sitemap](https://solutionhub.digital/sitemap.xml): Machine-readable list of indexable site URLs.',
        '- [Robots Instructions](https://solutionhub.digital/robots.txt): Crawler access rules and sitemap location.',
        '',
    ];

    return response(implode("\n", $lines), 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('llms');

// ── Catch-all Public Platform Route (Must be last) ────────────────────────────
Route::get('/{slug}', [PlatformController::class, 'show'])->name('platforms.show');
