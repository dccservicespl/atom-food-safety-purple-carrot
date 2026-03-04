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
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-8 mt-2">
                    <strong class="m-0 text-dark h5"> Add Items </strong>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="card shadow p-4">
                <form action="#" method="POST" id="mixing_measure_form" class="cta_btn_form">
                    @csrf
                    <div class="mb-5 mt-3">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group ">
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text bg_light border_dark h-100 pt-2 pb-2 ps-3 pe-3">
                                            Weight 1
                                        </div>
                                    </div>
                                    <input type="number" class="form-control border_dark pt-2 pb-2 ps-3 pe-3"
                                        name="weight_1" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text bg_light border_dark h-100 pt-2 pb-2 ps-3 pe-3">
                                            Weight 2
                                        </div>
                                    </div>
                                    <input type="number" class="form-control border_dark pt-2 pb-2 ps-3 pe-3"
                                        name="weight_2">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group ">
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text bg_light border_dark h-100 pt-2 pb-2 ps-3 pe-3">
                                            Weight 3
                                        </div>
                                    </div>
                                    <input type="number" class="form-control border_dark pt-2 pb-2 ps-3 pe-3"
                                        name="weight_3">
                                </div>
                            </div>
                        </div>
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
