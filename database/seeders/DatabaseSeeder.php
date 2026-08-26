<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PlatformPageSeeder::class);

        User::updateOrCreate(['email' => 'admin@solutionhub.digital'], [
            'name' => 'Solution Hub Admin',
            'password' => Hash::make('Admin@12345'),
        ]);

        $bannerDirectory = public_path('images/blog/generated');
        File::ensureDirectoryExists($bannerDirectory);

        $blogPosts = config('blog');
        BlogPost::whereNotIn('slug', collect($blogPosts)->pluck('slug')->all())->delete();

        foreach ($blogPosts as $index => $post) {
            $image = $post['image'] ?? '/images/blog/generated/' . Str::slug($post['title']) . '.svg';
            $this->writeBanner($bannerDirectory, basename($image), $post, $index + 1);

            BlogPost::updateOrCreate(['slug' => $post['slug']], [
                'title' => $post['title'], 'category' => $post['category'], 'excerpt' => $post['excerpt'],
                'meta_title' => Str::limit($post['title'], 60, ''),
                'meta_description' => Str::limit($post['description'], 155, ''),
                'content' => $post['content'],
                'image' => $image, 'image_alt' => $post['image_alt'] ?? $post['title'] . ' illustrated guide',
                'read_minutes' => (int) $post['read'], 'is_published' => true,
                'published_at' => now()->subDays(99 - $index),
            ]);
        }

        foreach ([
            'site_name' => 'Solution Hub',
            'hero_title' => 'Public Media Link Analyzer',
            'hero_subtitle' => 'Review source-dependent video and audio formats from supported public links. Use the tool only for content you own or have permission to save.',
            'default_meta_description' => 'Analyze supported public media links and review available HD formats with Solution Hub.',
        ] as $key => $value) SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    private function writeBanner(string $directory, string $filename, array $post, int $number): void
    {
        $palettes = [
            ['#09111f', '#12b3a8', '#ff3d57'],
            ['#0b1020', '#5267df', '#39e1b6'],
            ['#111827', '#f43f5e', '#22c55e'],
            ['#07131f', '#0ea5e9', '#f97316'],
            ['#101014', '#8b5cf6', '#14b8a6'],
            ['#061a18', '#10b981', '#f43f5e'],
            ['#111322', '#f59e0b', '#38bdf8'],
            ['#08111f', '#ec4899', '#2dd4bf'],
        ];
        $palette = $palettes[($number - 1) % count($palettes)];
        $title = e(Str::limit($post['title'], 54, ''));
        $category = e($post['category'] ?? 'Guide');
        $keyword = e(strtoupper((string) ($post['keyword'] ?? 'HD VIDEO')));
        $x = 70 + (($number * 29) % 220);
        $y = 140 + (($number * 41) % 170);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675" role="img" aria-label="{$title}">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$palette[0]}"/>
      <stop offset=".58" stop-color="#101827"/>
      <stop offset="1" stop-color="{$palette[1]}"/>
    </linearGradient>
    <radialGradient id="glow" cx="50%" cy="50%" r="60%">
      <stop offset="0" stop-color="{$palette[2]}" stop-opacity=".52"/>
      <stop offset="1" stop-color="{$palette[2]}" stop-opacity="0"/>
    </radialGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="22" stdDeviation="22" flood-color="#000" flood-opacity=".35"/>
    </filter>
  </defs>
  <rect width="1200" height="675" rx="0" fill="url(#bg)"/>
  <circle cx="{$x}" cy="{$y}" r="260" fill="url(#glow)"/>
  <circle cx="960" cy="520" r="280" fill="{$palette[1]}" opacity=".16"/>
  <path d="M0 498 C190 430 296 540 474 472 C666 398 790 312 1200 380 L1200 675 L0 675 Z" fill="{$palette[1]}" opacity=".16"/>
  <g opacity=".12" stroke="#fff" stroke-width="1">
    <path d="M80 110 H1120"/>
    <path d="M80 210 H1120"/>
    <path d="M80 310 H1120"/>
    <path d="M80 410 H1120"/>
    <path d="M210 70 V608"/>
    <path d="M390 70 V608"/>
    <path d="M570 70 V608"/>
    <path d="M750 70 V608"/>
    <path d="M930 70 V608"/>
  </g>
  <g filter="url(#shadow)">
    <rect x="705" y="142" width="330" height="210" rx="28" fill="#0b1220" stroke="{$palette[1]}" stroke-opacity=".62"/>
    <rect x="745" y="180" width="250" height="132" rx="16" fill="{$palette[1]}" opacity=".18"/>
    <path d="M858 206 L858 286 L924 246 Z" fill="#fff"/>
    <rect x="770" y="382" width="238" height="32" rx="16" fill="#fff" opacity=".14"/>
    <rect x="770" y="432" width="150" height="32" rx="16" fill="{$palette[2]}" opacity=".84"/>
  </g>
  <text x="78" y="108" fill="{$palette[1]}" font-family="Inter,Arial,sans-serif" font-size="25" font-weight="800" letter-spacing="4">{$keyword}</text>
  <text x="78" y="184" fill="#fff" font-family="Inter,Arial,sans-serif" font-size="56" font-weight="900">{$category}</text>
  <foreignObject x="78" y="220" width="560" height="190">
    <div xmlns="http://www.w3.org/1999/xhtml" style="font-family:Inter,Arial,sans-serif;color:white;font-size:44px;font-weight:900;line-height:1.08;">{$title}</div>
  </foreignObject>
  <text x="78" y="560" fill="#c8d2df" font-family="Inter,Arial,sans-serif" font-size="24" font-weight="700">Solution Hub Guide {$number}</text>
</svg>
SVG;

        File::put($directory . DIRECTORY_SEPARATOR . $filename, $svg);
    }
}
