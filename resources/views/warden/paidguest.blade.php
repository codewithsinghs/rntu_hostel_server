@extends('warden.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">
        <div class="mt-5 mb-3">
        <h2 class="mb-4">Paid Guests</h2>
        </div>

        <div class="cust_box p-4">
            <table class="table table-bordered table-striped" id="paidGuestsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Guest ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be inserted via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- ✅ Include DataTables CSS + JS -->
    <!-- @include('backend.components.datatable-lib') -->

    <script>
        $(document).ready(function () {
            fetchPaidGuests();
        });

        // ✅ Fetch Paid Guests and Initialize DataTable
        function fetchPaidGuests() {
            $.ajax({
                url: '/api/admin/paid-guests',
                type: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                success: function (data) {
                    const tableBody = $('#paidGuestsTable tbody');
                    tableBody.empty();

                    const guests = Array.isArray(data) ? data : (data.data || data.guests || []);

                    if (guests.length === 0) {
                        tableBody.html('<tr><td colspan="4" class="text-center">No guests have paid yet.</td></tr>');
                        return;
                    }

                    guests.forEach((guest) => {
                        const id = guest.id || 'N/A';
                        const name = guest.name || 'N/A';
                        const status = 'Paid';
                        const createdDate = guest.created_at ? new Date(guest.created_at).toLocaleString() : 'N/A';

                        tableBody.append(`
                            <tr>
                                <td>${id}</td>
                                <td>${name}</td>
                                <td><span class="badge bg-success">${status}</span></td>
                                <td>${createdDate}</td>
                            </tr>
                        `);
                    });

                    // ✅ Destroy existing DataTable before re-initializing
                    if ($.fn.DataTable.isDataTable('#paidGuestsTable')) {
                        $('#paidGuestsTable').DataTable().destroy();
                    }

                    // Datatable
                    InitializeDatatable();
                    
                },
                error: function (err) {
                    console.error('Error fetching paid guests:', err);
                    $('#paidGuestsTable tbody').html('<tr><td colspan="4" class="text-center text-danger">Error loading data.</td></tr>');
                }
            });
        }
    </script>
@endpush