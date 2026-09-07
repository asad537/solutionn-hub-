<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RewriteSocialBatchThreeSeeder extends Seeder
{
 public function run(){$posts=json_decode(file_get_contents(database_path('content/social-batch-03.json')),true,512,JSON_THROW_ON_ERROR);DB::transaction(function()use($posts){$slugs=array_column($posts,'slug');$rows=DB::table('blog_posts')->whereIn('slug',$slugs)->lockForUpdate()->get();if($rows->count()!==count($posts))throw new \RuntimeException('Target articles missing; stopped safely.');$backup=dirname(base_path()).'/social-batch-three-'.date('Ymd-His').'.json';file_put_contents($backup,json_encode($rows,JSON_PRETTY_PRINT),LOCK_EX);chmod($backup,0600);foreach($posts as $p){$slug=$p['slug'];unset($p['slug']);$p['read_minutes']=max(1,(int)ceil(str_word_count(strip_tags($p['content']))/200));$p['updated_at']=now();DB::table('blog_posts')->where('slug',$slug)->update($p);}$this->command->info('Updated '.count($posts).' articles. Backup: '.$backup);});}
}
