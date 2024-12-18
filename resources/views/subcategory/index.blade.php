@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route("subcategories.create") }}" class="btn btn-success">New Sub Category</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap subcategory">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>


                </table>
            </div>
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection
@section('customejs')
<script>
    $(function() {
    var table = $('.subcategory').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('subcategories.index') }}",
    columns: [
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'name',
            name: 'name',
        },
        {
            data: 'slug',
            name: 'slug',
        },

        { data: 'category.name', name: 'category.name' }, // Corrected column definition
          {
            data: 'status',
            name: 'status',
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },

    ]
});

});

$(document).ready(function() {
    $(document).on('click', '.delete-form', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action');
        var row = $(this).closest('tr'); // Assuming the delete button is within a table row

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            Swal.fire(
                                'Deleted!',
                                'SubCategory has been deleted.',
                                'success'
                            );
                            row.remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the subcategory.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the subcategory.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});

</script>
@endsection
