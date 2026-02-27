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

    .mix_color {
        color: #EA724C !important
    }

    .upload_box_bar_code:hover,
    .upload_box_bar_code i:hover {
        color: #2196f3 !important;
        background-color: #ffff;
    }
</style>
<div class="container-fluid">
    <?php echo flashMessage(); ?>
    <div class="page_card bg_white">
        <div class="row">
            <div class="col-4">
                <p class="text-center mb-0 text-dark">
                    <i class="bi bi-calendar-check mr-2 p-1 mix_color"> </i>
                    Measurement Date
                </p>
                <p class="text-center text-dark mb-0 font-weight-bold bold">
                    <strong> {{ date('m-d-Y', strtotime($get_the_date_details->measure_date)) }}</strong>
                </p>
            </div>

            <div class="col-8 text-end">
                <a href="#" class="show_box_bar_code_image_popup_trigger pe-3">
                    @if ($get_the_date_details->container_bar_code_img)
                    <img src="{{ $get_the_date_details->container_bar_code_img }}" alt=""
                        style="height: 50px">
                    @else
                    <img src="/assets/img/img_icon.png" alt="" style="height: 55px">
                    @endif
                </a>

                <button class="btn btn-outline-warning pt-2 pb-2 upload_box_bar_code">
                    <i class="bi bi-upload h3 pe-2 text-warning upload_box_bar_code"></i>
                    Box barcode
                </button>

                @if (Auth::user()->role_id == 2)
                {{-- <a href="{{ route('download_guacamole_excel', $get_the_date_details->id) }}"
                class="btn btn-outline-primary pt-2 pb-2 ps-5 pe-5 ms-4 me-4"><i
                    class="bi bi-download h3 pe-2 text-primary upload_box_bar_code"></i>
                Download Excel</a> --}}
                <div class="btn-group">
                    <button class="btn btn-outline-primary generate_report_btn  pt-2 pb-2 ms-4 me-4">
                        <i class="bi bi-download"></i> Generate Report
                    </button>
                </div>
                {{-- <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle pt-2 pb-2 ms-4 me-4"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Download
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" target="_blank" style="font-size: 16px; padding-bottom:20px;"
                                        href="{{ route('download_guacamole_pdf', Crypt::encrypt($get_the_date_details->id)) }}"><i
                    class="far fa-file-pdf mix_color" style="margin-right: 10px;"></i> PDF</a>
                </li>
                <li><a class="dropdown-item" style="font-size: 16px;"
                        href="{{ route('download_guacamole_excel', $get_the_date_details->id) }}"><i
                            class="far fa-file-excel mix_color" style="margin-right: 10px;"></i> Excel</a>
                </li>
                </ul>
            </div> --}}
            @endif
            </a>

        </div>
    </div>
</div>
<div class="page_card">
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <table class="table measure_date_list">
                    <thead>
                        <tr style="background-color:#EA724C !important ; color: #ffff">
                            <th> Item Description </th>
                            <th> Weight </th>
                            <th class="text-center">Food Safety Technician</th>
                            <th class="text-center">Food Safety Manager</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($get_the_guacamole_items->isNotEmpty())
                        @foreach ($get_the_guacamole_items as $get_the_guacamole_items_data)
                        <tr>
                            <td>{{ $get_the_guacamole_items_data->item_name }}
                                @if ($get_the_guacamole_items_data->batch_no > 0)
                                <span style="color: #EA724C; font-size:12px">
                                    - Batch No </span>
                                <span style="color: #EA724C; font-size:12px; font-weight:600">
                                    {{ $get_the_guacamole_items_data->batch_no }} </span>
                                @endif

                            </td>
                            <td>{{ $get_the_guacamole_items_data->weight > 0 ? $get_the_guacamole_items_data->weight . ' oz' : 'N/A' }}
                            </td>
                            <td class="text-center">{{$get_the_guacamole_items_data->technician_name ?? '-'}}</td>
                            <td class="text-center">{{$get_the_guacamole_items_data->manager_name ?? '-'}}</td>
                            @if ($get_the_guacamole_items_data->status === 'Verified')
                            <td class="text-center">
                                <span class="text-center new_date_status badge_completed">
                                    <i class="bi bi-patch-check pe-2"></i> Verified
                                </span>
                            </td>
                            @elseif ($get_the_guacamole_items_data->status === 'Submitted')
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
                                                    data-itemId="{{ Crypt::encrypt($get_the_guacamole_items_data->guacamole_items_id) }}"
                                data-dailyMeasureIt="{{ $date_id }}">
                                <i class="bi bi-chevron-double-right text_blending"
                                    style="color: #EA724C !important;"></i>
                                </button> --}}
                                <a
                                    href="{{ route('guacamole_measure', [Crypt::encrypt($get_the_guacamole_items_data->guacamole_items_id), $date_id, $get_the_guacamole_items_data->batch_no ?? 1]) }}">
                                    <i class="bi bi-chevron-double-right text_blending"
                                        style="color: #EA724C !important;"></i>
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

