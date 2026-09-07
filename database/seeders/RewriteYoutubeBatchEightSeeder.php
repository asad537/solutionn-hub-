<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RewriteYoutubeBatchEightSeeder extends Seeder
{
 public function run(){
  $items=[
   ['youtube-short-downloader-quick-safe-workflow','YouTube Shorts: A Quick Workflow for an Authorized Copy','checking a Short before keeping it'],
   ['youtube-video-downloader-mp4-compatibility-guide','YouTube MP4 Compatibility: Check Audio and Codec','MP4 compatibility on a phone or laptop'],
   ['youtube-video-downloader-1080p-quality-checklist','YouTube 1080p: A Real Quality Checklist','checking whether 1080p detail is actually useful'],
   ['youtube-video-downloader-4k-what-to-know-first','YouTube 4K: What to Check Before Choosing a Large File','4K source detail and storage'],
   ['youtube-video-downloader-hd-best-quality-tips','YouTube HD: Keep the Picture Sharp After Transfer','preserving HD detail between devices'],
   ['youtube-video-downloader-online-no-app-guide','YouTube Browser Workflow: Keep Your Account Safe','using public links without unknown apps'],
   ['youtube-video-downloader-free-safe-public-links','YouTube Public Links: Safe Use and Permission','public links and creator permission'],
   ['is-a-youtube-video-downloader-safe','Is a YouTube Link Tool Safe? A Practical Check','passwords, cookies, redirects, and file safety'],
   ['youtube-to-mp4-converter-format-guide','YouTube to MP4: Understand the Container','MP4 containers, codecs, and playback'],
   ['youtube-mp4-download-file-size-and-resolution-guide','YouTube MP4: Balance File Size and Resolution','file size versus resolution'],
  ];$slugs=array_column($items,0);$rows=DB::table('blog_posts')->whereIn('slug',$slugs)->lockForUpdate()->get();if($rows->count()!==10)throw new \RuntimeException('Target articles missing; stopped safely.');DB::transaction(function()use($items,$rows){$backup=dirname(base_path()).'/youtube-batch-eight-'.date('Ymd-His').'.json';file_put_contents($backup,json_encode($rows,JSON_PRETTY_PRINT),LOCK_EX);chmod($backup,0600);foreach($items as [$slug,$title,$focus]){$content='<p>This guide focuses on '.$focus.'. It is written for a real visitor who needs to make one useful decision, not repeat a keyword. Use only your own, licensed, public-domain, or explicitly permitted media.</p><h2>Check the source first</h2><p>Open the individual YouTube video, confirm its title and creator, and copy the video URL rather than a channel or search page. Public playback does not automatically grant permission to reuse or redistribute a video.</p><h2>Test the result</h2><ol><li>Play the opening and final seconds.</li><li>Check sound and synchronization.</li><li>Inspect text or fine detail.</li><li>Confirm duration and orientation.</li><li>Open the file on its destination device.</li></ol><p>A larger label cannot restore detail absent from the source. Keep the first working copy before resizing, and check the browser Downloads folder if the file does not appear in Photos or Gallery.</p><h2>Protect the workflow</h2><p>Do not provide passwords, browser cookies, or payment details to an unknown tool. Keep the creator name, source URL, and permission note beside any file you retain.</p><p>Use the <a href="/youtube-video-downloader">YouTube platform guide</a> for public-link checks and the <a href="/supported-platforms">supported platforms directory</a> for other sources.</p>';$data=['title'=>$title,'excerpt'=>'A practical guide to '.$focus.'.','meta_title'=>$title,'meta_description'=>'A practical guide to '.$focus.'.','content'=>$content,'read_minutes'=>1,'updated_at'=>now()];DB::table('blog_posts')->where('slug',$slug)->update($data);}$this->command->info('Updated 10 articles. Backup: '.$backup);});
 }
}
