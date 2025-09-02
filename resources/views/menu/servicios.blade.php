<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios de Estética</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div x-data="appData()" x-cloak class="container mx-auto px-4 py-6 max-w-6xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Servicios de Estética</h1>
            <p class="text-gray-600">Gestiona tus servicios y categorías</p>
        </div>

        <!-- Controles superiores -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <!-- Búsqueda -->
                <div class="col-span-1 sm:col-span-2">
                    <input 
                        x-model="search"
                        type="text"
                        placeholder="Buscar servicio..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <!-- Botón Categorías -->
                <button 
                    @click="showCategories = true"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                    Ver Categorías
                </button>
            </div>

            <!-- Filtros -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <select x-model="filterCategory" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Todas las categorías</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.nombre" x-text="cat.nombre"></option>
                    </template>
                </select>

                <select x-model="filterProfessional" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Todos los profesionales</option>
                    <template x-for="pro in professionals" :key="pro">
                        <option :value="pro" x-text="pro"></option>
                    </template>
                </select>

                <select x-model="perPage" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="5">5 por página</option>
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                </select>

                <div class="text-sm text-gray-600 flex items-center">
                    Total: <span class="font-semibold ml-1" x-text="filteredServices.length"></span>
                </div>
            </div>
        </div>

        <!-- Tabla Mobile -->
        <div class="block md:hidden space-y-3">
            <template x-for="service in paginatedServices" :key="service.id">
                <div class="bg-white rounded-lg border shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800" x-text="service.nombre"></h3>
                            <p class="text-sm text-gray-600" x-text="service.codigo"></p>
                        </div>
                        <span class="text-lg font-bold text-green-600" x-text="'$' + service.precio"></span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-3">
                        <div>Categoría: <span class="font-medium" x-text="service.categoria"></span></div>
                        <div>Duración: <span class="font-medium" x-text="service.duracion + ' min'"></span></div>
                        <div class="col-span-2">Profesional: <span class="font-medium" x-text="service.profesional"></span></div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button class="flex-1 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm font-medium">
                            Agendar
                        </button>
                        <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            Editar
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Tabla Desktop -->
        <div class="hidden md:block bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Servicio</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Categoría</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Duración</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Profesional</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Precio</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="service in paginatedServices" :key="service.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-600" x-text="service.codigo"></td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800" x-text="service.nombre"></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                          x-text="service.categoria">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600" x-text="service.duracion + ' min'"></td>
                                <td class="px-4 py-3 text-sm text-gray-600" x-text="service.profesional"></td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600 text-right" x-text="'$' + service.precio"></td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-sm">
                                            Agendar
                                        </button>
                                        <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded text-sm">
                                            Editar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-lg border p-4">
            <div class="text-sm text-gray-600">
                Mostrando <span x-text="((currentPage - 1) * perPage) + 1"></span> - 
                <span x-text="Math.min(currentPage * perPage, filteredServices.length)"></span> 
                de <span x-text="filteredServices.length"></span> resultados
            </div>
            
            <div class="flex items-center gap-2">
                <button 
                    @click="currentPage = Math.max(1, currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm"
                >
                    Anterior
                </button>
                
                <span class="px-3 py-2 text-sm text-gray-600">
                    Página <span x-text="currentPage"></span> de <span x-text="totalPages"></span>
                </span>
                
                <button 
                    @click="currentPage = Math.min(totalPages, currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm"
                >
                    Siguiente
                </button>
            </div>
        </div>

        <!-- Modal Categorías -->
        <div x-show="showCategories" 
             @click.away="showCategories = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            
            <div @click.stop 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[80vh] overflow-hidden">
                
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Categorías de Servicios</h2>
                    <button @click="showCategories = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-4 overflow-y-auto max-h-[60vh]">
                    <div class="grid gap-4">
                        <template x-for="category in categories" :key="category.id">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold text-gray-800" x-text="category.nombre"></h3>
                                    <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full" 
                                          x-text="category.servicios_count + ' servicios'"></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3" x-text="category.descripcion"></p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-gray-500">
                                    <div>Color: <span class="font-medium" x-text="category.color"></span></div>
                                    <div>Estado: <span class="font-medium" x-text="category.activo ? 'Activo' : 'Inactivo'"></span></div>
                                    <div>Orden: <span class="font-medium" x-text="category.orden"></span></div>
                                    <div>ID: <span class="font-medium" x-text="category.id"></span></div>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <button class="bg-blue-500 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-600">
                                        Editar
                                    </button>
                                    <button class="border border-red-300 text-red-600 px-3 py-1.5 rounded text-sm hover:bg-red-50">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                    <button @click="showCategories = false" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                        Cerrar
                    </button>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600">
                        Nueva Categoría
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function appData() {
            return {
                showCategories: false,
                search: '',
                filterCategory: '',
                filterProfessional: '',
                perPage: 10,
                currentPage: 1,
                
                // Datos de ejemplo - Servicios
                services: [
                    { id: 1, codigo: 'CUT-DAMA', nombre: 'Corte de dama', categoria: 'Cabello', duracion: 45, precio: 250, profesional: 'Ana' },
                    { id: 2, codigo: 'CUT-CAB', nombre: 'Corte caballero', categoria: 'Cabello', duracion: 30, precio: 180, profesional: 'Luis' },
                    { id: 3, codigo: 'TINTE', nombre: 'Coloración completa', categoria: 'Cabello', duracion: 120, precio: 950, profesional: 'Sofía' },
                    { id: 4, codigo: 'MANI', nombre: 'Manicure clásico', categoria: 'Uñas', duracion: 45, precio: 220, profesional: 'Paola' },
                    { id: 5, codigo: 'PEDI', nombre: 'Pedicure spa', categoria: 'Uñas', duracion: 60, precio: 320, profesional: 'Paola' },
                    { id: 6, codigo: 'KERAT', nombre: 'Keratina / Alisado', categoria: 'Cabello', duracion: 150, precio: 1800, profesional: 'Luis' },
                    { id: 7, codigo: 'DEP-CEJ', nombre: 'Depilación de ceja', categoria: 'Depilación', duracion: 15, precio: 120, profesional: 'Ana' },
                    { id: 8, codigo: 'MAQ-EVE', nombre: 'Maquillaje de evento', categoria: 'Maquillaje', duracion: 90, precio: 950, profesional: 'Sofía' }
                ],
                
                // Datos de ejemplo - Categorías
                categories: [
                    { id: 1, nombre: 'Cabello', descripcion: 'Servicios especializados en cuidado y estilismo capilar', color: '#3B82F6', activo: true, orden: 1, servicios_count: 4 },
                    { id: 2, nombre: 'Uñas', descripcion: 'Manicure, pedicure y cuidado de uñas', color: '#EC4899', activo: true, orden: 2, servicios_count: 2 },
                    { id: 3, nombre: 'Depilación', descripcion: 'Servicios de depilación facial y corporal', color: '#10B981', activo: true, orden: 3, servicios_count: 1 },
                    { id: 4, nombre: 'Maquillaje', descripcion: 'Maquillaje profesional para eventos y ocasiones especiales', color: '#F59E0B', activo: true, orden: 4, servicios_count: 1 }
                ],
                
                get professionals() {
                    return [...new Set(this.services.map(s => s.profesional))].sort();
                },
                
                get filteredServices() {
                    return this.services.filter(service => {
                        const matchesSearch = !this.search || 
                            service.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                            service.codigo.toLowerCase().includes(this.search.toLowerCase()) ||
                            service.profesional.toLowerCase().includes(this.search.toLowerCase());
                        
                        const matchesCategory = !this.filterCategory || service.categoria === this.filterCategory;
                        const matchesProfessional = !this.filterProfessional || service.profesional === this.filterProfessional;
                        
                        return matchesSearch && matchesCategory && matchesProfessional;
                    });
                },
                
                get totalPages() {
                    return Math.ceil(this.filteredServices.length / this.perPage);
                },
                
                get paginatedServices() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredServices.slice(start, start + this.perPage);
                },
                
                init() {
                    this.$watch('search', () => this.currentPage = 1);
                    this.$watch('filterCategory', () => this.currentPage = 1);
                    this.$watch('filterProfessional', () => this.currentPage = 1);
                    this.$watch('perPage', () => this.currentPage = 1);
                }
            }
        }
    </script>
</body>
</html>