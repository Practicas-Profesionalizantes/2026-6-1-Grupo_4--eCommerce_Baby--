@extends('admin.layout')

@section('titulo', $id ? 'Editar producto' : 'Agregar producto')

@section('contenido')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-serif text-4xl">{{ $id ? 'Editar producto' : 'Agregar producto' }}</h1>
        <a href="{{ route('admin.productos') }}" class="text-xs font-bold tracking-widest uppercase underline underline-offset-4 hover:text-[#d98c8c] transition-colors">Volver</a>
    </div>

    <p id="aviso" class="hidden rounded-2xl px-5 py-3 text-sm mb-6"></p>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <label for="imagen" class="relative cursor-pointer bg-white/90 border-2 border-dashed border-[#f0dfc8] hover:border-[#f2a6a6] rounded-3xl aspect-square flex items-center justify-center overflow-hidden transition-colors">
            <img id="vista" class="hidden absolute inset-0 w-full h-full object-cover" alt="">
            <div id="textoImagen" class="text-center text-[#7a5c3e]">
                <div class="text-5xl leading-none mb-2">+</div>
                <div class="text-sm">Agregar imagen</div>
                <div class="text-xs mt-1">JPG, PNG o WEBP, hasta 4 MB</div>
            </div>
            <input id="imagen" type="file" accept="image/jpeg,image/png,image/webp" class="hidden">
        </label>

        <div class="bg-white/90 border border-[#f0dfc8] rounded-3xl p-6 flex flex-col">
            <label for="descripcion" class="text-xs font-bold tracking-widest uppercase mb-3">Descripción del producto</label>
            <textarea id="descripcion" maxlength="2000" class="flex-1 min-h-[200px] w-full rounded-2xl border border-[#f0dfc8] bg-[#faf0e6]/60 p-4 text-sm outline-none focus:border-[#f2a6a6] resize-none"></textarea>
        </div>
    </div>

    <div class="bg-white/90 border border-[#f0dfc8] rounded-3xl p-6 mb-6 grid sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-2">
            <label for="nombre" class="text-xs font-bold tracking-widest uppercase">Nombre del producto</label>
            <input id="nombre" type="text" maxlength="100" class="rounded-full border border-[#f0dfc8] bg-[#faf0e6]/60 px-5 py-3 text-sm outline-none focus:border-[#f2a6a6]">
        </div>
        <div class="flex flex-col gap-2">
            <label for="categoria" class="text-xs font-bold tracking-widest uppercase">Categoría</label>
            <select id="categoria" class="rounded-full border border-[#f0dfc8] bg-[#faf0e6]/60 px-5 py-3 text-sm outline-none focus:border-[#f2a6a6]"></select>
        </div>
        <label class="flex items-center gap-3 text-sm sm:col-span-2 cursor-pointer">
            <input id="destacado" type="checkbox" class="w-4 h-4 accent-[#4c3300]">
            Producto destacado (aparece primero en la tienda)
        </label>
    </div>

    <div class="bg-white/90 border border-[#f0dfc8] rounded-3xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-bold tracking-widest uppercase">Variantes (talle, color, precio y stock)</h2>
            <button id="agregarVariante" type="button" class="rounded-full bg-[#faf0e6] border border-[#f0dfc8] hover:bg-[#f2a6a6]/40 px-4 py-2 text-xs font-semibold transition-colors cursor-pointer">Agregar variante</button>
        </div>
        <div id="variantes" class="flex flex-col gap-3"></div>
        <datalist id="talles">
            <option value="0-3 meses">
            <option value="3-6 meses">
            <option value="6-9 meses">
            <option value="Único">
        </datalist>
    </div>

    <button id="guardar" type="button" class="w-full sm:w-auto rounded-full bg-[#4c3300] hover:opacity-90 text-[#faf0e6] px-10 py-4 text-xs font-bold tracking-widest uppercase transition-opacity cursor-pointer disabled:opacity-50">
        {{ $id ? 'Guardar cambios' : 'Agregar producto' }}
    </button>
</div>

