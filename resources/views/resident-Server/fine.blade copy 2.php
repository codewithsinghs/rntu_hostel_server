@extends('resident.layout')

@section('content')
@if (session('success'))
<div class="alert alert-success text-center mt-3">
    {{ session('success') }}
</div>
@endif

<h2 class="text-center mt-3">Your Pending Hostel Fines</h2>

<div class="container mt-4">
    <table class="table table-bordered table-striped" id="payments-table" style="display: none;">
        <thead class="table-dark">
            <tr>
                <th>Fee Type</th>
                <th>Remarks</th>
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Remaining Amount</th>
                <th>Payment Status</th>
                <th>Payment Method</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="payments-body"></tbody>
    </table>

    <p id="no-payments" class="text-danger text-center mt-3" style="display: none;">No pending fines found.</p>
    <p id="error-message" class="text-danger text-center mt-3" style="display: none;">Error loading data, please try again.</p>
</div>

<div class="modal fade" id="invoiceItemsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Invoice Items</h5></div>
      <div class="modal-body">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Amount (₹)</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="invoiceItemsBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script>
    // document.addEventListener("DOMContentLoaded", function () {
    //     const table = document.getElementById("payments-table");
    //     const tbody = document.getElementById("payments-body");
    //     const noData = document.getElementById("no-payments");
    //     const errorMsg = document.getElementById("error-message");


    //     // Clear previous data
    //     tbody.innerHTML = "";
    //     table.style.display = "none";
    //     noData.style.display = "none";
    //     errorMsg.style.display = "none";

    //     fetch("{{url('/api/resident/appliedFines')}}", {
    //         method: "GET",
    //         headers: {
    //             "Accept": "application/json",
    //             'token': localStorage.getItem('token'),
    //             'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
    //         }
    //     })

    //         .then(response => {
    //             if (!response.ok) {
    //                 // throw new Error(`HTTP error! status: ${response.status}`);
    //             }
    //             return response.json();
    //         })

    //         .then(apiResponse => {
    //             const subscriptions = (apiResponse.success && apiResponse.data && apiResponse.data.subscriptions)
    //                 ? apiResponse.data.subscriptions
    //                 : [];

    //             let hasPayments = false;

    //             subscriptions.forEach(subscription => {
    //                 // ✅ Show only "Other" fees (which represent fines)
    //                 if (subscription.subscription_type !== "Other") {
    //                     return;
    //                 }

    //                 const feeType = "Fine"; // Display name override
    //                 const subscriptionId = subscription.subscription_id;
    //                 const remarks = subscription.remarks ?? 'N/A';

    //                 if (subscription.payments && subscription.payments.length > 0) {
    //                     const payment = subscription.payments[0]; // latest

    //                     if (payment.payment_status === 'Pending') {
    //                         hasPayments = true;

    //                         const row = `
    //                             <tr>
    //                                 <td>${feeType}</td>
    //                                 <td>${remarks}</td>
    //                                 <td>₹${payment.total_amount ?? '0.00'}</td>
    //                                 <td>₹${payment.amount ?? '0.00'}</td>
    //                                 <td>₹${payment.remaining_amount ?? '0.00'}</td>
    //                                 <td><span class="badge bg-warning text-dark">${payment.payment_status}</span></td>
    //                                 <td>${payment.payment_method ?? 'N/A'}</td>
    //                                 <td>${payment.created_at ?? '-'}</td>
    //                                 <td>
    //                                     <a href="/resident/subscription_payment?subscription_id=${subscriptionId}&amount=${payment.remaining_amount}" class="btn btn-danger btn-sm">Pay Fine</a>
    //                                 </td>
    //                             </tr>
    //                         `;
    //                         tbody.insertAdjacentHTML('beforeend', row);
    //                     }
    //                 }
    //             });

    //             if (hasPayments) {
    //                 table.style.display = "table";
    //             } else {
    //                 noData.style.display = "block";
    //             }
    //         })
    //         .catch(error => {
    //             errorMsg.innerText = "Error loading payments: " + error.message;
    //             errorMsg.style.display = "block";
    //         });
    // });



    // document.addEventListener("DOMContentLoaded", function() {
    //     const table = document.getElementById("payments-table");
    //     const tbody = document.getElementById("payments-body");
    //     const noData = document.getElementById("no-payments");
    //     const errorMsg = document.getElementById("error-message");

    //     // Reset UI
    //     tbody.innerHTML = "";
    //     table.style.display = "none";
    //     noData.style.display = "none";
    //     errorMsg.style.display = "none";

    //     fetch("/api/resident/pending/appliedFines", {
    //             method: "POST",
    //             headers: {
    //                 "Content-Type": "application/json",
    //                 "Accept": "application/json",
    //                 "token": localStorage.getItem("token"),
    //                 "auth-id": localStorage.getItem("auth-id")
    //             },
    //             body: JSON.stringify({
    //                 resident_id: localStorage.getItem("auth-id")
    //             }) // or pass explicitly
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (!data.success || !data.fines || data.fines.length === 0) {
    //                 noData.style.display = "block";
    //                 return;
    //             }

    //             const rows = data.fines.map(fine => `
    //         <tr>
    //             <td>Fine</td>
    //             <td>${fine.description || '—'}</td>
    //             <td class="text-end">₹${parseFloat(fine.total_amount).toFixed(2)}</td>
    //             <td class="text-end">₹${parseFloat(fine.amount_paid || 0).toFixed(2)}</td>
    //             <td class="text-end">₹${parseFloat(fine.total_amount - (fine.amount_paid || 0)).toFixed(2)}</td>
    //             <td><span class="badge ${fine.status === 'processed' ? 'bg-success' : 'bg-warning'}">${fine.status}</span></td>
    //             <td>${fine.payment_method || '—'}</td>
    //             <td>${fine.created_at ? new Date(fine.created_at).toLocaleDateString('en-GB') : '-'}</td>
    //             <td>
    //                 <button class="btn btn-info btn-sm" onclick="viewInvoiceItems(${fine.invoice_id})">View Items</button>
    //             </td>
    //         </tr>
    //     `).join('');

    //             tbody.innerHTML = rows;
    //             table.style.display = "table";
    //         })
    //         .catch(error => {
    //             errorMsg.innerText = "Error loading fines: " + error.message;
    //             errorMsg.style.display = "block";
    //         });
    // });
    document.addEventListener("DOMContentLoaded", function() {
        const table = document.getElementById("payments-table");
        const tbody = document.getElementById("payments-body");
        const noData = document.getElementById("no-payments");
        const errorMsg = document.getElementById("error-message");

        // Reset UI
        tbody.innerHTML = "";
        table.style.display = "none";
        noData.style.display = "none";
        errorMsg.style.display = "none";

        fetch("/api/resident/pending/appliedFines", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "token": localStorage.getItem("token"),
                    "auth-id": localStorage.getItem("auth-id")
                },
                body: JSON.stringify({
                    resident_id: localStorage.getItem("auth-id")
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.fines || data.fines.length === 0) {
                    noData.style.display = "block";
                    return;
                }

                const rows = data.fines.map(fine => {
                    const remaining = parseFloat(fine.total_amount) - parseFloat(fine.amount_paid || 0);
                    const payButton = remaining > 0 ?
                        `<button class="btn btn-danger btn-sm" onclick="initiateFinePayment(${fine.invoice_id}, ${remaining})">Pay Now</button>` :
                        `<span class="text-muted">Paid</span>`;

                    return `
                <tr>
                    <td>Fine</td>
                    <td>${fine.description || '—'}</td>
                    <td class="text-end">₹${parseFloat(fine.total_amount).toFixed(2)}</td>
                    <td class="text-end">₹${parseFloat(fine.amount_paid || 0).toFixed(2)}</td>
                    <td class="text-end">₹${remaining.toFixed(2)}</td>
                    <td><span class="badge ${fine.invoice_status === 'paid' ? 'bg-success' : 'bg-warning'}">${fine.invoice_status}</span></td>
                    <td>${fine.payment_method || '—'}</td>
                    <td>${fine.created_at ? new Date(fine.created_at).toLocaleDateString('en-GB') : '-'}</td>
                    <td>
  <button class="btn btn-info btn-sm" onclick="viewInvoiceItems(${fine.invoice_id})">View Items</button>
</td>

                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewInvoiceItems(${fine.invoice_id})">View Items</button>
                        ${payButton}
                    </td>
                </tr>
            `;
                }).join('');

                tbody.innerHTML = rows;
                table.style.display = "table";
            })
            .catch(error => {
                errorMsg.innerText = "Error loading fines: " + error.message;
                errorMsg.style.display = "block";
            });
    });


    function initiateFinePayment(invoiceId, amount) {
        fetch("/api/resident/payment/initiate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "token": localStorage.getItem("token"),
                    "auth-id": localStorage.getItem("auth-id")
                },
                body: JSON.stringify({
                    invoice_id: invoiceId,
                    amount: amount
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.script) {
                    const script = document.createElement("script");
                    script.innerHTML = data.script;
                    document.body.appendChild(script);
                } else {
                    alert("Unable to initiate payment.");
                }
            })
            .catch(err => {
                console.error("Payment initiation failed:", err);
            });
    }

    async function viewInvoiceItems(invoiceId) {
    try {
        const res = await fetch("/api/resident/payment/initiate", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "token": localStorage.getItem("token"),
                "auth-id": localStorage.getItem("auth-id")
            },
            body: JSON.stringify({ invoice_id: invoiceId })
        });

        const data = await res.json();
        if (data.success && data.items) {
            const rows = data.items.map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.description}</td>
                    <td class="text-end">₹${parseFloat(item.total_amount).toFixed(2)}</td>
                    <td><span class="badge bg-secondary">${item.status}</span></td>
                </tr>
            `).join('');

            document.getElementById("invoiceItemsBody").innerHTML = rows;
            new bootstrap.Modal(document.getElementById("invoiceItemsModal")).show();
        } else {
            alert("No items found for this invoice.");
        }
    } catch (error) {
        console.error("Error loading invoice items:", error);
    }
}



    // function renderPaymentsTable(invoices) {
    //     const table = document.getElementById("payments-table");
    //     const tbody = document.getElementById("payments-body");

    //     if (!invoices || invoices.length === 0) {
    //         table.style.display = "none";
    //         tbody.innerHTML = "<tr><td colspan='9' class='text-center'>No payments found</td></tr>";
    //         return;
    //     }

    //     const rows = invoices.map(invoice => `
    //     <tr>
    //         <td>${invoice.fee_type || 'N/A'}</td>
    //         <td>${invoice.remarks || '—'}</td>
    //         <td class="text-end">₹${parseFloat(invoice.total_amount).toFixed(2)}</td>
    //         <td class="text-end">₹${parseFloat(invoice.amount_paid).toFixed(2)}</td>
    //         <td class="text-end">₹${parseFloat(invoice.remaining_amount).toFixed(2)}</td>
    //         <td>
    //             <span class="badge ${invoice.payment_status === 'paid' ? 'bg-success' : 'bg-warning'}">
    //                 ${invoice.payment_status}
    //             </span>
    //         </td>
    //         <td>${invoice.payment_method || '—'}</td>
    //         <td>${new Date(invoice.created_at).toLocaleDateString('en-GB')}</td>
    //         <td>
    //             <button class="btn btn-info btn-sm" onclick="viewInvoiceItems(${invoice.id})">View Items</button>
    //         </td>
    //     </tr>
    // `).join('');

    //     tbody.innerHTML = rows;
    //     table.style.display = "table";
    // }


    // async function viewFines(invoiceId) {
    //     const res = await fetch('/api/resident/appliedFines', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'Accept': 'application/json',
    //             'token': localStorage.getItem('token'),
    //             'auth-id': localStorage.getItem('auth-id')
    //         },
    //         body: JSON.stringify({
    //             invoice_id: invoiceId
    //         })
    //     });

    //     const data = await res.json();
    //     if (data.success) {
    //         // Render fines in modal or table
    //         console.log(data.fines);
    //         renderPaymentsTable(data.invoices);
    //     }
    // }
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection