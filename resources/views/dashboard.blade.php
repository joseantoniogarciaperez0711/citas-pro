{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between px-2">
            <h2 class="font-bold text-xl sm:text-2xl text-black">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('app.clientes') }}" title="Agendar cita (Alt+N)"
                    class="group inline-flex items-center gap-2 rounded-xl px-3.5 py-2 sm:px-4 sm:py-2.5
    bg-blue-600 text-white font-semibold text-sm shadow-sm ring-1 ring-blue-600/20
    hover:bg-blue-700 hover:shadow-md hover:-translate-y-0.5
    active:bg-blue-800 active:translate-y-0
    focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400">

                    <!-- Icono calendario + (agendar) -->
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-blue-500/20">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13v4m2-2H10" />
                        </svg>
                    </span>

                    <!-- Texto siempre visible -->
                    <span class="inline">Agendar cita</span>
                </a>


                <!-- Píldora de fecha -->
                <div class="inline-block px-4 py-1.5 rounded-full bg-gray-500 text-white
                  text-sm sm:text-base font-semibold shadow-md transition"
                    x-data
                    x-text="new Date().toLocaleDateString('es-MX', { weekday: 'short', day: 'numeric', month: 'short' })">
                </div>
            </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 pb-6">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

        <style>
            [x-cloak] {
                display: none !important
            }

            body {
                background: #ffffff;
                color: #1f2937;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            /* Mobile optimizations */
            * {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                user-select: none;
            }

            input,
            textarea,
            select {
                -webkit-user-select: auto;
                user-select: auto;
            }

            /* Cards móviles */
            .dashboard-card {
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 1px solid #334155;
                border-radius: 0.75rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                transition: all 0.2s ease;
                padding: 1rem;
            }

            .dashboard-card:active {
                transform: scale(0.98);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            }

            @media (min-width: 768px) {
                .dashboard-card {
                    padding: 1.5rem;
                }

                .dashboard-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
                }
            }

            /* KPI Cards mobile */
            .kpi-card {
                min-height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .kpi-value {
                font-size: 1.5rem;
                font-weight: 700;
                line-height: 1.2;
            }

            @media (min-width: 640px) {
                .kpi-value {
                    font-size: 2rem;
                }
            }

            /* Progress ring mobile */
            .progress-ring {
                transform: rotate(-90deg);
                width: 60px;
                height: 60px;
            }

            @media (min-width: 640px) {
                .progress-ring {
                    width: 80px;
                    height: 80px;
                }
            }

            .progress-ring-circle {
                stroke: #374151;
                fill: transparent;
                stroke-width: 6;
                stroke-linecap: round;
                stroke-dasharray: 163.28;
                stroke-dashoffset: 163.28;
                transition: stroke-dashoffset 0.8s ease-in-out;
            }

            @media (min-width: 640px) {
                .progress-ring-circle {
                    stroke-width: 8;
                    stroke-dasharray: 251.2;
                    stroke-dashoffset: 251.2;
                }
            }

            .progress-ring-circle.animated {
                stroke: url(#gradient);
            }

            /* Mobile gradients */
            .gradient-green {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            }

            .gradient-red {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            }

            .gradient-blue {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            }

            .gradient-purple {
                background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            }

            /* Mobile buttons */
            .btn-primary,
            .btn-secondary {
                padding: 0.5rem 0.75rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.2s ease;
                font-size: 0.875rem;
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .btn-primary {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                color: white;
                border: none;
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            }

            .btn-primary:active {
                transform: scale(0.95);
                box-shadow: 0 1px 4px rgba(59, 130, 246, 0.4);
            }

            .btn-secondary {
                background: rgba(51, 65, 85, 0.8);
                color: #e2e8f0;
                border: 1px solid #475569;
                backdrop-filter: blur(10px);
            }

            .btn-secondary:active {
                background: rgba(71, 85, 105, 0.8);
                border-color: #64748b;
                transform: scale(0.95);
            }

            @media (min-width: 768px) {
                .btn-primary:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
                }

                .btn-secondary:hover {
                    background: rgba(71, 85, 105, 0.8);
                    border-color: #64748b;
                }
            }

            /* Mobile agenda */
            .schedule-grid {
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border-radius: 0.75rem;
                overflow: hidden;
            }

            .schedule-header {
                background: rgba(30, 41, 59, 0.9);
                border-bottom: 1px solid #475569;
                padding: 1rem;
            }

            .mobile-day-view {
                max-height: 70vh;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .day-column {
                border-left: 1px solid rgba(71, 85, 105, 0.3);
                min-height: 60px;
                position: relative;
            }

            .day-column:first-child {
                border-left: none;
            }

            .time-slot {
                height: 50px;
                border-bottom: 1px solid rgba(71, 85, 105, 0.2);
                position: relative;
            }

            @media (min-width: 768px) {
                .time-slot {
                    height: 60px;
                }
            }

            .time-slot:nth-child(even) {
                background: rgba(15, 23, 42, 0.3);
            }

            .appointment-event {
                position: absolute;
                left: 2px;
                right: 2px;
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                border-radius: 6px;
                padding: 6px 8px;
                color: white;
                font-size: 0.75rem;
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
                transition: all 0.2s ease;
                cursor: pointer;
                z-index: 10;
                min-height: 40px;
            }

            .appointment-event:active {
                transform: scale(0.98);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            }

            @media (min-width: 768px) {
                .appointment-event {
                    left: 4px;
                    right: 4px;
                    padding: 8px;
                }

                .appointment-event:hover {
                    transform: scale(1.02);
                    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
                }
            }

            /* Mobile chart */
            .chart-container {
                position: relative;
                height: 250px;
                background: rgba(15, 23, 42, 0.5);
                border-radius: 0.5rem;
                padding: 0.75rem;
            }

            @media (min-width: 768px) {
                .chart-container {
                    height: 300px;
                    padding: 1rem;
                }
            }

            /* Status badges mobile */
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 0.25rem 0.5rem;
                border-radius: 9999px;
                font-size: 0.7rem;
                font-weight: 600;
            }

            .status-programmed {
                background: rgba(59, 130, 246, 0.2);
                color: #93c5fd;
                border: 1px solid rgba(59, 130, 246, 0.3);
            }

            /* Mobile animations */
            .fade-in {
                animation: fadeIn 0.4s ease-out forwards;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .slide-in {
                animation: slideIn 0.3s ease-out forwards;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            /* Mobile scroll improvements */
            .mobile-scroll {
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                scrollbar-color: #475569 transparent;
            }

            .mobile-scroll::-webkit-scrollbar {
                width: 4px;
            }

            .mobile-scroll::-webkit-scrollbar-track {
                background: transparent;
            }

            .mobile-scroll::-webkit-scrollbar-thumb {
                background-color: #475569;
                border-radius: 2px;
            }

            /* Day picker mobile */
            .day-picker {
                display: flex;
                gap: 0.25rem;
                overflow-x: auto;
                padding: 0.5rem;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .day-picker::-webkit-scrollbar {
                display: none;
            }

            .day-button {
                min-width: 60px;
                height: 60px;
                border-radius: 0.75rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: rgba(51, 65, 85, 0.5);
                border: 1px solid #475569;
                color: #e2e8f0;
                font-size: 0.75rem;
                transition: all 0.2s ease;
                cursor: pointer;
                flex-shrink: 0;
            }

            .day-button.active {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                border-color: #2563eb;
                color: white;
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            }

            .day-button:active {
                transform: scale(0.95);
            }

            /* Mobile loading */
            .loading-spinner {
                width: 20px;
                height: 20px;
                border: 2px solid #374151;
                border-top: 2px solid #3b82f6;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }


            /* Base */
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.25rem 0.5rem;
                /* 4px 8px */
                border-radius: 9999px;
                font-size: 0.75rem;
                /* text-xs */
                font-weight: 700;
                /* font-bold */
                line-height: 1;
                border: 1px solid transparent;
                white-space: nowrap;
            }

            /* Puntito a la izquierda (opcional) */
            .status-badge::before {
                content: "";
                width: 6px;
                height: 6px;
                border-radius: 9999px;
                background: currentColor;
                opacity: .9;
            }

            /* ------- Estados ------- */

            /* pendiente */
            .status-pendiente {
                color: #fbbf24;
                /* amber-400 */
                background: rgba(251, 191, 36, 0.10);
                border-color: rgba(251, 191, 36, 0.25);
            }

            /* programada */
            .status-programada {
                color: #60a5fa;
                /* blue-400 */
                background: rgba(96, 165, 250, 0.12);
                border-color: rgba(96, 165, 250, 0.28);
            }

            /* terminada */
            .status-terminada {
                color: #34d399;
                /* emerald-400 */
                background: rgba(52, 211, 153, 0.12);
                border-color: rgba(52, 211, 153, 0.28);
            }

            /* cancelada */
            .status-cancelada {
                color: #f87171;
                /* red-400 */
                background: rgba(248, 113, 113, 0.12);
                border-color: rgba(248, 113, 113, 0.3);
            }

            /* reprogramada */
            .status-reprogramada {
                color: #f59e0b;
                /* amber-500 */
                background: rgba(245, 158, 11, 0.12);
                border-color: rgba(245, 158, 11, 0.28);
            }
        </style>

        <div x-data="dashboard()" x-cloak
            class="container mx-auto max-w-7xl px-3 sm:px-4 md:px-6 lg:px-8 space-y-4 sm:space-y-6">


            <!-- Agenda Semanal - Lista única (móvil y PC) con FECHA destacada -->
            <!-- Agenda Semanal - Lista única (móvil y PC) con FECHA destacada -->
            <section class="schedule-grid fade-in" x-data="{
                statusFilter: 'pendiente',
                /* 👈 por defecto 'pendiente' */
                search: '',
                dateTokens(dStr) {
                    const dt = new Date(dStr);
                    return {
                        dow: dt.toLocaleDateString('es-MX', { weekday: 'short' }).replace('.', '').toUpperCase(),
                        day: dt.toLocaleDateString('es-MX', { day: '2-digit' }),
                        mon: dt.toLocaleDateString('es-MX', { month: 'short' }).replace('.', '').toUpperCase(),
                        full: dt.toLocaleDateString('es-MX', { weekday: 'long', year: 'numeric', month: 'long', day: '2-digit' })
                    };
                },
                isToday(dStr) {
                    const d = new Date(dStr);
                    const now = new Date();
                    return d.getFullYear() === now.getFullYear() &&
                        d.getMonth() === now.getMonth() &&
                        d.getDate() === now.getDate();
                },
                // 👉 reprogramada cuenta como pendiente
                priority(estado) {
                    const e = (estado || '').toLowerCase();
                    if (e === 'pendiente' || e === 'reprogramada' || e === 'confirmada') return 0;
                    if (e === 'programada') return 1;
                    if (e === 'terminada' || e === 'cancelada') return 2;
                    return 99;
                },
                get filteredWeek() {
                    const q = (this.search || '').toLowerCase().trim();
                    const sf = (this.statusFilter || 'todas').toLowerCase();
            
                    // Toma eventos desde el root si existe, o desde una var local `week`
                    const items = ($root?.week?.eventos || week?.eventos || []);
            
                    const filtered = items.filter(ev => {
                        const est = (ev.estado || '').toLowerCase();
                        const nombre = (ev.cliente?.nombre || '').toLowerCase();
            
                        // 👉 magia: cuando filtro es 'pendiente', incluye 'reprogramada'
                        const byStatus = (sf === 'todas') ?
                            true :
                            (sf === 'pendiente') ?
                            (est === 'pendiente' || est === 'reprogramada') :
                            (est === sf);
            
                        const bySearch = !q || nombre.includes(q);
                        return byStatus && bySearch;
                    });
            
                    return filtered.sort((a, b) => {
                        const da = new Date(a.hora_inicio);
                        const db = new Date(b.hora_inicio);
                        if (sf === 'todas') {
                            const pa = this.priority(a.estado);
                            const pb = this.priority(b.estado);
                            if (pa !== pb) return pa - pb;
                            return da - db;
                        }
                        return da - db;
                    });
                }
            }">
                <div class="schedule-header">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                        <!-- Controles de semana -->
                        <div class="flex items-center gap-2 sm:gap-3">
                            <button @click="goPrevWeek()" :disabled="loading"
                                class="btn-secondary flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                                <div x-show="loading" class="loading-spinner"></div>
                                <svg x-show="!loading" class="w-3 h-3 sm:w-4 sm:h-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span class="hidden sm:inline">Anterior</span>
                            </button>

                            <div class="text-sm sm:text-lg font-semibold text-white" x-text="weekTitle"></div>

                            <button @click="goNextWeek()" :disabled="loading"
                                class="btn-secondary flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                                <span class="hidden sm:inline">Siguiente</span>
                                <div x-show="loading" class="loading-spinner"></div>
                                <svg x-show="!loading" class="w-3 h-3 sm:w-4 sm:h-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <button @click="goThisWeek()" :disabled="loading"
                                class="btn-primary text-xs sm:text-sm">Hoy</button>
                        </div>

                        <!-- Info / zona horaria -->
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-400">
                            <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">TZ: America/Mexico_City</span>
                        </div>
                    </div>

                    <!-- Filtros rápidos (opcional) -->
                    <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400">Filtrar:</span>
                            <div class="flex bg-gray-700 rounded-lg p-1">
                                <button @click="statusFilter='todas'"
                                    :class="statusFilter === 'todas' ? 'bg-blue-600 text-white' :
                                        'text-gray-300 hover:text-white'"
                                    class="px-2 py-1 rounded text-xs font-medium transition-all">Todas</button>
                                <button @click="statusFilter='pendiente'"
                                    :class="statusFilter === 'pendiente' ? 'bg-blue-600 text-white' :
                                        'text-gray-300 hover:text-white'"
                                    class="px-2 py-1 rounded text-xs font-medium transition-all">Pendiente</button>
                                <button @click="statusFilter='terminada'"
                                    :class="statusFilter === 'terminada' ? 'bg-blue-600 text-white' :
                                        'text-gray-300 hover:text-white'"
                                    class="px-2 py-1 rounded text-xs font-medium transition-all">Terminada</button>
                                <button @click="statusFilter='cancelada'"
                                    :class="statusFilter === 'cancelada' ? 'bg-blue-600 text-white' :
                                        'text-gray-300 hover:text-white'"
                                    class="px-2 py-1 rounded text-xs font-medium transition-all">Cancelada</button>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="sr-only" for="search-week">Buscar</label>
                            <input id="search-week" type="text" x-model="search" placeholder="Buscar por cliente…"
                                class="w-full sm:w-64 bg-gray-800 text-gray-200 text-xs sm:text-sm rounded-md px-3 py-2
                           border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-6">
                    <!-- Lista única para toda la semana -->
                    <div>
                        <div class="space-y-2 md:space-y-0 md:grid md:grid-cols-2 xl:grid-cols-3 md:gap-3"
                            x-show="filteredWeek.length">
                            <template x-for="ev in filteredWeek" :key="ev.id">
                                <div class="bg-gradient-to-r from-blue-900/50 to-indigo-900/50 border border-blue-800/50 rounded-lg p-3"
                                    :class="isToday(ev.hora_inicio) ? 'ring-2 ring-yellow-400/60' : ''"
                                    :aria-label="'Cita el ' + dateTokens(ev.hora_inicio).full">
                                    <div class="flex gap-3">
                                        <!-- FECHA DESTACADA -->
                                        <div class="w-16 sm:w-20 flex-shrink-0 rounded-lg bg-blue-900/70 border border-blue-700/50 shadow-inner
                                        flex flex-col items-center justify-center py-2"
                                            :class="isToday(ev.hora_inicio) ? 'bg-yellow-900/40 border-yellow-600/50' : ''"
                                            :title="dateTokens(ev.hora_inicio).full">
                                            <div class="text-[10px] tracking-wide"
                                                :class="isToday(ev.hora_inicio) ? 'text-yellow-300' : 'text-blue-300'"
                                                x-text="dateTokens(ev.hora_inicio).dow"></div>
                                            <div class="text-2xl sm:text-3xl font-extrabold leading-none text-white"
                                                x-text="dateTokens(ev.hora_inicio).day"></div>
                                            <div class="text-[10px] font-semibold"
                                                :class="isToday(ev.hora_inicio) ? 'text-yellow-200' : 'text-blue-200'"
                                                x-text="dateTokens(ev.hora_inicio).mon"></div>
                                        </div>

                                        <!-- CONTENIDO -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2 mb-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <div class="font-semibold text-white truncate"
                                                        x-text="ev.cliente?.nombre || 'Cliente'"></div>
                                                    <span class="status-badge"
                                                        :class="'status-' + (ev.estado || '').toLowerCase()"
                                                        x-text="(ev.estado || '').charAt(0).toUpperCase() + (ev.estado || '').slice(1)">
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-sm text-gray-300 flex items-center gap-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span x-text="rangeHour(ev.hora_inicio, ev.hora_fin)"></span>
                                            </div>

                                            <div class="mt-3 flex flex-wrap gap-1.5">
                                                <!-- Recordar por WhatsApp -->
                                                <button
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors inline-flex items-center justify-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed"
                                                    @click.stop="remindWhatsApp(ev)"
                                                    :disabled="['terminada', 'cancelada'].includes((ev.estado || '').toLowerCase())"
                                                    aria-label="Recordar por WhatsApp">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 32 32" aria-hidden="true">
                                                        <path fill="currentColor"
                                                            d="M16.01 5.6c-5.71 0-10.36 4.65-10.36 10.36 0 1.83.48 3.57 1.41 5.12l-1.5 5.49 5.64-1.47c1.5.82 3.2 1.25 4.93 1.25h.01c5.71 0 10.36-4.65 10.36-10.36s-4.65-10.39-10.36-10.39zm6.01 16.37c-.26.74-1.53 1.42-2.18 1.53-.57.09-1.32.12-2.13-.12-2.37-.75-3.9-1.71-5.63-3.33-1.18-1.08-2.1-2.34-2.73-3.69-.57-1.2-1.11-2.55-1.11-3.9 0-1.2.36-2.37 1.14-3.27.27-.33.75-.75 1.29-.75.15 0 .27 0 .39.03.12.03.3.03.45.72.18.84.63 2.28.69 2.46.06.18.09.42-.03.69-.12.27-.18.42-.36.63-.18.18-.39.42-.57.66-.18.21-.39.45-.18.84.21.39.93 1.53 2.01 2.49 1.38 1.23 2.49 1.62 2.88 1.83.39.18.63.15.84-.09.27-.3.96-1.11 1.23-1.5.27-.39.54-.33.9-.21.36.12 2.25 1.05 2.64 1.23.39.18.66.27.75.42.06.15.06.87-.21 1.62z" />
                                                        <path fill="currentColor"
                                                            d="M19.11 17.53c-.27-.15-1.59-.88-1.84-.98-.24-.09-.42-.15-.6.15-.18.27-.69.97-.84 1.17-.15.18-.3.21-.57.09-.27-.15-1.11-.42-2.11-1.35-.78-.69-1.29-1.53-1.44-1.8-.15-.27 0-.42.12-.57.12-.12.27-.3.39-.45.12-.15.15-.27.24-.45.09-.15.06-.33-.03-.48-.09-.15-.6-1.44-.84-1.98-.21-.51-.42-.42-.6-.42h-.51c-.18 0-.48.06-.72.33-.24.27-.93.9-.93 2.19s.96 2.55 1.11 2.73c.15.18 1.89 2.88 4.59 4.05.64.27 1.14.42 1.53.57.64.21 1.23.18 1.68.12.51-.09 1.59-.69 1.8-1.35.21-.66.21-1.23.15-1.35-.06-.12-.24-.18-.51-.33z" />
                                                    </svg>
                                                    <span>Recordar</span>
                                                </button>

                                                <!-- Terminado -->
                                                <button
                                                    class="bg-green-600 hover:bg-green-700 text-white px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors inline-flex items-center justify-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed"
                                                    @click.stop="markDone(ev.id)"
                                                    :disabled="['terminada', 'cancelada'].includes((ev.estado || '').toLowerCase())">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>Terminado</span>
                                                </button>

                                                <!-- Revisar -->
                                                <button
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors inline-flex items-center justify-center gap-1.5"
                                                    @click.stop="openModal(ev.id)">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    <span>Revisar</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Estado vacío -->
                        <template x-if="filteredWeek.length === 0">
                            <div class="text-center py-10 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">No hay eventos para esta semana</p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Loading overlay -->
                <div x-show="loading"
                    class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm rounded-lg flex items-center justify-center z-50">
                    <div class="flex items-center gap-3 text-white bg-gray-800 px-4 py-2 rounded-lg">
                        <div class="loading-spinner"></div>
                        <span class="text-sm">Cargando...</span>
                    </div>
                </div>
            </section>



            <!-- Main Modal for Appointment Details -->
            <div x-cloak x-show="showModal" x-transition.opacity
                class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/70" @click="closeModal()"></div>
                <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-700 rounded-2xl p-4 sm:p-6">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h4 class="text-white font-semibold text-lg" x-text="modal.title"></h4>
                            <p class="text-gray-400 text-sm"
                                x-text="rangeHour(citaSel?.hora_inicio, citaSel?.hora_fin)"></p>

                        </div>
                        <button class="text-gray-400 hover:text-white" @click="closeModal()">✕</button>
                    </div>

                    <!-- Detalle (mejorado) -->
                    <div class="max-h-[60vh] overflow-auto pr-1">
                        <!-- Contenido principal: izquierda servicios / derecha totales -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Columna izquierda: Servicios -->
                            <div class="lg:col-span-2 space-y-2">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-gray-200 font-semibold">Servicios</h5>
                                    <span class="text-xs text-gray-400"
                                        x-text="'Servicios: ' + (citaSel?.items?.length || 0)"></span>
                                </div>
                                <!-- Lista de servicios -->
                                <template x-if="(citaSel?.items || []).length">
                                    <div
                                        class="divide-y divide-slate-700/70 rounded-lg overflow-hidden border border-slate-700/70">
                                        <template x-for="it in (citaSel?.items || [])" :key="it.id">
                                            <div class="bg-slate-800/40 px-3 sm:px-4 py-2.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="font-medium text-gray-100 truncate"
                                                                x-text="it.nombre_servicio_snapshot"></span>
                                                            <!-- Duración -->
                                                            <span
                                                                class="text-[11px] sm:text-xs px-2 py-0.5 rounded-full bg-indigo-900/30 border border-indigo-600/30 text-indigo-200">
                                                                <span
                                                                    x-text="(it.duracion_minutos_snapshot || 0) + ' min'"></span>
                                                            </span>
                                                            <!-- Cantidad -->
                                                            <span
                                                                class="text-[11px] sm:text-xs px-2 py-0.5 rounded-full bg-slate-900/40 border border-slate-600/40 text-gray-300">
                                                                x<span x-text="Math.max(1, it.cantidad || 1)"></span>
                                                            </span>
                                                            <!-- Descuento por línea -->
                                                            <template x-if="(it.descuento || 0) > 0">
                                                                <span
                                                                    class="text-[11px] sm:text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-900/30 border border-amber-600/40 text-amber-300">
                                                                    - <span x-text="money(it.descuento || 0)"></span>
                                                                </span>
                                                            </template>
                                                        </div>
                                                        <!-- Precio unitario -->
                                                        <div class="text-xs text-gray-400 mt-1">
                                                            <span>Precio: </span>
                                                            <span
                                                                x-text="money(it.precio_servicio_snapshot || 0)"></span>
                                                            <span class="opacity-60"> c/u</span>
                                                        </div>
                                                    </div>
                                                    <!-- Total de la línea -->
                                                    <div class="text-right">
                                                        <div class="text-xs text-gray-400">servicio</div>
                                                        <div class="text-sm font-semibold text-gray-100"
                                                            x-text="money((it.precio_servicio_snapshot || 0)*Math.max(1, it.cantidad || 1) - (it.descuento || 0))">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <!-- Sin servicios -->
                                <template x-if="!(citaSel?.items || []).length">
                                    <div
                                        class="text-sm text-gray-400 bg-slate-800/40 border border-slate-700 rounded-lg p-3">
                                        No hay servicios capturados para esta cita.
                                    </div>
                                </template>
                            </div>
                            <!-- Columna derecha: Totales claros -->
                            <div class="lg:col-span-1">
                                <div class="bg-slate-800/60 border border-slate-700 rounded-xl p-3 sm:p-4">
                                    <h5 class="text-gray-200 font-semibold mb-3">Resumen de cobro</h5>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-300">Bruto</span>
                                            <span class="font-semibold text-gray-100"
                                                x-text="money(citaSel?.totales?.bruto || 0)"></span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-300">Desc. x servicios</span>
                                            <span class="font-semibold text-amber-300"
                                                x-text="money(citaSel?.totales?.desc_lineas || 0)"></span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-300">Subtotal</span>
                                            <span class="font-semibold text-gray-100"
                                                x-text="money(citaSel?.totales?.subtotal || 0)"></span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-300">Desc. general</span>
                                            <span class="font-semibold text-amber-300"
                                                x-text="money(citaSel?.totales?.desc_orden || 0)"></span>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 border-t border-slate-700">
                                            <span class="text-gray-300">Descuentos</span>
                                            <span class="font-semibold text-amber-300"
                                                x-text="money((citaSel?.totales?.desc_lineas || 0) + (citaSel?.totales?.desc_orden || 0))"></span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-gray-300">Total</span>
                                            <span class="text-lg font-bold text-emerald-400"
                                                x-text="money(citaSel?.totales?.neto || 0)"></span>
                                        </div>
                                    </div>
                                    <!-- Nota opcional -->
                                    <template x-if="(citaSel?.notas || '').trim().length">
                                        <div
                                            class="mt-4 text-xs text-gray-300/90 bg-slate-900/50 border border-slate-700/60 rounded-lg p-3">
                                            <div class="font-medium text-gray-200 mb-1">Notas</div>
                                            <div x-text="citaSel?.notas"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones principales -->
                    <div class="mt-6 flex flex-wrap items-center gap-2">
                        <button class="px-3 py-1.5 rounded-lg bg-rose-600/90 hover:bg-rose-600 text-white text-sm"
                            @click="askCancel(citaSel)" :disabled="!citaSel || citaSel.estado === 'cancelada'">
                            Cancelar cita
                        </button>
                        <button class="px-2.5 py-1.5 rounded-md bg-amber-600/90 hover:bg-amber-600 text-white text-xs"
                            @click="openReprogModal()" :disabled="!citaSel">
                            Reprogramar
                        </button>
                        <div class="ml-auto">
                            <button class="px-2.5 py-1.5 rounded-md bg-slate-700 hover:bg-slate-600 text-white text-xs"
                                @click="closeModal()">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile-Optimized Rescheduling Modal with Clean White Design -->
            <div x-cloak x-show="showReprogModal" x-transition.opacity
                class="fixed inset-0 z-[110] flex items-end sm:items-center justify-center px-0 sm:px-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeReprogModal()"></div>

                <!-- Mobile: Bottom sheet / Desktop: Centered modal -->
                <div
                    class="relative w-full sm:max-w-lg bg-white sm:rounded-2xl rounded-t-3xl shadow-2xl max-h-[85vh] overflow-hidden
                border border-gray-100 transform transition-all duration-300 ease-out">

                    <!-- Mobile drag indicator -->
                    <div class="sm:hidden flex justify-center pt-3 pb-1">
                        <div class="w-8 h-1 bg-gray-300 rounded-full"></div>
                    </div>

                    <!-- Header Enhanced -->
                    <div class="sticky top-0 bg-white/95 backdrop-blur-sm border-b border-gray-200 px-6 py-5">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 pr-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">Reprogramar cita</h3>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Reagendar
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600" x-text="citaSel?.cliente?.nombre || 'Cliente'"></p>

                                <!-- Current Appointment Info -->
                                <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Actual: <strong
                                                x-text="fmt(citaSel?.hora_inicio) + ' - ' + fmt(citaSel?.hora_fin)"></strong></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span x-text="totalMinsCita(citaSel) + ' minutos'"></span>
                                    </div>
                                </div>
                            </div>
                            <button class="p-2 hover:bg-gray-100 rounded-xl transition-colors group"
                                @click="closeReprogModal()">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content area -->
                    <div class="overflow-y-auto px-6 pb-6">

                        <!-- New Schedule Section -->
                        <div class="py-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Nuevo horario
                            </h4>

                            <div
                                class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-200 rounded-xl p-6">
                                <div class="space-y-5">
                                    <!-- Date Input -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Nueva fecha
                                        </label>
                                        <input type="date"
                                            class="w-full h-12 border border-gray-300 rounded-xl px-4 text-gray-900 text-base
                                          focus:ring-2 focus:ring-orange-500 focus:border-orange-500 
                                          transition-all duration-200 bg-white shadow-sm"
                                            x-model="reprog.fecha" @change="recalcEnd()">
                                    </div>

                                    <!-- Time Inputs -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-green-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hora de inicio
                                            </label>
                                            <input type="time"
                                                class="w-full h-12 border border-gray-300 rounded-xl px-4 text-gray-900 text-base
                                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500 
                                              transition-all duration-200 bg-white shadow-sm"
                                                x-model="reprog.hora_inicio" @change="recalcEnd()">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-purple-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Hora final
                                                <span
                                                    class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                                    automática
                                                </span>
                                            </label>
                                            <input type="time"
                                                class="w-full h-12 border border-gray-200 bg-gray-50 rounded-xl px-4 text-gray-600 text-base cursor-not-allowed shadow-sm"
                                                :value="reprog.hora_fin" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <template x-if="reprog.fecha && reprog.hora_inicio && reprog.hora_fin">
                                <div
                                    class="mt-6 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-5">
                                    <h5 class="text-base font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Vista previa del cambio
                                    </h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                                            <div
                                                class="text-xs font-medium text-blue-700 uppercase tracking-wide mb-1">
                                                Horario actual</div>
                                            <div class="font-semibold text-gray-900"
                                                x-text="fmt(citaSel?.hora_inicio) + ' – ' + fmt(citaSel?.hora_fin)">
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 border border-green-200">
                                            <div
                                                class="text-xs font-medium text-green-700 uppercase tracking-wide mb-1">
                                                Nuevo horario</div>
                                            <div class="font-semibold text-gray-900">
                                                <span x-text="formatDate(reprog.fecha)"></span><br>
                                                <span x-text="reprog.hora_inicio + ' – ' + reprog.hora_fin"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Actions - Sticky bottom with enhanced design -->
                    <div
                        class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-end sm:gap-3">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-2 w-full sm:w-auto">
                            <button
                                class="order-2 sm:order-1 w-full sm:w-auto px-5 py-3 sm:py-2.5 
                               bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl sm:rounded-lg 
                               border-2 border-gray-200 hover:border-gray-300 transition-all duration-200 
                               focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-base sm:text-sm
                               disabled:opacity-50 disabled:cursor-not-allowed"
                                @click="closeReprogModal()" :disabled="reprogLoading">
                                Cancelar
                            </button>
                            <button
                                class="order-1 sm:order-2 w-full sm:w-auto px-5 py-3 sm:py-2.5 
                               bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-xl sm:rounded-lg 
                               transition-all duration-200 text-base sm:text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2
                               disabled:bg-orange-300 disabled:cursor-not-allowed
                               flex items-center justify-center gap-2"
                                @click="submitReprog()"
                                :disabled="!reprog.fecha || !reprog.hora_inicio || reprogLoading">
                                <svg x-show="reprogLoading" class="w-4 h-4 animate-spin" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-30" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-90" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                </svg>
                                <svg x-show="!reprogLoading" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span x-show="!reprogLoading">Confirmar reprogramación</span>
                                <span x-show="reprogLoading">Guardando cambios...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Modal (Generic) -->
            <div x-cloak x-show="confirm.open" x-transition.opacity
                class="fixed inset-0 z-[120] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/70" @click="closeConfirm()"></div>
                <div class="relative w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl p-5 shadow-2xl">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div>
                            <h4 class="text-white font-semibold text-lg" x-text="confirm.title"></h4>
                            <p class="text-gray-400 text-sm mt-0.5" x-text="confirm.message"></p>
                        </div>
                        <button class="text-gray-400 hover:text-white" @click="closeConfirm()">✕</button>
                    </div>
                    <!-- Opcional: detalles resumidos de la cita -->
                    <template x-if="confirm.meta">
                        <div class="text-sm text-gray-300 bg-slate-800/60 border border-slate-700 rounded-lg p-3 mb-4">
                            <div class="font-medium" x-text="confirm.meta.cliente"></div>
                            <div class="text-gray-400" x-text="confirm.meta.horario"></div>
                        </div>
                    </template>
                    <div class="flex items-center justify-end gap-2">
                        <button class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm"
                            @click="closeConfirm()" :disabled="confirm.loading">
                            No, volver
                        </button>
                        <button
                            class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-sm inline-flex items-center gap-2"
                            @click="doConfirm()" :disabled="confirm.loading">
                            <svg x-show="confirm.loading" class="w-4 h-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-30" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-90" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>
                            <span x-text="confirm.cta || 'Sí, confirmar'"></span>
                        </button>
                    </div>
                </div>
            </div>


            <!-- KPI Cards Section - Mobile optimized grid -->
            <section class="grid grid-cols-1 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">


                <!-- Citas y Clientes Card (mejorada) -->
                <div class="dashboard-card kpi-card fade-in relative overflow-hidden">
                    <!-- halo sutil -->
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-br from-slate-900/0 via-slate-900/0 to-slate-900/20">
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:gap-4">
                        <!-- KPI: Citas hoy -->
                        <div class="rounded-xl bg-slate-800/60 border border-slate-700/60 p-3 sm:p-4 shadow-inner">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-blue-500/20 border border-blue-400/30 grid place-content-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2" />
                                        </svg>
                                    </div>
                                    <span class="text-[11px] sm:text-xs font-semibold text-blue-200/90">Citas
                                        hoy</span>
                                </div>
                            </div>

                            <div class="mt-2 flex items-end gap-2">
                                <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white"
                                    x-text="kpi.citas_hoy"></div>
                                <span class="text-[11px] text-slate-400">hoy</span>
                            </div>
                        </div>

                        <!-- KPI: Clientes en el periodo -->
                        <div class="rounded-xl bg-slate-800/60 border border-slate-700/60 p-3 sm:p-4 shadow-inner">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-purple-500/20 border border-purple-400/30 grid place-content-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0m6 3a2 2 0 11-4 0 2 2 0 014 0M7 10a2 2 0 11-4 0 2 2 0 014 0" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-[11px] sm:text-xs font-semibold text-purple-200/90">Clientes</span>
                                </div>
                            </div>

                            <div class="mt-2 flex items-end gap-2">
                                <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white"
                                    x-text="kpi.clientes_periodo"></div>
                                <span class="text-[11px] text-slate-400">en el periodo</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pie con rango activo -->
                    <div class="mt-3 flex items-center justify-center gap-2 text-[11px] sm:text-xs text-gray-400">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2" />
                        </svg>
                        <span x-text="periodLabel"></span>
                    </div>

                    <!-- Loading overlay -->
                    <div x-show="loading"
                        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm grid place-content-center">
                        <div
                            class="flex items-center gap-3 text-white bg-slate-800/90 px-4 py-2 rounded-lg border border-slate-700">
                            <div class="loading-spinner"></div>
                            <span class="text-sm">Cargando…</span>
                        </div>
                    </div>
                </div>


                <!-- Clientes más frecuentes (pro) -->
                <div class="dashboard-card kpi-card fade-in">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                <!-- trophy -->
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 21h8M12 17v4M7 4h10v4a5 5 0 01-10 0V4zM5 6h2a5 5 0 01-5 5V8a2 2 0 012-2zm14 0h2a2 2 0 012 2v3a5 5 0 01-5-5z" />
                                </svg>
                            </span>
                            <div class="text-white font-semibold text-sm sm:text-base">Clientes más frecuentes</div>
                        </div>
                        <div class="text-[11px] sm:text-xs text-gray-400 flex items-center gap-2">
                            <span class="hidden sm:inline" x-text="weekTitle"></span>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-700/60 border border-slate-600/60">
                                <svg class="w-3.5 h-3.5 text-slate-300" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14" />
                                </svg>
                                <span class="text-slate-300/90">Total:</span>
                                <strong class="text-white" x-text="totalCitasSemana"></strong>
                            </span>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <template x-if="topClientesSemana.length === 0">
                        <div class="text-xs sm:text-sm text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-70" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14" />
                            </svg>
                            Sin datos esta semana
                        </div>
                    </template>

                    <!-- List -->
                    <div class="space-y-2" x-show="topClientesSemana.length">
                        <template x-for="(c, idx) in topClientesSemana" :key="c.id ?? idx">
                            <div
                                class="rounded-lg border border-slate-600/40 bg-slate-800/40 p-3 hover:bg-slate-800/60 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <!-- Left: rank + avatar + name -->
                                    <div class="flex items-center gap-3 min-w-0">
                                        <!-- Rank badge -->
                                        <span
                                            class="inline-flex h-6 w-6 items-center justify-center rounded-md border text-[11px] font-bold"
                                            :class="[
                                                idx === 0 ? 'bg-amber-500/15 text-amber-300 border-amber-400/30' :
                                                idx === 1 ? 'bg-sky-500/15 text-sky-300 border-sky-400/30' :
                                                'bg-violet-500/15 text-violet-300 border-violet-400/30'
                                            ]"
                                            x-text="idx+1">
                                        </span>

                                        <!-- Avatar inicial -->
                                        <div
                                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-white
                     flex items-center justify-center text-xs sm:text-sm font-extrabold shadow-inner">
                                            <span x-text="(c.nombre || 'C')[0]?.toUpperCase()"></span>
                                        </div>

                                        <!-- Name + meta -->
                                        <div class="min-w-0">
                                            <div class="text-white text-sm font-semibold truncate"
                                                x-text="c.nombre || 'Cliente'"></div>
                                            <div class="text-[11px] text-gray-400">
                                                <span>x<span x-text="c.count"></span> citas</span>
                                                <span class="mx-1.5 opacity-40">•</span>
                                                <span>
                                                    <span
                                                        x-text="Math.round((c.count/Math.max(1,totalCitasSemana))*100)"></span>%
                                                    del total
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: count pill -->
                                    <div
                                        class="shrink-0 inline-flex items-center gap-1 rounded-md border border-blue-400/30 bg-blue-500/10
                   px-2 py-1 text-[11px] font-semibold text-blue-200">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14v7" />
                                        </svg>
                                        <span>x<span x-text="c.count"></span></span>
                                    </div>
                                </div>

                                <!-- Progress bar -->
                                <div class="mt-2">
                                    <div class="w-full h-1.5 rounded-full bg-slate-700/60 overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r"
                                            :class="idx === 0 ? 'from-emerald-400 to-emerald-500' :
                                                idx === 1 ? 'from-sky-400 to-sky-500' :
                                                'from-violet-400 to-violet-500'"
                                            :style="`width: ${Math.round((c.count/Math.max(1,totalCitasSemana))*100)}%`">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>


                <section class="lg:col-span-2 grid grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <!-- Ingresos Netos Card -->
                    <div class="dashboard-card kpi-card fade-in">
                        <div class="flex items-center justify-between mb-2 sm:mb-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg gradient-green flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-gray-300 font-medium text-sm sm:text-base truncate">Ingresos
                                    Netos</span>
                            </div>
                        </div>

                        <div class="kpi-value text-green-400 mb-2" x-text="money(kpi.neto)"></div>
                        <div class="text-xs sm:text-sm text-gray-400" x-text="periodLabel"></div>
                    </div>

                    <!-- Descuentos Card -->
                    <div class="dashboard-card kpi-card fade-in">
                        <div class="flex items-center justify-between mb-2 sm:mb-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg gradient-red flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-gray-300 font-medium text-sm sm:text-base truncate">Descuentos</span>
                            </div>
                        </div>

                        <!-- total = desc_linea + desc_orden -->
                        <div class="kpi-value text-red-400 mb-2" x-text="'-' + money(kpi.desc_total)"></div>

                        <!-- breakdown -->
                        <div class="text-xs sm:text-sm text-gray-400">
                            <span class="mr-3">xServicios: <span x-text="money(kpi.desc_linea)"></span></span>
                            <br>
                            <span>General: <span x-text="money(kpi.desc_orden)"></span></span>
                        </div>

                        <!-- mismo pie de rango que en Ingresos Netos -->
                        <div class="text-xs sm:text-sm text-gray-400 mt-1" x-text="periodLabel"></div>
                    </div>

                </section>





            </section>

            <!-- Chart de Ingresos -->
            <div class="dashboard-card xl:col-span-3 fade-in">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Ingresos
                    </h3>

                    <!-- Controles Desktop -->
                    <div class="hidden sm:flex items-center gap-2">
                        <!-- Rangos -->
                        <div class="bg-gray-700 rounded-lg p-1">
                            <button @click="setChartRange('day')"
                                :class="chartRange === 'day' ? 'bg-blue-600 text-white' : 'text-gray-300'"
                                class="px-2 py-1 rounded text-xs font-semibold">Día</button>
                            <button @click="setChartRange('week')"
                                :class="chartRange === 'week' ? 'bg-blue-600 text-white' : 'text-gray-300'"
                                class="px-2 py-1 rounded text-xs font-semibold">Semana</button>
                            <button @click="setChartRange('month')"
                                :class="chartRange === 'month' ? 'bg-blue-600 text-white' : 'text-gray-300'"
                                class="px-2 py-1 rounded text-xs font-semibold">Mes</button>
                            <button @click="setChartRange('custom')"
                                :class="chartRange === 'custom' ? 'bg-blue-600 text-white' : 'text-gray-300'"
                                class="px-2 py-1 rounded text-xs font-semibold">Rango</button>
                        </div>
                    </div>
                </div>

                <!-- Controles Móviles -->
                <div class="sm:hidden flex items-center gap-2 mb-2">
                    <div class="flex-1">
                        <label class="sr-only">Rango</label>
                        <select x-model="chartRange" @change="setChartRange(chartRange)"
                            class="w-full h-10 rounded-md bg-gray-800 border border-gray-600 text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="day">Día</option>
                            <option value="week">Semana</option>
                            <option value="month">Mes</option>
                            <option value="custom">Rango</option>
                        </select>
                    </div>
                </div>

                <!-- Etiqueta de periodo (del chart) -->
                <div class="mb-2 text-xs sm:text-sm text-gray-400" x-text="chartLabel || periodLabel"></div>

                <!-- Rango personalizado (UI mejorada) -->
                <div x-show="chartRange==='custom'" x-cloak class="mb-3">
                    <div class="rounded-xl border border-slate-600/50 bg-slate-800/60 p-3 sm:p-4 shadow-inner">
                        <!-- Header -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/20 text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2" />
                                    </svg>
                                </span>
                                <div class="leading-tight">
                                    <div class="font-semibold text-white text-sm sm:text-base">Rango personalizado
                                    </div>
                                    <div class="text-[12px] text-slate-400">Elige fechas o usa un preset</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <button
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-700/80 hover:bg-slate-700 text-slate-200 border border-slate-600/60"
                                    @click="[chartFrom, chartTo] = [chartTo, chartFrom]"
                                    :disabled="!chartFrom || !chartTo" title="Intercambiar fechas">
                                    ↔️
                                </button>
                                <button
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-white underline underline-offset-4"
                                    @click="chartFrom=''; chartTo=''">
                                    Limpiar
                                </button>
                            </div>
                        </div>

                        <!-- Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 sm:gap-3">
                            <label class="col-span-2">
                                <span class="sr-only">Desde</span>
                                <div
                                    class="flex items-center gap-2 rounded-lg bg-gray-900/60 border border-gray-700 px-3 h-11">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" />
                                    </svg>
                                    <input type="date" x-model="chartFrom"
                                        class="w-full bg-transparent text-gray-100 placeholder-slate-400 focus:outline-none">
                                </div>
                            </label>

                            <label class="col-span-2">
                                <span class="sr-only">Hasta</span>
                                <div
                                    class="flex items-center gap-2 rounded-lg bg-gray-900/60 border border-gray-700 px-3 h-11">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" />
                                    </svg>
                                    <input type="date" x-model="chartTo"
                                        class="w-full bg-transparent text-gray-100 placeholder-slate-400 focus:outline-none">
                                </div>
                            </label>

                            <div class="col-span-1 flex">
                                <button class="btn-primary w-full h-11" @click="applyCustomRange()"
                                    :disabled="!chartFrom || !chartTo || new Date(chartFrom) > new Date(chartTo)">
                                    Aplicar
                                </button>
                            </div>
                        </div>

                        <!-- Error / ayuda -->
                        <p x-show="chartFrom && chartTo && new Date(chartFrom) > new Date(chartTo)"
                            class="mt-2 text-xs font-medium text-amber-300 bg-amber-900/30 border border-amber-700/40 rounded-lg px-3 py-2">
                            La fecha <strong>Desde</strong> no puede ser mayor que <strong>Hasta</strong>.
                        </p>

                        <!-- Presets -->
                        <div class="mt-3 flex flex-wrap items-center gap-1.5">
                            <span class="text-[11px] text-slate-400 mr-1.5">Rápidos:</span>

                            <button
                                class="px-2.5 py-1.5 rounded-full text-[11px] font-semibold bg-slate-700/70 hover:bg-slate-700 text-slate-200 border border-slate-600/60"
                                @click="
            (()=>{ 
              const t=new Date(); const d=new Date(t); d.setDate(t.getDate()-6);
              chartFrom = d.toISOString().slice(0,10); chartTo = t.toISOString().slice(0,10);
              applyCustomRange();
            })()
          ">
                                Últimos 7 días
                            </button>

                            <button
                                class="px-2.5 py-1.5 rounded-full text-[11px] font-semibold bg-slate-700/70 hover:bg-slate-700 text-slate-200 border border-slate-600/60"
                                @click="
            (()=>{ 
              const t=new Date();
              const start=new Date(t.getFullYear(), t.getMonth(), 1);
              const end=new Date(t.getFullYear(), t.getMonth()+1, 0);
              chartFrom = start.toISOString().slice(0,10); chartTo = end.toISOString().slice(0,10);
              applyCustomRange();
            })()
          ">
                                Este mes
                            </button>

                            <button
                                class="px-2.5 py-1.5 rounded-full text-[11px] font-semibold bg-slate-700/70 hover:bg-slate-700 text-slate-200 border border-slate-600/60"
                                @click="
            (()=>{ 
              const t=new Date(); const d=new Date(t); d.setDate(t.getDate()-29);
              chartFrom = d.toISOString().slice(0,10); chartTo = t.toISOString().slice(0,10);
              applyCustomRange();
            })()
          ">
                                Últimos 30 días
                            </button>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="chartIngresos"></canvas>
                </div>

                <br>
                <!-- Totales del rango actual (estilo pro) -->
                <div class="mb-3 sm:mb-4 grid grid-cols-2 gap-2 sm:gap-4">
                    <!-- Ingresos Netos -->
                    <div
                        class="group relative overflow-hidden rounded-xl border border-emerald-400/20 bg-gradient-to-br from-emerald-900/20 via-slate-900/30 to-slate-900/10 backdrop-blur-sm px-3 py-3 sm:px-4 sm:py-4 transition-all hover:shadow-lg hover:-translate-y-0.5">
                        <!-- Glow decorativo -->
                        <span
                            class="pointer-events-none absolute -top-12 -right-12 h-28 w-28 rounded-full bg-emerald-500/10 blur-2xl"></span>

                        <div class="flex items-start justify-between">
                            <div class="space-y-0.5">
                                <div class="inline-flex items-center gap-1.5">
                                    <span
                                        class="text-[11px] sm:text-xs font-semibold tracking-wide text-emerald-300/90 uppercase">
                                        Ingresos netos
                                    </span>
                                    <span
                                        class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-400/10 text-emerald-200 border border-emerald-400/20">
                                        MXN
                                    </span>
                                </div>
                                <div class="text-[11px] sm:text-xs text-slate-300/70"
                                    x-text="chartLabel || periodLabel"></div>
                            </div>

                            <div
                                class="shrink-0 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/15 border border-emerald-400/20">
                                <svg class="w-4 h-4 text-emerald-300" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 17l6-6 4 4 7-7M14 7h7v7" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-2 sm:mt-3">
                            <div class="text-xl sm:text-2xl font-extrabold tracking-tight text-emerald-200"
                                x-text="money(chartTotals.neto)"></div>
                        </div>

                        <div class="mt-2 border-t border-emerald-400/10 pt-2">
                            <span class="text-[11px] sm:text-xs text-slate-300/70">Total del periodo
                                seleccionado</span>
                        </div>
                    </div>

                    <!-- Descuentos -->
                    <div
                        class="group relative overflow-hidden rounded-xl border border-rose-400/20 bg-gradient-to-br from-rose-900/20 via-slate-900/30 to-slate-900/10 backdrop-blur-sm px-3 py-3 sm:px-4 sm:py-4 transition-all hover:shadow-lg hover:-translate-y-0.5">
                        <!-- Glow decorativo -->
                        <span
                            class="pointer-events-none absolute -top-12 -right-12 h-28 w-28 rounded-full bg-rose-500/10 blur-2xl"></span>

                        <div class="flex items-start justify-between">
                            <div class="space-y-0.5">
                                <div class="inline-flex items-center gap-1.5">
                                    <span
                                        class="text-[11px] sm:text-xs font-semibold tracking-wide text-rose-300/90 uppercase">
                                        Descuentos
                                    </span>
                                    <span
                                        class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-rose-400/10 text-rose-200 border border-rose-400/20">
                                        MXN
                                    </span>
                                </div>
                                <div class="text-[11px] sm:text-xs text-slate-300/70"
                                    x-text="chartLabel || periodLabel"></div>
                            </div>

                            <div
                                class="shrink-0 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/15 border border-rose-400/20">
                                <svg class="w-4 h-4 text-rose-300" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 12H4M12 4v16" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-2 sm:mt-3">
                            <div class="text-xl sm:text-2xl font-extrabold tracking-tight text-rose-200"
                                x-text="'-' + money(chartTotals.descuentos)"></div>
                        </div>

                        <div class="mt-2 border-t border-rose-400/10 pt-2">
                            <span class="text-[11px] sm:text-xs text-slate-300/70">Total aplicado en el periodo</span>
                        </div>
                    </div>
                </div>


            </div>







            <!-- Onboarding: Primeros pasos (solo si no hay empleados NI servicios) -->
            <div x-cloak x-show="showFirstStepsModal" x-transition.opacity
                class="fixed inset-0 z-[200] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/70" @click="showFirstStepsModal=false"></div>

                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">¡Bienvenido! Configura lo básico</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Antes de empezar a agendar, entra a la sección de <strong>Servicios</strong>: ahí puedes
                                dar de alta todo lo necesario (categorias, empleados y servicios) en un mismo lugar.
                            </p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-700"
                            @click="showFirstStepsModal=false">✕</button>
                    </div>

                    <div class="p-6 space-y-4">


                        <div class="flex items-start gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">Servicios</h4>
                                <p class="text-sm text-gray-600">Crea los servicios con precio y duración.</p>
                            </div>
                            <a href="{{ route('app.servicios') }}"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                Ir a Servicios
                            </a>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
                        <button class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900"
                            @click="showFirstStepsModal=false">
                            Entendido
                        </button>
                    </div>
                </div>
            </div>



        </div>

        <script>
            function dashboard() {
                return {
                    tz: 'America/Mexico_City',
                    period: {
                        from: '',
                        to: '',
                        range: 'week'
                    },
                    kpi: {
                        bruto: 0,
                        desc_linea: 0,
                        desc_orden: 0,
                        desc_total: 0,
                        neto: 0,
                        citas_hoy: 0,
                        clientes_periodo: 0
                    },
                    ocupacionPct: 0,
                    todayList: [],
                    series: [],
                    week: {
                        start: '',
                        end: '',
                        eventos: []
                    },
                    loading: false,

                    /* ====== ESTADOS Y LABELS ====== */
                    statusInfo(st) {
                        const key = String(st || '').toLowerCase();
                        const map = {
                            pendiente: {
                                cls: 'status-pending',
                                label: 'Pendiente'
                            },
                            programada: {
                                cls: 'status-programmed',
                                label: 'Programada'
                            },
                            reprogramada: {
                                cls: 'status-reprog',
                                label: 'Reprogramada'
                            },
                            terminada: {
                                cls: 'status-done',
                                label: 'Terminada'
                            },
                            cancelada: {
                                cls: 'status-cancelled',
                                label: 'Cancelada'
                            },
                        };
                        return map[key] || {
                            cls: 'status-pending',
                            label: (st || 'Pendiente')
                        };
                    },

                    /* ====== MODAL DE CONFIRMACIÓN (BONITO) ====== */
                    confirm: {
                        open: false,
                        title: '',
                        message: '',
                        cta: 'Confirmar',
                        loading: false,
                        onAccept: null,
                        meta: null
                    },
                    openConfirm(opts = {}) {
                        this.confirm.title = opts.title || '¿Confirmar acción?';
                        this.confirm.message = opts.message || 'Esta acción no se puede deshacer.';
                        this.confirm.cta = opts.cta || 'Confirmar';
                        this.confirm.onAccept = typeof opts.onAccept === 'function' ? opts.onAccept : null;
                        this.confirm.meta = opts.meta || null;
                        this.confirm.loading = false;
                        this.confirm.open = true;
                    },
                    closeConfirm() {
                        this.confirm.open = false;
                        this.confirm.loading = false;
                        this.confirm.onAccept = null;
                        this.confirm.meta = null;
                    },
                    async doConfirm() {
                        if (typeof this.confirm.onAccept !== 'function') {
                            this.closeConfirm();
                            return;
                        }
                        try {
                            this.confirm.loading = true;
                            await this.confirm.onAccept();
                            this.closeConfirm();
                        } catch (e) {
                            console.error(e);
                            this.confirm.loading = false;
                        }
                    },

                    /* ====== UI helpers ====== */
                    get periodLabel() {
                        return `${this.formatDate(this.period.from)} → ${this.formatDate(this.period.to)}`;
                    },

                    get chartTotals() {
                        // Suma lo que está actualmente en la serie del chart (día/semana/mes/rango)
                        const arr = Array.isArray(this.series) ? this.series : [];
                        const neto = arr.reduce((acc, it) => acc + (Number(it.neto) || 0), 0);
                        const descuentos = arr.reduce((acc, it) => acc + (Number(it.descuentos) || 0), 0);
                        return {
                            neto,
                            descuentos
                        };
                    },


                    money(v) {
                        return new Intl.NumberFormat('es-MX', {
                            style: 'currency',
                            currency: 'MXN',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(v);
                    },
                    formatDate(dateStr) {
                        if (!dateStr) return '';
                        const date = this.naiveToDate(dateStr);
                        if (isNaN(date)) return '';
                        return new Intl.DateTimeFormat('es-MX', {
                            month: 'short',
                            day: '2-digit'
                        }).format(date);
                    },
                    // Robusto: acepta varios formatos
                    fmt(t) {
                        if (!t) return '';
                        const nums = String(t).match(/\d+/g);
                        if (!nums || nums.length < 3) return '';
                        let [Y, m, d, H = 0, Mi = 0, S = 0] = nums.map(n => parseInt(n, 10));
                        const dt = new Date(Y || 1970, (m || 1) - 1, d || 1, H, Mi, S);
                        if (isNaN(dt.getTime())) return '';
                        return new Intl.DateTimeFormat('es-MX', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false,
                            timeZone: this.tz
                        }).format(dt);
                    },
                    rangeHour(i, f) {
                        return `${this.fmt(i)} – ${this.fmt(f)}`;
                    },
                    get todayHuman() {
                        return new Intl.DateTimeFormat('es-MX', {
                            weekday: window.innerWidth < 640 ? 'short' : 'long',
                            day: '2-digit',
                            month: 'short',
                            timeZone: this.tz
                        }).format(new Date());
                    },

                    /* ====== Recordatorios WhatsApp (usando cliente.telefono) ====== */
                    remindWhatsApp(cita) {
                        if (!cita) return;

                        // 1) intenta con lo que ya viene en la tarjeta
                        let phone = this._waPhoneFrom(cita);

                        // 2) fallback: intenta con el detalle si no vino en la tarjeta
                        const tryDetail = async () => {
                            if (phone) return phone;
                            const det = await this.fetchCitaDetalle(cita.id); // esta función devolverá la cita
                            if (det) phone = this._waPhoneFrom(det);
                            return phone;
                        };

                        Promise.resolve(tryDetail()).then((p) => {
                            if (!p) {
                                alert('No encuentro un número de WhatsApp para este cliente.');
                                return;
                            }
                            const url = this._waBuildLink(cita, p);
                            window.open(url, '_blank', 'noopener,noreferrer');
                        });
                    },

                    _waPhoneFrom(cita) {
                        // Tu BD: clientes solo tiene "telefono"
                        const raw =
                            cita?.cliente?.telefono ??
                            cita?.telefono ?? // por si tu /app/citas/lista lo trae plano
                            ''; // compat: si luego agregas whatsapp/celular, puedes sumarlos aquí

                        const digits = String(raw).replace(/\D/g, '');
                        if (!digits) return '';

                        let msisdn = digits;

                        // Normaliza MX moderno:
                        // - "521..." -> "52" + resto
                        if (msisdn.startsWith('521') && msisdn.length >= 13) {
                            msisdn = '52' + msisdn.slice(3);
                        }

                        // - si empieza con 52 lo dejamos
                        // - si son 10 dígitos, anteponemos 52
                        if (msisdn.startsWith('52')) {
                            // ok
                        } else if (msisdn.length === 10) {
                            msisdn = '52' + msisdn;
                        }

                        // Evita números demasiado cortos
                        if (msisdn.length < 11) return '';
                        return msisdn;
                    },

                    _waBuildLink(cita, phone) {
                        const nombre = (cita?.cliente?.nombre || 'cliente').trim();

                        // Fecha “bonita”
                        const d = this.naiveToDate(cita?.hora_inicio);
                        const fechaFmt = isNaN(d) ? '' : new Intl.DateTimeFormat('es-MX', {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            timeZone: this.tz
                        }).format(d);

                        // Rango horario con helper existente
                        const rango = this.rangeHour(cita?.hora_inicio, cita?.hora_fin);

                        const msg =
                            `¡Hola ${nombre}! 👋\n` +
                            `🔔 *Recordatorio de cita*\n` +
                            `📅 *Fecha:* ${fechaFmt}\n` +
                            `🕒 *Horario:* ${rango}\n\n` +
                            `Llegar con 10 minutos de anticipación de lo contrario su cita será cancelada. Gracias.\n\n` +
                            `Si necesitas reprogramar, avísanos por aquí 🔁\n` +
                            `Por favor confirma con un 👍. ¡Gracias! 🙌`;

                        return `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;


                    },


                    /* Función auxiliar para móvil */
                    formatMobileDate(dateStr) {
                        if (!dateStr) return '';
                        const date = this.naiveToDate(dateStr);
                        if (isNaN(date)) return '';
                        return new Intl.DateTimeFormat('es-MX', {
                            weekday: 'short',
                            day: 'numeric',
                            timeZone: this.tz
                        }).format(date);
                    },

                    /* ====== Chart ====== */
                    chart: null,
                    drawChart() {
                        const ctx = document.getElementById('chartIngresos');
                        if (!ctx) return;

                        const labels = this.series.map(x => this.formatDate(x.date));
                        const netos = this.series.map(x => x.neto);
                        const descuentos = this.series.map(x => x.descuentos);

                        if (this.chart) this.chart.destroy();

                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels,
                                datasets: [{
                                        label: 'Ingresos Netos',
                                        data: netos,
                                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1,
                                        borderRadius: 6,
                                    },
                                    {
                                        label: 'Descuentos',
                                        data: descuentos,
                                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                        borderColor: 'rgba(239, 68, 68, 1)',
                                        borderWidth: 1,
                                        borderRadius: 6,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            color: '#e2e8f0',
                                            usePointStyle: true,
                                            padding: window.innerWidth < 640 ? 10 : 20,
                                            font: {
                                                size: window.innerWidth < 640 ? 10 : 12
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                        titleColor: 'white',
                                        bodyColor: 'white',
                                        borderColor: 'rgba(59, 130, 246, 0.5)',
                                        borderWidth: 1,
                                        cornerRadius: 8
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(71, 85, 105, 0.3)'
                                        },
                                        ticks: {
                                            color: '#94a3b8',
                                            font: {
                                                size: window.innerWidth < 640 ? 9 : 11
                                            },
                                            callback: function(value) {
                                                return new Intl.NumberFormat('es-MX', {
                                                    style: 'currency',
                                                    currency: 'MXN',
                                                    minimumFractionDigits: 0
                                                }).format(value);
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#94a3b8',
                                            font: {
                                                size: window.innerWidth < 640 ? 9 : 11
                                            }
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1000,
                                    easing: 'easeOutQuart'
                                }
                            }
                        });
                    },

                    /* ====== Chart (navegación por fechas) ====== */
                    chart: null,
                    chartRange: 'week', // 'day' | 'week' | 'month' | 'custom'
                    chartAnchor: '', // YYYY-MM-DD (pivote para day/week/month)
                    chartFrom: '', // para range personalizado
                    chartTo: '',
                    chartPeriod: {
                        from: '',
                        to: ''
                    }, // periodo usado por el chart

                    get chartLabel() {
                        const f = this.chartPeriod?.from || '';
                        const t = this.chartPeriod?.to || '';
                        if (!f && !t) return '';
                        const F = this.formatDate(f);
                        const T = this.formatDate(t);
                        return (F && T) ? `${F} — ${T}` : (F || T);
                    },

                    setChartRange(r) {
                        this.chartRange = r;
                        if (r === 'custom') return; // espera a que el usuario elija fechas y presione "Aplicar"
                        this.chartAnchor = this.todayIso();
                        this.fetchChart();
                    },

                    chartToday() {
                        if (this.chartRange === 'custom') return;
                        this.chartAnchor = this.todayIso();
                        this.fetchChart();
                    },

                    chartPrev() {
                        if (this.chartRange === 'custom') return;
                        const base = this.chartPeriod?.from || this.chartAnchor || this.todayIso();
                        let d = new Date(base + 'T00:00:00');
                        if (this.chartRange === 'day') d.setDate(d.getDate() - 1);
                        if (this.chartRange === 'week') d.setDate(d.getDate() - 7);
                        if (this.chartRange === 'month') d.setMonth(d.getMonth() - 1);
                        this.chartAnchor = d.toISOString().slice(0, 10);
                        this.fetchChart();
                    },

                    chartNext() {
                        if (this.chartRange === 'custom') return;
                        const base = this.chartPeriod?.from || this.chartAnchor || this.todayIso();
                        let d = new Date(base + 'T00:00:00');
                        if (this.chartRange === 'day') d.setDate(d.getDate() + 1);
                        if (this.chartRange === 'week') d.setDate(d.getDate() + 7);
                        if (this.chartRange === 'month') d.setMonth(d.getMonth() + 1);
                        this.chartAnchor = d.toISOString().slice(0, 10);
                        this.fetchChart();
                    },

                    applyCustomRange() {
                        if (!this.chartFrom || !this.chartTo) return;
                        // normaliza por si el usuario invierte el orden
                        const a = this.chartFrom <= this.chartTo ? this.chartFrom : this.chartTo;
                        const b = this.chartFrom <= this.chartTo ? this.chartTo : this.chartFrom;
                        this.chartFrom = a;
                        this.chartTo = b;
                        this.fetchChart();
                    },

                    todayIso() {
                        const d = new Date();
                        const m = String(d.getMonth() + 1).padStart(2, '0');
                        const day = String(d.getDate()).padStart(2, '0');
                        return `${d.getFullYear()}-${m}-${day}`;
                    },

                    async fetchChart() {
                        // Reusa tu endpoint existente del dashboard, mandando parámetros de rango.
                        // Soporta los anchors que ya tienes (ej: week_anchor). Si tu backend acepta
                        // 'day'/'month'/'custom', quedará plug&play.

                        const q = new URLSearchParams();
                        q.set('range', this.chartRange);

                        if (this.chartRange === 'custom') {
                            if (this.chartFrom) q.set('from', this.chartFrom);
                            if (this.chartTo) q.set('to', this.chartTo);
                        } else if (this.chartAnchor) {
                            const anchorParam =
                                this.chartRange === 'week' ? 'week_anchor' :
                                this.chartRange === 'day' ? 'day_anchor' :
                                this.chartRange === 'month' ? 'month_anchor' : 'anchor';
                            q.set(anchorParam, this.chartAnchor);
                        }

                        try {
                            const r = await fetch('/app/dashboard/data?' + q.toString(), {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            const d = await r.json();

                            // Actualiza SOLO los datos del chart y su periodo (no tocamos la agenda semanal)
                            const list = d?.series?.ingresos_por_dia || d?.series || [];
                            this.series = Array.isArray(list) ? list : [];

                            // Si el backend devuelve el periodo del rango pedido, úsalo para la etiqueta:
                            if (d?.period?.from || d?.period?.to) {
                                this.chartPeriod = {
                                    from: d.period.from || '',
                                    to: d.period.to || ''
                                };
                            } else {
                                // Fallback: si no vino periodo, infiere de la serie
                                if (this.series.length) {
                                    const first = this.series[0]?.date;
                                    const last = this.series[this.series.length - 1]?.date;
                                    this.chartPeriod = {
                                        from: first || '',
                                        to: last || ''
                                    };
                                }
                            }

                            await this.$nextTick();
                            this.drawChart();
                        } catch (e) {
                            console.error('fetchChart error', e);
                        }
                    },


                    /* ====== Agenda semanal ====== */
                    gridStart: '08:00',
                    gridEnd: '19:00',
                    rowH: 60,

                    get slotsPerDay() {
                        return this.minutesBetween(this.gridStart, this.gridEnd) / 30;
                    },
                    get hoursGrid() {
                        const out = [];
                        const [sh, sm] = this.gridStart.split(':').map(Number);
                        const [eh, em] = this.gridEnd.split(':').map(Number);
                        for (let h = sh; h <= eh; h++) out.push(String(h).padStart(2, '0') + ':00');
                        return out;
                    },
                    get weekTitle() {
                        if (!this.week.start || !this.week.end) return '';
                        return `${this.formatDate(this.week.start)} — ${this.formatDate(this.week.end)}`;
                    },
                    get weekDays() {
                        if (!this.week.start) return [];
                        const base = new Date(this.week.start + 'T00:00:00');
                        const today = new Date();
                        const out = [];
                        for (let i = 0; i < 7; i++) {
                            const d = new Date(base);
                            d.setDate(base.getDate() + i);
                            out.push({
                                day: d.getDate().toString().padStart(2, '0'),
                                short: new Intl.DateTimeFormat('es-MX', {
                                    timeZone: this.tz,
                                    weekday: 'short'
                                }).format(d),
                                isToday: d.toDateString() === today.toDateString()
                            });
                        }
                        return out;
                    },

                    // Posiciones de eventos
                    get positionedEvents() {
                        const res = [];
                        const base = new Date(this.week.start + 'T00:00:00');
                        for (const ev of this.week.eventos) {
                            const s = this.naiveToDate(ev.hora_inicio);
                            const e = this.naiveToDate(ev.hora_fin);
                            const dayIdx = (new Date(s.getFullYear(), s.getMonth(), s.getDate()) - base) / (1000 * 60 * 60 *
                                24);
                            if (dayIdx < 0 || dayIdx > 6) continue;
                            const top = this.pxFromTime(s);
                            const height = Math.max(40, this.pxDuration(s, e));
                            const relativeTop = this.pxFromTime(s);
                            res.push({
                                id: ev.id,
                                dayIndex: dayIdx,
                                top,
                                height,
                                relativeTop,
                                hora_inicio: ev.hora_inicio,
                                hora_fin: ev.hora_fin,
                                cliente: {
                                    id: ev.cliente?.id ?? null,
                                    nombre: ev.cliente?.nombre || 'Cliente',
                                    telefono: ev.cliente?.telefono || ev.telefono || ''
                                },
                                estado: ev.estado || 'pendiente'
                            });


                        }
                        return res;
                    },

                    get totalCitasSemana() {
                        return Array.isArray(this.week?.eventos) ? this.week.eventos.length : 0;
                    },
                    get topClientesSemana() {
                        if (!Array.isArray(this.week?.eventos) || !this.week.eventos.length) return [];
                        const map = new Map();
                        for (const ev of this.week.eventos) {
                            const id = ev?.cliente?.id ?? '__sin_id__';
                            const nombre = ev?.cliente?.nombre || 'Cliente';
                            const cur = map.get(id) || {
                                id,
                                nombre,
                                count: 0
                            };
                            cur.count++;
                            map.set(id, cur);
                        }
                        return [...map.values()].sort((a, b) => b.count - a.count).slice(0, 3);
                    },

                    get csrf() {
                        const el = document.querySelector('meta[name="csrf-token"]');
                        return el ? el.getAttribute('content') : '';
                    },

                    /* ====== Aux ====== */
                    minutesBetween(t1, t2) {
                        const [h1, m1] = t1.split(':').map(Number);
                        const [h2, m2] = t2.split(':').map(Number);
                        return (h2 * 60 + m2) - (h1 * 60 + m1);
                    },
                    // Robusto para múltiples formatos
                    naiveToDate(str) {
                        if (!str) return new Date(NaN);
                        const nums = String(str).match(/\d+/g);
                        if (!nums || nums.length < 3) return new Date(NaN);
                        let [Y, m, d, H = 0, Mi = 0, S = 0] = nums.map(n => parseInt(n, 10));
                        return new Date(Y || 1970, (m || 1) - 1, d || 1, H, Mi, S);
                    },
                    pxFromTime(date) {
                        const minsStart = date.getHours() * 60 + date.getMinutes();
                        const [h0, m0] = this.gridStart.split(':').map(Number);
                        const offset = minsStart - (h0 * 60 + m0);
                        return (offset / 30) * this.rowH;
                    },
                    pxDuration(s, e) {
                        const mins = Math.max(0, (e - s) / 60000);
                        return (mins / 30) * this.rowH;
                    },

                    /* ====== Fetch & navegación ====== */
                    anchor: '',
                    async fetch(range = 'week') {
                        this.loading = true;
                        try {
                            const q = new URLSearchParams();
                            q.set('range', range);
                            if (this.anchor) q.set('week_anchor', this.anchor);
                            const r = await fetch('/app/dashboard/data?' + q.toString());
                            const d = await r.json();
                            this.period = d.period;
                            this.kpi = d.kpi;
                            this.series = d.series.ingresos_por_dia;
                            this.todayList = d.hoy || [];
                            this.week = d.semana;
                            this.ocupacionPct = d.kpi.ocupacion_pct ?? 0;
                            setTimeout(() => this.drawChart(), 100);
                        } catch (error) {
                            console.error('Error fetching dashboard data:', error);
                        } finally {
                            this.loading = false;
                        }
                    },
                    goPrevWeek() {
                        if (!this.week.start || this.loading) return;
                        const d = new Date(this.week.start + 'T00:00:00');
                        d.setDate(d.getDate() - 7);
                        this.anchor = d.toISOString().slice(0, 10);
                        this.fetch('week');
                    },
                    goNextWeek() {
                        if (!this.week.start || this.loading) return;
                        const d = new Date(this.week.start + 'T00:00:00');
                        d.setDate(d.getDate() + 7);
                        this.anchor = d.toISOString().slice(0, 10);
                        this.fetch('week');
                    },
                    goThisWeek() {
                        if (this.loading) return;
                        this.anchor = '';
                        this.fetch('week');
                    },

                    /* ====== Modal revisar (principal) ====== */
                    selectedId: null,
                    showModal: false,
                    citaSel: null,
                    modal: {
                        title: 'Cita',
                        subtitle: ''
                    },

                    /* ====== Modal reprogramar (separado) ====== */
                    showReprogModal: false,
                    reprogLoading: false,
                    reprog: {
                        fecha: '',
                        hora_inicio: '',
                        hora_fin: ''
                    },

                    async openModal(id) {
                        this.selectedId = id;

                        // 1) Toma la cita ya renderizada (mismo horario que ves en la tarjeta)
                        const base =
                            (this.todayList || []).find(x => x.id === id) ||
                            (this.week?.eventos || []).find(x => x.id === id) ||
                            null;

                        // Si hay base, arráncala como citaSel para que el horario sea idéntico
                        this.citaSel = base ? JSON.parse(JSON.stringify(base)) : null;

                        // 2) Trae el detalle (items, descuentos, etc.) PERO conserva el horario de `base`
                        const prevHoras = base ? {
                            hi: base.hora_inicio,
                            hf: base.hora_fin
                        } : null;
                        await this.fetchCitaDetalle(id); // esta función hoy asigna this.citaSel = found;

                        // Si el fetch cambió la referencia, vuelve a fijar las horas “buenas”
                        if (this.citaSel && prevHoras) {
                            this.citaSel.hora_inicio = prevHoras.hi;
                            this.citaSel.hora_fin = prevHoras.hf;
                        }

                        // 3) Título/subtítulo del modal usando la misma fuente de horas
                        this.modal.title = this.citaSel?.cliente?.nombre || 'Cita';
                        this.modal.subtitle = this.rangeHour(this.citaSel?.hora_inicio, this.citaSel?.hora_fin);

                        this.showModal = true;
                    },

                    closeModal() {
                        this.showModal = false;
                        this.citaSel = null;
                        this.selectedId = null;
                        this.resetReprogData();
                    },

                    openReprogModal() {
                        if (!this.citaSel) return;

                        // Inicializar datos de reprogramación
                        const d = this.naiveToDate(this.citaSel.hora_inicio);
                        this.reprog.fecha = !isNaN(d) ? d.toISOString().slice(0, 10) : '';
                        if (!isNaN(d)) {
                            this.reprog.hora_inicio = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes())
                                .padStart(2, '0');
                        } else {
                            this.reprog.hora_inicio = '';
                        }
                        this.recalcEnd();

                        this.showReprogModal = true;
                    },

                    closeReprogModal() {
                        this.showReprogModal = false;
                        this.resetReprogData();
                    },

                    resetReprogData() {
                        this.reprog = {
                            fecha: '',
                            hora_inicio: '',
                            hora_fin: ''
                        };
                        this.reprogLoading = false;
                    },

                    async fetchCitaDetalle(id) {
                        const r = await fetch('/app/citas/lista', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const d = await r.json();
                        const found = (d.citas || []).find(x => x.id === id);

                        if (found) {
                            this.citaSel = found;

                            // Calcula totales si no vienen
                            if (!this.citaSel.totales) {
                                let base = 0,
                                    descLineas = 0;
                                for (const it of (found.items || [])) {
                                    base += (it.precio_servicio_snapshot || 0) * Math.max(1, it.cantidad || 1);
                                    descLineas += it.descuento || 0;
                                }
                                const subtotal = Math.max(0, base - descLineas);
                                const descOrden = Math.min(subtotal, found.descuento || 0);
                                const neto = Math.max(0, subtotal - descOrden);
                                this.citaSel.totales = {
                                    bruto: base,
                                    desc_lineas: descLineas,
                                    subtotal,
                                    desc_orden: descOrden,
                                    neto
                                };
                            }
                        }

                        // IMPORTANTE: devolver la cita para que el fallback de WhatsApp pueda usarla
                        return found || null;
                    },


                    totalMinsCita(cita) {
                        let mins = 0;
                        for (const it of (cita?.items || [])) {
                            mins += (it.duracion_minutos_snapshot || 0) * Math.max(1, it.cantidad || 1);
                        }
                        return Math.max(0, mins);
                    },

                    recalcEnd() {
                        if (!this.citaSel || !this.reprog.fecha || !this.reprog.hora_inicio) return;
                        const start = new Date(this.reprog.fecha + 'T' + this.reprog.hora_inicio + ':00');
                        const mins = this.totalMinsCita(this.citaSel);
                        const end = new Date(start.getTime() + mins * 60000);
                        const eh = String(end.getHours()).padStart(2, '0');
                        const em = String(end.getMinutes()).padStart(2, '0');
                        this.reprog.hora_fin = `${eh}:${em}`;
                    },


                    /* ====== Acciones ====== */
                    async markDone(id) {
                        if (!id) return;

                        // Busca la cita en las listas visibles
                        const item =
                            (this.todayList || []).find(x => x.id === id) ||
                            (this.week?.eventos || []).find(x => x.id === id) ||
                            null;

                        const prev = item ? item.estado : null;

                        // UI optimista: marca como terminada localmente
                        if (item) item.estado = 'terminada';

                        try {
                            const r = await fetch(`/app/citas/${id}/estado`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrf,
                                    'Accept': 'application/json',
                                    // 'X-Requested-With': 'XMLHttpRequest', // opcional si tu middleware lo espera
                                },
                                body: JSON.stringify({
                                    estado: 'terminada'
                                })
                            });

                            if (!r.ok) throw new Error(`HTTP ${r.status}`);

                            // Refresca datos para mantener todo consistente
                            await this.fetch(this.period.range);

                        } catch (e) {
                            console.error(e);
                            // Revierte si falló
                            if (item) item.estado = prev;
                            // Opcional: dar feedback
                            // alert('No se pudo marcar como terminada. Intenta de nuevo.');
                        }
                    },

                    askCancel(cita) {
                        if (!cita) return;
                        const cliente = cita?.cliente?.nombre || 'Cliente';
                        const horario = (cita?.hora_inicio && cita?.hora_fin) ?
                            `${this.fmt(cita.hora_inicio)} – ${this.fmt(cita.hora_fin)}` :
                            '';

                        this.openConfirm({
                            title: 'Cancelar cita',
                            message: '¿Seguro que deseas cancelar esta cita?',
                            cta: 'Sí, cancelar',
                            meta: {
                                cliente,
                                horario
                            },
                            onAccept: async () => {
                                await fetch(`/app/citas/${cita.id}/estado`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': this.csrf,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        estado: 'cancelada'
                                    })
                                });
                                await this.fetch(this.period.range);
                                this.closeModal();
                            }
                        });
                    },

                    async submitReprog() {
                        if (!this.citaSel || !this.reprog.fecha || !this.reprog.hora_inicio) return;

                        this.reprogLoading = true;
                        try {
                            await fetch(`/app/citas/${this.citaSel.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrf,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    fecha: this.reprog.fecha,
                                    hora_inicio: this.reprog.hora_inicio,
                                    estado: 'reprogramada'
                                })
                            });
                            await this.fetch(this.period.range);
                            this.closeReprogModal();
                            this.closeModal();
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.reprogLoading = false;
                        }
                    },

                    showFirstStepsModal: false,
                    needEmps: false,
                    needServices: false,

                    async checkFirstSteps() {
                        try {
                            const [re, rs] = await Promise.all([
                                fetch('/app/empleados/lista', {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }),
                                fetch('/app/servicios/lista', {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                }),
                            ]);

                            let empCount = 0,
                                servCount = 0;

                            if (re.ok) {
                                const de = await re.json();
                                // soporta { empleados: [...] } o { data: [...] }
                                empCount = Array.isArray(de.empleados) ? de.empleados.length :
                                    Array.isArray(de.data) ? de.data.length : 0;
                            }

                            if (rs.ok) {
                                const ds = await rs.json();
                                // soporta { servicios: [...] } o { data: [...] }
                                servCount = Array.isArray(ds.servicios) ? ds.servicios.length :
                                    Array.isArray(ds.data) ? ds.data.length : 0;
                            }

                            this.needEmps = empCount === 0;
                            this.needServices = servCount === 0;

                            // requisito: mostrar SOLO si NO hay empleados NI servicios
                            this.showFirstStepsModal = (empCount === 0 && servCount === 0);
                        } catch (e) {
                            console.error(e);
                        }
                    },

                    /* ====== Init ====== */
                    async init() {
                        await this.fetch('week');
                        await this.checkFirstSteps();

                    }
                }
            }

            // Mejoras táctiles para móvil
            document.addEventListener('DOMContentLoaded', function() {
                let lastTouchEnd = 0;
                document.addEventListener('touchend', function(event) {
                    const now = (new Date()).getTime();
                    if (now - lastTouchEnd <= 300) event.preventDefault();
                    lastTouchEnd = now;
                }, false);
            });
        </script>


    </div>
</x-app-layout>
