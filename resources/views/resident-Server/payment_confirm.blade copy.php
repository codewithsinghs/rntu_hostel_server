@extends('resident.layout')

@section('content')
    <div class="container my-4">


        <div class="container py-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Confirm Your Payment</h5>
                </div>
                <div class="card-body">

                    <!-- Order Info -->
                    <div id="orderSummary" class="mb-4">
                        <p><strong>Order No:</strong> <span id="orderNo"></span></p>
                        <p><strong>Total Amount:</strong> ₹<span id="orderAmount"></span></p>
                        <p><strong>Resident:</strong> <span id="residentName"></span></p>
                    </div>

                    <!-- Invoice Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceList"></tbody>
                        </table>
                    </div>

                    <!-- Actions -->
                    <div class="text-end">
                        <button class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                        <button id="proceedPay" class="btn btn-success">Proceed to Pay</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                const urlParams = new URLSearchParams(window.location.search);
                console.log(urlParams);
                const orderRef = urlParams.get("order_ref");

                if (!orderNo) {
                    alert("Invalid request: Order number missing.");
                    return;
                }

                // ✅ Fetch order details via API
                $.ajax({
                    url: `/api/resident/payment/confirmation/${orderNo}`,
                    method: "GET",
                     headers: {
                            "Accept": "application/json",
                            'token': localStorage.getItem('token'),     
                            'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                        }
                    success: function(res) {
                        if (res.success) {
                            const order = res.data;

                            // Fill order info
                            $("#orderNo").text(order.order_number);
                            $("#orderAmount").text(order.amount);
                            $("#residentName").text(order.resident?.name ?? "N/A");

                            // Fill invoices
                            $("#invoiceList").empty();
                            order.invoices.forEach(inv => {
                                $("#invoiceList").append(`
                        <tr>
                          <td>${inv.invoice_number}</td>
                          <td>₹${inv.amount}</td>
                          <td>${inv.remark ?? ""}</td>
                        </tr>
                    `);
                            });

                            // Proceed to payment
                            $("#proceedPay").off("click").on("click", function() {
                                $.ajax({
                                    url: "/api/resident/payment/initiate",
                                    method: "POST",
                                    data: {
                                        order_no: order.order_number
                                    },
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    success: function(resp) {
                                        if (resp.success) {
                                            // Redirect to gateway or show further instructions
                                            window.location.href = resp.data
                                                .payment_url;
                                        } else {
                                            alert(resp.message);
                                        }
                                    },
                                    error: function() {
                                        alert("Failed to initiate payment.");
                                    }
                                });
                            });

                        } else {
                            alert(res.message || "Order not found.");
                        }
                    },
                    error: function() {
                        alert("Error fetching order details.");
                    }
                });
            });
        </script>



        {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paymentDetailsEl = document.getElementById("paymentDetails");
            const totalAmountEl = document.getElementById("totalAmount");
            const proceedBtn = document.getElementById("proceedPaymentBtn");

            let invoices = [];
            let totalAmount = 0;

            // 🔹 Fetch selected invoices via API
            // Example: pass invoice IDs via query params ?invoices[]=1&invoices[]=2
            const urlParams = new URLSearchParams(window.location.search);
            const invoiceIds = urlParams.getAll('invoices[]');

            if (invoiceIds.length === 0) {
                paymentDetailsEl.innerHTML =
                    `<div class="alert alert-warning text-center">No invoices selected.</div>`;
                return;
            }

            fetch(`/api/resident/payment/summary?invoices=${invoiceIds.join(',')}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                })
                .then(res => res.json())
                .then(response => {
                    if (!response.success || !response.data || response.data.length === 0) {
                        paymentDetailsEl.innerHTML =
                            `<div class="alert alert-warning">${response.message || 'No payment details found.'}</div>`;
                        return;
                    }

                    invoices = response.data;

                    // Render table
                    let tableHtml = `<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Resident Name</th>
                    <th>Remaining Amount</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                ${invoices.map(inv => `
                            <tr>
                                <td>${inv.invoice_number}</td>
                                <td>${inv.resident_name}</td>
                                <td>₹${parseFloat(inv.remaining_amount).toFixed(2)}</td>
                                <td>${inv.remarks || '-'}</td>
                            </tr>
                        `).join('')}
            </tbody>
        </table>`;

                    paymentDetailsEl.innerHTML = tableHtml;

                    totalAmount = invoices.reduce((sum, inv) => sum + parseFloat(inv.remaining_amount), 0);
                    totalAmountEl.textContent = totalAmount.toFixed(2);

                    proceedBtn.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    paymentDetailsEl.innerHTML =
                        `<div class="alert alert-danger">Error loading payment details.</div>`;
                });

            // 🔹 Proceed to initiate payment
            proceedBtn.addEventListener("click", function() {
                fetch('/api/resident/payment/initiate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'token': localStorage.getItem('token'),
                            'auth-id': localStorage.getItem('auth-id')
                        },
                        body: JSON.stringify({
                            invoices: invoices.map(inv => inv.id)
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.data) {
                            const {
                                txnUrl,
                                body
                            } = data.data;

                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = txnUrl;

                            for (const key in body) {
                                if (body.hasOwnProperty(key)) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = key;
                                    input.value = body[key];
                                    form.appendChild(input);
                                }
                            }

                            document.body.appendChild(form);
                            form.submit(); // Redirect to Paytm
                        } else {
                            alert(data.message || 'Failed to initiate transaction');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error initiating payment. Please try again.');
                    });
            });

        });
    </script> --}}

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endsection
