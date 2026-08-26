<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformPageSeeder extends Seeder
{
    public function run()
    {
        foreach ($this->platforms() as $data) {
            $content = $this->content($data);
            $platform = Platform::updateOrCreate(['slug' => $data['slug']], [
                'name' => $data['name'],
                'icon' => $data['icon'],
                'h1' => $data['name'] . ' Public Link Format Guide',
                'description' => $data['hero'],
                'meta_title' => $data['name'] . ' Public Link Format Guide',
                'meta_description' => $data['meta'],
                'meta_keywords' => strtolower($data['name']) . ' public link analyzer, ' . strtolower($data['name']) . ' media formats, ' . strtolower($data['name']) . ' format guide',
                'meta_robots' => 'index, follow',
                'content' => json_encode(['time' => time(), 'blocks' => $content, 'version' => '2.29.1'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'status' => 'active',
            ]);

            DB::table('faqs')->where('page', $platform->slug)->delete();
            foreach ($this->faqs($data) as $index => $faq) {
                DB::table('faqs')->insert([
                    'question' => $faq[0], 'answer' => $faq[1], 'page' => $platform->slug,
                    'is_active' => true, 'sort_order' => $index + 1,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    private function content(array $p): array
    {
        $sections = [
            ['About the ' . $p['name'] . ' public link guide', [
                "The {$p['name']} public link guide is for people who need to review media they own or have permission to use. It accepts supported public {$p['name']} links, analyzes the page, and displays formats that the source makes available. {$p['overview']} The workflow runs in a modern browser without a separate desktop program or browser extension.",
                "A link analyzer cannot create quality that is missing from the original upload. Resolution, audio, file size, and available formats depend on the source media and the way {$p['name']} delivers it. Some links may provide several MP4 resolutions, while others may expose only one combined file or separate video and audio streams. The results area is designed to make those differences visible before a file is used.",
            ]],
            ['Supported ' . $p['name'] . ' links and media', [
                "{$p['links']} Links should be publicly reachable in a private browser window without signing in. Shortened share links may redirect before analysis, and that redirect must resolve to a valid {$p['name']} page. Links copied from a private message, drafts area, restricted group, paid post, or account-only feed normally cannot be processed because the downloader has no access to the viewer's private session.",
                "{$p['media']} Availability changes when {$p['name']} updates its website or delivery system. If a valid public link stops working, copy it again from the official share menu, remove tracking text after the essential URL when appropriate, and retry. A freshly copied canonical link is more reliable than an old redirect saved in a messaging app.",
            ]],
            ['How to review a public ' . $p['name'] . ' link', [
                "Open {$p['name']} and navigate to the public video you are authorized to use. Use the platform's share menu and choose Copy Link. Return to Solution Hub, paste the complete URL into the field near the top of this page, and start analysis. The analyzer checks the host, requests public metadata, and lists the formats it can detect.",
                "Review the title and thumbnail to confirm that the correct media was found. Choose a format according to your device, connection, and storage space. A higher resolution can look sharper on a large display but usually produces a larger file. A lower resolution is often more practical for messaging, limited mobile data, or a small screen. Use any detected file responsibly and only when you have permission.",
            ]],
            ['Video quality, MP4, and audio options', [
                "MP4 is commonly offered because it works in modern browsers, gallery apps, media players, and editing software. The exact codec inside an MP4 file can still vary. Older devices may play H.264 more reliably, while newer hardware may support efficient codecs. If a downloaded file does not open in one application, try an updated browser or media player before assuming the file is damaged.",
                "Resolution labels such as 360p, 480p, 720p, or 1080p describe the frame height, not a guaranteed level of visual detail. The original bitrate, compression, frame rate, and source quality also matter. Audio-only results appear only when a usable audio stream is exposed. Solution Hub reports detected options rather than inventing formats or promising a resolution unavailable from {$p['name']}.",
            ]],
            ['Using the downloader on Android, iPhone, and desktop', [
                "On Android, downloaded files usually appear in the Downloads folder and may also become visible in the gallery after media scanning. Chrome, Firefox, Edge, and other current browsers can handle the workflow. If storage permission is requested, review the browser prompt carefully. Keeping enough free space prevents large HD files from failing near the end of a transfer.",
                "On iPhone and iPad, Safari normally places files in the Downloads folder inside the Files app. From there, an authorized file can be previewed or moved to another folder. On Windows, macOS, and Linux, the browser's download tray shows progress and the destination. Browser settings can be changed to ask where each file should be saved.",
            ]],
            ['Why a ' . $p['name'] . ' link may not work', [
                "The most common reason is privacy. A page visible only while logged in is not a public source, even when its URL can be copied. Other causes include a deleted upload, geographic restriction, age gate, expired share redirect, live broadcast that has not finished processing, unsupported media type, temporary rate limit, or a change in {$p['name']}'s delivery markup.",
                "First open the URL in a private window. If the media cannot play there, the downloader is unlikely to reach it. If it is public, refresh this page, paste the URL again, disable aggressive script-blocking for this site, and try another current browser. Avoid repeatedly submitting the same failing link at high speed because automated protection may temporarily delay requests.",
            ]],
            ['Privacy, safety, and responsible use', [
                "Do not submit private, confidential, paywalled, or access-controlled URLs. A public-link tool should never ask for your {$p['name']} password, session cookie, or account token. Keep account credentials only on the official platform. Treat unknown download files cautiously, use an updated operating system, and verify that the title and extension match the media you expected.",
                "Downloading does not transfer copyright or permission. Save only material you created, public-domain media, content covered by an appropriate license, or media for which the owner gave you permission. Do not redistribute copyrighted work, remove attribution, bypass access controls, or use saved media to impersonate another person. Local law and the source platform's terms still apply.",
            ]],
            ['Choosing the right format for your needs', [
                "For offline viewing on a phone, a moderate MP4 resolution often provides a useful balance between clarity and file size. For editing, select the highest legitimate source quality that your editor and storage can handle. For presentations, confirm the display resolution and test playback before the event. For archiving your own work, keep the original upload whenever possible because a platform copy may already be compressed.",
                "File-size estimates are useful but can vary because remote servers may not always publish an exact content length. Long duration, high frame rate, detailed motion, and high bitrate increase size. Downloading over stable Wi-Fi reduces interruptions. If the browser offers multiple files with similar labels, compare resolution and size rather than assuming the first option is always best.",
            ]],
            ['Understanding public and private content', [
                "Public content can be opened by an ordinary visitor without account-specific approval. Private content includes direct messages, close-friends posts, restricted groups, drafts, subscriber-only media, and pages protected by a login or payment. A copied address does not make restricted content public. Solution Hub intentionally works with supported public links and does not provide a method to bypass authentication.",
                "Creators can change visibility or delete media at any time. A previously working URL may therefore stop resolving. Respect those choices. If you need a copy of restricted media that belongs to you, use the export or download feature supplied by {$p['name']}, or request the original file directly from the creator.",
            ]],
            ['Troubleshooting slow or interrupted transfers', [
                "Processing time depends on the source response, number of formats, video duration, and network conditions. Keep the tab open while analysis runs. If the result takes unusually long, confirm that your connection is stable and retry once. Switching repeatedly between networks can invalidate an in-progress request. Very large media may need more preparation time than a short clip.",
                "If a transfer stops, clear only the failed item from the browser download list and request a fresh link because temporary media URLs can expire. Check available storage and battery-saving settings. Corporate, school, or public networks may block media hosts; use a network you are authorized to use. Never install an unknown extension presented by an unrelated pop-up.",
            ]],
            ['Platform-specific notes for ' . $p['name'], [
                $p['specific'],
                $p['quality'],
            ]],
            ['A practical checklist before using a public link', [
                "Confirm that the URL belongs to {$p['domain']}, that the page is public, and that you have permission to save the media. Check the preview, title, duration, file extension, resolution, and approximate size. Select a destination with enough storage. Keep the original attribution and any license information when the creator requires it.",
                "After using a detected format, open the file before closing the browser tab. If audio or video is missing, return to the results and select a combined format when available. Store important authorized files with descriptive names and backups. Delete files you no longer need, especially on shared devices, and avoid uploading someone else's work to another service without consent.",
            ]],
        ];

        // Keep universal instructions concise and devote half of every page to
        // source-specific link types, media behavior, restrictions, and formats.
        $universalSections = [$sections[2], $sections[3], $sections[4], $sections[6]];
        $platformSections = [
            ['What makes ' . $p['name'] . ' different', [
                $p['overview'],
                "Unlike a generic file URL, a {$p['name']} page can change its media response according to post type, creator settings, region, and processing status. Solution Hub therefore reports only the public resources detected for the submitted {$p['domain']} address instead of promising the same choices for every post.",
            ]],
            ['Accepted ' . $p['name'] . ' URL patterns', [
                $p['links'],
                "Before submitting a {$p['name']} link, check that the browser address belongs to {$p['domain']} or an official share domain described above. Open it in a private window to confirm that it does not depend on your signed-in session. Copied tracking parameters can usually remain, but the underlying post must still resolve publicly.",
            ]],
            [$p['name'] . ' media and format behavior', [
                $p['media'],
                $p['specific'],
            ]],
            [$p['name'] . ' restrictions and quality limits', [
                $p['quality'],
                "A result from {$p['name']} reflects the source response at that moment. Creator privacy changes, removal, regional rules, unfinished processing, or delivery updates can change what is available later. If no usable option appears, do not install an unrelated extension or provide account credentials to bypass that limitation.",
            ]],
        ];
        $sections = array_merge($platformSections, $universalSections);

        $blocks = [];
        foreach ($sections as $section) {
            $blocks[] = ['type' => 'header', 'data' => ['text' => $section[0], 'level' => 2]];
            foreach ($section[1] as $paragraph) $blocks[] = ['type' => 'paragraph', 'data' => ['text' => $paragraph]];
        }

        // Never pad pages to an arbitrary word count. Repeated boilerplate is less
        // useful than a shorter platform-specific guide.
        return $blocks;
    }

    private function faqs(array $p): array
    {
        return [
            ["Is this {$p['name']} link analyzer available online?", 'Yes. The public-link analysis workflow runs in the browser. Available formats depend on the source.'],
            ["Can I use private {$p['name']} videos?", 'No. Private, login-only, paid, restricted, or account-specific content is not supported.'],
            ["Which {$p['name']} video quality is available?", 'Quality depends on the original upload and formats exposed by the platform. The tool cannot create missing quality.'],
            ["Does it work on iPhone and Android?", 'Yes. Use a current browser. iPhone downloads normally appear in Files, while Android downloads normally appear in Downloads.'],
            ["Why is my {$p['name']} link not working?", 'Check that it is a complete public URL, recopy it from the official share menu, and confirm it opens in a private browser window.'],
            ["Is using {$p['name']} media legal?", 'Only use content you own or have permission to save, and follow applicable law and the platform terms.'],
        ];
    }

    private function platforms(): array
    {
        return [
            $this->p('YouTube', 'youtube-video-downloader', 'youtube', 'youtube.com', 'Analyze supported public YouTube links and review available MP4 and audio formats.', 'YouTube pages can include standard videos, Shorts, and public live-stream replays after processing.', 'Use watch URLs, youtu.be share links, and public Shorts URLs. Playlist pages are not treated as a promise of bulk access.', 'Public videos and Shorts may expose combined or separate audio and video streams.', 'YouTube frequently serves higher resolutions as separate video and audio tracks. A prepared combined result may take longer than a lower-resolution progressive MP4.', 'The displayed resolution depends on the uploaded master and YouTube transcodes. Private, members-only, rented, age-restricted, and region-blocked videos may not be accessible.'),
            $this->p('Facebook', 'facebook-video-downloader', 'facebook', 'facebook.com', 'Analyze supported public Facebook videos and Reels after reviewing available formats.', 'Facebook hosts feed videos, public page posts, Watch URLs, and Reels with different link patterns.', 'Use public Watch, Reel, page-post, fb.watch, or video URLs copied from the Share menu.', 'Public videos and Reels can be analyzed when they open without a Facebook login.', 'Facebook may provide separate HD and SD resources, and availability varies with the original upload.', 'Friends-only posts, private groups, Stories requiring a session, and login-wall URLs are not public and cannot be processed.'),
            $this->p('Instagram', 'instagram-video-downloader', 'instagram', 'instagram.com', 'Analyze supported public Instagram Reel and video links in your browser.', 'Instagram media includes Reels, video posts, and public profile content, while visibility depends heavily on account privacy.', 'Use complete public /reel/ or /p/ links copied from Instagram sharing controls.', 'Public Reels and video posts may expose a downloadable media resource and thumbnail.', 'Instagram often delivers a limited set of optimized MP4 variants rather than a long resolution list.', 'Private-account posts, Close Friends Stories, direct-message media, and login-only pages are not supported.'),
            $this->p('TikTok', 'tiktok-video-downloader', 'tiktok', 'tiktok.com', 'Analyze supported public TikTok video links and review available media options.', 'TikTok uses full video URLs and shortened share links that redirect to the canonical post.', 'Use a public /@user/video/ URL or an official vm.tiktok.com or vt.tiktok.com share link.', 'Public posts can include standard video, captions, author information, and cover images.', 'Available resources may include platform-processed versions; watermark behavior depends on the source response and is not guaranteed.', 'Private accounts, friends-only posts, removed videos, age restrictions, and regional limits can prevent access.'),
            $this->p('Twitter / X', 'twitter-video-downloader', 'x', 'x.com', 'Analyze supported public X videos from accessible post links.', 'X posts may contain one video, animated media, or several media items attached to a public status.', 'Use complete x.com or twitter.com status URLs that open publicly.', 'Public status videos and animated media may provide multiple bitrate variants.', 'X commonly exposes several MP4 bitrate options; the highest pixel dimensions do not always mean the smallest file.', 'Protected accounts, deleted posts, sensitive-content gates, spaces, and login-limited media may not resolve.'),
            $this->p('Vimeo', 'vimeo-video-downloader', 'vimeo', 'vimeo.com', 'Analyze supported public Vimeo links and review creator-authorized formats.', 'Vimeo is widely used for portfolios, demonstrations, courses, and embedded professional video.', 'Use a public vimeo.com video URL or publicly accessible player link.', 'Public videos may provide progressive MP4 files or streaming playlists depending on owner settings.', 'Download availability is strongly influenced by the uploader settings and Vimeo plan configuration.', 'Password-protected, private, unlisted-with-restrictions, paid, and domain-locked videos are not bypassed.'),
            $this->p('Dailymotion', 'dailymotion-video-downloader', 'dailymotion', 'dailymotion.com', 'Analyze public Dailymotion video URLs and choose an available quality.', 'Dailymotion publishes news, entertainment, creator uploads, and embedded videos using standard and short URLs.', 'Use public dailymotion.com/video/ or dai.ly links.', 'Public videos may expose several stream qualities and a preview image.', 'Detected qualities depend on Dailymotion encodes, regional availability, and the source upload.', 'Geo-blocked, age-gated, private, deleted, or partner-restricted videos may not be accessible.'),
            $this->p('Pinterest', 'pinterest-video-downloader', 'pinterest', 'pinterest.com', 'Analyze supported public Pinterest video Pin links and save authorized media.', 'Pinterest uses Pins and idea-style visual posts that may contain video, images, descriptions, and outbound links.', 'Use a public pin URL or official pin.it share redirect that resolves to a video Pin.', 'Public video Pins may expose an MP4 rendition and cover image.', 'Pinterest often supplies mobile-optimized variants; resolution choices depend on the uploaded Pin.', 'Secret boards, private profiles, removed Pins, image-only Pins, and login-restricted content cannot produce a public video result.'),
        ];
    }

    private function p($name, $slug, $icon, $domain, $meta, $overview, $links, $media, $specific, $quality): array
    {
        return compact('name', 'slug', 'icon', 'domain', 'meta', 'overview', 'links', 'media', 'specific', 'quality') + ['hero' => $meta];
    }
}
