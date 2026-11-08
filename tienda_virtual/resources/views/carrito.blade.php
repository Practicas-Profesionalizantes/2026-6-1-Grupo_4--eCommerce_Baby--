@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-8 max-w-4xl mx-auto">
    <div class="bg-[#fcfaf6] dark:bg-[#151210] rounded-[2.5rem] p-6 sm:p-10 shadow-xl shadow-[#1a1512]/5 border border-[#ede9e1] dark:border-[#2e2621]">

        <h2 class="font-serif text-2xl text-[#1a1512] dark:text-[#fcfaf6] mb-6 pb-4 border-b border-[#ede9e1] dark:border-[#2e2621] font-medium tracking-wide">
            Mi carrito
        </h2>

        <p id="aviso-carrito" class="hidden mb-4 p-3 rounded-2xl text-sm" style="background:#f8e4e4; color:#8a2e2e; border:1px solid #e6bcbc;"></p>

        <div id="estado-carga" class="py-16 text-center font-serif italic text-sm text-[#8C7A6B]">
            Cargando carrito...
        </div>

        <div id="carrito-vacio" class="hidden py-16 text-center">
            <p class="font-serif italic text-sm text-[#8C7A6B] mb-6">Tu carrito está vacío.</p>
            <a href="/catalogo" class="inline-block bg-[#1a1512] dark:bg-[#fcfaf6] text-white dark:text-[#1a1512] text-xs font-bold py-4 px-8 rounded-2xl uppercase tracking-widest">
                Ver catálogo
            </a>
        </div>

        <div id="carrito-contenido" class="hidden">
            <div id="lista-items" class="space-y-4"></div>

            <div class="mt-8 pt-6 border-t border-[#ede9e1] dark:border-[#2e2621] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-[#1a1512] dark:text-[#fcfaf6]">
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7A6B]">Subtotal</p>
                    <p id="subtotal-carrito" class="text-2xl font-bold"></p>
                    <p class="text-[11px] text-[#8C7A6B]">El envío se elige en el siguiente paso.</p>
                </div>
                <div class="flex gap-3">
                    <a href="/catalogo" class="border border-[#1a1512] dark:border-[#ede9e1]/30 text-[#1a1512] dark:text-[#fcfaf6] text-xs font-bold py-4 px-6 rounded-2xl uppercase tracking-widest">
                        Seguir comprando
                    </a>
                    <a id="boton-finalizar" href="/checkout/carrito" class="bg-[#1a1512] dark:bg-[#fcfaf6] text-white dark:text-[#1a1512] text-xs font-bold py-4 px-6 rounded-2xl uppercase tracking-widest">
                        Finalizar compra
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const CSRF = '{{ csrf_token() }}';

    const formatoPrecio = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
        maximumFractionDigits: 0
    });

    function urlImagen(ruta) {
        if (!ruta) return null;
        return ruta.startsWith('http') ? ruta : '/' + ruta.replace(/^\/+/, '');
    }

    function crear(etiqueta, clases, texto) {
        const el = document.createElement(etiqueta);
        if (clases) el.className = clases;
        if (texto !== undefined) el.textContent = texto;
        return el;
    }

    function mostrarAviso(texto) {
        const caja = document.getElementById('aviso-carrito');
        if (!texto) {
            caja.classList.add('hidden');
            return;
        }
        caja.textContent = texto;
        caja.classList.remove('hidden');
    }

    async function llamar(url, metodo, cuerpo) {
        try {
            const respuesta = await fetch(url, {
                method: metodo,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: cuerpo ? JSON.stringify(cuerpo) : undefined
            });

            if (respuesta.status === 401) {
                window.location.href = '/login';
                return null;
            }

            const datos = await respuesta.json().catch(function () { return {}; });

            if (!respuesta.ok) {
                mostrarAviso(datos.mensaje || datos.message || 'No se pudo completar la acción.');
                return null;
            }

            return datos;
        } catch (error) {
            mostrarAviso('No pudimos conectar con el servidor. Intentá de nuevo.');
            return null;
        }
    }

    function fila(item) {
        const caja = crear('div', 'flex gap-4 items-center bg-white dark:bg-[#1a1512] border border-[#ede9e1] dark:border-[#2e2621] rounded-[1.5rem] p-4');

        const ruta = urlImagen(item.imagen);
        if (ruta) {
            const img = crear('img', 'w-20 h-20 object-cover rounded-xl flex-shrink-0');
            img.src = ruta;
            img.alt = item.producto;
            img.onerror = function () { img.remove(); };
            caja.appendChild(img);
        }

        const datos = crear('div', 'flex-1 min-w-0 text-sm text-[#4a3d33] dark:text-[#e0dcd3]');
        datos.appendChild(crear('p', 'font-bold text-[#1a1512] dark:text-[#fcfaf6]', item.producto));
        datos.appendChild(crear('p', '', [item.talle, item.color].filter(Boolean).join(' · ')));
        datos.appendChild(crear('p', '', formatoPrecio.format(item.precio) + ' c/u'));

        if (!item.disponible) {
            datos.appendChild(crear('p', 'font-bold', 'Sin stock por el momento'));
            datos.lastChild.style.color = '#8a2e2e';
        } else {
            datos.appendChild(crear('p', 'text-[11px] text-[#8C7A6B]', 'Disponibles: ' + item.stock));
        }
        caja.appendChild(datos);

        const derecha = crear('div', 'flex flex-col items-end gap-2 flex-shrink-0');

        if (item.disponible) {
            const entrada = crear('input', 'w-20 text-center p-2 bg-[#f2ede4] dark:bg-[#26201b] text-[#1a1512] dark:text-[#fcfaf6] rounded-xl text-sm focus:outline-none');
            entrada.type = 'number';
            entrada.min = '1';
            entrada.max = String(item.stock);
            entrada.value = String(item.cantidad);
            entrada.addEventListener('change', async function () {
                const valor = parseInt(entrada.value, 10);
                if (!valor || valor < 1) {
                    entrada.value = String(item.cantidad);
                    return;
                }
                const datosNuevos = await llamar('/api/mi-carrito/items/' + item.id, 'PUT', { cantidad: valor });
                if (datosNuevos) pintar(datosNuevos);
                else entrada.value = String(item.cantidad);
            });
            derecha.appendChild(entrada);
            derecha.appendChild(crear('p', 'font-bold text-sm text-[#d96b62]', formatoPrecio.format(item.subtotal)));
        }

        const quitar = crear('button', 'text-[11px] uppercase tracking-widest text-[#8C7A6B] hover:text-[#d96b62] cursor-pointer', 'Quitar');
        quitar.type = 'button';
        quitar.addEventListener('click', async function () {
            const datosNuevos = await llamar('/api/mi-carrito/items/' + item.id, 'DELETE');
            if (datosNuevos) pintar(datosNuevos);
        });
        derecha.appendChild(quitar);

        caja.appendChild(derecha);
        return caja;
    }

    function pintar(datos) {
        document.getElementById('estado-carga').classList.add('hidden');
        mostrarAviso(datos.aviso || '');

        const vacio = document.getElementById('carrito-vacio');
        const contenido = document.getElementById('carrito-contenido');
        const lista = document.getElementById('lista-items');
        lista.innerHTML = '';

        if (!datos.items || datos.items.length === 0) {
            contenido.classList.add('hidden');
            vacio.classList.remove('hidden');
        } else {
            vacio.classList.add('hidden');
            contenido.classList.remove('hidden');
            datos.items.forEach(function (item) { lista.appendChild(fila(item)); });
            document.getElementById('subtotal-carrito').textContent = formatoPrecio.format(datos.subtotal);

            const hayDisponibles = datos.items.some(function (i) { return i.disponible; });
            const boton = document.getElementById('boton-finalizar');
            boton.style.opacity = hayDisponibles ? '1' : '0.4';
            boton.style.pointerEvents = hayDisponibles ? 'auto' : 'none';
        }

        if (window.actualizarContadorCarrito) window.actualizarContadorCarrito();
    }

    async function cargarCarrito() {
        const datos = await llamar('/api/mi-carrito', 'GET');
        if (datos) {
            pintar(datos);
        } else {
            document.getElementById('estado-carga').textContent = 'No pudimos cargar el carrito. Intentá recargar la página.';
        }
    }

    cargarCarrito();
</script>
@endsection