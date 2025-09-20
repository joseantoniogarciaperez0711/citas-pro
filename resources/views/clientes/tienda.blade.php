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

    // Mapea iconos opcionales por nombre de categoría
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
                'features' => [],
            ];
        })
        ->values();

    // Logo / nombre
    $file =
        $usuario->business_logo_filename ??
        ($usuario->business_logo_path ? basename($usuario->business_logo_path) : null);
    $logoUrl = $file ? asset('storage/business-logos/' . $file) : null;
    $businessName = $usuario->business_name ?? 'Negocio';
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Tienda</title>

    {{-- Favicon si hay logo --}}
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
            --shadow-md: 0 4px 12px rgba(0, 0, 0, .08);
        }

        * {
            box-sizing: border-box
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--white) 50%, var(--gray-50) 100%);
            color: var(--gray-700)
        }

        /* Slider */
        .slider-section {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto
        }

        .section-title {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #1a1a1a
        }

        .slider-container {
            position: relative;
            overflow: hidden
        }

        .slider-track {
            display: flex;
            transition: transform .5s ease
        }

        .slide {
            min-width: 100%;
            position: relative
        }

        .slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .25)
        }

        .slide-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 1rem;
            border-radius: 1rem
        }

        .slide-info {
            color: #fff;
            backdrop-filter: blur(6px);
            background: rgba(0, 0, 0, .5);
            padding: .8rem 1rem;
            border-radius: .8rem;
            text-align: center;
            max-width: 90%
        }

        .slide-info h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: .3rem
        }

        .slide-info p {
            font-size: .85rem;
            margin-bottom: .8rem;
            color: #e5e5e5
        }

        .service-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .8rem
        }

        .service-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #007dd1
        }

        .detail-btn {
            background: #007dd1;
            color: #fff;
            border: none;
            padding: .5rem 1rem;
            font-size: .85rem;
            border-radius: 999px;
            cursor: pointer
        }

        .detail-btn:hover {
            background: #005fa3
        }

        .slider-dots {
            text-align: center;
            margin-top: 1rem
        }

        .slider-dots button {
            background: #ccc;
            border: none;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer
        }

        .slider-dots button.active {
            background: #0066ff;
            transform: scale(1.2)
        }

        @media (min-width:1024px) {
            .slide img {
                height: 550px
            }

            .slide-overlay {
                justify-content: flex-start;
                align-items: center;
                padding: 3rem;
                background: linear-gradient(to right, rgba(0, 0, 0, .6), rgba(0, 0, 0, 0))
            }

            .slide-info {
                max-width: 45%;
                text-align: left;
                padding: 2rem
            }

            .slide-info h3 {
                font-size: 2rem
            }

            .slide-info p {
                font-size: 1rem
            }

            .service-footer {
                justify-content: flex-start
            }

            .detail-btn {
                font-size: 1rem;
                padding: .8rem 1.4rem
            }
        }

        /* Categorías y servicios (resumen) */
        .categories-section {
            padding: 4rem 2rem;
            background: linear-gradient(180deg, #fff 0%, var(--gray-50) 100%)
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto
        }

        .category-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--gray-200);
            cursor: pointer
        }

        .category-card:hover {
            border-color: var(--primary-blue)
        }

        .category-icon {
            font-size: 3rem;
            margin-bottom: 1rem
        }

        .category-title {
            font-size: 1.2rem;
            font-weight: 700
        }

        .category-description {
            color: #6b7280;
            font-size: .95rem;
            margin: .5rem 0
        }

        .category-count {
            display: inline-block;
            background: #F5F5F7;
            color: #515154;
            padding: .25rem .8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: .85rem
        }

        .services-section {
            padding: 1.5rem 1rem;
            max-width: 1200px;
            margin: 0 auto
        }

        .services-title {
            text-align: center;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            justify-content: center;
            margin-bottom: 1.5rem
        }

        .filter-btn {
            background: #f4f4f4;
            border: none;
            padding: .5rem 1rem;
            border-radius: 999px;
            font-size: .85rem;
            cursor: pointer
        }

        .filter-btn.active {
            background: #0066ff;
            color: #fff;
            font-weight: 600
        }

        .clear-filters {
            font-size: .85rem;
            color: #0066ff;
            cursor: pointer;
            align-self: center
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem
        }

        @media(min-width:600px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(min-width:900px) {
            .services-grid {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        .service-card {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08)
        }

        .service-image {
            width: 100%;
            height: 180px;
            object-fit: cover
        }

        .service-content {
            padding: 1rem
        }

        .service-badge {
            display: inline-block;
            font-size: .75rem;
            padding: .3rem .7rem;
            border-radius: 999px;
            background: #eef3ff;
            color: #0066ff;
            margin-bottom: .5rem;
            font-weight: 600
        }

        .service-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: .4rem
        }

        .service-description {
            font-size: .9rem;
            color: #555;
            margin-bottom: 1rem;
            line-height: 1.4
        }

        .service-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0066ff
        }

        .detail-btn {
            background: #0066ff;
            color: #fff;
            border: none;
            padding: .5rem 1rem;
            border-radius: 999px;
            font-size: .85rem;
            cursor: pointer
        }

        .no-results {
            text-align: center;
            padding: 2rem 1rem;
            color: #555
        }

        .no-results-icon {
            font-size: 2rem;
            margin-bottom: .5rem
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(8px);
            z-index: 1000
        }

        .modal-overlay.active {
            display: flex;
            align-items: flex-end;
            justify-content: center
        }

        @media(min-width:768px) {
            .modal-overlay.active {
                align-items: center
            }
        }

        .modal-container {
            background: #fff;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column
        }

        .modal-header {
            position: sticky;
            top: 0;
            background: #fff;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #E5E5E7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10
        }

        .modal-close {
            background: #F5F5F7;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer
        }

        .modal-content {
            overflow-y: auto;
            flex: 1
        }

        .modal-image {
            width: 100%;
            height: 250px;
            object-fit: cover
        }

        .modal-body {
            padding: 1.5rem
        }

        .modal-badge {
            background: #F0F8FF;
            color: #007AFF;
            padding: .4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1D1D1F;
            margin-bottom: .5rem
        }

        .modal-price {
            font-size: 2.2rem;
            font-weight: 700;
            color: #007AFF;
            margin-bottom: 1.5rem
        }

        .modal-description {
            color: #515154;
            line-height: 1.6;
            margin-bottom: 2rem
        }

        .feature-list {
            list-style: none;
            padding: 0
        }

        .feature-item {
            display: flex;
            gap: .8rem;
            padding: .8rem 0;
            border-bottom: 1px solid #F5F5F7;
            color: #515154
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 1.5rem;
            border-top: 1px solid #E5E5E7;
            display: flex;
            gap: 1rem
        }

        .add-to-cart-btn {
            flex: 1;
            background: #007AFF;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer
        }
    </style>
