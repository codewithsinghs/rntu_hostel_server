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

    <div class="container mt-4">

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
                            <td id="patentContact">-</td>
                            <th>EmergencyContact</th>
                            <td id="emergencyContact">-</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Application Details -->
            <div class="mb-4">
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
            </div>


            <!-- Accessories -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2">🛏️ Accessories & Facilities</h5>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            {{-- <th>Qty</th> --}}
                            <th>price (Monthly)</th>
{{--                         
                            <th>Duration</th>
                            <th>Total Amount</th> --}}
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
                        </tr>
                        <tr>
                            <th>Transaction ID</th>
                            <td id="txnId">-</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="status">-</td>
                        </tr>
                        <tr>
                            <th>Amount Paid</th>
                            <td id="amount">-</td>
                        </tr>
                        <tr>
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
            $("#generatedAt").text(new Date().toLocaleString());

            if (!orderId) {
                $("#receipt").html("<h4 class='text-danger text-center'>⚠️ Invalid Request: Missing Order ID</h4>");
                return;
            }

            $.ajax({
                url: "/api/guests/payment/status",
                type: "GET",
                data: {
                    order_id: orderId
                },
                headers: {
                    Accept: "application/json",
                    'token': localStorage.getItem("token") || "",
                    'auth-id': localStorage.getItem("auth-id") || ""
                },
                success: function(response) {
                    console.log( response );
                    if (response.success && response.status === "TXN_SUCCESS") {
                        // Fill Transaction Info
                        $("#orderId").text(response.order_id);
                        $("#txnId").text(response.txn_id);
                        $("#status").html(`<span class="text-success fw-bold">✅ Successful</span>`);
                        $("#amount").text("₹" + response.amount);
                        $("#txnDate").text(new Date().toLocaleString());

                        // Application Info (dummy example, replace with real response fields)
                        $("#sc_n").text(response.guest.sc_n || "N/A");
                        $("#applicantName").text(response.guest.name || "N/A");
                        $("#courseName").text(response.guest.course || "N/A");
                        $("#email").text(response.guest.email || "N/A");
                        $("#mobile").text(response.guest.mobile || "N/A");
                        $("#gender").text(response.guest.gender || "N/A");
                        $("#fatherName").text(response.guest.fathers_name || "N/A");
                        $("#motherName").text(response.guest.mothers_name || "N/A");
                        $("#parentContact").text(response.guest.parent_contact || "N/A");
                        $("#guardianName").text(response.guest.guardian_name || "N/A");
                        $("#emergencyContact").text(response.guest.emergency_contact || "N/A");

                        $("#guardianContact").text(response.guest.guardian_contact || "N/A");
                        $("#stayDuration").text(response.guest.stay_duration || "N/A");
                        $("#roomType").text(response.guest.room_type || "N/A");

                        // Accessories (if any)
                        if (response.accessories && response.accessories.length > 0) {
                            let rows = "";
                            response.accessories.forEach(item => {
                                rows += `<tr>
                            <td>${item.name}</td>
                          
                            <td>₹${item.price}</td>
                           
                            </tr>`;
                            });
                            $("#accessoriesList").html(rows);
                        }
                    } else {
                        $("#receipt").html(
                            `<h4 class="text-danger text-center">❌ Payment Failed: ${response.message || "Unknown error"}</h4>`
                        );
                    }
                },
                error: function() {
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
