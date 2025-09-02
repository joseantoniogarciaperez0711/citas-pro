@props(['class' => 'h-9 w-auto'])

@auth
    @if (auth()->user()->business_logo_path)
        <img
            src="{{ auth()->user()->business_logo_url }}?v={{ optional(auth()->user()->updated_at)->timestamp }}"
            alt="{{ config('app.name') }}"
            class="{{ $class }} rounded-lg object-cover"
        >
    @else
        {{-- SVG por defecto de Jetstream (déjalo tal cual estaba) --}}
        {{ $slot ?? '' }}
    @endif
@else
    {{-- Invitados: SVG por defecto --}}
    {{ $slot ?? '' }}
@endauth