<script>
(function () {
    const idProducto = @json($id);
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const aviso = document.getElementById('aviso');
    const contenedor = document.getElementById('variantes');
    const inputImagen = document.getElementById('imagen');
    const vista = document.getElementById('vista');
    const textoImagen = document.getElementById('textoImagen');
    const botonGuardar = document.getElementById('guardar');
    const clasesInput = 'rounded-full border border-[#f0dfc8] bg-[#faf0e6]/60 px-4 py-2 text-sm outline-none focus:border-[#f2a6a6] w-full';

    function el(etiqueta, clases, texto) {
        const nodo = document.createElement(etiqueta);
        if (clases) nodo.className = clases;
        if (texto !== undefined && texto !== null) nodo.textContent = texto;
        return nodo;
    }

    function ruta(url) {
        if (!url) return '';
        if (url.startsWith('http')) return url;
        return '/' + url.replace(/^\/+/, '');
    }

    function mostrarAviso(texto, esError) {
        aviso.textContent = texto;
        aviso.className = 'rounded-2xl px-5 py-3 text-sm mb-6 ' + (esError ? 'bg-[#f2a6a6]/40 text-[#4c3300]' : 'bg-[#e4f1df] text-[#2f4a26]');
        aviso.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function campo(titulo, tipo, valor, extra) {
        const caja = el('div', 'flex flex-col gap-1');
        caja.appendChild(el('span', 'text-[10px] font-bold tracking-widest uppercase text-[#7a5c3e]', titulo));
        const input = el('input', clasesInput);
        input.type = tipo;
        input.value = valor === null || valor === undefined ? '' : valor;
        if (extra) Object.keys(extra).forEach(function (k) { input.setAttribute(k, extra[k]); });
        caja.appendChild(input);
        return { caja: caja, input: input };
    }

    function agregarVariante(datos) {
        const v = datos || {};
        const fila = el('div', 'grid grid-cols-2 sm:grid-cols-5 gap-3 items-end');
        fila.dataset.id = v.Id_variante || '';

        const talle = campo('Talle', 'text', v.Talle, { list: 'talles', maxlength: '30' });
        const color = campo('Color', 'text', v.Color, { maxlength: '30' });
        const precio = campo('Precio', 'number', v.Precio_Venta, { min: '1', step: '1' });
        const stock = campo('Stock', 'number', v.Stock_Actual, { min: '0', step: '1' });

        fila.appendChild(talle.caja);
        fila.appendChild(color.caja);
        fila.appendChild(precio.caja);
        fila.appendChild(stock.caja);

        const celda = el('div', 'flex items-end');
        if (v.Id_variante) {
            celda.appendChild(el('span', 'text-[10px] text-[#7a5c3e] py-3', 'SKU ' + (v.SKU || '')));
        } else {
            const quitar = el('button', 'rounded-full bg-[#faf0e6] border border-[#f0dfc8] hover:bg-[#f2a6a6]/40 px-4 py-2 text-xs font-semibold transition-colors cursor-pointer', 'Quitar');
            quitar.type = 'button';
            quitar.addEventListener('click', function () { fila.remove(); });
            celda.appendChild(quitar);
        }
        fila.appendChild(celda);

        fila._campos = { talle: talle.input, color: color.input, precio: precio.input, stock: stock.input };
        contenedor.appendChild(fila);
    }

    function leerVariantes() {
        return Array.from(contenedor.children).map(function (fila) {
            const c = fila._campos;
            const dato = {
                Talle: c.talle.value.trim(),
                Color: c.color.value.trim(),
                Precio_Venta: c.precio.value,
                Stock_Actual: c.stock.value
            };
            if (fila.dataset.id) dato.Id_variante = Number(fila.dataset.id);
            return dato;
        });
    }

    function cargarCategorias(seleccionada) {
        return fetch('/api/admin/categorias', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (lista) {
                const select = document.getElementById('categoria');
                select.replaceChildren();
                const vacio = el('option', null, 'Elegí una categoría');
                vacio.value = '';
                select.appendChild(vacio);
                lista.forEach(function (c) {
                    const opcion = el('option', null, c.Nombre);
                    opcion.value = c.Id_categoria;
                    select.appendChild(opcion);
                });
                if (seleccionada) select.value = seleccionada;
            });
    }

    function cargarProducto() {
        return fetch('/api/admin/productos/' + idProducto, { headers: { 'Accept': 'application/json' } })
            .then(function (r) {
                if (!r.ok) throw new Error('error');
                return r.json();
            })
            .then(function (p) {
                document.getElementById('nombre').value = p.Nombre || '';
                document.getElementById('descripcion').value = p.Descripcion || '';
                document.getElementById('destacado').checked = Number(p.Destacado) === 1;
                if (p.Imagen) {
                    vista.src = ruta(p.Imagen);
                    vista.classList.remove('hidden');
                    textoImagen.classList.add('hidden');
                }
                p.Variantes.forEach(function (v) { agregarVariante(v); });
                return p.Id_categoria;
            });
    }

    inputImagen.addEventListener('change', function () {
        const archivo = inputImagen.files[0];
        if (!archivo) return;
        vista.src = URL.createObjectURL(archivo);
        vista.classList.remove('hidden');
        textoImagen.classList.add('hidden');
    });

    document.getElementById('agregarVariante').addEventListener('click', function () { agregarVariante(); });

    botonGuardar.addEventListener('click', function () {
        const datos = new FormData();
        datos.append('nombre', document.getElementById('nombre').value.trim());
        datos.append('descripcion', document.getElementById('descripcion').value.trim());
        datos.append('id_categoria', document.getElementById('categoria').value);
        datos.append('destacado', document.getElementById('destacado').checked ? '1' : '0');
        datos.append('variantes', JSON.stringify(leerVariantes()));
        if (inputImagen.files[0]) datos.append('imagen', inputImagen.files[0]);

        const url = idProducto ? '/api/admin/productos/' + idProducto + '/actualizar' : '/api/admin/productos';

        botonGuardar.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
            body: datos
        })
        .then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (cuerpo) {
                return { ok: r.ok, estado: r.status, cuerpo: cuerpo };
            });
        })
        .then(function (res) {
            if (res.ok) {
                mostrarAviso(res.cuerpo.message || 'Listo', false);
                setTimeout(function () { window.location.href = '/admin/productos'; }, 900);
                return;
            }
            botonGuardar.disabled = false;
            if (res.estado === 422 && res.cuerpo.errors) {
                const primero = Object.values(res.cuerpo.errors)[0];
                mostrarAviso(Array.isArray(primero) ? primero[0] : res.cuerpo.message, true);
                return;
            }
            mostrarAviso('No se pudo guardar. Revisá storage/logs/laravel.log.', true);
        })
        .catch(function () {
            botonGuardar.disabled = false;
            mostrarAviso('No se pudo conectar con el servidor.', true);
        });
    });

    if (idProducto) {
        cargarProducto()
            .then(function (idCategoria) { return cargarCategorias(idCategoria); })
            .catch(function () { mostrarAviso('No se pudo cargar el producto.', true); });
    } else {
        cargarCategorias(null);
        agregarVariante();
    }
})();
</script>
@endsection