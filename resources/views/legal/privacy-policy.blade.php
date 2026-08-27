<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.google-tag')
    @include('partials.favicons')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $seo = \App\Models\PageSeo::getFor('privacy-policy'); @endphp
    <title>{{ $seo->meta_title ?? 'Privacy Policy - Video Saver' }}</title>
    @if($seo && $seo->meta_description)
    <meta name="description" content="{{ $seo->meta_description }}">
    @endif
    @if($seo && $seo->meta_keywords)
    <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
    @if($seo && $seo->meta_robots)
    <meta name="robots" content="{{ $seo->meta_robots }}">
    @else
    <meta name="robots" content="index,follow">
    @endif
    <link rel="canonical" href="{{ route('privacy') }}">
    <meta property="og:title" content="{{ $seo->meta_title ?? 'Privacy Policy | Solution Hub' }}">
    <meta property="og:description" content="{{ $seo->meta_description ?? 'Read the Solution Hub privacy policy.' }}">
    <meta property="og:url" content="{{ route('privacy') }}">
    <meta property="og:type" content="website">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #fff;
            color: #111827;
        }

        .legal-wrap {
            max-width: 980px;
            margin: 0 auto;
            padding: 3rem 1.25rem 3.5rem; line-height: 1.45; }

        .legal-hero {
            margin-bottom: 1.4rem;
            background: linear-gradient(135deg, rgba(255, 157, 7, 0.22), #FFFFFF);
            border: 1px solid #FF9D07;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 0 6px 20px rgba(17, 24, 39, 0.05);
        }

        .legal-hero h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.55rem;
        }

        .legal-hero p {
            color: #000;
            font-size: 0.98rem;
            line-height: 1.45;
            max-width: 760px;
        }

        .legal-head {
            margin-bottom: 1.4rem;
        }

        .legal-head h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.55rem;
        }

        .legal-head p {
            color: #000;
            font-size: 0.95rem; line-height: 1.45; }

        .legal-card {
            border: 1px solid #ECEFF3;
            border-radius: 18px;
            padding: 1.6rem;
            box-shadow: 0 4px 14px rgba(17, 24, 39, 0.04);
        }

        .legal-card h2 {
            font-size: 1.08rem;
            font-weight: 700;
            margin: 1rem 0 0.5rem;
            color: #111827;
        }

        .legal-card h2:first-child {
            margin-top: 0;
        }

        .legal-card p,
        .legal-card li {
            font-size: 0.95rem;
            color: #000;
            line-height: 1.75;
        }

        .legal-card ul {
            margin: 0.5rem 0 0.8rem 1.2rem;
        }

        .accent {
            color: #FFB800;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .legal-wrap {
                padding-top: 2.1rem; line-height: 1.45; }

            .legal-hero {
                margin-top: 5.2rem;
                padding: 1.35rem 1rem;
                border-radius: 16px;
            }

            .legal-hero h1 {
                font-size: 1.55rem;
            }

            .legal-hero p {
                font-size: 0.9rem; line-height: 1.45; }
        }
    </style>
<style>h1{font-size:3rem !important;}h2{font-size:2rem !important;}h3{font-size:1.5rem !important;}p{font-size:1.2rem !important;}</style>
    @include('partials.adsense-head')
</head>

<body class="has-fixed-header">
    @include('partials.navbar')
    <main class="legal-wrap">
        <section class="legal-hero">
            <h1>Privacy Policy</h1>
            <p style="line-height: 1.45;">We are committed to protecting your privacy and keeping your data secure while you use Video Saver.</p>
        </section>

        <div class="legal-head">
            <p style="line-height: 1.45;">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="legal-card">
            <h2>1. Introduction</h2>
            <p style="line-height: 1.45;">At <span class="accent">Video Saver</span>, we value your privacy. This Privacy Policy explains what
                information we collect, how we use it, and the choices you have when using our services.</p>

            <h2>2. Information We Collect</h2>
            <ul>
                <li>Usage data such as browser type, pages visited, and device information.</li>
                <li>Submitted URLs used only to process download requests.</li>
                <li>Basic technical logs for security, abuse prevention, and service stability.</li>
            </ul>

            <h2>3. How We Use Information</h2>
            <p style="line-height: 1.45;">We use collected data to operate and improve the service, detect abuse, resolve technical issues, and
                maintain platform performance.</p>

            <h2>4. Cookies and Tracking</h2>
            <p style="line-height: 1.45;">We may use cookies and similar technologies for language preferences, analytics, and essential site
                functionality.</p>

            <h2>5. Data Sharing</h2>
            <p style="line-height: 1.45;">We do not sell your personal information. Data may be shared only with trusted service providers where
                needed to maintain or secure the platform.</p>

            <h2>6. Data Security</h2>
            <p style="line-height: 1.45;">We implement reasonable technical and organizational safeguards. However, no internet transmission or
                storage system is 100% secure.</p>

            <h2>7. Your Rights</h2>
            <p style="line-height: 1.45;">Depending on your location, you may have rights to access, update, or request deletion of your personal
                data.</p>

            <h2>8. Contact</h2>
            <p style="line-height: 1.45;">For privacy-related requests, please contact us through the website support channel.</p>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>




