@extends('admin.layout')

@section('content')
    <div class="container-fluid mt-5">
        <h3>List of Residents</h3>
        <hr>

        <div id="responseMessage" class="mt-3"></div> {{-- Added message container here --}}


        <div class="table-responsive">
            {{-- <table class="table table-bordered table-hover table-striped align-middle"> --}}
            <table id="residentTable" class="table table-bordered table-hover dt-responsive nowrap" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>S. No</th>
                        <th>Scholar No</th>
                        <th>Resident Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Bed Number</th>
                        <th>Room Number</th>
                        <th>Building Name</th>
                        <th>Room Preference</th>
                        <th>Faculty</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody id="residentList"></tbody>
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
                // success: function(response) {
                //     const residents = response.data;
                //     const residentList = $("#residentList");
                //     residentList.empty();

                //     if (!Array.isArray(residents) || residents.length === 0) {
                //         residentList.append(`<tr>
            //             <td colspan="10" class="text-center">No residents found.</td>
            //         </tr>`);
                //         return;
                //     }
                //     // console.log("Fetched residents: ", residents); // Debug log to check fetched residents
                //     residents.forEach((resident, index) => {
                //         const guest = resident.guest || {};
                //         const bed = resident.bed || {};
                //         residentList.append(`
            //         <tr>
            //             <td>${index + 1}</td>
            //             <td>${resident.scholar_no ?? 'N/A'}</td>
            //             <td>${resident.name ?? 'N/A'}</td>
            //             <td>${resident.email ?? 'N/A'}</td>
            //             <td>${resident.gender ?? 'N/A'}</td> {{-- Changed from guest.gender to resident.gender based on API --}}
            //             <td>${bed?.bed_number ?? 'Not Assigned'}</td>
            //             <td>${bed?.room?.room_number ?? 'N/A'}</td>
            //             <td>${bed?.room?.building.name ?? 'N/A'}</td>
            //             <td>${guest.room_preference ?? 'N/A'}</td>
            //             <td>${guest.faculty ? guest.faculty.name: 'N/A'}</td>
            //             <td>${guest.department ?guest.department.name: 'N/A'}</td>
            //             <td>${guest.course ?guest.course.name : 'N/A'}</td>
            //             <td><span class="badge ${getStatusClass(resident.status)}">${resident.status ?? 'N/A'}</span></td>

            //             <td>${new Date(resident.created_at).toLocaleString()}</td>
            //         </tr>
            //         `);
                //     });
                // },
                success: function(response) {
                    const residents = response.data;
                    const residentList = $("#residentList");
                    residentList.empty();

                    if (!Array.isArray(residents) || residents.length === 0) {
                        residentList.append(`<tr>
                                    <td colspan="14" class="text-center">No residents found.</td>
                                    </tr>`);
                        return;
                    }

                    residents.forEach((resident, index) => {
                        const guest = resident.guest || {};
                        const bed = resident.bed || {};
                        residentList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${resident.scholar_no ?? 'N/A'}</td>
                            <td>${resident.name ?? 'N/A'}</td>
                            <td>${resident.email ?? 'N/A'}</td>
                            <td>${resident.gender ?? 'N/A'}</td>
                            <td>${bed?.bed_number ?? 'Not Assigned'}</td>
                            <td>${bed?.room?.room_number ?? 'N/A'}</td>
                            <td>${bed?.room?.building.name ?? 'N/A'}</td>
                            <td>${guest.room_preference ?? 'N/A'}</td>
                            <td>${guest.faculty ? guest.faculty.name : 'N/A'}</td>
                            <td>${guest.department ? guest.department.name : 'N/A'}</td>
                            <td>${guest.course ? guest.course.name : 'N/A'}</td>
                            <td><span class="badge ${getStatusClass(resident.status)}">${resident.status ?? 'N/A'}</span></td>
                            <td>${new Date(resident.created_at).toLocaleString()}</td>
                        </tr>
                        `);
                    });

                    // Initialize DataTable after data is populated
                    $('#residentTable').DataTable({
                        responsive: true,
                        destroy: true, // Important to reinitialize if table already exists
                        pageLength: 10
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
