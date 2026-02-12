@extends('layouts.admin')

@section('title', 'Account Verifications')

@section('page', 'ngos')

@section('content')
    <h1 class="page-title">Account Verifications</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Submitted</th>
                        <th>Attached File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ngos as $ngo)
                        <tr>
                            <td>
                                <a href="{{ route('admin.ngos.show', $ngo) }}"
                                    class="ngo-name text-decoration-none">{{ $ngo->organization_name ?? $ngo->name }}</a>
                            </td>
                            <td>{{ $ngo->email }}</td>
                            <td>{{ $ngo->created_at->format('M j, g:i A') }}</td>
                            <td>
                                @if ($ngo->certificate_path)
                                    <a href="{{ route('admin.ngos.certificate', $ngo) }}" class="file-link" target="_blank">
                                        {{ basename($ngo->certificate_path) }}
                                    </a>
                                @else
                                    <span class="text-muted">No file</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if ($ngo->verification_status === 'pending')
                                        <form method="POST" action="{{ route('admin.ngos.approve', $ngo) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-status btn-status-approve">
                                                <i class="bi bi-check-circle me-1"></i>Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn-status btn-status-reject-action"
                                            data-bs-toggle="modal" data-bs-target="#rejectNgoModal-{{ $ngo->id }}">
                                            <i class="bi bi-x-circle me-1"></i>Reject
                                        </button>
                                    @elseif ($ngo->verification_status === 'verified')
                                        <form method="POST" action="{{ route('admin.ngos.destroy', $ngo) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this NGO?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                        <span class="btn-status btn-status-verified">
                                            <i class="bi bi-check-circle-fill me-1"></i>Verified
                                        </span>
                                    @elseif ($ngo->verification_status === 'rejected')
                                        <form method="POST" action="{{ route('admin.ngos.destroy', $ngo) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this NGO?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                        <span class="btn-status btn-status-rejected">
                                            <i class="bi bi-x-circle-fill me-1"></i>Rejected
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <p>No NGO accounts found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($ngos->hasPages())
        <div class="mt-4">
            {{ $ngos->links() }}
        </div>
    @endif

    {{-- Reject NGO Modals --}}
    @foreach ($ngos as $ngo)
        @if ($ngo->verification_status === 'pending')
            <div class="modal fade" id="rejectNgoModal-{{ $ngo->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.ngos.reject', $ngo) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reject NGO</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to reject
                                    <strong>{{ $ngo->organization_name ?? $ngo->name }}</strong>?
                                </p>
                                <div class="mb-3">
                                    <label for="rejection_reason_{{ $ngo->id }}" class="form-label">Reason for
                                        Rejection <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="rejection_reason_{{ $ngo->id }}" name="rejection_reason" rows="3"
                                        placeholder="Provide a reason for rejecting this NGO..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle me-2"></i>Reject NGO
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('styles')
    <style>
        .ngo-name {
            font-weight: 500;
            color: var(--relief-dark-blue);
        }

        .file-link {
            color: var(--relief-dark-blue);
            text-decoration: none;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 100px;
        }

        .btn-status-pending {
            background-color: var(--relief-red);
            color: #ffffff;
        }

        .btn-status-pending:hover {
            background-color: var(--relief-vivid-red);
        }

        .btn-status-approve {
            background-color: #198754;
            color: #ffffff;
        }

        .btn-status-approve:hover {
            background-color: #157347;
        }

        .btn-status-reject-action {
            background-color: #dc3545;
            color: #ffffff;
        }

        .btn-status-reject-action:hover {
            background-color: #bb2d3b;
        }

        .btn-status-verified {
            background-color: #198754;
            color: #ffffff;
            cursor: default;
        }

        .btn-status-rejected {
            background-color: #6c757d;
            color: #ffffff;
            cursor: default;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            background-color: #ffffff;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: var(--relief-gray-blue);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }

        .empty-state p {
            margin: 0;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .content-card-body {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .admin-table {
                min-width: 580px;
            }

            .action-buttons {
                flex-wrap: nowrap;
                gap: 6px;
            }

            .btn-status {
                padding: 5px 10px;
                font-size: 12px;
                min-width: 80px;
            }
        }
    </style>
@endsection
