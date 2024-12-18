@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Teacher</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('teachers.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <form action="{{ route('teachers.update', $teacher->id) }}" id="teacher_form" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <span class="text-danger">*</span>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter teacher name" value="{{ old('name', $teacher->name) }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <span class="text-danger">*</span>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email', $teacher->email) }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="mobile">Mobile</label>
            <span class="text-danger">*</span>
            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" value="{{ old('mobile', $teacher->mobile) }}">
            @error('mobile')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="about_teacher">About Teacher</label>
            <span class="text-danger">*</span>
            <textarea class="form-control" id="about_teacher" name="about_teacher" placeholder="Enter about teacher">{{ old('about_teacher', $teacher->about_teacher) }}</textarea>
            @error('about_teacher')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="social_media">Social Media</label>
            <span class="text-danger">*</span>
            <select class="form-control" id="social_media_select" name="social_media">
                <option value="" disabled>Select Social Media</option>
                <option value="facebook" {{ old('social_media', $teacher->social_media) == 'facebook' ? 'selected' : '' }}>Facebook</option>
                <option value="instagram" {{ old('social_media', $teacher->social_media) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                <option value="linkdin" {{ old('social_media', $teacher->social_media) == 'linkdin' ? 'selected' : '' }}>LinkedIn</option>
                <option value="youtube" {{ old('social_media', $teacher->social_media) == 'youtube' ? 'selected' : '' }}>YouTube</option>
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
                @foreach($educationHistories as $education)
               <div class="education-entry" data-id="{{ $education->id }}">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="education_title">Title</label>
                            <input type="text" class="form-control" name="education[title][]" value="{{ $education->title }}" placeholder="Title">
                        </div>
                        <div class="col-md-4">
                            <label for="education_start_year">Start Year</label>
                            <select class="form-control" name="education[start_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" {{ $education->start_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="education_end_year">End Year</label>
                            <select class="form-control" name="education[end_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" {{ $education->end_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="education_description">Short Description</label>
                        <textarea class="form-control" name="education[description][]" placeholder="Description">{{ $education->short_description }}</textarea>
                    </div>
                    <button type="button" class="btn btn-danger remove-education">Remove</button>
                    <hr>
                </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Experience</h4>
               <button type="button" class="btn btn-success" id="add-experience">Add Experience</button>
            </div>
            <div id="experience-container">
                @foreach($experiences as $experience)
                <div class="experience-entry" data-id="{{ $experience->id }}">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="experience_company">Company Name</label>
                            <input type="text" class="form-control" name="experience[company][]" value="{{ $experience->company_name }}" placeholder="Company Name">
                        </div>
                        <div class="col-md-4">
                            <label for="experience_start_year">Start Year</label>
                            <select class="form-control" name="experience[start_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" {{ $experience->start_year == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="experience_end_year">End Year</label>
                            <select class="form-control" name="experience[end_year][]">
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" {{ $experience->end_year == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="experience_description">Description</label>
                        <textarea class="form-control" name="experience[description][]" placeholder="Description">{{ $experience->description }}</textarea>
                    </div>
                    <button type="button" class="btn btn-danger remove-experience">Remove</button>
                    <hr>
                </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="products">Associated Products</label>
            <select name="products[]" id="products" class="form-control select2" multiple>
                <option value="">Select Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                        {{ in_array($product->id, old('products', $selectedProducts)) ? 'selected' : '' }}>
                        {{ $product->title }}
                    </option>
                @endforeach
            </select>
                @error('products')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">Update Teacher</button>
        </div>
    </form>
</div>
@endsection
@section('customejs')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Add new education entry
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

    // Add new experience entry
    $('#add-experience').click(function() {
        $('#experience-container').append(`
            <div class="experience-entry">
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="experience_company">Company Name</label>
                        <input type="text" class="form-control" name="experience[company][]" placeholder="Company Name">
                    </div>
                    <div class="col-md-4">
                        <label for="experience_start_year">Start Year</label>
                        <select class="form-control" name="experience[start_year][]">
                            ${generateYearOptions()}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="experience_end_year">End Year</label>
                        <select class="form-control" name="experience[end_year][]">
                            ${generateYearOptions()}
                        </select>
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

    // Remove education entry with validation
    $(document).on('click', '.remove-education', function() {
        if ($('#education-container .education-entry').length > 1) {
            $(this).closest('.education-entry').remove();
        } else {
            alert('At least one education entry is required.');
        }
    });

    // Remove experience entry with validation
    $(document).on('click', '.remove-experience', function() {
        if ($('#experience-container .experience-entry').length > 1) {
            $(this).closest('.experience-entry').remove();
        } else {
            alert('At least one experience entry is required.');
        }
    });

    function generateYearOptions() {
        const currentYear = new Date().getFullYear();
        let options = '';
        for (let year = currentYear; year >= 1900; year--) {
            options += `<option value="${year}">${year}</option>`;
        }
        return options;
    }
});


</script>
@endsection
