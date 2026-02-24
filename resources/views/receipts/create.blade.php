@extends('layouts.app')

@section('title', 'Upload Donation Receipt - ' . $ngo->display_name)

@section('content')
    <div class="container py-4" style="max-width: 640px;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('ngo.profile.public', $ngo->id) }}"
                        class="text-decoration-none">{{ $ngo->display_name }}</a></li>
                <li class="breadcrumb-item active">Upload Receipt</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    @if ($ngo->logo)
                        <img src="{{ $ngo->logo }}" alt="{{ $ngo->display_name }}"
                            class="rounded-circle border mb-2" width="64" height="64"
                            style="object-fit: cover;">
                    @else
                        <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 64px; height: 64px; background: var(--dark-blue);">
                            <i class="bi bi-building text-white fs-4"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold mb-1">Upload Donation Receipt</h5>
                    <p class="text-muted small mb-0">Submitting to <strong>{{ $ngo->display_name }}</strong></p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('receipt.store', $ngo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- User Info (auto-filled, read-only) --}}
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Your Name</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" class="form-control bg-light" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Contact Number</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ auth()->user()->phone ?? auth()->user()->contact_numbers ?? 'N/A' }}" readonly>
                        </div>
                    </div>

                    <hr>

                    {{-- Amount --}}
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold small">Amount Donated (₱) <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount"
                                name="amount" value="{{ old('amount') }}" min="1" step="0.01"
                                placeholder="0.00" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold small">Message (optional)</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message"
                            rows="3" placeholder="Add a message or note about your donation..."
                            maxlength="1000">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Receipt Image Upload --}}
                    <div class="mb-4">
                        <label for="receipt_image" class="form-label fw-semibold small">Receipt Image <span
                                class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('receipt_image') is-invalid @enderror"
                            id="receipt_image" name="receipt_image" accept="image/*" required>
                        <div class="form-text">Upload a screenshot or photo of your donation receipt (max 5MB, JPG/PNG/GIF/WebP).</div>
                        @error('receipt_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Image Preview --}}
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="" alt="Preview"
                                class="img-fluid rounded border" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-upload me-1"></i>Submit Receipt
                        </button>
                        <a href="{{ route('ngo.profile.public', $ngo->id) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('receipt_image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    previewImg.src = ev.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
@endsection
