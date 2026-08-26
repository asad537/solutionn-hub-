<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuideController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $guides = Guide::latest()->get();
        return view('admin.guides.index', compact('guides'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $platforms = \App\Models\Platform::where('status', 'active')->get();
        return view('admin.guides.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'title' => 'required',
            'meta_description' => 'required|string',
            'meta_keywords' => 'nullable|string',
            'meta_robots' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->title);
        $data['tags'] = $request->category;
        unset($data['category']);
        
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/guides'), $filename);
            $data['featured_image'] = '/uploads/guides/' . $filename;
        }

        Guide::create($data);

        return redirect()->route('admin.guides.index')->with('success', 'Guide created successfully!');
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $guide = Guide::findOrFail($id);
        $platforms = \App\Models\Platform::where('status', 'active')->get();
        return view('admin.guides.edit', compact('guide', 'platforms'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'title' => 'required',
            'meta_description' => 'required|string',
            'meta_keywords' => 'nullable|string',
            'meta_robots' => 'nullable|string',
        ]);

        $guide = Guide::findOrFail($id);
        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->title);
        $data['tags'] = $request->category;
        unset($data['category']);

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/guides'), $filename);
            $data['featured_image'] = '/uploads/guides/' . $filename;
        }

        $guide->update($data);

        return redirect()->route('admin.guides.index')->with('success', 'Guide updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $guide = Guide::findOrFail($id);
        $guide->delete();
        return back()->with('success', 'Guide deleted successfully!');
    }

    public function publicShow($slug)
    {
        $blog = Guide::where('slug', $slug)->where('status', 1)->firstOrFail();
        $related = Guide::where('status', 1)->where('id', '!=', $blog->id)->limit(5)->get();
        $type = 'guide';
        return view('blog.show', compact('blog', 'related', 'type'));
    }
}
