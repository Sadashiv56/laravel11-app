<script>
    $(function() {
    var table = $('#table-users').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('users.index') }}",
    columns: [
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'first_name',
            name: 'first_name',
        },
        {
            data: 'last_name',
            name: 'last_name',
        },
        
        {
            data: 'email',
            name: 'email',
        },
        {
            data: 'mobile',
            name: 'mobile',
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
                                'User has been deleted.',
                                'success'
                            );
                            row.remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the user.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the user.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>