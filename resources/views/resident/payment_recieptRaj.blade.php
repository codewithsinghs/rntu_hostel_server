@extends('resident.layout')

@section('content')
    @if (session('success'))
        <div class="alert alert-success text-center mt-3">
            {{ session('success') }}
        </div>
    @endif
    <style>
        @media print {

            body * {
                visibility: hidden;
            }

            #receipt,
            #receipt * {
                visibility: visible;
            }

            #receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }


            #receipt {
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }

            .p-4 {
                padding: 0 !important;
            }

            body {
                font-size: 9px !important;
                line-height: 1.2 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            table {
                font-size: 8px !important;
                border-collapse: collapse !important;
            }

            th,
            td {
                padding: 2px 4px !important;
                border: 1px solid #ccc !important;
            }

            img {
                max-height: 30px !important;
            }

            h3,
            h5 {
                font-size: 12px !important;
                margin: 2px 0 !important;
            }

            .mb-4,
            .pb-2,
            .pb-3 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <div class="container-fluid mt-4">

        <div id="receipt" class="card shadow-lg p-4">
            <!-- Header -->
            <div class="text-center mb-4 pb-3">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" height="60">https://rntu.ac.in/ --}}
                <img class="mx-auto" src="https://rntu.ac.in/public/uploads/images/logo.png" alt="Logo" height="60">
                <h3 class="mt-5">Billing Statement</h3>
                <small class="text-muted">Generated on: <span id="generatedAt"></span></small>
            </div>

            <!-- Application Details -->
            <div class="mb-4 mt-4">
                <h5 class="border-bottom pb-2">📌 User Details</h5>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Scholar Number</th>
                            <td id="sc_n">-</td>
                            <th>Invoice Number</th>
                            <td id="invNumber">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Application Details -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">📌 Resident Details</h5>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Applicant Name</th>
                            <td id="applicantName">-</td>
                            <th>Course & Department</th>
                            <td id="course">-</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td id="email">-</td>
                            <th>Mobile</th>
                            <td id="mobile">-</td>
                        </tr>

                        <tr>
                            <th>Gender</th>
                            <td id="gender">-</td>
                            <th>Hostel & Bed</th>
                            <td id="bed_id">Hostel -</td>

                        </tr>
                        <!-- <tr>
                                <th>Father's Name</th>
                                <td id="fatherName">-</td>
                                <th>Mother Name</th>
                                <td id="motherName">-</td>
                            </tr>
                            <tr>
                                <th>Guardien's Name</th>
                                <td id="guardianName">-</td>
                                <th>Guardien Contact</th>
                                <td id="guardianContact">-</td>
                            </tr>
                            <tr>
                                <th>Parent Contact</th>
                                <td id="parentContact">-</td>
                                <th>EmergencyContact</th>
                                <td id="emergencyContact">-</td>
                            </tr> -->

                    </tbody>
                </table>
            </div>

            <!-- Hostel Details -->
            <!-- <div class="mb-4">
                    <h5 class="border-bottom pb-2">📌 Hostel Details</h5>
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th>Hostel Name</th>
                                <td id="hostelName">-</td>
                                <th>Room Type</th>
                                <td id="roomType">-</td>

                            </tr>
                            <tr>
                                <th>Room Number</th>
                                <td id="roomNumber">-</td>
                                <th>Bed</th>
                                <td id="bed">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->


            <!-- Accessories -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2">🛏️ Ordered items</h5>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price (Monthly)</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody id="accessoriesList">
                        <tr>
                            <td colspan="5" class="text-center text-muted">No accessories details available</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Transaction Details -->
            <div>
                <h5 class="border-bottom pb-2">💳 Transaction Details</h5>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Order ID</th>
                            <td id="orderId">-</td>

                            <th>Transaction ID</th>
                            <td id="txnId">-</td>
                        </tr>

                        <tr>
                            <th>Payment Mode</th>
                            <td id="payMode">-</td>
                            <th>Amount Paid</th>
                            <td id="amount">-</td>
                        <tr>
                            <th>Payment Regards</th>
                            <td id="paidFor">-</td>
                            <th>Date & Time</th>
                            <td id="txnDate">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Button (hidden in print view) -->
        <div class="text-center mt-4 no-print">
            {{-- <button onclick="printReceipt()" class="btn btn-primary">
                🖨️ Print Receipt
            </button> --}}
            <button onclick="window.print()">Print Receipt</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get("order_id");
            const txnId = urlParams.get("txn_id");
            const txnAmount = urlParams.get("amount");

            if (!orderId || !txnId || !txnAmount) {
                console.error("❌ Missing required query parameters");
                $("#receipt").html(
                    "<h4 class='text-danger text-center'>❌ Invalid request: Missing required parameters</h4>");
                return;
            }

            $.ajax({
                url: "/api/resident/payment/status",
                type: "GET",
                data: {
                    order_id: orderId,
                    txnId: txnId,
                    txnAmount: txnAmount
                },
                  headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                success: function(response) {
                    console.log("✅ API Response:", response);

                    if (response.success && response.status === "TXN_SUCCESS" || response.status ===
                        "paid") {
                        $("#orderId").text(response.order.order_number);
                        $("#txnId").text(response.txn_id || "N/A");
                        $("#status").html(`<span class="text-success fw-bold">✅ Successful</span>`);
                        $("#amount").text(response.amount ? "₹" + response.amount : "N/A");
                        $("#txnDate").text(new Date().toLocaleString());

                        const invoice = response.invoices || {};
                        $("#invNumber").text(response.order.invoice_numbers || "N/A");

                        const guest = response.resident || {};
                        $("#sc_n").text(guest.scholar_no || "N/A");
                        $("#applicantName").text(guest.name || "N/A");
                        // $("#courseName").text(guest.course?.name || "N/A");
                        $("#email").text(guest.email || "N/A");
                        $("#mobile").text(guest.mobile || "N/A");
                        $("#gender").text(guest.gender || "N/A");
                        $("#fatherName").text(guest.fathers_name || "N/A");
                        $("#motherName").text(guest.mothers_name || "N/A");
                        $("#parentContact").text(guest.parent_no || "N/A");
                        $("#guardianName").text(guest.guardian_name || "N/A");
                        $("#emergencyContact").text(guest.emergency_no || "N/A");
                        $("#guardianContact").text(guest.guardian_no || "N/A");
                        $("#stayDuration").text(guest.stay_duration || "N/A");
                        $("#roomType").text(guest.room_type || "N/A");
                        $("#bed_id").text(guest.bed_id || "N/A");

                        $("#status").text(guest.status || "N/A");
                        const transaction = response.transaction || {};
                        $("#payMode").text(transaction.payment_mode || "N/A");
                        $("#paidFor").text(invoice.remarks || "N/A");

                        if (Array.isArray(response.accessories) && response.accessories.length > 0) {
                            const rows = response.accessories.map(item => `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.qty}</td>
                             <td>₹${item.price}</td>
                              <td>₹${item.from_date}</td>
                              <td>₹${item.to_date}</td>
                               <td>₹${item.total_amount}</td>
                        </tr>
                    `).join("");
                            $("#accessoriesList").html(rows);
                        } else {
                            $("#accessoriesList").html(
                                "<tr><td colspan='2'>No accessories selected</td></tr>");
                        }
                    } else {
                        console.warn("⚠️ Payment not successful:", response.message);
                        $("#receipt").html(
                            `<h4 class="text-danger text-center">❌ Payment Failed: ${response.message || "Unknown error"}</h4>`
                            );
                    }
                },
                error: function(xhr, status, error) {
                    console.error("❌ AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText);
                    $("#receipt").html(
                        "<h4 class='text-danger text-center'>❌ Unable to fetch payment status. Please try again later.</h4>"
                        );
                }
            });
        });


        // Print function


        // function printReceipt() {
        //     const printContent = document.getElementById("receipt").innerHTML;
        //     const originalContent = document.body.innerHTML;

        //     document.body.innerHTML = printContent;
        //     window.print();
        //     document.body.innerHTML = originalContent;
        //     location.reload();
        // }
    </script>
@endsection
