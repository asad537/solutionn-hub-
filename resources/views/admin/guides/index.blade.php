@extends('layouts.admin_master')

@section('title', 'Guides Management')

@section('header_icon')
    <i class="fas fa-book" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Guide Management')

@section('breadcrumb')
    / Guides
@endsection

@section('topbar_actions')
    <div style="display:flex;gap:1rem;">
        <a href="/" target="_blank" class="btn-preview"><i class="fas fa-external-link-alt"></i> View Site</a>
        <a href="{{ route('admin.guides.create') }}" class="btn-add"><i class="fas fa-plus"></i> Create New Guide</a>
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
        
        .guide-info{display:flex;align-items:center;gap:1.2rem;}
        .guide-img-wrap{width:110px;height:70px;border-radius:10px;overflow:hidden;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);flex-shrink:0; line-height: 1.45; }
        .guide-img{width:100%;height:100%;object-fit:cover;}
        .guide-title{font-weight:700;color:#fff;font-size:0.95rem;line-height:1.4;}
        .guide-meta{font-size:0.78rem;color:rgba(255,255,255,0.3);margin-top:4px;}
        
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

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Guide Post</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width:120px;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guides as $guide)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="guide-info">
                            <div class="guide-img-wrap">
                                <img src="{{ $guide->featured_image ?: 'https://via.placeholder.com/300x200' }}" class="guide-img" alt="{{ $guide->title }}" title="{{ $guide->title }}">
                            </div>
                            <div>
                                <div class="guide-title">{{ $guide->title }}</div>
                                <div class="guide-meta">{{ $guide->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $guide->author_name ?: 'Admin' }}</td>
                    <td>
                        <span class="status-badge {{ $guide->status ? 'status-published' : 'status-draft' }}">
                            {{ $guide->status ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>{{ $guide->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="actions" style="justify-content:flex-end;">
                            <a href="{{ route('admin.guides.edit', $guide->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.guides.delete', $guide->id) }}" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-del"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:4rem;color:rgba(255,255,255,0.2);">
                        No guide posts found. <a href="{{ route('admin.guides.create') }}" style="color:#3668de;">Create your first post!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

