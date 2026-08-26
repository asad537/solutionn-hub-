<!doctype html>
<html lang="en">
<head>
    @include('partials.google-tag')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,follow">
    <title>Page Not Found | Solution Hub</title>
    <style>body{margin:0;background:#f7f8fa;color:#172033;font-family:Inter,system-ui,sans-serif}.box{min-height:100vh;display:grid;place-items:center;padding:24px;text-align:center}.card{max-width:640px;background:#fff;border:1px solid #e8ebf0;border-radius:18px;padding:48px;box-shadow:0 18px 48px rgba(24,32,51,.09)}.code{color:#0d3ca9;font-size:14px;font-weight:800;letter-spacing:.12em}h1{font-size:clamp(34px,6vw,58px);margin:12px 0}p{color:#677185;line-height:1.7}.actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:28px}a{padding:13px 18px;border-radius:10px;text-decoration:none;font-weight:700;background:#172033;color:#fff}a.secondary{background:#eef2f5;color:#172033}</style>
@include('partials.adsense-head')</head>
<body><main class="box"><div class="card"><div class="code">ERROR 404</div><h1>Page not found</h1><p>The page may have moved or the address may be incorrect. Return home or browse the supported video platforms.</p><div class="actions"><a href="{{ route('home') }}">Go to homepage</a><a class="secondary" href="{{ route('platforms') }}">Supported platforms</a></div></div></main></body>
</html>
