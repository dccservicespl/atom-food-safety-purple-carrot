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
                        <i class="bi bi-calendar-check mr-2 p-1" style="color: #A982DD"> </i> Measurement Date
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
                                    <strong><i class="bi bi-clock mr-2 p-1" style="color: #A982DD"> </i>
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
                                <strong class="m-0 text-700 h6"> pH Result </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->ph_result_new != $get_all_blending_measure_log_data->ph_result_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->ph_result_new }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Temperature</strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->temperature_new != $get_all_blending_measure_log_data->temperature_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->temperature_new }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Appearance </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->appearance_new != $get_all_blending_measure_log_data->appearance_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->appearance_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Odor </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->odor_new != $get_all_blending_measure_log_data->odor_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->odor_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>
                            <div class="col-4 mt-1 mb-3">
                                <strong class="m-0 text-700 h6"> Taste </strong>
                                <p style="font-weight: 700"
                                    class="{{ $get_all_blending_measure_log_data->taste_new != $get_all_blending_measure_log_data->taste_old ? 'text-danger' : 'text-dark' }}">
                                    {{ $get_all_blending_measure_log_data->taste_new == 'P' ? 'Pass' : 'Fail' }}
                                </p>
                            </div>
                            <div class="col-4 mt-1">
                                <strong class="m-0 text-700 h6"> Initial </strong>
                                <p>
                                    <span style="font-weight: 700"
                                        class="m-0 text-dark h6">{{ $get_all_blending_measure_log_data->initial_new }}</span>
                                </p>
                            </div>
                            <div class="col-12 mt-0">
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
