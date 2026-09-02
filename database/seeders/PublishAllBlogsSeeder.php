<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishAllBlogsSeeder extends Seeder
{
    /**
     * Publish every draft blog post (is_published = 1) so it appears
     * on the site and in the sitemap. Safe to run multiple times.
     */
    public function run()
    {
        $count = DB::table('blog_posts')
            ->where('is_published', 0)
            ->update([
                'is_published' => 1,
                'published_at' => DB::raw('COALESCE(published_at, NOW())'),
                'updated_at'   => now(),
            ]);

        echo "Published {$count} draft blog post(s). Total published: "
            . DB::table('blog_posts')->where('is_published', 1)->count() . "\n";
    }
}
