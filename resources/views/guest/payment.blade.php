@extends('guest.layout')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .payment-container {
            max-width: 600px;
            margin: auto;
            padding-top: 60px;
        }

        .accessory-total {
            margin-top: -10px;
            font-weight: 600;
        }
    </style>

    {{-- <div class="container payment-container">
        <div class="card shadow p-4">
            <h3 class="mb-4 text-center">Guest Payment Summary</h3>

            <input type="hidden" id="guest_id">
            <input type="hidden" id="resident_id">
            <input type="hidden" id="accessory_ids">
            <input type="hidden" id="hidden_final_amount">

            <div id="paymentDetails" class="mb-4">
                <div class='alert alert-info text-center'>Loading payment details...</div>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Total Amount (Advance for <span id="monthDisplay"></span> Months)
                    (₹)</label>
                <input type="number" id="amount" class="form-control" readonly>
            </div>

            <button id="payNowBtn" class="btn btn-primary w-100" disabled>Pay Now</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const guestInput = document.getElementById("guest_id");
            const residentInput = document.getElementById("resident_id");
            const accessoryInput = document.getElementById("accessory_ids");
            const amountInput = document.getElementById("amount");
            const hiddenFinalAmountInput = document.getElementById("hidden_final_amount");
            const paymentDetailsEl = document.getElementById("paymentDetails");
            const payNowBtn = document.getElementById("payNowBtn");
            const monthDisplay = document.getElementById("monthDisplay");

            payNowBtn.disabled = true;


            let accessoryIds = [];
            let numberOfMonths = 1;

            fetch(`/api/guest/total-amount`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                })
                .then(res => res.json())
                .then(response => {
                    if (!response.success || !response.data) {
                        paymentDetailsEl.innerHTML =
                            `<div class='alert alert-warning'>${response.message || 'No guest payment details found.'}</div>`;
                        throw new Error(response.message || 'No guest payment details found.');
                    }

                    const guestData = response.data;

                    guestInput.value = guestData.guest_id;
                    residentInput.value = guestData.resident_id || '';
                    accessoryIds = guestData.accessory_head_ids || [];
                    // console.log('Accessory IDs:', guestData);
                    accessoryInput.value = accessoryIds.join(',');

                    numberOfMonths = guestData.months || 1;
                    const hostelFeeTotal = parseFloat(guestData.hostel_fee) || 0;
                    const hostelFeePerMonth = hostelFeeTotal / numberOfMonths;
                    const cautionMoney = parseFloat(guestData.caution_money) || 0;
                    const accessoryAmount = parseFloat(guestData.total_accessory_amount) || 0;
                    const finalTotal = parseFloat(guestData.final_total_amount) || 0;

                    amountInput.value = finalTotal.toFixed(2);
                    hiddenFinalAmountInput.value = finalTotal.toFixed(2);
                    monthDisplay.textContent = numberOfMonths;

                    return fetch('/api/guests/accessories/active', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'token': localStorage.getItem('token'),
                                'auth-id': localStorage.getItem('auth-id')
                            }
                        })
                        .then(res => res.json())
                        .then(accessoryResponse => {
                            const allAccessories = accessoryResponse.data || [];
                            // console.log('All Accessories:', allAccessories);
                            const matchedAccessories = allAccessories.filter(acc =>
                                accessoryIds.includes(acc.accessory_head_id)
                            );

                            console.log('Matched Accessories:', matchedAccessories);

                            displayPaymentSummary(hostelFeeTotal, cautionMoney, accessoryAmount,
                                numberOfMonths, matchedAccessories);
                            payNowBtn.disabled = false;
                        });
                })
                .catch(error => {
                    console.error('Error:', error);
                    paymentDetailsEl.innerHTML =
                        `<div class='alert alert-danger'>Error loading payment details: ${error.message}</div>`;
                    payNowBtn.disabled = true;
                });

            function displayPaymentSummary(hostelFeeTotal, cautionMoney, accessoryAmount, numberOfMonths,
                matchedAccessories) {
                const accessoriesHtml = matchedAccessories.length > 0 ?
                    `
                <h5 class="mt-3">Accessory Breakdown</h5>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Amount (per month)</th>
                            <th>Total (${numberOfMonths} Months)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${matchedAccessories.map((acc, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${acc.accessory_head?.name || 'N/A'}</td>
                                    <td>₹${parseFloat(acc.price).toFixed(2)}</td>
                                    <td>₹${(parseFloat(acc.price) * numberOfMonths).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                    </tbody>
                </table>
                <div class="accessory-total text-start">Total Accessory Charges: ₹${accessoryAmount.toFixed(2)}</div>
            ` : `<p>No optional accessories selected for this guest.</p>`;

                paymentDetailsEl.innerHTML = `
            <div class="bg-light p-3 rounded border">
                <p><strong>Hostel Fee (${numberOfMonths} Month${numberOfMonths > 1 ? 's' : ''}):</strong> ₹${hostelFeeTotal.toFixed(2)}</p>
                <p><strong>Caution Money:</strong> ₹${cautionMoney.toFixed(2)}</p>
                ${accessoriesHtml}
            </div>
        `;
            }

            payNowBtn.addEventListener("click", function() {
                const queryParams = new URLSearchParams({
                    guest_id: guestInput.value,
                    resident_id: residentInput.value,
                    accessory_ids: accessoryInput.value,
                    amount: hiddenFinalAmountInput.value
                });

                $.ajax({
                    url: '/api/guest/initiate-transaction',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    data: JSON.stringify({
                        guest_id: guestInput.value,
                        amount: parseFloat(hiddenFinalAmountInput.value)
                    }),
                    success: function(response) {
                        if (response.success && response.data) {
                            const {
                                txnUrl,
                                body
                            } = response.data;

                            const form = document.createElement("form");
                            form.method = "POST";
                            form.action = txnUrl;

                            for (const key in body) {
                                if (body.hasOwnProperty(key)) {
                                    const input = document.createElement("input");
                                    input.type = "hidden";
                                    input.name = key;
                                    input.value = body[key];
                                    form.appendChild(input);
                                }
                            }

                            document.body.appendChild(form);
                            form.submit(); // 🚀 Redirect to Paytm
                        } else {
                            alert('Error initiating transaction: ' + (response.message ||
                                'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        alert('Error initiating transaction. Please try again.');
                    }
                });

                // window.location.href = `/guest/makepayment?${queryParams.toString()}`;
            });
        });
    </script> --}}

    <div class="container payment-container">
        <div class="card shadow p-4">
            <h3 class="mb-4 text-center">Guest Payment Summary</h3>

            <input type="hidden" id="guest_id">
            <input type="hidden" id="resident_id">
            <input type="hidden" id="accessory_ids">
            <input type="hidden" id="hidden_final_amount">

            <div id="paymentDetails" class="mb-4">
                <div class='alert alert-info text-center'>Loading payment details...</div>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">
                    Total Amount (Advance for <span id="monthDisplay"></span> Months) (₹)
                </label>
                <input type="number" id="amount" class="form-control" readonly>
            </div>

            <button id="payNowBtn" class="btn btn-primary w-100" disabled>Proceed to Pay</button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Confirm Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">You are about to pay:</p>
                    <h4 class="text-success mb-3">₹ <span id="confirmAmount"></span></h4>
                    <p class="small text-muted">Please review your details before continuing.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="confirmPayBtn" class="btn btn-success">Confirm & Pay</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const guestInput = document.getElementById("guest_id");
            const residentInput = document.getElementById("resident_id");
            const accessoryInput = document.getElementById("accessory_ids");
            const amountInput = document.getElementById("amount");
            const hiddenFinalAmountInput = document.getElementById("hidden_final_amount");
            const paymentDetailsEl = document.getElementById("paymentDetails");
            const payNowBtn = document.getElementById("payNowBtn");
            const confirmPayBtn = document.getElementById("confirmPayBtn");
            const confirmAmountEl = document.getElementById("confirmAmount");
            const monthDisplay = document.getElementById("monthDisplay");

            let accessoryIds = [];
            let numberOfMonths = 1;

            payNowBtn.disabled = true;

            // 🔹 Fetch payment details
            fetch(`/api/guest/total-amount`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                })
                .then(res => res.json())
                .then(response => {
                    if (!response.success || !response.data) {
                        paymentDetailsEl.innerHTML =
                            `<div class='alert alert-warning'>${response.message || 'No guest payment details found.'}</div>`;
                        throw new Error(response.message || 'No guest payment details found.');
                    }

                    const guestData = response.data;

                    guestInput.value = guestData.guest_id;
                    residentInput.value = guestData.resident_id || '';
                    accessoryIds = guestData.accessory_head_ids || [];
                    accessoryInput.value = accessoryIds.join(',');

                    numberOfMonths = guestData.months || 1;
                    const hostelFeeTotal = parseFloat(guestData.hostel_fee) || 0;
                    const cautionMoney = parseFloat(guestData.caution_money) || 0;
                    const accessoryAmount = parseFloat(guestData.total_accessory_amount) || 0;
                    const finalTotal = parseFloat(guestData.final_total_amount) || 0;

                    amountInput.value = finalTotal.toFixed(2);
                    hiddenFinalAmountInput.value = finalTotal.toFixed(2);
                    monthDisplay.textContent = numberOfMonths;

                    return fetch('/api/guests/accessories/active', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'token': localStorage.getItem('token'),
                                'auth-id': localStorage.getItem('auth-id')
                            }
                        })
                        .then(res => res.json())
                        .then(accessoryResponse => {
                            const allAccessories = accessoryResponse.data || [];
                            const matchedAccessories = allAccessories.filter(acc =>
                                accessoryIds.includes(acc.accessory_head_id)
                            );

                            displayPaymentSummary(hostelFeeTotal, cautionMoney, accessoryAmount,
                                numberOfMonths, matchedAccessories);

                            payNowBtn.disabled = false;
                        });
                })
                .catch(error => {
                    console.error('Error:', error);
                    paymentDetailsEl.innerHTML =
                        `<div class='alert alert-danger'>Error loading payment details: ${error.message}</div>`;
                    payNowBtn.disabled = true;
                });

            // 🔹 Show payment summary
            function displayPaymentSummary(hostelFeeTotal, cautionMoney, accessoryAmount, numberOfMonths,
                matchedAccessories) {
                const accessoriesHtml = matchedAccessories.length > 0 ? `
            <h5 class="mt-3">Accessory Breakdown</h5>
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Amount (per month)</th>
                        <th>Total (${numberOfMonths} Months)</th>
                    </tr>
                </thead>
                <tbody>
                    ${matchedAccessories.map((acc, index) => `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${acc.accessory_head?.name || 'N/A'}</td>
                                                <td>₹${parseFloat(acc.price).toFixed(2)}</td>
                                                <td>₹${(parseFloat(acc.price) * numberOfMonths).toFixed(2)}</td>
                                            </tr>
                                        `).join('')}
                </tbody>
            </table>
            </div>
            <div class="accessory-total text-start">Total Accessory Charges: ₹${accessoryAmount.toFixed(2)}</div>
        ` : `<p>No optional accessories selected for this guest.</p>`;

                paymentDetailsEl.innerHTML = `
            <div class="bg-light p-3 rounded border">
                <p><strong>Hostel Fee (${numberOfMonths} Month${numberOfMonths > 1 ? 's' : ''}):</strong> ₹${hostelFeeTotal.toFixed(2)}</p>
                <p><strong>Caution Money:</strong> ₹${cautionMoney.toFixed(2)}</p>
                ${accessoriesHtml}
            </div>
        `;
            }

            // 🔹 Step 1: Open confirmation modal
            payNowBtn.addEventListener("click", function() {
                confirmAmountEl.textContent = hiddenFinalAmountInput.value;
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
                confirmModal.show();
            });

            // 🔹 Step 2: Proceed to payment after confirmation
            confirmPayBtn.addEventListener("click", function() {
                $.ajax({
                    url: '/api/guest/payment/initiate',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    data: JSON.stringify({
                        guest_id: guestInput.value,
                        amount: parseFloat(hiddenFinalAmountInput.value)
                    }),
                    success: function(response) {
                        if (response.success && response.data) {
                            const {
                                txnUrl,
                                body
                            } = response.data;

                            const form = document.createElement("form");
                            form.method = "POST";
                            form.action = txnUrl;

                            for (const key in body) {
                                if (body.hasOwnProperty(key)) {
                                    const input = document.createElement("input");
                                    input.type = "hidden";
                                    input.name = key;
                                    input.value = body[key];
                                    form.appendChild(input);
                                }
                            }

                            document.body.appendChild(form);
                            form.submit(); // 🚀 Redirect to Paytm
                        } else {
                            alert('Error initiating transaction: ' + (response.message ||
                                'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        alert('Error initiating transaction. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
