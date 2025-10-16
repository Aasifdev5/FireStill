<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inspección - {{ $inspection->equipment->code }}</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 3px solid #e74c3c; padding-bottom: 10px; }
        .header h1 { font-size: 24px; color: #2c3e50; margin: 0; }
        .header h2 { font-size: 14px; color: #e74c3c; margin: 2px 0 0 0; }
        .header .qr img { width: 120px; height: auto; }

        /* Info boxes */
        .info-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-box { width: 48%; background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px solid #ddd; }
        .info-box p { margin: 4px 0; }
        .info-box strong { color: #2c3e50; min-width: 120px; display: inline-block; }

        /* Checklist Table */
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
        .table th { background-color: #f0f0f0; }

        /* Badges */
        .badge { padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 11px; display: inline-block; text-align: center; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-danger { background-color: #dc3545; color: #fff; }

        /* Observations */
        .observations { margin-bottom: 20px; padding: 10px; background: #f0f8ff; border-left: 5px solid #3498db; border-radius: 5px; }
        .observations h4 { margin: 0 0 5px 0; color: #2c3e50; }

        /* Photos Grid */
        .photos-section { margin-bottom: 20px; }
        .photos-section h4 { background: #f0f0f0; padding: 5px 10px; border-left: 4px solid #007bff; margin-bottom: 10px; }
        .photo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .photo-grid img { width: 100%; height: auto; border-radius: 6px; border: 1px solid #ccc; }

        /* Status */
        .status { margin-bottom: 20px; font-size: 14px; }
        .status .badge { font-size: 12px; padding: 6px 15px; }

        /* Footer */
        .footer { text-align: center; font-size: 10px; margin-top: 30px; color: #555; border-top: 1px solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div>
            <h1>REPORTE DE INSPECCIÓN</h1>
            <h2>Checklist para Extintor - NFPA Nº10 / IRAM Nº3517</h2>
        </div>
        <div class="qr">
            @if($inspection->equipment->qr_code && file_exists(public_path($inspection->equipment->qr_code)))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($inspection->equipment->qr_code))) }}" alt="QR Code">
            @endif
        </div>
    </div>

    <!-- Equipment & Inspection Info -->
    <div class="info-section">
        <div class="info-box">
            <p><strong>Equipo:</strong> {{ $inspection->equipment->code }}</p>
            <p><strong>Cliente:</strong> {{ $inspection->equipment->client->name ?? 'N/A' }}</p>
            <p><strong>Inspector:</strong> {{ $inspection->user->name ?? 'N/A' }}</p>
            <p><strong>Fecha:</strong> {{ $inspection->inspected_at->format('d/m/Y') }}</p>
            <p><strong>Vencimiento:</strong> {{ $inspection->expiration_date->format('d/m/Y') }}</p>
        </div>
        <div class="info-box">
            <p><strong>Ubicación:</strong> {{ $inspection->equipment->location ?? 'N/A' }}</p>
            <p><strong>Tipo:</strong> {{ $inspection->equipment->type ?? 'N/A' }}</p>
            <p><strong>Cantidad:</strong> {{ $inspection->equipment->quantity ?? 1 }}</p>
        </div>
    </div>

    <!-- Checklist -->
    <h4>Checklist de Inspección</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->checklist as $key => $value)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                    <td>
                        <span class="badge {{ ($value === 'yes' && !in_array($key, ['presents_abnormalities','is_obstructed'])) || ($value === 'no' && in_array($key, ['presents_abnormalities','is_obstructed'])) ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($value) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Observations -->
    @if($inspection->observations)
        <div class="observations">
            <h4>Observaciones</h4>
            <p>{{ $inspection->observations }}</p>
        </div>
    @endif

    <!-- Photos -->
    @if($inspection->photos && $inspection->photos->count())
        <div class="photos-section">
            <h4>Fotografías de la Inspección</h4>
            <div class="photo-grid">
                @foreach($inspection->photos as $photo)
                    @php $photoPath = public_path($photo->path); @endphp
                    @if(file_exists($photoPath))
                        <img src="data:image/{{ pathinfo($photoPath, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($photoPath)) }}" alt="Foto de inspección">
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Status -->
    <div class="status">
        <h4>Estado General</h4>
        <span class="badge {{ $inspection->status === 'critical' ? 'badge-danger' : 'badge-success' }}">
            {{ ucfirst($inspection->status) }}
        </span>
    </div>

    <!-- Footer -->
    <div class="footer">
        Generado automáticamente por FireStill &copy; {{ date('Y') }}
    </div>

</body>
</html>
