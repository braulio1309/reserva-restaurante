<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};
use Livewire\WithPagination;
use App\Models\Cliente;

new #[Layout('layouts.app')] #[Title('Admin - Gestión de Clientes')] class extends Component {
    use WithPagination;
    
    public $search = '';
    public $tipoFilter = '';
    public $orderBy = 'created_at';
    
    // Modal properties
    public $showModal = false;
    public $editingClienteId = null;
    public $nombre = '';
    public $email = '';
    public $telefono = '';
    public $tipo = 'nuevo';
    public $notas = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingTipoFilter()
    {
        $this->resetPage();
    }
    
    public function with(): array
    {
        $query = Cliente::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('telefono', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->tipoFilter) {
            $query->where('tipo', $this->tipoFilter);
        }
        
        $query->orderBy($this->orderBy, 'desc');
        
        return [
            'clientes' => $query->paginate(10)
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
        $this->editingClienteId = null;
        $this->nombre = '';
        $this->email = '';
        $this->telefono = '';
        $this->tipo = 'nuevo';
        $this->notas = '';
    }
    
    public function editCliente($id)
    {
        $cliente = Cliente::findOrFail($id);
        $this->editingClienteId = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->email = $cliente->email;
        $this->telefono = $cliente->telefono;
        $this->tipo = $cliente->tipo;
        $this->notas = $cliente->notas;
        $this->showModal = true;
    }
    
    public function saveCliente()
    {
        $this->validate([
            'nombre' => 'required|min:3',
            'telefono' => 'required',
            'email' => 'nullable|email',
            'tipo' => 'required|in:nuevo,vip,regular',
        ]);
        
        $data = [
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'tipo' => $this->tipo,
            'notas' => $this->notas,
        ];
        
        if ($this->editingClienteId) {
            Cliente::find($this->editingClienteId)->update($data);
        } else {
            Cliente::create($data);
        }
        
        $this->closeModal();
        $this->resetPage();
    }
    
    public function deleteCliente($id)
    {
        Cliente::find($id)->delete();
        $this->resetPage();
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
            --tag-vip-bg: #7e22ce;
            --tag-vip-text: #ffffff;
            --tag-new-bg: #3b82f6;
            --tag-new-text: #ffffff;
            --tag-regular-bg: #f1f5f9;
            --tag-regular-text: #475569;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-dark); display: flex; min-height: 100vh; }

        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: var(--white); display: flex; flex-direction: column; position: fixed; height: 100%; left: 0; top: 0; z-index: 10; }
        .brand { padding: 25px; font-size: 20px; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .menu { list-style: none; padding: 20px 0; }
        .menu-item { padding: 15px 25px; color: var(--sidebar-text); cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .menu-item:hover, .menu-item.active { background-color: rgba(255,255,255,0.05); color: var(--white); border-left: 4px solid var(--primary-color); }

        .main-content { margin-left: 260px; flex: 1; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: 600; }
        
        .btn-add {
            background-color: var(--primary-color); color: white; padding: 10px 20px; border: none; 
            border-radius: 6px; cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: 0.3s;
        }
        .btn-add:hover { background-color: #bf360c; }

        .search-toolbar {
            background: var(--white); padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
            display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); gap: 15px; flex-wrap: wrap;
        }

        .search-group { display: flex; gap: 10px; flex: 1; min-width: 300px; }
        
        .search-input {
            flex: 1; padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; outline: none; transition: 0.3s;
        }
        .search-input:focus { border-color: var(--primary-color); }

        .filter-select {
            padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; outline: none; background-color: white; cursor: pointer;
        }

        .table-container { background: var(--white); border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        
        th { text-align: left; padding: 18px 20px; background-color: #f8fafc; color: #64748b; font-size: 13px; font-weight: 600; text-transform: uppercase; border-bottom: 1px solid var(--border-color); }
        td { padding: 18px 20px; border-bottom: 1px solid var(--border-color); font-size: 14px; vertical-align: middle; }
        tr:hover { background-color: #fcfcfc; }

        .client-profile { display: flex; align-items: center; gap: 12px; }
        .avatar-circle {
            width: 40px; height: 40px; border-radius: 50%; background-color: #e2e8f0; 
            display: flex; justify-content: center; align-items: center; font-weight: 600; color: #475569;
        }
        .client-info div { line-height: 1.4; }
        .client-email { font-size: 12px; color: #94a3b8; }

        .tag { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .tag.vip { background-color: var(--tag-vip-bg); color: var(--tag-vip-text); }
        .tag.nuevo { background-color: var(--tag-new-bg); color: var(--tag-new-text); }
        .tag.regular { background-color: var(--tag-regular-bg); color: var(--tag-regular-text); }

        .action-icon { background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 16px; margin-right: 8px; transition: 0.2s; }
        .action-icon:hover { color: var(--primary-color); }

        .pagination { padding: 20px; display: flex; justify-content: flex-end; gap: 5px; }

        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; animation: fadeIn 0.3s; }
        .modal.show { display: flex; }
        .modal-content { background-color: var(--white); padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); position: relative; animation: slideUp 0.3s; }
        .close-modal { position: absolute; top: 20px; right: 25px; font-size: 28px; font-weight: bold; color: #94a3b8; cursor: pointer; }
        .close-modal:hover { color: var(--primary-color); }
        .modal-form-group { margin-bottom: 15px; }
        .modal-label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: 500; }
        .modal-input, .modal-select, .modal-textarea { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; outline: none; }
        .modal-input:focus { border-color: var(--primary-color); }
        .btn-save { width: 100%; padding: 12px; background-color: var(--primary-color); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .btn-save:hover { background-color: #bf360c; }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .brand span, .menu-item span { display: none; }
            .main-content { margin-left: 70px; }
            .table-container { overflow-x: auto; }
            .search-toolbar { flex-direction: column; align-items: stretch; }
        }
    </style>

    <nav class="sidebar">
        <div class="brand"><i>🍽️</i> <span>SaborAdmin</span></div>
        <ul class="menu">
            <a href="#" class="menu-item"><i>📊</i> <span>Dashboard</span></a>
            <a href="#" class="menu-item"><i>📅</i> <span>Reservas</span></a>
            <a href="#" class="menu-item"><i>🪑</i> <span>Mesas</span></a>
            <a href="#" class="menu-item active"><i>👥</i> <span>Clientes</span></a>
            <a href="#" class="menu-item"><i>⚙️</i> <span>Configuración</span></a>
        </ul>
    </nav>

    <main class="main-content">
        
        <header class="header">
            <div>
                <h1>Cartera de Clientes</h1>
                <p style="color: #64748b; font-size: 14px;">Gestiona y conoce a tus comensales</p>
            </div>
            <button wire:click="openModal" class="btn-add">
                <span>+</span> Nuevo Cliente
            </button>
        </header>

        <div class="search-toolbar">
            <div class="search-group">
                <input type="text" wire:model.live="search" class="search-input" placeholder="Buscar por nombre, correo o teléfono...">
            </div>
            <select wire:model.live="tipoFilter" class="filter-select">
                <option value="">Todos los Tipos</option>
                <option value="vip">💎 VIP</option>
                <option value="regular">🔄 Regular</option>
                <option value="nuevo">🆕 Nuevo</option>
            </select>
            <select wire:model.live="orderBy" class="filter-select">
                <option value="created_at">Más Recientes</option>
                <option value="ultima_visita">Última Visita</option>
                <option value="visitas">Más Visitas</option>
            </select>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Visitas</th>
                        <th>Última Visita</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td>
                            <div class="client-profile">
                                <div class="avatar-circle">{{ strtoupper(substr($cliente->nombre, 0, 2)) }}</div>
                                <div class="client-info">
                                    <div style="font-weight: 500;">{{ $cliente->nombre }}</div>
                                    <div class="client-email">{{ $cliente->email ?: 'Sin correo' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $cliente->telefono }}</td>
                        <td><span class="tag {{ $cliente->tipo }}">{{ strtoupper($cliente->tipo) }}</span></td>
                        <td>{{ $cliente->visitas ?? 0 }}</td>
                        <td>{{ $cliente->ultima_visita ? $cliente->ultima_visita->format('d M Y') : '-' }}</td>
                        <td><span style="font-size: 12px; color: #64748b;">{{ $cliente->notas ?: '-' }}</span></td>
                        <td>
                            <button wire:click="editCliente({{ $cliente->id }})" class="action-icon" title="Editar">✏️</button>
                            <button wire:click="deleteCliente({{ $cliente->id }})" wire:confirm="¿Estás seguro de eliminar este cliente?" class="action-icon" title="Eliminar">🗑️</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 40px;">
                            No se encontraron clientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $clientes->links() }}
            </div>
        </div>

    </main>

    <div class="modal {{ $showModal ? 'show' : '' }}">
        <div class="modal-content">
            <span wire:click="closeModal" class="close-modal">&times;</span>
            <h2>{{ $editingClienteId ? 'Editar Cliente' : 'Registrar Cliente' }}</h2>
            <form wire:submit.prevent="saveCliente">
                <div class="modal-form-group">
                    <label class="modal-label">Nombre Completo</label>
                    <input type="text" wire:model="nombre" class="modal-input" placeholder="Ej. Roberto Gómez" required>
                    @error('nombre') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
                
                <div style="display: flex; gap: 15px;">
                    <div class="modal-form-group" style="flex: 1;">
                        <label class="modal-label">Teléfono</label>
                        <input type="tel" wire:model="telefono" class="modal-input" placeholder="+52..." required>
                        @error('telefono') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="modal-form-group" style="flex: 1;">
                        <label class="modal-label">Correo (Opcional)</label>
                        <input type="email" wire:model="email" class="modal-input" placeholder="correo@ejemplo.com">
                        @error('email') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label">Etiqueta</label>
                    <select wire:model="tipo" class="modal-select">
                        <option value="nuevo">Nuevo</option>
                        <option value="vip">VIP</option>
                        <option value="regular">Regular</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label">Notas / Alergias</label>
                    <textarea wire:model="notas" class="modal-textarea" rows="3" placeholder="Detalles importantes del cliente..."></textarea>
                </div>

                <button type="submit" class="btn-save">Guardar Cliente</button>
            </form>
        </div>
    </div>
</div>
