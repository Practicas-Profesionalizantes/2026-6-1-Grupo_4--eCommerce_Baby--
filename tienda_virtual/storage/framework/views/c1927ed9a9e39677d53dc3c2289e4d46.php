

<?php $__env->startSection('content'); ?>
<?php
    $provincias = [
        'Buenos Aires', 'Ciudad Autónoma de Buenos Aires', 'Catamarca', 'Chaco', 'Chubut',
        'Córdoba', 'Corrientes', 'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 'La Rioja',
        'Mendoza', 'Misiones', 'Neuquén', 'Río Negro', 'Salta', 'San Juan', 'San Luis',
        'Santa Cruz', 'Santa Fe', 'Santiago del Estero', 'Tierra del Fuego', 'Tucumán',
    ];
?>
<div class="py-16 px-6 sm:px-12">
    <div class="max-w-6xl mx-auto">
        
        <div class="mb-10 text-center md:text-left">
            <span class="text-[10px] font-bold tracking-[0.3em] text-[#d98c8c] uppercase mb-2 block">Panel de Usuario</span>
            <h1 class="font-serif text-4xl sm:text-5xl text-[#4c3300]">Mi Cuenta</h1>
            <p class="text-sm text-[#7a5c3e] mt-2">Administrá tu información y gestioná tus pedidos.</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            
            <aside class="w-full md:w-1/3">
                <div class="bg-[#f2a6a6] rounded-[2.5rem] p-8 shadow-xl shadow-[#f2a6a6]/20 relative overflow-hidden">
                    <div class="flex flex-col items-center mt-2 mb-8 relative z-10">
                        <div class="w-24 h-24 rounded-full bg-white/40 flex items-center justify-center text-[#4c3300] mb-4 shadow-inner border-4 border-white/60 hover:scale-105 transition-transform duration-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <h2 class="text-lg font-bold text-[#4c3300] text-center"><?php echo e(Auth::user()->name ?? 'Invitado'); ?></h2>
                        <p class="text-xs text-[#4c3300]/70 mt-1 text-center"><?php echo e(Auth::user()->email ?? ''); ?></p>
                    </div>

                    <nav class="flex flex-col gap-2 relative z-10">
                        <a href="#" class="group relative flex items-center gap-3 px-5 py-4 bg-white text-[#4c3300] rounded-full text-sm font-bold shadow-md cursor-pointer focus:outline-none">
                            <span class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#f2a6a6] rounded-full animate-pulse"></span>
                            <svg class="w-4 h-4 text-[#d98c8c] group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Datos personales
                        </a>
                        <a href="<?php echo e(route('cuenta.pedidos')); ?>" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300] hover:shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:scale-110 group-hover:text-[#d98c8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Mis Pedidos</span>
                        </a>
                        <a href="<?php echo e(route('cuenta.apariencia')); ?>" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300] hover:shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 transition-all duration-500 group-hover:rotate-90 group-hover:text-[#d98c8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Configuración</span>
                        </a>
                    </nav>

                    <div class="mt-8 pt-6 border-t border-[#4c3300]/10 relative z-10">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-[#4c3300] text-white hover:bg-[#3a2600] rounded-full text-xs font-bold uppercase tracking-widest transition-all duration-300 active:scale-95 group shadow-md">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="w-full md:w-2/3">
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-[#4c3300]/5 border border-[#f0dfc8] h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#faebd7] via-[#f2a6a6] to-[#d98c8c]"></div>

                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-[#faf0e6] mt-2">
                        <div class="w-10 h-10 bg-[#faebd7] rounded-full flex items-center justify-center text-[#d98c8c]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="font-serif text-2xl text-[#4c3300]">Editar Información</h3>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="mb-8 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] px-5 py-4 text-sm text-[#4c3300]">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div id="mensaje" class="hidden mb-8 rounded-2xl bg-[#faebd7] border border-[#f0dfc8] px-5 py-4 text-sm text-[#4c3300]"></div>

                    <form id="form-cuenta" data-cliente-id="<?php echo e(Auth::user()->cliente->Id_cliente ?? ''); ?>" class="flex flex-col gap-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Nombre</label>
                                <input type="text" id="nombre" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Apellido</label>
                                <input type="text" id="apellido" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Correo electrónico</label>
                                <input type="email" value="<?php echo e(Auth::user()->email ?? ''); ?>" readonly class="w-full border border-[#f0dfc8] bg-[#faf0e6]/50 dark:bg-[#1f1a17] rounded-2xl px-5 py-4 text-sm text-[#7a5c3e] dark:text-[#b3a193] cursor-not-allowed">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Teléfono</label>
                                <input type="tel" id="telefono" placeholder="+54 11 0000 0000" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">DNI o CUIT</label>
                                <input type="text" id="dni" inputmode="numeric" placeholder="Solo números" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Condición frente al IVA</label>
                                <select id="condicion" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all cursor-pointer">
                                    <option value="">Elegí una opción</option>
                                    <option value="Consumidor Final">Consumidor Final</option>
                                    <option value="Resp. Inscripto">Responsable Inscripto</option>
                                </select>
                            </div>
                        </div>

                        <p class="text-[10px] font-bold tracking-widest text-[#d98c8c] uppercase pl-3 pt-2">Dirección de envío</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Calle</label>
                                <input type="text" id="calle" maxlength="40" placeholder="Calle" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Número</label>
                                <input type="text" id="numero" maxlength="8" placeholder="Número" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Departamento (opcional)</label>
                                <input type="text" id="departamento" maxlength="8" placeholder="Departamento" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Barrio</label>
                                <input type="text" id="barrio" maxlength="25" placeholder="Barrio" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Ciudad</label>
                                <input type="text" id="ciudad" placeholder="Ciudad" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Código postal</label>
                                <input type="text" id="cp" maxlength="8" placeholder="1439" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all">
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-3">Provincia</label>
                            <select id="provincia" class="w-full border border-[#f0dfc8] bg-[#faf0e6] dark:bg-[#12100e] rounded-2xl px-5 py-4 text-sm text-[#4c3300] dark:text-[#faf0e6] focus:outline-none focus:border-[#f2a6a6] focus:ring-2 focus:ring-[#f2a6a6]/20 transition-all cursor-pointer">
                                <option value="">Elegí una provincia</option>
                                <?php $__currentLoopData = $provincias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provincia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($provincia); ?>"><?php echo e($provincia); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="pt-4 mt-auto">
                            <button type="submit" class="w-full sm:w-auto bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-10 py-4 rounded-full transition-all duration-300 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-cuenta');
    const mensaje = document.getElementById('mensaje');
    const idCliente = form.dataset.clienteId;
    const csrf = '<?php echo e(csrf_token()); ?>';

    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    const telefono = document.getElementById('telefono');
    const dni = document.getElementById('dni');
    const condicion = document.getElementById('condicion');
    const calle = document.getElementById('calle');
    const numero = document.getElementById('numero');
    const departamento = document.getElementById('departamento');
    const barrio = document.getElementById('barrio');
    const cp = document.getElementById('cp');
    const ciudad = document.getElementById('ciudad');
    const provincia = document.getElementById('provincia');

    const formatoCp = /^([0-9]{4}|[A-Za-z][0-9]{4}[A-Za-z]{3})$/;

    function mostrarMensaje(texto) {
        mensaje.textContent = texto;
        mensaje.classList.remove('hidden');
        mensaje.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    async function primerError(respuesta) {
        try {
            const detalle = await respuesta.json();
            if (detalle.errors) {
                return Object.values(detalle.errors)[0][0];
            }
            return detalle.message || 'Revisá los datos.';
        } catch (error) {
            return 'Revisá los datos.';
        }
    }

    async function cargarDatos() {
        try {
            const resCliente = await fetch('/api/cliente/' + idCliente, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            if (!resCliente.ok) throw new Error('datos personales: error ' + resCliente.status);

            const cliente = await resCliente.json();
            nombre.value = cliente.Nombre ?? '';
            apellido.value = cliente.Apellido ?? '';
            telefono.value = (cliente.Telefono && cliente.Telefono !== '00000000') ? cliente.Telefono : '';
            dni.value = cliente.DNI_CUIT ?? '';
            condicion.value = cliente.Condicion_IVA ?? '';

            const resDireccion = await fetch('/api/cliente/' + idCliente + '/direccion', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            if (!resDireccion.ok) throw new Error('dirección: error ' + resDireccion.status);

            const direccion = await resDireccion.json();
            if (direccion) {
                const separada = direccion.Calle !== null && direccion.Calle !== undefined && direccion.Calle !== '';
                calle.value = separada ? direccion.Calle : (direccion.Calle_Numero ?? '');
                numero.value = direccion.Numero ?? '';
                departamento.value = direccion.Departamento ?? '';
                barrio.value = direccion.Barrio ?? '';
                cp.value = direccion.Codigo_Postal ?? '';
                ciudad.value = direccion.Ciudad ?? '';
                provincia.value = direccion.Provincia ?? '';
                if (provincia.value !== (direccion.Provincia ?? '')) {
                    provincia.value = '';
                }
            }
        } catch (error) {
            mostrarMensaje('No se pudieron cargar tus datos. (' + error.message + ')');
        }
    }

    form.addEventListener('submit', async function (evento) {
        evento.preventDefault();

        if (nombre.value.trim() === '' || apellido.value.trim() === '' || telefono.value.trim() === '' || dni.value.trim() === '' || condicion.value === '') {
            mostrarMensaje('Completá nombre, apellido, teléfono, DNI o CUIT y condición frente al IVA. Son obligatorios.');
            return;
        }

        if (calle.value.trim() === '' || numero.value.trim() === '' || barrio.value.trim() === '' || cp.value.trim() === '' || ciudad.value.trim() === '' || provincia.value === '') {
            mostrarMensaje('Completá la dirección de envío: calle, número, barrio, código postal, ciudad y provincia. Solo el departamento es opcional.');
            return;
        }

        if (!formatoCp.test(cp.value.trim())) {
            mostrarMensaje('El código postal no es válido: son 4 números, o el formato A1234BCD.');
            return;
        }

        const datos = {
            Nombre: nombre.value.trim(),
            Apellido: apellido.value.trim(),
            Telefono: telefono.value.trim(),
            DNI_CUIT: dni.value.trim(),
            Condicion_IVA: condicion.value
        };

        try {
            const resCliente = await fetch('/api/cliente/' + idCliente, {
                method: 'PUT',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(datos)
            });
            if (resCliente.status === 422) {
                mostrarMensaje(await primerError(resCliente));
                return;
            }
            if (!resCliente.ok) throw new Error('datos personales: error ' + resCliente.status);

            const resDireccion = await fetch('/api/cliente/' + idCliente + '/direccion', {
                method: 'PUT',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    Calle: calle.value.trim(),
                    Numero: numero.value.trim(),
                    Departamento: departamento.value.trim(),
                    Barrio: barrio.value.trim(),
                    Ciudad: ciudad.value.trim(),
                    Provincia: provincia.value,
                    Codigo_Postal: cp.value.trim()
                })
            });
            if (resDireccion.status === 422) {
                mostrarMensaje(await primerError(resDireccion));
                return;
            }
            if (!resDireccion.ok) throw new Error('dirección: error ' + resDireccion.status);

            mostrarMensaje('¡Datos actualizados con éxito!');
        } catch (error) {
            mostrarMensaje('No se pudieron guardar los cambios. (' + error.message + ')');
        }
    });

    if (idCliente) {
        cargarDatos();
    } else {
        mostrarMensaje('No encontramos tu perfil de cliente.');
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/cuenta/index.blade.php ENDPATH**/ ?>