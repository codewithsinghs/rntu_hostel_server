@extends('warden.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">
        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>List of Residents</h2>
        </div>

        <div class="cust_box p-4">

            <div id="responseMessage" class="mt-3"></div>

            <div class="table-responsive">
                <table id="residentTable" class="table table-bordered table-hover dt-responsive nowrap" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>S. No</th>
                            <th>Scholar No</th>
                            <th>Resident Name</th>                            
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Pay Type</th>
                            <th>Actions</th>
                            <th>Gender</th>
                            <th>Check In Date</th>
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

@endsection

    @push('scripts')
        <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
        @include('backend.components.datatable-lib')


        <script>
            $(document).ready(function () {
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

                    success: function (response) {
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
                            // console.log('Resident Data:', resident.guest); // Debugging line
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
                            let checkinupdatebutton = '';
                            if(resident.check_in_date === null) {
                                checkinupdatebutton = `<button class="btn btn-sm btn-danger edit-checkin-btn" data-id="${resident.id}" onclick="showCheckInValidatedDateSwal('${resident.id}')">Check In</button>`; 
                            }
                            else {
                                checkinupdatebutton = ``;
                            }   

                            residentList.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${resident.scholar_no ?? 'N/A'}</td>
                                    <td>${resident.name ?? 'N/A'}</td>
                                    <td>${resident.number ?? 'N/A'}</td>
                                    <td>${resident.email ?? 'N/A'}</td>
                                    <td>${pay_type ?? 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success edit-resident-btn" data-id="${resident.id}">Edit</button>
                                        ${checkinupdatebutton}
                                    </td>
                                    <td>${resident.gender ?? 'N/A'}</td>
                                    <td>${resident.check_in_date ? new Date(resident.check_in_date).toLocaleDateString() : 'N/A'}</td>
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

                        // ✅ Destroy existing DataTable instance before reinitializing
                        if ($.fn.DataTable.isDataTable('#residentTable')) {
                            $('#residentTable').DataTable().destroy();
                        }

                        // Datatable
                        InitializeDatatable();
                    },

                    error: function (xhr) {
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

                            function showCheckInValidatedDateSwal(residentId) {

                Swal.fire({
                    title: 'Select Check-In Date',
                    input: 'date',
                    inputPlaceholder: 'Choose a date',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please select a valid date.';
                        }
                        return null;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const selectedDate = result.value;
                        // AJAX CALL START
                        $.ajax({
                            url: '/api/admin/check-in-date-update',  // <-- Your API route here
                            method: 'PUT',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'token': localStorage.getItem('token'),
                                'auth-id': localStorage.getItem('auth-id')
                            },
                            data: JSON.stringify({
                                resident_id: residentId,
                                check_in_date: selectedDate,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Check-In Successful',
                                    text: response.message || 'Date saved successfully!'
                                });

                                // Reload the page after 2 seconds to reflect changes
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || 'Something went wrong!'
                                });
                            }
                        });
                        // AJAX END
                    }
                });
            }


    
    
    $(document).ready(function() {
        $(document).on('click', '.view-invoices-btn', function() {
            const residentId = $(this).data('id');
            $viewInvoicesModal.data('resident-id', residentId);
            //  console.log("asd",residentId);

            $pendingInvoicesBody.empty();
            $noPendingInvoicesMessage.hide();
            $invoiceErrorMessage.hide();
            // $pendingInvoicesBody.append('<tr><td colspan="5" class="text-center text-muted">Loading invoices payments...</td></tr>');
            $viewInvoicesModal.modal('show');
            $.ajax({
                url: `/api/accountant/resident/${residentId}/invoices`,
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // alert(JSON.stringify(response));
                    $pendingAccessoriesBody.empty();

                    if (response.success && response.data && Array.isArray(response.data) && response.data.length > 0) {
                        response.data.forEach((invoice, index) => {
                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${invoice.invoice_number ?? 'N/A'}</td>
                                    <td>${invoice.total_amount ?? 'N/A'}</td>
                                    <td>${invoice.paid_amount ?? 'N/A'}</td>
                                    <td>${invoice.remaining_amount ?? 'N/A'}</td>
                                    <td>${invoice.due_date ?? 'N/A'}</td>
                                    <td>${invoice.status ?? 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-transactions-btn"
                                                    data-invoice-id="${invoice.id}"
                                                    data-resident-id="${residentId}">View Transactions</button>
                                    </td>
                                </tr>
                                `;
                            $pendingInvoicesBody.append(row);
                        });
                    } else {
                        $noPendingInvoicesMessage.show();
                    }
                },
                error: function(xhr, status, error) {
                    $pendingInvoicesBody.empty();
                    $invoiceErrorMessage.text('Error fetching invoice payments: ' + (xhr.responseJSON?.message || error)).show();
                    console.error("Error fetching invoices:", status, error, xhr.responseJSON);
                }
            });

        });
 
    
    });

    </script>
    
    
    @endpush