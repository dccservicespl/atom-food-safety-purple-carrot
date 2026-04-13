@props(['weeks_of_month' => [], 'current_week' => null, 'order_header'])
<div>
    <div class="row">
        {{-- @foreach ($weeks_of_month as $week)
        <div class="mb-4 d-flex gap-3 col-md-6">
            <div class="week_card present justify-content-betwee position-relative w-100">
                <span class="badge present"><span>Present Day:</span> <span>Monday - 9 Mar</span> </span>
                <div class="week_details d-flex justify-content-between">
                    <h4 class="mb-5">Week - {{ $loop->iteration }} </h4>
                    <div class="text-end mb-5">
                        <p class="mb-2">9 Feb to 14 Feb </p>
                        <small class="d-flex flex-column"><span class="fw-bold">Upload Date</span><span>1 Feb -
                                Sunday</span>
                        </small>
                    </div>
                </div>

                <div class="date_time d-flex justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-2">Total Quantity</h3>
                        <h2 class="fw-bold fs-1">42,129</h2>
                    </div>

                    <div class="text-end text-end d-flex justify-content-end"><a href="{{route('week_details', $week)}}"
                            class="btn_arrow present"><i class="bi bi-arrow-right"></i></a></div>
                </div>
            </div>
        </div>
        @endforeach --}}

        @forelse($order_header as $week)
        <div class="mb-4 d-flex gap-3 col-md-6">
            <div class="week_card present justify-content-betwee position-relative w-100">
                <span class="badge present"><span>Present Day:</span> <span>Monday - 9 Mar</span> </span>
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
                        <h2 class="fw-bold fs-1">42,129</h2>
                    </div>

                    <div class="text-end text-end d-flex justify-content-end"><a href="{{route('week_details', $week)}}"
                            class="btn_arrow present"><i class="bi bi-arrow-right"></i></a></div>
                </div>
            </div>
        </div>
        @empty
        <div class="d-flex flex-column align-items-center gap-3 mt-5">
            <img src="{{ asset('assets/images/no_data.png') }}" alt="No Data" class="no_data_img">
            <h3 class="text-center text-danger">No data available for the selected month and year.</h3>
        </div>
        @endforelse

    </div>
</div>
