<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\Reserva;
use App\Models\Cliente;

new #[Title('Reserva tu Mesa - Restaurante Sabor y Estilo')] class extends Component {
    public $nombre_cliente = '';
    public $email_cliente = '';
    public $telefono_cliente = '';
    public $fecha = '';
    public $hora = '';
    public $num_personas = '';
    public $notas = '';
    public $successMessage = '';
    
    public function mount()
    {
        // Set minimum date to today
        $this->fecha = now()->toDateString();
    }
    
    public function submitReservation()
    {
        $validated = $this->validate([
            'nombre_cliente' => 'required|min:3',
            'email_cliente' => 'nullable|email',
            'telefono_cliente' => 'required',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'num_personas' => 'required|integer|min:1|max:20',
        ]);
        
        $validated['notas'] = $this->notas;
        $validated['estado'] = 'pendiente';
        
        // Check if cliente exists, if not create
        if ($this->email_cliente) {
            $cliente = Cliente::firstOrCreate(
                ['email' => $this->email_cliente],
                [
                    'nombre' => $this->nombre_cliente,
                    'telefono' => $this->telefono_cliente,
                    'tipo' => 'nuevo',
                ]
            );
            $validated['cliente_id'] = $cliente->id;
        }
        
        // Create reservation
        Reserva::create($validated);
        
        $this->successMessage = '¡Tu reserva ha sido registrada exitosamente! Te contactaremos pronto para confirmar.';
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->nombre_cliente = '';
        $this->email_cliente = '';
        $this->telefono_cliente = '';
        $this->fecha = now()->toDateString();
        $this->hora = '';
        $this->num_personas = '';
        $this->notas = '';
    }
}; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Mesa - Restaurante Sabor y Estilo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #e65100;
            --secondary-color: #263238;
            --bg-light: #f5f7fa;
            --white: #ffffff;
            --border-color: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--secondary-color);
            line-height: 1.6;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .booking-container {
            background-color: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-icon {
            font-size: 40px;
            color: var(--primary-color);
            display: block;
            margin-bottom: 5px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
            color: #546e7a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-input, 
        .form-textarea, 
        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-input:focus, 
        .form-textarea:focus, 
        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(230, 81, 0, 0.1);
        }

        .form-textarea {
            resize: vertical;
            height: 80px;
        }

        .dateTime-group {
            display: flex;
            gap: 15px;
        }
        .dateTime-group .form-group {
            flex: 1;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .btn-submit:hover {
            background-color: #bf360c;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .error-message {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        @media (max-width: 480px) {
            .booking-container {
                padding: 25px;
            }
            .dateTime-group {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>

    <div class="booking-container">
        <div class="logo-container">
            <span class="logo-icon">🍽️🍷</span>
            <span class="logo-text">Sabor & Estilo</span>
        </div>

        <h2>Haz tu Reservación</h2>

        @if($successMessage)
        <div class="success-message">
            {{ $successMessage }}
        </div>
        @endif

        <form wire:submit.prevent="submitReservation">
            
            <div class="form-group">
                <label for="fullname" class="form-label">Nombre Completo *</label>
                <input type="text" wire:model="nombre_cliente" class="form-input" placeholder="Ej. Juan Pérez" required>
                @error('nombre_cliente') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" wire:model="email_cliente" class="form-input" placeholder="ejemplo@correo.com">
                @error('email_cliente') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Teléfono de Contacto *</label>
                <input type="tel" wire:model="telefono_cliente" class="form-input" placeholder="Ej. +52 555 123 4567" required>
                @error('telefono_cliente') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="dateTime-group">
                <div class="form-group">
                    <label for="date" class="form-label">Fecha *</label>
                    <input type="date" wire:model="fecha" class="form-input" min="{{ now()->toDateString() }}" required>
                    @error('fecha') <div class="error-message">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="time" class="form-label">Hora *</label>
                    <input type="time" wire:model="hora" class="form-input" min="12:00" max="23:00" required>
                    @error('hora') <div class="error-message">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="guests" class="form-label">Número de Personas *</label>
                <select wire:model="num_personas" class="form-select" required>
                    <option value="" disabled selected>Selecciona cantidad</option>
                    <option value="1">1 Persona</option>
                    <option value="2">2 Personas</option>
                    <option value="3">3 Personas</option>
                    <option value="4">4 Personas</option>
                    <option value="5">5 Personas</option>
                    <option value="6">6 Personas</option>
                    <option value="7">7 Personas</option>
                    <option value="8">8 Personas</option>
                    <option value="9">9 Personas</option>
                    <option value="10">10 Personas</option>
                </select>
                @error('num_personas') <div class="error-message">{{ $message }}</div> @enderror
                <small style="color: #64748b; font-size: 12px;">Para grupos de más de 10 personas, por favor contáctenos directamente.</small>
            </div>

            <div class="form-group">
                <label for="requests" class="form-label">Peticiones Especiales / Alergias</label>
                <textarea wire:model="notas" class="form-textarea" placeholder="¿Alguna alergia o celebración especial?"></textarea>
            </div>

            <button type="submit" class="btn-submit">Confirmar Reserva</button>

        </form>
    </div>

</body>
</html>
