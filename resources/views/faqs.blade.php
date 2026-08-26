<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.google-tag')
    <!-- Preload hero image for instant LCP -->
    <link rel="preload" as="image" href="/images/faqs.webp" type="image/webp" fetchpriority="high">
    <link rel="icon" type="image/webp" href="/images/Fav-logo.webp">
    <link rel="apple-touch-icon" href="/images/logo-hafiz.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $seo = \App\Models\PageSeo::getFor('faqs'); @endphp
    <title>{{ $seo->meta_title ?? ($settings->faq_meta_title ?? 'Frequently Asked Questions | Solution Hub') }}</title>
    @if($seo && $seo->meta_description)
    <meta name="description" content="{{ $seo->meta_description }}">
    @else
    <meta name="description" content="{{ $settings->faq_meta_description ?? 'Find clear answers about supported public video links, available formats, device compatibility, troubleshooting, privacy, and responsible use.' }}">
    @endif
    @if($seo && $seo->meta_keywords)
    <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @else
    <meta name="keywords" content="{{ $settings->faq_meta_keywords ?? '' }}">
    @endif
    @if($seo && $seo->meta_robots)
    <meta name="robots" content="{{ $seo->meta_robots }}">
    @else
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1">
    @endif
    <link rel="canonical" href="{{ route('public.faqs') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Solution Hub">
    <meta property="og:title" content="{{ $seo->meta_title ?? 'Frequently Asked Questions | Solution Hub' }}">
    <meta property="og:description" content="{{ $seo->meta_description ?? ($settings->faq_meta_description ?? 'Find answers about supported links, formats, devices, troubleshooting, privacy, and responsible use.') }}">
    <meta property="og:url" content="{{ route('public.faqs') }}">
    <meta property="og:image" content="{{ asset('images/logo-hafiz.svg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- JSON-LD Schemas for FAQ Page -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "@id": "https://solutionhub.digital/#organization",
      "name": "Solution Hub",
      "alternateName": [
        "Solution Hub",
        "HD Video DL"
      ],
      "url": "https://solutionhub.digital/",
      "logo": {
        "@type": "ImageObject",
        "url": "https://solutionhub.digital/images/logo-hafiz.svg"
      },
      "description": "Solution Hub helps users analyze supported public media links and review source-dependent media formats.",
      "sameAs": [
        "https://play.google.com/store/apps/details?id=com.jmdsol.videodownloader.videosaver"
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "name": "Frequently Asked Questions - Solution Hub",
      "url": "https://solutionhub.digital/faqs",
      "description": "Answers about Solution Hub, supported platforms, troubleshooting, and responsible use.",
      "publisher": {
        "@id": "https://solutionhub.digital/#organization"
      },
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How does this media link analyzer work?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Paste a supported public link and the system checks the source response for available metadata and media formats. Results depend on the source and the visibility of the link."
          }
        },
        {
          "@type": "Question",
          "name": "Do I need to create an account?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. The basic public-link analysis workflow does not require registration or personal account details."
          }
        },
        {
          "@type": "Question",
          "name": "Is this service available in the browser?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. The basic workflow runs in a modern browser without requiring a desktop app or browser extension."
          }
        },
        {
          "@type": "Question",
          "name": "Which devices are supported?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Video Saver is a web-based tool, meaning it works on any device with a browser. You can use it on Windows, macOS, Android, and iOS (iPhone/iPad)."
          }
        },
        {
          "@type": "Question",
          "name": "Can I review YouTube videos in 4K?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "If the public source exposes a 4K format, it may appear in the results. The tool cannot create a resolution that the source does not provide."
          }
        },
        {
          "@type": "Question",
          "name": "Does it work with private Instagram profiles?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. The analyzer works only with supported public links. Private, login-only, paid, or access-controlled media is not supported."
          }
        },
        {
          "@type": "Question",
          "name": "Can I remove a watermark from TikTok videos?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No watermark removal is promised. Available formats depend on the public source response and creator/platform settings."
          }
        },
        {
          "@type": "Question",
          "name": "Why is my analysis slow?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Processing time depends on your internet connection, the responsiveness of the source platform, media duration, and the number of formats exposed."
          }
        },
        {
          "@type": "Question",
          "name": "Why does the media open in a new tab?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Some browsers open supported media formats in a new tab. Browser behavior varies by file type, settings, and device."
          }
        },
        {
          "@type": "Question",
          "name": "Why did my link analysis fail?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "A link may fail if the media was deleted, restricted by region, login-only, private, or affected by a source-platform delivery change."
          }
        },
        {
          "@type": "Question",
          "name": "Is there a limit on link checks?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Reasonable manual use is supported. Automated or abusive request patterns may be limited to protect the service."
          }
        },
        {
          "@type": "Question",
          "name": "What video formats are supported?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "We primarily support MP4 for video and MP3 for audio. Depending on the source, you may also see options for WEBM, M4A, and different resolution tiers."
          }
        },
        {
          "@type": "Question",
          "name": "Can I review audio-only formats?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Audio-only formats may appear when the public source exposes them. Availability varies by platform and media item."
          }
        },
        {
          "@type": "Question",
          "name": "Is it legal to save videos?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Only save content you own, content you have permission to save, or content whose license allows it. Follow local law and the source platform's terms."
          }
        },
        {
          "@type": "Question",
          "name": "How do saved files work on iPhone?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "On iOS, Safari usually places saved files in the Files app. Browser and device behavior can vary by format."
          }
        },
        {
          "@type": "Question",
          "name": "Are the downloads safe and secure?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The basic workflow does not require software installation or extensions. Do not submit private URLs, passwords, account cookies, or sensitive information."
          }
        }
      ]
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
          "name": "FAQ",
          "item": "https://solutionhub.digital/faqs"
        }
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Solution Hub",
      "alternateName": [
        "HD Video Saver",
        "HDVideoSaver",
        "HVS Downloader"
      ],
      "url": "https://solutionhub.digital/",
      "description": "Solution Hub helps users analyze supported public media links and review source-dependent media formats.",
      "publisher": {
        "@id": "https://solutionhub.digital/#organization"
      }
    }
    </script>

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

        /* ── Dark Header ── */
        .platform-header {
            position: sticky;
            top: 0;
            z-index: 9999;
            background: rgba(9,12,17,0.92);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            padding: 0.8rem 0;
        }
        .platform-nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }
        .platform-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .platform-nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .platform-nav-links a {
            color: #a0aaba;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.2s;
        }
        .platform-nav-links a:hover,
        .platform-nav-links a.active { color: #fff; }

        /* Desktop Mega Menu */
        .nav-dropdown-wrap { position:relative; display:inline-flex; align-items:center; }
        .dropdown-trigger { color:#a0aaba; font-size:15px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; transition:color .2s; display:inline-flex; align-items:center; gap:4px; cursor:pointer; }
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
        .platform-nav-links .mega-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            text-decoration: none;
            color: #c3cad5;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .platform-nav-links .mega-item:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
            transform: translateX(3px);
        }
        .platform-nav-links .mega-item .item-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            font-size: 16px;
        }
        /* Sub-Platform Child Menu */
        .platform-nav-links .mega-parent-wrap { position: relative; }
        .platform-nav-links .mega-child-menu {
            display: none;
            position: absolute;
            left: calc(100% + 6px);
            right: auto;
            top: 0;
            min-width: 240px;
            background: rgba(15, 20, 28, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246,0.15);
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.55);
            padding: 6px;
            z-index: 999999;
        }
        .platform-nav-links .mega-parent-wrap.has-kids:hover .mega-child-menu { display: block; }
        .platform-nav-links .mega-child-item { padding: 8px 12px; border-radius: 8px; font-size: 12px; }
        .platform-nav-links .mega-child-item .item-icon { width: 22px; height: 22px; font-size: 12px; }
        /* Mobile Hamburger */
        .hamburger { display:none; flex-direction:column; gap:5px; background:none; border:none; cursor:pointer; padding:5px; z-index:100; }
        .hamburger span { display:block; width:24px; height:2px; background:#fff; transition:all 0.3s ease; }
        
        @media (max-width:900px) {
            .platform-nav-links { display:none; }
            .hamburger { display:flex; }
        }

        /* Hero */
        .platform-hero {
            padding: 5rem 2rem 3rem;
            text-align: center;
            position: relative;
        }
        .platform-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            transform: translateX(-50%);
            width: 100%; max-width: 800px;
            height: 400px;
            background: radial-gradient(ellipse at top, rgba(139, 92, 246,0.15) 0%, rgba(9,12,17,0) 70%);
            pointer-events: none;
            z-index: -1;
        }
        .platform-hero-wrap {
            max-width: 800px;
            margin: 0 auto;
        }
        .platform-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(139, 92, 246,0.1);
            border: 1px solid rgba(139, 92, 246,0.2);
            border-radius: 50px;
            color: #8b5cf6;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2rem;
        }
        .platform-hero h1 {
            font-size: clamp(32px, 5vw, 56px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 1.25rem;
            color: #fff;
        }
        .platform-hero p {
            font-size: clamp(16px, 2vw, 18px);
            color: #a0aaba;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }

        /* FAQs */
        .platform-faq-wrap {
            max-width: 820px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .platform-faq-header {
            margin-bottom: 1.5rem;
        }
        .platform-faq-header h2 {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        .platform-faq-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .platform-faq-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            overflow: hidden;
            transition: border-color 0.25s ease, background 0.25s ease;
        }
        .platform-faq-item:hover {
            border-color: rgba(139, 92, 246,0.2);
            background: rgba(139, 92, 246,0.03);
        }
        .platform-faq-item[open] {
            border-color: rgba(139, 92, 246,0.3);
            background: rgba(139, 92, 246,0.04);
        }
        .platform-faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            list-style: none;
            color: #e2e8f0;
            font-weight: 700;
            font-size: 1rem;
            user-select: none;
        }
        .platform-faq-question::-webkit-details-marker { display: none; }
        .platform-faq-item[open] .platform-faq-question { color: #8b5cf6; }
        .faq-toggle-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }
        .faq-toggle-icon svg {
            width: 14px;
            height: 14px;
            stroke: #a0aaba;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: transform 0.25s ease, stroke 0.25s ease;
        }
        .platform-faq-item[open] .faq-toggle-icon {
            background: rgba(139, 92, 246,0.12);
            border-color: rgba(139, 92, 246,0.3);
        }
        .platform-faq-item[open] .faq-toggle-icon svg {
            transform: rotate(180deg);
            stroke: #8b5cf6;
        }
        .platform-faq-answer {
            padding: 0 1.5rem 1.5rem;
            color: #a0aaba;
            font-size: 0.95rem;
            line-height: 1.75;
        }
        .platform-faq-answer p { margin: 0; }
    </style>
    <style>h1{font-size:3rem !important;}h2{font-size:2rem !important;}h3{font-size:1.5rem !important;}p{font-size:1.2rem !important;}</style>
    @include('partials.adsense-head')
</head>

<body>
    @include('partials.navbar')

    <section class="platform-hero" style="margin-bottom: 2rem;">
        <div class="platform-hero-wrap">
            <span class="platform-hero-badge" style="margin-bottom:1.5rem;"><i class="fas fa-question-circle"></i> Find Answers</span>
            <h1>{{ $settings->faq_h1 ?? 'Answers to Your Common Questions' }}</h1>
            <p>{{ $settings->faq_description ?? 'Find everything you need to know about downloading videos, quality settings, and platform support.' }}</p>
        </div>
    </section>

    <main class="platform-faq-wrap" style="padding-bottom: 6rem;">
        @forelse($faqs as $category => $items)
            <div class="platform-faq-header" style="margin-top: 3rem; margin-bottom: 1.5rem; text-align:left;">
                <h2 style="font-size: 24px; margin-bottom:0;">{{ $category }}</h2>
            </div>
            <div class="platform-faq-list">
                @foreach($items as $faq)
                    <details class="platform-faq-item">
                        <summary class="platform-faq-question">
                            <span>{{ $faq->question }}</span>
                            <div class="faq-toggle-icon">
                                <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </summary>
                        <div class="platform-faq-answer">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        @empty
            <div style="text-align:center; padding:5rem 0;">
                <i class="fas fa-search" style="font-size:3rem; color:#a0aaba; margin-bottom:1.5rem;"></i>
                <p style="color:#a0aaba; line-height: 1.45;">No FAQs found at the moment.</p>
            </div>
        @endforelse
    </main>

    @include('partials.footer')
</body>

</html>
