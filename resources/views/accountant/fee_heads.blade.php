@extends('accountant.layout')

@section('content')
<div class="container mt-4">
    <div class="mt-5 mb-3">
        <h2>Fee Heads</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('accountant.create_fee_head') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Fee Heads
        </a>
    </div>

    {{-- Alert for errors --}}
    <div id="errorAlert" class="alert alert-danger d-none" role="alert"></div>
    {{-- Alert for success messages --}}
    <div id="successAlert" class="alert alert-success d-none" role="alert"></div>

    <div class="mb-4 cust_box">
        <table class="table table-bordered" id="feeHeadList">
            <thead class="table-dark">
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <td>University Name</td>
                    <td>Is Mandatory</td>
                    <td>Is One Time</td>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
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
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: '/api/accountant/fee-heads', // your API endpoint
            type: 'GET',
            headers: {
                'token': localStorage.getItem('token'),
                'Auth-ID': localStorage.getItem('auth-id')
            },
            success: function(response) {
                if (response.success && Array.isArray(response.data)) {
                    let rows = '';
                    response.data.forEach(function(feeHead, index) {
                        rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${feeHead.name}</td>
                            <td>${feeHead.university.name}</td>
                            <td>${feeHead.is_mandatory==1?"Yes":"-"}</td>
                            <td>${feeHead.is_one_time==1?"Yes":"-"}</td>
                            <td>${feeHead.status==1?"Active":"Inactive"}</td>
                            <td>
                            <a href="/accountant/fee-heads/edit/${feeHead.id}" class="btn btn-sm btn-primary edit-btn" data-id="${feeHead.id}">Edit</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="${feeHead.id}">Delete</a>
                            </td>
                        </tr>
                    `;
                    });
                    $('#feeHeadList tbody').html(rows);
                    //datatables
                    InitializeDatatable();

                } else {
                    $('#feeHeadList tbody').html('<tr><td colspan="4">No data found</td></tr>');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                $('#feeHeadList tbody').html('<tr><td colspan="4">Error loading data</td></tr>');
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
    $(document).on('click', '.delete-btn', function() {
        let feeHeadId = $(this).data('id'); // Get the ID from button
        $('#deleteConfirmationModal').modal('show');
        $('#confirmDeleteBtn').off('click').on('click', function() {
            $.ajax({
                url: `/api/accountant/fee-heads/${feeHeadId}`,
                type: 'DELETE',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert("success", response.message ||
                            "Fee Head deleted successfully!");
                        $('#deleteConfirmationModal').modal('hide');
                        window.location.reload(); // Reload the page to reflect changes

                    } else {
                        showAlert("danger", response.message ||
                            "Failed to delete fee head.");
                        $('#deleteConfirmationModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    showAlert("danger", "An error occurred while deleting the fee head.");
                    $('#deleteConfirmationModal').modal('hide');
                }
            });
        });
    });
</Script>
@endpush