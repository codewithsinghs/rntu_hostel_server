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
                <h5 class="mb-0">Profile</h5>
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
    {{-- <script>
        console.log("Profile Blade JS loaded");

        $(document).on('profileDataLoaded', function() {
            const user = profileData;
            console.log("Profile data ready:", user);

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
        });
    </script> --}}

    <script>
        console.log("Profile Blade JS loaded");

// Listen for the event fired by layout when profileData is ready
$(document).on('profileDataLoaded', function(e, data) {
    console.log("Profile data loaded in Blade:", data);

    const user = data;

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
});

    </script>
@endpush
