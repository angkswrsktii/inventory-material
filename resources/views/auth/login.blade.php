<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Nexstock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:         #0b0d12;
            --panel:      #0f1117;
            --card:       #13161f;
            --card-2:     #181c28;
            --border:     rgba(255,255,255,0.06);
            --border-2:   rgba(255,255,255,0.10);
            --accent:     #4f8ef7;
            --accent-2:   #7c6bef;
            --accent-glow:rgba(79,142,247,0.18);
            --text:       #e8eaf0;
            --muted:      #636880;
            --dim:        #2e3248;
            --danger:     #f87171;
            --danger-bg:  rgba(248,113,113,0.07);
            --success:    #34d399;
            --radius:     16px;
            --radius-sm:  10px;
        }

        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            color: var(--text);
            overflow: hidden;
        }

        /* ════════════════════════════════
           LEFT PANEL — branding
        ════════════════════════════════ */
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
            background: var(--panel);
            border-right: 1px solid var(--border);
        }

        /* Animated mesh background */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(79,142,247,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(124,107,239,0.10) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Dot grid */
        .left-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .panel-brand {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 14px;
        }

        .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white;
            box-shadow: 0 8px 20px rgba(79,142,247,0.3);
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 20px;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .brand-sub {
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 300;
            margin-top: 1px;
        }

        .panel-center {
            position: relative;
            z-index: 1;
        }

        .panel-headline {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(28px, 3.5vw, 42px);
            line-height: 1.15;
            color: var(--text);
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .panel-headline span {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .panel-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 340px;
            font-weight: 300;
        }

        /* Feature chips */
        .feature-list {
            margin-top: 36px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: var(--muted);
        }

        .feature-dot {
            width: 28px; height: 28px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }
        .fd-blue  { background: rgba(79,142,247,0.12);  color: var(--accent); }
        .fd-purple{ background: rgba(124,107,239,0.12); color: var(--accent-2); }
        .fd-green { background: rgba(52,211,153,0.10);  color: var(--success); }

        .panel-footer {
            position: relative;
            z-index: 1;
            font-size: 11.5px;
            color: var(--muted);
        }

        /* ════════════════════════════════
           RIGHT PANEL — form
        ════════════════════════════════ */
        .right-panel {
            width: 460px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--card);
            position: relative;
        }

        /* Subtle top gradient line */
        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), var(--accent-2), transparent);
            opacity: 0.6;
        }

        .form-wrap {
            width: 100%;
            max-width: 360px;
        }

        /* ── Form header ── */
        .form-header {
            margin-bottom: 36px;
        }

        .form-title {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 13.5px;
            color: var(--muted);
            font-weight: 300;
        }

        /* ── Alert ── */
        .alert-error {
            background: var(--danger-bg);
            border: 1px solid rgba(248,113,113,0.18);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 13px;
            color: var(--danger);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .alert-error i { margin-top: 1px; flex-shrink: 0; }

        /* ── Form groups ── */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .label-text {
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        /* ── Input ── */
        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: var(--dim);
            font-size: 13px;
            pointer-events: none;
            transition: color 0.2s;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 13px 15px 13px 42px;
            background: var(--card-2);
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            -webkit-appearance: none;
        }

        .form-input::placeholder { color: var(--dim); }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            background: #1a1f30;
        }

        .input-wrap:focus-within .input-icon { color: var(--accent); }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer;
            color: var(--dim);
            font-size: 13px;
            padding: 4px;
            transition: color 0.2s;
            line-height: 1;
        }
        .pw-toggle:hover { color: var(--muted); }

        /* ── Divider row ── */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            margin-top: -4px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: var(--muted);
            user-select: none;
            transition: color 0.15s;
        }
        .remember-label:hover { color: var(--text); }

        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
            border-radius: 4px;
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            border: none;
            border-radius: var(--radius-sm);
            color: white;
            font-size: 14px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            letter-spacing: 0.2px;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 24px rgba(79,142,247,0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(79,142,247,0.38);
        }
        .btn-login:hover::after { opacity: 1; }
        .btn-login:active { transform: translateY(0); box-shadow: 0 4px 16px rgba(79,142,247,0.25); }

        /* ── Footer ── */
        .form-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        /* ════════════════════════════════
           RESPONSIVE
        ════════════════════════════════ */
        @media (max-width: 860px) {
            body { flex-direction: column; overflow: auto; }
            .left-panel { display: none; }
            .right-panel {
                width: 100%;
                min-height: 100vh;
                padding: 40px 24px;
            }
            .right-panel::before { display: none; }
        }

        @media (max-width: 400px) {
            .right-panel { padding: 32px 20px; }
            .form-title { font-size: 20px; }
        }
    </style>
</head>
<body>

    <!-- ── Left branding panel ── -->
    <div class="left-panel">
        <div class="panel-brand">
            <div class="brand-logo">
                <div class="brand-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div>
                    <div class="brand-name">Nexstock</div>
                    <div class="brand-sub">SIM Inventory</div>
                </div>
            </div>
        </div>

        <div class="panel-center">
            <div class="panel-headline">
                Kelola stok<br>dengan <span>lebih cerdas.</span>
            </div>
            <div class="panel-desc">
                Platform manajemen inventory terpadu — dari purchase request hingga good issue, semua dalam satu sistem.
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot fd-blue"><i class="fas fa-arrow-right-to-bracket"></i></div>
                    Good Receipt & Good Issue real-time
                </div>
                <div class="feature-item">
                    <div class="feature-dot fd-purple"><i class="fas fa-file-invoice-dollar"></i></div>
                    Purchase Request & Purchase Order
                </div>
                <div class="feature-item">
                    <div class="feature-dot fd-green"><i class="fas fa-chart-line"></i></div>
                    Laporan mutasi & stok otomatis
                </div>
            </div>
        </div>

        <div class="panel-footer">
            © {{ date('Y') }} Nexstock · PT. Madya Puji Rahayu
        </div>
    </div>

    <!-- ── Right form panel ── -->
    <div class="right-panel">
        <div class="form-wrap">

            <div class="form-header">
                <div class="form-title">Selamat datang 👋</div>
                <div class="form-subtitle">Masuk ke akun Nexstock Anda</div>
            </div>

            {{-- Error messages --}}
            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <div class="form-label">
                        <span class="label-text">Email</span>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-input"
                               placeholder="email@perusahaan.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <div class="form-label">
                        <span class="label-text">Password</span>
                    </div>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="passwordInput"
                               class="form-input" placeholder="Masukkan password" required
                               style="padding-right: 44px;">
                        <button type="button" class="pw-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="pwIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
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

            <div class="form-footer">
                © {{ date('Y') }} Nexstock · PT. Madya Puji Rahayu
            </div>

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
    </script>
</body>
</html>