<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ChefMaster — Intelligent Recipe Management & Discovery Platform">
    <title>ChefMaster — Culinary Platform</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

<div class="toast-container" id="toastContainer" aria-live="polite"></div>
<div class="modal-backdrop" id="modalBackdrop" style="display:none;" role="dialog" aria-modal="true"></div>

@include('layouts.header')

@yield('content')

@include('layouts.footer')

</body>
</html>