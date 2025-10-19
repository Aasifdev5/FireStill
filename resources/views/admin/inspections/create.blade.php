@extends('layout.master')
@section('title', 'Nueva Inspección')

@section('main_content')
    <!-- External CSS and Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #e1e5ee;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            border-color: var(--primary-color);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .checklist-section {
            background-color: var(--light-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .checklist-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .checklist-title i {
            margin-right: 0.5rem;
        }

        .checklist-item {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
            transition: var(--transition);
        }

        .checklist-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }

        .checklist-question {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-check-input {
            margin-right: 0.5rem;
        }

        .form-check-label {
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .photo-upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background-color: #f8fafc;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }

        .photo-upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .photo-upload-icon {
            font-size: 3rem;
            color: #a0aec0;
            margin-bottom: 1rem;
        }

        .photo-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .photo-preview {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .photo-preview:hover img {
            transform: scale(1.05);
        }

        .photo-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            z-index: 1;
        }

        .photo-remove:hover {
            background: rgba(220, 53, 69, 0.8);
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e1e5ee, transparent);
            margin: 2rem 0;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-complete {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-pending::before {
            content: attr(data-count);
        }

        .status-pending::after {
            content: ' Pendiente';
        }

        .status-complete::before {
            content: 'Completado';
        }

        .equipment-badge {
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .equipment-badge i {
            margin-right: 0.5rem;
        }

        #imageModal .modal-body img {
            max-height: 80vh;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }

            .checklist-section {
                padding: 1rem;
            }

            .photo-preview {
                width: 100px;
                height: 100px;
            }
        }
    </style>

    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-clipboard-check me-2"></i>Nueva Inspección de Equipo</h5>
            </div>
            <div class="card-body">
                <div class="equipment-badge">
                    <i class="fas fa-fire-extinguisher"></i>
                    <span>Equipo: {{ $equipment->code }}</span>
                </div>

                <form id="inspectionForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                    <!-- Fechas Section -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="inspected_at" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Fecha de Inspección
                            </label>
                            <input type="datetime-local" name="inspected_at" id="inspected_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expiration_date" class="form-label">
                                <i class="fas fa-calendar-times me-2"></i>Fecha de Vencimiento
                            </label>
                            <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
                        </div>
                    </div>

                    <!-- Checklist Section -->
                    <div class="checklist-section">
                        <h6 class="checklist-title">
                            <i class="fas fa-tasks me-2"></i>Lista de Verificación
                            <span class="status-indicator status-pending" data-count="0/9"></span>
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Left Column -->
                                <div class="checklist-item">
                                    <div class="checklist-question">¿Tiene Presión?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_pressure]" id="has_pressure_yes" value="yes" required>
                                            <label class="form-check-label" for="has_pressure_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_pressure]" id="has_pressure_no" value="no">
                                            <label class="form-check-label" for="has_pressure_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿Tiene Sello?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_seal]" id="has_seal_yes" value="yes" required>
                                            <label class="form-check-label" for="has_seal_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_seal]" id="has_seal_no" value="no">
                                            <label class="form-check-label" for="has_seal_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿Presenta Anomalías?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[presents_abnormalities]" id="presents_abnormalities_yes" value="yes" required>
                                            <label class="form-check-label" for="presents_abnormalities_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[presents_abnormalities]" id="presents_abnormalities_no" value="no">
                                            <label class="form-check-label" for="presents_abnormalities_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿Condición de Pintura?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[paint_condition]" id="paint_condition_yes" value="yes" required>
                                            <label class="form-check-label" for="paint_condition_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[paint_condition]" id="paint_condition_no" value="no">
                                            <label class="form-check-label" for="paint_condition_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿Ubicación es Correcta?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[location_correct]" id="location_correct_yes" value="yes" required>
                                            <label class="form-check-label" for="location_correct_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[location_correct]" id="location_correct_no" value="no">
                                            <label class="form-check-label" for="location_correct_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Right Column -->
                                <div class="checklist-item">
                                    <div class="checklist-question">¿Está Obstruido?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[is_obstructed]" id="is_obstructed_yes" value="yes" required>
                                            <label class="form-check-label" for="is_obstructed_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[is_obstructed]" id="is_obstructed_no" value="no">
                                            <label class="form-check-label" for="is_obstructed_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿Tiene Señalización e Identificación?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_signage_identification]" id="has_signage_identification_yes" value="yes" required>
                                            <label class="form-check-label" for="has_signage_identification_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_signage_identification]" id="has_signage_identification_no" value="no">
                                            <label class="form-check-label" for="has_signage_identification_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿Tiene Etiqueta de Instrucciones?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_instruction_label]" id="has_instruction_label_yes" value="yes" required>
                                            <label class="form-check-label" for="has_instruction_label_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[has_instruction_label]" id="has_instruction_label_no" value="no">
                                            <label class="form-check-label" for="has_instruction_label_no">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="checklist-item">
                                    <div class="checklist-question">¿La Boquilla está Limpia y Libre de Polvo?</div>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[nozzle_clean_free_dust]" id="nozzle_clean_free_dust_yes" value="yes" required>
                                            <label class="form-check-label" for="nozzle_clean_free_dust_yes">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="checklist[nozzle_clean_free_dust]" id="nozzle_clean_free_dust_no" value="no">
                                            <label class="form-check-label" for="nozzle_clean_free_dust_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observations Section -->
                    <div class="mb-4">
                        <label for="observations" class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>Observaciones
                        </label>
                        <textarea name="observations" id="observations" class="form-control" rows="5" placeholder="Ingrese cualquier observación relevante sobre la inspección..."></textarea>
                    </div>

                    <!-- Photos Section -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-camera me-2"></i>Fotografías
                        </label>

                        <div class="photo-upload-area" id="photoUploadArea">
                            <div class="photo-upload-icon">
                                <i class="fas fa-camera me-2"></i>
                            </div>
                            <h5>Arrastra y suelta las fotos aquí</h5>
                            <p class="text-muted">o haz clic para seleccionar archivos</p>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="d-none">
                        </div>

                        <div class="photo-preview-container" id="photoPreviewContainer">
                            <!-- Preview images will be added here -->
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Inspección
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Vista Previa de Imagen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <!-- External JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedFiles = [];

            // Photo upload functionality
            const photoUploadArea = document.getElementById('photoUploadArea');
            const photoInput = document.getElementById('photos');
            const photoPreviewContainer = document.getElementById('photoPreviewContainer');

            if (photoUploadArea && photoInput && photoPreviewContainer) {
                // Click on upload area to trigger file input
                photoUploadArea.addEventListener('click', function() {
                    photoInput.click();
                });

                // Handle file selection
                photoInput.addEventListener('change', function(e) {
                    handleFiles(e.target.files);
                    e.target.value = ''; // Clear input to allow re-selecting same files
                });

                // Drag and drop functionality
                photoUploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    photoUploadArea.style.borderColor = '#4361ee';
                    photoUploadArea.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
                });

                photoUploadArea.addEventListener('dragleave', function() {
                    photoUploadArea.style.borderColor = '#cbd5e0';
                    photoUploadArea.style.backgroundColor = '#f8fafc';
                });

                photoUploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    photoUploadArea.style.borderColor = '#cbd5e0';
                    photoUploadArea.style.backgroundColor = '#f8fafc';

                    if (e.dataTransfer.files.length) {
                        handleFiles(e.dataTransfer.files);
                    }
                });

                // Handle selected files
                function handleFiles(files) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        if (!file.type.match('image.*')) {
                            continue;
                        }

                        if (selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                            continue; // Skip duplicates
                        }

                        selectedFiles.push(file);

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'photo-preview';
                            previewDiv.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="photo-remove"><i class="fas fa-times"></i></button>
                            `;

                            photoPreviewContainer.appendChild(previewDiv);

                            // Remove photo
                            const removeButton = previewDiv.querySelector('.photo-remove');
                            removeButton.addEventListener('click', function(e) {
                                e.stopPropagation(); // Prevent triggering preview click
                                const index = Array.from(photoPreviewContainer.children).indexOf(previewDiv);
                                selectedFiles.splice(index, 1);
                                previewDiv.remove();
                            });

                            // View image in modal
                            previewDiv.addEventListener('click', function(e) {
                                if (!e.target.classList.contains('photo-remove') && !e.target.classList.contains('fas')) {
                                    const modalImage = document.getElementById('modalImage');
                                    modalImage.src = previewDiv.querySelector('img').src;
                                    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                                    modal.show();
                                }
                            });
                        };

                        reader.readAsDataURL(file);
                    }
                }

                // Update checklist status
                const radioInputs = document.querySelectorAll('input[type="radio"]');
                radioInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const totalQuestions = document.querySelectorAll('.checklist-item').length;
                        const answeredQuestions = document.querySelectorAll('.checklist-item input[type="radio"]:checked').length;
                        const statusIndicator = document.querySelector('.status-indicator');

                        if (statusIndicator) {
                            if (answeredQuestions === totalQuestions) {
                                statusIndicator.classList.remove('status-pending');
                                statusIndicator.classList.add('status-complete');
                                statusIndicator.removeAttribute('data-count');
                            } else {
                                statusIndicator.classList.remove('status-complete');
                                statusIndicator.classList.add('status-pending');
                                statusIndicator.setAttribute('data-count', `${answeredQuestions}/${totalQuestions}`);
                            }
                        }
                    });
                });

                // Form submission
                const inspectionForm = document.getElementById('inspectionForm');
                if (inspectionForm) {
                    inspectionForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const totalQuestions = document.querySelectorAll('.checklist-item').length;
                        const answeredQuestions = document.querySelectorAll('.checklist-item input[type="radio"]:checked').length;

                        if (answeredQuestions < totalQuestions) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Lista de verificación incompleta',
                                text: `Por favor, responda todas las preguntas de la lista de verificación. (${answeredQuestions}/${totalQuestions} completadas)`,
                                confirmButtonColor: '#4361ee'
                            });
                            return;
                        }

                        let formData = new FormData(inspectionForm);

                        // Clear existing files and set new ones
                        const dt = new DataTransfer();
                        selectedFiles.forEach(file => dt.items.add(file));
                        photoInput.files = dt.files;

                        formData = new FormData(inspectionForm);

                        fetch('{{ route("inspections.store") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Inspección guardada y PDF generado.',
                                confirmButtonColor: '#4361ee'
                            }).then(() => {
                                window.location.href = '{{ route("inspections.index") }}';
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo guardar la inspección: ' + error.message,
                                confirmButtonColor: '#4361ee'
                            });
                        });
                    });
                }
            } else {
                console.error('Required elements not found in the DOM');
            }
        });
    </script>

@endsection
