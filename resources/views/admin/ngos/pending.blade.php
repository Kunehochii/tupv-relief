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
                                            <button type="submit" class="btn-status btn-status-pending">
                                                Pending
                                            </button>
                                        </form>
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
    </style>
@endsection
