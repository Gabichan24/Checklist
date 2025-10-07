<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: true }" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<body class="h-full flex">

    <!-- Sidebar -->
    <aside class="w-64 fixed h-full bg-white border-r shadow-lg">
        <!-- Logo -->
        <div class="p-6 border-b flex items-center gap-2">
            <img src="{{ asset('images/GP.png') }}" alt="Logo" class="w-10 h-10 rounded-lg">
        </div>

        <!-- Navegación principal -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">📋 Programar Tarea</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">📝 Nueva Plantilla</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">👁️ Monitores</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">⚠️ Incidencias</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">📊 Reportes</a>
        </nav>

        <!-- Configuración abajo -->
        <div class="p-4 border-t">
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 w-full">⚙️ Configuraciones</a>
        </div>
    </aside>

    <!-- Contenedor principal -->
    <div class="flex-1 ml-64 flex flex-col h-screen overflow-hidden">
        
        <!-- Navbar -->
        <header class="h-16 bg-white flex items-center justify-between px-6 shadow-sm flex-shrink-0">
            <h1 class="text-lg font-semibold text-gray-700">@yield('page-title', '')</h1>
            <div class="flex items-center gap-3">
                <button class="hover:bg-gray-100 p-2 rounded-lg">🔔</button>
                <button class="hover:bg-gray-100 p-2 rounded-lg">💬</button>
                <a href="#" class="text-gray-700 hover:text-blue-600 text-sm">Ajustes</a>
                <div class="flex items-center gap-2">
                    <span class="hidden md:block text-gray-700 text-sm">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
                    <img src="{{ asset('images/user.png') }}" alt="Perfil" class="w-8 h-8 rounded-full border">
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-5xl mx-auto bg-white shadow rounded-lg p-6 mt-16">
    @yield('content')
</main>
    </div>
</body>
</html>
