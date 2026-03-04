<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>Metal Detector Measure</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
</head>

<body width="100%"
    style="
      margin: 0;
      padding: 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
    ">
    <div style="max-width: 1170px; margin: 0 auto">
        <h1
            style="
      margin: 0;
      padding: 15px;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 17px; text-align: center; color:red;
    ">
            FOOD SAFETY</h1>
        <p
            style="
      margin: 0;
      padding:10px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    ">
            <strong>Directions:</strong> Food Safety will monitor all metal detector functionality by placing approved
            standards on finished product container at the start of every production run prior to passing product
            through, and every hour during processing. Note any rejected product required.
        </p>
        <p
            style="
      margin: 0;
      padding:10px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    ">
            <strong>Corrective Actions:</strong> If a metal detector fails to detect and reject/stop, food safety must
            place metal detector and product on hold back to the last good check for management disposition review.
        </p>
        <p
            style="
      margin: 0;
      padding:10px 0 15px;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    ">
            "If any standard does not detect or reject, contact management for support. Verify the metal detector
            functionality with all three standards after the auto setup is complete. If all standards detect and
            reject/stop the product, proceed with passing the product. If not, repeat the auto setup.<br>
            Hold the metal detector if it fails to detect and reject/stop all standards after three auto setup attempts.
            You may run the product through another functioning metal detector."</p>
        <h1
            style="
      margin: 0;
      padding: 15px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 14px; text-align: left;
				  font-weight: 400;
    ">
            <strong>Metal Detector Model</strong>&nbsp;&nbsp;&nbsp;Kick Out&nbsp;&nbsp;&nbsp;Belt Stop
        </h1>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="9%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Time </strong>
                </td>
                <td width="18%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Product Description </strong>
                </td>
                <td width="9%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>2.0mm FE <br>
                        Pass / Fail</strong>
                </td>
                <td width="9%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>3.0mm Nfe <br>
                        Pass / Fail</strong>
                </td>
                <td width="9%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>4.0mm SS<br>
                        Pass / Fail</strong>
                </td>
                <td width="9%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Confirm Label</strong>
                </td>
                <td width="26%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Comments</strong>
                </td>
                <td width="11%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Initials</strong>
                </td>
            </tr>
            @php
                $metal_detector_measures_fst = DB::table('metal_detector_measures')
                    ->join('users', 'users.id', 'metal_detector_measures.created_by')
                    ->latest('measure_date')
                    ->first();
                $measure_date = date('m-d-Y', strtotime($metal_detector_measures_fst->measure_date)) ?? 'N/A';
                $created_by = $metal_detector_measures_fst->name ?? 'N/A';

                $metal_detector_measure = DB::table('metal_detector_measures')
                    ->join('users', 'users.id', 'metal_detector_measures.reviewed_by')
                    ->latest('reviewed_at')
                    ->first();
                $reviewed_by = $metal_detector_measure->name ?? 'N/A';
                $reviewed_date = date('m-d-Y', strtotime($metal_detector_measure->reviewed_at));
            @endphp
            @foreach ($metal_detector_measures as $item)
                <tr>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: right;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        {{ $item->updated_at->format('g:i A') }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ $item->item_name }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        {{ $item->mm_2_fe == 'N' ? 'N/A' : $item->mm_2_fe }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        {{ $item->mm_3_nfe == 'N' ? 'N/A' : $item->mm_3_nfe }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999999;
                    border-collapse: collapse;
                  ">
                        {{ $item->mm_4_ss == 'N' ? 'N/A' : $item->mm_4_ss }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999999;
                    border-collapse: collapse;
                  ">
                        {{ $item->confirm_label == 'N' ? 'N/A' : $item->confirm_label }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ $item->comments ?? 'N/A' }}</td>
                    <td
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ $item->initial ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </table>
        <p
            style="
      margin: 0;
      padding:15px 0 5px;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    ">
            <strong>Notes / Comments:</strong> &nbsp;
        </p>
        <table style="border: 0.5px solid #999; padding: 15px 0;margin: 0 auto 15px" role="presentation" border="0"
            cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="20%"
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px; text-align: left;
                  ">
                    Food Safety Technician:</td>
                <td width="60%"
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  ">
                    <p
                        style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">
                        &nbsp; {{ $get_created_and_review_data['created_by_name'] }}</p>
                </td>
                <td width=""
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
							 text-align: right;
                  ">
                    Date:</td>
                <td width="15%"
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  ">
                    <p
                        style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">
                        &nbsp; {{ $get_created_and_review_data['created_date'] }}</p>
                </td>
            </tr>
            <tr>
                <td
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px; text-align: left;
                  ">
                    Reviewed by:</td>
                <td
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  ">
                    <p
                        style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">
                        &nbsp; {{ $get_created_and_review_data['review_by_name'] }}</p>
                </td>
                <td width=""
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
							 text-align: right;
                  ">
                    Date:</td>
                <td
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                  ">
                    <p
                        style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
	  border-bottom: 1px solid #999999;
    ">
                        &nbsp; {{ $get_created_and_review_data['reviewed_date'] }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
