{{-- resources/views/menu/clientes.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl sm:text-2xl text-black">
            {{ __('Clientes') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <html lang="es">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>Clientes</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>
                [x-cloak] {
                    display: none !important
                }

                .modal-backdrop {
                    backdrop-filter: blur(2px)
                }

                .card-hover {
                    transition: all .2s ease
                }

                .card-hover:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(0, 0, 0, .1)
                }

                .gradient-bg {
                    background: linear-gradient(135deg, #1f2937 0%, #374151 100%)
                }
            </style>
        </head>

        <body class="bg-gray-50 min-h-screen">
            <div x-data="clientsData()" x-cloak class="container mx-auto px-3 py-4 max-w-7xl">
                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl shadow-md p-5 sm:p-7 mb-6 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <!-- Título y subtítulo -->
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-semibold leading-tight mb-1">Información de clientes
                            </h1>
                            <p class="text-white/70 text-sm sm:text-base">Directorio y documentos</p>
                        </div>

                        <!-- Stats y acción -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6 gap-3 text-sm text-white/80">
                            <!-- Contadores -->
                            <div class="flex justify-center sm:justify-start gap-4 sm:gap-6">
                                <span>Total: <span class="font-semibold" x-text="clients.length"></span></span>
                                <span>Activos: <span class="font-semibold"
                                        x-text="clients.filter(c => c.activo).length"></span></span>
                                <span>Eliminados: <span class="font-semibold"
                                        x-text="clients.filter(c => !c.activo).length"></span></span>
                            </div>

                            <!-- Botón de acción -->
                            <div class="flex justify-center sm:justify-end">
                                <button @click="newClient()"
                                    class="inline-flex items-center gap-2 bg-white text-gray-800 hover:text-black px-4 py-2 rounded-xl font-medium shadow hover:shadow-md transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Nuevo Cliente</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="space-y-3">
                        <input x-model="search" type="text" placeholder="Buscar por nombre, correo o teléfono..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <select x-model="filterActive"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="activos">Mostrar: Activos</option>
                                <option value="eliminados">Mostrar: Eliminados</option>
                                <option value="todos">Mostrar: Todos</option>
                            </select>
                            <div></div>
                            <select x-model="perPage"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="6">6 por página</option>
                                <option value="12">12 por página</option>
                                <option value="18">18 por página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grid -->
                <!-- Grid de Clientes Profesional -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-6">
                    <template x-for="c in paginatedClients" :key="c.id">
                        <div
                            class="bg-white rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 hover:border-gray-300 
                    transition-all duration-300 p-5 sm:p-6 group">

                            <!-- Header de la Tarjeta -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <!-- Avatar/Inicial -->
                                        <div
                                            class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold text-sm"
                                                x-text="c.nombre ? c.nombre.charAt(0).toUpperCase() : '?'"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 truncate" x-text="c.nombre">
                                            </h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <template x-if="!c.activo">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                                        Inactivo
                                                    </span>
                                                </template>
                                                <template x-if="c.activo">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                        <span
                                                            class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                                        Activo
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contador de Documentos -->
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-900 text-white">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span x-text="(c.archivos_count ?? 0)"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Información de Contacto -->
                            <div class="space-y-2 mb-4">
                                <template x-if="c.telefono">
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="truncate" x-text="c.telefono"></span>
                                    </div>
                                </template>

                                <template x-if="c.correo">
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate" x-text="c.correo"></span>
                                    </div>
                                </template>

                                <template x-if="!c.telefono && !c.correo">
                                    <div class="text-sm text-gray-400 italic">Sin información de contacto</div>
                                </template>
                            </div>

                            <!-- Notas -->
                            <div class="mb-5">
                                <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed"
                                    x-text="c.notas || 'Sin notas registradas'"></p>
                            </div>

                            <!-- Acciones -->
                            <div class="space-y-3">

                                <!-- Fila 1: Acciones de Información -->
                                <div class="flex gap-2">
                                    <button @click="openFiles(c)"
                                        class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 px-3 py-2.5 bg-gray-100 hover:bg-gray-200 
                   text-gray-700 rounded-xl text-xs sm:text-sm font-medium transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-center">Archivos</span>
                                    </button>

                                    <button @click="openHistory(c)"
                                        class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 px-3 py-2.5 bg-blue-50 hover:bg-blue-100 
                   text-blue-700 rounded-xl text-xs sm:text-sm font-medium transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-center">Historial</span>
                                    </button>
                                </div>

                                <!-- Fila 2: Acciones de Comunicación -->
                                <div class="flex gap-2">
                                    <button @click="openWhatsApp(c)" :disabled="!hasValidPhone(c)"
                                        class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-medium 
                   transition-colors disabled:opacity-40 disabled:cursor-not-allowed
                   bg-green-50 text-green-700 hover:bg-green-100 enabled:hover:shadow-sm">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20.52 3.48A11.8 11.8 0 0012.06 0C5.5 0 .18 5.31.18 11.85c0 2.09.55 4.14 1.59 5.95L0 24l6.35-1.66a11.89 11.89 0 005.72 1.46h.01c6.56 0 11.88-5.31 11.88-11.85 0-3.17-1.24-6.15-3.44-8.47zM12.08 21.3h-.01a9.7 9.7 0 01-4.95-1.35l-.35-.2-3.77.99 1.01-3.67-.23-.38a9.67 9.67 0 01-1.49-5.14c0-5.35 4.36-9.7 9.73-9.7 2.6 0 5.04 1.01 6.88 2.84a9.64 9.64 0 012.84 6.86c0 5.35-4.36 9.7-9.66 9.7zm5.33-7.25c-.29-.14-1.7-.83-1.96-.92-.26-.09-.45-.14-.64.14-.18.27-.74.92-.9 1.11-.17.18-.33.21-.61.07-.29-.14-1.24-.45-2.36-1.44a8.86 8.86 0 01-1.64-2.02c-.17-.27-.02-.42.12-.56.12-.12.29-.33.43-.49.14-.17.18-.28.27-.47.09-.18.05-.35-.02-.49-.07-.14-.64-1.54-.88-2.11-.23-.55-.46-.47-.64-.48h-.55c-.18 0-.49.07-.74.35-.26.27-1 1-1 2.42 0 1.42 1.02 2.8 1.16 2.99.14.19 2 3.18 4.87 4.45.68.29 1.21.46 1.62.59.68.22 1.3.19 1.8.11.55-.08 1.7-.7 1.94-1.38.24-.68.24-1.27.17-1.38-.07-.11-.26-.18-.55-.32z" />
                                        </svg>
                                        <span class="text-center">WhatsApp</span>
                                    </button>

                                    <button @click="scheduleFor(c)" :disabled="!c.activo"
                                        class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 px-3 py-2.5 rounded-xl text-xs sm:text-sm font-medium 
                   transition-colors disabled:opacity-40 disabled:cursor-not-allowed
                   bg-emerald-600 text-white hover:bg-emerald-700 enabled:hover:shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                        </svg>
                                        <span class="text-center">Agendar</span>
                                    </button>
                                </div>

                                <!-- Fila 3: Acciones de Gestión -->
                                <template x-if="!c.activo">
                                    <button @click="restoreClient(c)"
                                        class="w-full flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 
                   text-white rounded-xl text-xs sm:text-sm font-medium transition-colors hover:shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span class="text-center">Reincorporar</span>
                                    </button>
                                </template>

                                <template x-if="c.activo">
                                    <div class="flex gap-2">
                                        <button @click="editClient(c)"
                                            class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 py-2.5 bg-gray-900 hover:bg-gray-800 
                       text-white rounded-xl text-xs sm:text-sm font-medium transition-colors hover:shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span class="text-center">Editar</span>
                                        </button>

                                        <button @click="deleteClient(c)"
                                            class="px-3 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs sm:text-sm font-medium 
                       transition-colors hover:shadow-sm flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="text-center sm:hidden">Eliminar</span>
                                        </button>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>
                </div>


                <!-- Modal Historial Profesional Mobile-First -->
                <div x-show="showHistory" x-cloak class="fixed inset-0 z-50 flex items-end">

                    <!-- Backdrop Profesional -->
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeHistory()"
                        x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    </div>

                    <!-- Panel Principal -->
                    <div class="relative w-full bg-white shadow-2xl max-h-[94vh] flex flex-col
                rounded-t-[2rem] sm:rounded-t-3xl sm:max-w-5xl sm:mx-auto sm:mb-6 sm:max-h-[88vh]
                overflow-hidden"
                        x-transition:enter="transition ease-out duration-400"
                        x-transition:enter-start="transform translate-y-full opacity-0"
                        x-transition:enter-end="transform translate-y-0 opacity-100"
                        x-transition:leave="transition ease-in duration-250"
                        x-transition:leave-start="transform translate-y-0 opacity-100"
                        x-transition:leave-end="transform translate-y-full opacity-0">

                        <!-- Handle Elegante -->
                        <div class="flex justify-center pt-4 pb-2 sm:hidden">
                            <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
                        </div>

                        <!-- Header Profesional -->
                        <div
                            class="sticky top-0 bg-white/95 backdrop-blur-sm border-b border-gray-200 px-5 py-5 sm:px-7 sm:py-6 z-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <!-- Icono Profesional -->
                                    <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                                            Historial de Citas
                                        </h2>
                                        <p class="text-sm font-medium text-gray-600">
                                            <span x-text="historyClient?.nombre || 'Cliente'"></span>
                                        </p>
                                    </div>
                                </div>

                                <button @click="closeHistory()"
                                    class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 active:scale-95 transition-all duration-200">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 overflow-y-auto px-5 pb-6 sm:px-7 sm:pb-8 scroll-smooth">

                            <!-- Loading State -->
                            <template x-if="historyLoading">
                                <div class="flex flex-col items-center justify-center py-16">
                                    <div
                                        class="w-8 h-8 border-2 border-gray-200 border-t-gray-900 rounded-full animate-spin mb-4">
                                    </div>
                                    <p class="text-gray-600 font-medium">Cargando historial...</p>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <template x-if="!historyLoading && history.length === 0">
                                <div class="text-center py-20">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sin historial registrado</h3>
                                    <p class="text-gray-500 max-w-sm mx-auto">
                                        Este cliente no tiene citas registradas. El historial aparecerá aquí una vez que
                                        se registren citas.
                                    </p>
                                </div>
                            </template>

                            <!-- Lista de Citas -->
                            <div class="space-y-4 mt-4" x-show="!historyLoading && history.length">
                                <template x-for="(a, index) in history" :key="a.id">
                                    <div
                                        class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-200 
                                transition-all duration-300 hover:border-gray-300">

                                        <!-- Header de Cita -->
                                        <button @click="toggleAppt(a.id)"
                                            class="w-full p-5 sm:p-6 hover:bg-gray-50 transition-colors duration-200 text-left">

                                            <div class="flex items-start gap-4">
                                                <!-- Indicador de Estado -->
                                                <div class="flex-shrink-0 mt-2">
                                                    <div class="w-3 h-3 rounded-full"
                                                        :class="a.estado === 'completada' ? 'bg-green-500' :
                                                            (a.estado === 'cancelada' ? 'bg-red-500' : 'bg-blue-500')">
                                                    </div>
                                                </div>

                                                <!-- Contenido Principal -->
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-semibold text-gray-900 text-lg mb-2 leading-tight"
                                                        x-text="a.fecha_larga"></h3>

                                                    <!-- Información de la cita -->
                                                    <div
                                                        class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span x-text="a.hora_rango"></span>
                                                        </span>
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                            <span x-text="a.duracion_label"></span>
                                                        </span>
                                                    </div>

                                                    <!-- Estado y Precio -->
                                                    <div class="flex items-center justify-between">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                            :class="a.estado === 'completada' ?
                                                                'bg-green-100 text-green-800' :
                                                                (a.estado === 'cancelada' ?
                                                                    'bg-red-100 text-red-800' :
                                                                    'bg-blue-100 text-blue-800')"
                                                            x-text="a.estado.charAt(0).toUpperCase() + a.estado.slice(1)">
                                                        </span>

                                                        <div class="text-right">
                                                            <div class="text-xl font-bold text-gray-900"
                                                                x-text="money(a.neto)"></div>
                                                            <div class="text-xs text-gray-500">Total</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Icono de Expandir -->
                                                <div class="flex-shrink-0 mt-2">
                                                    <svg :class="expanded[a.id] ? 'rotate-180' : ''"
                                                        class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </button>

                                        <!-- Detalle Expandible -->
                                        <div x-show="expanded[a.id]" x-collapse>
                                            <div class="px-5 py-5 sm:px-6 sm:py-6 bg-gray-50 border-t border-gray-200">

                                                <!-- Servicios -->
                                                <div class="mb-6">
                                                    <h4
                                                        class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">
                                                        Servicios Realizados
                                                    </h4>
                                                    <div class="space-y-3">
                                                        <template x-for="it in a.items" :key="it.id">
                                                            <div
                                                                class="bg-white rounded-xl p-4 border border-gray-200">
                                                                <div class="flex justify-between items-start gap-3">
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="font-medium text-gray-900 mb-1"
                                                                            x-text="it.cantidad + '× ' + it.nombre">
                                                                        </p>

                                                                        <div
                                                                            class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                                                                            <template x-if="it.empleado">
                                                                                <span class="flex items-center gap-1">
                                                                                    <svg class="w-3 h-3"
                                                                                        fill="currentColor"
                                                                                        viewBox="0 0 20 20">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                                                            clip-rule="evenodd" />
                                                                                    </svg>
                                                                                    <span x-text="it.empleado"></span>
                                                                                </span>
                                                                            </template>
                                                                            <span>•</span>
                                                                            <span
                                                                                x-text="it.duracion + ' minutos'"></span>
                                                                        </div>
                                                                    </div>
                                                                    <span class="text-sm font-semibold text-gray-900"
                                                                        x-text="money(it.total_linea)"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <!-- Resumen Financiero -->
                                                <div class="bg-white rounded-xl p-4 border border-gray-200 mb-4">
                                                    <h4
                                                        class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">
                                                        Resumen de Costos
                                                    </h4>
                                                    <div class="space-y-2 text-sm">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Subtotal</span>
                                                            <span class="font-medium text-gray-900"
                                                                x-text="money(a.bruto)"></span>
                                                        </div>
                                                        <template x-if="a.desc_lineas > 0">
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Descuentos (x
                                                                    servicios)</span>
                                                                <span class="text-red-600 font-medium"
                                                                    x-text="'-' + money(a.desc_lineas)"></span>
                                                            </div>
                                                        </template>
                                                        <template x-if="a.desc_orden > 0">
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Descuento general</span>
                                                                <span class="text-red-600 font-medium"
                                                                    x-text="'-' + money(a.desc_orden)"></span>
                                                            </div>
                                                        </template>
                                                        <div class="border-t border-gray-200 pt-2 mt-3">
                                                            <div class="flex justify-between items-center">
                                                                <span class="font-semibold text-gray-900">Total
                                                                    Final</span>
                                                                <span class="font-bold text-lg text-gray-900"
                                                                    x-text="money(a.neto)"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Notas -->
                                                <template x-if="a.notas">
                                                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                                        <h4
                                                            class="text-sm font-semibold text-gray-900 mb-2 uppercase tracking-wide">
                                                            Notas
                                                        </h4>
                                                        <p class="text-sm text-gray-700 leading-relaxed"
                                                            x-text="a.notas"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-gray-600 text-sm text-center">
                            Mostrando <span class="font-medium" x-text="((currentPage - 1) * perPage) + 1"></span> -
                            <span class="font-medium"
                                x-text="Math.min(currentPage * perPage, filteredClients.length)"></span>
                            de <span class="font-medium" x-text="filteredClients.length"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50' : 'hover:bg-gray-50'"
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm">← Ant</button>
                            <span class="px-3 py-1 text-sm font-medium"
                                x-text="currentPage + '/' + totalPages"></span>
                            <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50' : 'hover:bg-gray-50'"
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm">Sig →</button>
                        </div>
                    </div>
                </div>

                <!-- Modal Cliente -->
                <div x-show="showClientForm" x-transition
                    class="fixed inset-0 bg-black/50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-50">
                    <div @click.stop class="bg-white w-full sm:max-w-lg sm:rounded-lg max-h-screen overflow-y-auto">
                        <div class="bg-gray-800 text-white p-4">
                            <h2 class="text-xl font-semibold"
                                x-text="editingClient ? 'Editar cliente' : 'Nuevo cliente'"></h2>
                        </div>
                        <form @submit.prevent="saveClient()" class="p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                <input x-model="clientForm.nombre" type="text" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <input x-model="clientForm.telefono" type="text"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo</label>
                                    <input x-model="clientForm.correo" type="email"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                                <textarea x-model="clientForm.notas" rows="4"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500"></textarea>
                            </div>
                            <div class="flex items-center">
                                <input x-model="clientForm.activo" type="checkbox" id="cli_activo"
                                    class="h-4 w-4 text-gray-600 border-gray-300 rounded focus:ring-gray-500">
                                <label for="cli_activo" class="ml-3 text-sm font-medium text-gray-700">Cliente
                                    activo</label>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="closeClientForm()"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700">Cancelar</button>
                                <button type="submit"
                                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg font-medium">
                                    <span x-text="editingClient ? 'Actualizar' : 'Guardar'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Archivos -->
                <div x-show="showFiles" x-transition
                    class="fixed inset-0 bg-black/50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-[60]">
                    <div @click.stop class="bg-white w-full sm:max-w-2xl sm:rounded-lg max-h-screen overflow-y-auto">
                        <div class="bg-gray-800 text-white p-4 flex items-center justify-between">
                            <h2 class="text-xl font-semibold">Archivos de <span x-text="currentClient?.nombre"></span>
                            </h2>
                            <button @click="showFiles=false" class="hover:text-gray-300">✕</button>
                        </div>

                        <div class="p-4 space-y-4">
                            <form @submit.prevent="uploadFiles"
                                class="border border-dashed border-gray-300 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subir documentos
                                    (múltiples)</label>
                                <input id="fileInput" type="file" multiple class="w-full"
                                    @change="handleFileInput($event)">
                                <p class="text-xs text-gray-500 mt-1">Máx 20MB por archivo.</p>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" :disabled="uploading || filesToUpload.length === 0"
                                        class="px-4 py-2 bg-blue-600 disabled:opacity-40 text-white rounded-lg">
                                        <span x-show="!uploading">Subir</span>
                                        <span x-show="uploading">Subiendo…</span>
                                    </button>
                                </div>
                            </form>

                            <div>
                                <h3 class="font-semibold mb-2">Archivos</h3>
                                <template x-if="clientFiles.length === 0">
                                    <p class="text-sm text-gray-500">Sin archivos.</p>
                                </template>
                                <div class="divide-y border rounded">
                                    <template x-for="f in clientFiles" :key="f.id">
                                        <div class="p-3 flex items-center justify-between">
                                            <div>
                                                <div class="font-medium" x-text="f.titulo || f.nombre_original"></div>
                                                <div class="text-xs text-gray-500"
                                                    x-text="(f.mime || 'archivo') + ' · ' + humanSize(f.tamano)"></div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a :href="`/app/clientes/archivos/${f.id}/descargar`"
                                                    class="px-2 py-1 text-sm bg-gray-100 rounded hover:bg-gray-200">Descargar</a>
                                                <button @click="deleteFile(f)"
                                                    class="px-2 py-1 text-sm bg-red-50 text-red-600 rounded hover:bg-red-100">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    // ---- SweetAlert2 helpers (mismo estilo que empleados/servicios) ----
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true,
                    });
                    const swalConfirm = ({
                            title = '¿Estás seguro?',
                            text = '',
                            confirmText = 'Sí, continuar'
                        } = {}) =>
                        Swal.fire({
                            icon: 'warning',
                            title,
                            text,
                            showCancelButton: true,
                            confirmButtonText: confirmText,
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#10b981', // emerald-600
                            cancelButtonColor: '#6b7280', // gray-500
                        });
                    const swalError = (text = 'Ocurrió un error') =>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text,
                            confirmButtonColor: '#1f2937'
                        }); // gray-800

                    Alpine.data('clientsData', () => ({

                        // --- estado para historial ---
                        showHistory: false,
                        historyClient: null,
                        history: [],
                        historyLoading: false,
                        expanded: {},

                        // helpers
                        money(v) {
                            try {
                                return new Intl.NumberFormat('es-MX', {
                                    style: 'currency',
                                    currency: 'MXN',
                                    maximumFractionDigits: 0
                                }).format(v || 0);
                            } catch (_) {
                                return '$' + (v || 0);
                            }
                        },

                        toggleAppt(id) {
                            this.expanded[id] = !this.expanded[id];
                        },
                        closeHistory() {
                            this.showHistory = false;
                            this.history = [];
                            this.expanded = {};
                            this.historyClient = null;
                        },

                        async openHistory(c) {
                            this.showHistory = true;
                            this.historyClient = c;
                            this.historyLoading = true;
                            this.history = [];
                            this.expanded = {};
                            try {
                                const r = await fetch(`/app/clientes/${c.id}/citas`, {
                                    headers: {
                                        Accept: 'application/json'
                                    }
                                });
                                const d = await r.json();
                                if (!r.ok) throw new Error(d.error || 'No se pudo cargar el historial');

                                // Normaliza para el modal
                                this.history = (d.citas || []).map(x => ({
                                    id: x.id,
                                    fecha_larga: x.fecha_larga, // ej. "lun, 01 sep 2025"
                                    hora_rango: x.hora_rango, // "10:00 – 11:30"
                                    duracion_label: x.duracion_label, // "1h 30m"
                                    estado: x.estado || 'pendiente',
                                    notas: x.notas || '',
                                    bruto: x.totales.bruto,
                                    desc_lineas: x.totales.desc_lineas,
                                    subtotal: x.totales.subtotal,
                                    desc_orden: x.totales.desc_orden,
                                    neto: x.totales.neto,
                                    items: (x.items || []).map(it => ({
                                        id: it.id,
                                        nombre: it.nombre_servicio_snapshot,
                                        cantidad: it.cantidad,
                                        duracion: it.duracion_minutos_snapshot,
                                        empleado: it.empleado_nombre || null,
                                        total_linea: Math.max(0, (it
                                            .precio_servicio_snapshot * it
                                            .cantidad) - (it.descuento ||
                                            0)),
                                    })),
                                }));
                            } catch (e) {
                                console.error(e);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: e.message || 'No se pudo cargar el historial'
                                });
                                this.showHistory = false;
                            } finally {
                                this.historyLoading = false;
                            }
                        },


                        scheduleFor(c) {
                            // 1) Persistimos el cliente en localStorage para que la vista de Citas lo recupere
                            try {
                                const appt = JSON.parse(localStorage.getItem('CITAS_APPOINTMENT') || '{}');
                                localStorage.setItem('CITAS_APPOINTMENT', JSON.stringify({
                                    ...appt,
                                    cliente_id: c.id
                                }));
                            } catch (_) {}

                            // 2) Redirigimos a /app/citas con el cliente en querystring
                            const url = new URL('/app/citas', window.location.origin);
                            url.searchParams.set('cliente_id', c.id);
                            window.location.href = url.toString();
                        },


                        // UI
                        showClientForm: false,
                        showFiles: false,
                        uploading: false,

                        // filtros / paginación
                        search: '',
                        filterActive: 'activos',
                        perPage: 12,
                        currentPage: 1,

                        // edición
                        editingClient: null,
                        currentClient: null,

                        // data
                        clients: [],
                        clientFiles: [],
                        filesToUpload: [],

                        // form
                        clientForm: {
                            nombre: '',
                            telefono: '',
                            correo: '',
                            notas: '',
                            activo: true
                        },

                        // computed
                        get filteredClients() {
                            const q = (this.search || '').toLowerCase();
                            return this.clients.filter(c => {
                                if (this.filterActive === 'activos' && !c.activo) return false;
                                if (this.filterActive === 'eliminados' && c.activo) return false;
                                const mQ = !q ||
                                    (c.nombre || '').toLowerCase().includes(q) ||
                                    (c.telefono || '').toLowerCase().includes(q) ||
                                    (c.correo || '').toLowerCase().includes(q);
                                return mQ;
                            });
                        },
                        get totalPages() {
                            return Math.max(1, Math.ceil(this.filteredClients.length / this.perPage));
                        },
                        get paginatedClients() {
                            const start = (this.currentPage - 1) * this.perPage;
                            return this.filteredClients.slice(start, start + this.perPage);
                        },

                        humanSize(bytes) {
                            if (!bytes) return '—';
                            const units = ['B', 'KB', 'MB', 'GB'];
                            let i = 0;
                            while (bytes >= 1024 && i < units.length - 1) {
                                bytes /= 1024;
                                i++;
                            }
                            return bytes.toFixed(1) + ' ' + units[i];
                        },

                        // --- WhatsApp helpers ---
                        normalizePhone(raw) {
                            if (!raw) return null;
                            const digits = String(raw).replace(/\D/g, '');
                            if (digits.length >= 11 && digits.length <= 15) return digits; // ya con LADA
                            if (digits.length === 10) return '52' + digits; // asume MX
                            return null;
                        },
                        hasValidPhone(c) {
                            return !!this.normalizePhone(c?.telefono);
                        },
                        openWhatsApp(c) {
                            const to = this.normalizePhone(c?.telefono);
                            if (!to) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Teléfono inválido',
                                    text: 'Este cliente no tiene un número válido (incluye LADA).',
                                    confirmButtonColor: '#1f2937'
                                });
                                return;
                            }
                            const msg = encodeURIComponent(`Hola ${c?.nombre ?? ''}`);
                            window.open(`https://wa.me/${to}?text=${msg}`, '_blank');
                        },

                        // lifecycle
                        async init() {
                            this.$watch('search', () => this.currentPage = 1);
                            this.$watch('filterActive', () => this.currentPage = 1);
                            this.$watch('perPage', () => this.currentPage = 1);
                            await this.refreshClients();
                        },

                        // API
                        async refreshClients() {
                            try {
                                const r = await fetch('/app/clientes/lista', {
                                    headers: {
                                        Accept: 'application/json'
                                    }
                                });
                                if (!r.ok) throw new Error('No se pudo cargar la lista');
                                const d = await r.json();
                                this.clients = (d.clientes || []).map(c => ({
                                    ...c,
                                    activo: !!Number(c.activo)
                                }));
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'No se pudieron cargar los clientes');
                            }
                        },

                        // CRUD Cliente
                        newClient() {
                            this.editingClient = null;
                            this.clientForm = {
                                nombre: '',
                                telefono: '',
                                correo: '',
                                notas: '',
                                activo: true
                            };
                            this.showClientForm = true;
                        },
                        editClient(c) {
                            this.editingClient = c;
                            this.clientForm = {
                                nombre: c.nombre || '',
                                telefono: c.telefono || '',
                                correo: c.correo || '',
                                notas: c.notas || '',
                                activo: !!c.activo,
                            };
                            this.showClientForm = true;
                        },
                        closeClientForm() {
                            this.showClientForm = false;
                            this.editingClient = null;
                        },

                        async saveClient() {
                            try {
                                const isEdit = !!this.editingClient?.id;
                                const url = isEdit ? `/app/clientes/${this.editingClient.id}` :
                                    '/app/clientes';
                                const method = isEdit ? 'PUT' : 'POST';

                                const resp = await fetch(url, {
                                    method,
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify(this.clientForm)
                                });
                                const data = await resp.json();
                                if (!resp.ok) throw new Error(data.error || 'Error al guardar');

                                if (isEdit) {
                                    const i = this.clients.findIndex(x => x.id === this.editingClient.id);
                                    if (i !== -1) this.clients[i] = {
                                        ...this.clients[i],
                                        ...data.cliente,
                                        activo: !!Number(data.cliente.activo)
                                    };
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Cliente actualizado'
                                    });
                                } else {
                                    this.clients.push({
                                        ...data.cliente,
                                        activo: !!Number(data.cliente.activo)
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Cliente creado'
                                    });
                                }
                                this.closeClientForm();
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'No se pudo guardar');
                            }
                        },

                        async deleteClient(c) {
                            const ok = await swalConfirm({
                                title: `¿Desactivar a ${c.nombre}?`,
                                text: 'Podrás reincorporarlo después.',
                                confirmText: 'Sí, desactivar'
                            });
                            if (!ok.isConfirmed) return;

                            try {
                                const resp = await fetch(`/app/clientes/${c.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    }
                                });
                                const data = await resp.json().catch(() => ({}));
                                if (!resp.ok) throw new Error(data.error || 'Error al desactivar');

                                const i = this.clients.findIndex(x => x.id === c.id);
                                if (i !== -1) this.clients[i] = {
                                    ...this.clients[i],
                                    ...(data.cliente || {}),
                                    activo: false
                                };
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Cliente desactivado'
                                });
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'No se pudo desactivar');
                            }
                        },

                        async restoreClient(c) {
                            try {
                                const resp = await fetch(`/app/clientes/${c.id}/activar`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    }
                                });
                                const data = await resp.json();
                                if (!resp.ok) throw new Error(data.error || 'Error al reactivar');

                                const i = this.clients.findIndex(x => x.id === c.id);
                                if (i !== -1) this.clients[i] = {
                                    ...this.clients[i],
                                    ...data.cliente,
                                    activo: true
                                };
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Cliente reincorporado'
                                });
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'No se pudo reactivar');
                            }
                        },

                        // Archivos
                        async openFiles(c) {
                            this.currentClient = c;
                            await this.loadFiles();
                            this.filesToUpload = [];
                            this.showFiles = true;
                        },
                        async loadFiles() {
                            this.clientFiles = [];
                            if (!this.currentClient) return;
                            try {
                                const r = await fetch(`/app/clientes/${this.currentClient.id}/archivos`, {
                                    headers: {
                                        Accept: 'application/json'
                                    }
                                });
                                const d = await r.json();
                                if (!r.ok) throw new Error(d.error || 'No se pudo cargar');
                                this.clientFiles = d.archivos || [];
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'No se pudieron cargar los archivos');
                            }
                        },
                        handleFileInput(e) {
                            this.filesToUpload = Array.from(e.target.files || []);
                        },
                        async uploadFiles() {
                            if (!this.currentClient || this.filesToUpload.length === 0) return;
                            this.uploading = true;
                            try {
                                const fd = new FormData();
                                this.filesToUpload.forEach(f => fd.append('archivos[]', f));
                                const r = await fetch(`/app/clientes/${this.currentClient.id}/archivos`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: fd
                                });
                                const d = await r.json();
                                if (!r.ok) throw new Error(d.error || 'Error al subir');
                                await this.loadFiles();

                                // actualiza contador en tarjeta
                                const i = this.clients.findIndex(x => x.id === this.currentClient.id);
                                if (i !== -1) this.clients[i].archivos_count = (this.clients[i]
                                    .archivos_count || 0) + (d.archivos?.length || 0);

                                this.filesToUpload = [];
                                document.getElementById('fileInput').value = '';
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Archivos cargados'
                                });
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'Falló la subida');
                            } finally {
                                this.uploading = false;
                            }
                        },
                        async deleteFile(f) {
                            const ok = await swalConfirm({
                                title: '¿Eliminar archivo?',
                                text: f.nombre_original,
                                confirmText: 'Sí, eliminar'
                            });
                            if (!ok.isConfirmed) return;

                            try {
                                const r = await fetch(`/app/clientes/archivos/${f.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    }
                                });
                                const d = await r.json().catch(() => ({}));
                                if (!r.ok) throw new Error(d.error || 'No se pudo eliminar');

                                this.clientFiles = this.clientFiles.filter(x => x.id !== f.id);

                                // bajar contador
                                const i = this.clients.findIndex(x => x.id === this.currentClient.id);
                                if (i !== -1) this.clients[i].archivos_count = Math.max(0, (this.clients[i]
                                    .archivos_count || 0) - 1);

                                Toast.fire({
                                    icon: 'success',
                                    title: 'Archivo eliminado'
                                });
                            } catch (e) {
                                console.error(e);
                                swalError(e.message || 'No se pudo eliminar');
                            }
                        },
                    }));
                });
            </script>
        </body>

        </html>
    </div>
</x-app-layout>