</head>

<body>


    @php
        // vienen del controlador: $bizToken, $clientToken, $clienteNombre, $logoUrl, $usuario

        $profileUrl =
            $clientToken && Route::has('cliente.perfil')
                ? route('cliente.perfil', ['bizToken' => $bizToken, 'clientToken' => $clientToken])
                : null;

        $logoutUrl =
            $clientToken && Route::has('cliente.logout')
                ? route('cliente.logout', ['bizToken' => $bizToken, 'clientToken' => $clientToken])
                : null;
    @endphp

    <x-client-nav :usuario="$usuario" :logo-url="$logoUrl" :links="[['href' => '#inicio', 'label' => 'Inicio'], ['href' => '#servicios', 'label' => 'Servicios']]" :cart-count="0" :cliente-nombre="$clienteNombre"
        :profile-url="$profileUrl" :logout-url="$logoutUrl" logout-method="post" />




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

    <section class="categories-section" id="inicio">
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
        <h2 class="services-title">Todos los Servicios</h2>
        <div class="filter-controls">
            <button class="filter-btn active" onclick="filterByCategory('all')">Todos</button>
            @foreach ($cats as $c)
                <button class="filter-btn"
                    onclick="filterByCategory('{{ $c['slug'] }}')">{{ $c['name'] }}</button>
            @endforeach
            <span class="clear-filters" onclick="clearFilters()">Limpiar filtros</span>
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

        <div class="no-results" id="noResults" style="display:none">
            <div class="no-results-icon">🔍</div>
            <h3>No se encontraron servicios</h3>
            <p>Intenta con otros términos de búsqueda o categorías</p>
        </div>
    </section>

    {{-- Modal Detalles --}}
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

                    <div class="quantity-control"
                        style="display:flex;gap:1rem;align-items:center;background:#F5F5F7;padding:.5rem 1rem;border-radius:12px;margin-bottom:2rem">
                        <button class="quantity-btn" onclick="updateQuantity(-1)">−</button>
                        <span class="quantity-value" id="quantityValue">1</span>
                        <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="add-to-cart-btn" id="addToCartBtn" onclick="addToCart()">
                    <span>🛒</span><span>Agregar al Carrito</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        // ====== Datos desde BD ======
        const services = @json($servicesJs);

        // ====== Slider ======
        (function() {
            const track = document.querySelector('.slider-track');
            if (!track) return;
            const slides = document.querySelectorAll('.slide');
            const dotsContainer = document.querySelector('.slider-dots');
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
                dots.forEach(d => d.classList.remove('active'));
                dots[index].classList.add('active');
            }
            let autoPlay = setInterval(() => showSlide(index + 1), 5000);

            let startX = 0,
                dragging = false;
            track.addEventListener('touchstart', e => {
                startX = e.touches[0].clientX;
                dragging = true;
                clearInterval(autoPlay);
            });
            track.addEventListener('touchmove', e => {
                if (!dragging) return;
                const diff = startX - e.touches[0].clientX;
                if (diff > 50) {
                    showSlide(index + 1);
                    dragging = false;
                } else if (diff < -50) {
                    showSlide(index - 1);
                    dragging = false;
                }
            });
            track.addEventListener('touchend', () => {
                dragging = false;
                autoPlay = setInterval(() => showSlide(index + 1), 5000);
            });
        })();

        // ====== Filtros / Búsqueda ======
        let currentFilter = 'all';
        let searchTerm = '';
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const mobileSearchInput = document.getElementById('mobileSearchInput');
        const mobileSearchResults = document.getElementById('mobileSearchResults');

        function debounce(fn, ms) {
            let t;
            return (...a) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...a), ms);
            };
        }

        const debouncedSearchMobile = debounce(function(query, resultsEl, inputEl) {
            if (!resultsEl) return;
            if (query.length < 2) {
                resultsEl.style.display = 'none';
                return;
            }
            const q = query.toLowerCase();
            const res = services.filter(s =>
                s.title.toLowerCase().includes(q) ||
                s.category.toLowerCase().includes(q) ||
                (s.description || '').toLowerCase().includes(q) ||
                (s.keywords || []).some(k => (k || '').toLowerCase().includes(q))
            );
            resultsEl.innerHTML = (res.length ? res.map(s => `
            <div class="search-result-item" style="padding:1rem 1.25rem;border-bottom:1px solid #F5F5F7;cursor:pointer"
                 onclick="selectSearchResult('${s.id}', '${s.title.replace(/'/g, '&#39;')}', '${inputEl.id}')">
                <div class="search-result-icon" style="width:40px;height:40px;border-radius:8px;background:#F0F8FF;display:flex;align-items:center;justify-content:center;margin-right:.75rem">🔹</div>
                <div class="search-result-content">
                    <div class="search-result-title" style="font-weight:600">${s.title}</div>
                    <div class="search-result-meta" style="font-size:.85rem;color:#6E6E73">${s.categoryRaw} • $${s.price.toFixed(2)}</div>
                </div>
            </div>`).join('') :
                `<div class="search-result-item" style="padding:1rem 1.25rem">
                <div class="search-result-icon" style="width:40px;height:40px;border-radius:8px;background:#F0F8FF;display:flex;align-items:center;justify-content:center;margin-right:.75rem">🔍</div>
                <div class="search-result-content">
                    <div class="search-result-title" style="font-weight:600">Sin resultados</div>
                    <div class="search-result-meta" style="font-size:.85rem;color:#6E6E73">Intenta con otros términos</div>
                </div>
            </div>`
            );
            resultsEl.style.display = 'block';
        }, 300);

        function handleSearchInput(inputEl, resultsEl) {
            inputEl.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                searchTerm = q;
                debouncedSearchMobile(q, resultsEl, inputEl);
                if (q.length >= 2) filterServices();
                else {
                    searchTerm = '';
                    filterServices();
                }
            });

            document.addEventListener('click', e => {
                if (!inputEl.contains(e.target) && !resultsEl.contains(e.target)) {
                    resultsEl.style.display = 'none';
                }
            });
        }

        if (searchInput) handleSearchInput(searchInput, searchResults);
        if (mobileSearchInput) handleSearchInput(mobileSearchInput, mobileSearchResults);

        function selectSearchResult(id, title, inputId = null) {
            const input = inputId ? document.getElementById(inputId) : null;
            if (input) input.value = title;
            if (searchResults) searchResults.style.display = 'none';
            if (mobileSearchResults) mobileSearchResults.style.display = 'none';

            const card = document.querySelector(`[data-service-id="${id}"]`);
            if (card) {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                card.style.transform = 'scale(1.02)';
                setTimeout(() => card.style.transform = '', 500);
            }
        }

        function filterByCategory(cat) {
            currentFilter = cat;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            if (cat === 'all') document.querySelector('.filter-btn').classList.add('active');
            else {
                const btn = Array.from(document.querySelectorAll('.filter-btn')).find(b => b.textContent.toLowerCase()
                    .includes(cat));
                if (btn) btn.classList.add('active');
            }
            filterServices();
        }

        function filterServices() {
            const cards = document.querySelectorAll('.service-card');
            const noRes = document.getElementById('noResults');
            let visible = 0;
            cards.forEach(card => {
                const cat = card.dataset.category;
                const text = (card.textContent || '').toLowerCase();
                const ok = (currentFilter === 'all' || cat === currentFilter) && (!searchTerm || text.includes(
                    searchTerm));
                card.style.display = ok ? 'block' : 'none';
                if (ok) visible++;
            });
            if (noRes) noRes.style.display = visible ? 'none' : 'block';
        }

        function clearFilters() {
            currentFilter = 'all';
            searchTerm = '';
            if (searchInput) {
                searchInput.value = '';
                if (searchResults) searchResults.style.display = 'none';
            }
            if (mobileSearchInput) {
                mobileSearchInput.value = '';
                if (mobileSearchResults) mobileSearchResults.style.display = 'none';
            }
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            document.querySelector('.filter-btn').classList.add('active');
            filterServices();
        }

        // ====== Modal & Carrito ======
        let selectedService = null,
            quantity = 1,
            cartItems = [];

        function openModal(id) {
            selectedService = services.find(s => s.id === String(id));
            if (!selectedService) return;
            quantity = 1;
            document.getElementById('modalImage').src = selectedService.image;
            document.getElementById('modalBadge').textContent = selectedService.categoryRaw || 'Servicio';
            document.getElementById('modalTitle').textContent = selectedService.title;
            document.getElementById('modalPrice').textContent = `$${selectedService.price.toFixed(2)}`;
            document.getElementById('modalDescription').textContent = selectedService.description || '';
            const feats = selectedService.features || [];
            document.getElementById('modalFeatures').innerHTML = feats.map(f =>
                `<li class="feature-item"><span>✅</span><span>${f}</span></li>`).join('');
            document.getElementById('quantityValue').textContent = '1';
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.getElementById('modalOverlay').addEventListener('click', e => {
            if (e.target.id === 'modalOverlay') closeModal();
        });

        function updateQuantity(delta) {
            quantity = Math.max(1, quantity + delta);
            document.getElementById('quantityValue').textContent = quantity;
            if (selectedService) {
                document.getElementById('modalPrice').textContent = `$${(selectedService.price * quantity).toFixed(2)}`;
            }
        }

        function addToCart() {
            if (!selectedService) return;
            cartItems.push({
                id: selectedService.id,
                qty: quantity,
                price: selectedService.price
            });
            const total = cartItems.reduce((s, i) => s + i.qty, 0);
            const badge = document.getElementById('cartCount');
            const mobileBadge = document.getElementById('mobileCartCount');
            if (badge) badge.textContent = total;
            if (mobileBadge) mobileBadge.textContent = total;
            const btn = document.getElementById('addToCartBtn');
            btn.textContent = '¡Agregado! ✓';
            setTimeout(() => {
                closeModal();
                btn.innerHTML = '<span>🛒</span><span>Agregar al Carrito</span>';
            }, 900);
        }

        function toggleCart() {
            alert(`🛒 Tienes ${cartItems.length} servicios en tu carrito`);
        }

        // Animaciones al cargar
        window.addEventListener('load', () => {
            document.querySelectorAll('.service-card').forEach((card, i) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, i * 120);
            });
        });

        // Accesibilidad básica
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && document.getElementById('modalOverlay').classList.contains('active'))
                closeModal();
        });
    </script>

    <div class="cart-modal-overlay" id="cartModal">
    <div class="cart-modal" id="cartModalContent">
        <!-- Indicador de arrastre para mejor UX -->
        <div class="drag-indicator"></div>
        
        <div class="cart-modal-header">
            <h3>🛒 Tu Carrito</h3>
            <button class="close-cart" onclick="closeCart()" aria-label="Cerrar carrito">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        
        <div class="cart-modal-body" id="cartItemsList">
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <p>Tu carrito está vacío</p>
                <small>Agrega productos para comenzar</small>
            </div>
        </div>
        
        <div class="cart-modal-footer">
            <div class="cart-summary">
                <div class="cart-total">
                    <span class="total-label">Total:</span>
                    <span class="total-amount" id="cartTotal">$0.00</span>
                </div>
                <button class="checkout-btn" id="checkoutBtn">
                    <span>Finalizar Compra</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14m-7-7l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Overlay */
    .cart-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0);
        z-index: 9999;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        /* Soporte para safe areas en iOS */
        padding-bottom: env(safe-area-inset-bottom);
    }

    .cart-modal-overlay.active {
        background: rgba(0, 0, 0, 0.5);
        opacity: 1;
        visibility: visible;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* Modal Principal */
    .cart-modal {
        background: #ffffff;
        width: 100%;
        max-width: 100vw;
        border-radius: 20px 20px 0 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 85vh;
        min-height: 300px;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
    }

    .cart-modal-overlay.active .cart-modal {
        transform: translateY(0);
    }

    /* Indicador de arrastre */
    .drag-indicator {
        width: 40px;
        height: 4px;
        background: #d1d1d6;
        border-radius: 2px;
        margin: 12px auto 8px;
        transition: background-color 0.2s ease;
    }

    .drag-indicator:hover {
        background: #a1a1aa;
    }

    /* Header */
    .cart-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 24px 16px;
        border-bottom: 1px solid #f1f1f1;
    }

    .cart-modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1d1d1f;
    }

    .close-cart {
        background: #f2f2f7;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #1d1d1f;
    }

    .close-cart:hover {
        background: #e5e5ea;
        transform: scale(1.05);
    }

    .close-cart:active {
        transform: scale(0.95);
    }

    /* Body */
    .cart-modal-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 24px;
        -webkit-overflow-scrolling: touch; /* Suaviza el scroll en iOS */
    }

    /* Estado vacío mejorado */
    .empty-cart {
        text-align: center;
        padding: 40px 20px;
        color: #8e8e93;
    }

    .empty-cart-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.6;
    }

    .empty-cart p {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 500;
        color: #1d1d1f;
    }

    .empty-cart small {
        font-size: 14px;
        color: #8e8e93;
    }

    /* Items del carrito mejorados */
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        background: #f1f3f4;
        border-color: #dee2e6;
    }

    .cart-item:last-child {
        margin-bottom: 0;
    }

    .cart-item-info {
        flex: 1;
        min-width: 0; /* Previene overflow */
    }

    .cart-item h4 {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 600;
        color: #1d1d1f;
        line-height: 1.3;
        /* Trunca texto largo */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .cart-item-details {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .cart-item small {
        color: #8e8e93;
        font-size: 14px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: #007AFF;
        color: white;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .qty-btn:hover {
        background: #0056b3;
        transform: scale(1.05);
    }

    .qty-btn:active {
        transform: scale(0.95);
    }

    .qty-btn:disabled {
        background: #d1d1d6;
        cursor: not-allowed;
        transform: none;
    }

    .qty-display {
        min-width: 32px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        color: #1d1d1f;
    }

    .cart-item-price {
        text-align: right;
        margin-left: 16px;
    }

    .cart-item .price {
        font-weight: 700;
        color: #007AFF;
        font-size: 16px;
        margin: 0;
    }

    .remove-item {
        background: #ff3b30;
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 14px;
        font-size: 12px;
        cursor: pointer;
        margin-top: 4px;
        transition: all 0.2s ease;
    }

    .remove-item:hover {
        background: #d70015;
        transform: scale(1.1);
    }

    /* Footer */
    .cart-modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #f1f1f1;
        background: #ffffff;
        /* Sticky footer con safe area */
        padding-bottom: calc(20px + env(safe-area-inset-bottom));
    }

    .cart-summary {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
    }

    .total-label {
        font-size: 16px;
        color: #8e8e93;
        font-weight: 500;
    }

    .total-amount {
        font-size: 20px;
        font-weight: 700;
        color: #1d1d1f;
    }

    .checkout-btn {
        background: linear-gradient(135deg, #007AFF 0%, #0056b3 100%);
        color: #ffffff;
        border: none;
        padding: 16px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 122, 255, 0.3);
        width: 100%;
        min-height: 52px;
    }

    .checkout-btn:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d7a 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 122, 255, 0.4);
    }

    .checkout-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 15px rgba(0, 122, 255, 0.3);
    }

    .checkout-btn:disabled {
        background: #d1d1d6;
        color: #8e8e93;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Botón para limpiar carrito */
    .clear-cart-btn {
        background: #ff3b30;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        margin-left: auto;
        margin-bottom: 16px;
    }

    .clear-cart-btn:hover {
        background: #d70015;
        transform: scale(1.02);
    }

    .clear-cart-btn:active {
        transform: scale(0.98);
    }

    /* Toast notifications */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 10000;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
        max-width: 300px;
        word-wrap: break-word;
        transform: translateX(100%);
    }

    .toast-success {
        background: #34c759;
        color: white;
    }

    .toast-warning {
        background: #ff9500;
        color: white;
    }

    .toast-info {
        background: #007AFF;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 375px) {
        .cart-modal-header,
        .cart-modal-body,
        .cart-modal-footer {
            padding-left: 16px;
            padding-right: 16px;
        }
        
        .cart-item h4 {
            max-width: 150px;
        }
        
        .cart-item {
            padding: 12px;
        }
        
        .toast {
            right: 10px;
            left: 10px;
            top: 10px;
            max-width: none;
        }
    }

    /* Mejoras para pantallas grandes en landscape */
    @media (min-width: 768px) {
        .cart-modal {
            max-width: 480px;
            border-radius: 20px;
            margin: 20px;
            max-height: calc(100vh - 40px);
        }
        
        .cart-modal-overlay {
            align-items: center;
            padding: 20px;
        }
        
        .cart-modal-overlay.active .cart-modal {
            transform: scale(1);
        }
    }

    /* Animaciones para feedback táctil */
    .cart-item:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }

    /* Scroll personalizado */
    .cart-modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .cart-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .cart-modal-body::-webkit-scrollbar-thumb {
        background: #d1d1d6;
        border-radius: 2px;
    }

    .cart-modal-body::-webkit-scrollbar-thumb:hover {
        background: #a1a1aa;
    }

    /* Estilos personalizados para SweetAlert móvil */
    .swal2-container {
        z-index: 10500 !important; /* Por encima del modal del carrito */
    }

    .swal-mobile-popup {
        border-radius: 16px !important;
        padding: 20px !important;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        z-index: 10500 !important;
    }

    .swal2-backdrop-show {
        background: rgba(0, 0, 0, 0.6) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
    }

    .swal-mobile-title {
        font-size: 18px !important;
        font-weight: 600 !important;
        color: #1d1d1f !important;
        margin-bottom: 12px !important;
    }

    .swal-mobile-content {
        font-size: 16px !important;
        color: #8e8e93 !important;
        line-height: 1.4 !important;
    }

    .swal-mobile-confirm {
        background: #ff3b30 !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 12px 24px !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        min-width: 120px !important;
    }

    .swal-mobile-confirm:hover {
        background: #d70015 !important;
    }

    .swal-mobile-cancel {
        background: #007AFF !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 12px 24px !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        min-width: 120px !important;
    }

    .swal-mobile-cancel:hover {
        background: #0056b3 !important;
    }

    /* Responsive para SweetAlert */
    @media (max-width: 480px) {
        .swal-mobile-popup {
            margin: 20px !important;
            max-width: calc(100vw - 40px) !important;
        }
        
        .swal-mobile-confirm,
        .swal-mobile-cancel {
            min-width: 100px !important;
            padding: 10px 16px !important;
            font-size: 14px !important;
        }
    }
