<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Nexstock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:          #0e0e10;
            --surface:     #18181b;
            --surface-deep:#09090b;
            --border:      #27272a;
            --border-dim:  #1f1f23;
            --text:        #f4f4f5;
            --text-body:   #e4e4e7;
            --muted:       #71717a;
            --dim:         #3f3f46;
            --accent:      #6366f1;
            --accent-h:    #4f46e5;
            --accent-ring: rgba(99,102,241,0.12);
            --accent-bg:   rgba(99,102,241,0.12);
            --accent-br:   rgba(99,102,241,0.22);
            --danger:      #f87171;
            --danger-bg:   rgba(248,113,113,0.08);
            --danger-br:   rgba(248,113,113,0.2);
            --radius:      7px;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            background: var(--bg);
            color: var(--text-body);
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 340px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 36px 28px 28px;
        }

        /* Logo */
        .logo-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .logo-mark {
            width: 40px; height: 40px;
            background: var(--accent-bg);
            border: 1px solid var(--accent-br);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .logo-mark svg {
            width: 18px; height: 18px;
            stroke: #818cf8;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .logo-name {
            font-size: 17px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin-bottom: 24px;
        }

        /* Heading */
        .title   { font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 6px; text-align: center; letter-spacing: -0.3px; }
        .subtitle { font-size: 13px; color: var(--muted); margin-bottom: 22px; text-align: center; letter-spacing: 0.02em; }

        /* Alert */
        .alert {
            background: var(--danger-bg);
            border: 1px solid var(--danger-br);
            border-radius: var(--radius);
            padding: 10px 12px;
            font-size: 13px;
            color: var(--danger);
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 16px;
            line-height: 1.45;
        }
        .alert svg { flex-shrink: 0; margin-top: 1px; width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* Fields */
        .field { margin-bottom: 12px; }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 5px;
        }

        .input-wrap { position: relative; }

        .field input[type="email"],
        .field input[type="password"],
        .field input[type="text"] {
            width: 100%;
            padding: 9px 12px;
            background: var(--surface-deep);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-body);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            -webkit-appearance: none;
        }

        .field input::placeholder { color: var(--dim); }

        .field input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-ring);
        }

        .field input[name="password"] { padding-right: 36px; }

        .pw-btn {
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 36px;
            background: none;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--dim);
            transition: color 0.15s;
            border-radius: 0 var(--radius) var(--radius) 0;
        }
        .pw-btn:hover { color: var(--muted); }
        .pw-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* Remember */
        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            margin: 14px 0 18px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }
        .remember input[type="checkbox"] {
            width: 13px; height: 13px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 10px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius);
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            letter-spacing: -0.1px;
            transition: background 0.15s;
        }
        .btn:hover  { background: var(--accent-h); }
        .btn:active { background: #3730a3; }

        /* Footer */
        .footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--border-dim);
            text-align: center;
            font-size: 11px;
            color: var(--dim);
            line-height: 1.7;
        }

        @media (max-width: 400px) {
            body { padding: 16px; }
            .card { padding: 28px 20px 24px; }
        }
    </style>
</head>
<body>

<div class="card">

    <div class="logo-block">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24">
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
            </svg>
        </div>
        <div class="logo-name">Nexstock</div>
    </div>

    <hr class="divider">

    <div class="title" style="text-align:center;">Login</div>

    @if ($errors->any())
        <div class="alert">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">{{ __('app.supplier.email') }}</label>
            <input type="email" id="email" name="email"
                   placeholder="nama@perusahaan.com"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="email">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input type="password" id="password" name="password"
                       placeholder="••••••••"
                       required autocomplete="current-password">
                <button type="button" class="pw-btn" onclick="togglePw()" aria-label="Tampilkan password">
                    <svg id="pwIcon" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <label class="remember">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Ingat saya
        </label>

        <button type="submit" class="btn">Masuk</button>
    </form>

    <div class="footer">
        © {{ date('Y') }} Nexstock<br>
        SIM Inventory · PT. Madya Puji Rahayu
    </div>

</div>

<script>
function togglePw() {
    const el = document.getElementById('password');
    const ic = document.getElementById('pwIcon');
    const show = el.type === 'password';
    el.type = show ? 'text' : 'password';
    ic.innerHTML = show
        ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
        : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}
</script>

</body>
</html>