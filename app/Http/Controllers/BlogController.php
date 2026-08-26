<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');

        // New Blog model posts
        $newBlogs = Blog::latest()->get();

        // Old BlogPost model posts - convert to Blog-like format
        $oldPosts = \App\Models\BlogPost::latest()->get()->map(function ($post) {
            return (object)[
                'id'             => 'legacy_' . $post->id,
                'real_id'        => $post->id,
                'is_legacy'      => true,
                'title'          => $post->title,
                'slug'           => $post->slug,
                'featured_image' => $post->image ?? null,
                'author_name'    => 'Admin',
                'status'         => $post->is_published ? 1 : 0,
                'tags'           => $post->category ?? null,
                'created_at'     => $post->published_at ?? $post->created_at,
            ];
        });

        // Merge: new blogs first, then old posts
        $blogs = $newBlogs->concat($oldPosts);

        $seo = \App\Models\PageSeo::where('page_name', 'blogs')->first();
        return view('admin.blogs.index', compact('blogs', 'seo'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $platforms = \App\Models\Platform::where('status', 'active')->get();
        return view('admin.blogs.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'title' => 'required',
            'meta_title' => 'nullable|string',
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
            $file->move(public_path('uploads/blogs'), $filename);
            $data['featured_image'] = '/uploads/blogs/' . $filename;
        }

        Blog::create($data);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $blog = Blog::findOrFail($id);
        $platforms = \App\Models\Platform::where('status', 'active')->get();
        return view('admin.blogs.edit', compact('blog', 'platforms'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'title' => 'required',
            'meta_title' => 'nullable|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'nullable|string',
            'meta_robots' => 'nullable|string',
        ]);

        $blog = Blog::findOrFail($id);
        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->title);
        $data['tags'] = $request->category;

        unset($data['category']);

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blogs'), $filename);
            $data['featured_image'] = '/uploads/blogs/' . $filename;
        }

        $blog->update($data);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return back()->with('success', 'Blog deleted successfully!');
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $related = Blog::where('status', 1)->where('id', '!=', $blog->id)->limit(5)->get();
        $type = 'blog';
        return view('blog.show', compact('blog', 'related', 'type'));
    }

    public function filter(Request $request)
    {
        session(['helpcenter_resource' => $request->request->get('resource') ?: $request->resource]);
        session(['helpcenter_category' => $request->request->get('category') ?: $request->category]);
        return redirect()->away(url('/help-center') . '/');
    }

    public function publicIndex(Request $request)
    {
        $resource = session('helpcenter_resource', 'blog');
        $category = session('helpcenter_category', '');
        
        if ($resource === 'guide') {
            $query = \App\Models\Guide::where('status', 1);
        } else {
            $query = Blog::where('status', 1);
        }

        if (!empty($category)) {
            $query->where('tags', 'LIKE', '%' . $category . '%');
        }

        $blogs = $query->latest()->paginate(10);
        
        // Get popular items (from current resource)
        if ($resource === 'guide') {
            $popular = \App\Models\Guide::where('status', 1)->latest()->limit(5)->get();
        } else {
            $popular = Blog::where('status', 1)->latest()->limit(5)->get();
        }
        
        // Get unique tags for the category filter dynamically from platforms
        $categories = \App\Models\Platform::where('status', 'active')->pluck('name')->toArray();

        return view('blogs', compact('blogs', 'popular', 'categories', 'resource'));
    }
    // ── Legacy BlogPost Edit ───────────────────────────────────────────────────

    public function legacyEdit($id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');
        $post = \App\Models\BlogPost::findOrFail($id);
        return view('admin.blogs.legacy_edit', compact('post'));
    }

    public function legacyUpdate(Request $request, $id)
    {
        if (!session('admin_logged_in')) return redirect()->route('admin.login');

        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'excerpt'          => 'nullable|string',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_published'     => 'nullable',
        ]);

        $post = \App\Models\BlogPost::findOrFail($id);

        $post->title            = $request->title;
        $post->slug             = $request->slug ?: Str::slug($request->title);
        $post->excerpt          = $request->excerpt;
        $post->content          = $request->content;
        $post->meta_title       = $request->meta_title;
        $post->meta_description = $request->meta_description;
        $post->category         = $request->category;
        $post->is_published     = $request->has('is_published') ? 1 : 0;

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/custom_blogs'), $filename);
            $post->image = '/images/custom_blogs/' . $filename;
        }

        $post->save();
        return redirect()->route('admin.blogs.index')->with('success', 'Legacy blog post updated successfully!');
    }
}
