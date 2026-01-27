@extends('guest.layout')

@section('content')
    {{-- <div class="my-5">

        <!-- Page Header -->
        <div class="text-center mb-4">
            <h2 class="mb-1">Guest Dashboard</h2>
            <p class="text-muted mb-0">
                Track your hostel application, approval, and payment status
            </p>
        </div>

        <!-- Global Message Area -->
        <div id="mainResponseMessage" class="mb-4"></div>

        <!-- Applications Grid -->
        <div id="guestApplications" class="row g-4">

            <!-- Loading State -->
            <div class="col-12 text-center" id="loadingState">
                <div class="spinner-border text-primary"></div>
                <div class="text-muted mt-2">
                    Loading your hostel applications...
                </div>
            </div>

        </div>
    </div> --}}


    <div class="container my-5">

        <div class="mb-4">
            <h3 class="mb-1">Hostel Applications</h3>
            <p class="text-muted mb-0">
                Track verification, approval, and payment status of your hostel applications
            </p>
        </div>

        <div id="globalMessage" class="mb-3"></div>

        <div id="guestApplications" class="row g-4">
            <div id="loadingState" class="col-12 text-center py-5">
                <div class="spinner-border text-primary"></div>
                <div class="text-muted mt-2">Loading applications...</div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    {{-- <script>
        (() => {
            'use strict';

            /* ----------------------------------------------------------
             | Configuration
             |---------------------------------------------------------- */
            const CONFIG = {
                apiUrl: "{{ url('/api/guest/approved-rejected-guest') }}",
                paymentUrl: "{{ url('/guest/payment') }}",
                tokenKey: 'token',
                authIdKey: 'auth-id',
                container: '#guestApplications'
            };

            /* ----------------------------------------------------------
             | Utilities
             |---------------------------------------------------------- */
            const getToken = () => localStorage.getItem(CONFIG.tokenKey);
            const getAuthId = () => localStorage.getItem(CONFIG.authIdKey);

            const showMessage = (message, type = 'info') => {
                const box = document.getElementById('mainResponseMessage');
                box.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            };

            const apiFetch = async () => {
                const token = getToken();
                if (!token) throw new Error('Authentication missing');

                const res = await fetch(CONFIG.apiUrl, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'auth-id': getAuthId()
                    }
                });

                if (!res.ok) throw new Error('Failed to load data');
                return res.json();
            };

            /* ----------------------------------------------------------
             | State Resolvers (AUTHORITATIVE)
             |---------------------------------------------------------- */

            const STATUS_META = {
                pending: ['Pending', 'bg-secondary'],
                approved: ['Approved', 'bg-success'],
                rejected: ['Rejected', 'bg-danger'],
                waiver_approved: ['Waiver Approved', 'bg-success'],
                waiver_rejected: ['Waiver Rejected', 'bg-warning text-dark'],
                cancelled: ['Cancelled', 'bg-dark']
            };

            const verificationState = v => {
                if (v === 1) return {
                    text: 'Completed',
                    class: 'text-success'
                };
                return {
                    text: 'Pending',
                    class: 'text-warning'
                };
            };

            const durationText = g => {
                if (g.months) return `${g.months} Month${g.months > 1 ? 's' : ''}`;
                if (g.days) return `${g.days} Day${g.days > 1 ? 's' : ''}`;
                return '—';
            };

            /* ----------------------------------------------------------
             | Payment Status (STRICT RULES)
             |---------------------------------------------------------- */
            const paymentBadge = g => {

                // Payment is NOT applicable before approval
                if (!['approved', 'waiver_approved', 'waiver_rejected'].includes(g.status)) {
                    return `<span class="badge bg-secondary">Not Applicable</span>`;
                }

                if (g.status === 'waiver_approved') {
                    return `<span class="badge bg-success">Waived</span>`;
                }

                if (g.is_postpaid == 1) {
                    return `<span class="badge bg-info">Postpaid</span>`;
                }

                if (Number(g.pending_amount ?? 0) > 0) {
                    return `<span class="badge bg-warning text-dark">
                Pending Payment
            </span>`;
                }

                return `<span class="badge bg-success">Completed</span>`;
            };

            /* ----------------------------------------------------------
             | Action Resolver (NO INVALID ACTIONS)
             |---------------------------------------------------------- */
            const actionView = g => {

                console.log('data', g);
                if (g.status === 'pending') {
                    return `<span class="text-muted">
                Awaiting Admin Approval
            </span>`;
                }

                if (['rejected', 'cancelled'].includes(g.status)) {
                    return `<span class="text-muted">No Action Available</span>`;
                }

                if (g.status === 'approved' && g.is_verified !== 1) {
                    return `<span class="badge bg-warning text-dark">
                Verification Pending
            </span>`;
                }

                if (g.status === 'waiver_approved') {
                    return `<span class="badge bg-success">
                No Payment Required
            </span>`;
                }

                if (g.status === 'waiver_rejected') {
                    return `
                <button class="btn btn-warning btn-sm"
                    onclick="GuestDashboard.pay(${g.id})">
                    Pay as Normal
                </button>`;
                }

                if (g.is_postpaid == 1) {
                    return `<span class="badge bg-info">Postpaid</span>`;
                }

                if (Number(g.pending_amount ?? 0) > 0) {
                    return `
                <button class="btn btn-primary btn-sm"
                    onclick="GuestDashboard.pay(${g.id})">
                    <i class="fa fa-credit-card"></i> Pay Now
                </button>`;
                }

                return `<span class="badge bg-success">Completed</span>`;
            };

            /* ----------------------------------------------------------
             | Card Renderer (Matches Your API Exactly)
             |---------------------------------------------------------- */
            const renderCard = g => {
                const [statusText, statusClass] =
                STATUS_META[g.status] || STATUS_META.pending;

                const verify = verificationState(g.is_verified);

                console.log(verify);

                return `
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border-0">

                <div class="card-header bg-light d-flex justify-content-between">
                    <div>
                        <strong>${g.name}</strong>
                        <div class="small text-muted">
                            Scholar No: ${g.scholar_no}
                        </div>
                    </div>
                    <span class="badge ${statusClass}">
                        ${statusText}
                    </span>
                </div>

                <div class="card-body">

                    <div class="mb-2">
                        <strong>Verification:</strong>
                        <span class="${verify.class}">
                            ${verify.text}
                        </span>
                    </div>

                    <div class="mb-2">
                        <strong>Duration:</strong>
                        ${durationText(g)}
                    </div>

                    <div class="mb-2">
                        <strong>Room Preference:</strong>
                        ${g.room_preference ?? '—'}
                    </div>

                    <div class="mb-2">
                        <strong>Contact:</strong>
                        ${g.number ?? '—'}
                    </div>

                    <hr>

                    <div>
                        <strong>Payment Status:</strong><br>
                        ${paymentBadge(g)}
                    </div>

                </div>

                <div class="card-footer bg-white text-end">
                    ${actionView(g)}
                </div>

            </div>
        </div>`;
            };

            /* ----------------------------------------------------------
             | Render Flow
             |---------------------------------------------------------- */
            const renderGuests = guests => {
                const container = document.querySelector(CONFIG.container);
                container.innerHTML = '';

                if (!guests.length) {
                    container.innerHTML = `
                <div class="col-12 text-center text-muted">
                    No hostel applications found.
                </div>`;
                    return;
                }

                guests.forEach(g => {
                    container.insertAdjacentHTML('beforeend', renderCard(g));
                });
            };

            /* ----------------------------------------------------------
             | Public API
             |---------------------------------------------------------- */
            window.GuestDashboard = {
                pay: id => {
                    window.location.href =
                        CONFIG.paymentUrl + '?guest_id=' + id;
                }
            };

            /* ----------------------------------------------------------
             | Init
             |---------------------------------------------------------- */
            document.addEventListener('DOMContentLoaded', async () => {
                try {
                    const res = await apiFetch();
                    renderGuests(res.data || []);
                } catch (err) {
                    console.error(err);
                    showMessage('Unable to load guest dashboard', 'danger');
                }
            });

        })();
    </script> --}}


    {{-- <div class="container my-5">

        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="mb-1">Guest Hostel Dashboard</h2>
            <p class="text-muted">Track your application, approval, and payment</p>
        </div>

        <!-- Message -->
        <div id="globalMessage"></div>

        <!-- Applications -->
        <div class="row g-4" id="guestApplications">
            <div class="col-12 text-center" id="loadingState">
                <div class="spinner-border text-primary"></div>
                <div class="mt-2 text-muted">Loading applications...</div>
            </div>
        </div>

    </div>
    <script>
        /*
            |--------------------------------------------------------------------------
            | Guest Dashboard Script (Enterprise Grade)
            |--------------------------------------------------------------------------
            | Handles:
            | - Approved / Pending / Rejected
            | - Verification states
            | - Payment eligibility
            | - Postpaid logic
            | - Clean normalization of API data
            |--------------------------------------------------------------------------
            */

        (() => {
            'use strict';

            /* ----------------------------------------------------
             | CONFIG
             |---------------------------------------------------- */
            const CONFIG = {
                apiUrl: "{{ url('/api/guest/approved-rejected-guest') }}",
                paymentUrl: "{{ url('/guest/payment') }}",
                tokenKey: 'token',
                authIdKey: 'auth-id',
                container: '#guestApplications'
            };

            /* ----------------------------------------------------
             | HELPERS
             |---------------------------------------------------- */
            const getToken = () => localStorage.getItem(CONFIG.tokenKey);
            const getAuthId = () => localStorage.getItem(CONFIG.authIdKey);

            const qs = (selector) => document.querySelector(selector);

            const showError = (msg) => {
                qs(CONFIG.container).innerHTML = `
            <div class="alert alert-danger">${msg}</div>
        `;
            };

            /* ----------------------------------------------------
             | API FETCH
             |---------------------------------------------------- */
            const apiFetch = async () => {
                const token = getToken();
                if (!token) throw new Error('Authentication token missing');

                const res = await fetch(CONFIG.apiUrl, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'auth-id': getAuthId()
                    }
                });

                if (!res.ok) {
                    throw new Error('Unable to load guest applications');
                }

                return res.json();
            };

            /* ----------------------------------------------------
             | DATA NORMALIZATION (CRITICAL)
             |---------------------------------------------------- */
            const normalizeGuest = (g) => ({
                id: g.id,
                name: g.name,
                email: g.email,
                scholarNo: g.scholar_no,

                // strict normalization
                isVerified: g.is_verified === 1 || g.is_verified === '1',
                status: (g.status || '').toLowerCase(),

                isPostpaid: Number(g.is_postpaid) === 1,
                feeWaiver: Number(g.fee_waiver) === 1
            });

            /* ----------------------------------------------------
             | UI RENDER HELPERS
             |---------------------------------------------------- */
            const renderStatusBadge = (status) => {
                const map = {
                    approved: 'success',
                    pending: 'secondary',
                    rejected: 'danger'
                };

                return `
            <span class="badge bg-${map[status] || 'secondary'}">
                ${status.toUpperCase()}
            </span>
        `;
            };

            const renderVerificationBadge = (isVerified) => {
                return isVerified ?
                    `<span class="badge bg-success">Verified</span>` :
                    `<span class="badge bg-warning text-dark">Pending Verification</span>`;
            };

            /* ----------------------------------------------------
             | ACTION LOGIC (NO BUSINESS VIOLATION)
             |---------------------------------------------------- */
            const renderAction = (g) => {

                // ❌ Application not approved
                if (g.status !== 'approved') {
                    return `<span class="text-muted">No action available</span>`;
                }

                // ❌ Approved but not verified
                if (!g.isVerified) {
                    return `<span class="text-warning">Awaiting verification</span>`;
                }

                // ✅ Postpaid user
                if (g.isPostpaid) {
                    return `<span class="badge bg-info">Postpaid</span>`;
                }

                // ✅ Eligible for payment
                return `
            <button class="btn btn-primary btn-sm"
                onclick="GuestDashboard.makePayment(${g.id})">
                <i class="fa fa-credit-card"></i> Pay Now
            </button>
        `;
            };

            /* ----------------------------------------------------
             | MAIN RENDER
             |---------------------------------------------------- */
            const renderGuests = (guests) => {
                const container = qs(CONFIG.container);
                container.innerHTML = '';

                if (!guests || guests.length === 0) {
                    container.innerHTML = `
                <div class="alert alert-info">
                    No hostel applications found.
                </div>
            `;
                    return;
                }

                guests.forEach((raw) => {
                    const g = normalizeGuest(raw);

                    container.insertAdjacentHTML('beforeend', `
                <div class="card shadow-sm mb-3">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">${g.name}</h6>
                                <small class="text-muted">
                                    Scholar No: ${g.scholarNo}
                                </small>
                            </div>
                            ${renderStatusBadge(g.status)}
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center">
                            ${renderVerificationBadge(g.isVerified)}
                            ${renderAction(g)}
                        </div>

                    </div>
                </div>
            `);
                });
            };

            /* ----------------------------------------------------
             | PAYMENT
             |---------------------------------------------------- */
            const makePayment = (guestId) => {
                window.location.href = `${CONFIG.paymentUrl}?guest_id=${guestId}`;
            };

            /* ----------------------------------------------------
             | PUBLIC API
             |---------------------------------------------------- */
            window.GuestDashboard = {
                makePayment
            };

            /* ----------------------------------------------------
             | INIT
             |---------------------------------------------------- */
            document.addEventListener('DOMContentLoaded', async () => {
                try {
                    const res = await apiFetch();
                    renderGuests(res.data);
                } catch (err) {
                    console.error(err);
                    showError(err.message);
                }
            });

        })();
    </script> --}}



    <script>
        (() => {
            'use strict';

            /* =====================================================
             | CONFIG (CENTRALIZED)
             ===================================================== */
            const CONFIG = {
                apiUrl: "{{ url('/api/guest/approved-rejected-guest') }}",
                paymentUrl: "{{ url('/guest/payment') }}",
                tokenKey: 'token',
                authIdKey: 'auth-id',
                container: '#guestApplications',
                messageBox: '#globalMessage'
            };

            /* =====================================================
             | INIT
             ===================================================== */
            document.addEventListener('DOMContentLoaded', fetchApplications);

            /* =====================================================
             | AUTH HELPERS
             ===================================================== */
            const getToken = () => localStorage.getItem(CONFIG.tokenKey);
            const getAuthId = () => localStorage.getItem(CONFIG.authIdKey);

            /* =====================================================
             | API FETCH (PROPERLY AUTHENTICATED)
             ===================================================== */
            async function apiFetch() {
                const token = getToken();
                const authId = getAuthId();

                if (!token || !authId) {
                    throw new Error('Authentication missing. Please login again.');
                }

                const res = await fetch(CONFIG.apiUrl, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'auth-id': authId
                    }
                });

                if (!res.ok) {
                    if (res.status === 401) {
                        throw new Error('Session expired. Please login again.');
                    }
                    throw new Error('Failed to fetch applications.');
                }

                return res.json();
            }

            /* =====================================================
             | MAIN FLOW
             ===================================================== */
            async function fetchApplications() {
                try {
                    const response = await apiFetch();
                    document.getElementById('loadingState')?.remove();

                    if (!response.success || !response.data?.length) {
                        showMessage('No hostel applications found.', 'info');
                        return;
                    }

                    renderApplications(response.data);
                } catch (err) {
                    document.getElementById('loadingState')?.remove();
                    showMessage(err.message, 'danger');
                }
            }

            /* =====================================================
             | NORMALIZE API DATA (CRITICAL)
             ===================================================== */
            function normalize(app) {
                return {
                    id: app.id,
                    name: app.name,
                    scholarNo: app.scholar_no,
                    appliedAt: app.created_at,

                    isVerified: app.is_verified === 1 || app.is_verified === '1',
                    status: (app.status || 'pending').toLowerCase(),

                    isPostpaid: app.is_postpaid == 1,
                    months: app.months,
                    days: app.days,
                    room: app.room_preference
                };
            }

            /* =====================================================
             | RENDER
             ===================================================== */
            function renderApplications(applications) {
                const container = document.querySelector(CONFIG.container);
                container.innerHTML = '';

                applications.forEach(app => {
                    const data = normalize(app);
                    container.insertAdjacentHTML('beforeend', buildCard(data));
                });
            }

            /* =====================================================
             | CARD UI (HIERARCHICAL)
             ===================================================== */
            function buildCard(app) {
                return `
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">

                <!-- HEADER -->
                <div class="card-header bg-white">
                    <strong>${app.name}</strong>
                    <div class="small text-muted">
                        Scholar No: ${app.scholarNo}
                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body">

                    ${renderTimeline(app)}

                    <hr>

                    <div class="small text-muted mb-2">
                        Stay Duration: ${getDuration(app)}
                    </div>

                    <div class="small text-muted mb-3">
                        Room Preference: ${app.room ?? '—'}
                    </div>

                    ${renderAction(app)}

                </div>

                <!-- FOOTER -->
                <div class="card-footer bg-light small text-muted">
                    Applied on ${formatDate(app.appliedAt)}
                </div>
            </div>
        </div>
        `;
            }

            /* =====================================================
             | TIMELINE (PROCESS-DRIVEN UI)
             ===================================================== */
            function renderTimeline(app) {
                return `
        <ul class="list-unstyled mb-0">

            ${step('Application Submitted', true)}

            ${step('Verification', app.isVerified,
                app.isVerified ? '' : 'Pending verification')}

            ${step('Application Status', app.status !== 'pending',
                app.status === 'approved' ? 'Approved' :
                app.status === 'rejected' ? 'Rejected' :
                app.status === 'cancelled' ? 'Cancelled' : 'Pending')}

            ${step('Payment',
                app.status === 'approved' && app.isVerified,
                paymentNote(app))}

        </ul>
        `;
            }

            function step(label, done, note = '') {
                return `
        <li class="d-flex align-items-start mb-2">
            <i class="fa fa-${done ? 'check-circle text-success' : 'circle text-muted'} me-2"></i>
            <div>
                <div>${label}</div>
                ${note ? `<div class="small text-muted">${note}</div>` : ''}
            </div>
        </li>
        `;
            }

            /* =====================================================
             | ACTION LOGIC (STRICT BUSINESS RULES)
             ===================================================== */
            function renderAction(app) {

                if (app.status === 'rejected') {
                    return badge('Application Rejected', 'danger');
                }

                if (app.status === 'cancelled') {
                    return badge('Application Cancelled', 'secondary');
                }

                if (!app.isVerified) {
                    return badge('Waiting for Verification', 'warning');
                }

                if (app.status !== 'approved') {
                    return badge('Under Review', 'secondary');
                }

                if (app.isPostpaid) {
                    return badge('Postpaid – Pay at Office', 'info');
                }

                return `
            <button class="btn btn-primary w-100"
                onclick="GuestDashboard.pay(${app.id})">
                Proceed to Payment
            </button>
        `;
            }

            function paymentNote(app) {
                if (app.status !== 'approved') return 'Waiting for approval';
                if (!app.isVerified) return 'Verification pending';
                if (app.isPostpaid) return 'Postpaid';
                return 'Payment pending';
            }

            function badge(text, type) {
                return `<span class="badge bg-${type}">${text}</span>`;
            }

            /* =====================================================
             | HELPERS
             ===================================================== */
            function getDuration(app) {
                if (app.months) return `${app.months} Month(s)`;
                if (app.days) return `${app.days} Day(s)`;
                return '—';
            }

            function formatDate(date) {
                return new Date(date).toLocaleDateString('en-IN', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }

            function showMessage(msg, type) {
                document.querySelector(CONFIG.messageBox).innerHTML =
                    `<div class="alert alert-${type}">${msg}</div>`;
            }

            /* =====================================================
             | PUBLIC API
             ===================================================== */
            window.GuestDashboard = {
                pay(id) {
                    window.location.href =
                        // `${CONFIG.paymentUrl}?application_id=${id}`;
                        `${CONFIG.paymentUrl}`;
                }
            };

        })();
    </script>
@endpush
