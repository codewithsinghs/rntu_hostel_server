@extends('admin.layout')

@section('content')

    <!-- Pending Payments Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Pending Payments List</a></div>

                <div id="mainResponseMessage" class="mt-3"></div>

                <div class="overflow-auto">

                    <table class="status-table" id="pendingPaymentsTable">
                        <thead>
                            <tr>
                                <th>Serial No.</th>
                                <th>Resident Name</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center">Loading pending payments...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>


    <!-- Pending Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Pending Payments List</a></div>

                <div class="overflow-auto">

                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Resident Name</th>
                                <th>Faculty</th>
                                <th>Department</th>
                                <th>Hostel Name</th>
                                <th>Room no.</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rahul Sharma</td>
                                <td>Engineering</td>
                                <td>Computer Science</td>
                                <td>Tagore Hostel</td>
                                <td>101</td>
                                <td>₹8,000</td>
                                <td>₹5,000</td>
                                <td><span style="color: orange; font-weight: bold;">₹3,000</span></td>
                                <td><span style="color: orange; font-weight: bold;">Pending</span></td>
                                <td><span style="color: orange; font-weight: bold;">15-01-2025</span></td>
                                <td>01-01-2025</td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Neha Singh</td>
                                <td>Science</td>
                                <td>Physics</td>
                                <td>Saraswati Hostel</td>
                                <td>204</td>
                                <td>₹10,000</td>
                                <td>₹10,000</td>
                                <td>₹0</td>
                                <td><span style="color: green; font-weight: bold;">Paid</span></td>
                                <td>—</td>
                                <td>05-01-2025</td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Aman Verma</td>
                                <td>Commerce</td>
                                <td>Accounting</td>
                                <td>Vivekanand Hostel</td>
                                <td>309</td>
                                <td>₹12,000</td>
                                <td>₹7,000</td>
                                <td><span style="color: red; font-weight: bold;">₹5,000</span></td>
                                <td><span style="color: red; font-weight: bold;">Overdue</span></td>
                                <td><span style="color: red; font-weight: bold;">10-01-2025</span></td>
                                <td>28-12-2024</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')

    <script>
        // Function to show a custom message box
        function showCustomMessageBox(message, type = 'info', targetElementId = 'mainResponseMessage') {
            const messageContainer = document.getElementById(targetElementId);
            if (messageContainer) {
                messageContainer.innerHTML = ""; // Clear previous messages
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                messageContainer.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000); // Remove after 3 seconds
            } else {
                console.warn(`Message container #${targetElementId} not found.`);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetchPendingPayments();

            function fetchPendingPayments() {
                const apiUrl = "{{ url('/api/admin/allPendingPayments') }}";
                const tableBody = document.getElementById("pendingPaymentsTable").querySelector("tbody");

                fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            // If the response is not OK, try to parse it as JSON to get the message
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(response => { // Changed 'data' to 'response' for consistency
                        // Assuming the payments array is directly under the 'data' key
                        const payments = response.data;
                        tableBody.innerHTML = "";

                        if (!response.success || !Array.isArray(payments) || payments.length === 0) {
                            tableBody.innerHTML = `<tr><td colspan="9" class="text-center">No pending payments found.</td></tr>`;
                            if (!response.success && response.message) {
                                showCustomMessageBox(response.message, 'info'); // Use info for "no payments found"
                            }
                            return;
                        }

                        payments.forEach((payment, index) => {
                            tableBody.innerHTML += `
                                                                                    <tr>
                                                                                        <td>${index + 1}</td> <td>${payment.resident_name || 'N/A'}</td>
                                                                                        <td>${payment.total_amount || 'N/A'}</td>
                                                                                        <td>${payment.amount_paid || 'N/A'}</td>
                                                                                        <td>${payment.remaining_amount || 'N/A'}</td>
                                                                                        <td>${payment.payment_status || 'N/A'}</td>
                                                                                        <td>${payment.due_date ?? 'N/A'}</td>
                                                                                        <td>${payment.created_at ? new Date(payment.created_at).toLocaleString() : 'N/A'}</td>
                                                                                    </tr>
                                                                                `;
                        });
                        if (response.message) {
                            showCustomMessageBox(response.message, 'success');
                        }

                        // ✅ Initialize DataTable AFTER data load
                        $('table').DataTable({
                            pageLength: 10,
                            dom: 'Bfrtip',
                            buttons: [
                                { extend: 'copy', className: 'btn btn-sm btn-outline-primary' },
                                { extend: 'csv', className: 'btn btn-sm btn-outline-success' },
                                { extend: 'excel', className: 'btn btn-sm btn-outline-info' },
                                {
                                    extend: 'pdfHtml5',
                                    className: 'btn btn-sm btn-outline-danger',

                                    customize: function (doc) {
                                        doc.pageMargins = [20, 20, 20, 20];
                                        doc.styles.tableHeader = {
                                            bold: true,
                                            fontSize: 12,
                                            fillColor: '#343a40',
                                            color: 'white',
                                            alignment: 'center',
                                            margin: [5, 5, 5, 5]
                                        };
                                        doc.styles.tableBodyEven = { margin: [5, 5, 5, 5] };
                                        doc.styles.tableBodyOdd = { margin: [5, 5, 5, 5] };
                                        doc.content.splice(0, 0, {
                                            text: 'Department Report',
                                            fontSize: 16,
                                            bold: true,
                                            alignment: 'center',
                                            margin: [0, 0, 0, 10]
                                        });
                                    }
                                },
                                {
                                    extend: 'print',
                                    className: 'btn btn-sm btn-outline-secondary',

                                    customize: function (win) {
                                        $(win.document.body)
                                            .css('font-size', '12pt')
                                            .css('padding', '20px');

                                        $(win.document.body).find('table')
                                            .addClass('compact')
                                            .css('margin', '20px auto')
                                            .css('border-collapse', 'collapse')
                                            .css('width', '100%');

                                        $(win.document.body).find('th, td')
                                            .css('padding', '8px')
                                            .css('border', '1px solid #ddd');
                                    }
                                }
                            ]

                        });

                    })
                    .catch(error => {
                        console.error("Error fetching pending payments:", error);
                        tableBody.innerHTML = `
                                                                                <tr><td colspan="9" class="text-center text-danger">Failed to load pending payments.</td></tr>
                                                                            `;
                        showCustomMessageBox(error.message || "Failed to load pending payments.", 'danger');
                    });
            }
        });
    </script>
@endpush