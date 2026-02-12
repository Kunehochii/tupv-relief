@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="container py-3 py-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
            <h4 class="mb-0">Notifications</h4>
            @if ($notifications->where('read_at', null)->count() > 0)
                <form method="POST" action="{{ route(auth()->user()->role . '.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-check-all me-1"></i><span class="d-none d-sm-inline">Mark all as read</span><span
                            class="d-sm-none">Read all</span>
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item px-3 py-2 px-md-3 py-md-3 {{ $notification->isRead() ? '' : 'bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="d-flex align-items-start flex-grow-1 min-width-0">
                                <div class="me-2 me-md-3 flex-shrink-0">
                                    @php
                                        $iconClass = match ($notification->type) {
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
                                    <i class="bi {{ $iconClass }} fs-5 fs-md-4"></i>
                                </div>
                                <div class="min-width-0">
                                    <h6 class="mb-0 mb-md-1" style="font-size: 0.9rem;">{{ $notification->title }}</h6>
                                    <p class="mb-1 text-muted small text-break">{{ $notification->message }}</p>
                                    <small class="text-muted"
                                        style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @if (!$notification->isRead())
                                <form method="POST"
                                    action="{{ route(auth()->user()->role . '.notifications.read', $notification) }}"
                                    class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary px-2 py-1">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center py-4 py-md-5 text-muted">
                        <i class="bi bi-bell-slash fs-2 fs-md-1 mb-2 mb-md-3 d-block"></i>
                        <p class="mb-0">No notifications yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
