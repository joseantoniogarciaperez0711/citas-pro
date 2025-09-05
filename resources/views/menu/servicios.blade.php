{{-- resources/views/app/servicios.blade.php --}}
@php
    $items = $items ?? [];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Servicios / Inventario') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Servicios de Estética</title>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script src="https://cdn.tailwindcss.com"></script>
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            {{-- SweetAlert2 --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>
                [x-cloak]{display:none!important}
                .modal-backdrop{backdrop-filter:blur(8px)}
                .card-hover{transition:all .3s cubic-bezier(.4,0,.2,1)}
                .card-hover:hover{transform:translateY(-1px);box-shadow:0 10px 25px -5px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05)}
                .glass-effect{background:rgba(255,255,255,.95);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.2)}
                .gradient-primary{background:linear-gradient(135deg,#1e293b 0%,#334155 100%)}
                .gradient-accent{background:linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%)}
                .btn-primary{background:linear-gradient(135deg,#1e293b 0%,#334155 100%);transition:all .3s ease}
                .btn-primary:hover{background:linear-gradient(135deg,#334155 0%,#475569 100%);transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.15)}
                .btn-secondary{background:rgba(148,163,184,.1);border:1px solid rgba(148,163,184,.2);transition:all .3s ease}
                .btn-secondary:hover{background:rgba(148,163,184,.2);transform:translateY(-1px)}
                .input-modern{transition:all .3s ease;border:1px solid #e2e8f0;background:rgba(255,255,255,.8)}
                .input-modern:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1);background:#fff}
                .category-chip{display:inline-flex;align-items:center;padding:.25rem .75rem;border-radius:9999px;font-size:.75rem;font-weight:600;color:white;backdrop-filter:blur(4px)}
                @media (max-width:640px){.modal-content{border-radius:1rem 1rem 0 0;margin-top:auto}}
                .fade-in{animation:fadeIn .3s ease-out}
                @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
                .pulse-dot{animation:pulse 2s cubic-bezier(.4,0,.6,1) infinite}
                @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
            </style>
        </head>

        <body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
            <div
                x-data="appData(@json($servicios ?? []), @json($categorias ?? []), @json($empleados ?? []))"
                x-cloak
                class="container mx-auto px-4 py-6 max-w-7xl">

                <!-- Header -->
                <div class="gradient-primary rounded-2xl shadow-xl p-6 mb-8 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-transparent"></div>
                    <div class="relative">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl sm:text-4xl font-bold mb-2">Servicios</h1>
                                <p class="text-slate-300 text-sm sm:text-base">Gestión de servicios</p>
                            </div>
                            <div class="flex gap-6 text-sm sm:text-base">
                                <div class="text-center">
                                    <div class="text-2xl font-bold" x-text="services.length"></div>
                                    <div class="text-slate-300">Servicios</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold" x-text="categories.length"></div>
                                    <div class="text-slate-300">Categorías</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-6">
                            <button @click="newService()"
                                class="btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12M6 12h12"/>
                                </svg>
                                Nuevo Servicio
                            </button>
                            <button @click="openCategoriesModal()"
                                class="bg-white text-slate-800 px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all border border-slate-200 hover:border-slate-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Categorías
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="glass-effect rounded-2xl shadow-lg p-6 mb-8 fade-in">
                    <div class="space-y-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input x-model="search" type="text" placeholder="Buscar servicios..."
                                class="input-modern w-full pl-12 pr-4 py-4 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <select x-model.number="filterCategory"
                                class="input-modern px-4 py-4 rounded-xl text-gray-900 focus:outline-none appearance-none bg-white">
                                <option value="">Todas las categorías</option>
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.nombre"></option>
                                </template>
                            </select>

                            <select x-model="perPage"
                                class="input-modern px-4 py-4 rounded-xl text-gray-900 focus:outline-none appearance-none bg-white">
                                <option value="6">6 por página</option>
                                <option value="12">12 por página</option>
                                <option value="18">18 por página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grid de Servicios -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <template x-for="service in paginatedServices" :key="service.id">
                        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-white/20 fade-in">
                            <div class="flex justify-between items-start mb-4">
                                <div class="category-chip"
                                     :style="'background: linear-gradient(135deg, ' + getCategoryColorById(service.categoria_id) + ' 0%, ' + getCategoryColorById(service.categoria_id) + 'CC 100%)'">
                                    <div class="w-2 h-2 bg-white rounded-full mr-2 pulse-dot"></div>
                                    <span x-text="getCategoryNameById(service.categoria_id)"></span>
                                </div>
                                <div class="text-2xl font-bold text-emerald-600"
                                     x-text="'$' + Number(service.precio).toLocaleString()"></div>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-4" x-text="service.nombre"></h3>

                            <div class="space-y-3 mb-6">
                                <!-- Duración -->
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium" x-text="service.duracion_minutos + ' minutos'"></span>
                                </div>

                                <!-- Profesional (con aviso si está inactivo) -->
                                <div class="text-gray-600">
                                    <!-- Caso normal: activo -->
                                    <template x-if="service.empleado_id && !isEmployeeInactive(service.empleado_id)">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="font-medium" x-text="service.empleado_nombre"></span>
                                        </div>
                                    </template>

                                    <!-- Inactivo: aviso + abrir edición para reasignar -->
                                    <template x-if="service.empleado_id && isEmployeeInactive(service.empleado_id)">
                                        <button type="button" @click="editService(service)"
                                                class="flex items-center text-red-600 hover:text-red-700 group">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0h8"/>
                                            </svg>
                                            <span class="text-sm font-semibold">
                                                <span x-text="service.empleado_nombre"></span> inactivo — reasignar
                                            </span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button
                                    class="flex-1 gradient-accent text-white px-4 py-3 rounded-xl font-semibold transition-all hover:shadow-lg">
                                    Agendar
                                </button>
                                <button @click="editService(service)"
                                    class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="deleteService(service.id)"
                                    class="px-4 py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Paginación -->
                <div class="glass-effect rounded-2xl shadow-lg p-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-gray-600 text-sm">
                            Mostrando <span class="font-bold text-gray-900" x-text="((currentPage - 1) * perPage) + 1"></span> -
                            <span class="font-bold text-gray-900" x-text="Math.min(currentPage * perPage, filteredServices.length)"></span>
                            de <span class="font-bold text-gray-900" x-text="filteredServices.length"></span> servicios
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium transition-all">Anterior</button>
                            <div class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-bold">
                                <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                            </div>
                            <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium transition-all">Siguiente</button>
                        </div>
                    </div>
                </div>

                <!-- Modal Categorías -->
                <div x-show="showCategories" x-transition
                    class="fixed inset-0 bg-black/50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-40">
                    <div @click.stop class="modal-content bg-white w-full sm:max-w-4xl sm:rounded-2xl max-h-screen overflow-hidden shadow-2xl">
                        <div class="gradient-primary text-white p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">Gestión de Categorías</h2>
                                    <p class="text-slate-300 text-sm mt-1">Organiza y administra tus categorías</p>
                                </div>
                                <button @click="showCategories = false"
                                    class="text-white hover:text-gray-300 p-2 rounded-lg hover:bg-white/10 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 max-h-[80vh] overflow-y-auto">
                            <div class="flex justify-between items-center mb-6">
                                <div class="text-gray-600">
                                    Total: <span class="font-bold text-gray-900" x-text="categories.length"></span> categorías
                                </div>
                                <button @click="openNewCategoryFromCategories()"
                                    class="btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-lg">
                                    + Nueva Categoría
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <template x-for="category in categories" :key="category.id">
                                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-100 card-hover">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-4 h-4 rounded-full pulse-dot" :style="'background-color: ' + category.color"></div>
                                                <h4 class="font-bold text-gray-900" x-text="category.nombre"></h4>
                                            </div>
                                            <span class="category-chip"
                                                  :style="'background: linear-gradient(135deg, ' + category.color + ' 0%, ' + category.color + 'CC 100%)'">
                                                <span x-text="(category.servicios_count ?? 0)"></span>
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="category.descripcion || 'Sin descripción'"></p>
                                        <div class="flex gap-2">
                                            <button @click="editCategory(category)"
                                                class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-medium transition-all">
                                                Editar
                                            </button>
                                            <button @click="deleteCategory(category.id)"
                                                class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-all">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Servicio -->
                <div x-show="showServiceForm" x-transition
                     class="fixed inset-0 bg-black/50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-50">
                    <div @click.stop class="modal-content bg-white w-full sm:max-w-lg sm:rounded-2xl max-h-screen overflow-y-auto shadow-2xl">
                        <div class="gradient-primary text-white p-6">
                            <h2 class="text-2xl font-bold" x-text="editingService ? 'Editar Servicio' : 'Nuevo Servicio'"></h2>
                        </div>
                        <form @submit.prevent="saveService()" class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Nombre del servicio</label>
                                <input x-model="serviceForm.nombre" type="text" required
                                       class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none">
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-sm font-semibold text-gray-700">Categoría</label>
                                    <button type="button" @click="openNewCategoryFromService()"
                                            class="text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                                        + Nueva categoría
                                    </button>
                                </div>
                                <select x-model.number="serviceForm.categoria_id" required
                                        class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none appearance-none bg-white">
                                    <option value="">Seleccionar categoría</option>
                                    <template x-for="cat in categories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Profesional</label>
                                <select x-model.number="serviceForm.empleado_id"
                                        class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none appearance-none bg-white">
                                    <option value="">Sin asignar</option>
                                    <!-- Sólo activos -->
                                    <template x-for="emp in activeEmployees" :key="emp.id">
                                        <option :value="emp.id" x-text="emp.nombre"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Precio</label>
                                    <input x-model.number="serviceForm.precio" type="number" required min="0" step="0.01"
                                           class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Duración (min)</label>
                                    <input x-model.number="serviceForm.duracion_minutos" type="number" required min="5" step="5"
                                           class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none">
                                </div>
                            </div>

                            <div class="flex gap-3 pt-6">
                                <button type="button" @click="closeServiceForm()"
                                        class="flex-1 px-6 py-4 border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 btn-primary text-white px-6 py-4 rounded-xl font-semibold">
                                    <span x-text="editingService ? 'Actualizar' : 'Guardar'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Categoría -->
                <div x-show="showCategoryForm" x-transition
                     class="fixed inset-0 bg-black/50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-[60]">
                    <div @click.stop class="modal-content bg-white w-full sm:max-w-lg sm:rounded-2xl max-h-screen overflow-y-auto shadow-2xl">
                        <div class="gradient-primary text-white p-6">
                            <h2 class="text-2xl font-bold" x-text="editingCategory ? 'Editar Categoría' : 'Nueva Categoría'"></h2>
                        </div>
                        <form @submit.prevent="saveCategory()" class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Nombre</label>
                                <input x-model="categoryForm.nombre" type="text" required
                                       class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Descripción</label>
                                <textarea x-model="categoryForm.descripcion" rows="3"
                                          class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Color</label>
                                    <input x-model="categoryForm.color" type="color"
                                           class="w-full h-12 border border-gray-200 rounded-xl cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Orden</label>
                                    <input x-model.number="categoryForm.orden" type="number" required min="1"
                                           class="input-modern w-full px-4 py-4 rounded-xl text-gray-900 focus:outline-none">
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input x-model="categoryForm.activo" type="checkbox" id="activo"
                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="activo" class="text-sm font-semibold text-gray-700">Categoría activa</label>
                            </div>
                            <div class="flex gap-3 pt-6">
                                <button type="button" @click="closeCategoryForm()"
                                        class="flex-1 px-6 py-4 border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="flex-1 btn-primary text-white px-6 py-4 rounded-xl font-semibold">
                                    <span x-text="editingCategory ? 'Actualizar' : 'Crear'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SCRIPT -->
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('appData', (serviciosBackend = [], categoriasBackend = [], empleadosBackend = []) => ({
                        // UI state
                        showCategories: false,
                        showServiceForm: false,
                        showCategoryForm: false,
                        returnToServiceForm: false,
                        returnToCategoriesList: false,

                        // filtros/paginación
                        search: '',
                        filterCategory: '',
                        perPage: 6,
                        currentPage: 1,

                        // edición
                        editingService: null,
                        editingCategory: null,

                        // forms
                        serviceForm: {
                            id: null,
                            nombre: '',
                            categoria_id: '',
                            duracion_minutos: 30,
                            precio: 0,
                            empleado_id: '' // opcional
                        },
                        categoryForm: {
                            nombre: '',
                            descripcion: '',
                            color: '#3b82f6',
                            activo: true,
                            orden: 1
                        },

                        // data
                        services: serviciosBackend || [],
                        categories: categoriasBackend || [],
                        employees: (empleadosBackend || []).map(e => ({ ...e, activo: !!Number(e?.activo) })),

                        // ===== SweetAlert2 helpers =====
                        toast(msg, icon = 'success') {
                            const T = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1700, timerProgressBar: true });
                            T.fire({ icon, title: msg });
                        },
                        alertError(msg) {
                            Swal.fire({ icon: 'error', title: 'Ups…', text: msg || 'Ha ocurrido un error' });
                        },
                        async confirmAction({ title='¿Estás seguro?', text='', confirmText='Sí, confirmar', icon='warning' } = {}) {
                            const r = await Swal.fire({ title, text, icon, showCancelButton: true, confirmButtonText: confirmText, cancelButtonText: 'Cancelar', reverseButtons: true });
                            return r.isConfirmed;
                        },

                        // ===== Helpers categorías (por ID) =====
                        getCategoryById(id) { return this.categories.find(c => Number(c.id) === Number(id)); },
                        getCategoryNameById(id) { const c = this.getCategoryById(id); return c ? c.nombre : 'Sin categoría'; },
                        getCategoryColorById(id) { const c = this.getCategoryById(id); return c ? (c.color || '#6366f1') : '#6366f1'; },

                        // ===== Helpers empleados =====
                        get activeEmployees() {
                            return (this.employees || []).filter(e => e && (e.activo === true));
                        },
                        isEmployeeInactive(empId) {
                            if (!empId) return false;
                            if (!Array.isArray(this.employees) || this.employees.length === 0) return false;
                            const emp = this.employees.find(e => Number(e.id) === Number(empId));
                            return !emp || emp.activo === false;
                        },

                        // ===== Computed =====
                        get filteredServices() {
                            return this.services.filter(s => {
                                const q = this.search?.toLowerCase() ?? '';
                                const matchesSearch = !q
                                    || (s.nombre || '').toLowerCase().includes(q)
                                    || (s.empleado_nombre || '').toLowerCase().includes(q);
                                const matchesCategory = !this.filterCategory
                                    || Number(s.categoria_id) === Number(this.filterCategory);
                                return matchesSearch && matchesCategory;
                            });
                        },
                        get totalPages() { return Math.max(1, Math.ceil(this.filteredServices.length / this.perPage)); },
                        get paginatedServices() {
                            const start = (this.currentPage - 1) * this.perPage;
                            return this.filteredServices.slice(start, start + this.perPage);
                        },

                        // ===== lifecycle =====
                        async init() {
                            this.$watch('search', () => this.currentPage = 1);
                            this.$watch('filterCategory', () => this.currentPage = 1);
                            this.$watch('perPage', () => this.currentPage = 1);

                            try {
                                const r = await fetch('/app/servicios/lista', { headers: { 'Accept': 'application/json' } });
                                if (!r.ok) throw new Error('No se pudieron cargar los servicios');
                                const d = await r.json();
                                this.services = Array.isArray(d.servicios) ? d.servicios : [];
                            } catch (e) {
                                console.error(e);
                                this.alertError('No se pudieron cargar los servicios');
                            }

                            await this.refreshCategories();

                            try {
                                const re = await fetch('/app/empleados/lista', { headers: { 'Accept': 'application/json' } });
                                if (re.ok) {
                                    const de = await re.json();
                                    this.employees = (Array.isArray(de.empleados) ? de.empleados : []).map(e => ({ ...e, activo: !!Number(e?.activo) }));
                                }
                            } catch(_) {}
                        },

                        // ===== Categorías =====
                        async openCategoriesModal() { await this.refreshCategories(); this.showCategories = true; },
                        async refreshCategories() {
                            try {
                                const resp = await fetch('/app/categorias', { headers: { 'Accept': 'application/json' } });
                                if (!resp.ok) throw new Error('No se pudieron cargar las categorías');
                                const data = await resp.json();
                                const cats = Array.isArray(data.categorias) ? data.categorias : [];
                                this.categories = cats.map(c => ({
                                    ...c,
                                    servicios_count: c.servicios_count ?? this.services.filter(s => Number(s.categoria_id) === Number(c.id)).length
                                }));
                            } catch (e) {
                                console.error(e);
                                this.alertError('No se pudieron cargar las categorías');
                            }
                        },
                        openNewCategoryFromService() {
                            this.returnToServiceForm = true;
                            this.showServiceForm = false;
                            this.editingCategory = null;
                            this.categoryForm = { nombre: '', descripcion: '', color: '#3b82f6', activo: true, orden: (this.categories.length || 0) + 1 };
                            this.showCategoryForm = true;
                        },
                        openNewCategoryFromCategories() {
                            this.returnToCategoriesList = true;
                            this.showCategories = false;
                            this.editingCategory = null;
                            this.categoryForm = { nombre: '', descripcion: '', color: '#3b82f6', activo: true, orden: (this.categories.length || 0) + 1 };
                            this.showCategoryForm = true;
                        },
                        editCategory(category) {
                            this.editingCategory = category;
                            this.categoryForm = {
                                nombre: category.nombre,
                                descripcion: category.descripcion,
                                color: category.color,
                                activo: !!category.activo,
                                orden: Number(category.orden) || 1
                            };
                            this.showCategories = false;
                            this.showCategoryForm = true;
                        },
                        closeCategoryForm() {
                            this.showCategoryForm = false;
                            this.editingCategory = null;
                            this.categoryForm = { nombre: '', descripcion: '', color: '#3b82f6', activo: true, orden: (this.categories.length || 0) + 1 };
                            if (this.returnToServiceForm) { this.returnToServiceForm = false; this.showServiceForm = true; }
                            else if (this.returnToCategoriesList) { this.returnToCategoriesList = false; this.showCategories = true; }
                        },
                        async saveCategory() {
                            try {
                                const isEdit = !!this.editingCategory?.id;
                                const url = isEdit ? `/app/categorias/${this.editingCategory.id}` : `/app/categorias`;
                                const method = isEdit ? 'PUT' : 'POST';

                                const response = await fetch(url, {
                                    method,
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' },
                                    body: JSON.stringify(this.categoryForm)
                                });

                                const data = await response.json();
                                if (!response.ok) throw new Error(data.error || 'Error al guardar');

                                if (isEdit) {
                                    const idx = this.categories.findIndex(c => c.id === this.editingCategory.id);
                                    if (idx !== -1) this.categories[idx] = { ...data.categoria };
                                    this.toast('Categoría actualizada');
                                } else {
                                    this.categories.push({ ...data.categoria });
                                    this.toast('Categoría creada');
                                }

                                this.closeCategoryForm();
                                await this.refreshCategories();
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al guardar la categoría');
                            }
                        },
                        async deleteCategory(id) {
                            const cat = this.categories.find(c => c.id === id);
                            if (!cat) return;
                            const ok = await this.confirmAction({
                                title: `¿Eliminar la categoría "${cat.nombre}"?`,
                                text: 'Si tiene servicios vinculados no se podrá eliminar.',
                                confirmText: 'Sí, eliminar',
                                icon: 'warning'
                            });
                            if (!ok) return;

                            try {
                                const response = await fetch(`/app/categorias/${id}`, {
                                    method: 'DELETE',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }
                                });

                                if (response.status === 409) {
                                    const data = await response.json().catch(() => ({}));
                                    this.alertError(data.error || 'No se puede eliminar: tiene servicios vinculados.');
                                    return;
                                }

                                if (!response.ok) {
                                    const data = await response.json().catch(() => ({}));
                                    throw new Error(data.error || 'Error al eliminar');
                                }

                                this.categories = this.categories.filter(c => c.id !== id);
                                this.toast('Categoría eliminada');
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al eliminar la categoría');
                            }
                        },

                        // ===== Servicios =====
                        newService() {
                            this.editingService = null;
                            this.serviceForm = { id: null, nombre: '', categoria_id: '', duracion_minutos: 30, precio: 0, empleado_id: '' };
                            this.showServiceForm = true;
                        },
                        editService(service) {
                            const categoriaId = service.categoria_id ?? (this.categories.find(c => c.nombre === service.categoria)?.id || '');
                            const duracion = service.duracion_minutos ?? service.duracion ?? 30;

                            this.editingService = service;
                            this.serviceForm = {
                                id: service.id ?? null,
                                nombre: service.nombre ?? '',
                                categoria_id: Number(categoriaId) || '',
                                duracion_minutos: Number(duracion),
                                precio: Number(service.precio ?? 0),
                                empleado_id: service.empleado_id ?? service.profesional ?? ''
                            };
                            this.showServiceForm = true;
                        },
                        closeServiceForm() {
                            this.showServiceForm = false;
                            this.editingService = null;
                            this.serviceForm = { id: null, nombre: '', categoria_id: '', duracion_minutos: 30, precio: 0, empleado_id: '' };
                        },
                        async saveService() {
                            try {
                                const isEdit = !!this.editingService?.id;
                                const url = isEdit ? `/app/servicios/${this.editingService.id}` : `/app/servicios`;
                                const method = isEdit ? 'PUT' : 'POST';

                                const payload = {
                                    nombre: this.serviceForm.nombre,
                                    categoria_id: this.serviceForm.categoria_id,
                                    duracion_minutos: this.serviceForm.duracion_minutos,
                                    precio: this.serviceForm.precio,
                                    empleado_id: this.serviceForm.empleado_id || null
                                };

                                const response = await fetch(url, {
                                    method,
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' },
                                    body: JSON.stringify(payload)
                                });

                                const data = await response.json();
                                if (!response.ok) throw new Error(data.error || 'Error al guardar');

                                const emp = this.employees.find(e => e.id === this.serviceForm.empleado_id);
                                const empleado_nombre = emp ? emp.nombre : '';

                                if (isEdit) {
                                    const idx = this.services.findIndex(s => s.id === this.editingService.id);
                                    if (idx !== -1) {
                                        this.services[idx] = { ...this.services[idx], ...data.servicio, empleado_nombre };
                                    }
                                    this.toast('Servicio actualizado');
                                } else {
                                    this.services.push({ ...data.servicio, empleado_nombre });
                                    this.toast('Servicio creado');
                                }

                                this.closeServiceForm();
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al guardar el servicio');
                            }
                        },
                        async deleteService(id) {
                            const svc = this.services.find(s => s.id === id);
                            const ok = await this.confirmAction({
                                title: `¿Eliminar "${svc?.nombre || 'este servicio'}"?`,
                                confirmText: 'Sí, eliminar',
                                icon: 'warning'
                            });
                            if (!ok) return;

                            try {
                                const response = await fetch(`/app/servicios/${id}`, {
                                    method: 'DELETE',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }
                                });

                                if (!response.ok) {
                                    const data = await response.json().catch(() => ({}));
                                    throw new Error(data.error || 'Error al eliminar');
                                }

                                this.services = this.services.filter(s => s.id !== id);
                                this.toast('Servicio eliminado');
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al eliminar el servicio');
                            }
                        }
                    }));
                });
            </script>
        </body>

        </html>
    </div>
</x-app-layout>
