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
                                    <a href="javascript:void(0)" class="file-link" onclick="openLightbox('{{ asset('storage/' . $ngo->certificate_path) }}', '{{ basename($ngo->certificate_path) }}')">
                                        <i class="bi bi-eye me-1"></i>{{ basename($ngo->certificate_path) }}
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

        /* Lightbox Styles */
        .lightbox-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .lightbox-overlay.active {
            display: flex;
        }

        .lightbox-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 90vw;
            padding: 12px 16px;
            color: #ffffff;
        }

        .lightbox-filename {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
        }

        .lightbox-actions {
            display: flex;
            gap: 8px;
        }

        .lightbox-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #ffffff;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 18px;
            text-decoration: none;
        }

        .lightbox-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .lightbox-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 90vw;
            max-height: calc(100vh - 120px);
            padding: 0 16px 16px;
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .lightbox-content iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 8px;
            background: #ffffff;
        }

        .lightbox-content .unsupported-file {
            text-align: center;
            color: #ffffff;
            padding: 40px;
        }

        .lightbox-content .unsupported-file i {
            font-size: 64px;
            margin-bottom: 16px;
            display: block;
            opacity: 0.7;
        }

        .lightbox-content .unsupported-file p {
            font-size: 16px;
            margin-bottom: 16px;
        }

        .lightbox-content .unsupported-file a {
            color: #ffffff;
            text-decoration: underline;
        }
    </style>
@endsection

@section('scripts')
    {{-- Certificate Lightbox --}}
    <div class="lightbox-overlay" id="certificateLightbox">
        <div class="lightbox-header">
            <span class="lightbox-filename" id="lightboxFilename"></span>
            <div class="lightbox-actions">
                <a href="#" class="lightbox-btn" id="lightboxDownload" title="Download" target="_blank">
                    <i class="bi bi-download"></i>
                </a>
                <button class="lightbox-btn" onclick="closeLightbox()" title="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="lightbox-content" id="lightboxContent"></div>
    </div>

    <script>
        function openLightbox(fileUrl, filename) {
            const overlay = document.getElementById('certificateLightbox');
            const content = document.getElementById('lightboxContent');
            const filenameEl = document.getElementById('lightboxFilename');
            const downloadBtn = document.getElementById('lightboxDownload');

            filenameEl.textContent = filename;
            downloadBtn.href = fileUrl;

            const ext = filename.split('.').pop().toLowerCase();
            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
            const pdfExts = ['pdf'];

            if (imageExts.includes(ext)) {
                content.innerHTML = '<img src="' + fileUrl + '" alt="' + filename + '">';
            } else if (pdfExts.includes(ext)) {
                content.innerHTML = '<iframe src="' + fileUrl + '#toolbar=1" style="width:100%;height:100%;"></iframe>';
            } else {
                content.innerHTML = '<div class="unsupported-file">' +
                    '<i class="bi bi-file-earmark"></i>' +
                    '<p>Preview not available for this file type.</p>' +
                    '<a href="' + fileUrl + '" target="_blank">Download to view</a>' +
                    '</div>';
            }

            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const overlay = document.getElementById('certificateLightbox');
            overlay.classList.remove('active');
            document.getElementById('lightboxContent').innerHTML = '';
            document.body.style.overflow = '';
        }

        // Close on overlay click (not on content)
        document.getElementById('certificateLightbox').addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('lightbox-content')) {
                closeLightbox();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
@endsection
