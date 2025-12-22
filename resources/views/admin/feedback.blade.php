@extends('admin.layout')

@section('content')



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-4">

        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>Resident Feedback</h2>

        </div>

        <div
            style=" padding: 10px; margin: 10px auto; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.3rem;">
            Feedbacks List</div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S. No.</th>
                    <th>Resident Name</th>
                    <th>User Email</th>
                    <th>Facility Name</th>
                    <th>Feedback</th>
                    <th>Suggestion</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="feedback-list">
                <tr>
                    <td colspan="7" class="text-center">Loading feedbacks...</td>
                </tr>
            </tbody>
        </table>

        {{-- Delete Confirmation Modal --}}
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this room? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/api/admin/feedbacks', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                }
            }) // API endpoint from your controller
                .then(response => response.json())
                .then(data => {
                    let feedbackList = document.getElementById('feedback-list');
                    feedbackList.innerHTML = ""; // Clear the loading message

                    // Access the feedback array from the 'data' property of the API response
                    const feedbacks = data.data;

                    let count = 1;

                    if (!feedbacks || feedbacks.length === 0) {
                        feedbackList.innerHTML = `<tr><td colspan="7" class="text-center">No feedbacks available.</td></tr>`;
                        return;
                    }

                    feedbacks.forEach(feedback => {
                        let residentName = feedback.resident ? feedback.resident.user.name : 'N/A';
                        let userEmail = feedback.resident && feedback.resident.user ? feedback.resident.user.email : 'N/A';

                        feedbackList.innerHTML += `
                                            <tr>
                                                <td>${count++}</td>
                                                <td>${residentName}</td>
                                                <td>${userEmail}</td>
                                                <td>${feedback.facility_name}</td>
                                                <td>${feedback.feedback}</td>
                                                <td>${feedback.suggestion ? feedback.suggestion : 'N/A'}</td>
                                                <td>${new Date(feedback.created_at).toLocaleDateString()}</td>
                                            </tr>
                                        `;
                    });

                    // Datatable
                    InitializeDatatable();

                })

                .catch(error => {
                    console.error('Error fetching feedback:', error);
                    let feedbackList = document.getElementById('feedback-list');
                    feedbackList.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading feedbacks. Please try again.</td></tr>`;
                });
        });
    </script>
@endpush