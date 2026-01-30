@extends('layouts.app')

@section('title', 'Make a Pledge' . ($selectedDrive ? ' - ' . $selectedDrive->name : ''))

@section('styles')
    <style>
        :root {
            --dark-blue: #000167;
            --red: #dd3319;
            --vivid-red: #e51d00;
            --orange: #ffae44;
            --gray-blue: #8a95b6;
            --gray: #e6e6e4;
            --vivid-orange: #ea4f2d;
        }

        .pledge-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Drive Selection Section */
        .drive-select-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .drive-select-section label {
            color: var(--dark-blue);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .drive-select-section select {
            border: 2px solid var(--gray);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .drive-select-section select:focus {
            border-color: var(--vivid-orange);
            box-shadow: 0 0 0 3px rgba(234, 79, 45, 0.15);
        }

        /* Main Content Grid */
        .pledge-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 991px) {
            .pledge-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Map Section */
        .map-section {
            position: sticky;
            top: 100px;
        }

        .map-container {
            background: #e8f4f8;
            border-radius: 16px;
            overflow: hidden;
            height: 400px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .no-map-placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--gray-blue);
        }

        .no-map-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Drive Info Section */
        .drive-info-section {
            background: white;
        }

        .drive-cover {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .drive-cover-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--gray) 0%, #d0d0d0 100%);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-blue);
        }

        .drive-title {
            color: var(--dark-blue);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .drive-details {
            color: var(--gray-blue);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .drive-details p {
            margin-bottom: 0.25rem;
        }

        .drive-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .drive-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-blue);
            font-size: 0.9rem;
        }

        .drive-meta-item i {
            color: var(--vivid-orange);
        }

        /* Items Progress Section */
        .items-section-title {
            color: var(--vivid-red);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--gray);
        }

        .pack-type-group {
            margin-bottom: 1.5rem;
        }

        .pack-type-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .pack-type-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-blue);
            color: white;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .pack-type-name {
            font-weight: 600;
            color: var(--dark-blue);
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Item Row */
        .item-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f4ff 0%, #d0e8ff 100%);
            border-radius: 6px;
            flex-shrink: 0;
        }

        .item-icon i {
            color: var(--dark-blue);
            font-size: 0.9rem;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-weight: 500;
            color: #333;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .item-progress-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.25rem;
        }

        .item-progress-bar {
            flex: 1;
            height: 8px;
            background: var(--gray);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .item-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--vivid-red) 0%, var(--vivid-orange) 100%);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .item-progress-percent {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--dark-blue);
            min-width: 45px;
            text-align: right;
        }

        .item-quantity-input {
            width: 80px;
            flex-shrink: 0;
        }

        .item-quantity-input input {
            width: 100%;
            padding: 0.5rem;
            border: 2px solid var(--gray);
            border-radius: 6px;
            text-align: center;
            font-weight: 500;
        }

        .item-quantity-input input:focus {
            border-color: var(--vivid-orange);
            outline: none;
            box-shadow: 0 0 0 3px rgba(234, 79, 45, 0.15);
        }

        .item-unit {
            font-size: 0.85rem;
            color: var(--gray-blue);
            min-width: 60px;
        }

        /* Financial Section */
        .financial-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .financial-section label {
            color: var(--dark-blue);
            font-weight: 600;
        }

        .amount-input {
            font-size: 1.25rem;
            padding: 1rem;
            border: 2px solid var(--gray);
            border-radius: 8px;
        }

        .amount-input:focus {
            border-color: var(--vivid-orange);
            box-shadow: 0 0 0 3px rgba(234, 79, 45, 0.15);
        }

        /* Pledge Type Toggle */
        .pledge-type-toggle {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .pledge-type-btn {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--gray);
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .pledge-type-btn.active {
            border-color: var(--dark-blue);
            background: var(--dark-blue);
            color: white;
        }

        .pledge-type-btn:hover:not(.active) {
            border-color: var(--gray-blue);
        }

        .pledge-type-btn i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .pledge-type-btn span {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Contact Section */
        .contact-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .contact-section label {
            color: var(--dark-blue);
            font-weight: 600;
        }

        .contact-section input,
        .contact-section textarea {
            border: 2px solid var(--gray);
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .contact-section input:focus,
        .contact-section textarea:focus {
            border-color: var(--vivid-orange);
            box-shadow: 0 0 0 3px rgba(234, 79, 45, 0.15);
        }

        /* Submit Button */
        .submit-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--gray);
        }

        .btn-pledge {
            background: var(--dark-blue);
            color: white;
            border: none;
            padding: 1.25rem 3rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-pledge:hover {
            background: #000050;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 1, 103, 0.3);
        }

        .btn-cancel {
            background: transparent;
            border: 2px solid var(--gray-blue);
            color: var(--gray-blue);
            padding: 1.25rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: var(--gray-blue);
            color: white;
        }

        /* Alert styling */
        .info-alert {
            background: linear-gradient(135deg, #e8f4ff 0%, #d0e8ff 100%);
            border: none;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            color: var(--dark-blue);
        }

        .info-alert i {
            color: var(--vivid-orange);
        }

        /* No Drive Selected State */
        .no-drive-selected {
            text-align: center;
            padding: 3rem;
            color: var(--gray-blue);
        }

        .no-drive-selected i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-drive-selected h4 {
            margin-bottom: 0.5rem;
            color: var(--dark-blue);
        }

        /* No Items State */
        .no-items-message {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
            color: var(--gray-blue);
        }

        .no-items-message i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Custom marker styles */
        .custom-marker {
            background: transparent;
            border: none;
        }

        /* NGO Badge */
        .ngo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--dark-blue) 0%, #000050 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .ngo-badge i {
            color: var(--orange);
        }
    </style>
@endsection

@section('content')
    <div class="pledge-container">
        {{-- NGO Badge --}}
        <div class="ngo-badge">
            <i class="bi bi-building"></i>
            Pledging as {{ auth()->user()->organization_name }}
        </div>

        @if (session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-danger mb-4" id="validationAlert" style="display: none;">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2" id="validationErrors"></ul>
        </div>

        <form method="POST" action="{{ route('ngo.pledges.store') }}" id="pledgeForm" novalidate>
            @csrf

            {{-- Drive Selection --}}
            <div class="drive-select-section">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label for="drive_id">Select a Relief Drive</label>
                        <select class="form-select @error('drive_id') is-invalid @enderror" id="drive_id" name="drive_id"
                            required onchange="loadDriveDetails()">
                            <option value="">Choose a drive to support...</option>
                            @foreach ($drives as $drive)
                                <option value="{{ $drive->id }}" data-name="{{ $drive->name }}"
                                    data-description="{{ $drive->description }}" data-cover="{{ $drive->cover_photo_url }}"
                                    data-address="{{ $drive->address }}" data-lat="{{ $drive->latitude }}"
                                    data-lng="{{ $drive->longitude }}" data-end="{{ $drive->end_date->format('M d, Y') }}"
                                    data-families="{{ $drive->families_affected }}"
                                    data-items='@json($drive->driveItems->groupBy('pack_type'))'
                                    {{ ($selectedDrive && $selectedDrive->id == $drive->id) || old('drive_id') == $drive->id ? 'selected' : '' }}>
                                    {{ $drive->name }} — Ends {{ $drive->end_date->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('drive_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="text-muted">
                            <i class="bi bi-heart-fill text-danger me-1"></i>
                            {{ $drives->count() }} active {{ Str::plural('drive', $drives->count()) }} available
                        </span>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div id="driveContent">
                @if ($selectedDrive)
                    <div class="pledge-grid">
                        <!-- Left: Map -->
                        <div class="map-section">
                            <div class="map-container">
                                @if ($selectedDrive->latitude && $selectedDrive->longitude)
                                    <div id="map"></div>
                                @else
                                    <div class="no-map-placeholder">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Location not specified</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Drive Info & Pledge Form -->
                        <div class="drive-info-section">
                            @if ($selectedDrive->cover_photo_url)
                                <img src="{{ $selectedDrive->cover_photo_url }}" alt="{{ $selectedDrive->name }}"
                                    class="drive-cover">
                            @else
                                <div class="drive-cover-placeholder">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <h2 class="drive-title">{{ $selectedDrive->name }}</h2>

                            <div class="drive-meta">
                                @if ($selectedDrive->families_affected)
                                    <div class="drive-meta-item">
                                        <i class="bi bi-people-fill"></i>
                                        {{ $selectedDrive->families_affected }} families affected
                                    </div>
                                @endif
                                <div class="drive-meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    Ends {{ $selectedDrive->end_date->format('M d, Y') }}
                                </div>
                                @if ($selectedDrive->address)
                                    <div class="drive-meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        {{ $selectedDrive->address }}
                                    </div>
                                @endif
                            </div>

                            <div class="drive-details">
                                <p>{{ $selectedDrive->description ?: 'Help us support those in need by pledging donations to this relief drive.' }}
                                </p>
                            </div>

                            <!-- Pledge Type Toggle -->
                            <div class="pledge-type-toggle">
                                <label
                                    class="pledge-type-btn {{ old('pledge_type', 'in-kind') == 'in-kind' ? 'active' : '' }}"
                                    id="btn_inkind" onclick="selectPledgeType('in-kind')">
                                    <i class="bi bi-box-seam-fill"></i>
                                    <span>In-Kind Donation</span>
                                </label>
                                <label class="pledge-type-btn {{ old('pledge_type') == 'financial' ? 'active' : '' }}"
                                    id="btn_financial" onclick="selectPledgeType('financial')">
                                    <i class="bi bi-cash-stack"></i>
                                    <span>Financial Donation</span>
                                </label>
                            </div>
                            <input type="hidden" name="pledge_type" id="pledge_type"
                                value="{{ old('pledge_type', 'in-kind') }}">

                            <!-- Financial Section -->
                            <div class="financial-section" id="financial_section"
                                style="display: {{ old('pledge_type') == 'financial' ? 'block' : 'none' }};">
                                <label for="financial_amount" class="form-label mb-2">Donation Amount (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number"
                                        class="form-control amount-input @error('financial_amount') is-invalid @enderror"
                                        id="financial_amount" name="financial_amount" value="{{ old('financial_amount') }}"
                                        min="0" step="0.01" placeholder="Enter amount">
                                    @error('financial_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Financial pledges are coordinated with DSWD partners.
                                </small>
                            </div>

                            <!-- Items Section -->
                            <div id="items_section"
                                style="display: {{ old('pledge_type') == 'financial' ? 'none' : 'block' }};">
                                <h4 class="items-section-title">
                                    <i class="bi bi-box-seam me-2"></i>Items Needed
                                </h4>

                                @php
                                    $groupedItems = $selectedDrive->driveItems->groupBy('pack_type');
                                    $packTypeIcons = [
                                        'food' => 'bi-basket2-fill',
                                        'kitchen' => 'bi-cup-hot-fill',
                                        'hygiene' => 'bi-droplet-fill',
                                        'sleeping' => 'bi-moon-stars-fill',
                                        'clothing' => 'bi-person-standing-dress',
                                    ];
                                    $packTypeNames = [
                                        'food' => 'Food Pack',
                                        'kitchen' => 'Kitchen Pack',
                                        'hygiene' => 'Hygiene Pack',
                                        'sleeping' => 'Sleeping Pack',
                                        'clothing' => 'Clothing Pack',
                                    ];
                                    $itemIndex = 0;
                                @endphp

                                @if ($groupedItems->count() > 0)
                                    @foreach ($groupedItems as $packType => $items)
                                        <div class="pack-type-group">
                                            <div class="pack-type-header">
                                                <div class="pack-type-icon">
                                                    <i class="bi {{ $packTypeIcons[$packType] ?? 'bi-box' }}"></i>
                                                </div>
                                                <span
                                                    class="pack-type-name">{{ $packTypeNames[$packType] ?? strtoupper($packType) }}</span>
                                            </div>
                                            <div class="pack-type-items">
                                                @foreach ($items as $item)
                                                    @php
                                                        $pledgedPct =
                                                            $item->quantity_needed > 0
                                                                ? min(
                                                                    100,
                                                                    ($item->quantity_pledged / $item->quantity_needed) *
                                                                        100,
                                                                )
                                                                : 0;
                                                    @endphp
                                                    <div class="item-row">
                                                        <div class="item-icon">
                                                            <i class="bi bi-check2-square"></i>
                                                        </div>
                                                        <div class="item-details">
                                                            <div class="item-name">{{ $item->item_name }}</div>
                                                            <div class="item-progress-container">
                                                                <div class="item-progress-bar">
                                                                    <div class="item-progress-fill"
                                                                        style="width: {{ $pledgedPct }}%"></div>
                                                                </div>
                                                                <span
                                                                    class="item-progress-percent">{{ number_format($pledgedPct, 0) }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="item-quantity-input">
                                                            <input type="number"
                                                                name="items[{{ $itemIndex }}][quantity]"
                                                                placeholder="Qty" min="0" step="0.01"
                                                                value="{{ old('items.' . $itemIndex . '.quantity') }}"
                                                                aria-label="Quantity for {{ $item->item_name }}">
                                                            <input type="hidden"
                                                                name="items[{{ $itemIndex }}][drive_item_id]"
                                                                value="{{ $item->id }}">
                                                        </div>
                                                        <div class="item-unit">{{ $item->unit }}</div>
                                                    </div>
                                                    @php $itemIndex++; @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-items-message">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-0">No specific items listed for this drive.<br>
                                            You can describe your donation in the details field below.</p>
                                    </div>
                                @endif
                                @error('items')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Section -->
                            <div class="contact-section">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number *</label>
                                    <input type="text"
                                        class="form-control @error('contact_number') is-invalid @enderror"
                                        id="contact_number" name="contact_number"
                                        value="{{ old('contact_number', auth()->user()->phone) }}"
                                        placeholder="09XX XXX XXXX" required>
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="details" class="form-label">Additional Details</label>
                                    <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="2"
                                        placeholder="Describe your items (brand, size, condition, etc.)">{{ old('details') }}</textarea>
                                    @error('details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="notes" class="form-label">Special Instructions</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2"
                                        placeholder="Pickup availability, delivery preferences, etc.">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <!-- Info Alert -->
                            <div class="info-alert mt-4">
                                <i class="bi bi-info-circle me-2"></i>
                                After submitting, you'll receive a reference number. Please bring your items to the
                                designated location within 24 hours for verification.
                            </div>

                            <!-- Submit Section -->
                            <div class="submit-section">
                                <div class="d-flex gap-3 flex-column flex-md-row">
                                    <button type="submit" class="btn-pledge">
                                        <i class="bi bi-heart-fill me-2"></i>Submit Pledge
                                    </button>
                                    <a href="{{ route('ngo.dashboard') }}" class="btn-cancel">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="no-drive-selected">
                        <i class="bi bi-hand-index-thumb"></i>
                        <h4>Select a Relief Drive</h4>
                        <p>Choose a drive from the dropdown above to see what items are needed and make your pledge.</p>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const packTypeIcons = {
            'food': 'bi-basket2-fill',
            'kitchen': 'bi-cup-hot-fill',
            'hygiene': 'bi-droplet-fill',
            'sleeping': 'bi-moon-stars-fill',
            'clothing': 'bi-person-standing-dress'
        };

        const packTypeNames = {
            'food': 'Food Pack',
            'kitchen': 'Kitchen Pack',
            'hygiene': 'Hygiene Pack',
            'sleeping': 'Sleeping Pack',
            'clothing': 'Clothing Pack'
        };

        let currentMap = null;
        let currentMarker = null;

        function loadDriveDetails() {
            const select = document.getElementById('drive_id');
            const container = document.getElementById('driveContent');
            const option = select.options[select.selectedIndex];

            if (!option.value) {
                container.innerHTML = `
                <div class="no-drive-selected">
                    <i class="bi bi-hand-index-thumb"></i>
                    <h4>Select a Relief Drive</h4>
                    <p>Choose a drive from the dropdown above to see what items are needed and make your pledge.</p>
                </div>
            `;
                return;
            }

            const name = option.dataset.name;
            const description = option.dataset.description;
            const cover = option.dataset.cover;
            const address = option.dataset.address;
            const lat = parseFloat(option.dataset.lat);
            const lng = parseFloat(option.dataset.lng);
            const endDate = option.dataset.end;
            const families = option.dataset.families;
            const itemsGrouped = JSON.parse(option.dataset.items || '{}');

            container.innerHTML = buildPledgeFormContent(name, description, cover, address, lat, lng, endDate, families,
                itemsGrouped);

            // Initialize map if coordinates exist
            if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                initMap(lat, lng, name, address);
            }

            // Initialize pledge type toggle
            togglePledgeType();
        }

        function buildPledgeFormContent(name, description, cover, address, lat, lng, endDate, families, itemsGrouped) {
            let itemsHtml = buildItemsHtml(itemsGrouped);

            const mapHtml = (lat && lng && !isNaN(lat) && !isNaN(lng)) ?
                `<div id="map"></div>` :
                `<div class="no-map-placeholder">
                    <i class="bi bi-geo-alt"></i>
                    <span>Location not specified</span>
               </div>`;

            const coverHtml = cover ?
                `<img src="${cover}" alt="${name}" class="drive-cover">` :
                `<div class="drive-cover-placeholder">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
               </div>`;

            return `
            <div class="pledge-grid">
                <!-- Left: Map -->
                <div class="map-section">
                    <div class="map-container">
                        ${mapHtml}
                    </div>
                </div>

                <!-- Right: Drive Info & Pledge Form -->
                <div class="drive-info-section">
                    ${coverHtml}
                    
                    <h2 class="drive-title">${name}</h2>
                    
                    <div class="drive-meta">
                        ${families ? `<div class="drive-meta-item"><i class="bi bi-people-fill"></i>${families} families affected</div>` : ''}
                        <div class="drive-meta-item"><i class="bi bi-calendar-event"></i>Ends ${endDate}</div>
                        ${address ? `<div class="drive-meta-item"><i class="bi bi-geo-alt-fill"></i>${address}</div>` : ''}
                    </div>
                    
                    <div class="drive-details">
                        <p>${description || 'Help us support those in need by pledging donations to this relief drive.'}</p>
                    </div>

                    <!-- Pledge Type Toggle -->
                    <div class="pledge-type-toggle">
                        <label class="pledge-type-btn active" id="btn_inkind" onclick="selectPledgeType('in-kind')">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>In-Kind Donation</span>
                        </label>
                        <label class="pledge-type-btn" id="btn_financial" onclick="selectPledgeType('financial')">
                            <i class="bi bi-cash-stack"></i>
                            <span>Financial Donation</span>
                        </label>
                    </div>
                    <input type="hidden" name="pledge_type" id="pledge_type" value="in-kind">

                    <!-- Financial Section -->
                    <div class="financial-section" id="financial_section" style="display: none;">
                        <label for="financial_amount" class="form-label mb-2">Donation Amount (₱)</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control amount-input" 
                                   id="financial_amount" name="financial_amount" 
                                   min="0" step="0.01" placeholder="Enter amount">
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Financial pledges are coordinated with DSWD partners.
                        </small>
                    </div>

                    <!-- Items Section -->
                    <div id="items_section">
                        <h4 class="items-section-title">
                            <i class="bi bi-box-seam me-2"></i>Items Needed
                        </h4>
                        ${itemsHtml}
                    </div>

                    <!-- Contact Section -->
                    <div class="contact-section">
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number *</label>
                            <input type="text" class="form-control" 
                                id="contact_number" name="contact_number" 
                                value="{{ old('contact_number', auth()->user()->phone ?? '') }}" 
                                placeholder="09XX XXX XXXX" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="details" class="form-label">Additional Details</label>
                            <textarea class="form-control" id="details" name="details" rows="2" 
                                placeholder="Describe your items (brand, size, condition, etc.)">{{ old('details') }}</textarea>
                        </div>
                        
                        <div class="mb-0">
                            <label for="notes" class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                placeholder="Pickup availability, delivery preferences, etc.">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="info-alert mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        After submitting, you'll receive a reference number. Please bring your items to the designated location within 24 hours for verification.
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <div class="d-flex gap-3 flex-column flex-md-row">
                            <button type="submit" class="btn-pledge">
                                <i class="bi bi-heart-fill me-2"></i>Submit Pledge
                            </button>
                            <a href="{{ route('ngo.dashboard') }}" class="btn-cancel">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        }

        function buildItemsHtml(itemsGrouped) {
            if (!itemsGrouped || Object.keys(itemsGrouped).length === 0) {
                return `
                <div class="no-items-message">
                    <i class="bi bi-inbox"></i>
                    <p class="mb-0">No specific items listed for this drive.<br>
                    You can describe your donation in the details field below.</p>
                </div>
            `;
            }

            let html = '';
            let itemIndex = 0;

            for (const [packType, items] of Object.entries(itemsGrouped)) {
                const icon = packTypeIcons[packType] || 'bi-box';
                const typeName = packTypeNames[packType] || packType.toUpperCase();

                html += `
                <div class="pack-type-group">
                    <div class="pack-type-header">
                        <div class="pack-type-icon"><i class="bi ${icon}"></i></div>
                        <span class="pack-type-name">${typeName}</span>
                    </div>
                    <div class="pack-type-items">
            `;

                items.forEach(item => {
                    const pledgedPct = item.quantity_needed > 0 ?
                        Math.min(100, (item.quantity_pledged / item.quantity_needed) * 100) :
                        0;

                    html += `
                    <div class="item-row">
                        <div class="item-icon">
                            <i class="bi bi-check2-square"></i>
                        </div>
                        <div class="item-details">
                            <div class="item-name">${item.item_name}</div>
                            <div class="item-progress-container">
                                <div class="item-progress-bar">
                                    <div class="item-progress-fill" style="width: ${pledgedPct}%"></div>
                                </div>
                                <span class="item-progress-percent">${pledgedPct.toFixed(0)}%</span>
                            </div>
                        </div>
                        <div class="item-quantity-input">
                            <input type="number" name="items[${itemIndex}][quantity]" 
                                   placeholder="Qty" min="0" step="0.01"
                                   aria-label="Quantity for ${item.item_name}">
                            <input type="hidden" name="items[${itemIndex}][drive_item_id]" value="${item.id}">
                        </div>
                        <div class="item-unit">${item.unit}</div>
                    </div>
                `;
                    itemIndex++;
                });

                html += `
                    </div>
                </div>
            `;
            }

            return html;
        }

        function initMap(lat, lng, name, address) {
            setTimeout(() => {
                const mapEl = document.getElementById('map');
                if (!mapEl) return;

                if (currentMap) {
                    currentMap.remove();
                }

                currentMap = L.map('map').setView([lat, lng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(currentMap);

                // Custom red marker icon
                const redIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40">
                    <path fill="#e51d00" d="M12 0C7.58 0 4 3.58 4 8c0 5.25 8 13 8 13s8-7.75 8-13c0-4.42-3.58-8-8-8zm0 11c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                </svg>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                });

                currentMarker = L.marker([lat, lng], {
                        icon: redIcon
                    }).addTo(currentMap)
                    .bindPopup(`<strong>${name}</strong>${address ? '<br>' + address : ''}`);

                // Invalidate size to handle rendering issues
                setTimeout(() => currentMap.invalidateSize(), 100);
            }, 50);
        }

        function selectPledgeType(type) {
            document.getElementById('pledge_type').value = type;

            const btnInkind = document.getElementById('btn_inkind');
            const btnFinancial = document.getElementById('btn_financial');
            const financialSection = document.getElementById('financial_section');
            const itemsSection = document.getElementById('items_section');

            if (type === 'financial') {
                btnFinancial.classList.add('active');
                btnInkind.classList.remove('active');
                financialSection.style.display = 'block';
                itemsSection.style.display = 'none';
            } else {
                btnInkind.classList.add('active');
                btnFinancial.classList.remove('active');
                financialSection.style.display = 'none';
                itemsSection.style.display = 'block';
            }
        }

        function togglePledgeType() {
            const type = document.getElementById('pledge_type')?.value || 'in-kind';
            selectPledgeType(type);
        }

        // Form validation
        function validateForm() {
            const errors = [];
            const pledgeType = document.getElementById('pledge_type')?.value || 'in-kind';
            const driveId = document.getElementById('drive_id')?.value;
            const contactNumber = document.getElementById('contact_number')?.value?.trim();

            // Check drive selection
            if (!driveId) {
                errors.push('Please select a relief drive.');
            }

            // Check contact number
            if (!contactNumber) {
                errors.push('Contact number is required.');
            }

            // Check pledge type specific requirements
            if (pledgeType === 'financial') {
                const amount = parseFloat(document.getElementById('financial_amount')?.value) || 0;
                if (amount <= 0) {
                    errors.push('Please enter a valid financial donation amount.');
                }
            } else {
                // In-kind: check if at least one item has quantity > 0
                const itemInputs = document.querySelectorAll('input[name^="items"][name$="[quantity]"]');
                let hasItems = false;
                itemInputs.forEach(input => {
                    if (parseFloat(input.value) > 0) {
                        hasItems = true;
                    }
                });

                if (!hasItems && itemInputs.length > 0) {
                    errors.push('Please enter a quantity for at least one item.');
                }
            }

            return errors;
        }

        function showValidationErrors(errors) {
            const alertEl = document.getElementById('validationAlert');
            const errorsList = document.getElementById('validationErrors');

            if (errors.length > 0) {
                errorsList.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
                alertEl.style.display = 'block';
                alertEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                alertEl.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if ($selectedDrive && $selectedDrive->latitude && $selectedDrive->longitude)
                initMap({{ $selectedDrive->latitude }}, {{ $selectedDrive->longitude }},
                    '{{ $selectedDrive->name }}', '{{ $selectedDrive->address ?? '' }}');
            @endif

            const pledgeType = document.getElementById('pledge_type');
            if (pledgeType) {
                togglePledgeType();
            }

            // Form submission handler
            const form = document.getElementById('pledgeForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const errors = validateForm();
                    if (errors.length > 0) {
                        e.preventDefault();
                        showValidationErrors(errors);
                        return false;
                    }
                    // Hide any previous errors and allow form submission
                    document.getElementById('validationAlert').style.display = 'none';
                    return true;
                });
            }
        });
    </script>
@endsection
