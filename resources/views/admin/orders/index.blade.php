@extends('layout.master')

@section('title')
    📦 Orders Dashboard
@endsection

@section('main_content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-boxes me-2"></i>Orders Dashboard</h2>
                <div class="badge bg-white text-primary fs-6 py-2 px-3">
                    <i class="fas fa-clock me-1"></i> {{ now()->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card -primary">
                        <div class="card-body">
                            <h6 class="text-muted">TOTAL ORDERS</h6>
                            <h3 class="fw-bold">{{ count($orders) }}</h3>
                            <i class="fas fa-shopping-bag float-end text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card -success">
                        <div class="card-body">
                            <h6 class="text-muted">COMPLETED</h6>
                            <h3 class="fw-bold">{{ $orders->where('status', 'completed')->count() }}</h3>
                            <i class="fas fa-check-circle float-end text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card -warning">
                        <div class="card-body">
                            <h6 class="text-muted">PENDING</h6>
                            <h3 class="fw-bold">{{ $orders->where('status', 'pending')->count() }}</h3>
                            <i class="fas fa-clock float-end text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card -info">
                        <div class="card-body">
                            <h6 class="text-muted">TOTAL REVENUE</h6>
                            <h3 class="fw-bold">Bs {{ number_format($orders->sum('total'), 2) }}</h3>
                            <i class="fas fa-coins float-end text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0" id="ordersTable">
                    <thead class="">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Placed</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="{{ $order->status === 'completed' ? '-success' : '' }}">
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="-primary rounded p-2 me-2">
                                            <i class="fas fa-box text-primary"></i>
                                        </div>
                                        <strong>{{ $order->order_number }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title bg-primary rounded-circle">
                                                {{ substr($order->user->name ?? 'N/A', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $order->user->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $order->order_type }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold">Bs {{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill text-uppercase">
                                        <i class="fas {{ $order->status === 'completed' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill">
                                        <i class="fas {{ $order->payment_mode === 'cash' ? 'fa-money-bill-wave' : 'fa-credit-card' }} me-1"></i>
                                        {{ ucfirst($order->payment_mode) }}
                                    </span>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $order->created_at->format('d M') }}
                                        <br>
                                        <small>{{ $order->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill me-1" data-bs-toggle="modal"
                                        data-bs-target="#orderModal{{ $order->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                    @if($order->status !== 'completed')
                                        <button class="btn btn-sm btn-success rounded-pill btn-mark-complete" data-order-id="{{ $order->id }}">
                                            <i class="fas fa-check me-1"></i> Mark as Completed
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details Modals -->
    @foreach($orders as $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-receipt me-2"></i>Order #{{ $order->order_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 p-3">
                                    <h6 class="text-muted mb-3"><i class="fas fa-user me-2"></i>CUSTOMER INFO</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-lg me-3">
                                            <span class="avatar-title bg-primary rounded-circle">
                                                {{ substr($order->user->name ?? 'N/A', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $order->user->name ?? 'N/A' }}</h5>
                                            <small class="text-muted">Order Type: {{ ucfirst($order->order_type) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 p-3">
                                    <h6 class="text-muted mb-3"><i class="fas fa-truck me-2"></i>DELIVERY INFO</h6>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $order->delivery_address }}</p>
                                    <p class="mb-0"><i class="fas fa-clock me-2 text-primary"></i> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 p-3 mb-4">
                            <h6 class="text-muted mb-3"><i class="fas fa-credit-card me-2"></i>PAYMENT INFO</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Method:</strong></p>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ ucfirst($order->payment_mode) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Status:</strong></p>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Total Amount:</strong></p>
                                    <h5 class="text-primary">Bs {{ number_format($order->total, 2) }}</h5>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3"><i class="fas fa-shopping-basket me-2 text-primary"></i>ORDER ITEMS</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead class="">
                                    <tr>
                                        <th class="text-light">Product</th>
                                        <th class="text-light">Price</th>
                                        <th class="text-light">Qty</th>
                                        <th class="text-end text-light">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items ?? [] as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['image'] ?? asset('images/placeholder.png') }}"
                                                         alt="{{ $item['name'] ?? 'N/A' }}"
                                                         class="rounded me-3" width="60">
                                                    <div>
                                                        <h6 class="mb-0 text-light">{{ $item['name'] ?? 'N/A' }}</h6>
                                                        <small class="text-muted">SKU: {{ $item['sku'] ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-light">Bs {{ number_format($item['price'] ?? 0, 2) }}</td>
                                            <td class="text-light">{{ $item['quantity'] ?? 0 }}</td>
                                            <td class="text-end text-light fw-bold">Bs {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="3" class="text-end text-light fw-bold">TOTAL:</td>
                                        <td class="text-end fw-bold text-primary">Bs {{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($order->notes)
                            <div class="card border-0 p-3 mt-3">
                                <h6 class="text-muted mb-2"><i class="fas fa-sticky-note me-2"></i>CUSTOMER NOTES</h6>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0">
                        <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@section('scripts')
    <!-- DataTables JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
    <style>
        .stat-card {
            border-radius: 12px;
            border: none;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .-primary { background-color: #e3f2fd; }
        .-success { background-color: #e8f5e9; }
        .-warning { background-color: #fff8e1; }
        .-info { background-color: #e0f7fa; }
        .avatar-sm { width: 32px; height: 32px; }
        .avatar-lg { width: 48px; height: 48px; }
        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05) !important;
        }
        #ordersTable {
            border-collapse: separate;
            border-spacing: 0;
        }
        #ordersTable thead th {
            position: sticky;
            top: 0;

            z-index: 10;
        }
        /* Improved Export Button Styling */
        .dt-buttons {
            margin-bottom: 15px;
            padding: 10px;

            display: inline-flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .dt-button {
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .dt-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .dt-button:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .btn-outline-secondary { /* Copy */
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white !important;
        }
        .btn-outline-success { /* Excel */
            background: linear-gradient(135deg, #28a745, #218838);
            color: white !important;
        }
        .btn-outline-primary { /* CSV */
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white !important;
        }
        .btn-outline-danger { /* PDF */
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white !important;
        }
        .btn-outline-info { /* Print */
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white !important;
        }
        .dt-button i {
            font-size: 1rem;
        }
        @media (max-width: 576px) {
            .dt-buttons {
                justify-content: center;
            }
            .dt-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        $(document).ready(function () {
            var table = $('#ordersTable').DataTable({
                dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-outline-secondary',
                        text: '<i class="fas fa-copy"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-outline-success',
                        text: '<i class="fas fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-outline-primary',
                        text: '<i class="fas fa-file-csv"></i> CSV'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-outline-danger',
                        text: '<i class="fas fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-info',
                        text: '<i class="fas fa-print"></i> Print'
                    }
                ],
                responsive: true,
                pageLength: 5,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                initComplete: function () {
                    $('.dataTables_scrollBody').css('max-height', $(window).height() - 300 + 'px');
                }
            });

            // Mark as completed
            $(document).on('click', '.btn-mark-complete', function () {
                const orderId = $(this).data('order-id');
                Swal.fire({
                    title: 'Complete This Order?',
                    text: 'Are you sure you want to mark this order as completed?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fas fa-check me-1"></i> Yes, mark as completed',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/orders/${orderId}/complete`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                                setTimeout(() => location.reload(), 1500);
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to mark order as completed: ' + (xhr.responseJSON?.message || 'Unknown error'),
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Make table header sticky when scrolling
            $(window).on('resize scroll', function () {
                const tableOffset = $('#ordersTable').offset().top;
                const scrollTop = $(window).scrollTop();
                if (scrollTop > tableOffset) {
                    $('#ordersTable thead').addClass('fixed-header');
                } else {
                    $('#ordersTable thead').removeClass('fixed-header');
                }
            });
        });
    </script>
@endsection
