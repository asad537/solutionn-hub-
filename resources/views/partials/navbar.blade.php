<link rel="stylesheet" href="/css/navbar.css">
<header class="topbar">
    <nav class="wrap nav">
        <a class="brand" href="{{ route('home') }}" aria-label="Solution Hub home">
            <img src="/images/logo-hafiz.svg" alt="Solution Hub" width="190" height="60" style="height:75px;width:auto;object-fit:contain;">
        </a>
        <button class="menu-toggle" aria-label="Toggle menu"><span></span></button>
        <div class="nav-links">
            <a class="{{ ($page ?? "") === 'home' ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            @php
                $menuPlatforms = \App\Models\Platform::whereNull('parent_id')
                    ->where('status', 'active')
                    ->where('show_in_navbar', 1)
                    ->with(['children' => function($query) {
                        $query->where('show_in_navbar', 1);
                    }])
                    ->orderBy('name')
                    ->get();
            @endphp
            <div class="nav-dropdown-wrap {{ ($page ?? "") === 'platforms' ? 'active' : '' }}">
                <a class="dropdown-trigger {{ ($page ?? "") === 'platforms' ? 'active' : '' }}" style="cursor:pointer;">Supported Platforms <svg style="display:inline-block;width:12px;height:12px;margin-left:3px;vertical-align:middle;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></a>
                <div class="mega-menu">
                    <div class="mega-menu-grid">
                        @foreach($menuPlatforms as $mp)
                        @php
                            $icoName = strtolower($mp->name);
                            if($icoName == 'twitter' || $icoName == 'x') $icoName = 'x';
                            
                            // Original brand colors for each platform
                            $brandColors = [
                                'youtube' => 'ff0000',
                                'facebook' => '1877f2',
                                'instagram' => 'e4405f',
                                'tiktok' => '000000',
                                'x' => '000000',
                                'twitter' => '1da1f2',
                                'vimeo' => '1ab7ea',
                                'dailymotion' => '0066dc',
                                'pinterest' => 'e60023',
                                'linkedin' => '0a66c2',
                                'snapchat' => 'fffc00',
                                'reddit' => 'ff4500',
                                'tumblr' => '36465d',
                                'whatsapp' => '25d366',
                            ];
                            
                            $iconColor = $brandColors[strtolower($icoName)] ?? '39e1b6'; // fallback to teal
                            
                            if(!empty($mp->icon) && strpos($mp->icon, 'fa-') !== false) {
                                $mpIconHtml = '<i class="'.$mp->icon.'"></i>';
                            } else {
                                $iconSlug = (!empty($mp->icon) && strpos($mp->icon, 'fa-') === false) ? strtolower($mp->icon) : $icoName;
                                $mpIconHtml = '<img src="https://cdn.simpleicons.org/'.$iconSlug.'/'.$iconColor.'" alt="" width="18" height="18" style="display:block;">';
                            }
                            $hasKids = $mp->children->isNotEmpty();
                        @endphp
                        <div style="position:relative;" class="mega-parent-wrap {{ $hasKids ? 'has-kids' : '' }}">
                            <a href="{{ route('platforms.show', $mp->slug) }}" class="mega-item">
                                <div class="mega-icon">{!! $mpIconHtml !!}</div>
                                <span style="text-transform:uppercase;">{{ $mp->name }}</span>
                                @if($hasKids)
                                <i class="fas fa-chevron-right" style="margin-left:auto;font-size:0.65rem;color:#8b5cf6;flex-shrink:0;"></i>
                                @endif
                            </a>
                            @if($hasKids)
                            <div class="mega-child-menu">
                                @foreach($mp->children as $child)
                                @php
                                    $cIconSource = !empty($child->icon) ? $child->icon : $mp->icon;
                                    $cIcoNameFallback = !empty($child->icon) ? strtolower($child->name) : strtolower($mp->name);
                                    if($cIcoNameFallback == 'twitter' || $cIcoNameFallback == 'x') $cIcoNameFallback = 'x';

                                    // Child icon color (use parent's color if not found)
                                    $cIconColor = $brandColors[strtolower($cIcoNameFallback)] ?? $iconColor;

                                    if(!empty($cIconSource) && strpos($cIconSource, 'fa-') !== false) {
                                        $cIconHtml = '<i class="'.$cIconSource.'"></i>';
                                    } else {
                                        $cIconSlug = (!empty($cIconSource) && strpos($cIconSource, 'fa-') === false) ? strtolower($cIconSource) : $cIcoNameFallback;
                                        $cIconHtml = '<img src="https://cdn.simpleicons.org/'.$cIconSlug.'/'.$cIconColor.'" alt="" width="14" height="14" style="display:block;">';
                                    }
                                @endphp
                                <a href="{{ route('platforms.show', $child->slug) }}" class="mega-item mega-child-item">
                                    <div class="mega-icon" style="width:28px;height:28px;">{!! $cIconHtml !!}</div>
                                    <span style="text-transform:uppercase;font-size:0.78rem;">{{ $child->name }}</span>
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <div class="mega-footer">
                        <a href="{{ route('platforms') }}" class="mega-all-link">View all platforms <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <a class="{{ in_array(($page ?? ''), ['blog', 'blog-post']) ? 'active' : '' }}" href="{{ route('blog') }}">Blog</a>
            <a class="{{ ($page ?? "") === 'privacy' ? 'active' : '' }}" href="{{ route('privacy') }}">Privacy</a>
            <!-- Language Selector -->
            <div class="lang-selector notranslate" id="langSelector" translate="no">
                <button class="lang-btn" id="langBtn" aria-haspopup="listbox" aria-expanded="false" aria-label="Select language">
                    <svg class="lang-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span id="langLabel">English</span>
                    <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="lang-dropdown notranslate" id="langDropdown" role="listbox" aria-label="Languages" translate="no">
                    <div class="lang-dropdown-inner">
                        <div class="lang-option active" data-lang="en"    data-label="English"    role="option">English</div>
                        <div class="lang-option"        data-lang="ar"    data-label="العربية"    role="option">العربية</div>
                        <div class="lang-option"        data-lang="ur"    data-label="اردو"       role="option">اردو</div>
                        <div class="lang-option"        data-lang="hi"    data-label="हिंदी"      role="option">हिंदी</div>
                        <div class="lang-option"        data-lang="es"    data-label="Español"    role="option">Español</div>
                        <div class="lang-option"        data-lang="fr"    data-label="Français"   role="option">Français</div>
                        <div class="lang-option"        data-lang="pt"    data-label="Português"  role="option">Português</div>
                        <div class="lang-option"        data-lang="ko"    data-label="한국어"      role="option">한국어</div>
                        <div class="lang-option"        data-lang="tr"    data-label="Türkçe"     role="option">Türkçe</div>
                        <div class="lang-option"        data-lang="vi"    data-label="Tiếng Việt" role="option">Tiếng Việt</div>
                        <div class="lang-option"        data-lang="ru"    data-label="Русский"    role="option">Русский</div>
                        <div class="lang-option"        data-lang="it"    data-label="Italiano"   role="option">Italiano</div>
                        <div class="lang-option"        data-lang="de"    data-label="Deutsch"    role="option">Deutsch</div>
                        <div class="lang-option"        data-lang="zh-CN" data-label="中文"        role="option">中文</div>
                        <div class="lang-option"        data-lang="ja"    data-label="日本語"      role="option">日本語</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    iframe.goog-te-banner-frame,
    iframe.VIpgJd-ZVi9od-ORHb-OEVmcd,
    .goog-te-banner-frame,
    .VIpgJd-ZVi9od-ORHb,
    .VIpgJd-ZVi9od-ORHb-OEVmcd,
    #goog-gt-tt,
    .goog-te-balloon-frame,
    .goog-te-spinner-pos,
    .goog-te-spinner,
    .goog-te-spinner-animation,
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
    .VIpgJd-ZVi9od-aZ2wEe-OiiCO,
    .VIpgJd-ZVi9od-aZ2wEe { display:none !important; visibility:hidden !important; width:0 !important; height:0 !important; opacity:0 !important; pointer-events:none !important; }
    html, body { top:0 !important; margin-top:0 !important; }
    .goog-text-highlight { background:transparent !important; box-shadow:none !important; }
    #gt-root { position:absolute !important; width:1px !important; height:1px !important; overflow:hidden !important; opacity:0 !important; pointer-events:none !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        if (menuToggle && navLinks) {
            menuToggle.addEventListener('click', function() {
                menuToggle.classList.toggle('is-open');
                navLinks.classList.toggle('is-open');
            });
        }

        // Language Selector
        (function() {
            var selector = document.getElementById('langSelector');
            var btn      = document.getElementById('langBtn');
            var label    = document.getElementById('langLabel');
            var options  = document.querySelectorAll('.lang-option');
            if (!selector || !btn) return;

            function loadGoogleTranslate() {
                if (window._gtLoaded) return;
                window._gtLoaded = true;
                var el = document.getElementById('gt-root');
                if (!el) {
                    el = document.createElement('div');
                    el.id = 'gt-root';
                    document.body.appendChild(el);
                }
                window.googleTranslateElementInit = function() {
                    new google.translate.TranslateElement({pageLanguage:'en', autoDisplay:false}, 'gt-root');
                    window._gtReady = true;
                };
                var s = document.createElement('script');
                s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                s.async = true;
                document.head.appendChild(s);
            }

            // Preload in the background so the first language switch is faster.
            if ('requestIdleCallback' in window) requestIdleCallback(loadGoogleTranslate, {timeout:1200});
            else setTimeout(loadGoogleTranslate, 300);

            // Toggle dropdown
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var isOpen = selector.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen);
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!selector.contains(e.target)) {
                    selector.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });

            // Language option click
            options.forEach(function(opt) {
                opt.addEventListener('click', function() {
                    var lang     = this.getAttribute('data-lang');
                    var langName = this.getAttribute('data-label');

                    // Update active state
                    options.forEach(function(o) { o.classList.remove('active'); });
                    this.classList.add('active');

                    // Update button label
                    if (label) label.textContent = langName;

                    // Save to localStorage
                    localStorage.setItem('hd_lang', lang);
                    localStorage.setItem('hd_lang_label', langName);

                    // Close dropdown
                    selector.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');

                    // Apply translation via Google Translate
                    applyGoogleTranslate(lang);
                });
            });

            // Restore saved language on page load
            var savedLang  = localStorage.getItem('hd_lang');
            var savedLabel = localStorage.getItem('hd_lang_label');
            if (savedLang && savedLang !== 'en') {
                if (label) label.textContent = savedLabel || savedLang;
                options.forEach(function(o) {
                    o.classList.toggle('active', o.getAttribute('data-lang') === savedLang);
                });
                applyGoogleTranslate(savedLang);
            }

            function applyGoogleTranslate(lang) {
                if (lang === 'en') {
                    document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                    document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + location.hostname + ';';
                    location.reload();
                    return;
                }
                var val = '/en/' + lang;
                document.cookie = 'googtrans=' + val + '; path=/';
                document.cookie = 'googtrans=' + val + '; path=/; domain=' + location.hostname;

                loadGoogleTranslate();
                var attempts = 0;
                var switchLanguage = setInterval(function() {
                    var combo = document.querySelector('#gt-root select.goog-te-combo, select.goog-te-combo');
                    if (combo) {
                        clearInterval(switchLanguage);
                        combo.value = lang;
                        combo.dispatchEvent(new Event('change', {bubbles:true}));
                    } else if (++attempts >= 30) {
                        clearInterval(switchLanguage);
                        location.reload();
                    }
                }, 100);
            }

            function hideGoogleChrome() {
                document.querySelectorAll('iframe.goog-te-banner-frame, iframe.VIpgJd-ZVi9od-ORHb-OEVmcd, .goog-te-spinner-pos, .goog-te-spinner, .VIpgJd-ZVi9od-aZ2wEe-wOHMyf, .VIpgJd-ZVi9od-aZ2wEe-OiiCO').forEach(function(f) {
                    f.style.setProperty('display','none','important');
                    f.style.setProperty('visibility','hidden','important');
                    f.style.setProperty('opacity','0','important');
                });
                if (document.body.style.top && document.body.style.top !== '0px') {
                    document.body.style.setProperty('top','0','important');
                }
                document.documentElement.style.setProperty('margin-top','0','important');
            }
            new MutationObserver(hideGoogleChrome).observe(document.documentElement, {childList:true, subtree:true, attributes:true});
            setInterval(hideGoogleChrome, 500);
            hideGoogleChrome();
        })();
    });
</script>
