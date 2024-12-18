@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Category</h1>
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
        <form id="categoryform" method="POST" action="{{ route('categories.update', $category->id) }}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Category Name</label>
                                <span class="text-danger">*</span>
                                <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" placeholder="Enter category name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <span class="text-danger">*</span>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ $category->status == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $category->status == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_id">Parent Category</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="0">Select Parent Category</option>
                                    @foreach($categories as $categoryOption)
                                        <option value="{{ $categoryOption->id }}" 
                                            {{ isset($category) && $category->parent_id == $categoryOption->id ? 'selected' : '' }}>
                                            {{ $categoryOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</section>
@endsection
@section('customejs')
<script>
    $(document).ready(function() {
        $("#categoryform").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                status: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter a category name",
                    minlength: "Category name must be at least 3 characters long",
                },
                status: {
                    required: "Please select a status"
                }
            }
        });
        let initialStatus = $('#status').val();
        $('#status').on('change', function (e) {
            let selectedStatus = $(this).val();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to change the category status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    initialStatus = selectedStatus;
                } else {
                    $('#status').val(initialStatus); 
                }
            });
        });
    });
</script>
@endsection
