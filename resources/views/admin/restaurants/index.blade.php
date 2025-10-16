@extends('layout.master')

@section('title')
    Restaurants
@endsection

@section('main_content')
 <div class="card">
                        <div class="card-header">
<div class="container">
    <h2>Restaurants</h2>

    {{-- Flash Messages --}}
    @if(session('success'))
        <script>
            Swal.fire('Success', '{{ session('success') }}', 'success');
        </script>
    @endif

    {{-- Add Button --}}
    <button class="btn btn-primary mb-3 pull-right" data-bs-toggle="modal" data-bs-target="#createModal">Add Restaurant</button>

    {{-- Restaurant Table --}}
    <table id="basic-1" class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Location (Lat, Lng)</th>
                <th>Opening Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($restaurants as $restaurant)
            <tr>
                <td>{{ $restaurant->name }}</td>
                <td>{{ $restaurant->address }}</td>
                <td>{{ $restaurant->latitude }}, {{ $restaurant->longitude }}</td>
                <td>
                    @foreach($restaurant->openingHours as $hour)
                        <div>{{ $hour->day }}: {{ $hour->open_time }} - {{ $hour->close_time }}</div>
                    @endforeach
                </td>
                <td>
                    {{-- Delete Form --}}
                    <form method="POST" action="{{ route('restaurants.destroy', $restaurant->id) }}" class="d-inline delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                    {{-- Edit Button --}}
                    <button
                        class="btn btn-warning btn-sm edit-button"
                        data-id="{{ $restaurant->id }}"
                        data-name="{{ $restaurant->name }}"
                        data-address="{{ $restaurant->address }}"
                        data-latitude="{{ $restaurant->latitude }}"
                        data-longitude="{{ $restaurant->longitude }}"
                        data-hours='@json($restaurant->openingHours)'
                    >
                        Edit
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
                        </div>
 </div>
{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('restaurants.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add Restaurant</h5></div>
                <div class="modal-body">
                    <input name="name" class="form-control" placeholder="Name" required>
                    <input name="address" class="form-control mt-2" placeholder="Address" required>
                    <input name="latitude" class="form-control mt-2" placeholder="Latitude">
                    <input name="longitude" class="form-control mt-2" placeholder="Longitude">
                    <hr>
                    <h6>Opening Hours</h6>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <div class="row mb-2">
                            <input type="hidden" name="days[]" value="{{ $day }}">
                            <div class="col-md-4"><strong>{{ $day }}</strong></div>
                            <div class="col-md-4"><input type="time" name="open_times[]" class="form-control"></div>
                            <div class="col-md-4"><input type="time" name="close_times[]" class="form-control"></div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Restaurant</h5></div>
                <div class="modal-body">
                    <input name="name" id="edit_name" class="form-control" placeholder="Name" required>
                    <input name="address" id="edit_address" class="form-control mt-2" placeholder="Address" required>
                    <input name="latitude" id="edit_latitude" class="form-control mt-2" placeholder="Latitude">
                    <input name="longitude" id="edit_longitude" class="form-control mt-2" placeholder="Longitude">
                    <hr>
                    <h6>Opening Hours</h6>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <div class="row mb-2">
                            <input type="hidden" name="days[]" value="{{ $day }}">
                            <div class="col-md-4"><strong>{{ $day }}</strong></div>
                            <div class="col-md-4"><input type="time" name="open_times[]" class="form-control edit-open"></div>
                            <div class="col-md-4"><input type="time" name="close_times[]" class="form-control edit-close"></div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Scripts --}}
<script>
    // SweetAlert Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the restaurant!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Handle Edit Button Click
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const address = this.dataset.address;
            const latitude = this.dataset.latitude;
            const longitude = this.dataset.longitude;
            const hours = JSON.parse(this.dataset.hours);

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_latitude').value = latitude;
            document.getElementById('edit_longitude').value = longitude;

            const openInputs = document.querySelectorAll('.edit-open');
            const closeInputs = document.querySelectorAll('.edit-close');
            for (let i = 0; i < 7; i++) {
                openInputs[i].value = hours[i]?.open_time ?? '';
                closeInputs[i].value = hours[i]?.close_time ?? '';
            }

            // Update form action dynamically
            document.getElementById('editForm').action = `/admin/restaurants/${id}`;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });
</script>
@endsection
