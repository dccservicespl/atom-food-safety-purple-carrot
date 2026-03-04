@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-8 mt-2">
                    <strong class="m-0 text-dark h3">{{ $get_measure_item->item_name }}</strong>
                </div>
                <div class="col-4 text-end">
                    <p class="text-end mb-0 text-dark">
                        <i class="bi bi-calendar-check mr-2 p-1" style="color: #FBB231"> </i> Measurement Date
                    </p>
                    <p class="text-end text-dark mb-0 font-weight-bold bold">
                        <strong> {{ date('m/d/Y', strtotime($get_measure_item->measure_date)) }} </strong>
                    </p>
                </div>
            </div>
        </div>
        @if ($get_all_blending_measure_log->isNotEmpty())
            @foreach ($get_all_blending_measure_log as $get_all_blending_measure_log_data)
                <div class="card mt-3" style="box-shadow: none;">
                    <div class="card-header bg-100">
                        <div class="row">
                            <div class="col-6">
                                <p class="text-start mb-0 text-dark">
                                    <strong><i class="bi bi-clock mr-2 p-1" style="color: #FBB231"> </i>
                                        {{ date('H:i A', strtotime($get_all_blending_measure_log_data->created_at)) }}
                                    </strong>
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="text-end mb-0 text-700">
                                    <span><i class="bi bi-circle-fill text-danger"></i> Modified</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="page_card bg_white mt-0">
                        <div class="row">
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Metal Detector Model </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->md_model_result_old != $get_all_blending_measure_log_data->md_model_result_new ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->md_model_result_new }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> FE </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->md_fe_new != $get_all_blending_measure_log_data->md_fe_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->md_fe_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Nfe </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->md_nfe_new != $get_all_blending_measure_log_data->md_nfe_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->md_nfe_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> St </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->md_st_new != $get_all_blending_measure_log_data->md_st_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->md_st_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Batch 1 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->sc_batch_1_new != $get_all_blending_measure_log_data->sc_batch_1_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->sc_batch_1_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Batch 2 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->sc_batch_2_new != $get_all_blending_measure_log_data->sc_batch_2_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->sc_batch_2_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Batch 3 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->sc_batch_3_new != $get_all_blending_measure_log_data->sc_batch_3_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->sc_batch_3_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Batch 4 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->sc_batch_4_new != $get_all_blending_measure_log_data->sc_batch_4_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->sc_batch_4_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Batch 5 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->sc_batch_5_new != $get_all_blending_measure_log_data->sc_batch_5_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->sc_batch_5_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Batch 6 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->sc_batch_6_new != $get_all_blending_measure_log_data->sc_batch_6_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->sc_batch_6_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Weight Checks 1 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->weight_checks_1_new != $get_all_blending_measure_log_data->weight_checks_1_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->weight_checks_1_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Weight Checks 2 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->weight_checks_2_new != $get_all_blending_measure_log_data->weight_checks_2_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->weight_checks_2_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Weight Checks 3 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->weight_checks_3_new != $get_all_blending_measure_log_data->weight_checks_3_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->weight_checks_3_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Weight Checks 4 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->weight_checks_4_new != $get_all_blending_measure_log_data->weight_checks_4_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->weight_checks_4_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Oxygen Levels 1 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->oxygen_levels_1_new != $get_all_blending_measure_log_data->oxygen_levels_1_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->oxygen_levels_1_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Oxygen Levels 2 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->oxygen_levels_2_new != $get_all_blending_measure_log_data->oxygen_levels_2_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->oxygen_levels_2_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Oxygen Levels 3 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->oxygen_levels_3_new != $get_all_blending_measure_log_data->oxygen_levels_3_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->oxygen_levels_3_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Oxygen Levels 4 </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->oxygen_levels_4_new != $get_all_blending_measure_log_data->oxygen_levels_4_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->oxygen_levels_4_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> CUPS </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->cups_new != $get_all_blending_measure_log_data->cups_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->cups_new }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> lids </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->lids_new != $get_all_blending_measure_log_data->lids_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->lids_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Total Containers </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->total_containers_new != $get_all_blending_measure_log_data->total_containers_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->total_containers_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Retains Collected </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->retains_collected_new != $get_all_blending_measure_log_data->retains_collected_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->retains_collected_new }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Best By Date </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->best_by_date_new != $get_all_blending_measure_log_data->best_by_date_new ? 'text-danger' : 'text-dark' }}">
                                    {{ date('m-d-Y', strtotime($get_all_blending_measure_log_data->best_by_date_new)) }}
                                </p>
                            </div>

                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Initial </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->initial_new != $get_all_blending_measure_log_data->initial_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->initial_new }}
                                </p>
                            </div>

                            <div class="col-8 mt-0">
                                <strong class="m-0 text-700 h6"> Comments </strong>
                                <p class="m-0">
                                    <span class="m-0 text-dark h6"> {{ $get_all_blending_measure_log_data->comments_new }}
                                    </span>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="page_card bg_white mt-3">
                <div class="p-3">
                    <?php echo no_record_found_in_table(); ?>
                </div>
            </div>
        @endif
    </div>

@section('scripts')
@endsection
@endsection
