<div>
    <div class="d-flex flex-column align-items-center gap-3 mt-5">
        {{-- <img src="{{ asset('assets/images/no_data.png') }}" alt="No Data" class="no_data_img"> --}}
        <svg class="mb-4" style="width:160px" viewBox="0 0 200 180" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Shadow -->
            <ellipse cx="100" cy="168" rx="55" ry="8" fill="#e2e8f0" />
            <!-- Sheet base -->
            <rect x="40" y="20" width="120" height="140" rx="8" fill="#ffffff" stroke="#cbd5e1" stroke-width="2" />
            <!-- Header bar -->
            <rect x="40" y="20" width="120" height="28" rx="8" fill="#e0e7ff" />
            <rect x="40" y="36" width="120" height="12" fill="#e0e7ff" />
            <!-- Lines -->
            <rect x="56" y="64" width="88" height="8" rx="3" fill="#e2e8f0" />
            <rect x="56" y="80" width="64" height="8" rx="3" fill="#e2e8f0" />
            <rect x="56" y="96" width="76" height="8" rx="3" fill="#e2e8f0" />
            <rect x="56" y="112" width="50" height="8" rx="3" fill="#e2e8f0" />
            <!-- Magnifier -->
            <circle cx="138" cy="118" r="22" fill="#f1f5f9" stroke="#94a3b8" stroke-width="2.5" />
            <circle cx="138" cy="118" r="13" fill="#ffffff" stroke="#cbd5e1" stroke-width="2" />
            <line x1="154" y1="134" x2="166" y2="148" stroke="#94a3b8" stroke-width="3" stroke-linecap="round" />
            <!-- X inside magnifier -->
            <line x1="132" y1="112" x2="144" y2="124" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" />
            <line x1="144" y1="112" x2="132" y2="124" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" />
        </svg>
        <h3 class="text-center text-danger fs-5 text-muted">No data available for the selected month and year.</h3>
    </div>
</div>
