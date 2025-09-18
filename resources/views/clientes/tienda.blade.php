<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <!-- Favicon -->
    <link rel="icon" href="https://tuservidor.com/ruta/logo.png" type="image/png">



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

        /* Desktop adjustments for modal */
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
        <div class="logo">CitaFlow</div>
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
                    🛒 Carrito
                    <span class="cart-count" id="cartCount">0</span>
                </button>
            </div>
        </div>
    </nav>

    <section class="slider-section" id="sliderServicios">
        <h2 class="section-title">Servicios Premium Populares</h2>
        <div class="slider-container">
            <div class="slider-track">

                <!-- Slide -->
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop&q=80"
                        alt="Tour por la Ciudad">
                    <div class="slide-overlay">
                        <div class="slide-info">
                            <h3>Tour por la Ciudad</h3>
                            <p>Explora la ciudad con comodidad y estilo en transporte premium con guía especializado.
                            </p>
                            <div class="service-footer">
                                <span class="service-price">$80</span>
                                <button class="detail-btn" onclick="openModal('tour')">Ver Detalles</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide -->
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?w=1200&h=800&fit=crop&q=80"
                        alt="Peinado Profesional">
                    <div class="slide-overlay">
                        <div class="slide-info">
                            <h3>Peinado Profesional</h3>
                            <p>Peinados elegantes y sofisticados para cualquier ocasión especial.</p>
                            <div class="service-footer">
                                <span class="service-price">$35</span>
                                <button class="detail-btn" onclick="openModal('peinado')">Ver Detalles</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Más slides... -->

            </div>

            <!-- Indicadores -->
            <div class="slider-dots"></div>
        </div>
    </section>

    <style>
        /* ===== SECCIÓN SLIDER ===== */
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

/* ===== SLIDER ===== */
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
  box-shadow: 0 8px 24px rgba(0,0,0,0.25);
}

