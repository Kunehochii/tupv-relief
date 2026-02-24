@extends('layouts.app')

@section('title', 'Donation Receipt - ' . $receipt->user->name)

@section('content')
    <div class="container py-4" style="max-width: 900px;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('ngo.receipts.index') }}"
                        class="text-decoration-none">Donation Receipts</a></li>
                <li class="breadcrumb-item active">Receipt #{{ $receipt->id }}</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold mb-0">Receipt Details</h5>
                            <span class="badge bg-{{ $receipt->status_color }} fs-6">
                                {{ ucfirst($receipt->status) }}
                            </span>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label class="text-muted small">Donor Name</label>
                                <p class="fw-semibold mb-0">{{ $receipt->user->name }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $receipt->user->email }}"
                                        class="text-decoration-none">{{ $receipt->user->email }}</a>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label class="text-muted small">Contact Number</label>
                                <p class="mb-0">
                                    {{ $receipt->user->phone ?? $receipt->user->contact_numbers ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="text-muted small">Role</label>
                                <p class="mb-0">
                                    <span class="badge bg-info text-dark">{{ ucfirst($receipt->user->role) }}</span>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label class="text-muted small">Amount Donated</label>
                                <p class="fw-bold fs-5 mb-0 text-success">
                                    ₱{{ number_format($receipt->amount, 2) }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="text-muted small">Date Submitted</label>
                                <p class="mb-0">{{ $receipt->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if ($receipt->message)
                            <div class="mb-3">
                                <label class="text-muted small">Message</label>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0" style="white-space: pre-line;">{{ $receipt->message }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($receipt->rejection_reason)
                            <div class="mb-3">
                                <label class="text-muted small">Rejection Reason</label>
                                <div class="alert alert-danger mb-0">{{ $receipt->rejection_reason }}</div>
                            </div>
                        @endif

                        @if ($receipt->verified_at)
                            <div class="mb-0">
                                <label class="text-muted small">Verified At</label>
                                <p class="mb-0">{{ $receipt->verified_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    @if ($receipt->status === 'pending')
                        <div class="card-footer bg-transparent border-top p-3 d-flex gap-2 flex-wrap">
                            <form method="POST" action="{{ route('ngo.receipts.verify', $receipt) }}">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Verify this donation receipt?')">
                                    <i class="bi bi-check-circle me-1"></i>Verify Receipt
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectReceiptModal">
                                <i class="bi bi-x-circle me-1"></i>Reject
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Receipt Image Sidebar --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-image me-2 text-primary"></i>Receipt Image</h6>
                        <div class="receipt-image-wrapper" onclick="openLightbox('{{ asset('storage/' . $receipt->receipt_path) }}', 'Receipt #{{ $receipt->id }}')" role="button">
                            <img src="{{ asset('storage/' . $receipt->receipt_path) }}"
                                alt="Donation Receipt" class="img-fluid rounded border w-100">
                            <div class="receipt-image-overlay">
                                <i class="bi bi-zoom-in fs-3"></i>
                                <span class="small mt-1">Click to enlarge</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Receipt Modal --}}
    @if ($receipt->status === 'pending')
        <div class="modal fade" id="rejectReceiptModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 12px;">
                    <form method="POST" action="{{ route('ngo.receipts.reject', $receipt) }}">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Reject Receipt</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject this donation receipt from
                                <strong>{{ $receipt->user->name }}</strong> for
                                <strong>₱{{ number_format($receipt->amount, 2) }}</strong>?
                            </p>
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label fw-semibold small">
                                    Reason for Rejection <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                    placeholder="Provide a reason for rejecting this receipt..." required
                                    maxlength="1000"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i>Reject Receipt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Lightbox --}}
    <div class="lightbox-overlay" id="receiptLightbox">
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
@endsection

@section('styles')
    <style>
        .receipt-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .receipt-image-wrapper:hover {
            transform: scale(1.02);
        }

        .receipt-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.35);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            color: #fff;
        }

        .receipt-image-wrapper:hover .receipt-image-overlay {
            opacity: 1;
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
    </style>
@endsection

@section('scripts')
    <script>
        function openLightbox(fileUrl, filename) {
            const overlay = document.getElementById('receiptLightbox');
            const content = document.getElementById('lightboxContent');
            const filenameEl = document.getElementById('lightboxFilename');
            const downloadBtn = document.getElementById('lightboxDownload');

            filenameEl.textContent = filename;
            downloadBtn.href = fileUrl;
            content.innerHTML = '<img src="' + fileUrl + '" alt="' + filename + '">';

            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const overlay = document.getElementById('receiptLightbox');
            overlay.classList.remove('active');
            document.getElementById('lightboxContent').innerHTML = '';
            document.body.style.overflow = '';
        }

        document.getElementById('receiptLightbox').addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('lightbox-content')) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
@endsection
