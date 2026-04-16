<div>
    <?php echo flashMessage()?>

    <section class="mb-4">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-md-4 gap-2 mb-3">
                <div class="d-flex gap-1 align-items-center">
                    <h4 class="fs-6">Production Schedule:</h4>
                    <p class="fw-bold color">{{ $portioning_category_name}}</p>
                </div>
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center gap-2 text-color"><span><i
                                    class="bi bi-calendar2-minus"></i></span>
                            <p>{{ date('D - d M', strtotime(date('Y-m-d'))) }}</p>
                        </div>
                        {{-- <button type="button" class="btn_2" wire:click='openStartTimePopup'>
                            <span><i class="bi bi-clock"></i></span> Start Time</button> --}}
                        @if ($mode === 'read_only')
                        <button type="button" class="btn_2" wire:click="openStartTimePopup">
                            <span><i class="bi bi-clock"></i></span> Start Time
                        </button>
                        @endif
                        @if ($mode === 'edit_mode')
                        <button type="button" class="btn_3 "> <span><i class="bi bi-clock"></i></span>
                            End Time</button>
                        @endif


                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="color fs-6 fw-medium">
                    <span>Start Time: </span>
                    <span>
                        {{ $check_start_time && $check_start_time->start_time
                        ? date('h:i a', strtotime($check_start_time->start_time))
                        : "N/A" }}
                    </span>
                </div>
                <div class="vr"></div>
                <div class="color fs-6 fw-medium">
                    <span>End Time: </span>
                    <span>
                        {{ $check_start_time && $check_start_time->end_time
                        ? date('h:i a', strtotime($check_start_time->end_time))
                        : "N/A" }}
                    </span>
                </div>
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
                                @forelse ($portioning_order_data as $read_only_data)
                                <tr>
                                    <td><span class="letter-badge">{{ $read_only_data->letter }}</span></td>
                                    <td>{{ $read_only_data->component_details }}</td>
                                    <td>{{ $read_only_data->label }}</td>
                                    <td><span class="weight-chip border border-secondary">{{ $read_only_data->weight
                                            }}</span></td>
                                    <td><span class="qty-value">{{ $read_only_data->quantity }}</span></td>
                                    <td class="text-center">
                                        <a href="#" class="action-btn" @disabled(true)>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <x-no-data-found />
                                @endforelse
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
                                <h2 class="fs-2 color fw-bold">{{ number_format($read_only_data->sum('quantity'))
                                    }}</h2>
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
                        <table class="component-table">
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
                                @forelse ($portioning_order_data as $data)
                                <tr>
                                    <td><span class="letter-badge">{{ $data->letter }}</span></td>
                                    <td>{{ $data->component_details }}</td>
                                    <td>{{ $data->label }}</td>
                                    <td><span class="weight-chip">{{ $data->weight }}</span></td>
                                    <td><span class="qty-value">{{ $data->quantity }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('portioning_measurement_form_new') }}" class="action-btn">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <x-no-data-found />
                                @endforelse

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
                                <h2 class="fs-2 color fw-bold">{{ number_format($portioning_order_data->sum('quantity'))
                                    }}</h2>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card_completed_quantity card-box">
                                <h4 class="fw-bold fs-6">Completed Quantity</h4>
                                <h2 class="fs-2 fw-bold">{{
                                    number_format($portioning_order_data->where('status','Completed')->sum('quantity'))
                                    }}</h2>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card_pending_quantity card-box">
                                <h4 class="fw-bold fs-6">Pending Quantity</h4>
                                <h2 class="fs-2 fw-bold">{{ number_format($portioning_order_data->where('status', '!=',
                                    'Completed')->sum('quantity')) }}</h2>
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
                                <input type="radio" name="preop" id="preop_yes" value="1" wire:model="preop">
                                <label for="preop_yes">
                                    <span class="radio-circle"><span></span></span>Yes
                                </label>
                            </div>
                            <div class="radio-pill">
                                <input type="radio" name="preop" id="preop_no" value="0" wire:model="preop">
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