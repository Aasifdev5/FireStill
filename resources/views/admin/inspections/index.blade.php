@extends('layout.master')
@section('title', 'Inspecciones')

@section('main_content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header"><h5>Historial de Inspecciones</h5></div>
            <div class="card-body">
                <table class="table table-hover" id="inspectionTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Cliente</th>
                            <th>Inspector</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspections as $i => $ins)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $ins->equipment->code }}</td>
                                <td>{{ $ins->equipment->client->name ?? 'N/A' }}</td>
                                <td>{{ $ins->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $ins->status == 'critical' ? 'danger' : 'success' }}">
                                        {{ ucfirst($ins->status) }}
                                    </span>
                                </td>
                                <td>{{ $ins->inspected_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('inspections.pdf', $ins->id) }}" target="_blank" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- DataTables JS and Initialization -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#inspectionTable').DataTable();
        });
    </script>
@endsection
