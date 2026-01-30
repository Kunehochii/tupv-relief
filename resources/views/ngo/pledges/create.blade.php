@extends('layouts.app')

@section('title', 'Make a Pledge')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Make a Donation Pledge</h5>
                    <small class="text-muted">Pledging as {{ auth()->user()->organization_name }}</small>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route('ngo.pledges.store') }}" id="pledgeForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="drive_id" class="form-label">Select Drive *</label>
                            <select class="form-select @error('drive_id') is-invalid @enderror" 
                                id="drive_id" name="drive_id" required>
                                <option value="">Choose a drive...</option>
                                @foreach($drives as $drive)
                                    <option value="{{ $drive->id }}" 
                                        {{ ($selectedDrive && $selectedDrive->id == $drive->id) || old('drive_id') == $drive->id ? 'selected' : '' }}
                                        data-items='@json($drive->driveItems->map(fn($i) => ["id" => $i->id, "item_name" => $i->item_name, "unit" => $i->unit, "remaining" => $i->remaining_quantity]))'>
                                        {{ $drive->name }} (Ends: {{ $drive->end_date->format('M d') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('drive_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Pledge Type Toggle --}}
                        <div class="mb-3">
                            <label class="form-label">Pledge Type *</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="pledge_type" id="type_inkind" value="in-kind" 
                                    {{ old('pledge_type', 'in-kind') === 'in-kind' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="type_inkind">
                                    <i class="bi bi-box-seam me-2"></i>In-Kind Donation
                                </label>
                                
                                <input type="radio" class="btn-check" name="pledge_type" id="type_financial" value="financial"
                                    {{ old('pledge_type') === 'financial' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="type_financial">
                                    <i class="bi bi-cash me-2"></i>Financial Donation
                                </label>
                            </div>
                        </div>
                        
                        {{-- Financial Amount (shown when financial is selected) --}}
                        <div class="mb-3" id="financialSection" style="display: none;">
                            <label for="financial_amount" class="form-label">Amount (PHP) *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control @error('financial_amount') is-invalid @enderror" 
                                    id="financial_amount" name="financial_amount" value="{{ old('financial_amount') }}" 
                                    min="1" step="0.01" placeholder="0.00">
                            </div>
                            @error('financial_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Drive Items Selection (shown when in-kind is selected) --}}
                        <div id="inkindSection">
                            <div class="mb-3">
                                <label class="form-label">Items Needed by Drive</label>
                                <div id="driveItemsContainer">
                                    <div class="alert alert-secondary">
                                        <i class="bi bi-info-circle me-2"></i>Select a drive to see items needed.
                                    </div>
                                </div>
                            </div>
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
                            <a href="{{ route('ngo.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const driveSelect = document.getElementById('drive_id');
        const itemsContainer = document.getElementById('driveItemsContainer');
        const pledgeTypeRadios = document.querySelectorAll('input[name="pledge_type"]');
        const financialSection = document.getElementById('financialSection');
        const inkindSection = document.getElementById('inkindSection');
        
        // Toggle pledge type sections
        function togglePledgeType() {
            const selectedType = document.querySelector('input[name="pledge_type"]:checked').value;
            if (selectedType === 'financial') {
                financialSection.style.display = 'block';
                inkindSection.style.display = 'none';
                document.getElementById('financial_amount').required = true;
            } else {
                financialSection.style.display = 'none';
                inkindSection.style.display = 'block';
                document.getElementById('financial_amount').required = false;
            }
        }
        
        pledgeTypeRadios.forEach(radio => {
            radio.addEventListener('change', togglePledgeType);
        });
        
        // Load drive items when drive is selected
        function loadDriveItems() {
            const selected = driveSelect.options[driveSelect.selectedIndex];
            if (!selected.value) {
                itemsContainer.innerHTML = `
                    <div class="alert alert-secondary">
                        <i class="bi bi-info-circle me-2"></i>Select a drive to see items needed.
                    </div>
                `;
                return;
            }
            
            const items = JSON.parse(selected.dataset.items || '[]');
            
            if (items.length === 0) {
                itemsContainer.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>This drive has no specific items defined. Please contact the administrator.
                    </div>
                `;
                return;
            }
            
            let html = '<div class="list-group">';
            items.forEach((item, index) => {
                html += `
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <strong>${item.item_name}</strong>
                                <div class="small text-muted">Still needed: ${parseFloat(item.remaining).toFixed(2)} ${item.unit}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" 
                                        name="items[${index}][quantity]" 
                                        placeholder="0" min="0" step="0.01"
                                        max="${item.remaining}">
                                    <span class="input-group-text">${item.unit}</span>
                                </div>
                                <input type="hidden" name="items[${index}][drive_item_id]" value="${item.id}">
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            itemsContainer.innerHTML = html;
        }
        
        driveSelect.addEventListener('change', loadDriveItems);
        
        // Initialize
        togglePledgeType();
        loadDriveItems();
    });
</script>
@endsection
@endsection
