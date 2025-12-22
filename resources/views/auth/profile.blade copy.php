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
<div class="container py-4" style="max-width:900px;">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Profiles</h5>
            <span class="badge bg-primary" id="userRoles">User</span>
        </div>

        <div class="card-body">
            <form id="profileForm">
                <div class="row mb-3">
                    <label for="name" class="col-md-3 col-form-label">Full Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-3 col-form-label">Email</label>
                    <div class="col-md-9">
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="gender" class="col-md-3 col-form-label">Gender</label>
                    <div class="col-md-9">
                        <select class="form-select" id="gender" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Resident Info -->
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
                        <label class="col-md-3 col-form-label">Fathers Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="fathers_name" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Mothers Name</label>
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

                <div class="row">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-success">Update Profile</button>
                        <span id="updateMessage" class="ms-3 text-success d-none">Profile updated!</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        console.log("Profile Blade JS loaded"); // ✅ check if script runs

        $(document).on('profileDataLoaded', function(e, profileData) {
            console.log("Profile data received in Blade:", profileData); // ✅ should print data
        });
    });

    const token = localStorage.getItem('token');
    const authId = localStorage.getItem('auth-id');
    console.log("Layout script loaded, token:", token, "authId:", authId);

    if (!token || !authId) {
        console.warn("Token or Auth-ID missing, skipping profile AJAX");
        // callLogoutAPI(); // temporarily comment out to test
    }
</script>


<script>
    $(document).ready(function() {
        // Listen for profile data
        $(document).on('profileDataLoaded', function(e, profileData) {
            console.log("Profile data received:", profileData); // ✅ debug log

            $('#name').val(profileData.name ?? '');
            $('#email').val(profileData.email ?? '');
            $('#gender').val(profileData.gender ?? '');
            $('#userRoles').text(profileData.roles?.join(', ') ?? 'User');

            if (profileData.resident) {
                $('#residentSection').show();
                $('#scholar_no').val(profileData.resident.scholar_no ?? '');
                $('#contact_no').val(profileData.resident.number ?? '');
                $('#fathers_name').val(profileData.resident.fathers_name ?? '');
                $('#mothers_name').val(profileData.resident.mothers_name ?? '');
                $('#guardian_no').val(profileData.resident.guardian_no ?? '');
                $('#bed_room_building').val(
                    `${profileData.resident.bed_number ?? ''}/${profileData.resident.room_number ?? ''}/${profileData.resident.building_name ?? ''}`
                );
            }
        });
    });
</script>

{{-- <script>
        $(document).ready(function() {
            $('#profileForm').submit(function(e) {
                e.preventDefault();

                const token = localStorage.getItem('token');
                const authId = localStorage.getItem('auth-id');
                if (!token || !authId) {
                    alert('Session expired');
                    return;
                }

                $.ajax({
                    url: '/api/profile/update', // Your API endpoint to update personal info
                    type: 'POST',
                    headers: {
                        'token': token,
                        'Auth-ID': authId
                    },
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            $('#updateMessage').removeClass('d-none').fadeIn().delay(2000)
                                .fadeOut();
                        } else {
                            alert(res.message || 'Update failed');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script> --}}
@endpush