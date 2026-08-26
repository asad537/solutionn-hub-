<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.google-tag')
    @include('partials.favicons')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->meta_title ?: $blog->title . ' — Video Saver Blog' }}</title>
    <meta name="description" content="{{ $blog->meta_description }}">
    @php $articleUrl = route(($type ?? 'blog') . '.show', $blog->slug); @endphp
    <link rel="canonical" href="{{ $articleUrl }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Solution Hub">
    <meta property="og:title" content="{{ $blog->meta_title ?: $blog->title . ' | Solution Hub' }}">
    <meta property="og:description" content="{{ $blog->meta_description }}">
    <meta property="og:url" content="{{ $articleUrl }}">
    <meta property="og:image" content="{{ $blog->featured_image ? asset($blog->featured_image) : asset('images/logo-hafiz.svg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->meta_title ?: $blog->title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description }}">
    <meta name="twitter:image" content="{{ $blog->featured_image ? asset($blog->featured_image) : asset('images/logo-hafiz.svg') }}">
    <meta name="keywords" content="{{ $blog->meta_keywords }}">
    <meta name="robots" content="{{ $blog->meta_robots ?: 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1' }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @if($blog->schema)
        @if(str_contains(strtolower($blog->schema), '<script'))
            {!! $blog->schema !!}
        @else
            <script type="application/ld+json">
                {!! $blog->schema !!}
            </script>
        @endif
    @else
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        '@id' => $articleUrl . '#article',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $articleUrl],
        'headline' => $blog->title,
        'description' => $blog->meta_description,
        'image' => $blog->featured_image ? asset($blog->featured_image) : asset('images/logo-hafiz.svg'),
        'datePublished' => optional($blog->created_at)->toAtomString(),
        'dateModified' => optional($blog->updated_at)->toAtomString(),
        'author' => ['@type' => 'Organization', 'name' => 'Solution Hub'],
        'publisher' => [
            '@type' => 'Organization',
            '@id' => 'https://solutionhub.digital/#organization',
            'name' => 'Solution Hub',
            'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo-hafiz.svg')],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    <style>
        :root {
            --primary: #FFB800;
            --primary-dark: #E6A600;
            --text-main: #1A1A2E;
            --text-muted: #64748B;
            --bg-light: #F8FAFC;
            --border: #E8EDF2;
            --radius: 14px;
            --text-dark: #111827;
            --bg-off: #F9FAFB;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            color: var(--text-main);
            background: #fff;
            line-height: 1.6;
        }

        /* ── Header CSS ── */
        .site-header {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 0.8rem 0;
            background: #FFB800;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .header-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 800;
            font-size: 1.1rem;
        }

        .logo img { height: 65px; width: auto; border-radius: 8px; }

        .nav-wrap { display: flex; align-items: center; gap: 1.5rem; line-height: 1.45; }

        .nav-links { display: flex; gap: 1.5rem; list-style: none; }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: var(--primary-dark); }

        .lang-dropdown {
            background: #FFB800;
            border: 2px solid rgba(255, 255, 255, 0.5);
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            position: relative;
            z-index: 1000;
            color: #000;
        }

        .lang-menu {
            position: absolute;
            top: 110%;
            right: 0;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            width: 150px;
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 1001;
        }

        .lang-menu div {
            padding: 10px 15px;
            font-size: 0.85rem;
            color: var(--text-dark);
            transition: background 0.2s;
            cursor: pointer;
        }

        .lang-menu div:hover { background: var(--bg-off); color: var(--primary); }

        .hamburger { display: none; }

        .mobile-nav { display: none; }

        @media (max-width: 768px) {
            .nav-wrap { display: none; line-height: 1.45; }
            .hamburger {
                display: flex;
                flex-direction: column;
                gap: 5px;
                background: none;
                border: none;
                cursor: pointer;
                padding: 5px;
            }
            .hamburger span {
                display: block;
                width: 22px;
                height: 2px;
                background: var(--text-dark);
                border-radius: 2px;
            }
            .lang-dropdown-mobile { display: flex; }
        }

        .lang-dropdown-mobile { display: none; }

        /* ── Main 2-col layout ── */
        .post-wrapper {
            max-width: 1100px;
            margin: 2.5rem auto 4rem;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* ══ LEFT SIDEBAR ══ */
        .post-sidebar {
            position: sticky;
            top: 100px;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .sidebar-widget {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .sidebar-widget-title {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
        }

        /* Popular guides list */
        .popular-list {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .popular-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .popular-item:hover { background: #F8FAFC; }

        .pop-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pop-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pop-info { flex: 1; min-width: 0; }

        .pop-info h5 {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pop-info span {
            font-size: 0.68rem;
            color: #000;
            font-weight: 500;
        }

        /* Download widget */
        .download-widget {
            text-align: center;
            background: #FFFBF0;
            border: 1px solid rgba(255,184,0,0.25);
        }

        .download-widget .dl-icon {
            width: 44px;
            height: 44px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            margin: 0 auto 0.8rem;
        }

        .download-widget h4 {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.3rem;
        }

        .download-widget p {
            font-size: 0.72rem;
            color: #000;
            margin-bottom: 0.8rem;
            line-height: 1.45;
        }

        .dl-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-dl {
            padding: 0.4rem 0.9rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-app {
            background: var(--primary);
            color: #fff; line-height: 1.45; }

        .btn-app:hover { background: var(--primary-dark); }

        .btn-web {
            background: #fff;
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-web:hover { background: #F1F5F9; }

        /* ══ RIGHT MAIN CONTENT ══ */
        .post-main { min-width: 0; }

        /* Post title */
        .post-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.3;
            letter-spacing: -0.02em;
            margin-bottom: 1.2rem;
        }

        /* Featured image */
        .featured-img-wrap {
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1rem;
            background: #F1F5F9;
            aspect-ratio: 16/9; line-height: 1.45; }

        .featured-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Post meta */
        .post-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.82rem;
            color: #000;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .post-meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .post-meta-item i { color: var(--primary); font-size: 0.8rem; }

        /* Table of Contents */
        .toc-box {
            background: #FAFAFA;
            border: 1px solid var(--border);
            border-left: 3px solid var(--primary);
            border-radius: var(--radius);
            padding: 1.2rem 1.5rem;
            margin-bottom: 2rem;
        }

        .toc-box h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.8rem;
        }

        .toc-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .toc-list li a {
            font-size: 0.82rem;
            color: #000;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
            font-weight: 500;
        }

        .toc-list li a::before {
            content: '›';
            color: var(--primary);
            font-weight: 800;
            font-size: 1rem;
        }

        .toc-list li a:hover { color: var(--primary-dark); }

        /* Editor content */
        .post-content {
            font-size: 1rem;
            color: #334155;
            line-height: 1.85;
        }

        .post-content h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 2rem 0 0.8rem;
            letter-spacing: -0.01em;
        }

        .post-content h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 1.5rem 0 0.6rem;
        }

        .post-content p { margin-bottom: 1.2rem; line-height: 1.45; }

        .post-content ul, .post-content ol {
            padding-left: 1.5rem;
            margin-bottom: 1.2rem;
        }

        .post-content li { margin-bottom: 0.4rem; }

        .post-content blockquote {
            border-left: 4px solid var(--primary);
            background: #FFFBF0;
            padding: 1rem 1.5rem;
            border-radius: 0 8px 8px 0;
            margin: 1.5rem 0;
            font-style: italic;
        }

        .post-content code {
            background: #F1F5F9;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.875rem;
            color: #E11D48;
            font-family: monospace;
        }

        /* Tags */
        .post-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .tag-pill {
            background: #F1F5F9;
            color: #475569;
            padding: 0.35rem 0.8rem;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .tag-pill:hover {
            background: rgba(255,184,0,0.12);
            color: var(--primary-dark);
        }

        /* ══ RELATED SECTION (bottom) ══ */
        .related-section {
            background: var(--bg-light);
            padding: 3.5rem 0 4rem;
            margin-top: 3rem;
        }

        .related-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .related-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .related-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .related-header a {
            font-size: 0.85rem;
            color: var(--primary-dark);
            font-weight: 700;
            text-decoration: none;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .related-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .related-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 35px rgba(0,0,0,0.07);
            border-color: rgba(255,184,0,0.3);
        }

        .related-card-img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
        }

        .related-card-body { padding: 1rem 1.1rem 1.2rem; flex: 1; }

        .related-card-body h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.4;
            margin-bottom: 0.4rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-card-body span {
            font-size: 0.75rem;
            color: #000;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .post-wrapper { 
                grid-template-columns: 1fr;
                display: flex;
                flex-direction: column;
            }
            .post-sidebar { 
                position: static;
                order: 2; /* Sidebar neeche */
            }
            .post-main {
                order: 1; /* Main content pehle */
            }
            .related-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 600px) {
            .post-title { font-size: 1.4rem; }
            .related-grid { grid-template-columns: 1fr; }
        }
    </style>

    @include('partials.adsense-head')
</head>
<body class="has-fixed-header">
    @include('partials.navbar')

    @include('partials.header', ['headerClass' => 'header-white'])

    <div class="post-wrapper">

        {{-- ══ LEFT SIDEBAR ══ --}}
        <aside class="post-sidebar">

            {{-- Popular Guides --}}
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Popular Guides</div>
                <div class="popular-list">
                    @foreach($related as $item)
                    <a href="{{ route($type . '.show', $item->slug) }}" class="popular-item">
                        <div class="pop-avatar">
                            @if($item->featured_image)
                                <img src="{{ $item->featured_image }}" alt="{{ $item->title }}" loading="lazy">
                            @else
                                {{ strtoupper(substr($item->title, 0, 1)) }}
                            @endif
                        </div>
                        <div class="pop-info">
                            <h5>{{ Str::limit($item->title, 45) }}</h5>
                            <span>{{ $item->created_at->format('M d, Y') }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Download Your Way widget --}}
            <div class="sidebar-widget download-widget">
                <div class="dl-icon"><i class="fas fa-download"></i></div>
                <h4>Download Your Way</h4>
                <p style="line-height: 1.45;">Choose how you want to download and start using the app.</p>
                <div class="dl-buttons">
                    <a href="/" class="btn-dl btn-app">Via App</a>
                    <a href="/" class="btn-dl btn-web">Via Web</a>
                </div>
            </div>

        </aside>

        {{-- ══ MAIN CONTENT ══ --}}
        <main class="post-main">

            {{-- Title --}}
            <h1 class="post-title">{{ $blog->title }}</h1>

            {{-- Featured Image --}}
            @if($blog->featured_image)
            <div class="featured-img-wrap">
                <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}">
            </div>
            @endif

            {{-- Meta --}}
            <div class="post-meta">
                <div class="post-meta-item">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ $blog->author_name ?: 'Admin' }}</span>
                </div>
                <div class="post-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                </div>
                @if($blog->reading_time)
                <div class="post-meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $blog->reading_time }}</span>
                </div>
                @endif
            </div>

            {{-- Table of Contents (auto-generated from headings) --}}
            <div class="toc-box" id="toc-box" style="display:none;">
                <h4>Table of Contents</h4>
                <ul class="toc-list" id="toc-list"></ul>
            </div>

            {{-- EditorJS Content --}}
            <div class="post-content" id="post-body">
                {!! $blog->renderContent() !!}
            </div>

        </main>
    </div>

    {{-- ══ RELATED POSTS (full width bottom) ══ --}}
    @if(isset($related) && count($related) > 0)
    <section class="related-section">
        <div class="related-inner">
            <div class="related-header">
                <h2>Related Articles</h2>
                <a href="/help-center/?resource={{ $type }}">View all {{ $type === 'guide' ? 'guides' : 'posts' }} →</a>
            </div>
            <div class="related-grid">
                @foreach($related as $post)
                <a href="{{ route($type . '.show', $post->slug) }}" class="related-card">
                    <img src="{{ $post->featured_image ?: '/images/placeholder-blog.jpg' }}" alt="{{ $post->title }}" class="related-card-img" loading="lazy">
                    <div class="related-card-body">
                        <h4>{{ $post->title }}</h4>
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('partials.footer')

    <!-- Google Translate Scripts -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,ar,ur,hi,es,fr,pt,ko,tr,vi,ru,it',
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript"
        src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>

    {{-- Header JS --}}
    <script>
        function toggleLangMenu() {
            const menu = document.getElementById('lang-menu');
            if (menu) menu.style.display = (menu.style.display === 'flex') ? 'none' : 'flex';
        }
        function toggleLangMenuMobile() {
            const menu = document.getElementById('lang-menu-mobile');
            if (menu) menu.style.display = (menu.style.display === 'flex') ? 'none' : 'flex';
        }
        function changeLanguage(langCode) {
            const select = document.querySelector('.goog-te-combo');
            if (select) {
                let actualLang = select.value || 'en';
                if (actualLang === langCode) {
                    const menu = document.getElementById('lang-menu');
                    if (menu) menu.style.display = 'none';
                    const menuMobile = document.getElementById('lang-menu-mobile');
                    if (menuMobile) menuMobile.style.display = 'none';
                    return;
                }

                if (langCode === 'en') {
                    select.value = '';
                } else {
                    select.value = langCode;
                }
                select.dispatchEvent(new Event('change'));
                
                const langNames = {
                    'en': 'English', 'ar': 'Arabic', 'ur': 'Urdu',
                    'hi': 'Hindi', 'es': 'Spanish', 'fr': 'French', 'pt': 'Portuguese', 'ko': 'Korean', 'tr': 'Turkish', 'vi': 'Vietnamese', 'ru': 'Russian', 'it': 'Italian'
                };

                // Save to localStorage
                localStorage.setItem('selectedLanguage', langCode);
                localStorage.setItem('selectedLanguageName', langNames[langCode]);

                if (langCode === 'en') {
                    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + location.host;
                    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + location.hostname.split('.').slice(-2).join('.');
                }

                const currentLangEl = document.getElementById('current-lang');
                if (currentLangEl) currentLangEl.innerText = langNames[langCode];
                
                const currentLangMobileEl = document.getElementById('current-lang-mobile');
                if (currentLangMobileEl) currentLangMobileEl.innerText = langNames[langCode];
            } else {
                console.log("Retrying translation...");
                setTimeout(() => changeLanguage(langCode), 300);
            }
            const menu = document.getElementById('lang-menu');
            if (menu) menu.style.display = 'none';
            const menuMobile = document.getElementById('lang-menu-mobile');
            if (menuMobile) menuMobile.style.display = 'none';
        }
        function toggleMobileMenu() {
            const nav = document.getElementById('mobile-nav');
            const overlay = document.getElementById('mobile-overlay');
            const btn = document.getElementById('hamburger');
            
            if (nav && overlay) {
                nav.classList.toggle('open');
                overlay.classList.toggle('open');
                if (btn) btn.classList.toggle('open');
                
                // Prevent body scroll when menu is open
                if (nav.classList.contains('open')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }
        window.onclick = function(event) {
            if (!event.target.closest('.lang-dropdown')) {
                const menu = document.getElementById('lang-menu');
                if (menu) menu.style.display = 'none';
            }
        }
        
        // Auto-Hide Google Translate Banner
        setInterval(function () {
            const banner = document.querySelector('.goog-te-banner-frame');
            if (banner) banner.remove();
            document.body.style.top = '0px';
        }, 500);
    </script>

    {{-- Auto-build Table of Contents from h2/h3 tags --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('post-body');
            const tocList = document.getElementById('toc-list');
            const tocBox = document.getElementById('toc-box');
            if (!body || !tocList) return;

            const headings = body.querySelectorAll('h2, h3');
            if (headings.length < 2) return;

            tocBox.style.display = 'block';

            headings.forEach(function (h, i) {
                const id = 'heading-' + i;
                h.id = id;
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = '#' + id;
                a.textContent = h.textContent;
                if (h.tagName === 'H3') a.style.paddingLeft = '0.8rem';
                li.appendChild(a);
                tocList.appendChild(li);
            });
        });
    </script>

</body>
</html>

