@extends('admin.layout')

@section('titulo', 'Productos')

@section('contenido')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h1 class="font-serif text-4xl">Productos</h1>
        <a href="{{ route('admin.productos.crear') }}" class="inline-block rounded-full bg-[#4c3300] hover:opacity-90 text-[#faf0e6] px-6 py-3 text-xs font-bold tracking-widest uppercase text-center transition-opacity">Agregar producto</a>
    </div>

    <input id="buscador" type="search" placeholder="Buscar producto" class="w-full rounded-full border border-[#f0dfc8] bg-white/90 px-6 py-3 text-sm outline-none focus:border-[#f2a6a6] mb-8">

    <p id="mensaje" class="hidden text-sm text-[#7a5c3e] mb-6"></p>

    <div id="grilla" class="grid grid-cols-2 lg:grid-cols-4 gap-6"></div>
</div>

<script>
(function () {
    const grilla = document.getElementById('grilla');
    const mensaje = document.getElementById('mensaje');
    const buscador = document.getElementById('buscador');
    const token = document.querySelector('meta[name="csrf-token"]').content;
    let temporizador = null;

    function el(etiqueta, clases, texto) {
        const nodo = document.createElement(etiqueta);
        if (clases) nodo.className = clases;
        if (texto !== undefined && texto !== null) nodo.textContent = texto;
        return nodo;
    }

    function pesos(numero) {
        return '$' + Number(numero).toLocaleString('es-AR', { maximumFractionDigits: 0 });
    }

    function ruta(url) {
        if (!url) return '';
        if (url.startsWith('http')) return url;
        return '/' + url.replace(/^\/+/, '');
    }

    function avisar(texto) {
        grilla.replaceChildren();
        mensaje.textContent = texto;
        mensaje.classList.remove('hidden');
    }

    function cambiarVisibilidad(id, boton) {
        boton.disabled = true;
        fetch('/api/admin/productos/' + id + '/visibilidad', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
        })
        .then(function (r) {
            if (!r.ok) throw new Error('error');
            return r.json();
        })
        .then(function () { cargar(); })
        .catch(function () {
            boton.disabled = false;
            alert('No se pudo cambiar la visibilidad. Probá de nuevo.');
        });
    }

    function tarjeta(p) {
        const activo = Number(p.Activo) === 1;
        const caja = el('div', 'bg-white/90 border border-[#f0dfc8] rounded-3xl p-4 flex flex-col gap-3 shadow-sm ' + (activo ? '' : 'opacity-70'));

        const marco = el('div', 'relative aspect-[3/4] w-full bg-[#faebd7] rounded-2xl overflow-hidden flex items-center justify-center');
        const foto = ruta(p.Imagen);
        if (foto) {
            const img = el('img', 'w-full h-full object-cover');
            img.src = foto;
            img.alt = p.Nombre;
            marco.appendChild(img);
        } else {
            marco.appendChild(el('span', 'text-xs italic text-[#7a5c3e]/60', 'Sin foto'));
        }
        if (!activo) {
            marco.appendChild(el('span', 'absolute top-3 left-3 bg-[#4c3300] text-[#faf0e6] text-[10px] font-bold tracking-widest uppercase px-3 py-1 rounded-full', 'Oculto'));
        }
        caja.appendChild(marco);

        const datos = el('div', 'flex flex-col gap-1');
        datos.appendChild(el('h3', 'text-sm font-bold', p.Nombre));
        const detalle = (p.Categoria || 'Sin categoría') + ' · Desde ' + (p.Precio_Desde === null ? '-' : pesos(p.Precio_Desde));
        datos.appendChild(el('p', 'text-xs text-[#7a5c3e]', detalle));
        datos.appendChild(el('p', 'text-xs text-[#7a5c3e]', 'Stock: ' + p.Stock_Total));
        caja.appendChild(datos);

        const acciones = el('div', 'flex gap-2 mt-auto');
        const editar = el('a', 'flex-1 text-center rounded-full bg-[#faf0e6] border border-[#f0dfc8] hover:bg-[#f2a6a6]/40 px-3 py-2 text-xs font-semibold transition-colors', 'Editar');
        editar.href = '/admin/productos/' + p.Id_producto + '/editar';
        const visibilidad = el('button', 'flex-1 rounded-full bg-[#4c3300] hover:opacity-90 text-[#faf0e6] px-3 py-2 text-xs font-semibold transition-opacity cursor-pointer disabled:opacity-50', activo ? 'Ocultar' : 'Mostrar');
        visibilidad.type = 'button';
        visibilidad.addEventListener('click', function () { cambiarVisibilidad(p.Id_producto, visibilidad); });
        acciones.appendChild(editar);
        acciones.appendChild(visibilidad);
        caja.appendChild(acciones);

        return caja;
    }

    function cargar() {
        const q = buscador.value.trim();
        fetch('/api/admin/productos?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
            .then(function (r) {
                if (!r.ok) throw new Error('error');
                return r.json();
            })
            .then(function (productos) {
                grilla.replaceChildren();
                if (!Array.isArray(productos) || productos.length === 0) {
                    avisar(q ? 'No hay productos que coincidan con la búsqueda.' : 'Todavía no hay productos cargados.');
                    return;
                }
                mensaje.classList.add('hidden');
                productos.forEach(function (p) { grilla.appendChild(tarjeta(p)); });
            })
            .catch(function () { avisar('No se pudieron cargar los productos.'); });
    }

    buscador.addEventListener('input', function () {
        clearTimeout(temporizador);
        temporizador = setTimeout(cargar, 300);
    });

    cargar();
})();
</script>
@endsection