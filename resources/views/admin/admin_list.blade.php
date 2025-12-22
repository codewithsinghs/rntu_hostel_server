@extends('admin.layout')

@section('content')

    <!-- ================= TOP BAR ================= -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs p-0"><a class="p-0">Admin Management</a></div>
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddAdmin">
            + Add Admin
        </button>
    </div>

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <div class="top-breadcrumbs">
                    <div class="breadcrumbs p-0"><a class="p-0">Admin List</a></div>
                </div>

                <div class="overflow-auto">
                    <table class="status-table" id="adminList">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= ADD ADMIN MODAL ================= -->
    <div class="modal fade" id="AddAdmin" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="top">
                        <div class="pop-title">Add Admin</div>
                    </div>

                    <div id="addadminmessage"></div>

                    <form id="createAdminForm">
                        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">

                        <div class="middle">

                            <span class="input-set">
                                <label>Full Name</label>
                                <input type="text" id="name" name="name" required>
                            </span>

                            <span class="input-set">
                                <label>Email</label>
                                <input type="email" id="email" name="email" required>
                            </span>

                            <span class="input-set">
                                <label>Password</label>
                                <input type="password" id="password" name="password" required>
                            </span>

                            <span class="input-set">
                                <label>Role</label>
                                <select id="role" name="role" required></select>
                            </span>

                            <span class="input-set">
                                <label>Status</label>
                                <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="blue">Add Admin</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= EDIT ADMIN MODAL ================= -->
    <div class="modal fade" id="EditAdmin" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="top">
                        <div class="pop-title">Edit Admin</div>
                    </div>

                    <div id="editadminmessage"></div>

                    <form id="updateAdminForm">
                        <input type="hidden" id="edit_id">
                        <input type="hidden" id="edit_csrf" value="{{ csrf_token() }}">

                        <div class="middle">

                            <span class="input-set">
                                <label>Full Name</label>
                                <input type="text" id="edit_name" required>
                            </span>

                            <span class="input-set">
                                <label>Email</label>
                                <input type="email" id="edit_email" required>
                            </span>

                            <span class="input-set">
                                <label>Role</label>
                                <select id="edit_role" required></select>
                            </span>

                            <span class="input-set">
                                <label>Status</label>
                                <select id="edit_status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="blue">Save Changes</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        /* ================= FETCH ADMINS ================= */
        function fetchStaff() {
            fetch("{{ url('/api/admin/admin-list') }}", {
                headers: {
                    'Accept': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                }
            })
                .then(r => r.json())
                .then(res => {
                    let rows = '';
                    if (res.success && res.data.length) {
                        res.data.forEach((a, i) => {
                            rows += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${a.name}</td>
                            <td>${a.email}</td>
                            <td>${a.roles?.[0]?.name ?? 'N/A'}</td>
                            <td>${a.status == 1 ? 'Active' : 'Inactive'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="${a.id}">Edit</button>
                            </td>
                        </tr>`;
                        });
                        $('#adminList tbody').html(rows);
                        InitializeDatatable();
                    } else {
                        rows = `<tr><td colspan="6" class="text-center">No data found</td></tr>`;
                    }
                    // document.querySelector('#adminList tbody').innerHTML = rows;
                });
        }

        /* ================= LOAD ROLES ================= */
        function loadRoles(selectId) {
            fetch("{{ url('/api/admin/roles') }}", {
                headers: {
                    'Accept': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                }
            })
                .then(r => r.json())
                .then(res => {
                    let select = document.getElementById(selectId);
                    select.innerHTML = `<option value="">Select Role</option>`;
                    res.data.forEach(role => {
                        select.innerHTML += `<option value="${role.name}">${role.fullname}</option>`;
                    });
                });
        }

        /* ================= ADD ADMIN ================= */
        document.getElementById('createAdminForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let msgBox = document.getElementById('addadminmessage');
            msgBox.innerHTML = '';

            let formData = new FormData(this);

            fetch("{{ url('/api/admin/admin/create') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                body: formData
            })
                .then(async res => {
                    let data = await res.json();

                    if (!res.ok) {
                        let html = `<div class="alert alert-danger"><ul>`;
                        Object.values(data.errors || {}).forEach(errs => {
                            errs.forEach(e => html += `<li>${e}</li>`);
                        });
                        html += `</ul></div>`;
                        msgBox.innerHTML = html;
                        return;
                    }

                    msgBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    this.reset();

                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('AddAdmin')).hide();
                        fetchStaff();
                    }, 1000);
                });
        });

        /* ================= EDIT ADMIN ================= */
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('edit-btn')) {
                let id = e.target.dataset.id;
                document.getElementById('edit_id').value = id;
                loadRoles('edit_role');

                fetch(`{{ url('/api/admin/admin') }}/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                })
                    .then(r => r.json())
                    .then(res => {
                        let a = res.data;
                        edit_name.value = a.name;
                        edit_email.value = a.email;
                        edit_status.value = a.status;
                        edit_role.value = a.roles?.[0]?.name ?? '';
                        new bootstrap.Modal(EditAdmin).show();
                    });
            }
        });

        /* ================= UPDATE ADMIN ================= */
        document.getElementById('updateAdminForm').addEventListener('submit', function (e) {
            e.preventDefault();

            fetch(`{{ url('/api/admin/admin/update') }}/${edit_id.value}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': edit_csrf.value,
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                body: JSON.stringify({
                    name: edit_name.value,
                    email: edit_email.value,
                    role: edit_role.value,
                    status: edit_status.value
                })
            })
                .then(r => r.json())
                .then(res => {
                    document.getElementById('editadminmessage').innerHTML =
                        `<div class="alert alert-success">${res.message}</div>`;
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(EditAdmin).hide();
                        fetchStaff();
                    }, 1000);
                });
        });

        /* ================= INIT ================= */
        document.addEventListener('DOMContentLoaded', () => {
            fetchStaff();
            loadRoles('role');
        });
    </script>
@endpush