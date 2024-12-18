@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>
 </section>
 <div class="container">
    <form action="{{ route('users.update', $user->id) }}" method="POST" id="edit-user">
        @csrf
        @method('PATCH')
        <div class="row edit_table_fm">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <span class="text-danger">*</span>
                    <input type="text" name="first_name" id="first_name" placeholder="Enter first name" class="form-control" value="{{ old('first_name', $user->first_name) }}">
                    @error('first_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <span class="text-danger">*</span>
                    <input type="text" name="last_name" id="last_name" placeholder="Enter Last name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                    @error('last_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="email">Email</label>
                    <span class="text-danger">*</span>
                    <input type="email" name="email" id="email" placeholder="Enter email" class="form-control" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="mobile">Phone</label>
                    <span class="text-danger">*</span>
                    <input type="text" name="mobile" id="mobile" placeholder="Enter phone number" class="form-control" value="{{ old('mobile', $user->mobile) }}">
                    @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status">Status</label>
                    <span class="text-danger">*</span>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('customejs')
 <script>
    $(document).ready(function() {
        $("#edit-user").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                required: true,
                equalTo: "#password"
            },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                status: {
                    required: true
                },
            },
            messages: {
                first_name: {
                    required: "First name required",
                    minlength: "Your first name must consist of at least 2 characters"
                },
                last_name: {
                    required: "Last name required",
                    minlength: "Your last name must consist of at least 2 characters"
                },
                email: {
                    required: "Email required",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Password required",
                    minlength: "Your password must be at least 6 characters long"
                },
                 password_confirmation: {
                required: "Confirm password required",
                equalTo: "Passwords do not match"
            },
                mobile: {
                    required: "Phone number required",
                    digits: "Please enter a valid phone number",
                    minlength: "Phone number must be at least 10 digits",
                    maxlength: "Phone number must not exceed 15 digits"
                },
                status: {
                    required: "Status required",
                },
            },
        });
    });
</script>
@endsection
