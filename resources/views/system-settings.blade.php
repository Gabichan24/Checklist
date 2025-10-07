<!DOCTYPE html>
<html lang="es" class="light" x-data="{ darkMode: false }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema - Checklisto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans min-h-screen flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-xl font-bold text-primary dark:text-white mb-4">Configuración del Sistema</h1>
        <div class="mb-4">
            <label for="dark-mode-toggle" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Modo Oscuro</label>
            <input id="dark-mode-toggle" type="checkbox" x-model="darkMode" class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
        </div>
    </div>

    <script>
        <body x-cloak x-data="{darkMode: $persist(false)}" :class="{'dark': darkMode === true }" class="antialiased"> body>
        document.addEventListener('DOMContentLoaded', () => {
            const darkModeToggle = document.querySelector('#dark-mode-toggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('change', () => {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
                });
            }
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                document.querySelector('#dark-mode-toggle').checked = true;
            }
        });
    </script>
</body>
</html>