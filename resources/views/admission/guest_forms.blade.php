@extends('admission.layout')

@section('content')
    <div class="container mt-5">
        <div class="mt-5 mb-3">
            <h3>List of Guests</h3>
        </div>
        <div class="mb-4 cust_box">
        <div id="responseMessage" class="mt-3"></div> {{-- Added message container here --}}

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>S. No</th>
                    <th>Scholar No</th>
                    <th>Resident Name</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Gender</th>
                    <th>Verification Status</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="guestsList">
            </tbody>
        </table>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Fetch guests when the document is ready
            fetchGuests();

            // Function to show a custom message box
            function showCustomMessageBox(message, type = 'info') {
                const messageContainer = $('#responseMessage');
                messageContainer.html(`<div class="alert alert-${type}">${message}</div>`);
                setTimeout(() => messageContainer.empty(), 3000); // Clear after 3 seconds
            }
        });

        // Function to fetch guests from the API
        function fetchGuests() {
            $.ajax({
                url: "{{ url('/api/admin/guests') }}",
                type: 'GET',
                // headers: {
                //     'token': localStorage.getItem('token'),
                //     'Auth-ID': localStorage.getItem('auth-id')
                // },
                // headers: {
                //     "Accept": "application/json",
                //     "Authorization": `Bearer ${localStorage.getItem('token')}`
                // },
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                },

                success: function(response) {
                    const guests = response.data;
                    const guestsList = $("#guestsList");
                    guestsList.empty();

                    if (!Array.isArray(guests) || guests.length === 0) {
                        guestsList.append(
                            `<tr><td colspan="10" class="text-center">No guests found.</td></tr>`);
                        return;
                    }

                    guests.forEach((guest, index) => {

                        guestsList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${guest.scholar_no || 'N/A'}</td>
                            <td>${guest.name || 'N/A'}</td>
                            <td>${guest.email || 'N/A'}</td>
                            <td>${guest.emergency_no || 'N/A'}</td>
                            <td>${guest.gender || 'N/A'}</td>
                            <td>${guest.is_verified == 1?'Verified':'Pending'}</td>
                            <td>${guest.status || 'N/A'}</td>
                            <td>${new Date(guest.created_at).toLocaleString()}</td>
                            <td>${guest.is_verified == 1?'':`<a href="/admission/form_verify/${guest.id}" class="btn btn-primary btn-sm">Edit & Verify</a>`}
                                
                            </td>
                        </tr>
                    `);
                    });
                    //datatables
                    InitializeDatatable();
                },
                error: function(xhr) {
                    console.error("Error fetching guests:", xhr);
                    $("#guestsList").html(
                        `<tr><td colspan="10" class="text-danger text-center">Error loading guests.</td></tr>`
                    );
                    showCustomMessageBox("Failed to load guests.", 'danger'); // Display error message
                }
            });
        }
    </script>
@endpush
