<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('titulo'); ?> | Kiddy Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo $__env->make('tema', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('ui-extras', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .fondo-estrellas { position: relative; overflow: hidden; flex: 1; display: flex; align-items: center; justify-content: center; }
        .fondo-estrellas::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'%3E%3Cpath d='M32 22l2.5 7.5L42 32l-7.5 2.5L32 42l-2.5-7.5L22 32l7.5-2.5z' fill='%23ecd9c0'/%3E%3C/svg%3E");
            opacity: 0.9;
            pointer-events: none;
        }
        html[data-tema="predeterminado"] .fondo-estrellas::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'%3E%3Cpath d='M32 22l2.5 7.5L42 32l-7.5 2.5L32 42l-2.5-7.5L22 32l7.5-2.5z' fill='%23c9ab83'/%3E%3C/svg%3E");
            opacity: 0.35;
        }
        html.dark .fondo-estrellas::before { opacity: 0.08; }
        .nube { position: absolute; pointer-events: none; }
        html[data-tema="predeterminado"] .nube { color: #d9b98f !important; opacity: 0.5 !important; }
        html.dark .nube { opacity: 0.12 !important; }
        .barra {
            position: relative;
            z-index: 10;
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
        }
        .tarjeta {
            position: relative;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 16px 40px rgba(76, 51, 0, 0.10);
        }
        .numero { color: var(--accent-color); }
        .titulo { color: var(--text-main); }
        .texto { color: var(--text-muted); }
        .boton-principal {
            background-color: #4c3300;
            color: #faf0e6;
            transition: opacity 0.2s, transform 0.2s;
            cursor: pointer;
        }
        .boton-principal:hover { opacity: 0.9; }
        .boton-principal:active { transform: scale(0.96); }
        html.dark .boton-principal { background-color: #e6a1a1; color: #12100e; }
        .boton-borde {
            border: 1px solid var(--text-main);
            color: var(--text-main);
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }
        .boton-borde:hover { background-color: var(--text-main); color: var(--bg-color); }
        .enlace-menu { color: var(--text-muted); transition: color 0.2s; }
        .enlace-menu:hover { color: var(--text-main); }
    </style>
</head>
<body>

    <header class="barra">
        <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-[8%] h-20 flex items-center justify-between">
            <a href="/" class="font-serif text-xl tracking-wider titulo hover:opacity-80 transition-opacity">Kiddy Shop</a>
            <nav class="flex items-center gap-5 sm:gap-8 text-[10px] sm:text-xs font-medium tracking-widest uppercase">
                <a href="/" class="enlace-menu">Inicio</a>
                <a href="/catalogo" class="enlace-menu">Ver Catálogo</a>
            </nav>
        </div>
    </header>

    <main class="fondo-estrellas px-5 sm:px-8 py-14">

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

        <section class="tarjeta relative w-full max-w-xl rounded-3xl overflow-hidden text-center">
            <div class="h-2" style="background-color: var(--accent-color);"></div>
            <div class="px-6 sm:px-12 py-12 sm:py-16">
                <p class="numero font-serif text-7xl sm:text-8xl tracking-tight leading-none"><?php echo $__env->yieldContent('codigo'); ?></p>
                <h1 class="titulo font-serif text-2xl sm:text-3xl mt-6 tracking-tight"><?php echo $__env->yieldContent('encabezado'); ?></h1>
                <p class="texto text-[15px] leading-relaxed mt-4 max-w-sm mx-auto"><?php echo $__env->yieldContent('mensaje'); ?></p>
                <div class="mt-10 flex flex-wrap justify-center gap-3">
                    <?php if (! empty(trim($__env->yieldContent('botones')))): ?>
                        <?php echo $__env->yieldContent('botones'); ?>
                    <?php else: ?>
                        <a href="/" class="boton-principal inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Volver al inicio</a>
                        <a href="/catalogo" class="boton-borde inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Ver catálogo</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
<?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/errors/layout.blade.php ENDPATH**/ ?>