<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs('site_name') }} - Admin Login</title>
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
            /* Darker navy */
        }

        .login-main {
            display: flex;
            width: 100%;
            height: 100%;
        }

        .login-left {
            width: 35%;
            min-width: 450px;
            height: 100%;
            background: #111827;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 2%;
            color: #fff;
        }

        .login-right {
            width: 65%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
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
            max-width: 400px;
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

        .password-wrap {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            cursor: pointer;
            font-size: 16px;
        }

        .lost-pass {
            color: #3b82f6;
            font-size: 12px;
            text-decoration: none;
            float: right;
            margin-top: 2px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0 25px 0;
            cursor: pointer;
        }

        .remember-wrap input {
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .remember-wrap span {
            color: #f8fafc;
            font-size: 13px;
            font-weight: 500;
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
        }

        .btn-submit:hover {
            background: #1d4ed8;
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
            <div class="sign-in-subtext">Sign In Below</div>

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>@lang('Email/Username') <span>*</span></label>
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                        placeholder="admin" required>
                </div>

                <div class="form-group">
                    <label style="display: inline-block;">@lang('Password') <span>*</span></label>
                    <a href="{{ route('admin.password.reset') }}" class="lost-pass">@lang('Lost your password?')</a>

                    <div class="password-wrap">
                        <input type="password" class="form-control" name="password" placeholder="********" required>
                        <i class="las la-eye eye-icon"></i>
                    </div>
                </div>

                <label class="remember-wrap">
                    <input type="checkbox" name="remember" checked>
                    <span>@lang('Remember me?')</span>
                </label>

                <button type="submit" class="btn-submit">
                    <i class="las la-sign-in-alt"></i> @lang('Sign in')
                </button>
            </form>
        </div>
        <div class="login-right" style="background-image: url('{{ asset('assets/admin/images/login.png') }}');"></div>
    </div>
    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.eye-icon').on('click', function () {
                let passwordInput = $(this).siblings('input');
                if (passwordInput.attr('type') == 'password') {
                    passwordInput.attr('type', 'text');
                    $(this).removeClass('la-eye').addClass('la-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    $(this).removeClass('la-eye-slash').addClass('la-eye');
                }
            });
        });
    </script>
</body>

</html>