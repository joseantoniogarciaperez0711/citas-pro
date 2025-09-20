<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-10px) scale(1.02);
            }
        }

        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.2), 0 15px 30px rgba(0, 0, 0, 0.1);
            }

            50% {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.3), 0 15px 30px rgba(0, 0, 0, 0.15);
            }
        }

        .login-card {
            animation: slideIn 0.8s ease-out, float 6s ease-in-out infinite 0.8s, glow 4s ease-in-out infinite;
            max-width: 800px;
            height: 450px;
        }

        .input-focus {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }

        .btn-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }

        .btn-hover:active {
            transform: translateY(0px);
        }

        .logo-section {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-right: 1px solid rgba(148, 163, 184, 0.2);
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 95vw;
                height: auto;
                margin: 1rem;
            }

            .logo-section {
                border-right: none;
                border-bottom: 1px solid rgba(148, 163, 184, 0.2);
                min-height: 200px;
                order: 1;
            }

            .form-section {
                order: 2;
            }

            body {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                margin: 0.5rem;
                border-radius: 1rem;
            }
        }
    </style>

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


</head>

<body
    class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 flex items-center justify-center p-4 overflow-hidden">
    <!-- Elementos de fondo animados -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div
            class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000">
        </div>
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-cyan-500/10 rounded-full blur-2xl animate-pulse delay-500">
        </div>
    </div>



    <!-- Login Card Compacto -->
    <div
        class="login-card bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl flex w-full overflow-hidden border border-white/20">

        <!-- Logo Section - Izquierda (Ocupa todo el lado) -->
        <div class="logo-section flex-1 flex items-center justify-center relative overflow-hidden">
            <!-- Logo que ocupa TODO el espacio del lado izquierdo -->
            <div class="w-full h-full flex items-center justify-center p-4">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo {{ $businessName }}"
                        class="w-full h-full object-contain" />
                @else
                    {{-- Fallback: imagen por defecto o placeholder --}}
                    <div class="w-full h-full flex items-center justify-center">
                        <div
                            class="w-48 h-48 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl">
                            <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Efectos decorativos sutiles -->
            <div
                class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-100/30 to-transparent rounded-full blur-xl">
            </div>
            <div
                class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-purple-100/30 to-transparent rounded-full blur-lg">
            </div>
        </div>

        <!-- Form Section - Derecha -->
        <div class="form-section flex-1 p-8 flex flex-col justify-center">
            <div class="w-full max-w-xs mx-auto">
                <!-- Nombre del negocio -->
                <div class="mb-6">


                    <h2
                        class="text-lg font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">
                        {{ $businessName }}
                    </h2>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">
                        Bienvenido
                    </h1>
                    <p class="text-gray-600 text-sm">
                        Ingresa tu número de teléfono
                    </p>
                </div>

                <!-- Formulario -->
                <form class="space-y-4" onsubmit="handleSubmit(event)">
                    <!-- Campo de teléfono -->
                    <div class="space-y-2">
                        <label for="phone" class="block text-xs font-semibold text-gray-700">
                            Número de Teléfono
                        </label>
                        <div class="relative">
                            <input type="tel" id="phone" name="phone" placeholder="000 000 0000"
                                class="input-focus w-full px-3 py-2.5 border-2 border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none text-sm font-medium"
                                inputmode="numeric" autocomplete="tel" maxlength="12"
                                pattern="[0-9]{3}\s[0-9]{3}\s[0-9]{4}" required>


                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <button type="submit"
                        class="btn-hover w-full py-2.5 px-4 text-white font-semibold text-sm rounded-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 active:scale-95">
                        Continuar
                    </button>
                </form>


            </div>
        </div>

    </div>



    <!-- Modal Alta Cliente -->
    <div id="registerModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-sm rounded-xl p-6 shadow-2xl">
            <h3 class="text-lg font-bold mb-3">Regístrate</h3>
            <p class="text-sm text-gray-600 mb-4">Completa tus datos para continuar.</p>

            <form id="registerForm" class="space-y-3">
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre"
                        class="w-full border-2 rounded-lg px-3 py-2 focus:border-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Teléfono</label>
                    <input type="tel" name="telefono" id="regTelefono"
                        class="w-full border-2 rounded-lg px-3 py-2 focus:border-blue-500 outline-none" required>
                    <p class="text-xs text-gray-500 mt-1">10 dígitos</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Correo (opcional)</label>
                    <input type="email" name="correo"
                        class="w-full border-2 rounded-lg px-3 py-2 focus:border-blue-500 outline-none">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="hideRegisterModal()"
                        class="px-4 py-2 rounded-lg border">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold">Crear
                        cuenta</button>
                </div>
            </form>
        </div>
    </div>





    <!-- Loader -->
    <div id="appLoader" class="app-loader">
        <div class="app-spinner"></div>
    </div>

    <style>
        /* Loader fullscreen */
        .app-loader {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .35);
            z-index: 60;
            backdrop-filter: blur(2px);
        }

        .app-spinner {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 4px solid #e5e7eb;
            border-top-color: #3b82f6;
            animation: spin 0.9s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }
    </style>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        (async () => {
            if (!window.Swal) {
                await new Promise((res, rej) => {
                    const s = document.createElement('script');
                    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                    s.onload = res;
                    s.onerror = rej;
                    document.head.appendChild(s);
                });
            }

            const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const token = @json($token);

            function showLoader() {
                getLoader().style.display = 'flex';
            }

            function hideLoader() {
                getLoader().style.display = 'none';
            }

            function getLoader() {
                let el = document.getElementById('appLoader');
                if (!el) {
                    el = document.createElement('div');
                    el.id = 'appLoader';
                    el.className = 'app-loader';
                    el.style.cssText =
                        'position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.35);z-index:60;backdrop-filter:blur(2px)';
                    el.innerHTML =
                        '<div class="app-spinner" style="width:56px;height:56px;border-radius:50%;border:4px solid #e5e7eb;border-top-color:#3b82f6;animation:spin .9s linear infinite"></div>';
                    const key = document.createElement('style');
                    key.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
                    document.head.appendChild(key);
                    document.body.appendChild(el);
                }
                return el;
            }

            function setLoading(btn, on, text = 'Continuar') {
                if (!btn) return;
                btn.disabled = on;
                btn.innerHTML = on ?
                    `<span class="inline-flex items-center gap-2">
           <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
             <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
             <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
           </svg> Procesando...
         </span>` :
                    text;
            }

            // Formato input teléfono
            const phoneInput = document.getElementById('phone');

            function formatMX(d) {
                const c = (d || '').replace(/\D/g, '').slice(0, 10);
                if (c.length <= 3) return c;
                if (c.length <= 6) return `${c.slice(0,3)} ${c.slice(3)}`;
                return `${c.slice(0,3)} ${c.slice(3,6)} ${c.slice(6)}`;
            }
            if (phoneInput) phoneInput.addEventListener('input', e => {
                e.target.value = formatMX(e.target.value);
            });

            // Modal
            function showRegisterModal(phoneDigits) {
                const m = document.getElementById('registerModal');
                document.getElementById('regTelefono').value = phoneDigits;
                m.classList.remove('hidden');
                m.classList.add('flex');
                setTimeout(() => m.querySelector('input[name="nombre"]').focus(), 40);
            }

            function hideRegisterModal() {
                const m = document.getElementById('registerModal');
                m.classList.add('hidden');
                m.classList.remove('flex');
            }
            (function bindClose() {
                const m = document.getElementById('registerModal');
                if (!m) return;
                m.addEventListener('click', e => {
                    if (e.target.id === 'registerModal') hideRegisterModal();
                });
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') hideRegisterModal();
                });
            })();

            // Login: verifica si existe el cliente
            window.handleSubmit = async function(event) {
                event.preventDefault();
                const btn = event.submitter;
                const digits = (phoneInput?.value || '').replace(/\D/g, '');
                if (digits.length !== 10) {
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Número inválido',
                        text: 'Ingresa un número de 10 dígitos.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                try {
                    showLoader();
                    setLoading(btn, true);
                    const resp = await fetch(@json(route('public.clients.check')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            token,
                            phone: digits
                        })
                    });
                    const data = await resp.json();
                    hideLoader();
                    setLoading(btn, false);

                    if (!resp.ok) throw new Error(data?.message || 'Error al verificar el teléfono.');

                    if (data.exists) {
                        await Swal.fire({
                            icon: 'success',
                            title: `¡Hola ${data.cliente.nombre}!`,
                            text: 'Accediendo…',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        // 👇 Redirección inmediata a la tienda (2 tokens)
                        window.location.href = data.redirect_url;
                        return;
                    }

                    const {
                        isConfirmed
                    } = await Swal.fire({
                        icon: 'info',
                        title: '¿Eres nuevo?',
                        html: `<p class="text-sm text-gray-600">Tu número <b>${formatMX(digits)}</b> no está registrado con este negocio.</p>
               <p class="text-sm text-gray-600 mt-2">¿Deseas crear tu cuenta ahora?</p>`,
                        showCancelButton: true,
                        confirmButtonText: 'Sí, registrarme',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280'
                    });
                    if (isConfirmed) showRegisterModal(digits);

                } catch (e) {
                    hideLoader();
                    setLoading(btn, false);
                    console.error(e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Ups…',
                        text: e.message || 'Ocurrió un error inesperado.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            };

            // Registro: crea cliente y redirige de inmediato
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const btn = e.submitter;
                    const form = e.currentTarget;

                    const payload = {
                        token: form.token.value,
                        nombre: (form.nombre.value || '').trim(),
                        telefono: (form.telefono.value || '').replace(/\D/g, '').slice(0, 10),
                        correo: (form.correo.value || '').trim() || null,
                    };

                    if (!payload.nombre || payload.telefono.length !== 10) {
                        await Swal.fire({
                            icon: 'warning',
                            title: 'Campos incompletos',
                            text: 'Verifica nombre y teléfono (10 dígitos).',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    try {
                        showLoader();
                        setLoading(btn, true, 'Crear cuenta');

                        const resp = await fetch(@json(route('public.clients.register')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF
                            },
                            body: JSON.stringify(payload)
                        });
                        const data = await resp.json();
                        hideLoader();
                        setLoading(btn, false, 'Crear cuenta');

                        if (!resp.ok || !data.ok) throw new Error(data?.message ||
                            'No se pudo registrar.');

                        hideRegisterModal();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        Toast.fire({
                            icon: 'success',
                            title: `¡Cuenta creada! Bienvenido, ${data.cliente.nombre}`
                        });

                        // 👇 Redirección inmediata a la tienda con 2 tokens
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 700);

                    } catch (e) {
                        hideLoader();
                        setLoading(btn, false, 'Crear cuenta');
                        console.error(e);
                        Swal.fire({
                            icon: 'error',
                            title: 'No se pudo registrar',
                            text: e.message || 'Intenta nuevamente.',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                });
            }
        })();
    </script>



</body>

</html>
