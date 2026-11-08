

<?php $__env->startSection('content'); ?>
<div class="py-12 px-4 sm:px-8 max-w-4xl mx-auto" style="font-family: sans-serif;">
    <div class="bg-[#fcfaf6] dark:bg-[#151210] rounded-[2.5rem] p-8 sm:p-12 border border-[#ede9e1] dark:border-[#2e2621] shadow-sm transition-colors duration-200">

        <h2 class="text-2xl text-[#1a1512] dark:text-[#fcfaf6] text-center mb-8 font-normal tracking-wide">
            Vista De Panel De Datos Del Comprador
        </h2>

        <?php if($errors->any()): ?>
            <div id="caja-errores" style="background:#f8e4e4; color:#8a2e2e; border:1px solid #e6bcbc; border-radius:1rem; padding:1rem 1.25rem; margin:0 auto 2rem auto; max-width:42rem; font-size:0.875rem;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p style="margin:0 0 0.25rem 0;"><?php echo e($error); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form id="form-comprador" action="<?php echo e(url('/checkout/datos/guardar')); ?>" method="POST" novalidate class="space-y-8 max-w-2xl mx-auto">
            <?php echo csrf_field(); ?>

            <div>
                <h4 class="text-[11px] font-bold tracking-widest text-[#7a6f66] dark:text-[#b3a79d] uppercase mb-4">Datos del destinatario</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Nombre</label>
                        <input type="text" name="nombre" value="<?php echo e(old('nombre', $form['nombre'] ?? '')); ?>" placeholder="Nombre" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Apellido</label>
                        <input type="text" name="apellido" value="<?php echo e(old('apellido', $form['apellido'] ?? '')); ?>" placeholder="Apellido" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="sm:col-span-2 space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Teléfono</label>
                        <input type="tel" name="telefono" value="<?php echo e(old('telefono', $form['telefono'] ?? '')); ?>" placeholder="Teléfono" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[11px] font-bold tracking-widest text-[#7a6f66] dark:text-[#b3a79d] uppercase mb-4">Domicilio del destinatario</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="sm:col-span-3 space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Calle</label>
                        <input type="text" name="calle" value="<?php echo e(old('calle', $form['calle'] ?? '')); ?>" placeholder="Calle" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Número</label>
                        <input type="text" name="numero" value="<?php echo e(old('numero', $form['numero'] ?? '')); ?>" placeholder="Número" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="sm:col-span-2 space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Departamento (Opcional)</label>
                        <input type="text" name="departamento" value="<?php echo e(old('departamento', $form['departamento'] ?? '')); ?>" placeholder="Departamento" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm">
                    </div>
                    <div class="sm:col-span-3 space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Barrio</label>
                        <input type="text" name="barrio" value="<?php echo e(old('barrio', $form['barrio'] ?? '')); ?>" placeholder="Barrio" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="sm:col-span-3 space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Ciudad</label>
                        <input type="text" name="ciudad" value="<?php echo e(old('ciudad', $form['ciudad'] ?? '')); ?>" placeholder="Ciudad" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Código postal</label>
                        <input type="text" name="codigo_postal" value="<?php echo e(old('codigo_postal', $form['codigo_postal'] ?? '')); ?>" placeholder="Código postal" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="sm:col-span-2 space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Provincia</label>
                        <select name="provincia" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm cursor-pointer" required>
                            <option value="" disabled <?php echo e(old('provincia', $form['provincia'] ?? '') === '' ? 'selected' : ''); ?>>Provincia</option>
                            <?php $__currentLoopData = $provincias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provincia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($provincia); ?>" <?php echo e(old('provincia', $form['provincia'] ?? '') === $provincia ? 'selected' : ''); ?>><?php echo e($provincia); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[11px] font-bold tracking-widest text-[#7a6f66] dark:text-[#b3a79d] uppercase mb-4">Datos de facturacion</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">DNI o CUIT</label>
                        <input type="text" name="dni" inputmode="numeric" value="<?php echo e(old('dni', $form['dni'] ?? '')); ?>" placeholder="Solo números" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none placeholder-[#8c7e74] text-sm" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-[#7a6f66] dark:text-[#b3a79d] uppercase tracking-wider block pl-1">Condición frente al IVA</label>
                        <select name="condicion_iva" class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] text-[#4a3d33] dark:text-[#e0dcd3] rounded-2xl focus:outline-none text-sm cursor-pointer" required>
                            <option value="" disabled <?php echo e(old('condicion_iva', $form['condicion_iva'] ?? '') === '' ? 'selected' : ''); ?>>Condición frente al IVA</option>
                            <?php $__currentLoopData = $condiciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($valor); ?>" <?php echo e(old('condicion_iva', $form['condicion_iva'] ?? '') === $valor ? 'selected' : ''); ?>><?php echo e($etiqueta); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="w-full p-4 bg-[#f2ede4] dark:bg-[#26201b] rounded-2xl flex items-center gap-3">
                    <input type="checkbox" id="input-terminos" name="terminos" value="1" <?php echo e(old('terminos', $form['terminos'] ?? null) ? 'checked' : ''); ?> class="w-4 h-4 rounded text-[#7a6f66]" required>
                    <label for="input-terminos" class="text-sm font-normal text-[#5c524a] dark:text-[#b3a79d] cursor-pointer">Acepto terminos y condiciones</label>
                </div>
            </div>

            <div class="text-center pt-6">
                <button type="submit"
                        class="bg-white dark:bg-[#1a1512] hover:bg-gray-50 dark:hover:bg-[#2e241f] text-[#1a1512] dark:text-white border border-[#1a1512] dark:border-[#ede9e1]/20 text-xs font-bold py-4 px-20 rounded-xl uppercase tracking-wider transition-all shadow-sm cursor-pointer">
                    Aplicar
                </button>
            </div>
        </form>

    </div>
</div>

<?php if($errors->any()): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var caja = document.getElementById('caja-errores');
        if (caja) {
            caja.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/comprador.blade.php ENDPATH**/ ?>