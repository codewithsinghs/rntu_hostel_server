@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overflow</a></div>
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#courses">+ Raise
            Fines & Penalties</button>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Fines & Penalties Details</a></div>

                <div class="card-ds-bottom">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Fines Incurred</p>
                            <h3>₹25,500/-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Fines Amount</p>
                            <h3>₹5,000/-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Paid Fines Amount</p>
                            <h3>₹20,000/-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png') }}" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <div class="overflow-auto">

                    <table class="status-table" id="">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Faculty</th>
                                <th>Department</th>
                                <th>Hostel Name</th>
                                <th>Room no.</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rahul Sharma</td>
                                <td>Engineering</td>
                                <td>Computer Science</td>
                                <td>Tagore Hostel</td>
                                <td>101</td>
                                <td>₹0</td>
                                <td><span style="color: green; font-weight: bold;">Paid</span></td>
                                <td>
                                    <button class="edit-btn">Edit</button>
                                    <button class="delete-btn">Delete</button>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Aman Verma</td>
                                <td>Engineering</td>
                                <td>Mechanical</td>
                                <td>Vivekanand Hostel</td>
                                <td>203</td>
                                <td>₹500</td>
                                <td><span style="color: green; font-weight: bold;">Paid</span></td>
                                <td>
                                    <button class="edit-btn">Edit</button>
                                    <button class="delete-btn">Delete</button>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Neha Singh</td>
                                <td>Science</td>
                                <td>Physics</td>
                                <td>Saraswati Hostel</td>
                                <td>305</td>
                                <td>₹0</td>
                                <td><span style="color: orange; font-weight: bold;">Pending</span></td>
                                <td>
                                    <button class="edit-btn">Edit</button>
                                    <button class="delete-btn">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Create courses Popup-->
    <div class="modal fade" id="courses" tabindex="-1" aria-labelledby="coursesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Assign Fine to Resident</div>
                    </div>

                    <div id="success-message" class="alert alert-success d-none"></div>
                    <div id="error-message" class="alert alert-danger d-none"></div>

                    <form id="fineAssignmentForm" novalidate>

                        {{-- Hidden Inputs --}}
                        <input type="hidden" name="subscription_type" value="Other">

                        <div class="middle">

                            <span class="input-set">
                                <label for="resident_id">Resident</label>
                                <select name="resident_id" id="resident_id" required>
                                    <option value="">Loading residents...</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="remarks">Remarks <span class="text-danger">*</span></label>
                                <textarea name="remarks" id="remarks" required></textarea>
                            </span>

                            <span class="input-set">
                                <label for="amount">Propose Amount (In Rupees) <span
                                        class="text-danger">Optional</span></label>
                                <input type="number" name="amount" id="amount" placeholder="Enter amount ">
                            </span>

                            <span class="input-set">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="submit" class="blue"> Assign Fine</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

    <script>
        $(document).ready(function () {

            const residentSelect = $('#resident_id');

            // Load residents
            fetch("{{ url('/api/admin/residents') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id')
                }
            })
                .then(res => res.json())
                .then(res => {
                    residentSelect.html('<option value="">Select Resident</option>');
                    res.data.forEach(r => {
                        residentSelect.append(
                            `<option value="${r.id}">${r.name} (${r.scholar_no})</option>`
                        );
                    });

                    residentSelect.select2({
                        dropdownParent: $('#courses'),
                        width: '100%',
                        placeholder: 'Select Resident'
                    });
                })
                .catch(() => {
                    $('#error-message').removeClass('d-none').text('Failed to load residents');
                });

            // Submit fine
            $('#fineAssignmentForm').on('submit', function (e) {
                e.preventDefault();

                $('#error-message, #success-message').addClass('d-none');

                const payload = {
                    resident_id: residentSelect.val(),
                    remarks: $('#remarks').val().trim(),
                    amount: $('#amount').val() || 0,
                    status: $('#status').val(),
                    subscription_type: 'Other'
                };

                if (!payload.resident_id || !payload.remarks || payload.status === '') {
                    $('#error-message').removeClass('d-none').text('Please fill all required fields.');
                    return;
                }

                fetch("{{ url('/api/admin/assign/fine') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'token': localStorage.getItem('token'),
                        'Auth-ID': localStorage.getItem('auth-id')
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            $('#success-message').removeClass('d-none').text(res.message);
                            $('#fineAssignmentForm')[0].reset();
                            residentSelect.val(null).trigger('change');
                        } else {
                            $('#error-message').removeClass('d-none').text(res.message || 'Failed to assign fine');
                        }
                    })
                    .catch(() => {
                        $('#error-message').removeClass('d-none').text('Server error occurred');
                    });
            });

        });
    </script>

@endpush