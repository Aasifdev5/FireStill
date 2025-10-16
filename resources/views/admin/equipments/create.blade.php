@extends('layout.master')
@section('title', 'Registrar Equipo')

@section('main_content')
<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header">
      <h5>Registrar Nuevo Equipo</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('equipments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label>Cliente</label>
          <select name="client_id" class="form-control" required>
            @foreach($clients as $client)
              <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label>Tipo</label>
          <select name="type" class="form-control" required>
            <option value="extinguisher">Extintor</option>
            <option value="hydrant">Hidrante</option>
            <option value="hose">Manguera</option>
            <option value="cabinet">Gabinete</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Ubicación</label>
          <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Tipo de Agente</label>
          <input type="text" name="agent_type" class="form-control">
        </div>

        <div class="mb-3">
          <label>Fecha de Instalación</label>
          <input type="text" name="installation_date" class="form-control flatpickr" placeholder="Selecciona fecha">
        </div>

        <div class="mb-3">
          <label>Fecha de Expiración</label>
          <input type="text" name="expiry_date" class="form-control flatpickr" placeholder="Selecciona fecha">
        </div>

        <div class="mb-3">
          <label>Notas</label>
          <textarea name="notes" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary" type="submit">Guardar</button>
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
