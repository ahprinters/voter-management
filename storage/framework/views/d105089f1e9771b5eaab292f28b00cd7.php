<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    <?php echo e(filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel')); ?>

</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php echo app('flux')->fluxAppearance(); ?>

<?php /**PATH C:\Users\ahps3\Herd\voter-management\resources\views/partials/head.blade.php ENDPATH**/ ?>