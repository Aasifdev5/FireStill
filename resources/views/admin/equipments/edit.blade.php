@extends('layout.master')
@section('title', 'Editar Equipo')

@section('main_content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Editar Equipo</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('equipments.update', $equipment->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Cliente</label>
                    <select name="client_id" class="form-control" required>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ $equipment->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tipo</label>
                    <select name="type" class="form-control" required>
                        <option value="extinguisher" {{ $equipment->type == 'extinguisher' ? 'selected' : '' }}>Extintor</option>
                        <option value="hydrant" {{ $equipment->type == 'hydrant' ? 'selected' : '' }}>Hidrante</option>
                        <option value="hose" {{ $equipment->type == 'hose' ? 'selected' : '' }}>Manguera</option>
                        <option value="cabinet" {{ $equipment->type == 'cabinet' ? 'selected' : '' }}>Gabinete</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Ubicación</label>
                    <input type="text" name="location" value="{{ $equipment->location }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tipo de Agente</label>
                    <input type="text" name="agent_type" value="{{ $equipment->agent_type }}" class="form-control">
                </div>

                <div class="mb-3">
    <label>Fecha de Instalación</label>
    <input type="text" name="installation_date"
           value="{{ $equipment->installation_date ? \Carbon\Carbon::parse($equipment->installation_date)->format('Y-m-d') : '' }}"
           class="form-control flatpickr">
</div>

<div class="mb-3">
    <label>Fecha de Expiración</label>
    <input type="text" name="expiry_date"
           value="{{ $equipment->expiry_date ? \Carbon\Carbon::parse($equipment->expiry_date)->format('Y-m-d') : '' }}"
           class="form-control flatpickr">
</div>


                <div class="mb-3">
                    <label>Notas</label>
                    <textarea name="notes" class="form-control">{{ $equipment->notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('equipments.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr(".flatpickr", {
      dateFormat: "Y-m-d",
      allowInput: true
  });
</script>
@endsection
