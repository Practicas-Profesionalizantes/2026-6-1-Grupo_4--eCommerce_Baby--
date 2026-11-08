<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condiciones de privacidad | Kiddy Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --fondo: #fff8f0;
            --tarjeta: #ffffff;
            --borde: #f0dfc8;
            --titulo: #4c3300;
            --texto: #6b5236;
            --suave: #9a8266;
            --insignia: #f2a6a6;
        }
        html.dark {
            --fondo: #12100e;
            --tarjeta: #1c1917;
            --borde: #3a322b;
            --titulo: #faf0e6;
            --texto: #d9c9b6;
            --suave: #a89680;
            --insignia: #e6a1a1;
        }
        html[data-tema="predeterminado"] {
            --fondo: #ead9c1;
            --tarjeta: #f8eee0;
            --borde: #d2b793;
            --titulo: #2b1a0b;
            --texto: #3d2814;
            --suave: #6b4f36;
            --insignia: #d2a574;
        }
        html[data-tema="predeterminado"] .fondo-estrellas::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'%3E%3Cpath d='M32 22l2.5 7.5L42 32l-7.5 2.5L32 42l-2.5-7.5L22 32l7.5-2.5z' fill='%23c9ab83'/%3E%3C/svg%3E");
            opacity: 0.35;
        }
        html[data-tema="predeterminado"] .nube { color: #d9b98f !important; opacity: 0.5 !important; }
        body { background-color: var(--fondo); color: var(--texto); }
        .fondo-estrellas { position: relative; overflow: hidden; }
        .fondo-estrellas::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'%3E%3Cpath d='M32 22l2.5 7.5L42 32l-7.5 2.5L32 42l-2.5-7.5L22 32l7.5-2.5z' fill='%23ecd9c0'/%3E%3C/svg%3E");
            opacity: 0.9;
            pointer-events: none;
        }
        html.dark .fondo-estrellas::before { opacity: 0.08; }
        .nube { position: absolute; pointer-events: none; }
        html.dark .nube { opacity: 0.12; }
        .tarjeta {
            position: relative;
            background-color: var(--tarjeta);
            border: 1px solid var(--borde);
            box-shadow: 0 16px 40px rgba(76, 51, 0, 0.10);
        }
        .txt-titulo { color: var(--titulo); }
        .txt-suave { color: var(--suave); }
        .insignia {
            background-color: var(--insignia);
            color: #4c3300;
            width: 30px;
            height: 30px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .separador { border-top: 1px solid var(--borde); }
        .boton-principal {
            background-color: #4c3300;
            color: #faf0e6;
            transition: opacity 0.2s, transform 0.2s;
        }
        .boton-principal:hover { opacity: 0.9; }
        .boton-principal:active { transform: scale(0.96); }
        html.dark .boton-principal { background-color: #e6a1a1; color: #12100e; }
        .boton-borde {
            border: 1px solid var(--titulo);
            color: var(--titulo);
            transition: background-color 0.2s, color 0.2s;
        }
        .boton-borde:hover { background-color: var(--titulo); color: var(--fondo); }
    </style>
</head>
<body class="min-h-screen">

@include('header-footer')

<main class="fondo-estrellas">

    <svg class="nube" style="top: 40px; left: -70px; width: 300px; color: #f4cfc4; opacity: 0.55;" viewBox="0 0 200 120" aria-hidden="true">
        <g fill="currentColor">
            <circle cx="60" cy="70" r="40"/>
            <circle cx="105" cy="50" r="45"/>
            <circle cx="150" cy="72" r="36"/>
            <rect x="40" y="70" width="130" height="38" rx="19"/>
        </g>
    </svg>

    <svg class="nube" style="bottom: 30px; right: -90px; width: 360px; color: #ecd9b8; opacity: 0.6;" viewBox="0 0 200 120" aria-hidden="true">
        <g fill="currentColor">
            <circle cx="60" cy="70" r="40"/>
            <circle cx="105" cy="50" r="45"/>
            <circle cx="150" cy="72" r="36"/>
            <rect x="40" y="70" width="130" height="38" rx="19"/>
        </g>
    </svg>

    <div class="relative max-w-3xl mx-auto px-5 sm:px-8 py-12 sm:py-20">
        <article class="tarjeta rounded-3xl overflow-hidden">
            <div class="h-2" style="background-color: var(--insignia);"></div>

            <div class="px-6 sm:px-12 py-10 sm:py-14">
                <header>
                    <h1 class="font-serif text-3xl sm:text-4xl tracking-tight txt-titulo">Condiciones de privacidad</h1>
                    <p class="mt-3 text-[11px] font-medium tracking-widest uppercase txt-suave">Última actualización: 29 de octubre de 2026</p>
                    <p class="mt-6 text-[15px] leading-relaxed">En Kiddy Shop nos importa tu privacidad. Acá te contamos, de forma simple, qué datos usamos y para qué.</p>
                </header>

                <div class="separador mt-8 pt-8 space-y-8">

                    <section class="flex gap-4">
                        <span class="insignia">1</span>
                        <div>
                            <h2 class="font-serif text-lg txt-titulo">Qué datos guardamos</h2>
                            <p class="mt-2 text-[15px] leading-relaxed">Tu nombre y apellido, tu mail, tu teléfono, tu dirección de entrega, los pedidos que hacés y los mensajes que nos mandás desde Soporte.</p>
                        </div>
                    </section>

                    <section class="flex gap-4">
                        <span class="insignia">2</span>
                        <div>
                            <h2 class="font-serif text-lg txt-titulo">Para qué los usamos</h2>
                            <p class="mt-2 text-[15px] leading-relaxed">Para procesar y entregar tus pedidos, emitir tus facturas, responder tus consultas y avisarte sobre lo que pasa con tus compras.</p>
                        </div>
                    </section>

                    <section class="flex gap-4">
                        <span class="insignia">3</span>
                        <div>
                            <h2 class="font-serif text-lg txt-titulo">Con quién los compartimos</h2>
                            <p class="mt-2 text-[15px] leading-relaxed">No vendemos ni cedemos tus datos a terceros. Solo los compartimos con quienes participan de tu compra, como los servicios de pago y de envío, y únicamente lo necesario para completarla.</p>
                        </div>
                    </section>

                    <section class="flex gap-4">
                        <span class="insignia">4</span>
                        <div>
                            <h2 class="font-serif text-lg txt-titulo">Cuánto tiempo los guardamos</h2>
                            <p class="mt-2 text-[15px] leading-relaxed">Mientras tu cuenta esté activa o mientras tengamos que conservarlos por obligaciones como la facturación.</p>
                        </div>
                    </section>

                    <section class="flex gap-4">
                        <span class="insignia">5</span>
                        <div>
                            <h2 class="font-serif text-lg txt-titulo">Tus derechos</h2>
                            <p class="mt-2 text-[15px] leading-relaxed">Podés pedirnos ver, corregir o eliminar tus datos escribiéndonos desde la sección <a href="{{ route('cuenta.soporte') }}" class="underline underline-offset-4 txt-titulo hover:opacity-80">Soporte</a> de tu cuenta. Te vamos a responder lo antes posible.</p>
                        </div>
                    </section>

                    <section class="flex gap-4">
                        <span class="insignia">6</span>
                        <div>
                            <h2 class="font-serif text-lg txt-titulo">Cambios en estas condiciones</h2>
                            <p class="mt-2 text-[15px] leading-relaxed">Si algún día las modificamos, vamos a actualizar la fecha que figura arriba.</p>
                        </div>
                    </section>

                </div>

                <div class="separador mt-10 pt-8 flex flex-wrap gap-3">
                    <a href="/" class="boton-principal inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Volver al inicio</a>
                    <a href="/catalogo" class="boton-borde inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Ver catálogo</a>
                </div>
            </div>
        </article>
    </div>
</main>

</body>
</html>