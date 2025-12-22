@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview</a></div>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a href="">Academic Details</a></div>

                <div class="card-ds-bottom">
                    @php
// Example stats (replace with dynamic data if available)
$stats = [
    ['label' => 'Total Faculties', 'value' => 500, 'img' => 'backend/img/Room Management/1.png'],
    ['label' => 'Total Departments', 'value' => 400, 'img' => 'backend/img/Room Management/2.png'],
    ['label' => 'Total Courses', 'value' => 50, 'img' => 'backend/img/Room Management/3.png'],
];
                    @endphp

                    @foreach($stats as $s)
                        <div class="card-d">
                            <div class="card-d-content">
                                <p>{{ $s['label'] }}</p>
                                <h3>{{ $s['value'] }}</h3>
                            </div>
                            <div class="card-d-image">
                                <img src="{{ asset($s['img']) }}" alt="">
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </section>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="top-breadcrumbs">
                    <div class="breadcrumbs p-0"><a class="p-0">Departments List</a></div>
                    <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#department">+ Add
                        Department</button>
                </div>

                <div class="overflow-auto">

                    {{-- Alerts container (single unified area) --}}
                    <div id="alerts"></div>

                    <!-- Loader -->
                    <div id="globalLoader" class="d-none">
                        <div class="loader-overlay">
                            <div class="loader"></div>
                        </div>
                    </div>

                    <table class="status-table" id="departmentsList">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Full Name</th>
                                <th>Faculty</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </section>

    <!-- Create department Popup--> 
    <div class="modal fade" id="department" tabindex="-1" aria-labelledby="departmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Departments</div>
                    </div>

                    <form id="departmentsForm" novalidate>

                        <div class="middle">

                            <span class="input-set">
                                <label for="name">Department Name</label>
                                <input type="text" id="name" name="name" required>
                                <div class="invalid-feedback" id="name_error"></div>
                            </span>

                            <span class="input-set">
                                <label for="faculty_id">Select Faculty</label>
                                <select id="faculty_id" name="faculty_id" required>
                                    <option value="">Select Faculty</option>
                                </select>
                                <div class="invalid-feedback" id="faculty_id_error"></div>
                            </span>

                            <span class="input-set">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="submit" class="blue"> Add Department</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Department Popup-->
    <div class="modal fade" id="EditDepartment" tabindex="-1" aria-labelledby="EditDepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Department</div>
                    </div>

                    <div id="edit-dept-alert-container"></div>

                    <form id="editDepartmentForm" novalidate>

                        <input type="hidden" id="edit_dept_id" name="id" />

                        <div class="middle">
                            <span class="input-set">
                                <label for="edit_name">Department Name</label>
                                <input type="text" id="edit_name" name="name" required>
                                <div class="invalid-feedback" id="edit_name_error"></div>
                            </span>

                            <span class="input-set">
                                <label for="edit_faculty_id">Select Faculty</label>
                                <select id="edit_faculty_id" name="faculty_id" required>
                                    <option value="">Select Faculty</option>
                                </select>
                                <div class="invalid-feedback" id="edit_faculty_id_error"></div>
                            </span>

                            <span class="input-set">
                                <label for="edit_status">Status</label>
                                <select id="edit_status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="edit_status_error"></div>
                            </span>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="submit" class="blue"> Update Department</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Delete Popup -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-remove">Confirm Deletion</div>
                    </div>

                    <div class="middle-content">
                        <p>Deleting this record will permanently remove it from your system. Proceed with caution.</p>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" id="confirmDeleteBtn"> Delete </button>
                        <button type="button" class="blue" data-bs-dismiss="modal" aria-label="Close"> Go Back </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <style>
        /* Simple loader styles (move to css file if needed) */
        #globalLoader .loader-overlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .25);
            z-index: 2000
        }

        .d-none {
            display: none
        }

        .loader {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 6px solid rgba(255, 255, 255, .2);
            border-top-color: #fff;
            animation: spin 1s linear infinite
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }
    </style>

    <script>
        (function ($) {
            'use strict';

            // --- Helpers ---
            const apiHeaders = function (extra) {
                return Object.assign({
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, extra || {});
            };

            const showLoader = () => $('#globalLoader').removeClass('d-none');
            const hideLoader = () => $('#globalLoader').addClass('d-none');

            const showAlert = (type, message, target = '#alerts', autoHide = true) => {
                const html = `
                                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                    ${message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
                $(target).html(html);
                if (autoHide) setTimeout(() => $(target).children().alert && $(target).children().alert('close'), 4000);
            };

            const apiRequest = (opts) => {
                // opts: {method, url, data, success, error, always, headers}
                showLoader();
                return $.ajax({
                    url: opts.url,
                    type: opts.method || 'GET',
                    data: opts.data ? (opts.contentType === 'application/json' ? JSON.stringify(opts.data) : opts.data) : undefined,
                    contentType: opts.contentType || undefined,
                    headers: apiHeaders(opts.headers || {}),
                }).done(function (res) {
                    if (typeof opts.success === 'function') opts.success(res);
                }).fail(function (xhr) {
                    if (typeof opts.error === 'function') opts.error(xhr);
                }).always(function () {
                    hideLoader();
                    if (typeof opts.always === 'function') opts.always();
                });
            };

            // --- Render / utilities ---
            const renderDepartments = (departments) => {
                if (!Array.isArray(departments) || !departments.length) {
                    $('#departmentsList tbody').html('<tr><td colspan="5">No data found</td></tr>');
                    return;
                }

                const rows = departments.map((d, i) => {
                    const statusText = d.status == 1 ? 'Active' : 'Inactive';
                    const faculty = d.faculty ? d.faculty.name : 'N/A';
                    return `
                                    <tr data-id="${d.id}">
                                        <td>${i + 1}</td>
                                        <td class="dept-name">${d.name}</td>
                                        <td class="dept-faculty">${faculty}</td>
                                        <td class="dept-status">${statusText}</td>
                                        <td>
                                            <button type="button" class="edit-btn edit-dept-btn" data-id="${d.id}">Edit</button>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="${d.id}">Delete</a>
                                        </td>
                                    </tr>
                                `;
                }).join('');

                $('#departmentsList tbody').html(rows);

                // Initialize or re-draw datatable safely
                InitializeDatatable();

                // Initialize datatable only once if plugin exists
                // if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#departmentsList')) {
                //     $('#departmentsList').DataTable && $('#departmentsList').DataTable();
                // }
            };

            const clearValidationErrors = (prefix = '') => {
                $(`[id$="_error"]`).text('');
                $(`.is-invalid`).removeClass('is-invalid');
            };

            const applyValidationErrors = (errors = {}, prefix = '') => {
                clearValidationErrors(prefix);
                for (const field in errors) {
                    const selector = `#${prefix}${field}`;
                    const errorDiv = `#${prefix}${field}_error`;
                    $(selector).addClass('is-invalid');
                    $(errorDiv).text(errors[field][0]);
                }
            };

            // --- Page flows ---
            $(document).ready(function () {
                // Load departments
                const loadDepartments = () => apiRequest({
                    url: '/api/admin/departments',
                    method: 'GET',
                    success: function (res) {
                        if (res && res.success && Array.isArray(res.data)) {
                            renderDepartments(res.data);
                        } else {
                            renderDepartments([]);
                            showAlert('danger', res.message || 'Failed to load departments');
                        }
                    },
                    error: function () {
                        renderDepartments([]);
                        showAlert('danger', 'An error occurred while loading departments.');
                    }
                });

                // Load faculties into a select
                const loadFacultiesInto = (selectSelector) => apiRequest({
                    url: '/api/admin/faculties',
                    method: 'GET',
                    success: function (res) {
                        let options = '<option value="">Select Faculty</option>';
                        if (res && res.success && Array.isArray(res.data)) {
                            res.data.forEach(f => options += `<option value="${f.id}">${f.name}</option>`);
                        } else {
                            options = '<option value="">Failed to load faculties</option>';
                        }
                        $(selectSelector).html(options);
                    },
                    error: function () {
                        $(selectSelector).html('<option value="">Failed to load faculties</option>');
                    }
                });

                // Initial data load
                loadDepartments();
                loadFacultiesInto('#faculty_id');

                // Create department
                $('#departmentsForm').on('submit', function (e) {
                    e.preventDefault();
                    clearValidationErrors();

                    const payload = {
                        name: $('#name').val(),
                        faculty_id: $('#faculty_id').val(),
                        status: $('#status').val(),
                    };

                    apiRequest({
                        url: '/api/admin/departments/create',
                        method: 'POST',
                        data: payload,
                        contentType: 'application/json',
                        success: function (res) {
                            if (res && res.success) {
                                showAlert('success', res.message || 'Department created successfully!');
                                $('#department').modal('hide');
                                loadDepartments();
                                // reset form
                                $('#departmentsForm')[0].reset();
                            } else {
                                showAlert('danger', res.message || 'Failed to create Department.');
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                applyValidationErrors(xhr.responseJSON.errors);
                            } else {
                                showAlert('danger', 'An error occurred while creating the department.');
                            }
                        }
                    });
                });

                // Delete (event delegation)
                let deletingId = null;
                $(document).on('click', '.delete-btn', function () {
                    deletingId = $(this).data('id');
                    $('#deleteConfirmationModal').modal('show');
                });

                $('#confirmDeleteBtn').on('click', function () {
                    if (!deletingId) return;

                    apiRequest({
                        url: `/api/admin/departments/${deletingId}`,
                        method: 'DELETE',
                        success: function (res) {
                            if (res && res.success) {
                                showAlert('success', res.message || 'Department deleted successfully!');
                                $('#deleteConfirmationModal').modal('hide');
                                loadDepartments();
                            } else {
                                showAlert('danger', res.message || 'Failed to delete Department.');
                            }
                        },
                        error: function () {
                            showAlert('danger', 'An error occurred while deleting.');
                        }
                    });
                });

                // Edit - open modal
                $(document).on('click', '.edit-dept-btn', function () {
                    const id = $(this).data('id');
                    if (!id) return;

                    $('#edit-dept-alert-container').html('');
                    clearValidationErrors();
                    $('#edit_dept_id').val('');
                    $('#edit_name').val('');
                    $('#edit_status').val('');
                    $('#edit_faculty_id').html('<option>Loading...</option>');

                    const faculties = $.ajax({ url: '/api/admin/faculties', type: 'GET', headers: apiHeaders() });
                    const dept = $.ajax({ url: `/api/admin/departments/${id}`, type: 'GET', headers: apiHeaders() });

                    $.when(faculties, dept).done(function (facResp, deptResp) {
                        const facData = facResp[0];
                        const deptData = deptResp[0];

                        // populate faculties
                        if (facData && facData.success && Array.isArray(facData.data)) {
                            let options = '<option value="">Select Faculty</option>';
                            facData.data.forEach(f => options += `<option value="${f.id}">${f.name}</option>`);
                            $('#edit_faculty_id').html(options);
                        } else {
                            $('#edit_faculty_id').html('<option value="">Failed to load faculties</option>');
                        }

                        // populate department
                        if (deptData && deptData.success && deptData.data) {
                            $('#edit_dept_id').val(deptData.data.id);
                            $('#edit_name').val(deptData.data.name);
                            $('#edit_status').val(deptData.data.status);
                            if (deptData.data.faculty_id) $('#edit_faculty_id').val(deptData.data.faculty_id);
                            $('#EditDepartment').modal('show');
                        } else {
                            $('#edit-dept-alert-container').html('<div class="alert alert-danger">Failed to load department</div>');
                        }
                    }).fail(function () {
                        $('#edit-dept-alert-container').html('<div class="alert alert-danger">An error occurred while loading data.</div>');
                    });
                });

                // Submit edit
                $('#editDepartmentForm').on('submit', function (e) {
                    e.preventDefault();
                    clearValidationErrors();

                    const id = $('#edit_dept_id').val();
                    const payload = {
                        name: $('#edit_name').val(),
                        faculty_id: $('#edit_faculty_id').val(),
                        status: $('#edit_status').val(),
                    };

                    apiRequest({
                        url: `/api/admin/departments/${id}`,
                        method: 'PUT',
                        data: payload,
                        contentType: 'application/json',
                        success: function (res) {
                            if (res && res.success) {
                                $('#EditDepartment').modal('hide');
                                showAlert('success', res.message || 'Department updated successfully!');
                                // update row if exists
                                const $row = $(`tr[data-id="${id}"]`);
                                if ($row.length) {
                                    $row.find('.dept-name').text(payload.name);
                                    $row.find('.dept-status').text(payload.status == 1 ? 'Active' : 'Inactive');
                                    const facultyText = $('#edit_faculty_id option:selected').text();
                                    $row.find('.dept-faculty').text((res.data && res.data.faculty && res.data.faculty.name) || facultyText);
                                } else {
                                    loadDepartments();
                                }

                            } else {
                                $('#edit-dept-alert-container').html(`<div class="alert alert-danger">${res.message || 'Failed to update department.'}</div>`);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                applyValidationErrors(xhr.responseJSON.errors, 'edit_');
                            } else {
                                $('#edit-dept-alert-container').html('<div class="alert alert-danger">An error occurred while updating department.</div>');
                            }
                        }
                    });
                });

            });

        })(jQuery);
    </script>

@endpush