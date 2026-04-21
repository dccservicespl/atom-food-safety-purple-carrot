<div>
    @if ($mode === 'read_only' || $mode === 'edit_mode')
    <section class="mb-4">
        <div class="container">

            <?php echo flashMessage() ?>

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
                        @if ($mode === 'read_only')
                        <button type="button" class="btn_2" wire:click="openStartTimePopup">
                            <span><i class="bi bi-clock"></i></span> Start Time
                        </button>
                        <!-- DEBUG: {{ $showStartTimeModal ? 'TRUE' : 'FALSE' }} -->
                        @endif
                        @if ($mode === 'edit_mode')
                        @if($check_start_time->end_time == null)
                        <button type="button" class="btn_3 "> <span><i class="bi bi-clock"></i></span>
                            End Time</button>
                        @endif
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
    @endif


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
    @php
    $is_ended = $check_start_time && $check_start_time->end_time !== null;
    @endphp
    <section class="mb-5">
        <div class="container">
            <div class="" style="">
                <div class="table-card">
                    <div class="table-scroll-wrapper">
                        <table class="{{ $is_ended ? 'component-table disabled' : 'component-table' }}">
                            <thead>
                                <tr>
                                    <th>Letter</th>
                                    <th>Component Details</th>
                                    <th>Label</th>
                                    <th>Weight</th>
                                    <th>Status</th>
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
                                    <td><span class="weight-chip border border-secondary">{{ $data->weight }}</span>
                                    </td>
                                    <td>
                                        <x-status-badge :label="$data->status" />
                                    </td>
                                    <td><span class="qty-value">{{ $data->quantity }}</span></td>
                                    <td class="text-center">
                                        <button wire:click="measurementFormOpen({{ $data->order_detail_id }})"
                                            class="action-btn">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                        {{-- <a href="{{ route('portioning_measurement_form_new') }}"
                                            class="action-btn">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        </a> --}}
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
                                <h2 class="fs-2 color fw-bold">{{
                                    number_format($total_quantity = $portioning_order_data->sum('quantity'))
                                    }}</h2>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card_completed_quantity card-box">
                                <h4 class="fw-bold fs-6">Completed Quantity</h4>
                                <h2 class="fs-2 fw-bold">{{
                                    number_format($complete_quantity =
                                    $portioning_order_data->where('status','Completed')->sum('quantity'))
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
                        @php
                        $total = $portioning_order_data->sum('quantity');
                        $complete = $portioning_order_data->where('status', 'Completed')->sum('quantity');
                        $percentage = $total > 0 ? round(($complete / $total) * 100) : 0;
                        @endphp
                        <x-progress-card :percentage="$percentage" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($showStartTimeModal)
    <div class="modal show d-block" style="background:rgba(0,0,0,0.5); z-index: 9999;" tabindex="-1">
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
                    <?php echo flashMessage() ?>
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
    </div>
    @endif

    @if ($mode === 'measure_form_open')
    <div class="form-card container bg-light p-sm-4 p-3 rounded-3">
        {{-- ── Header ── --}}
        <div class="form-header">
            <h5 class="item-name">{{ $selected_item_name }}</h5>
            <div class="date-block">
                <div class="date-label">
                    <i class="bi bi-clock"></i> Measurement Date
                </div>
                <div class="date-value">{{ $measure_date }}</div>
            </div>
        </div>

        <form wire:submit.prevent="save">
            {{-- @if ($measurement_id)
            <input type="hidden" wire:model="measurement_id" name="measurement_id">
            @endif --}}
            {{-- ── Row 1: Lot Number + Temperature ── --}}
            <div class="row section-gap g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="lot_number">Lot number</label>
                    <input type="text" wire:model.blur="lot_number"
                        class="form-control border @error('lot_number') is-invalid border-danger @enderror"
                        id="lot_number" name="lot_number" placeholder="Enter Lot number" value="">
                    @error('lot_number')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="temperature">Temperature (°F)</label>
                    <input type="text" step="0.01" wire:model.blur="temperature"
                        class="form-control border  @error('temperature') is-invalid border-danger @enderror"
                        id="temperature" placeholder="Enter Temperature">
                    @error('temperature')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- ── Row 2: Allergen Result + Allergen + Pack Size ── --}}
            <div class="row section-gap g-3 align-items-start">
                <div class="col-6">
                    <label class="form-label d-block">Allergen Result</label>
                    <div class="allergen-group">

                        <label class="allergen-option">
                            <input type="radio" name="allergen-option" wire:model="allergen_result" id="aller_yes"
                                value="Yes">
                            <span class="radio-dot"></span>
                            <span>Yes</span>
                        </label>
                        <label class="allergen-option">
                            <input type="radio" name="allergen-option" wire:model="allergen_result" id="aller_no"
                                value="No">
                            <span class="radio-dot"></span>
                            <span>No</span>
                        </label>
                        <label class="allergen-option">
                            <input type="radio" name="allergen-option" wire:model="allergen_result" id="aller_na"
                                value="N/A">
                            <span class="radio-dot"></span>
                            <span>N/A</span>
                        </label>

                    </div>

                    @error('allergen_result')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror

                </div>
                <div class="col-md-6">
                    <label class="form-label" for="allergen">Allergen (if applicable)</label>
                    <input type="text" step="0.01" wire:model.blur="allergen"
                        class="form-control border  @error('allergen') is-invalid border-danger @enderror" id="allergen"
                        placeholder="Enter Allergen">

                    @error('allergen')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="pack_size">Pack Size</label>
                    <input type="text" step="0.01" wire:model.blur="pack_size"
                        class="form-control border @error('pack_size') is-invalid border-danger @enderror"
                        id="pack_size" placeholder="Enter Pack size">

                    @error('pack_size')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror

                </div>
            </div>

            {{-- ── Simple section ── --}}
            <div class="simple-panel section-gap">
                <div class="simple-panel-header">
                    <p class="panel-title">Simple</p>
                    <button type="button" class="btn-add-simple" wire:click="addSample">
                        <i class="bi bi-plus-square-fill"></i> Add Simple
                    </button>
                </div>
                <div class="simple-panel-body">
                    @foreach($simple as $index => $value)
                    <div class="simple-item">
                        <div class="item-badge">{{ $loop->iteration }}</div>
                        <input type="text" wire:model.blur="simple.{{ $index }}" placeholder="Enter Value">
                        <button type="button" class="btn-delete" wire:click="removeSample({{ $index }})" {{
                            count($simple) <=1 ? 'disabled' : '' }}>
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                    @endforeach

                    {{-- Show one input if empty --}}
                    @if(empty($simple))
                    <div class="simple-item">
                        <div class="item-badge">1</div>
                        <input type="text" wire:model.blur="simple.0" placeholder="Enter Value">
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── Row 3: Kit Letter + QTY + Fs Initial ── --}}
            <div class="row section-gap g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="kit_letter">Kit Letter</label>
                    <input type="text" wire:model.blur="kit_letter"
                        class="form-control border @error('kit_letter') is-invalid border-danger @enderror"
                        id="kit_letter" placeholder="Enter Kit letter">

                    @error('kit_letter')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label" for="qty_produces_final">QTY produces (Final)</label>
                    <input type="text" wire:model.blur="qty_produces_final"
                        class="form-control  border @error('qty_produces_final') is-invalid border-danger @enderror"
                        id="qty_produces_final" placeholder="Enter Qty produces final">
                    @error('qty_produces_final')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="fs_initial">Fs Initial</label>
                    <input type="text" class="form-control" wire:model.blur="fs_initial"
                        class="form-control  @error('fs_initial') is-invalid border-danger @enderror" id="fs_initial"
                        placeholder="Enter Fs Initial">

                    @error('fs_initial')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="section-gap">
                <label class="form-label">Upload Attachment</label>
                <div class="d-flex flex-wrap gap-3 align-items-start">

                    {{-- Upload zone --}}
                    <div class="upload-zone" onclick="$('#attachmentInput').trigger('click')">
                        <div class="upload-icon">
                            <i class="bi bi-camera"></i>
                        </div>
                        <h6>Upload Attachment</h6>
                        <p>Click to Open Camera</p>
                        <button type="button" class="btn-camera" title="Click to upload image">
                            <i class="bi bi-camera-fill"></i> Open Camera
                        </button>
                    </div>

                    <input type="file" id="attachmentInput" class="d-none" accept="image/*" capture="environment"
                        wire:model="attachment">

                    {{-- Preview grid --}}
                    <div class="d-flex flex-wrap gap-3" id="previewGrid">
                        {{-- New attachment preview --}}
                        @if($attachmentPreview)
                        <div class="image-preview-wrap">
                            <div class="image-preview">
                                <img src="{{ $attachmentPreview }}" alt="preview" class="img-fluid">
                            </div>
                            <button type="button" class="btn-img-delete" title="Remove" wire:click="removeAttachment">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @endif

                        {{-- Existing attachment preview --}}
                        @if($existingAttachment)
                        <div class="image-preview-wrap">
                            <div class="image-preview">
                                <img src="{{ asset('storage/' . $existingAttachment) }}" alt="existing"
                                    class="img-fluid">
                            </div>
                            <button type="button" class="btn-img-delete" title="Remove"
                                wire:click="removeExistingAttachment">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @endif
                    </div>

                </div>

                @error('attachment')
                <span class="text-danger d-block mt-2">{{ $message }}</span>
                @enderror
            </div>
            {{-- ── Description ── --}}
            <div class="section-gap">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" wire:model.blur="description"
                    class="form-control border @error('description') is-invalid border-danger @enderror"
                    id="description" rows="3" placeholder="Enter description…"></textarea>
                @error('description')
                <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                    {{ $message }}</span>
                @enderror
            </div>

            {{-- ── Footer buttons ── --}}
            <div class="form-footer  pb-5 justify-content-end">
                <button type="button" class="btn-cancel" wire:click="cancel">Cancel</button>
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>
    @endif


    {{-- <script>
        document.getElementById('attachment').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const grid = document.getElementById('previewGrid');

            const existing = grid.querySelector('.image-preview img');
            if (existing) {
                existing.src = e.target.result;
                return;
            }

            const wrap = document.createElement('div');
            wrap.className = 'image-preview-wrap';
            wrap.innerHTML = `
                <div class="image-preview">
                    <img src="${e.target.result}" alt="preview" class="img-fluid">
                </div>
                <button type="button" class="btn-img-delete" title="Remove">
                    <i class="bi bi-x"></i>
                </button>
            `;

            wrap.querySelector('.btn-img-delete').addEventListener('click', function () {
                wrap.remove();
                document.getElementById('attachment').value = '';
            });

            grid.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
    </script> --}}

</div>