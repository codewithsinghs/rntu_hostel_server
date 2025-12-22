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
                                    <th>Invoice No & Items</th>
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
                const orderRef = urlParams.get("order_ref");

                if (!orderRef) {
                    alert("Invalid request: Order reference missing.");
                    return;
                }

                $.ajax({
                    url: `/api/resident/payment/confirmation/${orderRef}`,
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    },
                    success: function(res) {
                        if (!res.success) {
                            alert(res.message || "Order not found.");
                            return;
                        }

                        const order = res.data;

                        // Fill order info
                        $("#orderNo").text(order.order_number);
                        $("#orderAmount").text(order.amount);
                        $("#residentName").text(order.resident?.name ?? "N/A");

                        // Fill invoices
                        // $("#invoiceList").empty();
                        // order.invoices.forEach(inv => {
                        //     let itemDetails = inv.items.map(item =>
                        //         `<li>${item.name} - Qty: ${item.qty}, Rate: ₹${item.rate}, Amount: ₹${item.amount}</li>`
                        //     ).join('');

                        //     $("#invoiceList").append(`
                        //         <tr>
                        //             <td>${inv.invoice_number}<ul class="mb-0 pl-3">${itemDetails}</ul></td>
                        //             <td>₹${inv.amount}</td>
                        //             <td>${inv.remark ?? ""}</td>
                        //         </tr>
                        //     `);
                        // });

                        // Usage
                        renderInvoices(order.invoices);

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
                                        window.location.href = resp.data.payment_url;
                                    } else {
                                        alert(resp.message);
                                    }
                                },
                                error: function() {
                                    alert("Failed to initiate payment.");
                                }
                            });
                        });

                    },
                    error: function() {
                        alert("Error fetching order details.");
                    }
                });

                // Function to render invoices with their items
                function renderInvoices(invoices) {
                    $("#invoiceList").empty();

                    invoices.forEach(inv => {
                        // Start invoice row
                        let invoiceRow = `
            <tr class="table-primary">
                <td colspan="3"><strong>Invoice: ${inv.invoice_number}</strong></td>
            </tr>
        `;

                        // Table header for items under invoice
                        invoiceRow += `
            <tr class="table-light">
                <th>Item</th>
                <th>Qty</th>
                <th>Amount (₹)</th>
            </tr>
        `;

                        // Items for this invoice
                        inv.items.forEach(item => {
                            invoiceRow += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>${item.amount}</td>
                </tr>
            `;
                        });

                        // Optional: invoice total
                        invoiceRow += `
            <tr class="table-secondary">
                <td colspan="2" class="text-end"><strong>Invoice Total:</strong></td>
                <td><strong>₹${inv.amount}</strong></td>
            </tr>
        `;

                        $("#invoiceList").append(invoiceRow);
                    });
                }



            });
        </script>




        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endsection
