@extends('layout.master')
@section('title', 'Lista de Equipos')

@section('main_content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Equipos Registrados</h5>
            <a href="{{ route('equipments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Equipo
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="equipmentsTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>QR</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipmentsData as $i => $eq)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $eq['code'] }}</td>
                            <td>{{ $eq['client'] }}</td>
                            <td>{{ $eq['type'] }}</td>
                            <td>{{ $eq['location'] }}</td>
                            <td>{{ $eq['created'] }}</td>
                            <td>
                                @if ($eq['qr_code'] && $eq['qr_code_exists'])
                                    <img src="{{ asset($eq['qr_code']) }}" width="80" alt="QR Code for {{ $eq['code'] }}">
                                @else
                                    <span class="text-muted">No QR Code</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $eq['status_indicator'] }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('equipments.edit', $eq['id']) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning approve-btn" data-id="{{ $eq['id'] }}">
                                        <i class="fa fa-check"></i> Aprobar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger reject-btn" data-id="{{ $eq['id'] }}">
                                        <i class="fa fa-times"></i> Rechazar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $eq['id'] }}">
                                        <i class="fa fa-trash"></i> Eliminar
                                    </button>
                                    <a href="{{ route('equipments.profile') }}?code={{ urlencode($eq['code']) }}" class="btn btn-sm btn-info mt-1">
                                        <i class="fas fa-qrcode"></i> Ver Perfil
                                    </a>

                                    <!-- Nueva Inspección solo para equipos aprobados -->
                                    @if ($eq['status'] === 'approved')
                                        <a href="{{ route('inspections.create', $eq['id']) }}" class="btn btn-sm btn-primary mt-1">
                                            <i class="fas fa-plus"></i> Nueva Inspección
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    $('#equipmentsTable').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json" }
    });

    // Aprobar
    $('.approve-btn').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Aprobar equipo?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aprobar'
        }).then(result => {
            if(result.isConfirmed){
                $.post('{{ route("equipments.approve") }}', {id, _token: '{{ csrf_token() }}'})
                    .done(() => Swal.fire('¡Aprobado!', '', 'success').then(() => location.reload()))
                    .fail(() => Swal.fire('Error', 'No se pudo aprobar.', 'error'));
            }
        });
    });

    // Rechazar
    $('.reject-btn').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Rechazar equipo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Rechazar'
        }).then(result => {
            if(result.isConfirmed){
                $.post('{{ route("equipments.reject") }}', {id, _token: '{{ csrf_token() }}'})
                    .done(() => Swal.fire('¡Rechazado!', '', 'success').then(() => location.reload()))
                    .fail(() => Swal.fire('Error', 'No se pudo rechazar.', 'error'));
            }
        });
    });

    // Eliminar
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar equipo?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if(result.isConfirmed){
                $.ajax({
                    url: '/equipments/' + id,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: () => Swal.fire('¡Eliminado!', 'El equipo ha sido eliminado.', 'success').then(() => location.reload()),
                    error: () => Swal.fire('Error', 'No se pudo eliminar el equipo.', 'error')
                });
            }
        });
    });
});
</script>
@endsection
