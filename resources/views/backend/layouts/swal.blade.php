@if (session('swal_success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('swal_success') }}",
            timer: 2500,
            showConfirmButton: false
        });
    </script>
@endif

@if (session('swal_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('swal_error') }}",
            confirmButtonColor: '#d33'
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    </script>
@endif
