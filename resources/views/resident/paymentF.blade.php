@extends('resident.layout')

@section('content')
    @if (session('success'))
        <div class="alert alert-success text-center mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Payments Overview d</a></div>

                <!-- Payments Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Transactions</p>
                            <h3>5</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Subscription.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Transactions</p>
                            <h3>20</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Last Payment</p>
                            <h3>₹5,000 on 05 Aug 2025</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/neutral.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Subscription Due Date</p>
                            <h3>15 Aug 2025</h3>
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

                    <p id="no-payments" class="text-danger text-center mt-3" style="display: none;">No pending payments
                        found.</p>

                    <button id="proceedSelectedBtn" class="btn btn-primary mt-3" disabled>Proceed to Payment</button>
                </div>

            </div>
        </div>
    </section>

    <!-- All Transactions Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>All Transactions</a></div>

                <div class="overflow-auto">

                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
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
                        <tbody>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Rohan Sharma</td>
                                <td>INV-1001</td>
                                <td>₹8,000</td>
                                <td>₹5,000</td>
                                <td>₹3,000</td>
                                <td>2025-01-05</td>
                                <td>Room Rent, Maintenance</td>
                                <td><span style="color: red; font-weight: bold;">Failed</span></td>
                                <td><button class="view-btn">View Detail</button></td>
                            </tr>

                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Priya Verma</td>
                                <td>INV-1002</td>
                                <td>₹10,000</td>
                                <td>₹10,000</td>
                                <td>₹0</td>
                                <td>2025-01-10</td>
                                <td>Room Rent</td>
                                <td><span style="color: green; font-weight: bold;">Paid</span></td>
                                <td><button class="view-btn">View Detail</button></td>
                            </tr>

                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Amit Singh</td>
                                <td>INV-1003</td>
                                <td>₹6,500</td>
                                <td>₹2,500</td>
                                <td>₹4,000</td>
                                <td>2025-01-12</td>
                                <td>Room Rent, Food</td>
                                <td><span style="color: green; font-weight: bold;">Paid</span></td>
                                <td><button class="view-btn">View Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>


    <!-- Invoice Items Modal -->
    <div class="modal fade" id="invoiceItemsModal" tabindex="-1" aria-labelledby="invoiceItemsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
@endsection

{{-- @push('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let paymentsTable = document.getElementById("payments-table");
            let paymentsBody = document.getElementById("payments-body");
            let noPaymentsMsg = document.getElementById("no-payments");

            paymentsBody.innerHTML = "";
            paymentsTable.style.display = "none";
            noPaymentsMsg.style.display = "none";
            // console.log('auth-id' : .localStorage.getItem('auth-id'));
            fetch(`/api/resident/pending-payments`, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(apiResponse => {
                    if (apiResponse.success && apiResponse.data && apiResponse.data.length > 0) {
                        paymentsTable.style.display = "";

                        apiResponse.data.forEach(payment => {
                            let amount = parseFloat(payment.paid_amount || 0);
                            let remainingAmount = parseFloat(payment.remaining_amount || 0);
                            let totalAmount = amount + remainingAmount;
                            let paymentStatus = remainingAmount > 0 ? 'Pending' : 'Paid';
                            let statusBadge = remainingAmount > 0 ? 'bg-warning text-dark' :
                                'bg-success';
                            // console.log('Payment Data:', payment); // Debug payment data
                            let row = `<tr>
                                    <td>
                                        ${remainingAmount > 0 
                                            ? `<input type="checkbox" class="invoice-select" 
                                                        data-invId="${payment.id}" 
                                                        data-amount="${remainingAmount}" 
                                                        data-rId="${payment.resident_id}" 
                                                        data-invoice_number="${payment.invoice_number}" 
                                                        data-remark="${payment.remarks}">`
                                            : `<span class="text-muted">Paid</span>`}
                                    </td>

                                        <td>${payment.resident.name || 'N/A'}</td>
                                        <td>${payment.invoice_number || 'N/A'}</td>
                                        <td>${totalAmount.toFixed(2)}</td>
                                        <td>${amount.toFixed(2)}</td>
                                        <td>${remainingAmount.toFixed(2)}</td>
                                        <td>${  payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-GB') : 'N/A'}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" onclick="viewInvoiceItems(${payment.id})">View Items</button>
                                        </td>

                                        <td><span class="badge ${statusBadge}">${paymentStatus}</span></td>
                                        <td>
                                        ${remainingAmount > 0 
                                            ? `<button type="button" class="btn btn-success btn-sm make-payment-btn" data-invId="${payment.id}" data-amount="${remainingAmount}"data-rId="${payment.resident_id}" data-remark="${payment.remarks}" data-invoice_number="${payment.invoice_number}">Make Payment</button>` 
                                            : `<span class="text-muted">Paid</span>`}
                                        </td>
                                    </tr>`;

                            paymentsBody.innerHTML += row;
                        });

                        // Attach click listeners to dynamically added buttons
                        document.querySelectorAll(".make-payment-btn").forEach(button => {
                            button.addEventListener("click", function() {
                                const invId = this.getAttribute("data-invId");
                                const resId = this.getAttribute("data-rId");
                                const amount = this.getAttribute("data-amount");
                                const InvNum = this.getAttribute("data-invoice_number");
                                const remark = this.getAttribute("data-remark");

                                console.log(invId, resId, amount, InvNum, remark); // ✅ Debug

                                handlePaymentInitiation({
                                    payId: resId,
                                    amount: amount,
                                    invoiceNumber: InvNum,
                                    remark: remark,
                                    invId: invId
                                });
                            });
                        });


                    } else {
                        noPaymentsMsg.style.display = "block";
                    }
                })
                .catch(error => {
                    console.error("❌ Error fetching payments:", error);
                    noPaymentsMsg.innerText = "Error loading payments. " + error.message;
                    noPaymentsMsg.style.display = "block";
                });
        });


        function handlePaymentInitiation({
            payId,
            amount,
            invoiceNumber,
            remark,
            invId
        }) {
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
                    resident_id: payId,
                    amount: parseFloat(amount),
                    invoice_number: invoiceNumber,
                    remark: remark,
                    invId: invId
                }),
                success: function(response) {
                    if (response.success && response.data) {
                        const {
                            txnUrl,
                            body
                        } = response.data;

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
                        form.submit(); // 🚀 Redirect to payment gateway
                    } else {
                        alert('Error initiating transaction: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('Error initiating transaction. Please try again.');
                }
            });
        }
    </script>

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
                    <td class="text-end">${parseFloat(item.price).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.total_amount).toFixed(2)}</td>
                    <td><span class="badge bg-success">${item.status}</span></td>
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
@endpush --}}

