@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <!-- breadcrumbs -->
        <div class="breadcrumbs"><a href="">Overview</a></div>
        <!-- Popup Btn -->
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddHostel">+ Add Hostel</button>
    </div>

    <div class="accordion" id="accordionExample">

        <!-- Hostel Status Card 1 -->

        <div class="accordion-item">
            <h2 class="accordion-header" id="hostel_one_a">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#tel_one_a" aria-expanded="false" aria-controls="tel_one_a">
                    Hostel 1
                </button>
            </h2>

            <div id="tel_one_a" class="accordion-collapse collapse" aria-labelledby="hostel_one_a"
                data-bs-parent="#accordionExample" style="">

                <div class="accordion-body">

                    <div class="hostel-status-section">
                        <div class="hostel-status-btn">
                            <h3 class="hostel-status-title">Hostel Status 1</h3>

                            <div class="status-btn">
                                <label for="hostelName">Select Status</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                </select>
                            </div>

                        </div>

                        <div class="hostel-status-container">
                            <!-- Hostel Name and Progress -->
                            <div class="hostel-progress-card">
                                <div class="progress-circle">
                                    <svg>
                                        <circle cx="60" cy="60" r="50"></circle>
                                        <circle cx="60" cy="60" r="50"></circle>
                                    </svg>
                                    <div class="progress-value">50%</div>
                                </div>
                                <div class="hostel-name">Hostel - <span style="font-weight: 700;">Boys Hostel</span> </div>
                                <div class="hostel-name">Warden - <span style="font-weight: 700;">Mr. Sharma</span> </div>
                                <div class="hostel-name">Contact Info - <span style="font-weight: 700;">7024393158</span>
                                </div>
                                <div class="hostel-name"> Security - <span style="font-weight: 700;"> Mr. Ram Babu</span>
                                </div>
                            </div>

                            <!-- Fee collection -->
                            <div class="fee-details">
                                <h4>Fee collection Details</h4>
                                <ul>
                                    <li><span>Expected</span> ₹ 15,65,852</li>
                                    <li><span>collected</span> ₹ 3,91,463</li>
                                    <li><span>Remaining</span> ₹ 7,51,608</li>
                                    <li><span>Others</span> ₹ 1,65,852</li>
                                    <li><span>Overdue</span> ₹ 4,85,414</li>
                                </ul>
                            </div>

                            <!-- Assets -->
                            <div class="assets">
                                <h4>Assets</h4>
                                <ul>
                                    <li><span>Ceiling Fan</span> 50</li>
                                    <li><span>Center Table</span> 12</li>
                                    <li><span>Chairs</span> 30</li>
                                    <li><span>Refrigerator</span> 44</li>
                                    <li><span>Kettle</span> 33</li>
                                </ul>
                            </div>

                            <!-- Hostel Stats -->
                            <div class="hostel-stats">
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bed</p>
                                        <strong>550</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bathroom</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Floors</p>
                                        <strong>3</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Rooms</p>
                                        <strong>458</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Occupied</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Students</p>
                                        <strong>500</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>In Hostel</p>
                                        <strong>290</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Outside Hostel</p>
                                        <strong>210</strong>
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="hostel-actions">
                                    <button class="edit" type="button" data-bs-toggle="modal" data-bs-target="#EditHostel">
                                        Edit Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="delete" type="button" data-bs-toggle="modal"
                                        data-bs-target="#ConfirmationPopup">
                                        Delete Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="remove" type="button" data-bs-toggle="modal"
                                        data-bs-target="#RemoveRoom">Remove Room
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="add" type="button" data-bs-toggle="modal" data-bs-target="#AddRoom"> Add
                                        Room
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="hostel-status-title mt-4 mb-4">Hostel Staff Details</h3>

                        <div class="hostel-staff mt-3">

                            <!-- Administrative Staff -->
                            <div class="fee-details">
                                <h4>Administrative Staff</h4>

                                <ul>
                                    <li><span>Hostel Owner</span> Rajat Pradhan</li>
                                    <li><span>Hostel Warden</span> Rajat Pradhan</li>
                                    <li><span>Assistant Warden</span> Rajat Pradhan</li>
                                    <li><span>Office Clerk</span> Rajat Pradhan</li>
                                </ul>

                            </div>

                            <!-- Security Staff -->
                            <div class="fee-details">
                                <h4>Security Staff</h4>

                                <ul>
                                    <li><span>Security Head</span> Amresh Singh</li>
                                    <li><span>Security Guard</span> Amresh Singh</li>
                                    <li><span>Gatekeeper</span> Amresh Singh</li>
                                    <li><span>CCTV Operator</span> Amresh Singh</li>
                                </ul>

                            </div>

                            <!-- Maintenance & Housekeeping Staff -->
                            <div class="fee-details">
                                <h4>Maintenance & Housekeeping Staff</h4>

                                <ul>
                                    <li><span>Housekeeping</span> Unnati Un</li>
                                    <li><span>Electrician</span> Unnati Un</li>
                                    <li><span>Plumber</span> Unnati Un</li>
                                    <li><span>Carpenter</span> Unnati Un</li>
                                </ul>

                            </div>

                            <!-- Kitchen & Mess Staff -->
                            <div class="fee-details">
                                <h4>Kitchen & Mess Staff</h4>

                                <ul>
                                    <li><span>Mess Manager</span> Gopal Gopu</li>
                                    <li><span>Cook / Chef</span> Gopal Gopu</li>
                                    <li><span>Kitchen Helpers</span> Gopal Gopu</li>
                                    <li><span>Storekeeper</span> Gopal Gopu</li>
                                </ul>

                            </div>

                            <!-- Medical & Support Staff -->
                            <div class="fee-details">
                                <h4>Medical & Support Staff</h4>

                                <ul>
                                    <li><span>Hostel Doctor</span> John Cena</li>
                                    <li><span>Nurse </span> John Cena</li>
                                    <li><span>First-Aid</span> John Cena</li>
                                    <li><span>Doctor (visiting)</span> John Cena</li>
                                </ul>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Hostel Status Card 2 -->

        <div class="accordion-item">
            <h2 class="accordion-header" id="hostel_two_b">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#tel_two_b" aria-expanded="false" aria-controls="tel_two_b">
                    Hostel 2
                </button>
            </h2>
            <div id="tel_two_b" class="accordion-collapse collapse" aria-labelledby="hostel_two_b"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <div class="hostel-status-section">
                        <div class="hostel-status-btn">
                            <h3 class="hostel-status-title">Hostel Status 4</h3>

                            <div class="status-btn">
                                <label for="hostelName">Select Status</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                </select>
                            </div>

                        </div>

                        <div class="hostel-status-container">
                            <!-- Hostel Name and Progress -->
                            <div class="hostel-progress-card">
                                <div class="progress-circle">
                                    <svg>
                                        <circle cx="60" cy="60" r="50"></circle>
                                        <circle cx="60" cy="60" r="50"></circle>
                                    </svg>
                                    <div class="progress-value">50%</div>
                                </div>
                                <div class="hostel-name">Hostel - <span style="font-weight: 700;">Boys Hostel</span> </div>
                                <div class="hostel-name">Warden - <span style="font-weight: 700;">Mr. Sharma</span> </div>
                                <div class="hostel-name">Contact Info - <span style="font-weight: 700;">7024393158</span>
                                </div>
                                <div class="hostel-name"> Security - <span style="font-weight: 700;"> Mr. Ram Babu</span>
                                </div>
                            </div>

                            <!-- Fee collection -->
                            <div class="fee-details">
                                <h4>Fee collection Details</h4>
                                <ul>
                                    <li><span>Expected</span> ₹ 15,65,852</li>
                                    <li><span>collected</span> ₹ 3,91,463</li>
                                    <li><span>Remaining</span> ₹ 7,51,608</li>
                                    <li><span>Others</span> ₹ 1,65,852</li>
                                    <li><span>Overdue</span> ₹ 4,85,414</li>
                                </ul>
                            </div>

                            <!-- Assets -->
                            <div class="assets">
                                <h4>Assets</h4>
                                <ul>
                                    <li><span>Ceiling Fan</span> 50</li>
                                    <li><span>Center Table</span> 12</li>
                                    <li><span>Chairs</span> 30</li>
                                    <li><span>Refrigerator</span> 44</li>
                                    <li><span>Kettle</span> 33</li>
                                </ul>
                            </div>

                            <!-- Hostel Stats -->
                            <div class="hostel-stats">
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bed</p>
                                        <strong>550</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bathroom</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Floors</p>
                                        <strong>3</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Rooms</p>
                                        <strong>458</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Occupied</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Students</p>
                                        <strong>500</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>In Hostel</p>
                                        <strong>290</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Outside Hostel</p>
                                        <strong>210</strong>
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="hostel-actions">
                                    <button class="edit" type="button" data-bs-toggle="modal" data-bs-target="#EditHostel">
                                        Edit Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="delete" type="button" data-bs-toggle="modal"
                                        data-bs-target="#ConfirmationPopup">
                                        Delete Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="remove" type="button" data-bs-toggle="modal"
                                        data-bs-target="#RemoveRoom">Remove Room
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="add" type="button" data-bs-toggle="modal" data-bs-target="#AddRoom"> Add
                                        Room
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="hostel-status-title mt-4 mb-4">Hostel Staff Details</h3>

                        <div class="hostel-staff mt-3">

                            <!-- Administrative Staff -->
                            <div class="fee-details">
                                <h4>Administrative Staff</h4>

                                <ul>
                                    <li><span>Hostel Owner</span> Rajat Pradhan</li>
                                    <li><span>Hostel Warden</span> Rajat Pradhan</li>
                                    <li><span>Assistant Warden</span> Rajat Pradhan</li>
                                    <li><span>Office Clerk</span> Rajat Pradhan</li>
                                </ul>

                            </div>

                            <!-- Security Staff -->
                            <div class="fee-details">
                                <h4>Security Staff</h4>

                                <ul>
                                    <li><span>Security Head</span> Amresh Singh</li>
                                    <li><span>Security Guard</span> Amresh Singh</li>
                                    <li><span>Gatekeeper</span> Amresh Singh</li>
                                    <li><span>CCTV Operator</span> Amresh Singh</li>
                                </ul>

                            </div>

                            <!-- Maintenance & Housekeeping Staff -->
                            <div class="fee-details">
                                <h4>Maintenance & Housekeeping Staff</h4>

                                <ul>
                                    <li><span>Housekeeping</span> Unnati Un</li>
                                    <li><span>Electrician</span> Unnati Un</li>
                                    <li><span>Plumber</span> Unnati Un</li>
                                    <li><span>Carpenter</span> Unnati Un</li>
                                </ul>

                            </div>

                            <!-- Kitchen & Mess Staff -->
                            <div class="fee-details">
                                <h4>Kitchen & Mess Staff</h4>

                                <ul>
                                    <li><span>Mess Manager</span> Gopal Gopu</li>
                                    <li><span>Cook / Chef</span> Gopal Gopu</li>
                                    <li><span>Kitchen Helpers</span> Gopal Gopu</li>
                                    <li><span>Storekeeper</span> Gopal Gopu</li>
                                </ul>

                            </div>

                            <!-- Medical & Support Staff -->
                            <div class="fee-details">
                                <h4>Medical & Support Staff</h4>

                                <ul>
                                    <li><span>Hostel Doctor</span> John Cena</li>
                                    <li><span>Nurse </span> John Cena</li>
                                    <li><span>First-Aid</span> John Cena</li>
                                    <li><span>Doctor (visiting)</span> John Cena</li>
                                </ul>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Hostel Status Card 3 -->

        <div class="accordion-item">
            <h2 class="accordion-header" id="hostel_three_a">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#tel_three_a" aria-expanded="false" aria-controls="tel_three_a">
                    Hostel 3
                </button>
            </h2>
            <div id="tel_three_a" class="accordion-collapse collapse" aria-labelledby="hostel_three_a"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <div class="hostel-status-section">
                        <div class="hostel-status-btn">
                            <h3 class="hostel-status-title">Hostel Status 3</h3>

                            <div class="status-btn">
                                <label for="hostelName">Select Status</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                </select>
                            </div>

                        </div>

                        <div class="hostel-status-container">
                            <!-- Hostel Name and Progress -->
                            <div class="hostel-progress-card">
                                <div class="progress-circle">
                                    <svg>
                                        <circle cx="60" cy="60" r="50"></circle>
                                        <circle cx="60" cy="60" r="50"></circle>
                                    </svg>
                                    <div class="progress-value">50%</div>
                                </div>
                                <div class="hostel-name">Hostel - <span style="font-weight: 700;">Boys Hostel</span> </div>
                                <div class="hostel-name">Warden - <span style="font-weight: 700;">Mr. Sharma</span> </div>
                                <div class="hostel-name">Contact Info - <span style="font-weight: 700;">7024393158</span>
                                </div>
                                <div class="hostel-name"> Security - <span style="font-weight: 700;"> Mr. Ram Babu</span>
                                </div>
                            </div>

                            <!-- Fee collection -->
                            <div class="fee-details">
                                <h4>Fee collection Details</h4>
                                <ul>
                                    <li><span>Expected</span> ₹ 15,65,852</li>
                                    <li><span>collected</span> ₹ 3,91,463</li>
                                    <li><span>Remaining</span> ₹ 7,51,608</li>
                                    <li><span>Others</span> ₹ 1,65,852</li>
                                    <li><span>Overdue</span> ₹ 4,85,414</li>
                                </ul>
                            </div>

                            <!-- Assets -->
                            <div class="assets">
                                <h4>Assets</h4>
                                <ul>
                                    <li><span>Ceiling Fan</span> 50</li>
                                    <li><span>Center Table</span> 12</li>
                                    <li><span>Chairs</span> 30</li>
                                    <li><span>Refrigerator</span> 44</li>
                                    <li><span>Kettle</span> 33</li>
                                </ul>
                            </div>

                            <!-- Hostel Stats -->
                            <div class="hostel-stats">
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bed</p>
                                        <strong>550</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bathroom</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Floors</p>
                                        <strong>3</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Rooms</p>
                                        <strong>458</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Occupied</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Students</p>
                                        <strong>500</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>In Hostel</p>
                                        <strong>290</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Outside Hostel</p>
                                        <strong>210</strong>
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="hostel-actions">
                                    <button class="edit" type="button" data-bs-toggle="modal" data-bs-target="#EditHostel">
                                        Edit Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="delete" type="button" data-bs-toggle="modal"
                                        data-bs-target="#ConfirmationPopup">
                                        Delete Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="remove" type="button" data-bs-toggle="modal"
                                        data-bs-target="#RemoveRoom">Remove Room
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="add" type="button" data-bs-toggle="modal" data-bs-target="#AddRoom"> Add
                                        Room
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="hostel-status-title mt-4 mb-4">Hostel Staff Details</h3>

                        <div class="hostel-staff mt-3">

                            <!-- Administrative Staff -->
                            <div class="fee-details">
                                <h4>Administrative Staff</h4>

                                <ul>
                                    <li><span>Hostel Owner</span> Rajat Pradhan</li>
                                    <li><span>Hostel Warden</span> Rajat Pradhan</li>
                                    <li><span>Assistant Warden</span> Rajat Pradhan</li>
                                    <li><span>Office Clerk</span> Rajat Pradhan</li>
                                </ul>

                            </div>

                            <!-- Security Staff -->
                            <div class="fee-details">
                                <h4>Security Staff</h4>

                                <ul>
                                    <li><span>Security Head</span> Amresh Singh</li>
                                    <li><span>Security Guard</span> Amresh Singh</li>
                                    <li><span>Gatekeeper</span> Amresh Singh</li>
                                    <li><span>CCTV Operator</span> Amresh Singh</li>
                                </ul>

                            </div>

                            <!-- Maintenance & Housekeeping Staff -->
                            <div class="fee-details">
                                <h4>Maintenance & Housekeeping Staff</h4>

                                <ul>
                                    <li><span>Housekeeping</span> Unnati Un</li>
                                    <li><span>Electrician</span> Unnati Un</li>
                                    <li><span>Plumber</span> Unnati Un</li>
                                    <li><span>Carpenter</span> Unnati Un</li>
                                </ul>

                            </div>

                            <!-- Kitchen & Mess Staff -->
                            <div class="fee-details">
                                <h4>Kitchen & Mess Staff</h4>

                                <ul>
                                    <li><span>Mess Manager</span> Gopal Gopu</li>
                                    <li><span>Cook / Chef</span> Gopal Gopu</li>
                                    <li><span>Kitchen Helpers</span> Gopal Gopu</li>
                                    <li><span>Storekeeper</span> Gopal Gopu</li>
                                </ul>

                            </div>

                            <!-- Medical & Support Staff -->
                            <div class="fee-details">
                                <h4>Medical & Support Staff</h4>

                                <ul>
                                    <li><span>Hostel Doctor</span> John Cena</li>
                                    <li><span>Nurse </span> John Cena</li>
                                    <li><span>First-Aid</span> John Cena</li>
                                    <li><span>Doctor (visiting)</span> John Cena</li>
                                </ul>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Hostel Status Card 4 -->

        <div class="accordion-item">
            <h2 class="accordion-header" id="hostel_four_a">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#tel_four_a" aria-expanded="false" aria-controls="tel_four_a">
                    Hostel Four
                </button>
            </h2>
            <div id="tel_four_a" class="accordion-collapse collapse" aria-labelledby="hostel_four_a"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <div class="hostel-status-section">
                        <div class="hostel-status-btn">
                            <h3 class="hostel-status-title">Hostel Status 4</h3>

                            <div class="status-btn">
                                <label for="hostelName">Select Status</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                </select>
                            </div>

                        </div>

                        <div class="hostel-status-container">
                            <!-- Hostel Name and Progress -->
                            <div class="hostel-progress-card">
                                <div class="progress-circle">
                                    <svg>
                                        <circle cx="60" cy="60" r="50"></circle>
                                        <circle cx="60" cy="60" r="50"></circle>
                                    </svg>
                                    <div class="progress-value">50%</div>
                                </div>
                                <div class="hostel-name">Hostel - <span style="font-weight: 700;">Boys Hostel</span> </div>
                                <div class="hostel-name">Warden - <span style="font-weight: 700;">Mr. Sharma</span> </div>
                                <div class="hostel-name">Contact Info - <span style="font-weight: 700;">7024393158</span>
                                </div>
                                <div class="hostel-name"> Security - <span style="font-weight: 700;"> Mr. Ram Babu</span>
                                </div>
                            </div>

                            <!-- Fee collection -->
                            <div class="fee-details">
                                <h4>Fee collection Details</h4>
                                <ul>
                                    <li><span>Expected</span> ₹ 15,65,852</li>
                                    <li><span>collected</span> ₹ 3,91,463</li>
                                    <li><span>Remaining</span> ₹ 7,51,608</li>
                                    <li><span>Others</span> ₹ 1,65,852</li>
                                    <li><span>Overdue</span> ₹ 4,85,414</li>
                                </ul>
                            </div>

                            <!-- Assets -->
                            <div class="assets">
                                <h4>Assets</h4>
                                <ul>
                                    <li><span>Ceiling Fan</span> 50</li>
                                    <li><span>Center Table</span> 12</li>
                                    <li><span>Chairs</span> 30</li>
                                    <li><span>Refrigerator</span> 44</li>
                                    <li><span>Kettle</span> 33</li>
                                </ul>
                            </div>

                            <!-- Hostel Stats -->
                            <div class="hostel-stats">
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bed</p>
                                        <strong>550</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Bathroom</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Floors</p>
                                        <strong>3</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Total Rooms</p>
                                        <strong>458</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Occupied</p>
                                        <strong>58</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Students</p>
                                        <strong>500</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>In Hostel</p>
                                        <strong>290</strong>
                                    </span>
                                </div>
                                <div class="stat-card">
                                    <span>
                                        <p>Outside Hostel</p>
                                        <strong>210</strong>
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="hostel-actions">
                                    <button class="edit" type="button" data-bs-toggle="modal" data-bs-target="#EditHostel">
                                        Edit Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="delete" type="button" data-bs-toggle="modal"
                                        data-bs-target="#ConfirmationPopup">
                                        Delete Hostel
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="remove" type="button" data-bs-toggle="modal"
                                        data-bs-target="#RemoveRoom">Remove Room
                                    </button>
                                </div>

                                <div class="hostel-actions">
                                    <button class="add" type="button" data-bs-toggle="modal" data-bs-target="#AddRoom"> Add
                                        Room
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="hostel-status-title mt-4 mb-4">Hostel Staff Details</h3>

                        <div class="hostel-staff mt-3">

                            <!-- Administrative Staff -->
                            <div class="fee-details">
                                <h4>Administrative Staff</h4>

                                <ul>
                                    <li><span>Hostel Owner</span> Rajat Pradhan</li>
                                    <li><span>Hostel Warden</span> Rajat Pradhan</li>
                                    <li><span>Assistant Warden</span> Rajat Pradhan</li>
                                    <li><span>Office Clerk</span> Rajat Pradhan</li>
                                </ul>

                            </div>

                            <!-- Security Staff -->
                            <div class="fee-details">
                                <h4>Security Staff</h4>

                                <ul>
                                    <li><span>Security Head</span> Amresh Singh</li>
                                    <li><span>Security Guard</span> Amresh Singh</li>
                                    <li><span>Gatekeeper</span> Amresh Singh</li>
                                    <li><span>CCTV Operator</span> Amresh Singh</li>
                                </ul>

                            </div>

                            <!-- Maintenance & Housekeeping Staff -->
                            <div class="fee-details">
                                <h4>Maintenance & Housekeeping Staff</h4>

                                <ul>
                                    <li><span>Housekeeping</span> Unnati Un</li>
                                    <li><span>Electrician</span> Unnati Un</li>
                                    <li><span>Plumber</span> Unnati Un</li>
                                    <li><span>Carpenter</span> Unnati Un</li>
                                </ul>

                            </div>

                            <!-- Kitchen & Mess Staff -->
                            <div class="fee-details">
                                <h4>Kitchen & Mess Staff</h4>

                                <ul>
                                    <li><span>Mess Manager</span> Gopal Gopu</li>
                                    <li><span>Cook / Chef</span> Gopal Gopu</li>
                                    <li><span>Kitchen Helpers</span> Gopal Gopu</li>
                                    <li><span>Storekeeper</span> Gopal Gopu</li>
                                </ul>

                            </div>

                            <!-- Medical & Support Staff -->
                            <div class="fee-details">
                                <h4>Medical & Support Staff</h4>

                                <ul>
                                    <li><span>Hostel Doctor</span> John Cena</li>
                                    <li><span>Nurse </span> John Cena</li>
                                    <li><span>First-Aid</span> John Cena</li>
                                    <li><span>Doctor (visiting)</span> John Cena</li>
                                </ul>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- All Popup -->

    <!-- Add Hostel -->
    <div class="modal fade" id="AddHostel" tabindex="-1" aria-labelledby="AddHostelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Hostel</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="hostelName">Select University</label>
                            <input type="text" id="SelectUniversity" name="SelectUniversity"
                                placeholder="Select University">
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Hostel Name</label>
                            <input type="text" id="hostelName" name="hostelName" placeholder="Enter Hostel Name">
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Hostel Code</label>
                            <input type="text" id="hostelCode" name="hostelCode" placeholder="Enter Hostel Code">
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Numbers of Floors</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Hostel Status</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Status</option>
                                <option value="Open">Open</option>
                                <option value="Close">Close</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                        <button type="button" class="blue"> Add hostel </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- ConfirmationPopup -->
    <div class="modal fade" id="ConfirmationPopup" tabindex="-1" aria-labelledby="ConfirmationPopupLabel"
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
                        <button type="button" class="red"> Delete </button>
                        <button type="button" class="blue"> Go Back </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Add Room -->
    <div class="modal fade" id="AddRoom" tabindex="-1" aria-labelledby="AddRoomLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Room</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="SelectHostel">Select Hostel</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Hostel</option>
                                <option value="Hostel 1">Hostel 1</option>
                                <option value="Hostel 2">Hostel 2</option>
                                <option value="Hostel 3">Hostel 3</option>
                                <option value="Hostel 4">Hostel 4</option>
                                <option value="Hostel 5">Hostel 5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectFloor">Select Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="CreateRoomNumber">Create Room Number</label>
                            <input type="text" id="CreateRoomNumber" name="CreateRoomNumber"
                                placeholder="Enter Create Room Number">
                        </span>

                        <span class="input-set">
                            <label for="RoomType">Room Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="Single Bed">Single Bed</option>
                                <option value="Double Bed">Double Bed</option>
                                <option value="Triple Bed">Triple Bed</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="RoomStatus">Room Status</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Status</option>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Add Room </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Remove Room -->
    <div class="modal fade" id="RemoveRoom" tabindex="-1" aria-labelledby="RemoveRoomLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-remove">Remove Room</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="SelectHostel">Select Hostel</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Hostel</option>
                                <option value="Hostel 1">Hostel 1</option>
                                <option value="Hostel 2">Hostel 2</option>
                                <option value="Hostel 3">Hostel 3</option>
                                <option value="Hostel 4">Hostel 4</option>
                                <option value="Hostel 5">Hostel 5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectFloor">Select Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectRoom">Select Room</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Room</option>
                                <option value="101">101</option>
                                <option value="102">102</option>
                                <option value="103">103</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Remove Room </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- EditHostel -->
    <div class="modal fade" id="EditHostel" tabindex="-1" aria-labelledby="EditHostelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Hostel</div>
                    </div>

                    <div class="tabs-middle">

                        <div class="tabset">

                            <!-- Tab 1 -->
                            <input type="radio" name="tabset" id="tab1" aria-controls="StaffDetails" checked>
                            <label for="tab1">Staff Details</label>

                            <!-- Tab 2 -->
                            <input type="radio" name="tabset" id="tab2" aria-controls="HostelDetails">
                            <label for="tab2">Hostel Details</label>

                            <!-- Tab 3 -->
                            <input type="radio" name="tabset" id="tab3" aria-controls="Fee Collection Details">
                            <label for="tab3">Fee Collection Details </label>

                            <!-- Tab 4 -->
                            <input type="radio" name="tabset" id="tab4" aria-controls="Assests">
                            <label for="tab4">Assests</label>


                            <div class="tab-panels">
                                <!-- Tab 1 -->
                                <section id="StaffDetails" class="tab-panel">
                                    <div class="accordion" id="accordionExample">


                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    Administrative Staff
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">

                                                <div class="accordion-body">
                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="HostelOwner">Hostel Owner</label>
                                                                <input type="text" id="HostelOwner" name="HostelOwner"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="HostelWarden">Hostel Warden</label>
                                                                <input type="text" id="HostelWarden" name="HostelWarden"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="AssistantWarden">Assistant Warden</label>
                                                                <input type="text" id="AssistantWarden"
                                                                    name="AssistantWarden" placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="OfficeClerk">Office Clerk</label>
                                                                <input type="text" id="OfficeClerk" name="OfficeClerk"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Security Staff
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="SecurityHead">Security Head</label>
                                                                <input type="text" id="SecurityHead" name="SecurityHead"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="SecurityGuard">Security Guard</label>
                                                                <input type="text" id="SecurityGuard" name="SecurityGuard"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Gatekeeper">Gatekeeper</label>
                                                                <input type="text" id="Gatekeeper" name="Gatekeeper"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="CCTVOperator">CCTV Operator (optional)</label>
                                                                <input type="text" id="CCTVOperator" name="CCTVOperator"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    Maintenance & Housekeeping Staff
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="HousekeepingStaff">Housekeeping Staff</label>
                                                                <input type="text" id="HousekeepingStaff"
                                                                    name="HousekeepingStaff" placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Electrician">Electrician</label>
                                                                <input type="text" id="Electrician" name="Electrician"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Plumber">Plumber</label>
                                                                <input type="text" id="Plumber" name="Plumber"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Carpenter">Carpenter (optional)</label>
                                                                <input type="text" id="Carpenter" name="Carpenter"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFour">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                    Kitchen & Mess Staff
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="MessManager">Mess Manager</label>
                                                                <input type="text" id="MessManager" name="MessManager"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Cook/Chef">Cook / Chef</label>
                                                                <input type="text" id="Cook/Chef" name="Cook/Chef"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="KitchenHelpers">Kitchen Helpers</label>
                                                                <input type="text" id="KitchenHelpers" name="KitchenHelpers"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Storekeeper">Storekeeper</label>
                                                                <input type="text" id="Storekeeper" name="Storekeeper"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingFive">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                    aria-expanded="false" aria-controls="collapseFive">
                                                    Medical & Support Staff
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse"
                                                aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Nurse/First-AidStaff">Hostel Doctor</label>
                                                                <input type="text" id="Nurse/First-AidStaff"
                                                                    name="Nurse/First-AidStaff" placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Nurse/First-AidStaff">Nurse</label>
                                                                <input type="text" id="Nurse/First-AidStaff"
                                                                    name="Nurse/First-AidStaff" placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Nurse/First-AidStaff">First-Aid</label>
                                                                <input type="text" id="Nurse/First-AidStaff"
                                                                    name="Nurse/First-AidStaff" placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="row ">

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="Doctor">Doctor (visiting)</label>
                                                                <input type="text" id="Doctor" name="Doctor"
                                                                    placeholder="Rajat">
                                                            </span>
                                                        </div>

                                                        <div class=" col-sm-6">
                                                            <span class="input-set">
                                                                <label for="ContactNo">Contact No</label>
                                                                <input type="text" id="ContactNo" name="ContactNo"
                                                                    placeholder="99999-99999">
                                                            </span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </section>

                                <!-- Tab 2 -->
                                <section id="HostelDetails" class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalBed">Total Bed</label>
                                                <input type="text" id="TotalBed" name="TotalBed" placeholder="Total Bed">
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
                                                <input type="text" id="Occupied" name="Occupied" placeholder="Occupied">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Students">Students</label>
                                                <input type="text" id="Students" name="Students" placeholder="Students">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="In Hostel">In Hostel</label>
                                                <input type="text" id="In Hostel" name="In Hostel" placeholder="In Hostel">
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

                                <!-- Tab 3 -->
                                <section id="Fee Collection Details " class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Expected">Expected</label>
                                                <input type="text" id="Expected" name="Expected" placeholder="₹ 15,65,852">
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
                                                <input type="text" id="Remaining" name="Remaining" placeholder="₹ 7,51,608">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Others">Others</label>
                                                <input type="text" id="Others" name="Others" placeholder="₹ 1,65,852">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Overdue">Overdue</label>
                                                <input type="text" id="Overdue" name="Overdue" placeholder="₹ 4,85,414">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                        </div>

                                    </div>

                                </section>

                                <!-- Tab 4 -->
                                <section id="Assests" class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Ceiling Fan">Ceiling Fan</label>
                                                <input type="text" id="Ceiling Fan" name="Ceiling Fan" placeholder="33">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="CenterTable">Center Table</label>
                                                <input type="text" id="CenterTable" name="CenterTable" placeholder="12">
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
                                                <input type="text" id="Refrigerator" name="Refrigerator" placeholder="65">
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
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                        <button type="button" class="blue"> Save Changes </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Add Room -->
    <div class="modal fade" id="AddBed" tabindex="-1" aria-labelledby="AddBedLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Bed</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="SelectHostel">Select Hostel</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Hostel</option>
                                <option value="Hostel 1">Hostel 1</option>
                                <option value="Hostel 2">Hostel 2</option>
                                <option value="Hostel 3">Hostel 3</option>
                                <option value="Hostel 4">Hostel 4</option>
                                <option value="Hostel 5">Hostel 5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectFloor">Select Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="RoomType">Room Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="Single Bed">Single Bed</option>
                                <option value="Double Bed">Double Bed</option>
                                <option value="Triple Bed">Triple Bed</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="BedType">Bed Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="Single Bed">Single Bed</option>
                                <option value="Double Bed">Double Bed</option>
                                <option value="Triple Bed">Triple Bed</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="CreateRoomNumber">Create Bed Number</label>
                            <input type="text" id="CreateBedNumber" name="CreateBedNumber"
                                placeholder="Enter Create Bed Number">
                        </span>

                        <span class="input-set">
                            <label for="BedStatus">Bed Status</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Status</option>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Creat Bed </button>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <div class="container mt-4">

        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>Buildings</h2>

            <a href="{{ route('admin.create_building') }}" class="btn btn-primary p-3">
                <i class="fas fa-plus"></i> Create Building
            </a>

        </div>

        {{-- Alert for errors --}}
        <div id="errorAlert" class="alert alert-danger d-none" role="alert"></div>
        {{-- Alert for success messages --}}
        <div id="successAlert" class="alert alert-success d-none" role="alert"></div>

        <table class="table table-bordered" id="buildingList">
            <thead class="table-dark">
                <tr>
                    <th>S.No.</th>
                    <th>Building Name</th>
                    <th>Floors</th>
                    <th>Building Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this building? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')



    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                url: '/api/admin/buildings', // your API endpoint
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id')
                },
                success: function (response) {
                    // console.log(response.data.length);
                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        let rows = '';
                        response.data.forEach(function (building, index) {
                            rows += `
                                                                                <tr>
                                                                                    <td>${index + 1}</td>
                                                                                    <td>${building.name}</td>
                                                                                    <td>${building.floors}</td>
                                                                                    <td>${building.building_code}</td>  
                                                                                    <td>${building.status}</td>
                                                                                    <td>                                
                                                                                    <a href="/admin/buildings/edit/${building.id}" class="btn btn-sm btn-primary edit-btn" data-id="${building.id}">Edit</a>
                                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="${building.id}">Delete</a>
                                                                                    </td>
                                                                                </tr>
                                                                            `;
                        });
                        $('#buildingList tbody').html(rows);

                        // Datatable
                        InitializeDatatable();

                    } else {
                        $('#buildingList tbody').html('<tr><td colspan="4">No data found</td></tr>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $('#buildingList tbody').html('<tr><td colspan="4">Error loading data</td></tr>');
                }
            });
        });


        function showAlert(type, message) {
            let alertBox = type === 'success' ? $('#successAlert') : $('#errorAlert');
            alertBox.text(message).removeClass('d-none');
            setTimeout(() => {
                alertBox.addClass('d-none');
            }, 4000);
        }
        $(document).on('click', '.delete-btn', function () {
            let buildingId = $(this).data('id'); // Get the ID from button
            console.log("Deleting building with ID:", buildingId);
            $('#deleteConfirmationModal').modal('show');
            $('#confirmDeleteBtn').off('click').on('click', function () {
                $.ajax({
                    url: `/api/admin/buildings/${buildingId}`,
                    type: 'DELETE',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            showAlert("success", response.message || "Building deleted successfully!");
                            $('#deleteConfirmationModal').modal('hide');
                            // Refresh the building list
                            $.ajax({
                                url: '/api/admin/buildings',
                                type: 'GET',
                                headers: {
                                    'token': localStorage.getItem('token'),
                                    'auth-id': localStorage.getItem('auth-id')
                                },
                                success: function (response) {
                                    if (response.success && Array.isArray(response.data)) {
                                        let rows = '';
                                        response.data.forEach(function (building, index) {
                                            rows += `
                                                                                                <tr>        
                                                                                                    <td>${index + 1}</td>
                                                                                                    <td>${building.name}</td>
                                                                                                    <td>${building.floors}</td>
                                                                                                    <td>${building.building_code}</td>  
                                                                                                    <td>${building.status}</td>
                                                                                                    <td>                                
                                                                                                    <a href="/admin/buildings/edit/${building.id}" class="btn btn-sm btn-primary edit-btn" data-id="${building.id}">Edit</a>
                                                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="${building.id}">Delete</a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            `;
                                        });
                                        $('#buildingList tbody').html(rows);
                                    } else {
                                        $('#buildingList tbody').html('<tr><td colspan="6">No data found</td></tr>');
                                    }
                                },
                                error: function (xhr) {
                                    console.error(xhr);
                                    $('#buildingList tbody').html('<tr><td colspan="6">Error loading data</td></tr>');
                                }
                            });
                        } else {
                            showAlert("danger", response.message || "Failed to delete building.");
                            $('#deleteConfirmationModal').modal('hide');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        showAlert("danger", "An error occurred while deleting the building.");
                        $('#deleteConfirmationModal').modal('hide');
                    }
                });
            });
        });

    </Script>
@endpush