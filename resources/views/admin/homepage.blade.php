@extends('layouts.admin_master')

@section('title', 'Home Page Settings')

@section('header_icon')
    <i class="fas fa-home" style="color:#3668de;"></i>
@endsection

@section('header_title', 'Home Page Settings')

@section('breadcrumb')
    / Home Page
@endsection

@section('topbar_actions')
    <!-- Any specific topbar actions can go here -->
@endsection

@push('styles')
    <!-- Editor.js & Custom Styles -->
    <style>
        .form-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:1.8rem;margin-bottom:1.5rem;}
        .form-card-header{display:flex;align-items:center;gap:0.7rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .hicon{width:36px;height:36px;border-radius:10px;background:rgba(139, 92, 246,0.12);color:#3668de;display:flex;align-items:center;justify-content:center;font-size:0.95rem;}
        .form-card-header h3{font-size:0.95rem;font-weight:700;color:#fff;}
        .form-card-header p{font-size:0.75rem;color:rgba(255,255,255,0.35); line-height: 1.45; }
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.2rem;}
        .form-row.full{grid-template-columns:1fr;margin-bottom:1.2rem;}
        .form-group label{display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;}
        .form-group input, .form-group textarea{width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;transition:all 0.2s;}
        .form-group input:focus, .form-group textarea:focus{border-color:#3668de;background:rgba(139, 92, 246,0.06);box-shadow:0 0 0 4px rgba(139, 92, 246,0.05);}
        .hint{font-size:0.72rem;color:rgba(255,255,255,0.25);margin-top:0.4rem;}

        /* Editor.js Styling */
        #sites_description_editor {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            min-height: 400px;
            max-height: 700px;
            overflow-y: auto;
            color: #1E293B;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        #sites_description_editor::-webkit-scrollbar { width: 6px; }
        #sites_description_editor::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 0 12px 12px 0; }
        #sites_description_editor::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        #sites_description_editor::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .ce-header { color: #0F172A; font-weight: 800 !important; margin-bottom: 0.8em; }
        .ce-paragraph { color: #334155; line-height: 1.8; font-size: 1rem; }
        .ce-block__content, .ce-toolbar__content { max-width: 800px; }
        
        .ce-code__textarea, .ce-input, .cdx-input, .ce-link-autocomplete__input, 
        .ce-inline-tool-input, .ce-inline-toolbar input, .ce-toolbar input {
            color: #1E293B !important;
            background: #ffffff !important;
            border: 1px solid #CBD5E1 !important;
        }

        /* Preview Mockup */
        .preview-section{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:1.8rem;margin-bottom:1.5rem;}
        .preview-label{font-size:0.7rem;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;}
        .preview-mockup{background:linear-gradient(135deg,#1a1a2e,#16213e);border-radius:12px;padding:2.5rem 1.5rem;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,0.2); line-height: 1.45; }
        .preview-h1{font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:1.2rem;line-height:1.3;}
        .preview-btn-el{display:inline-block;background:linear-gradient(135deg,#3668de,#3668de);color:#fff;padding:0.7rem 1.8rem;border-radius:30px;font-size:0.9rem;font-weight:700;text-decoration:none;box-shadow:0 6px 20px rgba(139, 92, 246,0.3);}

        .btn-row{display:flex;align-items:center;gap:1rem;margin-top:2rem;}
        .btn-save{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.9rem 3rem;font-size:1rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;box-shadow:0 8px 25px rgba(139, 92, 246,0.35);transition:all 0.2s;}
        .btn-save:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(139, 92, 246,0.5);}
    </style>
@endpush

@section('content')
    <form id="homepageForm" method="POST" action="{{ route('admin.homepage.save') }}">
        @csrf

        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-heading"></i></div>
                <div>
                    <h3>Hero Section</h3>
                    <p style="line-height: 1.45;">Edit homepage headline, button, and description</p>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Hero Heading (H1)</label>
                    <input type="text" name="hero_heading" id="heroHeading"
                        value="{{ optional($settings)->hero_heading ?? '' }}"
                        placeholder="Enter main heading...">
                    <p class="hint" style="line-height: 1.45;">Main H1 title shown at the top of homepage.</p>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Hero Description</label>
                    @php
                        $heroDescText = optional($settings)->hero_description ?? '';
                        if (strpos(trim($heroDescText), '{') === 0 && strpos($heroDescText, '"blocks"') !== false) {
                            $descData = json_decode($heroDescText, true);
                            if (isset($descData['blocks']) && is_array($descData['blocks'])) {
                                $text = '';
                                foreach ($descData['blocks'] as $block) {
                                    if (isset($block['data']['text'])) {
                                        $text .= strip_tags($block['data']['text']) . "\n";
                                    }
                                }
                                $heroDescText = trim($text);
                            }
                        }
                    @endphp
                    <textarea name="hero_description" rows="3" placeholder="Enter hero description..." style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.8rem 1rem;font-size:0.88rem;color:#fff;font-family:'Inter',sans-serif;outline:none;resize:vertical;">{{ $heroDescText }}</textarea>
                </div>
            </div>
        </div>

        <!-- SEO SETTINGS SECTION -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-search"></i></div>
                <div>
                    <h3>Homepage SEO Settings</h3>
                    <p style="line-height: 1.45;">Manage meta title, description and keywords for SEO</p>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" value="{{ optional($seo)->meta_title ?? '' }}" placeholder="e.g. Public Media Link Analyzer - Solution Hub">
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" rows="3" placeholder="e.g. Analyze supported public media links and review available formats.">{{ optional($seo)->meta_description ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <textarea name="meta_keywords" rows="2" placeholder="e.g. public media link analyzer, format guide, supported public links">{{ optional($seo)->meta_keywords ?? '' }}</textarea>
                    <p class="hint">Separate keywords with commas.</p>
                </div>
            </div>

            <div class="form-row full">
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
        </div>

        <!-- SUPPORTED PLATFORMS SECTION -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-globe"></i></div>
                <div>
                    <h3>Supported Platforms</h3>
                    <p style="line-height: 1.45;">Manage the platform icons grid</p>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label><i class="fas fa-th"></i> Platforms List</label>
                    <input type="hidden" name="platforms_data" id="platformsJson">
                    <div id="platformRepeater" style="display:flex;flex-direction:column;gap:0.8rem;"></div>
                    <button type="button" onclick="addPlatform()"
                        style="margin-top:0.8rem;background:rgba(139, 92, 246,0.1);border:1px dashed rgba(139, 92, 246,0.4);color:#3668de;padding:0.7rem;border-radius:12px;font-size:0.85rem;font-weight:600;cursor:pointer;width:100%;">
                        <i class="fas fa-plus"></i> Add Platform
                    </button>
                </div>
            </div>
        </div>

        <!-- SEO CONTENT SECTION -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="hicon"><i class="fas fa-align-left"></i></div>
                <div>
                    <h3>Content</h3>
                    <p style="line-height: 1.45;">Add rich text content to display above the FAQs</p>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Content Heading</label>
                    <input type="text" name="sites_heading"
                        value="{{ optional($settings)->sites_heading ?? '' }}"
                        placeholder="Heading for this content...">
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label><i class="fas fa-edit"></i> Content Description (Editor.js)</label>
                    <div id="sites_description_editor"></div>
                    <input type="hidden" name="sites_description" id="sites_description_input">
                </div>
            </div>
        </div>

        <!-- LIVE PREVIEW -->
        <div class="preview-section">
            <div class="preview-label"><i class="fas fa-eye"></i> Desktop Preview (Mock)</div>
            <div class="preview-mockup">
                <div class="preview-h1" id="previewH1">{{ optional($settings)->hero_heading ?? 'Heading Preview' }}</div>
            </div>
        </div>

        <div class="btn-row">
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save All Changes</button>
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
        let sitesEditor;

        document.addEventListener('DOMContentLoaded', async () => {
            console.log('Admin Homepage: Initializing Editor.js...');
            try {
                // Initialize sites editor
                let sitesInitialData = { blocks: [] };
                <?php $rawSitesDesc = optional($settings)->sites_description ?? ''; ?>
                const rawSitesContent = {!! json_encode($rawSitesDesc) !!};
                
                if (rawSitesContent) {
                    try {
                        if (typeof rawSitesContent === 'object' && rawSitesContent !== null && rawSitesContent.blocks) {
                            sitesInitialData = rawSitesContent;
                        } else if (typeof rawSitesContent === 'string') {
                            try {
                                const parsed = JSON.parse(rawSitesContent);
                                if (parsed && typeof parsed === 'object' && parsed.blocks) {
                                    sitesInitialData = parsed;
                                } else {
                                    throw new Error('Not valid JSON');
                                }
                            } catch (e) {
                                if (rawSitesContent.includes('<') || rawSitesContent.includes('>')) {
                                    sitesInitialData = editorJSManager.fromHTML(rawSitesContent);
                                } else {
                                    sitesInitialData = { blocks: [{ type: 'paragraph', data: { text: rawSitesContent } }] };
                                }
                            }
                        }
                    } catch (e) {
                        console.error('Error processing sitesInitialData:', e);
                    }
                }

                sitesEditor = await editorJSManager.init('sites_description_editor', {
                    placeholder: 'Write SEO content description...',
                    minHeight: 200,
                    data: sitesInitialData
                });

                console.log('Editor.js initialized successfully');
            } catch (e) { 
                console.error('Editor.js initialization failed:', e); 
            }
        });

        document.getElementById('homepageForm').onsubmit = async function(e) {
            e.preventDefault();
            if (sitesEditor) {
                const sitesContentData = await editorJSManager.save('sites_description_editor');
                document.getElementById('sites_description_input').value = JSON.stringify(sitesContentData);
            }
            buildPlatformsJson();
            this.submit();
        };

        function addPlatform(data = {}) {
            const wrap = document.getElementById('platformRepeater');
            const row = document.createElement('div');
            row.className = 'platform-row';
            row.style.cssText = 'display:grid;grid-template-columns:auto 1fr 1fr 120px 40px;gap:0.6rem;align-items:center;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:0.8rem;';
            row.innerHTML = `
                <div>
                    <input type="text" placeholder="Img URL" value="${data.img||''}" class="p-img" style="width:60px;font-size:0.65rem;">
                </div>
                <input type="text" placeholder="Name" value="${data.name||''}" class="p-name">
                <input type="text" placeholder="Link" value="${data.url||''}" class="p-url">
                <div style="display:flex;gap:4px;">
                    <input type="text" placeholder="Icon" value="${data.icon||''}" class="p-icon" style="flex:1;font-size:0.7rem;">
                    <input type="color" value="${data.color||'#333333'}" class="p-color" style="width:30px;height:30px;border:none;background:transparent;">
                </div>
                <button type="button" onclick="this.closest('.platform-row').remove()" style="background:rgba(239,68,68,0.1);border:none;color:#FCA5A5;height:36px;border-radius:8px;cursor:pointer;"><i class="fas fa-trash"></i></button>`;
            wrap.appendChild(row);
        }

        function buildPlatformsJson() {
            const rows = document.querySelectorAll('.platform-row');
            const data = [];
            rows.forEach(row => {
                const img = row.querySelector('.p-img').value;
                const name = row.querySelector('.p-name').value;
                const url = row.querySelector('.p-url').value;
                const icon = row.querySelector('.p-icon').value;
                const color = row.querySelector('.p-color').value;
                if (name) data.push({ img, name, url, icon, color });
            });
            document.getElementById('platformsJson').value = JSON.stringify(data);
        }

        <?php $pData = json_decode(optional($settings)->platforms_data ?? '[]', true) ?: []; ?>
        const existingPlatforms = {!! json_encode($pData) !!};
        existingPlatforms.forEach(p => addPlatform(p));

        document.getElementById('heroHeading').oninput = function() { document.getElementById('previewH1').textContent = this.value; };
        document.getElementById('heroBtnText').oninput = function() { document.querySelector('.preview-btn-el').textContent = this.value; };
    </script>
@endpush
