@extends('layouts.admin_master')

@section('title', 'Static Pages')

@section('header_icon')
    <i class="fas fa-file-alt" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Static Pages SEO')

@section('breadcrumb')
    / Static Pages
@endsection

@push('styles')
<style>
    .seo-card {
        background: #161B27;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 2rem;
    }
    
    .tabs-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        padding-bottom: 1rem;
    }
    
    .tab-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.7);
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: capitalize;
    }
    
    .tab-btn:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
    }
    
    .tab-btn.active {
        background: rgba(139, 92, 246,0.15);
        color: #3668de;
        border-color: rgba(139, 92, 246,0.3);
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease-out;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-group label {
        display: block; font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.6); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 0.8rem 1rem;
        border-radius: 8px;
        color: #fff;
        font-family: inherit;
        font-size: 0.9rem;
    }
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: #3668de;
        background: rgba(0,0,0,0.3);
    }
    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #3668de, #3668de);
        color: #fff;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(139, 92, 246,0.3);
    }
</style>
@endpush

@section('content')

<div class="seo-card">
    <div class="tabs-nav">
        @foreach($seoSettings as $index => $seo)
            <button class="tab-btn {{ $index === 0 ? 'active' : '' }}" onclick="switchTab('{{ $seo->page_name }}')">
                {{ str_replace('-', ' ', $seo->page_name) }}
            </button>
        @endforeach
    </div>

    @foreach($seoSettings as $index => $seo)
        <div id="tab-{{ $seo->page_name }}" class="tab-content {{ $index === 0 ? 'active' : '' }}">
            <form action="{{ route('admin.seo_settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="page_name" value="{{ $seo->page_name }}">
                
                <h3 style="margin-bottom: 1.5rem; color: #fff; font-size: 1.1rem;">
                    SEO Settings for: <span style="color: #3668de; text-transform: capitalize;">{{ str_replace('-', ' ', $seo->page_name) }}</span>
                </h3>

                <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" value="{{ $seo->meta_title }}" placeholder="e.g. Public Media Link Analyzer - Solution Hub">
                </div>

                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" placeholder="e.g. Analyze supported public media links and review source-dependent formats.">{{ $seo->meta_description }}</textarea>
                </div>

                <div class="form-group">
                    <label>Meta Keywords</label>
                    <textarea name="meta_keywords" placeholder="e.g. public media link analyzer, media format guide, supported public links">{{ $seo->meta_keywords }}</textarea>
                    <small style="color: rgba(255,255,255,0.4); display: block; margin-top: 0.4rem;">Separate keywords with commas.</small>
                </div>

                <div class="form-group">
                    <label>Robots Tag</label>
                    <select name="meta_robots" style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 0.8rem 1rem; border-radius: 8px; color: #fff; font-family: inherit; font-size: 0.9rem;">
                        <option value="index, follow" {{ $seo->meta_robots == 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                        <option value="noindex, follow" {{ $seo->meta_robots == 'noindex, follow' ? 'selected' : '' }}>Noindex, Follow</option>
                        <option value="index, nofollow" {{ $seo->meta_robots == 'index, nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                        <option value="noindex, nofollow" {{ $seo->meta_robots == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                    </select>
                    <small style="color: rgba(255,255,255,0.4); display: block; margin-top: 0.4rem;">Control search engine indexing.</small>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save {{ ucfirst(str_replace('-', ' ', $seo->page_name)) }} SEO
                </button>
            </form>
        </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
    function switchTab(pageName) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        event.currentTarget.classList.add('active');
        document.getElementById('tab-' + pageName).classList.add('active');
    }
</script>
@endpush

