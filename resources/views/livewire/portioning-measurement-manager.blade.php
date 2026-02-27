<div>
    <?php echo flashMessage(); ?>

    {{-- Item Selection Modal --}}
    @if ($showItemModal)
    <div class="modal d-block" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Item to Add</h5>
                    <button type="button" class="btn-close" wire:click="closeItemModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Filter by Category</label>
                            <select wire:model.live="categorySearch" class="form-control">
                                <option value="">All Categories</option>
                                @foreach ($availableCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Search Item</label>
                            <input type="text" wire:model.live="itemSearch" placeholder="Search by item name..."
                                class="form-control">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th>Item Description</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filteredItems as $item)
                                <tr>
                                    <td>{{ $item->component_details }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>
                                        <button type="button" wire:click="selectItem({{ $item->id }})"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-plus"></i> Select
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No items found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeItemModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- List Mode --}}
    @if ($mode === 'list')
    <div class="page_card bg_white">
        <div class="row">
            <div class="col-8 mt-2">
                <strong class="m-0 text-dark h5">Portioning Measurements</strong>
            </div>
            <div class="col-4 text-end">
                <button wire:click="openItemModal" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Select Item
                </button>
            </div>
        </div>

        {{-- <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label">Filter by Date</label>
                <input type="date" wire:model.live="filter_date" class="form-control">
            </div>
        </div> --}}
    </div>

    <div class="mt-3">
        <div class="card shadow-none p-4 table-container table-responsive">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr class="bg-200">
                        <th class="text-nowrap">Date</th>
                        <th class="text-nowrap">Time</th>
                        <th class="text-nowrap">Item Description</th>
                        {{-- <th class="text-nowrap">Equipment</th> --}}
                        <th class="text-nowrap">Lot Number</th>
                        <th class="text-nowrap">Measured By</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($measurements as $measurement)
                    <tr>
                        <td>{{ $measurement->measure_date->setTimezone('America/Chicago')->format('m/d/Y') }}
                        </td>
                        <td>{{ $measurement->measure_time->setTimezone('America/Chicago')->format('h:i a') }}</td>
                        <td>{{ $measurement->item->component_details ?? '-' }}</td>
                        {{-- <td>{{ $measurement->equipment }}</td> --}}
                        <td>{{ $measurement->lot_number }}</td>
                        <td>{{ $measurement->measuredBy->name ?? '-' }}</td>
                        <td>
                            <button wire:click="edit({{ $measurement->id }})" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-chevron-double-right"></i>
                            </button>
                            {{-- <button wire:click="delete({{ $measurement->id }})"
                                onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button> --}}
                            <button wire:click="delete({{ $measurement->id }})"
                                wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE"
                                class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <p class="p-5 text-200 h3">No items found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Create Edit Mode --}}
    @if ($mode === 'create' || $mode === 'edit')
    <div class="page_card bg_white">
        <div class="row">
            <div class="col-8 mt-2">
                <strong class="m-0 text-dark h5">{{ $item_details->component_details ?? '-' }}</strong>
            </div>
            <div class="col-4 text-end">
                <p class="text-end mb-0 text-dark">
                    <i class="bi bi-calendar-check mr-2 p-1" style="color: #A982DD"></i> Measurement Date
                </p>
                <p class="text-end text-dark mb-0 font-weight-bold bold">
                    <strong>{{ date('m/d/Y') }}</strong>
                </p>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <div class="card shadow-none p-4">
            <form wire:submit.prevent="save">
                @if ($measurement_id)
                <input type="hidden" wire:model="measurement_id" name="measurement_id">
                @endif
                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="lot_number" class="form-label text-dark">Lot number</label>
                        </div>
                        <div class="col-4">
                            <input type="text" wire:model.blur="lot_number"
                                class="form-control p-2 border border-dark @error('lot_number') is-invalid border-danger @enderror"
                                id="lot_number">
                            @error('lot_number')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="temperature" class="form-label text-dark">Temperature (°F)</label>
                        </div>
                        <div class="col-4">
                            <div class="input-group">
                                <input type="text" step="0.01" wire:model.blur="temperature"
                                    class="form-control p-2 border border-dark @error('temperature') is-invalid border-danger @enderror"
                                    id="temperature">
                            </div>
                            @error('temperature')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <div class="row">
                        <div class="col-4"><label class="form-label d-block text-dark">Allergen Result</label></div>
                        <div class="col-4 p-0 text-end">
                            <div class="form-check form-check-inline">
                                <input type="radio" wire:model="allergen_result" id="aller_yes" value="Yes"
                                    class="form__radio-input">
                                <label class="form__label-radio" for="aller_yes">
                                    <span class="form__radio-button"></span> Yes
                                </label>
                            </div>
                        </div>
                        <div class="col-2 p-0 text-end">
                            <div class="form-check form-check-inline">
                                <input type="radio" wire:model="allergen_result" id="aller_no" value="No"
                                    class="form__radio-input">
                                <label class="form__label-radio" for="aller_no">
                                    <span class="form__radio-button"></span> No
                                </label>
                            </div>
                        </div>
                        <div class="col-2 p-0 text-end">
                            <div class="form-check form-check-inline">
                                <input type="radio" wire:model="allergen_result" id="aller_na" value="N/A"
                                    class="form__radio-input">
                                <label class="form__label-radio" for="aller_na">
                                    <span class="form__radio-button"></span> N/A
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            @error('allergen_result')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="allergen" class="form-label text-dark">Allergen (If applicable)</label>
                        </div>
                        <div class="col-4">
                            <input type="text" step="0.01" wire:model.blur="allergen"
                                class="form-control p-2 border border-dark @error('allergen') is-invalid border-danger @enderror"
                                id="allergen">
                            @error('allergen')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="pack_size" class="form-label text-dark">Pack size</label>
                        </div>
                        <div class="col-4">
                            <input type="number" step="0.01" wire:model.blur="pack_size"
                                class="form-control p-2 border border-dark @error('pack_size') is-invalid border-danger @enderror"
                                id="pack_size">
                            @error('pack_size')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-4 col-lg-8">
                            <label class="form-label text-dark">Sample</label>
                        </div>
                        <div class="col-8 col-lg-4">
                            <div class="row">
                                @forelse($samples as $index => $sample)
                                <div class="col-12 mb-3">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div
                                                        class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                                <input type="number" step="0.01"
                                                    wire:model.blur="samples.{{ $index }}.sample_value"
                                                    class="form-control p-2 border border-dark @error("
                                                    samples.{$index}.sample_value") is-invalid border-danger @enderror">
                                            </div>
                                            @error("samples.{$index}.sample_value")
                                            <span class="text-danger d-block mt-2 small"><i
                                                    class="bi bi-exclamation-circle"></i>
                                                {{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-2 ps-2">
                                            @if (count($samples) > 1)
                                            <button type="button" wire:click="removeSample({{ $index }})"
                                                class="btn btn-sm btn-outline-danger w-100">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p class="text-muted">No samples added yet</p>
                                </div>
                                @endforelse

                                <div class="col-12 mt-2">
                                    <button type="button" wire:click="addSample" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-plus-circle"></i> Add Sample
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="kit_letter" class="form-label text-dark">Kit letter</label>
                        </div>
                        <div class="col-4">
                            <input type="text" wire:model.blur="kit_letter"
                                class="form-control p-2 border border-dark @error('kit_letter') is-invalid border-danger @enderror"
                                id="kit_letter">
                            @error('kit_letter')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="qty_produces_final" class="form-label text-dark">Qty produces
                                (Final)</label>
                        </div>
                        <div class="col-4">
                            <input type="text" wire:model.blur="qty_produces_final"
                                class="form-control p-2 border border-dark @error('qty_produces_final') is-invalid border-danger @enderror"
                                id="qty_produces_final">
                            @error('qty_produces_final')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="fs_initial" class="form-label text-dark">FS initial</label>
                        </div>
                        <div class="col-4">
                            <input type="text" wire:model.blur="fs_initial"
                                class="form-control p-2 border border-dark @error('fs_initial') is-invalid border-danger @enderror"
                                id="fs_initial">
                            @error('fs_initial')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-8">
                            <label for="attachment" class="form-label text-dark">Upload Attachment</label>
                        </div>
                        <div class="col-4">
                            <div>
                                <!-- Hidden file input -->
                                <input type="file" wire:model="attachment" id="attachment" class="d-none"
                                    accept="image/*" capture="environment">

                                <!-- Camera Icon Button -->
                                <button type="button" onclick="document.getElementById('attachment').click()"
                                    class="btn btn-lg btn-outline-warning rounded-circle p-3"
                                    title="Click to upload image">
                                    <i class="bi bi-camera-fill" style="font-size: 2rem;padding: 10px 8px;"></i>
                                </button>

                                @error('attachment')
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Image Preview -->
                            @if ($attachment_preview)
                            <div class="mt-4">
                                <div class="position-relative" style="display: inline-block;">
                                    @if (is_string($attachment_preview) &&
                                    (str_ends_with($attachment_preview, '.jpg') ||
                                    str_ends_with($attachment_preview, '.jpeg') ||
                                    str_ends_with($attachment_preview, '.png') ||
                                    str_ends_with($attachment_preview, '.gif')))
                                    <!-- Existing image from database -->
                                    <img src="{{ asset('storage/' . $attachment_preview) }}" alt="Attachment Preview"
                                        class="img-fluid border border-dark rounded"
                                        style="max-width: 200px; max-height: 200px;">
                                    @else
                                    <!-- New uploaded temporary image -->
                                    <img src="{{ $attachment_preview }}" alt="Attachment Preview"
                                        class="img-fluid border border-dark rounded"
                                        style="max-width: 200px; max-height: 200px;">
                                    @endif

                                    <!-- Clear button -->
                                    <button type="button" wire:click="clearAttachment"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                        style="transform: translate(50%, -50%);" title="Remove image">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4">
                    <label for="description" class="form-label text-dark">Description</label>
                    <textarea wire:model.blur="description"
                        class="form-control border border-dark @error('description') is-invalid border-danger @enderror"
                        id="description" rows="3"></textarea>
                    @error('description')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                    @enderror
                </div>

                <div class="float-end mt-5">
                    <button type="button" wire:click="cancel"
                        class="btn btn-outline-secondary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">Cancel</button>
                    <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
