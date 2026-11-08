

<?php $__env->startSection('titulo', 'La sesión venció'); ?>
<?php $__env->startSection('codigo', '419'); ?>
<?php $__env->startSection('encabezado', 'Tu sesión venció'); ?>
<?php $__env->startSection('mensaje', 'Pasó mucho tiempo sin actividad y, por seguridad, cerramos el formulario. Volvé a empezar y listo.'); ?>

<?php $__env->startSection('botones'); ?>
    <a href="/login" class="boton-principal inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Iniciar sesión</a>
    <a href="/" class="boton-borde inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Volver al inicio</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/errors/419.blade.php ENDPATH**/ ?>