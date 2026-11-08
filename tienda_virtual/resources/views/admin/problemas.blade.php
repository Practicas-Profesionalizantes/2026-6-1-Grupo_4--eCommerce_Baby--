@extends('admin.layout')

@section('titulo', 'Problemas del cliente')

@section('contenido')
<div class="max-w-4xl">
    <h1 class="font-serif text-3xl mb-2">Problemas del cliente</h1>
    <p class="text-sm text-[#7a5c3e] mb-6">Acá llegan los reportes que te mandan los clientes desde Soporte y podés responderles.</p>

    <div id="estado" class="rounded-2xl bg-white/80 border border-[#f0dfc8] p-6 text-sm text-[#7a5c3e]">Cargando reportes...</div>
    <div id="lista" class="flex flex-col gap-4"></div>
</div>

<script>
    const urlApi = "{{ url('/api/admin/reportes') }}";
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const estado = document.getElementById('estado');
    const lista = document.getElementById('lista');

    function crear(tag, clases, texto) {
        const el = document.createElement(tag);
        if (clases) el.className = clases;
        if (texto !== undefined) el.textContent = texto;
        return el;
    }

    function formularioRespuesta(r) {
        const contenedor = crear('div', 'mt-4 flex flex-col gap-2');
        const campo = crear('textarea', 'w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-2xl px-4 py-3 text-sm text-[#4c3300] focus:outline-none focus:border-[#f2a6a6] resize-none');
        campo.rows = 3;
        campo.maxLength = 2000;
        campo.placeholder = 'Escribí tu respuesta para el cliente...';
        const error = crear('div', 'text-xs text-red-700');
        const boton = crear('button', 'self-start rounded-full bg-[#f2a6a6] text-[#4c3300] text-xs font-semibold px-4 py-2 hover:opacity-90 transition-opacity', 'Enviar respuesta');
        boton.addEventListener('click', function () { responder(r.Id_reporte, campo, boton, error); });
        contenedor.appendChild(campo);
        contenedor.appendChild(error);
        contenedor.appendChild(boton);
        return contenedor;
    }

    function tarjeta(r) {
        const noLeido = Number(r.Leido) === 0;
        const caja = crear('div', 'rounded-2xl border p-5 shadow-sm ' + (noLeido ? 'bg-white border-[#f2a6a6]' : 'bg-white/60 border-[#f0dfc8]'));

        const arriba = crear('div', 'flex items-start justify-between gap-4 mb-2');
        const izq = crear('div');
        const titulo = crear('div', 'flex items-center gap-2');
        titulo.appendChild(crear('span', 'font-semibold text-lg', r.Asunto));
        if (noLeido) titulo.appendChild(crear('span', 'rounded-full bg-[#f2a6a6] text-[#4c3300] text-xs font-semibold px-2 py-0.5', 'Nuevo'));
        izq.appendChild(titulo);

        const nombre = ((r.Nombre || '') + ' ' + (r.Apellido || '')).trim();
        const quien = (nombre ? nombre + ' · ' : '') + (r.email || 'sin mail');
        izq.appendChild(crear('div', 'text-xs text-[#7a5c3e] mt-1', quien));
        arriba.appendChild(izq);

        arriba.appendChild(crear('div', 'text-xs text-[#7a5c3e] whitespace-nowrap', r.Fecha_creacion || ''));
        caja.appendChild(arriba);

        caja.appendChild(crear('p', 'text-sm whitespace-pre-line mt-3', r.Mensaje));

        if (r.Respuesta) {
            const resp = crear('div', 'mt-4 rounded-2xl bg-[#faf0e6] border border-[#f0dfc8] p-4');
            resp.appendChild(crear('div', 'text-[10px] font-bold tracking-widest uppercase text-[#7a5c3e] mb-1', 'Tu respuesta · ' + (r.Fecha_respuesta || '')));
            resp.appendChild(crear('p', 'text-sm whitespace-pre-line', r.Respuesta));
            caja.appendChild(resp);
        } else {
            caja.appendChild(formularioRespuesta(r));
        }

        const pie = crear('div', 'mt-4 flex items-center justify-between gap-4');
        if (noLeido) {
            const boton = crear('button', 'rounded-full bg-[#4c3300] text-[#faf0e6] text-xs font-semibold px-4 py-2 hover:opacity-90 transition-opacity', '✓ Marcar como leído');
            boton.addEventListener('click', function () { marcar(r.Id_reporte, boton); });
            pie.appendChild(boton);
        } else {
            pie.appendChild(crear('div', 'text-xs text-[#7a5c3e]', '✓ Leído'));
        }

        const eliminar = crear('button', 'text-xs font-semibold text-[#7a5c3e] hover:text-red-700 underline underline-offset-4 transition-colors disabled:opacity-50', 'Eliminar');
        eliminar.type = 'button';
        eliminar.addEventListener('click', function () { eliminarReporte(r.Id_reporte, eliminar); });
        pie.appendChild(eliminar);
        caja.appendChild(pie);

        return caja;
    }

    async function cargar() {
        try {
            const respuesta = await fetch(urlApi, { headers: { 'Accept': 'application/json' } });
            if (!respuesta.ok) throw new Error('Error ' + respuesta.status);
            const reportes = await respuesta.json();
            lista.innerHTML = '';
            if (reportes.length === 0) {
                estado.style.display = 'block';
                estado.textContent = 'Todavía no hay reportes.';
                return;
            }
            estado.style.display = 'none';
            reportes.forEach(function (r) { lista.appendChild(tarjeta(r)); });
        } catch (e) {
            estado.style.display = 'block';
            estado.textContent = 'No se pudieron cargar los reportes. (' + e.message + ')';
        }
    }

    async function marcar(id, boton) {
        boton.disabled = true;
        boton.textContent = 'Guardando...';
        try {
            const respuesta = await fetch(urlApi + '/' + id + '/leido', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            if (!respuesta.ok) throw new Error('Error ' + respuesta.status);
            window.location.reload();
        } catch (e) {
            boton.disabled = false;
            boton.textContent = 'Error, probá de nuevo';
        }
    }

    async function eliminarReporte(id, boton) {
        if (!window.confirm('¿Eliminar este reporte de tu bandeja? El cliente va a seguir viendo su mensaje.')) return;

        boton.disabled = true;
        boton.textContent = 'Eliminando...';
        try {
            const respuesta = await fetch(urlApi + '/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            if (!respuesta.ok) throw new Error('Error ' + respuesta.status);
            await cargar();
            if (window.actualizarCampanita) window.actualizarCampanita();
        } catch (e) {
            boton.disabled = false;
            boton.textContent = 'Eliminar';
            window.alert('No se pudo eliminar el reporte. Probá de nuevo.');
        }
    }

    async function responder(id, campo, boton, error) {
        error.textContent = '';
        boton.disabled = true;
        boton.textContent = 'Enviando...';
        try {
            const respuesta = await fetch(urlApi + '/' + id + '/responder', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ respuesta: campo.value })
            });
            const datos = await respuesta.json();
            if (!respuesta.ok) {
                const primero = datos.errors ? Object.values(datos.errors)[0][0] : (datos.message || 'Error ' + respuesta.status);
                throw new Error(primero);
            }
            window.location.reload();
        } catch (e) {
            error.textContent = e.message;
            boton.disabled = false;
            boton.textContent = 'Enviar respuesta';
        }
    }

    cargar();
</script>
@endsection