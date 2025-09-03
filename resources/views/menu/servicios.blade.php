{{-- resources/views/app/servicios.blade.php --}}
@php
    // Datos de ejemplo por si no recibes $items desde el controlador.
    $items = $items ?? [
        [
            'sku' => 'MBP-17',
            'name' => 'Apple MacBook Pro 17"',
            'color' => 'Silver',
            'category' => 'Laptop',
            'price' => 2999,
        ],
        [
            'sku' => 'SUR-PRO',
            'name' => 'Microsoft Surface Pro',
            'color' => 'White',
            'category' => 'Laptop PC',
            'price' => 1999,
        ],
        ['sku' => 'MM-2', 'name' => 'Magic Mouse 2', 'color' => 'Black', 'category' => 'Accessories', 'price' => 99],
        ['sku' => 'PIXEL', 'name' => 'Google Pixel Phone', 'color' => 'Gray', 'category' => 'Phone', 'price' => 799],
        ['sku' => 'AW-5', 'name' => 'Apple Watch 5', 'color' => 'Red', 'category' => 'Wearables', 'price' => 999],
    ];
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
            <style>
                [x-cloak] {
                    display: none !important;
                }
                .modal-backdrop {
                    backdrop-filter: blur(2px);
                }
                .card-hover {
                    transition: all 0.2s ease;
                }
                .card-hover:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                }
                .gradient-bg {
                    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
                }
            </style>
        </head>
        <body class="bg-gray-50 min-h-screen">
            <div x-data="appData(@json($servicios ?? []), @json($categorias ?? []), @json($empleados ?? []))" x-cloak class="container mx-auto px-3 py-4 max-w-7xl">
                <!-- Header Mobile Optimized -->
                <div class="gradient-bg rounded-xl shadow-lg p-4 sm:p-6 mb-6 text-white">
                    <div class="flex flex-col space-y-4">
                        <div class="text-center sm:text-left">
                            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Servicios</h1>
                            <p class="text-gray-300 text-sm sm:text-base">Gestión de servicios por cátegorias</p>
                        </div>
                        <div class="flex justify-center sm:justify-between items-center">
                            <div class="hidden sm:flex gap-6 text-sm">
                                <span>Servicios: <span class="font-semibold" x-text="services.length"></span></span>
                                <span>Categorías: <span class="font-semibold" x-text="categories.length"></span></span>
                            </div>
                            <div class="flex gap-2">
                                <button @click="showServiceForm = true"
                                    class="bg-white text-gray-800 px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all text-sm">
                                    + Servicio
                                </button>
                                <button @click="showCategories = true"
                                    class="bg-gray-600 bg-opacity-50 border border-gray-400 border-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all text-sm">
                                    Categorías
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Controles Mobile First -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="space-y-3">
                        <input x-model="search" type="text" placeholder="Buscar servicios..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        <div class="grid grid-cols-2 gap-3">
                            <select x-model="filterCategory"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="">Todas</option>
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.nombre" x-text="cat.nombre"></option>
                                </template>
                            </select>
                            <select x-model="perPage"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="4">4 por página</option>
                                <option value="8">8 por página</option>
                                <option value="12">12 por página</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Grid Responsive -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <template x-for="service in paginatedServices" :key="service.id">
                        <div class="bg-white rounded-xl shadow-md p-4 card-hover">
                            <div class="flex justify-between items-start mb-3">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white"
                                    :style="'background-color: ' + getCategoryColor(service.categoria)"
                                    x-text="service.categoria">
                                </span>
                                <div class="text-xl font-bold text-green-600" x-text="'$' + service.precio"></div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3" x-text="service.nombre"></h3>
                            <div class="space-y-1 mb-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <span class="w-4 h-4 mr-2 text-gray-500">⏱</span>
                                    <span x-text="service.duracion + ' min'"></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-4 h-4 mr-2 text-gray-500">👤</span>
                                    <span x-text="service.empleado_nombre"></span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Agendar
                                </button>
                                <button @click="editService(service)"
                                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-colors">✏️</button>
                                <button @click="deleteService(service.id)"
                                    class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm transition-colors">🗑️</button>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Paginación Mobile -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-gray-600 text-sm text-center">
                            Mostrando <span class="font-medium" x-text="((currentPage - 1) * perPage) + 1"></span> -
                            <span class="font-medium"
                                x-text="Math.min(currentPage * perPage, filteredServices.length)"></span>
                            de <span class="font-medium" x-text="filteredServices.length"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50' : 'hover:bg-gray-50'"
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                ← Ant
                            </button>
                            <span class="px-3 py-1 text-sm font-medium" x-text="currentPage + '/' + totalPages"></span>
                            <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50' : 'hover:bg-gray-50'"
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                Sig →
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modal Categorías -->
                <div x-show="showCategories" x-transition
                    class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-40">
                    <div @click.stop class="bg-white w-full sm:max-w-4xl sm:rounded-lg max-h-screen overflow-hidden">
                        <div class="bg-gray-800 text-white p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold">Gestión de Categorías</h2>
                                    <p class="text-gray-300 text-sm">Organiza tus categorías</p>
                                </div>
                                <button @click="showCategories = false"
                                    class="text-white hover:text-gray-300 p-2">✕</button>
                            </div>
                        </div>
                        <div class="p-4 max-h-[80vh] overflow-y-auto">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-600">Total: <span class="font-semibold"
                                        x-text="categories.length"></span></span>
                                <button @click="showCategoryForm = true"
                                    class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                                    + Nueva
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="category in categories" :key="category.id">
                                    <div class="bg-gray-50 rounded-lg p-4 border">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-3 h-3 rounded-full"
                                                    :style="'background-color: ' + category.color"></div>
                                                <h4 class="font-semibold text-gray-900" x-text="category.nombre"></h4>
                                            </div>
                                            <span class="text-xs bg-white px-2 py-1 rounded-full text-gray-500"
                                                x-text="category.servicios_count + ' srv'"></span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3"
                                            x-text="category.descripcion || 'Sin descripción'"></p>
                                        <div class="flex gap-2">
                                            <button @click="editCategory(category)"
                                                class="flex-1 bg-gray-700 hover:bg-gray-800 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                Editar
                                            </button>
                                            <button @click="deleteCategory(category.id)"
                                                class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm transition-colors">Eliminar</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Servicio -->
                <div x-show="showServiceForm" x-transition
                    class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-50">
                    <div @click.stop class="bg-white w-full sm:max-w-lg sm:rounded-lg max-h-screen overflow-y-auto">
                        <div class="bg-gray-800 text-white p-4">
                            <h2 class="text-xl font-semibold"
                                x-text="editingService ? 'Editar Servicio' : 'Nuevo Servicio'"></h2>
                        </div>
                        <form @submit.prevent="saveService()" class="p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del servicio</label>
                                <input x-model="serviceForm.nombre" type="text" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-gray-700">Categoría</label>
                                    <button type="button" @click="openNewCategoryFromService()"
                                        class="text-gray-600 text-sm font-medium hover:text-gray-800 transition-colors">
                                        + Nueva categoría
                                    </button>
                                </div>
                                <select x-model="serviceForm.categoria" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    <option value="">Seleccionar categoría</option>
                                    <template x-for="cat in categories" :key="cat.id">
                                        <option :value="cat.nombre" x-text="cat.nombre"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Profesional</label>
                                <select x-model.number="serviceForm.empleado_id"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    <option value="">Sin asignar</option>
                                    <template x-for="emp in employees" :key="emp.id">
                                        <option :value="emp.id" x-text="emp.nombre"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                                    <input x-model.number="serviceForm.precio" type="number" required min="1"
                                        step="0.01"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Duración (min)</label>
                                    <input x-model.number="serviceForm.duracion" type="number" required
                                        min="5" step="5"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="closeServiceForm()"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                    <span x-text="editingService ? 'Actualizar' : 'Guardar'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal Categoría -->
                <div x-show="showCategoryForm" x-transition
                    class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-60">
                    <div @click.stop class="bg-white w-full sm:max-w-lg sm:rounded-lg max-h-screen overflow-y-auto">
                        <div class="bg-gray-800 text-white p-4">
                            <h2 class="text-xl font-semibold"
                                x-text="editingCategory ? 'Editar Categoría' : 'Nueva Categoría'"></h2>
                        </div>
                        <form @submit.prevent="saveCategory()" class="p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                <input x-model="categoryForm.nombre" type="text" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                <textarea x-model="categoryForm.descripcion" rows="2"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                    <input x-model="categoryForm.color" type="color"
                                        class="w-full h-12 border border-gray-200 rounded-lg cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                                    <input x-model.number="categoryForm.orden" type="number" required min="1"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input x-model="categoryForm.activo" type="checkbox" id="activo"
                                    class="h-4 w-4 text-gray-600 border-gray-300 rounded focus:ring-gray-500">
                                <label for="activo" class="ml-3 text-sm font-medium text-gray-700">Categoría
                                    activa</label>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="closeCategoryForm()"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                    <span x-text="editingCategory ? 'Actualizar' : 'Crear'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
