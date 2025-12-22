@extends('resident.layout')

@section('content')
@if (session('success'))
<div class="alert alert-success text-center mt-3">
    {{ session('success') }}
</div>
@endif

<h2 class="text-center mt-3">Pending Payments</h2>

<hr>

<div class="container mt-3">
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
        <tbody id="payments-body">
        </tbody>
    </table>

    <p id="no-payments" class="text-danger text-center mt-3" style="display: none;">No pending payments found.</p>

    <button id="proceedSelectedBtn" class="btn btn-primary mt-3" disabled>Proceed to Payment</button>
</div>




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
                        let statusBadge = remainingAmount > 0 ? 'bg-warning text-dark' : 'bg-success';
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


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection