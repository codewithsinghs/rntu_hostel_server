@extends('resident.layout')

@section('content')


    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Confirm Your Payment</a></div>

                <!-- Confirm Your Payment -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Order No</p>
                            <h3 id="orderNo" class="Payment">5</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/card.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Status</p>
                            <h3 id="orderStatus" class="Payment">Pending</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Status.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Purpose</p>
                            <h3 id="orderPurpose" class="Payment">Hostel Fee</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Purpose.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Amount</p>
                            <h3 class="Payment">₹<span id="orderAmount">10,000</span></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png')}}" alt="" />
                        </div>
                    </div>



                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Name</p>
                            <h3 id="residentName" class="Payment">Rajat Pradhan</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Name.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Email</p>
                            <h3 id="residentEmail" class="Payment">Prajat917@gmail.com</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Email.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Mobile No.</p>
                            <h3 id="residentPhone" class="Payment">7024393158</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Phone.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Scholar No</p>
                            <h3 class="Payment" id="residentScholar">RNTU7654576545</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Scholar.png')}}" alt="" />
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

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Recent Requests List</a></div>

                <div class="overflow-auto">
                    <table class="status-table table table-hover table-bordered align-middle" cellspacing="0"
                        cellpadding="8" width="100%">
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

                <button class="red" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="submitted" id="proceedPay">Proceed to Pay</button>

            </div>
        </div>
    </section>

    <div class="container my-4">

        <div class="container py-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Confirm Your Payment</h4>
                </div>
                <div class="card-body">

                    <!-- Order Info -->
                    <div class="mb-4">
                        <h5 class="text-secondary">Order Information</h5>
                        <div class="row g-2">
                            <div class="col-md-3"><strong>Order No:</strong> <span id="orderNo"></span></div>
                            <div class="col-md-3"><strong>Status:</strong> <span id="orderStatus"></span></div>
                            <div class="col-md-3"><strong>Purpose:</strong> <span id="orderPurpose"></span></div>
                            <div class="col-md-3"><strong>Total Amount:</strong> ₹<span id="orderAmount"></span></div>
                        </div>
                    </div>

                    <!-- Resident Info -->
                    <div class="mb-4">
                        <h5 class="text-secondary">Resident Information</h5>
                        <div class="row g-2">
                            <div class="col-md-3"><strong>Name:</strong> <span id="residentName"></span></div>
                            <div class="col-md-3"><strong>Email:</strong> <span id="residentEmail"></span></div>
                            <div class="col-md-2"><strong>Phone:</strong> <span id="residentPhone"></span></div>
                            <div class="col-md-2"><strong>Scholar No:</strong> <span id="residentScholar"></span></div>
                            <div class="col-md-2"><strong>Parent/Guardian:</strong> <span id="residentGuardian"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices & Items -->
                    <div class="mb-4">
                        <h5 class="text-secondary">Invoices & Items</h5>
                        <div class="table-responsive shadow-sm rounded">
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
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
                        <button id="proceedPay" class="btn btn-success">Proceed to Pay</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{--
        <script>
            $(document).ready(function () {
                const urlParams = new URLSearchParams(window.location.search);
                const orderRef = urlParams.get("order_ref");
                const proceedBtn = document.getElementById("proceedPay");

                if (!orderRef) return alert("Invalid request: Order reference missing.");

                $.ajax({
                    url: `/api/resident/payment/confirmation/${orderRef}`,
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    },
                    success: function (res) {
                        if (!res.success) return alert(res.message || "Order not found.");

                        const order = res.data;

                        // Order info
                        $("#orderNo").text(order.order_number);
                        $("#orderStatus").text(order.status);
                        $("#orderPurpose").text(order.purpose);
                        $("#orderAmount").text(order.amount);

                        // Resident info
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
                            inv.items.forEach(item => {
                                $("#invoiceList").append(`
                                                                    <tr>
                                                                        <td>${inv.invoice_number}</td>
                                                                        <td>${item.name}</td>
                                                                        <td>${item.qty}</td>
                                                                        <td>₹${item.rate}</td>
                                                                        <td>₹${item.amount}</td>
                                                                    </tr>
                                                                `);
                                grandTotal += parseFloat(item.amount);
                            });
                        });
                        $("#grandTotal").text(grandTotal);

                        // // Proceed to payment
                        $("#proceedPay").off("click").on("click", function () {




                            selectedInvoices = order.order_number
                            initiatePayment(selectedInvoices);
                        },
                            error: function () {
                                alert("Error fetching order details.");
                            }
                                                                });

                // // 🔹 Proceed to payment with Swal confirmation
                // proceedBtn.addEventListener("click", () => {
                //     if (selectedInvoices.length === 0) return;

                //     const totalAmount = selectedInvoices.reduce((sum, inv) => sum + inv.amount, 0);

                //     Swal.fire({
                //         title: 'Confirm Payment',
                //         html: `<p>Total invoices selected: <strong>${selectedInvoices.length}</strong></p>
                //         <p>Total Amount to Pay: <strong>₹${totalAmount.toFixed(2)}</strong></p>`,
                //         icon: 'question',
                //         showCancelButton: true,
                //         confirmButtonText: 'Proceed',
                //         cancelButtonText: 'Cancel'
                //     }).then(result => {
                //         if (result.isConfirmed) {
                //             initiatePayment(selectedInvoices);
                //         }
                //     });
                // });

                // 🔹 AJAX to initiate payment
                function initiatePayment(invoices) {
                    $.ajax({
                        url: '/api/resident/payment/initiate',
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'token': localStorage.getItem('token'),
                            'auth-id': localStorage.getItem('auth-id')
                        },
                        // data: JSON.stringify({
                        //     invoices
                        // }),
                        data: { order_no: invoices },

                        console.log(data) 
                                                                    success: function (res) {
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
                                form.submit(); // Redirect to payment gateway
                            } else {
                                Swal.fire('Error', res.message || 'Failed to initiate payment',
                                    'error');
                            }
                        },
                        error: function (xhr, status, err) {
                            console.error('AJAX Error:', status, err);
                            Swal.fire('Error', 'Failed to initiate payment. Please try again.',
                                'error');
                        }
                    });
                }

            });
        </script> --}}


        <script>
            $(document).ready(function () {
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
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
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
                            success: function (res) {
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
                            error: function (xhr, status, err) {
                                console.error('AJAX Error:', status, err);
                                Swal.fire('Error', 'Failed to initiate payment. Please try again.',
                                    'error');
                            }
                        });
                    });
                }

                // 🔹 Initial load
                fetchOrderDetails(orderRef)
                    .done(function (res) {
                        if (!res.success) return Swal.fire('Error', res.message || 'Order not found', 'error');
                        renderOrderDetails(res.data);
                    })
                    .fail(function () {
                        Swal.fire('Error', 'Error fetching order details.', 'error');
                    });

                // 🔹 Proceed to payment click
                $("#proceedPay").off("click").on("click", function () {
                    if (!orderData) return Swal.fire('Error', 'Order not loaded.', 'error');
                    initiatePayment(orderData.order_number);
                });
            });
        </script>





        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection