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
                        <strong>Mix Item Master</strong>
                    </div>
                </div>

                <div class="col-6 text-end pt-3">
                    <a href="{{ route('add_mix_item') }}" class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">
                        <i class="bi bi-plus fa-lg"></i> Add Item
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
                                <tr
                                    style="background-color: {{ $get_category_data->title_color_code }}; color: {{ $get_category_data->card_color_code }}">
                                    <th>Item Description</th>
                                    <th>Weight</th>
                                    <th>Min pH</th>
                                    <th>Max pH</th>
                                    <th>Temperature</th>
                                    <th class="text-center ">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($get_all_item->isNotEmpty())
                                    @foreach ($get_all_item as $get_the_guacamole_items_data)
                                        <tr>
                                            <td>{{ $get_the_guacamole_items_data->item_name }}</td>
                                            <td>
                                                {{ rtrim(rtrim(number_format($get_the_guacamole_items_data->weight, 2, '.', ''), '0'), '.') }} oz
                                            </td>
                                            <td>{{ $get_the_guacamole_items_data->ph_min }}</td>
                                            <td>{{ $get_the_guacamole_items_data->ph_max }}</td>
                                            <td>{{ $get_the_guacamole_items_data->temperature }}</td>
                                            @if ($get_the_guacamole_items_data->status === 'Active')
                                                <td class="text-center">
                                                    <span class="text-center new_date_status badge_completed">
                                                        {{ $get_the_guacamole_items_data->status }}
                                                    </span>
                                                </td>
                                            @elseif ($get_the_guacamole_items_data->status === 'Inactive')
                                                <td class="text-center">
                                                    <span
                                                        class="text-center new_date_status bg-danger-500 text-danger border border-danger">
                                                        {{ $get_the_guacamole_items_data->status }}
                                                    </span>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{ route('mix_edit_items', Crypt::encrypt($get_the_guacamole_items_data->id)) }}"
                                                    class="btn btn-outline-warning"> <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <?php echo no_record_found_in_table(); ?>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- add new Date --}}
    <div class="modal" tabindex="-1" role="dialog" id="open_new_model_for_add_new_date">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Safety Measures</h5>
                    <button type="button" class="close border rounded text-dark close_model" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="{{ route('add_measurement_action') }}" method="POST">
                            @csrf
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="split-login-password">Date</label>
                            </div>
                            <div class="input-group">
                                <input class="form-control p-3" type="date" name="date" required />
                            </div>
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-outline-secondary home-page_btn close_model"
                                    data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary home-page_btn">Apply</button>
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
