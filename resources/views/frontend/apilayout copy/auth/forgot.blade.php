@extends('frontend.apilayout.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning">Forgot Password</div>
    <div class="card-body">
        <form id="forgotForm">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" id="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Send Reset Link</button>
        </form>
        <div id="forgotMessage" class="mt-3"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('forgotForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    try {
        let res = await axios.post('/auth/forgot-password', {
            email: document.getElementById('email').value,
        });
        document.getElementById('forgotMessage').innerHTML =
            `<div class="alert alert-success">${res.data.message}</div>`;
    } catch (err) {
        document.getElementById('forgotMessage').innerHTML =
            `<div class="alert alert-danger">${err.response.data.message}</div>`;
    }
});
</script>
@endpush
