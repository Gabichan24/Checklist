<!DOCTYPE html>
<html lang="es" x-data="darkMode()" x-init="init()" :class="{ 'dark': isDark }" x-cloak>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        /* ================= GLOBAL ================= */
        body {
            background: #f9fafb;
            color: #1f2937;
            font-family: sans-serif;
            transition: background 0.3s, color 0.3s;
        }
        a { color: inherit; transition: color 0.3s; }

        /* ================= SIDEBAR ================= */
        #sidebar {
            width: 220px;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            background: #1f2937;
            color: white;
            display: flex;
            flex-direction: column;
            transition: background 0.3s, color 0.3s, width 0.3s;
            z-index: 50;
        }
        #sidebar.collapsed { width: 60px; }
        #sidebar .sidebar-title { font-size: 1.5em; padding: 20px; font-weight: bold; }
        #sidebar .sidebar-link {
            display: flex; align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: white;
            transition: background 0.3s, color 0.3s;
        }
        #sidebar .sidebar-link:hover { background: #374151; }
        #sidebar .icon { font-size: 1.2em; }
        #sidebar .link-text { margin-left: 10px; transition: opacity 0.3s; }
        #sidebar.collapsed .link-text { opacity: 0; pointer-events: none; }

        /* ================= CONTENT ================= */
        .content-area {
            margin-left: 220px;
            transition: margin-left 0.3s;
        }
        #sidebar.collapsed ~ .content-area { margin-left: 60px; }

        /* ================= HEADER ================= */
        .header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 20px;
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
            transition: background 0.3s, color 0.3s;
        }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .btn-menu { font-size: 1.5em; background: none; border: none; cursor: pointer; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar {
            width: 35px; height: 35px; border-radius: 50%;
            overflow: hidden; background: #9ca3af;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold;
        }
        .theme-btn { margin-left: 10px; cursor: pointer; background: none; border: none; font-size: 1.2em; }

        /* ================= MODAL ================= */
        .modal {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex; justify-content: center; align-items: center;
            z-index: 100;
            transition: opacity 0.3s;
        }
        .hidden { display: none; }
        .modal-content {
            background: white;
            color: #1f2937;
            padding: 25px;
            border-radius: 10px;
            width: 80%; max-width: 800px;
            transition: background 0.3s, color 0.3s;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; }
        .close-btn { background: none; border: none; font-size: 1.2em; cursor: pointer; }
        .modal-body {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .modal-section h3 { margin-bottom: 10px; font-size: 1.1em; }
        .modal-section ul { list-style: none; padding: 0; }
        .modal-section ul li a { text-decoration: none; color: #1f2937; transition: color 0.3s; }
        .modal-section ul li a.disabled { pointer-events: none; color: #9ca3af; }
        .modal-button {
            display: block; margin-top: 20px; text-align: center;
            background: #4f46e5; color: white; padding: 10px 15px;
            border-radius: 5px; text-decoration: none;
            transition: background 0.3s, color 0.3s;
        }

        /* ================= DARK MODE ================= */
        .dark body { background: #111827; color: #f9fafb; }
        .dark a { color: #f9fafb; }
        .dark #sidebar { background: #111827; color: #f9fafb; }
        .dark #sidebar .sidebar-link:hover { background: #374151; }
        .dark .header { background: #1f1b2e; color: #f9fafb; }
        .dark .modal-content { background: #1f2937; color: #f9fafb; }
        .dark .modal-button { background: #6366f1; color: #f9fafb; }
    </style>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-title">Checklist</div>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-link"><span class="icon">📅</span> <span class="link-text">Programar Tarea</span></a>
            <a href="{{ route('checklist.index') }}" class="sidebar-link"><span class="icon">📝</span> <span class="link-text">Nueva plantilla</span></a>
            <a href="#" class="sidebar-link"><span class="icon">🖥</span> <span class="link-text">Monitores</span></a>
            <a href="#" class="sidebar-link"><span class="icon">⚠️</span> <span class="link-text">Incidencias</span></a>
            <a href="#" class="sidebar-link"><span class="icon">📊</span> <span class="link-text">Reportes</span></a>
        </nav>
        <div class="sidebar-footer">
            <button onclick="toggleConfigModal()" class="sidebar-link"><span class="icon">⚙️</span> <span class="link-text">Configuraciones</span></button>
        </div>
    </aside>

    <!-- MODAL CONFIGURACIONES -->
    <div id="configModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Administra tu empresa</h2>
                <button onclick="toggleConfigModal()" class="close-btn">✕</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Staff</h3>
                    <ul>
                        <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                        <li><a href="{{ route('perfiles.index') }}">Perfiles</a></li>
                    </ul>
                </div>
                <div class="modal-section">
                    <h3>Estructura</h3>
                    <ul>
                        <li><a href="{{ route('regiones.index') }}">Regiones</a></li>
                        <li><a href="{{ route('zonas.index') }}">Zonas</a></li>
                        <li><a href="{{ route('areas.index') }}">Áreas</a></li>
                        <li><a href="{{ route('categorias.index') }}">Categorías</a></li>
                        <li><a href="{{ route('sucursales.index') }}">Sucursales</a></li>
                    </ul>
                </div>
                <div class="modal-section">
                    <h3>Logros</h3>
                    <ul>
                        <li><a class="disabled">Logros</a></li>
                    </ul>
                </div>
                <a href="{{ route('empresa.index') }}" class="modal-button">Configuración general</a>
            </div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="content-area">
        <header class="header">
            <div class="header-left">
                <button onclick="toggleSidebar()" class="btn-menu">☰</button>
                <h1 class="title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        @if(Auth::user() && Auth::user()->foto_perfil)
                            <img src="{{ asset('storage/' . Auth::user()->foto_perfil) }}" alt="Perfil">
                        @else
                            <div class="avatar-alt">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</div>
                        @endif
                    </div>
                    <span class="user-name">{{ Auth::user()->name ?? 'Invitado' }}</span>
                </div>
                <button x-show="isDark" @click="toggle()" class="theme-btn" x-cloak>☀️</button>
                <button x-show="!isDark" @click="toggle()" class="theme-btn" x-cloak>🌙</button>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- JS FUNCIONES -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const links = sidebar.querySelectorAll('.link-text');
            const collapsed = sidebar.classList.toggle('collapsed');
            links.forEach(link => link.classList.toggle('hidden', collapsed));
        }

        function toggleConfigModal() {
            document.getElementById('configModal').classList.toggle('hidden');
        }

        function darkMode() {
            return {
                isDark: false,
                init() {
                    this.isDark =
                        localStorage.theme === 'dark' ||
                        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    document.documentElement.classList.toggle('dark', this.isDark);
                },
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.theme = this.isDark ? 'dark' : 'light';
                    document.documentElement.classList.toggle('dark', this.isDark);
                }
            };
        }
    </script>

</body>
</html>
