@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap category">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
</section>
@endsection
@section('customejs')
<script>
    $(function() {
    var table = $('.category').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('categories.index') }}",
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
        var row = $(this).closest('tr'); 
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
                                'Category has been deleted.',
                                'success'
                            );
                            row.remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the category.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the category.',
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
