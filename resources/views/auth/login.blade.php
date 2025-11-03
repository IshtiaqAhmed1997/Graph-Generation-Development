<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Pharma Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            max-width: 420px;
            width: 100%;
            transition: 0.3s;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.1);
        }

        .login-card h3 {
            font-weight: 600;
            color: #1565c0;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #1565c0;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #cfd8dc;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 0 0.2rem rgba(66, 165, 245, 0.25);
        }

        .btn-primary {
            background-color: #1565c0;
            border: none;
            border-radius: 8px;
            width: 100%;
            padding: 0.75rem;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.3);
        }

        .text-link {
            color: #1565c0;
            text-decoration: none;
            font-weight: 500;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .brand-logo {
            width: 70px;
            display: block;
            margin: 0 auto 1rem auto;
        }

        @media (max-width: 480px) {
            .login-card {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <!-- Optional company logo -->
        <!-- <img src="https://upload.wikimedia.org/wikipedia/commons/6/6e/Medical_Symbol_Blue_Cross.svg" alt="Pharma Logo"
            class="brand-logo"> -->
        <h3>Portal Login</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" name="email" class="form-control" placeholder="Enter your email" required
                    autofocus>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" class="form-control"
                    placeholder="Enter your password" required>
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            <!-- Forgot Password -->
             
            {{--<div class="d-flex justify-content-between mb-3">
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-link">Forgot password?</a>
                @endif
            </div> --}}

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Log In</button>

            <!-- Register -->
            <div class="text-center mt-3">
                <span>Don’t have an account?</span>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-link">Sign up</a>
                @endif
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>