<?php echo $__env->make('tema', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('ui-extras', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    .hdr-tip-wrap { position: relative; display: flex; align-items: center; }
    .hdr-tip {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 14px;
        white-space: nowrap;
        background: #4c3300;
        color: #faf0e6;
        font-size: 10px;
        font-weight: 500;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 6px 12px;
        border-radius: 9999px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s;
        z-index: 60;
    }
    .hdr-tip-der { left: auto; right: -8px; transform: none; }
    .hdr-tip-wrap:hover .hdr-tip,
    .hdr-tip-wrap:focus-visible .hdr-tip { opacity: 1; visibility: visible; }
    .hdr-tip-wrap.abierto .hdr-tip,
    .hdr-tip-wrap.abierto:hover .hdr-tip { opacity: 0; visibility: hidden; }
    .hdr-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        background: #f2a6a6;
        color: #4c3300;
        font-size: 9px;
        font-weight: 600;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        border-radius: 9999px;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .hdr-badge.activo { display: flex; }
    .hdr-menu {
        position: absolute;
        top: 100%;
        right: -8px;
        margin-top: 14px;
        width: 280px;
        background: #ffffff;
        color: #4c3300;
        border: 1px solid #f0dfc8;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(76, 51, 0, 0.15);
        padding: 8px;
        display: none;
        z-index: 70;
        text-align: left;
        text-transform: none;
        letter-spacing: normal;
    }
    .hdr-menu.abierto { display: block; }
    .hdr-aviso { display: block; padding: 10px 12px; border-radius: 12px; font-size: 13px; line-height: 1.35; }
    a.hdr-aviso:hover { background: #faf0e6; }
    .hdr-aviso + .hdr-aviso { border-top: 1px solid #f0dfc8; }
    html.dark .hdr-menu { background: #1c1917; color: #faf0e6; border-color: #3a322b; }
    html.dark a.hdr-aviso:hover { background: #2a2420; }
    html.dark .hdr-aviso + .hdr-aviso { border-top-color: #3a322b; }
    .hdr-admin {
        display: none;
        background: #4c3300;
        color: #faf0e6 !important;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 10px 20px;
        border-radius: 9999px;
        box-shadow: 0 4px 10px rgba(76, 51, 0, 0.25);
        transition: opacity 0.2s, transform 0.2s;
        white-space: nowrap;
    }
    .hdr-admin:hover { opacity: 0.9; }
    .hdr-admin:active { transform: scale(0.95); }
    @media (min-width: 640px) { .hdr-admin { display: inline-block; } }
    html.dark .hdr-admin { background: #e6a1a1; color: #12100e !important; }

    .hdr-izq { display: flex; align-items: center; gap: 8px; }
    .hdr-hamb {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        margin-left: -8px;
        padding: 0;
        border: 0;
        border-radius: 9999px;
        background: transparent;
        color: var(--text-main, #4c3300);
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s;
    }
    .hdr-hamb:hover { color: var(--accent-color, #d98c8c); }
    .hdr-hamb:focus-visible { outline: 2px solid var(--accent-color, #d98c8c); outline-offset: 1px; }
    .hdr-hamb .hamb-cerrar { display: none; }
    .hdr-hamb.abierto .hamb-abrir { display: none; }
    .hdr-hamb.abierto .hamb-cerrar { display: block; }
    .hdr-movil {
        position: fixed;
        top: 80px;
        left: 0;
        right: 0;
        z-index: 45;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
        display: none;
        padding: 6px 24px 18px 24px;
        background: var(--card-bg, #ffffff);
        border-bottom: 1px solid var(--border-color, #f0dfc8);
        box-shadow: 0 16px 30px rgba(76, 51, 0, 0.15);
    }
    .hdr-movil.abierto { display: block; }
    .hdr-movil a {
        display: block;
        padding: 15px 4px;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-muted, #7a5c3e);
        border-bottom: 1px solid var(--border-color, #f0dfc8);
        transition: color 0.2s;
    }
    .hdr-movil a:last-child { border-bottom: 0; }
    .hdr-movil a:hover,
    .hdr-movil a[aria-current="page"] { color: var(--text-main, #4c3300); }
    .hdr-movil a[aria-current="page"] { font-weight: 700; }
    @media (min-width: 768px) {
        .hdr-hamb, .hdr-movil { display: none !important; }
    }
</style>

<header class="sticky top-0 z-50 backdrop-blur-md transition-colors duration-300 border-b">
    <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-[8%] h-20 flex items-center justify-between">

        <div class="hdr-izq font-serif text-xl tracking-wider text-[#4c3300]">
            <button type="button" id="botonMenuMovil" class="hdr-hamb" aria-label="Abrir menú" aria-expanded="false" aria-controls="menuMovil">
                <svg class="hamb-abrir" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
                <svg class="hamb-cerrar" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
            <a href="/" class="hover:opacity-80 transition-opacity">Kiddy Shop</a>
        </div>

        <nav class="hidden md:flex items-center gap-8 text-xs font-medium tracking-widest uppercase text-[#7a5c3e]">
            <a href="/" class="hover:text-[#4c3300] transition-colors">Inicio</a>
            <a href="/catalogo" class="hover:text-[#4c3300] transition-colors">Ver Catálogo</a>
            <a href="/terminos-de-privacidad" class="hover:text-[#4c3300] transition-colors">Términos de Privacidad</a>
        </nav>

        <div class="flex items-center gap-6 text-[#4c3300]">

            <?php if(auth()->guard()->check()): ?>
                <?php if((int) auth()->user()->Id_rol === 2): ?>
                    <a href="<?php echo e(route('admin.problemas')); ?>" id="campanaAdmin" data-href-pedidos="<?php echo e(route('admin.pedidos')); ?>" aria-label="Avisos de administración" class="hdr-tip-wrap hover:text-[#d98c8c] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                        </svg>
                        <span id="contadorReportes" class="hdr-badge"></span>
                        <span class="hdr-tip hdr-tip-der">Reportes y pedidos</span>
                    </a>
                    <a href="<?php echo e(route('admin.inicio')); ?>" class="hdr-admin">Panel de administrador</a>
                <?php else: ?>
                    <div id="campanaCliente" style="display:none" data-href-soporte="<?php echo e(route('cuenta.soporte')); ?>" data-href-pedidos="<?php echo e(\Illuminate\Support\Facades\Route::has('cuenta.pedidos') ? route('cuenta.pedidos') : route('cuenta.index')); ?>" class="hdr-tip-wrap">
                        <button type="button" id="botonCampanaCliente" aria-label="Avisos" class="hover:text-[#d98c8c] transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                            </svg>
                        </button>
                        <span id="contadorRespuestas" class="hdr-badge"></span>
                        <span class="hdr-tip hdr-tip-der">Avisos</span>
                        <div id="menuAvisos" class="hdr-menu"></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('register')); ?>" class="text-[10px] font-bold tracking-widest uppercase text-[#4c3300] border-b border-[#4c3300] hover:text-[#d98c8c] hover:border-[#d98c8c] pb-1 hidden sm:block transition-colors">
                    Registrarse
                </a>
                <a href="<?php echo e(route('login')); ?>" aria-label="Iniciar Sesión" class="hdr-tip-wrap hover:text-[#d98c8c] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="hdr-tip">Iniciar sesión</span>
                </a>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('cuenta.index')); ?>" aria-label="Mi Cuenta" class="hdr-tip-wrap hover:text-[#d98c8c] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="hdr-tip">Mi Cuenta</span>
                </a>
            <?php endif; ?>

            <a href="/carrito" aria-label="Mi Carrito" class="hdr-tip-wrap hover:text-[#d98c8c] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                <span id="contadorCarrito" class="hdr-badge"></span>
                <span class="hdr-tip hdr-tip-der">Mi Carrito</span>
            </a>
        </div>

    </div>
</header>

<nav id="menuMovil" class="hdr-movil" aria-label="Menú principal">
    <a href="/" <?php if(request()->is('/')): ?> aria-current="page" <?php endif; ?>>Inicio</a>
    <a href="/catalogo" <?php if(request()->is('catalogo')): ?> aria-current="page" <?php endif; ?>>Ver Catálogo</a>
    <a href="/terminos-de-privacidad" <?php if(request()->is('terminos-de-privacidad')): ?> aria-current="page" <?php endif; ?>>Términos de Privacidad</a>
    <?php if(auth()->guard()->guest()): ?>
        <a href="<?php echo e(route('register')); ?>">Registrarse</a>
        <a href="<?php echo e(route('login')); ?>">Iniciar sesión</a>
    <?php endif; ?>
    <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('cuenta.index')); ?>">Mi Cuenta</a>
        <?php if((int) auth()->user()->Id_rol === 2): ?>
            <a href="<?php echo e(route('admin.inicio')); ?>">Panel de administrador</a>
        <?php endif; ?>
    <?php endif; ?>
</nav>

<script>
(function () {
    const boton = document.getElementById('botonMenuMovil');
    const menu = document.getElementById('menuMovil');
    if (!boton || !menu) return;

    function abrir() {
        menu.classList.add('abierto');
        boton.classList.add('abierto');
        boton.setAttribute('aria-expanded', 'true');
        boton.setAttribute('aria-label', 'Cerrar menú');
    }

    function cerrar() {
        menu.classList.remove('abierto');
        boton.classList.remove('abierto');
        boton.setAttribute('aria-expanded', 'false');
        boton.setAttribute('aria-label', 'Abrir menú');
    }

    boton.addEventListener('click', function (evento) {
        evento.stopPropagation();
        if (menu.classList.contains('abierto')) cerrar(); else abrir();
    });

    menu.addEventListener('click', function (evento) {
        if (evento.target.closest('a')) cerrar();
    });

    document.addEventListener('click', function (evento) {
        if (!menu.contains(evento.target) && !boton.contains(evento.target)) cerrar();
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') cerrar();
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) cerrar();
    });
})();
</script>

<?php if(auth()->guard()->check()): ?>
<script>
const avisosCliente = { respuestas: 0, lista: [] };

document.addEventListener('DOMContentLoaded', function () {
    actualizarContadorCarrito();
    cargarAvisosAdmin();
    cargarAvisosCliente();
    prepararMenuAvisos();
});

async function actualizarContadorCarrito() {
    const contador = document.getElementById('contadorCarrito');
    if (!contador) return;
    try {
        const respuesta = await fetch('/api/mi-carrito/contador', { headers: { 'Accept': 'application/json' } });
        if (!respuesta.ok) return;
        const datos = await respuesta.json();
        const total = Number(datos.total) || 0;
        if (total > 0) {
            contador.textContent = total > 99 ? '99+' : total;
            contador.classList.add('activo');
        } else {
            contador.textContent = '';
            contador.classList.remove('activo');
        }
    } catch (error) {
    }
}

function ponerBadgeCliente(total) {
    const contador = document.getElementById('contadorRespuestas');
    if (!contador) return;
    if (total > 0) {
        contador.textContent = total > 99 ? '99+' : total;
        contador.classList.add('activo');
    } else {
        contador.textContent = '';
        contador.classList.remove('activo');
    }
}

async function cargarAvisosCliente() {
    const campana = document.getElementById('campanaCliente');
    if (!campana) return;

    let respuestas = 0;
    let lista = [];

    try {
        const r = await fetch('/api/mis-reportes/contador', { headers: { 'Accept': 'application/json' } });
        if (r.ok) {
            const datos = await r.json();
            respuestas = Number(datos.total) || 0;
        }
    } catch (error) {
    }

    try {
        const r = await fetch('/api/mis-avisos-pedidos', { headers: { 'Accept': 'application/json' } });
        if (r.ok) {
            const datos = await r.json();
            lista = Array.isArray(datos.lista) ? datos.lista : [];
        }
    } catch (error) {
    }

    avisosCliente.respuestas = respuestas;
    avisosCliente.lista = lista;

    const total = respuestas + lista.length;
    if (total > 0) {
        ponerBadgeCliente(total);
        campana.style.display = 'flex';
    }
}

function crearAviso(etiqueta, clases, texto) {
    const el = document.createElement(etiqueta);
    el.className = clases;
    el.textContent = texto;
    return el;
}

function dibujarMenuAvisos(lista) {
    const campana = document.getElementById('campanaCliente');
    const menu = document.getElementById('menuAvisos');
    menu.replaceChildren();

    lista.forEach(function (aviso) {
        const enlace = crearAviso('a', 'hdr-aviso', aviso.mensaje);
        enlace.href = campana.dataset.hrefPedidos;
        menu.appendChild(enlace);
    });

    if (avisosCliente.respuestas > 0) {
        const enlace = crearAviso('a', 'hdr-aviso', 'Tenés una respuesta de soporte.');
        enlace.href = campana.dataset.hrefSoporte;
        menu.appendChild(enlace);
    }

    if (lista.length === 0 && avisosCliente.respuestas === 0) {
        menu.appendChild(crearAviso('div', 'hdr-aviso', 'No tenés avisos nuevos.'));
    }
}

function cerrarMenuAvisos() {
    const campana = document.getElementById('campanaCliente');
    const menu = document.getElementById('menuAvisos');
    if (!campana || !menu) return;
    menu.classList.remove('abierto');
    campana.classList.remove('abierto');
}

async function marcarAvisosVistos() {
    try {
        await fetch('/api/mis-avisos-pedidos/vistos', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
        });
    } catch (error) {
    }
}

function prepararMenuAvisos() {
    const campana = document.getElementById('campanaCliente');
    const boton = document.getElementById('botonCampanaCliente');
    const menu = document.getElementById('menuAvisos');
    if (!campana || !boton || !menu) return;

    boton.addEventListener('click', function (e) {
        e.stopPropagation();
        if (menu.classList.contains('abierto')) {
            cerrarMenuAvisos();
            return;
        }
        const lista = avisosCliente.lista;
        dibujarMenuAvisos(lista);
        menu.classList.add('abierto');
        campana.classList.add('abierto');
        if (lista.length > 0) {
            avisosCliente.lista = [];
            ponerBadgeCliente(avisosCliente.respuestas);
            marcarAvisosVistos();
        }
    });

    document.addEventListener('click', function (e) {
        if (!campana.contains(e.target)) cerrarMenuAvisos();
    });
}

async function pedirContador(url) {
    try {
        const respuesta = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!respuesta.ok) return 0;
        const datos = await respuesta.json();
        return Number(datos.total) || 0;
    } catch (error) {
        return 0;
    }
}

async function cargarAvisosAdmin() {
    const campana = document.getElementById('campanaAdmin');
    const contador = document.getElementById('contadorReportes');
    if (!campana || !contador) return;
    const reportes = await pedirContador('/api/admin/reportes/contador');
    const pedidos = await pedirContador('/api/admin/pedidos-aviso/contador');
    const total = reportes + pedidos;
    if (total > 0) {
        contador.textContent = total > 99 ? '99+' : total;
        contador.classList.add('activo');
    }
    if (reportes === 0 && pedidos > 0) {
        campana.href = campana.dataset.hrefPedidos;
    }
}
</script>
<?php endif; ?>
<?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/header-footer.blade.php ENDPATH**/ ?>