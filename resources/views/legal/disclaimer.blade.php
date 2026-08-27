<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.google-tag')
    @include('partials.favicons')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $seo = \App\Models\PageSeo::getFor('disclaimer'); @endphp
    <title>{{ $seo->meta_title ?? 'Disclaimer - Video Saver' }}</title>
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
    <link rel="canonical" href="{{ route('disclaimer') }}">
    <meta property="og:title" content="{{ $seo->meta_title ?? 'Disclaimer | Solution Hub' }}">
    <meta property="og:description" content="{{ $seo->meta_description ?? 'Read the Solution Hub legal disclaimer.' }}">
    <meta property="og:url" content="{{ route('disclaimer') }}">
    <meta property="og:type" content="website">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #fff; color: #111827; }
        .legal-wrap { max-width: 980px; margin: 0 auto; padding: 3rem 1.25rem 3.5rem; line-height: 1.45; }
        .legal-hero {
            margin-bottom: 1.4rem;
            background: linear-gradient(135deg, rgba(255, 157, 7, 0.22), #FFFFFF);
            border: 1px solid #FF9D07;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 0 6px 20px rgba(17, 24, 39, 0.05);
        }
        .legal-hero h1 { font-size: 2rem; font-weight: 800; color: #111827; margin-bottom: 0.55rem; }
        .legal-hero p { color: #000; font-size: 0.98rem; line-height: 1.45; max-width: 760px; }
        .legal-head { margin-bottom: 1.4rem; }
        .legal-head h1 { font-size: 2rem; font-weight: 800; margin-bottom: 0.55rem; }
        .legal-head p { color: #000; font-size: 0.95rem; line-height: 1.45; }
        .legal-card { border: 1px solid #ECEFF3; border-radius: 18px; padding: 1.6rem; box-shadow: 0 4px 14px rgba(17, 24, 39, 0.04); }
        .legal-card h2 { font-size: 1.08rem; font-weight: 700; margin: 1rem 0 0.5rem; color: #111827; }
        .legal-card h2:first-child { margin-top: 0; }
        .legal-card p, .legal-card li { font-size: 0.95rem; color: #000; line-height: 1.75; }
        .legal-card ul { margin: 0.5rem 0 0.8rem 1.2rem; }
        .accent { color: #FFB800; font-weight: 700; }
        @media (max-width: 768px) {
            .legal-wrap { padding-top: 2.1rem; line-height: 1.45; }
            .legal-hero { margin-top: 5.2rem; padding: 1.35rem 1rem; border-radius: 16px; }
            .legal-hero h1 { font-size: 1.55rem; }
            .legal-hero p { font-size: 0.9rem; line-height: 1.45; }
        }
    </style>
<style>h1{font-size:3rem !important;}h2{font-size:2rem !important;}h3{font-size:1.5rem !important;}p{font-size:1.2rem !important;}</style>
    @include('partials.adsense-head')
</head>

<body class="has-fixed-header">
    @include('partials.navbar')
    <main class="legal-wrap">
        <section class="legal-hero">
            <h1>Disclaimer</h1>
            <p style="line-height: 1.45;">Please review this disclaimer to understand the scope, limitations, and proper use of our platform.</p>
        </section>

        <div class="legal-head">
            <p style="line-height: 1.45;">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="legal-card">
            <h2>1. General Information</h2>
            <p style="line-height: 1.45;"><span class="accent">Video Saver</span> provides tools for accessing publicly available media links. All
                information on this website is provided in good faith for general informational use.</p>

            <h2>2. No Legal Advice</h2>
            <p style="line-height: 1.45;">Content on this website does not constitute legal advice. Users are solely responsible for ensuring their
                actions comply with local laws and platform terms.</p>

            <h2>3. Copyright Responsibility</h2>
            <p style="line-height: 1.45;">Users must have the right, permission, or legal basis to download or use any content. Video Saver does
                not claim ownership of third-party media.</p>

            <h2>4. Third-Party Services</h2>
            <p style="line-height: 1.45;">We are not affiliated with, endorsed by, or sponsored by third-party social platforms. Trademarks and
                brand names belong to their respective owners.</p>

            <h2>5. Accuracy and Availability</h2>
            <p style="line-height: 1.45;">While we strive for reliability, we do not guarantee uninterrupted service, absolute accuracy, or
                compatibility at all times.</p>

            <h2>6. Limitation of Liability</h2>
            <p style="line-height: 1.45;">Video Saver is not liable for any direct or indirect losses, data issues, or legal consequences resulting
                from platform use.</p>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>