document.addEventListener('alpine:init', () => {
    Alpine.data('appData', (serviciosBackend = [], categoriasBackend = [], empleadosBackend = []) => ({
        // UI state
        showCategories: false,
        showServiceForm: false,
        showCategoryForm: false,
        returnToServiceForm: false,
        // filtros/paginación
        search: '',
        filterCategory: '',
        perPage: 4,
        currentPage: 1,
        // edición
        editingService: null,
        editingCategory: null,
        // forms
        serviceForm: {
            nombre: '',
            categoria: '',
            duracion: 30,
            precio: 0,
            empleado_id: '' // opcional
        },
        categoryForm: {
            nombre: '',
            descripcion: '',
            color: '#374151',
            activo: true,
            orden: 1
        },
        // data
        services: serviciosBackend || [],
        categories: categoriasBackend || [],
        employees: empleadosBackend || [],
        
        // computed helpers
        getCategoryColor(categoryName) {
            const category = this.categories.find(c => c.nombre === categoryName);
            return category ? category.color : '#6B7280';
        },
        
        get filteredServices() {
            return this.services.filter(s => {
                const q = this.search?.toLowerCase() ?? '';
                const matchesSearch = !q
                    || s.nombre?.toLowerCase().includes(q)
                    || (s.empleado_nombre || '').toLowerCase().includes(q);
                const matchesCategory = !this.filterCategory || s.categoria === this.filterCategory;
                return matchesSearch && matchesCategory;
            });
        },
        
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredServices.length / this.perPage));
        },
        
        get paginatedServices() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredServices.slice(start, start + this.perPage);
        },
        
        // lifecycle
        init() {
            // watchers
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('filterCategory', () => this.currentPage = 1);
            this.$watch('perPage', () => this.currentPage = 1);
            
            console.log('Alpine iniciado con:', {
                servicios: this.services.length,
                categorias: this.categories.length,
                empleados: this.employees.length
            });
        },
        
        // actions
        openNewCategoryFromService() {
            this.returnToServiceForm = true;
            this.showServiceForm = false;
            this.showCategoryForm = true;
        },
        
        editService(service) {
            this.editingService = service;
            this.serviceForm = {
                nombre: service.nombre,
                categoria: service.categoria,
                duracion: Number(service.duracion),
                precio: Number(service.precio),
                empleado_id: service.empleado_id || '' // opcional
            };
            this.showServiceForm = true;
        },
        
        closeServiceForm() {
            this.showServiceForm = false;
            this.editingService = null;
            this.serviceForm = {
                nombre: '',
                categoria: '',
                duracion: 30,
                precio: 0,
                empleado_id: '' // opcional
            };
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
            this.categoryForm = {
                nombre: '',
                descripcion: '',
                color: '#374151',
                activo: true,
                orden: (this.categories.length || 0) + 1
            };
            if (this.returnToServiceForm) {
                this.returnToServiceForm = false;
                this.showServiceForm = true;
            }
        },
        
        async saveService() {
            try {
                const payload = {
                    ...this.serviceForm,
                    // aseguramos null en backend si está vacío
                    empleado_id: this.serviceForm.empleado_id || null
                };
                const response = await fetch('/app/servicios', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Error al guardar');
                
                const empleado = this.employees.find(e => e.id === this.serviceForm.empleado_id);
                this.services.push({
                    ...data.servicio,
                    categoria: this.serviceForm.categoria,
                    empleado_nombre: empleado ? empleado.nombre : ''
                });
                this.closeServiceForm();
            } catch (e) {
                console.error(e);
                alert('Error al guardar el servicio');
            }
        },
        
        async saveCategory() {
            try {
                const response = await fetch('/app/categorias', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.categoryForm)
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Error al guardar');
                
                this.categories.push({
                    ...data.categoria,
                    servicios_count: 0
                });
                this.closeCategoryForm();
                if (!this.returnToServiceForm) this.showCategories = true;
            } catch (e) {
                console.error(e);
                alert('Error al guardar la categoría');
            }
        },
        
        async deleteService(id) {
            if (!confirm('¿Eliminar este servicio?')) return;
            this.services = this.services.filter(s => s.id !== id);
            // TODO: si tienes endpoint DELETE, dispara el fetch aquí.
        },
        
        async deleteCategory(id) {
            const cat = this.categories.find(c => c.id === id);
            if (cat && cat.servicios_count > 0) {
                alert('No se puede eliminar: tiene servicios asignados.');
                return;
            }
            if (!confirm('¿Eliminar esta categoría?')) return;
            this.categories = this.categories.filter(c => c.id !== id);
            // TODO: si tienes endpoint DELETE, dispara el fetch aquí.
        }
    }));
});
</script>
        </body>
        </html>
    </div>
</x-app-layout>
{{-- SOLO para pruebas/local --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        200: '#bfdbfe',
                        300: '#93c5fd',
                        400: '#60a5fa',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',
                        900: '#1e3a8a'
                    },
                    accent: {
                        50: '#f5f3ff',
                        100: '#ede9fe',
                        200: '#ddd6fe',
                        300: '#c4b5fd',
                        400: '#a78bfa',
                        500: '#8b5cf6',
                        600: '#7c3aed',
                        700: '#6d28d9',
                        800: '#5b21b6',
                        900: '#4c1d95'
                    }
                }
            }
        }
    }
</script>