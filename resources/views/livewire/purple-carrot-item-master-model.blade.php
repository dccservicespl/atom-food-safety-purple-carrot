<div>
    <div class="shadow-none">
        <?php echo flashMessage(); ?>
        @if ($mode === 'list')
            <div class="container-fluid">
                <div class="mt-5 page_card bg_white pb-0 pt-0 mb-0 rounded-0 rounded-top border-bottom border-secondary">
                    <div class="row">
                        <div class="col-6">
                            <div class="card_title_color text-dark text-start ps-4" style="font-size: 16px">
                                <a href="javascript:void(0);" style="color: unset;"><span>Purple Carrot</span></a>
                                <span>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        style="display: inline; margin: 0 8px; vertical-align: middle;">
                                        <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                {{-- <span>43rd Street</span>
                                <span>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        style="display: inline; margin: 0 8px; vertical-align: middle;">
                                        <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span> --}}
                                <a style="color: unset;" href="{{ route('work_type') }}"><span>Portioning</span></a>
                                <span>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        style="display: inline; margin: 0 8px; vertical-align: middle;">
                                        <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <strong>Items</strong>
                            </div>
                        </div>

                        <div class="col-6 text-end pt-3">
                            <button wire:click="create" class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">
                                <i class="bi bi-plus"></i> Add
                                Item</button>
                        </div>
                    </div>
                </div>
                <div class="page_card mt-0 rounded-0 rounded-bottom">
                    <div class="row">
                        <div class="col-12">
                            <div class="pb-3">
                                <button wire:click="$set('selectedCategoryId', '')"
                                    class="btn {{ $selectedCategoryId == '' ? 'btn-info' : 'btn-outline-info' }}">
                                    All
                                </button>

                                @foreach ($categories as $category)
                                    <button wire:click="$set('selectedCategoryId', {{ $category->id }})"
                                        class="btn {{ $selectedCategoryId == $category->id ? 'btn-info' : 'btn-outline-info' }}">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="table-container">
                                <table class="table measure_date_list">
                                    <thead>
                                        <tr style="background-color: #ddd; color: #000">
                                            <th>Component Details</th>
                                            <th>Category</th>
                                            <th>Label</th>
                                            <th>Unite</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $item)
                                            <tr>
                                                <td>{{ Str::limit($item->component_details ?? '-', 50) }}</td>
                                                <td>{{ $item->category->name ?? 'N/A' }}</td>
                                                <td>{{ $item->label }}</td>
                                                <td>{{ $item->unite ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $item->status === 'active' ? 'text-success new_date_status badge_completed' : 'new_date_status bg-danger-500 text-danger border border-danger' }}">
                                                        {{ ucfirst($item->status ?? 'inactive') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button wire:click="edit({{ $item->id }})"
                                                        class="btn btn-outline-warning"> <i
                                                            class="bi bi-pencil-square"></i>
                                                    </button>
                                                    @if ($item->status === 'active')
                                                        {{-- <button wire:click="edit(44)" class="btn btn-outline-primary">
                                                            <i class="bi bi-arrow-right-circle"></i>
                                                        </button> --}}
                                                        <a href="{{ route('portioning_measurement_form', ['item_id' => Crypt::encrypt($item->id)]) }}"
                                                            class="btn btn-outline-primary">
                                                            <i class="bi bi-arrow-right-circle"></i>
                                                        </a>
                                                    @endif
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
                                    {{ $items->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container-fluid">
                <div class="page_card bg_white">
                    <div class="row">
                        <div class="col-8 mt-2">
                            <strong class="m-0 text-dark h5"> Add Items </strong>
                        </div>
                        <div class="col-4">
                            <button style="float: right;" wire:click="cancel"
                                class="float-right text-gray-500 hover:text-gray-700 p-0 border-0 bg-transparent">
                                {{-- <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg> --}}
                                <img class="img-responsive" src="/assets/img/icons/back.png" alt=""
                                    width="30">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="card shadow p-4">
                        <form wire:submit="save" class="cta_btn_form">
                            @csrf
                            <div class="mb-5 mt-3">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="input-group ">
                                            <label class="">Category*</label>
                                            <select wire:model="category_id"
                                                class="form-control border_dark pt-2 pb-2 ps-3 pe-3 w-100">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="input-group ">
                                            <label class="">Label *</label>
                                            <input wire:model="label" type="text"
                                                class="form-control border_dark pt-2 pb-2 ps-3 pe-3 w-100"
                                                placeholder="Enter label">
                                            @error('label')
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="input-group ">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Component
                                                Details</label>
                                            <textarea wire:model="component_details" rows="3" class="form-control border_dark pt-2 pb-2 ps-3 pe-3 w-100"
                                                placeholder="Enter component details..."></textarea>
                                            @error('component_details')
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group ">
                                            <label class="">Unit</label>
                                            <input wire:model="unite" type="text"
                                                class="form-control border_dark pt-2 pb-2 ps-3 pe-3 w-100"
                                                placeholder="kg, g, cup, tbsp etc.">
                                            @error('unite')
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group ">
                                            <label class="">Status</label>
                                            <select wire:model="status"
                                                class="form-control border_dark pt-2 pb-2 ps-3 pe-3 w-100">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            @error('status')
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="flex space-x-4 pt-6 text-end">
                                    <button type="button" wire:click="cancel"
                                        class="btn btn-outline-secondary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5">
                                        {{ $mode === 'create' ? 'Create Item' : 'Update Item' }}
                                    </button>

                                </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
