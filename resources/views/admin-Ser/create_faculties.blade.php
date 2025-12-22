@extends('admin.layout')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="mt-5 mb-3">
            <h2>Create Faculty</h2>
        </div>

        <div class="cust_box">
            <div class="cust_heading">
                Add Faculty
            </div>

            <div id="alert-container"></div> {{-- Added alert container for consistent messaging --}}

            <form id="facultiesForm" novalidate> {{-- Added novalidate to prevent browser's default validation --}}
                @csrf
                <div class="row">

                    <div class="col-md-5">
                        <label for="name" class="form-label">Faculty Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback" id="name_error"></div> {{-- Validation error display --}}
                    </div>

                    <div class="col-md-5">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">-- Select Status --</option>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status_error"></div> {{-- Validation error display --}}
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Create</button>
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
            $('#facultiesForm').on('submit', function (event) {
                event.preventDefault(); // Prevent default form submission
                clearValidationErrors(); // Clear previous validation errors

                let formData = {
                    name: $('#name').val(),
                    status: $('#status').val(),
                };

                $.ajax({
                    url: '/api/admin/faculties/create',
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
                            showAlert("success", response.message || "Faculty created successfully!");
                            window.location.href = "{{ route('admin.faculties') }}"; // Redirect to faculties list
                        } else {
                            showAlert("danger", response.message || "Failed to create Faculty.");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) { // Validation error
                            displayValidationErrors(xhr.responseJSON.errors);
                        } else {
                            showAlert("danger", "An error occurred while creating the faculty.");
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