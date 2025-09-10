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
</x-app-layout>
