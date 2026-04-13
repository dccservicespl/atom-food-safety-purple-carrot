<div>
    <!-- Header Card -->
    <section>
        <div class="header-card container present">
            <div>
                <div class="hc-week-title">Week {{ $order_head->week_number }}</div>
                <div class="hc-week-range">
                    {{ \Carbon\Carbon::parse($order_head->from_date)->format('j M') }}
                    to
                    {{ \Carbon\Carbon::parse($order_head->to_date)->format('j M') }}
                </div>
            </div>

            <div class="hc-divider"></div>

            <div>
                <div class="hc-meta-label">Present Day</div>
                <div class="hc-meta-value">
                    {{ now()->format('l') }} – {{ now()->format('j M') }}
                </div>
            </div>

            <div class="hc-divider"></div>

            <div>
                <div class="hc-meta-label">Upload Date</div>
                <div class="hc-meta-value">
                    {{ \Carbon\Carbon::parse($order_head->created_at)->format('j F – l') }}
                </div>
            </div>

            <div class="hc-qty">
                <div class="hc-qty-label">Total Quantity</div>
                <div class="hc-qty-value">{{ number_format($order_head->total_qty) }}</div>
            </div>
        </div>
    </section>

    <!-- Weekly Table -->
    <section class="px-lg-5 px-3 pb-5">
        <div class="section-title">Weekly Work Details</div>

        <div class="tbl-scroll mb-5">
            <table class="week-table">
                <thead>
                    <tr>
                        @foreach ($week_days as $day)
                        @php
                        $is_today = $day === $today;
                        $is_selected = $selected_day === $day;
                        @endphp

                        <th
                            wire:click="selectDay('{{ $day }}')"
                            class="{{ $is_selected ? 'th-active' : 'th-inactive' }}"
                            style="cursor:pointer;">
                            {{ \Carbon\Carbon::parse($day)->format('l') }}
                            @if ($is_today)
                            <br><small class="today-badge">Today</small>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        @foreach ($week_days as $day)
                        @php
                        $is_today = $day === $today;
                        $is_selected = $selected_day === $day;
                        @endphp

                        <td class="{{ $is_selected ? 'td-active' : 'td-inactive' }}">
                            <div class="cell-inner">
                                @forelse ($days_with_categories[$day] ?? [] as $category)
                                @php
                                $status_badge = status_config($category['status'] ?? 'Not Started');
                                @endphp
                                @if ($is_selected)
                                <a
                                    href="#"
                                    class="chip"
                                    style="background:{{ $status_badge['bg'] }};border:1px solid {{ $status_badge['border'] }};color:{{ $status_badge['color'] }};">
                                    {{ $category['category_name'] }}
                                </a>
                                @else
                                <span
                                    class="chip"
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
    </section>

    <!-- Legend -->
    <section>
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