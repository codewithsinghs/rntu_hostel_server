@extends('resident.layout')

@section('content')
    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <h3 class="text-center">Confirm Your Payment</h3>
                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Order Information</a></div>

                <!-- Confirm Your Payment -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Order No</p>
                            <h3 id="orderNo" class="Payment"></h3>

                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/card.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Status</p>
                            <h3 id="orderStatus" class="Payment"></h3>

                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Status.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Purpose</p>
                            <h3 id="orderPurpose" class="Payment"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Purpose.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Amount</p>
                            <h3 class="Payment">₹<span id="orderAmount"></span></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png') }}" alt="" />
                        </div>
                    </div>

                </div>

                <div class="breadcrumbs"><a>Resident Information</a></div>
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Name</p>
                            <h3 id="residentName" class="Payment"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Name.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Email</p>
                            <h3 id="residentEmail" class="Payment"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Email.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Mobile No.</p>
                            <h3 id="residentPhone" class="Payment"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Phone.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Scholar No</p>
                            <h3 class="Payment" id="residentScholar"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Scholar.png') }}" alt="" />
                        </div>
                    </div>

                </div>

                <div class="breadcrumbs"><a>Invoices & Items</a></div>

                <div class="table-responsive shadow-sm rounded p-3">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice No</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Rate (₹)</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceList"></tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                <td><strong id="grandTotal"></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>



                <!-- Actions -->
                {{-- <div class="d-flex justify-content-end gap-2"> --}}
                <div class="d-flex justify-content-center my-4 gap-2">
                    <button class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
                    <button id="proceedPay" class="btn btn-success">Proceed to Pay</button>
                </div>

            </div>


        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const orderRef = urlParams.get("order_ref");

            if (!orderRef) {
                Swal.fire('Error', 'Invalid request: Order reference missing.', 'error');
                return;
            }

            let orderData = null;

            // 🔹 Fetch order confirmation details
            function fetchOrderDetails(orderRef) {
                return $.ajax({
                    url: `/api/resident/payment/confirmation/${orderRef}`,
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    }
                });
            }

            // 🔹 Populate order and resident info
            function renderOrderDetails(order) {
                orderData = order; // store globally for initiatePayment

                // Order Info
                $("#orderNo").text(order.order_number);
                $("#orderStatus").text(order.status);
                $("#orderPurpose").text(order.purpose);
                $("#orderAmount").text(order.amount);

                // Resident Info
                const resident = order.resident || {};
                $("#residentName").text(resident.name || "N/A");
                $("#residentEmail").text(resident.email || "N/A");
                $("#residentPhone").text(resident.number || "N/A");
                $("#residentScholar").text(resident.scholar_no || "N/A");
                $("#residentGuardian").text(resident.parent_no || "N/A");

                // Invoices
                $("#invoiceList").empty();
                let grandTotal = 0;

                order.invoices.forEach(inv => {
                    let itemRows = inv.items.map(item => `
                <tr>
                    <td>${inv.invoice_number}</td>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>₹${item.rate}</td>
                    <td>₹${item.amount}</td>
                </tr>
            `).join('');

                    $("#invoiceList").append(itemRows);
                    grandTotal += inv.items.reduce((sum, item) => sum + parseFloat(item.amount), 0);
                });

                $("#grandTotal").text(grandTotal.toFixed(2));
            }

            // 🔹 AJAX to initiate payment
            function initiatePayment(orderNumber) {
                Swal.fire({
                    title: 'Confirm Payment',
                    html: `<p>Order No: <strong>${orderNumber}</strong></p>
                   <p>Total Amount: <strong>₹${$("#grandTotal").text()}</strong></p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Proceed',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: '/api/resident/payment/initiate',
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'token': localStorage.getItem('token'),
                            'auth-id': localStorage.getItem('auth-id'),
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            order_no: orderNumber
                        },
                        success: function(res) {
                            if (res.success && res.data) {
                                const {
                                    txnUrl,
                                    body
                                } = res.data;

                                const form = document.createElement("form");
                                form.method = "POST";
                                form.action = txnUrl;

                                for (const key in body) {
                                    if (body.hasOwnProperty(key)) {
                                        const input = document.createElement("input");
                                        input.type = "hidden";
                                        input.name = key;
                                        input.value = body[key];
                                        form.appendChild(input);
                                    }
                                }

                                document.body.appendChild(form);
                                form.submit();
                            } else {
                                Swal.fire('Error', res.message || 'Failed to initiate payment',
                                    'error');
                            }
                        },
                        error: function(xhr, status, err) {
                            console.error('AJAX Error:', status, err);
                            Swal.fire('Error', 'Failed to initiate payment. Please try again.',
                                'error');
                        }
                    });
                });
            }

            // 🔹 Initial load
            fetchOrderDetails(orderRef)
                .done(function(res) {
                    if (!res.success) return Swal.fire('Error', res.message || 'Order not found', 'error');
                    renderOrderDetails(res.data);
                })
                .fail(function() {
                    Swal.fire('Error', 'Error fetching order details.', 'error');
                });

            // 🔹 Proceed to payment click
            $("#proceedPay").off("click").on("click", function() {
                if (!orderData) return Swal.fire('Error', 'Order not loaded.', 'error');
                initiatePayment(orderData.order_number);
            });
        });
    </script>
@endpush
