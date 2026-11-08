

<?php $__env->startSection('content'); ?>
<style>
    .stock-poco { color: #b8423f; font-weight: 700; }
    html.dark .stock-poco { color: #e07a77; }
</style>

<div class="py-12 px-4 sm:px-8 max-w-6xl mx-auto">
    <div class="bg-[#fcfaf6] rounded-[2.5rem] p-6 sm:p-10 shadow-xl shadow-[#1a1512]/5 border border-[#ede9e1]">

        <h2 class="font-serif text-2xl text-[#1a1512] mb-6 pb-4 border-b border-[#ede9e1] font-medium tracking-wide">
            Vista de inspección del Producto
        </h2>

        <div id="estado-carga" class="py-20 text-center font-serif italic text-sm text-[#8C7A6B]">
            Cargando producto...
        </div>

        <div id="contenido-producto" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                <div class="md:col-span-6 grid grid-cols-12 gap-4">
                    <div id="miniaturas" class="col-span-3 flex flex-col gap-3"></div>
                    <div class="col-span-9 bg-white border border-[#ede9e1] rounded-[2rem] p-6 flex items-center justify-center min-h-[380px] relative">
                        <span id="sin-foto" class="hidden absolute font-serif italic text-xs text-[#8C7A6B]/60">Kiddy Shop Premium</span>
                        <img id="visualizador-principal" src="" alt="" class="max-w-full max-h-[360px] object-contain rounded-xl">
                    </div>
                </div>

                <div class="md:col-span-6 flex flex-col justify-between">
                    <form id="form-compra" action="<?php echo e(route('checkout.datos')); ?>" method="GET" class="h-full flex flex-col justify-between gap-6">
                        <div class="space-y-3">
                            <p id="categoria-producto" class="text-[10px] uppercase tracking-widest text-[#8C7A6B] pl-1"></p>
                            <div id="nombre-producto" class="w-full p-4 bg-white border border-[#ede9e1] rounded-2xl text-[#1a1512] font-medium text-sm"></div>
                            <div id="precio-producto" class="w-fit min-w-32 p-3 bg-white border border-[#ede9e1] rounded-2xl text-[#d96b62] font-bold text-center text-lg"></div>
                        </div>

                        <div class="flex-1 bg-white border border-[#ede9e1] rounded-[2rem] p-6 text-[#5c524a] text-sm leading-relaxed min-h-[180px]">
                            <p class="font-bold text-[#1a1512] mb-2 uppercase text-xs tracking-wider">Descripcion del producto</p>
                            <p id="descripcion-producto"></p>
                        </div>

                        <div class="space-y-2">
                            <label for="select-variante" class="text-[11px] font-bold text-[#7a6f66] uppercase tracking-wider block pl-1">Talle y color</label>
                            <select name="variante" id="select-variante" class="w-full p-4 bg-white border border-[#ede9e1] rounded-2xl focus:outline-none focus:border-[#1a1512] text-[#1a1512] text-sm cursor-pointer" onchange="actualizarVariante()"></select>
                            <p id="stock-texto" class="text-[10px] tracking-wide text-[#8C7A6B] pl-1"></p>
                        </div>

                        <div class="grid grid-cols-12 gap-4 items-start">
                            <div class="col-span-4">
                                <input type="number" name="cantidad" id="input-cantidad" value="1" min="1" class="w-full text-center p-3.5 bg-white border border-[#ede9e1] rounded-2xl focus:outline-none focus:border-[#1a1512] text-[#1a1512] font-medium">
                            </div>
                            <div class="col-span-8 flex flex-col gap-3">
                                <button id="boton-comprar" type="submit" class="w-full bg-[#1a1512] hover:bg-[#332a24] disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-bold py-4 px-6 rounded-2xl uppercase tracking-widest transition-all">
                                    Comprar ahora
                                </button>
                                <button id="boton-carrito" type="button" onclick="agregarAlCarrito()" class="w-full bg-white hover:bg-[#f2ede4] disabled:opacity-40 disabled:cursor-not-allowed text-[#1a1512] border border-[#1a1512] text-xs font-bold py-4 px-6 rounded-2xl uppercase tracking-widest transition-all cursor-pointer">
                                    Agregar al carrito
                                </button>
                                <p id="mensaje-carrito" class="hidden text-xs pl-1"></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ID_PRODUCTO = <?php echo e((int) $idProducto); ?>;
    const LOGUEADO = <?php if(auth()->guard()->check()): ?> true <?php else: ?> false <?php endif; ?>;
    const CSRF = '<?php echo e(csrf_token()); ?>';
    const UMBRAL_POCO_STOCK = 3;

    const formatoPrecio = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
        maximumFractionDigits: 0
    });

    let variantes = [];

    function urlImagen(ruta) {
        if (!ruta) return null;
        return ruta.startsWith('http') ? ruta : '/' + ruta.replace(/^\/+/, '');
    }

    function intercambiarImagen(miniatura) {
        const principal = document.getElementById('visualizador-principal');
        principal.classList.remove('hidden');
        document.getElementById('sin-foto').classList.add('hidden');
        principal.src = miniatura.src;

        document.querySelectorAll('.img-miniatura-kiddy').forEach(img => {
            img.classList.remove('border-[#1a1512]');
            img.classList.add('border-transparent');
        });
        miniatura.classList.remove('border-transparent');
        miniatura.classList.add('border-[#1a1512]');
    }

    function actualizarVariante() {
        const select     = document.getElementById('select-variante');
        const variante   = variantes.find(v => String(v.Id_variante) === select.value);
        const cantidad   = document.getElementById('input-cantidad');
        const boton      = document.getElementById('boton-comprar');
        const botonCar   = document.getElementById('boton-carrito');
        const textoStock = document.getElementById('stock-texto');

        if (!variante || variante.Stock_Actual < 1) {
            document.getElementById('precio-producto').textContent = variante ? formatoPrecio.format(variante.Precio_Venta) : 'Consultar precio';
            textoStock.textContent = 'Sin stock por el momento';
            textoStock.classList.remove('stock-poco');
            cantidad.disabled = true;
            boton.disabled = true;
            botonCar.disabled = true;
            return;
        }

        document.getElementById('precio-producto').textContent = formatoPrecio.format(variante.Precio_Venta);

        if (variante.Stock_Actual <= UMBRAL_POCO_STOCK) {
            textoStock.textContent = variante.Stock_Actual === 1
                ? '¡Queda solo 1 disponible!'
                : '¡Quedan solo ' + variante.Stock_Actual + ' disponibles!';
            textoStock.classList.add('stock-poco');
        } else {
            textoStock.textContent = 'Disponibles: ' + variante.Stock_Actual;
            textoStock.classList.remove('stock-poco');
        }

        cantidad.disabled = false;
        cantidad.max = variante.Stock_Actual;
        if (Number(cantidad.value) > variante.Stock_Actual) cantidad.value = variante.Stock_Actual;
        if (Number(cantidad.value) < 1) cantidad.value = 1;
        boton.disabled = false;
        botonCar.disabled = false;
    }

    function mensajeCarrito(texto, error, conEnlace) {
        const caja = document.getElementById('mensaje-carrito');
        caja.textContent = texto + ' ';
        caja.style.color = error ? '#8a2e2e' : '#2f6b3a';
        if (conEnlace) {
            const enlace = document.createElement('a');
            enlace.href = '/carrito';
            enlace.textContent = 'Ver carrito';
            enlace.style.textDecoration = 'underline';
            enlace.style.fontWeight = 'bold';
            caja.appendChild(enlace);
        }
        caja.classList.remove('hidden');
    }

    async function agregarAlCarrito() {
        if (!LOGUEADO) {
            window.location.href = '/login';
            return;
        }

        const select   = document.getElementById('select-variante');
        const cantidad = document.getElementById('input-cantidad');
        const boton    = document.getElementById('boton-carrito');

        if (!select.value) return;

        boton.disabled = true;

        try {
            const respuesta = await fetch('/api/mi-carrito/items', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({
                    id_variante: Number(select.value),
                    cantidad: Math.max(1, parseInt(cantidad.value, 10) || 1)
                })
            });

            if (respuesta.status === 401) {
                window.location.href = '/login';
                return;
            }

            const datos = await respuesta.json().catch(function () { return {}; });

            if (!respuesta.ok) {
                mensajeCarrito(datos.mensaje || datos.message || 'No se pudo agregar al carrito.', true, false);
            } else {
                mensajeCarrito(datos.mensaje || 'Agregado al carrito.', false, true);
                if (window.actualizarContadorCarrito) window.actualizarContadorCarrito();
            }
        } catch (error) {
            mensajeCarrito('No pudimos conectar con el servidor. Intentá de nuevo.', true, false);
        } finally {
            boton.disabled = false;
        }
    }

    function pintarProducto(p) {
        document.title = p.Nombre + ' | Kiddy Shop';
        document.getElementById('nombre-producto').textContent = p.Nombre;
        document.getElementById('descripcion-producto').textContent = p.Descripcion ?? '';
        document.getElementById('categoria-producto').textContent = p.Categoria ?? '';

        const imagenes   = (p.Imagenes || []).map(urlImagen).filter(Boolean);
        const miniaturas = document.getElementById('miniaturas');
        const principal  = document.getElementById('visualizador-principal');
        const sinFoto    = document.getElementById('sin-foto');

        principal.onerror = function () {
            principal.classList.add('hidden');
            sinFoto.classList.remove('hidden');
        };

        if (imagenes.length === 0) {
            principal.classList.add('hidden');
            sinFoto.classList.remove('hidden');
        } else {
            principal.src = imagenes[0];
            principal.alt = p.Nombre;

            imagenes.forEach((url, i) => {
                const img = document.createElement('img');
                img.src = url;
                img.alt = p.Nombre;
                img.className = 'w-full h-20 object-cover border-2 rounded-2xl cursor-pointer transition-all duration-200 img-miniatura-kiddy '
                    + (i === 0 ? 'border-[#1a1512]' : 'border-transparent hover:border-[#1a1512]');
                img.onclick = () => intercambiarImagen(img);
                img.onerror = () => img.remove();
                miniaturas.appendChild(img);
            });
        }

        variantes = p.Variantes || [];
        const select = document.getElementById('select-variante');

        if (variantes.length === 0) {
            const op = document.createElement('option');
            op.textContent = 'Sin variantes disponibles';
            select.appendChild(op);
            select.disabled = true;
            actualizarVariante();
            return;
        }

        variantes.forEach(v => {
            const op = document.createElement('option');
            op.value = v.Id_variante;
            const detalle = [v.Talle, v.Color].filter(Boolean).join(' · ') || 'Única';
            op.textContent = detalle + (v.Stock_Actual < 1 ? ' (sin stock)' : '');
            op.disabled = v.Stock_Actual < 1;
            select.appendChild(op);
        });

        const primera = variantes.find(v => v.Stock_Actual > 0);
        if (primera) select.value = primera.Id_variante;
        actualizarVariante();
    }

    async function cargarProducto() {
        const estado = document.getElementById('estado-carga');

        try {
            const respuesta = await fetch('/api/catalogo/productos/' + ID_PRODUCTO, {
                headers: { 'Accept': 'application/json' }
            });

            if (respuesta.status === 404) {
                estado.textContent = 'Este producto no está disponible.';
                return;
            }
            if (!respuesta.ok) {
                throw new Error('Error ' + respuesta.status);
            }

            pintarProducto(await respuesta.json());
            estado.classList.add('hidden');
            document.getElementById('contenido-producto').classList.remove('hidden');
        } catch (error) {
            estado.textContent = 'No pudimos cargar el producto. Intentá recargar la página.';
            console.error(error);
        }
    }

    cargarProducto();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/producto.blade.php ENDPATH**/ ?>