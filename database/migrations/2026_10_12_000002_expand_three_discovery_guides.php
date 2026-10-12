<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandThreeDiscoveryGuides extends Migration
{
    public function up()
    {
        $this->appendToLegacyPost('download-youtube-videos-online-format-and-safety-guide', <<<'HTML'
<h2>Detailed pre-trip offline viewing plan</h2>
<p>A useful plan has three layers: what you want to watch, where it will be stored and how you will play it. Start by estimating the journey rather than downloading at random. A 90-minute flight may only need a few familiar videos, while a multi-day trip needs variety without exhausting the device. Group your watch list by purpose—short breaks, children’s viewing, long-form learning or background music—so you can find something quickly when you are offline.</p>
<p>Next, review the account and device. Official YouTube offline viewing is tied to the account and device that prepared it, and availability can vary by location and membership. YouTube’s own <a href="https://support.google.com/youtube/answer/7381437" rel="nofollow noopener" target="_blank">offline viewing FAQs</a> explain that saved videos are managed inside the service rather than as ordinary gallery files. That distinction matters when you switch phones or give a tablet to another traveller.</p>
<h3>Before you leave: five-minute check</h3>
<ol>
<li>Open the account that will be used during the trip.</li>
<li>Review the official app’s Downloads section and remove programmes you no longer need.</li>
<li>Set a sensible default quality for the screen and remaining space.</li>
<li>Play one item with headphones and one through the device speaker.</li>
<li>Reconnect once before departure if the app asks to verify availability.</li>
</ol>
<h3>When offline playback changes after travel</h3>
<p>Offline access can be checked again when the device reconnects, and a title that is no longer available may disappear from the download list. That is normal platform behaviour, not a signal to seek a workaround. Keep a small backup list of podcasts, reading material or locally created media so the trip does not depend on one unavailable video.</p>
<p>For travellers who regularly run out of space, an SD-card-capable Android device may help, but storage support and download settings are device-specific. Check the manufacturer guidance and the official app options before relying on external storage. The best travel setup is simple, tested and within the service’s rules.</p>
HTML
        );

        $this->appendToLegacyPost('youtube-mp4-download-file-size-and-resolution-guide', <<<'HTML'
<h2>How to estimate file size before choosing a quality</h2>
<p>There is no universal “one minute equals this many megabytes” rule because video content is not equally complex. A quiet lecture with a static background can be compressed differently from fast sport, gaming, dance or a scene full of confetti and movement. Audio choices also matter: spoken-word sound generally needs less data than a detailed music track. Treat any displayed size as a useful estimate for that specific file, not as a promise for every video at the same resolution.</p>
<h3>Match resolution to the way you will watch</h3>
<table>
<thead><tr><th>Viewing situation</th><th>Usually worth prioritising</th><th>Reason</th></tr></thead>
<tbody>
<tr><td>Phone on mobile data</td><td>Smaller available file</td><td>It saves storage and is usually adequate on a compact screen.</td></tr>
<tr><td>Tablet or laptop</td><td>Balanced HD option</td><td>It can preserve detail without using unnecessary space.</td></tr>
<tr><td>Large television</td><td>Higher returned quality</td><td>Extra detail is more noticeable when the source and device support it.</td></tr>
<tr><td>Archiving your own work</td><td>Original source or documented export</td><td>It keeps the most reliable record of quality and rights.</td></tr>
</tbody>
</table>
<p>YouTube’s <a href="https://support.google.com/youtube/answer/91449" rel="nofollow noopener" target="_blank">official quality guidance</a> notes that connection speed, screen size, the original upload and browser support can all affect playback quality. This is why a 1080p label is not a complete answer: the original material, codec compatibility and screen distance still influence what viewers actually see.</p>
<h3>File-size problems that are easy to avoid</h3>
<ul>
<li>Do not fill a phone to its last few percent of free storage; apps need room to work.</li>
<li>Use Wi-Fi for large authorised transfers where possible.</li>
<li>Keep only the versions you will actually watch; duplicate copies waste space.</li>
<li>Test playback before a presentation, trip or long offline period.</li>
<li>Choose the source-provided version rather than trying to force a missing resolution.</li>
</ul>
<p>For creators, the clearest long-term approach is to keep the original recording and a documented export. For viewers, a reasonable format is the one that plays reliably, fits the device and matches the intended screen—not necessarily the largest possible file.</p>
HTML
        );

        $this->appendToNewBlog('tiktok-video-downloader-download-tiktok-videos-without-watermark-hd', <<<'HTML'
<h2>A creator-first checklist before you reuse a TikTok video</h2>
<p>A useful quality check is not only technical. Before you save, send or include a TikTok video in another project, ask four questions: Is the post public? Do I have permission for the intended use? Does the creator need credit? Is sharing the original link a better option than making a separate copy? These questions protect the creator’s context and help viewers find the original account.</p>
<h3>When the original link is the best format</h3>
<p>For most casual sharing, the original TikTok link is the most respectful choice. It keeps captions, comments, music attribution and the creator profile together. It also avoids making a viewer think that an older copied file is current when the creator has edited or removed the post. Use a link for recommendations, references and personal messages unless you have a clear reason and permission to use media elsewhere.</p>
<h3>Quality and playback troubleshooting</h3>
<p>If a public video looks soft or does not play smoothly, start with ordinary checks: confirm your connection, update the app or browser, check free storage and open the original post again. Short-form video can be delivered at different qualities depending on the original upload, the viewer’s screen, the current connection and the available format. Repeatedly refreshing a temporary link will not add detail that the source did not provide.</p>
<h3>For schools, brands and client work</h3>
<p>Business and educational use has a higher bar than private viewing. Obtain written permission, save the agreed terms, keep attribution where requested and do not alter a creator’s message in a misleading way. If a post contains music, third-party clips or people who did not grant you rights, permission from the account owner may not be enough. Use original assets or appropriately licensed material when the work will be public, paid or promotional.</p>
<h3>Quick decision guide</h3>
<ol>
<li>If you only want to recommend the video, share the original post link.</li>
<li>If you want to reuse it publicly, ask the creator first and clarify credit.</li>
<li>If the post is private, deleted or region-limited, do not attempt to bypass that control.</li>
<li>If quality matters, review the actual source options and test playback on the target device.</li>
</ol>
<p>These habits make sharing more reliable for viewers and fairer for the people who make the videos.</p>
HTML
        );
    }

    public function down()
    {
        // The expanded editorial content is intentionally not removed on rollback.
    }

    private function appendToLegacyPost(string $slug, string $append): void
    {
        $content = DB::table('blog_posts')->where('slug', $slug)->value('content');
        if ($content !== null && strpos($content, 'Detailed pre-trip offline viewing plan') === false && strpos($content, 'How to estimate file size before choosing a quality') === false) {
            DB::table('blog_posts')->where('slug', $slug)->update([
                'content' => $content . "\n" . $append,
                'read_minutes' => 10,
                'updated_at' => now(),
            ]);
        }
    }

    private function appendToNewBlog(string $slug, string $append): void
    {
        $content = DB::table('blogs')->where('slug', $slug)->value('content');
        if ($content !== null && strpos($content, 'A creator-first checklist before you reuse a TikTok video') === false) {
            DB::table('blogs')->where('slug', $slug)->update([
                'content' => $content . "\n" . $append,
                'updated_at' => now(),
            ]);
        }
    }
}
