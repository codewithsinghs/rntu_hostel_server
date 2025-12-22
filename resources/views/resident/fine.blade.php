@extends('resident.layout')

@section('content')
    <!-- Data Card -->

    @if (session('success'))
        <div class="alert alert-success text-center mt-3">
            {{ session('success') }}
        </div>
    @endif

    <style>
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

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Fines & Penalties Overview</a></div>

                <!-- Fines & Penalties Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Fines Incurred</p>
                            <h3 id="totalFines">₹0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Fines</p>
                            <h3 id="pendingFines">₹0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Fines Paids</p>
                            <h3 id="paidFines">₹0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/neutral.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Last Fine Date</p>
                            <h3 id="lastFine"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Pending Fines Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Pending Fines</a></div>

                <div class="overflow-auto">

                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%" id="payments-table"
                        style="display: none;">
                        <thead class="table-dark">
                            <tr>
                                <th>Invoice</th>
                                <th>Remarks/Description</th>
                                <th>Total Amount</th>
                                <th>Amount Paid</th>
                                <th>Remaining Amount</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="payments-body"></tbody>
                    </table>

                    <p id="no-payments" class="text-danger text-center mt-3" style="display: none;">No pending fines found.
                    </p>
                    <p id="error-message" class="text-danger text-center mt-3" style="display: none;">Error loading data,
                        please try
                        again.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Paid Fines Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>All Fines</a></div>

                <div class="overflow-auto">

                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%" id="paidInfoTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Fee Type</th>
                                <th>Invoice Number</th>
                                <th>Order Number</th>
                                <th>Amount Paid</th>
                                <th>Remaining Amount</th>
                                <th>Payment Date</th>
                                <th>PaymentStatus</th>
                                <th>Invoice Info</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="paidInfoBody"></tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- Invoice Model -->
    <div class="modal fade" id="invoiceItemsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoice Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Article</th>
                                <th>Description</th>
                                <th>Amount (₹)</th>
                                <th>Total Amount (₹)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Items Modal -->
    {{-- <div class="modal fade" id="invoiceItemsModal" tabindex="-1" aria-labelledby="invoiceItemsLabel" aria-hidden="true">
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
                    <div class="receipt-header text-center">
                        <h3 class="fw-bold mb-1">Transaction Info</h3>
                        <p class="text-muted small mb-0">Payment Confirmation</p>
                    </div>

                    <!-- Resident Info -->
                    <div class="receipt-section ">
                        <h5 class="section-title bg-light ">Resident Details</h5>
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
                    <div class="receipt-section ">
                        <div class="d-flex bg-light">
                            <h5 class="section-title ">Invoice Summary</h5>
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
    <script>
        let invoiceItemMap = {}; // Stores invoice_id => items

        document.addEventListener("DOMContentLoaded", function() {
            const table = document.getElementById("payments-table");
            const tbody = document.getElementById("payments-body");
            const noData = document.getElementById("no-payments");
            const errorMsg = document.getElementById("error-message");

            // Reset UI
            tbody.innerHTML = "";
            table.style.display = "none";
            noData.style.display = "none";
            errorMsg.style.display = "none";

            fetch("/api/resident/pending/appliedFines", {
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        resident_id: localStorage.getItem("auth-id")
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // console.log(data);
                    // if (!data.success || !data.invoices || data.invoices.length === 0) {
                    if (!data.success) {
                        noData.style.display = "block";
                        return;
                    }
                    // console.log(data);

                    // ---- UPDATE SUMMARY UI ----
                    // ✅ Update Summary Cards
                    if (data.summary) {
                        const summary = data.summary;

                        document.getElementById("totalFines").innerText = `₹${summary
                            .total_fines_incurred.toLocaleString()}`;
                        document.getElementById("pendingFines").innerText = `₹${summary
                            .pending_fines.toLocaleString()}`;
                        document.getElementById("paidFines").innerText = `₹${summary
                            .total_fines_paid.toLocaleString()}`;
                        document.getElementById("lastFine").innerText =
                            `${summary.last_fine_date.toLocaleString()}`;
                    }

                    // if (lastFineDate) {
                    //     document.getElementById("lastFineDate").innerText =
                    //         lastFineDate.toLocaleDateString("en-IN", {
                    //             day: "2-digit",
                    //             month: "short",
                    //             year: "numeric"
                    //         });
                    // }


                    invoiceItemMap = {}; // Reset map

                    if (data.success) {

                        // ✅ normalize invoices (object → array)
                        const invoices = Array.isArray(data.invoices) ?
                            data.invoices :
                            Object.values(data.invoices || {});

                        if (invoices.length === 0) {
                            document.getElementById("paidInfoTable").style.display = "none";
                            return;
                        }

                        const rows = invoices.map(invoice => {

                            const items = Array.isArray(invoice.items) ? invoice.items : [];
                            invoiceItemMap[invoice.invoice_id] = items;

                            const total = Number(invoice.total_amount || 0);
                            const paid = Number(invoice.paid_amount || 0);
                            const balance = Number(invoice.balance || 0);

                            return `
                            <tr>
                                <td>${invoice.invoice_number}</td>
                                <td>${invoice.descriptions}</td>

                                <td class="text-center">₹${total.toFixed(2)}</td>
                                <td class="text-center">₹${paid.toFixed(2)}</td>
                                <td class="text-center">₹${balance.toFixed(2)}</td>

                                <td>
                                    <span class="badge ${invoice.status === 'paid' ? 'bg-success' : 'bg-warning'}">
                                        ${invoice.status}
                                    </span>
                                </td>

                                <td>
                                    <button class="btn btn-info btn-sm"
                                        onclick="viewInvoiceItems(${invoice.invoice_id})">
                                        View Items
                                    </button>
                                </td>

                                <td>
                                    ${balance > 0
                                        ? `<button type="button"
                                                        class="btn btn-success btn-sm single-payment-btn"
                                                        data-invid="${invoice.invoice_id}"
                                                        data-resid="${invoice.resident_id}"
                                                        data-amount="${balance}"
                                                        data-invoicenumber="${invoice.invoice_number}"
                                                        data-remark="${invoice.item_type || ''}">
                                                    Make Payment
                                                </button>`
                                        : `<span class="text-muted">Paid</span>`
                                    }
                                    </td>
                                </tr>
                            `;
                        }).join('');

                        tbody.innerHTML = rows;
                        table.style.display = "table";

                        bindSinglePaymentButtons();
                    }


                    // ---- UPDATE PAID INFO TABLE ----
                    if (data.paidFines && data.paidFines.length > 0) {
                        const paidRows = data.paidFines.map(paid => {
                            return `
                                <tr>

                                <td>${paid.descriptions || '-'}</td>
                                    <td>${paid.invoice_number}</td>
                                    <td>${paid.order_number}</td>
                                    <td class="text-center">₹${parseFloat(paid.paid_amount).toFixed(2)}</td>
                                    <td class="text-center">₹${parseFloat(paid.balance).toFixed(2)}</td>

                                    <td class="text-center">${paid.paid_at}</td>
                       
                                    
                               
                                     <td><span class="badge ${paid.status === 'paid' ? 'bg-success' : 'bg-warning'}">${paid.status}</span></td>
                                     <td>
                                            <button class="btn btn-info btn-sm" onclick='viewInvItems(${JSON.stringify(paid.items)})'>
                                                View Items
                                            </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick='openReceiptModal(${JSON.stringify(paid)})'>
                                            View Receipt
                                        </button>
                                    </td>
                                        
                                </tr>
                            `;
                        }).join('');

                        document.getElementById("paidInfoBody").innerHTML = paidRows;
                        document.getElementById("paidInfoTable").style.display = "table";
                    } else {
                        document.getElementById("paidInfoTable").style.display = "none";
                    }

                })
                .catch(error => {
                    errorMsg.innerText = "Error loading fines: " + error.message;
                    errorMsg.style.display = "block";
                });


        });

        // ✅ Single payment buttons
        function bindSinglePaymentButtons() {
            document.querySelectorAll(".single-payment-btn").forEach(btn => {
                btn.addEventListener("click", () => {

                    const invoice = [{
                        invId: btn.dataset.invid,
                        resId: btn.dataset.resid,
                        amount: parseFloat(btn.dataset.amount),
                        invoiceNumber: btn.dataset.invoicenumber,
                        remark: btn.dataset.remark
                    }];

                    // console.log("Selected Invoice (Single):", invoice);
                    confirmAndInitiatePayment(invoice);
                });
            });

            // console.log("Payment buttons bound!");
        }

        function confirmAndInitiatePayment(invoices) {
            const totalAmount = invoices.reduce((sum, inv) => sum + parseFloat(inv.amount), 0);

            // console.log("Invoices to process:", invoices); // ✅ Debug
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
                                // console.log("Confirmation API response:", response.data);

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

        // Old Payment function
        // function initiateFinePayment(invoiceId, amount) {
        //     console.log('payment', invoiceId, amount);
        //     fetch("/api/resident/payment/initiate", {
        //             method: "POST",
        //             headers: {
        //                 "Content-Type": "application/json",
        //                 "Accept": "application/json",
        //                 "token": localStorage.getItem("token"),
        //                 "auth-id": localStorage.getItem("auth-id")
        //             },
        //             body: JSON.stringify({
        //                 invoice_id: invoiceId,
        //                 amount: amount
        //             })
        //         })
        //         .then(res => res.json())
        //         .then(data => {
        //             if (data.success && data.script) {
        //                 const script = document.createElement("script");
        //                 script.innerHTML = data.script;
        //                 document.body.appendChild(script);
        //             } else {
        //                 alert("Unable to initiate payment.");
        //             }
        //         })
        //         .catch(err => {
        //             console.error("Payment initiation failed:", err);
        //         });
        // }

        function viewInvoiceItems(invoiceId) {
            // console.log(invoiceId);
            const items = invoiceItemMap[invoiceId];
            // console.log(items);
            if (!items || items.length === 0) {
                alert("No items found for this invoice.");
                return;
            }

            const rows = items.map((item, index) => `
            <tr>
                <td>${index + 1}</td>
                  <td>${item.item_type}</td>
                <td>${item.desc}</td>
                <td class="text-end">₹${parseFloat(item.amount).toFixed(2)}</td>
                <td class="text-end">₹${parseFloat(item.total).toFixed(2)}</td>
                <td><span class="badge bg-secondary">${item.status}</span></td>
            </tr>
            `).join('');

            document.getElementById("invoiceItemsBody").innerHTML = rows;
            new bootstrap.Modal(document.getElementById("invoiceItemsModal")).show();
        }


        // Function to show items in modal
        function viewInvItems(items) {
            // console.log(items);
            const tbody = document.getElementById("invoiceItemsBody");
            tbody.innerHTML = "";

            if (!items || items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">No items found</td></tr>`;
            } else {
                items.forEach((item, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>  <!-- Serial number -->
                           
                            <td>${item.desc}</td>
                            <td>${item.item_type}</td>
                            <td>₹${item.amount}</td>
                            <td>₹${item.total}</td>
                            <td>
                                ${
                                    item.status 
                                        ? `<span class="badge bg-success">${item.status}</span>`
                                        : (item.remarks ? item.remarks : '')
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

            console.log("RECEIPT DATA:", data);

            // 🧍‍♂️ Resident
            document.getElementById("rName").innerText = data.resident_name ?? "N/A";

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
                <td>${it.desc}</td>
                <td>₹${it.amount}</td>
                <td>₹${it.total}</td>
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
