@extends('layouts.main')
@section('content')

<!-- HTML CONTENT -->


<!-- top bar -->

<x-breadcrumb-component :get_route="$get_route" :back_route="$get_route" page_title="Weekly Work Details" :breadcrumb_links="[['name' => 'Home', 'route' => $get_route], ['name' => '3.2 Week', 'route' => route('portioning_measure_dashboard')], ['name' => 'Days Plan', 'route' =>'']]"/>


<!-- <section class="topbar py-3 mb-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <a href="" class="back-btn pointer"><i
                    class="bi bi-arrow-left-circle-fill text-white fs-2"></i></a>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="" class="text-white">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">3.2 Week</li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Days Plan</li>
                </ol>
            </nav>
        </div>
    </div>
</section>   -->



<section>
    
  <!-- ── Header Card ── -->
  <div class="header-card container present">
    
    <div>
      <div class="hc-week-title">Week 2</div>
      <div class="hc-week-range">9 Mar to 14 Mar</div>
    </div>
 
    <div class="hc-divider"></div>
 
    <div>
      <div class="hc-meta-label">Present Day</div>
      <div class="hc-meta-value">Monday – 9 Mar</div>
    </div>
 
    <div class="hc-divider"></div>
 
    <div>
      <div class="hc-meta-label">Upload Date</div>
      <div class="hc-meta-value">8 March – Sunday</div>
    </div>
 
    <div class="hc-qty">
      <div class="hc-qty-label">Total Quantity</div>
      <div class="hc-qty-value">42,129</div>
    </div>
  </div>

</section>

<section class="px-lg-5 px-3 pb-5">
 
 
  <!-- ── Section Title ── -->
  <div class="section-title">Weekly Work Details</div>
 
  <!-- ── Scrollable Table ── -->
  <div class="tbl-scroll mb-5">
    <table class="week-table">
 
      <thead>
        <tr>
          <th class="th-inactive">Monday</th>
          <th class="th-active">Tuesday</th>
          <th class="th-inactive">Wednesday</th>
          <th class="th-inactive">Thursday</th>
          <th class="th-inactive">Friday</th>
          <th class="th-inactive">Saturday</th>
        </tr>
      </thead>
 
      <tbody>
        <tr>
          <!-- Monday – sidebar category chips -->
          <td class="td-inactive">
            <div class="cell-inner">
              <a href="#" class="chip not_started">Piston 1200</a>
              <a href="#" class="chip  completed">Piston</a>
              <a href="#" class="chip in_process">Hand Allergen</a>
            </div>
          </td>
 
          <!-- Tuesday -->
          <td class="td-active">
            <div class="cell-inner">
              <a href="#" class="chip not_started">Piston</a>
            </div>          
          </td>
 
          <!-- Wednesday -->
          <td class="td-inactive">
            <div class="cell-inner">
              <a href="#" class="chip in_active_work">1200 Allergen</a>
              <a href="#" class="chip in_active_work">Granular</a>
            </div>
          </td>
 
          <!-- Thursday -->
          <td class="td-inactive">
            <div class="cell-inner">
              <a href="#" class="chip in_active_work">1200 Allergen</a>
              <a href="#" class="chip in_active_work">Sleek</a>
              <a href="#" class="chip in_active_work">Powder</a>
            </div>
          </td>
 
          <!-- Friday -->
          <td class="td-inactive">
            <div class="cell-inner">
              <a href="#" class="chip in_active_work">Granular</a>
              <a href="#" class="chip in_active_work">Powder</a>
              <a href="#" class="chip in_active_work">Sleek</a>
            </div>
          </td>
 
          <!-- Saturday – empty -->
          <td class="td-inactive">
            <div class="cell-inner"></div>
          </td>
        </tr>
      </tbody>
 
    </table>
  </div><!-- end tbl-scroll -->
 
</section><!-- end page-wrap -->
 


<section>
<div class="mb-5 d-flex gap-4 justify-content-center flex-wrap">
    <div class="d-flex align-items-center gap-2">
        <div style="background:#E8F8FF;border:1px solid #016B9D;width:30px;height:30px;border-radius:8px"></div>
        <p>Not Started</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div style="background:#FFF9BC;border:1px solid #7A7000;width:30px;height:30px;border-radius:8px"></div>
        <p>In Process</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div style="background:#CAFFB8;border:1px solid #208200;width:30px;height:30px;border-radius:8px"></div>
        <p>Completed</p>
    </div>
  </div>



</section>

  






@section('scripts')
<!-- Write Script Here -->
@endsection

@endsection