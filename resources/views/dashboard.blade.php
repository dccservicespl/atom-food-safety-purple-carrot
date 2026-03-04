@extends('layouts.main')
@section('content')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.css">

    <style>
        .measure_date_list th {
            color: #0D121C;
            padding-left: 5px;
            padding-right: 5px;
        }

        .measure_date_list th {
            padding: 15px 10px !important;
        }

        .measure_date_list th,
        .measure_date_list td {
            border-color: #CBD5E1 !important;
        }

        .measure_date_list td {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .daterangepicker {
            top: 170px !important;
            left: 30px !important;
            right: auto !important;
        }
    </style>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-4">
                    {{-- <a href="" class="btn btn-outline-primary rounded home-page_btn"> <i class="bi bi-filter-left"></i>
                        Filter </a> --}}
                    <a href="#" id="filterBtn" class="btn btn-outline-primary rounded home-page_btn">
                        <i class="bi bi-filter-left"></i> Filter
                    </a>
                    <input type="text" id="dateRangePicker" class="form-control d-none">
                </div>
                <div class="col-8 text-end">
                    @if (Auth::user()->role_id == 2)
                        <a href="{{ route('item_master') }}" class="btn btn-outline-primary rounded home-page_btn"> Item
                            Master </a>
                    @endif
                    <a href="#" class="btn btn-primary rounded home-page_btn add_new_date"> New Date </a>
                </div>
            </div>
        </div>

        <div class="page_card">
            <div class="row">
                <div class="col-12">
                    <div class="table-container">
                        <table class="table measure_date_list">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-center ">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($measure_date->isNotEmpty())
                                    <?php $sr = 1; ?>
                                    @foreach ($measure_date as $measure_date_data)
                                        @php
                                            $isPending = $measure_date_data->status === 'pending';
                                            $text_class = $isPending ? 'text-primary' : '';
                                            // $route_link = $isPending
                                            //     ? route('measurement_category', Crypt::encrypt($measure_date_data->id))
                                            //     : null;
                                            $route_link = route(
                                                'measurement_category',
                                                Crypt::encrypt($measure_date_data->id),
                                            );
                                            if ($sr === 1) {
                                                $style =
                                                    'background-color: #3b69b4 !important;color: #fff !important;font-weight:700';
                                            } else {
                                                $style = '';
                                            }
                                        @endphp
                                        <tr style="{{ $style }}">
                                            <td class="{{ $text_class }}" style="{{ $style }}">
                                                @if ($isPending)
                                                    <a href="#" class="{{ $text_class }}"
                                                        style="{{ $style }}">
                                                        {{ date('m/d/Y', strtotime($measure_date_data->measure_date)) }}
                                                    </a>
                                                @else
                                                    {{ date('m/d/Y', strtotime($measure_date_data->measure_date)) }}
                                                @endif
                                            </td>
                                            {{-- <td class="{{ $text_class }}" style="{{ $style }}">
                                                {{ date('H:i:s A', strtotime($measure_date_data->start_time)) }}
                                            </td> --}}

                                            <td class="{{ $text_class }} text-center" style="{{ $style }}">
                                                @if ($isPending)
                                                    <span class="text-center new_date_status badge_pending">
                                                        {{ Str::ucfirst($measure_date_data->status) }}
                                                    </span>
                                                @else
                                                    <span class="text-center new_date_status badge_completed">
                                                        Completed
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="{{ $text_class }} text-center">
                                                <a href="{{ route('label_inspection_list', Crypt::encrypt($measure_date_data->id)) }}"
                                                    class="{{ $sr == 1 ? 'btn btn-warning' : 'btn btn-outline-warning' }} ms-3 me-3 ">
                                                    <i class="bi bi-chevron-double-right"></i>
                                                    {{ Auth::user()->role_id == 2 ? 'Label Verify' : 'Label Inspection' }}
                                                </a>
                                                {{-- <a href="{{ route('blending_label_inspection_listing', Crypt::encrypt($measure_date_data->id)) }}"
                                                    class="{{ $sr == 1 ? 'btn btn-warning' : 'btn btn-outline-warning' }} ms-3 me-3 ">
                                                    <i class="bi bi-chevron-double-right"></i>
                                                    {{ Auth::user()->role_id == 2 ? 'Label Verify' : 'Label Inspection' }}
                                                </a> --}}
                                                @if (Auth::user()->role_id < 3)
                                                    @if ($isPending)
                                                        <a href="{{ $route_link }}"
                                                            class="{{ $sr == 1 ? 'btn btn-success' : 'btn btn-outline-success' }}">
                                                            <i class="bi bi-chevron-double-right"></i>
                                                            {{ Auth::user()->role_id == 2 ? 'Sample Verify' : 'Sample Collection' }}

                                                        </a>
                                                    @else
                                                        <a href="{{ $route_link }}"
                                                            class="{{ $sr == 1 ? 'btn btn-success' : 'btn btn-outline-success' }}">
                                                            <i class="bi bi-chevron-double-right"></i>
                                                            {{ Auth::user()->role_id == 2 ? 'Sample Verify' : 'Sample Collection' }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $sr++; ?>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="4">
                                            <?php echo no_record_found_in_table(); ?>
                                        </th>
                                    </tr>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add_new_date').on('click', function() {
                $('#open_new_model_for_add_new_date').modal('show');
            });

            $('.close_model').on('click', function() {
                $('#open_new_model_for_add_new_date').modal('hide');
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#filterBtn').click(function(e) {
                e.preventDefault();
                $('#dateRangePicker').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                }, function(start, end) {
                    console.log("Selected Date Range: " + start.format('YYYY-MM-DD') + ' to ' + end
                        .format('YYYY-MM-DD'));
                    window.location.href =
                        `?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`;
                });
                $('#dateRangePicker').click();
            });
            $('#dateRangePicker').on('show.daterangepicker', function(event, picker) {
                $('.daterangepicker').css({
                    "top": "170px",
                    "left": "30px",
                    "right": "auto"
                });
            });
        });
    </script>
@endsection
@endsection
