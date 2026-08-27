<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/webp" href="/images/solutionhub-favicon.webp?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supported Platforms — Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:#0F1117;color:#E2E8F0;display:flex;min-height:100vh;}
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

        .btn-add{background:linear-gradient(135deg,#3668de,#3668de);color:#fff;border:none;border-radius:12px;padding:0.6rem 1.2rem;font-size:0.88rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;box-shadow:0 4px 15px rgba(139, 92, 246,0.25);transition:transform 0.2s;}
        .btn-add:hover{transform:translateY(-2px);}
        .btn-preview{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:0.6rem 1rem;font-size:0.82rem;font-weight:600;font-family:'Inter',sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;transition:all 0.2s;}
        .btn-preview:hover{background:rgba(255,255,255,0.1);color:#fff;}

        .table-card{background:#161B27;border:1px solid rgba(255,255,255,0.07);border-radius:16px;overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th{text-align:left;font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.06em;padding:1rem 1.5rem;background:rgba(255,255,255,0.02);border-bottom:1px solid rgba(255,255,255,0.06);}
        td{padding:1rem 1.5rem;font-size:0.88rem;color:rgba(255,255,255,0.7);border-bottom:1px solid rgba(255,255,255,0.04);}
        tr:hover td{background:rgba(255,255,255,0.02);}
        
        .status-badge{padding:0.25rem 0.6rem;border-radius:6px;font-size:0.72rem;font-weight:700;text-transform:uppercase;}
        .status-active{background:rgba(124, 58, 237,0.12);color:#a78bfa;}
        .status-inactive{background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.5);}
        
        .actions{display:flex;align-items:center;gap:0.5rem;}
        .btn-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.85rem;text-decoration:none;transition:all 0.2s;border:none;cursor:pointer;}
        .btn-edit{background:rgba(139, 92, 246,0.12);color:#3668de;}
        .btn-edit:hover{background:rgba(139, 92, 246,0.2);}
        .btn-del{background:rgba(239,68,68,0.12);color:#FCA5A5;}
        .btn-del:hover{background:rgba(239,68,68,0.2);}

        .alert-success{background:rgba(124, 58, 237,0.12);border:1px solid rgba(124, 58, 237,0.25);color:#a78bfa;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.6rem;font-size:0.85rem;}
    </style>
</head>
<body>

@include('partials.admin_sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1><i class="fas fa-globe" style="color:#3668de;margin-right:0.5rem;"></i> Platforms Management</h1>
            <div class="breadcrumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / Platforms</div>
        </div>
        <div style="display:flex;gap:1rem;">
            <a href="/" target="_blank" class="btn-preview"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="{{ route('admin.platforms.create') }}" class="btn-add"><i class="fas fa-plus"></i> Add New Platform</a>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Platform Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th style="width:120px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($platforms as $platform)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:700;color:#fff;">{{ $platform->name }}</td>
                        <td style="font-family:monospace;font-size:0.8rem;color:rgba(255,255,255,0.4);">/{{ $platform->slug }}</td>
                        <td>
                            <span class="status-badge {{ $platform->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst($platform->status) }}
                            </span>
                        </td>
                        <td>{{ $platform->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="actions" style="justify-content:flex-end;">
                                <a href="{{ route('platforms.show', $platform->slug) }}" target="_blank" class="btn-icon" style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.5);" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.platforms.edit', $platform->id) }}" class="btn-icon btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.platforms.delete', $platform->id) }}" onsubmit="return confirm('Delete this platform?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-del" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:4rem;color:rgba(255,255,255,0.2);">
                            No platforms found. <a href="{{ route('admin.platforms.create') }}" style="color:#3668de;">Add your first platform!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

