@extends('layouts.app')

@section('content')
<div class="w-full px-6">

    <!-- Título y botón -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-100">
            @yield('table-title')
        </h2>

        @yield('table-actions')
    </div>

    <!-- Contenedor de tabla -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow transition-colors duration-300">

        <!-- Barra superior -->
        <div class="flex justify-between items-center mb-4">
            <div>
                @yield('table-extra-left')
            </div>

            <div class="w-1/3">
                @yield('table-search')
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full bg-white dark:bg-gray-800 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs text-center">
                    @yield('table-head')
                </thead>

                <tbody class="text-gray-700 dark:text-gray-300">
                    @yield('table-body')
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between mt-4 text-sm text-gray-500 dark:text-gray-400">
            <div>
                @yield('table-pagination')
            </div>

            <div>
                Mostrando @yield('table-count', '0 registros')
            </div>
        </div>

    </div>
</div>
@endsection
