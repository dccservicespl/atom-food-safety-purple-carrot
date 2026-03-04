<div>
    <div class="shadow-none">
        <div class="container-fluid">
            <?php echo flashMessage(); ?>
            @if ($mode === 'list')
            <div class="mt-5 page_card bg_white pb-0 pt-0 mb-0 rounded-0 rounded-top border-bottom border-secondary">
                <div class="row">
                    <div class="col-5">
                        <div class="card_title_color text-dark text-start ps-4" style="font-size: 16px">
                            <a href="javascript:void(0);" style="color: unset;"><span>Purple
                                    Carrot</span></a>
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="display: inline; margin: 0 8px; vertical-align: middle;">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <a style="color: unset;" href="{{ route('work_type') }}"><span>Portioning</span></a>
                            <span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="display: inline; margin: 0 8px; vertical-align: middle;">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <strong>Portioning measure</strong>
                        </div>
                    </div>

                    <div class="col-7 text-end pt-3">
                        @php
                        $user_id = Auth::id();
                        $hasIncomplete = $measurements
                        ->where('measure_by', $user_id)
                        ->whereNull('end_time')
                        ->first();
                        @endphp

                        @if (!$hasIncomplete)
                        <button wire:click="create" class="btn btn-outline-warning pt-2 pb-2 ms-4">
                            <i class="bi bi-clock"></i> Start Time
                        </button>
                        @else
                        <button wire:click="completeMeasure" class="btn btn-outline-danger pt-2 pb-2 ms-4">
                            <i class="bi bi-clock"></i> End Time
                        </button>
                        @endif
                        <a href="{{ route('work_type_item_master') }}" class="btn btn-outline-primary pt-2 pb-2 ms-4">
                            <i class="bi bi-plus"></i> Item Master
                        </a>
                    </div>
                </div>
            </div>
            <div class="page_card mt-0 rounded-0 rounded-bottom">
                <div class="row">
                    <div class="col-12">

                        <div class="table-container">
                            <table class="table measure_date_list">
                                <thead>
                                    <tr style="background-color: #ddd; color: #000">
                                        <th>Date</th>
                                        <th>Start time</th>
                                        <th>End time</th>
                                        <th>Operator</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($measurements as $measurement)
                                    <tr>
                                        <td>{{ $measurement->created_at->format('m/d/Y') }}</td>
                                        <td>{{ $measurement->start_time ? $measurement->start_time->format('h:i a') :
                                            '-' }}
                                        </td>
                                        <td>{{ $measurement->end_time ? $measurement->end_time->format('h:i a') : '-' }}
                                        </td>
                                        <td>{{ $measurement->operator->name ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $measurement->status === 'completed' ? 'text-success badge_completed' : 'bg-warning-subtle border border-warning text-dark' }}">
                                                {{ ucfirst($measurement->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('portioning_measurement_form', ['measure_id' => Crypt::encrypt($measurement->id)]) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-lg">
                                            <p class="p-5 text-200 h3">No items found</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-0">
                                {{-- {{ $measurements->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Start Measurement Modal -->
    @if ($showStartModal)
    <div class="modal d-block"
        style="background-color: rgba(0,0,0,0.5); z-index: 9999; position: fixed; top: 0 !important; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
        <div class="modal-dialog modal-lg" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title">
                        <i class="bi bi-clock"></i> Start Measurement
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeStartModal()"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <form wire:submit="savePortioningHead">
                        <!-- Equipment -->
                        <div class="mb-5 mt-3">
                            <div class="row">
                                <div class="col-12"><label class="form-label d-block text-dark">Equipment</label>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="@error('equipment') p-2 border border-danger rounded @endif">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" wire:model="equipment" id="auger_9" value="Auger 9"
                                                class="form__radio-input @error('equipment') border-danger @enderror">
                                            <label class="form__label-radio" for="auger_9">
                                                <span class="form__radio-button"></span> Auger 9
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" wire:model="equipment" id="auger_12" value="Auger 12"
                                                class="form__radio-input @error('equipment') border-danger @enderror">
                                            <label class="form__label-radio" for="auger_12">
                                                <span class="form__radio-button"></span> Auger 12
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" wire:model="equipment" id="piston_9" value="Piston 9"
                                                class="form__radio-input @error('equipment') border-danger @enderror">
                                            <label class="form__label-radio" for="piston_9">
                                                <span class="form__radio-button"></span> Piston 9
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" wire:model="equipment" id="sleek" value="Sleek"
                                                class="form__radio-input @error('equipment') border-danger @enderror">
                                            <label class="form__label-radio" for="sleek">
                                                <span class="form__radio-button"></span> Sleek
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mt-3">
                                            <input type="radio" wire:model="equipment" id="breeze" value="Breeze"
                                                class="form__radio-input @error('equipment') border-danger @enderror">
                                            <label class="form__label-radio" for="breeze">
                                                <span class="form__radio-button"></span> Breeze
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" wire:model="equipment" id="versa_pack"
                                                value="Versa Pack"
                                                class="form__radio-input @error('equipment') border-danger @enderror">
                                            <label class="form__label-radio" for="versa_pack">
                                                <span class="form__radio-button"></span> Versa Pack
                                            </label>
                                        </div>
                                    </div>
                                    @error('equipment')
                                    <span class="text-danger d-block mt-2 small"><i
                                            class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="mb-5 mt-3">
                            <div class="row">
                                <div class="col-12"><label class="form-label d-block text-dark">Table</label>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="@error('table') p-2 border border-danger rounded @endif">
                                        @for ($i = 1; $i <= 8; $i++) <div class="form-check form-check-inline">
                                            <input type="radio" wire:model="table" id="table_{{ $i }}" value="{{ $i }}"
                                                class="form__radio-input @error('table') border-danger @enderror">
                                            <label class="form__label-radio" for="table_{{ $i }}">
                                                <span class="form__radio-button"></span> {{ $i }}
                                            </label>
                                    </div>
                                    @endfor
                                    <div class="form-check form-check-inline mt-3">
                                        <input type="radio" wire:model="table" id="piston_10" value="Piston 10"
                                            class="form__radio-input @error('table') border-danger @enderror">
                                        <label class="form__label-radio" for="piston_10">
                                            <span class="form__radio-button"></span> Piston 10
                                        </label>
                                    </div>
                                </div>
                                @error('table')
                                <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i> {{
                                    $message }}</span>
                                @enderror
                            </div>
                        </div>
                </div>

                <!-- Pre-Op Complete -->
                <div class="mb-3 mt-3">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label d-block text-dark mb-2">Pre-Op Complete</label>
                            <div class="@error('pre_op_complete') p-2 border border-danger rounded @endif">
                                <div class="form-check form-check-inline mt-3">
                                    <input type="radio" wire:model="pre_op_complete" id="pre_op_yes" value="Yes"
                                        class="form__radio-input @error('pre_op_complete') border-danger @enderror">
                                    <label class="form__label-radio" for="pre_op_yes">
                                        <span class="form__radio-button"></span> Yes
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" wire:model="pre_op_complete" id="pre_op_no" value="No"
                                        class="form__radio-input @error('pre_op_complete') border-danger @enderror">
                                    <label class="form__label-radio" for="pre_op_no">
                                        <span class="form__radio-button"></span> No
                                    </label>
                                </div>
                            </div>
                            @error('pre_op_complete')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- People Qty -->
                <div class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-4">
                            <label for="people_qty" class="form-label text-dark">People Qty</label>
                            <input type="number" wire:model.blur="people_qty"
                                class="form-control p-2 border border-dark @error('people_qty') is-invalid border-danger @enderror"
                                id="people_qty">
                            @error('people_qty')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="scale" class="form-label text-dark">Scale #</label>
                            <input type="text" wire:model.blur="scale"
                                class="form-control p-2 border border-dark @error('scale') is-invalid border-danger @enderror"
                                id="scale">
                            @error('scale')
                            <span class="text-danger d-block mt-2 small"><i class="bi bi-exclamation-circle"></i>
                                {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="modal-footer mt-5">
                    <button type="button" class="btn btn-outline-danger" wire:click="closeStartModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Start Measurement
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
</div>