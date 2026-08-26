<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PlatformController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $platforms = Platform::latest()->get();
        return view('admin.platforms.index', compact('platforms'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $allPlatforms = Platform::whereNull('parent_id')->where('status', 'active')->orderBy('name')->get();
        return view('admin.platforms.create', compact('allPlatforms'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:platforms,slug|max:255',
            'icon' => 'nullable|string|max:255',
            'h1' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_robots' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'parent_id' => 'nullable|exists:platforms,id',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->slug);
        $data['parent_id'] = $request->input('parent_id') ?: null;
        $data['show_in_navbar'] = $request->has('show_in_navbar') ? 1 : 0;
        $data['show_in_footer'] = $request->has('show_in_footer') ? 1 : 0;

        Platform::create($data);

        return redirect()->route('admin.platforms.index')->with('success', 'Platform created successfully!');
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $platform = Platform::findOrFail($id);
        $faqs = DB::table('faqs')->where('page', $platform->slug)->orderBy('sort_order')->get();
        $allPlatforms = Platform::whereNull('parent_id')->where('id', '!=', $id)->where('status', 'active')->orderBy('name')->get();
        return view('admin.platforms.edit', compact('platform', 'faqs', 'allPlatforms'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:platforms,slug,'.$id.'|max:255',
            'icon' => 'nullable|string|max:255',
            'h1' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_robots' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'parent_id' => 'nullable|exists:platforms,id',
        ]);

        $platform = Platform::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->slug);
        $data['parent_id'] = $request->input('parent_id') ?: null;
        $data['show_in_navbar'] = $request->has('show_in_navbar') ? 1 : 0;
        $data['show_in_footer'] = $request->has('show_in_footer') ? 1 : 0;

        $platform->update($data);

        return redirect()->route('admin.platforms.index')->with('success', 'Platform updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $platform = Platform::findOrFail($id);
        
        // Also delete associated FAQs
        DB::table('faqs')->where('page', $platform->slug)->delete();
        
        $platform->delete();
        return back()->with('success', 'Platform deleted successfully!');
    }

    public function show($slug)
    {
        $platform = Platform::where('slug', $slug)->where('status', 'active')->firstOrFail();
        
        // Fetch platform-specific FAQs first
        $faqs = DB::table('faqs')->where('page', $platform->slug)->orderBy('sort_order')->get();
        
        // Fallback to 'home' FAQs if none exist for this platform
        if ($faqs->isEmpty()) {
            $faqs = DB::table('faqs')->where('page', 'home')->where('is_active', true)->orderBy('sort_order')->get();
        }

        $settings = DB::table('homepage_settings')->first();
        $blogs = \App\Models\Blog::where('status', 1)->latest()->limit(4)->get();
        return view('platforms.show', compact('platform', 'faqs', 'settings', 'blogs'));
    }

    public function faqStore(Request $request, $id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $platform = Platform::findOrFail($id);
        
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $maxOrder = DB::table('faqs')->where('page', $platform->slug)->max('sort_order') ?? 0;
        
        DB::table('faqs')->insert([
            'question' => $request->question,
            'answer' => $request->answer,
            'page' => $platform->slug,
            'sort_order' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'FAQ added successfully!');
    }

    public function faqDelete($faq_id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        DB::table('faqs')->where('id', $faq_id)->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
