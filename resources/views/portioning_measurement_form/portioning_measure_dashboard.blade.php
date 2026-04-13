@extends('layouts.main')
@section('content')
<x-breadcrumb-component :get_route="$get_route" :back_route="$get_route" page_title="Dashboard" :breadcrumb_links="[['name' => 'Home', 'route' => $get_route], ['name' => 'Dashboard', 'route' => '']]"/>
<section class="year_selection mt-5">
    <div class="container">
        <div class="row justify-content-between align-items-center ">
            <div class="col-md-5 col-6 mb-3">
                <label for="" class="color mb-2">Select Year</label>
                <select name="" id="" class="form-select form-select-lg fs-6 shadow-none selected">
                    <option value="">Select Year</option>
                    <option value="">2024</option>
                    <option value="">2025</option>
                    <option value="">2026</option>
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
        <div>
            <div class="month-scroll">
                <div class="month_box">
                   <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">January</p>
                </div>
                <div class="month_box active">
                  <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">February</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">March</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">April</p>
                </div>
                <div class="month_box">
                   <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">May</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">June</p>
                </div>
                <div class="month_box">
                   <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">July</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">August</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">September</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">October</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">November</p>
                </div>
                <div class="month_box">
                    <i class="bi bi-calendar2-minus"></i>
                    <p class="fw-medium">December</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- main layout -->
<main class="week_process my-5 pb-5">
    <div class="container">
        <div class="row">



            <div class="mb-4 d-flex gap-3 col-md-6">


                <div class="week_card completed justify-content-betwee position-relative w-100">
                    <span class="badge completed">Completed</span>
                    <div class="week_details d-flex justify-content-between">
                        <h4 class="mb-5">Week 1</h4>
                        <div class="text-end mb-5">
                            <p class="mb-2">2 Feb to 7 Feb </p>
                            <small class="d-flex flex-column"><span class="fw-bold">Upload Date</span><span>1 Feb -
                                    Sunday</span>
                            </small>
                        </div>

                    </div>

                    <div class="date_time d-flex justify-content-between">
                        <div>
                            <h3 class="fw-bold mb-2">Total Quantity</h3>
                            <h2 class="fw-bold fs-1">1345</h2>
                        </div>

                        <div class="text-end text-end d-flex justify-content-end"><a href="#"
                                class="btn_arrow completed"><i class="bi bi-arrow-right"></i></a></div>
                    </div>

                </div>

            </div>


            <div class="mb-4 d-flex gap-3 col-md-6">


                <div class="week_card present justify-content-betwee position-relative w-100">
                    <span class="badge present"><span>Present Day:</span> <span>Monday - 9 Mar</span> </span>
                    <div class="week_details d-flex justify-content-between">
                        <h4 class="mb-5">Week 2</h4>
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

                        <div class="text-end text-end d-flex justify-content-end"><a href="{{route('week_details','16')}}"
                                class="btn_arrow present"><i class="bi bi-arrow-right"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>


@section('scripts')
@endsection
@endsection
