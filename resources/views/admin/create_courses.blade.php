@extends('admin.layout')

@section('content')

    <section class="user-management-container">
        <!-- Header section -->
        <div class="header-bar">
            <div><a>Manage Staff</a> </div>
            <div class="search-section">
                <input type="text" placeholder="Type..." />
                <img src="{{ asset('backend/img/User Management/Icon.png') }}" alt="">
            </div>

        </div>

        <!-- Add member block -->
        <div class="add-member">
            <img src="{{ asset('backend/img/User Management/user-management-dp.png') }}" alt="Add Member" />
            <div class="info">
                <h5>Total Satff - 5,000</h5>
            </div>
            <button class="primary-btn add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddMember">Add
                Member</button>
        </div>

        <!-- Member Cards -->
        <h5 class="section-title">Members Profile</h5>
        <div class="cards-grid">
            <!-- Card 1 -->
            <div class="user-card">
                <div class="card-img-wrapper">
                    <img src="{{ asset('backend/img/User Management/user-management-dp.png') }}" alt="User" />
                    <span class="badge green">New Active</span>
                </div>
                <div class="card-body">
                    <p class="role">Management</p>
                    <h6>Rajat Pradhan</h6>
                    <p class="desc">
                        As Uber works through a huge amount of internal management
                        turmoil.
                    </p>
                    <div class="card-actions">
                        <button class="outline-btn"><a href="{{ route('admin.create_building') }}">View User</a></button>

                        <div class="dropdown">
                            <button class="dropdown-toggle-staff" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
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
            </div>

            <!-- Card 2 -->
            <div class="user-card">
                <div class="card-img-wrapper">
                    <img src="{{ asset('backend/img/User Management/user-management-dp.png') }}" alt="User" />
                    <span class="badge red">User Ban</span>
                </div>
                <div class="card-body">
                    <p class="role">Sales</p>
                    <h6>Amresh Rajput</h6>
                    <p class="desc">
                        As Uber works through a huge amount of internal management
                        turmoil.
                    </p>
                    <div class="card-actions">
                        <button class="outline-btn"><a href="{{ route('admin.create_building') }}">View User</a></button>
                        <div class="dropdown">
                            <button class="dropdown-toggle-staff" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
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
            </div>

            <!-- Card 3 -->
            <div class="user-card">
                <div class="card-img-wrapper">
                    <img src="{{ asset('backend/img/User Management/user-management-dp.png') }}" alt="User" />
                    <span class="badge green">New Active</span>
                </div>
                <div class="card-body">
                    <p class="role">Mess Management</p>
                    <h6>Unnati</h6>
                    <p class="desc">
                        As Uber works through a huge amount of internal management
                        turmoil.
                    </p>
                    <div class="card-actions">
                        <button class="outline-btn"><a href="{{ route('admin.create_building') }}">View User</a></button>
                        <div class="dropdown">
                            <button class="dropdown-toggle-staff" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
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
            </div>

            <!-- New User -->
            <div class="new-project" data-bs-toggle="modal" data-bs-target="#AddMember">
                <div class="new-project-div">
                    <h2>+</h2>
                    <p>Create a New User</p>
                </div>
            </div>
        </div>
    </section>


    <!-- All Popup -->

    <!-- add user popup  -->
    <div class="modal fade" id="AddMember" tabindex="-1" aria-labelledby="AddMemberLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add User</div>
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
                        <button type="button" class="blue" data-bs-toggle="modal" data-bs-target="#Approvepopup"> Create
                            User
                        </button>
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