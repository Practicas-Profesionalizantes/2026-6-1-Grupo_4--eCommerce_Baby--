<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo | Kiddy Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">

<?php echo $__env->make('header-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-[8%] py-12 sm:py-20">
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-start">

        <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-28">
            <div class="space-y-10">
                <div class="border-b border-[#F2EDE9] pb-8">
                    <h3 class="font-serif text-sm text-[#2C2520] tracking-wide uppercase mb-5">Talles (Edad)</h3>
                    <ul id="filtro-talles" class="space-y-3"></ul>
                </div>

                <div class="border-b border-[#F2EDE9] pb-8">
                    <h3 class="font-serif text-sm text-[#2C2520] tracking-wide uppercase mb-5">Categorías</h3>
                    <ul id="filtro-categorias" class="space-y-3"></ul>
                </div>

                <button id="limpiar-filtros" type="button" class="hidden text-[10px] font-medium tracking-widest uppercase text-[#322A24] underline underline-offset-4 hover:text-[#d98c8c] transition-colors cursor-pointer">
                    Limpiar filtros
                </button>
            </div>
        </aside>

        <main class="flex-grow w-full">
            <div class="flex flex-row items-center justify-between pb-6 border-b border-[#F2EDE9] mb-10 gap-4">
                <div>
                    <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2520] tracking-tight">Colección Completa</h1>
                    <p id="contador-resultados" class="font-sans text-[10px] text-[#8C7A6B] uppercase tracking-widest mt-1">Catálogo Exclusivo</p>
                </div>
                <div>
                    <select id="orden" class="text-xs text-[#5C534C] bg-white border border-[#E8DFD8] px-4 py-2.5 focus:border-[#322A24] focus:ring-0 outline-none transition-colors cursor-pointer" aria-label="Ordenar productos">
                        <option value="destacados">Destacados</option>
                        <option value="precio-asc">Precio: Menor a Mayor</option>
                        <option value="precio-desc">Precio: Mayor a Menor</option>
                    </select>
                </div>
            </div>

            <div id="grilla-productos" class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-10 sm:gap-x-6 sm:gap-y-14">
                <div class="col-span-full py-20 text-center">
                    <p class="font-serif italic text-sm text-[#8C7A6B]">Cargando prendas...</p>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const grilla = document.getElementById('grilla-productos');
    const listaTalles = document.getElementById('filtro-talles');
    const listaCategorias = document.getElementById('filtro-categorias');
    const selectorOrden = document.getElementById('orden');
    const botonLimpiar = document.getElementById('limpiar-filtros');
    const contador = document.getElementById('contador-resultados');
    const CSRF = '<?php echo e(csrf_token()); ?>';
    const LOGUEADO = <?php if(auth()->guard()->check()): ?> true <?php else: ?> false <?php endif; ?>;

    const ORDEN_TALLES = ['0-3 meses', '3-6 meses', '6-9 meses', 'Único'];
    const UMBRAL_POCO_STOCK = 3;

    const formatoPrecio = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
        maximumFractionDigits: 0
    });

    let productos = [];
    const seleccion = { categorias: new Set(), talles: new Set() };

    function escapar(texto) {
        const div = document.createElement('div');
        div.textContent = texto ?? '';
        return div.innerHTML.replace(/"/g, '&quot;');
    }

    function urlImagen(ruta) {
        if (!ruta) return null;
        return ruta.startsWith('http') ? ruta : '/' + ruta.replace(/^\/+/, '');
    }

    function posicionTalle(talle) {
        const posicion = ORDEN_TALLES.indexOf(talle);
        return posicion === -1 ? 999 : posicion;
    }

    function tarjetaProducto(p) {
        const imagen = urlImagen(p.Imagen);
        const precio = p.Precio_Desde !== null
            ? 'Desde ' + formatoPrecio.format(p.Precio_Desde)
            : 'Consultar precio';
        const talles = p.Talles.length ? p.Talles.join(' · ') : '';
        const sinStock = p.Precio_Desde !== null && p.Stock_Total <= 0;
        const pocoStock = !sinStock && p.Precio_Desde !== null && p.Stock_Total > 0 && p.Stock_Total <= UMBRAL_POCO_STOCK;
        const textoPocoStock = p.Stock_Total === 1 ? '¡Queda 1!' : '¡Quedan ' + p.Stock_Total + '!';

        return `
        <article class="group relative flex flex-col">
            <div class="aspect-[3/4] w-full bg-[#F6F3EE] overflow-hidden relative">
                <div class="absolute inset-0 flex items-center justify-center text-[10px] font-serif italic text-[#8C7A6B]/50">
                    Kiddy Shop Premium
                </div>
                ${imagen ? `<img src="${escapar(imagen)}" alt="${escapar(p.Nombre)}"
                    class="absolute inset-0 w-full h-full object-cover ${sinStock ? 'opacity-50' : ''}" loading="lazy"
                    onerror="this.remove()">` : ''}
                ${sinStock ? '<span class="kiddy-etiqueta kiddy-etiqueta--agotado">Agotado</span>' : ''}
                ${pocoStock ? `<span class="kiddy-etiqueta">${textoPocoStock}</span>` : ''}
            </div>

            <div class="mt-4 flex flex-col gap-1.5 flex-grow">
                <h2 class="font-sans text-xs text-[#2C2520] font-medium tracking-wide uppercase">
                    <a href="/producto/${p.Id_producto}">
                        <span class="absolute inset-0 z-10"></span>
                        ${escapar(p.Nombre)}
                    </a>
                </h2>
                <p class="font-sans text-xs text-[#7A726C]">${escapar(p.Descripcion)}</p>
                <p class="font-sans text-sm text-[#2C2520] font-medium mt-1">${precio}</p>
                ${talles ? `<p class="font-sans text-[10px] text-[#8C7A6B] tracking-wide">Talles: ${escapar(talles)}</p>` : ''}
            </div>

            <div class="mt-3 relative z-20" data-producto="${p.Id_producto}">
                <select class="js-variante hidden w-full mb-2 text-xs text-[#5C534C] bg-white border border-[#E8DFD8] px-3 py-2.5 outline-none cursor-pointer" aria-label="Elegir talle y color"></select>
                <button type="button" ${sinStock ? 'disabled' : ''} class="js-agregar w-full bg-transparent hover:bg-[#322A24] text-[#322A24] hover:text-white border border-[#322A24] text-[10px] font-medium tracking-widest uppercase py-3 transition-colors duration-300 ease-in-out cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                    ${sinStock ? 'Agotado' : 'Añadir al carrito'}
                </button>
                <p class="js-mensaje hidden mt-2 text-[11px] font-medium tracking-wide"></p>
            </div>
        </article>`;
    }

    function mensajeVacio(titulo, detalle) {
        return `
        <div class="col-span-full py-20 text-center">
            <p class="font-serif italic text-sm text-[#8C7A6B]">${titulo}</p>
            <p class="font-sans text-[10px] text-[#A89E95] tracking-wide mt-2">${detalle}</p>
        </div>`;
    }

    function casilla(grupo, valor, etiqueta, indice) {
        const id = grupo + '-' + indice;
        return `
        <li class="flex items-center gap-3 text-xs text-[#5C534C] tracking-wide">
            <input type="checkbox" id="${id}" data-grupo="${grupo}" data-valor="${escapar(valor)}" class="w-4 h-4 rounded-none border-[#C4B7AC] text-[#322A24] focus:ring-0 cursor-pointer accent-[#322A24]">
            <label for="${id}" class="cursor-pointer select-none">${escapar(etiqueta)}</label>
        </li>`;
    }

    function construirFiltros() {
        const categorias = new Map();
        const talles = new Set();

        productos.forEach(function (p) {
            if (p.Id_categoria !== null && p.Categoria) {
                categorias.set(String(p.Id_categoria), p.Categoria);
            }
            p.Talles.forEach(function (t) {
                talles.add(t);
            });
        });

        listaCategorias.innerHTML = Array.from(categorias.entries())
            .sort(function (a, b) { return a[1].localeCompare(b[1], 'es'); })
            .map(function (par, i) { return casilla('categorias', par[0], par[1], i); })
            .join('');

        listaTalles.innerHTML = Array.from(talles)
            .sort(function (a, b) {
                const diferencia = posicionTalle(a) - posicionTalle(b);
                return diferencia !== 0 ? diferencia : a.localeCompare(b, 'es');
            })
            .map(function (t, i) { return casilla('talles', t, t, i); })
            .join('');
    }

    function productosFiltrados() {
        let lista = productos.filter(function (p) {
            const cumpleCategoria = seleccion.categorias.size === 0 || seleccion.categorias.has(String(p.Id_categoria));
            const cumpleTalle = seleccion.talles.size === 0 || p.Talles.some(function (t) { return seleccion.talles.has(t); });
            return cumpleCategoria && cumpleTalle;
        });

        const orden = selectorOrden.value;
        if (orden === 'precio-asc' || orden === 'precio-desc') {
            const factor = orden === 'precio-asc' ? 1 : -1;
            lista = lista.slice().sort(function (a, b) {
                if (a.Precio_Desde === null) return 1;
                if (b.Precio_Desde === null) return -1;
                return (a.Precio_Desde - b.Precio_Desde) * factor;
            });
        }

        return lista;
    }

    function mostrar() {
        const lista = productosFiltrados();
        const hayFiltros = seleccion.categorias.size > 0 || seleccion.talles.size > 0;

        botonLimpiar.classList.toggle('hidden', !hayFiltros);
        contador.textContent = 'Catálogo Exclusivo · ' + lista.length + (lista.length === 1 ? ' prenda' : ' prendas');

        if (lista.length === 0) {
            grilla.innerHTML = mensajeVacio(
                'No encontramos prendas con esos filtros.',
                'Probá sacando alguno de los filtros marcados.'
            );
            return;
        }

        grilla.innerHTML = lista.map(tarjetaProducto).join('');
    }

    document.querySelector('aside').addEventListener('change', function (evento) {
        const marcada = evento.target;
        if (!marcada.matches('input[type="checkbox"]')) return;

        const conjunto = seleccion[marcada.dataset.grupo];
        if (marcada.checked) {
            conjunto.add(marcada.dataset.valor);
        } else {
            conjunto.delete(marcada.dataset.valor);
        }
        mostrar();
    });

    selectorOrden.addEventListener('change', mostrar);

    botonLimpiar.addEventListener('click', function () {
        seleccion.categorias.clear();
        seleccion.talles.clear();
        document.querySelectorAll('aside input[type="checkbox"]').forEach(function (c) {
            c.checked = false;
        });
        mostrar();
    });

    async function variantesDe(idProducto) {
        const respuesta = await fetch('/api/catalogo/productos/' + idProducto, {
            headers: { 'Accept': 'application/json' }
        });
        if (!respuesta.ok) {
            throw new Error('Error ' + respuesta.status);
        }
        const datos = await respuesta.json();
        return (datos.Variantes || []).filter(function (v) { return v.Stock_Actual > 0; });
    }

    function avisoTarjeta(caja, texto, error, conEnlace) {
        const mensaje = caja.querySelector('.js-mensaje');
        mensaje.textContent = texto + ' ';
        mensaje.style.color = error ? '#8a2e2e' : '#2f6b3a';
        if (conEnlace) {
            const enlace = document.createElement('a');
            enlace.href = '/carrito';
            enlace.textContent = 'Ver carrito';
            enlace.style.textDecoration = 'underline';
            enlace.style.fontWeight = 'bold';
            mensaje.appendChild(enlace);
        }
        mensaje.classList.remove('hidden');
    }

    async function agregarVariante(caja, idVariante, boton) {
        boton.disabled = true;
        try {
            const respuesta = await fetch('/api/mi-carrito/items', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ id_variante: Number(idVariante), cantidad: 1 })
            });

            if (respuesta.status === 401) {
                window.location.href = '/login';
                return false;
            }

            const datos = await respuesta.json().catch(function () { return {}; });

            if (!respuesta.ok) {
                avisoTarjeta(caja, datos.mensaje || datos.message || 'No se pudo agregar al carrito.', true, false);
                return false;
            }

            avisoTarjeta(caja, datos.mensaje || 'Agregado al carrito.', false, true);
            if (window.actualizarContadorCarrito) window.actualizarContadorCarrito();
            return true;
        } catch (error) {
            avisoTarjeta(caja, 'No pudimos conectar con el servidor. Intentá de nuevo.', true, false);
            return false;
        } finally {
            boton.disabled = false;
        }
    }

    grilla.addEventListener('click', async function (evento) {
        const boton = evento.target.closest('.js-agregar');
        if (!boton || boton.disabled) return;

        const caja = boton.closest('[data-producto]');
        const selector = caja.querySelector('.js-variante');

        if (!LOGUEADO) {
            window.location.href = '/login';
            return;
        }

        if (!selector.classList.contains('hidden')) {
            if (!selector.value) return;
            const agregado = await agregarVariante(caja, selector.value, boton);
            if (agregado) {
                selector.classList.add('hidden');
                boton.textContent = 'Añadir al carrito';
            }
            return;
        }

        boton.disabled = true;
        let variantes;
        try {
            variantes = await variantesDe(caja.dataset.producto);
        } catch (error) {
            avisoTarjeta(caja, 'No pudimos cargar las opciones. Intentá de nuevo.', true, false);
            boton.disabled = false;
            return;
        }
        boton.disabled = false;

        if (variantes.length === 0) {
            avisoTarjeta(caja, 'Sin stock por el momento.', true, false);
            return;
        }

        if (variantes.length === 1) {
            await agregarVariante(caja, variantes[0].Id_variante, boton);
            return;
        }

        selector.replaceChildren();
        variantes.forEach(function (v) {
            const opcion = document.createElement('option');
            opcion.value = v.Id_variante;
            opcion.textContent = [v.Talle, v.Color].filter(Boolean).join(' · ') || 'Única';
            selector.appendChild(opcion);
        });
        selector.classList.remove('hidden');
        boton.textContent = 'Confirmar';
    });

    async function cargarCatalogo() {
        try {
            const respuesta = await fetch('/api/catalogo/productos', {
                headers: { 'Accept': 'application/json' }
            });

            if (!respuesta.ok) {
                throw new Error('Error ' + respuesta.status);
            }

            productos = await respuesta.json();

            if (productos.length === 0) {
                grilla.innerHTML = mensajeVacio(
                    'No se encontraron prendas disponibles en esta sección.',
                    'Todavía no hay productos activos cargados.'
                );
                return;
            }

            construirFiltros();
            mostrar();
        } catch (error) {
            grilla.innerHTML = mensajeVacio(
                'No pudimos cargar el catálogo.',
                'Intentá recargar la página en unos segundos.'
            );
            console.error(error);
        }
    }

    cargarCatalogo();
</script>

</body>
</html>
<?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/catalog.blade.php ENDPATH**/ ?>