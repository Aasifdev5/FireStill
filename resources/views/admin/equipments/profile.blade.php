@extends('layout.master')
@section('title', 'Perfil de Equipo')

@section('main_content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Perfil Técnico del Equipo: {{ $equipment->code }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6>Datos Estáticos</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>ID Único:</strong> {{ $equipment->code }}</li>
                        <li class="list-group-item"><strong>Tipo de Equipo:</strong> {{ $equipment->getTypeDisplay() }}</li>
                        <li class="list-group-item"><strong>Marca:</strong> {{ $equipment->brand }}</li>
                        <li class="list-group-item"><strong>Modelo:</strong> {{ $equipment->model }}</li>
                        <li class="list-group-item"><strong>Número de Serie:</strong> {{ $equipment->serial_number }}</li>
                        <li class="list-group-item"><strong>Ubicación Exacta:</strong> {{ $equipment->location }}</li>
                        <li class="list-group-item"><strong>Cliente/Departamento Responsable:</strong> {{ $equipment->client->name ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Fecha de Manufactura:</strong> {{ $equipment->manufacture_date ? \Carbon\Carbon::parse($equipment->manufacture_date)->format('d/m/Y') : 'N/A' }}</li>
                        <li class="list-group-item"><strong>Fecha de Última Recarga:</strong> {{ $equipment->last_recharge_date ? \Carbon\Carbon::parse($equipment->last_recharge_date)->format('d/m/Y') : 'N/A' }}</li>
                        <li class="list-group-item"><strong>Notas:</strong> {{ $equipment->notes ?? 'Ninguna' }}</li>
                    </ul>

                    <h6 class="mt-4">Datos Dinámicos</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Estado Actual:</strong> {{ $equipment->getStatusIndicator() }}</li>
                        <li class="list-group-item"><strong>Próxima Inspección:</strong> {{ $equipment->nextInspectionDate()->format('d/m/Y') }}</li>
                    </ul>

                    @if ($equipment->needsReminder())
                        <div class="alert alert-warning mt-3">Alerta: Necesita mantenimiento pronto (dentro de 30 días).</div>
                    @endif
                    @if ($equipment->isCritical())
                        <div class="alert alert-danger mt-3">Alerta: Estado Crítico - Fuera de servicio.</div>
                    @endif

                    <h6 class="mt-4">Historial de Inspecciones</h6>
                    @if ($equipment->inspections->isEmpty())
                        <p>No hay inspecciones registradas.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipment->inspections as $inspection)
                                    <tr>
                                        <td>{{ $inspection->inspected_at->format('d/m/Y') }}</td>
                                        <td>{{ ucfirst($inspection->status) }}</td>
                                        <td>{{ $inspection->observations ?? 'Ninguna' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <h6 class="mt-4">Reportes</h6>
                    <p>Reportes no disponibles en este momento. (Implementar si se agregan reportes en el futuro)</p>
                </div>
                <div class="col-md-4">
                    @if ($equipment->qr_code && file_exists(public_path($equipment->qr_code)))
                        <img src="{{ asset($equipment->qr_code) }}" alt="QR Code" class="img-fluid">
                    @else
                        <p>No hay QR Code disponible.</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('scan.qr') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection
