<style>
    .kiddy-toasts { position: fixed; left: 50%; bottom: 24px; transform: translateX(-50%); z-index: 10000; display: flex; flex-direction: column; gap: 10px; width: min(92vw, 420px); pointer-events: none; }
    .kiddy-toast { pointer-events: auto; background: var(--card-bg, #ffffff); color: var(--text-main, #4c3300); border: 1px solid var(--border-color, #f0dfc8); border-left: 5px solid var(--accent-color, #f2a6a6); border-radius: 16px; padding: 14px 16px; font-size: 14px; line-height: 1.4; box-shadow: 0 12px 30px rgba(76, 51, 0, 0.18); cursor: pointer; opacity: 0; transform: translateY(12px); transition: opacity 0.25s, transform 0.25s; }
    .kiddy-toast.visible { opacity: 1; transform: translateY(0); }
    .kiddy-toast--ok { border-left-color: #4f8a5b; }
    .kiddy-toast--error { border-left-color: #b8423f; }

    .kiddy-modal { position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 10001; display: flex; align-items: center; justify-content: center; padding: 24px; background: rgba(40, 26, 5, 0.5); opacity: 0; transition: opacity 0.2s; }
    .kiddy-modal.visible { opacity: 1; }
    .kiddy-modal-caja { width: 100%; max-width: 400px; background: var(--card-bg, #ffffff); color: var(--text-main, #4c3300); border: 1px solid var(--border-color, #f0dfc8); border-radius: 28px; padding: 28px; box-shadow: 0 24px 60px rgba(0, 0, 0, 0.3); transform: translateY(10px) scale(0.98); transition: transform 0.2s; }
    .kiddy-modal.visible .kiddy-modal-caja { transform: none; }
    .kiddy-modal-titulo { font-family: 'Playfair Display', Georgia, serif; font-size: 22px; margin: 0 0 8px 0; }
    .kiddy-modal-texto { font-size: 14px; line-height: 1.55; margin: 0; color: var(--text-muted, #7a5c3e); }
    .kiddy-modal-botones { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 10px; margin-top: 24px; }

    .kiddy-boton { border-radius: 9999px; padding: 12px 22px; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; transition: opacity 0.2s, transform 0.2s, background-color 0.2s, color 0.2s; }
    .kiddy-boton:active { transform: scale(0.96); }
    .kiddy-boton:focus-visible { outline: 2px solid var(--accent-color, #d98c8c); outline-offset: 2px; }
    .kiddy-boton--borde { background: transparent; color: var(--text-main, #4c3300); border: 1px solid var(--text-main, #4c3300); }
    .kiddy-boton--borde:hover { background: var(--text-main, #4c3300); color: var(--card-bg, #ffffff); }
    .kiddy-boton--principal { background: #4c3300; color: #faf0e6; border: 1px solid #4c3300; }
    .kiddy-boton--principal:hover { opacity: 0.9; }
    .kiddy-boton--peligro { background: #b8423f; color: #ffffff; border: 1px solid #b8423f; }
    .kiddy-boton--peligro:hover { opacity: 0.9; }
    html.dark .kiddy-boton--principal { background: #e6a1a1; color: #12100e; border-color: #e6a1a1; }
    html.dark .kiddy-boton--peligro { background: #e07a77; color: #1a0c0b; border-color: #e07a77; }

    .kiddy-subir { position: fixed; right: 20px; bottom: 24px; z-index: 9000; width: 46px; height: 46px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; background: var(--card-bg, #ffffff); color: var(--text-main, #4c3300); border: 1px solid var(--border-color, #f0dfc8); box-shadow: 0 8px 20px rgba(76, 51, 0, 0.18); cursor: pointer; opacity: 0; visibility: hidden; transform: translateY(10px); transition: opacity 0.25s, transform 0.25s, visibility 0.25s, color 0.2s; }
    .kiddy-subir.visible { opacity: 1; visibility: visible; transform: translateY(0); }
    .kiddy-subir:hover { color: var(--accent-color, #d98c8c); }
    .kiddy-subir:focus-visible { outline: 2px solid var(--accent-color, #d98c8c); outline-offset: 2px; }

    .kiddy-ojo { position: absolute; top: 50%; right: 14px; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0; border: 0; border-radius: 9999px; background: transparent; color: var(--text-muted, #7a5c3e); cursor: pointer; transition: color 0.2s; }
    .kiddy-ojo:hover { color: var(--accent-color, #d98c8c); }
    .kiddy-ojo:focus-visible { outline: 2px solid var(--accent-color, #d98c8c); outline-offset: 1px; }
    .kiddy-ojo .ojo-cerrado { display: none; }
    .kiddy-ojo.activo .ojo-abierto { display: none; }
    .kiddy-ojo.activo .ojo-cerrado { display: block; }

    .kiddy-contador { font-size: 11px; text-align: right; padding-right: 12px; color: var(--text-muted, #7a5c3e); }
    .kiddy-contador.alerta { color: #b8423f; font-weight: 600; }

    .kiddy-etiqueta { position: absolute; top: 12px; left: 12px; z-index: 5; background: #4c3300; color: #faf0e6; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 6px 12px; border-radius: 9999px; }
    html.dark .kiddy-etiqueta { background: #e6a1a1; color: #12100e; }
    .kiddy-etiqueta--agotado { background: var(--text-muted, #7a5c3e); color: var(--bg-color, #fffbf7); }
    html.dark .kiddy-etiqueta--agotado { background: var(--text-muted, #b3a193); color: var(--bg-color, #12100e); }

    @media (prefers-reduced-motion: reduce) {
        .kiddy-toast, .kiddy-modal, .kiddy-modal-caja, .kiddy-subir { transition: none; }
    }
</style>

<script>
(function () {
    if (window.__kiddyUi) return;
    window.__kiddyUi = true;

    function ponerFavicon() {
        if (document.querySelector('link[rel~="icon"]')) return;
        const enlace = document.createElement('link');
        enlace.rel = 'icon';
        enlace.type = 'image/svg+xml';
        enlace.href = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%23f2a6a6'/%3E%3Ctext x='32' y='45' font-size='38' font-family='Georgia,serif' font-weight='bold' text-anchor='middle' fill='%234c3300'%3EK%3C/text%3E%3C/svg%3E";
        document.head.appendChild(enlace);
    }

    function contenedorAvisos() {
        let caja = document.getElementById('kiddyAvisos');
        if (!caja) {
            caja = document.createElement('div');
            caja.id = 'kiddyAvisos';
            caja.className = 'kiddy-toasts';
            document.body.appendChild(caja);
        }
        return caja;
    }

    window.kiddyAviso = function (texto, tipo) {
        tipo = tipo || 'info';
        const aviso = document.createElement('div');
        aviso.className = 'kiddy-toast' + (tipo === 'ok' ? ' kiddy-toast--ok' : (tipo === 'error' ? ' kiddy-toast--error' : ''));
        aviso.setAttribute('role', tipo === 'error' ? 'alert' : 'status');
        aviso.textContent = texto;
        contenedorAvisos().appendChild(aviso);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () { aviso.classList.add('visible'); });
        });

        function cerrar() {
            aviso.classList.remove('visible');
            setTimeout(function () { aviso.remove(); }, 300);
        }

        aviso.addEventListener('click', cerrar);
        setTimeout(cerrar, tipo === 'error' ? 6000 : 4000);
    };

    window.kiddyConfirmar = function (mensaje, opciones) {
        opciones = opciones || {};

        return new Promise(function (resolver) {
            const anterior = document.activeElement;
            const overflowAnterior = document.body.style.overflow;

            const fondo = document.createElement('div');
            fondo.className = 'kiddy-modal';

            const caja = document.createElement('div');
            caja.className = 'kiddy-modal-caja';
            caja.setAttribute('role', 'alertdialog');
            caja.setAttribute('aria-modal', 'true');
            caja.setAttribute('aria-labelledby', 'kiddyModalTitulo');
            caja.setAttribute('aria-describedby', 'kiddyModalTexto');

            const titulo = document.createElement('h3');
            titulo.id = 'kiddyModalTitulo';
            titulo.className = 'kiddy-modal-titulo';
            titulo.textContent = opciones.titulo || 'Confirmar';

            const texto = document.createElement('p');
            texto.id = 'kiddyModalTexto';
            texto.className = 'kiddy-modal-texto';
            texto.textContent = mensaje;

            const botones = document.createElement('div');
            botones.className = 'kiddy-modal-botones';

            const cancelar = document.createElement('button');
            cancelar.type = 'button';
            cancelar.className = 'kiddy-boton kiddy-boton--borde';
            cancelar.textContent = opciones.cancelar || 'Cancelar';

            const confirmar = document.createElement('button');
            confirmar.type = 'button';
            confirmar.className = 'kiddy-boton ' + (opciones.peligro ? 'kiddy-boton--peligro' : 'kiddy-boton--principal');
            confirmar.textContent = opciones.confirmar || 'Aceptar';

            botones.appendChild(cancelar);
            botones.appendChild(confirmar);
            caja.appendChild(titulo);
            caja.appendChild(texto);
            caja.appendChild(botones);
            fondo.appendChild(caja);

            function cerrar(valor) {
                document.removeEventListener('keydown', teclas, true);
                fondo.classList.remove('visible');
                document.body.style.overflow = overflowAnterior;
                setTimeout(function () { fondo.remove(); }, 200);
                if (anterior && anterior.focus) anterior.focus();
                resolver(valor);
            }

            function teclas(evento) {
                if (evento.key === 'Escape') {
                    evento.preventDefault();
                    cerrar(false);
                    return;
                }
                if (evento.key === 'Tab') {
                    evento.preventDefault();
                    const lista = [cancelar, confirmar];
                    const actual = lista.indexOf(document.activeElement);
                    let siguiente = 0;
                    if (actual !== -1) {
                        siguiente = evento.shiftKey ? (actual + lista.length - 1) % lista.length : (actual + 1) % lista.length;
                    }
                    lista[siguiente].focus();
                }
            }

            cancelar.addEventListener('click', function () { cerrar(false); });
            confirmar.addEventListener('click', function () { cerrar(true); });
            fondo.addEventListener('click', function (evento) {
                if (evento.target === fondo) cerrar(false);
            });
            document.addEventListener('keydown', teclas, true);

            document.body.style.overflow = 'hidden';
            document.body.appendChild(fondo);
            requestAnimationFrame(function () {
                fondo.classList.add('visible');
                (opciones.peligro ? cancelar : confirmar).focus();
            });
        });
    };

    window.kiddyFecha = function (texto) {
        if (!texto) return '';
        const fecha = new Date(String(texto).replace(' ', 'T'));
        if (isNaN(fecha.getTime())) return String(texto);
        const formato = { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit', hour12: false };
        if (fecha.getFullYear() !== new Date().getFullYear()) formato.year = 'numeric';
        return fecha.toLocaleString('es-AR', formato);
    };

    function prepararOjitos() {
        document.addEventListener('click', function (evento) {
            const boton = evento.target.closest('[data-ver-clave]');
            if (!boton) return;
            const campo = document.getElementById(boton.dataset.verClave);
            if (!campo) return;

            const mostrar = campo.type === 'password';
            campo.type = mostrar ? 'text' : 'password';
            boton.classList.toggle('activo', mostrar);
            boton.setAttribute('aria-pressed', mostrar ? 'true' : 'false');
            boton.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
        });
    }

    function prepararSubir() {
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'kiddy-subir';
        boton.setAttribute('aria-label', 'Volver arriba');
        boton.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>';
        document.body.appendChild(boton);

        function actualizar() {
            boton.classList.toggle('visible', window.scrollY > 600);
        }

        boton.addEventListener('click', function () {
            const sinMovimiento = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({ top: 0, behavior: sinMovimiento ? 'auto' : 'smooth' });
        });

        window.addEventListener('scroll', actualizar, { passive: true });
        actualizar();
    }

    function iniciar() {
        ponerFavicon();
        prepararOjitos();
        prepararSubir();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciar);
    } else {
        iniciar();
    }
})();
</script>
<?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/ui-extras.blade.php ENDPATH**/ ?>