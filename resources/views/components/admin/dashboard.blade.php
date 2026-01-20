<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

new #[Layout('layouts.app')] #[Title('Admin - Gestión de Reservas')] class extends Component {
    public $reservasHoy = 0;
    public $pendientes = 0;
    public $confirmadas = 0;
    public $comensalesTotales = 0;
    public $reservasRecientes = [];
    
    public function mount()
    {
        $this->loadStats();
        $this->loadRecentReservations();
    }
    
    public function loadStats()
    {
        $hoy = now()->toDateString();
        
        $this->reservasHoy = Reserva::whereDate('fecha', $hoy)->count();
        $this->pendientes = Reserva::where('estado', 'pendiente')->count();
        $this->confirmadas = Reserva::where('estado', 'confirmada')->count();
        $this->comensalesTotales = Reserva::whereDate('fecha', $hoy)->sum('num_personas');
    }
    
    public function loadRecentReservations()
    {
        $this->reservasRecientes = Reserva::with('mesa')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }
    
    public function confirmarReserva($id)
    {
        $reserva = Reserva::find($id);
        if ($reserva) {
            $reserva->update(['estado' => 'confirmada']);
            $this->loadStats();
            $this->loadRecentReservations();
        }
    }
    
    public function eliminarReserva($id)
    {
        $reserva = Reserva::find($id);
        if ($reserva) {
            $reserva->delete();
            $this->loadStats();
            $this->loadRecentReservations();
        }
    }
}; ?>

<div>
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

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border-left: 4px solid var(--primary-color);
        }

        .stat-card h3 {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: 700;
            margin-top: 5px;
        }

        .table-container {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-new {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            background-color: #f8fafc;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge.pending { background-color: var(--warning-bg); color: var(--warning-text); }
        .badge.confirmed { background-color: var(--success-bg); color: var(--success-text); }
        .badge.cancelled { background-color: var(--danger-bg); color: var(--danger-text); }
        .badge.pendiente { background-color: var(--warning-bg); color: var(--warning-text); }
        .badge.confirmada { background-color: var(--success-bg); color: var(--success-text); }
        .badge.cancelada { background-color: var(--danger-bg); color: var(--danger-text); }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            padding: 5px;
            border-radius: 4px;
            transition: 0.2s;
        }
        .action-btn.check:hover { background-color: var(--success-bg); color: var(--success-text); }
        .action-btn.trash:hover { background-color: var(--danger-bg); color: var(--danger-text); }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .brand span, .menu-item span { display: none; }
            .main-content { margin-left: 70px; }
            .table-container { overflow-x: auto; }
        }
    </style>

    <nav class="sidebar">
        <div class="brand">
            <i>🍽️</i> <span>SaborAdmin</span>
        </div>
        <ul class="menu">
            <a href="#" class="menu-item active">
                <i>📊</i> <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item">
                <i>📅</i> <span>Reservas</span>
            </a>
            <a href="#" class="menu-item">
                <i>🪑</i> <span>Mesas</span>
            </a>
            <a href="#" class="menu-item">
                <i>👥</i> <span>Clientes</span>
            </a>
            <a href="#" class="menu-item">
                <i>⚙️</i> <span>Configuración</span>
            </a>
        </ul>
    </nav>

    <main class="main-content">
        
        <header class="header">
            <div>
                <h1>Gestión de Reservas</h1>
                <p style="color: #64748b; font-size: 14px;">{{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}</p>
            </div>
            <div class="user-profile">
                <span>Hola, <strong>Admin</strong></span>
                <div class="avatar">A</div>
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Reservas Hoy</h3>
                <div class="number">{{ $reservasHoy }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #eab308;">
                <h3>Pendientes</h3>
                <div class="number">{{ $pendientes }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #22c55e;">
                <h3>Confirmadas</h3>
                <div class="number">{{ $confirmadas }}</div>
            </div>
            <div class="stat-card">
                <h3>Comensales Totales</h3>
                <div class="number">{{ $comensalesTotales }}</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>Reservas Recientes</h2>
                <button class="btn-new">+ Nueva Reserva Manual</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha / Hora</th>
                        <th>Personas</th>
                        <th>Contacto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasRecientes as $reserva)
                    <tr>
                        <td>#RES-{{ $reserva->id }}</td>
                        <td>
                            <strong>{{ $reserva->nombre_cliente }}</strong><br>
                            <small style="color:#64748b">{{ $reserva->mesa ? $reserva->mesa->nombre : 'Sin mesa' }}</small>
                        </td>
                        <td>{{ $reserva->fecha->format('d M, Y') }}<br>{{ $reserva->hora }}</td>
                        <td>{{ $reserva->num_personas }} Pers.</td>
                        <td>{{ $reserva->telefono_cliente ?: $reserva->email_cliente }}</td>
                        <td><span class="badge {{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span></td>
                        <td>
                            @if($reserva->estado === 'pendiente')
                                <button wire:click="confirmarReserva({{ $reserva->id }})" class="action-btn check" title="Confirmar">✅</button>
                            @endif
                            <button wire:click="eliminarReserva({{ $reserva->id }})" class="action-btn trash" title="Eliminar">🗑️</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 40px;">
                            No hay reservas recientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>
