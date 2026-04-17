@props(['label', 'badge_type'])
<div>
    @switch($label)
    @case('In Process')
    <span class="border border-warning bg-warning-subtle p-1 text-warning rounded-pill fs-9">{{ $label }}</span>
    @break
    @case('Completed')
    <span class="border border-success bg-success-subtle p-1 text-success rounded-pill fs-9">{{ $label }}</span>
    @break
    @case('Not Started')
    <span class="border border-danger bg-danger-subtle text-danger p-1 rounded-pill fs-9">{{ $label }}</span>
    @break
    @case('Working')
    <span class="border border-primary bg-primary-subtle text-primary p-1 rounded-pill fs-9">{{ $label }}</span>
    @break
    @default
    <span class="border border-secondary bg-seconder-subtle p-1 rounded-pill fs-9">{{ $label }}</span>
    @endswitch
</div>
