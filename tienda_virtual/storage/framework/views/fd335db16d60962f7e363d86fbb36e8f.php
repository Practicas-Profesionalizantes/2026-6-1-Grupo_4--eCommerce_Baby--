

<?php $__env->startSection('titulo', $titulo); ?>

<?php $__env->startSection('contenido'); ?>
    <h1 class="font-serif text-4xl mb-4"><?php echo e($titulo); ?></h1>
    <p class="text-lg"><?php echo e($texto); ?></p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bimol\Desktop\tienda_virtual\resources\views/admin/seccion.blade.php ENDPATH**/ ?>