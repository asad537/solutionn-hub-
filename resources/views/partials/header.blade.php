<noscript>
    <style>
        body { opacity: 1 !important; }
    </style>
</noscript>
<style>
    body {
        opacity: 0;
    }
    h1 { font-size: 3rem !important; }
    h2 { font-size: 2rem !important; }
    h3 { font-size: 1.5rem !important; }
    p { font-size: 1.2rem !important; }
</style>
<script>
    (function() {
        let shown = false;
        function showPage() {
            if (shown) return;
            shown = true;
            document.body.style.transition = 'opacity 0.18s ease-in-out';
            document.body.style.opacity = '1';
        }
        if (document.readyState === 'interactive' || document.readyState === 'complete') {
            showPage();
        } else {
            document.addEventListener('DOMContentLoaded', showPage);
            window.addEventListener('load', showPage);
            // Fallback after 1 second so page is never stuck blank
            setTimeout(showPage, 1000);
        }
    })();
</script>

<header class="site-header {{ $headerClass ?? '' }}">
    <div class="header-container">

        <!-- Mobile Hamburger (LEFT) — hidden on desktop -->
        <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <!-- Logo (CENTER on mobile, LEFT on desktop) -->
        <a href="/" class="logo">
            <img src="/images/logo-hafiz.svg" alt="Solution Hub logo" title="Solution Hub Logo">
        </a>

        <!-- Desktop Nav -->
        <div class="nav-wrap">
            <ul class="nav-links">
                <li><a href="/">Home</a></li>


                <li><a href="/faqs/">FAQs</a></li>
                
                @php
                    // Only parent platforms (no parent_id)
                    $navPlatforms = \App\Models\Platform::whereNull('parent_id')
                        ->where('status', 'active')
                        ->with('children')
                        ->orderBy('name')
                        ->get();
                @endphp
                
                <li class="nav-dropdown-wrap">
                    <a style="cursor: pointer;" class="dropdown-trigger">Supported Platforms</a>
                    <div class="nav-dropdown">
                        <div class="dropdown-grid">
                            @foreach($navPlatforms as $np)
                            @php
                                if(!empty($np->icon)) {
                                    $iconClass = $np->icon;
                                } else {
                                    $iconClass = 'fas fa-globe';
                                    if(stripos($np->name, 'facebook') !== false) $iconClass = 'fab fa-facebook';
                                    elseif(stripos($np->name, 'youtube') !== false) $iconClass = 'fab fa-youtube';
                                    elseif(stripos($np->name, 'instagram') !== false) $iconClass = 'fab fa-instagram';
                                    elseif(stripos($np->name, 'whatsapp') !== false) $iconClass = 'fab fa-whatsapp';
                                    elseif(stripos($np->name, 'tiktok') !== false) $iconClass = 'fab fa-tiktok';
                                }
                                $hasKids = $np->children->isNotEmpty();
                            @endphp

                            @if($hasKids)
                            {{-- Parent platform with children: show arrow + nested flyout --}}
                            <div class="dropdown-item-wrap has-children">
                                <a href="{{ route('platforms.show', $np->slug) }}" class="dropdown-item dropdown-item--parent">
                                    <div class="item-icon"><i class="{{ $iconClass }}"></i></div>
                                    <span>{{ $np->name }}</span>
                                    <i class="fas fa-chevron-right item-arrow"></i>
                                </a>
                                <div class="child-dropdown">
                                    @foreach($np->children as $child)
                                    @php
                                        if(!empty($child->icon)) {
                                            $cIcon = $child->icon;
                                        } else {
                                            $cIcon = $iconClass;
                                        }
                                    @endphp
                                    <a href="{{ route('platforms.show', $child->slug) }}" class="dropdown-item child-item">
                                        <div class="item-icon" style="width:28px;height:28px;font-size:11px;"><i class="{{ $cIcon }}"></i></div>
                                        <span>{{ $child->name }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            {{-- Regular platform: direct link --}}
                            <a href="{{ route('platforms.show', $np->slug) }}" class="dropdown-item">
                                <div class="item-icon"><i class="{{ $iconClass }}"></i></div>
                                <span>{{ $np->name }}</span>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </li>
                
            </ul>
            <div class="lang-dropdown notranslate" onclick="toggleLangMenu()">
                <span id="current-lang">English</span> <i class="fas fa-chevron-down"></i>
                <div id="lang-menu" class="lang-menu notranslate">
                    <div onclick="changeLanguage('en')">English</div>
                    <div onclick="changeLanguage('ar')">Arabic</div>
                    <div onclick="changeLanguage('ur')">Urdu</div>
                    <div onclick="changeLanguage('hi')">Hindi</div>
                    <div onclick="changeLanguage('es')">Spanish</div>
                    <div onclick="changeLanguage('fr')">French</div>
                    <div onclick="changeLanguage('pt')">Portuguese</div>
                    <div onclick="changeLanguage('ko')">Korean</div>
                    <div onclick="changeLanguage('tr')">Turkish</div>
                    <div onclick="changeLanguage('vi')">Vietnamese</div>
                    <div onclick="changeLanguage('ru')">Russian</div>
                    <div onclick="changeLanguage('it')">Italian</div>
                </div>
            </div>
        </div>

        <!-- Mobile Lang (RIGHT) — hidden on desktop -->
        <div class="lang-dropdown lang-dropdown-mobile notranslate" onclick="toggleLangMenuMobile()">
            <span id="current-lang-mobile">English</span> <i class="fas fa-chevron-down"></i>
            <div id="lang-menu-mobile" class="lang-menu notranslate">
                <div onclick="changeLanguage('en')">English</div>
                <div onclick="changeLanguage('ar')">Arabic</div>
                <div onclick="changeLanguage('ur')">Urdu</div>
                <div onclick="changeLanguage('hi')">Hindi</div>
                <div onclick="changeLanguage('es')">Spanish</div>
                <div onclick="changeLanguage('fr')">French</div>
                <div onclick="changeLanguage('pt')">Portuguese</div>
                    <div onclick="changeLanguage('ko')">Korean</div>
                    <div onclick="changeLanguage('tr')">Turkish</div>
                    <div onclick="changeLanguage('vi')">Vietnamese</div>
                    <div onclick="changeLanguage('ru')">Russian</div>
                    <div onclick="changeLanguage('it')">Italian</div>
            </div>
        </div>
    </div>

    <!-- Mobile Slide-down Nav (Sidebar) -->
    <div class="mobile-nav" id="mobile-nav" style="display:none;">
        <!-- Logo at top of sidebar -->
        <div style="text-align: center; padding: 2.5rem 0 1.5rem;">
            <img src="/images/logo-hafiz.svg" alt="Solution Hub logo" title="Solution Hub Logo" style="height: 60px; width: auto;">
        </div>
        <ul>
            <li><a href="/" onclick="toggleMobileMenu()">Home</a></li>
            <li><a href="/faqs/" onclick="toggleMobileMenu()">FAQs</a></li>
            
            <li style="padding: 1.2rem 1.5rem; border-bottom: 1px solid #F3F4F6;">
                <div style="font-size: 0.75rem; font-weight: 800; color: #9CA3AF; text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 0.05em;">Platforms</div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    @foreach($navPlatforms as $np)
                    <a href="{{ route('platforms.show', $np->slug) }}" onclick="toggleMobileMenu()" style="display:flex; align-items:center; gap:8px; text-decoration:none; color:#111827; font-size:0.9rem; font-weight:600; padding:8px; background:#F9FAFB; border-radius:8px;">
                        @php
                            if(!empty($np->icon)) {
                                $iconClass = $np->icon;
                            } else {
                                $iconClass = 'fas fa-globe';
                                if(stripos($np->name, 'facebook') !== false) $iconClass = 'fab fa-facebook';
                                elseif(stripos($np->name, 'youtube') !== false) $iconClass = 'fab fa-youtube';
                                elseif(stripos($np->name, 'instagram') !== false) $iconClass = 'fab fa-instagram';
                                elseif(stripos($np->name, 'whatsapp') !== false) $iconClass = 'fab fa-whatsapp';
                                elseif(stripos($np->name, 'tiktok') !== false) $iconClass = 'fab fa-tiktok';
                            }
                        @endphp
                        <i class="{{ $iconClass }}" style="color:#3668de;"></i>
                        {{ $np->name }}
                    </a>
                    @endforeach
                </div>
            </li>
            
            <li><a href="/help-center/" onclick="toggleMobileMenu()">Help Center</a></li>
        </ul>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobile-overlay" style="display:none;" onclick="toggleMobileMenu()"></div>

    <!-- Hidden Google Translate -->
    <div id="google_translate_element"
        style="position: absolute; opacity: 0; pointer-events: none; height: 0; overflow: hidden;"></div>
</header>

<style>
    h2 {
        font-size: 1.8rem !important;
    }

    p {
        font-size: 1.2rem !important; line-height: 1.45; }

    /* ══ Ultra-Aggressive Hide Google Translate Bar ══ */
    iframe.skiptranslate,
    .goog-te-banner-frame,
    .goog-te-banner,
    .goog-te-balloon-frame,
    #goog-gt-tt,
    .goog-tooltip,
    .goog-tooltip:hover,
    .goog-te-spinner-pos,
    .goog-te-spinner,
    .goog-te-spinner-pos+div,
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf-ti6hGc,
    .goog-te-gadget-icon,
    div[id^="goog-gt-"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    body {
        top: 0px !important;
    }
    
    body.has-fixed-header {
        padding-top: 97px;
    }
    
    @media (max-width: 768px) {
        body.has-fixed-header {
            padding-top: 80px;
        }
    }

    /* ── Header Styles ── */
    .site-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        padding: 1rem 0;
        overflow: visible;
        background: {{ (Request::routeIs('home') || Request::is('*/platforms/*') || Request::is('supported-platforms/*') || Request::routeIs('platforms.show')) ? 'transparent' : '#fff' }};
        box-shadow: {{ (Request::routeIs('home') || Request::is('*/platforms/*') || Request::is('supported-platforms/*') || Request::routeIs('platforms.show')) ? 'none' : '0 2px 10px rgba(0,0,0,0.05)' }};
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo img {
        height: 65px;
        width: auto;
    }

    .nav-wrap {
        display: flex;
        align-items: center;
        gap: 3.5rem; line-height: 1.45; }

    .nav-links {
        display: flex;
        gap: 2.5rem;
        list-style: none;
        align-items: center;
    }

    .nav-links a {
        text-decoration: none;
        color: #111827;
        font-weight: 600;
        font-size: 0.95rem;
        transition: color 0.2s;
    }

    .nav-links a:hover {
        color: #111827;
    }

    .lang-dropdown {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        position: relative;
    }

    .lang-menu {
        position: absolute;
        top: 110%;
        right: 0;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        width: 150px;
        display: none;
        flex-direction: column;
        overflow: hidden;
        z-index: 1001;
    }

    .lang-menu div {
        padding: 10px 15px;
        font-size: 0.85rem;
        color: #111827;
        transition: background 0.2s;
    }

    .lang-menu div:hover {
        background: #F9FAFB;
        color: #3668de;
    }

    .hamburger {
        display: none;
    }

    .mobile-nav {
        display: none;
    }

    .mobile-overlay {
        display: none;
    }

    .lang-dropdown-mobile {
        display: none !important;
    }

    @media (max-width: 768px) {

        /* Desktop-only elements */
        .nav-wrap {
            display: none !important; line-height: 1.45; }

        /* Mobile header: 3-column layout — ≡ | LOGO | English */
        .site-header .header-container {
            display: grid !important;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            padding: 0 1rem !important;
        }

        /* Hamburger: far left */
        .hamburger {
            display: flex !important;
            background: none;
            border: none;
            cursor: pointer;
            flex-direction: column;
            gap: 5px;
            padding: 4px;
            order: 1;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2.5px;
            background: #111827;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7.5px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7.5px) rotate(-45deg);
        }

        /* Logo: centered */
        .site-header .logo {
            order: 2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .site-header .logo img {
            height: 65px !important; 
            width: auto;
        }

        /* Lang dropdown: far right */
        .lang-dropdown-mobile {
            order: 3;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            border: 1px solid #000 !important;
            background: #fff !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
        }

        .lang-dropdown-mobile i {
            font-size: 0.75rem;
            margin-top: 2px; /* Moves arrow down slightly */
        }

        /* Sidebar Nav */
        .mobile-nav {
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: #fff;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .mobile-nav.open {
            left: 0;
        }

        .mobile-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav ul li a {
            display: block;
            padding: 1.2rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            text-decoration: none;
            border-bottom: 1px solid #F3F4F6;
            text-align: center;
        }

        /* Overlay */
        .mobile-overlay {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 999;
        }

        .mobile-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        /* Sticky Header on Mobile */
        .site-header {
            position: fixed !important;
            top: 0 !important;
            background: #ffffff !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.09);
            z-index: 999 !important;
            padding: 0.6rem 0 !important;
        }

        .faq-header {
            padding-top: 4rem !important;
        }
    }

    /* ── Desktop Header ── */
    @media (min-width: 769px) {
        .site-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 0;
            background: transparent;
        }

        /* If white background is needed */
        .site-header.header-white {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed;
        }
        
        /* Make it transparent by default on desktop if no white class */
        .site-header:not(.header-white) {
            background: transparent;
            box-shadow: none;
            position: absolute;
            overflow: visible;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 65px;
            width: auto;
            display: block;
        }

        .nav-wrap {
            display: flex;
            align-items: center;
            gap: 2.5rem; line-height: 1.45; }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #111827;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #111827;
        }

        .lang-dropdown {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #E5E7EB;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            position: relative;
            backdrop-filter: blur(4px);
        }

        .lang-menu {
            position: absolute;
            top: 110%;
            right: 0;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 150px;
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 1001;
        }

        .lang-menu div {
            padding: 10px 15px;
            font-size: 0.85rem;
            color: #111827;
            transition: background 0.2s;
        }

        .lang-menu div:hover {
            background: #F9FAFB;
            color: #3668de;
        }
    }

    /* ── Desktop Dropdown Styles ── */
    .nav-dropdown-wrap {
        position: relative;
    }
    .nav-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 12px);
        right: -20px;
        left: auto;
        transform: none;
        background: #ffffff;
        min-width: 320px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        padding: 1.5rem;
        border: 1px solid rgba(0,0,0,0.06);
        z-index: 99999;
        overflow: visible;
        pointer-events: auto;
    }
    /* Invisible bridge to prevent hover loss between trigger and dropdown */
    .nav-dropdown::before {
        content: '';
        position: absolute;
        top: -15px;
        left: 0;
        width: 100%;
        height: 15px;
        background: transparent;
    }
    .nav-dropdown-wrap:hover .nav-dropdown,
    .nav-dropdown-wrap.open .nav-dropdown {
        display: block;
    }
    .dropdown-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 8px;
    }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 16px;
        text-decoration: none;
        color: #0f172a;
        font-weight: 700;
        font-size: 1.05rem;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .dropdown-item span {
        color: #0f172a;
        transition: color 0.2s;
    }
    .dropdown-item:hover {
        background: rgba(255, 184, 0, 0.08);
        color: #0f172a !important;
        transform: translateX(4px);
    }
    .dropdown-item:hover span {
        color: #0f172a !important;
    }
    .item-icon {
        width: 42px;
        height: 42px;
        background: #FEF3C7;
        color: #3668de;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.15rem;
    }

    /* ── Child / Sub-Platform Submenu ── */
    .dropdown-item-wrap {
        position: relative;
    }
    .dropdown-item--parent {
        position: relative;
    }
    .item-arrow {
        margin-left: auto;
        font-size: 0.7rem;
        color: #aaa;
        transition: transform 0.2s ease, color 0.2s;
        flex-shrink: 0;
    }
    .dropdown-item-wrap:hover .item-arrow {
        color: #3668de;
        transform: translateX(2px);
    }
    .child-dropdown {
        display: none;
        position: absolute;
        left: calc(100% + 6px);
        right: auto;
        top: 0;
        min-width: 240px;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 14px;
        box-shadow: 0 16px 48px rgba(0,0,0,0.12);
        padding: 8px;
        z-index: 9999;
    }
    .dropdown-item-wrap:hover .child-dropdown {
        display: block;
    }
    .child-item {
        font-size: 0.9rem;
        padding: 9px 12px;
        border-radius: 8px;
        gap: 12px;
    }
    .child-item .item-icon {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
        border-radius: 8px;
    }
