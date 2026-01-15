@extends('layouts.app')

@section('title', 'All Pledges - Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Pledges</h2>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.pledges.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="distributed" {{ request('status') === 'distributed' ? 'selected' : '' }}>Distributed</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <select name="drive_id" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Drives</option>
                    @foreach($drives as $drive)
                        <option value="{{ $drive->id }}" {{ request('drive_id') == $drive->id ? 'selected' : '' }}>
                            {{ $drive->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Drive</th>
                        <th>Donor</th>
                        <th>Type</th>
                        <th>Amount/Qty</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pledges as $pledge)
                        <tr>
                            <td>
                                <a href="{{ route('admin.pledges.show', $pledge) }}" class="fw-bold">
                                    {{ $pledge->reference_number }}
                                </a>
                            </td>
                            <td>{{ Str::limit($pledge->drive->name, 25) }}</td>
                            <td>
                                {{ $pledge->user->name }}
                                @if($pledge->user->role === 'ngo')
                                    <span class="badge bg-info">NGO</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ ucfirst($pledge->type) }}</span>
                            </td>
                            <td>
                                @if($pledge->type === 'financial')
                                    ₱{{ number_format($pledge->amount, 2) }}
                                @else
                                    {{ $pledge->quantity }} {{ $pledge->item_description ?? 'items' }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $pledge->status_color }}">{{ ucfirst($pledge->status) }}</span>
                            </td>
                            <td>{{ $pledge->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.pledges.show', $pledge) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No pledges found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pledges->hasPages())
            <div class="card-footer">
                {{ $pledges->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
