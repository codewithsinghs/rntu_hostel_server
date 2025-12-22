@extends('admin.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="mt-5 mb-3">
            <h2 class="mb-3">Pending Payments</h2>


            <!-- Pending Payments List Table -->
            <div class="mb-4"
                style="border: 1px solid #2125294d; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);overflow: auto;">
                <div
                    style="padding: 10px; margin-bottom: 20px; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.2rem;">
                    Pending Payments List
                </div>

                <div id="mainResponseMessage" class="mt-3"></div> {{-- Message container for the page --}}

                <table class="table table-bordered" id="pendingPaymentsTable">
                    <thead class="table-dark">
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

        <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

    @push('scripts')
        <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
        @include('backend.components.datatable-lib')

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