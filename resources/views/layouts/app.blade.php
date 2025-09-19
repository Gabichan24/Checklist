<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: true }" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-white border-r shadow-lg z-40 flex flex-col">
        <!-- Logo -->
        <div class="p-6 border-b flex items-center gap-2">
            <img src="{{ asset('images/GP.png') }}" alt="Logo" class="w-10 h-10 rounded-lg">
        </div>

        <!-- Navegación -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">📋 Programar Tarea</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">📝 Nueva Plantilla</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">👁️ Monitores</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">⚠️ Incidencias</a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">📊 Reportes</a>
        </nav>

        <!-- Configuración abajo -->
        <div class="p-4 border-t">
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">⚙️ Configuraciones</a>
        </div>
    </aside>

    <!-- Contenedor principal (margen a la derecha de la sidebar) -->
    <div class="flex-1 flex flex-col ml-64">
        <!-- Navbar -->
        <header class="h-16 bg-white flex items-center justify-between px-6 shadow-sm relative w-full">
            <!-- Título a la izquierda -->
            <h1 class="text-lg font-semibold text-gray-700">@yield('page-title', '')</h1>
            
            <!-- Línea inferior sutil -->
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gray-300"></div>
            
            <!-- Menú derecho -->
            <div class="flex items-center gap-3">
                <button class="hover:bg-gray-100 p-2 rounded-lg text-base">🔔</button>
                <button class="hover:bg-gray-100 p-2 rounded-lg text-base">💬</button>
                <a href="#" class="text-gray-700 hover:text-blue-600 text-sm">Ajustes</a>
                <div class="flex items-center gap-2">
                    <span class="hidden md:block text-gray-700 text-sm">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
                    <img src="{{ asset('images/user.png') }}" alt="Perfil" class="w-8 h-8 rounded-full border">
                </div>
            </div>
        </header>

        <!-- Contenido dinámico -->
        <main class="flex-1 p-6 bg-gray-50 mt-16">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>