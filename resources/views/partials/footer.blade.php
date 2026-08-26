<style>
    .site-footer {
        background: #090c11;
        border-top: 1px solid rgba(255,255,255,0.05);
        padding: 4rem 0 0;
    }

    .footer-ad-slot {
        width: 100%;
        min-height: 250px;
        padding: 1.5rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #0c1016;
    }

    .footer-ad-slot-inner {
        width: 300px;
        height: 250px;
        max-width: 100%;
    }

    .footer-leaderboard-slot {
        width: 100%;
        min-height: 90px;
        padding: 1.25rem 1rem;
        display: flex;
        justify-content: center;
        overflow: hidden;
        background: #0c1016;
    }

    .footer-leaderboard-inner {
        width: 728px;
        height: 90px;
    }

    @media (max-width: 767px) {
        .footer-leaderboard-slot { display: none; }
    }

    .footer-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1.4fr 1fr;
        gap: 2.5rem;
        padding-bottom: 3rem;
        align-items: start;
    }

    .footer-logo-link {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
        margin-bottom: 1.2rem;
    }

    .footer-logo-link img {
        height: 60px;
        width: auto;
        margin-top: 0;
    }

    .footer-logo-text {
        color: #fff;
        font-weight: 800;
        font-size: 22px;
        letter-spacing: -0.5px;
    }
    .footer-logo-text span {
        color: #8b5cf6;
    }

    .footer-desc {
        font-size: 0.95rem !important;
        color: #a0aaba;
        line-height: 1.7;
        max-width: 280px;
    }

    .footer-col-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1.5rem;
        margin-top: 0;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .footer-links a {
        font-size: 0.95rem;
        color: #a0aaba;
        text-decoration: none;
        transition: color 0.2s, padding-left 0.2s;
    }

    .footer-links a:hover {
        color: #8b5cf6;
        padding-left: 5px;
    }

    .footer-copyright {
        border-top: 1px solid rgba(255,255,255,0.05);
        padding: 1.5rem 0;
        text-align: center;
    }

    .footer-copyright p {
        font-size: 0.85rem;
        color: #a0aaba;
        margin: 0; line-height: 1.45; }

    /* ── Tablet: 2 columns ── */
    @media (max-width: 900px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        .footer-desc {
            max-width: 100%;
        }
    }

    /* ── Mobile: single column ── */
    @media (max-width: 600px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .site-footer {
            padding: 16px 0 0;
        }

        .footer-logo-link img {
            height: 60px;
            margin-top: 0;
        }

        .footer-desc {
            max-width: 100%;
        }

        .footer-col-title {
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
        }

        .footer-links {
            gap: 0.5rem;
        }

        .footer-links a {
            font-size: 0.85rem;
        }

        .footer-copyright p {
            font-size: 0.78rem; line-height: 1.45; }
    }
</style>



<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">

            @php
                try {
                    $footerSettings = \App\Models\FooterSetting::first();
                } catch (\Throwable $exception) {
                    $footerSettings = null;
                }
                $footerDescription = optional($footerSettings)->description ?? 'Analyze supported public media links and review source-dependent formats. No login is required for the basic browser workflow, and available quality depends on the source.';
                $selectedPlatformIds = optional($footerSettings)->platforms ?? [];
            @endphp

            <!-- Brand -->
            <div>
                <a href="/" class="footer-logo-link">
                    <img src="/images/logo-hafiz.svg" alt="Solution Hub" width="190" height="60">
                </a>
                <p class="footer-desc" style="line-height: 1.6;">
                    {{ $footerDescription }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="footer-col-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('platforms') }}">Supported Platforms</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                    <li><a href="{{ route('public.faqs') }}">FAQs</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <!-- Supported Platforms -->
            <div>
                <h4 class="footer-col-title">Supported Platforms</h4>
                <ul class="footer-links">
                    @php
                        try {
                            $footerPlatforms = \App\Models\Platform::where('status', 'active')
                                ->where('show_in_footer', 1)
                                ->orderBy('name')
                                ->get();
                        } catch (\Throwable $exception) {
                            $footerPlatforms = collect();
                        }
                    @endphp

                    @forelse($footerPlatforms as $platform)
                        <li>
                            @php
                                $platformSlug = is_array($platform) ? $platform['slug'] : $platform->slug;
                                $platformName = is_array($platform) ? $platform['name'] : $platform->name;
                            @endphp
                            <a href="{{ route('platforms.show', $platformSlug) }}">{{ $platformName }}</a>
                        </li>
                    @empty
                        <li><span style="font-size: 0.95rem; color: #a0aaba; opacity: 0.6;">No platforms available</span></li>
                    @endforelse
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="footer-col-title">Legal</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    <li><a href="{{ route('dmca') }}">DMCA</a></li>
                    <li><a href="{{ route('disclaimer') }}">Disclaimer</a></li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-copyright">
        <div class="footer-inner">
            <p style="line-height: 1.45;">© {{ date('Y') }} Solution Hub. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
    #scrollToTopBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #040813;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(139, 92, 246,0.3);
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #scrollToTopBtn.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    #scrollToTopBtn:hover {
        box-shadow: 0 6px 20px rgba(139, 92, 246,0.5);
        transform: translateY(-2px);
    }

    #scrollToTopBtn svg {
        width: 24px;
        height: 24px;
    }

    @media (max-width: 600px) {
        #scrollToTopBtn {
            bottom: 20px;
            right: 20px;
            width: 42px;
            height: 42px;
        }
        #scrollToTopBtn svg {
            width: 20px;
            height: 20px;
        }
    }
</style>

<button id="scrollToTopBtn" aria-label="Scroll to top" title="Scroll to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
</button>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var scrollToTopBtn = document.getElementById("scrollToTopBtn");
        window.addEventListener("scroll", function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add("show");
            } else {
                scrollToTopBtn.classList.remove("show");
            }
        });
        scrollToTopBtn.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    });
</script>
