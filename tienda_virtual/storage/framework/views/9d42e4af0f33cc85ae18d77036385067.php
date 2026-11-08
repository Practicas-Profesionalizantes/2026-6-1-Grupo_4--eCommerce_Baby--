

<?php $__env->startSection('titulo', 'Soporte'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-16 px-6 sm:px-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8">
            
            <aside class="w-full md:w-1/3">
                <div class="bg-[#f2a6a6] rounded-[2.5rem] p-8 shadow-xl shadow-[#f2a6a6]/20 relative overflow-hidden">
                    <div class="flex flex-col items-center mt-2 mb-8 relative z-10">
                        <div class="w-24 h-24 rounded-full bg-white/40 flex items-center justify-center text-[#4c3300] mb-4 shadow-inner border-4 border-white/60">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <h2 class="text-lg font-bold text-[#4c3300] text-center"><?php echo e(Auth::user()->name ?? 'Invitado'); ?></h2>
                    </div>

                    <nav class="flex flex-col gap-2 relative z-10">
                        <a href="<?php echo e(route('cuenta.index')); ?>" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Volver a Mis Datos</span>
                        </a>
                        <a href="<?php echo e(route('cuenta.apariencia')); ?>" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                            <span>Apariencia</span>
                        </a>
                        <a href="<?php echo e(route('cuenta.tipografia')); ?>" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                            <span>Tipografía</span>
                        </a>
                        <a href="<?php echo e(route('cuenta.soporte')); ?>" class="group relative flex items-center gap-3 px-5 py-4 bg-white text-[#4c3300] rounded-full text-sm font-bold shadow-md">
                            <span class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#f2a6a6] rounded-full animate-pulse"></span>
                            <svg class="w-4 h-4 text-[#d98c8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Soporte</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <main class="w-full md:w-2/3 flex flex-col gap-8">
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-[#4c3300]/5 border border-[#f0dfc8] relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#faebd7] via-[#f2a6a6] to-[#d98c8c]"></div>

                    <div class="flex items-center gap-4 mb-8 mt-2">
                        <a href="<?php echo e(route('cuenta.index')); ?>" class="w-8 h-8 bg-[#faf0e6] rounded-full flex items-center justify-center text-[#d98c8c] hover:bg-[#f2a6a6] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <h3 class="font-serif text-2xl text-[#4c3300]">Soporte y Comentarios</h3>
                    </div>

                    <div id="avisoExito" style="display:none" class="mb-6 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] px-5 py-4 text-sm text-[#4c3300]"></div>
                    <div id="avisoError" style="display:none" class="mb-6 rounded-2xl bg-[#fde8e8] border border-[#f2a6a6] px-5 py-4 text-sm text-[#4c3300]"></div>

                    <form id="formSoporte" class="flex flex-col gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Asunto</label>
                            <input type="text" id="asunto" maxlength="150" placeholder="Por ejemplo: problema con mi pedido" class="w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-2xl px-5 py-4 text-sm text-[#4c3300] focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Comunicarse con soporte</label>
                            <textarea id="mensaje" rows="5" maxlength="2000" placeholder="Escribí tu mensaje acá..." class="w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-2xl px-5 py-4 text-sm text-[#4c3300] focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all resize-none"></textarea>
                            <div id="contadorMensaje" class="kiddy-contador"></div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Mail</label>
                            <input type="email" value="<?php echo e(Auth::user()->email ?? ''); ?>" class="w-full border border-[#f0dfc8] bg-[#faf0e6]/50 rounded-2xl px-5 py-4 text-sm text-[#7a5c3e] cursor-not-allowed" readonly>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="button" id="botonPrivacidad" class="w-full sm:w-1/2 bg-transparent border-2 border-[#4c3300] text-[#4c3300] hover:bg-[#4c3300] hover:text-white text-xs font-bold tracking-widest uppercase px-6 py-4 rounded-full transition-all duration-300 active:scale-95">
                                Condiciones de privacidad
                            </button>
                            <button type="submit" id="botonEnviar" class="w-full sm:w-1/2 bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-6 py-4 rounded-full transition-all duration-300 active:scale-95 shadow-lg hover:shadow-xl">
                                Enviar mensaje
                            </button>
                        </div>
                    </form>
                </div>

                <div id="bloqueMensajes" style="display:none" class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-[#4c3300]/5 border border-[#f0dfc8]">
                    <h3 class="font-serif text-2xl text-[#4c3300] mb-6">Tus mensajes</h3>
                    <div id="listaMensajes" class="flex flex-col gap-4"></div>
                </div>
            </main>
        </div>
    </div>
</div>

<style>
    .modal-privacidad { position: fixed; top: 0; right: 0; bottom: 0; left: 0; background: rgba(76, 51, 0, 0.45); z-index: 100; align-items: center; justify-content: center; padding: 24px; }
    .modal-privacidad-caja { background: #ffffff; border: 1px solid #f0dfc8; border-radius: 28px; max-width: 560px; width: 100%; max-height: 80vh; overflow-y: auto; padding: 32px; color: #4c3300; box-shadow: 0 20px 50px rgba(76, 51, 0, 0.25); }
    .modal-privacidad-caja h3 { font-family: 'Playfair Display', serif; font-size: 24px; margin: 0 0 4px 0; }
    .modal-privacidad-caja h4 { font-size: 14px; font-weight: 700; margin: 20px 0 6px 0; }
    .modal-privacidad-caja p { font-size: 14px; line-height: 1.6; margin: 0; }
    .modal-privacidad-fecha { font-size: 12px !important; color: #7a5c3e; margin-bottom: 16px !important; }
    .modal-privacidad-cerrar { margin-top: 24px; background: #4c3300; color: #faf0e6; border: 0; border-radius: 9999px; padding: 12px 28px; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; }
    .modal-privacidad-cerrar:hover { opacity: 0.9; }
    .boton-eliminar-mensaje { background: transparent; border: 0; padding: 4px 2px; font-size: 12px; font-weight: 600; color: #7a5c3e; text-decoration: underline; text-underline-offset: 3px; cursor: pointer; }
    .boton-eliminar-mensaje:hover { color: #b8423f; }
    .boton-eliminar-mensaje:disabled { opacity: 0.5; cursor: default; }
</style>

<div id="modalPrivacidad" class="modal-privacidad" style="display:none" role="dialog" aria-modal="true" aria-labelledby="tituloPrivacidad">
    <div class="modal-privacidad-caja">
        <h3 id="tituloPrivacidad">Condiciones de privacidad</h3>
        <p class="modal-privacidad-fecha">Última actualización: 29 de octubre de 2026</p>
        <p>En Kiddy Shop nos importa tu privacidad. Acá te contamos, de forma simple, qué datos usamos y para qué.</p>

        <h4>1. Qué datos guardamos</h4>
        <p>Tu nombre y apellido, tu mail, tu teléfono, tu dirección de entrega, los pedidos que hacés y los mensajes que nos mandás desde Soporte.</p>

        <h4>2. Para qué los usamos</h4>
        <p>Para procesar y entregar tus pedidos, emitir tus facturas, responder tus consultas y avisarte sobre lo que pasa con tus compras.</p>

        <h4>3. Con quién los compartimos</h4>
        <p>No vendemos ni cedemos tus datos a terceros. Solo los compartimos con quienes participan de tu compra, como los servicios de pago y de envío, y únicamente lo necesario para completarla.</p>

        <h4>4. Cuánto tiempo los guardamos</h4>
        <p>Mientras tu cuenta esté activa o mientras tengamos que conservarlos por obligaciones como la facturación.</p>

        <h4>5. Tus derechos</h4>
        <p>Podés pedirnos ver, corregir o eliminar tus datos escribiéndonos desde esta misma pantalla de Soporte. Te vamos a responder lo antes posible.</p>

        <h4>6. Cambios en estas condiciones</h4>
        <p>Si algún día las modificamos, vamos a actualizar la fecha que figura arriba.</p>

        <button type="button" id="cerrarPrivacidad" class="modal-privacidad-cerrar">Entendido</button>
    </div>
</div>

<script>
    const token = "<?php echo e(csrf_token()); ?>";
    const form = document.getElementById('formSoporte');
    const campoAsunto = document.getElementById('asunto');
    const campoMensaje = document.getElementById('mensaje');
    const botonEnviar = document.getElementById('botonEnviar');
    const avisoExito = document.getElementById('avisoExito');
    const avisoError = document.getElementById('avisoError');
    const bloqueMensajes = document.getElementById('bloqueMensajes');
    const listaMensajes = document.getElementById('listaMensajes');
    const contadorMensaje = document.getElementById('contadorMensaje');

    function actualizarContador() {
        const largo = campoMensaje.value.length;
        const maximo = Number(campoMensaje.maxLength) || 2000;
        contadorMensaje.textContent = largo + ' / ' + maximo + (largo < 10 ? ' · mínimo 10' : '');
        contadorMensaje.classList.toggle('alerta', largo >= maximo * 0.9);
    }

    campoMensaje.addEventListener('input', actualizarContador);

    function avisar(texto, tipo) {
        if (window.kiddyAviso) window.kiddyAviso(texto, tipo);
        else window.alert(texto);
    }

    function fechaAmable(texto) {
        return window.kiddyFecha ? window.kiddyFecha(texto) : (texto || '');
    }

    function crear(tag, clases, texto) {
        const el = document.createElement(tag);
        if (clases) el.className = clases;
        if (texto !== undefined) el.textContent = texto;
        return el;
    }

    async function eliminarMensaje(id, boton) {
        const confirmado = window.kiddyConfirmar
            ? await window.kiddyConfirmar('Esta acción no se puede deshacer.', { titulo: '¿Eliminar este mensaje?', confirmar: 'Eliminar', peligro: true })
            : window.confirm('¿Querés eliminar este mensaje?');
        if (!confirmado) return;

        boton.disabled = true;
        boton.textContent = 'Eliminando...';

        try {
            const respuesta = await fetch('/api/mis-reportes/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            if (!respuesta.ok) throw new Error('Error ' + respuesta.status);
            await cargarMensajes();
            avisar('Mensaje eliminado.', 'ok');
        } catch (error) {
            boton.disabled = false;
            boton.textContent = 'Eliminar mensaje';
            avisar('No se pudo eliminar el mensaje. Probá de nuevo.', 'error');
        }
    }

    function tarjetaMensaje(r) {
        const nueva = Boolean(r.Respuesta) && Number(r.Respuesta_vista) === 0;
        const caja = crear('div', 'rounded-2xl border border-[#f0dfc8] p-5');
        if (nueva) {
            caja.id = 'respuestaNueva';
            caja.style.borderColor = '#f2a6a6';
        }
        const arriba = crear('div', 'flex items-start justify-between gap-4');
        arriba.appendChild(crear('span', 'font-semibold text-[#4c3300]', r.Asunto));
        arriba.appendChild(crear('span', 'text-xs text-[#7a5c3e] whitespace-nowrap', fechaAmable(r.Fecha_creacion)));
        caja.appendChild(arriba);
        caja.appendChild(crear('p', 'text-sm text-[#4c3300] whitespace-pre-line mt-3', r.Mensaje));

        if (r.Respuesta) {
            const resp = crear('div', 'mt-4 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] p-4');
            resp.appendChild(crear('div', 'text-[10px] font-bold tracking-widest uppercase text-[#7a5c3e] mb-1', 'Respuesta de Kiddy Shop · ' + fechaAmable(r.Fecha_respuesta) + (nueva ? ' · Nueva' : '')));
            resp.appendChild(crear('p', 'text-sm text-[#4c3300] whitespace-pre-line', r.Respuesta));
            caja.appendChild(resp);
        } else {
            caja.appendChild(crear('div', 'mt-4 text-xs text-[#7a5c3e]', 'Todavía sin respuesta.'));
        }

        const pie = crear('div', 'mt-3 flex justify-end');
        const eliminar = crear('button', 'boton-eliminar-mensaje', 'Eliminar mensaje');
        eliminar.type = 'button';
        eliminar.addEventListener('click', function () { eliminarMensaje(r.Id_reporte, eliminar); });
        pie.appendChild(eliminar);
        caja.appendChild(pie);

        return caja;
    }

    async function marcarVistas() {
        try {
            await fetch('/api/mis-reportes/vistos', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            const campana = document.getElementById('campanaCliente');
            if (campana) campana.style.display = 'none';
        } catch (error) {
        }
    }

    async function cargarMensajes() {
        try {
            const respuesta = await fetch('/api/mis-reportes', { headers: { 'Accept': 'application/json' } });
            if (!respuesta.ok) return;
            const reportes = await respuesta.json();
            listaMensajes.innerHTML = '';
            if (!Array.isArray(reportes) || reportes.length === 0) {
                bloqueMensajes.style.display = 'none';
                return;
            }
            reportes.forEach(function (r) { listaMensajes.appendChild(tarjetaMensaje(r)); });
            bloqueMensajes.style.display = 'block';

            const nueva = document.getElementById('respuestaNueva');
            if (nueva) {
                nueva.scrollIntoView({ behavior: 'smooth', block: 'center' });
                marcarVistas();
            }
        } catch (error) {
        }
    }

    const modalPrivacidad = document.getElementById('modalPrivacidad');

    function abrirPrivacidad() { modalPrivacidad.style.display = 'flex'; }
    function cerrarPrivacidad() { modalPrivacidad.style.display = 'none'; }

    document.getElementById('botonPrivacidad').addEventListener('click', abrirPrivacidad);
    document.getElementById('cerrarPrivacidad').addEventListener('click', cerrarPrivacidad);
    modalPrivacidad.addEventListener('click', function (evento) {
        if (evento.target === modalPrivacidad) cerrarPrivacidad();
    });
    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') cerrarPrivacidad();
    });

    form.addEventListener('submit', async function (evento) {
        evento.preventDefault();
        avisoExito.style.display = 'none';
        avisoError.style.display = 'none';
        botonEnviar.disabled = true;
        botonEnviar.textContent = 'Enviando...';

        try {
            const respuesta = await fetch('/api/mis-reportes', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ asunto: campoAsunto.value, mensaje: campoMensaje.value })
            });
            const datos = await respuesta.json();

            if (!respuesta.ok) {
                const mensajes = datos.errors ? Object.values(datos.errors).flat() : [datos.message || 'Error ' + respuesta.status];
                avisoError.innerHTML = '';
                mensajes.forEach(function (m) { avisoError.appendChild(crear('p', '', m)); });
                avisoError.style.display = 'block';
                return;
            }

            avisoExito.textContent = datos.message;
            avisoExito.style.display = 'block';
            campoAsunto.value = '';
            campoMensaje.value = '';
            actualizarContador();
            cargarMensajes();
        } catch (error) {
            avisoError.innerHTML = '';
            avisoError.appendChild(crear('p', '', 'No se pudo enviar el mensaje. Probá de nuevo.'));
            avisoError.style.display = 'block';
        } finally {
            botonEnviar.disabled = false;
            botonEnviar.textContent = 'Enviar mensaje';
        }
    });

    actualizarContador();
    cargarMensajes();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/cuenta/soporte.blade.php ENDPATH**/ ?>