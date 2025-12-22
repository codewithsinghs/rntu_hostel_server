@extends('resident.layout')

@section('content')
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs">Room Details - <span id="university-name">-</span> </div>

                <!-- Room Details -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Hostel Name</p>
                            <h3 id="hostel-name">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Hostel Management.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Room Number</p>
                            <h3 id="room-number">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/livingroom.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Floor Number</p>
                            <h3 id="floor-number">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/floor.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Bed Number</p>
                            <h3 id="bed-number">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="" />
                        </div>
                    </div>
                </div>

                <!-- <div id="current-room-details"></div> -->

            </div>
        </div>
    </section>

    <!-- Final Check Out Request -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">


                <div id="response-message"></div>

                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#finalCheckoutRequest" aria-expanded="false"
                    aria-controls="finalCheckoutRequest">
                    <span class="breadcrumbs">Final Check-Out</span>
                    <span class="btn btn-primary">Check-Out Request</span>
                </button>

                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="finalCheckoutRequest">

                    <form id="checkoutForm">
                        @csrf

                        <div class="inpit-boxxx">

                            <div class="reason">
                                <label for="date" class="form-label">Checkout Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <div class="reason">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary my-3">Submit Checkout Request</button>
                    </form>

                </div>

            </div>
        </div>
    </section>

    <!-- Table -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Check-Out Status</a></div>

                <div class="overflow-auto">
                    <table class="status-table" id="checkoutTableContainer" cellspacing="0" cellpadding="8" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Account Approval</th>
                                <th>Admin Approval</th>
                                <th>Remarks</th>
                                <th>Acessory Check</th>
                                <th>View Details</th>
                            </tr>
                        </thead>
                        <tbody id="checkoutTableBody">
                        </tbody>
                    </table>
                    <p id="noData" style="display:none;">No checkout status found.</p>
                    <p id="fetchError" class="text-danger text-center mt-3" style="display:none;"></p>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="checkoutDetailsModal" tabindex="-1" aria-labelledby="checkoutDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutDetailsModalLabel">Checkout Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="checkoutDetailsContent">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            document.getElementById('response-message').innerHTML =
                '<div class="alert alert-info">Submitting...</div>';

            fetch('/api/resident/checkout/request', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    credentials: 'include'
                })
                .then(async response => {
                    const contentType = response.headers.get("content-type");

                    if (!contentType || !contentType.includes("application/json")) {
                        throw new Error("Unexpected response format");
                    }

                    const data = await response.json();

                    if (response.ok) {
                        document.getElementById('response-message').innerHTML =
                            '<div class="alert alert-success">' + data.message + '</div>';
                        form.reset();
                    } else {
                        document.getElementById('response-message').innerHTML =
                            '<div class="alert alert-danger">Error: ' + (data.message ||
                                'Something went wrong') + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('response-message').innerHTML =
                        '<div class="alert alert-danger">There was an error: ' + error.message + '</div>';
                });
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const form = document.getElementById('checkoutForm');
            const msgBox = document.getElementById('response-message');
            if (!form) return;

            let submitting = false;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (submitting) return;
                submitting = true;

                msgBox.innerHTML = `<div class="alert alert-info">Submitting...</div>`;

                try {
                    const response = await fetch('/api/resident/checkout/request', {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        // ✅ SHOW VALIDATION ERRORS
                        if (data.errors) {
                            const errorList = Object.values(data.errors)
                                .flat()
                                .map(err => `<li>${err}</li>`)
                                .join('');

                            throw new Error(`<ul class="mb-0">${errorList}</ul>`);
                        }

                        throw new Error(data.message || 'Request failed');
                    }

                    msgBox.innerHTML = `
                <div class="alert alert-success">
                    ${data.message}
                </div>
            `;

                    form.reset();

                } catch (error) {
                    msgBox.innerHTML = `
                <div class="alert alert-danger">
                    ${error.message}
                </div>
            `;
                } finally {
                    submitting = false;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* -------------------------------------------------
             * DOM REFERENCES
             * ------------------------------------------------- */
            const checkoutTableBody = document.getElementById('checkoutTableBody');
            const noDataMsg = document.getElementById('noData');
            const fetchErrorMsg = document.getElementById('fetchError');
            const checkoutDetailsEl = document.getElementById('checkoutDetailsModal');
            const checkoutDetailsBox = document.getElementById('checkoutDetailsContent');

            const checkoutDetailsModal = new bootstrap.Modal(checkoutDetailsEl);

            /* -------------------------------------------------
             * COMMON HEADERS
             * ------------------------------------------------- */
            const authHeaders = {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            };

            /* -------------------------------------------------
             * INIT
             * ------------------------------------------------- */
            fetchCheckoutStatus();

            /* -------------------------------------------------
             * FETCH CHECKOUT STATUS
             * ------------------------------------------------- */
            async function fetchCheckoutStatus() {
                try {
                    const res = await fetch('/api/resident/checkout-status', {
                        headers: authHeaders
                    });
                    if (!res.ok) throw new Error(`Status ${res.status}`);

                    // const {
                    //     data
                    // } = await res.json();
                    const resdata = await res.json();
                    // console.log(resdata);

                    const summary = resdata.summary; // ✅ Correct
                    const data = resdata.data; // ✅ If you need it

                    if (summary) {
                        // console.log(summary);

                        // Apply to UI
                        document.getElementById('university-name').innerText = summary.university || '';
                        document.getElementById('hostel-name').innerText = summary.building || '';
                        document.getElementById('room-number').innerText = summary.room || '';
                        // document.getElementById('floor-number').innerText = floorNumber || '';
                        // document.getElementById('floor-number').innerHTML = summary.floor || '';
                        document.getElementById('floor-number').innerHTML =
                            summary.floor ? ordinal(summary.floor) : '';

                        document.getElementById('bed-number').innerText = summary.bed || '';
                    }

                  
                    function ordinal(n) {
                        const s = ["th", "st", "nd", "rd"],
                            v = n % 100;

                        const suffix = (s[(v - 20) % 10] || s[v] || s[0]);
                        return `${n}<sup>${suffix}</sup> Floor`;
                    }

                    checkoutTableBody.innerHTML = '';
                    fetchErrorMsg.style.display = 'none';

                    if (!data || Object.keys(data).length === 0) {
                        noDataMsg.style.display = 'block';
                        return;
                    }

                    noDataMsg.style.display = 'none';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>${data.date ?? 'N/A'}</td>
                <td>${data.reason ?? 'N/A'}</td>
                <td><span class="badge ${badge(data.account_approval)}">${cap(data.account_approval)}</span></td>
                <td><span class="badge ${badge(data.admin_approval)}">${cap(data.admin_approval)}</span></td>
                <td>${data.remarks ?? 'N/A'}</td>
                <td>${formatAction(data.action)}</td>
                <td>
                    <button class="btn btn-primary btn-sm view-details">View Details</button>
                </td>
            `;
                    checkoutTableBody.appendChild(row);

                    row.querySelector('.view-details')
                        .addEventListener('click', () => showCheckoutDetails(data));

                } catch (err) {
                    showError(`Error fetching checkout status: ${err.message}`);
                }
            }

            /* -------------------------------------------------
             * SHOW CHECKOUT DETAILS
             * ------------------------------------------------- */
            async function showCheckoutDetails(mainData) {
                checkoutDetailsBox.innerHTML = '<p class="text-muted">Loading details...</p>';

                try {
                    const res = await fetch('/api/resident/checkout-logs', {
                        headers: authHeaders
                    });
                    if (!res.ok) throw new Error(`Status ${res.status}`);

                    const api = await res.json();
                    if (api.success === false) throw new Error(api.message);

                    const logs = Array.isArray(api.data) ? api.data : [];
                    const deposit = 10000;

                    const totalDebit = logs.reduce((sum, l) => sum + (+l.debit_amount || 0), 0);
                    const remaining = deposit - totalDebit;

                    let html = `
                <h5 class="mb-3">Checkout Summary</h5>
                <p><b>Date:</b> ${mainData.date}</p>
                <p><b>Reason:</b> ${mainData.reason}</p>
                <p><b>Deposited:</b> ₹${deposit.toFixed(2)}</p>
                <p><b>Total Debit:</b> ₹${totalDebit.toFixed(2)}</p>
                <p><b>Remaining:</b> ₹${remaining.toFixed(2)}</p>
                <p><b>Action:</b> ${formatAction(mainData.action)}</p>
                <p><b>Admin Approval:</b> <span class="badge ${badge(mainData.admin_approval)}">${cap(mainData.admin_approval)}</span></p>
                <p><b>Account Approval:</b> <span class="badge ${badge(mainData.account_approval)}">${cap(mainData.account_approval)}</span></p>
                <p><b>Remark:</b> ${mainData.remarks ?? 'N/A'}</p>
                <hr>
            `;

                    if (logs.length) {
                        const accessories = await fetchAccessories();

                        html += `
                    <h5>Accessory Logs</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Accessory</th>
                                <th>Returned</th>
                                <th>Debit</th>
                                <th>Remark</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                        logs.forEach(log => {
                            html += `
                        <tr>
                            <td>${log.accessory_name || accessories[log.accessory_head_id] || 'Unknown'}</td>
                            <td>${log.is_returned ? 'Yes' : 'No'}</td>
                            <td>₹${(+log.debit_amount || 0).toFixed(2)}</td>
                            <td>${log.remark ?? 'N/A'}</td>
                            <td>${log.logged_at ?? 'N/A'}</td>
                        </tr>
                    `;
                        });

                        html += '</tbody></table>';
                    } else {
                        html += '<p>No accessory logs found.</p>';
                    }

                    checkoutDetailsBox.innerHTML = html;
                    checkoutDetailsModal.show();

                } catch (err) {
                    checkoutDetailsBox.innerHTML =
                        `<p class="text-danger">Failed to load details: ${err.message}</p>`;
                    checkoutDetailsModal.show();
                }
            }

            /* -------------------------------------------------
             * FETCH ACCESSORIES (MAP)
             * ------------------------------------------------- */
            async function fetchAccessories() {
                try {
                    const res = await fetch('/api/resident/accessories/active', {
                        headers: authHeaders
                    });
                    if (!res.ok) return {};

                    const {
                        data
                    } = await res.json();
                    return (data || []).reduce((map, a) => {
                        if (a.accessory_head?.id) map[a.accessory_head.id] = a.accessory_head.name;
                        return map;
                    }, {});
                } catch {
                    return {};
                }
            }

            /* -------------------------------------------------
             * HELPERS
             * ------------------------------------------------- */
            function badge(status) {
                return {
                    approved: 'bg-success',
                    denied: 'bg-danger',
                    pending: 'bg-warning text-dark'
                } [status] || 'bg-secondary';
            }

            function cap(str) {
                return str ? str[0].toUpperCase() + str.slice(1) : 'Pending';
            }

            function formatAction(action) {
                return action ?
                    action.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) :
                    'N/A';
            }

            function showError(msg) {
                fetchErrorMsg.textContent = msg;
                fetchErrorMsg.style.display = 'block';
                noDataMsg.style.display = 'none';
            }

        });
    </script>
@endpush
