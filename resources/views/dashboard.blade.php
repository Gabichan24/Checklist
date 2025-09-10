<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Checklisto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-primary mb-4">Bienvenido, {{ Auth::user()->nombre }}</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                Cerrar Sesión
            </button>
        </form>
    </div>
</body>
</html>