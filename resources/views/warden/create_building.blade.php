@extends('warden.layout')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="mt-5 mb-3">
            <h2>Create Building</h2>
        </div>

        <div class="cust_box">
            <div class="cust_heading">
                Add Course
            </div>

            <div id="alert-container"></div> {{-- Added alert container for consistent messaging --}}

            <form id="buildingForm" novalidate> {{-- Added novalidate to prevent browser's default validation --}}
                @csrf
                <div class="row">

                    <div class="col-md-6 mb-2">
                        <label for="name" class="form-label">Building Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback" id="name_error"></div> {{-- Validation error display --}}
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="building_code" class="form-label">Building Code</label>
                        <input type="text" class="form-control" id="building_code" name="building_code" required>
                        <div class="invalid-feedback" id="building_code_error"></div> {{-- Validation error display --}}
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label><br>    
                        <input type="radio" id="male" name="gender" value="male" required>
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="female" required>
                        <label for="female">Female</label>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">-- Select Status --</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status_error"></div> {{-- Validation error display --}}
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="floors" class="form-label">Number of Floors</label>
                        <select class="form-control" id="floors" name="floors" required>
                            <option value="">-- Select Floors --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                        <div class="invalid-feedback" id="floors_error"></div> {{-- Validation error display --}}
                    </div>

                    <div class="col-md-12 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Create </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            // Handle form submission
            $('#buildingForm').on('submit', function (event) {
                event.preventDefault(); // Prevent default form submission
                clearValidationErrors(); // Clear previous validation errors

                let formData = {
                    name: $('#name').val(),
                    building_code: $('#building_code').val(),
                    floors: $('#floors').val(),
                    gender: $('input[name="gender"]:checked').val(),
                };

                $.ajax({
                    url: '/api/admin/buildings/create',
                    type: 'POST',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function (response) {
                        if (response.success) {
                            showAlert("success", response.message || "Building created successfully!");
                            $('#buildingForm')[0].reset(); // Reset the form
                        } else {
                            showAlert("danger", response.message || "Failed to create building.");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) { // Validation error
                            displayValidationErrors(xhr.responseJSON.errors);
                        } else {
                            showAlert("danger", "An error occurred while creating the building.");
                        }
                    }
                });
            });
        });


        function showAlert(type, message) {
            $('#alert-container').html(`
                                            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                                ${message}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        `);
        }
        function clearValidationErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }
        function displayValidationErrors(errors) {
            clearValidationErrors();
            for (const field in errors) {
                const input = $(`#${field}`);
                const errorDiv = $(`#${field}_error`);
                if (input.length) {
                    input.addClass('is-invalid ');
                }
                if (errorDiv.length) {
                    errorDiv.text(errors[field][0]); // Display the first error message
                }
            }
        }
    </script>
@endpush