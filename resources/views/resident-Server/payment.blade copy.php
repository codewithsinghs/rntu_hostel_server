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
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let paymentsTable = document.getElementById("payments-table");
                    let paymentsBody = document.getElementById("payments-body");
                    let noPaymentsMsg = document.getElementById("no-payments");

                    paymentsBody.innerHTML = "";
                    paymentsTable.style.display = "none";
                    noPaymentsMsg.style.display = "none";

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
                                        <td>${payment.resident.name || 'N/A'}</td>
                                        <td>${payment.invoice_number || 'N/A'}</td>
                                        <td>${totalAmount.toFixed(2)}</td>
                                        <td>${amount.toFixed(2)}</td>
                                        <td>${remainingAmount.toFixed(2)}</td>
                                        <td>${  payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-GB') : 'N/A'}</td>
                                        <td><a href="/resident/invoice/${payment.id}" class="btn btn-info   btn-sm">View Items</a></td> 
                                        <td><span class="badge ${statusBadge}">${paymentStatus}</span></td>
                                        <td>
                                            ${remainingAmount > 0 
                                                ? `<a href="/resident/payment/${payment.id}" class="btn btn-success btn-sm">Make Payment</a>` 
                                                : `<span class="text-muted">Paid</span>`}
                                        </td>
                                    </tr>`;

                                    paymentsBody.innerHTML += row;
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

                document.querySelectorAll(".make-payment-btn").forEach(button => {
                    button.addEventListener("click", function (e) {
                        e.preventDefault(); // Prevent default link behavior

                        let paymentId = this.getAttribute("data-id");

                        fetch(`/api/resident/initiate-payment/${paymentId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "token": localStorage.getItem("token"),
                                "auth-id": localStorage.getItem("auth-id")
                            },
                            body: JSON.stringify({ payment_id: paymentId }) // Optional, if backend expects it
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Payment initiation failed: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert("✅ Payment initiated successfully!");
                                // Optionally redirect or refresh
                                window.location.href = `/resident/payment/${paymentId}`;
                            } else {
                                alert("⚠️ Payment initiation failed: " + (data.message || "Unknown error"));
                            }
                        })
                        .catch(error => {
                            console.error("❌ Error initiating payment:", error);
                            alert("❌ Error initiating payment: " + error.message);
                        });
                    });
                });

            </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
