

<?php $__env->startSection('titulo', 'Acceso no permitido'); ?>
<?php $__env->startSection('codigo', '403'); ?>
<?php $__env->startSection('encabezado', 'No tenés acceso a esta página'); ?>
<?php $__env->startSection('mensaje', 'Esta sección es solo para quienes tienen permisos. Si creés que es un error, escribinos desde Soporte en tu cuenta.'); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/errors/403.blade.php ENDPATH**/ ?>