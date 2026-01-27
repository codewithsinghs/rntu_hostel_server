@extends('warden.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>Buildings</h2>

            <!-- <a href="{{ route('warden.create_building') }}" class="btn btn-primary p-3">
                <i class="fas fa-plus"></i> Create Building
            </a> -->

        </div>

        {{-- Alert for errors --}}
        <div id="errorAlert" class="alert alert-danger d-none" role="alert"></div>
        {{-- Alert for success messages --}}
        <div id="successAlert" class="alert alert-success d-none" role="alert"></div>

        <table class="table table-bordered" id="buildingList">
            <thead class="table-dark">
                <tr>
                    <th>S.No.</th>
                    <th>Building Name</th>
                    <th>Floors</th>
                    <th>Building Code</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this building? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
                url: '/api/admin/buildings', // your API endpoint
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id')
                },
                success: function (response) {
                    // console.log(response.data.length);
                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        let rows = '';
                        response.data.forEach(function (building, index) {
                            rows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${building.name}</td>
                                    <td>${building.floors}</td>
                                    <td>${building.building_code}</td>  
                                    <td>${building.gender ? building.gender.charAt(0).toUpperCase() + building.gender.slice(1).toLowerCase() : ''}</td>
                                    <td>${building.status}</td>
                                </tr>
                            `;
                        });
                        $('#buildingList tbody').html(rows);

                        // Datatable
                        InitializeDatatable();
                        
                    } else {
                        $('#buildingList tbody').html('<tr><td colspan="4">No data found</td></tr>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $('#buildingList tbody').html('<tr><td colspan="4">Error loading data</td></tr>');
                }
            });
        });


        function showAlert(type, message) {
            let alertBox = type === 'success' ? $('#successAlert') : $('#errorAlert');
            alertBox.text(message).removeClass('d-none');
            setTimeout(() => {
                alertBox.addClass('d-none');
            }, 4000);
        }
        $(document).on('click', '.delete-btn', function () {
            let buildingId = $(this).data('id'); // Get the ID from button
            console.log("Deleting building with ID:", buildingId);
            $('#deleteConfirmationModal').modal('show');
            $('#confirmDeleteBtn').off('click').on('click', function () {
                $.ajax({
                    url: `/api/admin/buildings/${buildingId}`,
                    type: 'DELETE',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            showAlert("success", response.message || "Building deleted successfully!");
                            $('#deleteConfirmationModal').modal('hide');
                            // Refresh the building list
                            $.ajax({
                                url: '/api/admin/buildings',
                                type: 'GET',
                                headers: {
                                    'token': localStorage.getItem('token'),
                                    'auth-id': localStorage.getItem('auth-id')
                                },
                                success: function (response) {
                                    if (response.success && Array.isArray(response.data)) {
                                        let rows = '';
                                        response.data.forEach(function (building, index) {
                                            rows += `
                                                <tr>        
                                                    <td>${index + 1}</td>
                                                    <td>${building.name}</td>
                                                    <td>${building.floors}</td>
                                                    <td>${building.building_code}</td>
                                                    <td>${toCamelCase(building.gender)}</td>
                                                    <td>${building.status}</td>
                                                    <td>                                
                                                    <a href="/warden/buildings/edit/${building.id}" class="btn btn-sm btn-primary edit-btn" data-id="${building.id}">Edit</a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="${building.id}">Delete</a>
                                                    </td>
                                                </tr>
                                            `;
                                        });
                                        $('#buildingList tbody').html(rows);
                                    } else {
                                        $('#buildingList tbody').html('<tr><td colspan="6">No data found</td></tr>');
                                    }
                                },
                                error: function (xhr) {
                                    console.error(xhr);
                                    $('#buildingList tbody').html('<tr><td colspan="6">Error loading data</td></tr>');
                                }
                            });
                        } else {
                            showAlert("danger", response.message || "Failed to delete building.");
                            $('#deleteConfirmationModal').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        showAlert("danger", "An error occurred while deleting the building.");
                        $('#deleteConfirmationModal').modal('hide');
                    }
                });
            });
        });

    </Script>
@endpush