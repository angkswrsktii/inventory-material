<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Material App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:        #0d0f14;
            --card:      #151821;
            --card-2:    #1c2030;
            --border:    rgba(255,255,255,0.07);
            --accent:    #4f8ef7;
            --accent-2:  #7c6bef;
            --text:      #e8eaf0;
            --muted:     #7b8299;
            --dim:       #3a4060;
            --danger:    #f87171;
            --danger-bg: rgba(248,113,113,0.08);
            --radius:    14px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* ── Background grid ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(79,142,247,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,142,247,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        /* ── Glow blobs ── */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            opacity: 0.18;
        }
        .blob-1 {
            width: 500px; height: 500px;
            background: var(--accent);
            top: -160px; left: -160px;
        }
        .blob-2 {
            width: 400px; height: 400px;
            background: var(--accent-2);
            bottom: -120px; right: -120px;
        }

        /* ── Card ── */
        .card {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            position: relative;
            z-index: 1;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
        }

        /* Top accent bar */
        .card::before {
            content: '';
            display: block;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
        }

        /* ── Header ── */
        .card-head {
            padding: 36px 40px 28px;
            text-align: center;
            border-bottom: 1px solid var(--border);
            background: var(--card-2);
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-radius: 16px;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(79,142,247,0.35);
        }

        .logo-wrap i { font-size: 24px; color: white; }

        .app-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 22px;
            color: var(--text);
            letter-spacing: -0.5px;
        }

        .app-tagline {
            font-size: 12px;
            color: var(--muted);
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ── Body ── */
        .card-body {
            padding: 32px 40px 36px;
        }

        .welcome {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .welcome-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
            font-weight: 300;
        }

        /* ── Alert ── */
        .alert-error {
            background: var(--danger-bg);
            border: 1px solid rgba(248,113,113,0.2);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px;
            color: var(--danger);
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 22px;
        }

        /* ── Form ── */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dim);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: var(--card-2);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input::placeholder { color: var(--dim); }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,142,247,0.12);
        }

        .form-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon {
            color: var(--accent);
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--dim);
            font-size: 14px;
            padding: 2px;
            transition: color 0.2s;
        }
        .pw-toggle:hover { color: var(--muted); }

        /* ── Options row ── */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: var(--muted);
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        /* ── Button ── */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(79,142,247,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(79,142,247,0.4);
        }

        .btn-login:active { transform: translateY(0); }

        /* ── Footer ── */
        .card-foot {
            padding: 16px 40px;
            border-top: 1px solid var(--border);
            text-align: center;
            background: var(--card-2);
        }

        .card-foot p {
            font-size: 11.5px;
            color: var(--dim);
        }

        /* ── Credentials hint (dev only) ── */
        .hint-box {
            margin-top: 20px;
            background: rgba(79,142,247,0.06);
            border: 1px solid rgba(79,142,247,0.15);
            border-radius: 10px;
            padding: 14px 16px;
        }

        .hint-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--accent);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hint-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            font-size: 12px;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: color 0.15s;
        }
        .hint-row:last-child { border-bottom: none; padding-bottom: 0; }
        .hint-row:hover { color: var(--text); }

        .hint-row .role-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .badge-admin { background: rgba(79,142,247,0.15); color: var(--accent); }
        .badge-karyawan { background: rgba(124,107,239,0.15); color: var(--accent-2); }

        .hint-cred {
            font-family: 'Courier New', monospace;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="card">
        <!-- Header -->
        <div class="card-head">
            <div class="logo-wrap">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <div class="app-name">Material App</div>
            <div class="app-tagline">Inventory Management System</div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="welcome">Selamat Datang 👋</div>
            <div class="welcome-sub">Masuk untuk mengelola persediaan raw material</div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-circle-exclamation" style="margin-top:1px; flex-shrink:0;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    <i class="fas fa-circle-exclamation" style="margin-top:1px; flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrap">
                        <input type="email" name="email" class="form-input"
                               placeholder="email@perusahaan.com"
                               value="{{ old('email') }}" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="passwordInput"
                               class="form-input" placeholder="Masukkan password" required
                               style="padding-right: 42px;">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="pw-toggle" onclick="togglePassword()" id="pwToggleBtn">
                            <i class="fas fa-eye" id="pwIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Options -->
                <div class="options-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-arrow-right-to-bracket"></i>
                    Masuk
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="card-foot">
            <p>© {{ date('Y') }} Material App · PT. Madya Puji Rahayu</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function fillLogin(email, password) {
            document.querySelector('input[name="email"]').value    = email;
            document.getElementById('passwordInput').value         = password;
        }
    </script>
</body>
</html>