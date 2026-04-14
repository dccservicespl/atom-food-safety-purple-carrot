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
    <x-week-table
        :week_days="$week_days"
        :days_with_categories="$days_with_categories"
        :selected_day="$selected_day"
        :order_head="$order_head"
        route_name="order_measure_details" />

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