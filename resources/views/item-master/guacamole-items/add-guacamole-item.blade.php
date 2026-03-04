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
                        <strong>Add Guacamole Item</strong>
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
                        <form action="{{ route('add_guacamole_item_action') }}" method="POST" id="add_guacamole_item_form">
                            @csrf
                            <div class="row">
                                <div class="col-6 p-3">
                                    <label for="item_name"> Item name <span class="text-danger">*</span> </label>
                                    <input type="text" name="item_name" class="form-control p-2 border border-dark"
                                        required>
                                </div>
                                <div class="col-6 p-3">
                                    <label for="item_name"> Weight <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="weight" class="form-control p-2 border border-dark"
                                        required>
                                </div>
                                <div class="col-12 p-3 float-end text-end">
                                    <a href="{{ $get_route }}" class="btn btn-outline-danger me-2"> Cancel </a>
                                    <button class="btn btn-primary" type="submit"> Add Item </button>
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
