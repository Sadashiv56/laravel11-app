@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route("subcategories.index")}}" class="btn btn-primary">Back</a>
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
        <form action="{{ route("subcategories.store") }}" method="post" id="subcategoryform" name="subcategoryform">
            @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">Category name</label>
                             <span class="text-danger">*</span>
                            <select name="category_id" id="category_id" class="form-control" placeholder="Enter category name">
                                <option value="">Select</option>
                                @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Sub category name</label>
                             <span class="text-danger">*</span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Sub category name">
                        </div>
                    </div>
                    <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                 <span class="text-danger">*</span>
                                <select name="status" id="status" class="form-control" placeholder="Enter status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button class="btn btn-primary">Create</button>
        </div>
    </div>
    <!-- /.card -->
</section>
</form>
@endsection
@section('customejs')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#subcategoryform").validate({
            rules: {
                 category_id: {
                    required: true,
                },
                name: {
                    required: true,
                },
                slug: {
                    required: true,
                },
                 status: {
                    required: true,
                }

            },
            messages: {
                 category_id: {
                    required: "Category name required",
                },
                name: {
                    required: "Sub category name required",
                },
                slug: {
                    required: "Slug required",
                },
                 status: {
                    required: "Status required",
                }

            },
        });

        $('input[name=name]').on('blur keyup', function() {
            var slugElm = $('input[name=slug]');
            slugElm.val(this.value.toLowerCase()
                .replace(/[^a-z0-9-]+/g, '-')
                .replace(/^-+|-+$/g, ''));
            return slugElm;
        });
    });
</script>
@endsection
