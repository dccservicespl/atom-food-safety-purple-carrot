@props(['weeks_of_month' => [], 'current_week' => null, 'order_header'])
<div>
    <div class="row">
        @forelse($order_header as $week)
        <div class="mb-4 d-flex gap-3 col-md-6">
            <div class="week_card {{ $week->status == 'Not Started'?" present ":"
                completed " }} justify-content-betwee position-relative w-100">
                @if ($week->status === "Not Started")
                <span class="badge present"><span>Present Day:</span> <span>{{ date('d M', strtotime($week->from_date)) }}</span> </span>
                @else
                <span class="badge completed"><span>Completed</span></span>
                @endif
                <div class="week_details d-flex justify-content-between">
                    <h4 class="mb-5">Week - {{ $loop->iteration }} </h4>
                    <div class="text-end mb-5">
                        <p class="mb-2"> {{ date('d M', strtotime($week->from_date))." to ". date('d M',
                            strtotime($week->to_date)) }}</p>
                        <small class="d-flex flex-column"><span class="fw-bold">Upload Date</span><span> {{ date('d M
                                D', strtotime($week->created_at)) }}</span>
                        </small>
                    </div>
                </div>

                <div class="date_time d-flex justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-2">Total Quantity</h3>
                        <h2 class="fw-bold fs-1">{{ number_format($week->total_qty, 0) }}</h2>
                    </div>

                    <div class="text-end text-end d-flex justify-content-end"><a
                            href="{{route('week_details', [$week->week_number, $week->order_head_id])}}"
                            class="btn_arrow present"><i class="bi bi-arrow-right"></i></a></div>
                </div>
            </div>
        </div>
        @empty
        <x-no-data-found label="No data available for the selected month and year." />
        @endforelse

    </div>
</div>
