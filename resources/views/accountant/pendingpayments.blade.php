@extends('accountant.layout')

@section('content')
<div class="container mt-4">
        <div class="mt-5 mb-3">
            <h2>Pending Payments</h2>
        </div>

        <div class="mb-4 cust_box">
            <div class="cust_heading">
                Pending Payment List
            </div>
            <div id="mainResponseMessage" class="mt-3"></div> {{-- Message container for the page --}}
<table class="table table-striped table-bordered" id="pendingPaymentsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Serial No.</th>
                                <th>Enrollment Number</th>
                                <th>Resident Name</th>
                                <th>Pay Type</th>
                                <th>Total Amount</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="10" class="text-center">Loading pending payments...</td></tr>
                        </tbody>
                    </table>
        </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card shadow">
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ✅ Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="PaymentForm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editModalLabel">Payment Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="invoice_id" name="id">
                    <div class="mb-3" id="labels"></div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Paid Amount</label>
                        <input type="text" id="amount" name="amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" id="transaction_id" name="transaction_id" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Transaction Date</label>
                        <input type="date" id="transaction_date" name="transaction_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="narration" class="form-label">Narration</label>
                        <input type="text" id="narration" name="narration" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                // console.log("Fetched pending payments:", payments);
                tableBody.innerHTML = "";

                if (!response.success || !Array.isArray(payments) || payments.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="9" class="text-center">No pending payments found.</td></tr>`;
                    if (!response.success && response.message) {
                        showCustomMessageBox(response.message, 'info'); // Use info for "no payments found"
                    }
                    return;
                }

                payments.forEach((payment, index) => {
                    // console.log("Payment:", payment);
                    // console.log("Guest Info:", payment.guest.bihar_credit_card);
                    let pay_type = '';
                    if(payment.guest.bihar_credit_card == 1) {
                        pay_type = 'Bihar Credit Card';
                    } 
                    else if(payment.guest.tnsd == 1) {
                        pay_type = 'TNSD';
                    } 
                    else {
                        pay_type = 'Regular';
                    }
                    // console.log("Pay Type:", pay_type);
                    tableBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td> 
                            <td>${payment.scholar_no || 'N/A'}</td>
                            <td>${payment.resident_name || 'N/A'}</td>
                            <td>${pay_type || 'Regular'}</td>
                            <td>${payment.total_amount || '0'}</td>
                            <td>${payment.amount_paid || '0'}</td>
                            <td>${payment.remaining_amount || '0'}</td>
                            <td>${payment.payment_status || 'N/A'}</td>
                            <td>${payment.due_date ?? 'N/A'}</td>
                            <td>${payment.created_at ? new Date(payment.created_at).toLocaleString() : 'N/A'}</td>
                            <td><button class="btn btn-sm btn-primary btn-edit" data-total_amount="${payment.total_amount}" data-resident_name="${payment.resident_name}" data-scholar_no="${payment.scholar_no}" data-remaining_amount="${payment.remaining_amount}" data-id="${payment.payment_id}">Pay Now</button></td>
                        </tr>
                    `;
                });
                // Datatable
                InitializeDatatable();

                if (response.message) {
                    showCustomMessageBox(response.message, 'success');
                }
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

<script type="text/javascript">
    $(document).ready(function() {
      $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const resident_name = $(this).data('resident_name');
        const scholar_no = $(this).data('scholar_no');  
        const remaining_amount = $(this).data('remaining_amount');
        const total_amount = $(this).data('total_amount');
        // Prefill modal inputs
        $('#invoice_id').val(id);
        $('#labels').html('<label class="form-label">Resident Name :'+ resident_name +' </label><br><label class="form-label">Scholar Number : '+ scholar_no +' </label><br><label class="form-label">Total Amount : '+ total_amount +' </label><br><label class="form-label">Remaining Amount : '+ remaining_amount +' </label>');
        // Show modal
        $('#editModal').modal('show');
    });

    // Handle Form Submit (AJAX)
    $('#PaymentForm').on('submit', function(e) {
        e.preventDefault();

        const invoice_id = $('#invoice_id').val();
        const amount = $('#amount').val();
        const transaction_id = $('#transaction_id').val();
        const transaction_date = $('#transaction_date').val();
        const narration = $('#narration').val();

        $.ajax({
            url: '/api/accountant/submitPayment',
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                'token': localStorage.getItem('token'),
                'auth-id': localStorage.getItem('auth-id')
            },
            data: JSON.stringify({
                _token: $('meta[name="csrf-token"]').attr('content'),
                invoice_id,
                amount,
                transaction_id,
                transaction_date,
                narration
            }),
            success: function(response) {
                console.log("abcd",response);
                if (response.success) {
                    // fetchPendingPayments();
                    $('#editModal').modal('hide');
                    showCustomMessageBox(response.message, 'success');
                    // Optionally, refresh the table or update the specific row
                    setTimeout(() => { location.reload(); }, 500);
                } else {
                    showCustomMessageBox(response.message, 'danger');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong!');
            }
        });

    });
});
</script>
@endpush