

<?php $__env->startSection('titulo', 'Pedidos'); ?>

<?php $__env->startSection('contenido'); ?>
<div class="max-w-6xl">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="font-serif text-3xl mb-1">Pedidos</h1>
            <p class="text-sm text-[#7a5c3e]">Acá ves las compras, cambiás su estado, cargás el envío y podés cancelarlas.</p>
        </div>
        <select id="filtro" class="rounded-full border border-[#f0dfc8] bg-white px-5 py-2 text-sm outline-none focus:border-[#f2a6a6]">
            <option value="">Todos los estados</option>
            <option value="Pendiente">Pendiente</option>
            <option value="Preparacion">Preparación</option>
            <option value="Enviado">Enviado</option>
            <option value="Entregado">Entregado</option>
            <option value="Cancelado">Cancelado</option>
        </select>
    </div>

    <div id="estado" class="rounded-2xl bg-white/80 border border-[#f0dfc8] p-6 text-sm text-[#7a5c3e]">Cargando pedidos...</div>

    <div id="tablaCaja" class="hidden rounded-2xl bg-white border border-[#f0dfc8] overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[10px] font-bold tracking-widest uppercase text-[#7a5c3e] border-b border-[#f0dfc8]">
                    <th class="px-4 py-3">Pedido</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Unidades</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Envío</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody id="filas"></tbody>
        </table>
    </div>
</div>

<div id="panel" class="hidden fixed inset-0 z-50 bg-black/30 flex justify-end">
    <div id="panelCuerpo" class="w-full max-w-xl h-full overflow-y-auto bg-[#fdfaf7] p-6 shadow-xl"></div>
</div>

