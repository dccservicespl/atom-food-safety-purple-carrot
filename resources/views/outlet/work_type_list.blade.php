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
            <div class="col-6 mb-4">
                <a class="" href="{{ route('kitting_measure_date_listing') }}">
                    <div class="measurement_card text-center" style="background-color: #f49e0a1f">
                        <!-- <svg width="74" height="80" viewBox="0 0 74 80" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M37.0001 36.0938C37.0001 37.1298 37.4116 38.1233 38.1442 38.8559C38.8767 39.5885 39.8703 40 40.9063 40C41.9423 40 42.9359 39.5885 43.6685 38.8559C44.401 38.1233 44.8126 37.1298 44.8126 36.0938C44.8126 35.0577 44.401 34.0642 43.6685 33.3316C42.9359 32.599 41.9423 32.1875 40.9063 32.1875C39.8703 32.1875 38.8767 32.599 38.1442 33.3316C37.4116 34.0642 37.0001 35.0577 37.0001 36.0938ZM72.8399 70.5566L54.9981 24.375V7.38281H62.0001V0.742188H12.0001V7.38281H19.002V24.375L1.16022 70.5566C0.88678 71.2793 0.740295 72.041 0.740295 72.8125C0.740295 76.2598 3.54303 79.0625 6.9903 79.0625H67.0098C67.7813 79.0625 68.543 78.916 69.2657 78.6426C72.4883 77.4023 74.0899 73.7793 72.8399 70.5566ZM25.6426 25.6152V7.57812H48.3575V25.6152L57.2344 48.5938C55.213 48.0762 53.1231 47.8125 50.9942 47.8125C45.0176 47.8125 39.3536 49.9121 34.8516 53.6719C31.529 56.4475 27.3354 57.9652 23.0059 57.959C19.8126 57.959 16.7462 57.1484 14.0411 55.6445L25.6426 25.6152ZM7.5567 72.4219L11.629 61.8945C15.1153 63.6621 18.9922 64.6094 23.0157 64.6094C28.9922 64.6094 34.6563 62.5098 39.1583 58.75C42.4688 55.9961 46.6094 54.4629 51.004 54.4629C54.4219 54.4629 57.6837 55.3906 60.5352 57.1094L66.4434 72.4219H7.5567Z"
                                    fill="#f49e0a"></path>
                            </svg> -->
                        <img src="{{asset('assets/img/kitting.png')}}" class="responsive-img mx-auto pb-4" alt="">
                    </div>
                    <div class="card_title_color text-white text-center py-2" style="background-color: #f49e0a">
                        <strong>Kitting</strong>
                    </div>
                </a>
            </div>
            <div class="col-6 mb-4">
                <a class="" href="{{ route('portioning_measure_dashboard') }}">
                    <div class="measurement_card text-center pb-4" style="background-color: #F7F2FD">
                        <!-- <svg width="82" height="100" viewBox="0 0 82 100" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.8555 27.4414C16.7988 27.4414 18.3711 25.8691 18.3711 23.9258V3.51562C18.3711 1.57227 16.7988 0 14.8555 0C12.9121 0 11.3398 1.57227 11.3398 3.51562V23.9258C11.3398 25.8691 12.9121 27.4414 14.8555 27.4414ZM74.7188 41.5039H63C63 37.6172 59.8555 34.4727 55.9688 34.4727H7.53125C3.64453 34.4727 0.5 37.6172 0.5 41.5039V65.7227C0.5 66.0547 0.519531 66.377 0.568359 66.6895C0.519531 67.373 0.5 68.0566 0.5 68.75C0.5 86.0059 14.4941 100 31.75 100C47.3848 100 60.334 88.5254 62.6387 73.5352H74.7188C78.6055 73.5352 81.75 70.3906 81.75 66.5039V48.5352C81.75 44.6484 78.6055 41.5039 74.7188 41.5039ZM55.9688 66.5039H55.8613C55.9297 67.2461 55.9688 67.9883 55.9688 68.75C55.9688 82.1289 45.1289 92.9688 31.75 92.9688C18.3711 92.9688 7.53125 82.1289 7.53125 68.75C7.53125 67.9883 7.57031 67.2461 7.63867 66.5039H7.53125V41.5039H55.9688V66.5039ZM74.7188 65.7227H63.7812V48.5352H74.7188V65.7227ZM47.8633 27.4414C49.8066 27.4414 51.3789 25.8691 51.3789 23.9258V3.51562C51.3789 1.57227 49.8066 0 47.8633 0C45.9199 0 44.3477 1.57227 44.3477 3.51562V23.9258C44.3477 25.8691 45.9199 27.4414 47.8633 27.4414ZM31.2617 27.4414C33.2051 27.4414 34.7773 25.8691 34.7773 23.9258V3.51562C34.7773 1.57227 33.2051 0 31.2617 0C29.3184 0 27.7461 1.57227 27.7461 3.51562V23.9258C27.7461 25.8691 29.3184 27.4414 31.2617 27.4414Z"
                                    fill="#a982dd"></path>
                            </svg> -->
                        <img src="{{asset('assets/img/portioning.png')}}" class="responsive-img" alt="">
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
