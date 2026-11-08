

<?php $__env->startSection('content'); ?>
<style>
@media print {
    header, footer, nav, .no-imprimir { display: none !important; }
    body { background: #ffffff !important; }
    #hoja { box-shadow: none !important; border: 0 !important; }
}
</style>
<div class="py-12 px-4 sm:px-12">
    <div class="max-w-3xl mx-auto">

        <div class="no-imprimir flex flex-wrap items-center justify-between gap-4 mb-6">
            <a id="volver" href="/mi-cuenta/pedidos" class="text-xs font-bold tracking-widest uppercase text-[#4c3300] border-b border-[#4c3300] pb-1 hover:text-[#d98c8c] hover:border-[#d98c8c] transition-colors">
                Volver
            </a>
            <button id="imprimir" type="button" class="hidden bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-8 py-3 rounded-full transition-all duration-300">
                Imprimir / Guardar PDF
            </button>
        </div>

        <div id="mensaje" class="hidden mb-8 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] px-5 py-4 text-sm text-[#4c3300]"></div>

        <div id="cargando" class="text-sm text-[#7a5c3e]">Cargando la factura...</div>

        <article id="hoja" class="hidden bg-white rounded-[2rem] p-6 sm:p-10 shadow-xl shadow-[#4c3300]/5 border border-[#f0dfc8] text-[#4c3300]"></article>

    </div>
</div>

<script>
const ID_FACTURA = <?php echo e((int) $id); ?>;

document.addEventListener('DOMContentLoaded', function () {
    const hoja = document.getElementById('hoja');
    const cargando = document.getElementById('cargando');
    const mensaje = document.getElementById('mensaje');
    const botonImprimir = document.getElementById('imprimir');
    const volver = document.getElementById('volver');

    function el(etiqueta, clases, texto) {
        const nodo = document.createElement(etiqueta);
        if (clases) nodo.className = clases;
        if (texto !== undefined && texto !== null) nodo.textContent = texto;
        return nodo;
    }

    function pesos(numero) {
        return '$ ' + Number(numero).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function fechaCorta(texto) {
        if (!texto) return '';
        const partes = String(texto).substring(0, 10).split('-');
        if (partes.length !== 3) return texto;
        return partes[2] + '/' + partes[1] + '/' + partes[0];
    }

    function aviso(texto) {
        cargando.classList.add('hidden');
        mensaje.textContent = texto;
        mensaje.classList.remove('hidden');
    }

    function linea(etiqueta, valor) {
        const p = el('p', 'text-sm');
        p.appendChild(el('span', 'font-bold', etiqueta + ': '));
        p.appendChild(document.createTextNode(valor));
        return p;
    }

    function caja(titulo, lineas) {
        const nodo = el('div', 'flex flex-col gap-1');
        nodo.appendChild(el('p', 'text-[10px] font-bold tracking-widest text-[#d98c8c] uppercase mb-1', titulo));
        lineas.forEach(function (l) {
            if (l) nodo.appendChild(l);
        });
        return nodo;
    }

    function filaTotal(etiqueta, valor, fuerte) {
        const fila = el('div', 'flex justify-between gap-8 ' + (fuerte ? 'font-bold text-lg pt-2 border-t border-[#f0dfc8]' : 'text-sm text-[#7a5c3e]'));
        fila.appendChild(el('span', '', etiqueta));
        fila.appendChild(el('span', '', valor));
        return fila;
    }

    function dibujar(d) {
        volver.href = d.es_admin ? '/admin/facturas' : '/mi-cuenta/pedidos';

        const cabecera = el('div', 'grid grid-cols-1 sm:grid-cols-3 gap-6 items-start pb-6 border-b border-[#f0dfc8]');

        const emisor = el('div', 'flex flex-col gap-1');
        emisor.appendChild(el('h1', 'font-serif text-3xl', d.vendedor.razon_social));
        emisor.appendChild(linea('CUIT', d.vendedor.cuit));
        emisor.appendChild(linea('Condición frente al IVA', d.vendedor.condicion_iva));
        emisor.appendChild(linea('Domicilio', d.vendedor.domicilio));
        cabecera.appendChild(emisor);

        const letra = el('div', 'flex flex-col items-center');
        const marco = el('div', 'w-16 h-16 border-2 border-[#4c3300] rounded-lg flex items-center justify-center font-serif text-4xl', d.comprobante.tipo);
        letra.appendChild(marco);
        letra.appendChild(el('p', 'text-[10px] tracking-widest uppercase mt-2 text-[#7a5c3e]', 'Factura'));
        cabecera.appendChild(letra);

        const datos = el('div', 'flex flex-col gap-1 sm:text-right');
        datos.appendChild(el('p', 'text-sm font-bold', 'N° ' + d.comprobante.numero));
        datos.appendChild(el('p', 'text-sm', 'Fecha de emisión: ' + fechaCorta(d.comprobante.fecha_emision)));
        datos.appendChild(el('p', 'text-sm', 'Pedido #' + d.comprobante.pedido));
        datos.appendChild(el('p', 'text-sm', 'Fecha del pedido: ' + fechaCorta(d.comprobante.fecha_pedido)));
        cabecera.appendChild(datos);
        hoja.appendChild(cabecera);

        const receptor = el('div', 'py-6 border-b border-[#f0dfc8]');
        receptor.appendChild(caja('Cliente', [
            linea('Nombre', d.receptor.nombre),
            linea('DNI / CUIT', d.receptor.documento ? d.receptor.documento : 'No informado'),
            linea('Condición frente al IVA', d.receptor.condicion_iva),
            linea('Email', d.receptor.email),
            d.receptor.direccion ? linea('Dirección de envío', d.receptor.direccion) : null
        ]));
        hoja.appendChild(receptor);

        const contenedorTabla = el('div', 'py-6 border-b border-[#f0dfc8] overflow-x-auto');
        const tabla = el('table', 'w-full text-sm');
        const cabeza = el('thead');
        const filaCabeza = el('tr', 'text-left text-[10px] uppercase tracking-widest text-[#d98c8c]');
        ['Descripción', 'Cant.', 'Precio unit.', 'Subtotal'].forEach(function (t, i) {
            filaCabeza.appendChild(el('th', 'py-2 ' + (i > 0 ? 'text-right' : ''), t));
        });
        cabeza.appendChild(filaCabeza);
        tabla.appendChild(cabeza);

        const cuerpo = el('tbody');
        d.items.forEach(function (it) {
            const fila = el('tr', 'border-t border-[#faf0e6]');
            const desc = el('td', 'py-3');
            desc.appendChild(el('p', 'font-bold', it.nombre));
            desc.appendChild(el('p', 'text-xs text-[#7a5c3e]', 'Talle ' + it.talle + ' · ' + it.color));
            fila.appendChild(desc);
            fila.appendChild(el('td', 'py-3 text-right', String(it.cantidad)));
            fila.appendChild(el('td', 'py-3 text-right', pesos(it.precio)));
            fila.appendChild(el('td', 'py-3 text-right font-bold', pesos(it.subtotal)));
            cuerpo.appendChild(fila);
        });
        tabla.appendChild(cuerpo);
        contenedorTabla.appendChild(tabla);
        hoja.appendChild(contenedorTabla);

        const abajo = el('div', 'grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6');

        const info = el('div', 'flex flex-col gap-4');
        info.appendChild(caja('Envío', [el('p', 'text-sm', d.envio ? d.envio : 'Sin información')]));
        if (d.destinatario) {
            const lineasEntrega = [linea('Recibe', d.destinatario.nombre)];
            if (d.destinatario.telefono) {
                lineasEntrega.push(linea('Teléfono', d.destinatario.telefono));
            }
            info.appendChild(caja('Entrega', lineasEntrega));
        }
        info.appendChild(caja('Pago', [el('p', 'text-sm', d.pago ? d.pago.metodo + ' · ' + d.pago.estado : 'Sin información')]));
        abajo.appendChild(info);

        const totales = el('div', 'flex flex-col gap-2');
        totales.appendChild(filaTotal('Subtotal', pesos(d.totales.subtotal), false));
        if (d.totales.descuento > 0) {
            totales.appendChild(filaTotal('Descuento', '- ' + pesos(d.totales.descuento), false));
        }
        totales.appendChild(filaTotal('Envío', d.totales.envio > 0 ? pesos(d.totales.envio) : 'Gratis', false));
        totales.appendChild(filaTotal('Total', pesos(d.totales.total), true));
        totales.appendChild(filaTotal('IVA contenido (21%)', pesos(d.totales.iva_contenido), false));
        abajo.appendChild(totales);
        hoja.appendChild(abajo);

        hoja.appendChild(el('p', 'text-[11px] text-[#7a5c3e] mt-8 pt-4 border-t border-[#f0dfc8]', 'CAE: pendiente (comprobante simulado, sin validez fiscal).'));

        cargando.classList.add('hidden');
        hoja.classList.remove('hidden');
        botonImprimir.classList.remove('hidden');
    }

    botonImprimir.addEventListener('click', function () {
        window.print();
    });

    async function cargar() {
        try {
            const respuesta = await fetch('/api/comprobante/' + ID_FACTURA, {
                headers: { 'Accept': 'application/json' }
            });

            if (respuesta.status === 401) {
                window.location.href = '/login';
                return;
            }
            if (respuesta.status === 403) {
                aviso('No tenés permiso para ver esta factura.');
                return;
            }
            if (respuesta.status === 404) {
                aviso('No encontramos esta factura.');
                return;
            }
            if (!respuesta.ok) throw new Error('error ' + respuesta.status);

            dibujar(await respuesta.json());
        } catch (error) {
            aviso('No se pudo cargar la factura. (' + error.message + ')');
        }
    }

    cargar();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/factura.blade.php ENDPATH**/ ?>