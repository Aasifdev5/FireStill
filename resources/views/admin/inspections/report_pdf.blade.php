<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Inspección - {{ $inspection->equipment->code }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e74c3c;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .title-section h1 {
            font-size: 22px;
            color: #2c3e50;
            margin: 0 0 5px 0;
            font-weight: 700;
        }

        .title-section h2 {
            font-size: 14px;
            color: #e74c3c;
            margin: 0;
            font-weight: 600;
        }

        .qr-section {
            text-align: right;
        }

        .qr-section img {
            width: 100px;
            height: auto;
        }

        .info-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #2c3e50;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
        }

        .info-label {
            color: #2c3e50;
            width: 140px;
            font-weight: 600;
        }

        .info-value {
            flex: 1;
        }

        .table-section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #2c3e50;
            color: white;
            padding: 8px 12px;
            margin: 0 0 10px 0;
            font-size: 14px;
            border-radius: 4px;
            font-weight: 600;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        .table th {
            background-color: #f0f3f5;
            font-weight: 600;
            color: #2c3e50;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 11px;
            display: inline-block;
            text-align: center;
            min-width: 60px;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .observations {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #f0f8ff;
            border-left: 4px solid #3498db;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .observations h4 {
            margin: 0 0 8px 0;
            color: #2c3e50;
            font-size: 14px;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .photo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 15px;
            min-height: 250px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .photo-container img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 4px;
        }

        .status-section {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .status-section h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #2c3e50;
        }

        .status-section .badge {
            font-size: 12px;
            padding: 6px 15px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 30px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 40px;
        }

        .signature-label {
            font-weight: 600;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-section">
                <img src="https://firestill.lat/uploads/setting/1760601118-8TzSSlUI8e.png" alt="FireStill Logo"
                    class="logo">
                <div class="title-section">
                    <h1>REPORTE DE INSPECCIÓN</h1>
                    <h2>Checklist para Equipo - NFPA Nº10 / IRAM Nº3517</h2>
                </div>
            </div>
            <div class="qr-section">
                @if ($inspection->equipment->inspection_qr_code && file_exists(public_path($inspection->equipment->inspection_qr_code)))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($inspection->equipment->inspection_qr_code))) }}"
                        alt="Inspection History QR Code">
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Información de Inspección</h3>
                <div class="info-row">
                    <span class="info-label">Equipo:</span>
                    <span class="info-value">{{ $inspection->equipment->code }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cliente:</span>
                    <span class="info-value">{{ $inspection->equipment->client->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Inspector:</span>
                    <span class="info-value">{{ $inspection->user->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value">{{ $inspection->inspected_at->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vencimiento:</span>
                    <span class="info-value">{{ $inspection->expiration_date->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="info-box">
                <h3>Información del Equipo</h3>
                <div class="info-row">
                    <span class="info-label">Ubicación:</span>
                    <span class="info-value">{{ $inspection->equipment->location ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipo:</span>
                    <span class="info-value">{{ $inspection->equipment->getTypeDisplay() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Marca:</span>
                    <span class="info-value">{{ $inspection->equipment->brand ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Modelo:</span>
                    <span class="info-value">{{ $inspection->equipment->model ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Código del Fabricante:</span>
                    <span class="info-value">{{ $inspection->equipment->manufacturer_code ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="table-section">
            <h3 class="section-title">Checklist de Inspección</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th width="70%">Item</th>
                        <th width="30%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inspection->checklist as $key => $value)
                        <tr>
                            <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                            <td>
                                @php
                                    $isPositive =
                                        ($value === 'yes' &&
                                            !in_array($key, ['presents_abnormalities', 'is_obstructed'])) ||
                                        ($value === 'no' &&
                                            in_array($key, ['presents_abnormalities', 'is_obstructed']));
                                @endphp
                                <span class="badge {{ $isPositive ? 'badge-success' : 'badge-danger' }}">
                                    {{ $isPositive ? 'OK' : 'NO OK' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($inspection->observations)
            <div class="observations">
                <h4>Observaciones</h4>
                <p>{{ $inspection->observations }}</p>
            </div>
        @endif

        @if ($inspection->photos && $inspection->photos->count())
            <div class="photos-section">
                <h4>Fotografías de la Inspección</h4>
                <div class="photo-grid">
                    @foreach ($inspection->photos as $photo)
                        @php $photoPath = public_path($photo->path); @endphp
                        @if (file_exists($photoPath))
                            <div class="photo-container">
                                <img src="data:image/{{ pathinfo($photoPath, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($photoPath)) }}"
                                    alt="Foto de inspección">
                            </div>
                            <br>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="status-section">
            <h4>Estado General</h4>
            @php
                switch ($inspection->status) {
                    case 'critical':
                        $badgeClass = 'badge-danger';
                        $statusText = '🔴 Fuera de servicio';
                        break;
                    case 'warning':
                        $badgeClass = 'badge-warning';
                        $statusText = '🟡 Necesita mantenimiento';
                        break;
                    default:
                        $badgeClass = 'badge-success';
                        $statusText = '🟢 Operativo';
                        break;
                }
            @endphp

            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
        </div>


        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Firma del Inspector</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Firma del Cliente</div>
            </div>
        </div>

        <div class="footer">
            Generado automáticamente por FireStill &copy; {{ date('Y') }}
        </div>
    </div>
</body>

</html>
