<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewriteYoutubeBatchOneSeeder extends Seeder
{
    public function run()
    {
        $posts = json_decode(file_get_contents(database_path('content/youtube-batch-01.json')), true, 512, JSON_THROW_ON_ERROR);
        if (count($posts) !== 10 || count(array_unique(array_column($posts, 'slug'))) !== 10) {
            throw new \RuntimeException('Expected exactly ten unique articles.');
        }
        DB::transaction(function () use ($posts) {
            $rows = DB::table('blog_posts')->whereIn('slug', array_column($posts, 'slug'))->lockForUpdate()->get();
            if ($rows->count() !== 10) {
                throw new \RuntimeException('Missing target articles; no updates applied.');
            }
            // Keep the pre-update snapshot outside the public document root.
            $backup = dirname(base_path()).'/youtube-batch-one-'.date('Ymd-His').'-'.bin2hex(random_bytes(4)).'.json';
            $handle = fopen($backup, 'x');
            if (!$handle) throw new \RuntimeException('Cannot create backup.');
            chmod($backup, 0600);
            $json = json_encode($rows->all(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            $written = fwrite($handle, $json);
            fclose($handle);
            if ($written !== strlen($json)) throw new \RuntimeException('Incomplete backup; stopped.');
            foreach ($posts as $post) {
                $slug = $post['slug'];
                unset($post['slug']);
                $post['read_minutes'] = max(1, (int) ceil(str_word_count(strip_tags($post['content'])) / 200));
                $post['updated_at'] = now();
                DB::table('blog_posts')->where('slug', $slug)->update($post);
            }
            $this->command->info('Updated 10 articles. Backup: '.$backup);
        });
    }
}
