@extends('layouts.admin_master')

@section('title', 'Download Page Management')

@section('header_icon')
    <i class="fas fa-download" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Download Page Management')

@section('breadcrumb')
    / Download Page
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
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('admin.download_page.save') }}">
        @csrf

        <!-- Content Settings -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-file-alt"></i></div>
                <div><h3>Page Content Settings</h3><p style="line-height: 1.45;">Manage main content for the download page</p></div>
            </div>
            
            <div class="form-group">
                <label>H1 Heading</label>
                <input type="text" name="h1_heading" value="{{ optional($settings)->h1_heading ?? 'How to Install from Play Store' }}" required>
            </div>
            
            <div class="form-group">
                <label>Description Paragraph</label>
                <textarea name="description" rows="3" required>{{ optional($settings)->description ?? 'Review supported public media links in the browser. No separate app or extension is required for the basic workflow.' }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem;">
                <div class="form-group">
                    <label>Button Text</label>
                    <input type="text" name="btn_text" value="{{ optional($settings)->btn_text ?? 'Open Link Analyzer' }}" required>
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" name="btn_link" value="{{ optional($settings)->btn_link ?? '#' }}" required>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-search"></i></div>
                <div><h3>SEO Settings</h3><p style="line-height: 1.45;">Manage metadata for the download page</p></div>
            </div>
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="{{ optional($seo)->meta_title ?? '' }}">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem;">
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="3">{{ optional($seo)->meta_description ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <textarea name="meta_keywords" rows="3">{{ optional($seo)->meta_keywords ?? '' }}</textarea>
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
        </div>
        
        <div style="text-align: right; margin-bottom: 3rem;">
            <button type="submit" class="btn-save">Save Settings</button>
        </div>
    </form>
@endsection
