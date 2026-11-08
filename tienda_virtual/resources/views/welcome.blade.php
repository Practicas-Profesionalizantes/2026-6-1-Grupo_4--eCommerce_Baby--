@extends('layouts.app')

@section('content')
    <section class="relative flex items-center min-h-[65vh] px-6 sm:px-12 md:px-[8%] py-16 overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block bg-[#f2a6a6] text-[#4c3300] text-[10px] font-bold tracking-[0.2em] uppercase px-4 py-2 rounded-full mb-6">Nueva Colección</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-[#4c3300] tracking-tight leading-[1.15] mb-6">
                Ropa delicada <br class="hidden sm:inline">para tu bebé
            </h1>
            <p class="font-sans text-sm sm:text-base text-[#7a5c3e] leading-relaxed max-w-md mb-8">
                Descubrí nuestra selección atemporal de prendas confeccionadas exclusivamente con el algodón orgánico más suave y puro del mercado.
            </p>
            <div>
                <a href="/catalogo" class="inline-block bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-8 py-4 rounded-full transition-all duration-300 active:scale-95 shadow-lg hover:shadow-xl">
                    Ver Catálogo
                </a>
            </div>
        </div>
    </section>

    <section class="px-6 sm:px-12 md:px-[8%] py-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <h2 class="font-serif text-2xl text-[#4c3300] tracking-tight">Esenciales de Temporada</h2>
                <p class="font-sans text-xs text-[#7a5c3e] mt-1">Diseños minimalistas pensados para el confort diario.</p>
            </div>
            <a href="/catalogo" class="text-xs font-bold text-[#4c3300] underline underline-offset-4 hover:text-[#d98c8c] transition-colors">
                Ver todos los productos
            </a>
        </div>

        <div id="productosPortada" class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8 sm:gap-x-6 sm:gap-y-12"></div>
        <p id="portadaMensaje" class="hidden font-sans text-sm text-[#7a5c3e]"></p>
    </section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('productosPortada');
    const mensaje = document.getElementById('portadaMensaje');
    const CANTIDAD = 4;

    function el(etiqueta, clases, texto) {
        const nodo = document.createElement(etiqueta);
        if (clases) nodo.className = clases;
        if (texto !== undefined && texto !== null) nodo.textContent = texto;
        return nodo;
    }

    function pesos(numero) {
        return '$' + Number(numero).toLocaleString('es-AR', { maximumFractionDigits: 0 });
    }

    function rutaImagen(url) {
        if (!url) return '';
        if (url.startsWith('http')) return url;
        return '/' + url.replace(/^\/+/, '');
    }

    function esqueleto() {
        const caja = el('div', 'flex flex-col');
        caja.appendChild(el('div', 'aspect-[3/4] w-full bg-[#faebd7] rounded-[2rem] animate-pulse'));
        return caja;
    }

    function tarjeta(producto) {
        const enlace = el('a', 'group relative flex flex-col');
        enlace.href = '/producto/' + producto.Id_producto;

        const marco = el('div', 'aspect-[3/4] w-full bg-[#faebd7] rounded-[2rem] overflow-hidden relative flex items-center justify-center transition-transform duration-300 group-hover:-translate-y-1 group-hover:shadow-lg');
        const foto = rutaImagen(producto.Imagen);
        if (foto) {
            const img = el('img', 'w-full h-full object-cover');
            img.src = foto;
            img.alt = producto.Nombre;
            marco.appendChild(img);
        } else {
            marco.appendChild(el('span', 'text-xs font-serif italic text-[#7a5c3e]/50', 'Sin foto'));
        }
        enlace.appendChild(marco);

        const datos = el('div', 'mt-4 flex flex-col gap-1');
        datos.appendChild(el('h3', 'font-sans text-xs text-[#4c3300] font-bold tracking-wide', producto.Nombre));
        datos.appendChild(el('p', 'font-sans text-xs text-[#7a5c3e]', 'Desde ' + pesos(producto.Precio_Desde)));
        if (Number(producto.Stock_Total) <= 0) {
            datos.appendChild(el('p', 'font-sans text-[10px] font-bold tracking-widest uppercase text-[#d98c8c]', 'Sin stock'));
        }
        enlace.appendChild(datos);

        return enlace;
    }

    function avisar(texto) {
        contenedor.replaceChildren();
        mensaje.textContent = texto;
        mensaje.classList.remove('hidden');
    }

    for (let i = 0; i < CANTIDAD; i++) {
        contenedor.appendChild(esqueleto());
    }

    fetch('/api/catalogo/productos', { headers: { 'Accept': 'application/json' } })
        .then(function (respuesta) {
            if (!respuesta.ok) throw new Error('error ' + respuesta.status);
            return respuesta.json();
        })
        .then(function (productos) {
            if (!Array.isArray(productos) || productos.length === 0) {
                avisar('Todavía no hay productos cargados.');
                return;
            }
            contenedor.replaceChildren();
            productos.slice(0, CANTIDAD).forEach(function (producto) {
                contenedor.appendChild(tarjeta(producto));
            });
        })
        .catch(function () {
            avisar('No se pudieron cargar los productos.');
        });
});
</script>
@endsection