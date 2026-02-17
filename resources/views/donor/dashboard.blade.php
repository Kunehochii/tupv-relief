@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('styles')
    <style>
        /* ===== Netflix-Style Drives Dashboard ===== */
        .netflix-dashboard {
            background: #0a0a2e;
            min-height: 100vh;
            padding-bottom: 4rem;
        }

        /* --- Hero / Featured Section --- */
        .hero-section {
            position: relative;
            width: 100%;
            height: 520px;
            overflow: hidden;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.8s ease;
            pointer-events: none;
        }

        .hero-slide.active {
            opacity: 1;
            pointer-events: auto;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            filter: brightness(0.45);
        }

        .hero-bg-fallback {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #000167 0%, #1a1a5e 40%, #0a0a2e 100%);
        }

        .hero-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, #0a0a2e 0%, transparent 50%),
                linear-gradient(to right, rgba(10, 10, 46, 0.95) 0%, transparent 60%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 0 5% 3rem;
            max-width: 650px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--vivid-red);
            color: white;
            padding: 4px 14px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 0.75rem;
            width: fit-content;
        }

        .hero-title {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero-description {
            color: rgba(255, 255, 255, 0.75);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hero-meta {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        .hero-meta-item i {
            color: var(--vivid-orange);
        }

        .hero-progress {
            width: 200px;
            height: 6px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .hero-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--vivid-red), var(--vivid-orange));
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
        }

        .btn-hero-pledge {
            background: var(--vivid-red);
            color: white;
            border: none;
            padding: 12px 32px;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-pledge:hover {
            background: #ff2d10;
            color: white;
            transform: scale(1.03);
        }

        .btn-hero-info {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 12px 28px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
        }

        .btn-hero-info:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }

        /* Hero Indicators */
        .hero-indicators {
            position: absolute;
            bottom: 2rem;
            right: 5%;
            display: flex;
            gap: 8px;
            z-index: 3;
        }

        .hero-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            padding: 0;
        }

        .hero-dot.active {
            background: var(--vivid-red);
            transform: scale(1.3);
        }

        /* --- Category Rows (Netflix rows) --- */
        .drive-row {
            padding: 0 5%;
            margin-bottom: 2.5rem;
        }

        .drive-row-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .drive-row-title {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .drive-row-title i {
            color: var(--vivid-red);
            font-size: 1.1rem;
        }

        .drive-row-title .row-count {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            font-weight: 500;
            padding: 2px 10px;
            border-radius: 10px;
        }

        /* Horizontal scroll container */
        .drive-row-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .drive-row-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Drive Card (Netflix tile) */
        .drive-tile {
            flex: 0 0 280px;
            border-radius: 8px;
            overflow: hidden;
            background: #141448;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            cursor: pointer;
            position: relative;
            text-decoration: none;
            color: inherit;
        }

        .drive-tile:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
            z-index: 5;
        }

        .drive-tile-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .drive-tile-placeholder {
            width: 100%;
            height: 160px;
            background: linear-gradient(135deg, #1e1e6e 0%, #000167 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drive-tile-placeholder i {
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.15);
        }

        .drive-tile-body {
            padding: 14px 16px;
        }

        .drive-tile-name {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .drive-tile-progress {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .drive-tile-progress-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 0.5s ease;
        }

        .drive-tile-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .drive-tile-ends {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.45);
        }

        .drive-tile-pct {
            font-size: 0.8rem;
            font-weight: 700;
        }

        .pct-low { color: #ff6b6b; }
        .pct-mid { color: var(--vivid-orange); }
        .pct-high { color: #51cf66; }

        /* Urgency badge on tile */
        .drive-tile-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--vivid-red);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .drive-tile-badge.ending-soon { background: #ff8c00; }
        .drive-tile-badge.completed { background: #51cf66; }
        .drive-tile-badge.new-drive { background: #228be6; }

        /* Hover overlay */
        .drive-tile-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 1, 103, 0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            gap: 10px;
        }

        .drive-tile:hover .drive-tile-overlay {
            opacity: 1;
        }

        .overlay-pledge-btn {
            background: var(--vivid-red);
            color: white;
            border: none;
            padding: 10px 28px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .overlay-pledge-btn:hover {
            background: #ff2d10;
            color: white;
        }

        .overlay-info-btn {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            text-decoration: none;
            transition: color 0.3s;
        }

        .overlay-info-btn:hover {
            color: white;
        }

        /* --- Flash Alerts on dark bg --- */
        .netflix-dashboard .alert {
            margin: 1rem 5% 0;
            border-radius: 8px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 991px) {
            .hero-section { height: 420px; }
            .hero-title { font-size: 1.8rem; }
            .hero-content { max-width: 100%; }
            .drive-tile { flex: 0 0 240px; }
        }

        @media (max-width: 767px) {
            .hero-section { height: 380px; }
            .hero-title { font-size: 1.5rem; }
            .hero-description { font-size: 0.9rem; -webkit-line-clamp: 2; }
            .hero-actions { flex-direction: column; }
            .btn-hero-pledge, .btn-hero-info {
                width: 100%;
                justify-content: center;
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            .hero-indicators {
                bottom: 1rem;
                right: 50%;
                transform: translateX(50%);
            }
            .drive-row { padding: 0 1rem; margin-bottom: 2rem; }
            .drive-row-title { font-size: 1.1rem; }
            .drive-tile { flex: 0 0 200px; }
            .drive-tile-img, .drive-tile-placeholder { height: 120px; }
            .drive-tile-body { padding: 10px 12px; }
            .drive-tile-name { font-size: 0.85rem; }
            .drive-tile-overlay { display: none; }
        }

        @media (max-width: 375px) {
            .hero-section { height: 340px; }
            .hero-title { font-size: 1.25rem; }
            .drive-tile { flex: 0 0 170px; }
            .drive-tile-img, .drive-tile-placeholder { height: 100px; }
        }
    </style>
@endsection

@section('content')
    <div class="netflix-dashboard">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===== HERO SECTION ===== --}}
        @if ($activeDrives->count())
            <div class="hero-section">
                @foreach ($activeDrives->take(5) as $i => $drive)
                    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                        @if ($drive->cover_photo)
                            <div class="hero-bg" style="background-image: url('{{ $drive->cover_photo_url }}');"></div>
                        @else
                            <div class="hero-bg-fallback"></div>
                        @endif
                        <div class="hero-gradient"></div>

                        <div class="hero-content">
                            @php
                                $daysLeft = now()->diffInDays($drive->end_date, false);
                            @endphp
                            <div class="hero-badge">
                                <i class="bi bi-lightning-fill"></i>
                                @if ($daysLeft <= 3 && $daysLeft >= 0)
                                    ENDING SOON — {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
                                @elseif($drive->created_at->diffInDays(now()) <= 3)
                                    NEW DRIVE
                                @else
                                    ACTIVE DRIVE
                                @endif
                            </div>

                            <h1 class="hero-title">{{ $drive->name }}</h1>
                            <p class="hero-description">{{ $drive->description }}</p>

                            <div class="hero-meta">
                                @if ($drive->families_affected)
                                    <span class="hero-meta-item">
                                        <i class="bi bi-people-fill"></i>
                                        {{ number_format($drive->families_affected) }} families
                                    </span>
                                @endif
                                <span class="hero-meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    Ends {{ $drive->end_date->format('M d, Y') }}
                                </span>
                                @if ($drive->address)
                                    <span class="hero-meta-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        {{ Str::limit($drive->address, 30) }}
                                    </span>
                                @endif
                            </div>

                            <div class="hero-progress">
                                <div class="hero-progress-fill" style="width: {{ $drive->progress_percentage }}%;"></div>
                            </div>

                            <div class="hero-actions">
                                <a href="{{ route('donor.pledges.create', ['drive' => $drive->id]) }}" class="btn-hero-pledge">
                                    <i class="bi bi-heart-fill"></i> Pledge Now
                                </a>
                                <a href="{{ route('drive.preview', $drive) }}" class="btn-hero-info">
                                    <i class="bi bi-info-circle"></i> More Info
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($activeDrives->take(5)->count() > 1)
                    <div class="hero-indicators">
                        @foreach ($activeDrives->take(5) as $i => $drive)
                            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== ENDING SOON ROW ===== --}}
        @if ($endingSoonDrives->count())
            <div class="drive-row" style="margin-top: 2.5rem;">
                <div class="drive-row-header">
                    <h2 class="drive-row-title">
                        <i class="bi bi-clock-history"></i> Ending Soon
                        <span class="row-count">{{ $endingSoonDrives->count() }}</span>
                    </h2>
                </div>
                <div class="drive-row-scroll">
                    @foreach ($endingSoonDrives as $drive)
                        @include('partials.drive-tile', [
                            'drive' => $drive,
                            'badgeType' => 'ending-soon',
                            'badgeText' => now()->diffInDays($drive->end_date, false) . ' days left',
                        ])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===== MOST NEEDED ROW ===== --}}
        @if ($mostNeededDrives->count())
            <div class="drive-row">
                <div class="drive-row-header">
                    <h2 class="drive-row-title">
                        <i class="bi bi-exclamation-triangle-fill"></i> Most Help Needed
                        <span class="row-count">{{ $mostNeededDrives->count() }}</span>
                    </h2>
                </div>
                <div class="drive-row-scroll">
                    @foreach ($mostNeededDrives as $drive)
                        @include('partials.drive-tile', ['drive' => $drive])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===== RECENTLY ADDED ROW ===== --}}
        @if ($recentDrives->count())
            <div class="drive-row">
                <div class="drive-row-header">
                    <h2 class="drive-row-title">
                        <i class="bi bi-stars"></i> Recently Added
                        <span class="row-count">{{ $recentDrives->count() }}</span>
                    </h2>
                </div>
                <div class="drive-row-scroll">
                    @foreach ($recentDrives as $drive)
                        @include('partials.drive-tile', [
                            'drive' => $drive,
                            'badgeType' => 'new-drive',
                            'badgeText' => 'New',
                        ])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===== ALL ACTIVE DRIVES ROW ===== --}}
        @if ($activeDrives->count())
            <div class="drive-row">
                <div class="drive-row-header">
                    <h2 class="drive-row-title">
                        <i class="bi bi-collection-fill"></i> All Active Drives
                        <span class="row-count">{{ $activeDrives->count() }}</span>
                    </h2>
                </div>
                <div class="drive-row-scroll">
                    @foreach ($activeDrives as $drive)
                        @include('partials.drive-tile', ['drive' => $drive])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===== COMPLETED DRIVES ROW ===== --}}
        @if ($completedDrives->count())
            <div class="drive-row">
                <div class="drive-row-header">
                    <h2 class="drive-row-title">
                        <i class="bi bi-check-circle-fill"></i> Completed Drives
                        <span class="row-count">{{ $completedDrives->count() }}</span>
                    </h2>
                </div>
                <div class="drive-row-scroll">
                    @foreach ($completedDrives as $drive)
                        @include('partials.drive-tile', [
                            'drive' => $drive,
                            'badgeType' => 'completed',
                            'badgeText' => 'Completed',
                            'isCompleted' => true,
                        ])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty state --}}
        @if ($activeDrives->isEmpty() && $completedDrives->isEmpty())
            <div class="text-center py-5" style="color: rgba(255,255,255,0.4);">
                <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Drives Available</h4>
                <p>Check back soon for new donation drives.</p>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== Hero auto-rotate =====
            const heroSlides = document.querySelectorAll('.hero-slide');
            const heroDots = document.querySelectorAll('.hero-dot');
            let heroIndex = 0;
            let heroInterval;

            function goToHero(index) {
                heroSlides.forEach((s, i) => s.classList.toggle('active', i === index));
                heroDots.forEach((d, i) => d.classList.toggle('active', i === index));
                heroIndex = index;
            }

            function nextHero() {
                goToHero((heroIndex + 1) % heroSlides.length);
            }

            function startHeroRotate() {
                heroInterval = setInterval(nextHero, 7000);
            }

            function stopHeroRotate() {
                clearInterval(heroInterval);
            }

            heroDots.forEach(dot => {
                dot.addEventListener('click', () => {
                    stopHeroRotate();
                    goToHero(parseInt(dot.dataset.index));
                    startHeroRotate();
                });
            });

            if (heroSlides.length > 1) {
                startHeroRotate();
            }

            // ===== Row scroll drag support =====
            document.querySelectorAll('.drive-row-scroll').forEach(row => {
                let isDown = false, startX, scrollLeft;

                row.addEventListener('mousedown', e => {
                    isDown = true;
                    row.style.cursor = 'grabbing';
                    startX = e.pageX - row.offsetLeft;
                    scrollLeft = row.scrollLeft;
                });
                row.addEventListener('mouseleave', () => { isDown = false; row.style.cursor = 'grab'; });
                row.addEventListener('mouseup', () => { isDown = false; row.style.cursor = 'grab'; });
                row.addEventListener('mousemove', e => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - row.offsetLeft;
                    row.scrollLeft = scrollLeft - (x - startX) * 2;
                });
                row.style.cursor = 'grab';
            });
        });
    </script>
@endsection
