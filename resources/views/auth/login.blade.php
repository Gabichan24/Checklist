<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Checklisto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center min-h-screen">
    <div class="bg-white p-4 rounded-3xl shadow-xl w-auto max-w-xs mx-auto flex flex-col items-center">
        
       <!-- Imagen de la empresa -->
        
        <img src="{{ asset('images/GP.png') }}" alt="Logo Grupo Patronis" class="w-24 h-24 mb-4 object-contain">
        <!-- Título -->
        <h1 class="text-2xl font-bold text-primary text-center mb-6">Iniciar Sesión</h1>

        <!-- Mensajes de error -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm text-center">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" class="w-full flex flex-col items-center">
            @csrf

            <!-- Correo -->
            <div class="mb-5 text-center">
                <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required autofocus autocomplete="username"
                       class="w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-200 mx-auto block">
            </div>

            <!-- Contraseña -->
            <div class="mb-5 text-center">
                <label for="contraseña" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
       class="w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-200 mx-auto block">
            </div>

            <!-- Recordar sesión -->
            <div class="mb-6 flex items-center justify-center">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Recordarme</label>
            </div>

            <!-- Botones -->
            <div class="flex flex-col items-center gap-4">
                <a href="#" class="text-sm text-primary hover:underline text-center">¿Olvidaste tu contraseña?</a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 w-40" style="background-color: blue; color: white;">
    Iniciar Sesión
</button>
            </div>
        </form>
    </div>
</body>
</html>

