@extends('guest.layout')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container payment-container">
        <div class="card shadow p-4">
            <h3 class="mb-4 text-center">Guest Payment Confirmation</h3>

            <div id="paymentSummary" class="mb-4">
                <div class="alert alert-info text-center">Loading payment summary...</div>
            </div>

            <div class="d-flex justify-content-between">
                <button id="cancelBtn" class="btn btn-secondary">Cancel</button>
                <button id="confirmBtn" class="btn btn-primary" disabled>Proceed to Pay</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const guestId = localStorage.getItem('guest_id'); // or pass via query param
            const confirmBtn = document.getElementById('confirmBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const summaryEl = document.getElementById('paymentSummary');
            let paymentData = null;

            // 1️⃣ Fetch payment preview
            fetch('/api/guest/payment/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    body: JSON.stringify({
                        guest_id: guestId
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.success) throw new Error(res.message || 'Failed to load data');
                    paymentData = res;

                    // Show summary
                    const invoicesHtml = paymentData.invoices && paymentData.invoices.length > 0 ? `
            <ul>${paymentData.invoices.map(i => `<li>${i.invoice_number} (₹${i.total_amount.toFixed(2)})</li>`).join('')}</ul>
        ` : '<p>No invoices.</p>';

                    summaryEl.innerHTML = `
            <p><strong>Name:</strong> ${paymentData.guest.name}</p>
            <p><strong>Email:</strong> ${paymentData.guest.email}</p>
            <p><strong>Phone:</strong> ${paymentData.guest.phone}</p>
            <p><strong>Purpose:</strong> ${paymentData.purpose}</p>
            <p><strong>Amount:</strong> ₹${paymentData.amount.toFixed(2)}</p>
            <h5>Invoices:</h5>
            ${invoicesHtml}
        `;

                    confirmBtn.disabled = false;
                })
                .catch(err => {
                    summaryEl.innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
                    confirmBtn.disabled = true;
                });

            // 2️⃣ Cancel button
            cancelBtn.addEventListener('click', () => {
                window.history.back();
            });

            // 3️⃣ Confirm button → initiate transaction
            confirmBtn.addEventListener('click', () => {
                if (!paymentData) return;

                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Processing...';

                fetch('/api/guest/payment/initiate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'token': localStorage.getItem('token'),
                            'auth-id': localStorage.getItem('auth-id')
                        },
                        body: JSON.stringify({
                            guest_id: paymentData.guest.id,
                            amount: paymentData.amount,
                            invoice_number: paymentData.invoices.map(i => i.invoice_number)
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            const {
                                txnUrl,
                                body
                            } = res.data;
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
                            form.submit(); // redirect to gateway
                        } else {
                            alert('Error initiating transaction: ' + (res.message || 'Unknown'));
                            confirmBtn.disabled = false;
                            confirmBtn.textContent = 'Proceed to Pay';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error initiating transaction.');
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Proceed to Pay';
                    });
            });
        });
    </script>
@endsection
