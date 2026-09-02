<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    private $adminPassword = 'admin@123';

    public function login()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if ($request->password === $this->adminPassword) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Invalid password.']);
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');

        $data = $this->getDashboardData();
        $stats      = $data['stats'];
        $recentLogs = $data['recentLogs'];
        $weeklyData = $data['weeklyData'];
        $platformData = $data['platformData'];
        $countryData = $data['countryData'];
        $sourceData = $data['sourceData'];
        $topPages = $data['topPages'];

        return view('admin.dashboard', compact('stats', 'recentLogs', 'weeklyData', 'platformData', 'countryData', 'sourceData', 'topPages'));
    }

    /**
     * AJAX endpoint — returns fresh dashboard data as JSON.
     */
    public function dashboardData()
    {
        if (!session('admin_logged_in')) return response()->json(['error' => 'Unauthorized'], 401);
        return response()->json($this->getDashboardData());
    }

    /** Publish any blog drafts from the admin dashboard. */
    public function publishDraftBlogs()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');

        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\PublishAllBlogsSeeder',
            '--force' => true,
        ]);

        return redirect()->route('admin.dashboard')->with('success', trim(Artisan::output()));
    }

    /**
     * Build all dashboard stats from the download_logs table.
     */
    private function getDashboardData(): array
    {
        // ── Top stats ────────────────────────────────────────────────────
        $downloads = DB::table('download_logs')->where('type', 'download');
        $totalDownloads   = (clone $downloads)->where('status', true)->count();
        $failedDownloads  = (clone $downloads)->where('status', false)->count();
        $todayDownloads   = (clone $downloads)->where('status', true)->whereDate('created_at', today())->count();
        $weekDownloads    = (clone $downloads)->where('status', true)->where('created_at', '>=', now()->startOfWeek())->count();
        $totalExtractions = DB::table('download_logs')->where('type', 'extraction')->count();
        $hasVisits = Schema::hasTable('analytics_visits');
        $activeUsers = $hasVisits ? DB::table('analytics_visits')->where('last_seen_at', '>=', now()->subMinutes(5))->distinct()->count('session_id') : 0;
        $todayVisitors = $hasVisits ? DB::table('analytics_visits')->whereDate('created_at', today())->count() : 0;
        $pageViews = $hasVisits ? DB::table('analytics_visits')->count() : 0;
        $attempts = $totalDownloads + $failedDownloads;

        $stats = [
            'total_downloads'   => $totalDownloads,
            'today_downloads'   => $todayDownloads,
            'total_extractions' => $totalExtractions,
            'active_users'      => $activeUsers,
            'failed_downloads'  => $failedDownloads,
            'week_downloads'    => $weekDownloads,
            'today_visitors'    => $todayVisitors,
            'page_views'        => $pageViews,
            'success_rate'      => $attempts ? round(($totalDownloads / $attempts) * 100, 1) : 0,
        ];

        // ── Last 7 days bar chart ────────────────────────────────────────
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i);
            $count = DB::table('download_logs')
                ->whereDate('created_at', $date->toDateString())
                ->where('type', 'download')->where('status', true)->count();
            $weeklyData[] = [
                'label' => $date->format('D'),
                'count' => $count,
            ];
        }

        // ── Platform breakdown ───────────────────────────────────────────
        $platformColors = [
            'YouTube'    => '#FF0000',
            'Instagram'  => '#E4405F',
            'TikTok'     => '#00F2EA',
            'Facebook'   => '#1877F2',
            'Twitter'    => '#1DA1F2',
            'Pinterest'  => '#E60023',
            'Vimeo'      => '#1AB7EA',
            'Dailymotion'=> '#0066DC',
            'Other'      => '#6B7280',
        ];

        $total = DB::table('download_logs')->count() ?: 1;
        $rawPlatforms = DB::table('download_logs')
            ->select('platform', DB::raw('COUNT(*) as cnt'))
            ->groupBy('platform')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $platformData = $rawPlatforms->map(function ($row) use ($total, $platformColors) {
            return [
                'name'  => $row->platform,
                'color' => $platformColors[$row->platform] ?? '#6B7280',
                'pct'   => (int) round(($row->cnt / $total) * 100),
            ];
        })->values()->toArray();

        // If no data yet, show placeholder
        if (empty($platformData)) {
            $platformData = [
                ['name' => 'YouTube',   'color' => '#FF0000', 'pct' => 0],
                ['name' => 'Instagram', 'color' => '#E4405F', 'pct' => 0],
                ['name' => 'TikTok',    'color' => '#00F2EA', 'pct' => 0],
                ['name' => 'Facebook',  'color' => '#1877F2', 'pct' => 0],
                ['name' => 'Twitter',   'color' => '#1DA1F2', 'pct' => 0],
            ];
        }

        // ── Recent 20 activity logs ──────────────────────────────────────
        $recentLogs = DB::table('download_logs')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->toArray();

        $countryData = $hasVisits ? DB::table('analytics_visits')
            ->select('country', 'country_code', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as users'))
            ->groupBy('country', 'country_code')->orderByDesc('views')->limit(8)->get()->toArray() : [];

        $sourceData = $hasVisits ? DB::table('analytics_visits')
            ->select('source', DB::raw('COUNT(*) as views'))->groupBy('source')->orderByDesc('views')->limit(6)->get()->toArray() : [];

        $topPages = $hasVisits ? DB::table('analytics_visits')
            ->select('path', DB::raw('COUNT(*) as views'))->groupBy('path')->orderByDesc('views')->limit(6)->get()->toArray() : [];

        return compact('stats', 'weeklyData', 'platformData', 'recentLogs', 'countryData', 'sourceData', 'topPages');
    }

    public function homepageEdit()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $settings = DB::table('homepage_settings')->first();
        $faqs = DB::table('faqs')->orderBy('sort_order')->get();
        $seo = \App\Models\PageSeo::where('page_name', 'home')->first();
        
        // Decode platforms or provide defaults
        $platforms = [];
        if ($settings && $settings->platforms_data) {
            $platforms = json_decode($settings->platforms_data, true);
        }
        
        if (empty($platforms)) {
            $platforms = [
                ['name' => 'YouTube', 'icon' => 'fab fa-youtube'],
                ['name' => 'Instagram', 'icon' => 'fab fa-instagram'],
                ['name' => 'TikTok', 'icon' => 'fab fa-tiktok'],
                ['name' => 'Facebook', 'icon' => 'fab fa-facebook'],
                ['name' => 'Twitter', 'icon' => 'fab fa-twitter'],
            ];
        }

        $allIcons = [
            'fab fa-youtube', 'fab fa-instagram', 'fab fa-tiktok', 'fab fa-facebook', 
            'fab fa-twitter', 'fab fa-pinterest', 'fab fa-vimeo-v', 'fab fa-dailymotion', 
            'fas fa-video', 'fas fa-download', 'fas fa-link', 'fas fa-globe'
        ];

        return view('admin.homepage', compact('settings', 'faqs', 'platforms', 'allIcons', 'seo'));
    }

    public function homepageSave(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        DB::table('homepage_settings')->updateOrInsert(
            ['id' => 1],
            [
                'hero_heading'      => $request->hero_heading,
                'hero_button_text'  => $request->hero_button_text ?? '',
                'hero_button_url'   => $request->hero_button_url ?? '',
                'hero_description'  => $request->hero_description,
                'sites_heading'     => $request->sites_heading,
                'sites_description' => $request->sites_description,
                'platforms_data'    => $request->platforms_data,
                'updated_at'        => now(),
            ]
        );

        \App\Models\PageSeo::updateOrCreate(
            ['page_name' => 'home'],
            [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_robots' => $request->meta_robots,
            ]
        );

        return back()->with('success', 'Homepage updated successfully!');
    }

    // ── Download Page Settings ──────────────────────────────────────────────────
    public function downloadPage()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $settings = \App\Models\DownloadPageSetting::first();
        $seo = \App\Models\PageSeo::where('page_name', 'download')->first();
        return view('admin.download_page', compact('settings', 'seo'));
    }

    public function downloadPageSave(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        \App\Models\DownloadPageSetting::updateOrCreate(
            ['id' => 1],
            [
                'h1_heading' => $request->h1_heading,
                'description' => $request->description,
                'btn_text' => $request->btn_text,
                'btn_link' => $request->btn_link,
            ]
        );

        \App\Models\PageSeo::updateOrCreate(
            ['page_name' => 'download'],
            [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_robots' => $request->meta_robots,
            ]
        );

        return back()->with('success', 'Download Page updated successfully!');
    }

    // ── Footer Settings ──────────────────────────────────────────────────────────
    public function footerSettings()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $settings = \App\Models\FooterSetting::first();
        $platforms = \App\Models\Platform::orderBy('name')->get();
        return view('admin.footer_settings', compact('settings', 'platforms'));
    }

    public function footerSettingsSave(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $platforms = $request->input('platforms', []);

        \App\Models\FooterSetting::updateOrCreate(
            ['id' => 1],
            [
                'description' => $request->description,
                'platforms' => $platforms,
            ]
        );

        return back()->with('success', 'Footer Settings updated successfully!');
    }

    // ── SEO Methods ────────────────────────────────────────────────────────────
    public function seoSettings()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        // Only load static pages for the "Static Pages" section
        $staticPageNames = ['privacy-policy', 'terms-of-service', 'disclaimer'];
        $seoSettings = \App\Models\PageSeo::whereIn('page_name', $staticPageNames)->get();
        
        // Ensure they exist in DB so tabs render correctly
        if ($seoSettings->count() < count($staticPageNames)) {
            foreach ($staticPageNames as $pageName) {
                \App\Models\PageSeo::firstOrCreate(['page_name' => $pageName]);
            }
            $seoSettings = \App\Models\PageSeo::whereIn('page_name', $staticPageNames)->get();
        }

        return view('admin.seo_settings', compact('seoSettings'));
    }

    public function seoSettingsUpdate(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'page_name' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_robots' => 'nullable|string',
        ]);

        \App\Models\PageSeo::updateOrCreate(
            ['page_name' => $request->page_name],
            [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_robots' => $request->meta_robots,
            ]
        );

        return back()->with('success', 'SEO settings updated for ' . ucfirst($request->page_name) . '!');
    }

    // ── FAQ Methods (Home) ────────────────────────────────────────────
    public function faqIndex()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $faqs = DB::table('faqs')->where('page', 'home')->orderBy('sort_order')->get();
        return view('admin.faqs', compact('faqs'));
    }

    public function faqStore(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
        ]);
        $maxOrder = DB::table('faqs')->where('page', 'home')->max('sort_order') ?? 0;
        DB::table('faqs')->insert([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'page'       => 'home',
            'sort_order' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'FAQ added to Home Page!');
    }

    public function faqEdit($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $faq = DB::table('faqs')->where('id', $id)->first();
        if (!$faq) abort(404);
        return view('admin.faqs_edit', compact('faq'));
    }

    public function faqUpdate(Request $request, $id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
        ]);
        
        $updateData = [
            'question' => $request->question,
            'answer'   => $request->answer,
            'updated_at' => now(),
        ];
        
        // If editing an FAQ that has a category, allow updating it
        if ($request->has('category')) {
            $updateData['category'] = $request->category;
        }

        DB::table('faqs')->where('id', $id)->update($updateData);

        // Redirect back to the appropriate page based on the FAQ's origin
        $faq = DB::table('faqs')->where('id', $id)->first();
        if ($faq && $faq->page === 'faq_page') {
            return redirect()->route('admin.faq_page')->with('success', 'FAQ updated!');
        }
        return redirect()->route('admin.faqs')->with('success', 'FAQ updated!');
    }

    public function faqDelete($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        DB::table('faqs')->where('id', $id)->delete();
        return back()->with('success', 'FAQ deleted!');
    }

    // ── FAQ Page Methods (Dedicated) ──────────────────────────────────
    public function faqPageSettings()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $faqs = DB::table('faqs')->where('page', 'faq_page')->orderBy('category')->orderBy('sort_order')->get();
        $categories = DB::table('faqs')->where('page', 'faq_page')->distinct()->pluck('category')->filter()->values();
        $settings = DB::table('homepage_settings')->first();
        $seo = \App\Models\PageSeo::where('page_name', 'faqs')->first();
        return view('admin.faq_page', compact('faqs', 'categories', 'settings', 'seo'));
    }

    public function faqPageStore(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'category' => 'required|string',
        ]);
        $maxOrder = DB::table('faqs')->where('page', 'faq_page')->where('category', $request->category)->max('sort_order') ?? 0;
        DB::table('faqs')->insert([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'category'   => $request->category,
            'page'       => 'faq_page',
            'sort_order' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'FAQ added to FAQ Page!');
    }

    public function faqPageSeoSave(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        // Save SEO settings
        \App\Models\PageSeo::updateOrCreate(
            ['page_name' => 'faqs'],
            [
                'meta_title' => $request->faq_meta_title,
                'meta_description' => $request->faq_meta_description,
                'meta_keywords' => $request->faq_meta_keywords,
                'meta_robots' => $request->meta_robots,
            ]
        );

        // Save Page Content settings
        DB::table('homepage_settings')->updateOrInsert(
            ['id' => 1],
            [
                'faq_h1' => $request->faq_h1,
                'faq_description' => $request->faq_description,
            ]
        );

        return back()->with('success', 'FAQ Page settings updated!');
    }

    public function faqPageDelete($id)
    {
        return $this->faqDelete($id);
    }

    public function publicFaqs()
    {
        $faqs = DB::table('faqs')->where('page', 'faq_page')->where('is_active', 1)->orderBy('sort_order')->get()->groupBy('category');
        $settings = DB::table('homepage_settings')->first();
        return view('faqs', compact('faqs', 'settings'));
    }

    public function uploadEditorImage(Request $request)
    {
        if (!session('admin_logged_in')) return response()->json(['success' => 0, 'message' => 'Unauthorized'], 401);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);

            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => asset('assets/images/' . $filename),
                ]
            ]);
        }

        return response()->json(['success' => 0, 'message' => 'No image uploaded']);
    }
}
