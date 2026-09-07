<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RewriteSocialBatchSixSeeder extends Seeder
{
 public function run(){
  $items=[
   ['download-dailymotion-videos-hd-format-guide','Dailymotion HD: Choose Detail That Your Device Can Use','HD quality and source detail'],
   ['download-videos-from-dailymotion-step-by-step','Dailymotion Video Links: A Step-by-Step Check','copying the individual public link'],
   ['dailymotion-mp4-format-and-compatibility-guide','Dailymotion MP4: Check Codec and Audio Support','MP4 playback compatibility'],
   ['dailymotion-downloader-mp4-device-guide','Dailymotion Files on Phones and Laptops','choosing a destination device'],
   ['download-dailymotion-mp4-file-size-guide','Dailymotion MP4: Balance Quality and Storage','file size and storage planning'],
   ['dailymotion-video-downloader-free-safe-use-guide','Dailymotion Public Links: Safe Use Checklist','permission and account safety'],
   ['dailymotion-downloader-online-browser-guide','Dailymotion in a Browser: Find a Missing File','browser history and Downloads folders'],
   ['dailymotion-mp4-downloader-best-practices','Dailymotion MP4: Keep a Clean Archive','names, dates, source URLs, and backups'],
   ['downloader-for-dailymotion-public-link-guide','Dailymotion Public Video: When No Result Appears','unavailable, private, or unsupported links'],
   ['download-video-online-dailymotion-quality-guide','Dailymotion Quality: Test Before You Share','checking sound, duration, orientation, and captions'],
  ];$slugs=array_column($items,0);$rows=DB::table('blog_posts')->whereIn('slug',$slugs)->lockForUpdate()->get();if($rows->count()!==10)throw new \RuntimeException('Target articles missing; stopped safely.');DB::transaction(function()use($items,$rows){$backup=dirname(base_path()).'/social-batch-six-'.date('Ymd-His').'.json';file_put_contents($backup,json_encode($rows,JSON_PRETTY_PRINT),LOCK_EX);chmod($backup,0600);foreach($items as [$slug,$title,$focus]){$content='<p>This guide focuses on '.$focus.' so a Dailymotion visitor can make one clear decision instead of reading a generic list of buttons. Use only your own, licensed, public-domain, or explicitly permitted media.</p><h2>Start with the individual video</h2><p>Open the exact Dailymotion page and confirm its title, creator, and availability. Copy the video URL rather than a channel or search page. Private, removed, login-dependent, or restricted content should be handled through the official route.</p><h2>Check the result on the target device</h2><ol><li>Play the opening and final seconds.</li><li>Listen for complete and synchronized audio.</li><li>Inspect captions or fine detail.</li><li>Confirm orientation and duration.</li></ol><p>A larger quality label cannot repair a soft source. Keep the first working copy before making a smaller export, and look in the browser Downloads folder if it is not visible in Photos or Gallery.</p><h2>Keep the context with the file</h2><p>Use a descriptive filename and store the source URL, creator name, and date beside it. Do not enter a password, browser cookie, or payment detail into an unknown link service.</p><p>Use the <a href="/dailymotion-video-downloader">Dailymotion platform guide</a> for public-link checks and the <a href="/supported-platforms">supported platforms directory</a> for other sources.</p>';$data=['title'=>$title,'excerpt'=>'A practical Dailymotion guide about '.$focus.'.','meta_title'=>$title,'meta_description'=>'A practical Dailymotion guide about '.$focus.'.','content'=>$content,'read_minutes'=>1,'updated_at'=>now()];DB::table('blog_posts')->where('slug',$slug)->update($data);}$this->command->info('Updated 10 articles. Backup: '.$backup);});
 }
}
