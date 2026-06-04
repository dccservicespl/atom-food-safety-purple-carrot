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
    <div class="page_card bg_white">
        <div class="row">
            <div class="col-4">
                <p class="text-center mb-0 text-dark">
                    <i class="bi bi-calendar-check mr-2 p-1" style="color: {{ $daily_measures->title_color_code }}"> </i>
                    Measurement Date
                </p>
                <p class="text-center text-dark mb-0 font-weight-bold bold">
                    <strong> {{ date('m-d-Y', strtotime($get_the_date_details->measure_date)) }}</strong>
                </p>
            </div>
            @if (Auth::user()->role_id == 1)
            <div class="col-4 text-center">
                <p class="text-center mb-0 text-dark">
                    <i class="bi bi-clock mr-2 p-1" style="color: {{ $daily_measures->title_color_code }}"> </i>
                    Submitted
                </p>
                <p class="text-center text-dark mb-0 font-weight-bold bold">
                    <strong>{{ $all_blending_item_submitted_count }}</strong>
                </p>
            </div>
            <div class="col-4">
                <p class="text-center mb-0 text-dark">
                    <i class="bi bi-clock mr-2 p-1" style="color: {{ $daily_measures->title_color_code }}"> </i>
                    Verified
                </p>
                <p class="text-center text-dark mb-0 font-weight-bold bold">
                    <strong>{{ $all_blending_item_verify_count }}</strong>
                </p>
            </div>
            @else
            <div class="col-8 text-end">
                {{-- <a href="{{ route('download_blending_pdf', $get_the_date_details->id) }}"
                class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4"><i class="bi bi-download"></i>
                Download Pdf</a>
                <a href="{{ route('download_blending_excel', $get_the_date_details->id) }}"
                    class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4 me-4"><i class="bi bi-download"></i>
                    Download Excel</a> --}}

                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle pt-2 pb-2 ms-4 me-4"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Download
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" target="_blank" style="font-size: 16px; padding-bottom:20px;"
                                href="{{ route('download_blending_pdf', Crypt::encrypt($get_the_date_details->id)) }}"><i
                                    class="far fa-file-pdf"
                                    style="margin-right: 10px; color: {{ $daily_measures->title_color_code }}"></i>
                                PDF</a>
                        </li>
                        <li><a class="dropdown-item" style="font-size: 16px;"
                                href="{{ route('download_blending_excel', $get_the_date_details->id) }}"><i
                                    class="far fa-file-excel"
                                    style="margin-right: 10px; color: {{ $daily_measures->title_color_code }}"></i>
                                Excel</a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="page_card">
        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <table class="table measure_date_list">
                        <thead>
                            <tr
                                style="background-color: {{ $daily_measures->title_color_code }}; color: {{ $daily_measures->card_color_code }}">
                                <th>Blending Description </th>
                                <th class="text-center">Food Safety Technician</th>
                                <th class="text-center">Food Safety Manager</th>
                                <th>Weight </th>
                                <th class="text-center ">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($get_the_blending_items->isNotEmpty())
                            @foreach ($get_the_blending_items as $get_the_blending_items_data)
                            <tr>
                                <td>{{ $get_the_blending_items_data->item_name }}
                                    @if ($get_the_blending_items_data->batch_no > 0)
                                    <span style="color: #EA724C; font-size:12px">
                                        - Batch No </span>
                                    <span style="color: #EA724C; font-size:12px; font-weight:600">
                                        {{ $get_the_blending_items_data->batch_no }} </span>
                                    @endif

                                </td>
                                <td class="text-center">
                                    <span>{{$get_the_blending_items_data->technician_name ?? '-'}}</span>
                                </td>
                                <td class="text-center">
                                    <span>{{$get_the_blending_items_data->manager_name ?? '-'}}</span>
                                </td>
                                <td>{{ $get_the_blending_items_data->weight > 0 ? $get_the_blending_items_data->weight . ' oz' : 'N/A' }}
                                </td>

                                @if ($get_the_blending_items_data->status === 'Verified')
                                <td class="text-center">
                                    <span class="text-center new_date_status badge_completed">
                                        <i class="bi bi-patch-check pe-2"></i> Verified
                                    </span>
                                </td>
                                @elseif ($get_the_blending_items_data->status === 'Submitted')
                                <td class="text-center">
                                    <span class="text-center new_date_status badge_submitted">
                                        Submitted
                                    </span>
                                </td>
                                @else
                                <td class="text-center">
                                    <span class="text-center new_date_status badge_pending">
                                        Pending
                                    </span>
                                </td>
                                @endif
                                <td>
                                    {{-- <button class="border border-0 bg-white blending_measure_item_popup"
                                                    data-itemId="{{ Crypt::encrypt($get_the_blending_items_data->blending_id) }}"
                                    data-dailyMeasureIt="{{ $date_id }}">
                                    <i class="bi bi-chevron-double-right text_blending"></i>
                                    </button> --}}
                                    <a
                                        href="{{ route('blending_details', [Crypt::encrypt($get_the_blending_items_data->blending_id), $date_id, $get_the_blending_items_data->batch_no ?? 1]) }}">
                                        <i class="bi bi-chevron-double-right text_blending"></i>
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

{{-- select lot number modal --}}
<!-- Modal -->
<div class="modal fade" id="blending_measure_item_model" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Batch No</h5>
                <p class="model_data"></p>
                <button type="button" class="close modal_close border border-danger text-danger rounded"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php //get_all_batch_it_by_item_id_and_daily_measure_id($date_id, $item_id);
            ?>

            <div class="modal-body all_batch_html text-center">
                {{-- @for ($i = 1; $i <= 10; $i++)
                        <a class="btn btn-outline-success m-1 mb-3 batch_no_url" data-batch="{{ $i }}">
                {{ $i }}</a>
                @endfor --}}
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {

        $('.blending_measure_item_popup').on('click', function(e) {
            e.preventDefault();

            var itemid = $(this).data('itemid');
            var dailymeasureit = $(this).data('dailymeasureit');

            $.ajax({
                url: "/get_item_batch_no/" + dailymeasureit + "/" + itemid,
                success: function(response) {
                    console.log(response.data);
                    var item_batch_number = response.data;
                    var all_batch = '';

                    for (let index = 1; index <= 5; index++) {
                        var dangerClass = item_batch_number.includes(index) ?
                            'btn-success' : 'btn-outline-success';

                        all_batch +=
                            '<a class="btn m-1 mb-3 batch_no_url ' +
                            dangerClass + '" data-batch="' +
                            index + '">' + index + '</a>';
                    }

                    $('.all_batch_html').html(all_batch);
                    $('.batch_no_url').each(function() {
                        var batch_no = $(this).data('batch');
                        var href_route = '/admin/blending-details/' + itemid + '/' +
                            dailymeasureit + '/' + batch_no;
                        $(this).attr('href', href_route);
                    });

                    $('#blending_measure_item_model').modal('show');
                }
            });

        });

        $('.modal_close').on('click', function() {
            $('#blending_measure_item_model').modal('hide');
        });
    });
</script>
@endsection
@endsection