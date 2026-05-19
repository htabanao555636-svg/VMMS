<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — VMMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f4f5f0;
        }

        /* LEFT PANEL */
        .left-panel {
            width: 420px;
            min-height: 100vh;
            background: linear-gradient(160deg, #1a5c42 0%, #2d9b6f 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            text-align: center;
            flex-shrink: 0;
        }
        .left-panel .logo-wrap {
            width: 90px; height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .left-panel .logo-wrap img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .left-panel h1 {
            font-size: 26px; font-weight: 800;
            color: white; margin-bottom: 8px;
        }
        .left-panel p {
            font-size: 13px; color: rgba(255,255,255,0.7);
            margin-bottom: 48px; line-height: 1.6;
        }
        .role-list {
            width: 100%;
            border-top: 1px solid rgba(255,255,255,0.15);
            padding-top: 32px;
        }
        .role-list .label {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
            color: rgba(255,255,255,0.5); margin-bottom: 14px;
        }
        .role-item {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px; padding: 11px 16px;
            margin-bottom: 10px;
        }
        .role-dot {
            width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
        }
        .role-item span { font-size: 13px; font-weight: 600; color: white; }

        /* RIGHT PANEL */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .form-card {
            width: 100%;
            max-width: 420px;
        }
        .form-card h2 {
            font-size: 28px; font-weight: 800;
            color: #1a2e1a; margin-bottom: 6px;
        }
        .form-card .subtitle {
            font-size: 13px; color: #9ca3af; margin-bottom: 36px;
        }
        .form-card .subtitle a {
            color: #2d9b6f; font-weight: 600; text-decoration: none;
        }

        /* Alerts */
        .alert-error {
            background: #fee2e2; border: 1px solid #fca5a5;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; font-size: 13px; color: #991b1b;
        }
        .alert-success {
            background: #dcfce7; border: 1px solid #86efac;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; font-size: 13px; color: #166534;
        }

        /* Form fields */
        .field-group { margin-bottom: 20px; }
        .field-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: #374151; margin-bottom: 8px;
        }
        .input-wrap {
            position: relative; display: flex; align-items: center;
        }
        .input-wrap i {
            position: absolute; left: 14px;
            color: #9ca3af; font-size: 14px;
        }
        .input-wrap input {
            width: 100%; padding: 13px 14px 13px 40px;
            border: 1.5px solid #e5e7eb; border-radius: 10px;
            font-size: 14px; color: #1a2e1a;
            background: #fafafa; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-wrap input:focus {
            border-color: #2d9b6f;
            box-shadow: 0 0 0 3px rgba(45,155,111,0.12);
            background: white;
        }
        .input-wrap .toggle-pw {
            position: absolute; right: 14px;
            background: none; border: none;
            color: #9ca3af; cursor: pointer; font-size: 14px;
        }

        /* Remember + forgot */
        .form-row {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 28px;
        }
        .remember {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #6b7280; cursor: pointer;
        }
        .remember input[type="checkbox"] {
            accent-color: #2d9b6f; width: 15px; height: 15px;
        }
        .forgot { font-size: 13px; color: #2d9b6f; font-weight: 600; text-decoration: none; }
        .forgot:hover { text-decoration: underline; }

        /* Submit button */
        .btn-primary {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #1a5c42, #2d9b6f);
            color: white; font-size: 15px; font-weight: 700;
            border: none; border-radius: 10px; cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-primary:hover { opacity: 0.92; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0; color: #d1d5db; font-size: 12px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #e5e7eb;
        }

        .register-link {
            text-align: center; font-size: 13px; color: #6b7280;
        }
        .register-link a {
            color: #2d9b6f; font-weight: 700; text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-panel {
                width: 100%; min-height: auto;
                padding: 40px 24px; border-radius: 0;
            }
            .role-list { display: none; }
            .right-panel { padding: 32px 20px; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
    <div class="logo-wrap">
        <img src="{{ asset('images/logo.png') }}" alt="VMMS Logo">
    </div>
    <h1>Easy Fix Garage</h1>
    <p>Vehicle Maintenance Made Simple.<br>Fast, reliable, and transparent.</p>

    <div class="role-list">
        <div class="label">Portal Access For</div>
        <div class="role-item">
            <div class="role-dot" style="background:#ef4444;"></div>
            <span>Administrator</span>
        </div>
        <div class="role-item">
            <div class="role-dot" style="background:#3b82f6;"></div>
            <span>Staff</span>
        </div>
        <div class="role-item">
            <div class="role-dot" style="background:#f59e0b;"></div>
            <span>Customer</span>
        </div>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
    <div class="form-card">
        <h2>Create Account</h2>
        <p class="subtitle">Already have an account? <a href="{{ route('login') }}">Sign in →</a></p>

        @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field-group">
                <label>Full Name</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                </div>
            </div>

            <div class="field-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>
            </div>

            <div class="field-group">
                <label>Phone Number</label>
                <div class="input-wrap">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" required>
                </div>
            </div>

            <div class="field-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="pw1" placeholder="Min. 8 characters" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw1', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="field-group" style="margin-bottom:24px;">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" id="pw2" placeholder="Confirm your password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw2', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:28px;font-size:13px;color:#6b7280;">
                <input type="checkbox" name="agree_terms" required
                       style="accent-color:#2d9b6f;margin-top:2px;width:15px;height:15px;flex-shrink:0;">
                <span>I agree to the
                    <a href="#" style="color:#2d9b6f;font-weight:600;">Terms & Conditions</a>
                </span>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-user-plus"></i> Create My Account
            </button>
        </form>
    </div>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
