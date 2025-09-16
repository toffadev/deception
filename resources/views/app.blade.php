<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    
    <!-- Scripts -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/3xwcpwxavpoajgu2hfs7qpnr7ml9wqoe9xgdtqoqtsowk771/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- Routes Ziggy -->
    @php
        $isAdmin = Request::is('admin*');
    @endphp
    
    @if($isAdmin)
        @routes
        @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @else
        @vite(['resources/css/client.css', 'resources/js/client.js'])
    @endif
    
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
    
    <!-- Formulaire caché pour les méthodes PUT/DELETE -->
    <form id="method-form" style="display: none;">
        @csrf
    </form>
</body>
</html>