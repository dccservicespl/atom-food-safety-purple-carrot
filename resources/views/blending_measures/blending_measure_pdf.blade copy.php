<!DOCTYPE html>
<html
  lang="en"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
<head>
<title>Blending Measure</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="x-apple-disable-message-reformatting" />
</head>

<body
    width="100%"
    style="
      margin: 0;
      padding: 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
    "
  >
<div style="max-width: 1170px; margin: 0 auto">
	
  <h1  style="
      margin: 0;
      padding: 15px;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 17px; text-align: center; color:red;
    ">FOOD SAFETY </h1>
	
  <table
              role="presentation"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
            >
    <tr>
      <td 
                  width="22%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Blending Description </strong></td>
      <td
                  width="10%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>pH result </strong></td>
      <td
                  width="9%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Temperature <br />
        °F </strong></td>
      <td
                  width="12%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong> Appearance <br />
        P (Pass) / F (Fail) </strong></td>
      <td
                  width="12%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong> Odor <br />
        P (Pass) / F (Fail) </strong></td>
      <td
                  width="12%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong> Taste <br />
        P (Pass) / F (Fail) </strong></td>
      <td
                  width="18%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Commets / Corrective Actions </strong></td>
      <td
                  width="5%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Initial </strong></td>
    </tr>
    @php 
        $colors = [ 
            '#FFFFB9',
            '#FFDE75',
            '#FF8989',
            '#93E3FF',
            '#FBE2D5',
            '#FFAFAF',
            '#C9E7A7',
            '#94DCF8',
        ];
        $item_index = 0;
        $blending_measure_fst = DB::table('blending_measures')
            ->join('users', 'users.id', 'blending_measures.created_by')
            ->latest('measure_date')->first();
        $measure_date = date('m-d-Y', strtotime($blending_measure_fst->measure_date)) ?? 'N/A';
        $created_by = $blending_measure_fst->name ?? 'N/A';

        $blending_measure = DB::table('blending_measures')
            ->join('users', 'users.id', 'blending_measures.reviewed_by')
            ->latest('reviewed_at')->first();
        $reviewed_by = $blending_measure->name ?? 'N/A';
        $reviewed_date = date('m-d-Y', strtotime($blending_measure->reviewed_at)) ?? 'N/A';
    @endphp
    @foreach($blending_measures as $item)
    @php
        $bg_color = $colors[$item_index % count($colors)];
        $item_index++;
    @endphp
    <tr>
        <td
                    style="
                      background: {{$bg_color}};
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                                border-right:none;	 
                      border-collapse: collapse;
                    "
                  ><strong>{{$item->item_name}}</strong></td>
       
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >&nbsp; {{floatval($item->ph_result)}}</td>
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >&nbsp; {{floatval($item->temperature)}}</td>
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >&nbsp; {{$item->appearance}}</td>
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >&nbsp; {{$item->odor}}</td>
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >&nbsp; {{$item->taste}}</td>
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >&nbsp; {{$item->comments ?? 'N/A'}}</td>
        <td
                    style="
                      background: #ffffff;
                      padding: 5px;
                      margin: 0 auto;
                      font-size: 12px;
                      text-align: center;
                      border: 0.5px solid #999;
                      border-collapse: collapse;
                    "
                  >{{$item->initial ?? 'N/A'}}</td>
    </tr>
    @endforeach
  </table>
	
  <h1  style="
      margin: 0;
      padding: 15px;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 14px; text-align: center;
    ">pH Critical Limits - Less than 4.5</h1>
	
  <table  role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style=" padding: 0;  margin:0px auto 5px; ">
    <tr>
      <td width="40%"><table role="presentation"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%">
          <tr>
            <td  width="50%"
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Product </strong></td>
            <td
                  width="50%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>pH result </strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Pico de Gallo </strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>1.7 to 3.5 </strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Hatch Pepper Salsa </strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>1.7 to 3.6 </strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Guacamole - Spread, Mild, Medium, Hot Spicy and Hatch Pepper </strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>3.0 to 4.5 </strong></td>
          </tr>
        </table></td>
      <td width="20%"></td>
      <td width="40%"><table role="presentation"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%">
          <tr>
            <td  width="50%"
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Product </strong></td>
            <td
                  width="50%"
                  style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>pH result </strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Mango Salsa </strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>4.5 or less </strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Fiery Salsa</strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>4.5 or less</strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Tomato Medley </strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>4.5 or less </strong></td>
          </tr>
          <tr>
            <td
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>Taqueria Topping </strong></td>
            <td
                
                  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  "
                ><strong>4.5 or less </strong></td>
          </tr>
        </table></td>
    </tr>
  </table>
	
  <p  style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    ">Corrective Action: If pH fails, place the blending on hold and add extra lime juice 0.1lb at a time until the test passes.
    Document all re-test in the correction column, including the Extra amount of lime juice added and the Final pH result.</p>
	
  <p  style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    "> <strong>Notes:</strong> </p>
	
  <h1  style="
      margin: 0;
      padding: 15px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 14px; text-align: left;
    ">COMPLETED AND REVIEWED BY:</h1>
	
  <table style="border: 0.5px solid #999; padding: 15px 0;margin: 0 auto 15px"
		    role="presentation"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
		   >
    <tr>
      <td width="20%"   style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px; text-align: left;
                  ">Food Safety Technician Printed Name:</td>
      <td  width="60%"     style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  "><p  style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">&nbsp; {{$created_by}}</p></td>
      <td  width=""   style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
							 text-align: right;
                  ">Date:</td>
      <td  width="15%"    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  "><p  style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">&nbsp; {{$measure_date}}</p></td>
    </tr>
    <tr>
      <td style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px; text-align: left;
                  ">Reviewed by Printed name:</td>
      <td  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  "><p  style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">&nbsp; {{$reviewed_by}}</p></td>
      <td  width=""   style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
							 text-align: right;
                  ">Date:</td>
      <td  style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  "><p  style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">&nbsp; {{$reviewed_date}}</p></td>
    </tr>	  
  </table>
</div>
</body>
</html>
