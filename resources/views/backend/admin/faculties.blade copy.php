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
                            <h3 id="stat-faculties">500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Departments</p>
                            <h3 id="stat-departments">400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Courses</p>
                            <h3 id="stat-courses">50</h3>
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
                <div class="top-breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Faculties List</a></div>
                    <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Faculty">+ Add
                        Faculty</button>
                </div>

                <div class="overflow-auto">

                    {{-- Alert for errors --}}
                    <div id="errorAlert" class="alert alert-danger d-none" role="alert"></div>
                    {{-- Alert for success messages --}}
                    <div id="successAlert" class="alert alert-success d-none" role="alert"></div>

                    <table class="status-table" id="facultiesList">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>University Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- rows injected via JS --}}
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </section>

    <!-- Create Faculty Popup-->
    <div class="modal fade" id="Faculty" tabindex="-1" aria-labelledby="FacultyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Faculties</div>
                    </div>

                    <div id="create-alert-container"></div>

                    <form id="facultiesForm" novalidate>
                        <div class="middle">
                            <span class="input-set">
                                <label for="name">Faculty Name</label>
                                <input type="text" id="name" name="name" required>
                                <div class="invalid-feedback" id="name_error"></div>
                            </span>

                            <span class="input-set">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </span>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="submit" class="blue"> Add Faculties</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Faculty Popup-->
    <div class="modal fade" id="EditFaculty" tabindex="-1" aria-labelledby="EditFacultyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Faculties</div>
                    </div>

                    <div id="edit-alert-container"></div>

                    <form id="editFacultyForm" novalidate>
                        <div class="middle">
                            <input type="hidden" id="edit_faculty_id" name="id" />

                            <span class="input-set">
                                <label for="edit_name">Faculty Name</label>
                                <input type="text" id="edit_name" name="name" required>
                                <div class="invalid-feedback" id="edit_name_error"></div>
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
                            <button type="submit" class="blue"> Update Faculty</button>
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

    {{-- Loader Overlay --}}
    <div id="globalLoader"
        style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.7); z-index:9999; align-items:center; justify-content:center;">
        <div style="text-align:center;">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <div style="margin-top:8px">Loading...</div>
        </div>
    </div>

@endsection

