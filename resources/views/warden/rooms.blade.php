@extends('warden.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>Rooms Management</h2>

            <!-- <a href="{{ route('admin.create_rooms') }}" class="btn btn-primary p-3">
                <i class="fas fa-plus"></i> Create Room
            </a> -->

        </div>

        <div
            style=" padding: 10px; margin: 10px auto; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.3rem;">
            Room List</div>

        <table class="table table-bordered" id="roomList">
            <thead class="table-dark">
                <tr>
                    <th>Serial No.</th>
                    <th>Room Number</th>
                    <th>Building Name</th>
                    <th>Floor No</th>
                    <th>Status</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center">Loading rooms...</td>
                </tr>
            </tbody>
        </table>

        <!-- {{-- Delete Confirmation Modal --}}
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this room? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

@endsection

@push('scripts')
    <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
    @include('backend.components.datatable-lib')

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                url: '/api/admin/rooms', // your API endpoint
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success && Array.isArray(response.data)) {
                        let rows = '';
                        response.data.forEach(function (room, index) {
                            rows += `
                                <tr data-id="${room.id}">
                                    <td>${index + 1}</td>
                                    <td class="room_number">${room.room_number}</td>
                                    <td class="building_name">${room.building_name || 'Unknown'}</td>
                                    <td class="building_name">${room.floor_no || 'Unknown'}</td>
                                    <td class="status">
                                        <span class="badge ${room.status === 'available' ? 'bg-success' : 'bg-danger'}">
                                            ${room.status.charAt(0).toUpperCase() + room.status.slice(1)}
                                        </span>
                                    </td>
                                </tr>
                            `;

                        });
                        $('#roomList tbody').html(rows);

                        // Datatable
                        InitializeDatatable();

                    } else {
                        $('#roomList tbody').html('<tr><td colspan="4">No data found</td></tr>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $('#roomList tbody').html('<tr><td colspan="4">Error loading data</td></tr>');
                }
            });



        });

   
    </script>
@endpush