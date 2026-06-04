@extends('layouts.main')
@section('content')
    <style>
        .swal2-title {
            font-size: 16px !important;
            text-align: left !important;
        }

        .swal2-html-container {
            font-size: 16px !important;
        }
    </style>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="mt-5 page_card bg_white pb-0 pt-0 mb-0 rounded-0 rounded-top border-bottom border-secondary">
            <div class="row">
                <div class="col-6">
                    <div class="card_title_color text-dark text-start ps-2" style="font-size: 22px">
                        <strong>Edit Mix Item</strong>
                    </div>
                </div>

                {{-- <div class="col-6 text-end pt-3">
                    <a href="{{ route('add_blending_item') }}" class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">
                        <i class="bi bi-file-earmark-plus"></i> Add Item
                    </a>
                </div> --}}

            </div>
        </div>
        <div class="page_card mt-0 rounded-0 rounded-bottom">
            <div class="row">
                <div class="col-12">
                    <div class="table-container">
                        <form action="{{ route('edit_mix_item_action', $get_item->id) }}" method="POST"
                            id="add_mix_item_form">
                            @csrf
                            <div class="row">
                                <div class="col-3 p-3">
                                    <label for="item_name"> Item name <span class="text-danger">*</span> </label>
                                    <input type="text" name="item_name" value="{{ $get_item->item_name }}"
                                        class="form-control p-2 border border-dark" required>
                                </div>
                                <div class="col-3 p-3">
                                    <label for="item_name"> Minimum pH <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="ph_min" value="{{ $get_item->ph_min }}"
                                        class="form-control p-2 border border-dark" required maxlength="5">
                                </div>
                                <div class="col-3 p-3">
                                    <label for="item_name"> Maximum pH <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="ph_max" value="{{ $get_item->ph_max }}"
                                        class="form-control p-2 border border-dark" required maxlength="5">
                                </div>
                                <div class="col-3 p-3">
                                    <label for="item_name"> Temperature <span class="text-danger">*</span> </label>
                                    <input type="text" name="temperature" value="{{ $get_item->temperature }}"
                                        class="form-control p-2 border border-dark" required>
                                </div>
                                <div class="col-3 p-3">
                                    <label for="item_name"> Weight <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="weight" value="{{ $get_item->weight }}"
                                        class="form-control p-2 border border-dark" required>
                                </div>
                                <div class="col-3 p-3">
                                    <label for="item_name"> Status
                                    </label>
                                    <select name="status" class="form-control p-2 border border-dark" required>
                                        <option value="Active" {{ $get_item->status == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ $get_item->status == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-6 p-5 float-end text-end">
                                    <a href="{{ $get_route }}" class="btn btn-outline-danger me-2"> Cancel </a>
                                    <button class="btn btn-primary" type="submit"> Update Item </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
@endsection
@endsection