</style>

<!-- Google Translate Scripts -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,ar,ur,hi,es,fr,pt,ko,tr,vi,ru,it',
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>

<script>

    function toggleMobileMenu() {
        const nav = document.getElementById('mobile-nav');
        const overlay = document.getElementById('mobile-overlay');
        const btn = document.getElementById('hamburger');

        // Clear inline style (set for FOUC prevention) on first open
        if (nav.style.display === 'none' && !nav.classList.contains('open')) {
            nav.style.display = '';
            overlay.style.display = '';
        }

        nav.classList.toggle('open');
        overlay.classList.toggle('open');
        btn.classList.toggle('open');

        // Prevent body scroll when menu is open
        if (nav.classList.contains('open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    function toggleLangMenu() {
        const menu = document.getElementById('lang-menu');
        if (menu) menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    }

    function toggleLangMenuMobile() {
        const menu = document.getElementById('lang-menu-mobile');
        if (menu) menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    }

    function changeLanguage(langCode) {
        if (langCode === 'en') {
            localStorage.setItem('selectedLanguage', 'en');
            localStorage.setItem('selectedLanguageName', 'English');
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + location.host;
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + location.hostname.split('.').slice(-2).join('.');
            location.reload();
            return;
        }

        const select = document.querySelector('.goog-te-combo');
        if (select) {
            let actualLang = select.value || 'en';
            if (actualLang === langCode) {
                const menu = document.getElementById('lang-menu');
                if (menu) menu.style.display = 'none';
                const menuMobile = document.getElementById('lang-menu-mobile');
                if (menuMobile) menuMobile.style.display = 'none';
                return;
            }

            select.value = langCode;
            select.dispatchEvent(new Event('change'));

            const langNames = {
                'en': 'English', 'ar': 'Arabic', 'ur': 'Urdu',
                'hi': 'Hindi', 'es': 'Spanish', 'fr': 'French', 'pt': 'Portuguese', 'ko': 'Korean', 'tr': 'Turkish', 'vi': 'Vietnamese', 'ru': 'Russian', 'it': 'Italian'
            };

            // Save to localStorage
            localStorage.setItem('selectedLanguage', langCode);
            localStorage.setItem('selectedLanguageName', langNames[langCode]);

            // Update UI
            const currentLangEl = document.getElementById('current-lang');
            if (currentLangEl) currentLangEl.innerText = langNames[langCode];

            const currentLangMobileEl = document.getElementById('current-lang-mobile');
            if (currentLangMobileEl) currentLangMobileEl.innerText = langNames[langCode];
        } else {
            console.log("Retrying translation...");
            setTimeout(() => changeLanguage(langCode), 300);
        }

        const menu = document.getElementById('lang-menu');
        if (menu) menu.style.display = 'none';
        const menuMobile = document.getElementById('lang-menu-mobile');
        if (menuMobile) menuMobile.style.display = 'none';
    }

    // Restore language on page load
    window.addEventListener('DOMContentLoaded', function () {
        // Check Google Translate cookie first
        const googleLangCookie = document.cookie.split('; ').find(row => row.startsWith('googtrans='));
        let savedLang = localStorage.getItem('selectedLanguage');

        // If Google Translate cookie exists, extract language from it
        if (googleLangCookie) {
            const cookieValue = googleLangCookie.split('=')[1];
            // Cookie format: /en/ur (from English to Urdu)
            const match = cookieValue.match(/\/en\/([a-z]{2})/);
            if (match && match[1]) {
                savedLang = match[1];
                // Update localStorage to match
                const langNames = {
                    'en': 'English', 'ar': 'Arabic', 'ur': 'Urdu',
                    'hi': 'Hindi', 'es': 'Spanish', 'fr': 'French', 'pt': 'Portuguese', 'ko': 'Korean', 'tr': 'Turkish', 'vi': 'Vietnamese', 'ru': 'Russian', 'it': 'Italian'
                };
                localStorage.setItem('selectedLanguage', savedLang);
                localStorage.setItem('selectedLanguageName', langNames[savedLang] || 'English');
            }
        }

        const savedLangName = localStorage.getItem('selectedLanguageName');

        if (savedLang && savedLang !== 'en') {
            // Update dropdown text immediately
            const currentLangEl = document.getElementById('current-lang');
            if (currentLangEl && savedLangName) currentLangEl.innerText = savedLangName;

            const currentLangMobileEl = document.getElementById('current-lang-mobile');
            if (currentLangMobileEl && savedLangName) currentLangMobileEl.innerText = savedLangName;

            // Wait for Google Translate to load, then apply translation
            let attempts = 0;
            const applyTranslation = setInterval(() => {
                const select = document.querySelector('.goog-te-combo');
                if (select) {
                    if (select.value !== savedLang) {
                        select.value = savedLang;
                        select.dispatchEvent(new Event('change'));
                    }
                    clearInterval(applyTranslation);
                }
                attempts++;
                if (attempts > 20) clearInterval(applyTranslation); // Stop after 4 seconds
            }, 200);
        } else if (savedLang === 'en') {
            // Update dropdown to show English
            const currentLangEl = document.getElementById('current-lang');
            if (currentLangEl) currentLangEl.innerText = 'English';

            const currentLangMobileEl = document.getElementById('current-lang-mobile');
            if (currentLangMobileEl) currentLangMobileEl.innerText = 'English';
        }
    });

    window.addEventListener('click', function (e) {
        if (!e.target.closest('.lang-dropdown') && !e.target.closest('.lang-dropdown-mobile')) {
            const m = document.getElementById('lang-menu');
            if (m) m.style.display = 'none';
            const mm = document.getElementById('lang-menu-mobile');
            if (mm) mm.style.display = 'none';
        }

        // Close nav dropdown if clicking outside
        const navDropdownWrap = document.querySelector('.nav-dropdown-wrap');
        if (navDropdownWrap && !e.target.closest('.nav-dropdown-wrap')) {
            navDropdownWrap.classList.remove('open');
        }
    });

    // Toggle dropdown on click for Supported Platforms
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.querySelector('.dropdown-trigger');
        if (trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const wrap = this.closest('.nav-dropdown-wrap');
                if (wrap) {
                    wrap.classList.toggle('open');
                }
            });
        }
    });

    // Auto-Hide Google Translate Banner
    setInterval(function () {
        const banner = document.querySelector('.goog-te-banner-frame');
        if (banner) banner.remove();
        document.body.style.top = '0px';
    }, 500);
</script>
