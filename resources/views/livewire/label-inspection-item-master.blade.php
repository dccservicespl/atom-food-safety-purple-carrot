<div>
    @if ($mode === 'list')
        <div class="container-fluid">
            <?php echo flashMessage(); ?>
            <div class="mt-5 page_card bg_white pb-0 pt-0 mb-0 rounded-0 rounded-top border-bottom border-secondary">
                <div class="row">
                    <div class="col-6">
                        <div class="card_title_color text-dark text-start ps-2" style="font-size: 22px">
                            <strong>Label Inspection Item Master</strong>
                        </div>
                    </div>

                    <div class="col-6 text-end pt-3">
                        <button wire:click="create" class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4 me-4"> <i
                                class="bi bi-plus"></i> Add
                            Item</button>
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
                                        <th>Item Name</th>
                                        <th>Weight</th>
                                        <th>Unite</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ $item->item_name }}</td>
                                            <td>{{ fmod($item->weight, 1) == 0 ? number_format($item->weight, 0) : number_format($item->weight, 2) }}
                                            </td>
                                            <td>{{ $item->item_unit }}</td>
                                            @if ($item->status === 'Active')
                                                <td class="">
                                                    <span class="new_date_status badge_completed">
                                                        {{ $item->status }}
                                                    </span>
                                                </td>
                                            @elseif ($item->status === 'Inactive')
                                                <td class="">
                                                    <span
                                                        class="new_date_status bg-danger-500 text-danger border border-danger">
                                                        {{ $item->status }}
                                                    </span>
                                                </td>
                                            @endif
                                            <td>
                                                <button wire:click="edit({{ $item->id }})"
                                                    class="btn btn-outline-warning"> <i
                                                        class="bi bi-pencil-square"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <?php echo no_record_found_in_table(); ?>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-5 page_card bg_white pb-0 pt-0 mb-0 rounded-0 rounded-top border-bottom border-secondary">
            <div class="row">
                <div class="col-12">
                    <div class="card_title_color text-dark text-start ps-2" style="font-size: 22px">
                        <strong>{{ $mode === 'edit' ? 'Edit ' : 'Add ' }} Label Inspection Item</strong>
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="store">
            <div class="card">
                <div class="p-4">
                    <div class="row">
                        <div class="col-6 col-md-4 p-3">
                            <label for="item_name"> Item name <span class="text-danger">*</span> </label>
                            <input type="text" wire:model="item_name" class="form-control p-2 border border-dark"
                                required>
                            @error('item_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 col-md-4 p-3">
                            <label for="item_name"> Weight <span class="text-danger">*</span>
                            </label>
                            <input type="text" wire:model="weight" class="form-control p-2 border border-dark"
                                required>
                            @error('weight')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 col-md-4 p-3">
                            <label for="item_name"> Unit <span class="text-danger">*</span>
                            </label>
                            <select wire:model="item_unit" id="" class="form-control p-2 border border-dark"
                                required>
                                <option value="">Select</option>
                                <option value="oz">OZ</option>
                                <option value="lb">LB</option>
                            </select>
                        </div>
                        @if ($mode === 'edit')
                            <div class="col-6 col-md-4 p-3">
                                <label for="item_name"> Unit <span class="text-danger">*</span>
                                </label>
                                <select wire:model="status" id="" class="form-control p-2 border border-dark"
                                    required>
                                    <option value="">Select</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        @endif

                        <div class="col-12 col-md-12 p-3 mt-3">
                            <label for="item_name"> <span class="text-danger"></span>
                            </label>
                            <button type="submit" class="btn btn-primary float-end ms-3">Save</button>
                            <button type="button" wire:click="backToList"
                                class="btn btn-outline-danger float-end">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
