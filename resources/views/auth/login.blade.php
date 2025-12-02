<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Checklis</title>
    <style>
        /* Fuente */
        body {
            font-family: sans-serif;
            background-color: #f3f4f6; /* gris claro */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Contenedor principal */
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 350px;
            width: 100%;
            text-align: center;
        }

        /* Logo */
        .login-container img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            margin-bottom: 1rem;
        }

        /* Título */
        .login-container h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1d4ed8; /* azul */
            margin-bottom: 1.5rem;
        }

        /* Formulario */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151; /* gris oscuro */
            margin-bottom: 0.25rem;
        }

        form input[type="email"],
        form input[type="password"] {
            width: 16rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
            transition: 0.2s;
            margin-bottom: 1.25rem;
        }

        form input[type="email"]:focus,
        form input[type="password"]:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            border-radius: 0.25rem;
            margin-right: 0.5rem;
            border: 1px solid #d1d5db;
        }

        .checkbox-group label {
            font-size: 0.875rem;
            color: #4b5563; /* gris */
            margin: 0;
        }

        /* Botones y enlaces */
        .login-container a {
            font-size: 0.875rem;
            color: #1d4ed8;
            text-decoration: none;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .login-container button {
            background-color: #1d4ed8;
            color: white;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            width: 10rem;
            transition: 0.2s;
        }

        .login-container button:hover {
            background-color: #1e40af;
        }

        /* Mensajes de error */
        .errors {
            color: #dc2626;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo -->
        <img src="{{ asset('images/GP.png') }}" alt="Logo Grupo Patronis">

        <!-- Título -->
        <h1>Iniciar Sesión</h1>

        <!-- Mensajes de error -->
        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Correo -->
            <div>
                <label for="correo">Correo Electrónico</label>
                <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required autofocus autocomplete="username">
            </div>

            <!-- Contraseña -->
            <div>
                <label for="contraseña">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <!-- Recordar sesión -->
            <div class="checkbox-group">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Recordarme</label>
            </div>

            <!-- Enlace y botón -->
            <a href="#">¿Olvidaste tu contraseña?</a>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>

