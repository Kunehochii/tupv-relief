{{-- 
    3-Color Progress Bar Component
    
    Usage: @include('partials.progress-bar-3color', ['drive' => $drive, 'showLegend' => true])
    
    Colors:
    - Green: Distributed
    - Blue: Pledged (but not yet distributed)
    - Gray: Remaining needed
--}}

@php
    // Support both passing a $drive object or direct percentage values
    $distributedPct = $distributed ?? ($drive->distributed_percentage ?? 0);
    $pledgedPct = $pledged ?? ($drive->pledged_percentage ?? 0);
    $showLegend = $showLegend ?? true;
    $height = $height ?? '12px';
    // Pledged-only portion (pledged but not distributed)
    $pledgedOnlyPct = max(0, $pledgedPct - $distributedPct);
    $neededPct = max(0, 100 - $pledgedPct);
@endphp

<div class="progress" style="height: {{ $height }}; background-color: #e9ecef;">
    {{-- Distributed (green) --}}
    @if ($distributedPct > 0)
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $distributedPct }}%"
            aria-valuenow="{{ $distributedPct }}" aria-valuemin="0" aria-valuemax="100"
            title="Distributed: {{ $distributedPct }}%">
        </div>
    @endif

    {{-- Pledged but not distributed (blue) --}}
    @if ($pledgedOnlyPct > 0)
        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pledgedOnlyPct }}%"
            aria-valuenow="{{ $pledgedOnlyPct }}" aria-valuemin="0" aria-valuemax="100"
            title="Pledged: {{ $pledgedOnlyPct }}%">
        </div>
    @endif

    {{-- Remaining needed is represented by the empty gray background --}}
</div>

@if ($showLegend)
    <div class="d-flex flex-wrap gap-3 mt-2 small">
        <span>
            <span class="badge bg-success">&nbsp;</span>
            Distributed: {{ number_format($distributedPct, 1) }}%
        </span>
        <span>
            <span class="badge bg-primary">&nbsp;</span>
            Pledged: {{ number_format($pledgedPct, 1) }}%
        </span>
        <span>
            <span class="badge bg-secondary">&nbsp;</span>
            Needed: {{ number_format($neededPct, 1) }}%
        </span>
    </div>
@endif
