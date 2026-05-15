<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="ChefMaster — Intelligent Recipe Management & Discovery Platform">
    <title>ChefMaster — Culinary Platform</title>

    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

<div class="toast-container" id="toastContainer" aria-live="polite"></div>
<div class="modal-backdrop" id="modalBackdrop" style="display:none;" role="dialog" aria-modal="true"></div>

<?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>
</html><?php /**PATH D:\ChefMaster-fixed\resources\views/layouts/app.blade.php ENDPATH**/ ?>