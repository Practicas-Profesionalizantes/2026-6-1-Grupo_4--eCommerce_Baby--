

<?php $__env->startSection('content'); ?>
<div class="py-16 px-6 sm:px-12">
    <div class="max-w-4xl mx-auto">

        <div class="mb-10 text-center md:text-left">
            <span class="text-[10px] font-bold tracking-[0.3em] text-[#d98c8c] uppercase mb-2 block">Panel de Usuario</span>
            <h1 class="font-serif text-4xl sm:text-5xl text-[#4c3300]">Mis Pedidos</h1>
            <p class="text-sm text-[#7a5c3e] mt-2">Seguí el estado de tus compras.</p>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-8 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] px-5 py-4 text-sm text-[#4c3300]">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div id="mensaje" class="hidden mb-8 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] px-5 py-4 text-sm text-[#4c3300]"></div>

        <div id="cargando" class="text-sm text-[#7a5c3e]">Cargando tus pedidos...</div>

        <div id="lista" class="flex flex-col gap-6"></div>

        <div id="vacio" class="hidden bg-white rounded-[2.5rem] p-10 border border-[#f0dfc8] text-center shadow-xl shadow-[#4c3300]/5">
            <p class="font-serif text-2xl text-[#4c3300] mb-2">Todavía no tenés pedidos</p>
            <p class="text-sm text-[#7a5c3e] mb-6">Cuando hagas tu primera compra la vas a ver acá.</p>
            <a href="/catalogo" class="inline-block bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-10 py-4 rounded-full transition-all duration-300">
                Ver catálogo
            </a>
        </div>

        <div class="mt-10">
            <a href="<?php echo e(route('cuenta.index')); ?>" class="text-xs font-bold tracking-widest uppercase text-[#4c3300] border-b border-[#4c3300] pb-1 hover:text-[#d98c8c] hover:border-[#d98c8c] transition-colors">
                Volver a Mi Cuenta
            </a>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const lista = document.getElementById('lista');
    const vacio = document.getElementById('vacio');
    const cargando = document.getElementById('cargando');
    const mensaje = document.getElementById('mensaje');
    const token = '<?php echo e(csrf_token()); ?>';
    const facturas = {};

    const estados = {
        Pendiente: { texto: 'Pendiente', clases: 'bg-[#faebd7] text-[#7a5c3e]' },
        Preparacion: { texto: 'En preparación', clases: 'bg-[#f2a6a6]/40 text-[#4c3300]' },
        Enviado: { texto: 'Enviado', clases: 'bg-[#d98c8c] text-white' },
        Entregado: { texto: 'Entregado', clases: 'bg-[#4c3300] text-white' },
        Cancelado: { texto: 'Cancelado', clases: 'bg-[#f0dfc8] text-[#7a5c3e]' }
    };

    function pesos(numero) {
        return '$ ' + Number(numero).toLocaleString('es-AR', { maximumFractionDigits: 0 });
    }

    function fechaLinda(texto) {
        if (!texto) return '';
        const fecha = new Date(texto.replace(' ', 'T'));
        if (isNaN(fecha)) return texto;
        return fecha.toLocaleString('es-AR', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function el(etiqueta, clases, texto) {
        const nodo = document.createElement(etiqueta);
        if (clases) nodo.className = clases;
        if (texto !== undefined && texto !== null) nodo.textContent = texto;
        return nodo;
    }

    function rutaImagen(url) {
        if (!url) return '';
        if (url.startsWith('http')) return url;
        return '/' + url.replace(/^\/+/, '');
    }

    function mostrarMensaje(texto) {
        mensaje.textContent = texto;
        mensaje.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function filaTotal(etiqueta, valor, fuerte) {
        const fila = el('div', 'flex justify-between ' + (fuerte ? 'font-bold text-base text-[#4c3300]' : 'text-[#7a5c3e]'));
        fila.appendChild(el('span', '', etiqueta));
        fila.appendChild(el('span', '', valor));
        return fila;
    }

    function bloque(titulo, lineas) {
        const caja = el('div', 'flex flex-col gap-1');
        caja.appendChild(el('p', 'text-[10px] font-bold tracking-widest text-[#d98c8c] uppercase mb-1', titulo));
        lineas.forEach(function (linea) {
            caja.appendChild(el('p', 'text-sm text-[#4c3300]', linea));
        });
        return caja;
    }

    function filaProducto(prod) {
        const fila = el('div', 'flex items-center gap-4');
        const foto = rutaImagen(prod.imagen);

        if (foto) {
            const img = el('img', 'w-16 h-16 object-cover rounded-xl border border-[#f0dfc8] shrink-0');
            img.src = foto;
            img.alt = prod.nombre;
            fila.appendChild(img);
        } else {
            fila.appendChild(el('div', 'w-16 h-16 rounded-xl bg-[#faf0e6] border border-[#f0dfc8] shrink-0'));
        }

        const datos = el('div', 'flex-1 min-w-0');
        datos.appendChild(el('p', 'text-sm font-bold text-[#4c3300]', prod.nombre));
        datos.appendChild(el('p', 'text-xs text-[#7a5c3e]', 'Talle ' + prod.talle + ' · ' + prod.color));
        datos.appendChild(el('p', 'text-xs text-[#7a5c3e]', prod.cantidad + ' × ' + pesos(prod.precio)));
        fila.appendChild(datos);

        fila.appendChild(el('p', 'text-sm font-bold text-[#4c3300]', pesos(prod.cantidad * prod.precio)));
        return fila;
    }

    function lineasEnvio(p) {
        if (!p.envio) return ['Sin información'];
        const lineas = [p.envio.empresa];
        if (p.envio.estado) lineas.push(p.envio.estado);
        if (p.envio.seguimiento) lineas.push('Seguimiento: ' + p.envio.seguimiento);
        return lineas;
    }

    function lineasPago(p) {
        if (!p.pago) return ['Sin información'];
        return [p.pago.metodo, p.pago.estado];
    }

    function lineasDireccion(p) {
        if (!p.direccion) return ['Sin información'];
        const lineas = [];
        if (p.direccion.calle) lineas.push(p.direccion.calle);
        if (p.direccion.barrio) lineas.push('Barrio ' + p.direccion.barrio);
        const lugar = [p.direccion.ciudad, p.direccion.provincia].filter(Boolean).join(', ');
        if (lugar) lineas.push(lugar);
        if (p.direccion.codigo_postal) lineas.push('CP ' + p.direccion.codigo_postal);
        return lineas.length ? lineas : ['Sin información'];
    }

    function lineasDestinatario(p) {
        const d = p.destinatario;
        const lineas = [[d.nombre, d.apellido].filter(Boolean).join(' ')];
        if (d.telefono) lineas.push('Tel. ' + d.telefono);
        return lineas;
    }

    function sePuedeCancelar(p) {
        return p.estado === 'Pendiente' || p.estado === 'Preparacion';
    }

    async function cancelarPedido(p, boton) {
        const textoConfirmar = '¿Querés cancelar el pedido #' + p.id + '? Esta acción no se puede deshacer.';
        const seguro = typeof kiddyConfirmar === 'function'
            ? await kiddyConfirmar(textoConfirmar, { titulo: 'Cancelar pedido', confirmar: 'Sí, cancelar', cancelar: 'Volver', peligro: true })
            : window.confirm(textoConfirmar);
        if (!seguro) return;

        const textoOriginal = boton.textContent;
        boton.disabled = true;
        boton.textContent = 'Cancelando...';

        try {
            const respuesta = await fetch('/api/mis-pedidos/' + p.id + '/cancelar', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            let datos = {};
            try {
                datos = await respuesta.json();
            } catch (error) {
                datos = {};
            }

            if (respuesta.status === 401) {
                window.location.href = '/login';
                return;
            }

            if (!respuesta.ok) {
                const aviso = datos.message || datos.error || datos.mensaje || ('No se pudo cancelar el pedido. (error ' + respuesta.status + ')');
                boton.disabled = false;
                boton.textContent = textoOriginal;
                mostrarMensaje(aviso);
                return;
            }

            mostrarMensaje('Cancelamos tu pedido #' + p.id + '.');
            await cargarPedidos();
        } catch (error) {
            boton.disabled = false;
            boton.textContent = textoOriginal;
            mostrarMensaje('No se pudo cancelar el pedido. (' + error.message + ')');
        }
    }

    function tarjeta(p) {
        const estado = estados[p.estado] || { texto: p.estado || '', clases: 'bg-[#faebd7] text-[#7a5c3e]' };

        const caja = el('article', 'bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-[#4c3300]/5 border border-[#f0dfc8] relative overflow-hidden');
        caja.appendChild(el('div', 'absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#faebd7] via-[#f2a6a6] to-[#d98c8c]'));

        const cabecera = el('div', 'flex flex-wrap items-start justify-between gap-4 mt-2 mb-6 pb-6 border-b border-[#faf0e6]');
        const titulo = el('div');
        titulo.appendChild(el('h2', 'font-serif text-2xl text-[#4c3300]', 'Pedido #' + p.id));
        titulo.appendChild(el('p', 'text-xs text-[#7a5c3e] mt-1', fechaLinda(p.fecha)));
        cabecera.appendChild(titulo);
        cabecera.appendChild(el('span', 'text-[10px] font-bold tracking-widest uppercase px-4 py-2 rounded-full ' + estado.clases, estado.texto));
        caja.appendChild(cabecera);

        const productos = el('div', 'flex flex-col gap-4 mb-6');
        p.productos.forEach(function (prod) {
            productos.appendChild(filaProducto(prod));
        });
        caja.appendChild(productos);

        const totales = el('div', 'flex flex-col gap-2 text-sm pt-6 border-t border-[#faf0e6]');
        totales.appendChild(filaTotal('Subtotal', pesos(p.subtotal), false));
        if (p.descuento > 0) {
            totales.appendChild(filaTotal('Descuento', '- ' + pesos(p.descuento), false));
        }
        totales.appendChild(filaTotal('Envío', p.costo_envio > 0 ? pesos(p.costo_envio) : 'Gratis', false));
        totales.appendChild(filaTotal('Total', pesos(p.total), true));
        caja.appendChild(totales);

        const detalle = el('div', 'grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pt-6 border-t border-[#faf0e6]');
        detalle.appendChild(bloque('Envío', lineasEnvio(p)));
        detalle.appendChild(bloque('Pago', lineasPago(p)));
        detalle.appendChild(bloque('Dirección', lineasDireccion(p)));
        if (p.destinatario) {
            detalle.appendChild(bloque('Recibe', lineasDestinatario(p)));
        }
        caja.appendChild(detalle);

        const puedeCancelar = sePuedeCancelar(p);
        const tieneFactura = Boolean(facturas[p.id]);

        if (puedeCancelar || tieneFactura) {
            const pie = el('div', 'flex flex-wrap justify-end gap-3 mt-6 pt-6 border-t border-[#faf0e6]');

            if (puedeCancelar) {
                const cancelar = el('button', 'inline-block bg-white hover:bg-[#faf0e6] text-[#4c3300] border border-[#4c3300] text-xs font-bold tracking-widest uppercase px-8 py-3 rounded-full transition-all duration-300 disabled:opacity-50', 'Cancelar pedido');
                cancelar.type = 'button';
                cancelar.addEventListener('click', function () {
                    cancelarPedido(p, cancelar);
                });
                pie.appendChild(cancelar);
            }

            if (tieneFactura) {
                const enlace = el('a', 'inline-block bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-8 py-3 rounded-full transition-all duration-300', 'Ver factura');
                enlace.href = '/factura/' + facturas[p.id];
                pie.appendChild(enlace);
            }

            caja.appendChild(pie);
        }

        return caja;
    }

    async function cargarFacturas() {
        try {
            const respuesta = await fetch('/api/mis-facturas', {
                headers: { 'Accept': 'application/json' }
            });
            if (!respuesta.ok) return;
            const filas = await respuesta.json();
            filas.forEach(function (f) {
                facturas[f.pedido] = f.factura;
            });
        } catch (error) {
            return;
        }
    }

    async function cargarPedidos() {
        try {
            const respuesta = await fetch('/api/mis-pedidos', {
                headers: { 'Accept': 'application/json' }
            });

            if (respuesta.status === 401) {
                window.location.href = '/login';
                return;
            }
            if (!respuesta.ok) throw new Error('error ' + respuesta.status);

            const pedidos = await respuesta.json();
            cargando.classList.add('hidden');
            lista.innerHTML = '';

            if (pedidos.length === 0) {
                vacio.classList.remove('hidden');
                return;
            }

            await cargarFacturas();

            pedidos.forEach(function (p) {
                lista.appendChild(tarjeta(p));
            });
        } catch (error) {
            cargando.classList.add('hidden');
            mostrarMensaje('No se pudieron cargar tus pedidos. (' + error.message + ')');
        }
    }

    cargarPedidos();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/cuenta/pedidos.blade.php ENDPATH**/ ?>