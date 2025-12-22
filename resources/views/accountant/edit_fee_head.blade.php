@extends('accountant.layout')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Edit Fee Head</h2>

        <div id="alert-container"></div> {{-- Added alert container for consistent messaging --}}

        <form id="feeHeadForm" novalidate> {{-- Added novalidate to prevent browser's default validation --}}
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Fee Head Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback" id="name_error"></div> {{-- Validation error display --}}
                </div>

                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="invalid-feedback" id="status_error"></div> {{-- Validation error display --}}
                </div>

                <div class="mb-3 col-md-2">
                    <label for="is_mandatory" class="form-label">Is Mandatory</label>
                    <input type="checkbox" id="is_mandatory" name="is_mandatory" value="1">
                    <div class="invalid-feedback" id="is_mandatory_error"></div> {{--   Validation error display --}}
                </div>
                <div class="mb-3 col-md-2">
                    <label for="is_one_time" class="form-label">Is One Time</label>
                    <input type="checkbox" id="is_one_time" name="is_one_time" value="1">
                    <div class="invalid-feedback" id="is_one_time_error"></div> {{--   Validation error display --}}
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>

    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#feeHeadForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                clearValidationErrors(); // Clear previous validation errors

                let formData = {
                    name: $('#name').val(),
                    status: $('#status').val(),
                    is_mandatory: $('#is_mandatory').is(':checked') ? 1 : 0,
                    is_one_time: $('#is_one_time').is(':checked') ? 1 : 0,
                };

                $.ajax({
                    url: `/api/accountant/fee-heads/{{ request()->route('id') }}`,
                    type: 'PUT',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            showAlert("success", response.message ||
                                "Fee Head created successfully!");
                            window.location.href =
                            "{{ route('accountant.fee_heads') }}"; // Redirect to fee heads list
                        } else {
                            showAlert("danger", response.message ||
                                "Failed to create Fee Head.");
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Validation error
                            displayValidationErrors(xhr.responseJSON.errors);
                        } else {
                            showAlert("danger",
                                "An error occurred while creating the fee head.");
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

        // Function to auto fetch and populate existing fee head data
        let feeHeadId = "{{ request()->route('id') }}"; // Get the fee head ID from the route
        if (feeHeadId) {
            fetchFeeHeadData(feeHeadId);
        }

        function fetchFeeHeadData(feeHeadId) {
            $.ajax({
                url: `/api/accountant/fee-heads/${feeHeadId}`,
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success && response.data) {
                        $('#name').val(response.data.name);
                        $('#status').val(response.data.status);
                        $('#is_mandatory').prop('checked', response.data.is_mandatory == 1);
                        $('#is_one_time').prop('checked', response.data.is_one_time == 1);
                    } else {
                        showAlert("danger", "Failed to load fee head data.");
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    showAlert("danger", "An error occurred while fetching the fee head data.");
                }
            });
        }
    </script>
@endpush
