@extends('layouts.main')
@section('content')

<x-breadcrumb-component :get_route="route('week_details',[(int) \Carbon\Carbon::now()->isoWeek(), $order_head_id])"
    :back_route="route('week_details',[(int) \Carbon\Carbon::now()->isoWeek(), $order_head_id])"
    page_title="Day Details"
    :breadcrumb_links="[['name' => 'Home', 'route' => $get_route],['name' => 'Dashboard', 'route' => route('portioning_measure_dashboard')], ['name' => 'Days Plan', 'route' => route('week_details',[(int) \Carbon\Carbon::now()->isoWeek(), $order_head_id])], ['name' => 'Day Details', 'route' =>'']]" />


<livewire:portioning-measure-form :order_head_id="$order_head_id" :portioning_category_id="$portioning_category_id" />
{{--
<section class="mb-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-md-4 gap-2 mb-3">
            <div class="d-flex gap-1 align-items-center">
                <h4 class="fs-6">Production Schedule:</h4>
                <p class="fw-bold color">Piston 1200</p>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center gap-2 text-color"><span><i
                                class="bi bi-calendar2-minus"></i></span>
                        <p>Monday - 9 March</p>
                    </div>
                    <button type="button" class="btn_2" data-bs-toggle="modal" data-bs-target="#measurementModal">
                        <span><i class="bi bi-clock"></i></span> Start Time</button>
                    <button type="button" class="btn_3 "> <span><i class="bi bi-clock"></i></span>
                        End Time</button>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="color fs-6 fw-medium"><span>Start Time:</span><span>05:41 am</span></div>
            <div class="vr"></div>
            <div class="color fs-6 fw-medium"><span>Start Time:</span><span>05:41 am</span></div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="container">
        <div class="" style="">
            <div class="table-card">
                <div class="table-scroll-wrapper">
                    <table class="component-table disabled">
                        <thead>
                            <tr>
                                <th>Letter</th>
                                <th>Component Details</th>
                                <th>Label</th>
                                <th>Weight</th>
                                <th>QTY</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="letter-badge">DC/DD/DH/DL</span></td>
                                <td>Vegan Mayo bulk</td>
                                <td>Vegan Mayo</td>
                                <td><span class="weight-chip">2 oz</span></td>
                                <td><span class="qty-value">1678</span></td>
                                <td class="text-center"><a href="#" class="action-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a>
                                </td>
                            </tr>
                            <tr class="">
                                <td><span class="letter-badge">DJ</span></td>
                                <td>Coconut milk, bulk portioned, 6 fl oz</td>
                                <td>Coconut milk Allergen tree nuts (coconut)</td>
                                <td><span class="weight-chip">6 fl oz</span></td>
                                <td><span class="qty-value">1203</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">DQ</span></td>
                                <td>Coconut milk, bulk portioned, 6 fl oz</td>
                                <td>Coconut milk Allergen tree nuts (coconut)</td>
                                <td><span class="weight-chip">6 fl oz</span></td>
                                <td><span class="qty-value">1073</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">DC</span></td>
                                <td>Bbq sauce, bulk portioned, 0.25 cup</td>
                                <td>BBQ sauce</td>
                                <td><span class="weight-chip">68 g</span></td>
                                <td><span class="qty-value">334</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">LB</span></td>
                                <td>Bbq sauce, bulk portioned, 0.25 cup</td>
                                <td>BBQ sauce</td>
                                <td><span class="weight-chip">68 g</span></td>
                                <td><span class="qty-value">94</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">DN</span></td>
                                <td>Chili garlic sauce, bulk portioned, 2 tbsp</td>
                                <td>Chili garlic sauce</td>
                                <td><span class="weight-chip">36 g</span></td>
                                <td><span class="qty-value">2759</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">LD</span></td>
                                <td>Chili garlic sauce, bulk portioned, 1 tbsp</td>
                                <td>Chili garlic sauce</td>
                                <td><span class="weight-chip">18 g</span></td>
                                <td><span class="qty-value">158</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">DR</span></td>
                                <td>Chutney, tomato, bulk portioned, 1/4 cup</td>
                                <td>Tomato chutney</td>
                                <td><span class="weight-chip">2 oz</span></td>
                                <td><span class="qty-value">431</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                            <tr>
                                <td><span class="letter-badge">BB</span></td>
                                <td>Preserve, apricot, bulk portioned, 1/4 cup</td>
                                <td>Apricot preserves</td>
                                <td><span class="weight-chip">72 g</span></td>
                                <td><span class="qty-value">149</span></td>
                                <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-4">
                        <div class="card_total_quantity card-box">
                            <h4 class="color fw-bold fs-6">Total Quantity</h4>
                            <h2 class="fs-2 color fw-bold">7,879</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card_completed_quantity card-box">
                            <h4 class="fw-bold fs-6">Completed Quantity</h4>
                            <h2 class="fs-2 fw-bold">1,000</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card_pending_quantity card-box">
                            <h4 class="fw-bold fs-6">Pending Quantity</h4>
                            <h2 class="fs-2 fw-bold">6,879</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="text-end">
                    <div class="progress-card" style="--progress: 80">
                        <div class="circle-wrap">
                            <svg viewBox="0 0 80 80">
                                <circle class="track" cx="40" cy="40" r="35" />
                                <circle class="progress-ring" cx="40" cy="40" r="35" />
                            </svg>
                            <div class="circle-inner"></div>
                        </div>
                        <div class="progress-label">Production<br>Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="measurementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header d-flex justify-content-between align-items-center">
                <div class="modal-title">
                    <i class="bi bi-clock"></i> Start Measurement
                </div>
                <button class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">&#x2715;</button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <!-- Table Section -->
                <div class="section-label">Table</div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <div class="radio-pill"><input type="radio" name="table" id="t1" value="1"><label for="t1"><span
                                class="radio-circle"><span></span></span>1</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t2" value="2"><label for="t2"><span
                                class="radio-circle"><span></span></span>2</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t3" value="3"><label for="t3"><span
                                class="radio-circle"><span></span></span>3</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t4" value="4"><label for="t4"><span
                                class="radio-circle"><span></span></span>4</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t5" value="5"><label for="t5"><span
                                class="radio-circle"><span></span></span>5</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t6" value="6"><label for="t6"><span
                                class="radio-circle"><span></span></span>6</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t7" value="7"><label for="t7"><span
                                class="radio-circle"><span></span></span>7</label></div>
                    <div class="radio-pill"><input type="radio" name="table" id="t8" value="8"><label for="t8"><span
                                class="radio-circle"><span></span></span>8</label></div>
                </div>

                <hr class="section-divider" />

                <!-- Pre-Op Complete -->
                <div class="section-label">Pre-Op Complete</div>
                <div class="d-flex gap-3 mb-2">
                    <div class="radio-pill"><input type="radio" name="preop" id="yes" value="yes"><label for="yes"><span
                                class="radio-circle"><span></span></span>Yes</label></div>
                    <div class="radio-pill"><input type="radio" name="preop" id="no" value="no"><label for="no"><span
                                class="radio-circle"><span></span></span>No</label></div>
                </div>

                <hr class="section-divider" />

                <!-- People Qty & Scale -->
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">People Qty</label>
                        <input type="number" class="form-control" placeholder="Enter People Qty" />
                    </div>
                    <div class="col-6">
                        <label class="form-label">Scale #</label>
                        <input type="text" class="form-control" placeholder="Enter Scale" />
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex">
                <button class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-start">Start Measurement</button>
            </div>

        </div>
    </div>
</div> --}}

@section('scripts')
<!-- Write Script Here -->
@endsection

@endsection