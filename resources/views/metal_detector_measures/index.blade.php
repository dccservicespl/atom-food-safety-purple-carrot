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
        color: #FBB231 !important
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
                    <strong> {{ $get_the_date_details->measure_date }}</strong>
                </p>
            </div>
            @if (Auth::user()->role_id == 1)
            <div class="col-4">
                <p class="text-center mb-0 text-dark">
                    <i class="bi bi-clock mr-2 p-1 mix_color"> </i> Pending
                </p>
                <p class="text-center text-dark mb-0 font-weight-bold bold">
                    <strong> {{ $all_md_item_count->total_items - $all_md_item_count->completed_status }}
                    </strong>
                </p>
            </div>
            <div class="col-4 text-center">
                <p class="text-center mb-0 text-dark">
                    <i class="bi bi-clock mr-2 p-1 mix_color"> </i>
                    Submitted
                </p>
                <p class="text-center text-dark mb-0 font-weight-bold bold">
                    <strong> {{ $all_md_item_count->completed_status }} </strong>
                </p>
            </div>
            @else
            <div class="col-8 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle pt-2 pb-2 ms-4 me-4"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Download
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" target="_blank" style="font-size: 16px; padding-bottom:20px;"
                                href="{{ route('download_metal_detector_pdf', Crypt::encrypt($get_the_date_details->id)) }}"><i
                                    class="far fa-file-pdf mix_color" style="margin-right: 10px;"></i> PDF</a></li>
                        <li><a class="dropdown-item" style="font-size: 16px;"
                                href="{{ route('download_metal_detector_excel', $get_the_date_details->id) }}"><i
                                    class="far fa-file-excel mix_color" style="margin-right: 10px;"></i> Excel</a>
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
                            <tr style="background-color:#FBB231 !important ; color: #ffff">
                                <th>Item Description </th>
                                <th>Weight </th>
                                <th class="text-center">Food Safety Technician</th>
                                <th class="text-center">Food Safety Manager</th>
                                <th class="text-center ">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($get_the_md_items->isNotEmpty())
                            @foreach ($get_the_md_items as $get_the_blending_items_data)
                            <tr>
                                <td>{{ $get_the_blending_items_data->item_name }}</td>
                                <td>{{ $get_the_blending_items_data->weight > 0 ? $get_the_blending_items_data->weight . '  ' . $get_the_blending_items_data->item_unit : 'N/A' }}
                                </td>
                                <td class="text-center">
                                    <span>{{$get_the_blending_items_data->technician_name ?? '-'}}</span>
                                </td>
                                <td class="text-center">
                                    <span>{{$get_the_blending_items_data->manager_name ?? '-'}}</span>
                                </td>
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
                                    <a
                                        href="{{ route('metal_detective_details', [Crypt::encrypt($get_the_blending_items_data->md_items_id), $date_id]) }}">
                                        <i class="bi bi-chevron-double-right text_blending"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <?php echo no_record_found_in_table();
                            ?>
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