</style>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ====================================
    // VARIABLES Y CONFIGURACIÓN
    // ====================================
    
    let touchStartY = 0;
    let touchCurrentY = 0;
    let isDragging = false;
    
    // Configuración de localStorage
    const CART_STORAGE_KEY = 'userCart';
    const CART_TIMESTAMP_KEY = 'cartTimestamp';
    
    // ====================================
    // FUNCIONES DE PERSISTENCIA
    // ====================================
    
    // Cargar carrito desde localStorage
    function loadCartFromStorage() {
        try {
            const savedCart = localStorage.getItem(CART_STORAGE_KEY);
            const timestamp = localStorage.getItem(CART_TIMESTAMP_KEY);
            
            if (savedCart) {
                const parsedCart = JSON.parse(savedCart);
                
                // Verificar si el carrito no es muy antiguo (7 días)
                const cartAge = Date.now() - parseInt(timestamp || '0');
                const maxAge = 7 * 24 * 60 * 60 * 1000; // 7 días
                
                if (cartAge < maxAge) {
                    cartItems = parsedCart;
                    console.log('Carrito cargado desde localStorage:', cartItems);
                    
                    // Solo mostrar notificación si hay productos en el carrito
                    if (cartItems.length > 0) {
                        showToast('Carrito restaurado', 'success');
                    }
                } else {
                    console.log('Carrito expirado, iniciando carrito vacío');
                    clearCartStorage();
                }
            }
        } catch (error) {
            console.error('Error cargando carrito desde localStorage:', error);
            clearCartStorage();
        }
    }
    
    // Guardar carrito en localStorage
    function saveCartToStorage() {
        try {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartItems));
            localStorage.setItem(CART_TIMESTAMP_KEY, Date.now().toString());
            console.log('Carrito guardado en localStorage');
        } catch (error) {
            console.error('Error guardando carrito en localStorage:', error);
            if (error.name === 'QuotaExceededError') {
                showToast('Almacenamiento lleno', 'warning');
            }
        }
    }
    
    // Limpiar carrito del almacenamiento
    function clearCartStorage() {
        try {
            localStorage.removeItem(CART_STORAGE_KEY);
            localStorage.removeItem(CART_TIMESTAMP_KEY);
            console.log('Carrito eliminado del localStorage');
        } catch (error) {
            console.error('Error limpiando carrito del localStorage:', error);
        }
    }
    
    // ====================================
    // FUNCIONES DEL CARRITO ACTUALIZADAS
    // ====================================
    
    function addToCart() {
        if (!selectedService) return;

        const existing = cartItems.find(i => i.id === selectedService.id);
        if (existing) {
            existing.qty += quantity;
        } else {
            cartItems.push({
                id: selectedService.id,
                title: selectedService.title,
                price: selectedService.price,
                qty: quantity,
                addedAt: Date.now()
            });
        }

        // Guardar en localStorage después de cada cambio
        saveCartToStorage();
        updateCartUI();
        updateCartBadges();

        const btn = document.getElementById('addToCartBtn');
        if (btn) {
            btn.textContent = '¡Agregado! ✓';
            btn.style.background = '#34c759';
            setTimeout(() => {
                closeModal();
                btn.innerHTML = '<span>🛒</span><span>Agregar al Carrito</span>';
                btn.style.background = '';
            }, 900);
        }
    }

    function toggleCart() {
        const modal = document.getElementById('cartModal');
        modal.classList.add('active');
        updateCartUI();
        document.body.style.overflow = 'hidden';
        
        // Añadir event listeners para gestos táctiles
        addTouchListeners();
    }

    function closeCart() {
        const modal = document.getElementById('cartModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Remover event listeners
        removeTouchListeners();
    }

    function updateCartBadges() {
        const badge = document.getElementById('cartCount');
        const mobileBadge = document.getElementById('mobileCartCount');
        const totalQty = cartItems.reduce((s, i) => s + i.qty, 0);
        if (badge) badge.textContent = totalQty;
        if (mobileBadge) mobileBadge.textContent = totalQty;
    }

    function updateCartUI() {
        const list = document.getElementById('cartItemsList');
        const totalEl = document.getElementById('cartTotal');
        const checkoutBtn = document.getElementById('checkoutBtn');

        if (!cartItems.length) {
            list.innerHTML = `
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <p>Tu carrito está vacío</p>
                    <small>Agrega productos para comenzar</small>
                </div>
            `;
            totalEl.textContent = '$0.00';
            if (checkoutBtn) checkoutBtn.disabled = true;
            return;
        }

        // Botón para limpiar carrito
        const clearButton = `
            <button class="clear-cart-btn" onclick="clearCart()">
                <span>🗑️</span>
                <span>Vaciar carrito</span>
            </button>
        `;

        const itemsHTML = cartItems.map(item => `
            <div class="cart-item">
                <div class="cart-item-info">
                    <h4>${item.title}</h4>
                    <div class="cart-item-details">
                        <small>$${item.price.toFixed(2)} c/u</small>
                    </div>
                    <div class="quantity-controls">
                        <button class="qty-btn" onclick="updateQuantity('${item.id}', -1)" ${item.qty <= 1 ? 'disabled' : ''}>−</button>
                        <span class="qty-display">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQuantity('${item.id}', 1)">+</button>
                    </div>
                </div>
                <div class="cart-item-price">
                    <div class="price">$${(item.qty * item.price).toFixed(2)}</div>
                    <button class="remove-item" onclick="removeFromCart('${item.id}')" title="Eliminar">×</button>
                </div>
            </div>
        `).join('');

        list.innerHTML = clearButton + itemsHTML;

        const total = cartItems.reduce((s, i) => s + i.qty * i.price, 0);
        totalEl.textContent = `$${total.toFixed(2)}`;
        if (checkoutBtn) checkoutBtn.disabled = false;
    }

    function updateQuantity(itemId, change) {
        const item = cartItems.find(i => i.id === itemId);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) {
                removeFromCart(itemId);
            } else {
                // Guardar cambios en localStorage
                saveCartToStorage();
                updateCartUI();
                updateCartBadges();
            }
        }
    }

    function removeFromCart(itemId) {
        const index = cartItems.findIndex(i => i.id === itemId);
        if (index > -1) {
            cartItems.splice(index, 1);
            // Guardar cambios en localStorage
            saveCartToStorage();
            updateCartUI();
            updateCartBadges();
            showToast('Producto eliminado', 'info');
        }
    }
    
    // Función para limpiar carrito completamente
    function clearCart() {
        Swal.fire({
            title: '¿Vaciar carrito?',
            text: '¿Estás seguro de que quieres eliminar todos los productos?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3b30',
            cancelButtonColor: '#007AFF',
            confirmButtonText: 'Sí, vaciar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'swal-mobile-popup',
                title: 'swal-mobile-title',
                content: 'swal-mobile-content',
                confirmButton: 'swal-mobile-confirm',
                cancelButton: 'swal-mobile-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                cartItems = [];
                clearCartStorage();
                updateCartUI();
                updateCartBadges();
                
                // SweetAlert de confirmación
                Swal.fire({
                    title: '¡Carrito vaciado!',
                    text: 'Todos los productos han sido eliminados',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'swal-mobile-popup',
                        title: 'swal-mobile-title'
                    }
                });
            }
        });
    }
    
    // ====================================
    // FUNCIONES DE UTILIDAD
    // ====================================
    
    // Mostrar notificaciones toast
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // ====================================
    // FUNCIONES DE GESTOS TÁCTILES
    // ====================================

    function addTouchListeners() {
        const modal = document.getElementById('cartModalContent');
        modal.addEventListener('touchstart', handleTouchStart, { passive: true });
        modal.addEventListener('touchmove', handleTouchMove, { passive: false });
        modal.addEventListener('touchend', handleTouchEnd, { passive: true });
    }

    function removeTouchListeners() {
        const modal = document.getElementById('cartModalContent');
        modal.removeEventListener('touchstart', handleTouchStart);
        modal.removeEventListener('touchmove', handleTouchMove);
        modal.removeEventListener('touchend', handleTouchEnd);
    }

    function handleTouchStart(e) {
        touchStartY = e.touches[0].clientY;
        isDragging = false;
    }

    function handleTouchMove(e) {
        if (!touchStartY) return;
        
        touchCurrentY = e.touches[0].clientY;
        const diffY = touchCurrentY - touchStartY;
        
        // Solo permitir cerrar arrastrando hacia abajo
        if (diffY > 0) {
            isDragging = true;
            const modal = document.getElementById('cartModalContent');
            const progress = Math.min(diffY / 200, 1);
            modal.style.transform = `translateY(${diffY * 0.5}px)`;
            modal.style.opacity = 1 - progress * 0.3;
            
            // Prevenir scroll cuando se está arrastrando
            if (diffY > 10) {
                e.preventDefault();
            }
        }
    }

    function handleTouchEnd(e) {
        if (!isDragging) return;
        
        const diffY = touchCurrentY - touchStartY;
        const modal = document.getElementById('cartModalContent');
        
        if (diffY > 100) {
            // Cerrar modal si se arrastró lo suficiente
            closeCart();
        } else {
            // Volver a la posición original
            modal.style.transform = '';
            modal.style.opacity = '';
        }
        
        touchStartY = 0;
        touchCurrentY = 0;
        isDragging = false;
    }

    // ====================================
    // EVENT LISTENERS E INICIALIZACIÓN
    // ====================================

    // Cerrar modal al hacer clic fuera
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('cartModal');
        if (e.target === modal) {
            closeCart();
        }
    });

    // Prevenir cierre al hacer clic dentro del modal
    document.addEventListener('click', function(e) {
        const modalContent = document.getElementById('cartModalContent');
        if (modalContent && modalContent.contains(e.target)) {
            e.stopPropagation();
        }
    });
    
    // Inicializar carrito al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar carrito guardado
        loadCartFromStorage();
        
        // Actualizar UI inicial
        updateCartUI();
        updateCartBadges();
        
        console.log('Carrito inicializado con', cartItems.length, 'productos');
    });
    
    // Guardar carrito antes de cerrar la página
    window.addEventListener('beforeunload', function() {
        saveCartToStorage();
    });
    
    // Manejar cambios de visibilidad de la página (para móviles)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            saveCartToStorage();
        }
    });
</script>.

</body>

</html>
