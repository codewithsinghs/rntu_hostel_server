@extends('admin.layout')

@section('content')
    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview </a></div>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Residents Details</a></div>

                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Residents</p>
                            <h3>53,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/student 1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Checked-In Residents</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Approvals</p>
                            <h3>3,720</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Vacant Beds</p>
                            <h3>1,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>List of Residents</a></div>

                <div class="overflow-auto">
                    <div id="responseMessage" class="mt-3"></div>
                    <table class="status-table" id="residentTable">
                        <thead class="table-dark">
                            <tr>
                                <th>S.No</th>
                                <th>Scholar No</th>
                                <th>Resident Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Mobile</th>
                                <th>Date of Joining</th>
                                <th>Bed Number</th>
                                <th>Room Number</th>
                                <th>Building Name</th>
                                <th>Room Preference</th>
                                <th>Faculty</th>
                                <th>Department</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="residentList"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>


    <!-- Edit Resident Popup-->
    <div class="modal fade" id="editResidentModal" tabindex="-1" aria-labelledby="editResidentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Resident</div>
                    </div>

                    <div id="editResponse"></div>

                    <form id="editResidentForm" novalidate>

                        <input type="hidden" id="edit_resident_id">

                        <div class="middle">
                            <span class="input-set">
                                <label>Scholar No</label>
                                <input type="text" id="edit_scholar_no">
                            </span>

                            <span class="input-set">
                                <label>Name</label>
                                <input type="text" id="edit_name">
                            </span>

                            <span class="input-set">
                                <label>Email</label>
                                <input type="email" id="edit_email">
                            </span>

                            <span class="input-set">
                                <label>Mobile Number</label>
                                <input type="text" id="edit_mobile">
                            </span>

                            <span class="input-set">
                                <label>Date of Joining</label>
                                <input type="date" id="edit_date_of_joining">
                            </span>

                            <span class="input-set">
                                <label>Gender</label>
                                <select id="edit_gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </span>
                        </div>


                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="submit" class="blue"> Update Resident</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
                                            <td>${resident.mobile ?? 'N/A'}</td>
                                            <td>${resident.date_of_joining
                                ? new Date(resident.date_of_joining).toLocaleDateString()
                                : 'N/A'}</td>
                                            <td>${bed.bed_number ?? 'N/A'}</td>
                                            <td>${bed.room?.room_number ?? 'N/A'}</td>
                                            <td>${bed.room?.building?.name ?? 'N/A'}</td>
                                            <td>${guest.room_preference ?? 'N/A'}</td>
                                            <td>${guest.faculty?.name ?? 'N/A'}</td>
                                            <td>${guest.department?.name ?? 'N/A'}</td>
                                            <td>${guest.course?.name ?? 'N/A'}</td>
                                            <td>${resident.status ?? 'N/A'}</td>
                                            <td>${new Date(resident.created_at).toLocaleString()}</td>
                                            <td>
                                            <a href="{{ route('admin.create_hod') }}" class="btn btn-sm btn-outline-dark">View Profile</a>
                                                <button 
                                                    class="btn btn-sm btn-outline-dark editResidentBtn"
                                                    data-id="${resident.id}"
                                                    data-scholar="${resident.scholar_no ?? ''}"
                                                    data-name="${resident.name ?? ''}"
                                                    data-email="${resident.email ?? ''}"
                                                    data-gender="${resident.gender ?? ''}"
                                                    data-mobile="${resident.mobile ?? ''}"
                                                    data-doj="${resident.date_of_joining ?? ''}">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                            `);
                    });

                    // ✅ Destroy existing DataTable instance before reinitializing
                    if ($.fn.DataTable.isDataTable('#residentTable')) {
                        $('#residentTable').DataTable().destroy();
                    }

                    // Datatable
                    InitializeDatatable();
                },

                error: function(xhr) {
                    console.error("Error fetching residents:", xhr);
                    $("#residentList").html(`<tr>
                                                                        <td colspan="10" class="text-danger text-center">Error loading residents.</td>
                                                                    </tr>`);
                    showCustomMessageBox("Failed to load residents.", 'danger'); // Display error message
                }
            });


            /* ================= OPEN EDIT MODAL ================= */
            $(document).on('click', '.editResidentBtn', function() {
                $('#edit_resident_id').val($(this).data('id'));
                $('#edit_scholar_no').val($(this).data('scholar'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_gender').val($(this).data('gender'));
                $('#edit_mobile').val($(this).data('mobile'));
                $('#edit_date_of_joining').val($(this).data('doj'));

                $('#editResidentModal').modal('show');
            });


            /* ================= UPDATE RESIDENT ================= */
            $('#editResidentForm').submit(function(e) {
                e.preventDefault();

                let id = $('#edit_resident_id').val();

                $.ajax({
                    url: `/api/admin/residents/${id}`,
                    type: 'PUT',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    data: {
                        scholar_no: $('#edit_scholar_no').val(),
                        name: $('#edit_name').val(),
                        email: $('#edit_email').val(),
                        gender: $('#edit_gender').val(),
                        mobile: $('#edit_mobile').val(),
                        date_of_joining: $('#edit_date_of_joining').val(),
                    },
                    success: function() {
                        $('#editResponse').html(
                            `<div class="alert alert-success">Updated Successfully</div>`);

                        setTimeout(() => {
                            $('#editResidentModal').modal('hide');
                            fetchResidents();
                        }, 1000);
                    },
                    error: function() {
                        $('#editResponse').html(`<div class="alert alert-danger">Update Failed</div>`);
                    }
                });
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
@endpush
