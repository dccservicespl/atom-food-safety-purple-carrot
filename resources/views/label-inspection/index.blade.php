@extends('layouts.main')
@section('content')
    <?php use App\Helpers\WebHookHelper; ?>
    <style>
        .swal2-title {
            font-size: 16px !important;
            text-align: left !important;
        }

        .swal2-html-container {
            font-size: 16px !important;
        }

        .mix_color {
            color: #49C6E6 !important
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-item.show .nav-link {
            color: #f1f1f1;
            background-color: rgb(41 197 237);
            border-bottom: 3px solid #2196F3;
        }

        .nav-tabs .nav-link {
            font-size: 1.1rem;
        }

        .nav-tabs {
            border-bottom: 0px solid;
        }

        .table-striped>tbody>tr:nth-of-type(even)>* {
            background-color: #f2f2f2 !important;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: #ffffff !important;
        }

        td {
            font-size: 14px;
        }
    </style>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>

        <div class="page_card bg_white">
            <div class="row">
                <div class="col-6">
                    <p class="text-start mb-0 text-dark h3 pt-2">
                        Label Inspection
                    </p>
                </div>
                <div class="col-6">
                    <p class="text-end mb-0 text-dark">
                        <i class="bi bi-calendar-check mr-2 p-1"> </i>
                        Inspection Date
                    </p>
                    <p class="text-end text-dark mb-0 font-weight-bold bold">
                        <strong> {{ date('m/d/Y', strtotime($get_the_measure_date->measure_date)) }}</strong>
                    </p>
                </div>
            </div>
        </div>


        <div class="page_card">
            <div class="row" style="border-bottom: 2px solid var(--falcon-border-color);">
                <div class="col-6 col-md-9">
                    <p class="text-start mb-0 text-dark h5 pt-2 pb-2">
                        Label Inspection Items
                    </p>
                </div>
                <div class="col-6 col-md-3 text-end">
                    @if (Auth::user()->role_id == 2)
                        <a href="{{ route('download_inspection_report', $id_decode) }}" class="btn btn-outline-primary"> <i
                                class="bi bi-download"></i> Download
                            Report </a>
                    @endif
                </div>
            </div>

            <div class="page_card mt-0">
                <div class="row" style="border-bottom: 2px solid var(--falcon-border-color);">
                    <div class="col-12 col-md-12">
                        {{-- <form action="" id="label_inspection_filter">
                            <div class="form-group mb-3">
                                <label for="filter_item_name" class="form-label">Filter by Item Name</label>
                                <input type="text" name="filter_item_name" id="filter_item_name" class="form-control"
                                    placeholder="Enter Item Name" value="{{ request()->get('filter_item_name', '') }}">
                        </form> --}}

                        <form action="javascript:void(0)" id="label_inspection_filter">
                            <div class="form-group mb-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="filter_item_name" id="filter_item_name" class="form-control"
                                    placeholder="Item Name">
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-container table-responsive">
                        <table class="table measure_date_list table-responsive table-striped">
                            <thead>
                                <tr class="table_bg_color_style">
                                    <th>Item </th>
                                    <th>Submitted </th>
                                    <th>Verified </th>
                                    <th>Approved </th>
                                    <th class="text-center ">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="item_list_table_body21 inspection_table_area">
                                @forelse ($get_all_category_items as $get_all_category_items_data)
                                    <tr onclick="window.location='{{ route('inspection_details_form', [
                                        'measure_id' => $id_decode,
                                        'item_id' => $get_all_category_items_data->item_id,
                                    ]) }}'"
                                        style="cursor:pointer;">
                                        <td>
                                            {{ $get_all_category_items_data->item_name .
                                                ' - ' .
                                                (fmod($get_all_category_items_data->weight, 1) == 0
                                                    ? number_format($get_all_category_items_data->weight, 0)
                                                    : number_format($get_all_category_items_data->weight, 2)) .
                                                ' ' .
                                                $get_all_category_items_data->item_unit }}
                                        </td>
                                        <td><?php echo WebHookHelper::get_inspection_status($get_all_category_items_data->inspectiondetails_id, 'S'); ?></td>
                                        <td><?php echo WebHookHelper::get_inspection_status($get_all_category_items_data->inspectiondetails_id, 'P'); ?></td>
                                        <td><?php echo WebHookHelper::get_inspection_status($get_all_category_items_data->inspectiondetails_id, 'V'); ?></td>
                                        <td class="text-center">
                                            <?php echo WebHookHelper::get_inspection_details_status($id_decode, $get_all_category_items_data->item_id); ?>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('inspection_details_form', ['measure_id' => $id_decode, 'item_id' => $get_all_category_items_data->item_id]) }}">
                                                <i class="bi bi-chevron-double-right text_blending"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="6">
                                            <?php echo no_record_found_in_table(); ?>
                                        </th>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <div id="inspection_spinner" class="text-center my-3" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                let typingTimer;
                const delay = 400;

                $('#filter_item_name').on('keyup', function() {
                    $('.inspection_table_area').hide();
                    $('#inspection_spinner').show();
                    clearTimeout(typingTimer);
                    let value = $(this).val();

                    typingTimer = setTimeout(function() {
                        $.ajax({
                            url: "{{ route('label_inspection_list_filter', $id_decode) }}",
                            type: "GET",
                            data: {
                                filter_item_name: value
                            },
                            success: function(response) {
                                console.log(response);
                                $('#inspection_spinner').hide();
                                $('.inspection_table_area').show();
                                $('.inspection_table_area').html(response.html);
                            }
                        });
                    }, delay);
                });
            });
        </script>
    @endsection
@endsection
