@extends('layouts.main')
@section('content')
    <style>
        .form__radio-input {
            display: none;
        }

        .form__label-radio {
            font-size: 1.6rem;
            cursor: pointer;
            position: relative;
            padding-left: 3.2rem;
        }

        .form__radio-button {
            height: 2.5rem;
            width: 2.5rem;
            border: 2px solid #344050;
            border-radius: 50%;
            display: inline-block;
            position: absolute;
            left: 0;
            top: -0.4rem;
        }

        .form__radio-button::after {
            content: "";
            display: block;
            height: 1.3rem;
            width: 1.3rem;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #EA724C;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .form__radio-input:checked~.form__label-radio .form__radio-button::after {
            opacity: 1;
        }
    </style>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-5 col-lg-6 mt-2">
                    <strong class="m-0 text-dark h3">{{ $get_the_item_details->item_name }}
                        {{ $get_the_item_details->weight > 0 ? $get_the_item_details->weight . ' oz' : ' ' }}
                        <span class="text-warning"> - Batch #{{ $batch_no }}</span>
                    </strong>
                    <p class="text-start text-dark mb-0 font-weight-bold bold">
                        <strong> <i class="bi bi-calendar-check mr-2 p-1" style="color: #EA724C"> </i>
                            {{ date('m/d/Y', strtotime($get_the_measure_date->measure_date)) }} </strong>
                    </p>
                </div>
                <div class="col-7 col-lg-6 text-end">
                    <a href="#" class="show_box_bar_code_image_popup_trigger pe-3">
                        @if ($find_blending_measure_by_id)
                            @if ($find_blending_measure_by_id->item_bar_code)
                                <img src="{{ $find_blending_measure_by_id->item_bar_code }}" alt=""
                                    style="height: 50px">
                            @else
                                <img src="/assets/img/img_icon.png" alt="" style="height: 55px">
                            @endif
                        @else
                            <img src="/assets/img/img_icon.png" alt="" style="height: 55px">
                        @endif

                    </a>
                    <button class="btn btn-outline-warning pt-3 pb-3 upload_box_bar_code" type="submit">
                        <i class="bi bi-upload text-lg pe-2 upload_box_bar_code"></i>
                        Upload barcode
                    </button>
                </div>
                {{-- <div class="col-5 text-end">
                    <button class="btn btn-outline-danger pt-3 pb-3 upload_box_bar_code">
                        <i class="bi bi-upload text-lg pe-2 upload_box_bar_code"></i>
                        Item BAR Code
                    </button>
                </div> --}}
            </div>
        </div>
        <div class="mt-3">
            <div class="card shadow p-4">
                <form action="{{ route('guacamole_measure_update_action') }}" method="POST" id="guacamole_measure_form"
                    class="cta_btn_form">
                    @csrf
                    <input type="hidden" name="daily_measures_id" value="{{ $daily_measures_id }}">
                    <input type="hidden" name="guacamole_item_id" value="{{ $item_id }}">
                    <input type="hidden" name="guacamole_measure_id" value="{{ $find_blending_measure_by_id->id }}">
                    <input type="hidden" name="batch_no" value="{{ $batch_no }}">

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Lot Number</label>
                            </div>
                            <div class="col-4">
                                <input type="text" maxlength="18" class="form-control p-2 border border-dark"
                                    id="lot_number" name="lot_number"
                                    value="{{ $find_blending_measure_by_id->lot_number }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Temperature (°F)</label>
                            </div>
                            <div class="col-4">
                                <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                    id="temperature" name="temperature"
                                    value="{{ $find_blending_measure_by_id->temperature }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <div class="col-4 mt-3">
                                <label class="form-label d-block text-dark">Metal Detector Model </label>
                            </div>
                            <div class="col-4 p-0 text-end mt-3">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="md_model_result" id="md_model_result_pass"
                                        class="form__radio-input" value="Kick out"
                                        {{ $find_blending_measure_by_id->md_model_result == 'Kick out' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="md_model_result_pass" class="form__radio-label">
                                        <span class="form__radio-button"></span> Kick out
                                    </label>
                                </div>
                            </div>
                            <div class="col-4 p-0 text-end mt-3">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="md_model_result" id="md_model_result_fail"
                                        class="form__radio-input" value="Belt Stop"
                                        {{ $find_blending_measure_by_id->md_model_result == 'Belt Stop' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="md_model_result_fail" class="form__radio-label">
                                        <span class="form__radio-button"></span> Belt Stop
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-danger mb-0" style="font-size: 1.5em; font-weight:600"> Metal Detector Check
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-5">
                        <div class="row">
                            <div class="col-6">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Fe</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="fe" id="fe_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->md_fe == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="fe_pass" class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="fe" id="fe_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->md_fe == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="fe_fail" class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">NFe</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="nfe" id="nfe_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->md_nfe == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="nfe_pass" class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="nfe" id="nfe_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->md_nfe == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="nfe_fail" class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">St</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="st" id="st_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->md_st == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="st_pass" class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="st" id="st_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->md_st == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="st_fail" class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-danger mb-0" style="font-size: 1.5em; font-weight:600"> Seal Check
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-5">
                        <div class="row">
                            <div class="col-6">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Sample 1</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="batch_1" id="batch_1_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->sc_batch_1 == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_1_pass"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="batch_1" id="batch_1_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->sc_batch_1 == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_1_fail"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Sample 2</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="batch_2" id="batch_2_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->sc_batch_2 == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_2_pass"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="batch_2" id="batch_2_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->sc_batch_2 == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_2_fail"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Sample 3</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="batch_3" id="batch_3_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->sc_batch_3 == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_3_pass"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="batch_3" id="batch_3_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->sc_batch_3 == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_3_fail"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Sample 4</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="batch_4" id="batch_4_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->sc_batch_4 == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_4_pass"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="batch_4" id="batch_4_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->sc_batch_4 == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_4_fail"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Sample 5</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="batch_5" id="batch_5_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->sc_batch_5 == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_5_pass"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="batch_5" id="batch_5_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->sc_batch_5 == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_5_fail"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mt-3">
                                <div class="row radio_button_ui">
                                    <div class="col-12 mb-2">
                                        <label class="form-label d-block text-dark">Sample 6</label>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline ps-0">
                                            <input type="radio" name="batch_6" id="batch_6_pass"
                                                class="form__radio-input" value="P"
                                                {{ $find_blending_measure_by_id->sc_batch_6 == 'P' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_6_pass"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Pass
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0 text-start">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="batch_6" id="batch_6_fail"
                                                class="form__radio-input" value="F"
                                                {{ $find_blending_measure_by_id->sc_batch_6 == 'F' ? 'checked' : '' }}>
                                            <label class="form__label-radio" for="batch_6_fail"
                                                class="form__radio-label">
                                                <span class="form__radio-button"></span> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-5">
                        <div class="row">
                            <div class="col-4">
                                <label for="ph_result" class="form-label text-dark">Weight Checks</label>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    1</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="weight_1"
                                                name="weight_1"
                                                value="{{ $find_blending_measure_by_id->weight_checks_1 }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    2</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="temperature"
                                                name="weight_2"
                                                value="{{ $find_blending_measure_by_id->weight_checks_2 }}">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    3</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="temperature"
                                                name="weight_3"
                                                value="{{ $find_blending_measure_by_id->weight_checks_3 }}">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    4</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="temperature"
                                                name="weight_4"
                                                value="{{ $find_blending_measure_by_id->weight_checks_4 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-4">
                                <label for="ph_result" class="form-label text-dark">Oxygen Levels</label>
                                <p class="mb-0">Less than 1.5% </p>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    1</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="oxygen_levels_1"
                                                name="oxygen_levels_1"
                                                value="{{ $find_blending_measure_by_id->oxygen_levels_1 }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    2</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="oxygen_levels_2"
                                                name="oxygen_levels_2"
                                                value="{{ $find_blending_measure_by_id->oxygen_levels_2 }}">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    3</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="oxygen_levels_3"
                                                name="oxygen_levels_3"
                                                value="{{ $find_blending_measure_by_id->oxygen_levels_3 }}">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    4</div>
                                            </div>
                                            <input type="number" maxlength="8"
                                                class="form-control p-2 border border-dark" id="oxygen_levels_4"
                                                name="oxygen_levels_4"
                                                value="{{ $find_blending_measure_by_id->oxygen_levels_4 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-4">
                                <label for="ph_result" class="form-label text-dark">Packaging Lot Numbers</label>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    LIDS</div>
                                            </div>
                                            <input type="text" maxlength="18"
                                                class="form-control p-2 border border-dark" id="lids" name="lids"
                                                value="{{ $find_blending_measure_by_id->lids }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    CUPS </div>
                                            </div>
                                            <input type="text" maxlength="18"
                                                class="form-control p-2 border border-dark" id="cups" name="cups"
                                                value="{{ $find_blending_measure_by_id->cups }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Total Containers Produced</label>
                            </div>
                            <div class="col-4">
                                <input type="number" maxlength="8" class="form-control p-2 border border-dark"
                                    id="total_containers" name="total_containers"
                                    value="{{ $find_blending_measure_by_id->total_containers }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Retains Collected</label>
                            </div>
                            <div class="col-4">
                                <input type="text" maxlength="10" class="form-control p-2 border border-dark"
                                    id="retains_collected" name="retains_collected"
                                    value="{{ $find_blending_measure_by_id->retains_collected }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Best By Date</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <input type="date" class="form-control p-2 border border-dark" id="best_by_date"
                                        name="best_by_date" value="{{ $find_blending_measure_by_id->best_by_date }}"
                                        min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="comments" class="form-label text-dark">Comments</label>
                        <textarea class="form-control border border-dark" id="comments" name="comments" rows="3">{{ $find_blending_measure_by_id->comments }}</textarea>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="comments" class="form-label text-dark">Initial</label>
                        <textarea class="form-control border border-dark" id="initial" name="initial" rows="2">{{ $find_blending_measure_by_id->initial }}</textarea>
                    </div>

                    <div class="float-start mt-5">
                        <a href="{{ route('guacamole_log', Crypt::encrypt($find_blending_measure_by_id->id)) }}"
                            type="submit" class="btn btn-outline-warning pt-2 pb-2"><i class="bi bi-eye pe-1"></i>
                            Log</a>
                        @if (check_old_pending_batch(Crypt::decrypt($daily_measures_id), Crypt::decrypt($item_id), 'guacamole_measures'))
                            <a href="#" class="btn btn-outline-primary pt-2 pb-2 ms-2 pending_batch_popup">
                                <i class="bi-plus-circle pe-1"></i>
                                New Batch
                            </a>
                        @else
                            <a href="{{ route('create_new_guacamole_batch', $find_blending_measure_by_id->id) }}"
                                class="btn btn-outline-primary pt-2 pb-2 ms-2"><i class="bi-plus-circle pe-1"></i>New
                                Batch</a>
                        @endif

                        @if (Auth::user()->role_id == 2)
                            <a href="#" class="btn btn-outline-danger pt-2 pb-2 ms-2 delete_blending_measure_popup">
                                <i class="bi-trash pe-1"></i>
                                Delete
                            </a>
                        @endif
                    </div>

                    <div class="float-end mt-5">
                        {{-- <a type="button" href="{{ route('guacamole_list', [Crypt::encrypt(8), $daily_measures_id]) }}"
                            class="btn btn-outline-secondary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">Cancel</a> --}}
                        <a href="{{ route('unverified_measure_item', [$find_blending_measure_by_id->id, 'guacamole_measures']) }}"
                            class="btn btn-outline-primary pt-2 pb-2 me-4 unverified_measure_item" style="display: none;">
                            Unverified
                        </a>
                        <button type="submit"
                            class="btn btn-primary pt-2 pb-2 ps-5 pe-5">{{ Auth::user()->role_id == 2 ? 'Update & Verify' : 'Update' }}</button>
                    </div>
                </form>
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
                    <form action="{{ route('update_box_bar_code_action') }}" class="upload_image_form" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="guacamole_measure_id"
                            value="{{ $find_blending_measure_by_id->id }}">
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
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
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
                    @if ($find_blending_measure_by_id)
                        @if ($find_blending_measure_by_id->item_bar_code)
                            <img src="{{ $find_blending_measure_by_id->item_bar_code }}" alt=""
                                style='max-width:100%; height:350px'>
                        @else
                            <img src="/assets/img/img_icon.png" alt="" style='max-width:100%; height:350px'>
                        @endif
                    @else
                        <img src="/assets/img/img_icon.png" alt="" style='width:100%; height:350px'>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- pending_batch_popup --}}
    <div class="modal" tabindex="-1" role="dialog" id="pending_batch_popup_model">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alert</h5>
                    <button type="button" class="close model_close border border-danger text-danger rounded"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>A batch with this measurement is already pending. Please submit the existing batch before creating a
                        new one.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- delete measure --}}
    <div class="modal" tabindex="-1" role="dialog" id="delete_blending_measure">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alert</h5>
                    <button type="button" class="close model_close border border-danger text-danger rounded"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure? Deleting this batch will remove all related data, including logs associated with this
                        measurement.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary model_close" data-dismiss="modal">Close</button>
                    <a href="{{ route('delete_guacamole_measure', $find_blending_measure_by_id->id) }}" type="button"
                        class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <?php echo disabled_all_input($find_blending_measure_by_id->status, 'guacamole_measure_form', 'unverified_measure_item'); ?>

    <script>
        $(document).ready(function() {
            $('.upload_box_bar_code').on('click', function(e) {
                e.preventDefault();
                $('#exampleModalCenter').modal('show');
            });

            $('.model_close').on('click', function(e) {
                e.preventDefault();
                $('#exampleModalCenter').modal('hide');
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
            $('.pending_batch_popup').on('click', function() {
                $('#pending_batch_popup_model').modal('show');
            });

            $('.delete_blending_measure_popup').on('click', function() {
                $('#delete_blending_measure').modal('show');
            });

            $('.model_close').on('click', function(e) {
                e.preventDefault();
                $('#exampleModalCenter').modal('hide');
                $('#show_box_bar_code_image_popup').modal('hide');
                $('#show_box_bar_code_image_popup').hide();
                $('#pending_batch_popup_model').modal('hide');
                $('#delete_blending_measure').modal('hide');
            });
        })
    </script>
@endsection
@endsection
