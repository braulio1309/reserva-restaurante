<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SaborAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #e65100;
            --secondary-color: #263238;
            --bg-light: #f5f7fa;
            --white: #ffffff;
            --border-color: #e0e0e0;
            --error-color: #d32f2f;
            --success-color: #388e3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--secondary-color);
            line-height: 1.6;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: var(--white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 50px;
            color: var(--primary-color);
            display: block;
            margin-bottom: 10px;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .logo-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
            color: #546e7a;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            color: var(--secondary-color);
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(230, 81, 0, 0.1);
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-checkbox label {
            font-size: 14px;
            color: #64748b;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            background-color: #bf360c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 81, 0, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .error-message {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #fee;
            color: var(--error-color);
            border: 1px solid #fcc;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border: 1px solid #c8e6c9;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background-color: var(--border-color);
        }

        .divider span {
            background-color: var(--white);
            padding: 0 15px;
            position: relative;
            color: #94a3b8;
            font-size: 13px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #bf360c;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
            
            .logo-text {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo-container">
            <span class="logo-icon">🍽️</span>
            <div class="logo-text">SaborAdmin</div>
            <div class="logo-subtitle">Panel de Administración</div>
        </div>

        <h2>Iniciar Sesión</h2>

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    class="form-input" 
                    placeholder="admin@example.com" 
                    value="{{ old('email') }}"
                    required 
                    autofocus
                >
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    class="form-input" 
                    placeholder="••••••••" 
                    required
                >
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-checkbox">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn-submit">Ingresar</button>
        </form>

        <div class="divider">
            <span>o</span>
        </div>

        <div class="back-link">
            <a href="{{ route('home') }}">← Volver al sitio público</a>
        </div>
    </div>

</body>
</html>
