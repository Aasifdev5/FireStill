<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Inspection History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .equipment-info {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .status-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-critical {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger-color);
        }

        .status-ok {
            background-color: rgba(46, 204, 113, 0.15);
            color: var(--success-color);
        }

        .table-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: var(--light-bg);
        }

        .table thead th {
            border-bottom: none;
            font-weight: 600;
            color: var(--secondary-color);
            padding: 1rem 0.75rem;
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-color: #f1f3f4;
        }

        .btn-pdf {
            background-color: var(--secondary-color);
            color: white;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            transition: var(--transition);
        }

        .btn-pdf:hover {
            background-color: #1a2530;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }

        .header-icon {
            background-color: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .expiration-warning {
            color: var(--warning-color);
            font-weight: 600;
        }

        .expiration-critical {
            color: var(--danger-color);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5><span class="header-icon"><i class="fas fa-clipboard-list"></i></span>Historial de Inspecciones - Equipo: {{ $equipment->code }}</h5>
            </div>
            <div class="card-body">
                <div class="equipment-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Cliente</span>
                            <span class="info-value">{{ $equipment->client->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ubicación</span>
                            <span class="info-value">{{ $equipment->location ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tipo</span>
                            <span class="info-value">{{ $equipment->getTypeDisplay() }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Marca</span>
                            <span class="info-value">{{ $equipment->brand ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Modelo</span>
                            <span class="info-value">{{ $equipment->model ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Código del Fabricante</span>
                            <span class="info-value">{{ $equipment->manufacturer_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($equipment->inspections->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-clipboard-check"></i>
                        <h4>No hay inspecciones registradas</h4>
                        <p>No se han encontrado inspecciones para este equipo.</p>
                    </div>
                @else
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Inspector</th>
                                    <th>Estado</th>
                                    <th>Vencimiento</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipment->inspections as $i => $inspection)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $inspection->inspected_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                                <span>{{ $inspection->user->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $inspection->status == 'critical' ? 'critical' : 'ok' }}">
                                                <i class="fas {{ $inspection->status == 'critical' ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-1"></i>
                                                {{ ucfirst($inspection->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $now = now();
                                                $expiration = $inspection->expiration_date;
                                                $daysUntilExpiration = $now->diffInDays($expiration, false);
                                            @endphp

                                            @if($daysUntilExpiration < 0)
                                                <span class="expiration-critical">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Vencido
                                                </span>
                                            @elseif($daysUntilExpiration <= 30)
                                                <span class="expiration-warning">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $expiration->format('d/m/Y') }}
                                                </span>
                                            @else
                                                {{ $expiration->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('inspections.pdf', $inspection->id) }}" target="_blank" class="btn btn-pdf">
                                                <i class="fas fa-file-pdf me-1"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
