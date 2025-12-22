@extends('admin.layout')

@section('content')
    <div class="container-fluid mt-5">
        <h3>List of Residents</h3>
        <hr>

        <div id="responseMessage" class="mt-3"></div> {{-- Added message container here --}}


        <div class="table-responsive">
            {{-- <table class="table table-bordered table-hover table-striped align-middle"> --}}
            <table id="residentTable" class="table table-bordered table-hover table-striped dt-responsive nowrap"
                style="width:100%">

                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">S. No</th>
                        <th scope="col">Scholar No</th>
                        <th scope="col">Resident Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Bed Number</th>
                        <th scope="col">Room Number</th>
                        <th scope="col">Building Name</th>
                        <th scope="col">Room Preference</th>
                        <th scope="col">Faculty</th>
                        <th scope="col">Department</th>
                        <th scope="col">Course</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody id="residentList">
                    <!-- Dynamic rows go here -->
                </tbody>
            </table>
        </div>


    </div>





    <script>
        $(document).ready(function() {
            // Fetch residents when the document is ready
            fetchResidents();

            // Function to show a custom message box
            function showCustomMessageBox(message, type = 'info') {
                const messageContainer = $('#responseMessage');
                messageContainer.html(`<div class="alert alert-${type}">${message}</div>`);
                setTimeout(() => messageContainer.empty(), 3000); // Clear after 3 seconds
            }
        });

        // Function to fetch residents
        function fetchResidents() {
            $.ajax({
                url: "{{ url('/api/admin/residents') }}",
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                success: function(response) {
                    const residents = response.data;
                    const residentList = $("#residentList");
                    residentList.empty();

                    if (!Array.isArray(residents) || residents.length === 0) {
                        residentList.append(`<tr>
                            <td colspan="10" class="text-center">No residents found.</td>
                        </tr>`);
                        return;
                    }
                    // console.log("Fetched residents: ", residents); // Debug log to check fetched residents
                    residents.forEach((resident, index) => {
                        const guest = resident.guest || {};
                        const bed = resident.bed || {};
                        residentList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${resident.scholar_no ?? 'N/A'}</td>
                            <td>${resident.name ?? 'N/A'}</td>
                            <td>${resident.email ?? 'N/A'}</td>
                            <td>${resident.gender ?? 'N/A'}</td> {{-- Changed from guest.gender to resident.gender based on API --}}
                            <td>${bed?.bed_number ?? 'Not Assigned'}</td>
                            <td>${bed?.room?.room_number ?? 'N/A'}</td>
                            <td>${bed?.room?.building.name ?? 'N/A'}</td>
                            <td>${guest.room_preference ?? 'N/A'}</td>
                            <td>${guest.faculty ? guest.faculty.name: 'N/A'}</td>
                            <td>${guest.department ?guest.department.name: 'N/A'}</td>
                            <td>${guest.course ?guest.course.name : 'N/A'}</td>
                            <td><span class="badge ${getStatusClass(resident.status)}">${resident.status ?? 'N/A'}</span></td>

                            <td>${new Date(resident.created_at).toLocaleString()}</td>
                        </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    console.error("Error fetching residents:", xhr);
                    $("#residentList").html(`<tr>
                        <td colspan="10" class="text-danger text-center">Error loading residents.</td>
                    </tr>`);
                    showCustomMessageBox("Failed to load residents.", 'danger'); // Display error message
                }
            });

            function getStatusClass(status) {
                switch (status) {
                    case 'active':
                        return 'bg-success text-white';
                    case 'pending':
                        return 'bg-warning text-dark';
                    case 'approved':
                        return 'bg-success';
                    case 'verified':
                        return 'bg-primary';
                    case 'rejected':
                        return 'bg-danger';
                    default:
                        return 'bg-secondary';
                }
            }

        }
    </script>
@endsection
