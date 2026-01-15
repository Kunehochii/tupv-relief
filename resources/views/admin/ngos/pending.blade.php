@extends('layouts.app')

@section('title', 'Pending NGO Verifications')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Pending NGO Verifications</h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Organization</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Certificate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingNgos as $ngo)
                            <tr>
                                <td>
                                    <strong>{{ $ngo->organization_name }}</strong>
                                </td>
                                <td>{{ $ngo->name }}</td>
                                <td>{{ $ngo->email }}</td>
                                <td>{{ $ngo->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($ngo->certificate_path)
                                        <a href="{{ route('admin.ngos.certificate', $ngo) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>View
                                        </a>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-success" 
                                            onclick="document.getElementById('approveForm{{ $ngo->id }}').submit()">
                                            <i class="bi bi-check-circle me-1"></i>Approve
                                        </button>
                                        <button type="button" class="btn btn-danger" 
                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $ngo->id }}">
                                            <i class="bi bi-x-circle me-1"></i>Reject
                                        </button>
                                    </div>
                                    
                                    <form id="approveForm{{ $ngo->id }}" method="POST" 
                                        action="{{ route('admin.ngos.approve', $ngo) }}" class="d-none">
                                        @csrf
                                    </form>
                                    
                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $ngo->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.ngos.reject', $ngo) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject {{ $ngo->organization_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Reason *</label>
                                                            <textarea class="form-control" name="rejection_reason" rows="3" required 
                                                                placeholder="Explain why this NGO is being rejected..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Application</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle fs-3 mb-2"></i>
                                    <p class="mb-0">No pending NGO verifications</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $pendingNgos->links() }}
    </div>
</div>
@endsection
