@extends('resident.layout')

@section('content')
    <style>
        .status-table thead th {
            color: white !important;
            font-weight: 900;
        }

        /* Print only #receiptArea */
        @media print {
            body * {
                visibility: hidden !important;
            }

            #receiptArea,
            #receiptArea * {
                visibility: visible !important;
            }

            #receiptArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                margin: 0;
            }
        }

        /* Modal styling */
        .receipt-box {
            border-radius: 12px;
            background: #ffffff;
            border: none;
            box-shadow: rgba(0, 0, 0, 0.12) 0px 6px 20px;
            padding: 0 10px;
        }

        /* Sections */
        .receipt-section {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* Amount cards */
        .amount-box {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
        }

        .amount-box div span {
            font-size: 13px;
            color: #6c757d;
        }

        .amount-box div h5 {
            margin: 0;
        }

        /* Table */
        .receipt-table th,
        .receipt-table td {
            padding: 8px !important;
            font-size: 14px;
        }

        /* Print Only */
        @media print {
            body * {
                visibility: hidden;
            }

            #receiptModal,
            #receiptModal * {
                visibility: visible;
            }

            #receiptModal .modal-dialog {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .modal-footer,
            .btn-close {
                display: none !important;
            }
        }

        /* Mobile Friendly */
        @media (max-width: 576px) {
            .amount-box {
                flex-direction: column;
                gap: 10px;
            }

            .receipt-box {
                padding: 5px !important;
            }
        }
    </style>



    @if (session('success'))
        <div class="alert alert-success text-center mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Overview -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Payments Overview</a></div>

                <!-- Payments Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Transactions</p>
                            <h3 id="total_transactions">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Transactions</p>
                            <h3 id="pending_transactions">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Last Payment</p>
                            <h3 id="last_transaction"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/neutral.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Subscription Due Date</p>
                            <h3 id="due_date"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Pending Payments Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Pending Payments</a></div>

                <div class="overflow-auto">

                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%" id="payments-table"
                        style="display: none;">
                        <thead class="table-dark bg-info fw-bold">
                            <tr>
                                <th>Select</th>
                                <th>Resident Name</th>
                                <th>Invoice Number</th>
                                <th>Total Amount</th>
                                <th>Amount Paid</th>
                                <th>Remaining Amount</th>
                                <th>Due Date</th>
                                <th>Invoice Items</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="payments-body"></tbody>
                    </table>

                    <p id="no-payments" class="text-danger text-center mt-3" style="display: none;">No pending payments
                        found.</p>

                    <div class="text-center mt-3">
                        <button id="proceedPaymentBtn" class="btn btn-success" style="display: none;">Proceed to
                            Payment</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <table class="table table-bordered table-striped" id="payments-table" style="display: none;">
                    <thead class="table-dark">
                        <tr>
                            <th>Select</th>
                            <th>Resident Name</th>
                            <th>Invoice Number</th>
                            <th>Total Amount</th>
                            <th>Amount Paid</th>
                            <th>Remaining Amount</th>
                            <th>Due Date</th>
                            <th>Invoice Items</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="payments-body"></tbody>
                </table>

                <p id="no-payments" class="text-danger text-center mt-3" style="display: none;">No pending payments found.
                </p>

                <div class="text-center mt-3">
                    <button id="proceedPaymentBtn" class="btn btn-success" style="display: none;">Proceed to
                        Payment</button>
                </div>

            </div>
        </div>
    </section> --}}

    <!-- All Transactions Table -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Recent Transactions</a></div>

                <div class="overflow-auto">

                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead class="table-dark bg-secondary text-white" style="color:white !important;">
                            <tr>
                                <th>#</th>
                                <th>Resident Name</th>
                                <th>Invoice Number</th>
                                <th>Order Number</th>
                                {{-- <th>Total Amount</th> --}}
                                <th>Amount Paid</th>
                                <th style="width: 80px;">Balance</th>
                                <th>Payment Date</th>
                                <th>Invoice Items</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="recent-transactions-list">
                            <!-- Data will be injected here dynamically -->
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- Invoice Items Modal -->
    <div class="modal fade" id="invoiceItemsModal" tabindex="-1" aria-labelledby="invoiceItemsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceItemsLabel">Invoice Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Price (₹)</th>
                                <th>Total Amount (₹)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody">
                            <!-- JS will inject rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal to show invoice items -->
    {{-- <div class="modal fade" id="invoiceItemsModal" tabindex="-1" aria-labelledby="invoiceItemsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceItemsModalLabel">Invoice Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-items-body">
                            <!-- Items will be injected here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content receipt-box" id="receiptArea">


                <div class="modal-header border-0">
                    <h4 class="modal-title fw-bold">Payment Receipt</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Header -->
                    <div class="receipt-header text-center mb-3">
                        <h3 class="fw-bold mb-1">Transaction Info</h3>
                        <p class="text-muted small mb-0">Payment Confirmation</p>
                    </div>

                    <!-- Resident Info -->
                    <div class="receipt-section">
                        <h5 class="section-title  bg-light">Resident Details</h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Name:</strong> <span id="rName"></span></p>
                                <p class=""><strong>Hostel:</strong> <span id="rFlat"></span></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Phone:</strong> <span id="rPhone"></span></p>
                                <p class=""><strong>Email:</strong> <span id="rEmail"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Info -->
                    <div class="receipt-section">
                        <div class="d-flex bg-light">
                            <h5 class="section-title">Invoice Summary</h5>
                            {{-- <strong class="ms-auto">Order Number :<span id="ordNum"></span></strong> --}}
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <p class="mb-1"><strong>Invoice No:</strong> <span id="invNumber"></span></p>
                                <p class="mb-1"><strong>Order Number:</strong> <span id="ordNum"></span></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Status:</strong> <span id="invStatus"></span></p>
                                <p class="mb-1"><strong>Paid At:</strong> <span id="invDate"></span></p>
                                {{-- <p class="mb-1"><strong>Due Date:</strong> <span id="invDue"></span></p> --}}
                            </div>
                            <div class="col-6">
                                <p class=""><strong>Transaction ID:</strong> <span id="txnStatus"></span></p>
                            </div>
                            <div class="col-6">
                                <p class=""><strong>Payment Mode:</strong> <span id="payMod"></span></p>
                            </div>

                            <div class="col-12">
                                <div class="amount-box">
                                    <div>
                                        <span>Total Amount</span>
                                        <h5 id="invTotal"></h5>
                                    </div>
                                    <div>
                                        <span>Paid Amount</span>
                                        <h5 class="text-success" id="invPaid"></h5>
                                    </div>
                                    <div>
                                        <span>Remaining</span>
                                        <h5 class="text-danger" id="invRemaining"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="receipt-section">
                        <h5 class="section-title">Payment Breakdown</h5>

                        <table class="table table-bordered receipt-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="receiptTxnBody"></tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-3">
                        <p class="text-muted small">This is an electronically generated receipt. No signature required.</p>
                    </div>

                </div>

                <div class="modal-footer receipt-footer">
                    <button class="btn btn-primary w-100" onclick="printReceipt()">Print Receipt</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Invoices Items and confirmation -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paymentsTable = document.getElementById("payments-table");
            const paymentsBody = document.getElementById("payments-body");
            const noPaymentsMsg = document.getElementById("no-payments");
            const proceedBtn = document.getElementById("proceedPaymentBtn");

            let selectedInvoices = [];

            // 🔹 Fetch pending payments
            fetch(`/api/resident/pending-payments`, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {

                        // console.log(data.summary);
                        // ✅ Update Summary Cards
                        if (data.summary) {
                            const summary = data.summary;

                            document.getElementById("total_transactions").innerText = summary
                                .total_transactions;
                            document.getElementById("pending_transactions").innerText = summary
                                .pending_transactions;
                            document.getElementById("last_transaction").innerText = summary
                                .last_transaction;
                            document.getElementById("due_date").innerText = summary
                                .due_date;
                        }

                        paymentsTable.style.display = "";
                        paymentsBody.innerHTML = "";

                        data.data.forEach(payment => {
                            const remainingAmount = parseFloat(payment.remaining_amount || 0);
                            const totalAmount = parseFloat(payment.paid_amount || 0) + remainingAmount;
                            const statusBadge = remainingAmount > 0 ? 'bg-warning text-dark' :
                                'bg-success';
                            const paymentStatus = remainingAmount > 0 ? 'Pending' : 'Paid';

                            const row = document.createElement('tr');
                            row.innerHTML = `
                                    <td>
                        <input type="checkbox" class="invoice-select"
                            data-invId="${payment.id}"
                            data-resId="${payment.resident_id}"
                            data-amount="${payment.remaining_amount}"
                            data-invoiceNumber="${payment.invoice_number}"
                            data-remark="${payment.description || ''}">
                    </td>
                    <td>${payment.resident_name || 'N/A'}</td>
                    <td>${payment.invoice_number || 'N/A'}</td>
                    <td>${totalAmount.toFixed(2)}</td>
                    <td>${parseFloat(payment.paid_amount || 0).toFixed(2)}</td>
                    <td>${remainingAmount.toFixed(2)}</td>
                    <td>${payment.due_date || 'N/A'}</td>
                    <td><button class="btn btn-info btn-sm" onclick="viewInvoiceItems(${payment.id})">View Items</button></td>
                    <td><span class="badge ${statusBadge}">${paymentStatus}</span></td>
                     <td>
                            ${payment.remaining_amount > 0
                            ? `<button type="button" class="btn btn-success btn-sm single-payment-btn"
                                                                                                                                                                                                                                                                data-invId="${payment.id}"
                                                                                                                                                                                                                                                                data-resId="${payment.resident_id}"
                                                                                                                                                                                                                                                                data-amount="${payment.remaining_amount}"
                                                                                                                                                                                                                                                                data-invoiceNumber="${payment.invoice_number}"
                                                                                                                                                                                                                                                                data-remark="${payment.description || ''}">
                                                                                                                                                                                                                                                        Make Payment
                                                                                                                                                                                                                                                    </button>`
                            : `<span class="text-muted">Paid</span>`}
                        </td>
                `;
                            paymentsBody.appendChild(row);
                        });

                        // <td>${payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-GB') : 'N/A'}</td>

                        // ✅ Checkbox selection for multiple payments
                        // ✅ Checkbox selection for multiple payments
                        document.querySelectorAll(".invoice-select").forEach(cb => {
                            cb.addEventListener('change', () => {
                                selectedInvoices = Array.from(document.querySelectorAll(
                                        ".invoice-select:checked"))
                                    .map(cb => ({
                                        invId: cb.dataset.invid,
                                        resId: cb.dataset.resid,
                                        amount: parseFloat(cb.dataset.amount),
                                        invoiceNumber: cb.dataset.invoicenumber,
                                        remark: cb.dataset.remark
                                    }));

                                console.log("Selected Invoices (Multiple):",
                                    selectedInvoices); // Debug log
                                // proceedBtn.disabled = selectedInvoices.length === 0;
                                // Show or hide the proceed button based on count
                                if (selectedInvoices.length > 1) {
                                    proceedBtn.style.display =
                                        "inline-block"; // or "block" depending on layout
                                } else {
                                    proceedBtn.style.display = "none";
                                }
                            });
                        });

                        // ✅ Single payment buttons
                        document.querySelectorAll(".single-payment-btn").forEach(btn => {
                            btn.addEventListener("click", () => {
                                const invoice = [{
                                    invId: btn.dataset.invid,
                                    resId: btn.dataset.resid,
                                    amount: parseFloat(btn.dataset.amount),
                                    invoiceNumber: btn.dataset.invoicenumber,
                                    remark: btn.dataset.remark
                                }];

                                console.log("Selected Invoice (Single):", invoice); // Debug log
                                confirmAndInitiatePayment(invoice);
                            });
                        });





                    } else {
                        noPaymentsMsg.style.display = "block";
                    }
                })
                .catch(err => {
                    console.error('Error fetching invoices:', err);
                    noPaymentsMsg.innerText = "Error loading payments.";
                    noPaymentsMsg.style.display = "block";
                });

            // ✅ Multiple payment proceed button
            proceedBtn.addEventListener("click", () => {
                if (selectedInvoices.length === 0) return;
                confirmAndInitiatePayment(selectedInvoices);
            });

            // 🔹 Confirmation & Initiate Payment
            // function confirmAndInitiatePayment(invoices) {
            //     const totalAmount = invoices.reduce((sum, inv) => sum + parseFloat(inv.amount), 0);

            //     // Debug log for invoices
            //     console.log("Invoices to process:", invoices);

            //     Swal.fire({
            //         title: 'Confirm Payment',
            //         html: `<p>Invoices selected: <strong>${invoices.length}</strong></p>
        //         <p>Total Amount: <strong>₹${totalAmount.toFixed(2)}</strong></p>`,
            //         icon: 'question',
            //         showCancelButton: true,
            //         confirmButtonText: 'Proceed',
            //         cancelButtonText: 'Cancel'
            //     }).then(result => {
            //         if (result.isConfirmed) {
            //             // ✅ Manually construct query string
            //             let queryString = invoices.map((inv, index) => {
            //                 return `invoices[${index}][invoice_id]=${encodeURIComponent(inv.invId)}&` +
            //                     `invoices[${index}][resident_id]=${encodeURIComponent(inv.resId)}&` +
            //                     `invoices[${index}][amount]=${encodeURIComponent(inv.amount)}&` +
            //                     `invoices[${index}][invoice_number]=${encodeURIComponent(inv.invoiceNumber)}&` +
            //                     `invoices[${index}][remark]=${encodeURIComponent(inv.remark || '')}`;
            //             }).join('&');

            //             console.log("Redirect query string:", queryString); // Debug

            //             // Redirect to confirmation page
            //             window.location.href = `/resident/payment/confirm?${queryString}`;
            //         }
            //     });
            // }


            function confirmAndInitiatePayment(invoices) {
                const totalAmount = invoices.reduce((sum, inv) => sum + parseFloat(inv.amount), 0);

                console.log("Invoices to process:", invoices); // ✅ Debug


                Swal.fire({
                    title: 'Confirm Payment',
                    html: `
                            <p>Invoices selected: <strong>${invoices.length}</strong></p>
                            <div style="text-align: center;">
                                ${invoices.map(inv => `
                                                                                                                                                                                                        <p>
                                                                                                                                                                                                            <strong>Invoice #${inv.invoiceNumber}</strong> - 
                                                                                                                                                                                                            Amount: ₹${inv.amount.toFixed(2)}
                                                                                                                                                                                                        </p>
                                                                                                                                                                                                    `).join('')}
                            </div>
                            <hr>
                            <p>Total Amount: <strong>₹${totalAmount.toFixed(2)}</strong></p>
                        
                            
                        `,

                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Proceed',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        // ✅ Send to your API endpoint to confirm
                        fetch('/api/resident/payment/confirm', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'token': localStorage.getItem('token'),
                                    'auth-id': localStorage.getItem('auth-id')
                                },
                                body: JSON.stringify({
                                    invoices
                                })
                            })
                            .then(res => res.json())
                            .then(response => {
                                if (response.success && response.data) {
                                    console.log("Confirmation API response:", response.data);

                                    // Now initiate payment
                                    // initiatePayment(response.data); // you can reuse your existing AJAX payment initiation

                                    // Redirect to confirmation page
                                    // window.location.href = `/resident/payment/confirm?${queryString}`;
                                    // Redirect with order_id only (clean URL 🚀)
                                    //    process.kill();
                                    //     window.location.href = `/resident/payment/confirm-page?order_no=${response.data.reference}`;
                                    // window.location.href = `/resident/pay/confirm?order_no=${encodeURIComponent(response.data.order_no)}&amount=${encodeURIComponent(response.data.total)}`;
                                    window.location.href =
                                        `/resident/pay/confirm?order_ref=${encodeURIComponent(response.data.reference)}&amount=${encodeURIComponent(response.data.total)}`;

                                } else {
                                    Swal.fire('Error', response.message || 'Failed to confirm payment',
                                        'error');
                                }


                            })
                            .catch(err => {
                                console.error("API Error:", err);
                                Swal.fire('Error', 'Failed to confirm payment', 'error');
                            });
                    }
                });
            }



            // ✅ SweetAlert confirmation and POST redirect
            // function confirmAndInitiatePayment(invoices) {
            //     const totalAmount = invoices.reduce((sum, inv) => sum + parseFloat(inv.amount), 0);
            //     Swal.fire({
            //         title: 'Confirm Payment',
            //         html: `<p>Invoices selected: <strong>${invoices.length}</strong></p>
        //    <p>Total Amount: <strong>₹${totalAmount.toFixed(2)}</strong></p>`,
            //         icon: 'question',
            //         showCancelButton: true,
            //         confirmButtonText: 'Proceed',
            //         cancelButtonText: 'Cancel'
            //     }).then(result => {
            //         if (result.isConfirmed) {
            //             console.log('Confirmed Invoices:', invoices);

            //             // ✅ Create form and POST JSON data
            //             const form = document.createElement('form');
            //             form.method = 'POST';
            //             form.action = '/resident/payment/confirm';

            //             const csrfInput = document.createElement('input');
            //             csrfInput.type = 'hidden';
            //             csrfInput.name = '_token';
            //             csrfInput.value = '{{ csrf_token() }}';
            //             form.appendChild(csrfInput);

            //             const dataInput = document.createElement('input');
            //             dataInput.type = 'hidden';
            //             dataInput.name = 'invoices';
            //             dataInput.value = JSON.stringify(invoices);
            //             form.appendChild(dataInput);

            //             document.body.appendChild(form);
            //             form.submit();
            //         }
            //     });
            // }

            // Optional AJAX payment (if you want to skip confirmation page)
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
                    data: JSON.stringify({
                        invoices
                    }),
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
                            Swal.fire('Error', res.message || 'Failed to initiate payment', 'error');
                        }
                    },
                    error: function(xhr, status, err) {
                        console.error('AJAX Error:', status, err);
                        Swal.fire('Error', 'Failed to initiate payment. Please try again.', 'error');
                    }
                });
            }
        });
    </script>

    <!-- Invoice Items Modal -->
    <script>
        const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceItemsModal'));

        async function viewInvoiceItems(id) {
            try {
                const res = await fetch('/api/resident/pending-payments/invoice-items', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    body: JSON.stringify({
                        invoice_id: id
                    })
                });

                const data = await res.json();


                if (data.success) {
                    const rows = data.items.map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.description}</td>
                    <td>${item.item_type}</td>
                    <td class="text-end">${parseFloat(item.price).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.total_amount).toFixed(2)}</td>
                   <td>
                        ${
                            item.status 
                                ? `<span class="badge bg-success">${item.status}</span>`
                                : (item.remarks ? item.remarks : '')
                        }
                    </td>
                </tr>
            `).join('');

                    document.getElementById('invoiceItemsBody').innerHTML = rows;
                    new bootstrap.Modal(document.getElementById('invoiceItemsModal')).show();
                } else {
                    console.warn("No items found or error in response");
                }
            } catch (error) {
                console.error("Error fetching invoice items:", error);
            }
        }
    </script>

    <!-- Transactions -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchRecentTransactions();
        });

        async function fetchRecentTransactions(limit = 10) {

            const token = localStorage.getItem("token");

            if (!token) {
                console.error("Token not found");
                window.location.href = "/login";
                return;
            }

            try {
                const response = await fetch(`/api/resident/recent-transactions?limit=${limit}`, {
                    method: "GET",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Accept": "application/json"
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    if (response.status === 401) {
                        localStorage.removeItem("auth_token");
                        window.location.href = "/login";
                        return;
                    }
                    console.error(result.message);
                    return;
                }

                if (result.success === true) {
                    updateTable(result.data.items);
                }

            } catch (error) {
                console.error("Error:", error);
            }
        }

        function updateTable(items) {
            const tbody = document.getElementById("recent-transactions-list");
            tbody.innerHTML = ""; // clear old rows

            if (!items || items.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align:center;">No transactions found.</td>
                    </tr>
                `;
                return;
            }

            items.forEach((item, index) => {

                let status = item.remaining_amount > 0 ? "Pending" : "Paid";
                let statusColor = status === "Paid" ? "green" : "red";

                let invoiceItems = Array.isArray(item.invoice_items) ?
                    item.invoice_items.map(i => i.description).join(", ") :
                    "N/A";

                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item?.name ?? 'N/A'}</td>
                        <td>${item.invoice_number ?? 'N/A'}</td>
                        <td>${item.order_number ?? 'N/A'}</td>
                        <td>₹${item.paid_amount ?? 0}</td>
                        <td>₹${item.remaining_amount ?? 0}</td>
                        <td>${item.paid_at ?? 'N/A'}</td>
                        <td>
                                <button class="btn btn-info btn-sm" onclick='viewInvItems(${JSON.stringify(item.items)})'>
                                    View Items
                                </button>
                            </td>
                        <td>
                            <span style="color:${statusColor}; font-weight:bold;">
                                ${status}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                onclick='openReceiptModal(${JSON.stringify(item)})'>
                                View Receipt
                            </button>
                        </td>

                    </tr>
                `;
            });
        }

        // Function to show items in modal
        function viewInvItems(items) {
            // const tbody = document.getElementById("invoice-items-body");
            const tbody = document.getElementById("invoiceItemsBody");
            tbody.innerHTML = "";

            if (!items || items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">No items found</td></tr>`;
            } else {
                items.forEach((item, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>  <!-- Serial number -->
                           
                            <td>${item.description}</td>
                            <td>${item.item_type}</td>
                            <td>₹${item.price}</td>
                            <td>₹${item.total_amount}</td>
                            <td>
                                ${
                                    item.status 
                                        ? `<span class="badge bg-success">${item.status}</span>`
                                        : (item.remarks ? item.remarks : 'N/A')
                                }
                            </td>
                        </tr>
                    `;
                });
            }

            // Show modal (Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('invoiceItemsModal'));
            modal.show();
        }

        function openReceiptModal(data) {

            // console.log("RECEIPT DATA:", data);

            // 🧍‍♂️ Resident
            document.getElementById("rName").innerText = data.name ?? "N/A";

            // If you want flat/phone/email, send them from API
            document.getElementById("rFlat").innerText = data.hostel ?? "N/A";
            document.getElementById("rPhone").innerText = data.phone ?? "N/A";
            document.getElementById("rEmail").innerText = data.email ?? "N/A";

            // 🧾 Invoice Summary
            document.getElementById("invNumber").innerText = data.invoice_number ?? "N/A";
            document.getElementById("ordNum").innerText = data.order_number ?? "N/A";
            document.getElementById("invDate").innerText = data.paid_at ?? "N/A";
            document.getElementById("invStatus").innerText = data.status ?? "N/A";

            document.getElementById("invTotal").innerText = "₹" + (data.total_amount ?? 0);
            document.getElementById("invPaid").innerText = "₹" + (data.paid_amount ?? 0);
            document.getElementById("invRemaining").innerText = "₹" + (data.remaining_amount ?? 0);
            // document.getElementById("invDue").innerText = data.due_date ?? "N/A";

            document.getElementById("txnStatus").innerText = (data.transaction_id ?? 0);
            document.getElementById("payMod").innerText = data.payment_mode ?? "N/A";

            // 🧩 Items Table
            let tbody = document.getElementById("receiptTxnBody");
            tbody.innerHTML = "";

            if (Array.isArray(data.items) && data.items.length) {
                data.items.forEach((it, i) => {
                    tbody.innerHTML += `
            <tr>
                <td>${i + 1}</td>
                <td>${it.description}</td>
                <td>₹${it.price}</td>
                <td>₹${it.total_amount}</td>
            </tr>
                `;
                });
            } else {
                tbody.innerHTML = `
                <tr><td colspan="4" class="text-center">No Items Found</td></tr>
            `;
            }

            // OPEN MODAL
            new bootstrap.Modal(document.getElementById("receiptModal")).show();
        }

        function printReceipt() {
            let modal = document.getElementById("receiptModal");

            // Add print class
            modal.classList.add("print-mode");

            // Delay required for browsers
            setTimeout(() => {
                window.print();
            }, 150);

            // Remove class after printing
            setTimeout(() => {
                modal.classList.remove("print-mode");
            }, 500);
        }
    </script>
@endpush
