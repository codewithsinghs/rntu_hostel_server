@extends('resident.layout')

@section('content')
    {{-- <div class="container my-4">
        <div class="card-body">
            <div class="text-muted">Active Facilities</div>
            <h3 class="fw-bold">5</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted">Extra Payable</div>
                <h3 class="fw-bold">₹2,300</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted">Paid Till Date</div>
                <h3 class="fw-bold text-success">₹8,500</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted">Pending Dues</div>
                <h3 class="fw-bold text-danger">₹1,200</h3>
            </div>
        </div>
    </div>
     <!-- ACTIVE SUBSCRIPTIONS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold">Active Facility Subscriptions</div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Facility</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Billing</th>
                        <th>Status</th>
                        <th>Subscribed On</th>
                    </tr>
                </thead>
                <tbody id="activeSubscriptions">
                    <!-- Dummy Data -->
                </tbody>
            </table>
        </div>
    </div>


    <!-- AVAILABLE FACILITIES -->
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">Available Facilities</div>
        <div class="row g-3 p-3" id="availableFacilities">
            <!-- Dummy Cards -->
        </div>
    </div>
     --}}

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">



                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">My Hostel Subscriptions</h3>
                        <p class="text-muted mb-0">Facilities & services availed apart from hostel fee</p>
                    </div>
                    @php
                        $year = now()->year;
                        $session = $year . '–' . substr($year + 1, -2);
                    @endphp

                    <span class="badge bg-primary fs-6">Session {{ $session }}</span>

                </div>

                <!-- Active Subscriptions -->
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Active Subscriptions</h5>
                    </div>
                    <div class="card-body w-100 p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Facility</th>
                                    <th>Type</th>
                                    <th>Billing</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Subscribed On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Hostel Subscription</strong><br>
                                        {{-- <small class="text-muted">Breakfast, Lunch & Dinner</small> --}}
                                    </td>
                                    <td>On-Demand</td>
                                    <td>Quarterly</td>
                                    <td>₹ 18,000</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td></td>
                                </tr>
                                {{-- <tr>
                                        <td>
                                            <strong>Laundry Service</strong><br>
                                            <small class="text-muted">Up to 30 clothes / month</small>
                                        </td>
                                        <td>Optional</td>
                                        <td>Monthly</td>
                                        <td>₹ 600</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>10 Aug 2024</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>High-Speed Internet</strong><br>
                                            <small class="text-muted">Unlimited (LAN + WiFi)</small>
                                        </td>
                                        <td>Optional</td>
                                        <td>Monthly</td>
                                        <td>₹ 300</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                        <td>02 Sep 2024</td>
                                    </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Extra / One-time Payables -->
                {{-- <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">Extra Payable / One-Time Charges</h5>
                        </div>
                        <div class="card-body w-100  p-0">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Room Damage Fine</td>
                                        <td>Penalty</td>
                                        <td>₹ 1,200</td>
                                        <td>15 Oct 2024</td>
                                        <td><span class="badge bg-danger">Unpaid</span></td>
                                    </tr>
                                    <tr>
                                        <td>Extra Mattress</td>
                                        <td>Add-On</td>
                                        <td>₹ 800</td>
                                        <td>—</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Available Facilities -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">Available Facilities</h5>
                        </div>
                        <div class="card-body  w-100 ">
                            <div class="row g-3">

                                <div class="col-md-6 col-lg-4">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-bold mb-1">Gym Membership</h6>
                                        <p class="text-muted small mb-2">Access to hostel gym & equipment</p>
                                        <ul class="list-unstyled small mb-3">
                                            <li>• Billing: Monthly</li>
                                            <li>• Amount: ₹ 400</li>
                                        </ul>
                                        <button class="btn btn-outline-primary btn-sm w-100">Avail Facility</button>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-bold mb-1">Vehicle Parking</h6>
                                        <p class="text-muted small mb-2">Two-wheeler parking space</p>
                                        <ul class="list-unstyled small mb-3">
                                            <li>• Billing: Monthly</li>
                                            <li>• Amount: ₹ 200</li>
                                        </ul>
                                        <button class="btn btn-outline-primary btn-sm w-100">Avail Facility</button>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-bold mb-1">Study Room Access</h6>
                                        <p class="text-muted small mb-2">24x7 silent study area</p>
                                        <ul class="list-unstyled small mb-3">
                                            <li>• Billing: One-Time</li>
                                            <li>• Amount: ₹ 500</li>
                                        </ul>
                                        <button class="btn btn-outline-primary btn-sm w-100">Avail Facility</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> --}}

            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        {
            id: 1,
            name: 'Gym Access',
            description: 'Unlimited gym access for residents',
            price: 1200,
            billing: 'Monthly'
        }, {
            id: 2,
            name: 'WiFi Premium',
            description: 'High-speed internet plan',
            price: 500,
            billing: 'Monthly'
        }]
        };


        renderActive(apiResponse.active);
        renderAvailable(apiResponse.available);


        function renderActive(list) {
            const tbody = document.getElementById('activeSubscriptions');
            tbody.innerHTML = '';


            list.forEach(item => {
                tbody.innerHTML += `
<tr>
<td>${item.name}</td>
<td>${item.type}</td>
<td>₹${item.amount}</td>
<td>${item.billing}</td>
<td><span class="badge bg-success">Active</span></td>
<td>${item.subscribed_on}</td>
</tr>`;
            });
        }


        function renderAvailable(list) {
            const container = document.getElementById('availableFacilities');
            container.innerHTML = '';


            list.forEach(item => {
                container.innerHTML += `
<div class="col-md-4">
<div class="card h-100 shadow-sm">
<div class="card-body d-flex flex-column">
<h6 class="fw-bold">${item.name}</h6>
<p class="text-muted small">${item.description}</p>
<div class="mt-auto">
<div class="fw-semibold">₹${item.price} <span class="text-muted">/ ${item.billing}</span></div>
<button class="btn btn-primary btn-sm mt-2 w-100" onclick="subscribe(${item.id})">
Avail Facility
</button>
</div>
</div>
</div>
</div>`;
            });
        }


        window.subscribe = function(id) {
        alert('Subscription request sent for Facility ID: ' + id);
        }
        });
    </script>
@endpush
