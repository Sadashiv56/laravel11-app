@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="categoryForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Category Name</label>
                                <span class="text-danger">*</span>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter category name">
                                <p id="category-message" class="text-success"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <span class="text-danger">*</span>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_id">Parent Category</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">Select Parent Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="child_category_div" style="display: none;">
                            <div class="mb-3">
                                <label for="child_id">Child Category</label>
                                <select name="child_id" id="child_id" class="form-control">
                                    <option value="">Select Child Category</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</section>
@endsection
@section('customejs')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#categoryForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    remote: {
                        url: "{{ route('categories.checkName') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: function() {
                                return $("#name").val();
                            }
                        }
                    }
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Category name required",
                    minlength: "Category name must be at least 3 characters long",
                    remote: "Category name already exists."
                },
                status: {
                    required: "Please select a status"
                }
            },
            submitHandler: function(form) {
                let formData = $(form).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('categories.store') }}",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            window.location.href = response.redirect_url; 
                        } else {
                            toastr.error('Failed to save category.');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error occurred while saving category.');
                    }
                });
            }
        });
    });
</script>
@endsection