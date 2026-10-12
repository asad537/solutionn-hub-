<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandFacebookAndYoutubeReviewGuides extends Migration
{
    public function up()
    {
        $this->replacePost('facebook-reel-download-save-reels-in-mp4', [
            'title' => 'Facebook Reels: A Practical Review Checklist for Public Links',
            'category' => 'Facebook guide',
            'excerpt' => 'Review a public Facebook Reel for picture, sound, duration and portrait framing before you keep an authorised copy or share the original link.',
            'meta_title' => 'Facebook Reel Review Checklist: Quality, Sound & Framing',
            'meta_description' => 'Use this practical Facebook Reel review checklist to inspect picture quality, audio, duration, portrait framing and creator context for public links.',
            'image' => '/images/blog/generated/facebook-reel-review-checklist.png',
            'image_alt' => 'Phone screen and checklist used to review picture, audio, duration and creator context for a public Facebook Reel',
            'read_minutes' => 8,
            'content' => <<<'HTML'
<p>A Facebook Reel can look excellent in the feed yet feel different when watched later on another screen. Before you keep an authorised copy, recommend it to a friend, or use it as a reference, review the actual video rather than trusting a label such as “HD” or “MP4”. Picture detail, sound, portrait framing, file duration and creator context each affect whether the Reel remains useful.</p>
<p>This guide is for public material that you created, licensed, or have permission to use. A public URL does not automatically grant permission to repost, edit or use someone else’s Reel in a business project. When sharing is the goal, the original Facebook link is often the clearest option because it preserves the creator name, caption and conversation around the post.</p>

<h2>Start with the individual public Reel link</h2>
<p>Open the Reel itself and confirm that it plays without access restrictions. Copy the individual Reel or post address, not the profile page, a search result, a private-group link or a general Facebook feed. This makes it easier to identify the exact source later and avoids confusing one video with another from the same creator.</p>
<p>Before going further, note the creator name and the reason you are keeping the link. A simple filename or note such as “Creator-name — recipe demonstration — reference only” is much more useful than an anonymous file called “video-final-final.mp4”.</p>

<h2>Facebook Reel review checklist</h2>
<ol>
<li><strong>Picture:</strong> pause on captions, faces, small products or fine detail. Check whether these remain sharp at normal viewing size.</li>
<li><strong>Framing:</strong> Reels are usually vertical. Confirm that the top and bottom edges are intact and that captions are not cropped.</li>
<li><strong>Sound:</strong> listen to a spoken section as well as a music section. Check volume, sync and whether the sound is needed for context.</li>
<li><strong>Duration:</strong> compare the opening and final seconds against the original Reel. A short or incomplete file is not a reliable reference.</li>
<li><strong>Context:</strong> preserve the source link and credit. Captions, disclosure notes and comments can change the meaning of a short clip.</li>
</ol>

<h2>Why an MP4 label is not a quality promise</h2>
<p>MP4 describes a media container. It does not tell you the original resolution, bitrate, audio settings or whether a player will display orientation metadata correctly. Two MP4 files can look very different: one may be a sharp source export, while another has already passed through several rounds of compression.</p>
<p>The best test is playback on the device where the video will actually be viewed. On a phone, inspect caption-heavy moments; on a desktop, look for blockiness around moving hands, hair, text and gradients. If the file will be included in a presentation, test it in the presentation software before the event rather than seconds before it starts.</p>

<h2>Choose the right action for the situation</h2>
<table>
<thead><tr><th>What you need</th><th>Best next step</th><th>Why it helps</th></tr></thead>
<tbody>
<tr><td>Recommend a Reel to a friend</td><td>Share the original Facebook link</td><td>The creator, caption and latest version remain connected.</td></tr>
<tr><td>Keep a reference for your own work</td><td>Store the source link with a short note</td><td>You can return to the original context and verify it later.</td></tr>
<tr><td>Reuse a Reel in public content</td><td>Ask the creator for written permission first</td><td>Permission, scope and credit expectations are clear.</td></tr>
<tr><td>Show a Reel in a presentation</td><td>Test the authorised source on the target screen and audio system</td><td>You avoid surprises with orientation, volume or playback.</td></tr>
</tbody>
</table>

<h2>Fix ordinary playback issues before assuming the video is broken</h2>
<p>If a public Reel appears soft, stops early or plays without sound, first check the connection, browser or app updates, device storage and the original post. Try the original link on one other device. This distinguishes a local playback problem from a source that has changed or is no longer available. Do not try to bypass private, deleted, age-gated or region-restricted access controls.</p>
<p>When an authorised file is needed, keep one verified working copy and avoid repeated conversions. Each additional encoding pass may make lettering and fast movement look worse. For public-link guidance, see the <a href="/facebook-video-downloader">Facebook video guide</a>; for other supported sources, visit <a href="/supported-platforms">supported platforms</a>.</p>

<h2>Frequently asked questions</h2>
<h3>How do I know whether a Facebook Reel is public?</h3>
<p>Open the individual Reel link in a normal browser session. If it requires a private-group membership, login, approval or another access control, treat it as non-public and do not attempt to work around that restriction.</p>
<h3>Why does a Reel look different on my laptop than on my phone?</h3>
<p>Screen size, player support, connection speed and the original upload all change how detail is perceived. Compare it at normal viewing size on the device that matters most.</p>
<h3>Can I repost a public Reel?</h3>
<p>Public visibility does not equal reuse permission. Share the original link unless you have permission from the rights holder for the new use.</p>
HTML,
        ]);

        $this->replacePost('download-yt-video-mobile-and-desktop-guide', [
            'title' => 'Review a YouTube Video on Mobile and Desktop: A Quality Checklist',
            'category' => 'YouTube guide',
            'excerpt' => 'A practical way to review a public YouTube video across phone and desktop screens, compare picture and sound, and keep the source context organised.',
            'meta_title' => 'Review YouTube Videos on Mobile & Desktop: Quality Checklist',
            'meta_description' => 'Review a public YouTube video on mobile and desktop with a practical checklist for quality, captions, sound, screen size and creator context.',
            'image' => '/images/blog/generated/youtube-mobile-desktop-review.png',
            'image_alt' => 'Phone and desktop monitor used to compare playback quality of a public YouTube video',
            'read_minutes' => 9,
            'content' => <<<'HTML'
<p>Reviewing a YouTube video on a phone and a desktop is not about chasing the largest resolution label. It is about checking whether the picture, captions and sound work for the screen, connection and task you have in mind. A clip that is perfectly clear on a small phone may reveal compression on a large monitor; a detailed desktop view may be unnecessary when you are watching a short tutorial on the move.</p>
<p>Use this checklist for public videos you own, have permission to use, or are reviewing through the original YouTube experience. Keep the creator and source URL with your notes. For a simple recommendation, sharing the original video link is usually preferable to creating a separate copy because it keeps the channel, captions and current description together.</p>

<h2>Review the source before you judge the quality</h2>
<p>Start with the individual video page. Confirm the title, creator, length and upload context. A search result, short preview or channel landing page is not the same thing as the full video. Check whether the video has captions, an important description link or an update pinned by the creator; these details may be more valuable than an extra step of picture sharpness.</p>
<p>For work, study or editorial research, record the URL, creator, date accessed and the exact moment you need to refer to. This makes it easier to find the source again and reduces the risk of using an outdated or similarly titled video.</p>

<h2>Mobile and desktop quality checklist</h2>
<ol>
<li><strong>Watch the opening:</strong> confirm that the video begins cleanly and that the first spoken words are audible.</li>
<li><strong>Check fine detail:</strong> pause on on-screen text, diagrams, faces or product labels. Do they remain readable at a natural viewing distance?</li>
<li><strong>Listen for sync:</strong> speech should match lip movement; use headphones for a quick check of low-volume detail.</li>
<li><strong>Test captions:</strong> enable captions where available and make sure they do not cover important information.</li>
<li><strong>Compare screens:</strong> view the same 20–30 second section on your phone and desktop. Look for the differences that matter to your task.</li>
</ol>

<h2>How screen size changes the best setting</h2>
<table>
<thead><tr><th>Scenario</th><th>What to prioritise</th><th>Useful check</th></tr></thead>
<tbody>
<tr><td>Phone while travelling</td><td>Reliable playback and readable captions</td><td>Watch a short section on the actual connection you expect to use.</td></tr>
<tr><td>Tablet or laptop lesson</td><td>Text clarity and sound</td><td>Pause on diagrams and listen through an explanation.</td></tr>
<tr><td>Large external monitor</td><td>Source detail and stable playback</td><td>Inspect fast movement and small labels at normal distance.</td></tr>
<tr><td>Presentation or class</td><td>Permission, audio routing and a tested source</td><td>Run the complete segment on the room’s equipment in advance.</td></tr>
</tbody>
</table>
<p>Quality availability can change with the original upload, current connection, browser support and the device screen. YouTube’s <a href="https://support.google.com/youtube/answer/91449" target="_blank" rel="nofollow noopener">official quality guidance</a> explains that playback quality is adaptive and may not be identical across devices. Use the returned or available quality as information, not as a guarantee that a particular label will be present everywhere.</p>

<h2>When a video looks blurry, buffers or has no sound</h2>
<p>First, rule out ordinary causes. Confirm that the internet connection is stable, close bandwidth-heavy apps, update the browser or app, and check the device audio output. A Bluetooth device can silently take over audio; a battery-saving setting can affect background playback; a small phone screen can hide detail that is obvious on a desktop.</p>
<p>If the issue happens only on one device, test the same timestamp elsewhere. If it happens everywhere, the source itself may have changed. Do not use unofficial workarounds to access private, deleted, region-limited or age-restricted videos. For offline viewing, use the options made available by YouTube and the rights holder for your location and account.</p>

<h2>Keep a clean research or viewing record</h2>
<p>A short record keeps a useful video review from becoming a pile of unnamed browser tabs. Save the video title, creator, URL, timestamp, date and a one-line note about why it matters. If you are using a creator’s material in public work, save the relevant licence or permission as well. This is clearer for collaborators and more respectful to the person who made the video.</p>
<p>For more guidance on public-link checks, visit the <a href="/youtube-video-downloader">YouTube platform guide</a>. You can also compare workflows for other services in the <a href="/supported-platforms">supported platforms directory</a>.</p>

<h2>Frequently asked questions</h2>
<h3>Why does the same YouTube video look sharper on one device?</h3>
<p>Screen size, display density, connection conditions and supported playback settings can all influence how the video appears. Compare the same moment at normal viewing size before deciding that one version is “better”.</p>
<h3>Should I watch at the highest quality available?</h3>
<p>Choose a level that fits the screen, connection and remaining storage. More detail is helpful when it is visible and reliable; it is not always useful on a small screen or slow connection.</p>
<h3>Can I use a public YouTube video in my own project?</h3>
<p>Public access does not automatically grant reuse rights. Check the creator’s licence and obtain permission where needed.</p>
HTML,
        ]);
    }

    public function down()
    {
        // This editorial improvement intentionally remains in place on rollback.
    }

    private function replacePost(string $slug, array $attributes): void
    {
        if (DB::table('blog_posts')->where('slug', $slug)->exists()) {
            DB::table('blog_posts')->where('slug', $slug)->update($attributes + ['updated_at' => now()]);
        }
    }
}
