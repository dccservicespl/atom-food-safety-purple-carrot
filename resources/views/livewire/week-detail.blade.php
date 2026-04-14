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
</div>