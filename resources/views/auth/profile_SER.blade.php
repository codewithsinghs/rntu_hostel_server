{{-- @php
    $role = request()->segment(1); // Gets 'admin' from /admin/change-password
@endphp

@extends("$role.layout") --}}

@php
    $role = request()->segment(1); // "admin", "resident", etc.
    $layouts = [
        'admin' => 'admin.layout',
        'resident' => 'resident.layout',
        'accountant' => 'accountant.layout',
        'warden' => 'warden.layout',
        'admission' => 'admission.layout',
    ];
    $layout = $layouts[$role] ?? 'backend.layouts.app'; // fallback
@endphp

@extends($layout)

{{-- @extends('layouts.app') --}}


@section('content')
    {{-- <div class="container py-4" style="max-width:900px;">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Profile</h5>
                <span class="badge bg-primary" id="userRoles">User</span>
            </div>

            <div class="card-body">
                <form id="profileForm">
                    <!-- Personal Info -->
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Personal Info</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="editPersonalBtn">Edit</button>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-md-3 col-form-label">Full Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="name" name="name" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-9">
                            <input type="email" class="form-control" id="email" name="email" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="gender" class="col-md-3 col-form-label">Gender</label>
                        <div class="col-md-9">
                            <select class="form-select" id="gender" name="gender" disabled>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Resident Info (Read-Only) -->
                    <div id="residentSection" style="display:none;">
                        <hr>
                        <h6 class="mb-3">Resident Info</h6>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Scholar Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="scholar_no" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Contact Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="contact_no" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Father's Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="fathers_name" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Mother's Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="mothers_name" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Guardian No</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="guardian_no" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Bed / Room / Building</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="bed_room_building" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Update Button -->
                    <div class="row mt-3">
                        <div class="col-md-9 offset-md-3 d-flex align-items-center">
                            <button type="submit" class="btn btn-success d-none" id="updateBtn">Update Profile</button>
                            <span id="updateMessage" class="ms-3 text-success d-none">Profile updated!</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid  p-5 py-4">
        <div class="row shadow-lg p-5">
            <div class="col-md-12">
                <!-- Header -->
                <div class="card-header mb-4 d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold"><i class="fas fa-user me-2"></i>My Profile</h3>
                    <span class="badge bg-primary fs-6" id="userRoles">User</span>
                </div>


                <!-- Personal Info (Read-only) -->
                <div class="card-body ">
                    <!-- Personal Info Two-Column Layout -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Personal Info</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="editPersonalBtn">Edit</button>
                            <button type="button" class="btn btn-sm btn-success d-none" id="savePersonalBtn">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary d-none"
                                id="cancelPersonalBtn">Cancel</button>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3">

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group row">

                                <label for="name" class="col-md-4 form-label fw-semibold">Full Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control profile-input" id="name" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 form-label fw-semibold">Email</label>
                                <div class="col-md-8">
                                    <input type="email" class="form-control profile-input" id="email" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Contact Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="contact_number" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Gender</label>
                                <div class="col-md-8">
                                    <select class="form-select" id="gender" disabled style="height: auto;">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9 d-flex align-items-center">
                                <input type="password" class="form-control me-2" value="********" readonly>
                                <a href="/change-password" class="btn btn-outline-secondary btn-sm">Change</a>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Resident Info (Read-only) -->
                <div id="residentSection" style="display:none;">
                    <div class="card-header sh mb-4 ">
                        <h6 class="fw-bold"><i class="fas fa-user me-2"></i>Resident Info</h6>
                    </div>

                    <div class="card-body ">
                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Scholar Number</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="scholar_no" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Father Name -->
                            <div class="col-md-6">
                                <div class="form-group row">

                                    <label class="col-md-4 col-form-label">Father's Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="fathers_name" readonly>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Mother's Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="mothers_name" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Guardian No</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="guardian_no" readonly>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Bed / Room / Building</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="bed_room_building" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            console.log("Profile Blade JS loaded");

            function populateProfile(user) {
                $('#name').val(user.name ?? '');
                $('#email').val(user.email ?? '');
                $('#gender').val(user.gender ?? '');
                $('#userRoles').text(user.roles?.join(', ') ?? 'User');

                if (user.resident) {
                    $('#residentSection').show();
                    $('#scholar_no').val(user.resident.scholar_no ?? '');
                    $('#contact_no').val(user.resident.number ?? '');
                    $('#fathers_name').val(user.resident.fathers_name ?? '');
                    $('#mothers_name').val(user.resident.mothers_name ?? '');
                    $('#guardian_no').val(user.resident.guardian_no ?? '');
                    $('#bed_room_building').val(
                        `${user.resident.bed_number ?? ''}/${user.resident.room_number ?? ''}/${user.resident.building_name ?? ''}`
                    );
                }
            }

            // Use cached layout data or wait for event
            const cachedProfile = JSON.parse(localStorage.getItem('profileData'));
            if (cachedProfile) {
                populateProfile(cachedProfile);
            }
            $(document).on('profileDataLoaded', function(e, data) {
                populateProfile(data);
            });

            // Edit personal info
            $('#editPersonalBtn').click(function() {
                $('#name, #email, #gender').prop('readonly', false).prop('disabled', false);
                $('#updateBtn').removeClass('d-none');
            });

            // Update form
            $('#profileForm').submit(function(e) {
                e.preventDefault();
                const token = localStorage.getItem('token');
                const authId = localStorage.getItem('auth-id');

                const updatedData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    gender: $('#gender').val()
                };

                $.ajax({
                    url: '/api/profile/update', // your update API
                    type: 'POST',
                    headers: {
                        'token': token,
                        'Auth-ID': authId
                    },
                    data: updatedData,
                    success: function(res) {
                        if (res.success) {
                            $('#updateMessage').removeClass('d-none');
                            $('#updateBtn').addClass('d-none');
                            $('#name, #email, #gender').prop('readonly', true).prop('disabled',
                                true);
                            // Update cached profile
                            const profileData = JSON.parse(localStorage.getItem(
                                'profileData')) || {};
                            Object.assign(profileData, updatedData);
                            localStorage.setItem('profileData', JSON.stringify(profileData));
                        } else {
                            alert(res.message || "Update failed");
                        }
                    },
                    error: function(err) {
                        console.error(err);
                        alert("Something went wrong while updating profile.");
                    }
                });
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            console.log("Profile Blade JS loaded");

            function populateProfile(user) {
                $('#name').val(user.name ?? '');
                $('#email').val(user.email ?? '');
                $('#contact_number').val(user.resident?.number ?? '');
                $('#gender').val(user.gender ?? '');
                $('#userRoles').text(user.roles?.join(', ').toUpperCase() ?? 'User');

                if (user.resident) {
                    $('#residentSection').show();
                    $('#scholar_no').val(user.resident.scholar_no ?? '');
                    $('#fathers_name').val(user.resident.fathers_name ?? '');
                    $('#mothers_name').val(user.resident.mothers_name ?? '');
                    $('#guardian_no').val(user.resident.guardian_no ?? '');
                    $('#bed_room_building').val(
                        `${user.resident.bed_number ?? ''}/${user.resident.room_number ?? ''}/${user.resident.building_name ?? ''}`
                    );
                }
            }

            const cachedProfile = JSON.parse(localStorage.getItem('profileData'));
            if (cachedProfile) populateProfile(cachedProfile);
            $(document).on('profileDataLoaded', function(e, data) {
                populateProfile(data);
            });

            // Edit personal info
            $('#editPersonalBtn').click(function() {
                $('#name,#email,#gender,#contact_number').prop('readonly', false).prop('disabled', false);
                $('#editPersonalBtn').addClass('d-none');
                $('#savePersonalBtn,#cancelPersonalBtn').removeClass('d-none');
            });

            $('#cancelPersonalBtn').click(function() {
                const profile = JSON.parse(localStorage.getItem('profileData')) || {};
                populateProfile(profile);
                $('#editPersonalBtn').removeClass('d-none');
                $('#savePersonalBtn,#cancelPersonalBtn').addClass('d-none');
                $('#name,#email,#gender,#contact_number').prop('readonly', true).prop('disabled', true);
            });

            $('#savePersonalBtn').click(function() {
                const token = localStorage.getItem('token');
                const authId = localStorage.getItem('auth-id');

                const updatedData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    gender: $('#gender').val(),
                    contact_number: $('#contact_number').val()
                };

                $.ajax({
                    url: '/api/profile/update',
                    type: 'POST',
                    headers: {
                        'token': token,
                        'Auth-ID': authId
                    },
                    data: updatedData,
                    success: function(res) {
                        if (res.success) {
                            const profileData = JSON.parse(localStorage.getItem(
                                'profileData')) || {};
                            Object.assign(profileData, updatedData);
                            localStorage.setItem('profileData', JSON.stringify(profileData));
                            populateProfile(profileData);
                            $('#savePersonalBtn,#cancelPersonalBtn').addClass('d-none');
                            $('#editPersonalBtn').removeClass('d-none');
                            alert('Profile updated successfully');
                        } else alert(res.message || "Update failed");
                    },
                    error: function(err) {
                        console.error(err);
                        alert("Error updating profile");
                    }
                });
            });
        });
    </script>
@endpush
