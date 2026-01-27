{{-- @extends('guest.layout')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Guest Dashboard</h2>
    </div>
@endsection --}}

@extends('guest.layout')

@section('content')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> --}}

    <div class="mt-5">
        <h2 class="text-center mb-4">Guest Dashboard</h2>

        <div id="mainResponseMessage"></div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>S.No.</th>
                    <th>Scholar No</th>
                    <th>Verification Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="guestList">
                <tr id="loadingRow">
                    <td colspan="5" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="waiverRejectedInfoModal" tabindex="-1" aria-labelledby="waiverRejectedInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Waiver Rejected Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your fee waiver request has been rejected. You can still proceed with the normal payment process.</p>
                    <div class="text-center mt-3">
                        <button class="btn btn-success" id="proceedToNormalPaymentBtn">Pay as Normal & Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        const token = localStorage.getItem("token");

        console.log("SCRIPT STARTED", token);
        document.addEventListener("DOMContentLoaded", function() {
            fetchGuestStatus();
            waiverRejectedInfoModal = new bootstrap.Modal(document.getElementById('waiverRejectedInfoModal'));
        });

        function showCustomMessageBox(message, type = 'info', targetElementId = 'mainResponseMessage') {
            const container = document.getElementById(targetElementId);
            if (container) {
                container.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            `;
            }
        }

        function fetchGuestStatus() {
            fetch("{{ url('/api/guest/approved-rejected-guest') }}", {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        'token': localStorage.getItem('token'),
                        "Authorization": `Bearer ${localStorage.getItem('token')}`,
                        "auth-id": localStorage.getItem('auth-id') // still included if required
                    }

                    // headers: {
                    //     "Accept": "application/json",
                    //     'token': localStorage.getItem('token'),
                    //     'auth-id': localStorage.getItem('auth-id')
                    // }
                    // headers: {
                    //     "Authorization": "Bearer " + token,
                    //     "Accept": "application/json"
                    // },
                })
                .then(response => {
                    if (!response.ok) throw new Error("Failed to load data");
                    return response.json();
                })
                .then(data => {
                    const guestList = document.getElementById("guestList");
                    guestList.innerHTML = "";

                    if (!data.data || data.data.length === 0) {
                        guestList.innerHTML =
                            `<tr><td colspan="5" class="text-center">No guest requests found.</td></tr>`;
                        return;
                    }

                    let serial = 1;
                    data.data.forEach(guest => {
                        const status = guest.status.trim().toLowerCase();
                        const statusClass = getStatusClass(status);
                        let action = '-';

                        switch (status) {
                            case 'approved':
                            case 'waiver_approved':
                                action =
                                    `<button class="btn btn-primary btn-sm" onclick="makePayment(${guest.id})"><i class="fa fa-credit-card"></i> Make Payment</button>`;
                                break;
                            case 'waiver_rejected':
                                action =
                                    `<button class="btn btn-warning btn-sm" onclick="showWaiverRejectedMessage(${guest.id})">Details / Pay</button>`;
                                break;
                        }

                        guestList.innerHTML += `
                <tr>
                    <td>${serial++}</td>
                    <td>${guest.scholar_no}</td>
                    <td>${guest.is_verified ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-warning text-dark">Pending</span>'}</td>
                    <td><span class="badge ${statusClass}">${guest.status}</span></td>
                    <td>${guest.is_postpaid ? "<span class='badge bg-info text-white'>Post Paid</span>" : action}</td>
                </tr>
            `;
                    });
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById("guestList").innerHTML =
                        `<tr><td colspan="5" class="text-center text-danger">Failed to load guest requests.</td></tr>`;
                    showCustomMessageBox('Failed to load guest requests.', 'danger');
                });
        }

        function getStatusClass(status) {
            switch (status) {
                case 'approved':
                case 'waiver_approved':
                    return 'bg-success text-white';
                case 'rejected':
                    return 'bg-danger text-white';
                case 'waiver_rejected':
                    return 'bg-warning text-dark';
                case 'pending':
                    return 'bg-secondary text-white';
                default:
                    return 'bg-secondary text-white';
            }
        }

        let waiverRejectedInfoModal;
        let proceedHandler;

        function showWaiverRejectedMessage(guestId) {
            const proceedBtn = document.getElementById('proceedToNormalPaymentBtn');
            if (proceedBtn) {
                if (proceedHandler) {
                    proceedBtn.removeEventListener('click', proceedHandler);
                }
                proceedHandler = () => {
                    waiverRejectedInfoModal.hide();
                    makePayment(guestId);
                };
                proceedBtn.addEventListener('click', proceedHandler);
            }
            waiverRejectedInfoModal.show();
        }

        function makePayment(guestId) {
            window.location.href = `{{ url('/guest/payment') }}?guest_id=${guestId}`;
        }
    </script> --}}

    <script>
        /*
            |--------------------------------------------------------------------------
            | Guest Dashboard Script (Professional Version)
            |--------------------------------------------------------------------------
            */

        (() => {
            'use strict';

            /* ---------------------------------------------
             | Config
             |--------------------------------------------- */
            const CONFIG = {
                api: {
                    guestStatus: "{{ url('/api/guest/approved-rejected-guest') }}",
                },
                storage: {
                    token: 'token',
                    authId: 'auth-id',
                },
                selectors: {
                    guestList: '#guestList',
                    messageBox: '#mainResponseMessage',
                    waiverModal: '#waiverRejectedInfoModal',
                    proceedBtn: '#proceedToNormalPaymentBtn',
                }
            };

            /* ---------------------------------------------
             | State
             |--------------------------------------------- */
            let waiverRejectedInfoModal = null;
            let proceedHandler = null;

            /* ---------------------------------------------
             | Utils
             |--------------------------------------------- */
            const getToken = () => localStorage.getItem(CONFIG.storage.token);
            const getAuthId = () => localStorage.getItem(CONFIG.storage.authId);

            const showMessage = (message, type = 'info') => {
                const container = document.querySelector(CONFIG.selectors.messageBox);
                if (!container) return;

                container.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
            };

            const apiFetch = async (url, options = {}) => {
                const token = getToken();

                if (!token) {
                    throw new Error('Authentication token missing');
                }

                const response = await fetch(url, {
                    ...options,
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'auth-id': getAuthId(),
                        ...(options.headers || {})
                    }
                });

                if (!response.ok) {
                    const error = await response.json().catch(() => ({}));
                    throw new Error(error.message || 'API request failed');
                }

                return response.json();
            };

            /* ---------------------------------------------
             | Guest Status Logic
             |--------------------------------------------- */
            const getStatusClass = (status) => {
                switch (status) {
                    case 'approved':
                    case 'waiver_approved':
                        return 'bg-success text-white';
                    case 'rejected':
                        return 'bg-danger text-white';
                    case 'waiver_rejected':
                        return 'bg-warning text-dark';
                    case 'pending':
                        return 'bg-secondary text-white';
                    default:
                        return 'bg-secondary text-white';
                }
            };

            const renderGuestList = (guests) => {
                const tbody = document.querySelector(CONFIG.selectors.guestList);
                if (!tbody) return;

                tbody.innerHTML = '';

                if (!guests || guests.length === 0) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">No guest requests found.</td>
                    </tr>
                `;
                    return;
                }

                guests.forEach((guest, index) => {
                    const status = guest.status?.trim().toLowerCase();
                    const statusClass = getStatusClass(status);

                    let action = '-';

                    if (['approved', 'waiver_approved'].includes(status)) {
                        action = `
                    <button class="btn btn-primary btn-sm"
                            onclick="GuestDashboard.makePayment(${guest.id})">
                        <i class="fa fa-credit-card"></i> Make Payment
                    </button>
                    `;
                    }

                    if (status === 'waiver_rejected') {
                        action = `
                    <button class="btn btn-warning btn-sm"
                            onclick="GuestDashboard.showWaiverRejectedMessage(${guest.id})">
                        Details / Pay
                    </button>
                `;
                    }

                    tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${index + 1}</td>
                    <td>${guest.scholar_no}</td>
                    <td>
                        ${guest.is_verified
                            ? '<span class="badge bg-success">Verified</span>'
                            : '<span class="badge bg-warning text-dark">Pending</span>'}
                    </td>
                    <td><span class="badge ${statusClass}">${guest.status}</span></td>
                    <td>${guest.is_postpaid
                        ? "<span class='badge bg-info text-white'>Post Paid</span>"
                        : action}
                    </td>
                </tr>
            `);
                });
            };

            const fetchGuestStatus = async () => {
                try {
                    const response = await apiFetch(CONFIG.api.guestStatus, {
                        method: 'GET'
                    });
                    renderGuestList(response.data);
                } catch (error) {
                    console.error(error);
                    showMessage('Failed to load guest requests.', 'danger');

                    const tbody = document.querySelector(CONFIG.selectors.guestList);
                    if (tbody) {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            Failed to load guest requests.
                        </td>
                    </tr>
                `;
                    }
                }
            };

            /* ---------------------------------------------
             | Modal & Actions
             |--------------------------------------------- */
            const showWaiverRejectedMessage = (guestId) => {
                const proceedBtn = document.querySelector(CONFIG.selectors.proceedBtn);

                if (proceedBtn) {
                    if (proceedHandler) {
                        proceedBtn.removeEventListener('click', proceedHandler);
                    }

                    proceedHandler = () => {
                        waiverRejectedInfoModal.hide();
                        makePayment(guestId);
                    };

                    proceedBtn.addEventListener('click', proceedHandler);
                }

                waiverRejectedInfoModal.show();
            };

            const makePayment = (guestId) => {
                // window.location.href = `{{ url('/guest/payment') }}?guest_id=${guestId}`;
                window.location.href = `{{ url('/guest/payment') }}`;
            };

            /* ---------------------------------------------
             | Public API (Minimal Exposure)
             |--------------------------------------------- */
            window.GuestDashboard = {
                makePayment,
                showWaiverRejectedMessage
            };

            /* ---------------------------------------------
             | Init
             |--------------------------------------------- */
            document.addEventListener('DOMContentLoaded', () => {
                waiverRejectedInfoModal = new bootstrap.Modal(
                    document.querySelector(CONFIG.selectors.waiverModal)
                );

                fetchGuestStatus();
            });

        })();
    </script>
@endpush
