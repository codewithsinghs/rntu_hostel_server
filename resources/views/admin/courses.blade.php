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
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Faculties</p>
                            <h3>500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Departments</p>
                            <h3>400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Courses</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/3.png') }}" alt="">
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
                <div class="top-breadcrumbs">
                    <div class="breadcrumbs p-0"><a class="p-0">Courses List</a></div>
                    <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#courses">+ Add
                        Courses</button>
                </div>

                <div class="overflow-auto">

                    {{-- Alert for errors --}}
                    <div id="errorAlert" class="alert alert-danger d-none" role="alert"></div>
                    {{-- Alert for success messages --}}
                    <div id="successAlert" class="alert alert-success d-none" role="alert"></div>

                    <div id="alert-container"></div>

                    <table class="status-table" id="coursesList">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Faculty</th>
                                <th>Department</th>
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

    <!-- Create courses Popup-->
    <div class="modal fade" id="courses" tabindex="-1" aria-labelledby="coursesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Course</div>
                    </div>

                    <form id="coursesForm" novalidate>

                        <div class="middle">

                            <span class="input-set">
                                <label for="name">Course Name</label>
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
                                <label for="department_id">Select Department</label>
                                <select id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                </select>
                                <div class="invalid-feedback" id="department_id_error"></div>
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
                            <button type="submit" class="blue"> Add Course</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Course Popup-->
    <div class="modal fade" id="EditCourse" tabindex="-1" aria-labelledby="EditCourseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Course</div>
                    </div>

                    <div id="edit-course-alert"></div>

                    <form id="editCourseForm" novalidate>

                        <input type="hidden" id="edit_course_id">

                        <div class="middle">
                            <span class="input-set">
                                <label>Course Name</label>
                                <input type="text" id="edit_name">
                                <div class="invalid-feedback" id="edit_name_error"></div>
                            </span>

                            <span class="input-set">
                                <label>Select Faculty</label>
                                <select id="edit_faculty_id">
                                    <option value="">Select Faculty</option>
                                </select>
                                <div class="invalid-feedback" id="edit_faculty_id_error"></div>
                            </span>

                            <span class="input-set">
                                <label>Select Department</label>
                                <select id="edit_department_id">
                                    <option value="">Select Department</option>
                                </select>
                                <div class="invalid-feedback" id="edit_department_id_error"></div>
                            </span>

                            <span class="input-set">
                                <label>Status</label>
                                <select id="edit_status">
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
                            <button type="submit" class="blue"> Update Course</button>
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

    <!-- loader -->
    <div id="edit-loader" class="d-none text-center my-3">
        <div class="spinner-border" role="status" aria-hidden="true"></div>
        <div class="mt-2">Loading...</div>
    </div>

@endsection

