@props(['month', 'selected_month'])
<div>
    <div class="month-scroll">
        @foreach ($month as $key => $months)
        <div class="month_box {{ $selected_month == $months[0] ? 'active' : '' }}"
            wire:click="monthSelected({{ $months[0] }})">
            <i class="bi bi-calendar2-minus"></i>
            <p class="fw-medium">{{ $months[1] }}</p>
        </div>
        @endforeach
    </div>
</div>
