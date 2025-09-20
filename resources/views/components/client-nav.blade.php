@props([
    // Dueño del negocio (para brand: nombre/logo)
    'usuario' => null,

    // Logo absoluto (opcional)
    'logoUrl' => null,

    // Enlaces del lado derecho (por defecto)
    'links' => [['href' => '#inicio', 'label' => 'Inicio'], ['href' => '#servicios', 'label' => 'Servicios']],

    // Carrito
    'cartCount' => 0,

    // --- PERFIL DEL CLIENTE (opcional) ---
    // Nombre del cliente (p.ej., decodificado desde el clientToken)
    'clienteNombre' => null,

    // URL al perfil del cliente
    'profileUrl' => null,

    // URL para cerrar sesión (si es POST, usa logoutMethod='post')
    'logoutUrl' => null,
    'logoutMethod' => 'get', // 'get' | 'post'
])

@php
    $businessName = $usuario->business_name ?? 'CitaFlow';
    $inicial = $clienteNombre ? mb_strtoupper(mb_substr($clienteNombre, 0, 1)) : null;
@endphp

<nav class="client-navbar" x-data="{ mobileMenu: false, profileOpen: false }">
    {{-- Versión Desktop --}}
    <div class="client-navbar__desktop">
        <div class="client-navbar__left">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo {{ $businessName }}" class="client-navbar__logo">
            @else
                <div class="client-navbar__brand">{{ $businessName }}</div>
            @endif
        </div>

        <div class="client-navbar__center">
            <div class="client-search">
                <span class="client-search__icon">🔍</span>
                <input id="searchInput" type="text" class="client-search__input"
                    placeholder="Buscar servicios, categorías...">
                <div id="searchResults" class="client-search__results" style="display:none"></div>
            </div>
        </div>

        <div class="client-navbar__right">
            @foreach ($links as $lnk)
                <a href="{{ $lnk['href'] }}" class="client-link">{{ $lnk['label'] }}</a>
            @endforeach

            <div class="client-cart">
                <button class="client-cart__btn" onclick="toggleCart()">
                    🛒 Carrito <span id="cartCount" class="client-cart__count">{{ $cartCount }}</span>
                </button>
            </div>

            {{-- Perfil del cliente (solo si hay nombre) --}}
            @if ($clienteNombre)
                <div class="client-profile">
                    <button class="client-profile__btn" @click="profileOpen = !profileOpen"
                        @keydown.escape="profileOpen=false">
                        <span class="client-profile__avatar">{{ $inicial }}</span>
                        <span class="client-profile__name">{{ $clienteNombre }}</span>
                        <svg class="client-profile__chev" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="client-profile__menu" x-show="profileOpen" x-transition
                        @click.outside="profileOpen=false">
                        @if ($profileUrl)
                            <a href="{{ $profileUrl }}" class="client-profile__item">👤 Mi perfil</a>
                        @endif

                        @if ($logoutUrl)
                            <form id="logoutFormMobile" method="POST" action="{{ $logoutUrl }}">
                                @csrf
                                <button type="button" class="mobile-profile__item mobile-profile__item--danger"
                                    onclick="showLogoutConfirmation('logoutFormMobile')">
                                    ⎋ Cerrar sesión
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            @endif

            {{-- Slot por si quieres meter botones extra --}}
            {{ $slot ?? '' }}
        </div>
    </div>

    {{-- Versión Móvil --}}
    <div class="client-navbar__mobile">
        <button class="mobile-menu__toggle" @click="mobileMenu = !mobileMenu">
            <span :class="mobileMenu ? 'rotate-45 translate-y-1.5' : '-translate-y-0.5'"></span>
            <span :class="mobileMenu ? 'opacity-0' : 'opacity-100'"></span>
            <span :class="mobileMenu ? '-rotate-45 -translate-y-1.5' : 'translate-y-0.5'"></span>
        </button>

        {{-- Buscador en el centro --}}
        <div class="mobile-navbar__center">
            <div class="mobile-search-bar">
                <span class="mobile-search-bar__icon">🔍</span>
                <input id="mobileSearchInput" type="text" class="mobile-search-bar__input" placeholder="Buscar...">
                <div id="mobileSearchResults" class="mobile-search-bar__results" style="display:none"></div>
            </div>
        </div>

        <div class="mobile-navbar__actions">
            {{-- Carrito siempre visible --}}
            <button class="mobile-cart__btn" onclick="toggleCart()">
                🛒 <span id="mobileCartCount" class="mobile-cart__count">{{ $cartCount }}</span>
            </button>

            {{-- Perfil del cliente siempre visible (si está logueado) --}}
            @if ($clienteNombre)
                <button class="mobile-profile__btn" @click="profileOpen = !profileOpen">
                    <span class="mobile-profile__avatar">{{ $inicial }}</span>
                </button>
            @endif
        </div>

        {{-- Dropdown del perfil en móvil --}}
        @if ($clienteNombre)
            <div class="mobile-profile__dropdown" x-show="profileOpen" x-transition @click.outside="profileOpen=false">
                <div class="mobile-profile__header">
                    <span class="mobile-profile__avatar--large">{{ $inicial }}</span>
                    <div class="mobile-profile__info">
                        <div class="mobile-profile__greeting">Hola,</div>
                        <div class="mobile-profile__name">{{ $clienteNombre }}</div>
                    </div>
                </div>
                @if ($profileUrl)
                    <a href="{{ $profileUrl }}" class="mobile-profile__item">👤 Mi perfil</a>
                @endif
                @if ($logoutUrl)
                    @if ($logoutMethod === 'post')
                        <form method="POST" action="{{ $logoutUrl }}">
                            @csrf
                            <button type="submit" class="mobile-profile__item mobile-profile__item--danger">
                                ⎋ Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ $logoutUrl }}" class="mobile-profile__item mobile-profile__item--danger">
                            ⎋ Cerrar sesión
                        </a>
                    @endif
                @endif
            </div>
        @endif
    </div>

    {{-- Menú móvil flotante --}}
    <div class="mobile-menu" x-show="mobileMenu" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full" @click.outside="mobileMenu = false">

        {{-- Header con logo/nombre del negocio --}}
        <div class="mobile-menu__header">
            <div class="mobile-menu__brand-section">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo {{ $businessName }}" class="mobile-menu__logo">
                @endif
                <div class="mobile-menu__business-name">{{ $businessName }}</div>
            </div>
            <button class="mobile-menu__close" @click="mobileMenu = false">✕</button>
        </div>

        {{-- Enlaces de navegación --}}
        <div class="mobile-menu__links">
            @foreach ($links as $lnk)
                <a href="{{ $lnk['href'] }}" class="mobile-menu__link" @click="mobileMenu = false">
                    <span class="mobile-menu__link-icon">📍</span>
                    {{ $lnk['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Footer opcional --}}
        <div class="mobile-menu__footer">
            <div class="mobile-menu__footer-text">© 2024 {{ $businessName }}</div>
        </div>
    </div>

    {{-- Overlay para el menú móvil --}}
    <div class="mobile-menu__overlay" x-show="mobileMenu" x-transition.opacity @click="mobileMenu = false"></div>
</nav>

<style>
    /* ===== NAVBAR GENERAL ===== */
    .client-navbar {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid #e5e5e7;
        z-index: 100;
    }

    /* ===== VERSIÓN DESKTOP ===== */
    .client-navbar__desktop {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem;
    }

    .client-navbar__left {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .client-navbar__logo {
        width: 36px;
        height: 36px;
        object-fit: contain;
        border-radius: 10px;
    }

    .client-navbar__brand {
        font-weight: 700;
        font-size: 1.25rem;
        color: #007AFF;
        letter-spacing: -.3px;
    }

    .client-navbar__center {
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .client-search {
        position: relative;
        width: min(380px, 90%);
    }

    .client-search__icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #86868B;
    }

    .client-search__input {
        width: 100%;
        background: #F5F5F7;
        border: 1px solid #E5E5E7;
        border-radius: 20px;
        padding: .7rem 1rem .7rem 2.4rem;
        color: #1D1D1F;
        transition: .2s;
    }

    .client-search__input:focus {
        outline: none;
        border-color: #007AFF;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0, 122, 255, .1);
    }

    .client-search__results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: .5rem;
        background: #fff;
        border: 1px solid #E5E5E7;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        max-height: 350px;
        overflow: auto;
        z-index: 50;
    }

    .client-navbar__right {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .client-link {
        color: #515154;
        text-decoration: none;
        font-weight: 500;
    }

    .client-link:hover {
        color: #007AFF;
    }

    .client-cart {
        position: relative;
    }

    .client-cart__btn {
        background: #007AFF;
        color: #fff;
        border: none;
        padding: .6rem 1.1rem;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        cursor: pointer;
        transition: .2s;
    }

    .client-cart__btn:hover {
        background: #0051D5;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
    }

    .client-cart__count {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #FF3B30;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 700;
    }

    /* Perfil Desktop */
    .client-profile {
        position: relative;
    }

    .client-profile__btn {
        display: flex;
        align-items: center;
        gap: .6rem;
        background: #fff;
        border: 1px solid #E5E5E7;
        padding: .35rem .6rem;
        border-radius: 999px;
        cursor: pointer;
    }

    .client-profile__avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #E5F0FF;
        color: #0051D5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .client-profile__name {
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
        color: #1D1D1F;
    }

    .client-profile__chev {
        width: 16px;
        height: 16px;
        color: #6E6E73
    }

    .client-profile__menu {
        position: absolute;
        right: 0;
        top: 110%;
        width: 220px;
        background: #fff;
        border: 1px solid #E5E5E7;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        padding: .4rem;
        z-index: 120;
    }

    .client-profile__item {
        display: block;
        width: 100%;
        text-align: left;
        padding: .6rem .8rem;
        border-radius: 8px;
        color: #1D1D1F;
        text-decoration: none;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 14px;
    }

    .client-profile__item:hover {
        background: #F5F5F7;
    }

    .client-profile__item--danger {
        color: #b91c1c;
    }

    .client-profile__item--danger:hover {
        background: #fee2e2;
    }

    /* ===== VERSIÓN MÓVIL ===== */
    .client-navbar__mobile {
        display: none;
        padding: 0.75rem 1rem;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    /* Botón hamburguesa */
    .mobile-menu__toggle {
        width: 40px;
        height: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 4px;
        background: transparent;
        border: none;
        cursor: pointer;
        z-index: 150;
    }

    .mobile-menu__toggle span {
        width: 22px;
        height: 2px;
        background: #1D1D1F;
        transition: all 0.3s ease;
        display: block;
    }

    /* Centro del navbar móvil */
    .mobile-navbar__center {
        flex: 1;
        display: flex;
        justify-content: center;
        padding: 0 0.5rem;
    }

    /* Buscador móvil siempre visible */
    .mobile-search-bar {
        position: relative;
        width: 100%;
        max-width: 200px;
    }

    .mobile-search-bar__icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #86868B;
        font-size: 0.85rem;
    }

    .mobile-search-bar__input {
        width: 100%;
        padding: 0.5rem 0.75rem 0.5rem 2.1rem;
        background: #F5F5F7;
        border: 1px solid #E5E5E7;
        border-radius: 16px;
        font-size: 13px;
        height: 36px;
        transition: 0.2s;
    }

    .mobile-search-bar__input:focus {
        outline: none;
        border-color: #007AFF;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0, 122, 255, .1);
    }

    /* Resultados del buscador móvil */
    .mobile-search-bar__results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 0.5rem;
        background: #fff;
        border: 1px solid #E5E5E7;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        max-height: 300px;
        overflow: auto;
        z-index: 150;
    }

    /* Acciones del navbar móvil (carrito y perfil) */
    .mobile-navbar__actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .mobile-cart__btn {
        position: relative;
        background: #007AFF;
        color: #fff;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
    }

    .mobile-cart__count {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 18px;
        height: 18px;
        background: #FF3B30;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
    }

    .mobile-profile__btn {
        background: transparent;
        border: 1px solid #E5E5E7;
        border-radius: 50%;
        padding: 0;
        cursor: pointer;
    }

    .mobile-profile__avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #E5F0FF;
        color: #0051D5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Dropdown del perfil móvil */
    .mobile-profile__dropdown {
        position: absolute;
        top: 100%;
        right: 1rem;
        margin-top: 0.5rem;
        width: 260px;
        background: #fff;
        border: 1px solid #E5E5E7;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
        z-index: 150;
        overflow: hidden;
    }

    .mobile-profile__header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .mobile-profile__avatar--large {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        color: #764ba2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .mobile-profile__info {
        flex: 1;
    }

    .mobile-profile__greeting {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .mobile-profile__name {
        font-weight: 600;
        font-size: 1rem;
    }

    .mobile-profile__item {
        display: block;
        padding: 0.9rem 1.25rem;
        color: #1D1D1F;
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
    }

    .mobile-profile__item:hover {
        background: #F5F5F7;
    }

    .mobile-profile__item--danger {
        color: #FF3B30;
    }

    .mobile-profile__item--danger:hover {
        background: #fee2e2;
    }

    /* Menú móvil flotante */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        height: 100vh;
        background: #fff;
        z-index: 200;
        box-shadow: 2px 0 25px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
    }

    .mobile-menu__overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 199;
    }

    .mobile-menu__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #E5E5E7;
        background: linear-gradient(135deg, #007AFF 0%, #0051D5 100%);
    }

    .mobile-menu__brand-section {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .mobile-menu__logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 10px;
        background: white;
        padding: 2px;
    }

    .mobile-menu__business-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #fff;
        letter-spacing: -0.3px;
    }

    .mobile-menu__title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #1D1D1F;
    }

    .mobile-menu__close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        color: #fff;
        transition: background 0.2s;
    }

    .mobile-menu__close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .mobile-menu__links {
        flex: 1;
        padding: 1rem 0;
        overflow-y: auto;
    }

    .mobile-menu__link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        color: #1D1D1F;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s;
    }

    .mobile-menu__link:hover {
        background: #F5F5F7;
    }

    .mobile-menu__link-icon {
        font-size: 1.2rem;
    }

    .mobile-menu__footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #E5E5E7;
        background: #F5F5F7;
    }

    .mobile-menu__footer-text {
        font-weight: 500;
        color: #6E6E73;
        font-size: 0.8rem;
        text-align: center;
    }

    /* ===== MEDIA QUERIES ===== */
    @media (max-width: 768px) {
        .client-navbar__desktop {
            display: none;
        }

        .client-navbar__mobile {
            display: flex;
        }
    }

    /* Transiciones Alpine.js */
    [x-cloak] {
        display: none !important;
    }
</style>

{{-- Alpine.js (si no lo tienes ya en la página) --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function showLogoutConfirmation(formId = 'logoutForm') {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro de que deseas cerrar tu sesión?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007AFF',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }


    // Sincronizar contadores de carrito
    document.addEventListener('DOMContentLoaded', function() {
        const desktopCount = document.getElementById('cartCount');
        const mobileCount = document.getElementById('mobileCartCount');
        if (desktopCount && mobileCount) {
            const obs = new MutationObserver(() => {
                mobileCount.textContent = desktopCount.textContent;
            });
            obs.observe(desktopCount, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }
    });

    // ===== BÚSQUEDA UNIFICADA (desktop + móvil) =====


    document.addEventListener('DOMContentLoaded', initSearchFunctionality);
</script>
