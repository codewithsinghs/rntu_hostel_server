@extends('accountant.layout')

@section('content')
<div class="container mt-4">
    <div class="mt-5 mb-3">
        <h3>List of Residents</h3>
    </div>
    <div class="mb-4 cust_box">
        <div class="cust_heading">
            Residents List
        </div>
        <div id="responseMessage" class="mt-3"></div> {{-- Added message container here --}}

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Sr.No</th>
                    <th>Scholar No</th>
                    <th>Resident Name</th>
                    <!-- <th>Email</th> -->
                    <th>Contact No</th>                    
                    <th>Gender</th>
                    <th>Course</th>
                    <th>Pay Type</th>
                    <th>Building Name</th>
                    <th>Room No</th>
                    <th>Bed No</th>
                    <!-- <th>Verification Status</th> -->
                    <th>Status</th>
                    <!-- <th>Created At</th> -->
                    <!-- <th>Action</th> -->
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
        fetchResidents();

        // Function to show a custom message box
        function showCustomMessageBox(message, type = 'info') {
            const messageContainer = $('#responseMessage');
            messageContainer.html(`<div class="alert alert-${type}">${message}</div>`);
            setTimeout(() => messageContainer.empty(), 3000); // Clear after 3 seconds
        }
    });

    // Function to fetch residents from the API
    function fetchResidents() {
        $.ajax({
            url: "{{ url('/api/admin/residents') }}",
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
                const residents = response.data;
                const residentsList = $("#guestsList");
                residentsList.empty();

                if (!Array.isArray(residents) || residents.length === 0) {
                    residentsList.append(
                        `<tr><td colspan="10" class="text-center">No residents found.</td></tr>`);
                    return;
                }



                residents.forEach((resident, index) => {

                    let pay_type = '';
                    if(resident.guest.bihar_credit_card == 1) {
                        pay_type = 'Bihar Credit Card';
                    } 
                    else if(resident.guest.tnsd == 1) {
                        pay_type = 'TNSD';
                    } 
                    else {
                        pay_type = 'Regular';
                    }

                    residentsList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${resident.scholar_no || 'N/A'}</td>
                            <td>${resident.name || 'N/A'}</td>`
                            //  <td>${resident.email || 'N/A'}</td> 
                            +`
                            <td>${resident.number || 'N/A'}</td>
                            <td>${resident.gender || 'N/A'}</td>
                            <td>${resident.guest.course.name || 'N/A'}</td>
                            <td>${pay_type || 'N/A'}</td>
                            <td>${resident.bed.room.building.name || 'N/A'}</td>
                            <td>${resident.bed.room.room_number || 'N/A'}</td>
                            <td>${resident.bed.bed_number || 'N/A'}</td>
                            <td>${resident.status || 'N/A'}</td>
                        </tr>
                    `);
                    // <td>${guest.is_verified == 1?'Verified':'Pending'}</td>
                    // <td>${guest.status || 'N/A'}</td>
                    // <td>${new Date(guest.created_at).toLocaleString()}</td>
                });
                // Datatable
                InitializeDatatable();

            },
            error: function(xhr) {
                console.error("Error fetching residents:", xhr);
                $("#guestsList").html(
                    `<tr><td colspan="10" class="text-danger text-center">Error loading residents.</td></tr>`
                );
                showCustomMessageBox("Failed to load residents.", 'danger'); // Display error message
            }
        });
    }
</script>
@endpush