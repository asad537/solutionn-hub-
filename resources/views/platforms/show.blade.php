<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.google-tag')
    <!-- Preload hero image for instant LCP -->
    <link rel="preload" as="image" href="/images/supporteds.webp" type="image/webp" fetchpriority="high">
    @include('partials.favicons')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $platform->meta_title ?: $platform->name . ' - Video Saver' }}</title>
    <meta name="description" content="{{ $platform->meta_description }}">
    <link rel="canonical" href="{{ route('platforms.show', $platform->slug) }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Solution Hub">
    <meta property="og:title" content="{{ $platform->meta_title ?: $platform->name . ' Public Link Format Guide | Solution Hub' }}">
    <meta property="og:description" content="{{ $platform->meta_description ?: 'Analyze public ' . $platform->name . ' links and review source-dependent media formats.' }}">
    <meta property="og:url" content="{{ route('platforms.show', $platform->slug) }}">
    <meta property="og:image" content="{{ asset('images/logo-hafiz.svg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $platform->meta_title ?: $platform->name . ' Public Link Format Guide' }}">
    <meta name="twitter:description" content="{{ $platform->meta_description ?: 'Analyze public ' . $platform->name . ' links and review source-dependent media formats.' }}">
    <meta name="twitter:image" content="{{ asset('images/logo-hafiz.svg') }}">
    @if($platform->meta_keywords)
    <meta name="keywords" content="{{ $platform->meta_keywords }}">
    @endif
    <meta name="robots" content="{{ $platform->meta_robots ?: 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- JSON-LD Schemas for Platform Page -->
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
      "description": "Solution Hub is a browser-based public media link analyzer that helps users review source-dependent formats for links they own or have permission to use."
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
      "description": "Solution Hub is a browser-based public media link analyzer that helps users review source-dependent formats for links they own or have permission to use.",
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
          "name": "{{ $platform->name }} Public Link Guide",
          "item": "{{ route('platforms.show', $platform->slug) }}"
        }
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ $platform->name }} Public Link Format Guide",
      "alternateName": [
        "Solution Hub",
        "HD Video DL",
        "HDVDownloader"
      ],
      "description": "Analyze supported public {{ $platform->name }} links and review media formats made available by the source.",
      "url": "{{ route('platforms.show', $platform->slug) }}",
      "isPartOf": {
        "@type": "WebSite",
        "@id": "https://solutionhub.digital/#website",
        "url": "https://solutionhub.digital/"
      },
      "publisher": {
        "@id": "https://solutionhub.digital/#organization"
      }
    }
    </script>
    @if(count($faqs) > 0)
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        '@id' => route('platforms.show', $platform->slug) . '#faq',
        'name' => 'Frequently Asked Questions - ' . $platform->name,
        'url' => route('platforms.show', $platform->slug),
        'mainEntity' => collect($faqs)->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => strip_tags($faq->question),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($faq->answer),
            ],
        ])->values()->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    <style>
        :root {
            --primary: #8b5cf6;
            --primary-glow: rgba(139, 92, 246,0.15);
            --text-dark: #f8fafc;
            --text-gray: #a0aaba;
            --bg-dark: #090c11;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-dark);
            overflow-x: hidden;
            top: 0 !important;
        }

        /* ── Dark Hero ── */
        .platform-hero {
            background:
                radial-gradient(circle at 20% 50%, rgba(139, 92, 246,0.12) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(96,165,250,0.08) 0%, transparent 35%),
                linear-gradient(180deg, #0d1219 0%, #090c11 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 80px 0 90px;
            text-align: center;
        }
        .platform-hero-wrap {
            max-width: 760px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .platform-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(139, 92, 246,0.08);
            border: 1px solid rgba(139, 92, 246,0.25);
            color: #8b5cf6;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 7px 16px;
            border-radius: 999px;
            margin-bottom: 1.5rem;
        }
        .platform-hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.2rem;
        }
        .platform-hero p {
            font-size: 1.1rem;
            color: #a0aaba;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ── Dark Downloader & Trust Bar ── */
        .download-panel-wrap { margin-top: -30px; position: relative; z-index: 10; margin-bottom: 50px; }
        .download-panel {
            max-width: 900px; margin: 0 auto; padding: 10px;
            border: 1px solid rgba(255,255,255,0.06); border-radius: 18px;
            background: rgba(13,17,23,0.98);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.03);
        }
        .download-panel.has-result { max-width: 1120px; }
        .url-form { display: grid; gap: 8px; grid-template-columns: 1fr; }
        .url-form input {
            min-height: 62px; padding: 18px 20px;
            border: 1px solid #324155; border-radius: 12px;
            background: #16202c; color: #f8fafc;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            font-size: 17px;
        }
        .url-form input::placeholder { color: #8292a8; }
        .url-form input:hover { background: #1b2635; border-color: #40526b; }
        .url-form input:focus { color: #fff; background: #16202c; border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139, 92, 246,0.15); outline: none; }
        @keyframes resultFadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes spin { to { transform:rotate(360deg); } }
        .result-fade-in { animation:resultFadeIn .4s ease-out both; }
        .loader-container { margin-top:20px; padding:60px 20px; text-align:center; background:#0c1117; border:1px solid #2a3440; border-radius:12px; }
        .spinner { width:40px; height:40px; margin:0 auto 20px; border:4px solid rgba(139, 92, 246,.2); border-left-color:#8b5cf6; border-radius:50%; animation:spin 1s linear infinite; }
        .loader-text { color:#a0aaba; font-size:16px; font-weight:600; }
        .result { margin-top:14px; padding:0; overflow:hidden; text-align:left; color:#edf1f6; background:#0c1117; border:1px solid #2a3440; border-radius:14px; box-shadow:0 8px 24px rgba(0,0,0,.2); }
        .result-layout { display:grid; grid-template-columns:minmax(230px,.72fr) minmax(0,1.55fr); }
        .media-summary { padding:24px; background:#0e141b; border-right:1px solid #29323e; }
        .media-thumb-wrap { position:relative; }
        .media-thumb { width:100%; aspect-ratio:16/9; display:block; object-fit:cover; background:#171e27; border-radius:10px; }
        .media-play { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:66px; height:66px; display:grid; place-items:center; color:#040813; background:rgba(255,255,255,.94); border-radius:50%; font-size:25px; box-shadow:0 18px 45px rgba(0,0,0,.28); }
        .media-platform { margin:18px 0 8px; color:#3767d8; font-size:12px; font-weight:800; text-transform:uppercase; }
        .media-title { margin:0; color:#f4f7fa; font-size:18px !important; line-height:1.55; }
        .media-duration { display:inline-flex; margin-top:14px; padding:8px 11px; color:#668de7; background:rgba(139, 92, 246,.1); border:1px solid rgba(139, 92, 246,.15); border-radius:8px; font-size:14px; font-weight:800; }
        .format-section + .format-section { border-top:1px solid #29323e; }
        .format-heading { display:flex; align-items:center; gap:10px; margin:0; padding:20px 24px; color:#edf1f6; background:#10161e; border-bottom:1px solid #29323e; font-size:21px !important; }
        .format-heading-mark { width:32px; height:32px; display:grid; place-items:center; color:#060f24; background:#2d5fd4; border-radius:8px; font-size:13px; }
        .format-row { display:grid; grid-template-columns:90px minmax(90px,1fr) minmax(90px,.8fr) auto; align-items:center; gap:12px; min-height:82px; padding:14px 24px; background:#0c1117; border-bottom:1px solid #29323e; }
        .format-row:last-child { border-bottom:0; }
        .format-row:hover { background:#111820; }
        .format-badge { justify-self:start; min-width:58px; padding:8px 9px; color:#251702; background:linear-gradient(135deg,#ffc45b,#f09b27); border-radius:8px; text-align:center; font-weight:800; }
        .format-quality { color:#edf1f6; font-weight:800; }
        .format-size { color:#9aa5b5; font-weight:800; }
        .download-link { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-width:142px; min-height:48px; padding:0 16px; color:#a78bfa; background:#0d1018; border:2px solid #2455c9; border-radius:10px; font-weight:800; font:inherit; line-height:1; white-space:nowrap; flex-shrink:0; text-decoration:none; cursor:pointer; }
        .download-link:hover { color:#030814; background:#a78bfa; }
        .download-link.is-loading { color:#7d8796; background:#171c23; border-color:#36404d; cursor:wait; }
        .download-link svg, .download-link .download-icon { width:20px; height:20px; flex:0 0 20px; }
        .download-label { font:inherit; font-weight:inherit; line-height:1; display:inline-block; }
        .empty-formats { padding:24px; color:#9aa5b5; }
        .result-note { margin:0; padding:14px 24px; color:#7e899a; background:#10161d; border-top:1px solid #29323e; font-size:12px !important; }
        @media (max-width:860px) { .result-layout{grid-template-columns:1fr;} .media-summary{border-right:0;border-bottom:1px solid #29323e;} }
        @media (max-width:620px) {
            .format-row{grid-template-columns:56px minmax(0,1fr) auto; gap:6px 8px; padding:12px 14px 14px; align-items:center;}
            .format-badge{grid-column:1; grid-row:1; align-self:center;}
            .format-quality{grid-column:2; grid-row:1; min-width:0; line-height:1.05; text-align:center; justify-self:center; font-size:15px; font-weight:800;}
            .format-size{grid-column:3; grid-row:1; min-width:max-content; white-space:nowrap; font-size:12px; line-height:1; overflow-wrap:normal; text-align:center; justify-self:center; color:#9aa6b4;}
            .download-link{grid-column:1 / -1; grid-row:2; min-width:0; width:100%; height:42px; padding:0 12px; font-size:0; white-space:nowrap; justify-self:stretch; justify-content:center; margin-top:6px; border-radius:10px;}
            .download-link svg, .download-link .download-icon{width:20px;height:20px;flex-basis:20px;}
            .format-heading{padding:17px 14px;}
        }
        .button {
            min-width: 165px; min-height: 62px; border-radius: 12px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: #040813; font-weight: 800; font-size: 16px;
            box-shadow: 0 14px 34px rgba(124, 58, 237,0.22), inset 0 1px 0 rgba(255,255,255,0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #5c86e9, #2053c9);
            transform: translateY(-1px); box-shadow: 0 18px 40px rgba(124, 58, 237,0.32);
        }
        .note { color: #7f8a9b; text-align: center; margin-top: 15px; font-size: 13px; }
        
        .platform-strip {
            display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 12px; margin: 34px auto 0;
        }
        .platform-pill {
            display: flex; align-items: center; gap: 8px; padding: 9px 14px;
            border: 1px solid rgba(255,255,255,0.09); border-radius: 999px;
            background: rgba(15,20,27,0.82); color: #c3cad5; font-size: 13px; font-weight: 700;
            box-shadow: 0 10px 28px rgba(0,0,0,0.22), inset 0 1px 0 rgba(255,255,255,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s, border-color 0.2s, color 0.2s;
        }
        .platform-pill:hover {
            color: #fff; border-color: rgba(139, 92, 246,0.3); background: #151c24;
            transform: translateY(-2px); box-shadow: 0 14px 34px rgba(0,0,0,0.32);
        }
        .platform-dot { width: 28px; height: 28px; display: grid; place-items: center; flex: 0 0 28px; border-radius: 50%; }
        .platform-dot img { width: 15px; height: 15px; display: block; object-fit: contain; }

        .trust-bar {
            border-top: 1px solid #202733; border-bottom: 1px solid #202733;
            background: #0d1117; color: var(--text-dark);
            margin-bottom: 60px;
        }
        .trust-grid { display: grid; grid-template-columns: repeat(3, 1fr); max-width: 1100px; margin: 0 auto; }
        .trust-item { padding: 22px; text-align: center; border-right: 1px solid #202733; }
        .trust-item:last-child { border-right: 0; }
        .trust-item strong { display: block; margin-bottom: 5px; font-size: 15px; color: #fff; }
        .trust-item span { color: #a0aaba; font-size: 13px; }
        
        @media (max-width:860px) {
            .trust-grid { grid-template-columns: 1fr; }
            .trust-item { border-right: 0; border-bottom: 1px solid #202733; padding: 18px 10px; }
            .trust-item:last-child { border-bottom: 0; }
        }
        @media (min-width: 620px) {
            .url-form { grid-template-columns: 1fr auto; }
        }
        @media (max-width: 620px) {
            .platform-strip { justify-content: flex-start; overflow-x: auto; flex-wrap: nowrap; padding: 0 2px 8px; scrollbar-width: none; }
            .platform-strip::-webkit-scrollbar { display: none; }
            .platform-pill { flex: 0 0 auto; }
        }



        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            width: 100%;
            height: 35vw;
            /* Height scales with width to maintain aspect ratio */
            min-height: 450px;
            max-height: 650px;
            display: flex;
            align-items: center;
            padding: 2vw 0;
            overflow: hidden;
            background-color: #fff5f6;
        }

        /* BG image — Exactly like homepage */
        .hero-bg-img {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center right;
            z-index: 0;
        }

        .hero picture {
            display: block;
            line-height: 0;
        }

        .hero-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            max-width: 45%;
            margin-top: 110px;
        }

        .hero h1 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            color: #111827;
            margin-bottom: 2rem;
        }

        .hero-subtext {
            font-size: 1.05rem;
            color: #111827;
            line-height: 1.5;
            margin-bottom: 2.5rem;
            max-width: 600px;
            font-weight: 500;
        }

        .hero-mobile-title,
        .hero-mobile-actions {
            display: none;
        }

        @media (max-width: 1024px) {
            .hero {
                text-align: center;
                padding: 8rem 0 300px 0;
                height: auto;
                min-height: 600px;
            }

            .hero-bg-img {
                object-position: center bottom;
                object-fit: contain;
            }

            .hero-content {
                max-width: 100%;
            }

            .hero h1 {
                font-size: 2.2rem;
            }
        }

        /* ── Search Section ── */
        @media (max-width: 768px) {
            .hero {
                display: block;
                height: auto;
                min-height: 0;
                max-height: none;
                padding: 0 0 1.35rem;
                margin-top: 84px;
                overflow: hidden;
                background: #fff;
                text-align: center;
            }

            .hero-bg-img {
                position: relative;
                top: auto;
                right: auto;
                display: block;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center top;
                z-index: 0;
            }

            .hero picture {
                height: clamp(500px, 102vw, 590px);
                overflow: hidden;
            }

            .hero-container {
                max-width: 620px;
                padding: 0 2rem;
            }

            .hero-content {
                max-width: 100%;
                margin: 1.25rem auto 0;
            }

            .hero-kicker,
            .hero-desktop-title {
                display: none !important;
            }

            .hero-mobile-title,
            .hero-mobile-actions {
                display: block;
            }

            .hero h1 {
                font-size: clamp(2rem, 5.9vw, 2.35rem);
                font-weight: 800;
                line-height: 1.2;
                color: #000;
                margin: 0 auto 1.25rem;
                max-width: 500px;
                text-align: center;
            }

            .hero-subtext {
                font-size: clamp(1.12rem, 4.1vw, 1.5rem);
                line-height: 1.55;
                color: #000;
                max-width: 530px;
                margin: 0 auto 1.65rem;
                font-weight: 400;
                text-align: center;
            }

            .hero-mobile-actions {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: clamp(0.85rem, 3vw, 1.25rem);
                width: 100%;
                margin: 0 auto;
            }

            .hero-mobile-pill {
                min-height: 50px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.55rem;
                padding: 0.75rem 0.65rem;
                border-radius: 999px;
                background: #fff;
                color: #000;
                font-size: clamp(0.95rem, 3.2vw, 1.13rem);
                font-weight: 800;
                line-height: 1;
                box-shadow: 0 5px 14px rgba(15, 23, 42, 0.13);
                white-space: nowrap;
            }

            .hero-mobile-pill i {
                color: #FFB800;
                font-size: clamp(1.05rem, 3.6vw, 1.3rem);
            }
        }

        @media (max-width: 430px) {
            .hero {
                margin-top: 78px;
            }

            .hero-container {
                padding: 0 1.45rem;
            }

            .hero-content {
                margin-top: 1.05rem;
            }

            .hero-subtext {
                margin-bottom: 1.35rem;
            }

            .hero-mobile-actions {
                gap: 0.65rem;
            }

            .hero-mobile-pill {
                min-height: 46px;
                padding: 0.65rem 0.4rem;
                gap: 0.42rem;
            }
        }

        /* ── Platform Content ── */
        .content-section {
            padding: 0px 0 2rem;
            background: #090c11; /* Match page bg */
        }
        
        .editor-content {
            max-width: 820px;
            margin: 0 auto;
            color: #f8fafc !important;
            font-size: 1.05rem;
            line-height: 1.8;
            padding: 0 1.5rem;
        }

        @media (max-width: 768px) {
            .editor-content {
                padding: 0 1rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .editor-content {
                padding: 0 0.75rem;
            }
        }

        .editor-content h2 {
            margin: 42px 0 14px;
            color: #ffffff !important;
            font-size: 28px;
            line-height: 1.25;
            font-weight: 800;
        }

        .editor-content > h1:first-child,
        .editor-content > h2:first-child,
        .editor-content > h3:first-child,
        .editor-content > p:first-child { margin-top: 0; }

        .editor-content h3 {
            margin: 32px 0 14px;
            color: #ffffff !important;
            font-size: 22px;
            line-height: 1.3;
            font-weight: 700;
        }

        .editor-content p {
            margin: 0 0 20px;
            color: #e2e8f0 !important;
        }

        .editor-content ul, .editor-content ol {
            display: grid;
            gap: 12px;
            margin: 22px 0 30px;
            padding: 0;
            list-style: none;
        }

        .editor-content li {
            position: relative;
            padding: 16px 18px 16px 48px;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px;
            color: #e2e8f0;
            background: rgba(255,255,255,0.03);
            margin: 0;
        }

        .editor-content li::before {
            content: '✓';
            position: absolute;
            left: 18px;
            top: 15px;
            color: #8b5cf6;
            font-weight: 900;
        }
        
        .editor-content a {
            color: #8b5cf6;
            text-decoration: none;
        }
        
        .editor-content a:hover {
            text-decoration: underline;
        }


        /* --- Shared UI CSS --- */

        .why-choose-section {
            padding: 2rem 0 12px;
            background: #ffffff;
        }

        .why-choose-container {
            max-width: 1050px;
        }

        .why-choose-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .why-choose-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0F0F0F;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .why-choose-desc {
            font-size: 0.93rem;
            color: #000;
            max-width: 620px;
            margin: 0 auto;
            line-height: 1.75;
            text-align: center !important;
        }

        .why-choose-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.2rem;
        }

        .why-choose-card {
            background: #ffffff;
            border: 1.5px solid #FFB800;
            border-radius: 18px;
            padding: 1.5rem 1.8rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .why-choose-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(255, 184, 0, 0.15);
        }

        .why-choose-icon {
            width: 60px;
            height: 60px;
            background: #FEF3C7;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .why-choose-icon img {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        .why-choose-card span.why-choose-card-title {
            display: block;
            font-size: 1.2rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 0.45rem;
            text-align: left !important;
        }

        .why-choose-card p {
             font-size: 1rem !important;
            color: #000;
            line-height: 1.45;
            margin: 0;
            text-align: left !important;
        }

        @media (max-width: 991px) {
            .why-choose-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            html, body { max-width:100%; overflow-x:hidden; }
            .download-panel-wrap { width:100%; padding:0 12px; box-sizing:border-box; }
            .download-panel { width:100%; padding:9px; border-radius:14px; box-sizing:border-box; }
            .url-form { grid-template-columns:1fr; }
            .url-form input, .button { min-height:54px; border-radius:10px; }
            .platform-strip { justify-content:flex-start; overflow-x:auto; flex-wrap:nowrap; padding:0 2px 8px; scrollbar-width:none; }
            .platform-strip::-webkit-scrollbar { display:none; }
            .platform-strip a, .platform-pill { flex:0 0 auto; }
            .result { margin-top:14px; border-radius:22px; background:#0d1117; border-color:#27313c; }
            .result-layout { grid-template-columns:1fr; }
            .media-summary { padding:18px 18px 20px; text-align:center; background:#0f151c; border-right:0; border-bottom:1px solid #27313c; }
            .media-thumb-wrap { overflow:hidden; border-radius:16px; box-shadow:0 18px 44px rgba(0,0,0,.28); }
            .media-thumb { border-radius:16px; }
            .media-play { width:58px; height:58px; font-size:21px; }
            .media-platform { margin-top:18px; font-size:11px; }
            .media-title { max-width:96%; margin:0 auto; color:#f8fafc; font-size:24px !important; line-height:1.25; font-weight:900; text-align:center; }
            .media-duration { margin-top:14px; padding:8px 12px; color:#030814; background:#8b5cf6; border:0; border-radius:8px; font-size:14px; }
            .format-list { background:#0d1117; }
            .format-section { padding:18px 0 6px; }
            .format-heading { gap:12px; padding:0 18px 14px; color:#f8fafc; background:transparent; border-bottom:1px solid #27313c; font-size:24px !important; font-weight:900; }
            .format-row { grid-template-columns:56px minmax(0,1fr) auto; gap:6px 8px; min-height:0; padding:12px 16px 14px; background:#0d1117; border-bottom:1px solid #1f2832; align-items:center; }
            .format-badge { grid-column:1; grid-row:1 / span 2; min-width:auto; padding:4px 8px; color:#030814; background:#8b5cf6; border-radius:6px; font-size:12px; align-self:center; }
            .format-quality { grid-column:2; grid-row:1; color:#f8fafc; font-size:15px; font-weight:800; text-align:center; min-width:0; line-height:1.05; justify-self:center; }
            .format-size { grid-column:2; grid-row:2; padding-right:0; color:#9da8b7; font-size:12px; text-align:center; min-width:0; line-height:1; white-space:nowrap; justify-self:center; }
            .download-link { grid-column:1 / -1; grid-row:3; width:100%; min-width:0; min-height:42px; padding:0 12px; color:#8b5cf6; background:transparent; border:1px solid #8b5cf6; border-radius:10px; font-size:12px; margin-top:6px; justify-content:center; white-space:nowrap; }
            .download-arrow { display:inline-block; margin-right:4px; font-size:14px; }
            .result-note { padding:14px 18px; text-align:center; }

            .why-choose-section {
                padding: 2rem 0;
            }

            .why-choose-header {
                margin-bottom: 1.4rem;
                text-align: left;
            }

            .why-choose-title {
                font-size: 1.35rem;
                margin-bottom: 0.6rem;
            }

            .why-choose-desc {
                max-width: 100%;
                margin: 0;
                font-size: 0.92rem;
                line-height: 1.6;
                text-align: left !important;
            }

            .why-choose-grid {
                grid-template-columns: 1fr;
                gap: 0.95rem;
            }

            .why-choose-card {
                border-radius: 16px;
                padding: 1.25rem 1.2rem;
            }
        }
    </style>
<style>
    .platform-hero { padding:56px 20px 72px !important; }
    .platform-hero-badge { margin-bottom:18px; }
    .platform-hero h1 { max-width:980px; margin:0 auto 18px; font-size:42px !important; line-height:1.12; letter-spacing:-1px; }
    .platform-hero p { max-width:760px; margin:0 auto; font-size:16px !important; line-height:1.6; }

    @media (max-width:768px) {
        .platform-hero { padding:64px 16px 56px !important; min-height:auto; }
        .platform-hero-wrap { width:100%; max-width:100%; }
        .platform-hero-badge { margin-bottom:20px; padding:7px 13px; font-size:11px; letter-spacing:.08em; }
        .platform-hero h1 { max-width:560px; margin:0 auto 16px; font-size:clamp(32px,8.5vw,44px) !important; line-height:1.08; letter-spacing:-1px; overflow-wrap:normal; word-break:normal; }
        .platform-hero p { max-width:560px; margin:0 auto; font-size:15px !important; line-height:1.65; }
    }

    @media (max-width:430px) {
        .platform-hero { padding:48px 14px 46px !important; }
        .platform-hero-badge { font-size:10px; padding:6px 11px; }
        .platform-hero h1 { font-size:clamp(28px,9vw,36px) !important; line-height:1.1; letter-spacing:-.7px; }
        .platform-hero p { font-size:14px !important; line-height:1.6; }
    }
</style>
    <link rel="stylesheet" href="{{ asset('css/result-dashboard.css') }}">
    @include('partials.adsense-head')
</head>

<body>
    @include('partials.navbar')

    <!-- Dark Header — matches homepage style -->
    <!-- Hero Section — dark homepage style -->
    <section class="platform-hero">
        <div class="platform-hero-wrap">
            <span class="platform-hero-badge"><i class="fas fa-rocket"></i> Supported Platforms</span>
            <h1>{{ $platform->h1 ?: $platform->name . ' Public Link Format Guide' }}</h1>
            <p>{{ $platform->description ?: 'Analyze supported public ' . $platform->name . ' links and review source-dependent video and audio formats.' }}</p>
        </div>
    </section>
    <div class="download-panel-wrap">
        <div class="download-panel {{ isset($result) && $result ? 'has-result' : '' }}">
            <form class="url-form" method="POST" action="{{ route('analyze') }}" id="analyze-form">
                @csrf
                <span class="url-input-wrap">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.6 13.4a4 4 0 0 0 5.7 0l3.1-3.1a4 4 0 0 0-5.7-5.7L12 6.3"/><path d="M13.4 10.6a4 4 0 0 0-5.7 0l-3.1 3.1a4 4 0 0 0 5.7 5.7l1.7-1.7"/></svg>
                    <input id="video-url-input" name="video_url" type="url" value="{{ old('video_url') }}" placeholder="Paste a video URL here" aria-label="Video URL" required>
                </span>
                <button id="analyze-btn" class="button" type="submit">
                    <span>Analyze</span>
                    <svg class="button-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </form>
            @error('video_url')<div class="error" id="error-container" style="color:#ef4444;text-align:center;margin-top:10px;">{{ $message }}</div>@else<div id="error-container" class="error" style="display:none;color:#ef4444;text-align:center;margin-top:10px;"></div>@enderror
            <div id="result-container">
                @if (isset($result) && $result)
                    @include('partials.result', ['result' => $result])
                @else

                @endif
            </div>
        </div>

        <div class="platform-strip" aria-label="Popular supported platforms">
            @php
                $platformIcons = [
                    ['name' => 'YouTube', 'accent' => '#ff3b30', 'icon' => 'youtube'],
                    ['name' => 'Facebook', 'accent' => '#1877f2', 'icon' => 'facebook'],
                    ['name' => 'Instagram', 'accent' => '#e1306c', 'icon' => 'instagram'],
                    ['name' => 'TikTok', 'accent' => '#0041d8', 'icon' => 'tiktok'],
                    ['name' => 'Twitter / X', 'accent' => '#111827', 'icon' => 'x'],
                    ['name' => 'Vimeo', 'accent' => '#1a58ea', 'icon' => 'vimeo'],
                ];
            @endphp
            @foreach ($platformIcons as $p)
                <span class="platform-pill">
                    <span class="platform-dot" style="background:{{ $p['accent'] }}">
                        <img src="https://cdn.simpleicons.org/{{ $p['icon'] }}/ffffff" alt="" loading="lazy" onerror="this.style.display='none'">
                    </span>
                    {{ $p['name'] }}
                </span>
            @endforeach
        </div>
    </div>

    <div class="trust-bar">
        <div class="trust-grid">
            <div class="trust-item"><strong>Fast link analysis</strong><span>Get available formats in seconds</span></div>
            <div class="trust-item"><strong>No installation</strong><span>Works directly in your browser</span></div>
            <div class="trust-item"><strong>HD quality</strong><span>Choose the best available resolution</span></div>
        </div>
    </div>
    @if(!empty($platform->content))
        <section class="content-section">
            <div class="container">
                <div class="editor-content" id="platformContent">
                    {!! App\Models\Blog::renderEditorJS($platform->content) !!}
                    <div class="read-more-wrapper" style="text-align:left; margin-top: 2rem;">
                        <button id="readMoreBtn" class="platform-read-more">Read More</button>
                    </div>
                </div>
            </div>
        </section>
        
        <style>
            .platform-read-more {
                background: linear-gradient(135deg, #8b5cf6, #6d28d9);
                color: #040813;
                border: none;
                padding: 10px 24px;
                border-radius: 30px;
                font-weight: 800;
                font-size: 14px;
                cursor: pointer;
                transition: transform 0.2s, box-shadow 0.2s;
                box-shadow: 0 8px 24px rgba(139, 92, 246,0.25);
                display: none;
            }
            .platform-read-more:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 30px rgba(139, 92, 246,0.35);
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const content = document.getElementById('platformContent');
                const btn = document.getElementById('readMoreBtn');
                
                if (content) {
                    const children = Array.from(content.children).filter(child => !child.classList.contains('read-more-wrapper'));
                    // 3 elements usually covers 1 Heading + 2 Paragraphs
                    if (children.length > 3) {
                        for (let i = 3; i < children.length; i++) {
                            children[i].style.display = 'none';
                            children[i].classList.add('hidden-content');
                        }
                        btn.style.display = 'inline-block';
                        
                        btn.addEventListener('click', function() {
                            const hiddenEls = content.querySelectorAll('.hidden-content');
                            if (btn.innerText === 'Read More') {
                                hiddenEls.forEach(el => el.style.display = '');
                                btn.innerText = 'Read Less';
                            } else {
                                hiddenEls.forEach(el => el.style.display = 'none');
                                btn.innerText = 'Read More';
                                content.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        });
                    }
                }

                // Platform FAQ Accordion (same logic as home page)
                const platformFaqs = document.querySelectorAll('.platform-faq-item');
                platformFaqs.forEach(faq => {
                    faq.addEventListener('toggle', function(e) {
                        if (this.open) {
                            platformFaqs.forEach(otherFaq => {
                                if (otherFaq !== this) {
                                    otherFaq.removeAttribute('open');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endif

   
        </div>
    </section>

    @if(count($faqs) > 0)
        <section class="platform-faq-section">
            <div class="platform-faq-wrap">
                <div class="platform-faq-header">
                    <h2>Frequently Asked <span>Questions</span></h2>
                    <p>Everything you need to know about downloading {{ $platform->name }} videos</p>
                </div>
                <div class="platform-faq-list">
                    @foreach ($faqs as $faq)
                    <details class="platform-faq-item" name="platform_faq">
                        <summary class="platform-faq-question">
                            {{ $faq->question }}
                        </summary>
                        <div class="platform-faq-answer">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </details>
                    @endforeach
                </div>
            </div>
        </section>
        <style>
            /* ── Platform FAQ Section (Home Page Style) ── */
            .platform-faq-section {
                padding: 48px 0 64px;
                background: #0d1117;
                border-top: 1px solid #202832;
            }
            .platform-faq-wrap {
                max-width: 880px;
                margin: 0 auto;
                padding: 0 18px;
            }
            .platform-faq-header {
                text-align: center;
                margin-bottom: 32px;
            }
            .platform-faq-header h2 {
                font-size: clamp(28px, 4vw, 40px);
                font-weight: 800;
                color: #f8fafc;
                margin: 0 0 8px;
            }
            .platform-faq-header h2 span { 
                color: #8b5cf6; 
            }
            .platform-faq-header p {
                color: #9da8b8;
                font-size: 16px;
                margin: 0;
            }
            .platform-faq-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .platform-faq-item {
                margin-bottom: 0;
                overflow: hidden;
                background: #121820;
                border: 1px solid #293341;
                border-radius: 12px;
                transition: border-color .2s ease, background .2s ease;
            }
            .platform-faq-item:hover,
            .platform-faq-item[open] {
                background: #151d26;
                border-color: rgba(139, 92, 246,.38);
            }
            .platform-faq-question {
                padding: 16px 18px;
                color: #edf2f7;
                text-align: left;
                cursor: pointer;
                list-style: none;
                font-weight: 700;
                font-size: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .platform-faq-question::-webkit-details-marker { 
                display: none; 
            }
            .platform-faq-question::after {
                content: '+';
                color: #8b5cf6;
                font-size: 22px;
                margin-left: 18px;
                font-weight: 700;
            }
            .platform-faq-item[open] .platform-faq-question::after {
                content: '−';
            }
            .platform-faq-answer {
                padding: 0 52px 16px 18px;
                text-align: left;
            }
            .platform-faq-answer p {
                margin: 0;
                color: #9da8b8;
                line-height: 1.7;
                font-size: 14px;
            }
            .faq-toggle-icon {
                display: none;
            }

            @media (max-width: 768px) {
                .platform-faq-section { 
                    padding: 32px 0 48px; 
                }
                .platform-faq-wrap { 
                    padding: 0 12px; 
                }
                .platform-faq-header { 
                    margin-bottom: 16px; 
                }
                .platform-faq-header h2 { 
                    font-size: 28px; 
                }
                .platform-faq-header p { 
                    font-size: 14px; 
                }
                .platform-faq-question { 
                    padding: 14px 15px; 
                    font-size: 14px; 
                }
                .platform-faq-answer { 
                    padding: 0 40px 14px 15px; 
                }
                .platform-faq-answer p { 
                    font-size: 13px; 
                }
            }
        </style>
    @endif

    @if(isset($blogs) && count($blogs) > 0)
        <section class="platform-guides-section">
            <div class="platform-guides-wrap">
                <div class="platform-guides-header">
                    <h2>Related Guides & <span>Tutorials</span></h2>
                    <p>Learn how to download videos, reels, and stories with original audio and HD quality</p>
                </div>
                <div class="platform-guides-grid">
                    @foreach ($blogs as $guide)
                    <article class="platform-guide-card">
                        <a href="{{ route('blog.show', $guide->slug) }}" class="platform-guide-img-link">
                            <img src="{{ $guide->featured_image ?: '/images/custom_blogs/img_1.png' }}" alt="{{ $guide->title }}" loading="lazy">
                        </a>
                        <div class="platform-guide-body">
                            <span class="platform-guide-tag">{{ $guide->tags ?: 'Guide' }}</span>
                            <h3><a href="{{ route('blog.show', $guide->slug) }}">{{ Str::limit($guide->title, 60) }}</a></h3>
                            <p>{{ Str::limit($guide->description ?: $guide->meta_description, 95) }}</p>
                            <a href="{{ route('blog.show', $guide->slug) }}" class="platform-guide-read">Read Full Guide →</a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </section>
        <style>
            .platform-guides-section {
                padding: 48px 0 64px;
                background: #090c11;
                border-top: 1px solid #1a222d;
            }
            .platform-guides-wrap {
                max-width: 1100px;
                margin: 0 auto;
                padding: 0 20px;
            }
            .platform-guides-header {
                text-align: center;
                margin-bottom: 36px;
            }
            .platform-guides-header h2 {
                font-size: 32px;
                font-weight: 800;
                color: #fff;
                margin: 0 0 8px;
            }
            .platform-guides-header h2 span {
                color: #8b5cf6;
            }
            .platform-guides-header p {
                color: #94a3b8;
                font-size: 15px;
                margin: 0;
            }
            .platform-guides-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 22px;
            }
            .platform-guide-card {
                background: #111720;
                border: 1px solid #202b38;
                border-radius: 14px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
            }
            .platform-guide-card:hover {
                transform: translateY(-4px);
                border-color: #8b5cf6;
                box-shadow: 0 12px 30px rgba(0,0,0,0.4);
            }
            .platform-guide-img-link {
                display: block;
                aspect-ratio: 16/9;
                overflow: hidden;
                background: #161f2b;
            }
            .platform-guide-img-link img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            .platform-guide-card:hover .platform-guide-img-link img {
                transform: scale(1.05);
            }
            .platform-guide-body {
                padding: 16px 18px 20px;
                display: flex;
                flex-direction: column;
                flex: 1;
            }
            .platform-guide-tag {
                display: inline-block;
                color: #8b5cf6;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 8px;
            }
            .platform-guide-body h3 {
                font-size: 16px;
                font-weight: 700;
                line-height: 1.35;
                margin: 0 0 8px;
                color: #fff;
            }
            .platform-guide-body h3 a {
                color: #fff;
                text-decoration: none;
                transition: color 0.2s;
            }
            .platform-guide-body h3 a:hover {
                color: #8b5cf6;
            }
            .platform-guide-body p {
                font-size: 13px;
                color: #8b99aa;
                line-height: 1.5;
                margin: 0 0 14px;
                flex: 1;
            }
            .platform-guide-read {
                font-size: 13px;
                font-weight: 700;
                color: #8b5cf6;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                transition: gap 0.2s;
            }
            .platform-guide-read:hover {
                gap: 8px;
            }
            @media (max-width: 768px) {
                .platform-guides-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endif

    @include('partials.footer')

    <script>
        const analyzeForm = document.getElementById('analyze-form');
        const urlInput = document.getElementById('video-url-input');
        const analyzeBtn = document.getElementById('analyze-btn');
        const resultContainer = document.getElementById('result-container');
        const errorContainer = document.getElementById('error-container');
        const downloadButtonHtml = analyzeBtn ? analyzeBtn.innerHTML : '';

        async function fetchResult(url) {
            if (!url || !/^https?:\/\//i.test(url) || !analyzeForm) return;

            analyzeBtn.disabled = true;
            analyzeBtn.innerHTML = '<span class="spinner" style="width:18px;height:18px;margin:0 8px 0 0;border-width:2px"></span><span>Analyzing...</span>';
            errorContainer.style.display = 'none';
            errorContainer.textContent = '';
            resultContainer.innerHTML = '<div class="loader-container result-fade-in"><div class="spinner"></div><div class="loader-text">Analyzing link and fetching formats...</div></div>';
            document.querySelector('.download-panel').classList.add('has-result');

            try {
                const formData = new FormData(analyzeForm);
                formData.set('video_url', url);
                const response = await fetch("{{ route('analyze') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
                });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.error || 'Failed to retrieve video data.');
                resultContainer.innerHTML = `<div class="result-fade-in">${data.html}</div>`;
            } catch (error) {
                resultContainer.innerHTML = '';
                errorContainer.textContent = error.message || 'An error occurred while connecting to the server.';
                errorContainer.style.display = 'block';
                document.querySelector('.download-panel').classList.remove('has-result');
            } finally {
                analyzeBtn.disabled = false;
                analyzeBtn.innerHTML = downloadButtonHtml;
            }
        }

        if (analyzeForm && urlInput) {
            analyzeForm.addEventListener('submit', event => {
                event.preventDefault();
                fetchResult(urlInput.value.trim());
            });

            let typingTimer;
            urlInput.addEventListener('input', function () {
                clearTimeout(typingTimer);
                const url = this.value.trim();
                if (/^https?:\/\//i.test(url)) typingTimer = setTimeout(() => fetchResult(url), 600);
            });
            urlInput.addEventListener('paste', () => setTimeout(() => fetchResult(urlInput.value.trim()), 100));
        }

        document.addEventListener('click', async function (event) {
            const button = event.target.closest('.prepare-download');
            if (!button || button.disabled) return;
            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.classList.add('is-loading');
            button.textContent = 'Preparing...';
            try {
                const response = await fetch(button.dataset.prepareUrl, {headers:{'Accept':'application/json'}});
                const data = await response.json();
                if (!response.ok || !data.download_url) throw new Error();
                const link = document.createElement('a');
                link.href = data.download_url;
                link.download = '';
                document.body.appendChild(link);
                link.click();
                link.remove();
                button.textContent = 'Started';
            } catch (error) {
                button.textContent = 'Try again';
            }
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.disabled = false;
                button.classList.remove('is-loading');
            }, 3000);
        });

        // Auto-Hide Google Translate Banner
        setInterval(function () {
            const banner = document.querySelector('.goog-te-banner-frame');
            if (banner) banner.remove();
            document.body.style.top = '0px';
        }, 500);
    </script>
</body>

</html>
