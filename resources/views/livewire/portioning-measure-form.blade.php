<div>
    <?php echo flashMessage()?>
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
                        {{-- <button type="button" class="btn_2" wire:click='openStartTimePopup'>
                            <span><i class="bi bi-clock"></i></span> Start Time</button> --}}
                        <button type="button" class="btn_2" wire:click="openStartTimePopup">
                            <span><i class="bi bi-clock"></i></span> Start Time
                        </button>
                        <button type="button" class="btn_3 "> <span><i class="bi bi-clock"></i></span>
                            End Time</button>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="color fs-6 fw-medium"><span>Start Time:</span><span>05:41 am</span></div>
                <div class="vr"></div>
                <div class="color fs-6 fw-medium"><span>End Time:</span><span>05:41 am</span></div>
            </div>
        </div>
    </section>

    @if ($mode === "read_only")
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
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($mode === 'edit_mode')
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
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
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
    @endif


    @if ($showStartTimeModal)
    <div class="modal fade show d-block" style="background:rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header d-flex justify-content-between align-items-center">
                    <div class="modal-title">
                        <i class="bi bi-clock"></i> Start Measurement
                    </div>
                    <button wire:click='closeStartTimePopup' class="btn-close-custom" data-bs-dismiss="modal"
                        aria-label="Close">&#x2715;</button>
                </div>

                <div class="modal-body">
                    <?php echo flashMessage()?>
                    <form wire:submit.prevent="startMeasurement">
                        <div class="section-label">Table</div>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach(range(1, 8) as $num)
                            <div class="radio-pill">
                                <input type="radio" name="table" id="t{{ $num }}" value="{{ $num }}" wire:model="table">
                                <label for="t{{ $num }}">
                                    <span class="radio-circle"><span></span></span>
                                    {{ $num }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('table')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <hr class="section-divider" />

                        <div class="section-label">Pre-Op Complete</div>
                        <div class="d-flex gap-3 mb-2">
                            <div class="radio-pill">
                                <input type="radio" name="preop" id="preop_yes" value="yes" wire:model="preop">
                                <label for="preop_yes">
                                    <span class="radio-circle"><span></span></span>Yes
                                </label>
                            </div>
                            <div class="radio-pill">
                                <input type="radio" name="preop" id="preop_no" value="no" wire:model="preop">
                                <label for="preop_no">
                                    <span class="radio-circle"><span></span></span>No
                                </label>
                            </div>
                        </div>
                        @error('preop')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <hr class="section-divider" />

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label" for="people_qty">People Qty</label>
                                <input type="number" id="people_qty"
                                    class="form-control @error('people_qty') is-invalid @enderror"
                                    placeholder="Enter People Qty" wire:model="people_qty" />
                                @error('people_qty')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="scale">Scale #</label>
                                <input type="text" id="scale" class="form-control @error('scale') is-invalid @enderror"
                                    placeholder="Enter Scale" wire:model="scale" />
                                @error('scale')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer d-flex">
                            <button wire:click='closeStartTimePopup' type="button" class="btn-cancel"
                                data-bs-dismiss="modal">Cancel</button>
                            {{-- <button type="submit" class="btn-start">
                                <span wire:loading wire:target="startMeasurement"
                                    class="spinner-border spinner-border-sm me-1"></span>
                                Start Measurement
                            </button> --}}
                            <button type="submit" class="btn-start" wire:loading.attr="disabled"
                                wire:target="startMeasurement">
                                {{-- Normal state: loading এর সময় hide হবে --}}
                                <span wire:loading.remove wire:target="startMeasurement">
                                    Start Measurement
                                </span>

                                {{-- Loading state: submit হলে দেখাবে --}}
                                <span wire:loading wire:target="startMeasurement" style="display:none;">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Starting...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>