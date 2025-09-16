{{-- resources/views/menu/citas.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl sm:text-2xl text-black">
            {{ __('Citas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        {{-- Si tu layout ya inyecta estas librerías, elimina estas líneas --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            :root {
                --sab: env(safe-area-inset-bottom);
            }

            [x-cloak] {
                display: none !important;
            }

            .card-hover {
                transition: all .25s ease;
            }

            .card-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 24px rgba(0, 0, 0, .08);
            }

            .gradient-primary {
                background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            }

            .input-modern {
                transition: all .2s ease;
                border: 1px solid #e5e7eb;
                outline: none;
            }

            .input-modern:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
            }

            .chip {
                display: inline-flex;
                align-items: center;
                padding: .35rem .65rem;
                border-radius: 9999px;
                font-size: .7rem;
                font-weight: 600;
                color: white;
                white-space: nowrap;
            }

            .backdrop {
                background: rgba(0, 0, 0, .45);
            }

            .pb-safe {
                padding-bottom: calc(1rem + var(--sab));
            }

            .mb-safe {
                margin-bottom: var(--sab);
            }

            .shadow-soft {
                box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            }

            .touch-target {
                min-height: 44px;
            }

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>

        <div x-data="citasData()" x-cloak class="container mx-auto px-3 md:px-6 max-w-7xl relative">

            <!-- Header -->
            <section class="gradient-primary text-white rounded-2xl p-5 sm:p-6 shadow-soft mb-5 sm:mb-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">Agendar cita</h1>
                        <p class="text-slate-300">Agrega varios servicios al carrito y agenda.</p>
                    </div>
                    <div class="flex gap-8 text-center">
                        <div>
                            <div class="text-2xl font-bold" x-text="services.length"></div>
                            <div class="text-slate-300 text-xs">Servicios</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold" x-text="categories.length"></div>
                            <div class="text-slate-300 text-xs">Categorías</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Barra resumen flotante (móvil) -->
            <div class="fixed inset-x-0 bottom-0 z-40 lg:hidden" x-show="cart.length > 0" x-transition.opacity
                aria-live="polite">
                <div class="mx-3 mb-3 mb-safe rounded-2xl bg-white/90 backdrop-blur border shadow-xl">
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-emerald-50 p-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.53 17h8.94M7 13l1.2-6M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </div>
                            <div class="leading-tight">
                                <div class="text-sm font-semibold"
                                    x-text="cartUnits + ' item' + (cartUnits !== 1 ? 's' : '')"></div>
                                <div class="text-xs text-gray-500">
                                    Total: <span class="font-semibold text-emerald-600"
                                        x-text="'$'+formatMoney(total)"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="isCartOpen = true; cartTab='items'"
                                class="touch-target inline-flex items-center px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50"
                                aria-label="Ver carrito">
                                Ver carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón FAB carrito (móvil) -->
            <button x-show="cart.length > 0" x-transition.scale @click="isCartOpen = true; cartTab='items'"
                class="lg:hidden fixed z-50 right-4 bottom-[88px] sm:bottom-20 inline-flex items-center justify-center rounded-full shadow-xl bg-slate-900 text-white w-14 h-14"
                aria-label="Abrir carrito">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.53 17h8.94M7 13l1.2-6M10 21a1 1 0 100-2 1 1 0 000 2z" />
                </svg>
                <span
                    class="absolute -top-1 -right-1 min-w-[1.5rem] h-6 px-1 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center"
                    x-text="cartUnits" aria-label="Cantidad en carrito"></span>
            </button>

            <!-- Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
                <!-- Col izquierda (servicios) -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-5">
                    <!-- Filtros -->
                    <section class="bg-white rounded-2xl p-4 sm:p-5 shadow-soft">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input x-model="search" type="text" placeholder="Buscar servicios..."
                                    class="input-modern w-full pl-10 pr-3 py-3 rounded-xl"
                                    aria-label="Buscar servicios">
                            </div>

                            <div class="md:col-span-2">
                                <!-- Chips de categorías (scrollable en móvil) -->
                                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                                    <button @click="filterCategory = ''; currentPage = 1"
                                        :class="!filterCategory ? 'bg-slate-900 text-white' :
                                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="chip !px-3 !py-2 rounded-full">Todas</button>
                                    <template x-for="c in categories" :key="c.id">
                                        <button class="chip !px-3 !py-2 rounded-full"
                                            :style="(Number(filterCategory) === Number(c.id)) ? 'background:' + (
                                                getCategoryColorById(c.id)) : ''"
                                            :class="Number(filterCategory) === Number(c.id) ? 'text-white' :
                                                'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                            @click="filterCategory = c.id; currentPage=1" x-text="c.nombre"></button>
                                    </template>
                                </div>
                            </div>

                            <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-3">
                                <select x-model.number="perPage" class="input-modern w-full px-3 py-3 rounded-xl">
                                    <option :value="6">6 por página</option>
                                    <option :value="12">12 por página</option>
                                    <option :value="18">18 por página</option>
                                </select>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <span>Resultados: </span>
                                    <span class="font-semibold text-gray-700" x-text="filteredServices.length"></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Grid de servicios (AHORA CON COLORES POR CATEGORÍA) -->
                    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
                        <template x-for="s in paginatedServices" :key="s.id">
                            <article class="relative rounded-2xl p-4 sm:p-5 shadow-soft border card-hover"
                                :style="cardStyleByCategory(s.categoria_id)">
                                <!-- franja lateral de color -->
                                <span class="absolute inset-y-0 left-0 w-1.5 rounded-l-2xl"
                                    :style="'background:' + rgba(getCategoryColorById(s.categoria_id), .85)"
                                    aria-hidden="true"></span>

                                <div class="flex items-start justify-between mb-3">
                                    <span class="chip" :style="'background:' + getCategoryColorById(s.categoria_id)">
                                        <span class="w-2 h-2 bg-white/90 rounded-full mr-2"></span>
                                        <span x-text="getCategoryNameById(s.categoria_id)"></span>
                                    </span>
                                    <span class="text-emerald-700 font-bold text-lg"
                                        x-text="'$'+formatMoney(s.precio)"></span>
                                </div>

                                <h3 class="font-semibold text-gray-900 text-base sm:text-lg" x-text="s.nombre"></h3>

                                <div class="mt-2 text-sm text-gray-700 space-y-1">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span x-text="s.duracion_minutos + ' min'"></span>
                                    </div>

                                    <template x-if="s.empleado_id && !isEmployeeInactive(s.empleado_id)">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="truncate" x-text="s.empleado_nombre"></span>
                                        </div>
                                    </template>
                                    <template x-if="s.empleado_id && isEmployeeInactive(s.empleado_id)">
                                        <div class="flex items-center text-red-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1 1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="text-xs font-semibold">
                                                <span x-text="s.empleado_nombre"></span> inactivo
                                            </span>
                                        </div>
                                    </template>
                                </div>

                                <button @click="addToCart(s)"
                                    class="mt-4 w-full py-2.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 touch-target">
                                    Agregar
                                </button>
                            </article>
                        </template>

                        <!-- Estado vacío -->
                        <template x-if="!paginatedServices.length">
                            <div class="col-span-full bg-white rounded-2xl p-10 text-center text-gray-500 shadow-soft">
                                No se encontraron servicios con esos filtros.
                            </div>
                        </template>
                    </section>

                    <!-- Paginación servicios -->
                    <section
                        class="bg-white rounded-2xl p-4 sm:p-5 shadow-soft flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="text-sm text-gray-600">
                            Mostrando
                            <span class="font-semibold" x-text="((currentPage - 1) * perPage) + 1"></span> -
                            <span class="font-semibold"
                                x-text="Math.min(currentPage * perPage, filteredServices.length)"></span>
                            de <span class="font-semibold" x-text="filteredServices.length"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-2 border rounded-xl hover:bg-gray-50 disabled:opacity-40"
                                :disabled="currentPage === 1"
                                @click="currentPage=Math.max(1,currentPage-1)">←</button>
                            <span class="text-sm font-semibold" x-text="currentPage + ' / ' + totalPages"></span>
                            <button class="px-3 py-2 border rounded-xl hover:bg-gray-50 disabled:opacity-40"
                                :disabled="currentPage === totalPages"
                                @click="currentPage=Math.min(totalPages,currentPage+1)">→</button>
                        </div>
                    </section>
                </div>

                <!-- Col derecha (carrito - Desktop con secciones) -->
                <aside class="hidden lg:block">
                    <div class="bg-white rounded-2xl p-5 shadow-soft sticky top-6 space-y-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Carrito</h3>
                            <button x-show="cart.length" @click="vaciarCarrito" type="button"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 text-sm font-medium bg-white
           hover:bg-rose-50 hover:border-rose-300 hover:text-rose-800
           active:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-400/50 focus:ring-offset-0
           transition shadow-sm"
                                title="Vaciar carrito" aria-label="Vaciar carrito">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7H5m4 0V5a2 2 0 012-2h2a2 2 0 012 2v2m-7 0l1 12a2 2 0 002 2h4a2 2 0 002-2l1-12M10 11v6m4-6v6" />
                                </svg>
                                Vaciar
                            </button>
                        </div>


                        <!-- 1) Servicios seleccionados -->
                        <section class="rounded-xl border p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-800">Servicios seleccionados</h4>
                                <span class="text-xs text-gray-500"
                                    x-text="cartUnits + ' item' + (cartUnits!==1?'s':'')"></span>
                            </div>

                            <template x-if="cart.length === 0">
                                <p class="text-sm text-gray-500">No hay servicios agregados.</p>
                            </template>

                            <div class="space-y-3" x-show="cart.length">
                                <template x-for="(it,idx) in cart" :key="idx">
                                    <div class="border rounded-xl p-3">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="min-w-0">
                                                <div class="font-semibold truncate" x-text="it.nombre"></div>
                                                <div class="text-xs text-gray-500"
                                                    x-text="getCategoryNameById(it.categoria_id) + ' · ' + it.duracion_minutos + ' min'">
                                                </div>
                                            </div>
                                            <button @click="removeFromCart(idx)"
                                                class="text-red-600 text-sm hover:underline flex-shrink-0">Quitar</button>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 mt-2">
                                            <div>
                                                <label class="text-xs text-gray-500">Cantidad</label>
                                                <input type="number" min="1" x-model.number="it.cantidad"
                                                    @input.debounce.300ms="persistState"
                                                    class="input-modern w-full px-2 py-2 rounded-lg">
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500">Desc. x servicio ($)</label>
                                                <input type="number" min="0" step="0.01"
                                                    x-model.number="it.descuento_mxn"
                                                    @input.debounce.300ms="persistState"
                                                    class="input-modern w-full px-2 py-2 rounded-lg">
                                            </div>

                                        </div>

                                        <div class="mt-2 text-sm flex justify-between">
                                            <span>Subtotal</span>
                                            <span class="font-semibold"
                                                x-text="'$'+formatMoney(lineTotal(it))"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </section>

                        <!-- 2) Cliente & Agenda -->
                        <section class="rounded-xl border p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Cliente &amp; Agenda</h4>

                            <div class="space-y-3">
                                <!-- Cliente seleccionado -->
                                <div x-show="appointment.cliente_id"
                                    class="mt-3 p-3 rounded-lg border border-emerald-200 bg-emerald-50 flex items-center gap-3 shadow-sm">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-emerald-600 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wide">
                                            Cliente seleccionado</p>
                                        <p class="text-sm font-bold text-gray-900 truncate"
                                            x-text="(clients.find(cc => Number(cc.id) === Number(appointment.cliente_id)) || {}).nombre || '—'">
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-semibold">Fecha</label>
                                        <input type="date" x-model="appointment.fecha"
                                            @input.debounce.300ms="persistState"
                                            class="input-modern w-full px-3 py-2 rounded-lg mt-1">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-semibold">Hora inicio</label>
                                        <input type="time" x-model="appointment.hora_inicio"
                                            @input.debounce.300ms="persistState"
                                            class="input-modern w-full px-3 py-2 rounded-lg mt-1">
                                    </div>
                                </div>

                                <!-- Hora fin automática -->
                                <div class="mt-2">
                                    <label class="text-xs text-gray-600 font-semibold">Hora fin (automática)</label>
                                    <input type="time" :value="horaFin" readonly tabindex="-1"
                                        class="input-modern w-full px-3 py-2 rounded-lg mt-1 bg-gray-50 text-gray-700 cursor-not-allowed">
                                    <p class="text-[11px] text-gray-500 mt-1" x-show="appointment.hora_inicio">
                                        Se calcula con <span class="font-medium"
                                            x-text="totalMinutes + ' min'"></span> de servicios.
                                    </p>
                                </div>


                                <div>
                                    <label class="text-xs text-gray-600 font-semibold">Notas</label>
                                    <textarea rows="2" x-model="appointment.notas" @input.debounce.500ms="persistState"
                                        class="input-modern w-full px-3 py-2 rounded-lg mt-1"></textarea>
                                </div>
                            </div>
                        </section>

                        <!-- 3) Descuentos -->
                        <section class="rounded-xl border p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Descuentos</h4>

                            <div class="grid grid-cols-1 gap-2">
                                <div>
                                    <label class="text-xs text-gray-600 font-semibold">Descuento General ($)</label>
                                    <input type="number" min="0" step="0.01"
                                        x-model.number="appointment.descuento_mxn"
                                        @input.debounce.300ms="persistState"
                                        class="input-modern w-full px-3 py-2 rounded-lg mt-1">
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        Se aplica sobre el <strong>subtotal</strong> después de los descuentos x
                                        servicio.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!-- 4) Resumen -->
                        <section class="rounded-xl border p-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Resumen</h4>

                            <dl class="text-sm grid grid-cols-2 gap-y-2">
                                <dt class="text-gray-600">Bruto (sin desc.)</dt>
                                <dd class="text-right font-semibold" x-text="'$'+formatMoney(gross)"></dd>

                                <dt class="text-gray-600">Desc. x servicios</dt>
                                <dd class="text-right font-semibold text-rose-600"
                                    x-text="'-$'+formatMoney(lineDiscounts)"></dd>

                                <dt class="text-gray-600">Subtotal</dt>
                                <dd class="text-right font-semibold" x-text="'$'+formatMoney(subtotal)"></dd>

                                <dt class="text-gray-600">Desc. General</dt>
                                <dd class="text-right font-semibold text-rose-600"
                                    x-text="'-$'+formatMoney(orderDiscountApplied)"></dd>

                                <dt class="text-gray-600">Duración</dt>
                                <dd class="text-right font-semibold" x-text="totalMinutes + ' min'"></dd>

                                <dt class="text-gray-800 mt-1 border-t pt-2 font-semibold">Total</dt>
                                <dd class="text-right mt-1 border-t pt-2 font-bold text-emerald-600"
                                    x-text="'$'+formatMoney(total)"></dd>
                            </dl>

                            <button @click="saveAppointment"
                                class="w-full mt-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 disabled:opacity-50"
                                :disabled="cart.length === 0">
                                Guardar cita
                            </button>
                        </section>
                    </div>
                </aside>
            </div>

            <!-- Bottom Sheet Carrito (móvil con pestañas) -->
            <div x-show="isCartOpen" class="fixed inset-0 z-50 lg:hidden" aria-modal="true" role="dialog">
                <!-- Backdrop -->
                <div class="backdrop absolute inset-0" x-transition.opacity @click="isCartOpen = false"></div>

                <!-- Sheet -->
                <div x-show="isCartOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                    x-transition:leave-end="translate-y-full"
                    class="absolute inset-x-0 bottom-0 bg-white rounded-t-3xl shadow-2xl max-h-[88vh] flex flex-col"
                    @keydown.escape.window="isCartOpen=false">
                    <!-- Handle + acciones -->
                    <div class="flex justify-center pt-3 relative">
                        <span class="w-12 h-1.5 bg-gray-300 rounded-full"></span>

                        <button x-show="cart.length" @click="vaciarCarrito" type="button"
                            class="absolute right-3 top-1.5 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
           text-rose-700 text-xs font-medium bg-rose-50/90 border border-rose-200
           hover:bg-rose-100 hover:border-rose-300 hover:text-rose-800
           active:bg-rose-200 focus:outline-none focus:ring-2 focus:ring-rose-400/40
           transition shadow-sm"
                            title="Vaciar carrito" aria-label="Vaciar carrito">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7H5m4 0V5a2 2 0 012-2h2a2 2 0 012 2v2m-7 0l1 12a2 2 0 002 2h4a2 2 0 002-2l1-12M10 11v6m4-6v6" />
                            </svg>
                            Vaciar
                        </button>
                    </div>


                    <div class="px-5 pb-2 pt-3 border-b flex items-center justify-between">
                        <h3 class="text-base font-bold">Carrito</h3>
                        <button @click="isCartOpen=false" class="p-2 rounded-lg hover:bg-gray-100"
                            aria-label="Cerrar">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="px-5 pt-3">
                        <div class="grid grid-cols-4 bg-gray-100 rounded-xl p-1">
                            <button @click="cartTab='items'"
                                :class="cartTab === 'items' ? 'bg-white shadow text-gray-900' : 'text-gray-600'"
                                class="py-2 rounded-lg text-sm font-medium">Servicios</button>
                            <button @click="cartTab='cliente'"
                                :class="cartTab === 'cliente' ? 'bg-white shadow text-gray-900' : 'text-gray-600'"
                                class="py-2 rounded-lg text-sm font-medium">Cliente</button>
                            <button @click="cartTab='notas'"
                                :class="cartTab === 'notas' ? 'bg-white shadow text-gray-900' : 'text-gray-600'"
                                class="py-2 rounded-lg text-sm font-medium">Notas</button>
                            <button @click="cartTab='resumen'"
                                :class="cartTab === 'resumen' ? 'bg-white shadow text-gray-900' : 'text-gray-600'"
                                class="py-2 rounded-lg text-sm font-medium">Resumen</button>
                        </div>
                    </div>

                    <!-- Contenido scroll -->
                    <div class="flex-1 overflow-y-auto px-5 py-3">
                        <!-- Tab: Servicios (modal) — REEMPLAZAR ESTE BLOQUE -->
                        <div x-show="cartTab==='items'">
                            <template x-if="cart.length === 0">
                                <p class="text-sm text-gray-500">No hay servicios agregados.</p>
                            </template>

                            <div class="space-y-3" x-show="cart.length">
                                <template x-for="(it,idx) in cart" :key="idx">
                                    <article class="relative rounded-xl p-3 border shadow-soft"
                                        :style="cardStyleByCategory(it.categoria_id)">
                                        <!-- franja lateral de color -->
                                        <span class="absolute inset-y-0 left-0 w-1.5 rounded-l-xl"
                                            :style="'background:' + rgba(getCategoryColorById(it.categoria_id), .85)"
                                            aria-hidden="true"></span>

                                        <!-- Encabezado -->
                                        <div class="flex justify-between items-start gap-3 mb-2">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="chip"
                                                        :style="'background:' + getCategoryColorById(it.categoria_id)">
                                                        <span class="w-2 h-2 bg-white/90 rounded-full mr-2"></span>
                                                        <span x-text="getCategoryNameById(it.categoria_id)"></span>
                                                    </span>
                                                    <h4 class="font-semibold text-gray-900 truncate"
                                                        x-text="it.nombre"></h4>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1"
                                                    x-text="it.duracion_minutos + ' min'"></div>
                                            </div>

                                            <button @click="removeFromCart(idx)"
                                                class="text-red-600 text-sm hover:underline flex-shrink-0">
                                                Quitar
                                            </button>
                                        </div>

                                        <!-- Campos -->
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="text-xs text-gray-500">Cantidad</label>
                                                <input type="number" min="1" x-model.number="it.cantidad"
                                                    @input.debounce.300ms="persistState"
                                                    class="input-modern bg-white w-full px-2 py-2 rounded-lg">
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500">Desc. x servicio ($)</label>
                                                <input type="number" min="0" step="0.01"
                                                    x-model.number="it.descuento_mxn"
                                                    @input.debounce.300ms="persistState"
                                                    class="input-modern bg-white w-full px-2 py-2 rounded-lg">
                                            </div>

                                        </div>

                                        <!-- Subtotal -->
                                        <div class="mt-2 text-sm flex justify-between">
                                            <span>Subtotal</span>
                                            <span class="font-semibold"
                                                x-text="'$'+formatMoney(lineTotal(it))"></span>
                                        </div>
                                    </article>
                                </template>
                            </div>
                        </div>

                        <!-- Tab: Cliente -->
                        <div x-show="cartTab==='cliente'">
                            <div class="space-y-3">
                                <!-- Cliente seleccionado -->
                                <div x-show="appointment.cliente_id"
                                    class="mt-3 p-3 rounded-lg border border-emerald-200 bg-emerald-50 flex items-center gap-3 shadow-sm">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-emerald-600 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wide">
                                            Cliente seleccionado</p>
                                        <p class="text-sm font-bold text-gray-900 truncate"
                                            x-text="(clients.find(cc => Number(cc.id) === Number(appointment.cliente_id)) || {}).nombre || '—'">
                                        </p>
                                    </div>
                                </div>


                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-600 font-semibold">Fecha</label>
                                        <input type="date" x-model="appointment.fecha"
                                            @input.debounce.300ms="persistState"
                                            class="input-modern w-full px-3 py-2 rounded-lg mt-1">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-600 font-semibold">Hora inicio</label>
                                        <input type="time" x-model="appointment.hora_inicio"
                                            @input.debounce.300ms="persistState"
                                            class="input-modern w-full px-3 py-2 rounded-lg mt-1">
                                    </div>
                                </div>

                                <!-- Hora fin automática (móvil) -->
                                <div class="mt-2">
                                    <label class="text-xs text-gray-600 font-semibold">Hora fin (automática)</label>
                                    <input type="time" :value="horaFin" readonly tabindex="-1"
                                        class="input-modern w-full px-3 py-2 rounded-lg mt-1 bg-gray-50 text-gray-700 cursor-not-allowed">
                                    <p class="text-[11px] text-gray-500 mt-1" x-show="appointment.hora_inicio">
                                        Termina a <span class="font-medium" x-text="horaFin || '—'"></span> (duración
                                        <span x-text="totalMinutes + ' min'"></span>).
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- Tab: Notas -->
                        <div x-show="cartTab==='notas'">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold">Notas</label>
                                <textarea rows="6" x-model="appointment.notas" @input.debounce.500ms="persistState"
                                    class="input-modern w-full px-3 py-2 rounded-lg mt-1"></textarea>
                            </div>
                            <div class="mt-4">
                                <label class="text-xs text-gray-600 font-semibold">Descuento total ($)</label>
                                <input type="number" min="0" step="0.01"
                                    x-model.number="appointment.descuento_mxn" @input.debounce.300ms="persistState"
                                    class="input-modern w-full px-3 py-2 rounded-lg mt-1">
                                <p class="text-[11px] text-gray-500 mt-1">
                                    Se aplica sobre el <strong>subtotal</strong> después de los descuentos de línea.
                                </p>
                            </div>
                        </div>

                        <!-- Tab: Resumen -->
                        <div x-show="cartTab==='resumen'">
                            <dl class="text-sm grid grid-cols-2 gap-y-2">
                                <dt class="text-gray-600">Bruto (sin desc.)</dt>
                                <dd class="text-right font-semibold" x-text="'$'+formatMoney(gross)"></dd>

                                <dt class="text-gray-600">Desc. x Servicios</dt>
                                <dd class="text-right font-semibold text-rose-600"
                                    x-text="'-$'+formatMoney(lineDiscounts)"></dd>

                                <dt class="text-gray-600">Subtotal</dt>
                                <dd class="text-right font-semibold" x-text="'$'+formatMoney(subtotal)"></dd>

                                <dt class="text-gray-600">Desc. General</dt>
                                <dd class="text-right font-semibold text-rose-600"
                                    x-text="'-$'+formatMoney(orderDiscountApplied)"></dd>

                                <dt class="text-gray-600">Duración</dt>
                                <dd class="text-right font-semibold" x-text="totalMinutes + ' min'"></dd>

                                <dt class="text-gray-800 mt-1 border-t pt-2 font-semibold">Total</dt>
                                <dd class="text-right mt-1 border-t pt-2 font-bold text-emerald-600"
                                    x-text="'$'+formatMoney(total)"></dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Footer acciones -->
                    <div class="border-t px-5 py-3 bg-white rounded-b-3xl pb-safe">
                        <button @click="saveAppointment"
                            class="w-full py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 disabled:opacity-50 touch-target"
                            :disabled="cart.length === 0">
                            Guardar cita
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('citasData', () => ({


                    // --- Prefill desde query/localStorage ---
                    prefilledClientId: null,

                    getQueryParam(name) {
                        try {
                            return new URLSearchParams(window.location.search).get(name);
                        } catch {
                            return null;
                        }
                    },
                    setPrefilledClientFromURL() {
                        const id = Number(this.getQueryParam('cliente_id') || 0);
                        if (id > 0) this.prefilledClientId = id;
                    },
                    applyPrefilledClient() {
                        if (!this.prefilledClientId) return;

                        // Asigna el cliente al modelo (coincide con :value numérico de las opciones)
                        this.appointment.cliente_id = Number(this.prefilledClientId);
                        this.persistState();

                        // Asegura que el <select> refleje la selección cuando ya existen las opciones
                        this.$nextTick(() => {
                            const el = this.$refs.selectCliente;
                            if (el) el.value = String(this.prefilledClientId);
                        });

                        // Evita re-aplicar
                        this.prefilledClientId = null;
                    },


                    // --- Tiempo: utilidades para calcular hora fin ---
                    timeAddMinutes(hhmm, minutes) {
                        // hhmm esperado "HH:MM" (24h)
                        if (!hhmm || !/^\d{2}:\d{2}$/.test(hhmm)) return '';
                        const [h, m] = hhmm.split(':').map(Number);
                        const d = new Date(2000, 0, 1, h, m);
                        d.setMinutes(d.getMinutes() + Number(minutes || 0));
                        const hh = String(d.getHours()).padStart(2, '0');
                        const mm = String(d.getMinutes()).padStart(2, '0');
                        return `${hh}:${mm}`;
                    },
                    get horaFin() {
                        if (!this.appointment.hora_inicio) return '';
                        const mins = Number(this.totalMinutes ||
                            0); // ya tienes totalMinutes en tu código
                        if (!mins) return this.appointment.hora_inicio;
                        return this.timeAddMinutes(this.appointment.hora_inicio, mins);
                    },



                    // ===== estado / UI
                    isCartOpen: false,
                    cartTab: 'items', // items | cliente | notas | resumen
                    search: '',
                    filterCategory: '',
                    perPage: 9,
                    currentPage: 1,

                    // ===== datos
                    services: [],
                    categories: [],
                    employees: [],
                    clients: [],

                    cart: [],
                    appointment: {
                        cliente_id: '',
                        fecha: '',
                        hora_inicio: '',
                        notas: '',
                        descuento_mxn: 0,
                    },

                    // ===== Colores (fallback si la categoría no trae color)
                    fallbackColors: ['#6366F1', '#10B981', '#F59E0B', '#EC4899', '#0EA5E9', '#22C55E',
                        '#F43F5E', '#8B5CF6', '#06B6D4', '#84CC16'
                    ],

                    hashId(val) {
                        const s = String(val ?? '');
                        let h = 0;
                        for (let i = 0; i < s.length; i++) {
                            h = ((h << 5) - h) + s.charCodeAt(i);
                            h |= 0;
                        }
                        return Math.abs(h);
                    },
                    hexToRgb(hex) {
                        try {
                            let c = (hex || '#111827').replace('#', '').trim();
                            if (c.length === 3) c = c.split('').map(x => x + x).join('');
                            const num = parseInt(c, 16);
                            return {
                                r: (num >> 16) & 255,
                                g: (num >> 8) & 255,
                                b: num & 255
                            };
                        } catch {
                            return {
                                r: 17,
                                g: 24,
                                b: 39
                            };
                        }
                    },
                    rgba(hex, a = 1) {
                        const {
                            r,
                            g,
                            b
                        } = this.hexToRgb(hex);
                        return `rgba(${r}, ${g}, ${b}, ${a})`;
                    },

                    // ===== helpers categorías
                    getCategoryById(id) {
                        return this.categories.find(c => Number(c.id) === Number(id));
                    },
                    getCategoryNameById(id) {
                        const c = this.getCategoryById(id);
                        return c ? c.nombre : 'Sin categoría';
                    },
                    getCategoryColorById(id) {
                        const c = this.getCategoryById(id);
                        if (c && c.color) return c.color;
                        const idx = this.hashId(id) % this.fallbackColors.length;
                        return this.fallbackColors[idx];
                    },
                    cardStyleByCategory(catId) {
                        const color = this.getCategoryColorById(catId);
                        // Degradado suave + borde tinte de la categoría
                        return `background: linear-gradient(135deg, ${this.rgba(color, .10)} 0%, ${this.rgba(color, .04)} 100%); border-color: ${this.rgba(color, .25)};`;
                    },

                    // === WhatsApp helpers (config + utilidades)
                    WHATSAPP_DEFAULT_CC: '52', // 🇲🇽 Prefijo por defecto. Cambia si es otro país

                    getClientById(id) {
                        return this.clients.find(c => Number(c.id) === Number(id));
                    },
                    getClientPhoneById(id) {
                        const c = this.getClientById(id) || {};
                        const keys = ['telefono_whatsapp', 'whatsapp', 'telefono', 'celular', 'movil',
                            'phone', 'mobile'
                        ];
                        for (const k of keys) {
                            if (c[k]) return String(c[k]);
                        }
                        return '';
                    },
                    sanitizePhoneForWhatsapp(phone) {
                        const digits = String(phone || '').replace(/\D/g, '');
                        if (!digits) return '';
                        // Si ya tiene prefijo o es largo, úsalo tal cual
                        if (digits.startsWith(this.WHATSAPP_DEFAULT_CC) || digits.length > 11)
                            return digits;
                        // Si es un número MX de 10 dígitos, prepende prefijo
                        if (digits.length === 10) return this.WHATSAPP_DEFAULT_CC + digits;
                        return digits;
                    },
                    formatDateHuman(fecha, hora) {
                        if (!fecha) return '';
                        const [y, m, d] = fecha.split('-');
                        const dd = String(d).padStart(2, '0');
                        const mm = String(m).padStart(2, '0');
                        return `${dd}/${mm}/${y}${hora ? ' ' + hora : ''}`;
                    },
                    buildWhatsappMessage() {
                        const cli = this.getClientById(this.appointment.cliente_id) || {};
                        const nombre = cli.nombre || 'Cliente';
                        const fechaHora = this.formatDateHuman(this.appointment.fecha, this.appointment
                            .hora_inicio);

                        // Líneas por servicio:
                        // - Si cantidad > 1 y SIN desc. -> "— $precio_unit → $total"
                        // - Si cantidad > 1 y CON desc. -> "— $precio_unit (desc. $X) → $total"
                        // - Si cantidad = 1 y SIN desc. -> "—  $total"
                        // - Si cantidad = 1 y CON desc. -> "— $precio_unit (desc. $X) → $total"
                        const lines = this.cart.map(it => {
                            const cant = Math.max(1, Number(it.cantidad || 1));
                            const unit = Number(it.precio || 0);
                            const bruto = cant * unit;
                            const descIn = Math.max(0, Number(it.descuento_mxn || 0));
                            const desc = Math.min(descIn, bruto); // clamp
                            const total = this.lineTotal(it); // ya clamped

                            if (cant > 1) {
                                if (desc > 0) {
                                    // Ej: "• Servicio x2 — $600.00 (desc. $100.00) → $1,100.00"
                                    return `• ${it.nombre} x${cant} — $${this.formatMoney(unit)} (desc. $${this.formatMoney(desc)}) → $${this.formatMoney(total)}`;
                                } else {
                                    // Ej: "• Servicio x2 — $600.00 → $1,200.00"
                                    return `• ${it.nombre} x${cant} — $${this.formatMoney(unit)} → $${this.formatMoney(total)}`;
                                }
                            } else {
                                if (desc > 0) {
                                    // Ej: "• Servicio x1 — $600.00 (desc. $100.00) → $500.00"
                                    return `• ${it.nombre} — $${this.formatMoney(unit)} (desc. $${this.formatMoney(desc)}) → $${this.formatMoney(total)}`;
                                } else {
                                    // Ej: "• Servicio x1 —  $600.00"
                                    return `• ${it.nombre} —  $${this.formatMoney(total)}`;
                                }
                            }
                        }).join('\n');

                        // Desglose al final (como pediste: bruto, desc. x servicios, subtotal intermedio, desc. general, total)
                        const brutoOrden = this.gross; // sin NINGÚN descuento
                        const descLineas = this.lineDiscounts; // suma descuentos de línea
                        const subTrasLinea = this.subtotal; // bruto - desc. líneas
                        const descGeneral = this.orderDiscountApplied; // descuento adicional (orden)
                        const totalFinal = this.total;

                        const totales = [];
                        totales.push(`Subtotal: $${this.formatMoney(brutoOrden)}`);

                        if (descLineas > 0 && descGeneral > 0) {
                            totales.push(`Descuento x servicios: -$${this.formatMoney(descLineas)}`);
                            totales.push(`Subtotal: $${this.formatMoney(subTrasLinea)}`);
                            totales.push(`Descuento general: -$${this.formatMoney(descGeneral)}`);
                        } else if (descLineas > 0) {
                            totales.push(`Descuento x servicios: -$${this.formatMoney(descLineas)}`);
                        } else if (descGeneral > 0) {
                            totales.push(`Descuento general: -$${this.formatMoney(descGeneral)}`);
                        }

                        totales.push(`TOTAL: $${this.formatMoney(totalFinal)}`);

                        const notas = (this.appointment.notas || '').trim();
                        const notasLine = notas ? `\n\nNotas: ${notas}` : '';

                        return `Hola ${nombre}, tu cita fue creada ✅
  
🗓 Fecha y hora: ${fechaHora}

🧾 Servicios:
${lines}
⏱ Duración total: ${this.totalMinutes} min

${totales.join('\n')}${notasLine}

Llegar con 10 minutos de anticipación de lo contrario su cita será cancelada. Gracias.`;
                    },

                    buildWhatsappUrl() {
                        const raw = this.getClientPhoneById(this.appointment.cliente_id);
                        const phone = this.sanitizePhoneForWhatsapp(raw);
                        const text = this.buildWhatsappMessage();
                        return phone ? `https://wa.me/${phone}?text=${encodeURIComponent(text)}` : '';
                    },

                    // ===== helpers empleados / clientes
                    get activeEmployees() {
                        return this.employees.filter(e => !!Number(e.activo));
                    },
                    get activeClients() {
                        return this.clients.filter(c => !!Number(c.activo));
                    },

                    isEmployeeInactive(empId) {
                        const emp = this.employees.find(e => Number(e.id) === Number(empId));
                        return emp ? !Boolean(Number(emp.activo)) : false;
                    },

                    // ===== dinero / formateo
                    formatMoney(v) {
                        const n = Number(v ?? 0);
                        return n.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },

                    // Cálculos por ítem
                    lineTotal(it) {
                        const cant = Math.max(1, Number(it.cantidad || 1));
                        const precio = Number(it.precio || 0);
                        const desc = Math.max(0, Number(it.descuento_mxn || 0));
                        return Math.max(0, cant * precio - Math.min(desc, cant * precio));
                    },

                    // Totales detallados
                    get gross() {
                        return this.cart.reduce((acc, it) =>
                            acc + Math.max(1, Number(it.cantidad || 1)) * Number(it.precio || 0), 0);
                    },
                    get lineDiscounts() {
                        return this.cart.reduce((acc, it) => {
                            const cant = Math.max(1, Number(it.cantidad || 1));
                            const precio = Number(it.precio || 0);
                            const desc = Math.max(0, Number(it.descuento_mxn || 0));
                            return acc + Math.min(desc, cant * precio);
                        }, 0);
                    },
                    get subtotal() {
                        return Math.max(0, this.gross - this.lineDiscounts);
                    },
                    get orderDiscountApplied() {
                        const d = Math.max(0, Number(this.appointment.descuento_mxn || 0));
                        return Math.min(this.subtotal, d);
                    },
                    get total() {
                        return Math.max(0, this.subtotal - this.orderDiscountApplied);
                    },
                    get totalMinutes() {
                        return this.cart.reduce((acc, it) => acc + Math.max(1, Number(it.cantidad ||
                            1)) * Number(it.duracion_minutos || 0), 0);
                    },
                    get cartUnits() {
                        return this.cart.reduce((acc, it) => acc + Math.max(1, Number(it.cantidad ||
                            1)), 0);
                    },

                    // ===== servicios filtrados/paginados
                    get filteredServices() {
                        const q = (this.search || '').toLowerCase();
                        return this.services.filter(s => {
                            const matchesQ = !q ||
                                (s.nombre || '').toLowerCase().includes(q) ||
                                (s.empleado_nombre || '').toLowerCase().includes(q);
                            const matchesCat = !this.filterCategory ||
                                Number(s.categoria_id) === Number(this.filterCategory);
                            return matchesQ && matchesCat;
                        });
                    },
                    get totalPages() {
                        return Math.max(1, Math.ceil(this.filteredServices.length / this.perPage));
                    },
                    get paginatedServices() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.filteredServices.slice(start, start + this.perPage);
                    },

                    // ===== lifecycle
                    async init() {
                        this.$watch('isCartOpen', v => {
                            document.documentElement.classList.toggle('overflow-hidden', v);
                            document.body.classList.toggle('overflow-hidden', v);
                        });
                        this.$watch('search', () => this.currentPage = 1);
                        this.$watch('filterCategory', () => this.currentPage = 1);
                        this.$watch('perPage', () => this.currentPage = 1);

                        // 1) Restaurar posible estado previo
                        this.restoreState();

                        // 2) Leer ?cliente_id=... de la URL (tiene prioridad sobre lo restaurado)
                        this.setPrefilledClientFromURL();

                        // 3) Cargar catálogos
                        await this.loadCategories();
                        await this.loadEmployees();
                        await this.loadClients();
                        await this.loadServices();

                        // 4) Aplicar selección de cliente
                        this.applyPrefilledClient();

                        // (Opcional) si por cualquier razón los clientes se vuelven a cargar:
                        this.$watch('clients', () => {
                            if (this.prefilledClientId) this.applyPrefilledClient();
                        });
                    },


                    // ===== persistencia simple
                    persistState() {
                        try {
                            localStorage.setItem('CITAS_CART', JSON.stringify(this.cart));
                            localStorage.setItem('CITAS_APPOINTMENT', JSON.stringify(this.appointment));
                        } catch (e) {
                            /* noop */
                        }
                    },
                    restoreState() {
                        try {
                            const cart = JSON.parse(localStorage.getItem('CITAS_CART') || '[]');
                            const appt = JSON.parse(localStorage.getItem('CITAS_APPOINTMENT') || '{}');
                            if (Array.isArray(cart)) this.cart = cart;
                            if (appt && typeof appt === 'object') this.appointment = {
                                ...this.appointment,
                                ...appt
                            };
                        } catch (e) {
                            /* noop */
                        }
                    },
                    clearState() {
                        try {
                            localStorage.removeItem('CITAS_CART');
                            localStorage.removeItem('CITAS_APPOINTMENT');
                        } catch (e) {
                            /* noop */
                        }
                    },

                    // ===== cargar datos
                    async loadServices() {
                        try {
                            const backendData = {!! json_encode($servicios ?? []) !!};
                            if (Array.isArray(backendData) && backendData.length > 0) {
                                this.services = backendData;
                                return;
                            }
                            const r = await fetch('/app/servicios/lista', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            const d = await r.json();
                            this.services = Array.isArray(d.servicios) ? d.servicios : [];
                        } catch (e) {
                            console.error('Error loading services:', e);
                        }
                    },

                    async loadCategories() {
                        try {
                            const backendData = {!! json_encode($categorias ?? []) !!};
                            if (Array.isArray(backendData) && backendData.length > 0) {
                                this.categories = backendData;
                                return;
                            }
                            const r = await fetch('/app/categorias', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            const d = await r.json();
                            this.categories = Array.isArray(d.categorias) ? d.categorias : [];
                        } catch (e) {
                            console.error('Error loading categories:', e);
                        }
                    },

                    async loadEmployees() {
                        try {
                            const backendData = {!! json_encode($empleados ?? []) !!};
                            if (Array.isArray(backendData) && backendData.length > 0) {
                                this.employees = backendData;
                                return;
                            }
                            const r = await fetch('/app/empleados/lista', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            const d = await r.json();
                            this.employees = Array.isArray(d.empleados) ? d.empleados : [];
                        } catch (e) {
                            console.error('Error loading employees:', e);
                        }
                    },

                    async loadClients() {
                        try {
                            const backendData = {!! json_encode($clientes ?? []) !!};
                            if (Array.isArray(backendData) && backendData.length > 0) {
                                this.clients = backendData;
                                return;
                            }
                            const r = await fetch('/app/clientes/lista', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            const d = await r.json();
                            this.clients = Array.isArray(d.clientes) ? d.clientes : [];
                        } catch (e) {
                            console.error('Error loading clients:', e);
                        }
                    },

                    // ===== carrito
                    addToCart(s) {
                        const idx = this.cart.findIndex(x => Number(x.id) === Number(s.id));
                        if (idx !== -1) {
                            this.cart[idx].cantidad = Math.max(1, Number(this.cart[idx].cantidad || 1) + 1);
                            this.persistState();
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'success',
                                title: 'Cantidad actualizada',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            return;
                        }
                        this.cart.push({
                            id: s.id,
                            nombre: s.nombre,
                            categoria_id: s.categoria_id,
                            duracion_minutos: Number(s.duracion_minutos || 0),
                            precio: Number(s.precio || 0),
                            cantidad: 1,
                            descuento_mxn: 0,
                            empleado_id: s.empleado_id || '',
                        });
                        this.persistState();
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'Agregado al carrito',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                    removeFromCart(i) {
                        this.cart.splice(i, 1);
                        this.persistState();
                        if (this.cart.length === 0) this.isCartOpen = false;
                    },
                    async vaciarCarrito() {
                        if (!this.cart.length) return;
                        const res = await Swal.fire({
                            icon: 'warning',
                            title: '¿Vaciar carrito?',
                            text: 'Se quitarán todos los servicios.',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, vaciar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#dc2626'
                        });
                        if (res.isConfirmed) {
                            this.cart = [];
                            this.persistState();
                            this.isCartOpen = false;
                        }
                    },

                    // ===== guardar cita
                    // ===== guardar cita (con botón de WhatsApp al finalizar)
                    async saveAppointment() {
                        try {
                            if (!this.cart.length) {
                                Swal.fire('Vacío', 'Agrega al menos un servicio', 'info');
                                return;
                            }
                            if (!this.appointment.cliente_id) {
                                Swal.fire('Falta cliente', 'Selecciona un cliente', 'warning');
                                this.cartTab = 'cliente';
                                return;
                            }
                            if (!this.appointment.fecha || !this.appointment.hora_inicio) {
                                Swal.fire('Falta fecha/hora', 'Indica fecha y hora de inicio',
                                    'warning');
                                this.cartTab = 'cliente';
                                return;
                            }

                            // Construye el payload para tu backend
                            const payload = {
                                cliente_id: this.appointment.cliente_id,
                                fecha: this.appointment.fecha,
                                hora_inicio: this.appointment.hora_inicio,
                                notas: this.appointment.notas || '',
                                descuento_mxn: Number(this.appointment.descuento_mxn || 0),
                                items: this.cart.map(it => ({
                                    servicio_id: it.id,
                                    cantidad: Number(it.cantidad || 1),
                                    precio_unit: Number(it.precio || 0),
                                    descuento_mxn: Number(it.descuento_mxn || 0),
                                    empleado_id: it.empleado_id || null,
                                    duracion_minutos: Number(it.duracion_minutos || 0),
                                }))
                            };

                            const resp = await fetch('/app/citas', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]')?.content ||
                                        '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(payload)
                            });
                            const data = await resp.json().catch(() => ({}));
                            if (!resp.ok) throw new Error(data.error || 'No se pudo guardar la cita');

                            // Prepara WhatsApp (usar estado ANTES de limpiar)
                            const waUrl = this.buildWhatsappUrl();
                            const waText = this.buildWhatsappMessage();

                            // Modal con botón de WhatsApp
                            Swal.fire({
                                icon: 'success',
                                title: 'Cita creada',
                                html: `
        <p class="text-gray-700">La cita se guardó correctamente.</p>
        ${
          waUrl
          ? `<a href="${waUrl}" target="_blank" rel="noopener"
                                                                                                                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#25D366] text-white font-semibold shadow hover:brightness-95">
                                                                                                                                <svg class="w-5 h-5" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                                                                                                                                  <path d="M16.01 3.2C9.38 3.2 4 8.58 4 15.21c0 2.67.86 5.14 2.33 7.15L4.8 28.8l6.62-1.74c1.92 1.05 4.13 1.65 6.48 1.65 6.63 0 12.01-5.38 12.01-12.01S22.64 3.2 16.01 3.2zm0 21.88c-2.26 0-4.35-.69-6.1-1.88l-.44-.28-3.91 1.03 1.04-3.82-.29-.47a10.22 10.22 0 01-1.58-5.45c0-5.66 4.6-10.26 10.28-10.26s10.28 4.6 10.28 10.26-4.62 10.27-10.29 10.27z"/>
                                                                                                                                  <path d="M19.11 17.5c-.27-.13-1.6-.79-1.84-.88-.24-.09-.42-.13-.6.13-.18.27-.69.88-.85 1.06-.16.18-.31.2-.58.07-.27-.13-1.14-.42-2.17-1.34-.8-.71-1.34-1.58-1.5-1.85-.16-.27-.02-.41.12-.54.13-.13.27-.31.4-.47.13-.16.18-.27.27-.45.09-.18.04-.34-.02-.47-.06-.13-.6-1.44-.82-1.97-.22-.53-.44-.46-.6-.47-.16-.01-.34-.01-.52-.01-.18 0-.47.07-.72.34-.24.27-.95.93-.95 2.27s.98 2.64 1.12 2.82c.13.18 1.93 2.95 4.68 4.13.65.28 1.16.45 1.56.58.65.21 1.24.18 1.7.11.52-.08 1.6-.65 1.83-1.28.22-.63.22-1.17.15-1.28-.07-.11-.25-.18-.52-.31z"/>
                                                                                                                                </svg>
                                                                                                                                Enviar por WhatsApp
                                                                                                                              </a>`
          : `<div class="mt-3 text-xs text-rose-600">No encontré teléfono del cliente. Puedes copiar el mensaje.</div>
                                                                                                                             <button id="copyWa"
                                                                                                                                     class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-gray-700 hover:bg-gray-50">
                                                                                                                               Copiar mensaje
                                                                                                                             </button>`
        }
      `,
                                focusConfirm: false,
                                confirmButtonText: 'Listo',
                                confirmButtonColor: '#16a34a',
                                didOpen: () => {
                                    const btn = document.getElementById('copyWa');
                                    if (btn) {
                                        btn.addEventListener('click', async () => {
                                            try {
                                                await navigator.clipboard
                                                    .writeText(waText);
                                                btn.textContent = 'Copiado ✓';
                                            } catch (e) {
                                                btn.textContent =
                                                    'No se pudo copiar';
                                            }
                                        });
                                    }
                                }
                            });

                            // reset + limpiar estado persistido
                            this.cart = [];
                            this.appointment = {
                                cliente_id: '',
                                fecha: '',
                                hora_inicio: '',
                                notas: '',
                                descuento_mxn: 0
                            };
                            this.persistState();
                            this.clearState();
                            this.isCartOpen = false;

                        } catch (e) {
                            console.error(e);
                            Swal.fire('Error', e.message || 'Ocurrió un error al guardar', 'error');
                        }
                    },
                }));
            });


            const date = new Date();
            const mxTime = date.toLocaleTimeString('es-MX', {
                timeZone: 'America/Mexico_City',
                hour: '2-digit',
                minute: '2-digit'
            });
            console.log(mxTime); // Ej: "14:30"
        </script>
    </div>
</x-app-layout>
