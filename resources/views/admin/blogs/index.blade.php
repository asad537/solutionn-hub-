@extends('layouts.admin_master')

@section('title', 'Blogs Management')

@section('header_icon')
    <i class="fas fa-blog" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Blog Management')

@section('breadcrumb')
    / Blogs
@endsection

@section('topbar_actions')
    <div style="display:flex;gap:1rem;">
        <a href="/" target="_blank" class="btn-preview"><i class="fas fa-external-link-alt"></i> View Site</a>
        <a href="{{ route('admin.blogs.create') }}" class="btn-add"><i class="fas fa-plus"></i> Create New Post</a>
    </div>
@endsection

@push('styles')
    <style>
        .btn-add{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.6rem 1.2rem;font-size:0.88rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;box-shadow:0 4px 15px rgba(139, 92, 246,0.25);transition:transform 0.2s;}
        .btn-add:hover{transform:translateY(-2px);}
        .btn-preview{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.6rem 1rem;font-size:0.82rem;font-weight:600;font-family:'Inter',sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;transition:all 0.2s;}
        
        .table-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th{text-align:left;font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.06em;padding:1rem 1.5rem;background:rgba(255,255,255,0.02);border-bottom:1px solid rgba(255,255,255,0.06);}
        td{padding:1rem 1.5rem;font-size:0.88rem;color:rgba(255,255,255,0.7);border-bottom:1px solid rgba(255,255,255,0.04);}
        tr:hover td{background:rgba(255,255,255,0.02);}
        
        .blog-info{display:flex;align-items:center;gap:1.2rem;}
        .blog-img-wrap{width:110px;height:70px;border-radius:10px;overflow:hidden;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);flex-shrink:0; line-height: 1.45; }
        .blog-img{width:100%;height:100%;object-fit:cover;}
        .blog-title{font-weight:700;color:#fff;font-size:0.95rem;line-height:1.4;}
        .blog-meta{font-size:0.78rem;color:rgba(255,255,255,0.3);margin-top:4px;}
        
        .status-badge{padding:0.25rem 0.6rem;border-radius:6px;font-size:0.72rem;font-weight:700;text-transform:uppercase;}
        .status-published{background:rgba(124, 58, 237,0.12);color:#a78bfa;}
        .status-draft{background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.5);}
        
        .actions{display:flex;align-items:center;gap:0.5rem;}
        .btn-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;text-decoration:none;transition:all 0.2s;border:none;cursor:pointer;}
        .btn-edit{background:rgba(139, 92, 246,0.12);color:#3668de;}
        .btn-del{background:rgba(239,68,68,0.12);color:#FCA5A5;}
    </style>
@endpush

@section('content')
    @if(session('success'))
    <div class="alert-success" style="background:rgba(124, 58, 237,0.12);border:1px solid rgba(124, 58, 237,0.25);color:#a78bfa;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.6rem;font-size:0.85rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="table-card" style="margin-bottom: 2rem; padding: 1.5rem;">
        <h3 style="color:#fff; font-size:1rem; margin-bottom:1rem;"><i class="fas fa-search" style="color:#3668de;"></i> Blog Index SEO Settings</h3>
        <form method="POST" action="{{ route('admin.seo_settings.update') }}">
            @csrf
            <input type="hidden" name="page_name" value="blogs">
            <div style="margin-bottom: 1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;margin-bottom:0.5rem;">Meta Title</label>
                <input type="text" name="meta_title" value="{{ optional($seo)->meta_title ?? '' }}" style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:0.8rem;color:#fff;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;margin-bottom:0.5rem;">Meta Description</label>
                    <textarea name="meta_description" rows="2" style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:0.8rem;color:#fff;">{{ optional($seo)->meta_description ?? '' }}</textarea>
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;margin-bottom:0.5rem;">Meta Keywords</label>
                    <textarea name="meta_keywords" rows="2" style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:0.8rem;color:#fff;">{{ optional($seo)->meta_keywords ?? '' }}</textarea>
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;margin-bottom:0.5rem;">Robots Tag</label>
                <select name="meta_robots" style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:0.8rem;color:#fff;">
                    <option value="index, follow" {{ (optional($seo)->meta_robots ?? 'index, follow') == 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                    <option value="noindex, follow" {{ optional($seo)->meta_robots == 'noindex, follow' ? 'selected' : '' }}>Noindex, Follow</option>
                    <option value="index, nofollow" {{ optional($seo)->meta_robots == 'index, nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                    <option value="noindex, nofollow" {{ optional($seo)->meta_robots == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                </select>
            </div>
            <button type="submit" class="btn-add"><i class="fas fa-save"></i> Save SEO</button>
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Blog Post</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width:150px;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="blog-info">
                            <div class="blog-img-wrap">
                                <img src="{{ $blog->featured_image ?: 'https://via.placeholder.com/300x200' }}" class="blog-img" alt="">
                            </div>
                            <div>
                                <div class="blog-title">{{ $blog->title }}</div>
                                <div class="blog-meta">{{ $blog->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{ $blog->author_name ?: 'Admin' }}
                        @if(!empty($blog->is_legacy))
                            <span style="margin-left:6px;background:rgba(99,102,241,0.15);color:#818CF8;font-size:0.68rem;font-weight:700;padding:2px 7px;border-radius:5px;text-transform:uppercase;">Legacy</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $blog->status ? 'status-published' : 'status-draft' }}">
                            {{ $blog->status ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</td>
                    <td>
                        <div class="actions" style="justify-content:flex-end;">
                            @if(empty($blog->is_legacy))
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn-icon btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.blogs.delete', $blog->id) }}" onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-del" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            @else
                                <a href="{{ route('admin.legacy_blogs.edit', $blog->real_id) }}" class="btn-icon btn-edit" title="Edit" style="background:rgba(129,140,248,0.12);color:#818CF8;"><i class="fas fa-edit"></i></a>
                                <a href="/blog/{{ $blog->slug }}/" target="_blank" class="btn-icon" style="background:rgba(99,102,241,0.06);color:rgba(129,140,248,0.6);" title="View Post"><i class="fas fa-eye"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:4rem;color:rgba(255,255,255,0.2);">
                        No blog posts found. <a href="{{ route('admin.blogs.create') }}" style="color:#3668de;">Create your first post!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

