<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — AmbatuGrow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
        }

        .login-card h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 4px;
        }

        .login-card p {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            background: #ffffff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            outline: none;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: #15803d;
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.12);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .login-btn {
            width: 100%;
            padding: 11px 0;
            background: #15803d;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
            margin-top: 4px;
        }

        .login-btn:hover {
            background: #166534;
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-message i {
            font-size: 14px;
            flex-shrink: 0;
        }

        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .logo-area img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            background: #15803d;
            border-radius: 50%;
            padding: 4px;
        }

        .logo-area span {
            font-size: 22px;
            font-weight: 800;
            color: #15803d;
            letter-spacing: 0.05em;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-area">
                <img src="{{ asset('images/logo.png') }}" alt="AMBATUGROW Logo">
                <span>AMBATUGROW</span>
            </div>

            <h1>Admin Login</h1>
            <p>Sign in to access the management system</p>

            <!-- Error Display -->
            @if ($errors->any() || session('error'))
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        @else
                            {{ session('error') }}
                        @endif
                    </span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@ambatugrow.com"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-lock mr-2"></i> Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>&copy; {{ date('Y') }} AmbatuGrow. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

