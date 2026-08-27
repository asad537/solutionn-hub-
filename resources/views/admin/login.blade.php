<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/webp" href="/images/solutionhub-favicon.webp?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Solution Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0F1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated BG */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            pointer-events: none;
        }
        .bg-orb-1 { width: 500px; height: 500px; background: #3668de; top: -150px; right: -150px; }
        .bg-orb-2 { width: 400px; height: 400px; background: #6C63FF; bottom: -100px; left: -100px; }
        .bg-orb-3 { width: 250px; height: 250px; background: #3668de; top: 50%; left: 30%; }

        .login-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo .icon-wrap {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3668de, #3668de);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 10px 30px rgba(139, 92, 246,0.4); line-height: 1.45; }

        .login-logo h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
        }

        .login-logo p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            margin-top: 0.3rem; line-height: 1.45; }

        .form-group {
            margin-bottom: 1.2rem; line-height: 1.45; }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrap {
            position: relative; line-height: 1.45; }

        .input-wrap i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            font-size: 0.95rem;
            color: #fff;
            font-family: 'Inter', sans-serif;
            transition: border 0.3s, background 0.3s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #3668de;
            background: rgba(139, 92, 246,0.08);
        }

        .error-msg {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #FCA5A5;
            font-size: 0.83rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #3668de, #3668de);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 25px rgba(139, 92, 246,0.35);
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(139, 92, 246,0.5);
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: rgba(255,255,255,0.4);
            font-size: 0.83rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover { color: #3668de; }
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    <div class="login-card">
        <div class="login-logo">
            <div class="icon-wrap"><i class="fas fa-shield-alt"></i></div>
            <h1>Admin Panel</h1>
            <p style="line-height: 1.45;">Solution Hub — Secure Access</p>
        </div>

        @if($errors->any())
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter admin password" autofocus>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left"></i> Back to Website</a>
        </div>
    </div>
</body>
</html>


