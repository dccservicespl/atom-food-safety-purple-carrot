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


<section class="topbar py-3 mb-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="" class="text-white">Purple Carrot</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Select Work Type</li>
                </ol>
            </nav>
        </div>
    </div>
</section>


<div class="container">
    <?php echo flashMessage(); ?>

    <div class="rounded-0 rounded-bottom page_card bg_white category_card_spacing mt-5">
        <div class="row" style="height:80vh">
            <div class="col-12 col-md-6 mb-4">
                <a class="" href="{{ route('kitting_measure_date_listing') }}">
                    <div class="measurement_card text-center" style="background-color: #f49e0a1f">
                        <img src="{{asset('assets/img/kitting.png')}}" class="responsive-img mx-auto pb-4" style="max-width: 100%; height: auto;" alt="Kitting">
                    </div>
                    <div class="card_title_color text-white text-center py-2" style="background-color: #f49e0a">
                        <strong>Kitting</strong>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 mb-4">
                <a class="" href="{{ route('portioning_measure_dashboard') }}">
                    <div class="measurement_card text-center pb-4" style="background-color: #F7F2FD">
                        <img src="{{asset('assets/img/portioning.png')}}" class="responsive-img" style="max-width: 100%; height: auto;" alt="Portioning">
                    </div>
                    <div class="card_title_color text-white text-center py-2" style="background-color: #A982DD">
                        <strong>Portioning</strong>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@section('scripts')
@endsection
@endsection
