@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Orders</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap order">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Payment Status</th>
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
    var table = $('.order').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('order.index') }}", 
        columns: [
            { data: 'id', name: 'id' },
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'email', name: 'email' },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            {
                data: 'payment_status',
                name: 'payment_status',
                render: function(data) {
                    if (data === 'completed') {
                        return '<span class="badge badge-success">' + data + '</span>';
                    } else if (data === 'pending') {
                        return '<span class="badge badge-danger">' + data + '</span>';
                    } else if (data === 'canceled') {
                        return '<span class="badge badge-secondary">' + data + '</span>';
                    }
                    return data; 
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
   $(document).on('click', '.change-status', function() {
    var orderId = $(this).data('id');
    var currentStatus = $(this).data('status');
    if (currentStatus === 'pending') {
        Swal.fire({
            title: 'Approve Payment',
            text: `Current status is ${currentStatus}. Do you want to approve the payment?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Approve Payment',
            cancelButtonText: 'Cancel Order'
        }).then((result) => {
            if (result.isConfirmed) {
                // Approve payment
                $.ajax({
                    url: "{{ url('order/approve') }}" + '/' + orderId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire('Approved!', 'Payment status has been approved.', 'success');
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            } else {
                $.ajax({
                    url: "{{ url('order/cancel') }}" + '/' + orderId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire('Canceled!', 'Order has been canceled.', 'success');
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    } else {
        Swal.fire('Info', 'This order cannot be approved or canceled.', 'info');
    }
});
});
</script>
@endsection




