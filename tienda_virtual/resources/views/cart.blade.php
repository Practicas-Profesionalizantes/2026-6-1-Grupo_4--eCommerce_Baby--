@include('header-footer')

<div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-[8%] py-12 sm:py-20">
    <!-- Título Principal Institucional -->
    <h1 class="font-serif text-3xl text-[#2C2520] tracking-tight mb-12 border-b border-[#F2EDE9] pb-6">
        Tu Carrito de Compras
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16 items-start">
        
        <!-- COLUMNA IZQUIERDA: ARTÍCULOS AGREGADOS (Ocupa 2 columnas de 3) -->
        <div class="lg:col-span-2 space-y-6">
            
            @php $subtotal = 0; @endphp

            @if(isset($items) && $items->count() > 0)
                @foreach($items as $item)
                <!-- Evaluamos si el ítem tiene un producto asignado (evita que falle si la relación está vacía) -->
                @php 
                    // Si tu modelo maneja el precio fijo o lo calcula, lo sumamos aquí.
                    // Usamos un precio base estético de $14.500 si la columna no está asignada aún.
                    $precioItem = 14500; 
                    $subtotal += ($precioItem * $item->Cantidad);
                @endphp
                <article class="flex items-center gap-6 bg-white border border-[#F2EDE9] p-6 relative transition-all duration-300 hover:border-[#C4B7AC]">
                    <!-- Imagen Recta Estilo Pasarela -->
                    <div class="w-20 h-24 bg-[#F6F3EE] shrink-0 overflow-hidden relative">
                        <div class="absolute inset-0 flex items-center justify-center text-[9px] font-serif italic text-[#8C7A6B]/50 text-center p-1">
                            Prenda
                        </div>
                    </div>

                    <!-- Detalles Técnicos del Producto -->
                    <div class="flex-grow flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="space-y-1">
                            <h2 class="font-sans text-xs text-[#2C2520] font-medium tracking-wide uppercase">
                                <!-- Si tu tabla tiene el nombre del producto directamente o mediante ID -->
                                ID Producto: {{ $item->id_producto }}
                            </h2>
                            <p class="font-sans text-[11px] text-[#8C7A6B] tracking-wide">
                                Código de Item: #{{ $item->id_item_carrito }}
                            </p>
                            
                            <!-- Acciones y Cantidad -->
                            <div class="flex items-center gap-6 pt-3">
                                <div class="flex items-center border border-[#E8DFD8]">
                                    <label for="qty-{{ $item->id_item_carrito }}" class="sr-only">Cantidad</label>
                                    <input type="number" id="qty-{{ $item->id_item_carrito }}" value="{{ $item->Cantidad ?? 1 }}" min="1" class="w-12 text-center text-xs font-sans py-1.5 focus:ring-0 focus:outline-none border-none text-[#2C2520]" aria-label="Cantidad">
                                </div>
                                
                                <!-- Botón de eliminación limpio con método DELETE de Laravel -->
                                <form action="/item/{{ $item->id_item_carrito }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] text-[#A84343] uppercase tracking-widest font-medium hover:text-[#5C1E1E] transition-colors flex items-center gap-1.5 cursor-pointer bg-transparent border-none p-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Quitar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Precio Calculado -->
                        <div class="text-right shrink-0">
                            <p class="font-sans text-sm font-semibold text-[#2C2520] tracking-wide">
                                ${{ number_format($precioItem * ($item->Cantidad ?? 1), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </article>
                @endforeach
            @else
                <!-- Estado vacío corporativo si la tabla item_carrito no tiene filas -->
                <div class="border border-dashed border-[#C4B7AC] p-12 text-center">
                    <svg class="w-6 h-6 text-[#8C7A6B] mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <p class="font-serif italic text-sm text-[#8C7A6B]">Tu carrito está actualmente vacío.</p>
                    <a href="/catalogo" class="inline-block mt-4 text-xs font-medium uppercase tracking-widest text-[#2C2520] underline underline-offset-4 hover:text-[#8C7A6B]">
                        Volver al Catálogo
                    </a>
                </div>
            @endif

        </div>

        <!-- COLUMNA DERECHA: RESUMEN INSTITUCIONAL DE COMPRA -->
        <aside class="bg-[#F9F6F0] border border-[#F2EDE9] p-8 lg:sticky lg:top-28">
            <h2 class="font-serif text-lg text-[#2C2520] tracking-tight pb-4 border-b border-[#E8DFD8] mb-6">
                Resumen de Compra
            </h2>
            
            <div class="space-y-4 font-sans text-xs tracking-wide">
                <div class="flex justify-between text-[#5C534C]">
                    <span>Subtotal Estimado</span>
                    <span class="font-medium text-[#2C2520]">${{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[#5C534C]">
                    <span>Envío estimado</span>
                    <span class="font-medium text-[#235E39] uppercase tracking-widest text-[10px]">Gratis</span>
                </div>
                
                <div class="border-t border-[#E8DFD8] pt-4 mt-6 flex justify-between text-sm font-medium text-[#2C2520]">
                    <span class="font-serif text-base">Total</span>
                    <span class="text-base tracking-wide">${{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Botón de Acción Directo y Corporativo -->
            <div class="mt-8">
                <a href="#" class="block w-full bg-[#322A24] hover:bg-[#4A3F36] text-[#FDFCFB] text-center text-xs font-medium tracking-widest uppercase py-4 transition-colors duration-300 ease-in-out">
                    Iniciar Proceso de Pago
                </a>
            </div>
        </aside>

    </div>
</div>

<!-- FOOTER: Consistencia absoluta de marca inferior -->
<footer class="bg-[#1A1613] text-[#DED7D1] font-sans border-t border-[#2C2520]">
    <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-[8%] py-16 grid grid-cols-1 sm:grid-cols-3 gap-12 sm:gap-8">
        <div class="flex flex-col gap-4">
            <span class="font-serif text-lg tracking-wider text-white">Kiddy Shop</span>
            <p class="text-xs text-[#A89E95] leading-relaxed max-w-xs">Comprometidos con la excelencia textil y el cuidado consciente de la piel de los recién nacidos.</p>
        </div>
        <div class="flex flex-col gap-4">
            <h4 class="text-xs font-semibold tracking-widest text-white uppercase">Soporte</h4>
            <ul class="flex flex-col gap-2.5 text-xs text-[#A89E95]">
                <li><a href="#" class="hover:text-white transition-colors">Preguntas Frecuentes</a></li>
                <li><a href="#" class="hover:text-white transition-colors">Políticas de Cambio</a></li>
                <li><a href="#" class="hover:text-white transition-colors">Guía de Talles</a></li>
            </ul>
        </div>
        <div class="flex flex-col gap-4">
            <h4 class="text-xs font-semibold tracking-widest text-white uppercase">Contacto</h4>
            <div class="flex items-start gap-2.5 text-xs text-[#A89E95]">
                <svg class="w-4 h-4 text-[#C4B7AC] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                <p class="leading-relaxed">Av. Corrientes 4500, CABA,<br>Buenos Aires, Argentina.</p>
            </div>
        </div>
    </div>
    <div class="border-t border-[#2C2520] bg-[#14110E]">
