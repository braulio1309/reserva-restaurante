<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborAdmin - Gestión de Reservas</title>
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
            --success-bg: #dcfce7;
            --success-text: #166534;
            --warning-bg: #fef9c3;
            --warning-text: #854d0e;
            --danger-bg: #fee2e2;
            --danger-text: #991b1b;
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
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--white);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 10;
        }

        .brand {
            padding: 25px;
            font-size: 20px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu {
            list-style: none;
            padding: 20px 0;
        }

        .menu-item {
            padding: 15px 25px;
            color: var(--sidebar-text);
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.05);
            color: var(--white);
            border-left: 4px solid var(--primary-color);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .brand span,
            .menu-item span {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
        }
    </style>
    @livewireStyles
</head>
<body>

    <nav class="sidebar">
        <div class="brand">
            <i>🍽️</i> <span>SaborAdmin</span>
        </div>
        <ul class="menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i>📊</i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i>📅</i> <span>Reservas</span>
            </a>
            <a href="{{ route('disponibilidad.index') }}" class="menu-item {{ request()->routeIs('disponibilidad.index') ? 'active' : '' }}">
                <i>🪑</i> <span>Mesas</span>
            </a>
            <a href="{{ route('clientes.index') }}" class="menu-item {{ request()->routeIs('clientes.index') ? 'active' : '' }}">
                <i>👥</i> <span>Clientes</span>
            </a>
            <a href="#" class="menu-item">
                <i>⚙️</i> <span>Configuración</span>
            </a>
        </ul>
    </nav>

    <main class="main-content">
        <x-admin.dashboard />
    </main>

    @livewireScripts
</body>
</html>
