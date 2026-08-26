<!doctype html>
<html lang="en">
<head>
    @include('partials.google-tag')
    <style>iframe.goog-te-banner-frame{display:none!important}body{top:0!important;position:static!important}.goog-te-banner-frame{display:none!important}.skiptranslate{display:none!important}#google_translate_element{display:none!important}.goog-tooltip{display:none!important}.goog-tooltip:hover{display:none!important}.goog-text-highlight{background:none!important;box-shadow:none!important}</style>
    @php
        $siteName = $siteSettings['site_name'] ?? 'Solution Hub';
        $staticTitles = [
            'platforms' => 'Supported Video Platforms | Solution Hub',
            'blog' => 'Video Download Guides & Tips | Solution Hub',
            'privacy' => 'Privacy Policy | Solution Hub',
            'terms' => 'Terms of Service | Solution Hub',
            'disclaimer' => 'Disclaimer | Solution Hub',
            'about' => 'About Solution Hub | Our Purpose & Standards',
            'contact' => 'Contact Solution Hub | Help & Website Feedback',
            'dmca' => 'DMCA & Copyright Policy | Solution Hub',
        ];
        $staticDescriptions = [
            'platforms' => 'Explore the public video platforms supported by Solution Hub and open a dedicated downloader page.',
            'blog' => 'Read practical guides about public video links, formats, quality, compatibility, and responsible downloading.',
            'privacy' => 'Learn how Solution Hub handles submitted links, temporary processing, privacy, and data protection.',
            'terms' => 'Review the terms governing responsible use of Solution Hub and its public-link analysis tools.',
            'disclaimer' => 'Read important legal information about Solution Hub, third-party platforms, copyright, and responsible use.',
            'about' => 'Learn about Solution Hub, our public-link tools, responsible-use standards, and commitment to user privacy.',
            'contact' => 'Find help for technical issues, privacy questions, website feedback, and copyright concerns related to Solution Hub.',
            'dmca' => 'Read the Solution Hub copyright policy and learn how rights holders can submit a complete removal notice.',
        ];
        $pageTitle = $page === 'blog-post'
            ? ($post['meta_title'] ?? (($post['title'] ?? 'Guide') . ' | ' . $siteName))
            : ($page === 'home'
                ? (!empty($homeSeo->meta_title) ? $homeSeo->meta_title : 'Solution Hub - Public Media Link Analyzer')
                : ($staticTitles[$page] ?? ucwords(str_replace('-', ' ', $page)) . ' | Solution Hub'));
        $pageDescription = $page === 'blog-post'
            ? ($post['description'] ?? $post['excerpt'] ?? '')
            : ($page === 'home'
                ? (!empty($homeSeo->meta_description) ? $homeSeo->meta_description : ($siteSettings['default_meta_description'] ?? 'Solution Hub is a public media link analyzer.'))
                : ($staticDescriptions[$page] ?? ($siteSettings['default_meta_description'] ?? 'Analyze supported public video links and review available media formats.')));
        $pageUrl = $page === 'blog-post' ? route('blog.show', $post['slug']) : url()->current();
        $pageImage = $page === 'blog-post' ? asset($post['image']) : asset('/images/blog/generated/001-youtube-video-downloader-safe-hd-mp4-guide.svg');
    @endphp
    <meta charset="utf-8">
    @include('partials.favicons')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $page === 'home' && !empty($homeSeo->meta_robots) ? $homeSeo->meta_robots : 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1' }}">
    <title>{{ $pageTitle }}</title>
    <link rel="canonical" href="{{ $pageUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $page === 'blog-post' ? 'article' : 'website' }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">
    @if($page === 'contact')
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'Contact Solution Hub',
            'url' => route('contact'),
            'mainEntity' => [
                '@type' => 'Organization',
                '@id' => 'https://solutionhub.digital/#organization',
                'name' => 'Solution Hub',
                'email' => 'support@solutionhub.digital',
                'telephone' => '+44 7308 208926',
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer support',
                    'email' => 'support@solutionhub.digital',
                    'telephone' => '+44 7308 208926',
                    'availableLanguage' => ['English', 'Urdu'],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Contact',
                    'item' => route('contact'),
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @if($page === 'home')
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "@id": "https://solutionhub.digital/#organization",
          "name": "Solution Hub",
          "alternateName": [
            "Solution Hub",
            "HD Video DL",
            "HDVDownloader"
          ],
          "url": "https://solutionhub.digital/",
          "logo": {
            "@type": "ImageObject",
            "url": "https://solutionhub.digital/images/logo-hafiz.svg"
          },
          "description": "Solution Hub is a browser-based utility that analyzes supported public media links and displays formats made available by the source."
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Solution Hub",
          "alternateName": [
            "Solution Hub",
            "HD Video DL",
            "HDVDownloader"
          ],
          "url": "https://solutionhub.digital/",
          "description": "Solution Hub is a browser-based utility that analyzes supported public media links and displays formats made available by the source.",
          "publisher": {
            "@id": "https://solutionhub.digital/#organization"
          }
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "https://solutionhub.digital/"
            }
          ]
        }
        </script>
        @php
            $homeFaqSchemaList = [];
            if(isset($faqs) && $faqs->count() > 0) {
                foreach($faqs as $faq) {
                    $homeFaqSchemaList[] = [
                        '@type' => 'Question',
                        'name' => strip_tags($faq->question),
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => strip_tags($faq->answer),
                        ]
                    ];
                }
            } else {
                $homeFaqSchemaList = [
                    [
                        '@type' => 'Question',
                        'name' => 'Do I need to install an app?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'No. The analyzer runs in your browser on mobile and desktop devices.'
                        ]
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Which video qualities are available?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Available formats depend on the source and may include SD, 720p, 1080p, and audio-only options.'
                        ]
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Is Solution Hub free?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Yes, the basic public-link analysis experience runs in the browser.'
                        ]
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Can I use any video?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Only use content you own or have permission to save, and follow the source platform\'s terms.'
                        ]
                    ]
                ];
            }
        @endphp
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $homeFaqSchemaList
        ], JSON_UNESCAPED_SLASHES) !!}</script>
    @elseif($page === 'blog')
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "@id": "https://solutionhub.digital/#organization",
          "name": "Solution Hub",
          "alternateName": [
            "Solution Hub",
            "HD Video DL",
            "HDVDownloader"
          ],
          "url": "https://solutionhub.digital/",
          "logo": {
            "@type": "ImageObject",
            "url": "https://solutionhub.digital/images/logo-hafiz.svg"
          },
          "description": "Solution Hub is a browser-based public media link analyzer for reviewing source-dependent formats from supported public links."
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Solution Hub",
          "alternateName": [
            "Solution Hub",
            "HD Video DL",
            "HDVDownloader"
          ],
          "url": "https://solutionhub.digital/",
          "description": "Solution Hub is a browser-based public media link analyzer for reviewing source-dependent formats from supported public links.",
          "publisher": {
            "@id": "https://solutionhub.digital/#organization"
          }
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "https://solutionhub.digital/"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Blog",
              "item": "https://solutionhub.digital/blog"
            }
          ]
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Blog",
          "@id": "https://solutionhub.digital/blog#blog",
          "name": "Solution Hub Blog",
          "url": "https://solutionhub.digital/blog",
          "description": "Browse all video download guides, how-to tutorials, and tips for downloading videos from YouTube, TikTok, Facebook, Instagram, and more.",
          "inLanguage": "en",
          "publisher": {
            "@type": "Organization",
            "name": "Solution Hub",
            "@id": "https://solutionhub.digital/#organization",
            "logo": {
              "@type": "ImageObject",
              "url": "https://solutionhub.digital/images/logo-hafiz.svg"
            }
          }
        }
        </script>
    @elseif($page === 'blog-post')
        <meta property="article:published_time" content="{{ date('c', strtotime($post['published'])) }}">
        <meta property="article:modified_time" content="{{ date('c', strtotime($post['published'])) }}">
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Article',
                    'headline' => $post['title'],
                    'description' => $post['description'],
                    'image' => asset($post['image']),
                    'datePublished' => date('Y-m-d', strtotime($post['published'])),
                    'dateModified' => date('Y-m-d', strtotime($post['published'])),
                    'author' => ['@type' => 'Organization', 'name' => 'Solution Hub', 'url' => url('/')],
                    'publisher' => ['@type' => 'Organization', 'name' => 'Solution Hub', 'url' => url('/')],
                    'mainEntityOfPage' => route('blog.show', $post['slug']),
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $post['title'], 'item' => route('blog.show', $post['slug'])],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --ink:#101828; --muted:#667085; --line:#e4e7ec; --soft:#f6f8fb; --paper:#fff; --brand:#fa3f3f; --brand-dark:#c91f31; --teal:#7c3aed; --blue:#2563eb; --gold:#f4b740; --shadow:0 24px 80px rgba(16,24,40,.12); }
        * { box-sizing: border-box; }
        body { margin:0; color:var(--ink); background:#fbfcfe; font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
        h1 { font-size: 3rem !important; }
        h2 { font-size: 2rem !important; }
        h3 { font-size: 1.5rem !important; }
        p { font-size: 1.2rem !important; }
        a { color:inherit; text-decoration:none; }
        .menu-toggle { display:none; }
        .wrap { width:min(1160px, calc(100% - 36px)); margin:0 auto; }
        .topbar { position:sticky; top:0; z-index:20; border-bottom:1px solid rgba(228,231,236,.8); background:rgba(255,255,255,.9); backdrop-filter:blur(16px); }
        .nav { display:flex; align-items:center; justify-content:space-between; min-height:76px; gap:24px; }
        .brand { display:inline-flex; align-items:center; gap:11px; font-weight:800; }
        .brand-mark { width:38px; height:38px; border-radius:8px; display:grid; place-items:center; color:white; background:linear-gradient(135deg,var(--brand),var(--blue)); box-shadow:0 10px 30px rgba(250,63,63,.28); font-size:15px; }
        .nav-links { display:flex; align-items:center; gap:8px; flex-wrap:wrap; justify-content:flex-end; }
        .nav-links a { padding:10px 13px; border-radius:8px; color:#475467; font-size:14px; font-weight:700; }
        .nav-links a.active, .nav-links a:hover { color:var(--ink); background:#f2f4f7; }
        .hero { overflow:hidden; padding:64px 0 38px; background:radial-gradient(circle at 14% 20%,rgba(139, 92, 246,.12),transparent 28%), radial-gradient(circle at 84% 12%,rgba(37,99,235,.12),transparent 24%), linear-gradient(180deg,#fff 0%,#f7f9fc 100%); }
        .hero-grid { display:grid; grid-template-columns:minmax(0,1.08fr) minmax(340px,.92fr); gap:44px; align-items:center; }
        .eyebrow { display:inline-flex; align-items:center; gap:9px; color:#344054; font-size:13px; font-weight:800; padding:8px 12px; border:1px solid var(--line); border-radius:999px; background:white; }
        .pulse { width:9px; height:9px; border-radius:50%; background:var(--teal); box-shadow:0 0 0 6px rgba(139, 92, 246,.12); }
        h1 { margin:20px 0 14px; font-size:clamp(40px,6vw,76px); line-height:.96; letter-spacing:0; }
        .hero-copy { max-width:660px; color:var(--muted); font-size:18px; line-height:1.7; margin:0 0 24px; }
        .download-panel { background:var(--paper); border:1px solid var(--line); border-radius:8px; box-shadow:var(--shadow); padding:14px; }
        .url-form { display:grid; grid-template-columns:1fr auto; gap:10px; }
        .url-form input { min-width:0; width:100%; border:1px solid #d0d5dd; border-radius:8px; padding:17px 16px; font-size:16px; outline:none; }
        .url-form input:focus { border-color:var(--blue); box-shadow:0 0 0 4px rgba(37,99,235,.12); }
        .button { border:0; border-radius:8px; padding:0 22px; min-height:56px; color:white; background:var(--ink); font-weight:800; cursor:pointer; box-shadow:0 14px 30px rgba(16,24,40,.18); }
        .error { margin:10px 2px 0; color:var(--brand-dark); font-size:14px; font-weight:700; }
        .result { margin-top:14px; border:1px solid #d9e0f1; border-radius:8px; padding:16px; background:#f1f4fb; }
        .result-head { display:flex; justify-content:space-between; gap:14px; align-items:center; flex-wrap:wrap; }
        .result strong { display:block; margin-bottom:4px; }
        .chips { display:flex; gap:8px; flex-wrap:wrap; margin-top:14px; }
        .chip { border:1px solid #b9c6e6; background:white; border-radius:999px; padding:8px 11px; font-size:13px; font-weight:800; color:#082976; }
        .note { color:var(--muted); font-size:13px; line-height:1.6; margin:12px 2px 0; }
        .mockup { min-height:470px; border-radius:8px; border:1px solid rgba(255,255,255,.48); background:linear-gradient(135deg,#172033,#29344d 42%,#7c3aed); box-shadow:var(--shadow); padding:18px; color:white; }
        .mock-browser { border-radius:8px; overflow:hidden; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.16); }
        .mock-top { display:flex; gap:7px; padding:13px; background:rgba(255,255,255,.12); }
        .dot { width:10px; height:10px; border-radius:50%; background:#ff6b6b; }
        .dot:nth-child(2) { background:var(--gold); } .dot:nth-child(3) { background:#2659d0; }
        .video-art { padding:22px; }
        .video-frame { aspect-ratio:16/10; border-radius:8px; background:linear-gradient(135deg,rgba(250,63,63,.85),rgba(37,99,235,.86)), url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='900' height='560' viewBox='0 0 900 560'%3E%3Cpath d='M0 0h900v560H0z' fill='%2320273d'/%3E%3Cpath d='M80 380c110-90 190-70 285-20s210 90 455-72v272H80z' fill='%233b5bdb' opacity='.42'/%3E%3Ccircle cx='688' cy='126' r='82' fill='%23ffcc55' opacity='.75'/%3E%3C/svg%3E"); background-size:cover; display:grid; place-items:center; box-shadow:inset 0 0 0 1px rgba(255,255,255,.22); }
        .play { width:72px; height:72px; border-radius:50%; display:grid; place-items:center; background:rgba(255,255,255,.94); color:var(--brand); font-size:26px; }
        .quality-row { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:16px; }
        .quality { background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); border-radius:8px; padding:13px; font-weight:800; }
        .quality span { display:block; margin-top:4px; color:rgba(255,255,255,.7); font-size:12px; }
        section { padding:56px 0; }
        .section-head { display:flex; justify-content:space-between; gap:24px; align-items:end; margin-bottom:24px; }
        .section-head h2 { margin:0; font-size:clamp(28px,4vw,44px); }
        .section-head p { margin:0; max-width:560px; color:var(--muted); line-height:1.7; }
        .grid { display:grid; gap:16px; }
        .platform-grid { grid-template-columns:repeat(4,minmax(0,1fr)); }
        .platform-card, .post-card, .feature-card { background:white; border:1px solid var(--line); border-radius:8px; padding:20px; }
        .platform-icon { width:44px; height:44px; border-radius:8px; display:grid; place-items:center; color:white; font-weight:900; margin-bottom:15px; }
        .platform-card { position:relative; min-height:210px; display:flex; flex-direction:column; justify-content:space-between; padding:22px !important; overflow:hidden; border-radius:16px !important; background:linear-gradient(145deg,#151c24,#11171e) !important; }
        .platform-card::before { content:''; position:absolute; inset:0 auto auto 0; width:100%; height:3px; background:var(--platform-accent); opacity:.9; }
        .platform-card::after { content:''; position:absolute; width:150px; height:150px; top:-85px; left:-70px; border-radius:50%; background:var(--platform-accent); opacity:.08; filter:blur(12px); pointer-events:none; }
        .platform-card-top { position:relative; z-index:1; display:flex; align-items:center; justify-content:center; }
        .platform-card .platform-icon { width:48px; height:48px; margin:0; border-radius:14px; background:var(--platform-accent); box-shadow:0 10px 26px color-mix(in srgb,var(--platform-accent) 30%,transparent); }
        .platform-card .platform-icon img { width:22px; height:22px; object-fit:contain; }
        .platform-arrow { position:absolute; top:0; right:0; width:32px; height:32px; display:grid; place-items:center; color:#7f8a9b; border:1px solid #2c3744; border-radius:50%; font-size:16px; transition:.2s ease; }
        .platform-card-copy { position:relative; z-index:1; }
        .platform-card h3 { margin:0 0 8px; color:#f8fafc; font-size:20px; letter-spacing:-.3px; text-align:center; }
        .platform-card p { width:100%; max-width:240px; max-height:3.1em; margin:0 auto; color:#8f9aaa; font-size:13px; line-height:1.55; display:-webkit-box !important; -webkit-box-orient:vertical; -webkit-line-clamp:2; line-clamp:2; overflow:hidden; text-overflow:ellipsis; white-space:normal; }
        .platform-card:hover .platform-arrow { color:#070a11; background:var(--platform-accent); border-color:var(--platform-accent); transform:translate(2px,-2px); }
        .platform-card h3, .post-card h3, .feature-card h3 { margin:0 0 8px; font-size:18px; }
        .platform-card p, .post-card p, .feature-card p { margin:0; color:var(--muted); line-height:1.6; font-size:14px; }
        .features, .blog-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
        .feature-card b { color:var(--teal); }
        .read { display:inline-flex; margin-top:18px; font-size:13px; font-weight:800; color:var(--blue); }
        .page-hero { padding:54px 0 22px; background:#f7f9fc; border-bottom:1px solid var(--line); }
        .page-hero h1 { font-size:clamp(36px,5vw,58px); margin-bottom:12px; }
        .content-band { background:white; }
        .privacy-box { max-width:820px; background:white; border:1px solid var(--line); border-radius:8px; padding:28px; color:var(--muted); line-height:1.8; }
        footer { padding:34px 0; border-top:1px solid var(--line); color:var(--muted); background:white; }
        .footer-row { display:flex; justify-content:space-between; gap:20px; flex-wrap:wrap; }
        @media (max-width:860px) { .hero-grid, .features, .blog-grid, .platform-grid { grid-template-columns:1fr; } .hero{padding-top:34px;} .mockup{min-height:auto;} .section-head{display:block;} .section-head p{margin-top:10px;} }
        @media (max-width:620px) { .wrap{width:min(100% - 24px,1160px);} .nav{align-items:flex-start; flex-direction:column; padding:14px 0;} .nav-links{justify-content:flex-start;} .url-form{grid-template-columns:1fr;} .button{width:100%;} .quality-row{grid-template-columns:1fr;} }
    </style>
    <style>
        :root { --ink:#172033; --muted:#677185; --line:#e8ebf0; --soft:#f7f8fa; --brand:#ff3d57; --brand-dark:#d82640; --teal:#7c3aed; --blue:#5267df; --shadow:0 18px 48px rgba(24,32,51,.09); }
        body { background:#fff; }
        .topbar { position:sticky; top:0; z-index:30; background:rgba(255,255,255,.86); border-bottom:1px solid rgba(218,225,232,.78); backdrop-filter:blur(18px); box-shadow:0 8px 30px rgba(27,39,58,.05); }
        .nav { min-height:76px; }
        .brand { font-size:20px; }
        .brand-mark { width:40px; height:40px; border-radius:12px; background:linear-gradient(145deg,#ff5266,#f32649); box-shadow:0 10px 24px rgba(243,38,73,.23); }
        .nav-links a { font-weight:600; }
        .nav-links a { position:relative; }
        .nav-links a.active, .nav-links a:hover { color:var(--brand); background:transparent; }
        .nav-links a.active::after { content:''; position:absolute; left:13px; right:13px; bottom:3px; height:2px; border-radius:2px; background:var(--brand); }
        .hero {
            position:relative;
            padding:78px 0 48px;
            text-align:center;
            background:
                linear-gradient(rgba(255,255,255,.3),rgba(255,255,255,.3)),
                linear-gradient(135deg,#eaeffb 0%,#eaf4ff 48%,#fff0ee 100%);
            border-bottom:1px solid rgba(210, 220, 228, .72);
        }
        .hero-center { max-width:930px; margin:0 auto; }
        .hero .eyebrow { padding:8px 13px; border:1px solid rgba(255,61,87,.18); background:rgba(255,255,255,.66); color:var(--brand); text-transform:uppercase; letter-spacing:.08em; box-shadow:0 8px 25px rgba(35,62,83,.05); }
        .hero .eyebrow::before { content:''; width:7px; height:7px; border-radius:50%; background:var(--teal); box-shadow:0 0 0 5px rgba(124, 58, 237,.12); }
        .hero h1 { margin:16px auto 12px; max-width:850px; font-size:clamp(40px,6vw,66px); line-height:1.08; }
        .hero h1 span { color:#082b7d; }
        .hero-copy { max-width:690px; margin:0 auto 30px; font-size:17px; }
        .download-panel { max-width:900px; margin:0 auto; padding:10px; border:1px solid rgba(255,255,255,.96); border-radius:18px; background:rgba(255,255,255,.98); backdrop-filter:none; box-shadow:0 15px 40px rgba(35,62,83,.12), inset 0 1px 0 #fff; transform:translateZ(0); will-change:transform; }
        .download-panel.has-result { max-width:1120px; }
        .url-form { gap:8px; }
        .url-form input { min-height:62px; padding:18px 20px; border:1px solid transparent; border-radius:12px; background:#f3f4f8; }
        .url-form input:hover { background:#eef2f5; }
        .url-form input:focus { background:#fff; }
        .button { min-width:165px; min-height:62px; border-radius:12px; background:linear-gradient(135deg,#7c3aed,#0d3ca9); box-shadow:0 12px 28px rgba(124, 58, 237,.24); font-size:16px; transition:transform .2s ease,box-shadow .2s ease; }
        .button:hover { background:linear-gradient(135deg,#1442ad,#07308f); transform:translateY(-1px); box-shadow:0 16px 32px rgba(124, 58, 237,.3); }
        .hero .note { margin-top:15px; }
        .platform-strip { display:flex; justify-content:center; align-items:center; flex-wrap:wrap; gap:12px; margin:34px auto 0; }
        .platform-pill { display:flex; align-items:center; gap:8px; padding:9px 14px; border:1px solid rgba(223,228,235,.9); border-radius:999px; background:rgba(255,255,255,.82); color:#4a5568; font-size:13px; font-weight:700; box-shadow:0 8px 22px rgba(31,45,65,.06); transition:transform .2s ease,box-shadow .2s ease; }
        .platform-pill:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(31,45,65,.1); }
        .platform-dot { width:28px; height:28px; display:grid; place-items:center; flex:0 0 28px; border-radius:50%; }
        .platform-dot img { width:15px; height:15px; display:block; object-fit:contain; }
        .result { text-align:left; box-shadow:none; }
        .result { padding:0; overflow:hidden; border:1px solid #dcdfe5; border-radius:14px; background:#fff; }
        .result-layout { display:grid; grid-template-columns:minmax(230px, .72fr) minmax(0, 1.55fr); }
        .media-summary { padding:24px; border-right:1px solid var(--line); background:#fff; min-width:0; }
        .media-thumb { width:100%; aspect-ratio:16/9; display:block; object-fit:cover; border-radius:10px; background:#edf0f3; }
        .media-platform { margin:18px 0 8px; color:var(--teal); font-size:12px; font-weight:800; text-transform:uppercase; }
        .media-title {
            margin:0;
            color:#fff;
            font-size:18px !important;
            line-height:1.45;
            display:-webkit-box;
            -webkit-box-orient:vertical;
            -webkit-line-clamp:2;
            overflow:hidden;
            text-overflow:ellipsis;
            overflow-wrap:anywhere;
            word-break:break-word;
        }
        .media-duration { display:inline-flex; align-items:center; gap:7px; margin-top:14px; padding:8px 11px; border-radius:8px; background:#dee6f8; color:#082a78; font-size:14px; font-weight:800; }
        .format-section + .format-section { border-top:1px solid var(--line); }
        .format-heading { display:flex; align-items:center; gap:10px; margin:0; padding:20px 24px; border-bottom:1px solid var(--line); font-size:21px; }
        .format-heading-mark { width:32px; height:32px; display:grid; place-items:center; border-radius:8px; color:#fff; background:var(--teal); font-size:13px; }
        .format-row { display:grid; grid-template-columns:90px minmax(90px, 1fr) minmax(90px, .8fr) auto; align-items:center; gap:12px; min-height:82px; padding:14px 24px; border-bottom:1px solid var(--line); }
        .format-row:last-child { border-bottom:0; }
        .format-badge { justify-self:start; min-width:58px; padding:8px 9px; border-radius:8px; background:#ffaf2f; color:#fff; text-align:center; font-weight:800; }
        .format-quality, .format-size { font-weight:800; color:#303746; }
        .format-size { color:#5e687b; }
        .download-link { position:relative; display:inline-flex; align-items:center; justify-content:center; gap:8px; min-width:142px; min-height:48px; padding:0 16px; border:2px solid #08ba54; border-radius:10px; color:#08a94d; background:#fff; font-weight:800; font:inherit; line-height:1; white-space:nowrap; flex-shrink:0; }
        .download-link:hover { color:#fff; background:#08ba54; }
        .download-link.is-loading { border-color:#cdd4df; color:#7b8494; background:#f4f6f8; cursor:wait; }
        .download-link svg, .download-link .download-icon { width:20px; height:20px; flex:0 0 20px; }
        .download-label { font:inherit; font-weight:inherit; line-height:1; display:inline-block; }
        .empty-formats { padding:24px; color:var(--muted); }
        .result-note { margin:0; padding:14px 24px; border-top:1px solid var(--line); background:#fafbfc; color:var(--muted); font-size:12px; }
        .trust-bar { border-top:1px solid var(--line); border-bottom:1px solid var(--line); background:#fff; }
        .trust-grid { display:grid; grid-template-columns:repeat(3,1fr); }
        .trust-item { padding:22px; text-align:center; border-right:1px solid var(--line); }
        .trust-item:last-child { border-right:0; }
        .trust-item strong { display:block; margin-bottom:5px; font-size:15px; }
        .trust-item span { color:var(--muted); font-size:13px; }
        section { padding:20px 0; }
        .section-head { display:block; text-align:center; max-width:720px; margin:0 auto 34px; padding:15px }
        .section-head h2 { font-size:clamp(30px,4vw,42px); }
        .section-head p { margin:12px auto 0; }
        .feature-card, .platform-card, .post-card { border-color:var(--line); border-radius:12px; box-shadow:none; }
        .feature-card { padding:28px; }
        .feature-icon { width:46px; height:46px; display:grid; place-items:center; margin-bottom:18px; border-radius:50%; background:#edf1f9; color:var(--teal); font-size:21px; font-weight:800; }
        .steps-section { background:#f7f8fa; }
        /* Steps layout — Screenshot 2 style: icon box + dashed connector */
        .steps-flow {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0;
            margin-top: 10px;
        }
        .step {
            flex: 1;
            position: relative;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 24px;
        }
        /* Dashed connector between steps */
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 36px;
            left: calc(50% + 52px);
            width: calc(100% - 104px);
            height: 0;
            border-top: 2px dashed var(--line);
            pointer-events: none;
        }
        /* Icon box — square with rounded corners */
        .step-icon-box {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: rgba(124, 58, 237,.08);
            border: 1.5px solid rgba(124, 58, 237,.22);
            color: var(--teal);
            margin-bottom: 24px;
            position: relative;
            z-index: 2;
            transition: background 0.25s ease, border-color 0.25s ease, transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
        }
        .step:hover .step-icon-box {
            background: rgba(124, 58, 237,.14);
            border-color: var(--teal);
            transform: translateY(-4px);
        }
        .step-icon-box svg {
            width: 26px;
            height: 26px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .step h3 { margin:0 0 10px; font-size:18px; font-weight:800; }
        .step p  { margin:0; color:var(--muted); line-height:1.65; font-size:14px; max-width:240px; }
        .faq { max-width:820px; margin:0 auto; padding: 40px; text-align: center; }
        .faq details { border-bottom:1px solid var(--line); }
        .faq summary { padding:20px 4px; cursor:pointer; font-weight:700; list-style:none; }
        .faq summary::-webkit-details-marker { display:none; }
        .faq summary::after { content:'+'; float:right; color:var(--teal); font-size:22px; }
        .faq details[open] summary::after { content:'−'; }
        .faq p { margin:0; padding:0 34px 20px 4px; color:var(--muted); line-height:1.7; }
        .content-band { background:#fff; }
        footer { background:#172033; color:#c8cfdb; border:0; }
        /* ===== Bento Grid ===== */
        .bento-grid     { display:grid; grid-template-columns:1.3fr 0.7fr; gap:20px; margin-top:32px; }
        .bento-grid-r2  { display:grid; grid-template-columns:0.7fr 1.3fr; gap:20px; margin-top:20px; }
        .bento-card {
            position:relative; overflow:hidden;
            background:#fff; border:1px solid var(--line); border-radius:20px;
            padding:32px; display:flex; flex-direction:column; justify-content:space-between;
            min-height:230px;
            transition:transform .25s cubic-bezier(.34,1.56,.64,1),border-color .25s,box-shadow .25s;
            box-shadow:var(--shadow);
        }
        .bento-card:hover { transform:translateY(-4px); box-shadow:0 20px 48px rgba(0,0,0,.09); }
        .bento-card h3 { margin:0 0 10px; font-size:22px; font-weight:800; color:var(--ink); letter-spacing:-.3px; }
        .bento-card p  { margin:0; font-size:14px; line-height:1.7; color:var(--muted); }
        .bento-icon-box {
            width:48px; height:48px; border-radius:13px; display:grid; place-items:center;
            background:rgba(124, 58, 237,.1); border:1px solid rgba(124, 58, 237,.18);
            color:var(--teal); margin-bottom:22px;
        }
        .bento-icon-box svg { width:22px; height:22px; stroke:currentColor; fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }
        /* Highlighted card */
        .bento-card.hl  { background:linear-gradient(135deg,#7c3aed,#0d3ca9); border-color:transparent; color:#fff; box-shadow:0 14px 36px rgba(124, 58, 237,.24); }
        .bento-card.hl:hover { box-shadow:0 20px 44px rgba(124, 58, 237,.34); }
        .bento-card.hl h3 { color:#fff; }
        .bento-card.hl p  { color:rgba(255,255,255,.85); }
        .bento-card.hl .bento-icon-box { background:rgba(255,255,255,.18); border-color:rgba(255,255,255,.22); color:#fff; }
        .bento-card.hl::after { content:'HD'; position:absolute; right:10px; bottom:-22px; font-size:110px; font-weight:900; color:rgba(255,255,255,.06); pointer-events:none; }
        .bento-chips { display:flex; gap:8px; margin-top:22px; flex-wrap:wrap; }
        .bento-chip  { padding:6px 14px; border-radius:99px; font-size:12px; font-weight:700; background:rgba(255,255,255,.18); color:#fff; }
        /* Circle watermark */
        .bento-circle { position:absolute; right:-50px; top:-50px; width:220px; height:220px; border-radius:50%; background:radial-gradient(circle,rgba(124, 58, 237,.06),transparent 70%); pointer-events:none; }
        /* Globe watermark */
        .bento-globe  { position:absolute; right:-20px; bottom:-20px; width:170px; height:170px; color:rgba(124, 58, 237,.06); pointer-events:none; }
        .bento-globe svg { width:100%; height:100%; stroke:currentColor; fill:none; stroke-width:1.2; stroke-linecap:round; stroke-linejoin:round; }
        /* Device icons row */
        .bento-devices { display:flex; gap:18px; margin-top:22px; }
        .bento-devices svg { width:22px; height:22px; stroke:var(--muted); fill:none; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
        @media (max-width:860px) { .bento-grid,.bento-grid-r2 { grid-template-columns:1fr; } .bento-grid-r2 { margin-top:0; } .bento-card { min-height:auto; } }
        /* ===== /Bento Grid ===== */
        @media (max-width:860px) { .trust-grid { grid-template-columns:1fr; } .steps-flow { flex-direction:column; align-items:center; gap:40px; } .step:not(:last-child)::after { display:none; } .trust-item { border-right:0; border-bottom:1px solid var(--line); } .trust-item:last-child { border-bottom:0; } .result-layout{grid-template-columns:1fr;} .media-summary{border-right:0; border-bottom:1px solid var(--line);} }
        @media (max-width:620px) {
            .hero{padding:48px 0 32px;}
            .nav{align-items:center; flex-direction:row;}
            .nav-links a:not(:first-child){display:none;}
            .brand{font-size:17px;}
            .download-panel{border-radius:12px;}
            .url-form{grid-template-columns:1fr;}
            .button{width:100%;}
            .platform-strip{gap:8px;}
            .platform-pill{padding:7px 10px;}
            .result-layout{grid-template-columns:1fr;}
            .media-summary{padding:18px 16px;}
            .media-title{
                font-size:20px !important;
                line-height:1.32;
                max-width:100%;
                display:-webkit-box;
                -webkit-box-orient:vertical;
                -webkit-line-clamp:2;
                overflow:hidden;
                text-overflow:ellipsis;
                overflow-wrap:anywhere;
                word-break:break-word;
            }
            .format-row{grid-template-columns:56px minmax(0,1fr) auto; gap:6px 8px; padding:12px 14px 14px; align-items:center;}
            .format-badge{grid-column:1; grid-row:1; align-self:center;}
            .format-quality{grid-column:2; grid-row:1; min-width:0; line-height:1.05; text-align:center; justify-self:center; font-size:15px; font-weight:800;}
            .format-size{grid-column:3; grid-row:1; min-width:max-content; white-space:nowrap; font-size:12px; line-height:1; overflow-wrap:normal; text-align:center; justify-self:center; color:#9aa6b4;}
            .download-link{grid-column:1 / -1; grid-row:2; min-width:0; width:100%; height:42px; padding:0 12px; font-size:0; white-space:nowrap; justify-self:stretch; justify-content:center; margin-top:6px; border-radius:10px;}
            .download-link svg, .download-link .download-icon{width:20px;height:20px;flex-basis:20px;}
            .format-heading{padding:17px 14px;}
        }

        /* SEO Content Section */
        .seo-content-section { padding:0px 0 24px; background:transparent; color:#a0aaba; border-top:1px solid rgba(255,255,255,0.05); }
        .seo-content-wrap { max-width:930px; margin:0 auto; padding:0 20px; text-align:left; }
        .seo-heading { font-size:clamp(26px, 4vw, 36px); font-weight:900; color:#fff; margin-bottom:24px; line-height:1.3; }
        .seo-text-container { position:relative; color:#a0aaba; line-height:1.85; font-size:16px; text-align:justify; }
        .seo-text-content p { margin-bottom:18px; }
        .seo-text-content p:last-child { margin-bottom:0; }
        .seo-text-content:not(.expanded) > *:nth-child(n+3) { display:none; }
        .seo-read-more { background:linear-gradient(135deg,#8b5cf6,#6d28d9); color:#040813; border:none; padding:10px 24px; border-radius:30px; font-weight:800; font-size:14px; cursor:pointer; margin-top:20px; transition:transform 0.2s, box-shadow 0.2s; box-shadow: 0 0px 10px rgba(139, 92, 246,0.25); display:none; }
        .seo-read-more.visible { display:inline-block; }
        .seo-read-more:hover { transform:translateY(-2px); box-shadow: 0 0px 10px rgba(139, 92, 246,0.35); }
        .home-faq-section { padding:32px 0 16px; }
        .home-faq-section .section-head { margin-bottom:20px !important; }
        .home-faq-section .section-head h2 { margin-bottom:8px; }
        .home-faq-section .section-head p { margin:0; }
        .home-faq-section .faq { max-width:880px; padding:0; }
        .home-faq-section .faq details { margin-bottom:10px; overflow:hidden; background:#121820; border:1px solid #293341; border-radius:12px; transition:border-color .2s ease, background .2s ease; }
        .home-faq-section .faq details:last-child { margin-bottom:0; }
        .home-faq-section .faq details:hover,
        .home-faq-section .faq details[open] { background:#151d26; border-color:rgba(139, 92, 246,.38); }
        .home-faq-section .faq summary { padding:16px 18px; color:#edf2f7; text-align:left; }
        .home-faq-section .faq summary::after { margin-left:18px; }
        .home-faq-section .faq details p { padding:0 52px 16px 18px; text-align:left; }
        @media (max-width:760px) {
            .seo-content-section { padding:32px 0 16px; }
            .home-faq-section { padding:24px 0 12px; }
            .home-faq-section .section-head { margin-bottom:16px !important; }
            .home-faq-section .faq { padding:0 12px; }
            .home-faq-section .faq summary { padding:14px 15px; font-size:14px; }
            .home-faq-section .faq details p { padding:0 40px 14px 15px; font-size:13px; }
        }
    </style>
    <style>
        :root {
            --ink:#f7f9fc;
            --muted:#98a2b3;
            --line:#293241;
            --soft:#151a22;
            --paper:#11161d;
            --brand:#ff4d67;
            --brand-dark:#ff6b7f;
            --teal:#8b5cf6;
            --blue:#60a5fa;
            --gold:#f7b84b;
            --shadow:0 26px 80px rgba(0,0,0,.42);
        }
        html { color-scheme:dark; background:#090c11; }
        body { color:var(--ink); background:#090c11; }
        .topbar {
            background:rgba(12,17,23,0.9);
            border-bottom:1px solid rgba(255,255,255,.05);
            box-shadow:0 12px 36px rgba(0,0,0,.3);
            backdrop-filter:blur(12px);
            padding:5px 0;
        }
        .brand-mark {
            background:linear-gradient(135deg,#8b5cf6,#6d28d9);
            box-shadow:0 8px 24px rgba(139, 92, 246,.25), inset 0 1px 0 rgba(255,255,255,.3);
            color:#040813;
            border-radius:10px;
        }
        .brand-mark svg { width:22px; height:22px; display:block; }
        .brand-name { color:#fff; font-weight:800; font-size:22px; letter-spacing:-0.5px; }
        .brand-name > span { color:#8b5cf6; font-weight:800; }
        .nav-links a { color:#a0aaba; font-size:15px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; transition:color .2s; }
        .nav-links a.active, .nav-links a:hover { color:#fff; }
        .nav-links a.active::after { background:#8b5cf6; box-shadow:0 0 10px rgba(139, 92, 246,.5); bottom:-5px; }
        /* Mega Menu */
        .nav-dropdown-wrap { position:relative; display:inline-flex; align-items:center; }
        .dropdown-trigger { color:#a0aaba; font-size:15px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; transition:color .2s; display:inline-flex; align-items:center; gap:4px; }
        .nav-dropdown-wrap:hover .dropdown-trigger, .dropdown-trigger.active { color:#fff; }
        .mega-menu {
            display: none;
            position: absolute;
            top: calc(100% + 16px);
            right: -20px;
            left: auto;
            transform: none;
            background: rgba(15, 20, 28, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246,0.15);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.04);
            padding: 20px;
            min-width: 380px;
            z-index: 99999;
        }
        .mega-menu::before {
            content: '';
            position: absolute;
            top: -16px;
            left: 0;
            width: 100%;
            height: 16px;
            background: transparent;
        }
        .nav-dropdown-wrap:hover .mega-menu { display: block; }
        .mega-menu-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 6px;
            margin-bottom: 12px;
        }
        .mega-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            text-decoration: none;
            color: #c3cad5;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.18s ease;
            border: 1px solid transparent;
        }
        .mega-item:hover {
            background: rgba(139, 92, 246,0.08);
            border-color: rgba(139, 92, 246,0.2);
            color: #fff;
            transform: translateX(3px);
        }
        .mega-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(139, 92, 246,0.08);
            border: 1px solid rgba(139, 92, 246,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b5cf6;
            font-size: 15px;
            flex-shrink: 0;
            transition: background 0.18s, border-color 0.18s;
        }
        .mega-item:hover .mega-icon {
            background: rgba(139, 92, 246,0.16);
            border-color: rgba(139, 92, 246,0.35);
        }
        .mega-footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 12px;
            text-align: center;
        }
        .mega-all-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #8b5cf6;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: gap 0.2s;
        }
        .mega-all-link:hover { gap: 10px; }
        /* Sub-Platform Child Menu */
        .mega-parent-wrap { position: relative; }
        .mega-child-menu {
            display: none;
            position: absolute;
            left: calc(100% + 8px);
            right: auto;
            top: 0;
            min-width: 240px;
            background: rgba(15, 20, 28, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246,0.15);
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.55);
            padding: 8px;
            z-index: 999999;
        }
        .mega-parent-wrap.has-kids:hover .mega-child-menu { display: block; }
        .mega-child-item {
            padding: 9px 12px;
            border-radius: 9px;
            font-size: 12px;
        }
        .mega-child-item .mega-icon {
            width: 28px;
            height: 28px;
            font-size: 12px;
            border-radius: 8px;
        }
        /* Language Selector */
        .lang-selector { position:relative; display:inline-flex; align-items:center; }
        .lang-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 14px; border-radius:10px; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.06); color:#c3cad5; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s ease; white-space:nowrap; letter-spacing:0.3px; }
        .lang-btn:hover { border-color:rgba(139, 92, 246,.35); color:#fff; background:rgba(139, 92, 246,.08); }
        .lang-btn svg { width:14px; height:14px; stroke:currentColor; fill:none; stroke-width:2.5; stroke-linecap:round; stroke-linejoin:round; transition:transform .2s; }
        .lang-selector.open .lang-btn svg { transform:rotate(180deg); }
        .lang-globe { width:16px; height:16px; stroke:var(--teal); fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; flex-shrink:0; }
        .lang-dropdown { position:absolute; top:calc(100% + 10px); right:0; min-width:190px; background:#111823; border:1px solid rgba(255,255,255,.1); border-radius:14px; box-shadow:0 20px 50px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.05); overflow:hidden; opacity:0; pointer-events:none; transform:translateY(-8px); transition:opacity .2s ease,transform .2s ease; z-index:99; }
        .lang-selector.open .lang-dropdown { opacity:1; pointer-events:all; transform:translateY(0); }
        .lang-dropdown-inner { max-height:320px; overflow-y:auto; padding:6px; scrollbar-width:thin; scrollbar-color:rgba(255,255,255,.1) transparent; }
        .lang-option { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:13px; font-weight:600; color:#c3cad5; cursor:pointer; transition:background .15s,color .15s; }
        .lang-option:hover { background:rgba(139, 92, 246,.1); color:#fff; }
        .lang-option.active { color:#8b5cf6; background:rgba(139, 92, 246,.08); }
        .lang-option .lang-flag { font-size:18px; line-height:1; flex-shrink:0; }
        /* Hide Google Translate toolbar */
        .goog-te-banner-frame, .goog-te-banner-frame.skiptranslate { display:none !important; }
        body { top:0 !important; }
        .goog-te-gadget { display:none !important; }
        #goog-gt-tt, .goog-te-balloon-frame { display:none !important; }
        .goog-tooltip, .goog-tooltip:hover { display:none !important; }
        .hero {
            background:
                radial-gradient(circle at 16% 5%,rgba(139, 92, 246,.14),transparent 30%),
                radial-gradient(circle at 82% 10%,rgba(96,165,250,.12),transparent 28%),
                radial-gradient(circle at 76% 86%,rgba(255,77,103,.08),transparent 25%),
                linear-gradient(180deg,#0d1219 0%,#090c11 100%);
            border-bottom:1px solid rgba(255,255,255,.08);
        }
        .hero::before {
            content:'';
            position:absolute;
            inset:0;
            pointer-events:none;
            opacity:.18;
            background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);
            background-size:52px 52px;
            mask-image:linear-gradient(to bottom,black,transparent 78%);
        }
        .hero-center { position:relative; z-index:1; }
        .hero .eyebrow {
            color:#7499f0;
            background:rgba(18,27,34,.72);
            border-color:rgba(139, 92, 246,.25);
            box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 12px 35px rgba(0,0,0,.25);
        }
        .hero h1 { color:#f8fafc; text-shadow:0 8px 35px rgba(0,0,0,.35); }
        .hero h1 span { color:#3162d6; }
        .hero-copy { color:#9da8b8; }
        .download-panel {
            background:#10161d;
            border:1px solid rgba(255,255,255,.05);
            backdrop-filter:none;
            box-shadow:0 15px 40px rgba(0,0,0,.3),inset 0 1px 0 rgba(255,255,255,.03);
            transform:translateZ(0);
            will-change:transform;
        }
        .url-form input {
            color:#f8fafc;
            background:#16202c;
            border-color:#324155;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            font-size: 17px;
        }
        .url-form input::placeholder { color:#8292a8; }
        .url-form input:hover { background:#1b2635; border-color:#40526b; }
        .url-form input:focus { color:#fff; background:#16202c; border-color:var(--teal); box-shadow:0 0 0 4px rgba(139, 92, 246,.15); }
        .button { color:#040813; background:linear-gradient(135deg,#8b5cf6,#6d28d9); box-shadow:0 14px 34px rgba(124, 58, 237,.22),inset 0 1px 0 rgba(255,255,255,.4); }
        .button:hover { background:linear-gradient(135deg,#5c86e9,#2053c9); box-shadow:0 18px 40px rgba(124, 58, 237,.32); }
        .note { color:#7f8a9b; }
        .platform-pill {
            color:#c3cad5;
            background:rgba(15,20,27,.82);
            border-color:rgba(255,255,255,.09);
            box-shadow:0 10px 28px rgba(0,0,0,.22),inset 0 1px 0 rgba(255,255,255,.04);
        }
        .platform-pill:hover { color:#fff; border-color:rgba(139, 92, 246,.3); background:#151c24; box-shadow:0 14px 34px rgba(0,0,0,.32); }
        .trust-bar, .content-band { color:var(--ink); background:#0d1117; border-color:#202733; }
        .trust-item { border-color:#202733; }
        .trust-item span, .section-head p, .platform-card p, .post-card p, .feature-card p, .step p, .faq p { color:var(--muted); }
        section { background:#0d1117; }
        section:nth-of-type(even), .steps-section { background:#10151c; }
        .feature-card, .platform-card, .post-card, .privacy-box {
            color:var(--ink);
            background:#141a22;
            border-color:#29313d;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.035),0 16px 40px rgba(0,0,0,.14);
        }
        .feature-card:hover, .platform-card:hover, .post-card:hover { transform:translateY(-3px); border-color:#3a4656; box-shadow:0 20px 46px rgba(0,0,0,.28); }
        .feature-card, .platform-card, .post-card { transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease; }
        .blog-grid { align-items:stretch; }
        .post-card { display:flex; flex-direction:column; padding:0; overflow:hidden; }
        .post-cover { position:relative; display:block; aspect-ratio:16/9; overflow:hidden; background:#090d12; }
        .post-cover::after { content:''; position:absolute; inset:0; border-bottom:1px solid rgba(255,255,255,.08); background:linear-gradient(180deg,transparent 58%,rgba(4,8,12,.5)); }
        .post-cover img { width:100%; height:100%; display:block; object-fit:cover; transition:transform .35s ease,filter .35s ease; }
        .post-card:hover .post-cover img { transform:scale(1.035); filter:brightness(1.08); }
        .post-content { display:flex; flex:1; flex-wrap:wrap; align-items:center; padding:20px; }
        .post-content h3, .post-content p { width:100%; }
        .post-meta { width:100%; display:flex; justify-content:space-between; gap:12px; margin-bottom:12px; color:#7f8a9b; font-size:11px; font-weight:700; text-transform:uppercase; }
        .post-meta span:first-child { color:#4472dc; }
        .post-content .read { margin-top:18px; color:#4875df; }
        .read-time { margin:18px 0 0 auto; color:#788394; font-size:12px; }
        .home-blog { padding:32px 0 72px; background:#0b0e13; border-top:1px solid #202832; }
        .home-blog-head { max-width:680px; margin:0 auto 42px; text-align:center; }
        .home-blog-label { display:inline-flex; margin-bottom:13px; padding:7px 16px; border:1px solid rgba(139, 92, 246,.45); border-radius:999px; color:var(--teal); font-size:11px; font-weight:800; }
        .home-blog-head h2 { margin:0 0 12px; font-size:34px; }
        .home-blog-head p { margin:0; color:#929cab; font-size:14px; }
        .editorial-blog-grid { display:grid; grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr); gap:56px; align-items:start; }
        .featured-post-image, .side-post-image { display:block; overflow:hidden; border:1px solid #2b3440; border-radius:8px; background:#151b22; }
        .featured-post-image { aspect-ratio:16/9; margin-bottom:16px; }
        .featured-post-image img, .side-post-image img { width:100%; height:100%; display:block; object-fit:cover; transition:transform .35s ease,filter .35s ease; }
        .featured-post:hover img, .side-post:hover img { transform:scale(1.035); filter:brightness(1.08); }
        .editorial-meta { display:flex; align-items:center; justify-content:space-between; gap:16px; color:#8993a2; font-size:11px; }
        .featured-post h3 { margin:13px 0 9px; font-size:21px; line-height:1.35; }
        .featured-post p, .side-post p { margin:0; color:#8f99a8; font-size:13px; line-height:1.65; }
        .side-posts { display:grid; gap:16px; }
        .side-post { display:grid; grid-template-columns:132px minmax(0,1fr); gap:18px; align-items:center; min-height:112px; }
        .side-post-image { width:132px; aspect-ratio:1.15/1; }
        .side-post h3 { margin:9px 0 5px; font-size:15px; line-height:1.4; }
        .side-post p { display:-webkit-box; overflow:hidden; -webkit-box-orient:vertical; -webkit-line-clamp:2; font-size:11px; }
        .view-all-posts { justify-self:start; margin-top:10px; display:inline-flex; align-items:center; gap:7px; background:linear-gradient(135deg,#8b5cf6,#6d28d9); color:#040813; padding:12px 28px; border-radius:30px; font-weight:800; font-size:14px; text-decoration:none; transition:transform 0.2s, box-shadow 0.2s; box-shadow: 0 8px 24px rgba(139, 92, 246,0.25); }
        .view-all-posts:hover { transform:translateY(-2px); box-shadow: 0 12px 30px rgba(139, 92, 246,0.35); color:#040813; }
        
        @media (max-width: 900px) {
            .dark-top-grid, .dark-bottom-grid { grid-template-columns: 1fr; }
            .dark-main-posts { grid-template-columns: 1fr; }
        }
        
        /* Dark Theme Blog Styles */
        .blog-page-hero { padding:100px 0 90px; text-align:center; background:#121212; position:relative; overflow:hidden; border-bottom:1px solid #2a2a2a; }
        .blog-page-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(circle at center, rgba(139, 92, 246,0.08) 0%, transparent 60%); pointer-events:none; }
        .blog-badge { display:inline-block; border:1px solid rgba(139, 92, 246,0.4); color:#8b5cf6; background:rgba(139, 92, 246,0.1); padding:6px 18px; border-radius:999px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:20px; }
        .blog-page-hero h1 { color:#fff; font-size:clamp(32px, 5vw, 56px); line-height:1.1; margin:0 0 16px; font-weight:800; max-width:800px; margin-left:auto; margin-right:auto; }
        .blog-page-hero h1 span { color:#8b5cf6; }
        .blog-page-hero p { color:#a0aaba; font-size:18px; max-width:600px; margin:0 auto; line-height:1.6; }
        
        .platforms-page-hero { padding:100px 0 90px; text-align:center; background:#101720; position:relative; overflow:hidden; border-bottom:1px solid #2a2a2a; }
        .platforms-page-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(circle at 50% -20%, rgba(139, 92, 246,0.12) 0%, transparent 60%); pointer-events:none; }
        .platforms-page-hero h1 { color:#fff; font-size:clamp(36px, 6vw, 64px); line-height:1.1; margin:0 0 16px; font-weight:800; }
        .platforms-page-hero h1 span { color:#8b5cf6; }
        .platforms-page-hero p { color:#a0aaba; font-size:18px; max-width:560px; margin:0 auto; line-height:1.6; }
        
        .privacy-hero { padding:100px 0 90px; text-align:center; background:#101720; position:relative; overflow:hidden; border-bottom:1px solid #2a2a2a; }
        .privacy-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(circle at 50% -20%, rgba(139, 92, 246,0.12) 0%, transparent 60%); pointer-events:none; }
        .privacy-hero h1 { color:#fff; font-size:clamp(36px, 6vw, 64px); line-height:1.1; margin:0 0 16px; font-weight:800; }
        .privacy-hero h1 span { color:#8b5cf6; }
        .privacy-hero p { color:#a0aaba; font-size:18px; max-width:560px; margin:0 auto; line-height:1.6; }
        
        .privacy-content-wrap { max-width:800px; margin:0 auto; padding:60px 20px 100px; }
        .privacy-box { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; padding:40px; color:#cfd6df; font-size:16px; line-height:1.8; box-shadow:0 10px 30px rgba(0,0,0,0.2); }
        .privacy-box h2 { color:#fff; font-size:24px; margin:30px 0 15px; }
        .privacy-box h2:first-child { margin-top:0; }
        .privacy-box p { margin:0 0 20px; }
        .privacy-box p:last-child { margin-bottom:0; }
        .privacy-box ul { padding-left:20px; margin-bottom:20px; }
        .privacy-box li { margin-bottom:10px; }
        
        .dark-blog-section { background:#1a1a1a; padding:60px 0 100px; color:#fff; }
        .dark-blog-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; flex-wrap:wrap; gap:20px; }
        .dark-blog-title { position:relative; font-size:28px; color:#fff; margin:0; padding-bottom:10px; font-weight:700; }
        .dark-blog-title::after { content:''; position:absolute; bottom:0; left:0; width:60px; height:3px; background:#8b5cf6; }
        .dark-search { position:relative; }
        .dark-search input { background:#252525; border:1px solid #333; color:#fff; padding:12px 20px 12px 40px; border-radius:30px; outline:none; width:280px; font-size:14px; }
        .dark-search input::placeholder { color:#888; }
        .dark-search::before { content:'⌕'; position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#888; font-size:18px; }
        
        .dark-top-grid { display:grid; grid-template-columns:1.2fr 0.8fr; gap:30px; margin-bottom:60px; }
        .dark-post-card { display:flex; flex-direction:column; background:transparent; }
        .dark-post-cover { border-radius:12px; overflow:hidden; aspect-ratio:16/10; position:relative; margin-bottom:16px; display:block; }
        .dark-post-cover img { width:100%; height:100%; object-fit:cover; transition:transform .4s ease; }
        .dark-post-card:hover .dark-post-cover img { transform:scale(1.05); }
        .dark-post-meta { display:flex; justify-content:space-between; color:#a0aaba; font-size:12px; font-weight:600; margin-bottom:12px; }
        .dark-post-title { font-size:22px; color:#fff; margin:0 0 10px; line-height:1.3; }
        .dark-post-title a { color:#fff; text-decoration:none; transition:color .2s; }
        .dark-post-title a:hover { color:#8b5cf6; }
        .dark-post-excerpt { color:#888; font-size:14px; line-height:1.6; margin:0; }
        
        .dark-side-stack { display:flex; flex-direction:column; gap:30px; }
        .dark-side-stack .dark-post-cover { aspect-ratio:16/10; }
        .dark-side-stack .dark-post-title { font-size:18px; }
        
        .dark-bottom-grid { display:grid; grid-template-columns:minmax(0, 1fr) 300px; gap:40px; }
        .dark-main-posts { display:grid; grid-template-columns:1fr 1fr; gap:30px; }
        .dark-main-posts .dark-post-title { font-size:18px; }
        .dark-sidebar { display:flex; flex-direction:column; gap:30px; }
        
        .dark-widget { background:#222; border:1px solid #333; border-radius:12px; padding:24px; }
        .dark-widget-title { font-size:18px; color:#fff; margin:0 0 20px; display:flex; align-items:center; gap:10px; font-weight:700; }
        .dark-widget-title span { color:#8b5cf6; }
        .dark-category-list { list-style:none; padding:0; margin:0; }
        .dark-category-list li { display:flex; align-items:center; gap:16px; padding:12px 0; border-bottom:1px solid #333; color:#ccc; font-size:14px; font-weight:600; }
        .dark-category-list li:last-child { border-bottom:none; }
        .dark-category-icon { width:36px; height:36px; border-radius:50%; background:#333; display:grid; place-items:center; color:#fff; font-size:14px; }
        
        .dark-form-group { margin-bottom:16px; }
        .dark-form-group input, .dark-form-group textarea { width:100%; background:#1a1a1a; border:1px solid #333; color:#fff; padding:14px; border-radius:8px; outline:none; font-size:14px; }
        .dark-form-group input::placeholder, .dark-form-group textarea::placeholder { color:#777; }
        .dark-form-group textarea { height:100px; resize:none; }
        .dark-form-row { display:flex; gap:12px; }
        .dark-btn-primary { background:linear-gradient(135deg,#8b5cf6,#6d28d9); color:#040813; border:none; padding:12px 24px; border-radius:8px; font-weight:700; cursor:pointer; width:100%; transition:box-shadow .2s, transform .2s; }
        .dark-btn-primary:hover { transform:translateY(-1px); box-shadow:0 12px 24px rgba(139, 92, 246,0.25); }
        .dark-btn-secondary { background:transparent; color:#fff; border:1px solid #fff; padding:12px 24px; border-radius:8px; font-weight:700; cursor:pointer; width:100%; transition:all .2s; }
        .dark-btn-secondary:hover { background:rgba(255,255,255,0.1); }
        
        @media (max-width: 900px) {
            .dark-top-grid, .dark-bottom-grid { grid-template-columns: 1fr; }
            .dark-main-posts { grid-template-columns: 1fr; }
            .article-layout { grid-template-columns: 1fr; }
            .article-hero-grid { grid-template-columns: 1fr; gap: 30px; }
            .article-aside { position: static; }
        }
        @media (max-width: 600px) {
            .blog-page-hero h1, .platforms-page-hero h1, .privacy-hero h1 { font-size: 32px; }
            .blog-page-hero { padding: 60px 20px; }
            .platforms-page-hero { padding: 60px 20px; }
            .privacy-hero { padding: 60px 20px; }
            .privacy-box { padding: 24px; }
            .dark-search input { width: 100%; }
            .dark-blog-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .dark-form-row { flex-direction: column; }
            .dark-widget { padding: 20px; }
            .article-hero h1 { font-size: 32px; }
        }
        .article-hero { padding:76px 0 56px; background:radial-gradient(circle at 12% 10%,rgba(139, 92, 246,.13),transparent 28%),linear-gradient(145deg,#101720,#080b10); border-bottom:1px solid #222b36; }
        .article-hero-grid { display:grid; grid-template-columns:minmax(0,1fr) minmax(380px,.85fr); gap:54px; align-items:center; }
        .article-back { display:inline-flex; margin-bottom:24px; color:#4573dd; font-size:13px; font-weight:800; }
        .article-meta { display:flex; flex-wrap:wrap; gap:10px 18px; color:#8490a2; font-size:12px; font-weight:700; text-transform:uppercase; }
        .article-meta span:first-child { color:#4573dd; }
        .article-hero h1 { margin:18px 0; font-size:clamp(38px,5vw,62px); line-height:1.05; }
        .article-hero p { margin:0; color:#a0aaba; font-size:17px; line-height:1.75; }
        .article-hero img { width:100%; height:auto; display:block; aspect-ratio:16/9; object-fit:cover; border:1px solid #303a48; border-radius:8px; box-shadow:0 26px 70px rgba(0,0,0,.4); }
        .article-layout { display:grid; grid-template-columns:minmax(0,760px) 240px; justify-content:center; gap:70px; padding-top:72px; padding-bottom:82px; }
        .article-body { color:#aeb7c5; font-size:16px; line-height:1.85; }
        .article-body h2 { margin:42px 0 14px; color:#f6f8fb; font-size:28px; line-height:1.25; }
        .article-body p { margin:0 0 20px; }
        .article-content ul { display:grid; gap:12px; margin:22px 0 30px; padding:0; list-style:none; }
        .article-content li { position:relative; padding:16px 18px 16px 48px; border:1px solid #293440; border-radius:8px; color:#d5dce5; background:#121820; }
        .article-content li::before { content:'✓'; position:absolute; left:18px; top:15px; color:#3565d6; font-weight:900; }
        .article-lead { color:#d9dfe8; font-size:20px; line-height:1.7; }
        .article-checklist { display:grid; gap:12px; margin:22px 0 30px; padding:0; list-style:none; }
        .article-checklist li { position:relative; padding:16px 18px 16px 48px; border:1px solid #293440; border-radius:8px; color:#d5dce5; background:#121820; }
        .article-checklist li::before { content:'✓'; position:absolute; left:18px; top:15px; color:#3565d6; font-weight:900; }
        .article-callout { margin:34px 0; padding:22px; color:#cfd6df; background:rgba(255,77,103,.08); border:1px solid rgba(255,77,103,.22); border-left:3px solid #ff526c; border-radius:8px; }
        .article-callout strong { color:#ff8294; }
        .article-cta { display:inline-flex; margin-top:6px; padding:14px 18px; border-radius:8px; color:#050914; background:#3565d6; font-weight:800; }
        .article-aside { position:sticky; top:112px; align-self:start; display:grid; gap:5px; padding:20px; border:1px solid #29323e; border-radius:8px; background:#11171e; }
        .article-aside strong { margin-bottom:9px; color:#f0f3f7; }
        .article-aside a { padding:9px 0; color:#8f9aaa; font-size:13px; border-bottom:1px solid #242c36; }
        .article-aside a:last-child { color:#4573dd; border:0; }
        .breadcrumb { display:flex; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:18px; color:#8d98a8; font-size:13px; font-weight:700; }
        .breadcrumb a { color:#c8d2df; }
        .breadcrumb a:hover { color:#4573dd; }
        .breadcrumb-separator { color:#556070; }
        .related-section { border-top:1px solid #252d38; background:#10151c; }
        .feature-icon { color:#406fdc; background:rgba(139, 92, 246,.1); border:1px solid rgba(139, 92, 246,.16); }
        /* Dark bento overrides */
        .bento-card { background:#141a22; border-color:#29313d; box-shadow:inset 0 1px 0 rgba(255,255,255,.03), 0 16px 40px rgba(0,0,0,.18); }
        .bento-card:hover { border-color:#3a4656; box-shadow:0 22px 50px rgba(0,0,0,.32); }
        .bento-card h3 { color:#f0f4f8; }
        .bento-icon-box { background:rgba(139, 92, 246,.07); border-color:rgba(139, 92, 246,.18); color:#8b5cf6; }
        .bento-card.hl { background:linear-gradient(135deg,#8b5cf6,#6d28d9); border-color:transparent; box-shadow:0 14px 36px rgba(124, 58, 237,.28); }
        .bento-card.hl h3 { color:#040813; }
        .bento-card.hl p  { color:rgba(4,19,15,.82); }
        .bento-card.hl .bento-icon-box { background:rgba(4,19,15,.08); border-color:rgba(4,19,15,.12); color:#040813; }
        .bento-card.hl .bento-chip { background:rgba(4,19,15,.08); color:#040813; }
        .bento-card.hl::after { color:rgba(4,19,15,.06); }
        .bento-circle { background:radial-gradient(circle,rgba(139, 92, 246,.05),transparent 70%); }
        .bento-globe  { color:rgba(255,255,255,.035); }
        .bento-devices svg { stroke:rgba(255,255,255,.3); }
        .step-icon-box {
            background: rgba(139, 92, 246,.07);
            border-color: rgba(139, 92, 246,.2);
            color: #8b5cf6;
        }
        .step:hover .step-icon-box {
            background: rgba(139, 92, 246,.13);
            border-color: #8b5cf6;
            box-shadow: 0 0 22px rgba(139, 92, 246,.15);
        }
        .step:not(:last-child)::after {
            border-top-color: rgba(255,255,255,.08);
        }
        .faq details { border-color:#28313e; }
        .faq summary { color:#e9edf3; }
        .page-hero { background:linear-gradient(135deg,#101720,#0a0e13); border-color:#252d38; }
        .page-hero h1 { color:#f8fafc; }
        .privacy-box { color:#a9b2c0; }
        footer { color:#8e99aa; background:#07090d; border-top:1px solid #202630; }
        .result { background:#0c1117; border-color:#2a3440; box-shadow:0 8px 24px rgba(0,0,0,.2); transform:translateZ(0); will-change:transform; }
        .media-summary { background:#0e141b; border-color:#29323e; }
        .media-thumb-wrap { position:relative; }
        .media-thumb { background:#171e27; transform:translateZ(0); }
        .media-play { position:absolute; inset:50% auto auto 50%; transform:translate(-50%,-50%); width:66px; height:66px; display:grid; place-items:center; border-radius:50%; color:#040813; background:rgba(255,255,255,.94); font-size:25px; box-shadow:0 18px 45px rgba(0,0,0,.28); }
        .media-platform { color:#3767d8; }
        .media-duration { color:#668de7; background:rgba(139, 92, 246,.1); border:1px solid rgba(139, 92, 246,.15); }
        .format-section + .format-section, .format-heading, .format-row, .result-note { border-color:#29323e; }
        .format-heading { background:#10161e; }
        .format-heading-mark { color:#060f24; background:#2d5fd4; }
        .format-row { background:#0c1117; transform:translateZ(0); }
        .format-row:hover { background:#111820; }
        .format-quality { color:#edf1f6; }
        .format-size, .empty-formats { color:#9aa5b5; }
        .format-badge { color:#251702; background:linear-gradient(135deg,#ffc45b,#f09b27); }
        .download-link { color:#a78bfa; background:#0d1018; border-color:#2455c9; }
        .download-link:hover { color:#030814; background:#a78bfa; border-color:#a78bfa; }
        .download-link.is-loading { color:#7d8796; background:#171c23; border-color:#36404d; }
        .result-note { color:#7e899a; background:#10161d; }
        .error { color:#ff8192; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .result-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        .loader-container {
            padding: 60px 20px;
            text-align: center;
            background: #0c1117;
            border-radius: 12px;
            border: 1px solid #2a3440;
            margin-top: 20px;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(139, 92, 246, 0.2);
            border-left-color: #8b5cf6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loader-text {
            color: #a0aaba;
            font-size: 16px;
            font-weight: 600;
        }

        @media (max-width:620px) {
            .hero::before { background-size:36px 36px; }
            .topbar { background:rgba(9,12,17,.94); }
            .article-hero { padding:46px 0 34px; }
            .article-hero h1 { font-size:36px; }
            .article-layout { padding-top:42px; padding-bottom:52px; }
        }
        @media (max-width:900px) { .article-hero-grid, .article-layout { grid-template-columns:1fr; } .article-hero-grid { gap:30px; } .article-aside { display:none; } }
        @media (max-width:860px) { .editorial-blog-grid { grid-template-columns:1fr; gap:34px; } }
        @media (max-width:560px) { .home-blog { padding:28px 0 44px; } .home-blog-head { margin-bottom:28px; } .home-blog-head h2 { font-size:29px; } .side-post { grid-template-columns:100px minmax(0,1fr); gap:12px; padding:12px; border-radius:12px; align-items:center; } .side-post-image { width:100px; } .side-post h3 { font-size:14px; margin-bottom:4px; } .editorial-meta { gap:8px; } }
        @media (max-width:760px) {
            html, body { max-width:100%; overflow-x:hidden; }
            .wrap { width:min(100% - 24px, 1160px); }
            .topbar { position:sticky; padding:8px 0; background:rgba(10,14,20,0.85); backdrop-filter:blur(16px); border-bottom:1px solid rgba(255,255,255,0.04); box-shadow:0 10px 30px rgba(0,0,0,0.4); }
            .nav { min-height:auto; padding:8px 0; flex-direction:row; align-items:center; gap:12px; }
            .brand { max-width:100%; }
            .brand-mark { width:38px; height:38px; flex:0 0 38px; border-radius:12px; box-shadow:0 6px 18px rgba(139, 92, 246,0.2); }
            .brand-name { font-size:20px; white-space:normal; line-height:1.1; letter-spacing:-0.5px; }
            .menu-toggle { display:inline-grid; place-items:center; margin-left:auto; width:44px; height:44px; border:1px solid rgba(255,255,255,0.06); border-radius:12px; color:#8b5cf6; background:rgba(255,255,255,0.03); cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:background 0.2s, border-color 0.2s; }
            .menu-toggle:active { background:rgba(255,255,255,0.06); border-color:rgba(139, 92, 246,0.3); }
            .menu-toggle span, .menu-toggle::before, .menu-toggle::after { content:''; width:20px; height:2px; border-radius:2px; background:currentColor; transition:transform .2s ease, opacity .2s ease; }
            .menu-toggle { gap:5px; }
            .menu-toggle.is-open span { opacity:0; }
            .menu-toggle.is-open::before { transform:translateY(7px) rotate(45deg); }
            .menu-toggle.is-open::after { transform:translateY(-7px) rotate(-45deg); }
            .nav-links { position:absolute; left:12px; right:12px; top:calc(100% + 12px); display:grid; grid-template-columns:1fr; gap:8px; padding:16px; border:1px solid rgba(255,255,255,0.08); border-radius:18px; background:rgba(15,20,28,0.96); backdrop-filter:blur(20px); box-shadow:0 24px 60px rgba(0,0,0,0.6); opacity:0; visibility:hidden; transform:translateY(-12px); transition:opacity .25s ease, transform .25s ease, visibility .25s ease; z-index: 50; }
            .nav-links.is-open { opacity:1; visibility:visible; transform:translateY(0); }
            .nav-links a, .nav-links a:not(:first-child) { display:flex; min-height:48px; align-items:center; justify-content:center; padding:10px 16px; border:1px solid rgba(255,255,255,0.03); border-radius:12px; background:rgba(255,255,255,0.02); color:#cfd6df; font-size:14px; font-weight:700; text-align:center; text-transform:none; letter-spacing:0.2px; transition:all 0.2s ease; }
            .nav-links a:hover { background:rgba(255,255,255,0.05); color:#fff; }
            .nav-links a.active::after { display:none; }
            .nav-links a.active { color:#040813; background:linear-gradient(135deg,#8b5cf6,#6d28d9); border-color:transparent; box-shadow:0 8px 24px rgba(139, 92, 246,0.25); }
            .nav-dropdown-wrap, .lang-selector { display:flex; flex-direction:column; width:100%; }
            .nav-dropdown-wrap .dropdown-trigger { width:100%; justify-content:center; }
            .lang-btn { width:100%; justify-content:center; min-height:48px; border-radius:12px; border:1px solid rgba(255,255,255,0.03); background:rgba(255,255,255,0.02); color:#cfd6df; font-size:14px; font-weight:700; padding:10px 16px; transition:all 0.2s ease; }
            .lang-btn:hover { background:rgba(255,255,255,0.05); color:#fff; }
            .lang-dropdown { position:static; width:100%; min-width:0; margin-top:8px; border-radius:12px; background:rgba(0,0,0,0.2); box-shadow:none; border:1px solid rgba(255,255,255,0.05); opacity:1 !important; visibility:visible !important; transform:none !important; display:none; pointer-events:auto !important; }
            .lang-selector.open .lang-dropdown { display:block; }
            .mega-menu { position:static; width:100%; margin-top:8px; border-radius:12px; background:rgba(0,0,0,0.2); box-shadow:none; border:1px solid rgba(255,255,255,0.05); padding:10px; display:none; backdrop-filter:none; }
            .nav-dropdown-wrap.open .mega-menu, .nav-dropdown-wrap:hover .mega-menu { display:block; }
            .mega-menu::before { display:none; }
            .nav-links .mega-item { justify-content:flex-start; text-align:left; }
            .mega-child-menu { position:static; width:100%; margin-top:6px; border-radius:8px; background:rgba(255,255,255,0.03); box-shadow:none; border:none; padding:6px; backdrop-filter:none; display:none; }
            .mega-parent-wrap.has-kids:hover .mega-child-menu { display:block; }
            .hero { padding:40px 0 30px; }
            .hero h1 { font-size:clamp(34px, 13vw, 48px); line-height:1.04; }
            .hero-copy { font-size:15px; line-height:1.65; }
            .download-panel { width:100%; border-radius:14px; padding:9px; }
            .url-form { grid-template-columns:1fr; }
            .url-form input, .button { min-height:54px; border-radius:10px; }
            .platform-strip { justify-content:flex-start; overflow-x:auto; flex-wrap:nowrap; padding:0 2px 8px; scrollbar-width:none; }
            .platform-strip::-webkit-scrollbar { display:none; }
            .platform-pill { flex:0 0 auto; }
            .trust-grid, .platform-grid, .features, .blog-grid { grid-template-columns:1fr; gap:12px; }
            .platform-card { min-height:170px; display:flex; padding:18px !important; text-align:left; }
            .platform-card .platform-icon { width:44px; height:44px; }
            .platform-card h3 { margin:0 0 6px; font-size:17px; }
            .platform-card p { max-width:260px; max-height:3.1em; font-size:12.5px; -webkit-line-clamp:2; line-clamp:2; }
            .trust-item { padding:18px 10px; }
            section { padding:0px 0; }
            .section-head h2 { font-size:28px; }
            .bento-grid, .bento-grid-r2 { grid-template-columns:1fr; gap:14px; margin-top:14px; }
            .bento-card { min-height:auto; padding:22px; border-radius:14px; }
            .steps-flow { flex-direction:column; align-items:stretch; gap:16px; margin-top:0px; }
            .step { display:grid; grid-template-columns:auto 1fr; grid-template-areas:"icon title" "icon text"; gap:4px 16px; padding:16px; text-align:left; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.04); border-radius:16px; }
            .step-icon-box { grid-area:icon; margin-bottom:0; width:52px; height:52px; border-radius:14px; align-self:center; }
            .step h3 { grid-area:title; margin:0; font-size:16px; align-self:end; }
            .step p { grid-area:text; max-width:none; font-size:13.5px; margin:0; align-self:start; }
            .result-layout { grid-template-columns:1fr; }
            .result { margin-top:14px; border-radius:22px; overflow:hidden; background:#0d1117; border-color:#27313c; }
            .media-summary { border-right:0; border-bottom:1px solid #27313c; padding:18px 18px 20px; text-align:center; background:#0f151c; }
            .media-thumb-wrap { border-radius:16px; overflow:hidden; box-shadow:0 18px 44px rgba(0,0,0,.28); }
            .media-thumb { border-radius:16px; }
            .media-play { width:58px; height:58px; font-size:21px; }
            .media-platform { margin-top:18px; font-size:11px; }
            .media-title {
                max-width:100%;
                margin:0 auto;
                color:#f8fafc;
                font-size:20px !important;
                line-height:1.32;
                font-weight:900;
                text-align:center;
                display:-webkit-box;
                -webkit-box-orient:vertical;
                -webkit-line-clamp:2;
                overflow:hidden;
                text-overflow:ellipsis;
                overflow-wrap:anywhere;
                word-break:break-word;
            }
            .media-duration { margin-top:14px; padding:8px 12px; border-radius:8px; color:#030814; background:#8b5cf6; border:0; font-size:14px; }
            .format-list { background:#0d1117; }
            .format-section { padding:18px 0 6px; }
            .format-section + .format-section { border-top:1px solid #27313c; }
            .format-heading { gap:12px; padding:0 18px 14px; border-bottom:1px solid #27313c; background:transparent; color:#f8fafc; font-size:24px; font-weight:900; }
            .format-heading-mark { width:32px; height:32px; border-radius:8px; color:#030814; background:#8b5cf6; }
            .format-row { grid-template-columns:56px minmax(0,1fr); gap:4px 8px; min-height:0; padding:12px 16px; background:#0d1117; border-bottom:1px solid #1f2832; align-items:start; }
            .format-badge { grid-column:1; grid-row:1 / span 2; min-width:auto; padding:4px 8px; border-radius:6px; color:#030814; background:#8b5cf6; font-size:12px; font-weight:800; display:inline-block; text-align:center; }
            .format-quality { grid-column:2; grid-row:1; color:#f8fafc; font-size:14px; font-weight:700; text-align:left; line-height:1.1; min-width:0; }
            .format-size { grid-column:2; grid-row:2; color:#9da8b7; font-size:12px; text-align:left; padding-right:0; line-height:1; white-space:nowrap; min-width:0; }
            .download-link { grid-column:1 / -1; grid-row:3; width:100%; min-width:0; min-height:32px; margin-top:4px; padding:0 12px; border:1px solid #8b5cf6; border-radius:6px; color:#8b5cf6; background:transparent; font-size:12px; font-weight:700; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; }
            .download-link svg, .download-link .download-icon { width:14px; height:14px; flex:0 0 14px; }
            .download-label { font-size:12px; }
            .download-link:hover { color:#030814; background:#8b5cf6; }
            .result-note { padding:14px 18px; text-align:center; }
            .blog-page-hero, .platforms-page-hero, .privacy-hero { padding:54px 0 40px; }
            .blog-page-hero h1, .platforms-page-hero h1, .privacy-hero h1 { font-size:clamp(34px, 11vw, 46px); }
            .dark-blog-header { align-items:stretch; }
            .dark-search, .dark-search input { width:100%; }
            .dark-top-grid, .dark-bottom-grid, .dark-main-posts { grid-template-columns:1fr; gap:18px; }
            .dark-sidebar { display:none; }
            .dark-post-card { border-radius:10px; }
            .dark-post-cover { aspect-ratio:16/9; }
            .dark-post-title, .dark-main-posts .dark-post-title, .dark-side-stack .dark-post-title { font-size:18px; }
            .editorial-blog-grid { grid-template-columns:1fr; }
            .featured-post h3 { font-size:23px; }
            .side-post { grid-template-columns:110px minmax(0,1fr); gap:14px; align-items:center; padding:12px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.04); border-radius:12px; }
            .side-post-image { width:110px; border-radius:8px; }
            .side-post p { display:none; }
            .editorial-meta { font-size:10px; flex-wrap:wrap; }
            .article-hero { padding:36px 0 30px; }
            .article-hero-grid { grid-template-columns:1fr; gap:22px; }
            .article-hero h1 { font-size:clamp(32px, 11vw, 44px); }
            .article-hero p { font-size:16px; }
            .article-hero img { width:100%; height:auto; border-radius:10px; }
            .article-layout { grid-template-columns:1fr; padding-top:34px; padding-bottom:48px; }
            .article-content h2 { font-size:25px; }
            .article-content p { font-size:16px; }
            .article-content li, .article-checklist li { padding:14px 14px 14px 42px; }
            .article-aside { display:block; position:static; order:-1; margin-bottom:18px; }
            .breadcrumb { margin-bottom:14px; font-size:12px; gap:6px; }
            .related-section .blog-grid { grid-template-columns:1fr; }
            footer .footer-row { display:grid; gap:10px; text-align:center; }
        }
        @media (max-width:420px) {
            .nav-links { grid-template-columns:1fr; }
        }

        /* Figma-inspired homepage hero */
        .topbar {
            z-index:1000;
            isolation:isolate;
            padding:0;
            background:rgba(4,8,19,.92);
            border-bottom:1px solid rgba(117,137,187,.16);
            box-shadow:none;
            backdrop-filter:blur(18px);
        }
        .nav { width:min(1240px,calc(100% - 128px)); min-height:66px; }
        .brand img { width:190px !important; height:auto !important; }
        .nav-links { gap:18px; }
        .nav-links > a,
        .nav-links .dropdown-trigger {
            padding:10px 8px;
            color:#b8bdd0;
            font-size:13px;
            font-weight:600;
            letter-spacing:0;
            text-transform:none;
        }
        .nav-links > a:hover,
        .nav-links .dropdown-trigger:hover { color:#fff; }
        .mega-menu {
            z-index:1100;
            min-width:480px;
            background:#0f141c;
            border-color:rgba(126,79,255,.24);
        }
        .mega-menu-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .lang-btn { min-height:38px; padding:8px 13px; background:#0a1020; border-color:#1c2741; color:#d3d7e2; }
        .lang-globe { color:#c8cfdd; stroke:currentColor; }

        .page-home .hero {
            min-height:408px;
            padding:25px 0 18px;
            isolation:isolate;
            background:
                radial-gradient(ellipse at 8% 72%,rgba(84,28,232,.35) 0%,rgba(40,18,126,.2) 19%,transparent 39%),
                radial-gradient(ellipse at 93% 80%,rgba(71,30,234,.4) 0%,rgba(24,22,111,.22) 21%,transparent 42%),
                radial-gradient(circle at 52% 12%,rgba(48,35,128,.18),transparent 34%),
                linear-gradient(180deg,#050916 0%,#070b19 58%,#050816 100%);
            border-bottom:1px solid rgba(104,78,245,.18);
        }
        .page-home .hero::before {
            z-index:-2;
            opacity:.58;
            background-image:radial-gradient(circle,rgba(94,92,227,.48) 1px,transparent 1.35px);
            background-size:15px 15px;
            background-position:calc(100% - 42px) 26px;
            mask-image:linear-gradient(90deg,transparent 0 66%,#000 78%,transparent 100%);
        }
        .page-home .hero::after {
            content:'';
            position:absolute;
            inset:0;
            z-index:-3;
            pointer-events:none;
            opacity:.24;
            background-image:linear-gradient(rgba(102,117,169,.09) 1px,transparent 1px),linear-gradient(90deg,rgba(102,117,169,.09) 1px,transparent 1px);
            background-size:56px 56px;
            mask-image:linear-gradient(to bottom,#000,transparent 82%);
        }
        .hero-orbit {
            position:absolute;
            z-index:-1;
            width:340px;
            height:110px;
            border:3px solid #5a2cff;
            border-radius:50%;
            clip-path:inset(48% -24px -24px -24px);
            filter:drop-shadow(0 0 15px rgba(84,44,255,.55));
            pointer-events:none;
            -webkit-mask-image: linear-gradient(to right, transparent, #000 15%, #000 85%, transparent);
            mask-image: linear-gradient(to right, transparent, #000 15%, #000 85%, transparent);
        }
        .hero-orbit-left { left:-5px; top:153px; width:240px; transform:none; }
        .hero-orbit-right { right:-40px; top:178px; width:300px; height:120px; transform:none; }
        .page-home .hero-center { max-width:1000px; }
        .page-home .hero .eyebrow {
            gap:7px;
            padding:7px 14px;
            color:#aab8ff;
            background:linear-gradient(90deg,rgba(8,50,96,.7),rgba(72,24,129,.75));
            border:1px solid rgba(113,71,255,.42);
            border-radius:999px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.08),0 8px 28px rgba(32,12,92,.28);
            font-size:10px;
            letter-spacing:.075em;
        }
        .page-home .hero .eyebrow::before { display:none; }
        .page-home .hero .eyebrow svg { width:13px; height:13px; fill:#23c6ff; filter:drop-shadow(0 0 6px rgba(35,198,255,.45)); }
        .page-home .hero h1 {
            max-width:760px;
            margin:10px auto 8px;
            color:#fff;
            font-size:clamp(38px,4vw,52px) !important;
            font-weight:800;
            line-height:1;
            letter-spacing:-1.8px;
            text-shadow:0 6px 28px rgba(0,0,0,.35);
        }
        .page-home .hero h1 span {
            color:transparent;
            background:linear-gradient(90deg,#3f7df7 0%,#8c3dff 88%);
            -webkit-background-clip:text;
            background-clip:text;
        }
        .page-home .hero-copy {
            max-width:650px;
            margin:0 auto 15px;
            color:#9ca7be;
            font-size:12.5px !important;
            line-height:1.45;
        }
        .page-home .download-panel {
            width:min(850px,60vw);
            max-width:850px;
            margin:0 auto;
            padding:8px;
            background:rgba(9,15,31,.86);
            border:1px solid rgba(118,57,255,.48);
            border-radius:16px;
            box-shadow:0 16px 44px rgba(0,0,0,.42),0 0 28px rgba(77,36,221,.1),inset 0 1px 0 rgba(255,255,255,.055);
        }
        .page-home .download-panel.has-result { max-width:1120px; }
        .page-home .url-form { grid-template-columns:minmax(0,1fr) 140px; gap:8px; }
        .page-home .url-input-wrap { position:relative; display:block; min-width:0; }
        .page-home .url-input-wrap > svg {
            position:absolute;
            left:18px;
            top:50%;
            z-index:2;
            width:20px;
            height:20px;
            transform:translateY(-50%);
            fill:none;
            stroke:#8b96b2;
            stroke-width:1.8;
            stroke-linecap:round;
            stroke-linejoin:round;
            pointer-events:none;
        }
        .page-home .url-form input {
            min-height:52px;
            padding:15px 18px 15px 52px;
            color:#eef2ff;
            background:rgba(4,10,23,.9);
            border:1px solid #273451;
            border-radius:10px;
            box-shadow:inset 0 1px 6px rgba(0,0,0,.22);
            font-size:14px;
        }
        .page-home .url-form input:hover { background:#070e20; border-color:#344364; }
        .page-home .url-form input:focus { background:#070e20; border-color:#7048ff; box-shadow:0 0 0 3px rgba(112,72,255,.14); }
        .page-home .url-form input::placeholder { color:#828da8; }
        .page-home .button {
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            min-width:0;
            min-height:52px;
            padding:0 14px;
            color:#fff;
            background:linear-gradient(135deg,#8f38f6 0%,#5c1ee0 100%);
            border:1px solid rgba(176,112,255,.65);
            border-radius:10px;
            box-shadow:0 9px 24px rgba(94,31,224,.34),inset 0 1px 0 rgba(255,255,255,.22);
            font-size:12.5px;
        }
        .page-home .button .download-label { white-space:nowrap; }
        .page-home .button:hover { color:#fff; background:linear-gradient(135deg,#a14cff,#6b29ec); transform:translateY(-1px); }
        .page-home .button-arrow { width:18px; height:18px; fill:none; stroke:currentColor; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }
        .page-home .platform-prompt { margin:11px 0 7px; color:#a7afc1; font-size:11px !important; }
        .page-home .platform-strip { gap:7px; margin:0 auto; }
        .page-home .platform-pill {
            gap:7px;
            min-height:36px;
            padding:6px 10px;
            color:#d0d5e2;
            background:rgba(9,15,29,.84);
            border:1px solid #202c45;
            border-radius:7px;
            box-shadow:0 7px 18px rgba(0,0,0,.22),inset 0 1px 0 rgba(255,255,255,.025);
            font-size:11px;
            font-weight:600;
        }
        .page-home .platform-pill:hover { transform:translateY(-1px); border-color:rgba(119,76,255,.56); background:#0d1426; }
        .page-home .platform-dot { width:20px; height:20px; flex-basis:20px; border-radius:6px; }
        .page-home .platform-dot img { width:12px; height:12px; }
        .page-home .hero-trust {
            display:grid;
            grid-template-columns:repeat(3,minmax(0,1fr));
            gap:30px;
            max-width:570px;
            margin:18px auto 0;
            text-align:left;
        }
        .page-home .hero-trust-item { display:flex; align-items:center; gap:11px; min-width:0; }
        .page-home .hero-trust-mobile-only { display:none; }
        .page-home .hero-trust-icon {
            display:grid;
            place-items:center;
            flex:0 0 42px;
            width:42px;
            height:42px;
            color:#9d57ff;
            background:rgba(76,26,155,.18);
            border:1px solid rgba(127,62,255,.3);
            border-radius:50%;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.035);
        }
        .page-home .hero-trust-icon svg { width:21px; height:21px; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; }
        .page-home .hero-trust-item > span:last-child { display:flex; min-width:0; flex-direction:column; gap:3px; }
        .page-home .hero-trust-item strong { color:#f4f5fb; font-size:12px; line-height:1.2; }
        .page-home .hero-trust-item small { color:#8994aa; font-size:10px; white-space:nowrap; }
        .page-home #how-it-works { scroll-margin-top:78px; }
        .page-home #faq { scroll-margin-top:78px; }

        /* Larger desktop hero scale */
        @media (min-width:761px) {
            .page-home .hero {
                min-height:520px;
                padding:38px 0 32px;
            }
            .page-home .hero-center { max-width:1220px; }
            .page-home .hero .eyebrow {
                padding:9px 18px;
                gap:9px;
                font-size:12px;
            }
            .page-home .hero .eyebrow svg { width:15px; height:15px; }
            .page-home .hero h1 {
                max-width:960px;
                margin:16px auto 13px;
                font-size:clamp(52px,4.8vw,68px) !important;
                line-height:1.02;
                letter-spacing:-2.2px;
            }
            .page-home .hero-copy {
                max-width:780px;
                margin-bottom:23px;
                font-size:16px !important;
                line-height:1.5;
            }
            .page-home .download-panel {
                width:min(1040px,72vw);
                max-width:1040px;
                padding:10px;
            }
            .page-home .url-form { grid-template-columns:minmax(0,1fr) 180px; gap:10px; }
            .page-home .url-form input {
                min-height:62px;
                padding-left:58px;
                font-size:16px;
            }
            .page-home .url-input-wrap > svg { left:20px; width:23px; height:23px; }
            .page-home .button {
                min-height:62px;
                font-size:15px;
            }
            .page-home .platform-prompt { margin:15px 0 10px; font-size:13px !important; }
            .page-home .platform-strip { gap:10px; }
            .page-home .platform-pill {
                min-height:43px;
                padding:8px 14px;
                gap:9px;
                font-size:13px;
            }
            .page-home .platform-dot { width:25px; height:25px; flex-basis:25px; }
            .page-home .platform-dot img { width:15px; height:15px; }
            .page-home .hero-trust {
                max-width:760px;
                margin-top:25px;
                gap:58px;
            }
            .page-home .hero-trust-item { gap:14px; }
            .page-home .hero-trust-icon { flex-basis:50px; width:50px; height:50px; }
            .page-home .hero-trust-icon svg { width:25px; height:25px; }
            .page-home .hero-trust-item strong { font-size:15px; }
            .page-home .hero-trust-item small { font-size:12px; }
            .hero-orbit-left { top:210px; width:300px; }
            .hero-orbit-right { top:245px; width:370px; }
        }

        @media (max-width:760px) {
            .topbar { padding:5px 0; }
            .nav { width:min(100% - 24px,1160px); min-height:58px; }
            .brand img { width:132px !important; }
            .mega-menu { min-width:0; }
            .mega-menu-grid { grid-template-columns:1fr; }
            .page-home .hero { min-height:0; padding:38px 0 34px; }
            .page-home .hero h1 { margin-top:16px; font-size:clamp(36px,11vw,49px) !important; letter-spacing:-1.6px; }
            .page-home .hero-copy { margin-bottom:20px; padding:0 6px; font-size:13px !important; }
            .page-home .download-panel { width:100%; padding:9px; }
            .page-home .url-form { grid-template-columns:1fr; }
            .page-home .url-form input { min-height:54px; }
            .page-home .button { min-height:52px; }
            .page-home .platform-strip { justify-content:flex-start; }
            .page-home .hero-trust { gap:12px; margin-top:20px; }
            .page-home .hero-trust-item { flex-direction:column; gap:7px; text-align:center; }
            .page-home .hero-trust-item small { white-space:normal; }
            .hero-orbit { opacity:.55; }
        }
        @media (max-width:480px) {
            .page-home .hero { padding:10px 0 4px; }
            .page-home .hero-center { width:min(100% - 36px,1000px); }
            .page-home .hero .eyebrow { padding:6px 11px; font-size:9px; }
            .page-home .hero h1 {
                max-width:345px;
                margin-top:13px;
                font-size:clamp(27px,7.5vw,31px) !important;
                line-height:1.03;
                letter-spacing:-1px;
            }
            .page-home .hero-copy {
                max-width:310px;
                margin-bottom:13px;
                font-size:11px !important;
                line-height:1.45;
            }
            .page-home .hero-copy br { display:block; }
            .page-home .download-panel { border-radius:10px; padding:6px; }
            .page-home .url-form { gap:6px; }
            .page-home .url-form input { min-height:48px; padding-left:46px; border-radius:7px; font-size:12px; }
            .page-home .url-input-wrap > svg { left:15px; width:18px; height:18px; }
            .page-home .button { min-height:42px; border-radius:7px; font-size:12px; }
            .page-home .platform-prompt { margin:10px 0 7px; font-size:9px !important; }
            .page-home .platform-strip {
                display:grid;
                grid-template-columns:repeat(3,minmax(0,1fr));
                gap:7px;
                width:100%;
                padding:0;
                overflow:visible;
            }
            .page-home .platform-pill {
                justify-content:flex-start;
                width:100%;
                min-height:38px;
                padding:6px 8px;
                font-size:9px;
                white-space:nowrap;
            }
            .page-home .platform-dot { width:19px; height:19px; flex-basis:19px; }
            .page-home .hero-trust {
                grid-template-columns:repeat(4,minmax(0,1fr));
                gap:5px;
                width:100%;
                max-width:none;
                margin-top:11px;
            }
            .page-home .hero-trust-item,
            .page-home .hero-trust-mobile-only {
                display:flex;
                min-height:88px;
                padding:7px 3px 6px;
                flex-direction:column;
                justify-content:flex-start;
                gap:5px;
                text-align:center;
                background:rgba(9,15,29,.78);
                border:1px solid #1b2740;
                border-radius:8px;
            }
            .page-home .hero-trust-icon { flex-basis:36px; width:36px; height:36px; }
            .page-home .hero-trust-icon svg { width:19px; height:19px; }
            .page-home .hero-trust-item > span:last-child { align-items:center; gap:2px; }
            .page-home .hero-trust-item strong { font-size:9px; white-space:nowrap; }
            .page-home .hero-trust-item small { font-size:7px; line-height:1.25; white-space:normal; }
            .page-home .hero-orbit { opacity:.3; }
        }

        /* Figma-style three-step workflow */
        .page-home .legacy-how-section,
        .page-home .steps-section { display:none; }
        .page-home .how-steps-section {
            padding:21px 0 22px;
            background:
                radial-gradient(circle at 50% 100%,rgba(62,34,164,.08),transparent 48%),
                linear-gradient(180deg,#060b17 0%,#07101d 100%);
            border-top:1px solid rgba(103,77,244,.16);
            border-bottom:1px solid rgba(103,77,244,.12);
        }
        .page-home .how-steps-intro { margin:0 auto 38px; text-align:center; }
        .page-home .how-steps-intro h2 {
            margin:0 0 7px;
            color:#f7f8fc;
            font-size:36px !important;
            font-weight:800;
            line-height:1.15;
            letter-spacing:-.5px;
        }
        .page-home .how-steps-intro p {
            margin:0;
            color:#9ba6bb;
            font-size:15px !important;
            line-height:1.5;
        }
        .page-home .how-steps-grid {
            display:grid;
            grid-template-columns:minmax(0,1fr) 34px minmax(0,1fr) 34px minmax(0,1fr);
            align-items:center;
            gap:18px;
            max-width:1160px;
            margin:0 auto;
        }
        .page-home .how-step-card {
            position:relative;
            display:flex;
            min-height:235px;
            padding:28px 30px 25px;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            color:#fff;
            text-align:center;
            background:linear-gradient(145deg,rgba(13,22,38,.98),rgba(8,15,28,.98));
            border:1px solid #1d2a42;
            border-radius:11px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.025),0 14px 35px rgba(0,0,0,.18);
            transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease;
        }
        .page-home .how-step-card:hover {
            transform:translateY(-3px);
            border-color:rgba(121,70,255,.48);
            box-shadow:0 18px 42px rgba(0,0,0,.3),0 0 24px rgba(88,41,213,.08);
        }
        .page-home .how-step-number {
            position:absolute;
            top:16px;
            left:16px;
            display:grid;
            place-items:center;
            width:38px;
            height:38px;
            color:#b795ff;
            background:rgba(98,48,194,.22);
            border:1px solid rgba(131,76,245,.18);
            border-radius:50%;
            font-size:12px;
            font-weight:800;
        }
        .page-home .how-step-icon {
            display:grid;
            place-items:center;
            width:64px;
            height:64px;
            margin:0 0 17px;
            color:#8854ff;
            background:linear-gradient(145deg,rgba(61,34,128,.34),rgba(22,24,56,.7));
            border:1px solid rgba(116,68,230,.2);
            border-radius:11px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.035),0 8px 22px rgba(0,0,0,.18);
        }
        .page-home .how-step-icon svg,
        .page-home .how-step-arrow svg {
            fill:none;
            stroke:currentColor;
            stroke-width:1.8;
            stroke-linecap:round;
            stroke-linejoin:round;
        }
        .page-home .how-step-icon svg { width:29px; height:29px; }
        .page-home .how-step-card h3 {
            margin:0 0 7px;
            color:#f5f6fb;
            font-size:18px !important;
            font-weight:800;
            line-height:1.2;
        }
        .page-home .how-step-card p {
            max-width:255px;
            margin:0;
            color:#9da8bc;
            font-size:13px !important;
            line-height:1.55;
        }
        .page-home .how-step-arrow { display:grid; place-items:center; color:#a648ff; }
        .page-home .how-step-arrow svg { width:34px; height:20px; }

        /* Why choose us feature row */
        .page-home .why-choose-section {
            scroll-margin-top:66px;
            padding:64px 0 72px;
            background:
                radial-gradient(circle at 50% 0,rgba(54,31,139,.07),transparent 45%),
                linear-gradient(180deg,#060b17 0%,#070d19 100%);
            border-bottom:1px solid rgba(103,77,244,.13);
        }
        .page-home .why-choose-intro { margin:0 auto 38px; text-align:center; }
        .page-home .why-choose-intro h2 {
            margin:0 0 5px;
            color:#f7f8fc;
            font-size:36px !important;
            font-weight:800;
            line-height:1.2;
            letter-spacing:-.45px;
        }
        .page-home .why-choose-intro p {
            margin:0;
            color:#929db2;
            font-size:15px !important;
            line-height:1.5;
        }
        .page-home .why-choose-grid {
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:16px;
            max-width:1160px;
            margin:0 auto;
        }
        .page-home .why-card {
            position:relative;
            display:flex;
            min-height:190px;
            padding:25px 25px 25px;
            align-items:flex-start;
            gap:16px;
            overflow:hidden;
            background:linear-gradient(145deg,rgba(14,23,39,.98),rgba(9,16,29,.98));
            border:1px solid #1c2940;
            border-radius:10px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.025),0 12px 30px rgba(0,0,0,.16);
            transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease;
        }
        .page-home .why-card:hover {
            transform:translateY(-3px);
            border-color:rgba(113,70,218,.46);
            box-shadow:0 17px 38px rgba(0,0,0,.28);
        }
        .page-home .why-card::after {
            content:'';
            position:absolute;
            width:85px;
            height:85px;
            top:-48px;
            left:-38px;
            border-radius:50%;
            background:currentColor;
            opacity:.035;
            pointer-events:none;
        }
        .page-home .why-card-icon {
            display:grid;
            place-items:center;
            flex:0 0 54px;
            width:54px;
            height:54px;
            color:#9f4fff;
            background:rgba(107,50,180,.22);
            border:1px solid rgba(153,76,239,.26);
            border-radius:10px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.045);
        }
        .page-home .why-card-icon svg {
            width:29px;
            height:29px;
            fill:none;
            stroke:currentColor;
            stroke-width:1.8;
            stroke-linecap:round;
            stroke-linejoin:round;
        }
        .page-home .why-card-hd { font-size:17px; font-weight:900; letter-spacing:-.4px; }
        .page-home .why-card > div { min-width:0; }
        .page-home .why-card h3 {
            margin:3px 0 8px;
            color:#f4f6fb;
            font-size:16px !important;
            font-weight:800;
            line-height:1.25;
            white-space:normal;
        }
        .page-home .why-card p {
            margin:0;
            color:#96a1b5;
            font-size:12px !important;
            line-height:1.6;
        }
        .page-home .why-card-blue { color:#3491ff; }
        .page-home .why-card-blue .why-card-icon { color:#3293ff; background:rgba(25,101,197,.22); border-color:rgba(48,144,255,.28); }
        .page-home .why-card-green { color:#14c987; }
        .page-home .why-card-green .why-card-icon { color:#17cd89; background:rgba(11,136,94,.22); border-color:rgba(22,205,137,.28); }
        .page-home .why-card-orange { color:#f39a32; }
        .page-home .why-card-orange .why-card-icon { color:#f5a23e; background:rgba(169,88,13,.25); border-color:rgba(245,154,50,.3); }

        /* Supported-platform showcase */
        .platform-showcase {
            scroll-margin-top:66px;
            padding:64px 0 72px;
            background:
                radial-gradient(circle at 50% 40%,rgba(26,60,124,.08),transparent 48%),
                linear-gradient(180deg,#080d18 0%,#060b16 100%) !important;
            border-top:1px solid rgba(255,255,255,.025);
            border-bottom:1px solid rgba(86,105,143,.16);
        }
        .platform-showcase > .wrap { width:min(1160px,calc(100% - 36px)); }
        .platform-showcase .section-head {
            max-width:700px;
            margin:0 auto 42px;
            padding:0;
        }
        .platform-showcase .section-head h2 {
            margin:0 0 7px;
            color:#f7f8fc;
            font-size:40px !important;
            font-weight:800;
            line-height:1.15;
            letter-spacing:-.8px;
        }
        .platform-showcase .section-head p {
            max-width:590px;
            margin:0 auto;
            color:#929eb4;
            font-size:16px !important;
            line-height:1.55;
        }
        .platform-showcase .platform-grid {
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:18px;
        }
        .platform-showcase .platform-card {
            position:relative;
            display:flex;
            min-height:220px;
            padding:24px 21px 21px !important;
            justify-content:flex-start;
            background:
                radial-gradient(circle at 50% 24%,color-mix(in srgb,var(--platform-accent) 25%,transparent),transparent 42%),
                linear-gradient(145deg,#101722,#0b111c) !important;
            border:1px solid color-mix(in srgb,var(--platform-accent) 45%,#243146) !important;
            border-radius:14px !important;
            box-shadow:0 0 28px color-mix(in srgb,var(--platform-accent) 10%,transparent),inset 0 1px 0 rgba(255,255,255,.025);
            transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease;
        }
        .platform-showcase .platform-card::before { height:1px; opacity:.95; }
        .platform-showcase .platform-card::after {
            top:-42px;
            left:50%;
            width:105px;
            height:105px;
            transform:translateX(-50%);
            opacity:.08;
        }
        .platform-showcase .platform-card:hover {
            transform:translateY(-3px);
            border-color:var(--platform-accent) !important;
            box-shadow:0 12px 34px rgba(0,0,0,.3),0 0 30px color-mix(in srgb,var(--platform-accent) 17%,transparent);
        }
        .platform-showcase .platform-card-top {
            display:flex;
            width:100%;
            min-height:64px;
            justify-content:center;
            align-items:flex-start;
        }
        .platform-showcase .platform-card .platform-icon {
            width:58px;
            height:58px;
            margin:0;
            border:1px solid color-mix(in srgb,var(--platform-accent) 60%,#fff 5%);
            border-radius:11px;
            background:color-mix(in srgb,var(--platform-accent) 82%,#0b1120) !important;
            box-shadow:0 7px 20px color-mix(in srgb,var(--platform-accent) 38%,transparent),inset 0 1px 0 rgba(255,255,255,.2);
        }
        .platform-showcase .platform-card .platform-icon img { width:31px; height:31px; }
        .platform-showcase .platform-arrow {
            top:0;
            right:0;
            width:29px;
            height:29px;
            color:#c8d0df;
            background:rgba(6,11,20,.62);
            border-color:#3a4659;
            font-size:13px;
        }
        .platform-showcase .platform-card-copy { width:100%; margin-top:14px; text-align:center; }
        .platform-showcase .platform-card h3 {
            margin:0 0 7px;
            color:#f5f7fb;
            font-size:21px !important;
            font-weight:800;
            line-height:1.2;
            text-align:center;
        }
        .platform-showcase .platform-card p {
            display:block !important;
            width:auto;
            max-width:210px;
            max-height:none;
            margin:0 auto;
            overflow:visible;
            color:#9aa5b8;
            font-size:13px !important;
            line-height:1.55;
            text-align:center;
            -webkit-line-clamp:unset;
            line-clamp:unset;
        }

        /* Analyzer result dashboard */
        .page-home .download-panel.has-result {
            width:min(760px,calc(100% - 24px));
            max-width:760px;
            padding:9px;
            background:
                radial-gradient(circle at 88% 22%,rgba(91,43,221,.13),transparent 30%),
                linear-gradient(145deg,rgba(9,13,35,.98),rgba(6,12,29,.98));
            border:1px solid rgba(111,69,255,.48);
            border-radius:12px;
            box-shadow:0 0 34px rgba(83,42,214,.18),0 18px 50px rgba(0,0,0,.38),inset 0 1px 0 rgba(255,255,255,.045);
        }
        .page-home .download-panel.has-result .url-form { grid-template-columns:minmax(0,1fr) 130px; gap:7px; }
        .page-home .download-panel.has-result .url-form input {
            min-height:43px;
            padding:10px 14px 10px 42px;
            border-color:#5a43c5;
            border-radius:6px;
            background:#080d26;
            box-shadow:inset 0 1px 8px rgba(0,0,0,.4),0 0 15px rgba(80,44,208,.1);
            font-size:10px;
        }
        .page-home .download-panel.has-result .url-input-wrap > svg { left:14px; width:16px; height:16px; }
        .page-home .download-panel.has-result .button {
            min-height:43px;
            border-radius:6px;
            font-size:10px;
            box-shadow:0 7px 20px rgba(104,37,230,.34),inset 0 1px 0 rgba(255,255,255,.25);
        }
        .page-home .download-panel.has-result .result {
            margin-top:8px;
            padding:0;
            overflow:visible;
            background:transparent;
            border:0;
            border-radius:0;
            box-shadow:none;
        }
        .page-home .download-panel.has-result .result-layout {
            display:grid;
            grid-template-columns:minmax(150px,28%) minmax(0,1fr);
            gap:8px;
            align-items:stretch;
        }
        .page-home .download-panel.has-result .media-summary {
            min-width:0;
            padding:9px;
            text-align:left;
            background:linear-gradient(165deg,rgba(12,19,46,.98),rgba(8,14,32,.98));
            border:1px solid #273255;
            border-radius:6px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.025);
        }
        .page-home .download-panel.has-result .media-thumb-wrap {
            overflow:hidden;
            border:1px solid rgba(81,69,177,.45);
            border-radius:6px;
            box-shadow:0 8px 22px rgba(0,0,0,.3);
        }
        .page-home .download-panel.has-result .media-thumb { aspect-ratio:1.46; border-radius:5px; }
        .page-home .download-panel.has-result .media-play {
            width:34px;
            height:34px;
            color:#131522;
            font-size:13px;
            box-shadow:0 6px 18px rgba(0,0,0,.32);
        }
        .page-home .download-panel.has-result .media-platform {
            margin:9px 0 5px;
            color:#6f8dff;
            font-size:7px;
            line-height:1.45;
        }
        .page-home .download-panel.has-result .media-title {
            margin:0;
            color:#f5f6fc;
            font-size:11px !important;
            font-weight:800;
            line-height:1.35;
        }
        .page-home .download-panel.has-result .media-duration {
            margin-top:8px;
            padding:4px 6px;
            color:#b9c7ff;
            background:rgba(69,54,164,.18);
            border:1px solid rgba(99,76,213,.35);
            border-radius:4px;
            font-size:7px;
        }
        .page-home .download-panel.has-result .format-list {
            display:flex;
            min-width:0;
            flex-direction:column;
            gap:8px;
            background:transparent;
        }
        .page-home .download-panel.has-result .format-section,
        .page-home .download-panel.has-result .format-section:nth-of-type(even) {
            padding:0;
            overflow:hidden;
            background:linear-gradient(160deg,rgba(11,17,43,.98),rgba(7,13,31,.98));
            border:1px solid #273255;
            border-radius:6px;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.024);
        }
        .page-home .download-panel.has-result .format-section + .format-section { border-top:1px solid #273255; }
        .page-home .download-panel.has-result .format-heading {
            min-height:33px;
            margin:0;
            padding:7px 10px;
            gap:7px;
            color:#f4f6fc;
            background:rgba(15,21,50,.62);
            border-bottom:1px solid #303a60;
            font-size:12px !important;
            font-weight:800;
        }
        .page-home .download-panel.has-result .format-heading-mark {
            width:19px;
            height:19px;
            color:#f1eaff;
            background:linear-gradient(145deg,#7448e5,#4c2cac);
            border-radius:4px;
            font-size:8px;
        }
        .page-home .download-panel.has-result .video-formats .format-heading-mark { color:#edeaff; }
        .page-home .download-panel.has-result .audio-formats .format-heading-mark { background:linear-gradient(145deg,#8158f0,#5232bb); }
        .page-home .download-panel.has-result .format-row {
            display:grid;
            grid-template-columns:34px 90px minmax(55px,1fr) 82px;
            min-height:32px;
            padding:5px 10px;
            align-items:center;
            gap:7px;
            background:transparent;
            border-bottom:1px solid rgba(49,59,94,.72);
        }
        .page-home .download-panel.has-result .format-row:hover { background:rgba(79,56,164,.08); }
        .page-home .download-panel.has-result .format-row:last-child { border-bottom:0; }
        .page-home .download-panel.has-result .format-badge {
            min-width:0;
            padding:4px 4px;
            color:#181109;
            background:linear-gradient(135deg,#ffc650,#f2a925);
            border-radius:3px;
            font-size:7px;
            line-height:1;
        }
        .page-home .download-panel.has-result .audio-formats .format-badge {
            color:#eaf0ff;
            background:linear-gradient(135deg,#3f72e5,#2852bd);
        }
        .page-home .download-panel.has-result .format-quality,
        .page-home .download-panel.has-result .format-size {
            color:#e9edf8;
            font-size:8px;
            font-weight:700;
            line-height:1.2;
        }
        .page-home .download-panel.has-result .format-size { color:#aeb7cb; text-align:center; }
        .page-home .download-panel.has-result .download-link {
            width:auto;
            min-width:82px;
            min-height:23px;
            margin:0;
            padding:0 8px;
            gap:5px;
            color:#eee9ff;
            background:linear-gradient(145deg,rgba(99,53,205,.72),rgba(49,30,111,.75));
            border:1px solid #7654e8;
            border-radius:4px;
            font-size:7px;
            font-weight:700;
            box-shadow:0 0 12px rgba(109,68,225,.15),inset 0 1px 0 rgba(255,255,255,.1);
        }
        .page-home .download-panel.has-result .audio-formats .download-link {
            background:linear-gradient(145deg,rgba(47,88,189,.76),rgba(29,47,111,.78));
            border-color:#3969db;
            box-shadow:0 0 12px rgba(52,98,219,.14),inset 0 1px 0 rgba(255,255,255,.08);
        }
        .page-home .download-panel.has-result .download-link:hover {
            color:#fff;
            background:linear-gradient(145deg,#8d58f2,#5a31c2);
            border-color:#a17aff;
        }
        .page-home .download-panel.has-result .download-link svg,
        .page-home .download-panel.has-result .download-link .download-icon { width:9px; height:9px; flex-basis:9px; }
        .page-home .download-panel.has-result .download-link .download-label { font-size:7px; }
        .page-home .download-panel.has-result .result-note {
            display:flex;
            min-height:23px;
            margin:8px 0 0;
            padding:5px 8px;
            align-items:center;
            gap:6px;
            color:#8792ab;
            background:rgba(9,17,37,.9);
            border:1px solid #252f4c;
            border-radius:4px;
            font-size:7px !important;
            line-height:1.35;
        }
        .page-home .download-panel.has-result .result-note svg {
            width:11px;
            height:11px;
            flex:0 0 11px;
            fill:none;
            stroke:#8e78df;
            stroke-width:1.8;
            stroke-linecap:round;
            stroke-linejoin:round;
        }

        @media (max-width:760px) {
            .page-home .how-steps-section { padding:30px 0 34px; }
            .page-home .how-steps-intro h2 { font-size:22px !important; }
            .page-home .how-steps-grid {
                grid-template-columns:1fr;
                gap:12px;
                max-width:340px;
            }
            .page-home .how-step-card { min-height:165px; padding:18px; }
            .page-home .how-step-arrow { height:18px; transform:rotate(90deg); }
            .page-home .why-choose-section { padding:28px 0 32px; }
            .page-home .why-choose-grid { grid-template-columns:repeat(2,minmax(0,1fr)); max-width:520px; }
            .page-home .why-card { min-height:150px; }
            .platform-showcase > .wrap { width:min(100% - 28px,560px); }
            .platform-showcase .platform-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
            .platform-showcase .platform-card { min-height:150px; }
            .page-home .download-panel.has-result { width:100%; }
            .page-home .download-panel.has-result .result-layout { grid-template-columns:1fr; }
            .page-home .download-panel.has-result .media-summary {
                display:grid;
                grid-template-columns:110px minmax(0,1fr);
                grid-template-rows:auto auto 1fr;
                gap:0 12px;
                border-right:1px solid #273255;
                border-bottom:1px solid #273255;
            }
            .page-home .download-panel.has-result .media-thumb-wrap { grid-row:1 / 4; }
            .page-home .download-panel.has-result .media-platform { margin-top:3px; }
            .page-home .download-panel.has-result .format-row { grid-template-columns:32px 74px minmax(48px,1fr) 76px; padding:5px 7px; gap:5px; }
            .page-home .download-panel.has-result .format-badge {
                grid-column:1;
                grid-row:1;
                align-self:center;
            }
            .page-home .download-panel.has-result .format-quality {
                grid-column:2;
                grid-row:1;
                justify-self:start;
                text-align:left;
            }
            .page-home .download-panel.has-result .format-size {
                grid-column:3;
                grid-row:1;
                min-width:0;
                justify-self:center;
                text-align:center;
            }
            .page-home .download-panel.has-result .download-link {
                grid-column:4;
                grid-row:1;
                width:auto;
                min-width:76px;
                height:auto;
                min-height:23px;
                margin:0;
                justify-self:end;
                align-self:center;
                font-size:7px;
            }
            .page-home .download-panel.has-result .download-link svg,
            .page-home .download-panel.has-result .download-link .download-icon { width:9px; height:9px; flex-basis:9px; }
            .page-home .download-panel.has-result .download-link .download-label { font-size:7px; }
        }
        @media (max-width:480px) {
            .page-home .why-choose-intro h2 { font-size:21px !important; }
            .page-home .why-choose-grid { grid-template-columns:1fr; max-width:338px; gap:9px; }
            .page-home .why-card { min-height:0; padding:16px; }
            .page-home .why-card h3 { white-space:normal; }
            .platform-showcase { padding:28px 0 32px; }
            .platform-showcase .section-head { margin-bottom:22px; }
            .platform-showcase .section-head h2 { font-size:26px !important; }
            .platform-showcase .section-head p { font-size:11px !important; }
            .platform-showcase .platform-card { min-height:142px; padding:13px 10px 11px !important; }
            .platform-showcase .platform-card h3 { font-size:14px !important; }
            .platform-showcase .platform-card p { max-width:125px; font-size:9px !important; }
            .page-home .download-panel.has-result {
                width:min(100%,390px);
                padding:8px;
                border-radius:13px;
                box-shadow:0 0 28px rgba(83,42,214,.22),0 15px 34px rgba(0,0,0,.42),inset 0 1px 0 rgba(255,255,255,.05);
            }
            .page-home .download-panel.has-result .url-form {
                grid-template-columns:1fr;
                gap:6px;
            }
            .page-home .download-panel.has-result .url-form input {
                min-height:38px;
                padding:8px 11px 8px 37px;
                font-size:8px;
            }
            .page-home .download-panel.has-result .url-input-wrap > svg { left:12px; width:13px; height:13px; }
            .page-home .download-panel.has-result .button {
                min-height:34px;
                font-size:9px;
            }
            .page-home .download-panel.has-result .button .button-arrow { display:none; }
            .page-home .download-panel.has-result .button::before {
                content:'✦';
                margin-right:6px;
                font-size:9px;
            }
            .page-home .download-panel.has-result .result { margin-top:7px; }
            .page-home .download-panel.has-result .result-layout { gap:7px; }
            .page-home .download-panel.has-result .media-summary {
                grid-template-columns:104px minmax(0,1fr);
                grid-template-rows:auto auto 1fr;
                min-height:78px;
                padding:7px;
                column-gap:9px;
                border-radius:6px;
            }
            .page-home .download-panel.has-result .media-thumb-wrap { align-self:center; }
            .page-home .download-panel.has-result .media-thumb { aspect-ratio:1.55; }
            .page-home .download-panel.has-result .media-play { width:27px; height:27px; font-size:10px; }
            .page-home .download-panel.has-result .media-platform { margin:1px 0 4px; font-size:6px; }
            .page-home .download-panel.has-result .media-title {
                display:-webkit-box;
                overflow:hidden;
                -webkit-box-orient:vertical;
                -webkit-line-clamp:2;
                font-size:9px !important;
                line-height:1.35;
            }
            .page-home .download-panel.has-result .media-duration { margin-top:5px; padding:3px 5px; font-size:6px; justify-self:start; }
            .page-home .download-panel.has-result .format-list { gap:7px; }
            .page-home .download-panel.has-result .format-heading {
                min-height:29px;
                padding:5px 7px;
                font-size:10px !important;
            }
            .page-home .download-panel.has-result .format-heading-mark { width:17px; height:17px; font-size:7px; }
            .page-home .download-panel.has-result .format-row {
                grid-template-columns:29px 56px minmax(42px,1fr) 72px;
                min-height:30px;
                padding:4px 7px;
                gap:4px;
            }
            .page-home .download-panel.has-result .format-badge { padding:4px 3px; font-size:6px; }
            .page-home .download-panel.has-result .format-quality,
            .page-home .download-panel.has-result .format-size { font-size:7px; }
            .page-home .download-panel.has-result .download-link {
                grid-column:4;
                grid-row:1;
                width:auto;
                min-width:72px;
                min-height:22px;
                height:auto;
                margin:0;
                padding:0 5px;
                justify-self:end;
            }
            .page-home .download-panel.has-result .result-note { margin-top:7px; min-height:20px; padding:4px 7px; font-size:6px !important; }
        }
        @media (max-width:360px) {
            .page-home .download-panel.has-result .media-summary { grid-template-columns:92px minmax(0,1fr); }
            .page-home .download-panel.has-result .format-row { grid-template-columns:27px 48px minmax(36px,1fr) 66px; padding-inline:5px; }
            .page-home .download-panel.has-result .download-link { min-width:66px; }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/result-dashboard.css') }}">
    @include('partials.adsense-head')
</head>
<body class="page-{{ $page }}">
    @include('partials.navbar')

    @if ($page === 'home')
        <main>
            <div class="hero">
                <span class="hero-orbit hero-orbit-left" aria-hidden="true"></span>
                <span class="hero-orbit hero-orbit-right" aria-hidden="true"></span>
                <div class="wrap hero-center">
                        <span class="eyebrow">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.2 2 5 13.1h6.2L10.8 22 19 10.9h-6.2L13.2 2Z"/></svg>
                            Browser-based · No software
                        </span>
                        @php 
                            $heroTitle = !empty($homeSettings->hero_heading) ? $homeSettings->hero_heading : ($siteSettings['hero_title'] ?? 'Analyze Public Media Links');
                            $lastSpace = strrpos($heroTitle, ' ');
                            if ($lastSpace !== false) {
                                $titleFirst = substr($heroTitle, 0, $lastSpace);
                                $titleLast = substr($heroTitle, $lastSpace + 1);
                            } else {
                                $titleFirst = $heroTitle;
                                $titleLast = '';
                            }
                            
                            $heroDesc = !empty($homeSettings->hero_description) ? $homeSettings->hero_description : ($siteSettings['hero_subtitle'] ?? 'Review formats made available by supported public sources. Use this tool only for content you own or have permission to save.');
                            $parsedDesc = $heroDesc;
                            if (strpos(trim($heroDesc), '{') === 0 && strpos($heroDesc, '"blocks"') !== false) {
                                $descData = json_decode($heroDesc, true);
                                if (isset($descData['blocks']) && is_array($descData['blocks'])) {
                                    $html = '';
                                    foreach ($descData['blocks'] as $block) {
                                        if ($block['type'] === 'paragraph' && !empty($block['data']['text'])) {
                                            $html .= $block['data']['text'] . '<br>';
                                        } elseif ($block['type'] === 'header' && !empty($block['data']['text'])) {
                                            $level = isset($block['data']['level']) ? $block['data']['level'] : 3;
                                            $html .= '<h' . $level . '>' . $block['data']['text'] . '</h' . $level . '>';
                                        } elseif ($block['type'] === 'raw' && !empty($block['data']['html'])) {
                                            $html .= $block['data']['html'];
                                        }
                                    }
                                    if ($html !== '') {
                                        $parsedDesc = $html;
                                    }
                                }
                            }
                        @endphp
                        <h1>{{ $titleFirst }} @if($titleLast)<span>{{ $titleLast }}</span>@endif</h1>
                        <div class="hero-copy">
                            {!! $parsedDesc !!}
                        </div>
                        <div class="download-panel {{ $result ? 'has-result' : '' }}">
                            <form class="url-form" method="POST" action="{{ route('analyze') }}" id="analyze-form">
                                @csrf
                                <span class="url-input-wrap">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.6 13.4a4 4 0 0 0 5.7 0l3.1-3.1a4 4 0 0 0-5.7-5.7L12 6.3"/><path d="M13.4 10.6a4 4 0 0 0-5.7 0l-3.1 3.1a4 4 0 0 0 5.7 5.7l1.7-1.7"/></svg>
                                    <input id="video-url-input" name="video_url" type="url" value="{{ old('video_url') }}" placeholder="Paste a video URL here" aria-label="Video URL" required>
                                </span>
                                <button id="analyze-btn" class="button" type="submit">
                                    <span class="download-label">{{ $result ? 'Analyze' : 'Analyze Link' }}</span>
                                    <svg class="button-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                </button>
                            </form>
                            @error('video_url')<div class="error" id="error-container">{{ $message }}</div>@else<div id="error-container" class="error" style="display:none;"></div>@enderror
                            <div id="result-container">
                                @if ($result)
                                    @include('partials.result', ['result' => $result])
                                @else

                                @endif
                            </div>
                        </div>
                        <p class="platform-prompt">Try a sample link or choose a platform</p>
                        <div class="platform-strip" aria-label="Popular supported platforms">
                            @foreach (array_slice($platforms, 0, 6) as $platform)
                                <a href="{{ route('platforms.show', $platform['slug']) }}" class="platform-pill"><span class="platform-dot" style="background:{{ $platform['accent'] }}"><img src="https://cdn.simpleicons.org/{{ $platform['icon'] }}/ffffff" alt="" loading="lazy"></span>{{ $platform['name'] }}</a>
                            @endforeach
                        </div>
                        <div class="hero-trust" aria-label="Service benefits">
                            <div class="hero-trust-item">
                                <span class="hero-trust-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg></span>
                                <span><strong>No Signup</strong><small>100% free to use</small></span>
                            </div>
                            <div class="hero-trust-item hero-trust-mobile-only">
                                <span class="hero-trust-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 18h8M10 22h4M12 2v3M4.9 4.9 7 7M19.1 4.9 17 7"/><path d="M7.5 14.5a6 6 0 1 1 9 0c-1 1-1.5 2-1.5 3h-6c0-1-.5-2-1.5-3Z"/></svg></span>
                                <span><strong>No Software</strong><small>Nothing to install</small></span>
                            </div>
                            <div class="hero-trust-item">
                                <span class="hero-trust-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m13 2-9 12h7l-1 8 9-12h-7l1-8Z"/></svg></span>
                                <span><strong>Fast Analysis</strong><small>Get results in seconds</small></span>
                            </div>
                            <div class="hero-trust-item">
                                <span class="hero-trust-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></span>
                                <span><strong>Privacy First</strong><small>We don't store your data</small></span>
                            </div>
                        </div>
                </div>
            </div>
            <section class="how-steps-section" id="how-it-works">
                <div class="wrap">
                    <div class="how-steps-intro">
                        <h2>How It Works</h2>
                        <p>Analyze any public media link in 3 simple steps</p>
                    </div>
                    <div class="how-steps-grid">
                        <article class="how-step-card">
                            <span class="how-step-number">01</span>
                            <span class="how-step-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M10.6 13.4a4 4 0 0 0 5.7 0l3.1-3.1a4 4 0 0 0-5.7-5.7L12 6.3"/><path d="M13.4 10.6a4 4 0 0 0-5.7 0l-3.1 3.1a4 4 0 0 0 5.7 5.7l1.7-1.7"/></svg>
                            </span>
                            <h3>Paste Link</h3>
                            <p>Copy and paste the public media link into the input box above.</p>
                        </article>
                        <span class="how-step-arrow" aria-hidden="true"><svg viewBox="0 0 28 16"><path d="M1 8h24M20 3l5 5-5 5"/></svg></span>
                        <article class="how-step-card">
                            <span class="how-step-number">02</span>
                            <span class="how-step-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m16.5 16.5 4 4"/></svg>
                            </span>
                            <h3>Analyze Link</h3>
                            <p>We analyze the link and fetch available formats and quality options.</p>
                        </article>
                        <span class="how-step-arrow" aria-hidden="true"><svg viewBox="0 0 28 16"><path d="M1 8h24M20 3l5 5-5 5"/></svg></span>
                        <article class="how-step-card">
                            <span class="how-step-number">03</span>
                            <span class="how-step-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                            </span>
                            <h3>Preview &amp; Download</h3>
                            <p>Preview the media and download in your preferred format and quality.</p>
                        </article>
                    </div>
                </div>
            </section>
            <section class="why-choose-section" id="why-choose" aria-labelledby="why-choose-title">
                <div class="wrap">
                    <div class="why-choose-intro">
                        <h2 id="why-choose-title">Why Choose Solution Hub?</h2>
                        <p>Built for speed, quality and a seamless experience.</p>
                    </div>
                    <div class="why-choose-grid">
                        <article class="why-card why-card-purple">
                            <span class="why-card-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="m13 2-9 12h7l-1 8 9-12h-7l1-8Z"/></svg>
                            </span>
                            <div>
                                <h3>Ultra-Fast Processing</h3>
                                <p>Our optimized servers analyze links and return results as quickly as network conditions allow.</p>
                            </div>
                        </article>
                        <article class="why-card why-card-blue">
                            <span class="why-card-icon why-card-hd" aria-hidden="true">HD</span>
                            <div>
                                <h3>High Quality</h3>
                                <p>Get the best available resolutions and audio formats from the source whenever possible.</p>
                            </div>
                        </article>
                        <article class="why-card why-card-green">
                            <span class="why-card-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                            </span>
                            <div>
                                <h3>Safe &amp; Private</h3>
                                <p>We don't require sign-up. All connections are secure and your data stays completely private.</p>
                            </div>
                        </article>
                        <article class="why-card why-card-orange">
                            <span class="why-card-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>
                            </span>
                            <div>
                                <h3>Multi-Platform Support</h3>
                                <p>Works with popular platforms like YouTube, Facebook, Instagram, TikTok, X (Twitter) and more.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
            {{-- Legacy feature/step sections replaced by the workflow above.
            <section class="legacy-how-section">
                <div class="wrap">
                    <div class="section-head"><h2>How Solution Hub works</h2><p>Analyze supported public links and review the formats reported by their source. Only save media you own or are authorized to use.</p></div>

                    <!-- Row 1 -->
                    <div class="bento-grid">

                        <!-- Card 1: Ultra-Fast Speeds (wide) -->
                        <div class="bento-card">
                            <div class="bento-circle" aria-hidden="true"></div>
                            <div>
                                <div class="bento-icon-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                                <h3>Ultra-Fast Speeds</h3>
                                <p>Our service analyzes supported public links and returns the formats reported by the source as quickly as network conditions allow.</p>
                            </div>
                        </div>

                        <!-- Card 2: High Quality (narrow, highlighted) -->
                        <div class="bento-card hl">
                            <div>
                                <div class="bento-icon-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                </div>
                                <h3>High Quality</h3>
                                <p>View the resolutions and audio options actually reported by the source. Specific formats are not guaranteed.</p>
                            </div>
                            <div class="bento-chips">
                                <span class="bento-chip">1080p</span>
                                <span class="bento-chip">Available HD</span>
                                <span class="bento-chip">Source dependent</span>
                            </div>
                        </div>

                    </div>

                    <!-- Row 2 -->
                    <div class="bento-grid-r2">

                        <!-- Card 3: Safe & Private (narrow) -->
                        <div class="bento-card">
                            <div>
                                <div class="bento-icon-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <h3>Safe &amp; Private</h3>
                                <p>No registration is required for basic link analysis. Submitted links are processed over encrypted HTTPS connections; avoid submitting private or sensitive URLs.</p>
                            </div>
                        </div>

                        <!-- Card 4: Universal Compatibility (wide) -->
                        <div class="bento-card">
                            <div class="bento-globe" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </div>
                            <div>
                                <div class="bento-icon-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                </div>
                                <h3>Universal Compatibility</h3>
                                <p>Works on any modern browser across Windows, macOS, Android, and iOS. No apps or extensions needed.</p>
                            </div>
                            <div class="bento-devices" aria-label="Supported devices">
                                <svg viewBox="0 0 24 24" title="Desktop"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                <svg viewBox="0 0 24 24" title="Mobile"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                                <svg viewBox="0 0 24 24" title="Tablet"><rect x="2" y="4" width="20" height="12" rx="2" ry="2"/><line x1="2" y1="20" x2="22" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/></svg>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <section class="steps-section">
                <div class="wrap">
                    <div class="section-head"><h2>Analyze links in 3 simple steps</h2><p>No account, complicated settings, or software installation required.</p></div>
                    <div class="steps-flow">

                        <!-- Step 1: Copy Link -->
                        <div class="step">
                            <div class="step-icon-box" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            </div>
                            <h3>1. Copy Link</h3>
                            <p>Find the public media page you want to review and copy its URL from the browser's address bar.</p>
                        </div>

                        <!-- Step 2: Paste URL -->
                        <div class="step">
                            <div class="step-icon-box" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            </div>
                            <h3>2. Paste URL</h3>
                            <p>Paste the link into the URL input field above and our engine will analyze the content immediately.</p>
                        </div>

                        <!-- Step 3: Download -->
                        <div class="step">
                            <div class="step-icon-box" aria-hidden="true">
                                <svg class="download-icon" viewBox="0 0 24 24"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg>
                            </div>
                            <h3>3. Review formats</h3>
                            <p>Choose a detected resolution or format only for media you own or have permission to use.</p>
                        </div>

                    </div>
                </div>
            </section>
            --}}
            @include('partials.platforms')
            
            @if(isset($homeSettings) && (!empty($homeSettings->sites_heading) || !empty($homeSettings->sites_description)))
            <section class="seo-content-section">
                <div class="seo-content-wrap">
                    @if(!empty($homeSettings->sites_heading))
                        <h2 class="seo-heading">{{ $homeSettings->sites_heading }}</h2>
                    @endif
                    
                    @if(!empty($homeSettings->sites_description))
                        <div class="seo-text-container" id="seoTextContainer">
                            <div class="seo-text-content">
                                @php
                                    $descData = json_decode($homeSettings->sites_description, true);
                                    if (json_last_error() === JSON_ERROR_NONE && isset($descData['blocks'])) {
                                        foreach ($descData['blocks'] as $block) {
                                            if ($block['type'] === 'paragraph' && !empty($block['data']['text'])) {
                                                echo '<p>' . $block['data']['text'] . '</p>';
                                            } elseif ($block['type'] === 'header' && !empty($block['data']['text'])) {
                                                $level = isset($block['data']['level']) ? $block['data']['level'] : 3;
                                                echo '<h' . $level . '>' . $block['data']['text'] . '</h' . $level . '>';
                                            } elseif ($block['type'] === 'raw' && !empty($block['data']['html'])) {
                                                echo $block['data']['html'];
                                            }
                                        }
                                    } else {
                                        // Fallback for plain text
                                        $lines = explode("\n", str_replace("\r", "", $homeSettings->sites_description));
                                        foreach($lines as $line) {
                                            if(trim($line) !== '') echo '<p>' . e($line) . '</p>';
                                        }
                                    }
                                @endphp
                            </div>
                        </div>
                        <button class="seo-read-more" id="seoReadMoreBtn">Read More</button>
                    @endif
                </div>
            </section>
            @endif

            <section class="home-faq-section" id="faq">
                <div class="wrap">
                    <div class="section-head"><h2>{{ $homeSettings->faq_h1 ?? 'Frequently asked questions' }}</h2><p>{{ $homeSettings->faq_description ?? 'Quick answers about using Solution Hub.' }}</p></div>
                    <div class="faq">
                        @if(isset($faqs) && $faqs->count() > 0)
                            @foreach($faqs as $faq)
                                <details name="faq_accordion"><summary>{{ $faq->question }}</summary><p>{!! nl2br(e($faq->answer)) !!}</p></details>
                            @endforeach
                        @else
                            <details name="faq_accordion"><summary>Do I need to install an app?</summary><p>No. The analyzer runs in your browser on mobile and desktop devices.</p></details>
                            <details name="faq_accordion"><summary>Which video qualities are available?</summary><p>Available formats depend on the source and may include SD, 720p, 1080p, and audio-only options.</p></details>
                            <details name="faq_accordion"><summary>Is Solution Hub available online?</summary><p>Yes, the basic public-link analysis experience runs in the browser.</p></details>
                            <details name="faq_accordion"><summary>Can I use any video?</summary><p>Only use content you own or have permission to save, and follow the source platform's terms.</p></details>
                        @endif
                    </div>
                </div>
            </section>
            @include('partials.blog')
        </main>
    @elseif ($page === 'platforms')
        <main>
            <div class="platforms-page-hero">
                <div class="wrap">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><span>Supported Platforms</span>
                    </nav>
                    <span class="blog-badge">Integrations</span>
                    <h1>Analyze Public <span>Media Links</span></h1>
                    <p>Solution Hub helps review source-dependent formats from supported public social networks and video platforms.</p>
                </div>
            </div>
            @include('partials.platforms')
        </main>
    @elseif ($page === 'blog')
        <main>
            <div class="blog-page-hero">
                <div class="wrap">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><span>Blog</span>
                    </nav>
                    <span class="blog-badge">Blogs</span>
                    <h1>Insights, <span>Formats</span> &<br><span>Responsible Use</span></h1>
                    <p>Explore practical guides about public links, media formats, compatibility, quality, and responsible use.</p>
                </div>
            </div>
            @include('partials.blog')
        </main>
    @elseif ($page === 'blog-post')
        <main class="article-page">
            <article>
                <header class="article-hero">
                    <div class="wrap article-hero-grid">
                        <div>
                            <nav class="breadcrumb" aria-label="Breadcrumb">
                                <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><a href="{{ route('blog') }}">Blog</a><span class="breadcrumb-separator">/</span><span>{{ \Illuminate\Support\Str::limit($post['title'], 42) }}</span>
                            </nav>
                            <a class="article-back" href="{{ route('blog') }}">← All guides</a>
                            <div class="article-meta"><span>{{ $post['category'] }}</span><time>{{ $post['published'] }}</time><span>{{ $post['read'] }}</span></div>
                            <h1>{{ $post['title'] }}</h1>
                            <p>{{ $post['excerpt'] }}</p>
                        </div>
                        <img src="{{ asset($post['image']) }}" alt="{{ $post['title'] }}" width="960" height="540">
                    </div>
                </header>
                <div class="wrap article-layout">
                    <div class="article-body">
                        <div class="article-content">{!! $post['content'] !!}</div>
                        <div class="article-callout"><strong>Important:</strong> Use only content you own or have permission to save. Public availability does not automatically grant permission to republish or redistribute a video.</div>
                        <h2>Use Solution Hub</h2>
                        <p>Paste a supported public link into the analyzer, review the formats detected from the source, and choose the option that fits your device. Solution Hub does not require an account for the basic link-analysis flow.</p>
                        <a class="article-cta" href="{{ route('home') }}">Open link analyzer -></a>
                    </div>
                    <aside class="article-aside">
                        <strong>In this guide</strong>
                        <a href="#">What you should know</a>
                        <a href="#">Practical recommendations</a>
                        <a href="#">Quality and formats</a>
                        <a href="{{ route('home') }}">Try the analyzer</a>
                    </aside>
                </div>
            </article>
            <section class="related-section"><div class="wrap"><div class="section-head"><h2>Related guides</h2><p>Continue learning about video formats, quality, and safer downloads.</p></div><div class="grid blog-grid">@foreach($relatedPosts as $related)<article class="post-card"><a class="post-cover" href="{{ route('blog.show', $related['slug']) }}"><img src="{{ asset($related['image']) }}" alt="{{ $related['title'] }}" loading="lazy"></a><div class="post-content"><div class="post-meta"><span>{{ $related['category'] }}</span><span>{{ $related['read'] }}</span></div><h3>{{ $related['title'] }}</h3><p>{{ $related['excerpt'] }}</p><a class="read" href="{{ route('blog.show', $related['slug']) }}">Read guide →</a></div></article>@endforeach</div></div></section>
        </main>
    @elseif ($page === 'privacy')
        <main class="{{ $page === 'contact' ? 'contact-page' : '' }}">
            <div class="privacy-hero {{ $page === 'contact' ? 'contact-hero' : '' }}">
                <div class="wrap">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><span>Privacy</span>
                    </nav>
                    <span class="blog-badge">Legal</span>
                    <h1>Privacy <span>Policy</span></h1>
                    <p>Learn how we handle your data and respect your privacy at Solution Hub.</p>
                </div>
            </div>
            <section class="privacy-content-wrap {{ $page === 'contact' ? 'contact-content-wrap' : '' }}">
                <div class="privacy-box {{ $page === 'contact' ? 'contact-shell' : '' }}">
                    <h2>Our Commitment</h2>
                    <p>Solution Hub is designed as a link analysis and download interface. We do not ask users to create an account for the basic paste-link flow, ensuring your activity remains mostly anonymous.</p>
                    
                    <h2>Data Processing</h2>
                    <p>Submitted URLs may be processed temporarily to detect the platform and available formats. Do not paste private, sensitive, or unauthorized links. We do not store or keep records of the specific media links you review.</p>
                    
                    <h2>User Responsibilities</h2>
                    <p>By using our service, you agree to the following:</p>
                    <ul>
                        <li>Respect the terms of service of the respective platforms you use.</li>
                        <li>Acknowledge and respect the rights of content creators.</li>
                        <li>Abide by the copyright laws applicable in your country.</li>
                    </ul>
                    <p>We provide the tool; you are responsible for how you use any media or format information.</p>
                </div>
            </section>
        </main>
    @elseif ($page === 'terms')
        <main>
            <div class="privacy-hero">
                <div class="wrap">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><span>Terms</span>
                    </nav>
                    <span class="blog-badge">Legal</span>
                    <h1>Terms of <span>Service</span></h1>
                    <p>Please read these terms carefully before using Solution Hub.</p>
                </div>
            </div>
            <section class="privacy-content-wrap">
                <div class="privacy-box">
                    <h2>Acceptance of Terms</h2>
                    <p>By accessing and using Solution Hub, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to abide by these terms, please do not use our service.</p>
                    
                    <h2>Use of Service</h2>
                    <p>Our service is provided "as is" and helps you analyze supported public media links. You are solely responsible for the media you access and how it is used.</p>
                    <ul>
                        <li>You may not use our service for any illegal or unauthorized purpose.</li>
                        <li>You must not transmit any worms, viruses, or any code of a destructive nature.</li>
                        <li>We reserve the right to modify or terminate the service for any reason, without notice at any time.</li>
                    </ul>
                    
                    <h2>Intellectual Property Rights</h2>
                    <p>We do not host third-party media through our service. All rights to videos, audio, and images belong to their respective owners. Users must obtain permission from the copyright holder before saving or distributing copyrighted material.</p>
                </div>
            </section>
        </main>
    @elseif ($page === 'disclaimer')
        <main>
            <div class="privacy-hero">
                <div class="wrap">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><span>Disclaimer</span>
                    </nav>
                    <span class="blog-badge">Legal</span>
                    <h1>Legal <span>Disclaimer</span></h1>
                    <p>Important legal information regarding the use of Solution Hub.</p>
                </div>
            </div>
            <section class="privacy-content-wrap">
                <div class="privacy-box">
                    <h2>General Disclaimer</h2>
                    <p>Solution Hub is a utility tool designed to help users analyze supported public media links. We do not host, store, or distribute copyrighted third-party material on our servers.</p>
                    
                    <h2>No Affiliation</h2>
                    <p>Solution Hub is an independent service and is not affiliated, associated, authorized, endorsed by, or in any way officially connected with any of the social media platforms (such as YouTube, Facebook, Instagram, TikTok, etc.) whose links are processed by our tool.</p>
                    
                    <h2>Limitation of Liability</h2>
                    <p>Under no circumstances shall Solution Hub be liable for any direct, indirect, incidental, consequential, special, or exemplary damages arising out of or in connection with your access or use of or inability to access or use the application and any third-party content and services.</p>
                    <p>Use this service at your own risk. It is the user's responsibility to ensure that any saved or reused content complies with local laws and the terms of service of the host platform.</p>
                </div>
            </section>
        </main>
    @elseif (in_array($page, ['about', 'contact', 'dmca'], true))
        <main class="{{ $page === 'contact' ? 'contact-page' : '' }}">
            <div class="privacy-hero {{ $page === 'contact' ? 'contact-hero' : '' }}">
                <div class="wrap">
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a><span class="breadcrumb-separator">/</span><span>{{ strtoupper($page) === 'DMCA' ? 'DMCA' : ucfirst($page) }}</span>
                    </nav>
                    <span class="blog-badge">{{ $page === 'about' ? 'Company' : ($page === 'contact' ? 'Support' : 'Legal') }}</span>
                    <h1>
                        @if($page === 'about') About <span>Solution Hub</span>
                        @elseif($page === 'contact') Contact <span>Us</span>
                        @else DMCA &amp; <span>Copyright</span>
                        @endif
                    </h1>
                    <p>
                        @if($page === 'about') Learn why we built this public-link utility and the standards that guide it.
                        @elseif($page === 'contact') Help us understand your question so it can reach the right part of the website.
                        @else Information for copyright owners and authorized representatives.
                        @endif
                    </p>
                </div>
            </div>
            <section class="privacy-content-wrap {{ $page === 'contact' ? 'contact-content-wrap' : '' }}">
                <div class="privacy-box {{ $page === 'contact' ? 'contact-shell' : '' }}">
                    @if($page === 'about')
                        <h2>Our Purpose</h2>
                        <p>Solution Hub provides a browser-based interface for analyzing supported public media links and showing formats made available by the source. The basic tool does not require an account or software installation.</p>
                        <h2>Responsible Use</h2>
                        <p>We encourage people to use only content they own, content for which they have permission, or content whose license permits saving. Public availability does not by itself grant permission to copy or redistribute a work.</p>
                        <h2>Our Standards</h2>
                        <ul><li>Clear information about supported sources and available formats.</li><li>No misleading action buttons or forced browser notifications.</li><li>Privacy-conscious processing over encrypted HTTPS connections.</li><li>A published process for copyright and legal concerns.</li></ul>
                    @elseif($page === 'contact')
                        <div class="contact-intro">
                            <span class="contact-kicker">Talk to our team</span>
                            <h2>Choose how you would like to reach us</h2>
                            <p>For technical questions, privacy enquiries, copyright concerns, and website feedback, use one of the verified support channels below.</p>
                        </div>

                        <div class="contact-methods">
                            <a class="contact-method-card" href="mailto:support@solutionhub.digital">
                                <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/></svg></span>
                                <span class="contact-method-copy"><small>Email support</small><strong>support@solutionhub.digital</strong><span>Send us a detailed message</span></span>
                                <span class="contact-arrow" aria-hidden="true">↗</span>
                            </a>
                            <a class="contact-method-card" href="tel:+447308208926">
                                <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6.6 3h3l1.5 4-2 1.7a15 15 0 0 0 6.2 6.2l1.7-2 4 1.5v3c0 2-1.6 3.6-3.6 3.6C9.4 21 3 14.6 3 6.6 3 4.6 4.6 3 6.6 3Z"/></svg></span>
                                <span class="contact-method-copy"><small>Telephone</small><strong>+44 7308 208926</strong><span>Speak with our support team</span></span>
                                <span class="contact-arrow" aria-hidden="true">↗</span>
                            </a>
                        </div>

                        <div class="contact-support-grid">
                            <article class="contact-info-card">
                                <span class="info-card-number">01</span><div><h2>Technical support</h2><p>Include the platform, device, browser, exact error, and approximate time. Confirm the source link opens publicly before contacting us.</p></div>
                            </article>
                            <article class="contact-info-card">
                                <span class="info-card-number">02</span><div><h2>Privacy &amp; copyright</h2><p>Review our <a href="{{ route('privacy') }}">Privacy Policy</a>. Rights holders can follow the requirements in our <a href="{{ route('dmca') }}">DMCA Policy</a>.</p></div>
                            </article>
                            <article class="contact-info-card">
                                <span class="info-card-number">03</span><div><h2>Website feedback</h2><p>Share the exact page, what you expected, and what happened. Screenshots are welcome when they contain no private information.</p></div>
                            </article>
                            <article class="contact-info-card">
                                <span class="info-card-number">04</span><div><h2>Stay secure</h2><p>Never send passwords, login cookies, private media, payment details, or account credentials through any support channel.</p></div>
                            </article>
                        </div>

                        <div class="contact-response-note"><span class="response-dot"></span><div><strong>Response guidance</strong><p>Keep follow-up messages in the same email thread. Complete enquiries help us investigate and respond more efficiently.</p></div></div>
                    @else
                        <h2>Copyright Policy</h2>
                        <p>Solution Hub does not claim ownership of third-party media. Users must comply with applicable copyright law, source-platform terms, and the permissions attached to each work.</p>
                        <h2>Submitting a Notice</h2>
                        <p>A complete notice should identify the copyrighted work, identify the affected Solution Hub page or URL, provide the rights holder's contact information, state a good-faith belief that the disputed use is not authorized, and confirm under penalty of perjury that the information is accurate and that the sender is authorized to act.</p>
                        <h2>Review Process</h2>
                        <p>We review complete notices and may disable relevant access or links when appropriate. Incomplete or unrelated requests may require additional information. Misrepresenting infringement may carry legal consequences.</p>
                        <h2>Important Scope</h2>
                        <p>If the material is hosted by a third-party platform, contact that platform as well. Removing a page or link from this website does not remove the original material from a third-party service.</p>
                    @endif
                </div>
            </section>
            @if($page === 'contact')
                <style>
                    .contact-page{background:#090d13}.contact-hero{padding:72px 0 68px}.contact-hero p{max-width:620px}.contact-content-wrap{max-width:1180px;padding:58px 24px 110px}.contact-shell{max-width:none;padding:0;background:transparent;border:0;box-shadow:none;color:#aeb8c6}.contact-intro{max-width:720px;margin-bottom:30px}.contact-kicker{display:inline-block;margin-bottom:12px;color:#8b5cf6;font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.contact-shell .contact-intro h2{margin:0 0 12px;color:#fff;font-size:clamp(26px,3vw,38px);line-height:1.2}.contact-intro p{max-width:680px;margin:0;font-size:16px!important;line-height:1.7}.contact-methods{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-bottom:24px}.contact-method-card{position:relative;display:flex;align-items:center;gap:18px;min-width:0;padding:24px;border:1px solid rgba(255,255,255,.09);border-radius:20px;background:linear-gradient(145deg,#171e27,#11171f);color:#fff;text-decoration:none;box-shadow:0 18px 50px rgba(0,0,0,.2);transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease}.contact-method-card:hover{transform:translateY(-3px);border-color:rgba(139, 92, 246,.45);box-shadow:0 24px 60px rgba(0,0,0,.3)}.contact-icon{display:grid;place-items:center;flex:0 0 54px;width:54px;height:54px;border-radius:16px;background:rgba(139, 92, 246,.12);color:#8b5cf6}.contact-icon svg{width:25px;height:25px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}.contact-method-copy{display:flex;min-width:0;flex-direction:column}.contact-method-copy small{margin-bottom:4px;color:#7f8b99;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.contact-method-copy strong{overflow-wrap:anywhere;color:#fff;font-size:17px;line-height:1.35}.contact-method-copy>span{margin-top:5px;color:#8e99a7;font-size:13px}.contact-arrow{margin-left:auto;color:#8b5cf6;font-size:22px}.contact-support-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.contact-info-card{display:flex;gap:18px;min-height:190px;padding:26px;border:1px solid rgba(255,255,255,.075);border-radius:20px;background:#11171f}.info-card-number{display:grid;place-items:center;flex:0 0 42px;width:42px;height:42px;border-radius:13px;background:#202a35;color:#8b5cf6;font-size:12px;font-weight:900}.contact-shell .contact-info-card h2{margin:4px 0 10px;color:#fff;font-size:19px!important;line-height:1.3}.contact-info-card p{margin:0;color:#909baa;font-size:14px!important;line-height:1.75}.contact-info-card a{color:#8b5cf6;text-decoration:underline;text-underline-offset:3px}.contact-response-note{display:flex;align-items:flex-start;gap:14px;margin-top:18px;padding:20px 22px;border:1px solid rgba(139, 92, 246,.16);border-radius:16px;background:rgba(139, 92, 246,.055)}.response-dot{flex:0 0 10px;width:10px;height:10px;margin-top:7px;border-radius:50%;background:#8b5cf6;box-shadow:0 0 0 6px rgba(139, 92, 246,.1)}.contact-response-note strong{color:#fff}.contact-response-note p{margin:3px 0 0;color:#929daa;font-size:14px!important;line-height:1.6}@media(max-width:760px){.contact-hero{padding:52px 16px 46px}.contact-content-wrap{padding:38px 16px 72px}.contact-methods,.contact-support-grid{grid-template-columns:1fr}.contact-method-card{padding:20px}.contact-info-card{min-height:0;padding:22px}.contact-method-copy strong{font-size:15px}.contact-arrow{display:none}}@media(max-width:420px){.contact-method-card{align-items:flex-start;gap:14px}.contact-icon{flex-basis:46px;width:46px;height:46px;border-radius:13px}.contact-method-copy strong{font-size:14px}.contact-info-card{display:block}.info-card-number{margin-bottom:16px}}
                </style>
            @endif
        </main>
    @endif

    @include('partials.footer')
    <script>
        function startFileDownload(url) {
            var a = document.createElement('a');
            a.href = url;
            a.download = '';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        document.addEventListener('click', async function (event) {
            // Let .direct-download links behave normally (native browser download)
            
            var prepareButton = event.target.closest('.prepare-download');
            if (!prepareButton || prepareButton.disabled) return;

            var originalHtml = prepareButton.innerHTML;
            prepareButton.disabled = true;
            prepareButton.classList.add('is-loading');
            prepareButton.innerHTML = 'Preparing...';

            try {
                var response = await fetch(prepareButton.dataset.prepareUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Preparation failed');
                var data = await response.json();
                if (!data.download_url) throw new Error('Format unavailable');
                startFileDownload(data.download_url);
                prepareButton.innerHTML = 'Started';
            } catch (error) {
                prepareButton.innerHTML = 'Try again';
            }

            window.setTimeout(function () {
                prepareButton.innerHTML = originalHtml;
                prepareButton.disabled = false;
                prepareButton.classList.remove('is-loading');
            }, 3000);
        });

        // AJAX Form Submission & Auto-Fetch
        const analyzeForm = document.getElementById('analyze-form');
        const urlInput = document.getElementById('video-url-input');
        const analyzeBtn = document.getElementById('analyze-btn');
        const resultContainer = document.getElementById('result-container');
        const errorContainer = document.getElementById('error-container');
        
        async function fetchResult(url) {
            if (!url || !/^https?:\/\//i.test(url)) return;
            if (analyzeBtn) {
                analyzeBtn.disabled = true;
                analyzeBtn.innerHTML = '<svg style="display:inline-block;vertical-align:middle;margin-right:8px;animation:spin 1s linear infinite" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg><span>Analyzing...</span>';
            }
            if (errorContainer) {
                errorContainer.style.display = 'none';
                errorContainer.innerText = '';
            }
            
            const defaultNote = document.getElementById('default-note');
            if (defaultNote) defaultNote.style.display = 'none';
            
            if (resultContainer) {
                resultContainer.innerHTML = `
                    <div class="loader-container result-fade-in">
                        <div class="spinner"></div>
                        <div class="loader-text">Analyzing link and fetching formats...</div>
                    </div>
                `;
            }
            document.querySelector('.download-panel').classList.add('has-result');
            
            try {
                const formData = new FormData(analyzeForm);
                formData.set('video_url', url);
                
                const response = await fetch("{{ route('analyze') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    if (resultContainer) resultContainer.innerHTML = `<div class="result-fade-in">${data.html}</div>`;
                } else {
                    if (errorContainer) {
                        errorContainer.innerText = data.error || 'Failed to retrieve video data.';
                        errorContainer.style.display = 'block';
                    }
                    document.querySelector('.download-panel').classList.remove('has-result');
                }
            } catch (error) {
                if (errorContainer) {
                    errorContainer.innerText = 'An error occurred while connecting to the server.';
                    errorContainer.style.display = 'block';
                }
                document.querySelector('.download-panel').classList.remove('has-result');
            } finally {
                if (analyzeBtn) {
                    analyzeBtn.disabled = false;
                    analyzeBtn.innerHTML = '<svg class="download-icon" style="display:inline-block;vertical-align:middle;margin-right:8px;flex-shrink:0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg><span>Analyze</span>';
                }
            }
        }

        if (analyzeForm && urlInput) {
            analyzeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchResult(urlInput.value.trim());
            });
            
            let typingTimer;
            urlInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                const val = this.value.trim();
                if (val && /^https?:\/\//i.test(val)) {
                    typingTimer = setTimeout(function() {
                        fetchResult(val);
                    }, 600); // 600ms debounce
                }
            });
            
            urlInput.addEventListener('paste', function(e) {
                setTimeout(function() {
                    const val = urlInput.value.trim();
                    if (val && /^https?:\/\//i.test(val)) {
                        fetchResult(val);
                    }
                }, 100);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // FAQ Accordion logic
            const faqs = document.querySelectorAll('.faq details');
            faqs.forEach(faq => {
                faq.addEventListener('toggle', function(e) {
                    if (this.open) {
                        faqs.forEach(otherFaq => {
                            if (otherFaq !== this) {
                                  otherFaq.removeAttribute('open');
                            }
                        });
                    }
                });
            });
        });

        // ===== SEO Read More Toggle =====
        var readMoreBtn = document.getElementById('seoReadMoreBtn');
        var textContent = document.querySelector('.seo-text-content');
        if (readMoreBtn && textContent) {
            if (textContent.children.length > 2) {
                readMoreBtn.classList.add('visible');
                readMoreBtn.addEventListener('click', function() {
                    textContent.classList.toggle('expanded');
                    if (textContent.classList.contains('expanded')) {
                        readMoreBtn.textContent = 'Read Less';
                    } else {
                        readMoreBtn.textContent = 'Read More';
                        // Scroll back to top of the content smoothly
                        const y = textContent.getBoundingClientRect().top + window.pageYOffset - 100;
                        window.scrollTo({top: y, behavior: 'smooth'});
                    }
                });
            }
        }
    </script>
</body>
</html>
