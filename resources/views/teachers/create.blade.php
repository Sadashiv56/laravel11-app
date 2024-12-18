@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Teacher</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('teachers.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <form action="{{ route('teachers.store') }}" id="teacher_form" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <span class="text-danger">*</span>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter teacher name" >
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <span class="text-danger">*</span>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="mobile">Mobile</label>
            <span class="text-danger">*</span>
            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile">
            @error('mobile')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="about_teacher">About Teacher</label>
            <span class="text-danger">*</span>
            <textarea class="form-control" id="about_teacher" name="about_teacher" placeholder="Enter about teacher"></textarea>
            @error('about_teacher')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="social_media">Social Media</label>
            <span class="text-danger">*</span>
             <select class="form-control" id="social_media_select" name="social_media">
                <option value="" disabled selected>Select Social Media</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
                <option value="linkdin">LinkedIn</option>
                <option value="youtube">YouTube</option>
             </select>
             @error('social_media')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Education History</h4>
                <button type="button" class="btn btn-success" id="add-education-description">Add Education</button>
            </div>
            <div id="education-container">
                <div class="education-entry">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="education_title">Title</label>
                            <input type="text" class="form-control" name="education[title][]" placeholder="Title">
                        </div>
                        <div class="col-md-4">
                            <label for="education_start_year">Start Year</label>
                            <select class="form-control" name="education[start_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="education_end_year">End Year</label>
                            <select class="form-control" name="education[end_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="education_description">Short Description</label>
                        <textarea class="form-control" name="education[description][]" placeholder="Description"></textarea>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Experience</h4>
                <button type="button" class="btn btn-success" id="add-experience-description">Add Experience</button>
            </div>
            <div id="experience-container">
                <div class="experience-entry">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="experience_company_name">Company name</label>
                            <input type="text" class="form-control" name="experience[company_name][]" placeholder="companyname">
                        </div>
                        <div class="col-md-4">
                            <label for="experience_start_year">Start Year</label>
                            <select class="form-control" name="experience[start_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="experience_end_year">End Year</label>
                            <select class="form-control" name="experience[end_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="experience_description">Short Description</label>
                        <textarea class="form-control" name="experience[description][]" placeholder="Description"></textarea>
                    </div>
                    <hr>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="products">Associated Products</label>
            <select name="products[]" id="products" class="form-control select2" multiple>
            <option value="">Select Products</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ in_array($product->id, old('products', [])) ? 'selected' : '' }}>
                    {{ $product->title }}
                </option>
            @endforeach
            </select>
            @error('products')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <center><button type="submit" class="btn btn-primary">Submit</button></center>
    </form>
</div>
@endsection
@section('customejs')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#teacher_form').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                digits: true
            },
            about_teacher: {
                required: true
            },
            social_media: {
                required: true
            },
            'products[]': {
                required: true
            }
        },
        messages: {
            name: {
                required: "Name required"
            },
            email: {
                required: "Email required",
                email: "Please enter a valid email address"
            },
            mobile: {
                required: "Mobile required",
                digits: "Please enter a valid mobile number"
            },
            about_teacher: {
                required: "About teacher required"
            },
            social_media: {
                required: "Please select a social media option"
            },
            'products[]': {
                required: "Please select at least one product"
            }
        },
        submitHandler: function(form) {
            form.submit(); // or you can use Ajax to submit the form data if needed
        }
    });

    // Function to add education entry
    $('#add-education-description').click(function() {
    $('#education-container').append(`
        <div class="education-entry">
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="education_title">Title</label>
                    <input type="text" class="form-control" name="education[title][]" placeholder="Title">
                </div>
                <div class="col-md-4">
                    <label for="education_start_year">Start Year</label>
                    <select class="form-control" name="education[start_year][]">
                        ${generateYearOptions()}
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="education_end_year">End Year</label>
                    <select class="form-control" name="education[end_year][]">
                        ${generateYearOptions()}
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="education_description">Short Description</label>
                <textarea class="form-control" name="education[description][]" placeholder="Description"></textarea>
            </div>
            <button type="button" class="btn btn-danger remove-education">Remove</button>
            <hr>
        </div>
    `);
});

// Function to generate year options for the select elements
function generateYearOptions() {
    const currentYear = new Date().getFullYear();
    let options = '';
    for (let year = currentYear; year >= 1900; year--) {
        options += `<option value="${year}">${year}</option>`;
    }
    return options;
}


    // Function to add experience entry
   $('#add-experience-description').click(function() {
    $('#experience-container').append(`
        <div class="experience-entry">
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="experience_company_name">Title</label>
                    <input type="text" class="form-control" name="experience[company_name][]" placeholder="companyname">
                </div>
                <div class="col-md-4">
                    <label for="experience_start_year">Start Year</label>
                    <input type="number" class="form-control" name="experience[start_year][]" placeholder="Start Year">
                </div>
                <div class="col-md-4">
                    <label for="experience_end_year">End Year</label>
                    <input type="number" class="form-control" name="experience[end_year][]" placeholder="End Year">
                </div>
            </div>
            <div class="form-group">
                <label for="experience_description">Short Description</label>
                <textarea class="form-control" name="experience[description][]" placeholder="Description"></textarea>
            </div>
            <button type="button" class="btn btn-danger remove-experience">Remove</button>
            <hr>
        </div>
    `);
});
    // Function to remove education entry
    $(document).on('click', '.remove-education', function() {
        $(this).closest('.education-entry').remove();
    });

    // Function to remove experience entry
    $(document).on('click', '.remove-experience', function() {
        $(this).closest('.experience-entry').remove();
    });
});

</script>
@endsection
