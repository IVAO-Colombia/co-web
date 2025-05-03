<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Web Team" />
    <meta name="description"
        content="En IVAO Colombia, división de IVAO (International Virtual Aviation Organisation), puedes volar o controlar un aeropuerto con el máximo profesionalismo posible. Además es totalmente gratis.">
    <link rel="icon" type="image/png" href="{{ asset('theme-1/images/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Document title -->
    <title>IVAO CO</title>
    <!-- Stylesheets & Fonts -->

    {{-- Google Fonts --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=edit" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- Alpine script --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- WYSIWYG (TRIX-EDITOR) --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <link href="{{ asset('theme-1/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('theme-1/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme-1/css/custom.css') }}">


    @livewireStyles
    @stack('styles')
</head>