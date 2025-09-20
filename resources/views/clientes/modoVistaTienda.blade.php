@php
    use Illuminate\Support\Str;

    // Catálogo de categorías desde los servicios (nombre + slug)
    $cats = $servicios
        ->map(function ($s) {
            $name = optional($s->categoria)->nombre ?? 'General';
            return ['name' => $name, 'slug' => Str::slug($name)];
        })
        ->unique('slug')
        ->values();

    // Mapea iconos opcionales por nombre de categoría (ajusta a tu gusto)
    $catIcons = [
        'transporte' => '🚗',
        'belleza' => '💅',
        'barberia' => '✂️',
        'bienestar' => '🧘',
    ];

    // Servicios para JS (tal cual esperan tus scripts)
    $servicesJs = $servicios
        ->map(function ($s) {
            $catName = optional($s->categoria)->nombre ?? 'General';
            return [
                'id' => (string) $s->id,
                'title' => $s->nombre,
                'category' => Str::slug($catName),
                'categoryRaw' => $catName,
                'price' => (float) $s->precio,
                'description' => (string) ($s->descripcion ?? ''),
                'image' => $s->imagen_url ?? "https://picsum.photos/seed/serv{$s->id}/1200/800",
                'keywords' => collect(explode(' ', Str::lower($s->nombre)))
                    ->filter()
                    ->values()
                    ->all(),
                'features' => [], // si tienes tabla de features, llénala aquí
            ];
        })
        ->values();
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <!-- Favicon -->
    <link rel="icon" href="https://tuservidor.com/ruta/logo.png" type="image/png">


    @php
        $file =
            $usuario->business_logo_filename ??
            ($usuario->business_logo_path ? basename($usuario->business_logo_path) : null);
        $logoUrl = $file ? asset('storage/business-logos/' . $file) : null;
        $businessName = $usuario->business_name ?? 'Negocio';
    @endphp


    {{-- Favicon / App icon: solo si hay logo --}}
    @if ($logoUrl)
        <link rel="icon" href="{{ $logoUrl }}">
        <link rel="apple-touch-icon" href="{{ $logoUrl }}">
    @endif

    <style>
        :root {
            --primary-blue: #007AFF;
            --secondary-blue: #5AC8FA;
            --dark-blue: #0051D5;
            --light-blue: #F0F8FF;
            --accent-blue: #34C759;
            --white: #FFFFFF;
            --gray-50: #FAFAFA;
            --gray-100: #F5F5F7;
            --gray-200: #E5E5E7;
            --gray-300: #D2D2D7;
            --gray-400: #86868B;
            --gray-500: #6E6E73;
            --gray-600: #515154;
            --gray-700: #1D1D1F;
            --border-radius: 12px;
            --border-radius-lg: 20px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.1);
            --backdrop-blur: blur(20px);
            --transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--white) 50%, var(--gray-50) 100%);
            min-height: 100vh;
            color: var(--gray-700);
            line-height: 1.6;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: var(--backdrop-blur);
            border-bottom: 1px solid var(--gray-200);
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: -0.5px;
        }

        .nav-center {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 0.7rem 1rem 0.7rem 2.8rem;
            font-size: 0.95rem;
            color: var(--gray-700);
            transition: var(--transition);
            width: 320px;
            font-weight: 400;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            width: 380px;
        }

        .search-input::placeholder {
            color: var(--gray-400);
            font-weight: 400;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: var(--gray-400);
            font-size: 1rem;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            max-height: 350px;
            overflow-y: auto;
            z-index: 50;
            margin-top: 0.5rem;
            backdrop-filter: var(--backdrop-blur);
        }

        .search-result-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-result-item:hover {
            background: var(--gray-50);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--light-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .search-result-content {
            flex: 1;
        }

        .search-result-title {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .search-result-meta {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 0.2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary-blue);
        }

        .cart-container {
            position: relative;
        }

        .cart-btn {
            background: var(--primary-blue);
            border: none;
            color: var(--white);
            padding: 0.7rem 1.3rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .cart-btn:hover {
            background: var(--dark-blue);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #FF3B30;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .hero {
            text-align: center;
            padding: 8rem 2rem 4rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 1.5rem;
            letter-spacing: -2px;
            line-height: 1.1;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--gray-500);
            margin-bottom: 3rem;
            font-weight: 400;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 3rem;
        }

        .cta-button {
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 1.1rem 2.8rem;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
        }

        .cta-button:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .categories-section {
            padding: 4rem 2rem;
            background: linear-gradient(180deg, var(--white) 0%, var(--gray-50) 100%);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: var(--gray-500);
            margin-bottom: 4rem;
            font-weight: 400;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .category-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--gray-200);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
            transform: scaleX(0);
            transition: var(--transition);
        }

        .category-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-blue);
        }

        .category-card:hover::before {
            transform: scaleX(1);
        }

        .category-card.active {
            border-color: var(--primary-blue);
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--white) 100%);
        }

        .category-card.active::before {
            transform: scaleX(1);
        }

        .category-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
        }

        .category-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.8rem;
            letter-spacing: -0.3px;
        }

        .category-description {
            color: var(--gray-500);
            font-size: 0.95rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .category-count {
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .category-card.active .category-count {
            background: var(--primary-blue);
            color: var(--white);
        }

        .services-section {
            padding: 4rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .services-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .services-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-700);
            letter-spacing: -1px;
        }

        .filter-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-btn {
            background: var(--white);
            border: 2px solid var(--gray-200);
            color: var(--gray-600);
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .filter-btn:hover,
        .filter-btn.active {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            background: var(--light-blue);
        }

        .clear-filters {
            color: var(--gray-400);
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: underline;
            transition: var(--transition);
        }

        .clear-filters:hover {
            color: var(--primary-blue);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            opacity: 1;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--gray-300);
        }

        .service-card.hidden {
            display: none;
        }

        .service-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .service-content {
            padding: 2rem;
        }

        .service-badge {
            background: var(--light-blue);
            color: var(--primary-blue);
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .service-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.8rem;
            letter-spacing: -0.3px;
        }

        .service-description {
            color: var(--gray-500);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .service-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
            letter-spacing: -0.5px;
        }

        .detail-btn {
            background: transparent;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 0.7rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .detail-btn:hover {
            background: var(--primary-blue);
            color: var(--white);
        }

        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }

        .no-results-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-results h3 {
            font-size: 1.5rem;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-container {
            background: var(--white);
            width: 100%;
            max-width: 500px;
            max-height: 85vh;
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            animation: slideUp 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .modal-header {
            position: sticky;
            top: 0;
            background: var(--white);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .modal-close {
            background: var(--gray-100);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .modal-close:hover {
            background: var(--gray-200);
            transform: scale(1.1);
        }

        .modal-content {
            overflow-y: auto;
            flex: 1;
            padding-bottom: 2rem;
        }

        .modal-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-badge {
            background: var(--light-blue);
            color: var(--primary-blue);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .modal-price {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        .modal-description {
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .modal-features {
            margin-bottom: 2rem;
        }

        .modal-features-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 1rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-600);
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-icon {
            color: var(--accent-blue);
            font-size: 1.2rem;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            background: var(--white);
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 1rem;
        }

        .add-to-cart-btn {
            flex: 1;
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 1rem;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .add-to-cart-btn:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .add-to-cart-btn.added {
            background: var(--accent-blue);
            animation: cartAdded 0.6s ease;
        }

        @keyframes cartAdded {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--gray-100);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
        }

        .quantity-btn {
            background: var(--white);
            border: 1px solid var(--gray-300);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .quantity-btn:hover {
            background: var(--primary-blue);
            color: var(--white);
            border-color: var(--primary-blue);
        }

        .quantity-value {
            font-weight: 600;
            font-size: 1.1rem;
            min-width: 30px;
            text-align: center;
        }

        /* Responsive tweaks */
        @media (min-width: 768px) {
            .modal-overlay.active {
                align-items: center;
            }

            .modal-container {
                border-radius: var(--border-radius-lg);
                max-height: 90vh;
            }

            .modal-image {
                height: 300px;
            }
        }

        @media (max-width: 1024px) {
            .services-header {
                flex-direction: column;
                gap: 2rem;
                align-items: stretch;
            }

            .filter-controls {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-center {
                display: none;
            }

            .hero {
                padding: 6rem 1rem 3rem;
            }

            .search-input {
                width: 100%;
            }

            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .category-card {
                padding: 2rem 1.5rem;
            }

            .category-icon {
                font-size: 2.8rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .categories-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">{{ $usuario->business_name ?? 'CitaFlow' }}</div>
        <div class="nav-center">
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Buscar servicios, categorías..."
                    id="searchInput">
                <div class="search-results" id="searchResults" style="display: none;"></div>
            </div>
        </div>
        <div class="nav-links">
            <a href="#inicio">Inicio</a>
            <a href="#servicios">Servicios</a>
            <div class="cart-container">
                <button class="cart-btn" onclick="toggleCart()">
                    🛒 Carrito <span class="cart-count" id="cartCount">0</span>
                </button>
            </div>
        </div>
    </nav>

    {{-- SLIDER: usa los 3 primeros servicios como destacados --}}
    @php $featured = $servicesJs->take(3); @endphp
    @if ($featured->isNotEmpty())
        <section class="slider-section" id="sliderServicios">
            <h2 class="section-title">Servicios Premium Populares</h2>
            <div class="slider-container">
                <div class="slider-track">
                    @foreach ($featured as $f)
                        <div class="slide">
                            <img src="{{ $f['image'] }}" alt="{{ $f['title'] }}">
                            <div class="slide-overlay">
                                <div class="slide-info">
                                    <h3>{{ $f['title'] }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($f['description'] ?? '', 140) }}</p>
                                    <div class="service-footer">
                                        <span class="service-price">${{ number_format($f['price'], 2) }}</span>
                                        <button class="detail-btn" onclick="openModal('{{ $f['id'] }}')">Ver
                                            Detalles</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="slider-dots"></div>
            </div>
        </section>
    @endif

    <style>
        /* ===== SECCIÓN SLIDER ===== (tu CSS original) */
        .slider-section {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .slider-container {
            position: relative;
            overflow: hidden;
        }

        .slider-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .slide {
            min-width: 100%;
            position: relative;
        }

        .slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .slide-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 1rem;
            border-radius: 1rem;
            pointer-events: none;
        }

        .slide-info {
            color: #fff;
            backdrop-filter: blur(6px);
            background: rgba(0, 0, 0, 0.5);
            padding: 0.8rem 1rem;
            border-radius: 0.8rem;
            text-align: center;
            width: auto;
            max-width: 90%;
            pointer-events: auto;
        }

        .slide-info h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .slide-info p {
            font-size: 0.85rem;
            margin-bottom: 0.8rem;
            line-height: 1.4;
            color: #e5e5e5;
        }

        .service-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .service-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #007dd1;
        }

        .detail-btn {
            background: #007dd1;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .detail-btn:hover {
            background: #005fa3;
            transform: translateY(-2px);
        }

        .slider-dots {
            text-align: center;
            margin-top: 1rem;
        }

        .slider-dots button {
            background: #ccc;
            border: none;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .slider-dots button.active {
            background: #0066ff;
            transform: scale(1.2);
        }

        @media (min-width: 1024px) {
            .slide img {
                height: 550px;
            }

            .slide-overlay {
                justify-content: flex-start;
                align-items: center;
                padding: 3rem;
                background: linear-gradient(to right, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0));
            }

            .slide-info {
                max-width: 45%;
                text-align: left;
                padding: 2rem;
            }

            .slide-info h3 {
                font-size: 2rem;
            }

            .slide-info p {
                font-size: 1rem;
            }

            .service-footer {
                justify-content: flex-start;
            }

            .detail-btn {
                font-size: 1rem;
                padding: 0.8rem 1.4rem;
            }
        }
    </style>

    <script>
        // ------- Slider (tu mismo JS) -------
        const track = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.slide');
        const dotsContainer = document.querySelector('.slider-dots');
        if (track && slides.length) {
            let index = 0;
            slides.forEach((_, i) => {
                const dot = document.createElement('button');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => showSlide(i));
                dotsContainer.appendChild(dot);
            });
            const dots = dotsContainer.querySelectorAll('button');

            function showSlide(i) {
                if (i < 0) index = slides.length - 1;
                else if (i >= slides.length) index = 0;
                else index = i;
                track.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach(dot => dot.classList.remove('active'));
                dots[index].classList.add('active');
            }
            let autoPlay = setInterval(() => {
                showSlide(index + 1);
            }, 5000);
            let startX = 0;
            let isDragging = false;
            track.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                clearInterval(autoPlay);
            });
            track.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                let diff = startX - e.touches[0].clientX;
                if (diff > 50) {
                    showSlide(index + 1);
                    isDragging = false;
                } else if (diff < -50) {
                    showSlide(index - 1);
                    isDragging = false;
                }
            });
            track.addEventListener('touchend', () => {
                isDragging = false;
                autoPlay = setInterval(() => {
                    showSlide(index + 1);
                }, 5000);
            });
        }
    </script>

    <section class="categories-section">
        <h2 class="section-title">Categorías</h2>
        <p class="section-subtitle">Encuentra exactamente lo que necesitas</p>
        <div class="categories-grid">
            @foreach ($cats as $c)
                @php
                    $count = $servicesJs->where('category', $c['slug'])->count();
                    $icon = $catIcons[$c['slug']] ?? '🛍️';
                @endphp
                <div class="category-card" data-category="{{ $c['slug'] }}"
                    onclick="filterByCategory('{{ $c['slug'] }}')">
                    <div class="category-icon">{{ $icon }}</div>
                    <h3 class="category-title">{{ $c['name'] }}</h3>
                    <p class="category-description">{{ $count }} servicio(s) del negocio</p>
                    <span class="category-count">{{ $count }} servicios</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="services-section" id="servicios">
        <div class="services-header">
            <h2 class="services-title">Todos los Servicios</h2>
            <div class="filter-controls">
                <button class="filter-btn active" onclick="filterByCategory('all')">Todos</button>
                @foreach ($cats as $c)
                    <button class="filter-btn"
                        onclick="filterByCategory('{{ $c['slug'] }}')">{{ $c['name'] }}</button>
                @endforeach
                <span class="clear-filters" onclick="clearFilters()">Limpiar filtros</span>
            </div>
        </div>

        <div class="services-grid" id="servicesGrid">
            @foreach ($servicesJs as $s)
                <div class="service-card" data-category="{{ $s['category'] }}" data-service-id="{{ $s['id'] }}">
                    <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}" class="service-image">
                    <div class="service-content">
                        <div class="service-badge">{{ $s['categoryRaw'] }}</div>
                        <h3 class="service-title">{{ $s['title'] }}</h3>
                        <p class="service-description">{{ \Illuminate\Support\Str::limit($s['description'], 160) }}</p>
                        <div class="service-footer">
                            <span class="service-price">${{ number_format($s['price'], 2) }}</span>
                            <button class="detail-btn" onclick="openModal('{{ $s['id'] }}')">Ver Detalles</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="no-results" id="noResults" style="display: none;">
            <div class="no-results-icon">🔍</div>
            <h3>No se encontraron servicios</h3>
            <p>Intenta con otros términos de búsqueda o categorías</p>
        </div>
    </section>

    <style>
        /* (Tu CSS de la sección services tal cual) */
        .services-section {
            padding: 1.5rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .services-title {
            font-size: 1.6rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: #1a1a1a;
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .filter-btn {
            background: #f4f4f4;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #0066ff;
            color: #fff;
            font-weight: 600;
        }

        .filter-btn:hover {
            background: #e0e0e0;
        }

        .clear-filters {
            font-size: 0.85rem;
            color: #0066ff;
            cursor: pointer;
            align-self: center;
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 600px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 900px) {
            .services-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .service-card {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .service-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .service-content {
            padding: 1rem;
        }

        .service-badge {
            display: inline-block;
            font-size: 0.75rem;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            background: #eef3ff;
            color: #0066ff;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .service-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .service-description {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .service-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .service-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0066ff;
        }

        .detail-btn {
            background: #0066ff;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .detail-btn:hover {
            background: #004bb3;
        }

        .no-results {
            text-align: center;
            padding: 2rem 1rem;
            color: #555;
        }

        .no-results-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>

    <!-- Modal de Detalles (idéntico) -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Detalles del Servicio</h3>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <div class="modal-content">
                <img id="modalImage" src="" alt="" class="modal-image">
                <div class="modal-body">
                    <div id="modalBadge" class="modal-badge"></div>
                    <h2 id="modalTitle" class="modal-title"></h2>
                    <div id="modalPrice" class="modal-price"></div>
                    <p id="modalDescription" class="modal-description"></p>

                    <div class="modal-features">
                        <h3 class="modal-features-title">Incluye:</h3>
                        <ul id="modalFeatures" class="feature-list"></ul>
                    </div>

                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="updateQuantity(-1)">−</button>
                        <span class="quantity-value" id="quantityValue">1</span>
                        <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="add-to-cart-btn" id="addToCartBtn" onclick="addToCart()">
                    <span>🛒</span>
                    <span>Agregar al Carrito</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        // ***** DATOS DESDE BD (inyectados como JSON) *****
        const services = @json($servicesJs);

        // ====== Modal & carrito (sin cambios visuales) ======
        let currentFilter = 'all';
        let searchTerm = '';
        let selectedService = null;
        let quantity = 1;
        let cartItems = [];

        function openModal(serviceId) {
            selectedService = services.find(s => s.id === String(serviceId));
            quantity = 1;
            if (!selectedService) return;
            document.getElementById('modalImage').src = selectedService.image;
            document.getElementById('modalImage').alt = selectedService.title;
            document.getElementById('modalBadge').textContent = selectedService.categoryRaw || 'Servicio';
            document.getElementById('modalTitle').textContent = selectedService.title;
            document.getElementById('modalPrice').textContent = `$${selectedService.price.toFixed(2)}`;
            document.getElementById('modalDescription').textContent = selectedService.description || '';

            const featuresList = document.getElementById('modalFeatures');
            const feats = selectedService.features || [];
            featuresList.innerHTML = feats.length ?
                feats.map(f => `<li class="feature-item"><span class="feature-icon">✅</span><span>${f}</span></li>`).join(
                    '') :
                '';

            document.getElementById('quantityValue').textContent = '1';
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = '';
            selectedService = null;
            quantity = 1;
        }

        function updateQuantity(change) {
            quantity = Math.max(1, quantity + change);
            document.getElementById('quantityValue').textContent = quantity;
            if (selectedService) {
                const totalPrice = selectedService.price * quantity;
                document.getElementById('modalPrice').textContent = `$${totalPrice.toFixed(2)}`;
            }
        }

        function addToCart() {
            if (!selectedService) return;
            cartItems.push({
                id: selectedService.id,
                qty: quantity,
                price: selectedService.price
            });
            const totalItems = cartItems.reduce((sum, it) => sum + it.qty, 0);
            document.getElementById('cartCount').textContent = totalItems;
            const addBtn = document.getElementById('addToCartBtn');
            addBtn.classList.add('added');
            addBtn.innerHTML = '<span>✓</span><span>¡Agregado!</span>';
            setTimeout(() => {
                closeModal();
                setTimeout(() => {
                    addBtn.classList.remove('added');
                    addBtn.innerHTML = '<span>🛒</span><span>Agregar al Carrito</span>';
                }, 300);
            }, 1000);
        }
        document.getElementById('modalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ====== Búsqueda (idéntico, pero con datos reales) ======
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        function debounce(func, wait) {
            let t;
            return (...a) => {
                clearTimeout(t);
                t = setTimeout(() => func(...a), wait);
            };
        }
        const debouncedSearch = debounce(function(query) {
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            const q = query.toLowerCase();
            const results = services.filter(s =>
                s.title.toLowerCase().includes(q) ||
                s.category.toLowerCase().includes(q) ||
                (s.description || '').toLowerCase().includes(q) ||
                (s.keywords || []).some(k => (k || '').toLowerCase().includes(q))
            );
            if (results.length > 0) {
                searchResults.innerHTML = results.map(s => `
                    <div class="search-result-item" onclick="selectSearchResult('${s.id}', '${s.title.replace(/'/g,"&#39;")}')">
                        <div class="search-result-icon">🔹</div>
                        <div class="search-result-content">
                            <div class="search-result-title">${s.title}</div>
                            <div class="search-result-meta">${s.categoryRaw} • $${s.price.toFixed(2)}</div>
                        </div>
                    </div>`).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = `
                    <div class="search-result-item">
                        <div class="search-result-icon">🔍</div>
                        <div class="search-result-content">
                            <div class="search-result-title">No se encontraron resultados</div>
                            <div class="search-result-meta">Intenta con otros términos</div>
                        </div>
                    </div>`;
                searchResults.style.display = 'block';
            }
        }, 300);
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            searchTerm = query;
            debouncedSearch(query);
            if (query.length >= 2) filterServices();
            else {
                searchTerm = '';
                filterServices();
            }
        });

        function selectSearchResult(serviceId, title) {
            searchInput.value = title;
            searchResults.style.display = 'none';
            const card = document.querySelector(`[data-service-id="${serviceId}"]`);
            if (card) {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 500);
            }
        }
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        // ====== Filtros de categoría (sin cambios visuales) ======
        function filterByCategory(category) {
            currentFilter = category;
            document.querySelectorAll('.category-card').forEach(card => {
                if (category === 'all') card.classList.remove('active');
                else if (card.dataset.category === category) card.classList.add('active');
                else card.classList.remove('active');
            });
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            if (category === 'all') document.querySelector('.filter-btn').classList.add('active');
            else {
                const targetBtn = Array.from(document.querySelectorAll('.filter-btn')).find(btn => btn.textContent
                    .toLowerCase().includes(category.toLowerCase()));
                if (targetBtn) targetBtn.classList.add('active');
            }
            filterServices();
        }

        function filterServices() {
            const serviceCards = document.querySelectorAll('.service-card');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;
            serviceCards.forEach(card => {
                const cardCategory = card.dataset.category;
                const title = card.querySelector('.service-title').textContent.toLowerCase();
                const desc = card.querySelector('.service-description').textContent.toLowerCase();
                const badge = card.querySelector('.service-badge').textContent.toLowerCase();
                const matchesFilter = currentFilter === 'all' || cardCategory === currentFilter;
                const matchesSearch = !searchTerm || title.includes(searchTerm) || desc.includes(searchTerm) ||
                    badge.includes(searchTerm) || cardCategory.includes(searchTerm);
                if (matchesFilter && matchesSearch) {
                    card.classList.remove('hidden');
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                    card.style.display = 'none';
                }
            });
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            const visibleCards = document.querySelectorAll('.service-card:not(.hidden)');
            visibleCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        function clearFilters() {
            currentFilter = 'all';
            searchTerm = '';
            searchInput.value = '';
            searchResults.style.display = 'none';
            document.querySelectorAll('.category-card').forEach(card => card.classList.remove('active'));
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector('.filter-btn').classList.add('active');
            filterServices();
        }

        function toggleCart() {
            alert(`🛒 Tienes ${cartItems.length} servicios en tu carrito`);
        }

        window.addEventListener('load', () => {
            document.querySelectorAll('.service-card').forEach((card, i) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, i * 150);
            });
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (document.getElementById('modalOverlay').classList.contains('active')) closeModal();
                else {
                    searchResults.style.display = 'none';
                    searchInput.blur();
                }
            }
            if (e.key === '/' && !searchInput.contains(document.activeElement)) {
                e.preventDefault();
                searchInput.focus();
            }
        });
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>
</body>

</html>
