{{-- Drive Carousel Component for Donor/NGO Dashboards --}}
{{-- Variables: $drives (collection), $userType ('donor'|'ngo'), $supportedDriveIds (array, optional for NGO) --}}

@php
    $userType = $userType ?? 'donor';
    $supportedDriveIds = $supportedDriveIds ?? [];
@endphp

<div class="drive-carousel-container" data-user-type="{{ $userType }}"
    data-fetch-url="{{ $userType === 'ngo' ? route('ngo.drives.fetch') : route('donor.drives.fetch') }}"
    data-supported-ids="{{ json_encode($supportedDriveIds) }}">

    {{-- Carousel Wrapper --}}
    <div class="drive-carousel">
        <div class="carousel-inner" id="driveCarouselInner">
            @forelse($drives as $index => $drive)
                <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-drive-id="{{ $drive->id }}">
                    <div class="drive-card">
                        <div class="row g-0 align-items-stretch">
                            {{-- Left: Map --}}
                            <div class="col-md-5 d-flex">
                                <div class="drive-map-wrapper">
                                    <div class="drive-map-container" id="map-{{ $drive->id }}"
                                        data-lat="{{ $drive->latitude ?? 10.3157 }}"
                                        data-lng="{{ $drive->longitude ?? 123.8854 }}">
                                    </div>
                                </div>
                            </div>
                            {{-- Right: Content --}}
                            <div class="col-md-7">
                                <div class="drive-content">
                                    {{-- Cover Photo with Name Overlay --}}
                                    <div class="drive-cover">
                                        @if ($drive->cover_photo)
                                            <img src="{{ $drive->cover_photo_url }}" alt="{{ $drive->name }}"
                                                class="drive-cover-img">
                                        @else
                                            <div class="drive-cover-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <div class="drive-name-overlay">
                                            <span class="drive-name">{{ strtoupper($drive->name) }}</span>
                                        </div>
                                    </div>

                                    {{-- Needs Your Help --}}
                                    <div class="drive-help-text">
                                        <span class="text-dark fw-bold">NEEDS YOUR HELP</span>
                                    </div>

                                    {{-- Description --}}
                                    <div class="drive-description">
                                        <p>{{ Str::limit($drive->description, 200) }}</p>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="drive-actions">
                                        @if ($userType !== 'ngo')
                                            <a href="{{ route('drive.donate', $drive) }}" class="btn btn-donate">
                                                DONATE
                                            </a>
                                        @endif
                                        <a href="{{ $userType === 'ngo' ? route('ngo.pledges.create', ['drive' => $drive->id]) : route('donor.pledges.create', ['drive' => $drive->id]) }}"
                                            class="btn btn-pledge">
                                            PLEDGE
                                        </a>
                                        @if ($userType === 'ngo')
                                            @php
                                                $isSupported = in_array($drive->id, $supportedDriveIds);
                                                $isVerified = auth()->user()->isVerifiedNgo();
                                            @endphp
                                            @if ($isVerified)
                                                <form action="{{ route('ngo.drives.support', $drive) }}" method="POST"
                                                    class="d-inline support-form">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-support {{ $isSupported ? 'supported' : '' }}"
                                                        {{ $isSupported ? 'disabled' : '' }}>
                                                        <i
                                                            class="bi {{ $isSupported ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                                        {{ $isSupported ? 'SUPPORTED' : 'SUPPORT' }}
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-support-disabled" disabled
                                                    title="NGO verification required to support drives">
                                                    <i class="bi bi-lock"></i>
                                                    SUPPORT
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-slide active">
                    <div class="no-drives-card">
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">No Active Drives</h4>
                            <p class="text-muted">Check back soon for new donation drives.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Carousel Controls --}}
        @if ($drives->count() > 1)
            <div class="carousel-controls">
                <button class="carousel-btn carousel-prev" id="carouselPrev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <div class="carousel-indicators" id="carouselIndicators">
                    @foreach ($drives as $index => $drive)
                        <span class="indicator {{ $index === 0 ? 'active' : '' }}"
                            data-index="{{ $index }}"></span>
                    @endforeach
                </div>
                <button class="carousel-btn carousel-next" id="carouselNext">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- Loading indicator --}}
    <div class="carousel-loading d-none" id="carouselLoading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<style>
    /* Drive Carousel Styles */
    .drive-carousel-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
    }

    .drive-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .carousel-inner {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-slide {
        min-width: 100%;
        opacity: 0;
        position: absolute;
        left: 0;
        top: 0;
        transition: opacity 0.5s ease-in-out;
        pointer-events: none;
    }

    .carousel-slide.active {
        opacity: 1;
        position: relative;
        pointer-events: auto;
    }

    .drive-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        padding: 20px;
    }

    /* Map Wrapper for proper sizing */
    .drive-map-wrapper {
        width: 100%;
        padding-right: 10px;
    }

    /* Map Container - With proper spacing and rounded corners */
    .drive-map-container {
        height: 100%;
        min-height: 460px;
        background: #e6e6e4;
        border-radius: 16px;
        margin: 0;
        overflow: hidden;
    }

    /* Ensure Leaflet map respects border radius */
    .drive-map-container .leaflet-container {
        border-radius: 16px;
    }

    /* Cover Photo - Made larger */
    .drive-cover {
        position: relative;
        height: 280px;
        overflow: hidden;
        border-radius: 12px;
    }

    .drive-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(100%);
    }

    .drive-cover-placeholder {
        width: 100%;
        height: 100%;
        background: #8a95b6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }

    .drive-name-overlay {
        position: absolute;
        bottom: 20px;
        left: 0;
        padding: 12px 35px 12px 24px;
        background: #ea4f2d;
        clip-path: polygon(0 0, 100% 0, 95% 100%, 0 100%);
    }

    .drive-name {
        color: white;
        font-weight: 900;
        font-size: 2.2rem;
        letter-spacing: 2px;
        font-style: italic;
        text-transform: uppercase;
    }

    /* Help Text */
    .drive-help-text {
        padding: 20px 24px 8px;
        font-size: 1.6rem;
        font-weight: 700;
        color: #333;
    }

    /* Description */
    .drive-description {
        padding: 0 24px 20px;
        color: #666;
        font-size: 1rem;
        line-height: 1.7;
    }

    .drive-description p {
        margin: 0;
    }

    /* Action Buttons */
    .drive-actions {
        padding: 20px 24px 30px;
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    .btn-donate,
    .btn-pledge,
    .btn-support {
        padding: 14px 40px;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-donate {
        background-color: #000167;
        border-color: #000167;
        color: white;
    }

    .btn-donate:hover {
        background-color: #000099;
        border-color: #000099;
        color: white;
        transform: translateY(-2px);
    }

    .btn-pledge {
        background-color: #000167;
        border-color: #000167;
        color: white;
    }

    .btn-pledge:hover {
        background-color: #000099;
        border-color: #000099;
        color: white;
        transform: translateY(-2px);
    }

    .btn-support {
        background-color: #dd3319;
        border-color: #dd3319;
        color: white;
    }

    .btn-support:hover:not(:disabled) {
        background-color: #e51d00;
        border-color: #e51d00;
        color: white;
        transform: translateY(-2px);
    }

    .btn-support.supported {
        background-color: #8a95b6;
        border-color: #8a95b6;
        cursor: not-allowed;
    }

    .btn-support-disabled {
        background-color: #c8c8c8;
        border-color: #c8c8c8;
        color: #888;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-support-disabled:hover {
        background-color: #c8c8c8;
        border-color: #c8c8c8;
        color: #888;
        transform: none;
    }

    /* Carousel Controls */
    .carousel-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        gap: 15px;
        background: rgba(255, 255, 255, 0.9);
    }

    .carousel-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #000167;
        background: white;
        color: #000167;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-btn:hover {
        background: #000167;
        color: white;
    }

    .carousel-indicators {
        display: flex;
        gap: 8px;
    }

    .indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #e6e6e4;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .indicator.active {
        background: #000167;
        transform: scale(1.2);
    }

    /* Loading */
    .carousel-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* No drives card */
    .no-drives-card {
        background: white;
        border-radius: 16px;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .drive-card {
            padding: 10px;
        }

        .drive-map-wrapper {
            padding-right: 0;
            padding-bottom: 10px;
        }

        .drive-map-container {
            min-height: 200px;
        }

        .drive-cover {
            height: 180px;
        }

        .drive-name {
            font-size: 1.2rem;
            letter-spacing: 1px;
        }

        .drive-name-overlay {
            padding: 8px 24px 8px 16px;
            bottom: 12px;
        }

        .drive-help-text {
            font-size: 1.1rem;
            padding: 12px 12px 4px;
        }

        .drive-description {
            padding: 0 12px 12px;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .drive-actions {
            flex-direction: column;
            align-items: stretch;
            padding: 10px 12px 20px;
            gap: 10px;
        }

        .btn-donate,
        .btn-pledge,
        .btn-support,
        .btn-support-disabled {
            width: 100%;
            padding: 12px 20px;
            font-size: 0.95rem;
        }

        .carousel-controls {
            padding: 10px;
            gap: 10px;
        }

        .carousel-btn {
            width: 34px;
            height: 34px;
        }

        .indicator {
            width: 8px;
            height: 8px;
        }
    }

    /* Very small screens */
    @media (max-width: 375px) {
        .drive-cover {
            height: 150px;
        }

        .drive-name {
            font-size: 1rem;
        }

        .drive-help-text {
            font-size: 1rem;
            padding: 10px 10px 2px;
        }

        .drive-description {
            padding: 0 10px 10px;
            font-size: 0.85rem;
        }

        .drive-actions {
            padding: 8px 10px 16px;
        }
    }

    /* Toast animations */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.drive-carousel-container');
        if (!container) return;

        const userType = container.dataset.userType;
        const fetchUrl = container.dataset.fetchUrl;
        let supportedIds = JSON.parse(container.dataset.supportedIds || '[]');

        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.indicator');
        const prevBtn = document.getElementById('carouselPrev');
        const nextBtn = document.getElementById('carouselNext');

        let currentIndex = 0;
        let autoRotateInterval;
        let allDrives = Array.from(slides);
        let offset = allDrives.length;
        let isLoading = false;
        let hasMore = true;

        // Initialize maps for visible drives
        function initializeMaps() {
            slides.forEach(slide => {
                if (!slide.classList.contains('active')) return;
                const mapContainer = slide.querySelector('.drive-map-container');
                if (mapContainer && !mapContainer._leaflet_id) {
                    const lat = parseFloat(mapContainer.dataset.lat) || 10.3157;
                    const lng = parseFloat(mapContainer.dataset.lng) || 123.8854;

                    const map = L.map(mapContainer, {
                        zoomControl: false,
                        dragging: false,
                        scrollWheelZoom: false
                    }).setView([lat, lng], 10);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    // Add marker
                    const markerIcon = L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background:#dd3319;width:30px;height:40px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;"><div style="background:white;width:12px;height:12px;border-radius:50%;transform:rotate(45deg);"></div></div>',
                        iconSize: [30, 40],
                        iconAnchor: [15, 40]
                    });
                    L.marker([lat, lng], {
                        icon: markerIcon
                    }).addTo(map);
                }
            });
        }

        function goToSlide(index) {
            if (index < 0 || index >= allDrives.length) return;

            allDrives.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });

            indicators.forEach((ind, i) => {
                ind.classList.toggle('active', i === index);
            });

            currentIndex = index;
            initializeMaps();

            // Fetch more if near end
            if (currentIndex >= allDrives.length - 3 && hasMore && !isLoading) {
                fetchMoreDrives();
            }
        }

        function nextSlide() {
            const next = (currentIndex + 1) % allDrives.length;
            goToSlide(next);
        }

        function prevSlide() {
            const prev = (currentIndex - 1 + allDrives.length) % allDrives.length;
            goToSlide(prev);
        }

        async function fetchMoreDrives() {
            if (isLoading || !hasMore) return;
            isLoading = true;

            const loading = document.getElementById('carouselLoading');
            if (loading) loading.classList.remove('d-none');

            try {
                const response = await fetch(`${fetchUrl}?offset=${offset}&limit=5`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                hasMore = data.hasMore;

                if (data.drives && data.drives.length > 0) {
                    data.drives.forEach(drive => {
                        const slide = createSlide(drive);
                        document.getElementById('driveCarouselInner').appendChild(slide);
                        allDrives.push(slide);

                        // Add indicator
                        const indicatorContainer = document.getElementById('carouselIndicators');
                        if (indicatorContainer) {
                            const indicator = document.createElement('span');
                            indicator.className = 'indicator';
                            indicator.dataset.index = allDrives.length - 1;
                            indicator.addEventListener('click', () => goToSlide(parseInt(indicator
                                .dataset.index)));
                            indicatorContainer.appendChild(indicator);
                        }
                    });

                    offset += data.drives.length;
                }
            } catch (error) {
                console.error('Error fetching drives:', error);
            } finally {
                isLoading = false;
                if (loading) loading.classList.add('d-none');
            }
        }

        function createSlide(drive) {
            const slide = document.createElement('div');
            slide.className = 'carousel-slide';
            slide.dataset.driveId = drive.id;

            const donateButtonHtml = userType !== 'ngo' ?
                `<a href="${drive.donate_url}" class="btn btn-donate">DONATE</a>` :
                '';

            const isSupported = supportedIds.includes(drive.id);
            const supportButtonHtml = userType === 'ngo' ? `
            <form action="${drive.support_url}" method="POST" class="d-inline support-form">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                <button type="submit" 
                        class="btn btn-support ${isSupported ? 'supported' : ''}"
                        ${isSupported ? 'disabled' : ''}>
                    <i class="bi ${isSupported ? 'bi-heart-fill' : 'bi-heart'}"></i>
                    ${isSupported ? 'SUPPORTED' : 'SUPPORT'}
                </button>
            </form>
        ` : '';

            slide.innerHTML = `
            <div class="drive-card">
                <div class="row g-0 align-items-stretch">
                    <div class="col-md-5 d-flex">
                        <div class="drive-map-wrapper">
                            <div class="drive-map-container" id="map-${drive.id}" 
                                 data-lat="${drive.latitude || 10.3157}" 
                                 data-lng="${drive.longitude || 123.8854}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="drive-content">
                            <div class="drive-cover">
                                ${drive.cover_photo_url 
                                    ? `<img src="${drive.cover_photo_url}" alt="${drive.name}" class="drive-cover-img">`
                                    : `<div class="drive-cover-placeholder"><i class="bi bi-image"></i></div>`
                                }
                                <div class="drive-name-overlay">
                                    <span class="drive-name">${drive.name.toUpperCase()}</span>
                                </div>
                            </div>
                            <div class="drive-help-text">
                                <span class="text-dark fw-bold">NEEDS YOUR HELP</span>
                            </div>
                            <div class="drive-description">
                                <p>${drive.description ? drive.description.substring(0, 200) : ''}</p>
                            </div>
                            <div class="drive-actions">
                                ${donateButtonHtml}
                                <a href="${drive.pledge_url}" class="btn btn-pledge">PLEDGE</a>
                                ${supportButtonHtml}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            return slide;
        }

        // Start auto-rotate
        function startAutoRotate() {
            autoRotateInterval = setInterval(nextSlide, 8000);
        }

        function stopAutoRotate() {
            clearInterval(autoRotateInterval);
        }

        // Event listeners
        if (prevBtn) prevBtn.addEventListener('click', () => {
            stopAutoRotate();
            prevSlide();
            startAutoRotate();
        });
        if (nextBtn) nextBtn.addEventListener('click', () => {
            stopAutoRotate();
            nextSlide();
            startAutoRotate();
        });

        indicators.forEach(ind => {
            ind.addEventListener('click', () => {
                stopAutoRotate();
                goToSlide(parseInt(ind.dataset.index));
                startAutoRotate();
            });
        });

        // Handle support form submissions with AJAX
        document.addEventListener('submit', async function(e) {
            if (!e.target.classList.contains('support-form')) return;
            e.preventDefault();

            const form = e.target;
            const button = form.querySelector('button');
            const driveId = form.closest('.carousel-slide').dataset.driveId;
            const originalHTML = button.innerHTML;
            const wasSupported = button.classList.contains('supported');

            // Show loading state
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();

                    // Update button state based on response
                    if (data.is_supporting) {
                        button.classList.add('supported');
                        button.disabled = true;
                        button.innerHTML = '<i class="bi bi-heart-fill"></i> SUPPORTED';
                        if (!supportedIds.includes(parseInt(driveId))) {
                            supportedIds.push(parseInt(driveId));
                        }
                        // Show success feedback
                        showToast(data.message || 'You are now supporting this drive!', 'success');
                    } else {
                        button.classList.remove('supported');
                        button.disabled = false;
                        button.innerHTML = '<i class="bi bi-heart"></i> SUPPORT';
                        const index = supportedIds.indexOf(parseInt(driveId));
                        if (index > -1) supportedIds.splice(index, 1);
                        // Show feedback
                        showToast(data.message || 'Support withdrawn.', 'info');
                    }
                } else {
                    // Error - restore button to previous state
                    button.disabled = wasSupported;
                    button.innerHTML = originalHTML;
                    const errorData = await response.json().catch(() => ({}));
                    showToast(errorData.message ||
                        'Failed to update support status. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error supporting drive:', error);
                button.disabled = wasSupported;
                button.innerHTML = originalHTML;
                showToast('An error occurred. Please try again.', 'error');
            }
        });

        // Simple toast function for feedback
        function showToast(message, type = 'info') {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
                document.body.appendChild(toastContainer);
            }

            const bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
            const toast = document.createElement('div');
            toast.className = 'toast-message';
            toast.style.cssText = `
                background: ${bgColor}; color: white; padding: 12px 20px; 
                border-radius: 8px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: slideIn 0.3s ease;
            `;
            toast.textContent = message;
            toastContainer.appendChild(toast);

            // Auto-remove after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Initialize
        initializeMaps();
        if (allDrives.length > 1) {
            startAutoRotate();
        }
    });
</script>
