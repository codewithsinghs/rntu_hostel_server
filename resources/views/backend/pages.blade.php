@extends('backend.layouts.app')

@section('title', 'Home Page')
@section('meta_description', 'Welcome to RNTU Hostel.')

@push('styles')
@endpush

@section('content')

   

@endsection

@push('scripts')
    <script>
        // Page-specific JS
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Index page loaded');
        });
    </script>
@endpush
