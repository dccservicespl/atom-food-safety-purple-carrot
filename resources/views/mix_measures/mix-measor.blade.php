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
            background-color: #49C6E6;
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
                <div class="col-8 mt-2">
                    <strong class="m-0 text-dark h3">{{ $get_the_item_details->item_name }}
                        {{ $get_the_item_details->weight > 0 ? $get_the_item_details->weight . ' oz' : ' ' }}
                    </strong>
                </div>
                <div class="col-4 text-end">
                    <p class="text-end mb-0 text-dark">
                        <i class="bi bi-calendar-check mr-2 p-1" style="color: #49C6E6"> </i> Measurement Date
                    </p>
                    <p class="text-end text-dark mb-0 font-weight-bold bold">
                        <strong> {{ date('m/d/Y', strtotime($get_the_measure_date->measure_date)) }} </strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="card shadow p-4">
                <form action="{{ route('mix_measure_action') }}" method="POST" id="mixing_measure_form" class="cta_btn_form">
                    @csrf
                    <input type="hidden" name="mixing_measure_id" value="{{ $date_id }}">
                    <input type="hidden" name="mixing_item_id" value="{{ $item_id }}">

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Odor</label></div>
                            <div class="col-5 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="odor" id="odor_pass" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="odor_pass" class="form__radio-label">
                                        <span class="form__radio-button"></span> Pass
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="odor" id="odor_fail" class="form__radio-input"
                                        value="F" required>
                                    <label class="form__label-radio" for="odor_fail" class="form__radio-label">
                                        <span class="form__radio-button"></span> Fail
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-5">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Appearance</label></div>
                            <div class="col-5 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="appearance" id="appearance_large" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="appearance_large" class="form__radio-label">
                                        <span class="form__radio-button"></span> Pass
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="appearance" id="appearance_small" class="form__radio-input"
                                        value="F" required>
                                    <label class="form__label-radio" for="appearance_small" class="form__radio-label">
                                        <span class="form__radio-button"></span> Fail
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-4">
                                <label for="ph_result" class="form-label text-dark">Temperature (°F)</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">1
                                        </div>
                                    </div>
                                    <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                        id="temperature" name="temperature_1" required>
                                </div>

                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">2
                                        </div>
                                    </div>
                                    <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                        id="temperature" name="temperature_2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
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
                                            <input type="number" maxlength="5"
                                                class="form-control p-2 border border-dark" id="weight_1"
                                                name="weight_1" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    2</div>
                                            </div>
                                            <input type="number" maxlength="5"
                                                class="form-control p-2 border border-dark" id="temperature"
                                                name="weight_2">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    3</div>
                                            </div>
                                            <input type="number" maxlength="5"
                                                class="form-control p-2 border border-dark" id="temperature"
                                                name="weight_3">
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <div class="input-group ">
                                            <div class="input-group-prepend ">
                                                <div
                                                    class="input-group-text p-2 ps-3 pe-3 border border-dark input_pre_label">
                                                    4</div>
                                            </div>
                                            <input type="number" maxlength="5"
                                                class="form-control p-2 border border-dark" id="temperature"
                                                name="weight_4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Table # / Line </label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control p-2 border border-dark" id="table"
                                    name="table" required>
                            </div>

                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Scale #</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control p-2 border border-dark" id="scale"
                                    name="scale" required>
                            </div>

                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="comments" class="form-label text-dark">Comments / Corrective Actions</label>
                        <textarea class="form-control border border-dark" id="comments" name="comments" rows="3"></textarea>
                    </div>

                    <div class="float-end mt-5">
                        <button type="button" onclick="history.back();"
                            class="btn btn-outline-secondary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">Cancel</button>
                        <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('scripts')
@endsection
@endsection
