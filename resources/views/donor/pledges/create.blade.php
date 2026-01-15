@extends('layouts.app')

@section('title', 'Make a Pledge')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Make a Donation Pledge</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route(auth()->user()->role . '.pledges.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="drive_id" class="form-label">Select Drive *</label>
                            <select class="form-select @error('drive_id') is-invalid @enderror" 
                                id="drive_id" name="drive_id" required>
                                <option value="">Choose a drive...</option>
                                @foreach($drives as $drive)
                                    <option value="{{ $drive->id }}" {{ ($selectedDrive && $selectedDrive->id == $drive->id) || old('drive_id') == $drive->id ? 'selected' : '' }}>
                                        {{ $drive->name }} (Ends: {{ $drive->end_date->format('M d') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('drive_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Items to Donate *</label>
                            <div id="itemsContainer">
                                @if(old('items'))
                                    @foreach(old('items') as $item)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="items[]" value="{{ $item }}" placeholder="e.g., Rice 5kg" required>
                                            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="items[]" placeholder="e.g., Rice 5kg" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="addItem()">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @error('items')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Total Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="details" class="form-label">Item Details</label>
                            <textarea class="form-control @error('details') is-invalid @enderror" 
                                id="details" name="details" rows="2" placeholder="Describe your items (brand, size, condition, etc.)">{{ old('details') }}</textarea>
                            @error('details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number *</label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                id="contact_number" name="contact_number" value="{{ old('contact_number', auth()->user()->phone) }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                placeholder="Any special instructions or notes...">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            After submitting, you'll receive a reference number. Please bring your items to the designated location within 24 hours for verification.
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Submit Pledge
                            </button>
                            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function addItem() {
        const container = document.getElementById('itemsContainer');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="items[]" placeholder="e.g., Canned goods" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }
</script>
@endsection
@endsection
