<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobBox - @lang('Recover Account')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body,
        html {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background: #111827;
        }

        .login-main {
            display: flex;
            width: 100%;
            height: 100%;
        }

        .login-left {
            width: 30%;
            min-width: 400px;
            height: 100%;
            background: #111827;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 5%;
            color: #fff;
        }

        .login-right {
            width: 70%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }

        .login-logo {
            max-width: 100%;
            max-height: 50px;
        }

        .sign-in-subtext {
            text-align: center;
            font-size: 14px;
            color: #f8fafc;
            margin-bottom: 40px;
            font-weight: 600;
        }

        form {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        .form-group label span {
            color: #ef4444;
        }

        .form-control {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 6px;
            color: #fff;
            padding: 12px 14px;
            width: 100%;
            font-size: 13px;
            outline: none;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            border-color: #3b82f6;
        }

        .btn-submit {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .back-to-login {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #94a3b8;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-to-login:hover {
            color: #fff;
        }

        .back-to-login i {
            margin-right: 5px;
        }

        @media (max-width: 1024px) {
            .login-left {
                width: 100%;
            }

            .login-right {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-main">
        <div class="login-left">
            <div class="logo-container">
                <img src="{{ siteLogo() }}" alt="@lang('logo')" class="login-logo">
            </div>
            <div class="sign-in-subtext">@lang('Recover Account')</div>

            <form action="{{ route('admin.password.reset') }}" method="POST" class="verify-gcaptcha">
                @csrf
                <div class="form-group">
                    <label>@lang('Email')</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                </div>

                <x-captcha />

                <button type="submit" class="btn-submit">
                    @lang('Recover Account')
                </button>

                <a href="{{ route('admin.login') }}" class="back-to-login">
                    <i class="las la-sign-in-alt"></i>@lang('Back to Login')
                </a>
            </form>
        </div>
        <div class="login-right" style="background-image: url('{{ asset('assets/admin/images/login.png') }}');"></div>
    </div>
</body>

</html>