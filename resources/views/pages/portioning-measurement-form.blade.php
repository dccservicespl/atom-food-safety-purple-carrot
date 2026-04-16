@extends('layouts.main')
@section('content')
    <!-- HTML CONTENT -->

    <div class="form-card container">
        {{-- ── Header ── --}}
        <div class="form-header">
            <h5 class="item-name">Cornstarch, bulk portioned, 1/4 cup</h5>
            <div class="date-block">
                <div class="date-label">
                    <i class="bi bi-clock"></i> Measurement Date
                </div>
                <div class="date-value">03/09/2026</div>
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
                        class="form-control border @error('lot_number') is-invalid border-danger @enderror" id="lot_number"
                        name="lot_number" placeholder="Enter Lot number" value="">
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
                    <input type="text"step="0.01" wire:model.blur="allergen"
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
                        class="form-control border @error('pack_size') is-invalid border-danger @enderror" id="pack_size"
                        placeholder="Enter Pack size">

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
                <div class="simple-panel-body" id="simpleItems">

                    {{-- this will add when add simple btn will click add this div in loop  --}}
                    <div class="simple-item">
                        <div class="item-badge">1</div>
                        <input type="text" name="simple[]" placeholder="Enter Value">
                        <button type="button" class="btn-delete" title="Remove">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                    {{-- this will add when add simple btn will click  --}}
                    <div class="simple-item">
                        <div class="item-badge">2</div>
                        <input type="text" name="simple[]" placeholder="Enter Value">
                        <button type="button" class="btn-delete" title="Remove">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>

                    <div class="col-12">
                        <p class="text-muted">No samples added yet</p>
                    </div>
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
                    <div class="upload-zone">
                        <input type="file" id="attachment" class="d-none" accept="image/*" capture="environment">
                        <div class="upload-icon">
                            <i class="bi bi-camera"></i>
                        </div>
                        <h6>Upload Attachment</h6>
                        <p>Click to Open Camera</p>
                        <button type="button" onclick="document.getElementById('attachment').click()" class="btn-camera"
                            title="Click to upload image">
                            <i class="bi bi-camera-fill"></i> Open Camera
                        </button>
                    </div>

                    {{-- Preview grid --}}
                    <div class="d-flex flex-wrap gap-3" id="previewGrid">
                        {{-- JS will inject previews here --}}
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
                    class="form-control border @error('description') is-invalid border-danger @enderror" id="description"
                    rows="3" placeholder="Enter description…"></textarea>
                @error('description')
                    <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                        {{ $message }}</span>
                @enderror
            </div>

            {{-- ── Footer buttons ── --}}
            <div class="form-footer mb-5 pb-5">
                <button type="button" class="btn-cancel" wire:click="cancel">Cancel</button>
                <button type="submit" class="btn-save">Save</button>
            </div>

        </form>
    </div>





@section('scripts')
    <script>

    document.getElementById('attachment').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const grid = document.getElementById('previewGrid');

            // ── If preview exists, just replace the src ──
            const existing = grid.querySelector('.image-preview img');
            if (existing) {
                existing.src = e.target.result;
                return;
            }

            // ── First time: create the preview card ──
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

    </script>
    <!-- Write Script Here -->
@endsection
@endsection
