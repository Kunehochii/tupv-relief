{{-- Netflix-style Drive Tile --}}
{{-- Variables: $drive, $badgeType (optional), $badgeText (optional), $isCompleted (optional) --}}
@php
    $isCompleted = $isCompleted ?? false;
    $badgeType = $badgeType ?? null;
    $badgeText = $badgeText ?? null;
    $pct = $drive->progress_percentage;
    $pctClass = $pct < 30 ? 'pct-low' : ($pct < 70 ? 'pct-mid' : 'pct-high');
    $progressGradient = $pct < 30
        ? 'linear-gradient(90deg, #ff6b6b, #ff8787)'
        : ($pct < 70
            ? 'linear-gradient(90deg, #ffae44, #ffca80)'
            : 'linear-gradient(90deg, #51cf66, #8ce99a)');
    $pledgeUrl = auth()->check() && auth()->user()->isNgo()
        ? route('ngo.pledges.create', ['drive' => $drive->id])
        : route('donor.pledges.create', ['drive' => $drive->id]);
@endphp

<div class="drive-tile">
    {{-- Badge --}}
    @if ($badgeType && $badgeText)
        <span class="drive-tile-badge {{ $badgeType }}">{{ $badgeText }}</span>
    @endif

    {{-- Image --}}
    @if ($drive->cover_photo)
        <img src="{{ $drive->cover_photo_url }}" alt="{{ $drive->name }}" class="drive-tile-img"
            @if ($isCompleted) style="filter: grayscale(40%);" @endif>
    @else
        <div class="drive-tile-placeholder">
            <i class="bi {{ $isCompleted ? 'bi-check-circle-fill' : 'bi-heart-fill' }}"></i>
        </div>
    @endif

    {{-- Body --}}
    <div class="drive-tile-body">
        <div class="drive-tile-name" title="{{ $drive->name }}">{{ $drive->name }}</div>
        <div class="drive-tile-progress">
            <div class="drive-tile-progress-fill" style="width: {{ $pct }}%; background: {{ $progressGradient }};"></div>
        </div>
        <div class="drive-tile-meta">
            <span class="drive-tile-ends">
                @if ($isCompleted)
                    Completed
                @else
                    Ends {{ $drive->end_date->format('M d') }}
                @endif
            </span>
            <span class="drive-tile-pct {{ $pctClass }}">{{ $pct }}%</span>
        </div>
    </div>

    {{-- Hover Overlay (hidden on mobile via CSS) --}}
    <div class="drive-tile-overlay">
        @if (!$isCompleted)
            <a href="{{ $pledgeUrl }}" class="overlay-pledge-btn" onclick="event.stopPropagation();">
                <i class="bi bi-heart-fill me-1"></i> Pledge
            </a>
        @endif
        <a href="{{ route('drive.preview', $drive) }}" class="overlay-info-btn" onclick="event.stopPropagation();">
            <i class="bi bi-info-circle me-1"></i> View Details
        </a>
    </div>
</div>
