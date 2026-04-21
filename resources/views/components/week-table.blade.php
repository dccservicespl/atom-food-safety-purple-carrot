@props([
'week_days' => [],
'days_with_categories' => [],
'selected_day' => null,
'order_head' => null,
'route_name' => 'order_measure_details',
])

@php
// Check if this week is current or past
$week_from = \Carbon\Carbon::parse($order_head->from_date);
$week_to = \Carbon\Carbon::parse($order_head->to_date);
$today = now()->startOfDay();
$is_past_week = $week_to->lt($today);
$is_current_week = $today->between($week_from, $week_to);
@endphp
<style>
    /* .th-inactive {
        pointer-events: none;
        opacity: 0.8;
    } */

     .week-table thead th.th-active {
        background: #0a4d78;
        color: #ffffff;
        width: auto;
    }
</style>
<div>
    <section class="px-lg-5 px-3 pb-5">
        <div class="section-title">Weekly Work Details</div>

        <div class="tbl-scroll mb-5">
            <table class="week-table {{ $is_past_week ? 'disabled-click' : '' }}">
                <thead>
                    <tr>
                        @foreach ($week_days as $day)
                        @php
                        $is_today = \Carbon\Carbon::parse($day)->format('l') === now()->format('l');
                        $is_selected = $selected_day === $day;
                        @endphp

                        <th wire:click="selectDay('{{ $day }}')"
                            class="{{ $is_selected ? 'th-active' : 'th-inactive' }}" style="cursor:pointer;">
                            {{ \Carbon\Carbon::parse($day)->format('l') }}
                        </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        @foreach ($week_days as $day)
                        @php
                        $is_selected = $selected_day === $day;
                        // dd($days_with_categories[$day])
                        @endphp

                        <td class="{{ $is_selected ? 'td-active' : 'td-inactive' }}">
                            <div class="cell-inner">
                                @forelse ($days_with_categories[$day] ?? [] as $category)
                                @php
                                $status_badge = status_config($category['status'] ?? 'Not Started');
                                @endphp

                                @if ($is_selected && !$is_past_week)
                                <a href="{{ route($route_name, ['order_head_id' => $order_head->order_head_id, 'portioning_category_id' => $category['category_id']]) }}"
                                    class="chip"
                                    style="background:{{ $status_badge['bg'] }};border:1px solid {{ $status_badge['border'] }};color:{{ $status_badge['color'] }};">
                                    {{ $category['category_name'] }}
                                </a>
                                @elseif ($is_past_week)
                                <a href="{{ route($route_name, ['order_head_id' => $order_head->order_head_id, 'portioning_category_id' => $category['category_id']]) }}"
                                    class="chip"
                                    style="background:#f0f0f0;border:1px solid #cccccc;color:#000;cursor:pointer;opacity:0.6;">
                                    {{ $category['category_name'] }}
                                </a>
                                @else
                                <span class="chip"
                                    style="background:#f0f0f0;border:1px solid #cccccc;color:#000;cursor:default;opacity:0.6;">
                                    {{ $category['category_name'] }}
                                </span>
                                @endif
                                @empty
                                {{-- No categories for this day --}}
                                @endforelse
                            </div>
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="mb-5 d-flex gap-4 justify-content-center flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <div style="background:#E8F8FF;border:1px solid #016B9D;width:30px;height:30px;border-radius:8px"></div>
                <p class="mb-0">Not Started</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div style="background:#FFF9BC;border:1px solid #7A7000;width:30px;height:30px;border-radius:8px"></div>
                <p class="mb-0">In Process</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div style="background:#CAFFB8;border:1px solid #208200;width:30px;height:30px;border-radius:8px"></div>
                <p class="mb-0">Completed</p>
            </div>
        </div>
    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var th_elements = document.querySelectorAll('.th-inactive');
        th_elements.forEach(function(th) {
            th.style.pointerEvents = 'none';
        });
    });
</script>