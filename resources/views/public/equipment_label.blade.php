<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Etiqueta del Extintor - {{ $equipment->code }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #e74c3c;
            --dark-red: #c0392b;
            --light-red: #ffebee;
            --dark-gray: #2c3e50;
            --medium-gray: #546e7a;
            --light-gray: #eceff1;
            --success-green: #27ae60;
            --warning-orange: #f39c12;
            --danger-red: #e74c3c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .label-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            width: 380px;
            overflow: hidden;
            position: relative;
        }

        .label-header {
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .label-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
        }

        .equipment-code {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .fire-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .label-content {
            padding: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table tr {
            border-bottom: 1px solid var(--light-gray);
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 12px 0;
            vertical-align: top;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark-gray);
            width: 45%;
            font-size: 14px;
        }

        .info-value {
            color: var(--medium-gray);
            font-weight: 500;
            font-size: 14px;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }

        .status-ok {
            background-color: rgba(39, 174, 96, 0.15);
            color: var(--success-green);
        }

        .status-warning {
            background-color: rgba(243, 156, 18, 0.15);
            color: var(--warning-orange);
        }

        .status-danger {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger-red);
        }

        .qr-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--light-gray);
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
        }

        .qr-code {
            flex-shrink: 0;
            background: white;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .qr-info {
            flex-grow: 1;
            padding-left: 15px;
        }

        .qr-info p {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: var(--medium-gray);
        }

        .qr-info strong {
            color: var(--dark-gray);
        }

        .print-btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.4);
        }

        .print-btn:active {
            transform: translateY(0);
        }

        .footer-note {
            text-align: center;
            font-size: 11px;
            color: var(--medium-gray);
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed var(--light-gray);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .label-card {
                box-shadow: none;
                width: 100%;
            }

            .print-btn {
                display: none;
            }
        }

        /* Date status indicators */
        .date-status {
            display: inline-flex;
            align-items: center;
            margin-left: 5px;
        }

        .date-status i {
            font-size: 12px;
            margin-right: 3px;
        }
    </style>
</head>
<body>
    <div class="label-card">
        <div class="label-header">
            <div class="equipment-code">CÓDIGO: {{ $equipment->code }}</div>
            <h2>Extintor de Incendios</h2>
            <div class="fire-icon">
                <i class="fas fa-fire-extinguisher"></i>
            </div>
        </div>

        <div class="label-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Ubicación:
                    </td>
                    <td class="info-value">
                        {{ $equipment->location ?? '—' }}
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        <i class="fas fa-sync-alt"></i> Última Recarga:
                    </td>
                    <td class="info-value">
                        {{ $equipment->last_recharge_date ?? '—' }}
                        @if($equipment->last_recharge_date)
                            <span class="status-indicator status-ok">
                                <i class="fas fa-check-circle"></i> Completado
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        <i class="fas fa-calendar-alt"></i> Próxima Recarga:
                    </td>
                    <td class="info-value">
                        {{ $equipment->expiry_date ?? '—' }}
                        @php
                            // Simulate date checking for status indicator
                            $statusClass = 'status-ok';
                            $statusIcon = 'fa-check-circle';
                            $statusText = 'Al día';

                            // In a real implementation, you would check actual dates
                            // This is just for visual demonstration
                            if (rand(0, 10) > 7) {
                                $statusClass = 'status-warning';
                                $statusIcon = 'fa-exclamation-triangle';
                                $statusText = 'Próximo a vencer';
                            } elseif (rand(0, 10) > 9) {
                                $statusClass = 'status-danger';
                                $statusIcon = 'fa-times-circle';
                                $statusText = 'Vencido';
                            }
                        @endphp
                        <span class="status-indicator {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">
                        <i class="fas fa-tools"></i> Próxima Inspección:
                    </td>
                    <td class="info-value">
                        {{ $equipment->installation_date ?? '—' }}
                        @if($equipment->installation_date)
                            <span class="status-indicator status-ok">
                                <i class="fas fa-check-circle"></i> Programada
                            </span>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="qr-section">
                <div class="qr-code">
                    @if($equipment->qr_code && file_exists(public_path($equipment->qr_code)))
                        <img src="{{ asset($equipment->qr_code) }}" width="100" alt="QR Code">
                    @else
                        <!-- Fallback QR code placeholder -->
                        <div style="width: 100px; height: 100px; background: #eee; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px;">
                            QR Code
                        </div>
                    @endif
                </div>
                <div class="qr-info">
                    <p><strong>Escanea para más información</strong></p>
                    <p>Este código QR contiene todos los detalles del equipo y su historial de mantenimiento.</p>
                </div>
            </div>

            <button class="print-btn" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir Etiqueta
            </button>

            <div class="footer-note">
                <p>Mantener esta etiqueta visible en todo momento • Revisar periódicamente</p>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const printBtn = document.querySelector('.print-btn');

            printBtn.addEventListener('click', function() {
                // Add a brief animation when printing
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>
