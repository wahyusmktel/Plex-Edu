<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Literasia</title>
    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Import Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ba80e8 0%, #d90d8b 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 48px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .login-header h5 {
            font-weight: 700;
            color: #333;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-field input:focus + label {
            color: #d90d8b !important;
        }

        .input-field input:focus {
            border-bottom: 1px solid #d90d8b !important;
            box-shadow: 0 1px 0 0 #d90d8b !important;
        }

        .btn-login {
            width: 100%;
            height: 45px;
            border-radius: 25px;
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .error-message {
            color: #ff2d55;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <i class="material-icons">import_contacts</i>
            <h5>Literasia</h5>
            <p class="grey-text">Sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="input-field">
                <i class="material-icons prefix">email</i>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                <label for="email">Email Address</label>
            </div>

            <div class="input-field">
                <i class="material-icons prefix">lock</i>
                <input id="password" name="password" type="password" required>
                <label for="password">Password</label>
            </div>

            <p>
                <label>
                    <input type="checkbox" name="remember" />
                    <span>Remember Me</span>
                </label>
            </p>

            <button type="submit" class="btn-login waves-effect waves-light">
                Login
            </button>
        </form>
    </div>

    <!-- Compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
