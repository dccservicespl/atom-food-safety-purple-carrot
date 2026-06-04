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

        .card_title_color {
            font-size: 24px;
        }
    </style>
    <div class="container-fluid">
        <?php echo flashMessage(); ?>
        <div class="col-12 mt-4">
            <div class="rounded-0 card shadow-none rounded-top border-secondary border-bottom">
                <div class="card_title_color text-dark text-start ps-4">
                    <strong>Item Master</strong>
                </div>
            </div>
        </div>
        <div class="rounded-0 rounded-bottom page_card bg_white category_card_spacing mt-0">
            <div class="row">
                @foreach ($get_all_category as $get_all_category_data)
                    <div class="col-6 mb-4">
                        <a class="" href="{{ route('item_list', Crypt::encrypt($get_all_category_data->id)) }}">
                            <div class="measurement_card"
                                style="background-color: {{ $get_all_category_data->card_color_code }}">
                                <?php echo $get_all_category_data->cat_icon; ?>
                            </div>
                            <div class="card_title_color"
                                style="background-color: {{ $get_all_category_data->title_color_code }}">
                                <strong>{{ $get_all_category_data->cat_name }} Item</strong>
                            </div>
                        </a>
                    </div>
                @endforeach
                <div class="col-6 mb-4">
                    <a class="" href="{{ route('label_inspection_items') }}">
                        <div class="measurement_card" style="background-color: #F7F2FD">
                            <svg width="98" height="91" viewBox="0 0 98 91" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.5 80.75H20.5V40.75H80.5V80.75H90.5V27.5L50.5 11.5L10.5 27.5V80.75ZM0.5 90.75V20.75L50.5 0.75L100.5 20.75V90.75H70.5V50.75H30.5V90.75H0.5ZM35.5 90.75V80.75H45.5V90.75H35.5ZM45.5 75.75V65.75H55.5V75.75H45.5ZM55.5 90.75V80.75H65.5V90.75H55.5Z"
                                    fill="#FBB231" />
                            </svg>


                        </div>
                        <div class="card_title_color" style="background-color: #A982DD">
                            <strong>Label Inspection Item</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
@endsection
@endsection