/* ===== OVERLAY Y DETALLES ===== */
.slide-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  padding: 1rem;
  border-radius: 1rem;
  pointer-events: none; /* permite que la imagen reciba gestos de swipe */
}
.slide-info {
  color: #fff;
  backdrop-filter: blur(6px);
  background: rgba(0,0,0,0.5);
  padding: 0.8rem 1rem;
  border-radius: 0.8rem;
  text-align: center;
  width: auto;
  max-width: 90%;
  pointer-events: auto; /* habilita clic en botón */
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
  color: #007dd1; /* azul premium */
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

/* ===== INDICADORES ===== */
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

/* ===== DESKTOP ===== */
@media (min-width: 1024px) {
  .slide img {
    height: 550px;
  }
  .slide-overlay {
    justify-content: flex-start;
    align-items: center;
    padding: 3rem;
    background: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0));
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
        const track = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.slide');
        const dotsContainer = document.querySelector('.slider-dots');

        let index = 0;

        // Crear puntos dinámicos
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

        // autoplay
        let autoPlay = setInterval(() => {
            showSlide(index + 1);
        }, 5000);

        // Swipe en móviles
        let startX = 0;
        let isDragging = false;

        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            clearInterval(autoPlay);
        });
        track.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            let moveX = e.touches[0].clientX;
            let diff = startX - moveX;
            if (diff > 50) { // deslizar izquierda
                showSlide(index + 1);
                isDragging = false;
            } else if (diff < -50) { // deslizar derecha
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
    </script>


    <section class="categories-section">
        <h2 class="section-title">Categorías</h2>
        <p class="section-subtitle">Encuentra exactamente lo que necesitas</p>
        <div class="categories-grid">
            <div class="category-card" data-category="transporte" onclick="filterByCategory('transporte')">
                <div class="category-icon">🚗</div>
                <h3 class="category-title">Transporte</h3>
                <p class="category-description">Tours y traslados premium con vehículos de lujo</p>
                <span class="category-count">2 servicios</span>
            </div>
            <div class="category-card" data-category="belleza" onclick="filterByCategory('belleza')">
                <div class="category-icon">💅</div>
                <h3 class="category-title">Belleza</h3>
                <p class="category-description">Tratamientos estéticos y cuidado personal profesional</p>
                <span class="category-count">2 servicios</span>
            </div>
            <div class="category-card" data-category="barberia" onclick="filterByCategory('barberia')">
                <div class="category-icon">✂️</div>
                <h3 class="category-title">Barbería</h3>
                <p class="category-description">Cortes y afeitados con técnicas tradicionales y modernas</p>
                <span class="category-count">1 servicio</span>
            </div>
            <div class="category-card" data-category="bienestar" onclick="filterByCategory('bienestar')">
                <div class="category-icon">🧘</div>
                <h3 class="category-title">Bienestar</h3>
                <p class="category-description">Terapias y masajes para tu relajación total</p>
                <span class="category-count">1 servicio</span>
            </div>
        </div>
    </section>

    <section class="services-section" id="servicios">
        <div class="services-header">
            <h2 class="services-title">Todos los Servicios</h2>
            <div class="filter-controls">
                <button class="filter-btn active" onclick="filterByCategory('all')">Todos</button>
                <button class="filter-btn" onclick="filterByCategory('transporte')">Transporte</button>
                <button class="filter-btn" onclick="filterByCategory('belleza')">Belleza</button>
                <button class="filter-btn" onclick="filterByCategory('barberia')">Barbería</button>
                <button class="filter-btn" onclick="filterByCategory('bienestar')">Bienestar</button>
                <span class="clear-filters" onclick="clearFilters()">Limpiar filtros</span>
            </div>
        </div>

        <div class="services-grid" id="servicesGrid">
            <!-- Service Card -->
            <div class="service-card" data-category="transporte" data-service-id="tour">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=220&fit=crop&q=80"
                    alt="Tour por la Ciudad" class="service-image">
                <div class="service-content">
                    <div class="service-badge">Transporte</div>
                    <h3 class="service-title">Tour por la Ciudad</h3>
                    <p class="service-description">Explora la ciudad con comodidad y estilo en transporte premium con
                        guía especializado.</p>
                    <div class="service-footer">
                        <span class="service-price">$80</span>
                        <button class="detail-btn" onclick="openModal('tour')">Ver Detalles</button>
                    </div>
                </div>
            </div>

            <!-- Otro ejemplo -->
            <div class="service-card" data-category="belleza" data-service-id="peinado">
                <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?w=400&h=220&fit=crop&q=80"
                    alt="Peinado Profesional" class="service-image">
                <div class="service-content">
                    <div class="service-badge">Belleza</div>
                    <h3 class="service-title">Peinado Profesional</h3>
                    <p class="service-description">Peinados elegantes y sofisticados para cualquier ocasión especial
                        con productos premium.</p>
                    <div class="service-footer">
                        <span class="service-price">$35</span>
                        <button class="detail-btn" onclick="openModal('peinado')">Ver Detalles</button>
                    </div>
                </div>
            </div>
            <!-- Resto de servicios... -->
        </div>

        <div class="no-results" id="noResults" style="display: none;">
            <div class="no-results-icon">🔍</div>
            <h3>No se encontraron servicios</h3>
            <p>Intenta con otros términos de búsqueda o categorías</p>
        </div>
    </section>

    <style>
        /* ===== SECCIÓN ===== */
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

        /* ===== FILTROS ===== */
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

        /* ===== GRID DE SERVICIOS ===== */
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

        /* ===== TARJETAS ===== */
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

        /* ===== NO RESULTADOS ===== */
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


    <!-- Modal de Detalles -->
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
        let currentFilter = 'all';
        let searchTerm = '';
        let selectedService = null;
        let quantity = 1;
        let cartItems = [];

        // Datos de servicios extendidos con más detalles
        const services = {
            tour: {
                id: 'tour',
                title: 'Tour por la Ciudad',
                category: 'transporte',
                price: 80,
                description: 'Explora la ciudad con comodidad y estilo en nuestro servicio de transporte premium. Disfruta de un recorrido completo por los lugares más emblemáticos con un guía especializado que te contará la historia y secretos mejor guardados de cada sitio.',
                image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=220&fit=crop&q=80',
                keywords: ['tour', 'ciudad', 'transporte', 'guía', 'premium', 'vehículo'],
                features: [
                    '✅ Vehículo premium con aire acondicionado',
                    '✅ Guía profesional bilingüe',
                    '✅ Duración de 4 horas',
                    '✅ Paradas en 6 puntos turísticos',
                    '✅ Agua embotellada incluida',
                    '✅ Seguro de viaje'
                ]
            },
            peinado: {
                id: 'peinado',
                title: 'Peinado Profesional',
                category: 'belleza',
                price: 35,
                description: 'Transforma tu look con nuestros peinados elegantes y sofisticados. Nuestros estilistas expertos trabajarán contigo para crear el peinado perfecto para tu ocasión especial, utilizando productos premium y técnicas de vanguardia.',
                image: 'https://images.unsplash.com/photo-1562322140-8baeececf3df?w=400&h=220&fit=crop&q=80',
                keywords: ['peinado', 'cabello', 'estilo', 'belleza', 'elegante', 'ocasión'],
                features: [
                    '✅ Consulta personalizada',
                    '✅ Lavado con shampoo premium',
                    '✅ Tratamiento capilar incluido',
                    '✅ Peinado a elección',
                    '✅ Fijación con productos de alta calidad',
                    '✅ Retoque de maquillaje'
                ]
            },
            corte: {
                id: 'corte',
                title: 'Corte Premium',
                category: 'barberia',
                price: 25,
                description: 'Experimenta el arte de la barbería tradicional con un toque moderno. Nuestros barberos expertos te ofrecerán un corte personalizado que realzará tus mejores rasgos, utilizando técnicas precisas y herramientas profesionales.',
                image: 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?w=400&h=220&fit=crop&q=80',
                keywords: ['corte', 'cabello', 'barbería', 'moderno', 'clásico', 'profesional'],
                features: [
                    '✅ Consulta de estilo personalizada',
                    '✅ Lavado con shampoo premium',
                    '✅ Corte con tijeras y/o máquina',
                    '✅ Perfilado de barba',
                    '✅ Aplicación de productos de acabado',
                    '✅ Toalla caliente'
                ]
            },
            masaje: {
                id: 'masaje',
                title: 'Terapia Relajante',
                category: 'bienestar',
                price: 60,
                description: 'Desconéctate del estrés diario con nuestra terapia relajante integral. Nuestros terapeutas certificados utilizarán técnicas especializadas para liberar tensiones, mejorar tu circulación y restaurar tu bienestar físico y mental.',
                image: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400&h=220&fit=crop&q=80',
                keywords: ['masaje', 'terapia', 'relajante', 'bienestar', 'terapéutico', 'mental'],
                features: [
                    '✅ Sesión de 60 minutos',
                    '✅ Aceites esenciales aromáticos',
                    '✅ Música relajante',
                    '✅ Técnicas de relajación profunda',
                    '✅ Ambiente climatizado',
                    '✅ Té de cortesía post-sesión'
                ]
            },
            transfer: {
                id: 'transfer',
                title: 'Transfer Premium',
                category: 'transporte',
                price: 45,
                description: 'Viaja con estilo y comodidad absoluta. Nuestro servicio de transfer premium te garantiza puntualidad, seguridad y el máximo confort en vehículos de lujo con conductores profesionales altamente capacitados.',
                image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=400&h=220&fit=crop&q=80',
                keywords: ['transfer', 'traslado', 'ejecutivo', 'lujo', 'puntual', 'comodidad'],
                features: [
                    '✅ Vehículo ejecutivo de lujo',
                    '✅ Conductor profesional uniformado',
                    '✅ WiFi a bordo',
                    '✅ Agua y snacks de cortesía',
                    '✅ Seguimiento en tiempo real',
                    '✅ Máximo 4 pasajeros'
                ]
            },
            manicure: {
                id: 'manicure',
                title: 'Manicure Premium',
                category: 'belleza',
                price: 28,
                description: 'Dale a tus manos el cuidado que merecen con nuestro servicio de manicure premium. Utilizamos productos de alta gama y técnicas profesionales para garantizar unas uñas perfectas y saludables.',
                image: 'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?w=400&h=220&fit=crop&q=80',
                keywords: ['manicure', 'uñas', 'cuidado', 'belleza', 'profesional', 'premium'],
                features: [
                    '✅ Limpieza y limado profesional',
                    '✅ Tratamiento de cutículas',
                    '✅ Exfoliación de manos',
                    '✅ Masaje hidratante',
                    '✅ Esmalte de alta duración',
                    '✅ Diseño personalizado opcional'
                ]
            }
        };

        const categories = {
            'transporte': {
                icon: '🚗',
                name: 'Transporte'
            },
            'belleza': {
                icon: '💅',
                name: 'Belleza'
            },
            'barberia': {
                icon: '✂️',
                name: 'Barbería'
            },
            'bienestar': {
                icon: '🧘',
                name: 'Bienestar'
            }
        };

        // Modal Functions
        function openModal(serviceId) {
            selectedService = services[serviceId];
            quantity = 1;

            if (!selectedService) return;

            // Update modal content
            document.getElementById('modalImage').src = selectedService.image;
            document.getElementById('modalImage').alt = selectedService.title;
            document.getElementById('modalBadge').textContent = categories[selectedService.category].name;
            document.getElementById('modalTitle').textContent = selectedService.title;
            document.getElementById('modalPrice').textContent = `$${selectedService.price}`;
            document.getElementById('modalDescription').textContent = selectedService.description;

            // Update features list
            const featuresList = document.getElementById('modalFeatures');
            featuresList.innerHTML = selectedService.features.map(feature =>
                `<li class="feature-item">
                    <span class="feature-icon">${feature.substring(0, 2)}</span>
                    <span>${feature.substring(2)}</span>
                </li>`
            ).join('');

            // Reset quantity
            document.getElementById('quantityValue').textContent = '1';

            // Show modal
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

            // Update price display
            if (selectedService) {
                const totalPrice = selectedService.price * quantity;
                document.getElementById('modalPrice').textContent = `$${totalPrice}`;
            }
        }

        function addToCart() {
            if (!selectedService) return;

            // Add item to cart
            const cartItem = {
                ...selectedService,
                quantity: quantity,
                totalPrice: selectedService.price * quantity
            };

            cartItems.push(cartItem);

            // Update cart count
            const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = totalItems;

            // Animate button
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

        // Close modal when clicking overlay
        document.getElementById('modalOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        const debouncedSearch = debounce(function(query) {
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            const results = Object.values(services).filter(service =>
                service.title.toLowerCase().includes(query) ||
                service.category.toLowerCase().includes(query) ||
                service.description.toLowerCase().includes(query) ||
                service.keywords.some(keyword => keyword.toLowerCase().includes(query))
            );

            if (results.length > 0) {
                searchResults.innerHTML = results.map(service => `
                    <div class="search-result-item" onclick="selectSearchResult('${service.id}', '${service.title}')">
                        <div class="search-result-icon">${categories[service.category].icon}</div>
                        <div class="search-result-content">
                            <div class="search-result-title">${service.title}</div>
                            <div class="search-result-meta">${categories[service.category].name} • $${service.price}</div>
                        </div>
                    </div>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = `
                    <div class="search-result-item">
                        <div class="search-result-icon">🔍</div>
                        <div class="search-result-content">
                            <div class="search-result-title">No se encontraron resultados</div>
                            <div class="search-result-meta">Intenta con otros términos</div>
                        </div>
                    </div>
                `;
                searchResults.style.display = 'block';
            }
        }, 300);

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            searchTerm = query;
            debouncedSearch(query);

            if (query.length >= 2) {
                filterServices();
            } else if (query.length === 0) {
                searchTerm = '';
                filterServices();
            }
        });

        function selectSearchResult(serviceId, title) {
            searchInput.value = title;
            searchResults.style.display = 'none';

            // Scroll to the specific service card
            const serviceCard = document.querySelector(`[data-service-id="${serviceId}"]`);
            if (serviceCard) {
                serviceCard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                serviceCard.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    serviceCard.style.transform = '';
                }, 500);
            }
        }

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        function scrollToServices() {
            document.getElementById('servicios').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function filterByCategory(category) {
            currentFilter = category;

            // Update category cards
            document.querySelectorAll('.category-card').forEach(card => {
                if (category === 'all') {
                    card.classList.remove('active');
                } else if (card.dataset.category === category) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            });

            // Update filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            if (category === 'all') {
                document.querySelector('.filter-btn').classList.add('active');
            } else {
                const targetBtn = Array.from(document.querySelectorAll('.filter-btn'))
                    .find(btn => btn.textContent.toLowerCase().includes(category.toLowerCase()));
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
                const serviceTitle = card.querySelector('.service-title').textContent.toLowerCase();
                const serviceDescription = card.querySelector('.service-description').textContent.toLowerCase();
                const serviceBadge = card.querySelector('.service-badge').textContent.toLowerCase();

                let matchesFilter = currentFilter === 'all' || cardCategory === currentFilter;
                let matchesSearch = !searchTerm ||
                    serviceTitle.includes(searchTerm) ||
                    serviceDescription.includes(searchTerm) ||
                    serviceBadge.includes(searchTerm) ||
                    cardCategory.includes(searchTerm);

                if (matchesFilter && matchesSearch) {
                    card.classList.remove('hidden');
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }

            // Animate visible cards
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

            // Reset all UI elements
            document.querySelectorAll('.category-card').forEach(card => {
                card.classList.remove('active');
            });

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector('.filter-btn').classList.add('active');

            filterServices();
        }

        function toggleCart() {
            // Placeholder for cart functionality
            alert(`🛒 Tienes ${cartItems.length} servicios en tu carrito`);
        }

        // Initialize on page load
        window.addEventListener('load', () => {
            // Staggered animation for service cards
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (document.getElementById('modalOverlay').classList.contains('active')) {
                    closeModal();
                } else {
                    searchResults.style.display = 'none';
                    searchInput.blur();
                }
            }

            if (e.key === '/' && !searchInput.contains(document.activeElement)) {
                e.preventDefault();
                searchInput.focus();
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
