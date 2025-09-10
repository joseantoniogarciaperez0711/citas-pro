<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    @if (Auth::check() && (Auth::user()->status_membresia === 'inactiva' || now()->gt(Auth::user()->fecha_fin_membresia)))
        <!-- Control del efecto + modal -->
        <div x-data="{ showModal: false, isDisintegrating: false }" x-init="setTimeout(() => { isDisintegrating = true;
            startDisintegration(); }, 5000);
        setTimeout(() => { showModal = true }, 8000);">
            <!-- Modal teleportado al body -->
            <template x-teleport="body">
                <div x-show="showModal" x-cloak>
                    <!-- Fondo de colores -->
                    <div class="fixed inset-0 z-40 bg-gradient-to-r from-slate-900 via-indigo-800 to-blue-600"></div>

                    <!-- Overlay + Modal -->
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
                        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 text-center">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Membresía finalizada</h2>
                            <p class="text-gray-600 mb-6">
                                Tu membresía ha terminado. Para seguir usando la plataforma debes renovarla.
                            </p>
                            @php
                                $waText =
                                    'Hola, soy ' .
                                    Auth::user()->name .
                                    ' (ID: ' .
                                    Auth::user()->id .
                                    '). Quiero renovar mi membresía.';
                            @endphp

                            <a href="https://wa.me/529221064114?text={{ rawurlencode($waText) }}" target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center px-5 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                                💬 Contactar por WhatsApp
                            </a>

                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif

    @verbatim
        <style>
            [x-cloak] {
                display: none !important;
            }

            /* Epicentro de la onda */
            .wave-epicenter {
                position: fixed;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                background: radial-gradient(circle, #ff6b35, #f7931e);
                border-radius: 50%;
                transform: translate(-50%, -50%);
                z-index: 1000;
                pointer-events: none;
                opacity: 0;
            }

            /* Ondas expansivas */
            .shock-wave {
                position: fixed;
                top: 50%;
                left: 50%;
                border: 3px solid transparent;
                border-radius: 50%;
                transform: translate(-50%, -50%);
                pointer-events: none;
                z-index: 999;
            }

            /* Animaciones de las ondas */
            .wave-1 {
                animation: expand-wave 2.5s ease-out forwards;
                border-color: rgba(255, 107, 53, 0.8);
            }

            .wave-2 {
                animation: expand-wave 2.5s ease-out 0.3s forwards;
                border-color: rgba(247, 147, 30, 0.6);
            }

            .wave-3 {
                animation: expand-wave 2.5s ease-out 0.6s forwards;
                border-color: rgba(255, 67, 101, 0.4);
            }

            @keyframes expand-wave {
                0% {
                    width: 0;
                    height: 0;
                    opacity: 1;
                    border-width: 6px;
                }

                50% {
                    opacity: 0.7;
                    border-width: 3px;
                }

                100% {
                    width: 300vmax;
                    height: 300vmax;
                    opacity: 0;
                    border-width: 1px;
                }
            }

            /* Epicentro animado */
            .epicenter-pulse {
                animation: pulse-epicenter 1.5s ease-in-out forwards;
            }

            @keyframes pulse-epicenter {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0);
                }

                20% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1);
                    box-shadow: 0 0 20px #ff6b35;
                }

                60% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1.5);
                    box-shadow: 0 0 40px #ff6b35, 0 0 80px #f7931e;
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(2);
                    box-shadow: 0 0 60px transparent;
                }
            }

            /* Efectos de desintegración optimizados */
            .disintegrable {
                transition: all 0.1s ease-out;
            }

            .particle-fade {
                animation: disintegrate 1.5s ease-out forwards;
                transform-origin: center;
            }

            @keyframes disintegrate {
                0% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                    filter: blur(0px) brightness(1);
                }

                30% {
                    opacity: 0.8;
                    transform: scale(1.02) translateY(-2px);
                    filter: blur(0.5px) brightness(1.2);
                }

                60% {
                    opacity: 0.4;
                    transform: scale(0.98) translateY(5px);
                    filter: blur(2px) brightness(0.8);
                }

                100% {
                    opacity: 0;
                    transform: scale(0.9) translateY(20px);
                    filter: blur(4px) brightness(0.3);
                }
            }

            /* Partículas flotantes (solo en pantallas grandes) */
            @media (min-width: 768px) {
                .floating-particles {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    pointer-events: none;
                    z-index: 998;
                }

                .particle {
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: linear-gradient(45deg, #ff6b35, #f7931e);
                    border-radius: 50%;
                    opacity: 0;
                }

                .particle-float {
                    animation: float-particle 3s ease-out forwards;
                }

                @keyframes float-particle {
                    0% {
                        opacity: 1;
                        transform: scale(1) translateY(0);
                    }

                    50% {
                        opacity: 0.8;
                        transform: scale(1.2) translateY(-30px);
                    }

                    100% {
                        opacity: 0;
                        transform: scale(0.5) translateY(-80px);
                    }
                }
            }
        </style>
    @endverbatim

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 disintegrable">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center disintegrable">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links (desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex disintegrable">
                    {{-- Dashboard --}}
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Citas --}}
                    <x-nav-link href="{{ route('app.citas') }}" :active="request()->routeIs('app.citas*')">
                        {{ __('Citas') }}
                    </x-nav-link>

                    {{-- Clientes --}}
                    <x-nav-link href="{{ route('app.clientes') }}" :active="request()->routeIs('app.clientes*')">
                        {{ __('Clientes') }}
                    </x-nav-link>

                    {{-- Servicios --}}
                    <x-nav-link href="{{ route('app.servicios') }}" :active="request()->routeIs('app.servicios*')">
                        {{ __('Servicios') }}
                    </x-nav-link>

                    {{-- Empleados --}}
                    <x-nav-link href="{{ route('app.empleados') }}" :active="request()->routeIs('app.empleados*')">
                        {{ __('Empleados') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 disintegrable">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden disintegrable">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden disintegrable">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Dashboard --}}
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-none" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l9-7 9 7M5 10v10a2 2 0 002 2h10a2 2 0 002-2V10" />
                    </svg>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </x-responsive-nav-link>

            {{-- Citas --}}
            <x-responsive-nav-link href="{{ route('app.citas') }}" :active="request()->routeIs('app.citas*')">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-none" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('Citas') }}</span>
                </span>
            </x-responsive-nav-link>

            {{-- Clientes --}}
            <x-responsive-nav-link href="{{ route('app.clientes') }}" :active="request()->routeIs('app.clientes*')">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-none" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20v-1a4 4 0 00-4-4H7a4 4 0 00-4 4v1m14-8a4 4 0 10-8 0m12 8v-1a4 4 0 00-3-3.87M15 7a4 4 0 110 8" />
                    </svg>
                    <span>{{ __('Clientes') }}</span>
                </span>
            </x-responsive-nav-link>

            {{-- Servicios --}}
            <x-responsive-nav-link href="{{ route('app.servicios') }}" :active="request()->routeIs('app.servicios*')">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-none" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 7.5l6 6m-3-9a4.5 4.5 0 11-6.364 6.364L3 18l3 3 4.136-4.136A4.5 4.5 0 1113.5 4.5z" />
                    </svg>
                    <span>{{ __('Servicios') }}</span>
                </span>
            </x-responsive-nav-link>

            {{-- Empleados --}}
            <x-responsive-nav-link href="{{ route('app.empleados') }}" :active="request()->routeIs('app.empleados*')">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-none" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20v-1a4 4 0 00-4-4H7a4 4 0 00-4 4v1m14-8a4 4 0 10-8 0m12 8v-1a4 4 0 00-3-3.87M15 7a4 4 0 110 8" />
                    </svg>
                    <span>{{ __('Empleados') }}</span>
                </span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if (Auth::check() && (Auth::user()->status_membresia === 'inactiva' || now()->gt(Auth::user()->fecha_fin_membresia)))
        <script>
            function startDisintegration() {
                // Crear epicentro y ondas
                createShockWaves();

                // Crear partículas flotantes solo en pantallas grandes
                if (window.innerWidth >= 768) {
                    createFloatingParticles();
                }

                // Desintegrar elementos con retraso basado en distancia del centro
                setTimeout(() => {
                    disintegrateByDistance();
                }, 800);
            }

            function createShockWaves() {
                const body = document.body;

                // Crear epicentro
                const epicenter = document.createElement('div');
                epicenter.className = 'wave-epicenter epicenter-pulse';
                body.appendChild(epicenter);

                // Crear ondas expansivas
                for (let i = 1; i <= 3; i++) {
                    const wave = document.createElement('div');
                    wave.className = `shock-wave wave-${i}`;
                    body.appendChild(wave);
                }

                // Limpiar después de la animación
                setTimeout(() => {
                    epicenter.remove();
                    document.querySelectorAll('.shock-wave').forEach(wave => wave.remove());
                }, 3000);
            }

            function createFloatingParticles() {
                const container = document.createElement('div');
                container.className = 'floating-particles';
                document.body.appendChild(container);

                // Crear partículas aleatorias
                for (let i = 0; i < 15; i++) {
                    setTimeout(() => {
                        const particle = document.createElement('div');
                        particle.className = 'particle particle-float';
                        particle.style.left = Math.random() * 100 + '%';
                        particle.style.top = Math.random() * 100 + '%';
                        container.appendChild(particle);

                        // Remover partícula después de la animación
                        setTimeout(() => particle.remove(), 3000);
                    }, Math.random() * 1000);
                }

                // Limpiar contenedor
                setTimeout(() => container.remove(), 4000);
            }

            function disintegrateByDistance() {
                const elements = document.querySelectorAll('.disintegrable');
                const centerX = window.innerWidth / 2;
                const centerY = window.innerHeight / 2;

                elements.forEach((element) => {
                    const rect = element.getBoundingClientRect();
                    const elementX = rect.left + rect.width / 2;
                    const elementY = rect.top + rect.height / 2;

                    // Calcular distancia del centro (normalizada)
                    const distance = Math.sqrt(
                        Math.pow(elementX - centerX, 2) + Math.pow(elementY - centerY, 2)
                    );
                    const maxDistance = Math.sqrt(Math.pow(centerX, 2) + Math.pow(centerY, 2));
                    const normalizedDistance = distance / maxDistance;

                    // Aplicar efecto con retraso basado en distancia
                    const delay = normalizedDistance * 800;

                    setTimeout(() => {
                        if (element && element.offsetParent !== null) {
                            element.classList.add('particle-fade');
                        }
                    }, delay);
                });
            }
        </script>
    @endif
</nav>