<!-- Modal -->
<div class="modal" tabindex="-1" role="dialog" id="exampleModalCenter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Box BAR Code</h5>
                <button type="button" class="close model_close border border-danger text-danger rounded"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($get_the_date_details->container_bar_code_img)
                <p class="text-secondary text-center">You already have a box bar code image first remove the
                    existing image. Are you sure want to remove the image?</p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger model_close"
                        data-dismiss="modal">Close</button>
                    <a href="{{ route('remove_bar_code', $get_the_date_details->id) }}"
                        class="btn btn-danger">Remove</a>
                </div>
                @else
                <form action="{{ route('store_container_br_code_action') }}" class="upload_image_form"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="daily_measure_id" value="{{ $get_the_date_details->id }}">
                    <div class="text-center">
                        <button type="button" class="btn p-2 h1 text-center" id="cameraButton"
                            style="background-color: #fff !important;font-size: 60px;width: 105px;border: 1px solid #EA724C  !important;color: #EA724C !important;">
                            <svg class="svg-inline--fa fa-camera fa-w-16" aria-hidden="true" focusable="false"
                                data-prefix="fa" data-icon="camera" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z">
                                </path>
                            </svg>
                            <!-- <i class="fa fa-camera"></i> Font Awesome fontawesome.com -->
                        </button>
                    </div>
                    <div class="mt-3 text-center">
                        <img id="imagePreview" src="#" alt="Selected Image"
                            class="img-fluid d-none border rounded" style="height: 100px; max-width: 100%;">
                    </div>
                    <input id="imageInput" class="d-none" type="file" name="image" accept="image/*"
                        capture="camera" required="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger model_close"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary upload_image_form_submit">Save
                            changes</button>
                    </div>
                </form>
                @endif
            </div>

        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="show_box_bar_code_image_popup">
    <div class="modal-dialog" role="document" style="max-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Box barcode image</h5>
                <button type="button" class="close model_close border border-danger text-danger rounded"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                @if ($get_the_date_details->container_bar_code_img)
                <img src="{{ $get_the_date_details->container_bar_code_img }}" alt="" width="100%">
                @else
                <img src="/assets/img/img_icon.png" alt="" style='width:100%; height:auto'>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- select Batch number modal --}}
<!-- Modal -->
<div class="modal fade" id="blending_measure_item_model" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Batch No</h5>
                <p class="model_data"></p>
                <button type="button" class="close model_close border border-danger text-danger rounded"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body all_batch_html text-center">
            </div>
        </div>
    </div>
</div>

{{-- Model for report generate  --}}

<div class="modal fade" id="generate_report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Report</h5>
                <button type="button" class="close model_close btn btn-outline-danger border border-danger"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('generate_guac_report') }}" method="POST" target="_blank"
                    class="report_generate_form">
                    @csrf
                    <input type="hidden" name="daily_measure_id" value="{{ $get_the_date_details->id }}">
                    <div class="form-group">
                        <label for=""> Operator Name </label>
                        <input type="text" name="operator_name" class="form-control" required>
                    </div>

                    <div class="form-check mt-4">
                        <input class="form-check-input" type="radio" name="report_type" id="exampleRadios1"
                            value="pdf" checked required>
                        <label class="form-check-label" for="exampleRadios1">
                            Generate PDF Report
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_type" id="exampleRadios2"
                            value="excel" required>
                        <label class="form-check-label" for="exampleRadios2">
                            Generate Excel Report
                        </label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger model_close"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Generate Report</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {

        //Report generate modal code start
        $('.generate_report_btn').on('click', function(e) {
            e.preventDefault();
            $('#generate_report').modal('show');
        });
        //Report generate modal code end
        $('.blending_measure_item_popup').on('click', function(e) {
            e.preventDefault();

            var itemid = $(this).data('itemid');
            var dailymeasureit = $(this).data('dailymeasureit');

            $.ajax({
                url: "/get_guaco_item_batch_no/" + dailymeasureit + "/" + itemid,
                success: function(response) {
                    console.log(response.data);
                    var item_batch_number = response.data;
                    var all_batch = '';

                    for (let index = 1; index <= 5; index++) {
                        var dangerClass = item_batch_number.includes(index) ?
                            'btn-warning' : 'btn-outline-warning';

                        all_batch +=
                            '<a class="btn m-1 mb-3 batch_no_url ' +
                            dangerClass + '" data-batch="' +
                            index + '">' + index + '</a>';
                    }

                    $('.all_batch_html').html(all_batch);
                    $('.batch_no_url').each(function() {
                        var batch_no = $(this).data('batch');
                        var href_route = '/admin/guacamole_measure/' + itemid +
                            '/' +
                            dailymeasureit + '/' + batch_no;
                        $(this).attr('href', href_route);
                    });

                    // $('.report_generate_form').on('submit', function(e) {
                    //     e.preventDefault();
                    //     // $('#generate_report').modal('hide');
                    // });
                }
            });


            $('.upload_box_bar_code').on('click', function(e) {
                e.preventDefault();
                $('#exampleModalCenter').modal('show');
            });

            $('.model_close').on('click', function(e) {
                e.preventDefault();
                $('#exampleModalCenter').modal('hide');
                $('#show_box_bar_code_image_popup').modal('hide');
                $('#show_box_bar_code_image_popup').hide();
                $('#blending_measure_item_model').modal('hide');
                $('#generate_report').modal('hide');
            });

            $('#cameraButton').on('click', function() {
                $('#imageInput').click();
            });

            $('#imageInput').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

        });

        $('.upload_box_bar_code').on('click', function(e) {
            e.preventDefault();
            $('#exampleModalCenter').modal('show');
        });

        $('.model_close').on('click', function(e) {
            e.preventDefault();
            $('#exampleModalCenter').modal('hide');
            $('#show_box_bar_code_image_popup').modal('hide');
            $('#show_box_bar_code_image_popup').hide();
            $('#blending_measure_item_model').modal('hide');
            $('#generate_report').modal('hide');
        });

        $('#cameraButton').on('click', function() {
            $('#imageInput').click();
        });

        $('#imageInput').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        $('.show_box_bar_code_image_popup_trigger').on('click', function(e) {
            e.preventDefault();
            $('#show_box_bar_code_image_popup').show();
        });
    })
</script>
@endsection
@endsection