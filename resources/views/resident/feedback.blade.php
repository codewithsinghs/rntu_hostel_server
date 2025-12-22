@extends('resident.layout')

@section('content')
    <style>
        .status-table td {
            padding: 5px 10px;
        }

        table th,
        table td {
            min-width: 100px;
        }
    </style>
    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Feedback Overview</a></div>

                <!-- Feedbacks Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Feedbacks</p>
                            <h3 id="total-faddbacks">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/feedback.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Positive Feedbacks</p>
                            <h3 id="positive-feedbacks">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/positive-vote.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Neutral Feedbacks</p>
                            <h3 id="neutral-feedbacks">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/neutral.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Negative Feedbacks</p>
                            <h3 id="negative-feedbacks">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/negative-vote.png') }}" alt="" />
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Feedback -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- message -->
                <div id="message" class="mt-3"></div>

                <!-- Collapse toggle button -->

                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#feedbackCollapse" aria-expanded="false"
                    aria-controls="feedbackCollapse">

                    <span class="breadcrumbs">Feedbacks</span>
                    <span class="btn btn-primary"> Submit Feedback</span>

                </button>


                <div class="collapse" id="feedbackCollapse">
                    <!-- Form -->
                    <form id="feedbackForm">
                        @csrf
                        <!-- Hidden Resident ID field -->
                        <input type="hidden" id="resident_id" name="resident_id">

                        <div class="inpit-boxxx">

                            <span class="input-set">
                                <label for="facility_name">Select Feedback Category</label>
                                <select class="form-select" id="facility_name" name="facility_name" required
                                    aria-label="Default select example">
                                    <option selected>Select Accessory</option>
                                    <option value="Mattress">Mattress</option>
                                    <option value="Pillow">Pillow</option>
                                    <option value="Blanket">Blanket</option>
                                    <option value="Bucket">Bucket</option>
                                    <option value="Mug">Mug</option>
                                    <option value="Chair">Chair</option>
                                    <option value="Key">Key</option>
                                    <option value="Other">Other</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="FeedbackCategory">Feedback Type</label>
                                <select class="form-select" id="feedback_type" name="feedback_type"
                                    aria-label="Default select example">
                                    <option value="" selected>Select Type</option>
                                    <option value="suggestion">Suggestion</option>
                                    <option value="appreciation">Appreciation</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="other">Other</option>
                                </select>
                            </span>

                            {{-- <span class="input-set">
                                <label for="Rating">Rating</label>
                                <select class="form-select"  id="rating" name="rating" aria-label="Default select example">
                                    <option value="" selected>Rating</option>
                                    <option value="Positive ">Positive </option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Negative ">Negative </option>
                                    <option value="Improvement Needed">Improvement Needed</option>
                                </select>
                            </span> --}}

                            <span class="input-set">
                                <label for="feedback">Feedback</label>
                                <textarea type="text" id="feedback" name="feedback" placeholder="Enter your Short title of your FeedBack..."
                                    required></textarea>
                            </span>

                            <span class="input-set">
                                <label for="suggestion">Your Suggestion (Optional)</label>
                                <textarea type="text" id="suggestion" name="suggestion" placeholder="Describe the issue..."></textarea>
                            </span>

                            <span class="input-set">
                                <label for="photo" class="form-label">Supporting Photo/Document (Optional):</label>
                                <input type="file" id="photo" name="photo" accept="image/*">
                            </span>
                        </div>
                        <button type="submit" class="submitted">Submit Request</button>


                    </form>
                    <!-- Form End -->
                </div>

            </div>
        </div>
    </section>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>FeedBack List</a></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Sr No</th>
                                <th>Feedback ID</th>
                                <th>Resident Name</th>

                                <th>Facility Name</th>
                                <th>Feedback Type</th>
                                <th>Feedback</th>
                                <th>Suggestion</th>
                                <th>Rating</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody id="feedback-body">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Automatically set resident_id from the logged-in user
            const residentId = "{{ auth()->user()->resident->id ?? '' }}";
            if (residentId) {
                document.getElementById("resident_id").value = residentId;
            }

            document.getElementById("feedbackForm").addEventListener("submit", function(event) {
                event.preventDefault();


                let formData = new FormData();
                formData.append('facility_name', document.getElementById("facility_name").value);
                formData.append('feedback', document.getElementById("feedback").value);
                formData.append('suggestion', document.getElementById("suggestion").value);

                fetch("{{ url('/api/resident/feedbacks') }}", {
                        method: "POST",
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            document.getElementById("message").innerHTML =
                                `<div class="alert alert-success">${data.message}</div>`;
                            document.getElementById("feedbackForm").reset();
                        }
                    })
                    .catch(error => {
                        console.error("Error submitting feedback:", error);
                        document.getElementById("message").innerHTML =
                            `<div class="alert alert-danger">Error submitting feedback.</div>`;
                    });
            });


            function loadFeedbacks() {
                fetch("/api/feedbacks", {
                        headers: {
                            "Authorization": "Bearer " + localStorage.getItem("token"),
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(response => {

                        const tbody = document.getElementById("feedback-body");
                        tbody.innerHTML = "";

                        if (!response.data || response.data.length === 0) {
                            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-3">
                        No feedback found.
                    </td>
                </tr>`;
                            return;
                        }

                        response.data.forEach((item, index) => {
                            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.message}</td>
                    <td>${item.rating ?? '-'}</td>
                    <td>${new Date(item.created_at).toLocaleDateString()}</td>
                </tr>
            `;
                        });
                    })
                    .catch(() => {
                        document.getElementById("feedback-body").innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-3 text-danger">
                    Failed to load feedback.
                </td>
            </tr>`;
                    });
            }




        });
    </script> --}}

    {{-- <script>
        document.addEventListener("DOMContentLoaded", () => {

            // ----------------------------------------------------------
            // COMMON FUNCTIONS (Reusable)
            // ----------------------------------------------------------

            const token = localStorage.getItem("token");

            const apiRequest = async (url, method = "GET", body = null) => {
                try {
                    const options = {
                        method,
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Accept": "application/json"
                        }
                    };

                    if (body) options.body = body;

                    const response = await fetch(url, options);
                    return await response.json();
                } catch (err) {
                    console.error("API Request Failed:", err);
                    return {
                        success: false,
                        message: "Network error"
                    };
                }
            };

            const showAlert = (selector, message, type = "success") => {
                document.querySelector(selector).innerHTML =
                    `<div class="alert alert-${type}">${message}</div>`;
            };

            const clearTable = (selector, message = "No records found.", colspan = 4) => {
                document.querySelector(selector).innerHTML = `
            <tr>
                <td colspan="${colspan}" class="text-center py-3 text-muted">${message}</td>
            </tr>`;
            };

            // ----------------------------------------------------------
            // SET RESIDENT ID (Auto-fill)
            // ----------------------------------------------------------

            const residentId = "{{ auth()->user()->resident->id ?? '' }}";
            if (residentId) {
                const resField = document.getElementById("resident_id");
                if (resField) resField.value = residentId;
            }

            // ----------------------------------------------------------
            // SUBMIT FEEDBACK
            // ----------------------------------------------------------

            const feedbackForm = document.getElementById("feedbackForm");

            if (feedbackForm) {
                feedbackForm.addEventListener("submit", async (e) => {
                    e.preventDefault();

                    const formData = new FormData(feedbackForm);

                    const result = await apiRequest("{{ url('/api/resident/feedbacks') }}", "POST",
                        formData);

                    if (result.success) {
                        showAlert("#message", result.message, "success");
                        feedbackForm.reset();
                        loadFeedbacks(); // Auto refresh table
                    } else {
                        showAlert("#message", result.message || "Failed to submit feedback.", "danger");
                    }
                });
            }

            // ----------------------------------------------------------
            // LOAD FEEDBACK LIST
            // ----------------------------------------------------------

            const loadFeedbacks = async () => {
                const tbody = document.getElementById("feedback-body");
                if (!tbody) return;

                tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-3 text-info">Loading...</td>
            </tr>`;

                const res = await apiRequest("{{ url('/api/resident/feedbacks') }}");

                if (!res.success || !Array.isArray(res.data) || res.data.length === 0) {
                    return clearTable("#feedback-body");
                }

                tbody.innerHTML = "";

                res.data.forEach((item, index) => {
                    tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.message}</td>
                    <td>${item.rating ?? '-'}</td>
                    <td>${new Date(item.created_at).toLocaleDateString()}</td>
                </tr>`;
                });
            };

            // ----------------------------------------------------------
            // INITIAL LOAD
            // ----------------------------------------------------------

            loadFeedbacks();

        });
    </script> --}}

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("JS Loaded…"); // Debug: Check if script runs

            const token = localStorage.getItem("token");
            // const residentId = "{{ auth()->user()->resident->id ?? '' }}";

            // // Auto-fill resident ID
            // if (residentId) {
            //     const resField = document.getElementById("resident_id");
            //     if (resField) {
            //         resField.value = residentId;
            //     }
            // }

            // -----------------------------------------------------------
            // UNIVERSAL API CALLER
            // -----------------------------------------------------------
            function api(url, method = "GET", body = null) {
                console.log("API Call:", url); // Debug

                return fetch(url, {
                        method,
                        headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                        body
                    })
                    .then(res => {
                        console.log("API Status:", res.status);
                        return res.json();
                    })
                    .catch(err => {
                        console.error("API Error:", err);
                        return {
                            success: false,
                            message: "Fetch failed"
                        };
                    });
            }

            // -----------------------------------------------------------
            // FEEDBACK SUBMISSION
            // -----------------------------------------------------------

            const feedbackForm = document.getElementById("feedbackForm");
            const messageBox = document.getElementById("message");

            if (feedbackForm) {
                feedbackForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    console.log("Submitting feedback…"); // Debug

                    const formData = new FormData(feedbackForm);

                    api("{{ url('/api/resident/feedbacks') }}", "POST", formData)
                        .then(data => {
                            console.log("Feedback Response:", data); // Debug log

                            if (data.success) {
                                messageBox.innerHTML =
                                    `<div class="alert alert-success">${data.message}</div>`;
                                feedbackForm.reset();
                                loadFeedbacks(); // reload feedback list
                            } else {
                                messageBox.innerHTML =
                                    `<div class="alert alert-danger">${data.message || 'Failed to submit'}</div>`;
                            }
                        });
                });
            }

            // -----------------------------------------------------------
            // LOAD FEEDBACK LIST
            // -----------------------------------------------------------

            function loadFeedbacks() {
                console.log("Loading feedback list…"); // Debug

                const tbody = document.getElementById("feedback-body");
                if (!tbody) {
                    console.warn("feedback-body not found!");
                    return;
                }

                tbody.innerHTML = `
            <tr><td colspan="4" class="text-center py-3 text-info">Loading...</td></tr>
        `;

                api("{{ url('/api/resident/feedbacks') }}")
                    .then(res => {
                        console.log("Feedback List Response:", res);

                        if (!res.success || !res.data || res.data.length === 0) {
                            tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-3">No feedback found.</td>
                        </tr>
                    `;
                            return;
                        }

                        tbody.innerHTML = "";
                        res.data.forEach((item, index) => {
                            tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.message}</td>
                            <td>${item.rating ?? '-'}</td>
                            <td>${new Date(item.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                        });
                    })
                    .catch(err => {
                        console.error("Error loading feedback:", err);
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-3 text-danger">Failed to load feedback.</td>
                    </tr>
                `;
                    });
            }

            // Initial load
            // loadFeedbacks();
            console.log("Calling loadFeedbacks...");
            loadFeedbacks();

        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("SCRIPT STARTED");

            const token = localStorage.getItem("token");
            const residentId = "{{ auth()->user()->resident->id ?? '' }}";

            if (residentId) {
                const r = document.getElementById("resident_id");
                if (r) r.value = residentId;
            }

            // ----------------------------------------
            // DEFINE FUNCTION FIRST (IMPORTANT)
            // ----------------------------------------
            function loadFeedbacks() {
                console.log("loadFeedbacks() EXECUTED");

                const tbody = document.getElementById("feedback-body");
                if (!tbody) {
                    console.error("❌ feedback-body not found in DOM!");
                    return;
                }

                tbody.innerHTML = `
            <tr><td colspan="4" class="text-center py-3 text-info">Loading...</td></tr>
        `;

                fetch("{{ url('/api/resident/feedbacks') }}", {
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json"
                        }
                    })
                    .then(r => r.json())
                    .then(res => {

                        // console.log("Feedback API Response:", res.data.summary);
                        // ✅ Update Summary Cards
                        if (res.data.summary) {
                            const summary = res.data.summary;

                            document.getElementById("total-faddbacks").innerText = summary
                                .totalfeedbacks;
                            document.getElementById("positive-feedbacks").innerText = summary
                                .positivefeedbacks;
                            document.getElementById("negative-feedbacks").innerText = summary
                                .negativefeedbacks;
                            document.getElementById("neutral-feedbacks").innerText = summary
                                .neutralfeedbacks;
                        }


                        // console.log("Feedback API Response:", res);

                        if (!res.success || !res.data || res.data.length === 0) {
                            tbody.innerHTML = `
                    <tr><td colspan="4" class="text-center py-3">No feedback found.</td></tr>
                `;
                            return;
                        }

                        tbody.innerHTML = "";
                        res.data.items.forEach((i, index) => {
                            tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${i.feedback_uid}</td>
           
                        <td>${i.res_name}</td>
                       
                         <td>${i.facility_name}</td>
                          <td>${i.feedback_type}</td>
                           <td>${i.feedback}</td>
                            <td>${i.suggestion}</td>

                        <td>${i.rating ?? '-'}</td>
                         <td>${i.feedback_date}</td>
                        
                    </tr>
                `;
                        });
                    })
                    // <td>${new Date(i.created_at).toLocaleDateString()}</td>
                    .catch(err => {
                        console.error("Feedback Load Error:", err);
                        tbody.innerHTML = `
                <tr><td colspan="4" class="text-center text-danger">Failed to load feedback.</td></tr>
            `;
                    });
            }

            // ----------------------------------------
            // CALL FUNCTION AFTER DEFINING IT
            // ----------------------------------------
            console.log("Calling loadFeedbacks...");
            loadFeedbacks();


            // ----------------------------------------
            // FEEDBACK FORM SUBMISSION
            // ----------------------------------------
            const feedbackForm = document.getElementById("feedbackForm");
            if (feedbackForm) {
                feedbackForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    console.log("Submitting feedback...");

                    const formData = new FormData(feedbackForm);

                    fetch("{{ url('/api/resident/feedbacks') }}", {
                            method: "POST",
                            headers: {
                                "Authorization": "Bearer " + token,
                                "Accept": "application/json"
                            },
                            body: formData
                        })
                        .then(r => r.json())
                        .then(data => {
                            console.log("Feedback Submit Response:", data);

                            const msg = document.getElementById("message");

                            if (data.success) {
                                msg.innerHTML =
                                    `<div class="alert alert-success">${data.message}</div>`;
                                feedbackForm.reset();
                                loadFeedbacks();
                            } else {
                                msg.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                            }
                        })
                        .catch(err => {
                            console.error("Submit Error:", err);
                            document.getElementById("message").innerHTML =
                                `<div class="alert alert-danger">Error submitting feedback.</div>`;
                        });
                });
            }

        });
    </script>
@endpush
