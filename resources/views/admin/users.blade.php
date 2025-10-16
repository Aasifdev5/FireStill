@extends('layout.master')
@section('title')
LISTA DE USUARIOS
@endsection
@section('main_content')

<div class="container-fluid">
    @if (session()->has('success'))
        <div class="alert alert-success" style="background-color: green;">
            <p style="color: #fff;">{{ session()->get('success') }}</p>
        </div>
    @endif
    @if (session()->has('fail'))
        <div class="alert alert-danger" style="background-color: red;">
            <p style="color: #fff;">{{ session()->get('fail') }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE USUARIOS (Firebase)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display" id="usersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UID</th>
                                    <th>Correo Electrónico</th>
                                    <th>Número de Teléfono</th>
                                    <th>Registrado en</th>
                                    <th>Último Inicio de Sesión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usersData as $i => $data)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $data['uid'] }}</td>
                                        <td>{{ $data['email'] ?? 'N/A' }}</td>
                                        <td>{{ $data['phone'] ?? 'N/A' }}</td>
                                        <td>{{ $data['created'] }}</td>
                                        <td>{{ $data['lastSignIn'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
            },
            responsive: true
        });
    });
</script>

@endsection
