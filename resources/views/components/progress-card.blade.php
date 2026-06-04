@props(['percentage' => 0])

<div class="progress-card" style="--progress: {{ round((int) $percentage) }}">
    @php
        $p = (int) $percentage;
        $status = match(true) {
            $p <= 30 => 'red',
            $p < 75 => 'yellow',
            default => 'green'
        };
    @endphp

    <div class="circle-wrap {{ $status }}">
        <svg viewBox="0 0 80 80">
            <circle class="track" cx="40" cy="40" r="35" />
            <circle class="progress-ring" cx="40" cy="40" r="35" />
        </svg>
        <div class="circle-inner"></div>
    </div>
    <div class="progress-label">Production<br>Completed</div>
</div>