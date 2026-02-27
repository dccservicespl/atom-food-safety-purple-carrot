@extends('layouts.main')
@section('content')
    <style>
        .stic_summery {
            text-align: center;
            font-family: Arial, sans-serif;
            position: absolute;
            left: 0;
            right: 0;
            top: 20%;
            bottom: 0;
        }
    </style>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-4">
                    <p class="text-center mb-0 text-dark">
                        <i class="bi bi-calendar-check text-primary mr-2 p-1"> </i> Measurement Date
                    </p>
                    <p class="text-center text-dark mb-0 font-weight-bold bold">
                        <strong>{{ date('m-d-Y', strtotime($get_daily_measures->measure_date)) }}</strong>
                    </p>
                </div>
                <div class="col-4">
                    <p class="text-center mb-0 text-dark">
                        <i class="bi bi-clock text-primary mr-2 p-1"> </i> Start Time
                    </p>
                    <p class="text-center text-dark mb-0 font-weight-bold bold">
                        <strong>{{ date('H:i:s A', strtotime($get_daily_measures->start_time)) }}</strong>
                    </p>
                </div>
                <div class="col-4 text-center">
                    {{-- @if (Auth::user()->role_id == 1) --}}
                    <p class="text-center mb-0 text-dark">
                        <i class="bi bi-clock text-primary mr-2 p-1"> </i> End Time
                    </p>
                    {{-- @else
                        <p class="text-center mb-0 text-dark">
                            <i class="bi bi-patch-check text-primary mr-2 p-1"> </i> Verify & Unverified
                        </p>
                    @endif --}}
                    {{-- @if (Auth::user()->role_id == 2)
                        @if ($get_daily_measures->is_lock > 0)
                            <button class="btn btn-outline-primary text-center mt-2 open_popup_for_verify"
                                style="border-radius: 10px">
                                <i class="bi bi-x-octagon"></i> Unverified All
                            </button>
                        @else
                            <button class="btn btn-outline-primary text-center mt-2 open_popup_for_verify"
                                style="border-radius: 10px">
                                <i class="bi bi-patch-check"></i> Verify All
                            </button>
                        @endif
                    @else --}}
                    <p class="text-center text-dark mb-0 font-weight-bold bold">
                        <strong>{{ date('H:i:s A', strtotime($get_daily_measures->end_time)) }}</strong>
                    </p>
                    {{-- @endif --}}

                </div>
            </div>
        </div>

        @if (Auth::user()->role_id == 1)
            <div class="page_card bg_white category_card_spacing">
                <div class="row">
                    @foreach ($get_all_category as $get_all_category_data)
                        <div class="col-6 mb-4">
                            <a class=""
                                href="{{ route($get_all_category_data->route, [Crypt::encrypt($get_all_category_data->id), $id]) }}">
                                <div class="measurement_card"
                                    style="background-color: {{ $get_all_category_data->card_color_code }}">
                                    <?php echo $get_all_category_data->cat_icon; ?>
                                </div>
                                <div class="card_title_color"
                                    style="background-color: {{ $get_all_category_data->title_color_code }}">
                                    <strong>{{ $get_all_category_data->cat_name }}</strong>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="row mt-4">
                {{-- For Blending List --}}
                <div class="col-6 mb-4">
                    <?php
                    $all_blending_item_count = $data_for_fsm['get_blending_item_counts'];
                    ?>
                    <a href="{{ route('blending_list', [Crypt::encrypt(5), $id]) }}">
                        <div class="card p-3">
                            <div class="card-title">
                                Blending
                            </div>
                            <hr style="margin-top: 5px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="blendingChart"></div>
                                    </div>
                                    <div class="col-12" style="margin-top: -25px;">
                                        <div class="row">
                                            <div class="col-6 text-end">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: blue;">●</span>
                                                    Submitted:
                                                    {{ $all_blending_item_count['total_submitted_blending_item'] }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: green;">●</span>
                                                    Verified:
                                                    {{ $all_blending_item_count['get_blending_item_verified_count'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- For Mix List --}}
                <div class="col-6 mb-4">
                    <?php
                    $all_mix_item_count = $data_for_fsm['get_mixing_item_counts'];
                    ?>
                    <a href="{{ route('mix_list', [Crypt::encrypt(6), $id]) }}">
                        <div class="card p-3">
                            <div class="card-title">
                                Mix
                            </div>
                            <hr style="margin-top: 5px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="mixingChart"></div>
                                    </div>
                                    <div class="col-12" style="margin-top: -25px;">
                                        <div class="row">
                                            <div class="col-6 text-end">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: blue;">●</span>
                                                    Submitted:
                                                    {{ $all_mix_item_count['total_submitted_mixing_item'] }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: green;">●</span>
                                                    Verified:
                                                    {{ $all_mix_item_count['get_mixing_item_verified_count'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- For Metal Detector List --}}
                <div class="col-6 mb-4">
                    <?php
                    $all_md_item_count = $data_for_fsm['get_md_item_counts'];
                    ?>
                    <a href="{{ route('metal_detector_list', [Crypt::encrypt(7), $id]) }}">
                        <div class="card p-3">
                            <div class="card-title">
                                Metal Detector
                            </div>
                            <hr style="margin-top: 5px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="all_md_item_count_chart"></div>
                                    </div>
                                    <div class="col-12" style="margin-top: -25px;">
                                        <div class="row">
                                            <div class="col-6 text-end">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: blue;">●</span>
                                                    Submitted: {{ $all_md_item_count['total_submitted_md_item'] }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: green;">●</span>
                                                    Verified:
                                                    {{ $all_md_item_count['get_md_item_verified_count'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- For Guacamole List --}}
                <div class="col-6 mb-4">
                    <?php
                    $all_guacamole_item_count = $data_for_fsm['get_guacamole_item_counts'];
                    ?>
                    <a href="{{ route('guacamole_list', [Crypt::encrypt(8), $id]) }}">
                        <div class="card p-3">
                            <div class="card-title">
                                Guacamole
                            </div>
                            <hr style="margin-top: 5px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="guacamole_list_chart"></div>
                                    </div>
                                    <div class="col-12" style="margin-top: -25px;">
                                        <div class="row">
                                            <div class="col-6 text-end">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: blue;">●</span>
                                                    Submitted:
                                                    {{ $all_guacamole_item_count['total_guacamole_item_submitted'] }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p style="font-size: 18px; margin: 5px 0;">
                                                    <span style="color: green;">●</span>
                                                    Verified:
                                                    {{ $all_guacamole_item_count['get_guacamole_item_verified_count'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="verify_and_unverified_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verify_and_unverified_modal_title"></h5>
                    <button type="button" class="close model_close border rounded border-danger text-danger"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body verify_and_unverified_modal_description text-center"></div>
                <form action="" class="verify_and_unverified_form" method="POST">
                    @csrf
                    <input type="hidden" name="daily_measures_id" value="{{ $get_daily_measures->id }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger model_close"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.open_popup_for_verify').on('click', function() {
                var modal_title = "Verify all the process";
                var modal_description =
                    "Are you sure you want to verify all measurements? Once verified, all records will be locked. You can only update measurement values after unverifying.";
                var verify_and_unverified_form = "{{ route('verify_all_form_action') }}";

                $('.verify_and_unverified_modal_title').text(modal_title);
                $('.verify_and_unverified_modal_description').text(modal_description);
                $('.verify_and_unverified_form').attr('action', verify_and_unverified_form);
                $('#verify_and_unverified_modal').modal('show');
            });

            $('.model_close').on('click', function() {
                $('#verify_and_unverified_modal').modal('hide');
            });


        });
    </script>
    @if (Auth::user()->role_id == 2)
        <script>
            var options = {
                series: [{{ $data_for_fsm['get_blending_item_counts']['blending_percentage'] }}],
                chart: {
                    type: 'radialBar',
                    height: 300
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#F7F2FD",
                            startAngle: -90,
                            endAngle: 90,
                        },
                        hollow: {
                            size: "60%",
                            color: '#F7F2FD'
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: "24px",
                                fontWeight: "bold",
                                color: "#000",
                                offsetY: 10
                            }
                        }
                    }
                },
                fill: {
                    colors: ['#A982DD']
                },
                labels: ["Blending"]
            };

            var chart = new ApexCharts(document.querySelector("#blendingChart"), options);
            chart.render();
        </script>
        <script>
            var options = {
                series: [{{ $all_mix_item_count['mixing_percentage'] }}],
                chart: {
                    type: 'radialBar',
                    height: 300
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#EDFAFC",
                            startAngle: -90,
                            endAngle: 90,
                        },
                        hollow: {
                            size: "60%",
                            color: '#EDFAFC'
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: "24px",
                                fontWeight: "bold",
                                color: "#000",
                                offsetY: 10
                            }
                        }
                    }
                },
                fill: {
                    colors: ['#49C6E6']
                },
                labels: ["Mix"]
            };

            var chart = new ApexCharts(document.querySelector("#mixingChart"), options);
            chart.render();
        </script>
        <script>
            var options = {
                series: [{{ $all_md_item_count['md_percentage'] }}],
                chart: {
                    type: 'radialBar',
                    height: 300
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#FEF8EB",
                            startAngle: -90,
                            endAngle: 90,
                        },
                        hollow: {
                            size: "60%",
                            color: '#FEF8EB'
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: "24px",
                                fontWeight: "bold",
                                color: "#000",
                                offsetY: 10
                            }
                        }
                    }
                },
                fill: {
                    colors: ['#FBB231']
                },
                labels: ["Mix"]
            };

            var chart = new ApexCharts(document.querySelector("#all_md_item_count_chart"), options);
            chart.render();
        </script>
        <script>
            var options = {
                series: [{{ $all_guacamole_item_count['guacamole_percentage'] }}],
                chart: {
                    type: 'radialBar',
                    height: 300
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#FEF1EE",
                            startAngle: -90,
                            endAngle: 90,
                        },
                        hollow: {
                            size: "60%",
                            color: '#FEF1EE'
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: "24px",
                                fontWeight: "bold",
                                color: "#000",
                                offsetY: 10
                            }
                        }
                    }
                },
                fill: {
                    colors: ['#EA724C']
                },
                labels: ["Mix"]
            };

            var chart = new ApexCharts(document.querySelector("#guacamole_list_chart"), options);
            chart.render();
        </script>
    @endif
@endsection
@endsection
