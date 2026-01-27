@extends('guest.layout')

@section('content')
    <div class="my-5">

        <!-- Header -->
        <div class="mb-4 text-center">
            <h2 class="mb-1">Guest Dashboard</h2>
            <p class="text-muted">
                Track your hostel application, verification, and payment status
            </p>
        </div>

        <!-- Global Message -->
        <div id="mainResponseMessage" class="mb-4"></div>

        <!-- Applications Container -->
        <div id="guestApplications" class="row g-4">

            <!-- Loading State -->
            <div class="col-12 text-center" id="loadingState">
                <div class="spinner-border text-primary"></div>
                <div class="text-muted mt-2">Loading your applications...</div>
            </div>

        </div>
    </div>


    <!-- Modal -->
    {{-- <div class="col-12 col-md-6 col-lg-4">

        <div class="card shadow-sm h-100 border-0">

            <!-- Header -->
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <strong>Rahul</strong>
                    <div class="small text-muted">Scholar No: 012345678</div>
                </div>

                <span class="badge bg-success">Approved</span>
            </div>

            <!-- Body -->
            <div class="card-body">

                <div class="mb-2">
                    <i class="fa fa-user-check text-success"></i>
                    Verification:
                    <strong class="text-success">Completed</strong>
                </div>

                <div class="mb-2">
                    <i class="fa fa-calendar-alt"></i>
                    Stay Duration:
                    <strong>3 Months</strong>
                </div>

                <div class="mb-2">
                    <i class="fa fa-bed"></i>
                    Room Preference:
                    <strong>Single</strong>
                </div>

                <div class="mb-2">
                    <i class="fa fa-phone"></i>
                    Contact:
                    <strong>9874563210</strong>
                </div>

                <hr>

                <div>
                    <strong>Payment Status:</strong>
                    <span class="badge bg-warning text-dark">
                        Pending ₹4500
                    </span>
                </div>

            </div>

            <!-- Footer -->
            <div class="card-footer bg-white text-end">
                <button class="btn btn-primary btn-sm">
                    <i class="fa fa-credit-card"></i> Pay Now
                </button>
            </div>

        </div>

    </div> --}}
@endsection

@push('scripts')
    <script>
        (() => {
            'use strict';

            /* --------------------------------------------------
             | Configuration
             |--------------------------------------------------- */
            const CONFIG = {
                apiUrl: "{{ url('/api/guest/approved-rejected-guest') }}",
                paymentUrl: "{{ url('/guest/payment') }}",
                tokenKey: 'token',
                authIdKey: 'auth-id',
                container: '#guestApplications'
            };

            /* --------------------------------------------------
             | Utilities
             |--------------------------------------------------- */
            const getToken = () => localStorage.getItem(CONFIG.tokenKey);
            const getAuthId = () => localStorage.getItem(CONFIG.authIdKey);

            const showMessage = (message, type = 'info') => {
                const box = document.getElementById('mainResponseMessage');
                if (!box) return;

                box.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            };

            const apiFetch = async () => {
                const token = getToken();
                if (!token) throw new Error('Authentication token missing');

                const response = await fetch(CONFIG.apiUrl, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'auth-id': getAuthId()
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch guest applications');
                }

                return response.json();
            };

            /* --------------------------------------------------
             | State Resolvers
             |--------------------------------------------------- */
            const STATUS_META = {
                approved: {
                    label: 'Approved',
                    class: 'bg-success'
                },
                rejected: {
                    label: 'Rejected',
                    class: 'bg-danger'
                },
                pending: {
                    label: 'Pending',
                    class: 'bg-secondary'
                },
                waiver_approved: {
                    label: 'Waiver Approved',
                    class: 'bg-success'
                },
                waiver_rejected: {
                    label: 'Waiver Rejected',
                    class: 'bg-warning text-dark'
                },
                cancelled: {
                    label: 'Cancelled',
                    class: 'bg-dark'
                }
            };

            const verificationBadge = verified =>
                verified == 1 ?
                `<span class="text-success fw-semibold">Completed</span>` :
                `<span class="text-warning fw-semibold">Pending</span>`;

            const durationText = g =>
                g.months ?
                `${g.months} Month${g.months > 1 ? 's' : ''}` :
                g.days ?
                `${g.days} Day${g.days > 1 ? 's' : ''}` :
                '—';

            const paymentBadge = g => {
                if (g.status === 'waiver_approved')
                    return `<span class="badge bg-success">Waived</span>`;

                if (g.is_postpaid == 1)
                    return `<span class="badge bg-info">Postpaid</span>`;

                if (Number(g.pending_amount) > 0)
                    return `<span class="badge bg-warning text-dark">
                        Pending ₹${g.pending_amount}
                    </span>`;

                return `<span class="badge bg-success">Completed</span>`;
            };

            const actionButton = g => {
                if (['rejected', 'cancelled'].includes(g.status))
                    return `<span class="text-muted">No Action</span>`;

                if (g.status === 'approved' && g.is_verified == 0)
                    return `<span class="badge bg-warning text-dark">
                        Awaiting Verification
                    </span>`;

                if (g.status === 'waiver_rejected')
                    return `
                <button class="btn btn-warning btn-sm"
                    onclick="GuestDashboard.pay(${g.id})">
                    Pay as Normal
                </button>
            `;

                if (g.is_postpaid == 1 || g.pending_amount == 0)
                    return `<span class="text-muted">—</span>`;

                return `
            <button class="btn btn-primary btn-sm"
                onclick="GuestDashboard.pay(${g.id})">
                <i class="fa fa-credit-card"></i> Pay Now
            </button>
        `;
            };

            /* --------------------------------------------------
             | Card Renderer
             |--------------------------------------------------- */
            const renderCard = g => {
                const status = STATUS_META[g.status] || STATUS_META.pending;

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
                    <span class="badge ${status.class}">
                        ${status.label}
                    </span>
                </div>

                <div class="card-body">

                    <div class="mb-2">
                        <i class="fa fa-user-check text-success me-1"></i>
                        Verification: ${verificationBadge(g.is_verified)}
                    </div>

                    <div class="mb-2">
                        <i class="fa fa-calendar-alt me-1"></i>
                        Duration: <strong>${durationText(g)}</strong>
                    </div>

                    <div class="mb-2">
                        <i class="fa fa-bed me-1"></i>
                        Room Preference:
                        <strong>${g.room_preference ?? '—'}</strong>
                    </div>

                    <div class="mb-2">
                        <i class="fa fa-phone me-1"></i>
                        Contact:
                        <strong>${g.number ?? '—'}</strong>
                    </div>

                    <hr>

                    <div>
                        <strong>Payment Status:</strong>
                        ${paymentBadge(g)}
                    </div>

                </div>

                <div class="card-footer bg-white text-end">
                    ${actionButton(g)}
                </div>

            </div>
        </div>`;
            };

            /* --------------------------------------------------
             | Render Flow
             |--------------------------------------------------- */
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

            /* --------------------------------------------------
             | Public API
             |--------------------------------------------------- */
            window.GuestDashboard = {
                pay: id => window.location.href = CONFIG.paymentUrl + '?guest_id=' + id
            };

            /* --------------------------------------------------
             | Init
             |--------------------------------------------------- */
            document.addEventListener('DOMContentLoaded', async () => {
                try {
                    const res = await apiFetch();
                    renderGuests(res.data || []);
                } catch (err) {
                    console.error(err);
                    showMessage('Unable to load guest applications', 'danger');
                }
            });

        })();
    </script>
@endpush
