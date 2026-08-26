@extends('layouts.admin_master')

@section('title', 'Edit Legacy Blog Post')

@section('header_icon')
    <i class="fas fa-edit" style="color:#818CF8;"></i>
@endsection

@section('header_title', 'Edit Legacy Blog Post')

@section('breadcrumb')
    / <a href="{{ route('admin.blogs.index') }}" style="color:#3668de;text-decoration:none;">Blogs</a> / Edit Legacy
@endsection

@section('topbar_actions')
    <div style="display:flex;gap:1rem;align-items:center;">
        <a href="/blog/{{ $post->slug }}/" target="_blank" class="btn-preview">
            <i class="fas fa-eye"></i> View Post
        </a>
        <a href="{{ route('admin.blogs.index') }}" class="btn-preview">
            <i class="fas fa-arrow-left"></i> Back to Blogs
        </a>
    </div>
@endsection

@push('styles')
<style>
    .btn-preview{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.6rem 1rem;font-size:0.82rem;font-weight:600;font-family:'Inter',sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;transition:all 0.2s;}
    .btn-save{background:linear-gradient(135deg,#818CF8,#6366F1);color:#fff;border:none;border-radius:12px;padding:0.7rem 1.6rem;font-size:0.88rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;box-shadow:0 4px 15px rgba(99,102,241,0.3);transition:transform 0.2s;}
    .btn-save:hover{transform:translateY(-2px);}

    .form-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:2rem;margin-bottom:1.5rem;}
    .form-card h3{color:#fff;font-size:0.95rem;font-weight:700;margin-bottom:1.5rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(255,255,255,0.06);}

    .form-group{margin-bottom:1.2rem;}
    .form-label{display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;}
    .form-control{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;color:#fff;font-size:0.9rem;font-family:'Inter',sans-serif;transition:border-color 0.2s;outline:none;}
    .form-control:focus{border-color:rgba(129,140,248,0.5);background:rgba(255,255,255,0.07);}
    textarea.form-control{resize:vertical;min-height:120px;}

    .legacy-badge{background:rgba(99,102,241,0.15);color:#818CF8;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:8px;display:inline-flex;align-items:center;gap:0.4rem;margin-bottom:1.5rem;}

    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;}
    .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.2rem;}

    .current-img{width:100%;max-height:200px;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,0.08);margin-top:0.5rem;}

    .toggle-wrap{display:flex;align-items:center;gap:0.75rem;}
    .toggle{position:relative;width:48px;height:26px;flex-shrink:0;}
    .toggle input{opacity:0;width:0;height:0;}
    .toggle-slider{position:absolute;inset:0;background:rgba(255,255,255,0.1);border-radius:26px;cursor:pointer;transition:0.3s;}
    .toggle-slider:before{content:'';position:absolute;width:20px;height:20px;left:3px;top:3px;background:#fff;border-radius:50%;transition:0.3s;}
    .toggle input:checked + .toggle-slider{background:#818CF8;}
    .toggle input:checked + .toggle-slider:before{transform:translateX(22px);}

    .content-area{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:1rem;color:rgba(255,255,255,0.7);font-size:0.88rem;line-height:1.6;min-height:400px;resize:vertical;width:100%;font-family:'Inter',monospace;outline:none;}
    .content-area:focus{border-color:rgba(129,140,248,0.5);}
</style>
@endpush

@section('content')

<div class="legacy-badge">
    <i class="fas fa-history"></i> Legacy Post — Stored as HTML
</div>

@if(session('success'))
<div style="background:rgba(124, 58, 237,0.12);border:1px solid rgba(124, 58, 237,0.25);color:#a78bfa;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.6rem;font-size:0.85rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.25);color:#FCA5A5;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.5rem;font-size:0.85rem;">
    <i class="fas fa-exclamation-circle"></i>
    @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('admin.legacy_blogs.update', $post->id) }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">

        {{-- LEFT COLUMN --}}
        <div>
            {{-- Basic Info --}}
            <div class="form-card">
                <h3><i class="fas fa-file-alt" style="color:#818CF8;margin-right:0.5rem;"></i> Post Details</h3>

                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Slug (URL)</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Category / Tag</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $post->category) }}" placeholder="e.g. YouTube, TikTok">
                </div>

                <div class="form-group">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>
            </div>

            {{-- Content --}}
            <div class="form-card">
                <h3><i class="fas fa-code" style="color:#818CF8;margin-right:0.5rem;"></i> Content (HTML)</h3>
                <div class="form-group">
                    <label class="form-label">HTML Content</label>
                    <textarea name="content" class="content-area" id="content-editor">{{ old('content', $post->content) }}</textarea>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div>
            {{-- Publish & Actions --}}
            <div class="form-card">
                <h3><i class="fas fa-cog" style="color:#818CF8;margin-right:0.5rem;"></i> Settings</h3>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="checkbox" name="is_published" value="1" {{ $post->is_published ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="color:rgba(255,255,255,0.6);font-size:0.88rem;">Published</span>
                    </div>
                </div>

                <button type="submit" class="btn-save" style="width:100%;justify-content:center;margin-top:0.5rem;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>

            {{-- Featured Image --}}
            <div class="form-card">
                <h3><i class="fas fa-image" style="color:#818CF8;margin-right:0.5rem;"></i> Featured Image</h3>

                @if($post->image)
                <div class="form-group">
                    <label class="form-label">Current Image</label>
                    <img src="{{ $post->image }}" alt="Current {{ $post->title }} image" title="{{ $post->title }}" class="current-img">
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div style="font-size:0.75rem;color:rgba(255,255,255,0.3);margin-top:0.4rem;">JPG, PNG, WebP • Max 5MB</div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="form-card">
                <h3><i class="fas fa-search" style="color:#818CF8;margin-right:0.5rem;"></i> SEO</h3>

                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $post->meta_description) }}</textarea>
                </div>
            </div>

            {{-- Post Info --}}
            <div class="form-card" style="font-size:0.82rem;color:rgba(255,255,255,0.4);">
                <h3><i class="fas fa-info-circle" style="color:#818CF8;margin-right:0.5rem;"></i> Info</h3>
                <div style="margin-bottom:0.5rem;"><span style="color:rgba(255,255,255,0.25);">ID:</span> {{ $post->id }}</div>
                <div style="margin-bottom:0.5rem;"><span style="color:rgba(255,255,255,0.25);">Created:</span> {{ $post->created_at->format('M d, Y') }}</div>
                <div><span style="color:rgba(255,255,255,0.25);">Read Time:</span> {{ $post->read_minutes ?? 5 }} min</div>
            </div>
        </div>
    </div>
</form>

@endsection

