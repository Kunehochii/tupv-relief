@extends('layouts.app')

@section('title', 'Make a Pledge')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Make a Donation Pledge</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route(auth()->user()->role . '.pledges.store') }}" id="pledgeForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="drive_id" class="form-label">Select Drive *</label>
                            <select class="form-select @error('drive_id') is-invalid @enderror" 
                                id="drive_id" name="drive_id" required onchange="loadDriveItems()">
                                <option value="">Choose a drive...</option>
                                @foreach($drives as $drive)
                                    <option value="{{ $drive->id }}" 
                                            data-items='@json($drive->driveItems)'
                                            {{ ($selectedDrive && $selectedDrive->id == $drive->id) || old('drive_id') == $drive->id ? 'selected' : '' }}>
                                        {{ $drive->name }} (Ends: {{ $drive->end_date->format('M d') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('drive_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Pledge Type Selection --}}
                        <div class="mb-4">
                            <label class="form-label">Pledge Type *</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="pledge_type" id="type_inkind" value="in-kind" 
                                       {{ old('pledge_type', 'in-kind') == 'in-kind' ? 'checked' : '' }} onchange="togglePledgeType()">
                                <label class="btn btn-outline-primary" for="type_inkind">
                                    <i class="bi bi-box-seam me-1"></i>In-Kind Donation
                                </label>
                                
                                <input type="radio" class="btn-check" name="pledge_type" id="type_financial" value="financial"
                                       {{ old('pledge_type') == 'financial' ? 'checked' : '' }} onchange="togglePledgeType()">
                                <label class="btn btn-outline-primary" for="type_financial">
                                    <i class="bi bi-cash me-1"></i>Financial Donation
                                </label>
                            </div>
                        </div>
                        
                        {{-- Financial Amount (shown when financial is selected) --}}
                        <div class="mb-4" id="financial_section" style="display: none;">
                            <label for="financial_amount" class="form-label">Donation Amount (₱) *</label>
                            <input type="number" class="form-control @error('financial_amount') is-invalid @enderror" 
                                   id="financial_amount" name="financial_amount" value="{{ old('financial_amount') }}" 
                                   min="0" step="0.01" placeholder="Enter amount">
                            @error('financial_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Money pledges are manually coordinated with an NGO partner.</small>
                        </div>
                        
                        {{-- Items Selection (shown when in-kind is selected) --}}
                        <div id="items_section">
                            <div class="mb-4">
                                <label class="form-label">Select Items to Pledge *</label>
                                <p class="text-muted small">Check items you want to donate and enter quantities.</p>
                                
                                <div id="driveItemsContainer">
                                    @if($selectedDrive && $selectedDrive->driveItems->count() > 0)
                                        @foreach($selectedDrive->driveItems as $item)
                                            <div class="card mb-2">
                                                <div class="card-body py-2">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6">
                                                            <strong>{{ $item->item_name }}</strong>
                                                            <div class="progress mt-1" style="height: 6px;">
                                                                <div class="progress-bar bg-success" style="width: {{ $item->distributed_percentage }}%"></div>
                                                                <div class="progress-bar bg-primary" style="width: {{ max(0, $item->pledged_percentage - $item->distributed_percentage) }}%"></div>
                                                            </div>
                                                            <small class="text-muted">{{ $item->pledged_percentage }}% pledged</small>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" class="form-control form-control-sm" 
                                                                   name="items[{{ $loop->index }}][quantity]" 
                                                                   placeholder="Qty" min="0" step="0.01">
                                                            <input type="hidden" name="items[{{ $loop->index }}][drive_item_id]" value="{{ $item->id }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <span class="text-muted">{{ $item->unit }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-info" id="noItemsAlert">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Select a drive to see available items to pledge.
                                        </div>
                                    @endif
                                </div>
                                @error('items')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="details" class="form-label">Additional Details</label>
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
                            After submitting, you'll receive a reference number. Please coordinate with DSWD for verification within 24 hours.
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
    function togglePledgeType() {
        const isFinancial = document.getElementById('type_financial').checked;
        document.getElementById('financial_section').style.display = isFinancial ? 'block' : 'none';
        document.getElementById('items_section').style.display = isFinancial ? 'none' : 'block';
    }
    
    function loadDriveItems() {
        const select = document.getElementById('drive_id');
        const container = document.getElementById('driveItemsContainer');
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption.value) {
            container.innerHTML = `
                <div class="alert alert-info" id="noItemsAlert">
                    <i class="bi bi-info-circle me-2"></i>
                    Select a drive to see available items to pledge.
                </div>
            `;
            return;
        }
        
        try {
            const items = JSON.parse(selectedOption.dataset.items || '[]');
            
            if (items.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This drive has no specific items defined. You can describe your donation in the details field.
                    </div>
                `;
                return;
            }
            
            let html = '';
            items.forEach((item, index) => {
                const pledgedPct = item.quantity_needed > 0 ? Math.min(100, (item.quantity_pledged / item.quantity_needed) * 100) : 0;
                const distributedPct = item.quantity_needed > 0 ? Math.min(100, (item.quantity_distributed / item.quantity_needed) * 100) : 0;
                
                html += `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <strong>${item.item_name}</strong>
                                    <div class="progress mt-1" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: ${distributedPct}%"></div>
                                        <div class="progress-bar bg-primary" style="width: ${Math.max(0, pledgedPct - distributedPct)}%"></div>
                                    </div>
                                    <small class="text-muted">${pledgedPct.toFixed(1)}% pledged</small>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="items[${index}][quantity]" 
                                           placeholder="Qty" min="0" step="0.01">
                                    <input type="hidden" name="items[${index}][drive_item_id]" value="${item.id}">
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted">${item.unit}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        } catch (e) {
            console.error('Error parsing items:', e);
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        togglePledgeType();
        if (document.getElementById('drive_id').value) {
            loadDriveItems();
        }
    });
</script>
@endsection
@endsection
