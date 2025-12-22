@extends('guest.layout')

@section('content')
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
                <img src="https://rntu.ac.in/public/uploads/images/logo.png" alt="Logo" height="60">
                <h3 class="mt-5">Hostel Booking Receipt</h3>
                <small class="text-muted">Generated on: <span id="generatedAt"></span></small>
            </div>

            <!-- Application Details -->
            <div class="mb-4 mt-4">
                <h5 class="border-bottom pb-2">📌 Application Details</h5>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Scholar Number</th>
                            <td id="sc_n">-</td>
                            <th>Course</th>
                            <td id="courseName">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Application Details -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">📌 Applicant Details</h5>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Applicant Name</th>
                            <td id="applicantName">-</td>
                            <th>Date oF Birth</th>
                            <td id="applicantName">-</td>
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

                        </tr>
                        <tr>
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
                        </tr>

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
                <h5 class="border-bottom pb-2">🛏️ Accessories & Facilities</h5>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            {{-- <th>Qty</th> --}}
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
                             <th>Date & Time</th>
                            <td id="txnDate">-</td>
                        </tr>
                        <tr>
                            <th>Transaction ID</th>
                            <td id="txnId">-</td>
                             <th>Amount Paid</th>
                            <td id="amount">-</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="status">-</td>
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


            const orderId = new URLSearchParams(window.location.search).get("order_id");
            const txnId = new URLSearchParams(window.location.search).get("txn_id");
            const txnAmount = new URLSearchParams(window.location.search).get("amount");

            if (!orderId || !txnId || !txnAmount) {
                console.error("❌ Missing order_id in URL");
                $("#receipt").html("<h4 class='text-danger text-center'>❌ Invalid request: Missing Order ID</h4>");
                return;
            }

            $.ajax({
                url: "/api/guest/payment/status",
                type: "GET",
                data: {
                    order_id: orderId,
                    txnId: txnId,
                    txnAmount: txnAmount
                },
                headers: {
                    Accept: "application/json",
                    'token': localStorage.getItem("token") || "",
                    'auth-id': localStorage.getItem("auth-id") || ""
                },
                // beforeSend: function() {
                //     console.log("🔄 Fetching payment status for Order ID:", orderId);
                //     $("#receipt").html(
                //         "<p class='text-info text-center'>⏳ Verifying payment status...</p>");
                // },
                success: function(response) {
                    console.log("✅ API Response:", response);

                    // if (response.success && response.status === "TXN_SUCCESS") {
                    if (response.success && response.status === "paid") {

                        $("#orderId").text(response.order_id);
                        $("#txnId").text(response.txn_id || "N/A");
                        $("#status").html(`<span class="text-success fw-bold">✅ Successful</span>`);
                        $("#amount").text("₹" + response.amount || "N/A");
                        $("#txnDate").text(new Date().toLocaleString());

                        const guest = response.guest || {};
                        $("#sc_n").text(guest.sc_n || "N/A");
                        $("#applicantName").text(guest.name || "N/A");
                        $("#courseName").text(guest.course.name || "N/A");
                        $("#email").text(guest.email || "N/A");
                        $("#mobile").text(guest.number || "N/A");
                        $("#gender").text(guest.gender || "N/A");
                        $("#fatherName").text(guest.fathers_name || "N/A");
                        $("#motherName").text(guest.mothers_name || "N/A");
                        $("#parentContact").text(guest.parent_no || "N/A");
                        $("#guardianName").text(guest.guardian_name || "N/A");
                        $("#emergencyContact").text(guest.emergency_no || "N/A");
                        $("#guardianContact").text(guest.guardian_no || "N/A");
                        $("#stayDuration").text(guest.stay_duration || "N/A");
                        $("#roomType").text(guest.room_type || "N/A");
                      
                        // Accessories
                        if (Array.isArray(response.accessories) && response.accessories.length > 0) {
                            console.log('hil');
                            let rows = response.accessories.map(item => `
                    <tr>
                        <td>${item.name}</td>
                        <td>₹ ${item.price}</td>
                        <td>${item.from_date}</td>
                        <td>${item.to_date}</td>
                        <td>₹ ${item.total_amount}</td>
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

    {{-- <script>
        function printReceipt() {
            const printWindow = window.open('', '', 'height=600,width=800');
            const receiptHTML = document.getElementById('receipt').outerHTML;

            printWindow.document.write('<html><head><title>Print Receipt</title>');
            printWindow.document.write('<style>@media print { body { font-size: 9px; } }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(receiptHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script> --}}



    {{-- <div class="text-center">
        <button onclick="printReceipt()" class="btn btn-primary text-center">🖨️ Print Receipt</button>
    </div>
    <script>
        function printReceipt() {
            const receipt = document.getElementById('receipt');
            const printWindow = window.open('', '', 'height=600,width=800');

            printWindow.document.write('<html><head><title>Print Receipt</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
            body {
                font-size: 9px;
                line-height: 1.3;
                margin: 10mm;
                color: #000;
                background: #fff;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8px;
            }
            th, td {
                padding: 2px 4px;
                border: 1px solid #ccc;
            }
            img {
                max-height: 30px;
            }
            h3, h5 {
                font-size: 12px;
                margin: 2px 0;
            }
        `);
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(receipt.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script> --}}
@endsection
