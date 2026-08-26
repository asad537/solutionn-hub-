@extends('layouts.admin_master')

@section('title', 'Edit FAQ')

@section('header_icon')
    <i class="fas fa-edit" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Edit FAQ')

@section('breadcrumb')
    / <a href="{{ $faq->page === 'faq_page' ? route('admin.faq_page') : route('admin.faqs') }}">FAQs</a> / Edit
@endsection

@push('styles')
    <style>
        .form-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:1.8rem;margin-bottom:1.5rem;max-width:800px;}
        .form-card-header{display:flex;align-items:center;gap:0.7rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .hicon{width:36px;height:36px;border-radius:10px;background:rgba(139, 92, 246,0.12);color:#3668de;display:flex;align-items:center;justify-content:center;font-size:0.95rem;}
        .form-card-header h3{font-size:0.95rem;font-weight:700;color:#fff;}
        
        .form-group label{display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;}
        .form-group input, .form-group textarea{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;transition:border 0.2s;}
        .form-group input:focus, .form-group textarea:focus{border-color:#3668de;background:rgba(139, 92, 246,0.06);}
        .form-group{margin-bottom:1.2rem; line-height: 1.45; }

        .btn-save{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.85rem 2.5rem;font-size:0.95rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;box-shadow:0 8px 25px rgba(139, 92, 246,0.3);transition:transform 0.2s;}
        .btn-save:hover{transform:translateY(-2px);}
        .btn-back{display:inline-block;background:rgba(255,255,255,0.05);color:#fff;text-decoration:none;padding:0.85rem 2rem;border-radius:12px;font-size:0.95rem;font-weight:600;margin-left:10px;transition:background 0.2s;}
        .btn-back:hover{background:rgba(255,255,255,0.1);}
    </style>
@endpush

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <div class="hicon"><i class="fas fa-edit"></i></div>
            <div><h3>Edit FAQ</h3></div>
        </div>
        <form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}">
            @csrf
            
            @if($faq->page === 'faq_page')
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Category</label>
                <input type="text" name="category" value="{{ $faq->category }}" required>
            </div>
            @endif

            <div class="form-group">
                <label><i class="fas fa-question"></i> Question</label>
                <input type="text" name="question" value="{{ $faq->question }}" required>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Answer</label>
                <textarea name="answer" rows="5" required style="resize:vertical;">{{ $faq->answer }}</textarea>
            </div>
            
            <div style="margin-top:2rem;">
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Update FAQ</button>
                <a href="{{ $faq->page === 'faq_page' ? route('admin.faq_page') : route('admin.faqs') }}" class="btn-back">Cancel</a>
            </div>
        </form>
    </div>
@endsection

