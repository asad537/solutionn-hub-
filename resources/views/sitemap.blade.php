{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticUrls as $entry)
    <url>
        <loc>{{ $entry['loc'] }}</loc>
        @if(!empty($entry['lastmod']))<lastmod>{{ $entry['lastmod'] }}</lastmod>@endif
        <changefreq>{{ $entry['changefreq'] }}</changefreq>
        <priority>{{ $entry['priority'] }}</priority>
    </url>
@endforeach
@foreach ($platforms as $platform)
    <url>
        <loc>{{ route('platforms.show', $platform->slug) }}</loc>
        @if($platform->updated_at)<lastmod>{{ $platform->updated_at->toAtomString() }}</lastmod>@endif
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
@foreach ($blogs as $blog)
    <url>
        <loc>{{ route('blog.show', $blog->slug) }}</loc>
        @if($blog->updated_at)<lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>@endif
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
@foreach ($legacyBlogs as $blog)
    <url>
        <loc>{{ route('blog.show', $blog->slug) }}</loc>
        @if($blog->updated_at)<lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>@endif
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
@foreach ($guides as $guide)
    <url>
        <loc>{{ route('guide.show', $guide->slug) }}</loc>
        @if($guide->updated_at)<lastmod>{{ $guide->updated_at->toAtomString() }}</lastmod>@endif
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>
