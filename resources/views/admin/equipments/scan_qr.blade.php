@extends('layout.master')
@section('title', 'Escanear QR')

@section('main_content')
<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header">
      <h5>Escanear Código QR</h5>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <input type="text" id="qrCodeInput" class="form-control" placeholder="Ingresa el código QR o escanea" value="{{ old('code', $code ?? '') }}">
        <button id="checkStatus" class="btn btn-primary mt-2">Verificar Estado</button>
      </div>
      <div id="statusResult" class="mt-3"></div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const code = '{{ $code ?? '' }}';
    if (code) {
        $('#qrCodeInput').val(code);
        $('#checkStatus').trigger('click');
    }

    $('#checkStatus').click(function() {
        const code = $('#qrCodeInput').val();
        if (!code) {
            Swal.fire('Error', 'Por favor ingresa un código QR.', 'error');
            return;
        }
        $.get('{{ route("equipment.status") }}', { code: code }, function(response) {
            if (response.success) {
                const status = response.data.status === 'critical' ? 'Crítico' : 'Bueno';
                let html = `
                    <div class="alert alert-${response.data.status === 'critical' ? 'danger' : 'success'}">
                        Estado: ${status}<br>
                        Última Inspección: ${response.data.last_inspection || 'N/A'}<br>
                        Observaciones: ${response.data.observations || 'Ninguna'}
                `;
                if (response.data.qr_code_url) {
                    html += `<br><img src="${response.data.qr_code_url}" alt="QR Code" width="100">`;
                }
                html += `</div>`;
                $('#statusResult').html(html);
            } else {
                Swal.fire('Error', 'Equipo no encontrado.', 'error');
            }
        }).fail(() => Swal.fire('Error', 'No se pudo verificar el estado.', 'error'));
    });
});
</script>
@endsection
