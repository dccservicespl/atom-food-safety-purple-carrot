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
    <?php //dd($existing_data);
    ?>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="page_card bg_white">
            <div class="row">
                <div class="col-8 mt-2">
                    <p class="m-0">Label Inspection of </p>
                    <strong class="m-0 text-dark h3">{{ $get_the_item_details->item_name }}</strong>
                </div>
                <div class="col-4 text-end">
                    <p class="text-end mb-0 text-dark">
                        <i class="bi bi-calendar-check mr-2 p-1" style="color: #A982DD"> </i> Inspection Date
                    </p>
                    <p class="text-end text-dark mb-0 font-weight-bold bold">
                        <strong> {{ date('m/d/Y', strtotime($get_the_measure_date->measure_date)) }} </strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="card shadow p-4">
                <form action="{{ route('inspection_details_form_action') }}" method="POST" id="blending_details_form"
                    class="label_inspection_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $request_data['item_id'] }}">
                    {{-- <input type="hidden" name="category_id" value="{{ $request_data['category_id'] }}"> --}}
                    <input type="hidden" name="measure_id" value="{{ $request_data['measure_id'] }}">
                    <input type="hidden" name="redirect_route" value="{{ $route_name }}">

                    <div class="row">
                        <!-- COO Present -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row radio_button_ui">
                                <div class="col-12 mb-2">
                                    <label class="form-label d-block text-dark">COO Present</label>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="coo_present" id="coo_present_pass"
                                            class="form__radio-input" value="P"
                                            {{ old('coo_present', optional($existing_data)->coo_present ?? 'P') == 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="coo_present_pass">
                                            <span class="form__radio-button"></span> Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="coo_present" id="coo_present_fail"
                                            class="form__radio-input" value="F"
                                            {{ optional($existing_data)->coo_present == 'F' ? 'checked' : '' }} required>
                                        <label class="form__label-radio" for="coo_present_fail">
                                            <span class="form__radio-button"></span> No
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="coo_present" id="coo_present_na"
                                            class="form__radio-input" value="N/A"
                                            {{ optional($existing_data)->coo_present == 'N/A' ? 'checked' : '' }} required>
                                        <label class="form__label-radio" for="coo_present_na">
                                            <span class="form__radio-button"></span> N/A
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Best By Accurate -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row radio_button_ui">
                                <div class="col-12 mb-2">
                                    <label class="form-label d-block text-dark">Best By Accurate</label>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="best_by_accurate" id="best_by_accurate_pass"
                                            class="form__radio-input" value="P"
                                            {{ old('best_by_accurate', optional($existing_data)->best_by_accurate ?? 'P') == 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="best_by_accurate_pass">
                                            <span class="form__radio-button"></span>Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="best_by_accurate" id="best_by_accurate_fail"
                                            class="form__radio-input" value="F"
                                            {{ optional($existing_data)->best_by_accurate == 'F' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="best_by_accurate_fail">
                                            <span class="form__radio-button"></span> No
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="best_by_accurate" id="best_by_accurate_na"
                                            class="form__radio-input" value="N/A"
                                            {{ optional($existing_data)->best_by_accurate == 'N/A' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="best_by_accurate_na"><span
                                                class="form__radio-button"></span> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nutritional Facts -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row radio_button_ui">
                                <div class="col-12 mb-2">
                                    <label class="form-label d-block text-dark">Nutritional Facts</label>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="nutritional_acts" id="nutritional_acts_pass"
                                            class="form__radio-input" value="P"
                                            {{ old('nutritional_acts', optional($existing_data)->nutritional_acts ?? 'P') == 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="nutritional_acts_pass">
                                            <span class="form__radio-button"></span>Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="nutritional_acts" id="nutritional_acts_fail"
                                            class="form__radio-input" value="F"
                                            {{ optional($existing_data)->nutritional_acts == 'F' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="nutritional_acts_fail">
                                            <span class="form__radio-button"></span> No
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="nutritional_acts" id="nutritional_acts_na"
                                            class="form__radio-input" value="N/A"
                                            {{ optional($existing_data)->nutritional_acts == 'N/A' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="nutritional_acts_na"><span
                                                class="form__radio-button"></span> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Allergen Statement -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row radio_button_ui">
                                <div class="col-12 mb-2">
                                    <label class="form-label d-block text-dark">Allergen Statement</label>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="allergen_statement" id="allergen_statement_pass"
                                            class="form__radio-input" value="P"
                                            {{ old('allergen_statement', optional($existing_data)->allergen_statement ?? 'P') == 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="allergen_statement_pass">
                                            <span class="form__radio-button"></span>Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="allergen_statement" id="allergen_statement_fail"
                                            class="form__radio-input" value="F"
                                            {{ optional($existing_data)->allergen_statement == 'F' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="allergen_statement_fail">
                                            <span class="form__radio-button"></span> No
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="allergen_statement" id="allergen_statement_na"
                                            class="form__radio-input" value="N/A"
                                            {{ optional($existing_data)->allergen_statement == 'N/A' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="allergen_statement_na"><span
                                                class="form__radio-button"></span> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ingredient Statement -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row radio_button_ui">
                                <div class="col-12 mb-2">
                                    <label class="form-label d-block text-dark">Ingredient Statement</label>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="ingredient_statement" id="ingredient_statement_pass"
                                            class="form__radio-input" value="P"
                                            {{ old('ingredient_statement', optional($existing_data)->ingredient_statement ?? 'P') == 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="ingredient_statement_pass">
                                            <span class="form__radio-button"></span>Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="ingredient_statement" id="ingredient_statement_fail"
                                            class="form__radio-input" value="F"
                                            {{ optional($existing_data)->ingredient_statement == 'F' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="ingredient_statement_fail">
                                            <span class="form__radio-button"></span> No
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="ingredient_statement" id="ingredient_statement_na"
                                            class="form__radio-input" value="N/A"
                                            {{ optional($existing_data)->ingredient_statement == 'N/A' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="ingredient_statement_na"><span
                                                class="form__radio-button"></span> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barcode Clear -->
                        <div class="col-12 col-md-6 mt-3">
                            <div class="row radio_button_ui">
                                <div class="col-12 mb-2">
                                    <label class="form-label d-block text-dark">Barcode Clear</label>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="barcode_clear" id="barcode_clear_pass"
                                            class="form__radio-input" value="P"
                                            {{ old('barcode_clear', optional($existing_data)->barcode_clear ?? 'P') == 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="barcode_clear_pass">
                                            <span class="form__radio-button"></span>Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="barcode_clear" id="barcode_clear_fail"
                                            class="form__radio-input" value="F"
                                            {{ optional($existing_data)->barcode_clear == 'F' ? 'checked' : '' }} required>
                                        <label class="form__label-radio" for="barcode_clear_fail">
                                            <span class="form__radio-button"></span> No
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 p-0 text-center">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="barcode_clear" id="barcode_clear_na"
                                            class="form__radio-input" value="N/A"
                                            {{ optional($existing_data)->barcode_clear == 'N/A' ? 'checked' : '' }}
                                            required>
                                        <label class="form__label-radio" for="barcode_clear_na"><span
                                                class="form__radio-button"></span> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-3 mt-4">
                                <label for="comments" class="form-label text-dark">Operator Initials</label>
                                <input class="form-control border border-dark" id="initials" name="initials"
                                    value="{{ $existing_data && $existing_data->initials ? $existing_data->initials : (Auth::user()->role_id == 3 ? get_abbreviation(Auth::user()->name) : '') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-3 mt-4">
                                <label for="comments" class="form-label text-dark">FS Initials</label>
                                <input class="form-control border border-dark" id="fs_initials" name="fs_initials"
                                    value="{{ $existing_data && $existing_data->fs_initials ? $existing_data->fs_initials : (Auth::user()->role_id == 3 ? get_abbreviation(Auth::user()->name) : '') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3 mt-4">
                                <label for="comments" class="form-label text-dark">Comments / Corrective Actions</label>
                                <textarea class="form-control border border-dark" id="comments" name="comments" rows="3">{{ old('comments', optional($existing_data)->note) }}</textarea>
                            </div>
                        </div>
                        @if (Auth::user()->role_id == 1)
                            <div class="col-7 col-md-8">
                                <div class="mb-3 mt-4">
                                    <label for="inspection_label_img" class="form-label text-dark">Capture Picture</label>
                                    <input type="file" id="inspection_label_img" name="inspection_label_img"
                                        class="form-control border border-1" accept="image/*" capture="environment"
                                        @if (!$existing_data && !$existing_data->inspection_img) required @endif>
                                </div>
                                <div id="preview-wrapper" class="mt-2">
                                    <img id="preview-image" src="" alt="Preview will appear here"
                                        style="display:none; max-width: 100%; height: 100px; border:1px solid #ddd; border-radius:6px; padding:5px;">
                                </div>
                            </div>
                        @endif
                        @if ($existing_data && $existing_data->inspection_img)
                            <div class="col-5 col-md-4 mt-3">
                                <div class="rounded on_click_preview_img text-center">
                                    <label for="" class="form-label text-dark">Uploaded Image</label><br>
                                    <img src="{{ $existing_data->inspection_img ?? '/assets/img/label_inspection/img_icon.png' }}"
                                        style=" max-width: 100%; height: 150px; border:1px solid #ddd; border-radius:6px; padding:5px;">
                                </div>
                                @if (Auth::user()->role_id == 1)
                                    <div class="text-center">
                                        <a href="#" class="text-danger text-center remove_img_popup"> Remove
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>


                    <div class="float-end mt-5">
                        <button type="button" onclick="history.back();"
                            class="btn btn-outline-secondary pt-2 pb-2 ps-5 pe-5 ms-4 me-4">Cancel</button>
                        {{-- @if (Auth::user()->role_id == 1)
                            <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5"
                                {{ $existing_data->status == 'V' ? 'disabled' : '' }}>
                    Verify </button>
                    @elseif (Auth::user()->role_id == 2)
                    <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5">
                        Approve </button>
                    @else
                    <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5"
                        {{ $existing_data->status == 'V' ? 'disabled' : '' }}>Submit</button>
                    @endif --}}

                        @if (Auth::user()->role_id == 1)
                            <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5"
                                {{ optional($existing_data)->status == 'V' ? 'disabled' : '' }}>
                                Verify
                            </button>
                        @elseif (Auth::user()->role_id == 2)
                            <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5">
                                Approve
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary pt-2 pb-2 ps-5 pe-5"
                                {{ optional($existing_data)->status == 'V' ? 'disabled' : '' }}>
                                Submit
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Inspection Image Modal -->
    <div class="modal fade" id="view_inspection_image_model" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" style="top: 0px !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-block">
                    <button type="button" class=" float-end close model_close border border-danger rounded text-danger"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ $existing_data->inspection_img ?? '/assets/img/label_inspection/img_icon.png' }}"
                        style=" max-width: 100%; height: auto; border:1px solid #ddd; border-radius:6px; padding:5px;">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="remove_label_inspection_image_model" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove Inspection Image</h5>
                    <button type="button" class="border border-danger rounded close model_close text-danger"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure that you want to remove the Inspection Image?</h6>
                    <p>After click on YES button the image will be removed, after that again you have to upload one Label
                        Inspection image.</p>
                </div>
                <div class="modal-footer">
                    @if (Auth::user()->role_id == 1)
                        <button type="button" class="btn btn-outline-primary model_close"
                            data-dismiss="modal">No</button>
                        <a href="{{ route('remove_inspection_image', ['id' => $existing_data->id]) }}" role="button"
                            class="btn btn-primary">Yes</a>
                    @endif
                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        const inputFile = document.getElementById('inspection_label_img');
        const previewImage = document.getElementById('preview-image');

        inputFile.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.on_click_preview_img').on('click', function(e) {
                e.preventDefault();
                $('#view_inspection_image_model').modal('show');
            });

            $('.remove_img_popup').on('click', function(e) {
                e.preventDefault();
                $('#remove_label_inspection_image_model').modal('show');
            });

            $('.model_close').on('click', function() {
                $('#view_inspection_image_model').modal('hide');
                $('#remove_label_inspection_image_model').modal('hide');
            });

            $('.label_inspection_form').on('submit', function() {
                $('#overlay').show();
            });
        });
    </script>
@endsection
@endsection
