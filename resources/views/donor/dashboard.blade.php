@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('content')
    <div class="container-fluid py-4 px-md-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Drive Carousel --}}
        @include('partials.drive-carousel', ['drives' => $activeDrives, 'userType' => 'donor'])
    </div>
@endsection
