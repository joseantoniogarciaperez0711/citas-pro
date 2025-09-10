<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    <x-action-section>
                        <x-slot name="title">
                            {{ __('Logo del negocio') }}
                        </x-slot>

                        <x-slot name="description">
                            {{ __('Sube el logo que se mostrará en la navegación y documentos.') }}
                        </x-slot>

                        <x-slot name="content">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <img src="{{ Auth::user()->business_logo_url }}"
                                    onclick="verLogo('{{ Auth::user()->business_logo_url }}')"
                                    class="h-12 w-12 sm:h-16 sm:w-16 rounded-lg object-cover border border-gray-200 mx-auto sm:mx-0 cursor-pointer hover:scale-105 transition-transform duration-200"
                                    alt="Logo del negocio">

                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                                    <form method="POST" action="{{ route('profile.business-logo.update') }}"
                                        enctype="multipart/form-data"
                                        class="flex flex-col sm:flex-row sm:items-center gap-3 w-full">
                                        @csrf
                                        <input type="file" name="business_logo" accept="image/*" required
                                            class="block w-full sm:w-56 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
                                        <x-button class="w-full sm:w-auto">{{ __('Subir logo') }}</x-button>
                                    </form>

                                    @if (Auth::user()->business_logo_path)
                                        <form method="POST" action="{{ route('profile.business-logo.destroy') }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-secondary-button type="submit" class="w-full sm:w-auto">
                                                {{ __('Quitar logo') }}
                                            </x-secondary-button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if (session('status') === 'business-logo-updated')
                                <p class="text-sm text-green-600 mt-3">{{ __('Logo actualizado correctamente.') }}</p>
                            @elseif (session('status') === 'business-logo-deleted')
                                <p class="text-sm text-green-600 mt-3">{{ __('Logo eliminado.') }}</p>
                            @endif
                        </x-slot>
                    </x-action-section>
                </div>

                <x-section-border />
            @endif

            {{-- SECCIÓN: MEMBRESÍA PREMIUM (SÚPER COMPACTA Y ORGANIZADA) --}}
<div id="membresia" class="mt-10 sm:mt-0">
    <x-action-section>
        <x-slot name="title">{{ __('Membresía') }}</x-slot>
        <x-slot name="description">{{ __('Estado de tu suscripción.') }}</x-slot>

        <x-slot name="content">
            @php
                $user = Auth::user();
                $plan = $user->plan_actual ?? 'prueba';
                $fechaFin = $user->fecha_fin_membresia
                    ? \Carbon\Carbon::parse($user->fecha_fin_membresia)
                    : null;
                $now = now();
                $expirada = $fechaFin ? $now->gt($fechaFin) : false;
                $estado = $user->status_membresia;
                $estadoVisual = $expirada && $estado !== 'inactiva' ? 'vencida' : $estado;
                $esActiva = $estado === 'activa' && !$expirada;

                $segundosRestantes = $fechaFin && !$expirada ? $now->diffInSeconds($fechaFin) : 0;
                $diasRestantes = intdiv($segundosRestantes, 86400);
                $horasRestantes = intdiv($segundosRestantes % 86400, 3600);
                $minutosRestantes = intdiv($segundosRestantes % 3600, 60);

                $waText = 'Hola, soy ' . $user->name . ' (ID: ' . $user->id . '). Quiero renovar mi membresía.';
                $waUrl = 'https://wa.me/529221064114?text=' . urlencode($waText);
            @endphp

            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                {{-- Encabezado: Plan y Estado --}}
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <h3 class="text-xl font-bold text-black">Plan {{ ucfirst($plan) }}</h3>
                        <span
                            class="px-3 py-1 rounded-full text-sm font-semibold {{ $esActiva ? 'bg-green-100 text-green-800' : ($estadoVisual === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($estadoVisual) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Botón Activar/Renovar corregido --}}
                        <a href="{{ $waUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ $esActiva ? 'Renovar' : 'Activar' }}
                        </a>

                        <a href="https://wa.me/529221064114?text=Necesito ayuda con mi membresía"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            Soporte
                        </a>
                    </div>
                </div>

                {{-- Información de tiempo --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Tiempo restante --}}
                    @if ($esActiva || $estadoVisual === 'pendiente')
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Tiempo restante</p>
                                <p class="text-lg font-bold text-black">
                                    @if ($diasRestantes > 0)
                                        {{ $diasRestantes }}d {{ $horasRestantes }}h
                                        {{ $minutosRestantes }}m
                                    @else
                                        {{ $horasRestantes }}h {{ $minutosRestantes }}m
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Estado</p>
                                <p class="text-lg font-bold text-red-600">Membresía vencida</p>
                            </div>
                        </div>
                    @endif

                    {{-- Fecha de vencimiento --}}
                    @if ($fechaFin)
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Fecha de vencimiento</p>
                                <p class="text-lg font-bold text-black">{{ $fechaFin->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </x-slot>
    </x-action-section>
</div>


            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function verLogo(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Logo del negocio',
                background: 'rgba(17, 24, 39, 0.9)', // gris oscuro translúcido
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'p-0 rounded-xl shadow-2xl',
                    closeButton: 'text-white text-2xl top-2 right-2'
                },
                width: 'auto',
                padding: '1rem',
                imageWidth: '100%',
                imageHeight: 'auto',
                didOpen: () => {
                    const img = Swal.getImage();
                    img.classList.add(
                        'rounded-lg',
                        'border-4',
                        'border-white',
                        'shadow-xl',
                        'max-w-3xl',
                        'w-full'
                    );
                }
            });
        }
    </script>
</x-app-layout>
