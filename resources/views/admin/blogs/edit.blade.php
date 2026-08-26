@extends('layouts.admin_master')

@section('title', 'Edit Blog Post')

@section('header_icon')
    <i class="fas fa-edit" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Edit Post')

@section('breadcrumb')
    / <a href="{{ route('admin.blogs.index') }}">Blogs</a> / Edit
@endsection

@section('view_site_url', route('blog.show', $blog->slug))

@push('styles')
    <meta name="editorjs-upload-url" content="{{ route('admin.cms.upload-editor-image') }}">
    <style>
        .form-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:1.8rem;margin-bottom:1.5rem;}
        .form-card-header{display:flex;align-items:center;gap:0.7rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .hicon{width:36px;height:36px;border-radius:10px;background:rgba(139, 92, 246,0.12);color:#3668de;display:flex;align-items:center;justify-content:center;font-size:0.95rem;}
        .form-card-header h3{font-size:0.95rem;font-weight:700;color:#fff;}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;}
        .form-group{margin-bottom:1.5rem; line-height: 1.45; }
        .form-group label{display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.6rem;}
        .form-group input, .form-group textarea, .form-group select{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;transition:border 0.2s;}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:#3668de;background:rgba(139, 92, 246,0.06);}
        
        #editor {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 2.5rem 5rem 2.5rem 5rem;
            min-height: 400px;
            color: #1E293B;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        
        
        
        .ce-header { color: #0F172A; font-weight: 800 !important; margin-bottom: 0.8em; }
        h1.ce-header { font-size: 2rem; }
        h2.ce-header { font-size: 1.5rem; }
        h3.ce-header { font-size: 1.25rem; }
        h4.ce-header { font-size: 1.1rem; }
        h5.ce-header { font-size: 1rem; }
        h6.ce-header { font-size: 0.875rem; }
        .ce-paragraph { color: #334155; line-height: 1.8; font-size: 1rem; }
        .ce-block__content, .ce-toolbar__content { max-width: 800px; margin: 0 auto; }
        
        .ce-code__textarea, .ce-input, .cdx-input, .ce-link-autocomplete__input, 
        .ce-inline-tool-input, .ce-inline-toolbar input, .ce-toolbar input {
            color: #1E293B !important;
            background: #ffffff !important;
            border: 1px solid #CBD5E1 !important;
        }

        .btn-save{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.9rem 3rem;font-size:1rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;box-shadow:0 8px 25px rgba(139, 92, 246,0.3);transition:transform 0.2s;}
        .btn-save:hover{transform:translateY(-2px);}
        .current-img{margin-top:1rem;display:flex;flex-direction:column;gap:0.5rem;}
        .current-img img{width:120px;height:120px;border-radius:10px;object-fit:cover;border:1px solid rgba(255,255,255,0.1);}
    </style>
@endpush

@section('content')
    <form id="blogForm" method="POST" action="{{ route('admin.blogs.update', $blog->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="content" id="contentInput">
        
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-file-alt"></i></div>
                <div><h3>Basic Information</h3></div>
            </div>
            
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ $blog->title }}" required>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" value="{{ $blog->slug }}">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="1" {{ $blog->status ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ !$blog->status ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Category (Platform)</label>
                <select name="category">
                    <option value="">Select Platform</option>
                    @foreach($platforms as $platform)
                        <option value="{{ $platform->name }}" {{ (isset($blog) && $blog->tags == $platform->name) ? 'selected' : '' }}>{{ $platform->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Short Description</label>
                <textarea name="description" rows="3">{{ $blog->description }}</textarea>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-pen-nib"></i></div>
                <div><h3>Content (Editor.js)</h3></div>
            </div>
            <div class="form-group">
                <div id="editor"></div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-image"></i></div>
                <div><h3>Media & Author</h3></div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*">
                    @if($blog->featured_image)
                    <div class="current-img">
                        <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" title="{{ $blog->title }}">
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>Image Alt Text</label>
                    <input type="text" name="image_alt" value="{{ $blog->image_alt }}">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Author Name</label>
                    <input type="text" name="author_name" value="{{ $blog->author_name }}">
                </div>
                <div class="form-group">
                    <label>Reading Time</label>
                    <input type="text" name="reading_time" value="{{ $blog->reading_time }}">
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-search"></i></div>
                <div><h3>SEO Metadata</h3></div>
            </div>
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="{{ $blog->meta_title }}" placeholder="SEO Title (Overrides default title)">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description" rows="3" required>{{ $blog->meta_description }}</textarea>
            </div>
            <div class="form-group">
                <label>Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ $blog->meta_keywords }}" placeholder="keyword1, keyword2, ...">
            </div>
            <div class="form-group">
                <label>Robots Tag</label>
                <select name="meta_robots">
                    <option value="index, follow" {{ $blog->meta_robots == 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                    <option value="noindex, follow" {{ $blog->meta_robots == 'noindex, follow' ? 'selected' : '' }}>Noindex, Follow</option>
                    <option value="index, nofollow" {{ $blog->meta_robots == 'index, nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                    <option value="noindex, nofollow" {{ $blog->meta_robots == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                </select>
            </div>
            <div class="form-group">
                <label>Custom Schema (JSON-LD)</label>
                <textarea name="schema" rows="5" placeholder='<script type="application/ld+json">&#10;{&#10;  "@context": "https://schema.org",&#10;  "@type": "Article",&#10;  "headline": "Example Title"&#10;}&#10;</script>'>{{ $blog->schema }}</textarea>
            </div>
        </div>

        <div style="margin-bottom:4rem; text-align:right;">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Update Blog Post</button>
        </div>
    </form>
@endsection

@push('scripts')
    <!-- Editor.js Core & Tools -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/nested-list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/attaches@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-drag-drop@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-inline-font-size-tool@1.0.1/dist/bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-inline-font-family-tool@1.0.4/dist/bundle.js"></script>
    <script src="{{ asset('assets/js/editorjs-manager.js') }}?v={{ filemtime(public_path('assets/js/editorjs-manager.js')) }}"></script>

    <script>
        let blogEditor;
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                let initialData = { blocks: [] };
                const rawContent = @json($blog->content);
                if (rawContent) {
                    try {
                        initialData = typeof rawContent === 'string' ? JSON.parse(rawContent) : rawContent;
                    } catch (e) {
                        if (typeof rawContent === 'string' && rawContent.includes('<')) {
                            initialData = editorJSManager.fromHTML(rawContent);
                        } else {
                            initialData = { blocks: [{ type: 'paragraph', data: { text: rawContent } }] };
                        }
                    }
                }

                blogEditor = await editorJSManager.init('editor', {
                    placeholder: 'Enter content...',
                    minHeight: 400,
                    data: initialData
                });
            } catch (e) { console.error('Editor init failed:', e); }
        });

        document.getElementById('blogForm').onsubmit = async function(e) {
            e.preventDefault();
            if (blogEditor) {
                const contentData = await editorJSManager.save('editor');
                document.getElementById('contentInput').value = JSON.stringify(contentData);
            }
            this.submit();
        };
    </script>
@endpush


