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
            background-color: #A982DD;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .form__radio-input:checked~.form__label-radio .form__radio-button::after {
            opacity: 1;
        }
    </style>
    {{-- <div class="container-fluid">
        <?php //echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-8 mt-2">
                    <strong class="m-0 text-dark h5"> {{ $item_details->component_details ?? '-' }} </strong>
                </div>
                <div class="col-4 text-end">
                    <p class="text-end mb-0 text-dark">
                        <i class="bi bi-calendar-check mr-2 p-1" style="color: #A982DD"> </i> Measurement Date
                    </p>
                    <p class="text-end text-dark mb-0 font-weight-bold bold">
                        <strong> {{ date('m/d/Y') }} </strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="card shadow p-4">
                <form action="#" method="POST" id="mixing_measure_form" class="cta_btn_form">
                    @csrf
                    <div class="mb-5 mt-3">
                        <div class="row">
                            <div class="col-2"><label class="form-label d-block text-dark">Equipment</label></div>
                            <div class="col-10">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="equipment" id="auger_9" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="auger_9" class="form__radio-label">
                                        <span class="form__radio-button"></span> Auger 9
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="equipment" id="auger_12" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="auger_12" class="form__radio-label">
                                        <span class="form__radio-button"></span> Auger 12
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="equipment" id="piston_9" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="piston_9" class="form__radio-label">
                                        <span class="form__radio-button"></span> Piston 9
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="equipment" id="sleek" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="sleek" class="form__radio-label">
                                        <span class="form__radio-button"></span> Sleek
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="equipment" id="breeze" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="breeze" class="form__radio-label">
                                        <span class="form__radio-button"></span> Breeze
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="equipment" id="versa_pack" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="versa_pack" class="form__radio-label">
                                        <span class="form__radio-button"></span> Versa Pack
                                    </label>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="mb-5 mt-3">
                        <div class="row">
                            <div class="col-2"><label class="form-label d-block text-dark">Table</label></div>
                            <div class="col-10">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_1" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_1" class="form__radio-label">
                                        <span class="form__radio-button"></span> 1
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_2" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_2" class="form__radio-label">
                                        <span class="form__radio-button"></span> 2
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_3" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_3" class="form__radio-label">
                                        <span class="form__radio-button"></span> 3
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_4" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_4" class="form__radio-label">
                                        <span class="form__radio-button"></span> 4
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_5" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_5" class="form__radio-label">
                                        <span class="form__radio-button"></span> 5
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_6" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_6" class="form__radio-label">
                                        <span class="form__radio-button"></span> 6
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_7" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_7" class="form__radio-label">
                                        <span class="form__radio-button"></span> 7
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="table_8" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="table_8" class="form__radio-label">
                                        <span class="form__radio-button"></span> 8
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="table" id="piston_10" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="piston_10" class="form__radio-label">
                                        <span class="form__radio-button"></span> Piston 10
                                    </label>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Pre-Op Complete</label></div>
                            <div class="col-5 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="odor" id="odor_pass" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="odor_pass" class="form__radio-label">
                                        <span class="form__radio-button"></span> Yes
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="odor" id="odor_fail" class="form__radio-input"
                                        value="F" required>
                                    <label class="form__label-radio" for="odor_fail" class="form__radio-label">
                                        <span class="form__radio-button"></span> No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">People Qty</label>
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
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Lot number</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control p-2 border border-dark" id="lot_number"
                                    name="lot_number" required>
                            </div>

                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
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

                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Allergen(If
                                    applicable)</label></div>
                            <div class="col-4 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="allergen" id="aller_yes" class="form__radio-input"
                                        value="P" required checked>
                                    <label class="form__label-radio" for="aller_yes" class="form__radio-label">
                                        <span class="form__radio-button"></span> Yes
                                    </label>
                                </div>
                            </div>
                            <div class="col-2 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="allergen" id="aller_no" class="form__radio-input"
                                        value="F" required>
                                    <label class="form__label-radio" for="aller_no" class="form__radio-label">
                                        <span class="form__radio-button"></span> No
                                    </label>
                                </div>
                            </div>
                            <div class="col-2 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="allergen" id="aller_na" class="form__radio-input"
                                        value="N/A" required>
                                    <label class="form__label-radio" for="aller_na" class="form__radio-label">
                                        <span class="form__radio-button"></span> N/A
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Pack size</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                        id="pack_size" name="pack_size" required>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-4">
                                <label for="ph_result" class="form-label text-dark">Sample</label>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-4">
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
                                    <div class="col-4">
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
                                    <div class="col-4">
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Kit letter</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                        id="pack_size" name="pack_size" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Qty produces (Final)</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                        id="pack_size" name="pack_size" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">FS initial</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <input type="number" maxlength="5" class="form-control p-2 border border-dark"
                                        id="pack_size" name="pack_size" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Upload Attachment</label>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <input type="file"class="form-control p-2 border border-dark" id="pack_size"
                                        name="pack_size" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="comments" class="form-label text-dark">Product Description</label>
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
    </div> --}}
    <livewire:portioning-measurement-manager />
@section('scripts')
@endsection
@endsection
