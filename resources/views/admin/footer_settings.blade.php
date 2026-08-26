@extends('layouts.admin_master')

@section('title', 'Footer Settings Management')

@section('header_icon')
    <i class="fas fa-shoe-prints" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Footer Settings Management')

@section('breadcrumb')
    / Footer Settings
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

        /* Checkbox list styling */
        .platform-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .platform-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .platform-item:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.15);
        }
        .platform-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #3668de;
            cursor: pointer;
        }
        .platform-item label {
            margin: 0;
            color: #fff;
            font-size: 0.9rem;
            text-transform: none;
            letter-spacing: normal;
            font-weight: 500;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('admin.footer_settings.save') }}">
        @csrf

        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-align-left"></i></div>
                <div><h3>Footer Description</h3><p style="line-height: 1.45;">Manage the text that appears below the logo in the footer</p></div>
            </div>
            
            <div class="form-group">
                <label>Description Paragraph</label>
                <textarea name="description" rows="4" required>{{ optional($settings)->description ?? 'Analyze supported public media links and review source-dependent formats. No login required for the basic browser workflow. Works on modern mobile and desktop browsers.' }}</textarea>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-globe"></i></div>
                <div><h3>Supported Platforms</h3><p style="line-height: 1.45;">Select which platforms to display in the footer links</p></div>
            </div>

            @php
                $selectedPlatforms = optional($settings)->platforms ?? [];
            @endphp
            
            <div class="platform-list">
                @foreach($platforms as $platform)
                    <div class="platform-item" onclick="document.getElementById('platform_{{ $platform->id }}').click()">
                        <input type="checkbox" id="platform_{{ $platform->id }}" name="platforms[]" value="{{ $platform->id }}" 
                            {{ in_array($platform->id, $selectedPlatforms) ? 'checked' : '' }} onclick="event.stopPropagation()">
                        <label for="platform_{{ $platform->id }}" onclick="event.stopPropagation()">{{ $platform->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div style="text-align: right; margin-bottom: 3rem;">
            <button type="submit" class="btn-save">Save Footer Settings</button>
        </div>
    </form>
@endsection
