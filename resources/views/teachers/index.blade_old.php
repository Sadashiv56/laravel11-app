@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Teachers</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('teachers.create') }}" class="btn btn-primary">New Teacher</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap teacher">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Social media</th>
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
$(document).ready(function() {
    var table = $('.teacher').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('teachers.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'mobile', name: 'mobile' },
            { data: 'social_media', name: 'social_media' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
     $(document).on('click', '.delete-button', function(e) {
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
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                'Teacher has been deleted.',
                                'success'
                            );
                            table.row(row).remove().draw(); 
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the teacher.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the teacher.',
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