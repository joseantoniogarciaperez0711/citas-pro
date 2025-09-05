{{-- resources/views/menu/empleados.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Empleados') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <html lang="es">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>Empleados</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            {{-- SweetAlert2 --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <style>
                [x-cloak] { display: none !important }
                .modal-backdrop { backdrop-filter: blur(2px) }
                .card-hover { transition: all .2s ease }
                .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.1) }
                .gradient-bg { background: linear-gradient(135deg,#1f2937 0%,#374151 100%) }
            </style>
        </head>

        <body class="bg-gray-50 min-h-screen">
            <div x-data="empData()" x-cloak class="container mx-auto px-3 py-4 max-w-7xl">
                <!-- Header -->
                <div class="gradient-bg rounded-xl shadow-lg p-4 sm:p-6 mb-6 text-white">
                    <div class="flex flex-col space-y-4">
                        <div class="text-center sm:text-left">
                            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Empleados</h1>
                            <p class="text-gray-300 text-sm sm:text-base">Gestión de tu equipo</p>
                        </div>
                        <div class="flex justify-center sm:justify-between items-center">
                            <div class="hidden sm:flex gap-6 text-sm">
                                <span>Total: <span class="font-semibold" x-text="employees.length"></span></span>
                                <span>Activos: <span class="font-semibold" x-text="employees.filter(e => !!e.activo).length"></span></span>
                                <span>Eliminados: <span class="font-semibold" x-text="employees.filter(e => !e.activo).length"></span></span>
                            </div>
                            <div class="flex gap-2">
                                <button @click="newEmployee()"
                                    class="bg-white text-gray-800 px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all text-sm">
                                    + Empleado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="space-y-3">
                        <input x-model="search" type="text" placeholder="Buscar por nombre o puesto..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Mostrar: Activos / Eliminados -->
                            <select x-model="filterActive"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="activos">Mostrar: Activos</option>
                                <option value="eliminados">Mostrar: Eliminados</option>
                                <option value="todos">Mostrar: Todos</option>
                            </select>

                            <!-- Estado (opcional) -->
                            <select x-model="filterStatus"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="">Todos los estados</option>
                                <template x-for="st in statusOptions" :key="st">
                                    <option :value="st" x-text="st"></option>
                                </template>
                            </select>

                            <!-- Per page -->
                            <select x-model="perPage"
                                class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-500">
                                <option value="4">4 por página</option>
                                <option value="8">8 por página</option>
                                <option value="12">12 por página</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <template x-for="emp in paginatedEmployees" :key="emp.id">
                        <div class="bg-white rounded-xl shadow-md p-4 card-hover">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block w-3 h-3 rounded-full" :style="'background-color:' + (emp.color || '#8b5cf6')"></span>
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="emp.nombre"></h3>
                                    <span x-show="!emp.activo" class="ml-2 text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">Inactivo</span>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full text-white"
                                    :style="'background-color:' + statusColor(emp.status)"
                                    x-text="emp.status || '—'"></span>
                            </div>

                            <p class="text-sm text-gray-600 mb-2" x-text="emp.puesto || 'Sin puesto'"></p>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>Servicios asignados:</span>
                                <span class="font-medium text-gray-800" x-text="emp.servicios_count ?? 0"></span>
                            </div>

                            <div class="flex gap-2">
                                <!-- Si está inactivo, mostrar únicamente Reincorporar -->
                                <template x-if="!emp.activo">
                                    <button @click="restoreEmployee(emp)"
                                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Reincorporar
                                    </button>
                                </template>

                                <!-- Si está activo, mostrar Editar / Eliminar (desactivar) -->
                                <template x-if="emp.activo">
                                    <div class="flex gap-2 w-full">
                                        <button @click="editEmployee(emp)"
                                            class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Editar
                                        </button>
                                        <button @click="deleteEmployee(emp)"
                                            class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm transition-colors">
                                            Eliminar
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Paginación -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-gray-600 text-sm text-center">
                            Mostrando <span class="font-medium" x-text="((currentPage - 1) * perPage) + 1"></span> -
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, filteredEmployees.length)"></span>
                            de <span class="font-medium" x-text="filteredEmployees.length"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50' : 'hover:bg-gray-50'"
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm">← Ant</button>
                            <span class="px-3 py-1 text-sm font-medium" x-text="currentPage + '/' + totalPages"></span>
                            <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50' : 'hover:bg-gray-50'"
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm">Sig →</button>
                        </div>
                    </div>
                </div>

                <!-- Modal Empleado -->
                <div x-show="showEmployeeForm" x-transition
                    class="fixed inset-0 bg-black/50 modal-backdrop flex items-end sm:items-center justify-center p-0 sm:p-4 z-50">
                    <div @click.stop class="bg-white w-full sm:max-w-lg sm:rounded-lg max-h-screen overflow-y-auto">
                        <div class="bg-gray-800 text-white p-4">
                            <h2 class="text-xl font-semibold" x-text="editingEmployee ? 'Editar empleado' : 'Nuevo empleado'"></h2>
                        </div>
                        <form @submit.prevent="saveEmployee()" class="p-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                <input x-model="employeeForm.nombre" type="text" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Puesto</label>
                                <input x-model="employeeForm.puesto" type="text"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                    <input x-model="employeeForm.color" type="color"
                                        class="w-full h-12 border border-gray-200 rounded-lg cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                    <select x-model="employeeForm.status"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        <option value="">Sin estado</option>
                                        <template x-for="st in statusOptions" :key="st">
                                            <option :value="st" x-text="st"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input x-model="employeeForm.activo" type="checkbox" id="emp_activo"
                                    class="h-4 w-4 text-gray-600 border-gray-300 rounded focus:ring-gray-500">
                                <label for="emp_activo" class="ml-3 text-sm font-medium text-gray-700">Empleado activo</label>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="closeEmployeeForm()"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700">Cancelar</button>
                                <button type="submit"
                                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                    <span x-text="editingEmployee ? 'Actualizar' : 'Guardar'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Script Alpine -->
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('empData', () => ({
                        // UI
                        showEmployeeForm: false,

                        // filtros / paginación
                        search: '',
                        filterStatus: '',
                        filterActive: 'activos', // por defecto solo activos
                        perPage: 8,
                        currentPage: 1,

                        // edición
                        editingEmployee: null,

                        // data
                        employees: [],

                        statusOptions: ['Disponible', 'Ocupado', 'Fuera', 'Descanso'],

                        // form
                        employeeForm: {
                            nombre: '',
                            puesto: '',
                            color: '#8b5cf6',
                            status: '',
                            activo: true,
                        },

                        // ===== Helpers SweetAlert2 =====
                        toast(msg, icon = 'success') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1700,
                                timerProgressBar: true
                            });
                            Toast.fire({ icon, title: msg });
                        },
                        alertError(msg) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ups…',
                                text: msg || 'Ha ocurrido un error'
                            });
                        },
                        async confirmAction({ title = '¿Estás seguro?', text = '', confirmText = 'Sí, confirmar', icon = 'warning' } = {}) {
                            const result = await Swal.fire({
                                title, text, icon,
                                showCancelButton: true,
                                confirmButtonText: confirmText,
                                cancelButtonText: 'Cancelar',
                                reverseButtons: true
                            });
                            return result.isConfirmed;
                        },

                        // computed
                        get filteredEmployees() {
                            const q = (this.search || '').toLowerCase();
                            return this.employees
                                .filter(e => {
                                    if (this.filterActive === 'activos' && !e.activo) return false;
                                    if (this.filterActive === 'eliminados' && e.activo) return false;

                                    const matchesQ = !q ||
                                        (e.nombre || '').toLowerCase().includes(q) ||
                                        (e.puesto || '').toLowerCase().includes(q);

                                    const matchesStatus = !this.filterStatus ||
                                        (e.status || '') === this.filterStatus;

                                    return matchesQ && matchesStatus;
                                });
                        },
                        get totalPages() {
                            return Math.max(1, Math.ceil(this.filteredEmployees.length / this.perPage));
                        },
                        get paginatedEmployees() {
                            const start = (this.currentPage - 1) * this.perPage;
                            return this.filteredEmployees.slice(start, start + this.perPage);
                        },

                        // helpers
                        statusColor(st) {
                            switch ((st || '').toLowerCase()) {
                                case 'disponible': return '#16a34a';
                                case 'ocupado':    return '#ea580c';
                                case 'fuera':      return '#6b7280';
                                case 'descanso':   return '#0ea5e9';
                                default:           return '#64748b';
                            }
                        },

                        // lifecycle
                        async init() {
                            this.$watch('search', () => this.currentPage = 1);
                            this.$watch('filterStatus', () => this.currentPage = 1);
                            this.$watch('filterActive', () => this.currentPage = 1);
                            this.$watch('perPage', () => this.currentPage = 1);

                            await this.refreshEmployees();
                        },

                        // actions
                        async refreshEmployees() {
                            try {
                                const r = await fetch('/app/empleados/lista', {
                                    headers: { 'Accept': 'application/json' }
                                });
                                if (!r.ok) throw new Error('No se pudieron cargar los empleados');
                                const d = await r.json();
                                this.employees = (Array.isArray(d.empleados) ? d.empleados : []).map(e => ({
                                    ...e,
                                    activo: !!Number(e.activo),
                                    servicios_count: e.servicios_count ?? 0,
                                }));
                            } catch (e) {
                                console.error(e);
                                this.alertError('No se pudieron cargar los empleados');
                            }
                        },

                        newEmployee() {
                            this.editingEmployee = null;
                            this.employeeForm = {
                                nombre: '',
                                puesto: '',
                                color: '#8b5cf6',
                                status: '',
                                activo: true,
                            };
                            this.showEmployeeForm = true;
                        },

                        editEmployee(emp) {
                            this.editingEmployee = emp;
                            this.employeeForm = {
                                nombre: emp.nombre || '',
                                puesto: emp.puesto || '',
                                color: emp.color || '#8b5cf6',
                                status: emp.status || '',
                                activo: !!emp.activo,
                            };
                            this.showEmployeeForm = true;
                        },

                        closeEmployeeForm() {
                            this.showEmployeeForm = false;
                            this.editingEmployee = null;
                        },

                        async saveEmployee() {
                            try {
                                const isEdit = !!this.editingEmployee?.id;
                                const url = isEdit ? `/app/empleados/${this.editingEmployee.id}` : '/app/empleados';
                                const method = isEdit ? 'PUT' : 'POST';

                                const resp = await fetch(url, {
                                    method,
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify(this.employeeForm)
                                });

                                const data = await resp.json();
                                if (!resp.ok) throw new Error(data.error || 'Error al guardar');

                                if (isEdit) {
                                    const i = this.employees.findIndex(e => e.id === this.editingEmployee.id);
                                    if (i !== -1) this.employees[i] = {
                                        ...this.employees[i],
                                        ...data.empleado,
                                        activo: !!Number(data.empleado.activo)
                                    };
                                    this.toast('Empleado actualizado');
                                } else {
                                    this.employees.push({
                                        ...data.empleado,
                                        activo: !!Number(data.empleado.activo)
                                    });
                                    this.toast('Empleado creado');
                                }

                                this.closeEmployeeForm();
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al guardar');
                            }
                        },

                        async deleteEmployee(emp) {
                            const ok = await this.confirmAction({
                                title: `¿Desactivar a ${emp.nombre}?`,
                                text: 'Podrás reactivarlo más tarde.',
                                confirmText: 'Sí, desactivar',
                                icon: 'warning'
                            });
                            if (!ok) return;

                            try {
                                const resp = await fetch(`/app/empleados/${emp.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                });

                                let payload = {};
                                try { payload = await resp.json(); } catch (_) {}

                                if (!resp.ok) {
                                    throw new Error(payload.error || 'Error al desactivar');
                                }

                                const i = this.employees.findIndex(e => e.id === emp.id);
                                if (i !== -1) {
                                    if (payload.empleado) {
                                        this.employees[i] = {
                                            ...this.employees[i],
                                            ...payload.empleado,
                                            activo: !!Number(payload.empleado.activo)
                                        };
                                    } else {
                                        this.employees[i].activo = false;
                                        this.employees[i].status = this.employees[i].status || 'inactivo';
                                    }
                                }
                                this.toast('Empleado desactivado');
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al desactivar');
                            }
                        },

                        async restoreEmployee(emp) {
                            const ok = await this.confirmAction({
                                title: `¿Reincorporar a ${emp.nombre}?`,
                                confirmText: 'Sí, reincorporar',
                                icon: 'question'
                            });
                            if (!ok) return;

                            try {
                                const resp = await fetch(`/app/empleados/${emp.id}/activar`, {
                                    method: 'PUT',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                });

                                const data = await resp.json();
                                if (!resp.ok) throw new Error(data.error || 'Error al reactivar');

                                const i = this.employees.findIndex(e => e.id === emp.id);
                                if (i !== -1) {
                                    this.employees[i] = {
                                        ...this.employees[i],
                                        ...data.empleado,
                                        activo: !!Number(data.empleado.activo)
                                    };
                                }

                                // Si se está viendo "Eliminados", cambiamos a "Activos" para que lo vean listo
                                if (this.filterActive === 'eliminados') {
                                    this.filterActive = 'activos';
                                    this.currentPage = 1;
                                }

                                this.toast('Empleado reactivado');
                            } catch (e) {
                                console.error(e);
                                this.alertError(e.message || 'Error al reactivar');
                            }
                        },
                    }));
                });
            </script>
        </body>

        </html>
    </div>
</x-app-layout>
