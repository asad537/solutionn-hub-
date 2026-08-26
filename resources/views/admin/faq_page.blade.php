@extends('layouts.admin_master')

@section('title', 'FAQ Page Management')

@section('header_icon')
    <i class="fas fa-list-ul" style="color:#3668de;"></i>
@endsection

@section('header_title', 'FAQ Page Management')

@section('breadcrumb')
    / FAQ Page
@endsection

@push('styles')
    <style>
        .form-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:1.8rem;margin-bottom:1.5rem;}
        .form-card-header{display:flex;align-items:center;gap:0.7rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .hicon{width:36px;height:36px;border-radius:10px;background:rgba(139, 92, 246,0.12);color:#3668de;display:flex;align-items:center;justify-content:center;font-size:0.95rem;}
        .form-card-header h3{font-size:0.95rem;font-weight:700;color:#fff;}
        .form-card-header p{font-size:0.75rem;color:rgba(255,255,255,0.35); line-height: 1.45; }
        
        .form-group label{display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;}
        .form-group input, .form-group textarea, .form-group select{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;transition:border 0.2s;}
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus{border-color:#3668de;background:rgba(139, 92, 246,0.06);}
        .form-group{margin-bottom:1.2rem; line-height: 1.45; }

        .btn-save{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.85rem 2.5rem;font-size:0.95rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;box-shadow:0 8px 25px rgba(139, 92, 246,0.3);transition:transform 0.2s;}
        .btn-save:hover{transform:translateY(-2px);}

        .category-block{margin-bottom:2.5rem;}
        .category-title{font-size:1.1rem;font-weight:800;color:#3668de;margin-bottom:1.2rem;display:flex;align-items:center;gap:0.8rem;}
        .category-title::after{content:'';flex:1;height:1px;background:rgba(255,255,255,0.06);}
        .faq-list{display:flex;flex-direction:column;gap:0.8rem;}
        .faq-item{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:1rem 1.2rem;display:flex;align-items:flex-start;gap:1rem;}
        .faq-item-body{flex:1;}
        .faq-item-body strong{font-size:0.9rem;color:#fff;display:block;margin-bottom:0.3rem;}
        .faq-item-body span{font-size:0.82rem;color:rgba(255,255,255,0.4);line-height:1.5;}
        .btn-del{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#FCA5A5;padding:0.4rem 0.8rem;border-radius:8px;font-size:0.78rem;cursor:pointer;font-family:'Inter',sans-serif;flex-shrink:0;transition:all 0.2s;}
        .btn-del:hover{background:rgba(239,68,68,0.2);}
        .btn-edit{background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);color:#93C5FD;padding:0.4rem 0.8rem;border-radius:8px;font-size:0.78rem;cursor:pointer;font-family:'Inter',sans-serif;flex-shrink:0;transition:all 0.2s;text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
        .btn-edit:hover{background:rgba(59,130,246,0.2);}
    </style>
@endpush

@section('content')
    <!-- Page Content & SEO Settings -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="hicon"><i class="fas fa-file-alt"></i></div>
            <div><h3>FAQ Page Settings</h3><p style="line-height: 1.45;">Manage page content and metadata</p></div>
        </div>
        <form method="POST" action="{{ route('admin.faq_page.seo.save') }}">
            @csrf
            
            <h4 style="margin-bottom:1rem;color:#3668de;font-size:0.9rem;">Page Content</h4>
            <div class="form-group">
                <label>H1 Heading</label>
                <input type="text" name="faq_h1" value="{{ optional($settings)->faq_h1 ?? 'Answers to Your Common Questions' }}">
            </div>
            <div class="form-group">
                <label>Hero Description</label>
                <textarea name="faq_description" rows="3">{{ optional($settings)->faq_description ?? 'Find everything you need to know about downloading videos, quality settings, and platform support.' }}</textarea>
            </div>
            
            <hr style="border:0;border-top:1px solid rgba(255,255,255,0.06);margin:2rem 0;">
            <h4 style="margin-bottom:1rem;color:#3668de;font-size:0.9rem;">SEO Metadata</h4>
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="faq_meta_title" value="{{ optional($seo)->meta_title ?? '' }}">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem;">
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="faq_meta_description" rows="3">{{ optional($seo)->meta_description ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <textarea name="faq_meta_keywords" rows="3">{{ optional($seo)->meta_keywords ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label>Robots Tag</label>
                <select name="meta_robots" style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;">
                    <option value="index, follow" {{ (optional($seo)->meta_robots ?? 'index, follow') == 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                    <option value="noindex, follow" {{ optional($seo)->meta_robots == 'noindex, follow' ? 'selected' : '' }}>Noindex, Follow</option>
                    <option value="index, nofollow" {{ optional($seo)->meta_robots == 'index, nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                    <option value="noindex, nofollow" {{ optional($seo)->meta_robots == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                </select>
            </div>
            <button type="submit" class="btn-save">Save SEO</button>
        </form>
    </div>

    <!-- Add New -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="hicon"><i class="fas fa-plus"></i></div>
            <div><h3>Add New FAQ to Page</h3><p style="line-height: 1.45;">Categorized help content</p></div>
        </div>
        <form method="POST" action="{{ route('admin.faq_page.store') }}">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem;">
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" list="categories" required>
                    <datalist id="categories">
                        @foreach($categories as $cat) <option value="{{ $cat }}"> @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" name="question" required>
                </div>
            </div>
            <div class="form-group">
                <label>Answer</label>
                <textarea name="answer" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn-save">Add FAQ</button>
        </form>
    </div>

    <!-- Content List -->
    <div class="form-card">
        @php $currentCat = null; @endphp
        @forelse($faqs as $faq)
            @if($currentCat !== $faq->category)
                @if($currentCat !== null) </div></div> @endif
                @php $currentCat = $faq->category; @endphp
                <div class="category-block">
                    <div class="category-title">{{ $currentCat }}</div>
                    <div class="faq-list">
            @endif
            
            <div class="faq-item">
                <div class="faq-item-body">
                    <strong>{{ $faq->question }}</strong>
                    <span>{{ $faq->answer }}</span>
                </div>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('admin.faq_page.delete', $faq->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>

            @if($loop->last) </div></div> @endif
        @empty
            <p style="text-align:center;padding:2rem;color:rgba(255,255,255,0.2); line-height: 1.45;">No FAQs yet.</p>
        @endforelse
    </div>
@endsection

