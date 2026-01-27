@php
    $role = request()->segment(1); // "admin", "resident", etc.
    $layouts = [
        'admin' => 'admin.layout',
        'resident' => 'resident.layout',
        'accountant' => 'accountant.layout',
        'admission' => 'admission.layout',
        'warden' => 'warden.layout',
        'admission' => 'admission.layout',
        'guest' => 'guest.layout',
    ];
    $layout = $layouts[$role] ?? 'backend.layouts.app'; // fallback
@endphp

@extends($layout)

@section('content')
    <section class="user-management-container">
        <!-- Header section -->

        <div class="header-bar" style="background-image: url('../backend/img/profile/user-management-bg.png') ">

            <div class="section-header">
                <p> <a href="{{ route('resident.dashboard') }}">Dashboard /</a> <a href="#">Resident
                        Details</a> </p>
                <h3>Resident Details</h3>
            </div>
            <div class="search-section">
                <input type="text" placeholder="Type..." />
                <img src="{{ asset('backend/img/profile/Icon.png') }}" alt="" />
            </div>
        </div>

        <!-- white block -->
        <div class="add-member">
            <div class="user-details">
                {{-- <div class="image-div">
                    <img src="{{ asset('backend/img/profile/user-management-dp.png') }}" alt="Add Member"
                        class="profile-photo" />
                    <div class="icon-div">
                        <img src="{{ asset('backend/img/profile/edit.png') }}" alt="" />
                    </div>
                </div> --}}
                <div class="image-div" data-bs-toggle="modal" data-bs-target="#changeProfilePhotoModal">
                    {{-- <img src="{{ asset('backend/img/profile/user-management-dp.png') }}" alt="Profile Photo"
                        class="profile-photo" /> --}}
                    <img id="profileImage" src="{{ asset('backend/img/profile/user-management-dp.png') }}"
                        alt="Profile Photo" class="profile-photo">

                    <div class="icon-div">
                        <img src="{{ asset('backend/img/profile/edit.png') }}" alt="Edit" />
                    </div>
                </div>

                <div class="info">
                    {{-- <h5>Rajat Pradhan (Scholar - 455465445)</h5>
                    <p>prajat917@gmail.com</p> --}}
                    <h5 id="header_name"></h5>
                    <p id="header_email"></p>
                </div>
            </div>
            <div class="add-member-btn">
                <button class="active-btn" disabled>On Class</button>
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#finalcheckout">Final Check-Out
                    Request</button>
            </div>
        </div>

        <!-- Personal Details Section -->
        <section class="profile-info-section">
            <div class="profile-info-header">
                <h3>Profile Information</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#ProfileInformation">Edit</button>
            </div>

            <div class="profile-grid">
                <div class="profile-field">
                    <label>Student Type</label>
                    <input type="text" id="view_user_type" value="" disabled />
                </div>
                <div class="profile-field">
                    <label>Full Name</label>
                    <input type="text" id="view_name" value="" disabled />
                </div>
                <div class="profile-field">
                    <label>Email Address</label>
                    <input type="text" id="view_email" value="" disabled />
                </div>
                <div class="profile-field">
                    <label>Mobile Number</label>
                    <input type="text" id="view_mobile" value="" disabled />
                </div>
                <div class="profile-field">
                    <label>Gender</label>
                    <input type="text" id="view_gender" value="" disabled />
                </div>
                <div class="profile-field">
                    <label>Enrollment / Scholar ID</label>
                    <input type="text" id="view_scholar_no" value="" disabled />
                </div>
                <div class="profile-field address-field">
                    <label>Address</label>
                    <input type="text" id="view_address" value="" disabled />
                </div>
            </div>
        </section>

        <!-- Family Details Section -->
        <section class="info-section">
            <div class="info-header">
                <h3>Family Details</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#FamilyDetails">Edit</button>
            </div>
            <div class="info-grid">
                <div class="field">
                    <label>Father's Name</label>
                    <input type="text" id="view_father_name" value="" disabled />
                </div>
                <div class="field">
                    <label>Mother's Name</label>
                    <input type="text" id="view_mother_name" value="" disabled />
                </div>
                <div class="field">
                    <label>Primary Contact Number</label>
                    <input type="text" id="primary_contact" value="" disabled />
                </div>
                <div class="field">
                    <label>Secondary Contact Number</label>
                    <input type="text" id="secondary_contact" value="" disabled />
                </div>
            </div>

            <h4>Emergency Contact Details</h4>
            <div class="info-grid">
                <div class="field">
                    <label>Name</label>
                    <input type="text" id="guardian_name" value="" disabled />
                </div>
                <div class="field">
                    <label>Relationship</label>
                    <input type="text" id="guardian_relation" value="Mother" disabled />
                </div>
                <div class="field">
                    <label>Contact Number</label>
                    <input type="text" id="guardian_contact" value="7879545752" disabled />
                </div>
            </div>

        </section>

        <!-- Hostel Information Section -->
        <section class="info-section">
            <div class="info-header">
                <h3>Student Hostel Information</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#HostelInformation">Edit</button>
            </div>
            <div class="info-grid">
                <div class="field">
                    <label>Hostel Name</label>
                    <input type="text" id="view_hostel_name" value="" disabled />
                </div>
                <div class="field">
                    <label>Hostel Floor</label>
                    <input type="text" id="view_hostel_floor" value="" disabled />
                </div>
                <div class="field">
                    <label>Room Number</label>
                    <input type="text" id="view_room_number" value="256" disabled />
                </div>
                <div class="field">
                    <label>Bed Number</label>
                    <input type="text" id="view_bed_number" value="8987" disabled />
                </div>
                <div class="field">
                    <label>Duration of Stay</label>
                    <input type="text" value="Full Degree" disabled />
                </div>
                <div class="field">
                    <label>Joining Date</label>
                    <input type="text" id="view_joining_date" value="25/05/2025" disabled />
                </div>
            </div>
        </section>

    </section>

    <!-- All Popup -->

    {{-- <div class="modal fade" id="changeProfilePhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">

                <div class="modal-header border-0">
                    <h5 class="modal-title">Update Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body text-center">

                        <img id="previewImage" src="{{ asset('backend/img/profile/user-management-dp.png') }}"
                            class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">

                        <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">

                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Photo</button>
                    </div>

                </form>

            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="changeProfilePhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">

                <div class="modal-header border-0">
                    <h5 class="modal-title">Update Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">

                    <!-- CROPPING AREA -->
                    <div id="cropSection">
                        <div class="crop-container">
                            <img id="cropImage" src="{{ asset('backend/img/profile/user-management-dp.png') }}">
                        </div>

                        <input type="file" id="photoInput" class="form-control mt-3" accept="image/*">
                    </div>

                    <!-- PREVIEW AREA -->
                    <div id="previewSection" class="d-none">
                        <h6 class="mb-3">Preview</h6>
                        <img id="finalPreview" class="rounded-circle" width="200" height="200"
                            style="object-fit: cover;">
                    </div>

                </div>

                <div class="modal-footer border-0">

                    <!-- CROP BUTTON -->
                    <button id="cropBtn" class="btn btn-primary">Crop</button>

                    <!-- BACK BUTTON -->
                    <button id="backBtn" class="btn btn-light d-none">Back</button>

                    <!-- FINAL SAVE BUTTON -->
                    <button id="saveBtn" class="btn btn-success d-none">Save Photo</button>

                </div>

            </div>
        </div>
    </div>



    <!-- Profile Information -->
    <div class="modal fade" id="ProfileInformation" tabindex="-1" aria-labelledby="ProfileInformationLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Profile Information</div>
                    </div>

                    <div class="middle">

                        {{-- <span class="input-set">
                            <label for="StudentType">Student Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option>Select Student Type</option>
                                <option value="Regular" selected>Regular</option>
                                <option value="Credit Card">Female</option>
                                <option value="Others">Others</option>
                            </select>
                        </span> --}}

                        <span class="input-set">
                            <label>Full Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="" />
                        </span>

                        <span class="input-set">
                            <label>Email Address</label>
                            <input class="form-control" type="text" name="email" id="email" value="" />
                        </span>

                        <span class="input-set">
                            <label>Mobile Number</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" />
                        </span>

                        <span class="input-set">
                            <label>Alternate Contact Number</label>
                            <input class="form-control" type="text" name="alternate_mobile" id="alternate_mobile"
                                value="" />
                        </span>

                        {{-- <span class="input-set">
                            <label for="Gender">Gender</label>
                            <select class="form-select" aria-label="Default select example">
                                <option>Select Gender</option>
                                <option value="Male" selected>Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>
                        </span> --}}

                        {{-- <span class="input-set">
                            <label>Enrollment / Scholar ID</label>
                            <input type="text"  name="scholar_number" id="scholar_number" value="4554654456" />
                        </span> --}}

                        <span class="input-set">
                            <label>Address</label>
                            <input class="form-control" type="text" name="address" id="address"
                                value="08 Gujar Pura" />
                        </span>

                        {{-- <span class="input-set">
                            <label>Attach Supporting Documents</label>
                            <input class="form-control" name="document" type="file" />
                        </span> --}}

                        <span class="input-set">
                            <label for="Reason">Reason for editing profile</label>
                            <textarea class="" type="text" name="reason" id="reason" placeholder="Enter your reason..."></textarea>
                        </span>

                    </div>
                    <h6 class="mt-3 p-3">Leave blank to keep current password</h6>

                    <div class="middle mt-1">
                        <span class="input-set">
                            <label for="password">New Password (optional)</label>
                            <input type="password" id="password" class=""
                                placeholder="Leave blank to keep current password">
                        </span>

                        <span class="input-set">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" class=""
                                placeholder="Re-enter password">
                        </span>

                    </div>
                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                        </button>
                        {{-- <button type="button" class="blue"> Send to Admin</button> --}}
                        <button type="button" class="blue" id="submitProfileModal">
                            Save Changes
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Family Details -->
    <div class="modal fade" id="FamilyDetails" tabindex="-1" aria-labelledby="FamilyDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Family Details</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label>Father's Name</label>
                            <input type="text" name="fathers_name" id="fathers_name" value="" />
                        </span>

                        <span class="input-set">
                            <label>Mother's Name</label>
                            <input type="text" name="mothers_name" id="mothers_name" value="" />
                        </span>

                        <span class="input-set">
                            <label>Parent Contact Number</label>
                            <input type="text" name="parent_contact" id="parent_contact" value="7879545752" />
                        </span>

                        <span class="input-set">
                            <label>Emergency Contact Number</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="7024390158" />
                        </span>

                        <span class="input-set">
                            <label>Guardean Name</label>
                            <input type="text" name="guardian_name" id="guardian_name" value="Kirti Pradhan" />
                        </span>

                        <span class="input-set">
                            <label>Guardean Relation</label>
                            <input type="text" name="guardian_relation" id="guardian_relation"
                                value="Kirti Pradhan" />
                        </span>

                        {{-- <span class="input-set">
                            <label for="Relationship">Relationship</label>
                            <select class="form-select" aria-label="Default select example">
                                <option>Select Relationship</option>
                                <option value="Mother" selected>Mother</option>
                                <option value="Father">Father</option>
                                <option value="Sister">Sister</option>
                                <option value="Brother">Brother</option>
                            </select>
                        </span> --}}

                        <span class="input-set">
                            <label>Guardean Contact Number</label>
                            <input type="text" name="guardian_contact" id="guardian_contact" value="" />
                        </span>

                        {{-- <span class="input-set">
                            <label>Attach Supporting Documents</label>
                            <input type="file" />
                        </span> --}}



                        <div class="input-set">
                            <label for="Reason">Reason for editing family details</label>
                            <textarea type="text" name="reason" placeholder="Enter your reason..."></textarea>
                        </div>
                    </div>
                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                        </button>
                        <button type="button" class="blue"> Send to Admin</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Hostel Information -->
    <div class="modal fade" id="HostelInformation" tabindex="-1" aria-labelledby="HostelInformationLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Hostel Information</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="HostelName">Hostel Name</label>
                            <select class="form-select" aria-label="Default select example">
                                <option>Select Hostel Name</option>
                                <option value="One" selected>One</option>
                                <option value="Two">Two</option>
                                <option value="Others">Others</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="HostelFloor">Hostel Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option>Select Hostel Floor</option>
                                <option value="One" selected>One</option>
                                <option value="Two">Two</option>
                                <option value="Others">Others</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label>Room Number</label>
                            <input type="text" value="202" />
                        </span>

                        <span class="input-set">
                            <label>Bed Number</label>
                            <input type="text" value="235" />
                        </span>

                        <span class="input-set">
                            <label for="DurationofStay">Duration of Stay</label>
                            <select class="form-select" aria-label="Default select example">
                                <option>Select Duration of Stay</option>
                                <option value="3 Months" selected>3 Months</option>
                                <option value="6 Months">6 Months</option>
                                <option value="Full Course">Full Course</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label>Joining Date</label>
                            <input type="text" value="20/02/25" />
                        </span>

                        <span class="input-set">
                            <label>Attach Supporting Documents</label>
                            <input type="file" />
                        </span>

                    </div>

                    <div class="reason">
                        <label for="Reason">Reason for editing hostel</label>
                        <textarea type="text" placeholder="Enter your reason..."></textarea>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                        </button>
                        <button type="button" class="blue"> Send to Admin</button>
                    </div>

                </div>

            </div>
        </div>
    </div>



    <!-- finalcheckout -->
    <div class="modal fade" id="finalcheckout" tabindex="-1" aria-labelledby="finalcheckoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Final Check-Out Form</div>
                    </div>

                    <div class="tabs-middle">

                        <div class="tabset">
                            <!-- Tab 1 -->
                            <input type="radio" name="tabset" id="tab1" aria-controls="HostelDetails" checked>
                            <label for="tab1">Hostel Details</label>

                            <!-- Tab 2 -->
                            <input type="radio" name="tabset" id="tab2"
                                aria-controls="Fee Collection Details  ">
                            <label for="tab2">Fee Collection Details </label>

                            <!-- Tab 3 -->
                            <input type="radio" name="tabset" id="tab3" aria-controls="Assests">
                            <label for="tab3">Assests</label>

                            <div class="tab-panels">
                                <!-- Tab 1 -->
                                <section id="HostelDetails" class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalBed">Total Bed</label>
                                                <input type="text" id="TotalBed" name="TotalBed"
                                                    placeholder="Total Bed">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalBathroom">Total Bathroom</label>
                                                <input type="text" id="TotalBathroom" name="TotalBathroom"
                                                    placeholder="Total Bathroom">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalFloors">Total Floors</label>
                                                <input type="text" id="TotalFloors" name="TotalFloors"
                                                    placeholder="Total Floors">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalRooms">Total Rooms</label>
                                                <input type="text" id="TotalRooms" name="TotalRooms"
                                                    placeholder="Total Rooms">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Occupied">Occupied</label>
                                                <input type="text" id="Occupied" name="Occupied"
                                                    placeholder="Occupied">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Students">Students</label>
                                                <input type="text" id="Students" name="Students"
                                                    placeholder="Students">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="In Hostel">In Hostel</label>
                                                <input type="text" id="In Hostel" name="In Hostel"
                                                    placeholder="In Hostel">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="OutsideHostel">Outside Hostel</label>
                                                <input type="text" id="OutsideHostel" name="OutsideHostel"
                                                    placeholder="Outside Hostel">
                                            </span>
                                        </div>

                                    </div>

                                </section>

                                <!-- Tab 2 -->
                                <section id="Fee Collection Details " class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Expected">Expected</label>
                                                <input type="text" id="Expected" name="Expected"
                                                    placeholder="₹ 15,65,852">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="col col-sm-6lected">col col-sm-6lected</label>
                                                <input type="text" id="col col-sm-6lected" name="col col-sm-6lected"
                                                    placeholder="₹ 3,91,463">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Remaining">Remaining</label>
                                                <input type="text" id="Remaining" name="Remaining"
                                                    placeholder="₹ 7,51,608">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Others">Others</label>
                                                <input type="text" id="Others" name="Others"
                                                    placeholder="₹ 1,65,852">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Overdue">Overdue</label>
                                                <input type="text" id="Overdue" name="Overdue"
                                                    placeholder="₹ 4,85,414">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                        </div>

                                    </div>

                                </section>

                                <!-- Tab 3 -->
                                <section id="Assests" class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Ceiling Fan">Ceiling Fan</label>
                                                <input type="text" id="Ceiling Fan" name="Ceiling Fan"
                                                    placeholder="33">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="CenterTable">Center Table</label>
                                                <input type="text" id="CenterTable" name="CenterTable"
                                                    placeholder="12">
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Chairs">Chairs</label>
                                                <input type="text" id="Chairs" name="Chairs" placeholder="51">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Refrigerator">Refrigerator</label>
                                                <input type="text" id="Refrigerator" name="Refrigerator"
                                                    placeholder="65">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Kettle">Kettle</label>
                                                <input type="text" id="Kettle" name="Kettle" placeholder="85">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                        </div>

                                </section>
                            </div>
                        </div>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                        </button>
                        <button type="button" class="blue"> Send to Admin </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* General Container */
        .user-management-container {
            padding: 10px;
            font-family: var(--ff-manrope);
            background-color: #F5F5F6;
        }

        /* Header Bar */
        .header-bar {
            /* background: var(--primary-clr);
                                                                                                                               */
            /* background-image: url('backend/img/profile/user-management-bg.png'); */
            background-size: cover;
            background-position: center;
            color: white;
            padding: 25px 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: start;
            height: 200px;
        }

        .header-bar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section-header {
            display: flex;
            flex-direction: column;
            gap: 0.50px;
        }

        .section-header p a {
            text-decoration: none;
            color: white;
            font-size: 13px;
            letter-spacing: .10px;
        }

        .section-header h3 {
            font-weight: bolder;
            margin-left: 6px;
        }

        .search-section {
            display: flex;
            gap: 0.50px;
            position: relative;
        }

        .search-section input {
            padding: 15px 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .search-section input:focus {
            outline: 0;
        }

        .search-section img {
            width: 50px;
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 0;
            padding: 15px;
        }

        /* Add Member */
        .add-member {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            gap: 10px;
            position: relative;
            top: -40px;
            border-radius: 15px;
            border: 1.5px solid #FFF;
            background: linear-gradient(113deg, rgba(255, 255, 255, 0.82) 0%, rgba(255, 255, 255, 0.80) 110.84%);
            box-shadow: 0 2px 5.5px 0 rgba(0, 0, 0, 0.02);
            backdrop-filter: blur(10.499999046325684px);
            width: 95%;
            margin: 0 auto;
        }

        .add-member .user-details {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .add-member .image-div {
            position: relative;
        }

        .add-member .image-div .icon-div {
            position: absolute;
            bottom: -15px;
            right: 0.20px;
            transform: translate(50%, -50%);
            background-color: white;
            border-radius: 0.50px;
            padding: 6px;
            cursor: pointer;
        }

        .add-member .image-div .icon-div img {
            width: 12px;
        }

        .add-member .profile-photo {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }

        .add-member .info h5 {
            margin: 0;
            color: #2D3748;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
        }

        .add-member .info p {
            margin: 0;
            color: #718096;
            font-size: 10px;
            font-weight: 700;
        }

        .add-member .add-member-btn {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .add-member .add-member-btn .active-btn {
            padding: 15px 10px;
            background: #149D52;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-member .primary-btn {
            margin-left: auto;
            padding: 15px 10px;
            background: var(--primary-clr);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-member .primary-btn span img {
            width: 16px;
        }

        .profile-info-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
            font-family: 'Inter', sans-serif;
        }

        .profile-info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-info-header h3,
        .info-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
        }

        .edit-btn {
            padding: 0.50px 1.250px;
            background-color: #1a202c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 9px;
        }

        .profile-grid,
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .profile-field {
            display: flex;
            flex-direction: column;
        }

        .profile-field label,
        .field label {
            font-size: 14px;
            line-height: 140%;
            font-family: Helvetica;
            color: #2d3748;
            text-align: left;
            margin-bottom: .50px;
        }

        .profile-field input,
        .field input {
            border: none;
            width: 100%;
            border-radius: 6.77px;
            background-color: #F5F7FA;
            height: 49px;
            overflow: hidden;
            text-align: left;
            font-size: 14px;
            color: #a0aec0;
            font-family: Helvetica;
            padding: 10px;
        }

        .profile-field input:read-only {
            background-color: #edf2f7;
            cursor: not-allowed;
        }


        .info-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
            font-family: 'Inter', sans-serif;
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }


        .info-section h4 {
            font-size: 15px;
            color: #4a5568;
            margin-top: 20px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .edit-btn {
            padding: 15px 20px;
            background-color: var(--primary-clr);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }



        .field {
            display: flex;
            flex-direction: column;
        }


        .field input:read-only {
            background-color: #edf2f7;
            cursor: not-allowed;
        }

        .attendance-leave-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            font-family: 'Inter', sans-serif;
        }

        .section-title {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            color: #2d3748;
            font-weight: bold;
        }

        .table-container {
            margin-bottom: 25px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .table-header h4 {
            font-size: 10px;
            color: #2d3748;
        }

        .btn-group {
            display: flex;
            gap: 0.50px;
        }

        .btn-primary {
            background-color: var(--primary-clr);
            color: #fff;
            padding: 15px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }


        @media screen and (max-width: 800px) and (min-width: 200px) {

            .profile-grid,
            .info-grid {
                grid-template-columns: repeat(1, 1fr);
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.50px;
            }

            .btn-primary {
                font-size: 0.750px;
            }
        }

        @media (max-width: 600px) {

            .search-section,
            .active-btn,
            .add-member-btn {
                display: none;
            }
        }

        @media screen and (max-width: 480px) {
            .header-bar {
                height: 130px;
            }

        }


        .crop-container {
            width: 260px;
            height: 260px;
            margin: auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #e5e5e5;
        }

        .crop-container img {
            width: 100%;
            display: block;
        }

        .crop-container {
            width: 260px;
            height: 260px;
            margin: auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #e5e5e5;
        }

        .crop-container img {
            width: 100%;
            display: block;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
@endpush

@push('scripts')
    <script>
        $(function() {

            /* =====================================================
             * CORE APP NAMESPACE
             * ===================================================== */
            const ProfileApp = {};

            /* =====================================================
             * UTILS
             * ===================================================== */
            ProfileApp.Utils = {
                token: localStorage.getItem("token"),

                headers() {
                    return {
                        Authorization: `Bearer ${this.token}`,
                        Accept: "application/json"
                    };
                },

                alert(type, message, timer = 2000) {
                    Swal.fire({
                        icon: type,
                        title: type === "success" ? "Success" : "Error",
                        text: message,
                        timer,
                        showConfirmButton: false
                    });
                }
            };

            /* =====================================================
             * VALIDATORS
             * ===================================================== */
            ProfileApp.Validators = {
                required: v => v && v.trim().length > 0,
                email: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
                mobile: v => /^[6-9]\d{9}$/.test(v),
                password: v => v.length >= 6,
                confirmPassword: (p, c) => p === c
            };

            /* =====================================================
             * CACHE
             * ===================================================== */
            ProfileApp.Cache = {
                get() {
                    return JSON.parse(localStorage.getItem("profileData"));
                },
                set(data) {
                    localStorage.setItem("profileData", JSON.stringify(data));
                }
            };

            /* =====================================================
             * API SERVICES
             * ===================================================== */
            ProfileApp.Services = {

                loadProfile(successCb) {
                    $.ajax({
                        url: "/api/profile",
                        type: "GET",
                        headers: ProfileApp.Utils.headers(),
                        success: res => {
                            if (res.success) {
                                ProfileApp.Cache.set(res.data);
                                successCb(res.data);
                            } else {
                                ProfileApp.Utils.alert("error", res.message);
                            }
                        },
                        error: () => ProfileApp.Utils.alert("error", "Failed to load profile")
                    });
                },

                updateProfile(payload, successCb) {
                    $.ajax({
                        url: "/api/profile",
                        method: "PUT",
                        headers: ProfileApp.Utils.headers(),
                        data: payload,
                        success: res => {
                            if (res.success) {
                                ProfileApp.Cache.set(res.data);
                                successCb(res.data);
                                ProfileApp.Utils.alert("success", "Profile updated successfully");
                            } else {
                                ProfileApp.Utils.alert("error", res.message || "Update failed");
                            }
                        },
                        error: () => ProfileApp.Utils.alert("error", "Update error")
                    });
                }
            };

            /* =====================================================
             * PERSONAL INFO MODAL
             * ===================================================== */

            ProfileApp.Header = {

                populate(user) {

                    // Build image URL or fallback 
                    const imageUrl = user?.profile_image ? 
                    `/storage/users/${user.profile_image}` : 
                    `/backend/img/profile/user-management-dp.png`; 
                    // Update image in DOM 
                    
                    $("#profileImage").attr("src", imageUrl);

                    const scholar = user.resident?.scholar_no ?
                        ` (Scholar - ${user.resident.scholar_no})` :
                        "";

                    $("#header_name").text(`${user.name}${scholar}`);
                    $("#header_email").text(user.email ?? "");
                }
            };


            /* =====================================================
             * PERSONAL INFO MODAL
             * ===================================================== */
            ProfileApp.PersonalInfo = {


                populate(user) {
                    $("#view_user_type").val(user.type ?? "Regular");
                    $("#view_name").val(user.name ?? "");
                    $("#view_email").val(user.email ?? "");
                    $("#view_mobile").val(user.resident?.number ?? "");
                    $("#view_gender").val(user.gender ?? "");
                    $("#view_scholar_no").val(user.resident?.scholar_no ?? "");
                    $("#view_address").val(user.address ?? "");
                    $("#profileImage").attr("src", "/storage/users/" + res.data.profile_image);

                    $("#view_father_name").val(user.resident.fathers_name ?? "Regular");
                    $("#view_mother_name").val(user.resident.mothers_name ?? "");
                    $("#primary_contact").val(user.resident?.parent_no ?? "");
                    $("#emergency_contact").val(user.resident?.emergency_no ?? "");

                    $("#guardian_name").val(user.resident?.guardian_name ?? "");
                    $("#guardian_contact").val(user.resident?.guardian_contact ?? "");
                    $("#guardian_relation").val(user.resident?.guardian_relation ?? "");

                    $("#name").val(user.name ?? "");
                    $("#email").val(user.email ?? "");
                    $("#mobile").val(user.resident?.number ?? "");
                    $("#gender").val(user.gender ?? "");
                },

                clearErrors() {
                    $(".form-control").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                },

                error(field, msg) {
                    $(field).addClass("is-invalid")
                        .after(`<div class="invalid-feedback">${msg}</div>`);
                },

                validate() {
                    this.clearErrors();
                    let ok = true;

                    if (!ProfileApp.Validators.required($("#name").val())) {
                        this.error("#name", "Name required");
                        ok = false;
                    }

                    if (!ProfileApp.Validators.email($("#email").val())) {
                        this.error("#email", "Invalid email");
                        ok = false;
                    }

                    if (!ProfileApp.Validators.mobile($("#mobile").val())) {
                        this.error("#mobile", "Invalid mobile");
                        ok = false;
                    }

                    const p = $("#password").val();
                    const c = $("#confirm_password").val();

                    if (p || c) {
                        if (!ProfileApp.Validators.password(p)) {
                            this.error("#password", "Min 6 chars");
                            ok = false;
                        }
                        if (!ProfileApp.Validators.confirmPassword(p, c)) {
                            this.error("#confirm_password", "Mismatch");
                            ok = false;
                        }
                    }

                    return ok;
                },

                save() {
                    if (!this.validate()) return;

                    const data = {
                        name: $("#name").val(),
                        email: $("#email").val(),
                        gender: $("#gender").val(),
                        mobile: $("#mobile").val()
                    };

                    if ($("#password").val()) {
                        data.password = $("#password").val();
                        data.password_confirmation = $("#confirm_password").val();
                    }

                    ProfileApp.Services.updateProfile(data, user => {
                        this.populate(user);
                        this.toggleEdit(false);
                        $("#password,#confirm_password").val("");
                    });
                },

                toggleEdit(enable) {
                    $("#name,#email,#gender,#mobile,#password,#confirm_password")
                        .prop("readonly", !enable)
                        .prop("disabled", !enable);

                    $("#editPersonalBtn").toggleClass("d-none", enable);
                    $("#savePersonalBtn,#cancelPersonalBtn").toggleClass("d-none", !enable);
                },

                bind() {
                    $("#editPersonalBtn").click(() => this.toggleEdit(true));
                    $("#savePersonalBtn").click(() => this.save());
                    $("#cancelPersonalBtn").click(() => {
                        this.populate(ProfileApp.Cache.get());
                        this.toggleEdit(false);
                        this.clearErrors();
                    });
                }
            };
            // console.log('profile', ProfileApp.PersonalInfo);
            /* =====================================================
             * HOSTEL / OTHER INFO MODAL
             * ===================================================== */
            ProfileApp.HostelInfo = {

                populate(user) {
                    if (!user.resident) return;

                    $("#view_hostel_name").val(user.resident.building_name ?? "");
                    $("#view_room_number").val(user.resident.room_number ?? "");
                    $("#view_bed_number").val(user.resident.bed_number ?? "");
                    $("#view_joining_date").val(user.resident.joining_date ?? "");

                    $("#scholar_no").val(user.resident.scholar_no ?? "");
                    $("#fathers_name").val(user.resident.fathers_name ?? "");
                    $("#mothers_name").val(user.resident.mothers_name ?? "");
                    $("#guardian_no").val(user.resident.guardian_no ?? "");
                    $("#bed_room_building").val(
                        `${user.resident.bed_number}/${user.resident.room_number}/${user.resident.building_name}`
                    );
                }
            };

            /* =====================================================
             * INIT
             * ===================================================== */
            const cached = ProfileApp.Cache.get();
            if (cached) {
                ProfileApp.Header.populate(cached);
                ProfileApp.PersonalInfo.populate(cached);
                ProfileApp.HostelInfo.populate(cached);
            }

            ProfileApp.Services.loadProfile(user => {
                ProfileApp.Header.populate(user);
                ProfileApp.PersonalInfo.populate(user);
                ProfileApp.HostelInfo.populate(user);
            });

            ProfileApp.PersonalInfo.bind();

            /* =====================================================
             * PROFILE EDIT MODAL
             * ===================================================== */
            ProfileApp.ProfileModal = {

                populate(user) {
                    $("#name").val(user.name ?? "");
                    $("#email").val(user.email ?? "");
                    $("#mobile").val(user.resident?.number ?? "");
                    $("#alternate_mobile").val(user.resident?.alternate_mobile ?? "");
                    $("#address").val(user.address ?? "");
                    $("#reason").val("");
                    $(".invalid-feedback").remove();
                    $(".is-invalid").removeClass("is-invalid");
                },

                validate() {
                    let ok = true;
                    $(".invalid-feedback").remove();
                    $(".is-invalid").removeClass("is-invalid");

                    const fields = [{
                            id: "#name",
                            rule: "required",
                            msg: "Name is required"
                        },
                        {
                            id: "#email",
                            rule: "email",
                            msg: "Valid email is required"
                        },
                        {
                            id: "#mobile",
                            rule: "mobile",
                            msg: "Valid mobile number required"
                        },
                        {
                            id: "#alternate_mobile",
                            rule: "mobile",
                            msg: "Valid alternate mobile required"
                        },
                        {
                            id: "#reason",
                            rule: "required",
                            msg: "Reason is required"
                        }
                    ];

                    fields.forEach(f => {
                        let value = $(f.id).val();
                        let valid = ProfileApp.Validators[f.rule](value);

                        if (!valid) {
                            ok = false;
                            $(f.id)
                                .addClass("is-invalid")
                                .after(`<div class="invalid-feedback">${f.msg}</div>`);
                        }
                    });

                    // 🔐 OPTIONAL PASSWORD VALIDATION
                    const password = $("#password").val();
                    const confirm = $("#password_confirmation").val();

                    if (password || confirm) {

                        if (!ProfileApp.Validators.password(password)) {
                            ok = false;
                            $("#password")
                                .addClass("is-invalid")
                                .after(
                                    `<div class="invalid-feedback">Password must be at least 6 characters</div>`
                                );
                        }

                        if (!ProfileApp.Validators.confirmPassword(password, confirm)) {
                            ok = false;
                            $("#password_confirmation")
                                .addClass("is-invalid")
                                .after(`<div class="invalid-feedback">Passwords do not match</div>`);
                        }
                    }

                    return ok;
                },

                submit() {

                    if (!this.validate()) return;

                    const fd = new FormData();
                    fd.append("_method", "PUT"); // 🔑 IMPORTANT
                    fd.append("name", $("#name").val());
                    fd.append("email", $("#email").val());
                    fd.append("mobile", $("#mobile").val());
                    fd.append("alternate_mobile", $("#alternate_mobile").val());
                    fd.append("address", $("#address").val());
                    fd.append("reason", $("#reason").val());

                    const file = $("#document")[0]?.files[0];
                    if (file) fd.append("document", file);

                    const password = $("#password").val();

                    if (password) {
                        fd.append("password", password);
                        fd.append("password_confirmation", $("#password_confirmation").val());
                    }


                    $.ajax({
                        url: "/api/profile", // same existing route
                        method: "POST", // Laravel PUT spoofing
                        headers: {
                            Authorization: `Bearer ${ProfileApp.Utils.token}`
                        },
                        data: fd,
                        processData: false,
                        contentType: false,

                        beforeSend: () => $("#submitProfileEdit").prop("disabled", true),

                        success: res => {
                            $("#submitProfileEdit").prop("disabled", false);

                            ProfileApp.Cache.set(res.data);
                            ProfileApp.Header.populate(res.data);
                            ProfileApp.PersonalInfo.populate(res.data);

                            $("#password, #password_confirmation").val("");

                            $("#ProfileInformation").modal("hide");
                            ProfileApp.Utils.alert("success", "Profile updated successfully");
                        },

                        error: xhr => {
                            $("#submitProfileEdit").prop("disabled", false);

                            if (xhr.status === 422) {
                                this.injectBackendErrors(xhr.responseJSON.errors);
                            } else {
                                ProfileApp.Utils.alert("error", "Submission failed");
                            }
                        }
                    });
                },

                injectBackendErrors(errors) {
                    Object.keys(errors).forEach(key => {
                        const field = $(`#${key}`);
                        if (field.length) {
                            field
                                .addClass("is-invalid")
                                .after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                        }
                    });
                }
            };



            /* =====================================================
             * MODAL EVENTS
             * ===================================================== */
            $('#ProfileInformation').on('show.bs.modal', function() {
                const user = ProfileApp.Cache.get();
                if (user) ProfileApp.ProfileModal.populate(user);
            });

            $("#submitProfileModal").on("click", function() {
                ProfileApp.ProfileModal.submit();
            });



            /// Profile image Change

            document.getElementById('photoInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    document.getElementById('previewImage').src = URL.createObjectURL(file);
                }
            });

            let cropper;
            let croppedBlob;

            // Load image into cropper
            document.getElementById('photoInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const img = document.getElementById('cropImage');
                img.src = URL.createObjectURL(file);

                if (cropper) cropper.destroy();

                cropper = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    background: false,
                    responsive: true,
                    zoomable: true,
                    minCropBoxWidth: 150,
                    minCropBoxHeight: 150
                });
            });

            // Crop button → show preview
            document.getElementById('cropBtn').addEventListener('click', function() {
                if (!cropper) return;

                cropper.getCroppedCanvas({
                    width: 400,
                    height: 400
                }).toBlob((blob) => {
                    croppedBlob = blob;
                    document.getElementById('finalPreview').src = URL.createObjectURL(blob);

                    // Switch UI to preview mode
                    document.getElementById('cropSection').classList.add('d-none');
                    document.getElementById('previewSection').classList.remove('d-none');

                    document.getElementById('cropBtn').classList.add('d-none');
                    document.getElementById('saveBtn').classList.remove('d-none');
                    document.getElementById('backBtn').classList.remove('d-none');
                });
            });

            // Back button → return to crop mode
            document.getElementById('backBtn').addEventListener('click', function() {
                document.getElementById('cropSection').classList.remove('d-none');
                document.getElementById('previewSection').classList.add('d-none');

                document.getElementById('cropBtn').classList.remove('d-none');
                document.getElementById('saveBtn').classList.add('d-none');
                document.getElementById('backBtn').classList.add('d-none');
            });

            document.getElementById('saveBtn').addEventListener('click', function() {

                const fd = new FormData();
                fd.append("photo", croppedBlob, "profile.jpg");

                $.ajax({
                    url: "{{ route('profile_image.update') }}", // your API endpoint
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${ProfileApp.Utils.token}`
                    },
                    data: fd,
                    processData: false,
                    contentType: false,

                    beforeSend: () => {
                        $("#saveBtn").prop("disabled", true);
                    },

                    success: res => {
                        $("#saveBtn").prop("disabled", false);

                        // Update cached profile data
                        ProfileApp.Cache.set(res.data);

                        // Update UI components
                        ProfileApp.Header.populate(res.data);
                        ProfileApp.PersonalInfo.populate(res.data);

                        // Close modal or switch UI
                        $("#PhotoCropModal").modal("hide");

                        ProfileApp.Utils.alert("success", "Profile photo updated successfully");
                    },

                    error: xhr => {
                        $("#saveBtn").prop("disabled", false);

                        if (xhr.status === 422) {
                            // Validation errors from backend
                            this.injectBackendErrors(xhr.responseJSON.errors);
                        } else {
                            ProfileApp.Utils.alert("error", "Photo upload failed");
                        }
                    }
                });
            });

        });
    </script>

    <script>
        // let cropper;
        // let croppedBlob;

        // // Load image into cropper
        // document.getElementById('photoInput').addEventListener('change', function(event) {
        //     const file = event.target.files[0];
        //     if (!file) return;

        //     const img = document.getElementById('cropImage');
        //     img.src = URL.createObjectURL(file);

        //     if (cropper) cropper.destroy();

        //     cropper = new Cropper(img, {
        //         aspectRatio: 1,
        //         viewMode: 1,
        //         dragMode: 'move',
        //         autoCropArea: 1,
        //         background: false,
        //         responsive: true,
        //         zoomable: true,
        //         minCropBoxWidth: 150,
        //         minCropBoxHeight: 150
        //     });
        // });

        // // Crop button → show preview
        // document.getElementById('cropBtn').addEventListener('click', function() {
        //     if (!cropper) return;

        //     cropper.getCroppedCanvas({
        //         width: 400,
        //         height: 400
        //     }).toBlob((blob) => {
        //         croppedBlob = blob;
        //         document.getElementById('finalPreview').src = URL.createObjectURL(blob);

        //         // Switch UI to preview mode
        //         document.getElementById('cropSection').classList.add('d-none');
        //         document.getElementById('previewSection').classList.remove('d-none');

        //         document.getElementById('cropBtn').classList.add('d-none');
        //         document.getElementById('saveBtn').classList.remove('d-none');
        //         document.getElementById('backBtn').classList.remove('d-none');
        //     });
        // });

        // // Back button → return to crop mode
        // document.getElementById('backBtn').addEventListener('click', function() {
        //     document.getElementById('cropSection').classList.remove('d-none');
        //     document.getElementById('previewSection').classList.add('d-none');

        //     document.getElementById('cropBtn').classList.remove('d-none');
        //     document.getElementById('saveBtn').classList.add('d-none');
        //     document.getElementById('backBtn').classList.add('d-none');
        // });

        // document.getElementById('saveBtn').addEventListener('click', function() {

        //     const fd = new FormData();
        //     fd.append("photo", croppedBlob, "profile.jpg");

        //     $.ajax({
        //         url: "{{ route('profile_image.update') }}", // your API endpoint
        //         method: "POST",
        //         headers: {
        //             Authorization: `Bearer ${ProfileApp.Utils.token}`
        //         },
        //         data: fd,
        //         processData: false,
        //         contentType: false,

        //         beforeSend: () => {
        //             $("#saveBtn").prop("disabled", true);
        //         },

        //         success: res => {
        //             $("#saveBtn").prop("disabled", false);

        //             // Update cached profile data
        //             ProfileApp.Cache.set(res.data);

        //             // Update UI components
        //             ProfileApp.Header.populate(res.data);
        //             ProfileApp.PersonalInfo.populate(res.data);

        //             // Close modal or switch UI
        //             $("#PhotoCropModal").modal("hide");

        //             ProfileApp.Utils.alert("success", "Profile photo updated successfully");
        //         },

        //         error: xhr => {
        //             $("#saveBtn").prop("disabled", false);

        //             if (xhr.status === 422) {
        //                 // Validation errors from backend
        //                 this.injectBackendErrors(xhr.responseJSON.errors);
        //             } else {
        //                 ProfileApp.Utils.alert("error", "Photo upload failed");
        //             }
        //         }
        //     });
        // });
    </script>

    {{-- // Save button → upload cropped image
        // document.getElementById('saveBtn').addEventListener('click', function() {
        //     const formData = new FormData();
        //     formData.append('photo', croppedBlob, 'profile.jpg');

       //  //     fetch("{{ route('profile.update_photo') }}", {
        //         method: 'POST',
        //         body: formData
        //     }).then(() => location.reload());
        // }); --}}
@endpush
