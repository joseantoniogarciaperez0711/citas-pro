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
                            <div class="flex items-center gap-4">
                                <img src="{{ Auth::user()->business_logo_url }}"
                                    class="h-16 w-16 rounded-lg object-cover border border-gray-200"
                                    alt="Logo del negocio">

                                <form method="POST" action="{{ route('profile.business-logo.update') }}"
                                    enctype="multipart/form-data" class="flex items-center gap-3">
                                    @csrf
                                    <input type="file" name="business_logo" accept="image/*" required
                                        class="block w-56 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
                                    <x-button>{{ __('Subir logo') }}</x-button>
                                </form>

                                @if (Auth::user()->business_logo_path)
                                    <form method="POST" action="{{ route('profile.business-logo.destroy') }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-secondary-button type="submit">
                                            {{ __('Quitar logo') }}
                                        </x-secondary-button>
                                    </form>
                                @endif

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
</x-app-layout>
