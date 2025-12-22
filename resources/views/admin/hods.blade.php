@extends('admin.layout')

@section('content')

    <!-- ================= TOP BAR ================= -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs p-0"><a class="p-0">HODs Management</a></div>
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddHodModal">
            + Add HOD
        </button>
    </div>

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <div class="top-breadcrumbs">
                    <div class="breadcrumbs p-0"><a class="p-0">HODs List</a></div>
                </div>

                <div class="overflow-auto">
                    <div id="mainResponseMessage"></div>

                    <table class="status-table" id="hodsList">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
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

    <!-- ================= ADD HOD MODAL ================= -->
    <div class="modal fade" id="AddHodModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="top">
                        <div class="pop-title">Add HOD</div>
                    </div>

                    <form id="createHODForm">
                        @csrf
                        <input type="hidden" id="csrf_token_field" value="{{ csrf_token() }}">

                        <div class="middle">
                            <span class="input-set">
                                <label>Full Name</label>
                                <input type="text" id="name" required>
                            </span>

                            <span class="input-set">
                                <label>Email</label>
                                <input type="email" id="email" required>
                            </span>

                            <span class="input-set">
                                <label>Password</label>
                                <input type="password" id="password" required>
                            </span>

                            <span class="input-set">
                                <label>Department</label>
                                <select id="department_id" required></select>
                            </span>

                            <span class="input-set">
                                <label>Status</label>
                                <select id="status" required>
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </span>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="blue">Add HOD</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <!-- ================= EDIT HOD MODAL ================= -->
    <div class="modal fade" id="EditHodModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit HOD</div>
                    </div>

                    <div id="editMessage"></div>

                    <form id="editHODForm">
                        <input type="hidden" id="edit_hod_id">

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
                            <label>Department</label>
                            <select id="edit_department_id" required></select>
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
                            <button type="submit" class="blue">Update HOD</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            loadHods();
            loadDepartments();

            document.getElementById("createHODForm").addEventListener("submit", createHod);
            document.getElementById("editHODForm").addEventListener("submit", updateHod);

            document.addEventListener("click", e => {
                if (e.target.classList.contains("edit-hod-btn")) {
                    openEditModal(e.target.dataset.id);
                }
            });
        });

        /* ---------- MESSAGE ---------- */
        function showMessage(msg, type = "success", target = "mainResponseMessage") {
            const el = document.getElementById(target);
            el.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
            setTimeout(() => el.innerHTML = "", 3000);
        }

        /* ---------- LOAD HODS ---------- */
        function loadHods() {
            fetch("{{ url('/api/admin/hods-list') }}", {
                headers: {
                    "Accept": "application/json",
                    "token": localStorage.getItem("token"),
                    "Auth-ID": localStorage.getItem("auth-id")
                }
            })
                .then(r => r.json())
                .then(res => {
                    const tbody = document.querySelector("#hodsList tbody");
                    tbody.innerHTML = "";

                    if (!res.success || !res.data.length) {
                        tbody.innerHTML = `<tr><td colspan="6" class="text-center">No data</td></tr>`;
                        return;
                    }

                    res.data.forEach((h, i) => {
                        tbody.innerHTML += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${h.name}</td>
                                    <td>${h.email}</td>
                                    <td>${h.department?.name ?? 'N/A'}</td>
                                    <td>${h.status == 1 ? 'Active' : 'Inactive'}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-hod-btn" data-id="${h.id}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>`;
                    });

                    if ($.fn.DataTable.isDataTable('#hodsList')) {
                        $('#hodsList').DataTable().destroy();
                    }
                    InitializeDatatable();
                });
        }

        /* ---------- LOAD DEPARTMENTS ---------- */
        function loadDepartments() {
            fetch("{{ url('/api/admin/departments') }}", {
                headers: {
                    "Accept": "application/json",
                    "token": localStorage.getItem("token"),
                    "Auth-ID": localStorage.getItem("auth-id")
                }
            })
                .then(r => r.json())
                .then(res => {
                    const add = department_id;
                    const edit = edit_department_id;
                    add.innerHTML = edit.innerHTML = `<option value="">Select Department</option>`;

                    res.data.forEach(d => {
                        add.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                        edit.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                    });
                });
        }

        /* ---------- CREATE HOD ---------- */
        function createHod(e) {
            e.preventDefault();

            const nameEl = document.getElementById("name");
            const emailEl = document.getElementById("email");
            const passwordEl = document.getElementById("password");
            const departmentEl = document.getElementById("department_id");
            const statusEl = document.getElementById("status");
            const csrfEl = document.getElementById("csrf_token_field");

            const data = {
                name: nameEl.value.trim(),
                email: emailEl.value.trim(),
                password: passwordEl.value,
                department_id: departmentEl.value,
                status: statusEl.value,
                role: "hod"
            };

            fetch("{{ url('/api/admin/hods/create') }}", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfEl.value,
                    "token": localStorage.getItem("token"),
                    "Auth-ID": localStorage.getItem("auth-id")
                },
                body: JSON.stringify(data)
            })
                .then(async res => {
                    const json = await res.json();
                    if (!res.ok) throw json;
                    return json;
                })
                .then(res => {
                    showMessage("HOD added successfully");
                    document.getElementById("createHODForm").reset();
                    bootstrap.Modal.getInstance(
                        document.getElementById("AddHodModal")
                    ).hide();
                    loadHods();
                })
                .catch(err => {
                    if (err.errors) {
                        const firstError = Object.values(err.errors)[0][0];
                        showMessage(firstError, "danger");
                    } else {
                        showMessage("Failed to add HOD", "danger");
                    }
                });
        }


        /* ---------- OPEN EDIT ---------- */
        function openEditModal(id) {
            fetch(`{{ url('/api/admin/hods') }}/${id}`, {
                headers: {
                    "Accept": "application/json",
                    "token": localStorage.getItem("token"),
                    "Auth-ID": localStorage.getItem("auth-id")
                }
            })
                .then(r => r.json())
                .then(res => {
                    edit_hod_id.value = id;
                    edit_name.value = res.data.name;
                    edit_email.value = res.data.email;
                    edit_department_id.value = res.data.department_id;
                    edit_status.value = res.data.status;

                    new bootstrap.Modal(EditHodModal).show();
                });
        }

        /* ---------- UPDATE HOD ---------- */
        function updateHod(e) {
            e.preventDefault();

            const id = edit_hod_id.value;

            fetch(`{{ url('/api/admin/hods/update') }}/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf_token_field.value,
                    "token": localStorage.getItem("token"),
                    "Auth-ID": localStorage.getItem("auth-id")
                },
                body: JSON.stringify({
                    name: edit_name.value.trim(),
                    email: edit_email.value.trim(),
                    department_id: edit_department_id.value,
                    status: edit_status.value
                })
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showMessage("HOD updated successfully");
                        bootstrap.Modal.getInstance(EditHodModal).hide();
                        loadHods();
                    } else {
                        showMessage(res.message, "danger", "editMessage");
                    }
                });
        }
    </script>
@endpush