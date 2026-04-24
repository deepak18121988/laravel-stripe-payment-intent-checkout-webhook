<!DOCTYPE html>
<html lang="{{ e(session('lang', 'en')) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? e($title) . ' | ' : '' }}{{ e(config('app.name', 'Stripe Demo')) }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">

    <!-- Bootstrap -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MDB -->
    <link href="{{ asset('/css/mdb.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('/css/style.min.css') }}" rel="stylesheet">

    <style>
        /* ===== GLOBAL LAYOUT FIX ===== */

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
       }

        /* ===== CAROUSEL (ONLY WHEN USED) ===== */

        .carousel {
            height: 60vh;
        }

        @media (max-width: 768px) {
            .carousel {
                height: 40vh;
            }
        }
    </style>
</head>

<body class="grey lighten-3">

    @include('partials.header')

    <main class="mt-4 pt-2">
        @yield('content')
    </main>

    @include('partials.footer')

    @yield('scripts')

</body>
</html>