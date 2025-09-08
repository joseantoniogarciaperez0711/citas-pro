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
</x-app-layout>
