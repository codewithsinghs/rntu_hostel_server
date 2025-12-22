@extends('resident.layout')

@section('content')
    <div class="container mt-5">
        <!-- Success Alert -->
        <div id="successAlert" class="alert alert-success text-center" style="display: none;">
            Payment was successful!
        </div>

        <!-- Error Alert -->
        <div id="errorAlert" class="alert alert-danger text-center" style="display: none;">
            Payment failed!
        </div>

        <h2 class="text-center">Make Payment</h2>

        <form id="paymentForm" method="POST">
            @csrf

            <div class="mb-3">
                <label for="amount" class="form-label">Enter Payment Amount:</label>
                <input type="number" id="amount" name="amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Select Payment Method:</label>
                <select name="payment_method" class="form-control" required>
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Card">Card</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="transaction_id" class="form-label">Transaction ID (Optional):</label>
                <input type="text" name="transaction_id" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Submit Payment</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Set up AJAX with CSRF token and auth headers
            $.ajaxSetup({
                  headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
            });
            //fetch payment amount
            let invoice_id = window.location.pathname.replace(/\/$/, "").split("/").pop(); 
            fetch(`/api/resident/invoices/${invoice_id}`, {
                method: 'GET',
                  headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
            })
            .then(res => res.json())
            .then(response => {
                if (response.data) {
                // console.log(response);
                    document.getElementById("amount").value = response.data.remaining_amount;
                } else {
                    console.error('Failed to fetch invoice details:', response.message);
                }
            })  .catch(error => {
                console.error('Error fetching invoice details:', error);
            }); 
        });
        // Handle form submission with AJAX
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Prevent the default form submission

            // Create a FormData object to send the form data
            let formData = new FormData(this);
            // console.log('Here form data', formData );
          
            let invoice_id = window.location.pathname.replace(/\/$/, "").split("/").pop(); 
            // Send the data via AJAX using fetch
            fetch(`/api/resident/invoices/${invoice_id}/pay`, {
                method: 'POST',
                body: formData,
                  headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show success alert
                    document.getElementById('successAlert').style.display = 'block';
                    document.getElementById('errorAlert').style.display = 'none';
                    window.location.href='/resident/payment';
                    // Optionally, add more logic like showing transaction ID or remaining balance
                    // console.log('Payment was successful!', data.transaction_id);
                } else {
                    // Show error alert
                    document.getElementById('errorAlert').style.display = 'block';
                    document.getElementById('successAlert').style.display = 'none';

                    console.log('Payment failed:', data.message);
                }
            })
            .catch(error => {
                document.getElementById('errorAlert').style.display = 'block';
                document.getElementById('successAlert').style.display = 'none';
                console.error('Error:', error);
            });
        });

        //  $.ajax({
        //     url: '/api/guest/initiate-transaction',
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'Accept': 'application/json',
        //         'token': localStorage.getItem('token'), 
        //         'auth-id': localStorage.getItem('auth-id')
        //     },
        //     data: JSON.stringify({
        //         guest_id: guestInput.value,
        //         amount: parseFloat(hiddenFinalAmountInput.value)
        //     }),
        //     success: function(response) {
        //     if (response.success && response.data) {
        //         const { txnUrl, body } = response.data;

        //         const form = document.createElement("form");
        //         form.method = "POST";
        //         form.action = txnUrl;

        //         for (const key in body) {
        //             if (body.hasOwnProperty(key)) {
        //                 const input = document.createElement("input");
        //                 input.type = "hidden";
        //                 input.name = key;
        //                 input.value = body[key];
        //                 form.appendChild(input);
        //             }
        //         }

        //         document.body.appendChild(form);
        //         form.submit(); // 🚀 Redirect to Paytm
        //     } else {
        //         alert('Error initiating transaction: ' + (response.message || 'Unknown error'));
        //     }
        // },
        // error: function(xhr, status, error) {
        //         console.error('AJAX Error:', status, error);
        //         alert('Error initiating transaction. Please try again.');
        //     }
        // });
    </script>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection  
