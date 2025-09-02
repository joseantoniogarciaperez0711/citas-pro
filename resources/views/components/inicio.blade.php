<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8" />
    <!-- Viewport anti-zoom y safe areas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,
             user-scalable=no, viewport-fit=cover, interactive-widget=resizes-content" />
    <title>CitasPro — móvil-first premium</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        accent: { 50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9', 800: '#5b21b6', 900: '#4c1d95' }
                    },
                    boxShadow: {
                        soft: '0 4px 20px rgba(0,0,0,0.06)',
                        softlg: '0 8px 32px rgba(0,0,0,0.08)',
                        inset: 'inset 0 1px 3px rgba(0,0,0,0.08)'
                    },
                    borderRadius: { xl2: '1.25rem' }
                }
            }
        }
    </script>

    <!-- Theme color para PWA / Android -->
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0b1220" media="(prefers-color-scheme: dark)">

    <style>
        /* --------- Animaciones suaves --------- */
        .animate-fade-in {
            animation: fadeIn .25s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .animate-pop {
            animation: pop .18s ease-out;
        }

        @keyframes pop {
            from {
                transform: scale(.98)
            }

            to {
                transform: scale(1)
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .animate-fade-in,
            .animate-pop {
                animation: none !important;
            }

            html:focus-within {
                scroll-behavior: auto;
            }
        }

        /* --------- Scrollbar (desktop) --------- */
        @media (min-width: 768px) {
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px
            }

            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 6px
            }

            .dark ::-webkit-scrollbar-thumb {
                background: #334155
            }

            ::-webkit-scrollbar-track {
                background: transparent
            }
        }

        /* --------- Safe areas iOS --------- */
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }

        .safe-top {
            padding-top: env(safe-area-inset-top);
        }

        /* --------- Bottom-sheet en móvil --------- */
        .sheet {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .sheet::before {
            content: "";
            display: block;
            width: 36px;
            height: 5px;
            border-radius: 999px;
            background: rgba(148, 163, 184, .7);
            margin: .5rem auto 0;
        }

        /* Ocultar bottom-nav/FAB al abrir teclado (heurística) */
        .kb-open #bottom-nav,
        .kb-open #fab {
            transform: translateY(120%);
            opacity: 0;
            pointer-events: none;
            transition: transform .2s ease, opacity .2s ease;
        }

        /* --------- Accesibilidad táctil --------- */
        html,
        body {
            -webkit-text-size-adjust: 100%;
        }

        button,
        [role="button"],
        .tap {
            min-height: 44px;
        }

        *:focus-visible {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, .6) !important;
            border-radius: .75rem;
        }

        a,
        button,
        [role="button"],
        label {
            touch-action: manipulation;
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* --------- Glass premium --------- */
        .glass {
            background: rgba(255, 255, 255, .72);
            backdrop-filter: blur(10px);
        }

        .dark .glass {
            background: rgba(2, 6, 23, .66);
        }

        /* --------- Legibilidad de campos (tema oscuro incluido) --------- */
        input,
        select,
        textarea {
            color: #0f172a;
            /* slate-900 */
            caret-color: #3b82f6;
            /* primary-500 */
        }

        .dark input,
        .dark select,
        .dark textarea {
            color: #f1f5f9;
            /* slate-100 */
            caret-color: #60a5fa;
            /* primary-400 */
        }

        ::placeholder {
            color: #94a3b8;
        }

        /* slate-400 */
        .dark ::placeholder {
            color: #64748b;
        }

        /* slate-500 */

        /* iOS hace zoom si font-size<16px en inputs: forzamos 16px en móvil */
        @media (max-width: 767px) {

            input,
            select,
            textarea {
                font-size: 16px !important;
            }
        }

        /* Chips utilitarios (desktop) */
        .chip {
            padding: .375rem .75rem;
            border-radius: .75rem;
            border-width: 1px;
            font-size: .75rem
        }

        .chip--b {
            border-color: rgb(191 219 254);
            color: rgb(29 78 216);
            background: rgb(239 246 255)
        }

        .chip--e {
            border-color: rgb(167 243 208);
            color: rgb(4 120 87);
            background: rgb(236 253 245)
        }

        .chip--p {
            border-color: rgb(221 214 254);
            color: rgb(91 33 182);
            background: rgb(245 243 255)
        }

        .chip--r {
            border-color: rgb(254 205 211);
            color: rgb(190 18 60);
            background: rgb(255 241 242)
        }




        /* ====== INICIO MÓVIL SIN SCROLL (solo CSS, sin JS) ====== */
        @media (max-width: 767px) {

            /* 1) Desactiva el scroll SOLO cuando Inicio está visible */
            body:has(#view-dashboard:not(.hidden)) {
                overflow-y: hidden;
            }

            /* 2) Compactar separación vertical del contenedor de Inicio */
            #view-dashboard>*+* {
                margin-top: .5rem !important;
            }

            /* sobreescribe space-y-6 */
            #content {
                padding-top: .75rem !important;
                padding-bottom: .75rem !important;
            }

            /* 3) KPIs: de carrusel a grid (3 columnas, sin desplazamiento) */
            #view-dashboard .kpi-container {
                overflow: visible !important;
            }

            #kpi-scroll {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr);
                gap: .5rem !important;
                overflow: hidden !important;
                padding-bottom: 0 !important;
            }

            #kpi-scroll>div {
                width: auto !important;
                max-width: none !important;
                padding: .75rem !important;
            }

            #kpi-scroll .text-3xl {
                font-size: 1.25rem !important;
            }

            /* cifras más contenidas */

            /* 4) Acciones rápidas compactas (misma fila, menos alto) */
            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 {
                gap: .5rem !important;
                margin-top: .5rem !important;
                padding-left: .25rem !important;
                padding-right: .25rem !important;
            }

            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 button {
                padding-top: .75rem !important;
                padding-bottom: .75rem !important;
            }

            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 svg {
                height: 18px !important;
                width: 18px !important;
            }

            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 span {
                font-size: .75rem !important;
            }

            /* 5) Agenda móvil: sin “tabs” y mostrando SOLO 1 tarjeta del día */
            #mobile-day-tabs {
                display: none !important;
            }

            #mobile-agenda {
                padding-left: .75rem !important;
                padding-right: .75rem !important;
            }

            #mobile-agenda>*:nth-child(n+2) {
                display: none !important;
            }

            /* deja solo la primera tarjeta/estado */
            #mobile-agenda .p-4 {
                padding: .75rem !important;
            }

            /* cartas más compactas */
            #mobile-agenda .text-lg {
                font-size: 1rem !important;
            }

            /* 6) Encabezado del bloque Agenda: menos alto en móvil */
            /* reduce paddings del header dentro de la tarjeta de Agenda */
            #view-dashboard .bg-white.dark\:bg-slate-900.rounded-2xl.border.border-slate-200\/60.dark\:border-slate-800.shadow-soft>.p-4.md\:p-5.border-b {
                padding-top: .5rem !important;
                padding-bottom: .5rem !important;
            }
        }



        /* ====== PARCHE: restaurar calendario móvil como antes ====== */
        @media (max-width: 767px) {

            /* 1) Permitir scroll normal en Inicio (para que la agenda completa se vea) */
            body:has(#view-dashboard:not(.hidden)) {
                overflow-y: auto;
                /* antes lo habíamos bloqueado */
            }

            /* 2) Volver a mostrar las píldoras de días (tabs) */
            #mobile-day-tabs {
                display: flex !important;
                /* cancelamos el display:none anterior */
            }

            /* 3) Mostrar todos los elementos de la agenda (quitamos el recorte a 1) */
            #mobile-agenda>* {
                display: block !important;
            }

            /* 4) Restaurar paddings/tamaños originales de la agenda móvil */
            #mobile-agenda {
                padding-left: 1rem !important;
                /* px-4 */
                padding-right: 1rem !important;
                /* px-4 */
            }

            #mobile-agenda .p-4 {
                padding: 1rem !important;
                /* p-4 */
            }

            #mobile-agenda .text-lg {
                font-size: 1.125rem !important;
                /* text-lg */
                line-height: 1.75rem !important;
            }
        }


        /* ====== PARCHE FINAL 100% MÓVIL (sin JS) ====== */
        @media (max-width: 767px) {

            /* 0) Alturas reales de viewport en móviles (iOS/Android) */
            html,
            body {
                height: 100%;
            }

            #app {
                min-height: 100vh;
            }

            @supports (min-height: 100dvh) {
                #app {
                    min-height: 100dvh;
                }

                /* usa unidad dinámica que no “corta” con la barra del navegador */
            }

            /* 1) Flex fix: evita que el contenedor corte el contenido por min-height:auto de Flexbox */
            main {
                min-height: 0 !important;
            }

            #content {
                min-height: 0 !important;
            }

            /* 2) Tabs del calendario: sticky correcto debajo del header (ya no se meten ni se cortan) */
            #mobile-day-tabs {
                position: sticky !important;
                top: 0 !important;
                /* antes estaba con calc(56px+safe-area) y podía quedar mal en algunos equipos */
                z-index: 10 !important;
            }

            /* 3) Espacio extra al final para que el bottom-nav no tape el último elemento */
            :root {
                --bn-h: 64px;
            }

            /* alto aproximado del bottom nav */
            #content {
                padding-bottom: calc(var(--bn-h) + env(safe-area-inset-bottom) + 12px) !important;
                padding-top: 8px !important;
                /* un poco de aire superior */
            }

            /* 4) KPIs compactos en grid (sin carrusel que desborde) */
            #view-dashboard .kpi-container {
                overflow: visible !important;
            }

            #kpi-scroll {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr);
                gap: .5rem !important;
                overflow: hidden !important;
                padding-bottom: 0 !important;
            }

            #kpi-scroll>div {
                width: auto !important;
                max-width: none !important;
                padding: .75rem !important;
            }

            #kpi-scroll .text-3xl {
                font-size: 1.25rem !important;
            }

            /* cifras más contenidas */

            /* 5) Acciones rápidas un poco más compactas (sin perder tactilidad) */
            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 {
                gap: .5rem !important;
                margin-top: .5rem !important;
                padding-left: .25rem !important;
                padding-right: .25rem !important;
            }

            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 button {
                padding-top: .75rem !important;
                padding-bottom: .75rem !important;
            }

            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 svg {
                height: 18px !important;
                width: 18px !important;
            }

            #view-dashboard .grid.grid-cols-3.gap-3.mt-6.px-2 span {
                font-size: .75rem !important;
            }

            /* 6) Agenda móvil con su padding original (se ve completa) */
            #mobile-agenda {
                padding-left: 1rem !important;
                /* px-4 */
                padding-right: 1rem !important;
                /* px-4 */
            }
        }

        /* ====== AGENDA MÓVIL ULTRA-COMPACTA (sin JS) ====== */
        @media (max-width: 767px) {

            /* — Contenedor de la tarjeta de Agenda (el que contiene #mobile-day-tabs) — */
            #view-dashboard .rounded-2xl:has(#mobile-day-tabs) {
                border-radius: 1rem !important;
            }

            /* Encabezado de la Agenda (título + botones) */
            #view-dashboard .rounded-2xl:has(#mobile-day-tabs)>[class*="border-b"] {
                padding: .5rem .75rem !important;
                /* antes p-4 */
            }

            #view-dashboard .rounded-2xl:has(#mobile-day-tabs)>[class*="border-b"] .text-lg {
                font-size: 1rem !important;
                /* reduce "Agenda semanal" */
                line-height: 1.5rem !important;
            }

            #view-dashboard .rounded-2xl:has(#mobile-day-tabs)>[class*="border-b"] .flex.items-center.gap-2 {
                gap: .375rem !important;
                /* controles Ant / Sig / Hoy más juntos */
            }

            #view-dashboard .rounded-2xl:has(#mobile-day-tabs)>[class*="border-b"] button {
                padding: .35rem .55rem !important;
                /* botones compactos */
                font-size: .75rem !important;
                border-radius: .6rem !important;
            }

            #week-label {
                font-size: .75rem !important;
                padding-left: .25rem !important;
                padding-right: .25rem !important;
            }

            /* — Píldoras de días (tabs) — */
            #mobile-day-tabs {
                padding: .35rem .5rem !important;
                /* menos alto */
                gap: .35rem !important;
            }

            #mobile-day-tabs button {
                padding: .35rem .5rem !important;
                border-radius: .75rem !important;
            }

            #mobile-day-tabs .text-xs {
                /* “Dom, Lun…” y “Hoy” */
                font-size: .7rem !important;
                line-height: 1rem !important;
            }

            #mobile-day-tabs .text-lg {
                /* número del día */
                font-size: .95rem !important;
                line-height: 1.2rem !important;
                margin-top: .1rem !important;
            }

            /* — Lista de Agenda (tarjetas del día) — */
            #mobile-agenda {
                padding-left: .75rem !important;
                /* px-3 */
                padding-right: .75rem !important;
                padding-bottom: .5rem !important;
            }

            /* Reduce el espacio vertical entre tarjetas */
            #mobile-agenda.space-y-3> :not([hidden])~ :not([hidden]) {
                margin-top: .5rem !important;
                /* antes .75rem */
            }

            /* Tarjeta individual */
            #mobile-agenda .rounded-2xl {
                border-radius: 1rem !important;
            }

            #mobile-agenda .p-4 {
                padding: .75rem !important;
                /* antes 1rem */
            }

            #mobile-agenda .text-lg.font-bold {
                font-size: 1rem !important;
                /* nombre del cliente */
                line-height: 1.4rem !important;
            }

            #mobile-agenda .text-sm {
                font-size: .85rem !important;
                /* hora + servicio */
                line-height: 1.2rem !important;
            }

            #mobile-agenda .text-xs {
                font-size: .72rem !important;
                /* notas, etiquetas */
                line-height: 1rem !important;
            }

            #mobile-agenda svg {
                width: 14px !important;
                height: 14px !important;
                /* iconos más pequeños */
            }

            /* — Ajustes generales para que nada se tape con el bottom-nav — */
            :root {
                --bn-h: 64px;
            }

            #content {
                padding-bottom: calc(var(--bn-h) + env(safe-area-inset-bottom) + 8px) !important;
            }
        }


        /* ====== INICIO — Resumen tipo app (gradiente + anillo) ====== */
        .home-hero {
            background:
                radial-gradient(120% 150% at 0% 0%, rgba(59, 130, 246, .25), transparent 55%),
                linear-gradient(135deg, #0b1220 0%, #0f172a 45%, #0b1220 100%);
            color: #e5e7eb;
        }

        .light .home-hero {
            background:
                radial-gradient(120% 150% at 0% 0%, rgba(99, 102, 241, .12), transparent 55%),
                linear-gradient(135deg, #f8fafc 0%, #eef2ff 45%, #f8fafc 100%);
            color: #0f172a;
        }

        .ring-pct {
            --pct: 0;
            /* se setea por JS */
            --ring-bg: #0b1220;
            --ring-color: #60a5fa;
            background:
                radial-gradient(closest-side, var(--ring-bg) 74%, transparent 75% 99%, var(--ring-bg) 0),
                conic-gradient(var(--ring-color) calc(var(--pct)*1%), rgba(99, 102, 241, .20) 0);
        }

        .dark .ring-pct {
            --ring-bg: #0b1220;
        }

        .light .ring-pct {
            --ring-bg: #ffffff;
        }

        /* ====== BUGFIX: agenda móvil debe listar TODAS las citas ====== */
        /* Si en algún lugar quedó la regla que ocultaba a partir del 2º hijo,
   la neutralizamos aquí con mayor especificidad + !important */
        @media (max-width: 767px) {
            #view-dashboard #mobile-agenda>* {
                display: block !important;
            }

            body:has(#view-dashboard:not(.hidden)) {
                overflow-y: auto;
            }

            /* asegura scroll */
        }
    </style>
</head>

<body class="h-full bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 antialiased overflow-x-hidden">
    <div id="app" class="min-h-screen flex">

        <!-- Sidebar (Desktop) -->
        <aside
            class="w-72 glass border-r border-slate-200/60 dark:border-slate-800 hidden md:flex md:flex-col shadow-soft">
            <div class="p-6 border-b border-slate-200/60 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-primary-600 text-white grid place-content-center font-bold">C
                    </div>
                    <div>
                        <div class="text-lg font-bold">CitasPro</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Gestión profesional</div>
                    </div>
                </div>
            </div>
            <nav class="p-3 space-y-1" id="sidebar-nav"></nav>
            <div class="mt-auto p-4 text-xs text-slate-400 dark:text-slate-500">
                <div class="font-medium">v1.4 • Mobile premium</div>
            </div>
        </aside>

        <!-- Main -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header
                class="glass safe-top border-b border-slate-200/60 dark:border-slate-800 sticky top-0 z-30 shadow-soft">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center gap-3">
                    <button
                        class="md:hidden p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        onclick="toggleMobileMenu()" aria-label="Abrir menú">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h1 class="text-lg sm:text-xl font-bold truncate flex-1" id="page-title">Inicio</h1>

                    <div class="hidden sm:flex items-center gap-2 mr-2">
                        <button id="btn-quick-client"
                            class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm tap"
                            onclick="openModal('modal-client')">+ Cliente</button>
                        <button id="btn-quick-service"
                            class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm tap"
                            onclick="openModal('modal-service')">+ Servicio</button>
                    </div>

                    <button id="theme-toggle"
                        class="p-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800"
                        title="Cambiar tema" onclick="toggleTheme()">🌙</button>
                    <button
                        class="px-3 sm:px-4 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 transition-all text-sm font-medium shadow-soft tap"
                        onclick="openModal('modal-appointment')">+ Cita</button>
                </div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-3">
                    <input id="global-search" placeholder="Buscar clientes, servicios o citas…" class="w-full px-4 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-inset
                        text-sm md:text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        oninput="onGlobalSearch(this.value)" />
                </div>
            </header>

            <!-- Content -->
            <section id="content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 flex-1">

                <div id="view-dashboard" class="space-y-6 animate-fade-in">

                    <!-- RESUMEN estilo app -->
                    <div
                        class="home-hero rounded-2xl border border-slate-200/10 dark:border-slate-800 shadow-soft p-4 md:p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="text-sm opacity-80">Ocupación semanal</div>
                                <div class="mt-1 text-3xl font-extrabold"><span id="home-occ">0</span>%</div>
                                <div class="text-xs opacity-70">Basado en tu horario de atención (L–V).</div>

                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    <div class="rounded-xl bg-white/5 dark:bg-white/5 border border-white/10 p-3">
                                        <div class="text-xs opacity-80">Citas de hoy</div>
                                        <div class="mt-1 text-2xl font-bold" id="home-today">0</div>
                                    </div>
                                    <div class="rounded-xl bg-white/5 dark:bg-white/5 border border-white/10 p-3">
                                        <div class="text-xs opacity-80">Clientes</div>
                                        <div class="mt-1 text-2xl font-bold" id="home-clients">0</div>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <div id="home-occ-ring"
                                    class="ring-pct h-24 w-24 rounded-full grid place-content-center border border-white/10">
                                    <div class="text-sm font-bold" id="home-occ-ring-label">0%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones rápidas -->
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <button class="px-3 py-3 rounded-xl bg-primary-600 text-white font-medium shadow-soft tap"
                                onclick="openModal('modal-appointment')">+ Cita</button>
                            <button class="px-3 py-3 rounded-xl border border-slate-200/20 bg-white/10 font-medium tap"
                                onclick="openModal('modal-client')">+ Cliente</button>
                            <button class="px-3 py-3 rounded-xl border border-slate-200/20 bg-white/10 font-medium tap"
                                onclick="openModal('modal-service')">+ Servicio</button>
                        </div>
                    </div>

                    <!-- PRÓXIMAS DE HOY -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft overflow-hidden">
                        <div
                            class="p-4 md:p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div class="text-lg font-bold">Próximas de hoy</div>
                            <button class="text-sm text-primary-600 dark:text-primary-400 hover:underline tap"
                                onclick="goTodayWeek()">Ver en agenda</button>
                        </div>
                        <div id="today-upcoming" class="p-4 space-y-3"></div>
                    </div>

                    <!-- AGENDA SEMANAL (igual que antes) -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft overflow-hidden">
                        <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-lg font-bold">Agenda semanal</div>
                                <div class="flex items-center gap-2">
                                    <button
                                        class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm tap"
                                        onclick="prevWeek()">← Ant</button>
                                    <div class="text-sm text-slate-500 dark:text-slate-400 font-medium px-2"
                                        id="week-label">—</div>
                                    <button
                                        class="px-3 py-2 rounded-lg border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm tap"
                                        onclick="nextWeek()">Sig →</button>
                                    <button
                                        class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 text-sm tap"
                                        onclick="goTodayWeek()">Hoy</button>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile: pills + list -->
                        <div class="block md:hidden">
                            <div id="mobile-day-tabs"
                                class="sticky top-[calc(56px+env(safe-area-inset-top))] z-10 flex gap-2 overflow-x-auto px-4 py-3 snap-x bg-slate-50 dark:bg-slate-950/40">
                            </div>
                            <div id="mobile-agenda" class="px-4 pb-4 space-y-3 bg-white dark:bg-slate-900"></div>
                        </div>

                        <!-- Desktop: week grid -->
                        <div class="hidden md:block">
                            <div id="calendar" class="bg-white dark:bg-slate-900"></div>
                        </div>
                    </div>
                </div>


                <!-- Appointments -->
                <div id="view-appointments" class="hidden animate-fade-in">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft overflow-hidden">
                        <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                            <!-- HEADER: filtro adaptativo -->
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="text-lg font-bold">Citas</div>

                                <!-- Móvil: Select (16px, anti-zoom) -->
                                <div class="md:hidden relative min-w-[46%]">
                                    <label for="appt-filter-mobile" class="sr-only">Filtrar citas</label>
                                    <select id="appt-filter-mobile"
                                        class="w-full appearance-none pr-9 px-3 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700
                           bg-white dark:bg-slate-900 shadow-inset text-base focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                        <option value="all">Todas</option>
                                        <option value="programada">Programadas</option>
                                        <option value="confirmada">Confirmadas</option>
                                        <option value="completada">Completadas</option>
                                        <option value="cancelada">Canceladas</option>
                                    </select>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>

                                <!-- Desktop: chips -->
                                <div id="appt-filter-chips" class="hidden md:flex gap-2">
                                    <button class="chip" data-filter="all" onclick="setApptFilter('all')">Todas</button>
                                    <button class="chip chip--b" data-filter="programada"
                                        onclick="setApptFilter('programada')">Programadas</button>
                                    <button class="chip chip--e" data-filter="confirmada"
                                        onclick="setApptFilter('confirmada')">Confirmadas</button>
                                    <button class="chip chip--p" data-filter="completada"
                                        onclick="setApptFilter('completada')">Completadas</button>
                                    <button class="chip chip--r" data-filter="cancelada"
                                        onclick="setApptFilter('cancelada')">Canceladas</button>
                                </div>
                            </div>

                            <div class="flex gap-3 flex-col sm:flex-row">
                                <input id="search-appointments" type="text"
                                    placeholder="Buscar por cliente, servicio o fecha" class="flex-1 px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl
                         text-base md:text-sm bg-white dark:bg-slate-900 shadow-inset
                         focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    oninput="renderAppointments()" />
                                <button
                                    class="px-4 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 text-sm font-medium shadow-soft tap"
                                    onclick="openModal('modal-appointment')">+ Nueva cita</button>
                            </div>
                        </div>

                        <!-- Mobile cards -->
                        <div id="appointments-mobile" class="block md:hidden px-4 py-4 space-y-3"></div>

                        <!-- Desktop table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead
                                    class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200/60 dark:border-slate-800">
                                    <tr>
                                        <th class="text-left px-5 py-4 font-semibold">Fecha</th>
                                        <th class="text-left px-5 py-4 font-semibold">Hora</th>
                                        <th class="text-left px-5 py-4 font-semibold">Cliente</th>
                                        <th class="text-left px-5 py-4 font-semibold">Servicio</th>
                                        <th class="text-left px-5 py-4 font-semibold">Estado</th>
                                        <th class="text-right px-5 py-4 font-semibold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="appointments-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Clients -->
                <div id="view-clients" class="hidden animate-fade-in">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft overflow-hidden">
                        <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="text-lg font-bold">Clientes</div>
                            </div>
                            <div class="flex gap-3 flex-col sm:flex-row">
                                <input id="search-clients" type="text" placeholder="Buscar clientes" class="flex-1 px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl
                         text-sm bg-white dark:bg-slate-900 shadow-inset
                         focus:ring-2 focus:ring-primary-500 focus:border-transparent" oninput="renderClients()" />
                                <button
                                    class="px-4 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 text-sm font-medium shadow-soft tap"
                                    onclick="openModal('modal-client')">+ Nuevo cliente</button>
                            </div>
                        </div>

                        <!-- Mobile cards -->
                        <div id="clients-mobile" class="block md:hidden px-4 py-4 space-y-3"></div>

                        <!-- Desktop table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead
                                    class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200/60 dark:border-slate-800">
                                    <tr>
                                        <th class="text-left px-5 py-4 font-semibold">Nombre</th>
                                        <th class="text-left px-5 py-4 font-semibold">Teléfono</th>
                                        <th class="text-left px-5 py-4 font-semibold">Email</th>
                                        <th class="text-right px-5 py-4 font-semibold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="clients-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div id="view-services" class="hidden animate-fade-in">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft overflow-hidden">
                        <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="text-lg font-bold">Servicios</div>
                            </div>
                            <div class="flex gap-3 flex-col sm:flex-row">
                                <input id="search-services" type="text" placeholder="Buscar servicios" class="flex-1 px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl
                         text-sm bg-white dark:bg-slate-900 shadow-inset
                         focus:ring-2 focus:ring-primary-500 focus:border-transparent" oninput="renderServices()" />
                                <button
                                    class="px-4 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 text-sm font-medium shadow-soft tap"
                                    onclick="openModal('modal-service')">+ Nuevo servicio</button>
                            </div>
                        </div>

                        <!-- Mobile cards -->
                        <div id="services-mobile" class="block md:hidden px-4 py-4 space-y-3"></div>

                        <!-- Desktop table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead
                                    class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200/60 dark:border-slate-800">
                                    <tr>
                                        <th class="text-left px-5 py-4 font-semibold">Servicio</th>
                                        <th class="text-left px-5 py-4 font-semibold">Duración</th>
                                        <th class="text-left px-5 py-4 font-semibold">Precio</th>
                                        <th class="text-right px-5 py-4 font-semibold">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="services-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div id="view-settings" class="hidden space-y-6 animate-fade-in">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft p-6">
                        <div class="text-lg font-bold mb-6">Horario de atención</div>
                        <form class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="saveSettings(event)">
                            <div>
                                <label class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2 block">Hora
                                    inicial</label>
                                <input id="settings-start" type="time" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                              bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    required />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2 block">Hora
                                    final</label>
                                <input id="settings-end" type="time" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                              bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    required />
                            </div>
                            <div class="flex items-end">
                                <button
                                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 font-medium tap">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-soft p-6">
                        <div class="text-lg font-bold mb-2">Integraciones</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">WhatsApp: genera un enlace automático
                            para confirmar citas.</div>
                    </div>
                </div>
            </section>

            <!-- Bottom Nav (Mobile) -->
            <nav id="bottom-nav"
                class="md:hidden sticky bottom-0 inset-x-0 z-40 glass border-t border-slate-200/60 dark:border-slate-800 shadow-soft safe-bottom">
                <div class="grid grid-cols-4 px-2">
                    <button class="bn-item py-3 flex flex-col items-center gap-1 text-xs font-medium rounded-xl"
                        data-view="dashboard" aria-label="Inicio">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                        </svg>
                        <span>Inicio</span>
                    </button>
                    <button class="bn-item py-3 flex flex-col items-center gap-1 text-xs font-medium rounded-xl"
                        data-view="appointments" aria-label="Citas">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2V7H3v10a2 2 0 002 2z" />
                        </svg>
                        <span>Citas</span>
                    </button>
                    <button class="bn-item py-3 flex flex-col items-center gap-1 text-xs font-medium rounded-xl"
                        data-view="clients" aria-label="Clientes">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5V4H2v16h5m10 0V10m0 10l-3-3m3 3l3-3M7 20V10" />
                        </svg>
                        <span>Clientes</span>
                    </button>
                    <button class="bn-item py-3 flex flex-col items-center gap-1 text-xs font-medium rounded-xl"
                        data-view="services" aria-label="Servicios">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 1.343-3 3v7h6v-7c0-1.657-1.343-3-3-3z" />
                        </svg>
                        <span>Servicios</span>
                    </button>
                </div>
            </nav>

            <!-- Floating Action Button (mobile) -->
            <button id="fab"
                class="md:hidden fixed bottom-16 right-4 h-14 w-14 rounded-full bg-primary-600 hover:bg-primary-700 text-white grid place-content-center shadow-softlg tap"
                onclick="openModal('modal-appointment')" aria-label="Nueva cita">＋</button>
        </main>
    </div>

    <!-- Toast -->
    <div id="toast"
        class="fixed bottom-24 left-1/2 -translate-x-1/2 px-4 py-2 rounded-lg bg-slate-900 text-white text-sm shadow-soft hidden"
        role="status" aria-live="polite"></div>

    <!-- Modales -->
    <!-- Appointment Modal -->
    <div id="modal-appointment"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-end md:items-center justify-center p-0 md:p-4 z-50">
        <div
            class="bg-white dark:bg-slate-900 w-full md:max-w-2xl md:rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-softlg animate-fade-in sheet">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="text-lg font-bold">Nueva cita</div>
                <button class="p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl"
                    onclick="closeModal('modal-appointment')" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form class="p-5 space-y-4" onsubmit="saveAppointment(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">Cliente</label>
                        <select id="appt-client" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required></select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Servicio</label>
                        <select id="appt-service" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required></select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Fecha</label>
                        <input id="appt-date" type="date" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium mb-2 block">Hora inicio</label>
                            <input id="appt-start" type="time" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                       bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                required />
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block">Hora fin</label>
                            <input id="appt-end" type="time" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                       bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                required />
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Estado</label>
                        <select id="appt-status" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="programada">Programada</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Notas</label>
                        <input id="appt-notes" type="text" placeholder="Opcional" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent" />
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a id="whats-link" href="#" target="_blank"
                        class="text-sm text-primary-400 md:text-primary-600 hover:text-primary-500 font-medium">WhatsApp</a>
                    <div class="flex items-center gap-3">
                        <button type="button"
                            class="px-5 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 font-medium tap"
                            onclick="closeModal('modal-appointment')">Cancelar</button>
                        <button
                            class="px-5 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 font-medium tap">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Client Modal -->
    <div id="modal-client"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-end md:items-center justify-center p-0 md:p-4 z-50">
        <div
            class="bg-white dark:bg-slate-900 w-full md:max-w-lg md:rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-softlg animate-fade-in sheet">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="text-lg font-bold">Nuevo cliente</div>
                <button class="p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl"
                    onclick="closeModal('modal-client')" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form class="p-5 space-y-4" onsubmit="saveClient(event)">
                <div>
                    <label class="text-sm font-medium mb-2 block">Nombre completo</label>
                    <input id="client-name" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                   bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent" required />
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Teléfono (10 dígitos)</label>
                    <input id="client-phone" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                   bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        pattern="[0-9]{10}" inputmode="numeric" autocomplete="tel" required />
                </div>
                <div>
                    <label class="text-sm font-medium mb-2 block">Email (opcional)</label>
                    <input id="client-email" type="email" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                   bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        autocomplete="email" />
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                        class="px-5 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 font-medium tap"
                        onclick="closeModal('modal-client')">Cancelar</button>
                    <button
                        class="px-5 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 font-medium tap">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Modal -->
    <div id="modal-service"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-end md:items-center justify-center p-0 md:p-4 z-50">
        <div
            class="bg-white dark:bg-slate-900 w-full md:max-w-lg md:rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-softlg animate-fade-in sheet">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="text-lg font-bold">Nuevo servicio</div>
                <button class="p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl"
                    onclick="closeModal('modal-service')" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form class="p-5 space-y-4" onsubmit="saveService(event)">
                <div>
                    <label class="text-sm font-medium mb-2 block">Nombre del servicio</label>
                    <input id="service-name" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                   bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">Duración (min)</label>
                        <input id="service-duration" type="number" min="5" step="5" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required />
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Precio (MXN)</label>
                        <input id="service-price" type="number" min="0" step="0.01" class="w-full px-4 py-2.5 border border-slate-200/60 dark:border-slate-700 rounded-xl shadow-inset
                     bg-white dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                        class="px-5 py-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 font-medium tap"
                        onclick="closeModal('modal-service')">Cancelar</button>
                    <button
                        class="px-5 py-2.5 rounded-xl bg-primary-600 text-white hover:bg-primary-700 font-medium tap">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Drawer móvil -->
    <div id="mobile-menu" class="fixed inset-0 z-40 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>
        <div
            class="absolute left-0 top-0 bottom-0 w-80 bg-white dark:bg-slate-900 border-r border-slate-200/60 dark:border-slate-800 shadow-softlg animate-fade-in">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-primary-600 text-white grid place-content-center font-bold">C
                    </div>
                    <div class="font-bold">CitasPro</div>
                </div>
                <button
                    class="p-2 rounded-xl border border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800"
                    onclick="toggleMobileMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav id="mobile-nav" class="p-3 space-y-1"></nav>
        </div>
    </div>

    <script>
        /******************** HELPERS ********************/
        const $ = (sel) => document.querySelector(sel);
        const $$ = (sel) => Array.from(document.querySelectorAll(sel));
        const fmtDate = (d) => { // YYYY-MM-DD en zona local
            const x = (d instanceof Date) ? d : new Date(d);
            return `${x.getFullYear()}-${String(x.getMonth() + 1).padStart(2, '0')}-${String(x.getDate()).padStart(2, '0')}`;
        };
        const parseLocalDate = (ymd) => { const [y, m, day] = ymd.split('-').map(Number); return new Date(y, m - 1, day); };
        const today = () => fmtDate(new Date());
        const toHM = (date) => date.toTimeString().slice(0, 5);
        const addDays = (date, days) => { const d = (date instanceof Date) ? new Date(date) : new Date(date); d.setDate(d.getDate() + days); return d; };
        const pad = (n) => String(n).padStart(2, '0');
        const vibrate = (ms = 15) => { try { navigator.vibrate && navigator.vibrate(ms) } catch { } };

        function showToast(msg) {
            const t = $('#toast');
            t.textContent = msg; t.classList.remove('hidden');
            vibrate(12);
            clearTimeout(t._h);
            t._h = setTimeout(() => t.classList.add('hidden'), 2000);
        }

        function download(filename, text) {
            const a = document.createElement('a');
            a.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(text);
            a.download = filename; a.style.display = 'none';
            document.body.appendChild(a); a.click(); a.remove();
        }

        /******************** THEME ********************/
        function initTheme() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = saved ? saved === 'dark' : prefersDark;
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.classList.toggle('light', !isDark);
            $('#theme-toggle').textContent = isDark ? '☀️' : '🌙';
        }
        function toggleTheme() {
            const isDark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.classList.toggle('light', !isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            $('#theme-toggle').textContent = isDark ? '☀️' : '🌙';
        }

        /******************** STATE ********************/
        const DB = { load(k, f) { try { return JSON.parse(localStorage.getItem(k)) ?? f } catch { return f } }, save(k, v) { localStorage.setItem(k, JSON.stringify(v)) } };
        const state = {
            view: 'dashboard',
            weekStart: (() => { // lunes de la semana actual (local)
                const now = new Date();
                const dow = (now.getDay() + 6) % 7; // 0=lun
                return new Date(now.getFullYear(), now.getMonth(), now.getDate() - dow);
            })(),
            mobileDayIndex: (() => ((new Date().getDay() + 6) % 7))(),
            apptFilter: 'all',
            settings: DB.load('settings', { start: '08:00', end: '20:00' }),
            clients: DB.load('clients', []),
            services: DB.load('services', []),
            appointments: DB.load('appointments', [])
        };
        function persistAll() { DB.save('settings', state.settings); DB.save('clients', state.clients); DB.save('services', state.services); DB.save('appointments', state.appointments); }

        // Seed demo
        if (!localStorage.getItem('seeded')) {
            state.clients = [
                { id: crypto.randomUUID(), name: 'María López', phone: '9211234567', email: 'maria@mail.com' },
                { id: crypto.randomUUID(), name: 'Carlos Ruiz', phone: '9219876543', email: 'carlos@mail.com' }
            ];
            state.services = [
                { id: crypto.randomUUID(), name: 'Consulta General', duration: 30, price: 400 },
                { id: crypto.randomUUID(), name: 'Limpieza Dental', duration: 45, price: 600 }
            ];
            const d0 = fmtDate(new Date());
            state.appointments = [
                { id: crypto.randomUUID(), clientId: state.clients[0].id, serviceId: state.services[0].id, date: d0, start: '10:00', end: '10:30', status: 'programada', notes: '' },
                { id: crypto.randomUUID(), clientId: state.clients[1].id, serviceId: state.services[1].id, date: d0, start: '12:00', end: '12:45', status: 'confirmada', notes: 'Confirmada por WhatsApp' }
            ];
            persistAll();
            localStorage.setItem('seeded', '1');
        }

        /******************** NAV ********************/
        const NAV = [
            { id: 'dashboard', label: 'Inicio', icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>` },
            { id: 'appointments', label: 'Citas', icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2V7H3v10a2 2 0 002 2z"/></svg>` },
            { id: 'clients', label: 'Clientes', icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0V10m0 10l-3-3m3 3l3-3M7 20V10"/></svg>` },
            { id: 'services', label: 'Servicios', icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v7h6v-7c0-1.657-1.343-3-3-3z"/></svg>` },
            { id: 'settings', label: 'Ajustes', icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-1.14 1.955-1.14 2.255 0a1.724 1.724 0 002.573 1.02c1.002-.58 2.175.593 1.596 1.596a1.724 1.724 0 001.02 2.573c1.14.3 1.14 1.955 0 2.255a1.724 1.724 0 00-1.02 2.573c.58 1.002-.593 2.175-1.596 1.596a1.724 1.724 0 00-2.573 1.02c-.3 1.14-1.955 1.14-2.255 0a1.724 1.724 0 00-2.573-1.02c-1.002.58-2.175-.593-1.596-1.596a1.724 1.724 0 00-1.02-2.573c-1.14-.3-1.14-1.955 0-2.255.882-.232 1.543-.893 1.775-1.775.3-1.14 1.955-1.14 2.255 0 .232.882.893 1.543 1.775 1.775z"/></svg>` }
        ];

        function buildNav() {
            const makeBtn = (item) => `
    <button data-view="${item.id}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors tap">${item.icon}<span>${item.label}</span></button>
  `;
            $('#sidebar-nav').innerHTML = NAV.map(makeBtn).join('');
            $('#mobile-nav').innerHTML = NAV.map(makeBtn).join('');
            $$('#sidebar-nav [data-view], #mobile-nav [data-view]').forEach(btn => {
                btn.addEventListener('click', () => { toggleMobileMenu(false); navigate(btn.dataset.view); });
            });
            $$('#bottom-nav .bn-item').forEach(btn => btn.addEventListener('click', () => navigate(btn.dataset.view)));
        }

        function navActive(view) {
            $$('#bottom-nav .bn-item').forEach(b => {
                const active = b.dataset.view === view;
                b.classList.toggle('text-primary-600', active);
                b.classList.toggle('text-slate-500', !active);
                b.classList.toggle('bg-primary-50', active);
                b.classList.toggle('dark:bg-slate-800', active);
            });
        }

        function navigate(view) {
            state.view = view;
            $('#page-title').textContent = NAV.find(n => n.id === view)?.label || '—';
            ['dashboard', 'appointments', 'clients', 'services', 'settings'].forEach(v => { $('#view-' + v).classList.toggle('hidden', v !== view); });
            navActive(view);
            if (view === 'dashboard') renderDashboard();
            if (view === 'appointments') renderAppointments();
            if (view === 'clients') renderClients();
            if (view === 'services') renderServices();
            if (view === 'settings') renderSettings();
        }

        function toggleMobileMenu(force) {
            const drawer = $('#mobile-menu');
            const show = typeof force === 'boolean' ? force : drawer.classList.contains('hidden');
            drawer.classList.toggle('hidden', !show);
        }

        function onGlobalSearch(q) {
            q = (q || '').toLowerCase();
            $('#search-appointments').value = q;
            $('#search-clients').value = q;
            $('#search-services').value = q;
            if (!$('#view-appointments').classList.contains('hidden')) renderAppointments();
            if (!$('#view-clients').classList.contains('hidden')) renderClients();
            if (!$('#view-services').classList.contains('hidden')) renderServices();
        }

        /******************** DASHBOARD & CALENDARIO ********************/
        function updateStats(todaysCount, clientsCount, occupancy) {
            const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };

            // Bloques antiguos (siguen por compatibilidad)
            set('stat-today', todaysCount);
            set('stat-clients', clientsCount);
            set('stat-occupancy', occupancy + '%');
            set('stat-today-m', todaysCount);
            set('stat-clients-m', clientsCount);
            set('stat-occupancy-m', occupancy + '%');

            // Nuevo Resumen (home-hero)
            set('home-today', todaysCount);
            set('home-clients', clientsCount);
            set('home-occ', occupancy);
            set('home-occ-ring-label', occupancy + '%');
            const ring = document.getElementById('home-occ-ring');
            if (ring) ring.style.setProperty('--pct', occupancy);
        }

        function renderDashboard() {
            const todayStr = today();
            const todays = state.appointments.filter(a => a.date === todayStr).length;
            const clients = state.clients.length;
            const capacity = capacityMinutesInWeek();
            const booked = minutesBookedInWeek();
            const occ = capacity ? Math.round((booked / capacity) * 100) : 0;
            updateStats(todays, clients, occ);
            renderTodayUpcoming();   // NUEVO: lista “Próximas de hoy”
            renderCalendar();
        }

        function capacityMinutesInWeek() {
            const start = state.settings.start.split(':').map(Number);
            const end = state.settings.end.split(':').map(Number);
            const startMin = start[0] * 60 + start[1];
            const endMin = end[0] * 60 + end[1];
            const perDay = Math.max(0, endMin - startMin);
            return perDay * 5; // L-V
        }

        /* Evita duraciones negativas y % semanales negativos */
        function hmToMinutes(hm) { if (!hm || !/^\d{2}:\d{2}$/.test(hm)) return null; const [h, m] = hm.split(':').map(Number); return h * 60 + m; }
        function diffMinutes(hmStart, hmEnd) {
            const S = hmToMinutes(hmStart), E = hmToMinutes(hmEnd);
            if (S == null || E == null) return 0;
            return Math.max(0, E - S); // no soportamos cruce de medianoche en una sola cita
        }
        function minutesBookedInWeek() {
            const range = weekRange();
            const appts = state.appointments.filter(a => {
                const d = parseLocalDate(a.date);
                return d >= range.start && d <= range.end;
            });
            return appts.reduce((sum, a) => sum + diffMinutes(a.start, a.end), 0);
        }

        function weekRange() { const start = new Date(state.weekStart); const end = addDays(start, 6); return { start, end }; }
        function prevWeek() { state.weekStart = addDays(state.weekStart, -7); renderDashboard(); }
        function nextWeek() { state.weekStart = addDays(state.weekStart, 7); renderDashboard(); }
        function goTodayWeek() {
            const now = new Date();
            const dow = (now.getDay() + 6) % 7;
            state.weekStart = new Date(now.getFullYear(), now.getMonth(), now.getDate() - dow);
            state.mobileDayIndex = dow;
            renderDashboard();
        }

        function renderCalendar() {
            const { start, end } = weekRange();
            const options = { day: '2-digit', month: 'short' };
            $('#week-label').textContent = `${start.toLocaleDateString('es-MX', options)} – ${end.toLocaleDateString('es-MX', options)}`;
            renderMobileAgenda();
            renderDesktopGrid();
        }

        /* ====== NUEVO: tarjetas “Próximas de hoy” ====== */
        function mobileApptRow(a) {
            const client = state.clients.find(c => c.id === a.clientId) || {};
            const service = state.services.find(s => s.id === a.serviceId) || {};
            return `
    <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl p-3 shadow-soft bg-white dark:bg-slate-900">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm font-semibold">${a.start}–${a.end}</div>
          <div class="text-xs text-slate-600 dark:text-slate-400">${client.name || '—'} · ${service.name || ''}</div>
        </div>
        ${statusBadge(a.status)}
      </div>
    </div>`;
        }
        function renderTodayUpcoming() {
            const todayStr = today();
            const appts = state.appointments
                .filter(a => a.date === todayStr)
                .sort((a, b) => a.start.localeCompare(b.start));
            const cont = document.getElementById('today-upcoming');
            if (!cont) return;
            cont.innerHTML = appts.length
                ? appts.map(mobileApptRow).join('')
                : `<div class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
         No hay más citas para hoy
         <div class="mt-3">
           <button class="px-4 py-2.5 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700 tap"
                   onclick="openModal('modal-appointment')">+ Crear cita</button>
         </div>
       </div>`;
        }

        /* ====== Agenda móvil y desktop ====== */
        function renderMobileAgenda() {
            const { start } = weekRange();
            const tabs = [];
            for (let i = 0; i < 7; i++) {
                const d = addDays(start, i);
                const isToday = fmtDate(d) === today();
                const isActive = state.mobileDayIndex === i;
                const dayStr = fmtDate(d);
                const count = state.appointments.filter(a => a.date === dayStr).length;
                tabs.push(`<button class="snap-start shrink-0 px-3 py-2.5 rounded-full text-sm font-medium transition-colors border ${isActive ? 'bg-primary-600 text-white border-primary-600' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-200/60 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800'}" onclick="setMobileDay(${i})" aria-current="${isActive ? 'date' : ''}">
      ${d.toLocaleDateString('es-MX', { weekday: 'short' })} ${d.getDate()} ${count ? `<span class='ml-1 text-xs opacity-80'>(${count})</span>` : ''} ${isToday ? '🔥' : ''}
    </button>`);
            }
            const tabsEl = $('#mobile-day-tabs');
            tabsEl.innerHTML = tabs.join('');
            setTimeout(() => {
                const active = tabsEl.querySelector('button[aria-current="date"]');
                if (active && active.scrollIntoView) active.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
            }, 0);

            const day = addDays(start, state.mobileDayIndex);
            const dateStr = fmtDate(day);
            const appts = state.appointments.filter(a => a.date === dateStr).sort((a, b) => (a.start).localeCompare(b.start));
            const list = appts.map(a => mobileApptCard(a)).join('');
            $('#mobile-agenda').innerHTML = list || emptyCard('Sin citas para este día', () => quickCreate(dateStr, state.settings.start));

            // Gestos: swipe día ±1
            const area = $('#mobile-agenda');
            let startX = null;
            area.ontouchstart = (e) => { startX = e.touches[0].clientX; };
            area.ontouchend = (e) => {
                if (startX === null) return;
                const dx = e.changedTouches[0].clientX - startX;
                if (Math.abs(dx) > 50) {
                    if (dx < 0 && state.mobileDayIndex < 6) setMobileDay(state.mobileDayIndex + 1);
                    if (dx > 0 && state.mobileDayIndex > 0) setMobileDay(state.mobileDayIndex - 1);
                }
                startX = null;
            };
        }
        function setMobileDay(i) { state.mobileDayIndex = i; renderMobileAgenda(); }

        function avatar(name) {
            const n = (name || '').trim(); const parts = n.split(' ');
            const ini = (parts[0]?.[0] || '') + (parts[1]?.[0] || '');
            return `<span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-primary-100 text-primary-700 text-xs font-bold mr-2">${ini.toUpperCase()}</span>`
        }

        function statusBadge(status) {
            const map = {
                programada: 'bg-blue-50 text-blue-700 border-blue-200',
                confirmada: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                completada: 'bg-purple-50 text-purple-700 border-purple-200',
                cancelada: 'bg-rose-50 text-rose-700 border-rose-200'
            };
            return `<span class="px-2.5 py-1 rounded-lg text-xs border font-medium ${map[status] || 'bg-slate-50 text-slate-700 border-slate-200'}">${status}</span>`;
        }

        function mobileApptCard(a) {
            const client = state.clients.find(c => c.id === a.clientId) || {};
            const service = state.services.find(s => s.id === a.serviceId) || {};
            return `<div class="border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-soft bg-white dark:bg-slate-900 hover:shadow-softlg transition-shadow">
    <div class="flex items-center justify-between mb-2">
      <div class="font-bold">${a.start}–${a.end}</div>
      ${statusBadge(a.status)}
    </div>
    <div class="mb-1 font-semibold flex items-center">${avatar(client.name)} ${client.name || '—'}</div>
    <div class="text-sm text-slate-600 dark:text-slate-400 mb-3">${service.name || ''}</div>
    <div class="flex items-center gap-2 text-sm">
      <button class="flex-1 px-3 py-2 border border-slate-200/60 dark:border-slate-700 rounded-lg font-medium hover:bg-slate-50 dark:hover:bg-slate-800 tap" onclick="editAppointment('${a.id}')">Editar</button>
      <button class="px-3 py-2 border border-rose-200 text-rose-700 rounded-lg font-medium hover:bg-rose-50 tap" onclick="deleteAppointment('${a.id}')">Eliminar</button>
      <button class="px-4 py-2 rounded-lg bg-primary-600 text-white font-medium hover:bg-primary-700 tap" onclick="openWhats('${a.id}')">Whats</button>
    </div>
  </div>`;
        }

        function emptyCard(text, onClick) {
            const id = 'btn-' + Math.random().toString(36).slice(2);
            setTimeout(() => { const el = document.getElementById(id); if (el) el.addEventListener('click', onClick); }, 0);
            return `<div class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-6 text-center bg-slate-50 dark:bg-slate-900">
    <div class="mb-3 text-slate-500">📅</div>
    <div class="mb-4 text-slate-600 dark:text-slate-400 font-medium">${text}</div>
    <button id="${id}" class="px-4 py-2.5 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700 tap">+ Crear cita</button>
  </div>`
        }

        function renderDesktopGrid() {
            const { start } = weekRange();
            const startHour = parseInt(state.settings.start.split(':')[0]);
            const endHour = parseInt(state.settings.end.split(':')[0]);
            const hours = Array.from({ length: endHour - startHour + 1 }, (_, i) => startHour + i);

            let html = '<div class="w-full">';

            // Header con conteo por día
            html += '<div class="grid border-b border-slate-200/60 dark:border-slate-800" style="grid-template-columns: 80px repeat(7, 1fr);">';
            html += '<div></div>';
            for (let i = 0; i < 7; i++) {
                const d = addDays(start, i);
                const isToday = fmtDate(d) === today();
                const dateStr = fmtDate(d);
                const totalDay = state.appointments.filter(a => a.date === dateStr).length;

                html += `
      <div class="py-3 px-3 text-sm font-bold ${isToday ? 'text-primary-700 bg-primary-50 dark:bg-slate-800' : ''} text-center">
        ${d.toLocaleDateString('es-MX', { weekday: 'short' })} ${d.getDate()}
        ${isToday ? ' 🔥' : ''}
        <span class="ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 align-middle">${totalDay}</span>
      </div>`;
            }
            html += '</div>';

            // Grid de horas
            html += '<div class="grid" style="grid-template-columns: 80px repeat(7, 1fr);">';
            hours.forEach((h) => {
                html += `<div class="py-3 px-3 text-xs font-medium text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40">${pad(h)}:00</div>`;

                for (let i = 0; i < 7; i++) {
                    const d = addDays(start, i);
                    const dateStr = fmtDate(d);
                    const apptsHour = state.appointments.filter(a => a.date === dateStr && parseInt(a.start.split(':')[0]) === h);
                    const cells = apptsHour.map(a => renderApptChip(a)).join('');

                    html += `
        <div class="border-b border-l border-slate-100 dark:border-slate-800 relative group min-h-16 hover:bg-slate-50/50 dark:hover:bg-slate-900/40 transition-colors ${cells ? '' : 'cursor-pointer'}"
             ${cells ? '' : `onclick="quickCreate('${dateStr}','${pad(h)}:00')"`}>
          <div class="p-2 space-y-1">${cells}</div>
        </div>`;
                }
            });
            html += '</div></div>';

            $('#calendar').innerHTML = html;
        }

        function renderApptChip(a) {
            const client = state.clients.find(c => c.id === a.clientId);
            const service = state.services.find(s => s.id === a.serviceId);
            const color = {
                programada: 'bg-blue-50 text-blue-800 border-blue-200',
                confirmada: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                completada: 'bg-purple-50 text-purple-800 border-purple-200',
                cancelada: 'bg-rose-50 text-rose-800 border-rose-200'
            }[a.status] || 'bg-slate-50';
            return `<div class="border ${color} rounded-lg px-2 py-1.5 text-xs flex items-center justify-between shadow-soft transition-shadow">
    <div class="truncate font-medium"><span class="font-bold">${client?.name || '—'}</span> • ${service?.name || ''}</div>
    <div class="flex items-center gap-1 ml-2">
      <button class="p-1 hover:bg-white/70 dark:hover:bg-slate-800/70 rounded" title="WhatsApp" onclick="openWhats('${a.id}')">💬</button>
      <button class="p-1 hover:bg-white/70 dark:hover:bg-slate-800/70 rounded" title="Editar" onclick="editAppointment('${a.id}')">✏️</button>
      <button class="p-1 hover:bg-white/70 dark:hover:bg-slate-800/70 rounded text-rose-700" title="Eliminar" onclick="deleteAppointment('${a.id}')">🗑️</button>
    </div>
  </div>`;
        }

        function quickCreate(dateStr, start) {
            $('#appt-date').value = dateStr;
            $('#appt-start').value = start;
            const duration = state.services[0]?.duration || 30;
            const [h, m] = start.split(':').map(Number);
            const end = new Date(); end.setHours(h, m + duration, 0, 0);
            $('#appt-end').value = toHM(end);
            populateSelects();
            openModal('modal-appointment');
            updateOverlapUI();
        }

        /******************** LISTADOS ********************/
        function setApptFilter(f) {
            state.apptFilter = f;
            syncApptFilterUI();
            renderAppointments();
        }
        function syncApptFilterUI() {
            const sel = document.getElementById('appt-filter-mobile');
            if (sel && sel.value !== state.apptFilter) sel.value = state.apptFilter;
        }

        function apptMatchesQuery(a, q) {
            const client = state.clients.find(c => c.id === a.clientId)?.name?.toLowerCase() || '';
            const service = state.services.find(s => s.id === a.serviceId)?.name?.toLowerCase() || '';
            return client.includes(q) || service.includes(q) || a.date.includes(q);
        }

        function renderAppointments() {
            syncApptFilterUI();
            const q = ($('#search-appointments').value || '').toLowerCase();
            const rowsData = state.appointments
                .slice().sort((a, b) => (a.date + a.start).localeCompare(b.date + b.start))
                .filter(a => state.apptFilter === 'all' ? true : a.status === state.apptFilter)
                .filter(a => apptMatchesQuery(a, q));

            const rows = rowsData.map(a => {
                const client = state.clients.find(c => c.id === a.clientId);
                const service = state.services.find(s => s.id === a.serviceId);
                return `<tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-900/40">
      <td class="px-5 py-4 font-medium">${a.date}</td>
      <td class="px-5 py-4 font-medium">${a.start}–${a.end}</td>
      <td class="px-5 py-4 font-semibold flex items-center">${avatar(client?.name || '')} ${client?.name || '—'}</td>
      <td class="px-5 py-4 text-slate-600 dark:text-slate-400">${service?.name || '—'}</td>
      <td class="px-5 py-4">${statusBadge(a.status)}</td>
      <td class="px-5 py-4 text-right">
        <button class="text-primary-700 dark:text-primary-400 hover:underline mr-3 tap" onclick="openWhats('${a.id}')">Whats</button>
        <button class="px-3 py-1.5 border border-slate-200/60 dark:border-slate-700 rounded-lg mr-2 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 text-sm tap" onclick="editAppointment('${a.id}')">Editar</button>
        <button class="px-3 py-1.5 border border-rose-200 text-rose-700 rounded-lg font-medium hover:bg-rose-50 text-sm tap" onclick="deleteAppointment('${a.id}')">Eliminar</button>
      </td>
    </tr>`;
            }).join('');
            $('#appointments-tbody').innerHTML = rows || `<tr><td colspan="6" class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">Sin resultados</td></tr>`;

            const cards = rowsData.map(mobileApptCard).join('');
            $('#appointments-mobile').innerHTML = cards || emptyCard('No hay citas con este filtro', () => openModal('modal-appointment'));
        }

        function renderClients() {
            const q = ($('#search-clients').value || '').toLowerCase();
            const list = state.clients.filter(c => c.name.toLowerCase().includes(q) || c.phone.includes(q) || (c.email || '').toLowerCase().includes(q));
            const rows = list.map(c => `
    <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-900/40">
      <td class="px-5 py-4 font-semibold flex items-center">${avatar(c.name)} ${c.name}</td>
      <td class="px-5 py-4 font-medium">${c.phone}</td>
      <td class="px-5 py-4 text-slate-600 dark:text-slate-400">${c.email || '—'}</td>
      <td class="px-5 py-4 text-right">
        <button class="px-3 py-1.5 border border-primary-200 text-primary-700 dark:text-primary-400 rounded-lg mr-2 font-medium hover:bg-primary-50/60 text-sm tap" onclick="prefillApptForClient('${c.id}')">Agendar</button>
        <button class="px-3 py-1.5 border border-rose-200 text-rose-700 rounded-lg font-medium hover:bg-rose-50 text-sm tap" onclick="deleteClient('${c.id}')">Eliminar</button>
      </td>
    </tr>`).join('');
            $('#clients-tbody').innerHTML = rows || `<tr><td colspan="4" class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">Sin resultados</td></tr>`;

            const cards = list.map(c => `
    <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-soft bg-white dark:bg-slate-900">
      <div class="flex items-center justify-between">
        <div class="font-semibold flex items-center">${avatar(c.name)} ${c.name}</div>
        <div class="text-xs text-slate-500">${c.phone}</div>
      </div>
      <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">${c.email || '—'}</div>
      <div class="flex gap-2 mt-3">
        <a class="px-3 py-2 border border-slate-200/60 dark:border-slate-700 rounded-lg font-medium tap flex-1 text-center" href="tel:${c.phone}">Llamar</a>
        <button class="px-3 py-2 border border-primary-200 text-primary-700 dark:text-primary-400 rounded-lg font-medium tap flex-1" onclick="prefillApptForClient('${c.id}')">Agendar</button>
        <button class="px-3 py-2 border border-rose-200 text-rose-700 rounded-lg font-medium tap" onclick="deleteClient('${c.id}')">Eliminar</button>
      </div>
    </div>`).join('');
            $('#clients-mobile').innerHTML = cards || emptyCard('Añade tu primer cliente', () => openModal('modal-client'));
        }

        function renderServices() {
            const q = ($('#search-services').value || '').toLowerCase();
            const list = state.services.filter(s => s.name.toLowerCase().includes(q));
            const rows = list.map(s => `
    <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-900/40">
      <td class="px-5 py-4 font-semibold">${s.name}</td>
      <td class="px-5 py-4 font-medium">${s.duration} min</td>
      <td class="px-5 py-4 font-bold text-green-600">$${Number(s.price).toFixed(2)}</td>
      <td class="px-5 py-4 text-right">
        <button class="px-3 py-1.5 border border-primary-200 text-primary-700 dark:text-primary-400 rounded-lg mr-2 font-medium hover:bg-primary-50/60 text-sm tap" onclick="prefillApptForService('${s.id}')">Usar</button>
        <button class="px-3 py-1.5 border border-rose-200 text-rose-700 rounded-lg font-medium hover:bg-rose-50 text-sm tap" onclick="deleteService('${s.id}')">Eliminar</button>
      </td>
    </tr>`).join('');
            $('#services-tbody').innerHTML = rows || `<tr><td colspan="4" class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">Sin resultados</td></tr>`;

            const cards = list.map(s => `
    <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-soft bg-white dark:bg-slate-900">
      <div class="flex items-center justify-between">
        <div class="font-semibold">${s.name}</div>
        <div class="text-sm font-bold text-green-600">$${Number(s.price).toFixed(2)}</div>
      </div>
      <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">${s.duration} min</div>
      <div class="flex gap-2 mt-3">
        <button class="px-3 py-2 border border-primary-200 text-primary-700 dark:text-primary-400 rounded-lg font-medium tap flex-1" onclick="prefillApptForService('${s.id}')">Usar</button>
        <button class="px-3 py-2 border border-rose-200 text-rose-700 rounded-lg font-medium tap" onclick="deleteService('${s.id}')">Eliminar</button>
      </div>
    </div>`).join('');
            $('#services-mobile').innerHTML = cards || emptyCard('Crea tu primer servicio', () => openModal('modal-service'));
        }

        function renderSettings() { $('#settings-start').value = state.settings.start; $('#settings-end').value = state.settings.end; }

        /******************** CRUD ********************/
        function openModal(id) {
            const m = document.getElementById(id);
            m.classList.remove('hidden'); m.classList.add('flex');
            document.body.style.overflow = 'hidden';
            if (id === 'modal-appointment') { // preparar aviso de choques si no existe
                if (!$('#overlap-warning')) {
                    const warn = document.createElement('div');
                    warn.id = 'overlap-warning';
                    warn.className = 'hidden mx-5 mb-2 px-3 py-2 rounded-lg border text-xs bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-300';
                    warn.innerHTML = '⚠️ Esta cita choca con otra.';
                    const form = document.querySelector('#modal-appointment form');
                    form && form.insertBefore(warn, form.firstChild);
                }
                updateOverlapUI();
            }
        }
        function closeModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden'); m.classList.remove('flex');
            document.body.style.overflow = 'auto';
            if (id === 'modal-appointment') delete m.dataset.editing; // limpiar modo edición
        }

        function populateSelects() {
            const cSel = $('#appt-client'); cSel.innerHTML = state.clients.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
            const sSel = $('#appt-service'); sSel.innerHTML = state.services.map(s => `<option value="${s.id}" data-duration="${s.duration}">${s.name} (${s.duration} min)</option>`).join('');
        }

        function saveClient(e) {
            e.preventDefault();
            const name = $('#client-name').value.trim();
            const phone = $('#client-phone').value.trim();
            const email = $('#client-email').value.trim();
            state.clients.push({ id: crypto.randomUUID(), name, phone, email });
            persistAll(); closeModal('modal-client'); renderClients(); populateSelects();
            $('#client-name').value = ''; $('#client-phone').value = ''; $('#client-email').value = '';
            showToast('Cliente guardado');
        }
        function deleteClient(id) {
            if (!confirm('¿Eliminar cliente? Se eliminarán también sus citas.')) return;
            state.clients = state.clients.filter(c => c.id !== id);
            state.appointments = state.appointments.filter(a => a.clientId !== id);
            persistAll(); renderClients(); renderDashboard(); showToast('Cliente eliminado');
        }

        function saveService(e) {
            e.preventDefault();
            const name = $('#service-name').value.trim();
            const duration = parseInt($('#service-duration').value);
            const price = parseFloat($('#service-price').value);
            state.services.push({ id: crypto.randomUUID(), name, duration, price });
            persistAll(); closeModal('modal-service'); renderServices(); populateSelects();
            $('#service-name').value = ''; $('#service-duration').value = ''; $('#service-price').value = '';
            showToast('Servicio guardado');
        }
        function deleteService(id) {
            if (!confirm('¿Eliminar servicio?')) return;
            state.services = state.services.filter(s => s.id !== id);
            persistAll(); renderServices(); renderDashboard(); showToast('Servicio eliminado');
        }

        function openWhats(id) {
            const a = state.appointments.find(x => x.id === id);
            const c = state.clients.find(x => x.clientId === a?.clientId) || state.clients.find(x => x.id === a.clientId);
            const s = state.services.find(x => x.id === a.serviceId);
            if (!a || !c || !s) return;
            const date = parseLocalDate(a.date);
            const f = `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()}`;
            const msg = encodeURIComponent(`Hola ${c.name}! 👋

Te recordamos tu cita:
📅 ${s.name}
🗓️ ${f} a las ${a.start}

¿Confirmas tu asistencia?`);
            const url = `https://wa.me/52${c.phone}?text=${msg}`;
            window.open(url, '_blank');
        }

        function editAppointment(id) {
            const a = state.appointments.find(x => x.id === id);
            populateSelects();
            $('#appt-client').value = a.clientId;
            $('#appt-service').value = a.serviceId;
            $('#appt-date').value = a.date;
            $('#appt-start').value = a.start;
            $('#appt-end').value = a.end;
            $('#appt-status').value = a.status;
            $('#appt-notes').value = a.notes || '';

            openModal('modal-appointment'); // abrir primero
            $('#modal-appointment').dataset.editing = id; // luego marcar edición
            updateWhatsPreview();
            updateOverlapUI();
        }

        function saveAppointment(e) {
            e.preventDefault();
            const clientId = $('#appt-client').value;
            const serviceId = $('#appt-service').value;
            const date = $('#appt-date').value;
            const start = $('#appt-start').value;
            const end = $('#appt-end').value;
            const status = $('#appt-status').value;
            const notes = $('#appt-notes').value;
            const editingId = $('#modal-appointment').dataset.editing;

            /* Validación dura: fin debe ser > inicio (evita % negativo) */
            const S = hmToMinutes(start), E = hmToMinutes(end);
            if (S == null || E == null || E <= S) {
                showToast('La hora fin debe ser posterior a la hora inicio.');
                $('#appt-end').focus();
                return;
            }

            // Aviso de choques (no bloquea)
            const clashes = findOverlaps(date, start, end, editingId);
            if (clashes.length) {
                const txt = clashes.slice(0, 2).map(c => {
                    const cli = state.clients.find(x => x.id === c.clientId)?.name || '—';
                    return `${c.start}-${c.end} ${cli}`;
                }).join(', ');
                showToast(`⚠️ Choca con: ${txt}${clashes.length > 2 ? '…' : ''}`);
            }

            if (editingId) {
                const a = state.appointments.find(x => x.id === editingId);
                Object.assign(a, { clientId, serviceId, date, start, end, status, notes });
                delete $('#modal-appointment').dataset.editing;
            } else {
                state.appointments.push({ id: crypto.randomUUID(), clientId, serviceId, date, start, end, status, notes });
            }
            persistAll(); closeModal('modal-appointment'); renderDashboard(); renderAppointments();
            showToast('Cita guardada'); e.target.reset(); vibrate(10);
        }

        function deleteAppointment(id) {
            if (!confirm('¿Eliminar esta cita?')) return;
            state.appointments = state.appointments.filter(a => a.id !== id);
            persistAll(); renderDashboard(); renderAppointments(); showToast('Cita eliminada');
        }

        function prefillApptForClient(clientId) {
            populateSelects();
            $('#appt-client').value = clientId;
            $('#appt-date').value = today();
            const start = state.settings.start;
            $('#appt-start').value = start;
            const dur = state.services[0]?.duration || 30;
            const [h, m] = start.split(':').map(Number);
            const end = new Date(); end.setHours(h, m + dur, 0, 0);
            $('#appt-end').value = toHM(end);
            openModal('modal-appointment');
            updateOverlapUI();
        }
        function prefillApptForService(serviceId) {
            populateSelects();
            $('#appt-service').value = serviceId;
            $('#appt-date').value = today();
            $('#appt-start').value = state.settings.start;
            const dur = state.services.find(s => s.id === serviceId)?.duration || 30;
            const [h, m] = state.settings.start.split(':').map(Number);
            const end = new Date(); end.setHours(h, m + dur, 0, 0);
            $('#appt-end').value = toHM(end);
            openModal('modal-appointment');
            updateOverlapUI();
        }

        function updateWhatsPreview() {
            const cId = $('#appt-client').value; const sId = $('#appt-service').value;
            const date = $('#appt-date').value; const start = $('#appt-start').value;
            const c = state.clients.find(x => x.id === cId); const s = state.services.find(x => x.id === sId);
            if (!c || !s || !date || !start) { $('#whats-link').href = '#'; return }
            const d = parseLocalDate(date);
            const f = `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()}`;
            const msg = encodeURIComponent(`Hola ${c.name}! 👋

Te recordamos tu cita:
📅 ${s.name}
🗓️ ${f} a las ${start}

¿Confirmas tu asistencia?`);
            $('#whats-link').href = `https://wa.me/52${c.phone}?text=${msg}`;
        }

        function saveSettings(e) {
            e.preventDefault();
            state.settings.start = $('#settings-start').value;
            state.settings.end = $('#settings-end').value;
            persistAll(); renderDashboard(); showToast('Ajustes guardados');
        }

        /********** Choques de horario (en tiempo real y al guardar) **********/
        function rangesOverlap(aStart, aEnd, bStart, bEnd) { return aStart < bEnd && aEnd > bStart; }
        function findOverlaps(date, start, end, exceptId) {
            const S = hmToMinutes(start), E = hmToMinutes(end);
            if (S == null || E == null || !date) return [];
            return state.appointments.filter(a => {
                if (a.id === exceptId) return false;
                if (a.date !== date) return false;
                const s2 = hmToMinutes(a.start), e2 = hmToMinutes(a.end);
                return rangesOverlap(S, E, s2, e2);
            });
        }
        function updateOverlapUI() {
            const date = $('#appt-date')?.value;
            const start = $('#appt-start')?.value;
            const end = $('#appt-end')?.value;
            const editingId = $('#modal-appointment')?.dataset.editing;
            const warn = $('#overlap-warning');
            if (!warn) return;

            const clashes = findOverlaps(date, start, end, editingId);
            if (!clashes.length) { warn.classList.add('hidden'); return; }

            const items = clashes.slice(0, 3).map(c => {
                const cli = state.clients.find(x => x.id === c.clientId)?.name || '—';
                return `<li>• ${c.start}–${c.end} — <b>${cli}</b></li>`;
            }).join('');
            warn.innerHTML = `⚠️ Esta cita choca con otras existentes:<ul class="mt-1 ml-3">${items}${clashes.length > 3 ? '<li>• …</li>' : ''}</ul><div class="mt-1 text-[11px] opacity-80">Solo aviso, puedes guardar de todos modos.</div>`;
            warn.classList.remove('hidden');
        }

        /******************** Anti-zoom (pinch/doble-tap) solo móvil ********************/
        (function lockMobileZoom() {
            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            if (!isMobile) return;
            const stopMultiTouch = (e) => { if (e.touches && e.touches.length > 1) e.preventDefault(); };
            document.addEventListener('touchstart', stopMultiTouch, { passive: false });
            document.addEventListener('touchmove', stopMultiTouch, { passive: false });
            let lastTouchEnd = 0;
            document.addEventListener('touchend', (e) => {
                const now = Date.now();
                if (now - lastTouchEnd <= 350) e.preventDefault();
                lastTouchEnd = now;
            }, { passive: false });
            document.addEventListener('gesturestart', (e) => e.preventDefault());
            document.addEventListener('wheel', (e) => { if (e.ctrlKey) e.preventDefault(); }, { passive: false });
        })();

        /******************** INIT ********************/
        function attachLiveHandlers() {
            // Whats & choque en tiempo real
            ['appt-client', 'appt-service', 'appt-date', 'appt-start', 'appt-end'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', () => { updateWhatsPreview(); updateOverlapUI(); });
            });

            // Al cambiar servicio, sugerir fin según duración
            const serviceEl = document.getElementById('appt-service');
            if (serviceEl) {
                serviceEl.addEventListener('change', (e) => {
                    const opt = e.target.selectedOptions[0];
                    if (!opt) return;
                    const dur = parseInt(opt.dataset.duration || '30');
                    const [h, m] = ($('#appt-start').value || state.settings.start).split(':').map(Number);
                    const end = new Date(); end.setHours(h, m + dur, 0, 0);
                    $('#appt-end').value = toHM(end);
                    updateWhatsPreview(); updateOverlapUI();
                });
            }

            // Validaciones y sugerencias de fin >= inicio
            const startEl = document.getElementById('appt-start');
            const endEl = document.getElementById('appt-end');
            const servEl = document.getElementById('appt-service');
            if (startEl && endEl) {
                startEl.addEventListener('input', () => {
                    const dur = parseInt(servEl?.selectedOptions?.[0]?.dataset?.duration || '30', 10);
                    const [h, m] = (startEl.value || state.settings.start).split(':').map(Number);
                    const end = new Date(); end.setHours(h, m + dur, 0, 0);
                    if (!endEl.value || hmToMinutes(endEl.value) <= hmToMinutes(startEl.value)) {
                        endEl.value = toHM(end);
                    }
                    endEl.min = startEl.value;
                    updateWhatsPreview(); updateOverlapUI();
                });
                endEl.addEventListener('input', () => {
                    if (hmToMinutes(endEl.value) <= hmToMinutes(startEl.value)) {
                        endEl.setCustomValidity('La hora fin debe ser posterior a la hora inicio');
                    } else {
                        endEl.setCustomValidity('');
                    }
                });
            }

            // Cerrar con Escape (modales/drawer)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    ['modal-appointment', 'modal-client', 'modal-service'].forEach(id => {
                        const m = document.getElementById(id);
                        if (m && !m.classList.contains('hidden')) closeModal(id);
                    });
                    if (!$('#mobile-menu').classList.contains('hidden')) toggleMobileMenu(false);
                }
            });

            // Ocultar bottom nav/FAB cuando abre el teclado
            const baseVH = window.innerHeight;
            window.addEventListener('resize', () => {
                const kbOpen = (baseVH - window.innerHeight) > 160;
                document.body.classList.toggle('kb-open', kbOpen);
            }, { passive: true });

            // Filtro de citas (móvil)
            const filterSel = document.getElementById('appt-filter-mobile');
            if (filterSel) filterSel.addEventListener('change', (e) => setApptFilter(e.target.value));
        }

        function init() { initTheme(); buildNav(); populateSelects(); attachLiveHandlers(); navigate('dashboard'); }
        document.addEventListener('DOMContentLoaded', init);
    </script>


</body>

</html>