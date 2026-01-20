<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};
use App\Models\Mesa;

new #[Layout('layouts.app')] #[Title('Admin - Mapa de Mesas')] class extends Component {
    public $zonaFilter = 'all';
    public $showModal = false;
    public $editingMesaId = null;
    public $nombre = '';
    public $zona = 'Salón Principal';
    public $capacidad = 2;
    public $estado = 'disponible';
    
    public function with(): array
    {
        $query = Mesa::query();
        
        if ($this->zonaFilter !== 'all') {
            $query->where('zona', $this->zonaFilter);
        }
        
        return [
            'mesas' => $query->orderBy('nombre')->get()
        ];
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->editingMesaId = null;
        $this->nombre = '';
        $this->zona = 'Salón Principal';
        $this->capacidad = 2;
        $this->estado = 'disponible';
    }
    
    public function editMesa($id)
    {
        $mesa = Mesa::findOrFail($id);
        $this->editingMesaId = $mesa->id;
        $this->nombre = $mesa->nombre;
        $this->zona = $mesa->zona;
        $this->capacidad = $mesa->capacidad;
        $this->estado = $mesa->estado;
        $this->showModal = true;
    }
    
    public function saveMesa()
    {
        $this->validate([
            'nombre' => 'required|min:2',
            'zona' => 'required',
            'capacidad' => 'required|integer|min:1|max:20',
            'estado' => 'required|in:disponible,ocupada,reservada',
        ]);
        
        $data = [
            'nombre' => $this->nombre,
            'zona' => $this->zona,
            'capacidad' => $this->capacidad,
            'estado' => $this->estado,
        ];
        
        if ($this->editingMesaId) {
            Mesa::find($this->editingMesaId)->update($data);
        } else {
            Mesa::create($data);
        }
        
        $this->closeModal();
    }
    
    public function cambiarEstado($id, $estado)
    {
        Mesa::find($id)->update(['estado' => $estado]);
    }
    
    public function deleteMesa($id)
    {
        Mesa::find($id)->delete();
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
            --status-free: #10b981;
            --status-busy: #f43f5e;
            --status-reserved: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-dark); display: flex; min-height: 100vh; }

        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: var(--white); display: flex; flex-direction: column; position: fixed; height: 100%; left: 0; top: 0; z-index: 10; }
        .brand { padding: 25px; font-size: 20px; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .menu { list-style: none; padding: 20px 0; }
        .menu-item { padding: 15px 25px; color: var(--sidebar-text); cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .menu-item:hover, .menu-item.active { background-color: rgba(255,255,255,0.05); color: var(--white); border-left: 4px solid var(--primary-color); }

        .main-content { margin-left: 260px; flex: 1; padding: 30px; position: relative; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: 600; }
        
        .btn-add {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .btn-add:hover { background-color: #bf360c; }

        .toolbar { background: var(--white); padding: 15px 20px; border-radius: 8px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); flex-wrap: wrap; gap: 15px; }
        .filters { display: flex; gap: 10px; }
        .filter-btn { background: none; border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 20px; cursor: pointer; color: var(--text-dark); font-size: 14px; transition: 0.2s; }
        .filter-btn.active, .filter-btn:hover { background-color: var(--sidebar-bg); color: var(--white); border-color: var(--sidebar-bg); }
        .legend { display: flex; gap: 15px; font-size: 13px; color: #64748b; }
        .legend-item { display: flex; align-items: center; gap: 5px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot.free { background-color: var(--status-free); }
        .dot.busy { background-color: var(--status-busy); }
        .dot.reserved { background-color: var(--status-reserved); }

        .tables-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }

        .table-card { background: var(--white); border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid var(--border-color); position: relative; transition: transform 0.2s, box-shadow 0.2s; }
        .table-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .table-card.disponible { border-top: 5px solid var(--status-free); }
        .table-card.ocupada { border-top: 5px solid var(--status-busy); }
        .table-card.reservada { border-top: 5px solid var(--status-reserved); }
        
        .card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .table-name { font-size: 18px; font-weight: 600; }
        .table-area { font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .capacity { background-color: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px; display: flex; align-items: center; gap: 4px; }
        .status-text { font-size: 14px; font-weight: 500; margin-bottom: 20px; }
        .disponible .status-text { color: var(--status-free); }
        .ocupada .status-text { color: var(--status-busy); }
        .reservada .status-text { color: var(--status-reserved); }
        
        .reservation-info { background-color: #f8fafc; padding: 10px; border-radius: 6px; font-size: 12px; color: #475569; margin-bottom: 15px; }
        
        .card-actions { display: flex; gap: 10px; }
        .btn-action { flex: 1; padding: 8px; border: 1px solid var(--border-color); background: white; border-radius: 4px; cursor: pointer; font-size: 12px; transition: 0.2s; }
        .btn-action:hover { background-color: #f1f5f9; }

        .modal {
            display: none;
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s;
        }
        .modal.show { display: flex; }

        .modal-content {
            background-color: var(--white);
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            animation: slideUp 0.3s;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            font-weight: bold;
            color: #94a3b8;
            cursor: pointer;
            transition: 0.2s;
        }
        .close-modal:hover { color: var(--primary-color); }

        .modal h2 { margin-bottom: 20px; color: var(--text-dark); }

        .modal-form-group { margin-bottom: 15px; }
        .modal-label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: 500; }
        .modal-input, .modal-select { 
            width: 100%; padding: 10px; border: 1px solid var(--border-color); 
            border-radius: 6px; font-size: 14px; outline: none; 
        }
        .modal-input:focus, .modal-select:focus { border-color: var(--primary-color); }
        
        .btn-save {
            width: 100%; padding: 12px; background-color: var(--primary-color); 
            color: white; border: none; border-radius: 6px; font-weight: 600; 
            cursor: pointer; margin-top: 10px;
        }
        .btn-save:hover { background-color: #bf360c; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .brand span, .menu-item span { display: none; }
            .main-content { margin-left: 70px; }
        }
    </style>

    <nav class="sidebar">
        <div class="brand"><i>🍽️</i> <span>SaborAdmin</span></div>
        <ul class="menu">
            <a href="#" class="menu-item"><i>📊</i> <span>Dashboard</span></a>
            <a href="#" class="menu-item"><i>📅</i> <span>Reservas</span></a>
            <a href="#" class="menu-item active"><i>🪑</i> <span>Mesas</span></a>
            <a href="#" class="menu-item"><i>👥</i> <span>Clientes</span></a>
            <a href="#" class="menu-item"><i>⚙️</i> <span>Configuración</span></a>
        </ul>
    </nav>

    <main class="main-content">
        <header class="header">
            <div>
                <h1>Gestión de Mesas</h1>
                <p style="color: #64748b; font-size: 14px;">Mapa en tiempo real</p>
            </div>
            <button wire:click="openModal" class="btn-add">
                <span>+</span> Agregar Mesa
            </button>
        </header>

        <div class="toolbar">
            <div class="filters">
                <button wire:click="$set('zonaFilter', 'all')" class="filter-btn {{ $zonaFilter === 'all' ? 'active' : '' }}">Todas</button>
                <button wire:click="$set('zonaFilter', 'Salón Principal')" class="filter-btn {{ $zonaFilter === 'Salón Principal' ? 'active' : '' }}">Salón Principal</button>
                <button wire:click="$set('zonaFilter', 'Terraza')" class="filter-btn {{ $zonaFilter === 'Terraza' ? 'active' : '' }}">Terraza</button>
                <button wire:click="$set('zonaFilter', 'Bar')" class="filter-btn {{ $zonaFilter === 'Bar' ? 'active' : '' }}">Bar</button>
            </div>
            <div class="legend">
                <div class="legend-item"><span class="dot free"></span> Disponible</div>
                <div class="legend-item"><span class="dot reserved"></span> Reservada</div>
                <div class="legend-item"><span class="dot busy"></span> Ocupada</div>
            </div>
        </div>

        <div class="tables-grid">
            @forelse($mesas as $mesa)
            <div class="table-card {{ $mesa->estado }}">
                <div class="card-header">
                    <div>
                        <div class="table-name">{{ $mesa->nombre }}</div>
                        <div class="table-area">{{ $mesa->zona }}</div>
                    </div>
                    <div class="capacity">👤 {{ $mesa->capacidad }}</div>
                </div>
                <div class="status-text">
                    @if($mesa->estado === 'disponible')
                        🟢 Disponible
                    @elseif($mesa->estado === 'ocupada')
                        🔴 Ocupada
                    @else
                        🟡 Reservada
                    @endif
                </div>
                <div style="height: 54px; display:flex; align-items:center; color:#94a3b8; font-size:12px;">
                    @if($mesa->estado === 'disponible')
                        Lista para asignar
                    @elseif($mesa->estado === 'ocupada')
                        En servicio
                    @else
                        Pendiente de llegada
                    @endif
                </div>
                <div class="card-actions">
                    @if($mesa->estado === 'disponible')
                        <button wire:click="cambiarEstado({{ $mesa->id }}, 'ocupada')" class="btn-action">Ocupar</button>
                        <button wire:click="cambiarEstado({{ $mesa->id }}, 'reservada')" class="btn-action">Reservar</button>
                    @elseif($mesa->estado === 'ocupada')
                        <button wire:click="cambiarEstado({{ $mesa->id }}, 'disponible')" class="btn-action">Liberar</button>
                    @else
                        <button wire:click="cambiarEstado({{ $mesa->id }}, 'disponible')" class="btn-action">Liberar</button>
                    @endif
                    <button wire:click="editMesa({{ $mesa->id }})" class="btn-action">✏️</button>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #94a3b8;">
                No hay mesas registradas
            </div>
            @endforelse
        </div>
    </main>

    <div class="modal {{ $showModal ? 'show' : '' }}">
        <div class="modal-content">
            <span wire:click="closeModal" class="close-modal">&times;</span>
            <h2>{{ $editingMesaId ? 'Editar Mesa' : 'Agregar Nueva Mesa' }}</h2>
            <form wire:submit.prevent="saveMesa">
                <div class="modal-form-group">
                    <label class="modal-label">Nombre de la Mesa</label>
                    <input type="text" wire:model="nombre" class="modal-input" placeholder="Ej. Mesa 15 o Terraza 4" required>
                    @error('nombre') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="modal-form-group">
                    <label class="modal-label">Zona</label>
                    <select wire:model="zona" class="modal-select">
                        <option value="Salón Principal">Salón Principal</option>
                        <option value="Terraza">Terraza</option>
                        <option value="Bar">Bar</option>
                        <option value="Privado">Privado</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label">Capacidad (Personas)</label>
                    <input type="number" wire:model="capacidad" class="modal-input" min="1" max="20" required>
                    @error('capacidad') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                @if($editingMesaId)
                <div class="modal-form-group">
                    <label class="modal-label">Estado</label>
                    <select wire:model="estado" class="modal-select">
                        <option value="disponible">Disponible</option>
                        <option value="ocupada">Ocupada</option>
                        <option value="reservada">Reservada</option>
                    </select>
                </div>
                @endif

                <button type="submit" class="btn-save">Guardar Mesa</button>
            </form>
        </div>
    </div>
</div>
