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
                <div class="card_title_color text-dark text-start ps-4" style="font-size: 16px">
                    <span>Purple Carrot</span>
                    <span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="display: inline; margin: 0 8px; vertical-align: middle;">
                            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <strong>Select Outlet Location</strong>
                </div>
            </div>
        </div>
        <div class="rounded-0 rounded-bottom page_card bg_white category_card_spacing mt-0">
            <div class="row">
                <div class="col-6 mb-4">
                    <a class="" href="{{ route('work_type') }}">
                        <div class="measurement_card" style="background-color: #F7F2FD">
                            <svg width="98" height="91" viewBox="0 0 98 91" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M49 8 C32 8 18 22 18 39 C18 52 28 68 49 88 C70 68 80 52 80 39 C80 22 66 8 49 8 Z M49 28 C58 28 65 35 65 44 C65 53 58 60 49 68 C40 60 33 53 33 44 C33 35 40 28 49 28 Z"
                                    fill="#a982dd" />
                            </svg>
                        </div>
                        <div class="card_title_color" style="background-color: #A982DD">
                            <strong>Wolcott Avenue</strong>
                        </div>
                    </a>
                </div>
                <div class="col-6 mb-4">
                    <a class="" href="{{ route('work_type') }}">
                        <div class="measurement_card" style="background-color: #f49e0a1f">
                            <svg width="98" height="91" viewBox="0 0 98 91" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M49 8 C32 8 18 22 18 39 C18 52 28 68 49 88 C70 68 80 52 80 39 C80 22 66 8 49 8 Z M49 28 C58 28 65 35 65 44 C65 53 58 60 49 68 C40 60 33 53 33 44 C33 35 40 28 49 28 Z"
                                    fill="#f49e0a" />
                            </svg>
                        </div>
                        <div class="card_title_color" style="background-color: #f49e0a">
                            <strong>43rd Street</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@section('scripts')
@endsection
@endsection
