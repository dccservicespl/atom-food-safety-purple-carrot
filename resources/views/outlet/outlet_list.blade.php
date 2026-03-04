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
                    <strong>Select Company</strong>
                </div>
            </div>
        </div>
        <div class="rounded-0 rounded-bottom page_card bg_white category_card_spacing mt-0">
            <div class="row">

                <div class="col-6 mb-4">
                    <a class="" href="{{ route('dashboard') }}">
                        <div class="measurement_card" style="background-color: #f49e0a1f">
                            <img class="card-img-top rounded rounded-4 p-4" src="/assets/img/f_t_m_logo.webp"
                                alt="Fresh Thyme">
                        </div>
                        <div class="card_title_color" style="background-color: #f49e0a">
                            <strong>Fresh Cut</strong>
                        </div>
                    </a>
                </div>
                <div class="col-6 mb-4">
                    <a class="" href="{{ route('work_type') }}">
                        <div class="measurement_card" style="background-color: #F7F2FD">
                            <img class="card-img-top p-4 rounded rounded-4" src="/assets/img/purple_carrot.png"
                                style="height: 330px;" alt="Fresh Thyme">
                        </div>
                        <div class="card_title_color" style="background-color: #A982DD !important">
                            <strong>Purple Carrot</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
@endsection
@endsection
