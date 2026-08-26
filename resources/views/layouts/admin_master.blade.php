<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/images/logofinal.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Solution Hub</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:#0F1117;color:#E2E8F0;display:flex;min-height:100vh;}
        
        /* Sidebar Styles */
        .sidebar{width:240px;background:#161B27;border-right:1px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;flex-shrink:0;position:fixed;height:100vh;z-index:100;}
        .sidebar-logo{padding:1.5rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.06);}
        .logo-wrap{display:flex;align-items:center;gap:0.7rem; line-height: 1.45; }
        .logo-icon{width:40px;height:40px;background:linear-gradient(135deg,#3668de,#3668de);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#fff;box-shadow:0 6px 20px rgba(139, 92, 246,0.3);}
        .logo-wrap h2{font-size:1rem;font-weight: 700;color:#fff;letter-spacing:-0.02em;}
        .logo-sub{font-size:0.7rem;color:rgba(255,255,255,0.3);margin-top:1px;}
        
        .sidebar-nav{padding:1.2rem 0.8rem;flex:1;overflow-y:auto;}
        .nav-label{font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.2);text-transform:uppercase;letter-spacing:0.12em;padding:0.5rem 0.8rem;margin-top:0.8rem;margin-bottom:0.4rem;}
        .nav-item{display:flex;align-items:center;gap:0.8rem;padding:0.7rem 0.9rem;border-radius:10px;color:rgba(255,255,255,0.45);font-size:0.85rem;font-weight:500;transition:all 0.2s;text-decoration:none;margin-bottom:4px;}
        .nav-item:hover{background:rgba(255,255,255,0.04);color:#fff;}
        .nav-item i{width:18px;text-align:center;font-size:0.95rem;transition:transform 0.2s;}
        .nav-item:hover i{transform:scale(1.1);}
        .nav-item.active{background:linear-gradient(135deg,rgba(139, 92, 246,0.15),rgba(139, 92, 246,0.08));color:#3668de;font-weight:600;box-shadow:inset 0 0 0 1px rgba(139, 92, 246,0.15);}
        .nav-item.active i{color:#3668de;}
        
        .sidebar-footer{padding:1rem 1.2rem;border-top:1px solid rgba(255,255,255,0.06);background:rgba(0,0,0,0.1);}
        .admin-badge{display:flex;align-items:center;gap:0.8rem;}
        .admin-avatar{width:36px;height:36px;background:linear-gradient(135deg,#3668de,#3668de);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;color:#fff;font-weight:800;}
        .admin-info p{font-size:0.82rem;font-weight:600;color:#fff; line-height: 1.45; }
        .admin-info span{font-size:0.7rem;color:rgba(255,255,255,0.3);}
        .logout-btn{margin-left:auto;width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.15);color:#FCA5A5;border-radius:8px;text-decoration:none;transition:all 0.2s;}
        .logout-btn:hover{background:rgba(239,68,68,0.2);transform:scale(1.05);}

        /* Main Content Styles */
        .main{margin-left:240px;flex:1;display:flex;flex-direction:column;min-width:0;}
        .topbar{background:#161B27;border-bottom:1px solid rgba(255,255,255,0.06);padding:1rem 2.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;backdrop-filter:blur(10px);}
        .topbar-left h1{font-size:1.3rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:0.6rem;}
        .breadcrumb{font-size:0.8rem;color:rgba(255,255,255,0.3);margin-top:2px;}
        .breadcrumb a{color:rgba(255,255,255,0.4);text-decoration:none;transition:color 0.2s;}
        .breadcrumb a:hover{color:#3668de;}
        
        .content{padding:2.5rem;flex:1;}
        
        /* Shared Components */
        .btn-preview{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.7rem 1.2rem;font-size:0.85rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:all 0.2s;}
        .btn-preview:hover{background:rgba(255,255,255,0.1);color:#fff;border-color:rgba(255,255,255,0.2);}
        
        .alert-success{background:rgba(124, 58, 237,0.12);border:1px solid rgba(124, 58, 237,0.2);color:#a78bfa;padding:1rem 1.5rem;border-radius:12px;margin-bottom:2rem;display:flex;align-items:center;gap:0.8rem;font-size:0.9rem;animation:slideIn 0.3s ease-out;}
        @keyframes slideIn{from{transform:translateY(-10px);opacity:0;}to{transform:translateY(0);opacity:1;}}

        /* Select Options Dark Mode Fix */
        select { background-color: #161B27 !important; color: #fff !important; color-scheme: dark; }
        select option { background-color: #161B27 !important; color: #fff !important; }

        /* Scrollbar */
        ::-webkit-scrollbar{width:8px;}
        ::-webkit-scrollbar-track{background:rgba(0,0,0,0.1);}
        ::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.05);border-radius:10px;}
        ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,0.1);}
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-wrap">
            <div class="logo-icon"><i class="fas fa-bolt"></i></div>
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
        <a href="{{ route('admin.download_page') }}" class="nav-item {{ Request::is('admin/download-page') ? 'active' : '' }}"><i class="fas fa-download"></i> Download Page</a>
        <a href="{{ route('admin.blogs.index') }}" class="nav-item {{ Request::is('admin/blogs*') ? 'active' : '' }}"><i class="fas fa-blog"></i> Blogs</a>
        <a href="{{ route('admin.guides.index') }}" class="nav-item {{ Request::is('admin/guides*') ? 'active' : '' }}"><i class="fas fa-book"></i> Guides</a>
        <a href="{{ route('admin.platforms.index') }}" class="nav-item {{ Request::is('admin/platforms*') ? 'active' : '' }}"><i class="fas fa-globe"></i> Platforms</a>
        
        <div class="nav-label">System</div>
        <a href="{{ route('admin.seo_settings') }}" class="nav-item {{ Request::is('admin/seo-settings') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Static Pages</a>
        <a href="{{ route('admin.footer_settings') }}" class="nav-item {{ Request::is('admin/footer-settings') ? 'active' : '' }}"><i class="fas fa-shoe-prints"></i> Footer Settings</a>
        <a href="#" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-badge">
            <div class="admin-avatar">A</div>
            <div class="admin-info">
                <p style="line-height: 1.45;">Administrator</p>
                <span>Super Admin</span>
            </div>
            <a href="{{ route('admin.logout') }}" class="logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>@yield('header_icon') @yield('header_title', 'Dashboard')</h1>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @yield('breadcrumb')
            </div>
        </div>
        <div class="topbar-right">
            @yield('topbar_actions')
            <a href="@yield('view_site_url', '/')" target="_blank" class="btn-preview"><i class="fas fa-external-link-alt"></i> View Site</a>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>



