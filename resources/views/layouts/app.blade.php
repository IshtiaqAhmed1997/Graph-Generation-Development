<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pharma Portal') }}</title>

    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f9ff;
            color: #333;
        }

        header.bg-white.shadow {
            background-color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
        }

        .content-wrapper main {
            padding: 2rem;
        }

        /* Ensure table and content take full width */
        .container-fluid {
            max-width: 100% !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    @include('layouts.navigation')

    <div class="content-wrapper">
        @isset($header)
            <header class="bg-white shadow p-3 mb-4 rounded">
                {{ $header }}
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>