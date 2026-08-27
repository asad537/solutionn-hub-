<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/webp" href="/images/solutionhub-favicon.webp?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Platform — Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSRF & Editor.js Upload URL -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="editorjs-upload-url" content="{{ route('admin.cms.upload-editor-image') }}">

    <!-- Editor.js Core -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <!-- Editor.js Tools -->
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
    
    <!-- Custom Editor.js Manager -->
    <script src="{{ asset('assets/js/editorjs-manager.js') }}?v={{ filemtime(public_path('assets/js/editorjs-manager.js')) }}"></script>

    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:#0F1117;color:#E2E8F0;display:flex;min-height:100vh;}
        
        /* SIDEBAR */
        .sidebar{width:220px;background:#161B27;border-right:1px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;flex-shrink:0;position:fixed;height:100vh;z-index:100;}
        .sidebar-logo{padding:1.5rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .logo-wrap{display:flex;align-items:center;gap:0.6rem; line-height: 1.45; }
        .logo-icon{width:38px;height:38px;background:linear-gradient(135deg,#3668de,#3668de);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;color:#fff;box-shadow:0 6px 20px rgba(139, 92, 246,0.35);}
        .logo-wrap h2{font-size:0.95rem;font-weight: 700;color:#fff;}
        .logo-sub{font-size:0.68rem;color:rgba(255,255,255,0.35);}
        .sidebar-nav{padding:1rem 0.6rem;flex:1;overflow-y:auto;}
        .nav-label{font-size:0.6rem;font-weight:600;color:rgba(255,255,255,0.2);text-transform:uppercase;letter-spacing:0.1em;padding:0.5rem 0.7rem;margin-top:0.5rem;}
        .nav-item{display:flex;align-items:center;gap:0.7rem;padding:0.6rem 0.8rem;border-radius:8px;color:rgba(255,255,255,0.45);font-size:0.82rem;font-weight:500;transition:all 0.2s;text-decoration:none;margin-bottom:2px;}
        .nav-item:hover{background:rgba(255,255,255,0.04);color:#fff;}
        .nav-item.active{background:linear-gradient(135deg,rgba(139, 92, 246,0.12),rgba(139, 92, 246,0.08));color:#3668de;border:1px solid rgba(139, 92, 246,0.1);}
        .nav-item i{width:16px;text-align:center;font-size:0.85rem;}
        .sidebar-footer{padding:0.8rem 1rem;border-top:1px solid rgba(255,255,255,0.06);}
        .admin-badge{display:flex;align-items:center;gap:0.6rem;}
        .admin-avatar{width:32px;height:32px;background:linear-gradient(135deg,#3668de,#3668de);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;color:#fff;font-weight:700;}
        .admin-info p{font-size:0.78rem;font-weight:600;color:#fff; line-height: 1.45; }
        .admin-info span{font-size:0.68rem;color:rgba(255,255,255,0.25);}
        .logout-btn{margin-left:auto;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#FCA5A5;padding:0.3rem 0.6rem;border-radius:6px;font-size:0.7rem;text-decoration:none;transition:all 0.2s;}
        .logout-btn:hover{background:rgba(239,68,68,0.2);}

        .main{margin-left:220px;flex:1;display:flex;flex-direction:column;}
        .topbar{background:#161B27;border-bottom:1px solid rgba(255,255,255,0.06);padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;}
        .topbar-left h1{font-size:1.2rem;font-weight:700;color:#fff;}
        .breadcrumb{font-size:0.78rem;color:rgba(255,255,255,0.3);}
        .breadcrumb a{color:rgba(255,255,255,0.35);text-decoration:none;}
        .breadcrumb a:hover{color:#3668de;}
        .content{padding:2rem;flex:1;}

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
            padding: 3rem 2rem;
            min-height: 500px;
            color: #1E293B;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        
        /* Fix for ALL EditorJS inputs/popups on white background */
        
        
        .ce-header { color: #0F172A; font-weight: 800 !important; margin-bottom: 0.8em; }
        h1.ce-header { font-size: 2rem; }
        h2.ce-header { font-size: 1.5rem; }
        h3.ce-header { font-size: 1.25rem; }
        h4.ce-header { font-size: 1.1rem; }
        h5.ce-header { font-size: 1rem; }
        h6.ce-header { font-size: 0.875rem; }
        .ce-code__textarea, .ce-input, .cdx-input, .ce-link-autocomplete__input, 
        .ce-inline-tool-input, .ce-inline-toolbar input, .ce-toolbar input {
            color: #1E293B !important;
            background: #ffffff !important;
            border: 1px solid #CBD5E1 !important;
        }
        
        .ce-inline-tool-input {
            color: #1E293B !important;
        }

        .ce-block__content, .ce-toolbar__content { max-width: 800px; }
        .ce-toolbar__plus, .ce-toolbar__settings-btn { background-color: #F1F5F9; color: #475569; }
        
        .btn-save{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.85rem 2.5rem;font-size:0.95rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;box-shadow:0 8px 25px rgba(139, 92, 246,0.3);transition:transform 0.2s;}
        .btn-save:hover{transform:translateY(-2px);}
        .btn-back{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.6rem 1rem;font-size:0.82rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;}
        
        select { background-color: #161B27 !important; color: #fff !important; color-scheme: dark; }
        select option { background-color: #161B27 !important; color: #fff !important; }

        /* ── Sub-Platform Toggle ── */
        .sub-toggle-wrap {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            background: rgba(139, 92, 246,0.04);
            border: 1px solid rgba(139, 92, 246,0.12);
            border-radius: 12px;
            cursor: pointer;
            user-select: none;
            margin-bottom: 1.5rem;
        }
        .sub-toggle-wrap input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #3668de;
            cursor: pointer;
            flex-shrink: 0;
        }
        .sub-toggle-wrap .toggle-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: rgba(255,255,255,0.75);
        }
        .sub-toggle-wrap .toggle-label span {
            display: block;
            font-size: 0.72rem;
            font-weight: 400;
            color: rgba(255,255,255,0.35);
            margin-top: 2px;
        }
        .parent-picker {
            display: none;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .parent-picker.visible { display: block; }
        .parent-picker-header {
            padding: 0.9rem 1.2rem;
            background: rgba(255,255,255,0.03);
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .parent-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem 1.2rem;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        .parent-option:last-child { border-bottom: 0; }
        .parent-option:hover { background: rgba(255,255,255,0.04); }
        .parent-option.selected { background: rgba(139, 92, 246,0.08); }
        .parent-option input[type="radio"] {
            width: 16px;
            height: 16px;
            accent-color: #3668de;
            cursor: pointer;
            flex-shrink: 0;
        }
        .parent-option-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            flex-shrink: 0;
        }
        .parent-option-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: rgba(255,255,255,0.75);
        }
    </style>
</head>
<body>

@include('partials.admin_sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1><i class="fas fa-plus" style="color:#3668de;margin-right:0.5rem;"></i> Add New Platform</h1>
            <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.platforms.index') }}">Platforms</a> / Create</div>
        </div>
        <a href="{{ route('admin.platforms.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back to List</a>
    </div>

    <div class="content">
        <form id="platformForm" method="POST" action="{{ route('admin.platforms.store') }}">
            @csrf
            <input type="hidden" name="content" id="contentInput">
            
            <div class="form-card">
                <div class="form-card-header">
                    <div class="hicon"><i class="fas fa-globe"></i></div>
                    <div><h3>Platform Information</h3></div>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label>Platform Name (e.g. Facebook)</label>
                        <input type="text" name="name" placeholder="Enter platform name" required>
                    </div>
                    <div class="form-group">
                        <label>Platform Icon (e.g. fa-brands fa-facebook)</label>
                        <input type="text" name="icon" placeholder="Font Awesome class, SVG path, or image URL">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Slug (URL Segment)</label>
                        <input type="text" name="slug" placeholder="e.g. facebook-video-downloader" required>
                    </div>
                    <div class="form-group">
                        <label>H1 Heading (On Page)</label>
                        <input type="text" name="h1" placeholder="Enter main H1 heading" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Short Description (Hero Subtext)</label>
                    <textarea name="description" rows="3" placeholder="Enter a short description for the hero section"></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Visibility Toggles</label>
                        <div style="display: flex; gap: 1rem; align-items: center; height: 100%;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; color: #fff; font-weight: 500; font-size: 0.85rem; text-transform: none; margin: 0; cursor: pointer;">
                                <input type="checkbox" name="show_in_navbar" style="width: 18px; height: 18px; accent-color: #3668de; cursor: pointer;">
                                Show in Navbar
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; color: #fff; font-weight: 500; font-size: 0.85rem; text-transform: none; margin: 0; cursor: pointer;">
                                <input type="checkbox" name="show_in_footer" style="width: 18px; height: 18px; accent-color: #3668de; cursor: pointer;">
                                Show in Footer
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ── Sub-Platform Toggle ── --}}
                <div class="sub-toggle-wrap" onclick="document.getElementById('isSubPlatform').click()">
                    <input type="checkbox" id="isSubPlatform" onclick="event.stopPropagation(); toggleParentPicker()">
                    <div class="toggle-label">
                        Is this a Sub-Platform?
                        <span>Enable to assign this platform under a parent (e.g. YouTube Shorts → YouTube)</span>
                    </div>
                </div>

                {{-- Parent Picker --}}
                <div class="parent-picker" id="parentPicker">
                    <div class="parent-picker-header">Select Parent Platform</div>
                    <div class="parent-option" onclick="selectParent(null, this)">
                        <input type="radio" name="parent_id" value="" id="parent_none" checked>
                        <div class="parent-option-icon"><i class="fas fa-ban"></i></div>
                        <span class="parent-option-name">-- None (Main Platform) --</span>
                    </div>
                    @foreach($allPlatforms as $pp)
                    <div class="parent-option" id="parent-row-{{ $pp->id }}" onclick="selectParent('{{ $pp->id }}', this)">
                        <input type="radio" name="parent_id" value="{{ $pp->id }}" id="parent_{{ $pp->id }}">
                        <div class="parent-option-icon">
                            @php
                                $ic = $pp->icon ?: 'fas fa-globe';
                                if(stripos($pp->name,'facebook')!==false) $ic='fab fa-facebook';
                                elseif(stripos($pp->name,'youtube')!==false) $ic='fab fa-youtube';
                                elseif(stripos($pp->name,'instagram')!==false) $ic='fab fa-instagram';
                                elseif(stripos($pp->name,'tiktok')!==false) $ic='fab fa-tiktok';
                                elseif(stripos($pp->name,'twitter')!==false||stripos($pp->name,'x.com')!==false) $ic='fab fa-x-twitter';
                            @endphp
                            <i class="{{ $ic }}"></i>
                        </div>
                        <span class="parent-option-name">{{ $pp->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <div class="hicon"><i class="fas fa-search"></i></div>
                    <div><h3>SEO Metadata</h3></div>
                </div>
                <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" placeholder="Meta title for SEO">
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="3" placeholder="Meta description for search engines"></textarea>
                </div>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input type="text" name="meta_keywords" placeholder="Comma separated keywords">
                </div>
                <div class="form-group">
                    <label>Robots Tag</label>
                    <select name="meta_robots">
                        <option value="index, follow" selected>Index, Follow</option>
                        <option value="noindex, follow">Noindex, Follow</option>
                        <option value="index, nofollow">Index, Nofollow</option>
                        <option value="noindex, nofollow">Noindex, Nofollow</option>
                    </select>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <div class="hicon"><i class="fas fa-pen-nib"></i></div>
                    <div><h3>Page Content (Editor.js)</h3></div>
                </div>
                <div class="form-group">
                    <div id="editor"></div>
                </div>
            </div>

            <div style="margin-bottom:4rem; text-align:right;">
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Platform Page</button>
            </div>
        </form>
    </div>
</div>

<script>
    let platformEditor;

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            platformEditor = await editorJSManager.init('editor', {
                placeholder: 'Start building your platform page content...',
                minHeight: 400
            });
        } catch (e) {
            console.error('Editor.js initialization failed:', e);
        }
    });

    document.getElementById('platformForm').onsubmit = async function(e) {
        e.preventDefault();
        try {
            if (platformEditor) {
                const contentData = await editorJSManager.save('editor');
                document.getElementById('contentInput').value = JSON.stringify(contentData);
            }
            this.submit();
        } catch (error) {
            console.error('Saving failed: ', error);
            alert('Something went wrong while saving the content.');
        }
    };

    function toggleParentPicker() {
        const picker = document.getElementById('parentPicker');
        const chk    = document.getElementById('isSubPlatform');
        picker.classList.toggle('visible', chk.checked);
        if (!chk.checked) {
            // Reset to no parent when unchecked
            document.getElementById('parent_none').checked = true;
            document.querySelectorAll('.parent-option').forEach(el => el.classList.remove('selected'));
            document.querySelector('.parent-option').classList.add('selected');
        }
    }

    function selectParent(id, rowEl) {
        document.querySelectorAll('.parent-option').forEach(el => el.classList.remove('selected'));
        rowEl.classList.add('selected');
        if (id) {
            document.getElementById('parent_' + id).checked = true;
        } else {
            document.getElementById('parent_none').checked = true;
        }
    }
</script>

</body>
</html>


