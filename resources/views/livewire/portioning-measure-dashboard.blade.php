<div>
    <section class="year_selection mt-5">
        <div class="container">
            <div class="row justify-content-between align-items-center">

                <div class="col-md-5 col-6 mb-3">
                    <label class="color mb-2">Select Year</label>
                    <select wire:model.live="selected_year"
                        class="form-select form-select-lg fs-6 shadow-none selected">
                        @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-6 text-end">
                    <a href="{{ route('portioning_measure_data_upload') }}"
                        class="btn_1 px-3 mx-auto position-relative">Upload Sheet</a>
                </div>

            </div>
        </div>
    </section>

    <section class=" month mt-3">
        <div class="">
            <label for="" class="color container d-block mb-2">Select Month</label>
            <div class="">
                <x-month-card :month="$all_month" :selected_month="$selected_month" />
            </div>
        </div>
    </section>

    <!-- main layout -->
    <main class="week_process my-5 pb-5">
        <div class="container">
            <x-week-of-month :order_header="$order_headers" :weeks_of_month="$weeks_of_month"
                :current_week="$current_week" />
        </div>
    </main>
</div>
