@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <!-- Título y botón -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-700">@yield('table-title')</h2>
            @yield('table-actions')
        </div>

        <!-- Tabla -->
        <div class="bg-indigo-50 border rounded-lg p-4 shadow-sm">
            <div class="flex justify-between mb-3">
                <div>
                    @yield('table-extra-left')
                </div>
                <div>
                    <input type="text" placeholder="Buscar"
                           class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200"/>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
                    <thead>
                        @yield('table-head')
                    </thead>
                    <tbody>
                        @yield('table-body')
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between mt-4 text-sm text-gray-500">
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