@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Notifications</h4>
        @if($notifications->where('read_at', null)->count() > 0)
            <form method="POST" action="{{ route(auth()->user()->role . '.notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-check-all me-1"></i>Mark all as read
                </button>
            </form>
        @endif
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <div class="list-group-item {{ $notification->isRead() ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                @php
                                    $iconClass = match($notification->type) {
                                        'pledge_verified' => 'bi-check-circle text-success',
                                        'pledge_expired' => 'bi-x-circle text-danger',
                                        'pledge_expiry_warning' => 'bi-exclamation-triangle text-warning',
                                        'donation_distributed' => 'bi-box-seam text-purple',
                                        'new_drive' => 'bi-megaphone text-info',
                                        'ngo_verified' => 'bi-building-check text-success',
                                        'ngo_rejected' => 'bi-building-x text-danger',
                                        default => 'bi-bell text-secondary',
                                    };
                                @endphp
                                <i class="bi {{ $iconClass }} fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if(!$notification->isRead())
                            <form method="POST" action="{{ route(auth()->user()->role . '.notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center py-5 text-muted">
                    <i class="bi bi-bell-slash fs-1 mb-3"></i>
                    <p class="mb-0">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
