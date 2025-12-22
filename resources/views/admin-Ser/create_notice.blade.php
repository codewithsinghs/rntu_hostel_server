@extends('admin.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="mt-5 mb-3">
            <h2>Create Notice</h2>
        </div>

        <div class="cust_box">
            <div class="cust_heading">
                Notification
            </div>

            <div id="messageContainer"></div>

            <form id="createNoticeForm">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-2">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" required>
                    </div>

                </div>

                <div class="row mt-3">
                    <div class="col-md-12 mb-2">
                        <label for="message_from" class="form-label">Message From</label>
                        <input type="text" class="form-control" id="message_from" name="message_from" required>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                    </div>
                </div>

                <div class="row mt-3">

                    <div class="col-md-6 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Create Notice</button>
                    </div>

                    <div class="col-md-6 mb-2 d-flex align-items-end">
                        <a href="{{ url('/admin/notices') }}" class="btn btn-secondary w-100">Cancel</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("createNoticeForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            let formData = {
                message_from: document.getElementById("message_from").value,
                message: document.getElementById("message").value,
                from_date: document.getElementById("from_date").value,
                to_date: document.getElementById("to_date").value
            };

            fetch("{{ url('/api/admin/notices') }}", {  // Directly calling the API
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        window.location.href = "{{ url('/admin/notices') }}"; // Redirect to Notices List
                    } else {
                        alert("Failed to create notice.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while creating the notice.");
                });
        });
    </script>
@endsection