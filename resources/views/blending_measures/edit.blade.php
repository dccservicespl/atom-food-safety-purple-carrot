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
    <script></script>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-8 mt-2">
                    <strong class="m-0 text-dark h3">{{ $get_the_item_details->item_name }}
                        <span class="text-primary"> - Batch #{{ $batch_no }}</span></strong>
                </div>
                <div class="col-4 text-end">
                    <p class="text-end mb-0 text-dark">
                        <i class="bi bi-calendar-check mr-2 p-1" style="color: #A982DD"> </i> Measurement Date
                    </p>
                    <p class="text-end text-dark mb-0 font-weight-bold bold">
                        <strong> {{ date('m/d/Y', strtotime($get_the_measure_date->measure_date)) }} </strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="card shadow p-4">
                <form action="{{ route('blending_details_update_action') }}" method="POST" id="blending_details_form"
                    class="cta_btn_form">
                    @csrf
                    <input type="hidden" name="blending_measure_id"
                        value="{{ $find_blending_measure_by_id->daily_measure_id }}">
                    <input type="hidden" name="blending_item_id"
                        value="{{ $find_blending_measure_by_id->blending_item_id }}">
                    <input type="hidden" name="id" value="{{ $find_blending_measure_by_id->id }}">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">PH Result</label>
                                <small class="d-block text-muted text-dark">PH Target: {{ $get_the_item_details->ph_min }}
                                    to {{ $get_the_item_details->ph_max }}</small>
                            </div>
                            <div class="col-4">
                                <input type="number" maxlength="7" class="form-control p-2 border border-dark"
                                    id="ph_result" name="ph_result" required
                                    value="{{ $find_blending_measure_by_id->ph_result }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="row">
                            <div class="col-8">
                                <label for="ph_result" class="form-label text-dark">Temperature (°F)</label>
                            </div>
                            <div class="col-4">
                                <input type="number" maxlength="7" class="form-control p-2 border border-dark"
                                    id="temperature" name="temperature" required
                                    value="{{ $find_blending_measure_by_id->temperature }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-5">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Appearance</label></div>
                            <div class="col-5 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="appearance" id="appearance_large" class="form__radio-input"
                                        value="P" required checked
                                        {{ $find_blending_measure_by_id->appearance == 'P' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="appearance_large" class="form__radio-label">
                                        <span class="form__radio-button"></span> Pass
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="appearance" id="appearance_small" class="form__radio-input"
                                        value="F" required
                                        {{ $find_blending_measure_by_id->appearance == 'F' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="appearance_small" class="form__radio-label">
                                        <span class="form__radio-button"></span> Fail
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Odor</label></div>
                            <div class="col-5 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="odor" id="odor_pass" class="form__radio-input"
                                        value="P" required checked
                                        {{ $find_blending_measure_by_id->odor == 'P' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="odor_pass" class="form__radio-label">
                                        <span class="form__radio-button"></span> Pass
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="odor" id="odor_fail" class="form__radio-input"
                                        value="F" required
                                        {{ $find_blending_measure_by_id->odor == 'F' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="odor_fail" class="form__radio-label">
                                        <span class="form__radio-button"></span> Fail
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div class="row">
                            <div class="col-4"><label class="form-label d-block text-dark">Taste</label></div>
                            <div class="col-5 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="taste" id="taste_pass" class="form__radio-input"
                                        value="P" required checked
                                        {{ $find_blending_measure_by_id->taste == 'P' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="taste_pass" class="form__radio-label">
                                        <span class="form__radio-button"></span> Pass
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 p-0 text-end">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="taste" id="taste_fail" class="form__radio-input"
                                        value="F" required
                                        {{ $find_blending_measure_by_id->taste == 'F' ? 'checked' : '' }}>
                                    <label class="form__label-radio" for="taste_fail" class="form__radio-label">
                                        <span class="form__radio-button"></span> Fail
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="comments" class="form-label text-dark">Comments / Corrective Actions</label>
                        <textarea class="form-control border border-dark" id="comments" name="comments" rows="3">{{ $find_blending_measure_by_id->comments }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="initial" class="form-label text-dark">Initial</label>
                        <textarea class="form-control border border-dark" id="initial" name="initial" rows="2">{{ $find_blending_measure_by_id->initial }}</textarea>
                    </div>

                    <div class="float-start mt-5">
                        <a href="{{ route('log_list', Crypt::encrypt($find_blending_measure_by_id->id)) }}"
                            class="btn btn-outline-warning pt-2 pb-2"><i class="bi bi-eye pe-1"></i>Log</a>

                        @if (check_old_pending_batch(
                                $find_blending_measure_by_id->daily_measure_id,
                                $find_blending_measure_by_id->blending_item_id,
                                'blending_measures'))
                            <a href="#" class="btn btn-outline-primary pt-2 pb-2 ms-2 pending_batch_popup">
                                <i class="bi-plus-circle pe-1"></i>
                                New Batch
                            </a>
                        @else
                            <a href="{{ route('create_new_batch', $find_blending_measure_by_id->id) }}"
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
                        {{-- <a href="{{ route('blending_list', [Crypt::encrypt(5), Crypt::encrypt($find_blending_measure_by_id->daily_measure_id)]) }}"
                            class="btn btn-outline-secondary pt-2 pb-2 ps-5 pe-5 me-4">Cancel</a> --}}
                        <button type="submit"
                            class="btn btn-primary pt-2 pb-2 ps-5 pe-5">{{ Auth::user()->role_id == 2 ? 'Update & Verify' : 'Update' }}</button>

                        <a href="{{ route('unverified_measure_item', [$find_blending_measure_by_id->id, 'blending_measures']) }}"
                            class="btn btn-outline-primary pt-2 pb-2 me-4 unverified_measure_item" style="display: none;">
                            Unverified
                        </a>
                    </div>
                </form>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ route('delete_blending_measure', $find_blending_measure_by_id->id) }}" type="button"
                        class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <?php echo disabled_all_input($find_blending_measure_by_id->status, 'blending_details_form', 'unverified_measure_item'); ?>

    <script>
        $(document).ready(function() {
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
    {{-- <script>
        $(document).ready(function() {
            $('.open_popup_for_verify').on('click', function() {
                var modal_title = "Verify the process";
                var modal_description = "Do you want to verify measurement now?";

                $('.verify_and_unverified_modal_title').text(modal_title);
                $('.verify_and_unverified_modal_description').text(modal_description);
                $('#verify_and_unverified_modal').modal('show');
            });

            $('.model_close').on('click', function() {
                $('#verify_and_unverified_modal').modal('hide');
            });
        });
    </script> --}}
@endsection
@endsection
