@extends('backend.layouts.app')

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('backend/css/admin.css') }}" /> --}}
@endpush

@push('navmenu')
    {{-- @include('backend.components.admin-nav') --}}
     @include('backend.components.main')
@endpush
{{-- @push('lscript')
    
@endpush --}}