@push('scripts')

    <script type="text/javascript">
        (function ($) {
            // ------- Config / Utilities -------
            const API_BASE = '/api/admin/faculties';
            const $table = $('#facultiesList');

            function getAuthHeaders() {
                return {
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id') || localStorage.getItem('Auth-ID'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                };
            }

            function showLoader(show = true) {
                if (show) $('#globalLoader').fadeIn(100).css('display', 'flex');
                else $('#globalLoader').fadeOut(100);
            }

            function apiRequest({ url, method = 'GET', data = null }) {
                return $.ajax({
                    url,
                    type: method,
                    headers: getAuthHeaders(),
                    data: data ? JSON.stringify(data) : null,
                    contentType: data ? 'application/json' : undefined,
                });
            }

            function showPageAlert(type, message, timeout = 3000) {
                const $el = type === 'success' ? $('#successAlert') : $('#errorAlert');
                $el.text(message).removeClass('d-none');
                if (timeout) setTimeout(() => $el.addClass('d-none'), timeout);
            }

            function showModalAlert(containerSelector, type, message) {
                $(containerSelector).html(`
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
            }

            // validation helpers
            function clearValidationErrors(prefix = '') {
                $(`#${prefix}name, #${prefix}status`).removeClass('is-invalid');
                $(`#${prefix}name_error, #${prefix}status_error`).text('');
            }

            function displayValidationErrors(errors, prefix = '') {
                clearValidationErrors(prefix);
                for (const field in errors) {
                    const selector = `#${prefix}${field}`;
                    const errorDiv = $(`#${prefix}${field}_error`);
                    if ($(selector).length) $(selector).addClass('is-invalid');
                    if (errorDiv.length) errorDiv.text(errors[field][0]);
                }
            }

            // ----- table utilities -----

            // get number of columns from the table header
            function getTableColumnCount() {
                const thCount = $('#facultiesList thead tr').first().find('th').length;
                return Math.max(1, thCount);
            }

            // simple escape to avoid injecting markup into table
            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // Build row according to header columns; pads with empty <td> if header expects more
            function buildRow(faculty) {
                const statusText = faculty.status == 1 ? 'Active' : 'Inactive';
                const universityName = faculty.university && faculty.university.name ? faculty.university.name : '-';

                // cells in order corresponding to your <th> order
                const cells = [
                    `<td></td>`,
                    `<td class="faculty-name">${escapeHtml(faculty.name)}</td>`,
                    `<td class="faculty-university">${escapeHtml(universityName)}</td>`,
                    `<td class="faculty-status">${statusText}</td>`,
                    `<td>
                            <button type="button" class="edit-btn btn btn-sm btn-primary" data-id="${faculty.id}">Edit</button>
                            <button type="button" class="delete-btn btn btn-sm btn-danger" data-id="${faculty.id}">Delete</button>
                        </td>`
                ];

                // If header has more columns, pad with empty tds
                const targetCols = getTableColumnCount();
                while (cells.length < targetCols) cells.push('<td></td>');

                // If header has fewer columns, truncate to prevent mismatch
                if (cells.length > targetCols) cells.length = targetCols;

                return `<tr data-id="${faculty.id}">${cells.join('')}</tr>`;
            }

            // ---------- DataTable management ----------
            let facultiesTable = null;

            function initFacultiesTable() {
                if (!$.fn.DataTable) return;
                if (facultiesTable) return;

                facultiesTable = $table.DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                    lengthChange: true,
                    columnDefs: [
                        { orderable: false, targets: -1 } // actions not sortable
                    ],
                    // Do not use ajax option here; we manage rows manually
                });
            }

            // validate a row DOM node matches expected column count
            function validateRowNode(node) {
                const expected = getTableColumnCount();
                const actual = $(node).find('td').length;
                return expected === actual;
            }

            // Replace full table data (rowsHtml is <tr>...</tr> string)
            function replaceTableData(rowsHtml) {
                // parse incoming HTML
                const $tmp = $('<table><tbody>' + (rowsHtml || '') + '</tbody></table>');
                let nodes = $tmp.find('tbody tr').map(function () { return this; }).get();

                // validate nodes and drop invalid ones (log them)
                const invalid = nodes.filter(n => !validateRowNode(n));
                if (invalid.length) {
                    console.warn('DataTables: dropping rows with incorrect column count', invalid);
                    nodes = nodes.filter(n => validateRowNode(n));
                }

                if (!facultiesTable) {
                    // initial insert & init
                    $('#facultiesList tbody').html(rowsHtml || `<tr><td colspan="${getTableColumnCount()}">No data found</td></tr>`);
                    initFacultiesTable();
                    return;
                }

                facultiesTable.clear();
                if (nodes.length) facultiesTable.rows.add(nodes);
                facultiesTable.draw(false);
            }

            // Add single row (rowHtml string). If DataTable is active, it will append to end.
            function addRowToTable(rowHtml, toTop = false) {
                const node = $(rowHtml)[0];

                // quick sanity: ensure node columns count matches header
                if (!validateRowNode(node)) {
                    console.warn('Attempted to add row with incorrect column count, padding/truncating.');
                    // pad/truncate to match header by rebuilding using object approach — minimal approach:
                    // fallback: append safe empty row instead to avoid DataTables error
                    const safe = `<tr><td colspan="${getTableColumnCount()}">${escapeHtml($(node).find('.faculty-name').text() || 'New')}</td></tr>`;
                    if (!facultiesTable) {
                        $('#facultiesList tbody').append(safe);
                        initFacultiesTable();
                        return;
                    } else {
                        facultiesTable.row.add($(safe)[0]).draw(false);
                        return;
                    }
                }

                if (!facultiesTable) {
                    if (toTop) $('#facultiesList tbody').prepend(rowHtml);
                    else $('#facultiesList tbody').append(rowHtml);
                    initFacultiesTable();
                    return;
                }

                // DataTables API: add node and draw
                facultiesTable.row.add(node).draw(false);
                // Note: DataTables adds row at end. If you need the row to appear on page 1 top, you'd re-order/redraw.
            }

            function removeRowById(id) {
                if (!facultiesTable) {
                    $(`#facultiesList tbody tr[data-id="${id}"]`).remove();
                    return;
                }
                facultiesTable.rows(function (idx, data, node) {
                    return $(node).attr('data-id') == id;
                }).remove().draw(false);
            }

            function updateRowById(id, newHtmlOrObj) {
                if (!facultiesTable) {
                    const $row = $(`#facultiesList tbody tr[data-id="${id}"]`);
                    if ($row.length && typeof newHtmlOrObj === 'object') {
                        if (newHtmlOrObj.name !== undefined) $row.find('.faculty-name').text(newHtmlOrObj.name);
                        if (newHtmlOrObj.university !== undefined) $row.find('.faculty-university').text(newHtmlOrObj.university);
                        if (newHtmlOrObj.statusText !== undefined) $row.find('.faculty-status').text(newHtmlOrObj.statusText);
                    } else if ($row.length && typeof newHtmlOrObj === 'string') {
                        $row.replaceWith(newHtmlOrObj);
                    }
                    return;
                }

                facultiesTable.rows(function (idx, data, node) {
                    return $(node).attr('data-id') == id;
                }).every(function () {
                    const node = this.node();
                    if (typeof newHtmlOrObj === 'string') {
                        $(node).replaceWith(newHtmlOrObj);
                        this.invalidate();
                    } else if (typeof newHtmlOrObj === 'object') {
                        if (newHtmlOrObj.name !== undefined) $(node).find('.faculty-name').text(newHtmlOrObj.name);
                        if (newHtmlOrObj.university !== undefined) $(node).find('.faculty-university').text(newHtmlOrObj.university);
                        if (newHtmlOrObj.statusText !== undefined) $(node).find('.faculty-status').text(newHtmlOrObj.statusText);
                        this.invalidate();
                    }
                });
                facultiesTable.draw(false);
            }

            // ------- CRUD Handlers -------
            function loadFaculties() {
                showLoader(true);
                apiRequest({ url: API_BASE, method: 'GET' })
                    .done(function (response) {
                        if (response.success && Array.isArray(response.data)) {
                            let rows = '';
                            response.data.forEach((faculty) => rows += buildRow(faculty));
                            const emptyRow = `<tr><td colspan="${getTableColumnCount()}">No data found</td></tr>`;
                            replaceTableData(rows || emptyRow);
                        } else {
                            replaceTableData(`<tr><td colspan="${getTableColumnCount()}">No data found</td></tr>`);
                            showPageAlert('danger', response.message || 'Failed to load faculties.');
                        }
                    })
                    .fail(function (xhr) {
                        console.error(xhr);
                        replaceTableData(`<tr><td colspan="${getTableColumnCount()}">Error loading data</td></tr>`);
                        showPageAlert('danger', 'Error loading faculties.');
                    })
                    .always(function () {
                        showLoader(false);
                    });
            }

            function createFaculty(payload) {
                showLoader(true);
                return apiRequest({ url: `${API_BASE}/create`, method: 'POST', data: payload });
            }

            function fetchFacultyById(id) {
                showLoader(true);
                return apiRequest({ url: `${API_BASE}/${id}`, method: 'GET' });
            }

            function updateFaculty(id, payload) {
                showLoader(true);
                return apiRequest({ url: `${API_BASE}/${id}`, method: 'PUT', data: payload });
            }

            function deleteFaculty(id) {
                showLoader(true);
                return apiRequest({ url: `${API_BASE}/${id}`, method: 'DELETE' });
            }

            // ------- DOM Events -------
            $(document).ready(function () {
                // initialize datatable (once) and load data
                initFacultiesTable();
                loadFaculties();

                // Create faculty submit
                $('#facultiesForm').on('submit', function (e) {
                    e.preventDefault();
                    clearValidationErrors();
                    $('#create-alert-container').html('');

                    const payload = {
                        name: $('#name').val(),
                        status: $('#status').val()
                    };

                    createFaculty(payload)
                        .done(function (response) {
                            if (response.success) {
                                $('#Faculty').modal('hide');
                                showPageAlert('success', response.message || 'Faculty created successfully!');
                                if (response.data) {
                                    addRowToTable(buildRow(response.data), true);
                                } else {
                                    loadFaculties();
                                }
                            } else {
                                showModalAlert('#create-alert-container', 'danger', response.message || 'Failed to create faculty.');
                            }
                        })
                        .fail(function (xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                displayValidationErrors(xhr.responseJSON.errors);
                            } else {
                                showModalAlert('#create-alert-container', 'danger', 'An error occurred while creating the faculty.');
                            }
                        })
                        .always(function () { showLoader(false); });
                });

                // Open Edit modal - delegated (table may be rendered later)
                $(document).on('click', '.edit-btn', function () {
                    const id = $(this).data('id');
                    $('#edit-alert-container').html('');
                    clearValidationErrors('edit_');

                    fetchFacultyById(id)
                        .done(function (response) {
                            if (response.success && response.data) {
                                const data = response.data;
                                $('#edit_faculty_id').val(data.id);
                                $('#edit_name').val(data.name);
                                $('#edit_status').val(data.status);
                                $('#EditFaculty').modal('show');
                            } else {
                                showPageAlert('danger', response.message || 'Failed to load faculty data.');
                            }
                        })
                        .fail(function () {
                            showPageAlert('danger', 'An error occurred while loading faculty data.');
                        })
                        .always(function () { showLoader(false); });
                });

                // Submit Edit form
                $('#editFacultyForm').on('submit', function (e) {
                    e.preventDefault();
                    clearValidationErrors('edit_');
                    $('#edit-alert-container').html('');

                    const id = $('#edit_faculty_id').val();
                    const payload = {
                        name: $('#edit_name').val(),
                        status: $('#edit_status').val()
                    };

                    updateFaculty(id, payload)
                        .done(function (response) {
                            if (response.success) {
                                $('#EditFaculty').modal('hide');

                                if (response.data) {
                                    updateRowById(id, {
                                        name: response.data.name || payload.name,
                                        statusText: response.data.status == 1 ? 'Active' : 'Inactive',
                                        university: response.data.university && response.data.university.name ? response.data.university.name : ''
                                    });
                                    showPageAlert('success', response.message || 'Faculty updated successfully!');
                                } else {
                                    showPageAlert('success', response.message || 'Faculty updated. Reloading...');
                                    setTimeout(loadFaculties, 600);
                                }
                            } else {
                                showModalAlert('#edit-alert-container', 'danger', response.message || 'Failed to update faculty.');
                            }
                        })
                        .fail(function (xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                displayValidationErrors(xhr.responseJSON.errors, 'edit_');
                            } else {
                                showModalAlert('#edit-alert-container', 'danger', 'An error occurred while updating the faculty.');
                            }
                        })
                        .always(function () { showLoader(false); });
                });

                // Delete action (open confirmation)
                let deleteTargetId = null;
                $(document).on('click', '.delete-btn', function () {
                    deleteTargetId = $(this).data('id');
                    $('#deleteConfirmationModal').modal('show');
                });

                // Confirm delete
                $('#confirmDeleteBtn').on('click', function () {
                    if (!deleteTargetId) return;

                    deleteFaculty(deleteTargetId)
                        .done(function (response) {
                            $('#deleteConfirmationModal').modal('hide');
                            if (response.success) {
                                showPageAlert('success', response.message || 'Faculty deleted successfully!');
                                removeRowById(deleteTargetId);
                            } else {
                                showPageAlert('danger', response.message || 'Failed to delete faculty.');
                            }
                        })
                        .fail(function () {
                            $('#deleteConfirmationModal').modal('hide');
                            showPageAlert('danger', 'An error occurred while deleting the faculty.');
                        })
                        .always(function () { showLoader(false); deleteTargetId = null; });
                });

                // When modal closed, clear inputs & alerts
                $('#Faculty').on('hidden.bs.modal', function () {
                    $('#facultiesForm')[0].reset();
                    $('#create-alert-container').html('');
                    clearValidationErrors();
                });

                $('#EditFaculty').on('hidden.bs.modal', function () {
                    $('#editFacultyForm')[0].reset();
                    $('#edit-alert-container').html('');
                    clearValidationErrors('edit_');
                });
            });
        })(jQuery);
    </script>


@endpush