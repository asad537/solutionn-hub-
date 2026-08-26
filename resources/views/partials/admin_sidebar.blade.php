<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-wrap">
            <div class="logo-icon"><i class="fas fa-download"></i></div>
            <div>
                <h2>Solution Hub</h2>
                <p class="logo-sub" style="line-height: 1.45;">Admin Dashboard</p>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ Request::is('admin') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="{{ route('admin.homepage') }}" class="nav-item {{ Request::is('admin/homepage') ? 'active' : '' }}"><i class="fas fa-home"></i> Home Page</a>
        <a href="{{ route('admin.faqs') }}" class="nav-item {{ Request::is('admin/faqs') ? 'active' : '' }}"><i class="fas fa-question-circle"></i> Home FAQs</a>
        <a href="{{ route('admin.faq_page') }}" class="nav-item {{ Request::is('admin/faq-page') ? 'active' : '' }}"><i class="fas fa-list-ul"></i> FAQ Page</a>
        <a href="{{ route('admin.blogs.index') }}" class="nav-item {{ Request::is('admin/blogs*') ? 'active' : '' }}"><i class="fas fa-blog"></i> Blogs</a>
        <a href="{{ route('admin.guides.index') }}" class="nav-item {{ Request::is('admin/guides*') ? 'active' : '' }}"><i class="fas fa-book"></i> Guides</a>
        <a href="{{ route('admin.platforms.index') }}" class="nav-item {{ Request::is('admin/platforms*') ? 'active' : '' }}"><i class="fas fa-globe"></i> Platforms</a>
        
        <div class="nav-label">System</div>
        <a href="{{ route('admin.footer_settings') }}" class="nav-item {{ Request::is('admin/footer-settings') ? 'active' : '' }}"><i class="fas fa-shoe-prints"></i> Footer Settings</a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-badge">
            <div class="admin-avatar">A</div>
            <div class="admin-info">
                <p style="line-height: 1.45;">Administrator</p>
                <span>Super Admin</span>
            </div>
            <a href="{{ route('admin.logout') }}" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</aside>

