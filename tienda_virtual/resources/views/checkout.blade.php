@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-8 max-w-6xl mx-auto" style="font-family: sans-serif;">
    <div class="bg-[#fcfaf6] dark:bg-[#151210] rounded-[2.5rem] p-6 sm:p-10 shadow-xl shadow-[#1a1512]/5 border border-[#ede9e1] dark:border-[#2e2621]">

        <h2 class="text-2xl text-[#1a1512] dark:text-[#fcfaf6] text-center mb-6 font-normal tracking-wide">
            Vista de Compra Finalizada
        </h2>

        <div class="w-full p-4 bg-white dark:bg-[#1a1512] border border-[#ede9e1] dark:border-[#2e2621] rounded-2xl text-center text-[#1a1512] dark:text-white font-serif tracking-widest text-lg mb-8 max-w-sm mx-auto">
            Kiddy Shop
        </div>

        @if($errors->any())
            <div id="caja-errores" style="background:#f8e4e4; color:#8a2e2e; border:1px solid #e6bcbc; border-radius:1rem; padding:1rem 1.25rem; margin:0 auto 2rem auto; max-width:42rem; font-size:0.875rem;">
                @foreach($errors->all() as $error)
                    <p style="margin:0 0 0.25rem 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="/checkout/procesar-pago" method="POST" novalidate class="grid grid-cols-1 md:grid-cols-12 gap-8">
            @csrf
            <input type="hidden" name="banco_seleccionado" id="banco-oculto" value="{{ old('banco_seleccionado') }}">

            <div class="md:col-span-7 space-y-6">
                <div class="border border-[#ede9e1] dark:border-[#2e2621] bg-white dark:bg-[#1a1512] rounded-[2rem] p-6 space-y-4">
                    <h3 class="text-sm font-bold text-[#1a1512] dark:text-[#fcfaf6] uppercase tracking-wider mb-2">Informacion de pago</h3>

                    <select name="metodo_pago" id="combo-metodo" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm cursor-pointer" onchange="actualizarMetodoPago()">
                        <option value="" disabled data-tipo="" {{ old('metodo_pago') ? '' : 'selected' }}>Elegí un método de pago</option>
                        @foreach($metodosPago as $m)
                            @php
                                $nombreMin = mb_strtolower($m->Nombre);
                                $tipo = str_contains($nombreMin, 'tarjeta') ? 'tarjeta' : (str_contains($nombreMin, 'transfer') ? 'transferencia' : 'otro');
                            @endphp
                            <option value="{{ $m->Id_metodo_pago }}" data-tipo="{{ $tipo }}" {{ (string) old('metodo_pago') === (string) $m->Id_metodo_pago ? 'selected' : '' }}>{{ $m->Nombre }}</option>
                        @endforeach
                    </select>

                    <div id="grupo-tarjeta-campos" class="space-y-4" style="display:none;">
                        <input type="text" name="numero_tarjeta" value="{{ old('numero_tarjeta') }}" autocomplete="off" placeholder="Número de tarjeta" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="vencimiento" value="{{ old('vencimiento') }}" autocomplete="off" placeholder="MM/AA" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm">
                            <input type="text" name="cvc" value="{{ old('cvc') }}" autocomplete="off" placeholder="CVC" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm">
                        </div>
                    </div>
                </div>

                <div class="border border-[#ede9e1] dark:border-[#2e2621] bg-white dark:bg-[#1a1512] rounded-[2rem] p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-[#1a1512] dark:text-[#fcfaf6] uppercase tracking-wider mb-4">Método de envío</h3>
                    <select name="metodo_envio" id="combo-envio" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm cursor-pointer" onchange="actualizarEnvio()">
                        <option value="" disabled data-costo="" {{ old('metodo_envio') ? '' : 'selected' }}>Elegí un método de envío</option>
                        @foreach($metodosEnvio as $e)
                            <option value="{{ $e->Id_metodo_envio }}" data-costo="{{ (float) $e->Costo_Base }}" {{ (string) old('metodo_envio') === (string) $e->Id_metodo_envio ? 'selected' : '' }}>
                                {{ $e->Nombre }} — {{ (float) $e->Costo_Base > 0 ? '$ ' . number_format($e->Costo_Base, 0, ',', '.') : 'Gratis' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="grupo-banco" class="border border-[#ede9e1] dark:border-[#2e2621] bg-white dark:bg-[#1a1512] rounded-[2rem] p-6 shadow-sm" style="display:none;">
                    <h3 class="text-sm font-bold text-[#1a1512] dark:text-[#fcfaf6] uppercase tracking-wider mb-4">
                        Banco
                    </h3>

                    <div class="grid grid-cols-2 gap-3 w-full">
                        <div class="caja-banco p-4 bg-[#fbf9f4] dark:bg-[#26201b] border border-[#ede9e1] dark:border-[#2e2621] rounded-xl text-center text-xs font-bold text-[#1a1512] dark:text-[#e0dcd3] cursor-pointer transition-all select-none"
                            onclick="marcarBancoGrid(this)">
                            Galicia
                        </div>
                        <div class="caja-banco p-4 bg-[#fbf9f4] dark:bg-[#26201b] border border-[#ede9e1] dark:border-[#2e2621] rounded-xl text-center text-xs font-bold text-[#1a1512] dark:text-[#e0dcd3] cursor-pointer transition-all select-none"
                            onclick="marcarBancoGrid(this)">
                            Santander
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-5 flex flex-col space-y-6">

                <div class="border border-[#ede9e1] dark:border-[#2e2621] bg-white dark:bg-[#1a1512] rounded-[2rem] p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-[#1a1512] dark:text-[#fcfaf6] uppercase tracking-wider mb-4">Resumen de tu compra</h3>

                    <div class="space-y-4 mb-4 max-h-80 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            <div class="flex gap-4 items-center">
                                @if($item->imagen)
                                    <img src="{{ asset($item->imagen) }}" alt="{{ $item->producto }}" class="w-20 h-20 object-cover rounded-xl">
                                @endif
                                <div class="text-sm text-[#4a3d33] dark:text-[#e0dcd3]">
                                    <p class="font-bold">{{ $item->producto }}</p>
                                    <p>Talle: {{ $item->talle }} · Color: {{ $item->color }}</p>
                                    <p>Cantidad: {{ $item->cantidad }} × $ {{ number_format($item->precio, 0, ',', '.') }}</p>
                                    <p class="font-bold">$ {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-sm text-[#4a3d33] dark:text-[#e0dcd3] space-y-2 border-t border-[#ede9e1] dark:border-[#2e2621] pt-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>$ {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Envío</span>
                            <span id="texto-envio">A elegir</span>
                        </div>
                        <div class="flex justify-between font-bold text-base text-[#1a1512] dark:text-white">
                            <span>Total</span>
                            <span id="texto-total">$ {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-white dark:bg-[#1a1512] hover:bg-gray-50 dark:hover:bg-[#2e241f] text-[#1a1512] dark:text-white border border-[#1a1512] dark:border-[#ede9e1]/20 text-xs font-bold py-4 rounded-xl uppercase tracking-widest transition-all shadow-md cursor-pointer">
                        Continuar
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

<script>
    const subtotalCompra = {{ (float) $subtotal }};

    function formatearPesos(numero) {
        return '$ ' + Number(numero).toLocaleString('es-AR', { maximumFractionDigits: 0 });
    }

    function actualizarMetodoPago() {
        const combo = document.getElementById('combo-metodo');
        const tipo = combo.options[combo.selectedIndex].dataset.tipo || '';

        document.getElementById('grupo-tarjeta-campos').style.display = (tipo === 'tarjeta') ? 'block' : 'none';
        document.getElementById('grupo-banco').style.display = (tipo === 'transferencia') ? 'block' : 'none';

        if (tipo !== 'transferencia') {
            document.getElementById('banco-oculto').value = '';
        }
    }

    function actualizarEnvio() {
        const combo = document.getElementById('combo-envio');
        const costo = parseFloat(combo.options[combo.selectedIndex].dataset.costo);
        const textoEnvio = document.getElementById('texto-envio');
        const textoTotal = document.getElementById('texto-total');

        if (isNaN(costo)) {
            textoEnvio.textContent = 'A elegir';
            textoTotal.textContent = formatearPesos(subtotalCompra);
            return;
        }

        textoEnvio.textContent = costo > 0 ? formatearPesos(costo) : 'Gratis';
        textoTotal.textContent = formatearPesos(subtotalCompra + costo);
    }

    function marcarBancoGrid(elemento) {
        document.querySelectorAll('.caja-banco').forEach(b => {
            b.style.backgroundColor = '';
            b.style.color = '';
            b.style.fontWeight = 'bold';
        });

        elemento.style.backgroundColor = '#4c3300';
        elemento.style.color = '#ffffff';
        elemento.style.fontWeight = '900';

        document.getElementById('banco-oculto').value = elemento.innerText.trim();
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarMetodoPago();
        actualizarEnvio();
    });
</script>
@endsection