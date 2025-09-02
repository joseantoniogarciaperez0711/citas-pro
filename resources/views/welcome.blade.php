{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600;700" rel="stylesheet" />

    <style>
        body{
            font-family:'Instrument Sans',sans-serif;background:linear-gradient(135deg,#FDFDFC 0%,#f8fafc 100%);
            min-height:100vh;display:flex;padding:1.5rem;align-items:center;justify-content:center;flex-direction:column
        }
        .auth-container{background:rgba(255,255,255,.9);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2);
            border-radius:16px;padding:2rem;box-shadow:0 8px 32px rgba(0,0,0,.1);max-width:400px;width:100%}
        .welcome-title{text-align:center;margin-bottom:2rem}
        .welcome-title h1{font-size:2.25rem;font-weight:700;color:#1b1b18;margin-bottom:.5rem}
        .welcome-title p{color:#706f6c;font-size:1rem}
        .auth-buttons{display:flex;flex-direction:column;gap:1rem}
        .btn{display:inline-block;padding:.875rem 1.5rem;border-radius:12px;text-decoration:none;font-size:.9rem;
            font-weight:600;text-align:center;transition:all .25s ease;position:relative;overflow:hidden}
        .btn-login{background:#1b1b18;color:#fff;border:1px solid #1b1b18}
        .btn-login:hover{background:#2d2d28;transform:translateY(-2px);box-shadow:0 8px 25px rgba(27,27,24,.3)}
        .btn-register{background:transparent;color:#1b1b18;border:1px solid #e3e3e0}
        .btn-register:hover{background:#1b1b18;color:#fff;border-color:#1b1b18;transform:translateY(-2px);
            box-shadow:0 8px 25px rgba(27,27,24,.2)}
        .btn-dashboard{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
        .btn-dashboard:hover{background:linear-gradient(135deg,#5a6fd8 0%,#6a4190 100%);transform:translateY(-2px);
            box-shadow:0 8px 25px rgba(102,126,234,.4)}
        .header-nav{width:100%;max-width:56rem;font-size:.875rem;margin-bottom:1.5rem;display:flex;align-items:center;
            justify-content:flex-end;gap:1rem}
        .header-nav .btn{padding:.6rem 1rem;border-radius:10px}
        @media (min-width:1024px){body{padding:2rem}.auth-container{padding:3rem}}
        @media (prefers-color-scheme:dark){
            body{background:linear-gradient(135deg,#161615 0%,#0f172a 100%);color:#ededec}
            .auth-container{background:rgba(30,30,28,.9);border-color:rgba(62,62,58,.3)}
            .welcome-title h1{color:#ededec}.welcome-title p{color:#a1a09a}
            .btn-register{color:#ededec;border-color:#3e3e3a}
            .btn-register:hover{background:#ededec;color:#1c1c1a;border-color:#ededec}
        }
    </style>
</head>
<body>
   

    <div class="auth-container">
        <div class="welcome-title">
            <h1>
                @auth
                    ¡Hola, {{ Str::of(auth()->user()->name)->limit(26) }}!
                @else
                    Bienvenido
                @endauth
            </h1>
            <p>
                @auth
                    Accede a tu panel y gestiona tu cuenta.
                @else
                    Accede a tu cuenta o crea una nueva.
                @endauth
            </p>
        </div>

        <div class="auth-buttons">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-dashboard">Ir al panel</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-login">Iniciar sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-register">Crear cuenta</a>
                @endif
            @endauth
        </div>
    </div>

    <div style="height:3.5rem"></div>
</body>
</html>
