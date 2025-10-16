@extends('layout.master')

@section('title')
    {{ __('Editar Producto') }}
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
                            <h2>{{ __('Editar Producto') }}</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('products.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Category --}}
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">{{ __('Categoría') }}</label>
                                    <select name="category_id" id="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">{{ __('Seleccionar Categoría') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Subcategory --}}
                                {{-- <div class="mb-3">
                                    <label for="subcategory_id" class="form-label">{{ __('Subcategoría') }}</label>
                                    <select name="subcategory_id" id="subcategory_id"
                                        class="form-control @error('subcategory_id') is-invalid @enderror">
                                        <option value="">{{ __('Seleccionar Subcategoría') }}</option>
                                    </select>
                                    @error('subcategory_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                {{-- Product Code --}}
                                <div class="mb-3">
                                    <label for="code" class="form-label">{{ __('Código del Producto') }}</label>
                                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                        value="{{ old('code', $product->code) }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Product Name --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Nombre del Producto') }}</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Descripción') }}</label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Price --}}
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ __('Precio') }}</label>
                                    <input type="number" step="0.01" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', isset($product) ? $product->price : '') }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Imagen del Producto') }}</label>
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="Product Image" class="img-thumbnail mt-2"
                                            style="max-width: 150px;">
                                    @endif
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Buttons --}}
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> {{ __('Actualizar Producto') }}
                                </button>
                                <a href="{{ route('products.list') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> {{ __('Volver') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->

    {{-- jQuery and AJAX Script --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var selectedSubcategoryId = "{{ old('subcategory_id', $product->subcategory_id) }}";

            function loadSubcategories(categoryId, selectedId = null) {
                $('#subcategory_id').empty().append('<option value="">{{ __("Cargando...") }}</option>');
                $.ajax({
                    url: '/get-subcategories/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $('#subcategory_id').empty();
                        $('#subcategory_id').append('<option value="">{{ __("Seleccionar Subcategoría") }}</option>');
                        $.each(data, function (key, value) {
                            $('#subcategory_id').append(
                                '<option value="' + value.id + '"' +
                                (selectedId == value.id ? ' selected' : '') +
                                '>' + value.name + '</option>'
                            );
                        });
                    },
                    error: function (xhr) {
                        console.error('Error fetching subcategories:', xhr);
                        $('#subcategory_id').empty().append('<option value="">{{ __("Seleccionar Subcategoría") }}</option>');
                    }
                });
            }

            // Initial load if category is preselected
            var initialCategoryId = $('#category_id').val();
            if (initialCategoryId) {
                loadSubcategories(initialCategoryId, selectedSubcategoryId);
            }

            // Load subcategories when category changes
            $('#category_id').on('change', function () {
                var categoryId = $(this).val();
                if (categoryId) {
                    loadSubcategories(categoryId);
                } else {
                    $('#subcategory_id').empty().append('<option value="">{{ __("Seleccionar Subcategoría") }}</option>');
                }
            });
        });
    </script>
@endsection
```
