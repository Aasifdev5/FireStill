@extends('layout.master')

@section('title')
    Productos
@endsection

@section('main_content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(Session::has('fail'))
                <div class="alert alert-danger">
                    <p>{{ session('fail') }}</p>
                </div>
            @endif
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ __('Lista de Productos') }}</h2>
                            <a href="{{ route('products.create') }}" class="btn btn-success btn-sm pull-right">
                                <i class="fa fa-plus"></i> {{ __('Añadir Producto') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <form id="bulk-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="bulk-delete-btn" class="btn btn-danger mb-3" disabled>
                                    <i class="fas fa-trash-alt"></i> {{ __('Eliminar Seleccionados') }}
                                </button>
                            </form>
                            <div class="table-responsive">
                                <table class="table row-border data-table-filter table-style" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>{{ __('Código') }}</th>
                                            <th>{{ __('Nombre') }}</th>
                                            <th>{{ __('Categoría') }}</th>
                                            <th>{{ __('Acciones') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr class="removable-item">
                                                <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                                                <td>{{ $product->code }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? '—' }}</td>
                                                <td>
                                                    <div class="action__buttons">
                                                        <a href="{{ route('products.edit', $product->id) }}"
                                                           class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5"
                                                           data-toggle="tooltip" title="{{ __('Editar') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-icon waves-effect waves-light btn-danger m-b-5 delete-btn"
                                                           data-toggle="tooltip" title="{{ __('Eliminar') }}"
                                                           data-url="{{ route('products.destroy', $product->id) }}">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
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
            </div>
        </div>
    </div>
    <!-- Page content area end -->

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Select all checkboxes
            $('#select-all').on('change', function () {
                $('.product-checkbox').prop('checked', this.checked);
                toggleBulkDeleteButton();
            });

            // Enable/disable bulk delete button
            $('.product-checkbox').on('change', function () {
                toggleBulkDeleteButton();
            });

            function toggleBulkDeleteButton() {
                $('#bulk-delete-btn').prop('disabled', $('.product-checkbox:checked').length === 0);
            }

            // Individual delete confirmation
            $('.delete-btn').on('click', function (e) {
                e.preventDefault();
                let deleteUrl = $(this).data('url');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción eliminará permanentemente este producto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#007bff',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: deleteUrl,
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Éxito',
                                        text: response.message,
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message || 'No se pudo eliminar el producto.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo eliminar el producto. Por favor, intenta de nuevo.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Bulk delete
            $('#bulk-delete-btn').on('click', function () {
                let selectedIds = $('.product-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        title: 'No hay productos seleccionados',
                        text: 'Por favor, selecciona productos para eliminar.',
                        icon: 'warning'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Eliminar productos seleccionados?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#007bff',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('products.bulkDelete') }}",
                            type: 'POST', // Changed to POST to match category controller
                            data: {
                                ids: selectedIds
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Éxito',
                                        text: response.message,
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message || 'No se pudieron eliminar los productos.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al eliminar los productos seleccionados.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
```
