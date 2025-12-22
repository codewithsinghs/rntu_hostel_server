@extends('admin.layout')

@section('content')

    <section class="user-management-container">
        <!-- Header section -->
        <div class="header-bar">
            <div><a href="{{ route('admin.create_courses') }}">Manage Users / </a> <a href="{{ route('admin.create_building') }}">User Details</a></div>
            <div class="search-section">
                <input type="text" placeholder="Type..." />
                <img src="{{ asset('backend/img/User Management/Icon.png') }}" alt="" />
            </div>
            <!-- <img
                  src="../../public/dashboard/manage-user-bg.png"
                  alt="Add Member"
                /> -->
        </div>

        <!-- Add member block -->
        <div class="add-member">
            <div class="user-details">
                <div class="image-div" data-bs-toggle="modal" data-bs-target="#editUser">
                    <img src="{{ asset('backend/img/User Details/user-details-profile.png') }}" alt="Add Member" class="profile-photo" />
                    <div class="icon-div">
                        <img src="{{ asset('backend/img/User Details/edit.png') }}" alt="" />
                    </div>
                </div>
                <div class="info">
                    <h5>Rajat Pradhan (Manager)</h5>
                    <p>prajat917@gmail.com</p>
                </div>
            </div>
            <div class="add-member-btn">
                <button class="active-btn">User Active</button>
                <!-- <button class="primary-btn">Action</button> -->
                <div class="dropdown">
                    <button class="dropdown-toggle-staff" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Action
                    </button>
                    <ul class="dropdown-menu custom-user-ul">
                        <li>
                            <a class="dropdown-item custom-user-li" type="button" data-bs-toggle="modal"
                                data-bs-target="#EditUser">Edit</a>
                        </li>
                        <li>
                            <a class="dropdown-item custom-user-li">Disable</a>
                        </li>
                        <li>
                            <a class="dropdown-item custom-user-li" type="button" data-bs-toggle="modal"
                                data-bs-target="#Rejectpopup">Delete</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Settings & Profile Info Section -->
    <section class="settings-profile-wrapper">
        <!-- Portal Settings -->
        <div class="portal-settings">
            <div class="section-header">
                <h5>Portal Settings</h5>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#PortalSettings">Edit</button>
            </div>
            <div class="portal-settings-container">

                <div class="portal-setting">
                    <div class="switch-container">
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Email me when someone follows me</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Email me when someone answers on my post</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Email me when someone mentions me</span>
                        </div>
                    </div>
                </div>

                <div class="portal-setting">
                    <div class="switch-container">
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>New launches and projects</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Monthly product updates</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Subscribe to newsletter</span>
                        </div>
                    </div>
                </div>

                <div class="portal-setting">
                    <div class="switch-container">
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Email me when someone follows me</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Email me when someone answers on my post</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Email me when someone mentions me</span>
                        </div>
                    </div>
                </div>

                <div class="portal-setting">
                    <div class="switch-container">
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>New launches and projects</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Monthly product updates</span>
                        </div>
                        <div class="switch-container-label">
                            <label class="switch">
                                <input type="checkbox" />
                                <span class="slider round"></span>
                            </label>
                            <span>Subscribe to newsletter</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="profile-info">
            <div class="section-header">
                <h5>Profile Information</h5>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#ediProfile">Edit</button>
            </div>
            <div class="profile-details">
                <div class="info-line">
                    <strong>Full Name:</strong><span>Rajat Pradhan</span>
                    <strong>Mobile:</strong><span>+91 7024393158</span>
                    <strong>Email:</strong><span>prajat917@gmail.com</span>
                    <strong>Joining Date:</strong><span>25/05/2025</span>
                    <strong>Role:</strong><span>Management</span>
                </div>
                <p class="bio">
                    Hi, I’m Rajat Pradhan, Decisions: If you can’t decide, the answer is no. If two equally difficult paths,
                    choose the one more painful in the short term (pain avoidance is creating an illusion of equality).
                </p>
            </div>
        </div>
    </section>

    <!-- Edit Profile  -->
    <div class="modal fade" id="ediProfile" tabindex="-1" aria-labelledby="ediProfileLabel" aria-hidden="true">
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

                        <span class="input-set">
                            <label for="Name">Name</label>
                            <input type="text" id="Name" name="Name" placeholder="Enter Name">
                        </span>
                        <span class="input-set">
                            <label for="MobileNumber">Mobile</label>
                            <input type="number" id="MobileNumber" name="MobileNumber" placeholder="Enter Mobile Number">
                        </span>
                        <span class="input-set">
                            <label for="Email">Email</label>
                            <input type="email" id="Email" name="Email" placeholder="Enter Email">
                        </span>
                        <span class="input-set">
                            <label for="GeneratePassword">Generate Password</label>
                            <input type="password" id="GeneratePassword" name="GeneratePassword"
                                placeholder="Generate Password">
                        </span>
                        <span class="input-set">
                            <label for="AssignRole">Assign Role</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Role</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="JoiningDate">Joining Date</label>
                            <input type="date" id="JoiningDate" name="JoiningDate" placeholder="DD/MM/YYYY">
                        </span>

                    </div>

                    <div class="reason">
                        <label for="Comment">Comment</label>
                        <textarea type="text">As Uber works through a huge amount of internal management turmoil.</textarea>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                        <button type="button" class="blue"> Save Edit </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Portal ettings  -->
    <div class="modal fade" id="PortalSettings" tabindex="-1" aria-labelledby="PortalSettingsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Portal Settings</div>
                    </div>
                    <div class="middle"></div>
                    <div class="Assign-Permissions">
                        <div class="portal-settings-container">

                            <div class="portal-setting">
                                <!-- <h2 class="label">ACCOUNT</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone follows me</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone answers on my post</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone mentions me</span>
                                    </div>
                                </div>
                            </div>

                            <div class="portal-setting">
                                <!-- <h2 class="label">APPLICATION</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>New launches and projects</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Monthly product updates</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Subscribe to newsletter</span>
                                    </div>
                                </div>
                            </div>

                            <div class="portal-setting">
                                <!-- <h2 class="label">APPLICATION</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>New launches and projects</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Monthly product updates</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Subscribe to newsletter</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                        <button type="button" class="blue"> Save Edit </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit popup  -->
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit User</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="Name">Name</label>
                            <input type="text" id="Name" name="Name" placeholder="Enter Name">
                        </span>
                        <span class="input-set">
                            <label for="MobileNumber">Mobile</label>
                            <input type="number" id="MobileNumber" name="MobileNumber" placeholder="Enter Mobile Number">
                        </span>
                        <span class="input-set">
                            <label for="Email">Email</label>
                            <input type="email" id="Email" name="Email" placeholder="Enter Email">
                        </span>
                        <span class="input-set">
                            <label for="GeneratePassword">Generate Password</label>
                            <input type="password" id="GeneratePassword" name="GeneratePassword"
                                placeholder="Generate Password">
                        </span>
                        <span class="input-set">
                            <label for="AssignRole">Assign Role</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Role</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="JoiningDate">Joining Date</label>
                            <input type="date" id="JoiningDate" name="JoiningDate" placeholder="DD/MM/YYYY">
                        </span>

                    </div>

                    <div class="reason">
                        <label for="Comment">Comment</label>
                        <textarea type="text">As Uber works through a huge amount of internal management turmoil.</textarea>
                    </div>

                    <div class="Assign-Permissions">
                        <h2>Assign Permissions</h2>
                        <div class="portal-settings-container">

                            <div class="portal-setting">
                                <!-- <h2 class="label">ACCOUNT</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone follows me</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone answers on my post</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone mentions me</span>
                                    </div>
                                </div>
                            </div>

                            <div class="portal-setting">
                                <!-- <h2 class="label">APPLICATION</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>New launches and projects</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Monthly product updates</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Subscribe to newsletter</span>
                                    </div>
                                </div>
                            </div>

                            <div class="portal-setting">
                                <!-- <h2 class="label">APPLICATION</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>New launches and projects</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Monthly product updates</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Subscribe to newsletter</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                        <button type="button" class="blue"> Create User </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit user popup  -->
    <div class="modal fade" id="EditUser" tabindex="-1" aria-labelledby="EditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit User</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="Name">Name</label>
                            <input type="text" id="Name" name="Name" placeholder="Enter Name">
                        </span>
                        <span class="input-set">
                            <label for="MobileNumber">Mobile</label>
                            <input type="number" id="MobileNumber" name="MobileNumber" placeholder="Enter Mobile Number">
                        </span>
                        <span class="input-set">
                            <label for="Email">Email</label>
                            <input type="email" id="Email" name="Email" placeholder="Enter Email">
                        </span>
                        <span class="input-set">
                            <label for="GeneratePassword">Generate Password</label>
                            <input type="password" id="GeneratePassword" name="GeneratePassword"
                                placeholder="Generate Password">
                        </span>
                        <span class="input-set">
                            <label for="AssignRole">Assign Role</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Role</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="JoiningDate">Joining Date</label>
                            <input type="date" id="JoiningDate" name="JoiningDate" placeholder="DD/MM/YYYY">
                        </span>

                    </div>

                    <div class="reason">
                        <label for="Comment">Comment</label>
                        <textarea type="text">As Uber works through a huge amount of internal management turmoil.</textarea>
                    </div>

                    <div class="Assign-Permissions">
                        <h2>Assign Permissions</h2>
                        <div class="portal-settings-container">

                            <div class="portal-setting">
                                <!-- <h2 class="label">ACCOUNT</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone follows me</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone answers on my post</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Email me when someone mentions me</span>
                                    </div>
                                </div>
                            </div>

                            <div class="portal-setting">
                                <!-- <h2 class="label">APPLICATION</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>New launches and projects</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Monthly product updates</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Subscribe to newsletter</span>
                                    </div>
                                </div>
                            </div>

                            <div class="portal-setting">
                                <!-- <h2 class="label">APPLICATION</h2> -->
                                <div class="switch-container">
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>New launches and projects</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Monthly product updates</span>
                                    </div>
                                    <div class="switch-container-label">
                                        <label class="switch">
                                            <input type="checkbox" />
                                            <span class="slider round"></span>
                                        </label>
                                        <span>Subscribe to newsletter</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                        <button type="button" class="blue" data-bs-toggle="modal" data-bs-target="#Approvepopup"> Save
                            Changes
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Approvepopup -->
    <div class="modal fade" id="Approvepopup" tabindex="-1" aria-labelledby="ApprovepopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-green">Confirm Approve</div>
                    </div>

                    <div class="middle-content">
                        <p class="green">Rejecting this will permanently show it from your system. Proceed with caution.</p>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="green"> Create </button>
                        <button type="button" class="blue" data-bs-dismiss="modal" aria-label="Close"> Go Back </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Detetepopup -->
    <div class="modal fade" id="Rejectpopup" tabindex="-1" aria-labelledby="RejectpopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-remove">Confirm Delete</div>
                    </div>

                    <div class="middle-content">
                        <p>Deleting this will permanently show it from your system. Proceed with caution.</p>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Delete </button>
                        <button type="button" class="blue" data-bs-dismiss="modal" aria-label="Close"> Go Back </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection