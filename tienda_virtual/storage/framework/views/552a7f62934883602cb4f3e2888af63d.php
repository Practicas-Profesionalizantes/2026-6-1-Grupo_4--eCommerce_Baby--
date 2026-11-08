

<?php $__env->startSection('titulo', 'Facturas'); ?>

<?php $__env->startSection('contenido'); ?>
<div class="max-w-5xl">
    <h1 class="font-serif text-3xl mb-2">Facturas</h1>
    <p class="text-sm text-[#7a5c3e] mb-6">Acá aparece una factura por cada compra. Podés marcarlas como leídas y abrir cada una para verla o imprimirla.</p>

    <div id="estado" class="rounded-2xl bg-white/80 border border-[#f0dfc8] p-6 text-sm text-[#7a5c3e]">Cargando facturas...</div>
    <div id="lista" class="flex flex-col gap-4"></div>
</div>

<script>
    const urlApi = "<?php echo e(url('/api/admin/facturas')); ?>";
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const estado = document.getElementById('estado');
    const lista = document.getElementById('lista');

    function crear(tag, clases, texto) {
        const el = document.createElement(tag);
        if (clases) el.className = clases;
        if (texto !== undefined && texto !== null) el.textContent = texto;
        return el;
    }

    function pesos(numero) {
        return '$ ' + Number(numero || 0).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function fechaCorta(texto) {
        if (!texto) return '';
        const partes = String(texto).substring(0, 10).split('-');
        if (partes.length !== 3) return texto;
        return partes[2] + '/' + partes[1] + '/' + partes[0];
    }

    function tarjeta(f) {
        const noLeida = Number(f.Leida) === 0;
        const caja = crear('div', 'rounded-2xl border p-5 shadow-sm ' + (noLeida ? 'bg-white border-[#f2a6a6]' : 'bg-white/60 border-[#f0dfc8]'));

        const arriba = crear('div', 'flex items-start justify-between gap-4 mb-2');
        const izq = crear('div');
        const titulo = crear('div', 'flex items-center gap-2');
        titulo.appendChild(crear('span', 'font-semibold text-lg', 'Factura ' + f.Tipo_Comprobante + ' N° ' + f.Nro_Comprobante));
        if (noLeida) titulo.appendChild(crear('span', 'rounded-full bg-[#f2a6a6] text-[#4c3300] text-xs font-semibold px-2 py-0.5', 'Nueva'));
        izq.appendChild(titulo);

        const nombre = ((f.Nombre || '') + ' ' + (f.Apellido || '')).trim();
        const quien = (nombre ? nombre + ' · ' : '') + (f.Mail || 'sin mail');
        izq.appendChild(crear('div', 'text-xs text-[#7a5c3e] mt-1', quien));
        arriba.appendChild(izq);

        arriba.appendChild(crear('div', 'text-xs text-[#7a5c3e] whitespace-nowrap', fechaCorta(f.Fecha_Emision)));
        caja.appendChild(arriba);

        const datos = crear('div', 'mt-3 flex flex-wrap gap-x-8 gap-y-1 text-sm');
        datos.appendChild(crear('span', '', 'Pedido #' + f.Id_pedido));
        datos.appendChild(crear('span', 'font-bold', 'Total: ' + pesos(f.Total_Final)));
        caja.appendChild(datos);

        const botones = crear('div', 'mt-4 flex flex-wrap items-center gap-3');

        const ver = crear('a', 'rounded-full bg-[#f2a6a6] text-[#4c3300] text-xs font-semibold px-4 py-2 hover:opacity-90 transition-opacity', 'Ver factura');
        ver.href = '/factura/' + f.Id_factura;
        botones.appendChild(ver);

        if (noLeida) {
            const boton = crear('button', 'rounded-full bg-[#4c3300] text-[#faf0e6] text-xs font-semibold px-4 py-2 hover:opacity-90 transition-opacity', '✓ Marcar leída');
            boton.addEventListener('click', function () { marcar(f.Id_factura, boton); });
            botones.appendChild(boton);
        } else {
            botones.appendChild(crear('span', 'text-xs text-[#7a5c3e]', '✓ Leída'));
        }

        caja.appendChild(botones);
        return caja;
    }

    async function cargar() {
        try {
            const respuesta = await fetch(urlApi, { headers: { 'Accept': 'application/json' } });
            if (!respuesta.ok) throw new Error('Error ' + respuesta.status);
            const facturas = await respuesta.json();
            lista.innerHTML = '';
            if (facturas.length === 0) {
                estado.style.display = 'block';
                estado.textContent = 'Todavía no hay facturas.';
                return;
            }
            estado.style.display = 'none';
            facturas.forEach(function (f) { lista.appendChild(tarjeta(f)); });
        } catch (e) {
            estado.style.display = 'block';
            estado.textContent = 'No se pudieron cargar las facturas. (' + e.message + ')';
        }
    }

    async function marcar(id, boton) {
        boton.disabled = true;
        boton.textContent = 'Guardando...';
        try {
            const respuesta = await fetch(urlApi + '/' + id + '/leida', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            if (!respuesta.ok) throw new Error('Error ' + respuesta.status);
            await cargar();
            if (window.actualizarCampanita) window.actualizarCampanita();
        } catch (e) {
            boton.disabled = false;
            boton.textContent = 'Error, probá de nuevo';
        }
    }

    cargar();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/admin/facturas.blade.php ENDPATH**/ ?>