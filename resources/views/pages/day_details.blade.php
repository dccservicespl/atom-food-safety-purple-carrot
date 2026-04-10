@extends('layouts.main')
@section('content')

<!-- HTML CONTENT -->


<!-- top bar -->

<x-breadcrumb-component :get_route="$get_route" :back_route="$get_route" page_title="Day Details" :breadcrumb_links="[['name' => 'Home', 'route' => $get_route], ['name' => '3.2 Week', 'route' => route('portioning_measure_dashboard')], ['name' => 'Days Plan', 'route' => route('week_details')], ['name' => 'Day Details', 'route' =>'']]"/>



<section class="mb-4">
    <div class="container">


    <!-- Select item details, date & start time -->

      <div class="d-flex align-items-center justify-content-between gap-4">

        <!-- Production Schedule:name -->
         <div class="d-flex gap-1 align-items-center"><h4 class="fs-6">Production Schedule:</h4>  <p class="fw-bold color">Piston 1200</p></div>


         <!-- Date and Start Btn -->

         <div class="d-flex align-items-center justify-content-between gap-3">
             <div class="d-flex gap-3">
               <div class="d-flex align-items-center gap-2 text-color"><span><i class="bi bi-calendar2-minus"></i></span> <p>Monday - 9 March</p></div>
              <button type="button" class="btn_2"> <span><i class="bi bi-clock"></i></span> Start Time</button>
              {{-- <button type="button" class="btn_3 disable" disabled> <span><i class="bi bi-clock"></i></span> End Time</button> --}}
            </div>

         </div>


         

      </div>


      {{-- Start  --}}
      <div class="d-flex align-items-center gap-3">
          {{-- Start Time --}}
          <div class="color fs-5 fw-medium"><span>Start Time:</span><span>05:41 am</span></div>
          
          <div class="vr"></div>

          <div class="color fs-5 fw-medium"><span>Start Time:</span><span>05:41 am</span></div>
          
      
        </div>


    </div>
</section>


<!-- Day details table -->


<section class="mb-5">
    <div class="container">

   <div class="" style="">
  <div class="table-card">
 
    <div class="table-scroll-wrapper">
      <table class="component-table disabled">
        <thead>
          <tr >
            <th>Letter</th>
            <th>Component Details</th>
            <th>Label</th>
            <th>Weight</th>
            <th>QTY</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="letter-badge">DC/DD/DH/DL</span></td>
            <td>Vegan Mayo bulk</td>
            <td>Vegan Mayo</td>
            <td><span class="weight-chip">2 oz</span></td>
            <td><span class="qty-value">1678</span></td>
            <td class="text-center"><a href="#" class="action-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            </td>
          </tr>
          <tr class="">
            <td><span class="letter-badge">DJ</span></td>
            <td>Coconut milk, bulk portioned, 6 fl oz</td>
            <td>Coconut milk Allergen tree nuts (coconut)</td>
            <td><span class="weight-chip">6 fl oz</span></td>
            <td><span class="qty-value">1203</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">DQ</span></td>
            <td>Coconut milk, bulk portioned, 6 fl oz</td>
            <td>Coconut milk Allergen tree nuts (coconut)</td>
            <td><span class="weight-chip">6 fl oz</span></td>
            <td><span class="qty-value">1073</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">DC</span></td>
            <td>Bbq sauce, bulk portioned, 0.25 cup</td>
            <td>BBQ sauce</td>
            <td><span class="weight-chip">68 g</span></td>
            <td><span class="qty-value">334</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">LB</span></td>
            <td>Bbq sauce, bulk portioned, 0.25 cup</td>
            <td>BBQ sauce</td>
            <td><span class="weight-chip">68 g</span></td>
            <td><span class="qty-value">94</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">DN</span></td>
            <td>Chili garlic sauce, bulk portioned, 2 tbsp</td>
            <td>Chili garlic sauce</td>
            <td><span class="weight-chip">36 g</span></td>
            <td><span class="qty-value">2759</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">LD</span></td>
            <td>Chili garlic sauce, bulk portioned, 1 tbsp</td>
            <td>Chili garlic sauce</td>
            <td><span class="weight-chip">18 g</span></td>
            <td><span class="qty-value">158</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">DR</span></td>
            <td>Chutney, tomato, bulk portioned, 1/4 cup</td>
            <td>Tomato chutney</td>
            <td><span class="weight-chip">2 oz</span></td>
            <td><span class="qty-value">431</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
          <tr>
            <td><span class="letter-badge">BB</span></td>
            <td>Preserve, apricot, bulk portioned, 1/4 cup</td>
            <td>Apricot preserves</td>
            <td><span class="weight-chip">72 g</span></td>
            <td><span class="qty-value">149</span></td>
            <td class="text-center"><a href="#" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></td>
          </tr>
        </tbody>
      </table>
    </div>
 
     </div>
   </div>


    </div>
</section>




<section>
  <div class="container">

    <div class="row">
      <div class="col-lg-8">
        <div class="row">

          <div class="col-4">
            <div class="card_total_quantity card-box">
              <h4 class="color fw-bold fs-5">Total Quantity</h4>
              <h2 class="fs-2 color fw-bold">7,879</h2>
            </div>
          </div>

          <div class="col-4">
              <div class="card_completed_quantity card-box">
              <h4 class="fw-bold fs-5">Completed Quantity</h4>
              <h2 class="fs-2 fw-bold">1,000</h2>
            </div>
          </div>

          <div class="col-4">
              <div class="card_pending_quantity card-box">
              <h4 class="fw-bold fs-5">Pending Quantity</h4>
              <h2 class="fs-2 fw-bold">6,879</h2>
            </div>
          </div>
          
        </div>
      </div>



      <div class="col-lg-4"></div>


    </div>
    


  </div>
</section>











@section('scripts')
<!-- Write Script Here -->
@endsection

@endsection