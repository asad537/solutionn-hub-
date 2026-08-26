@extends('layouts.admin_master')

@section('title', 'FAQs Management')

@section('header_icon')
    <i class="fas fa-question-circle" style="color:#3668de;"></i>
@endsection

@section('header_title', 'FAQ Management')

@section('breadcrumb')
    / FAQs
@endsection

@push('styles')
    <style>
        .form-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:1.8rem;margin-bottom:1.5rem;}
        .form-card-header{display:flex;align-items:center;gap:0.7rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .hicon{width:36px;height:36px;border-radius:10px;background:rgba(139, 92, 246,0.12);color:#3668de;display:flex;align-items:center;justify-content:center;font-size:0.95rem;}
        .form-card-header h3{font-size:0.95rem;font-weight:700;color:#fff;}
        .form-card-header p{font-size:0.75rem;color:rgba(255,255,255,0.35); line-height: 1.45; }
        
        .form-group label{display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;}
        .form-group input, .form-group textarea{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;transition:border 0.2s;}
        .form-group input:focus, .form-group textarea:focus{border-color:#3668de;background:rgba(139, 92, 246,0.06);}
        .form-group{margin-bottom:1.2rem; line-height: 1.45; }

        .btn-save{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.85rem 2.5rem;font-size:0.95rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;box-shadow:0 8px 25px rgba(139, 92, 246,0.3);transition:transform 0.2s;}
        .btn-save:hover{transform:translateY(-2px);}

        .faq-list{display:flex;flex-direction:column;gap:0.8rem;}
        .faq-item{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:1rem 1.2rem;display:flex;align-items:flex-start;gap:1rem;}
        .faq-item-body{flex:1;}
        .faq-item-body strong{font-size:0.9rem;color:#fff;display:block;margin-bottom:0.3rem;}
        .faq-item-body span{font-size:0.82rem;color:rgba(255,255,255,0.4);line-height:1.5;}
        .btn-del{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#FCA5A5;padding:0.4rem 0.8rem;border-radius:8px;font-size:0.78rem;cursor:pointer;font-family:'Inter',sans-serif;flex-shrink:0;transition:all 0.2s;}
        .btn-del:hover{background:rgba(239,68,68,0.2);}
        .btn-edit{background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);color:#93C5FD;padding:0.4rem 0.8rem;border-radius:8px;font-size:0.78rem;cursor:pointer;font-family:'Inter',sans-serif;flex-shrink:0;transition:all 0.2s;text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
        .btn-edit:hover{background:rgba(59,130,246,0.2);}
        .faq-num{width:28px;height:28px;background:rgba(139, 92, 246,0.12);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#3668de;flex-shrink:0;}
    </style>
@endpush

@section('content')
    <div class="form-card">
        <div class="form-card-header">
            <div class="hicon"><i class="fas fa-plus"></i></div>
            <div><h3>Add New FAQ</h3><p style="line-height: 1.45;">Add a question and answer to display on homepage</p></div>
        </div>
        <form method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-question"></i> Question</label>
                <input type="text" name="question" placeholder="e.g. How can I download videos?" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Answer</label>
                <textarea name="answer" rows="4" placeholder="Write the answer here..." required style="resize:vertical;"></textarea>
            </div>
            <button type="submit" class="btn-save"><i class="fas fa-plus"></i> Add FAQ</button>
        </form>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div class="hicon"><i class="fas fa-list"></i></div>
            <div><h3>All FAQs <span style="color:#3668de;">({{ count($faqs) }})</span></h3><p style="line-height: 1.45;">Manage existing questions and answers</p></div>
        </div>

        @if(count($faqs) > 0)
        <div class="faq-list">
            @foreach($faqs as $faq)
            <div class="faq-item">
                <div class="faq-num">{{ $loop->iteration }}</div>
                <div class="faq-item-body">
                    <strong>{{ $faq->question }}</strong>
                    <span>{{ Str::limit($faq->answer, 120) }}</span>
                </div>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                    <form method="POST" action="{{ route('admin.faqs.delete', $faq->id) }}" onsubmit="return confirm('Delete this FAQ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-del"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:rgba(255,255,255,0.3);font-size:0.88rem;text-align:center;padding:2rem; line-height: 1.45;">No FAQs yet.</p>
        @endif
    </div>
@endsection