@push('scripts')
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
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
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
                                                        <td>${payment.resident.name || 'N/A'}</td>
                                                        <td>${payment.invoice_number || 'N/A'}</td>
                                                        <td>${totalAmount.toFixed(2)}</td>
                                                        <td>${parseFloat(payment.paid_amount || 0).toFixed(2)}</td>
                                                        <td>${remainingAmount.toFixed(2)}</td>
                                                        <td>${payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-GB') : 'N/A'}</td>
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
                                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                    'Accept': 'application/json'
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
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
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


    <script>
        const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceItemsModal'));

        async function viewInvoiceItems(id) {
            try {
                const res = await fetch('/api/resident/pending-payments/invoice-items', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
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
                                                        <td class="text-end">${parseFloat(item.price).toFixed(2)}</td>
                                                        <td class="text-end">${parseFloat(item.total_amount).toFixed(2)}</td>
                                                        <td><span class="badge bg-success">${item.status}</span></td>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            fetchTransactions();

            function fetchTransactions() {
                fetch("{{ url('/api/resident/transactions') }}", {
                        method: "GET",
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`,
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            document.getElementById("txnMessage").innerHTML =
                                `<div class="alert alert-danger">${data.message}</div>`;
                            return;
                        }

                        updateTransactionTable(data.data);
                    })
                    .catch(err => {
                        console.error("Error loading transaction data:", err);
                        document.getElementById("txnMessage").innerHTML =
                            `<div class="alert alert-danger">Unable to load transactions.</div>`;
                    });
            }

            function updateTransactionTable(transactions) {
                let tbody = document.querySelector("#transactionTable tbody");
                tbody.innerHTML = "";

                if (!transactions.length) {
                    tbody.innerHTML =
                        `<tr><td colspan="6" class="text-center text-muted">No transactions found.</td></tr>`;
                    return;
                }

                transactions.forEach((txn, index) => {
                    tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${txn.date ?? '-'}</td>
                    <td>${txn.transaction_id ?? '-'}</td>
                    <td>₹${txn.amount ?? '-'}</td>
                    <td>${txn.payment_mode ?? '-'}</td>
                    <td>
                        <span class="badge bg-${txn.status === 'success' ? 'success' : 'danger'}">
                            ${txn.status}
                        </span>
                    </td>
                </tr>
            `;
                });
            }

        });
    </script>
@endpush
