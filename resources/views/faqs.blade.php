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
    <meta property="og:title" content="{{ $seo->meta_title ?? ($settings->faq_meta_title ?? 'Frequently Asked Questions | Solution Hub') }}">
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
    @php
        $faqSchemaList = [];
        foreach($faqs as $category => $items) {
            foreach($items as $faq) {
                $faqSchemaList[] = [
                    '@type' => 'Question',
                    'name' => strip_tags($faq->question),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($faq->answer),
                    ]
                ];
            }
        }
    @endphp
    @if(count($faqSchemaList) > 0)
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'name' => $seo->meta_title ?? ($settings->faq_meta_title ?? 'Frequently Asked Questions - Solution Hub'),
        'url' => route('public.faqs'),
        'description' => $seo->meta_description ?? ($settings->faq_meta_description ?? 'Find answers about supported links, formats, devices, troubleshooting, privacy, and responsible use.'),
        'publisher' => [
            '@id' => 'https://solutionhub.digital/#organization'
        ],
        'mainEntity' => $faqSchemaList
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
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
