<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SaborAdmin - Gestión de Reservas' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #e65100;
            --sidebar-bg: #1e293b;
            --sidebar-text: #94a3b8;
            --bg-body: #f1f5f9;
            --white: #ffffff;
            --text-dark: #334155;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
        }
    </style>
    @livewireStyles
</head>
<body>
    {{ $slot }}
    
    @livewireScripts
</body>
</html>