@push('scripts')

    <script type="text/javascript">
        (function ($) {
            'use strict';

            // ---------- Helpers & Cache ----------
            let cachedFaculties = null;       // [{id, name}, ...]
            const cachedDepartments = {};     // { facultyId: [{id, name}, ...] }

            const API_HEADERS = () => ({
                'token': localStorage.getItem('token'),
                'auth-id': localStorage.getItem('auth-id'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            });

            function showAlert(type, message) {
                $('#alert-container').html(`
                                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                    ${message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                setTimeout(() => { $('#alert-container .alert').alert('close'); }, 4000);
            }

            function clearValidationErrors(formSelector = '') {
                const base = formSelector ? $(formSelector) : $(document);
                base.find('.is-invalid').removeClass('is-invalid');
                base.find('.invalid-feedback').text('');
            }

            function displayValidationErrors(errors, prefix = '') {
                clearValidationErrors();
                for (const field in errors) {
                    const fieldId = `#${prefix}${field}`;
                    const errorDiv = $(`${fieldId}_error`);
                    const input = $(fieldId);
                    if (input.length) input.addClass('is-invalid');
                    if (errorDiv.length) errorDiv.text(errors[field][0]);
                }
            }




            // ---------- API helpers (with caching) ----------
            function fetchCourses() {
                return $.ajax({
                    url: '/api/admin/courses',
                    type: 'GET',
                    headers: API_HEADERS()
                }).done(function (response) {
                    if (response.success && Array.isArray(response.data)) {
                        let rows = '';
                        response.data.forEach(function (course, index) {
                            rows += `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${course.name}</td>
                                                <td>${course.faculty ? course.faculty.name : 'N/A'}</td>
                                                <td>${course.department ? course.department.name : 'N/A'}</td>
                                                <td>${course.status == 1 ? "Active" : "Inactive"}</td>
                                                <td>
                                                    <button type="button" class="edit-btn edit-course-btn" data-id="${course.id}">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${course.id}">Delete</button>
                                                </td>
                                            </tr>
                                        `;
                        });
                        $('#coursesList tbody').html(rows);
                        InitializeDatatable();
                    } else {
                        $('#coursesList tbody').html('<tr><td colspan="6">No data found</td></tr>');
                    }
                }).fail(function () {
                    $('#coursesList tbody').html('<tr><td colspan="6">Error loading data</td></tr>');
                });
            }


            

            function loadFaculties(targetSelector, forceReload = false) {
                // Use cache if available
                if (cachedFaculties && !forceReload) {
                    populateFacultyOptions(targetSelector, cachedFaculties);
                    return $.Deferred().resolve({ success: true, data: cachedFaculties }).promise();
                }

                return $.ajax({
                    url: '/api/admin/faculties',
                    type: 'GET',
                    headers: API_HEADERS()
                }).done(function (response) {
                    if (response.success && Array.isArray(response.data)) {
                        cachedFaculties = response.data;
                        populateFacultyOptions(targetSelector, cachedFaculties);
                    } else {
                        showAlert('danger', response.message || 'Failed to load faculties.');
                    }
                }).fail(function () {
                    showAlert('danger', 'An error occurred while fetching faculties.');
                });
            }

            function populateFacultyOptions(targetSelector, faculties) {
                let options = '<option value="">-- Select Faculty --</option>';
                faculties.forEach(function (f) {
                    options += `<option value="${f.id}">${f.name}</option>`;
                });
                $(targetSelector).html(options);
            }

            function loadDepartments(targetSelector, facultyId, forceReload = false) {
                if (!facultyId) {
                    $(targetSelector).html('<option value="">-- Select Department --</option>');
                    return $.Deferred().resolve({ success: true, data: [] }).promise();
                }

                // cache check
                if (cachedDepartments[facultyId] && !forceReload) {
                    let options = '<option value="">-- Select Department --</option>';
                    cachedDepartments[facultyId].forEach(d => { options += `<option value="${d.id}">${d.name}</option>`; });
                    $(targetSelector).html(options);
                    return $.Deferred().resolve({ success: true, data: cachedDepartments[facultyId] }).promise();
                }

                return $.ajax({
                    url: '/api/admin/departments?faculty_id=' + facultyId,
                    type: 'GET',
                    headers: API_HEADERS()
                }).done(function (response) {
                    if (response.success && Array.isArray(response.data)) {
                        cachedDepartments[facultyId] = response.data;
                        let options = '<option value="">-- Select Department --</option>';
                        response.data.forEach(function (department) {
                            options += `<option value="${department.id}">${department.name}</option>`;
                        });
                        $(targetSelector).html(options);
                    } else {
                        $(targetSelector).html('<option value="">-- Select Department --</option>');
                        showAlert('danger', response.message || 'No departments found for this faculty.');
                    }
                }).fail(function () {
                    showAlert('danger', 'An error occurred while fetching departments.');
                });
            }

            // ---------- Document ready ----------
            $(document).ready(function () {
                // initial loads: fetch courses + faculties (cache)
                fetchCourses();
                loadFaculties('#faculty_id'); // cachedFaculties set here

                // create form faculty → departments
                $('#faculty_id').on('change', function () {
                    const facultyId = $(this).val();
                    if (!facultyId) {
                        $('#department_id').html('<option value="">-- Select Department --</option>');
                        return;
                    }
                    loadDepartments('#department_id', facultyId);
                });

                // create course submit
                $('#coursesForm').on('submit', function (event) {
                    event.preventDefault();
                    clearValidationErrors('#coursesForm');

                    const formData = {
                        name: $('#name').val(),
                        faculty_id: $('#faculty_id').val(),
                        department_id: $('#department_id').val(),
                        status: $('#status').val()
                    };

                    $.ajax({
                        url: '/api/admin/courses/create',
                        type: 'POST',
                        headers: API_HEADERS(),
                        data: JSON.stringify(formData),
                        contentType: 'application/json'
                    }).done(function (response) {
                        if (response.success) {
                            showAlert('success', response.message || 'Course created successfully!');
                            $('#courses').modal('hide');
                            $('#coursesForm')[0].reset();
                            fetchCourses();
                        } else {
                            showAlert('danger', response.message || 'Failed to create Course.');
                        }
                    }).fail(function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            displayValidationErrors(xhr.responseJSON.errors);
                        } else {
                            showAlert('danger', 'An error occurred while creating the course.');
                        }
                    });
                });

                // ----- Delete course -----
                let courseIdToDelete = null;
                $(document).on('click', '.delete-btn', function () {
                    courseIdToDelete = $(this).data('id');
                    $('#deleteConfirmationModal').modal('show');
                });
                $('#confirmDeleteBtn').on('click', function () {
                    if (!courseIdToDelete) return;
                    $.ajax({
                        url: `/api/admin/courses/${courseIdToDelete}`,
                        type: 'DELETE',
                        headers: API_HEADERS()
                    }).done(function (response) {
                        if (response.success) {
                            showAlert('success', response.message || 'Course deleted successfully!');
                            $('#deleteConfirmationModal').modal('hide');
                            fetchCourses();
                        } else {
                            showAlert('danger', response.message || 'Failed to delete course.');
                            $('#deleteConfirmationModal').modal('hide');
                        }
                    }).fail(function () {
                        showAlert('danger', 'An error occurred while deleting the course.');
                        $('#deleteConfirmationModal').modal('hide');
                    }).always(function () { courseIdToDelete = null; });
                });

                // ----- Edit course (fast UX) -----
                $(document).on('click', '.edit-course-btn', function () {
                    const id = $(this).data('id');

                    // show modal immediately and show loader
                    $('#EditCourse').modal('show');
                    $('#edit-loader').removeClass('d-none');
                    $('#edit-course-alert').html('');
                    $('#editCourseForm')[0].reset();
                    $('#edit_course_id').val('');

                    // populate faculty dropdown instantly from cache if available (fast UX)
                    if (cachedFaculties) {
                        populateFacultyOptions('#edit_faculty_id', cachedFaculties);
                    } else {
                        // if not cached, load faculties (will populate and cache)
                        loadFaculties('#edit_faculty_id');
                    }

                    // fetch only course details first (fast)
                    $.ajax({
                        url: '/api/admin/courses/' + id,
                        type: 'GET',
                        headers: API_HEADERS()
                    }).done(function (resp) {
                        const course = resp.data;
                        if (!course) {
                            $('#edit-course-alert').html(`<div class="alert alert-danger">Course not found</div>`);
                            $('#edit-loader').addClass('d-none');
                            return;
                        }

                        // set basic fields right away
                        $('#edit_course_id').val(course.id);
                        $('#edit_name').val(course.name || '');
                        $('#edit_status').val(course.status || '');

                        // determine facultyId from nested structure or response field
                        const facultyId = course.department && course.department.faculty ? course.department.faculty.id : (course.faculty ? course.faculty.id : '');

                        if (facultyId) {
                            // set faculty if options exist (if not yet present, set after faculties load)
                            $('#edit_faculty_id').val(facultyId);

                            // Load departments (cached if possible) and set selected department when ready
                            loadDepartments('#edit_department_id', facultyId).done(function () {
                                $('#edit_department_id').val(course.department_id || '');
                                $('#edit-loader').addClass('d-none'); // hide loader after departments loaded
                            }).fail(function () {
                                $('#edit-loader').addClass('d-none');
                                $('#edit-course-alert').html(`<div class="alert alert-danger">Failed to load departments</div>`);
                            });
                        } else {
                            // no faculty found — simply hide loader
                            $('#edit_loader').addClass('d-none');
                            $('#edit_loader').hide();
                        }

                        // hide loader if department load didn't run (fast)
                        if (!facultyId) $('#edit-loader').addClass('d-none');
                    }).fail(function () {
                        $('#edit-course-alert').html(`<div class="alert alert-danger">Failed to load course details</div>`);
                        $('#edit-loader').addClass('d-none');
                    });
                });

                // when faculty changes in edit modal, load departments (with cache)
                $('#edit_faculty_id').on('change', function () {
                    const facId = $(this).val();
                    if (!facId) {
                        $('#edit_department_id').html(`<option value="">Select Department</option>`);
                        return;
                    }
                    loadDepartments('#edit_department_id', facId);
                });

                // submit edit form
                $('#editCourseForm').on('submit', function (e) {
                    e.preventDefault();
                    clearValidationErrors('#editCourseForm');

                    const id = $('#edit_course_id').val();
                    if (!id) {
                        $('#edit-course-alert').html(`<div class="alert alert-danger">Invalid Course ID</div>`);
                        return;
                    }

                    const data = {
                        name: $('#edit_name').val(),
                        faculty_id: $('#edit_faculty_id').val(),
                        department_id: $('#edit_department_id').val(),
                        status: $('#edit_status').val()
                    };

                    $.ajax({
                        url: '/api/admin/courses/' + id,
                        type: 'PUT',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: API_HEADERS()
                    }).done(function (response) {
                        if (response.success) {
                            $('#EditCourse').modal('hide');
                            showAlert('success', response.message || 'Course updated successfully!');
                            fetchCourses();
                        } else {
                            $('#edit-course-alert').html(`<div class="alert alert-danger">${response.message}</div>`);
                        }
                    }).fail(function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            displayValidationErrors(xhr.responseJSON.errors, 'edit_');
                        } else {
                            $('#edit-course-alert').html(`<div class="alert alert-danger">Something went wrong</div>`);
                        }
                    });
                });

            }); // document ready
        })(jQuery);
    </script>



@endpush