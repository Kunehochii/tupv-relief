@extends('layouts.app')

@section('title', $drive->name)

@section('content')
    <div class="container py-5">
        {{-- Back to Dashboard --}}
        <a href="{{ route('donor.dashboard') }}" class="btn-back mb-4 d-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Back to Drives
        </a>

        <div class="drive-detail-card">
            <div class="row g-0">
                {{-- Left: Map --}}
                <div class="col-lg-6">
                    <div class="map-container">
                        @if ($drive->latitude && $drive->longitude)
                            <div id="drive-map" class="drive-map"></div>
                        @else
                            <div class="map-placeholder">
                                <i class="bi bi-geo-alt-fill"></i>
                                <p>Location not available</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right: Drive Info --}}
                <div class="col-lg-6">
                    <div class="drive-info-section">
                        {{-- Cover Photo with Zone Badge --}}
                        <div class="cover-wrapper">
                            @if ($drive->cover_photo)
                                <img src="{{ asset('storage/' . $drive->cover_photo) }}"
                                    alt="{{ $drive->name }}" class="cover-photo">
                            @else
                                <div class="cover-placeholder">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                            @endif
                            {{-- Drive Name Badge Overlay --}}
                            <div class="zone-badge">
                                <span>{{ Str::limit($drive->name, 30) }}</span>
                            </div>
                            {{-- DSWD logo overlay --}}
                            <div class="dswd-badge">
                                <img src="{{ asset('logos/dswd.png') }}" alt="DSWD" onerror="this.style.display='none'">
                            </div>
                        </div>

                        {{-- Drive Details --}}
                        <div class="drive-details-body">
                            <h3 class="drive-heading">NEEDS YOUR HELP</h3>
                            <p class="drive-description">{{ Str::limit($drive->description, 200) }}</p>

                            @if ($drive->address)
                                <p class="drive-location">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $drive->address }}
                                </p>
                            @endif

                            @if ($drive->families_affected)
                                <p class="drive-meta">
                                    <i class="bi bi-people-fill me-1"></i>
                                    {{ number_format($drive->families_affected) }} families affected
                                </p>
                            @endif

                            <div class="drive-progress-wrapper mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-bold">{{ $drive->progress_percentage }}%</small>
                                </div>
                                <div class="drive-progress-bar-detail">
                                    <div class="drive-progress-fill-detail"
                                        style="width: {{ $drive->progress_percentage }}%"></div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-3 mt-4 action-buttons">
                                <a href="{{ route('drive.donate', $drive) }}?from=donor"
                                    class="btn-action btn-donate-action"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="Send financial aid via NGOs">
                                    DONATE
                                </a>
                                <a href="{{ route('donor.pledges.create', ['drive_id' => $drive->id]) }}"
                                    class="btn-action btn-pledge-action"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="Send material aid via DSWD">
                                    PLEDGE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Drive Info --}}
        <div class="row mt-4">
            @if ($drive->end_date)
                <div class="col-md-4 mb-3">
                    <div class="info-card">
                        <i class="bi bi-calendar-event"></i>
                        <div>
                            <small class="text-muted">Drive Ends</small>
                            <p class="mb-0 fw-bold">{{ $drive->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <div class="info-card">
                    <i class="bi bi-box-seam"></i>
                    <div>
                        <small class="text-muted">Items Needed</small>
                        <p class="mb-0 fw-bold">{{ number_format($drive->total_items_needed) }} items</p>
                    </div>
                </div>
            </div>
            @if ($drive->families_affected)
                <div class="col-md-4 mb-3">
                    <div class="info-card">
                        <i class="bi bi-people"></i>
                        <div>
                            <small class="text-muted">Families Affected</small>
                            <p class="mb-0 fw-bold">{{ number_format($drive->families_affected) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .btn-back {
            color: var(--dark-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: var(--vivid-red);
        }

        .drive-detail-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        /* Map */
        .map-container {
            height: 100%;
            min-height: 450px;
        }

        .drive-map {
            width: 100%;
            height: 100%;
            min-height: 450px;
        }

        .map-placeholder {
            width: 100%;
            height: 100%;
            min-height: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            color: #adb5bd;
        }

        .map-placeholder i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        /* Cover Photo */
        .cover-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        .cover-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(60%);
        }

        .cover-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cover-placeholder i {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .zone-badge {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: var(--vivid-red);
            color: #fff;
            padding: 6px 18px;
            font-weight: 800;
            font-size: 1.1rem;
            font-style: italic;
            letter-spacing: 1px;
            text-transform: uppercase;
            clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%);
            padding-right: 28px;
        }

        .dswd-badge {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .dswd-badge img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        /* Drive Details */
        .drive-details-body {
            padding: 1.5rem 1.75rem;
        }

        .drive-heading {
            font-weight: 800;
            color: #1a1a1a;
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
        }

        .drive-description {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .drive-location {
            color: var(--vivid-red);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
        }

        .drive-meta {
            color: var(--gray-blue);
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .drive-progress-bar-detail {
            height: 8px;
            background: #f1dddd;
            border-radius: 999px;
            overflow: hidden;
        }

        .drive-progress-fill-detail {
            height: 100%;
            background: linear-gradient(90deg, #e51d00 0%, #e54a3a 100%);
            border-radius: 999px;
            transition: width 0.5s ease;
        }

        /* Action Buttons */
        .action-buttons {
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 140px;
            text-align: center;
            padding: 0.9rem 2rem;
            font-weight: 700;
            font-size: 1.05rem;
            border-radius: 50px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s;
            border: 2.5px solid;
        }

        .btn-donate-action {
            background: #fff;
            color: var(--dark-blue);
            border-color: var(--dark-blue);
        }

        .btn-donate-action:hover {
            background: var(--dark-blue);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 1, 103, 0.35);
        }

        .btn-pledge-action {
            background: var(--dark-blue);
            color: #fff;
            border-color: var(--dark-blue);
        }

        .btn-pledge-action:hover {
            background: #000050;
            border-color: #000050;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 1, 103, 0.45);
        }

        /* Info Cards */
        .info-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #fff;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .info-card i {
            font-size: 1.5rem;
            color: var(--dark-blue);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .map-container,
            .drive-map,
            .map-placeholder {
                min-height: 300px;
            }

            .cover-wrapper {
                height: 180px;
            }

            .drive-heading {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .drive-details-body {
                padding: 1rem 1.25rem;
            }

            .btn-action {
                font-size: 0.9rem;
                padding: 0.75rem 1.5rem;
            }

            .zone-badge {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(el) { return new bootstrap.Tooltip(el); });
        });
    </script>
    @if ($drive->latitude && $drive->longitude)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('drive-map').setView([{{ $drive->latitude }}, {{ $drive->longitude }}], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([{{ $drive->latitude }}, {{ $drive->longitude }}])
                    .addTo(map)
                    .bindPopup(`
                        <strong>{{ $drive->name }}</strong><br>
                        {{ $drive->address ?? 'Drive location' }}
                    `)
                    .openPopup();

                // Fix map rendering in hidden containers
                setTimeout(() => map.invalidateSize(), 250);
            });
        </script>
    @endif
@endsection
