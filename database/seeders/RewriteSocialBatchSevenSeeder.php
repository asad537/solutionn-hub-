<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RewriteSocialBatchSevenSeeder extends Seeder
{
 public function run(){
  $items=[
   ['reddit-video-downloader-save-public-reddit-clips','Reddit Videos: Check the Post Before Saving','Reddit public post'],
   ['download-reddit-video-mp4-and-audio-guide','Reddit MP4 or Audio: Choose the Useful Copy','Reddit MP4 and audio'],
   ['download-reddit-videos-public-post-guide','Reddit Video Posts: Keep the Thread Context','Reddit thread context'],
   ['descargar-videos-de-reddit-guia-en-mp4','Reddit Videos in MP4: A Clear Format Guide','Reddit MP4 format'],
   ['linkedin-video-download-professional-content-guide','LinkedIn Videos: Save Professional References Responsibly','LinkedIn professional posts'],
   ['linkedin-video-downloader-public-post-guide','LinkedIn Public Video Posts: Verify the Source','LinkedIn public post links'],
   ['download-video-from-linkedin-safe-workflow','LinkedIn Video: A Safe Browser Workflow','LinkedIn browser workflow'],
   ['download-videos-from-linkedin-team-guide','LinkedIn Team Videos: Keep Project Copies Organized','team video archives'],
   ['snapchat-downloader-story-and-video-guide','Snapchat Stories: Save Your Own Memories Carefully','your own Snapchat Stories'],
   ['snapchat-video-downloader-public-content-guide','Snapchat Public Videos: Check Context and Permission','Snapchat public videos'],
  ];$slugs=array_column($items,0);$rows=DB::table('blog_posts')->whereIn('slug',$slugs)->lockForUpdate()->get();if($rows->count()!==10)throw new \RuntimeException('Target articles missing; stopped safely.');DB::transaction(function()use($items,$rows){$backup=dirname(base_path()).'/social-batch-seven-'.date('Ymd-His').'.json';file_put_contents($backup,json_encode($rows,JSON_PRETTY_PRINT),LOCK_EX);chmod($backup,0600);foreach($items as [$slug,$title,$focus]){$platform=str_contains($slug,'linkedin')?'linkedin':(str_contains($slug,'snapchat')?'snapchat':'reddit');$content='<p>This guide covers '.$focus.' with one practical goal: identify the correct public source, keep the context, and check the result on the device where you will use it.</p><h2>Verify the original post</h2><p>Open the individual post, confirm the creator and title, and copy the post URL rather than a profile or search page. Private, deleted, login-only, or restricted material should be handled through the platform’s official route.</p><h2>Check the saved copy</h2><ol><li>Play the start, middle, and ending.</li><li>Check audio and captions.</li><li>Confirm orientation and duration.</li><li>Open it on the target phone or computer.</li></ol><p>If the file is missing, check the browser Downloads folder as well as Photos or Gallery. If it becomes blurry only after sending, the messaging service compressed it.</p><h2>Keep permission and context</h2><p>Use your own work, licensed material, public-domain media, or a copy the creator has allowed. Keep the source URL, creator name, and date with the file. Never provide passwords or browser cookies to a link service.</p><p>Use the <a href="/'.$platform.'-video-downloader">'.$platform.' platform guide</a> and the <a href="/supported-platforms">supported platforms directory</a> for related sources.</p>';$data=['title'=>$title,'excerpt'=>'A practical guide to '.$focus.'.','meta_title'=>$title,'meta_description'=>'A practical guide to '.$focus.'.','content'=>$content,'read_minutes'=>1,'updated_at'=>now()];DB::table('blog_posts')->where('slug',$slug)->update($data);}$this->command->info('Updated 10 articles. Backup: '.$backup);});
 }
}