<script>
    const base = "<?php echo e(url('/api/admin/gestion-pedidos')); ?>";
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const estado = document.getElementById('estado');
    const tablaCaja = document.getElementById('tablaCaja');
    const filas = document.getElementById('filas');
    const filtro = document.getElementById('filtro');
    const panel = document.getElementById('panel');
    const panelCuerpo = document.getElementById('panelCuerpo');
    const claseInput = 'w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-full px-4 py-2 text-sm text-[#4c3300] focus:outline-none focus:border-[#f2a6a6]';
    const claseBoton = 'rounded-full bg-[#f2a6a6] text-[#4c3300] text-xs font-semibold px-4 py-2 hover:opacity-90 transition-opacity disabled:opacity-50';
    const COLORES = {
        Pendiente: 'bg-yellow-100 text-yellow-900',
        Preparacion: 'bg-blue-100 text-blue-900',
        Enviado: 'bg-purple-100 text-purple-900',
        Entregado: 'bg-green-100 text-green-900',
        Cancelado: 'bg-red-100 text-red-900'
    };
    const ETIQUETAS = { Preparacion: 'Preparación' };

    function crear(tag, clases, texto) {
        const el = document.createElement(tag);
        if (clases) el.className = clases;
        if (texto !== undefined && texto !== null) el.textContent = texto;
        return el;
    }

    function plata(n) {
        return '$' + Number(n || 0).toLocaleString('es-AR');
    }

    function etiqueta(e) {
        return ETIQUETAS[e] || e;
    }

    function chip(e) {
        return crear('span', 'inline-block rounded-full text-xs font-semibold px-3 py-1 ' + (COLORES[e] || 'bg-gray-100 text-gray-800'), etiqueta(e));
    }

    function ruta(url) {
        if (!url) return '';
        if (url.startsWith('http')) return url;
        return '/' + url.replace(/^\/+/, '');
    }

    async function llamar(url, metodo, cuerpo) {
        const opciones = { method: metodo, headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token } };
        if (cuerpo) {
            opciones.headers['Content-Type'] = 'application/json';
            opciones.body = JSON.stringify(cuerpo);
        }
        const respuesta = await fetch(url, opciones);
        let datos = {};
        try { datos = await respuesta.json(); } catch (e) {}
        if (!respuesta.ok) {
            const mensaje = datos.errors ? Object.values(datos.errors)[0][0] : (datos.message || 'Error ' + respuesta.status);
            throw new Error(mensaje);
        }
        return datos;
    }

    function textoAviso(p) {
        if (!p.aviso) return null;
        return p.estado === 'Cancelado' ? 'Cancelado por el cliente' : 'Nuevo';
    }

    function fila(p) {
        const tr = crear('tr', 'border-b border-[#f0dfc8] last:border-0 ' + (p.aviso ? 'bg-[#fff4f4]' : ''));
        tr.appendChild(crear('td', 'px-4 py-3 font-semibold', '#' + p.id));
        tr.appendChild(crear('td', 'px-4 py-3 whitespace-nowrap', p.fecha || ''));
        tr.appendChild(crear('td', 'px-4 py-3', p.cliente || 'Sin nombre'));
        tr.appendChild(crear('td', 'px-4 py-3', String(p.unidades)));
        tr.appendChild(crear('td', 'px-4 py-3 whitespace-nowrap', plata(p.total)));

        const celdaEstado = crear('td', 'px-4 py-3');
        celdaEstado.appendChild(chip(p.estado));
        const aviso = textoAviso(p);
        if (aviso) celdaEstado.appendChild(crear('div', 'text-[10px] font-bold text-[#c0392b] mt-1', aviso));
        tr.appendChild(celdaEstado);

        tr.appendChild(crear('td', 'px-4 py-3', p.envio || '—'));

        const celdaBoton = crear('td', 'px-4 py-3 text-right');
        const ver = crear('button', 'rounded-full bg-[#4c3300] text-[#faf0e6] text-xs font-semibold px-4 py-2 hover:opacity-90', 'Ver');
        ver.addEventListener('click', function () { abrir(p.id); });
        celdaBoton.appendChild(ver);
        tr.appendChild(celdaBoton);
        return tr;
    }

    async function cargarLista() {
        try {
            const url = base + (filtro.value ? '?estado=' + encodeURIComponent(filtro.value) : '');
            const lista = await llamar(url, 'GET');
            filas.replaceChildren();
            if (lista.length === 0) {
                tablaCaja.classList.add('hidden');
                estado.style.display = 'block';
                estado.textContent = 'No hay pedidos para mostrar.';
                return;
            }
            estado.style.display = 'none';
            tablaCaja.classList.remove('hidden');
            lista.forEach(function (p) { filas.appendChild(fila(p)); });
        } catch (e) {
            tablaCaja.classList.add('hidden');
            estado.style.display = 'block';
            estado.textContent = 'No se pudieron cargar los pedidos. (' + e.message + ')';
        }
    }

    function cerrarPanel() {
        panel.classList.add('hidden');
        panelCuerpo.replaceChildren();
    }

    panel.addEventListener('click', function (e) {
        if (e.target === panel) cerrarPanel();
    });

    filtro.addEventListener('change', cargarLista);

    function bloque(titulo) {
        const b = crear('div', 'rounded-2xl bg-white border border-[#f0dfc8] p-4 mt-4');
        b.appendChild(crear('div', 'text-[10px] font-bold tracking-widest uppercase text-[#7a5c3e] mb-2', titulo));
        return b;
    }

    function renglon(b, texto, clases) {
        b.appendChild(crear('div', clases || 'text-sm', texto));
    }

    async function accion(boton, textoEspera, funcion, aviso) {
        const original = boton.textContent;
        boton.disabled = true;
        boton.textContent = textoEspera;
        try {
            await funcion();
        } catch (e) {
            aviso.textContent = e.message;
            aviso.className = 'rounded-2xl px-4 py-3 text-sm mt-4 bg-[#f2a6a6]/40 text-[#4c3300]';
            aviso.scrollIntoView({ behavior: 'smooth', block: 'center' });
            boton.disabled = false;
            boton.textContent = original;
        }
    }

    async function abrir(id, mensaje) {
        panel.classList.remove('hidden');
        if (!mensaje) {
            panelCuerpo.replaceChildren(crear('div', 'text-sm text-[#7a5c3e]', 'Cargando pedido...'));
        }
        try {
            const p = await llamar(base + '/' + id, 'GET');
            dibujar(p, mensaje);
            if (p.aviso) {
                await llamar(base + '/' + id + '/visto', 'POST');
                if (window.actualizarCampanita) window.actualizarCampanita();
                cargarLista();
            }
        } catch (e) {
            panelCuerpo.replaceChildren(crear('div', 'text-sm text-red-700', 'No se pudo cargar el pedido. (' + e.message + ')'));
        }
    }

    async function refrescar(id, mensaje) {
        await cargarLista();
        if (window.actualizarCampanita) window.actualizarCampanita();
        await abrir(id, mensaje);
    }

    function dibujar(p, mensaje) {
        const c = panelCuerpo;
        c.replaceChildren();

        const cabecera = crear('div', 'flex items-start justify-between gap-4');
        const titulo = crear('div');
        titulo.appendChild(crear('h2', 'font-serif text-2xl', 'Pedido #' + p.id));
        titulo.appendChild(crear('div', 'text-xs text-[#7a5c3e] mt-1', p.fecha || ''));
        cabecera.appendChild(titulo);
        const cerrar = crear('button', 'text-3xl leading-none text-[#7a5c3e] hover:text-[#4c3300]', '×');
        cerrar.addEventListener('click', cerrarPanel);
        cabecera.appendChild(cerrar);
        c.appendChild(cabecera);

        const fichaEstado = crear('div', 'mt-3');
        fichaEstado.appendChild(chip(p.estado));
        c.appendChild(fichaEstado);

        const aviso = crear('div', mensaje ? 'rounded-2xl px-4 py-3 text-sm mt-4 bg-[#e4f1df] text-[#2f4a26]' : 'hidden');
        if (mensaje) aviso.textContent = mensaje;
        c.appendChild(aviso);

        const productos = bloque('Productos');
        p.productos.forEach(function (x) {
            const linea = crear('div', 'flex items-center gap-3 py-2 border-b border-[#f0dfc8] last:border-0');
            if (x.imagen) {
                const img = crear('img', 'w-12 h-12 rounded-xl object-cover');
                img.src = ruta(x.imagen);
                img.alt = '';
                linea.appendChild(img);
            }
            const info = crear('div', 'flex-1');
            info.appendChild(crear('div', 'text-sm font-semibold', x.nombre));
            info.appendChild(crear('div', 'text-xs text-[#7a5c3e]', [x.talle, x.color].filter(Boolean).join(' · ') + ' · Stock actual: ' + x.stock));
            linea.appendChild(info);
            linea.appendChild(crear('div', 'text-sm whitespace-nowrap', x.cantidad + ' × ' + plata(x.precio)));
            productos.appendChild(linea);
        });
        const enlaceStock = crear('a', 'inline-block mt-3 text-xs underline underline-offset-4 text-[#7a5c3e] hover:text-[#4c3300]', 'Ajustar stock en Publicaciones');
        enlaceStock.href = '/admin/productos';
        productos.appendChild(enlaceStock);
        c.appendChild(productos);

        const totales = bloque('Totales');
        renglon(totales, 'Subtotal: ' + plata(p.subtotal));
        if (p.descuento > 0) renglon(totales, 'Descuento: -' + plata(p.descuento));
        renglon(totales, 'Envío: ' + (p.costo_envio > 0 ? plata(p.costo_envio) : 'Gratis'));
        renglon(totales, 'Total: ' + plata(p.total), 'text-sm font-bold mt-1');
        c.appendChild(totales);

        const cliente = bloque('Cliente');
        renglon(cliente, p.cliente.nombre || 'Sin nombre', 'text-sm font-semibold');
        if (p.cliente.email) renglon(cliente, p.cliente.email);
        if (p.cliente.telefono) renglon(cliente, 'Tel: ' + p.cliente.telefono);
        if (p.cliente.documento) renglon(cliente, 'DNI / CUIT: ' + p.cliente.documento);
        c.appendChild(cliente);

        const entrega = bloque('Entrega');
        if (p.destinatario) {
            renglon(entrega, 'Recibe: ' + p.destinatario.nombre + (p.destinatario.telefono ? ' · ' + p.destinatario.telefono : ''), 'text-sm font-semibold');
        }
        if (p.direccion) {
            renglon(entrega, p.direccion.calle || '');
            const resto = [p.direccion.barrio, p.direccion.ciudad, p.direccion.provincia, p.direccion.codigo_postal].filter(Boolean).join(', ');
            if (resto) renglon(entrega, resto);
        } else {
            renglon(entrega, 'Sin dirección guardada.', 'text-sm text-[#7a5c3e]');
        }
        c.appendChild(entrega);

        const pago = bloque('Pago y factura');
        if (p.pago) {
            renglon(pago, p.pago.metodo + ' · ' + p.pago.estado + ' · ' + plata(p.pago.monto));
        } else {
            renglon(pago, 'Sin pago registrado.', 'text-sm text-[#7a5c3e]');
        }
        if (p.factura) {
            const enlace = crear('a', 'inline-block mt-2 text-xs underline underline-offset-4 text-[#7a5c3e] hover:text-[#4c3300]', 'Ver factura');
            enlace.href = '/factura/' + p.factura;
            enlace.target = '_blank';
            pago.appendChild(enlace);
        } else {
            renglon(pago, 'Este pedido no tiene factura.', 'text-xs text-[#7a5c3e] mt-1');
        }
        c.appendChild(pago);

        const cancelado = p.estado === 'Cancelado';

        const envio = bloque('Envío');
        if (p.envio) {
            renglon(envio, 'Estado del envío: ' + (p.envio.estado || '—') + (p.envio.despacho ? ' · Despachado el ' + p.envio.despacho : ''), 'text-xs text-[#7a5c3e] mb-2');
        }
        if (cancelado) {
            renglon(envio, 'El pedido está cancelado.', 'text-sm text-[#7a5c3e]');
        } else {
            const empresa = crear('input', claseInput + ' mb-2');
            empresa.type = 'text';
            empresa.maxLength = 100;
            empresa.placeholder = 'Empresa de envío';
            empresa.value = p.envio ? (p.envio.empresa || '') : '';
            const tracking = crear('input', claseInput + ' mb-3');
            tracking.type = 'text';
            tracking.maxLength = 100;
            tracking.placeholder = 'Número de seguimiento (opcional)';
            tracking.value = p.envio ? (p.envio.seguimiento || '') : '';
            const guardar = crear('button', claseBoton, 'Guardar envío');
            guardar.addEventListener('click', function () {
                accion(guardar, 'Guardando...', async function () {
                    const r = await llamar(base + '/' + p.id + '/envio', 'POST', { empresa: empresa.value, tracking: tracking.value });
                    await refrescar(p.id, r.message);
                }, aviso);
            });
            envio.appendChild(empresa);
            envio.appendChild(tracking);
            envio.appendChild(guardar);
        }
        c.appendChild(envio);

        if (!cancelado) {
            const cambio = bloque('Estado del pedido');
            const select = crear('select', claseInput + ' mb-3');
            ['Pendiente', 'Preparacion', 'Enviado', 'Entregado'].forEach(function (e) {
                const op = crear('option', null, etiqueta(e));
                op.value = e;
                if (e === p.estado) op.selected = true;
                select.appendChild(op);
            });
            const guardarEstado = crear('button', claseBoton, 'Guardar estado');
            guardarEstado.addEventListener('click', function () {
                accion(guardarEstado, 'Guardando...', async function () {
                    const r = await llamar(base + '/' + p.id + '/estado', 'POST', { estado: select.value });
                    await refrescar(p.id, r.message);
                }, aviso);
            });
            cambio.appendChild(select);
            cambio.appendChild(guardarEstado);
            c.appendChild(cambio);
        }

        if (p.estado !== 'Cancelado' && p.estado !== 'Entregado') {
            const botonCancelar = crear('button', 'mt-6 w-full rounded-full border border-[#c0392b] text-[#c0392b] text-xs font-semibold px-4 py-3 hover:bg-[#c0392b] hover:text-white transition-colors disabled:opacity-50', 'Cancelar pedido');
            botonCancelar.addEventListener('click', async function () {
                const textoConfirmar = '¿Cancelar el pedido #' + p.id + '? El stock vuelve a las publicaciones y el cliente lo va a ver cancelado.';
                const confirmado = typeof kiddyConfirmar === 'function'
                    ? await kiddyConfirmar(textoConfirmar, { titulo: 'Cancelar pedido', confirmar: 'Sí, cancelar', cancelar: 'Volver', peligro: true })
                    : window.confirm(textoConfirmar);
                if (!confirmado) return;
                accion(botonCancelar, 'Cancelando...', async function () {
                    const r = await llamar(base + '/' + p.id + '/cancelar', 'POST');
                    await refrescar(p.id, r.message);
                }, aviso);
            });
            c.appendChild(botonCancelar);
        }
    }

    cargarLista();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/admin/pedidos.blade.php ENDPATH**/ ?>