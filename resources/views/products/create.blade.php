@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ isset($product) ? 'Edit Product' : 'Create Product' }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <form id="productForm" action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Product Title -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title">Product Title</label>
                                <span class="text-danger">*</span>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Enter product title" value="{{ old('title', $product->title ?? '') }}">
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- SKU -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku">SKU</label>
                                <span class="text-danger">*</span>
                                <input type="text" name="sku" id="sku" class="form-control" placeholder="Enter SKU" value="{{ old('sku', $product->sku ?? '') }}">
                                @error('sku')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Price -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <span class="text-danger">*</span>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Enter product price" value="{{ old('price', $product->price ?? '') }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Quantity -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity">Quantity</label>
                                <span class="text-danger">*</span>
                                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter product quantity" value="{{ old('quantity', $product->quantity ?? '') }}">
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--Short Description --> 
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="short_description">Shortdescription</label>
                                <textarea name="short_description" id="short_description" class="form-control" placeholder="Enter product shortdescription">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Enter product description">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>
                        </div>
                        <!-- Specifications -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="specifications">Specifications</label>
                                <div id="specification-fields">
                                    @php
                                        $specifications = old('specifications', isset($product) ? json_decode($product->specifications, true) : []);
                                    @endphp

                                    @foreach($specifications as $index => $specification)
                                        <div class="input-group mb-2">
                                            <input type="text" name="specifications[{{ $index }}][title]" class="form-control" placeholder="Specification Title" value="{{ $specification['title'] ?? '' }}">
                                            <input type="text" name="specifications[{{ $index }}][value]" class="form-control" placeholder="Specification Value" value="{{ $specification['value'] ?? '' }}">
                                            <button type="button" class="btn btn-danger btn-remove-specification">Remove</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="btn-add-specification" class="btn btn-secondary mt-2">Add Specification</button>
                            </div>
                        </div>
                        <!-- Categories -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="categories">Categories</label>
                                <select name="categories[]" id="categories" class="form-control select2" multiple >
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', explode(',', $product->category_ids ?? ''))) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Product IDs -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="product_ids">Products</label>
                                <select name="product_ids[]" id="product_ids" class="form-control select2" multiple>
                                    @foreach($allProducts as $prod)
                                        <option value="{{ $prod->id }}" {{ in_array($prod->id, old('product_ids', explode(',', $product->product_ids ?? ''))) ? 'selected' : '' }}>{{ $prod->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Image -->
                       <div class="col-md-12">
                            <div class="mb-3">
                                <label for="image">Product Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                                @if(isset($product) && $product->image)
                                    <!-- Display the existing image -->
                                    <img src="{{ asset('/products_photo/' . $product->image) }}" alt="Product Image" style="max-width: 150px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update Product' : 'Create Product' }}</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customejs')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
$(document).ready(function() {
     $.validator.addMethod('skuUnique', function(value, element) {
        var isUnique = false;
        var productId = "{{ isset($product) ? $product->id : null }}"; 

        $.ajax({
            url: "{{ route('products.checkSku') }}",
            type: 'POST',
            async: false,
            data: {
                sku: value,
                product_id: productId, 
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                isUnique = !response.exists;
            }
        });

        return isUnique;
    }, 'This SKU is already taken.');

    $('#productForm').validate({
        rules: {
            title: {
                required: true,
                minlength: 3
            },
            sku: {
                required: true,
                minlength: 3,
                skuUnique: true 
            },
            price: {
                required: true,
                number: true
            },
            quantity: {
                required: true,
                number: true
            }
        },
        messages: {
            title: {
                required: "Please enter the product title",
                minlength: "Product title must be at least 3 characters"
            },
            sku: {
                required: "Please enter the SKU",
                minlength: "SKU must be at least 3 characters",
                skuUnique: "This SKU is already taken."
            },
            price: {
                required: "Please enter the product price",
                number: "Please enter a valid price"
            },
            quantity: {
                required: "Please enter the product quantity",
                number: "Please enter a valid quantity"
            }
        }
    });
     $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true,
        theme: 'bootstrap4'
    });
    $('#btn-add-specification').on('click', function() {
        var index = $('#specification-fields .input-group').length; 
        var html = '<div class="input-group mb-2">' +
            '<input type="text" name="specifications[' + index + '][title]" class="form-control" placeholder="Specification Title">' +
            '<input type="text" name="specifications[' + index + '][value]" class="form-control" placeholder="Specification Value">' +
            '<button type="button" class="btn btn-danger btn-remove-specification">Remove</button>' +
            '</div>';
        $('#specification-fields').append(html);
    });
    $(document).on('click', '.btn-remove-specification', function() {
        $(this).closest('.input-group').remove();
    });
});
</script>
@endsection
