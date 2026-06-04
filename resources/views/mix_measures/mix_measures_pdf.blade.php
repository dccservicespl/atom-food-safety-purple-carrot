<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>Mixing Measure</title>
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
            FOOD SAFETY </h1>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="14%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Item Description </strong>
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
                    <strong>Net Weight </strong>
                </td>
                <td width="10%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Odor </strong>
                </td>
                <td width="14%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong> Appearance </strong>
                </td>
                <td colspan="2"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong> Weight Checks <br />
                        (Net weight) </strong>
                </td>
                <td colspan="2"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999 !important;
                    border-collapse: collapse;
                  ">
                    <strong> Temperature Check <br />
                        (°F)</strong>
                </td>
                <td width="12%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Table # / <br />
                        Line </strong>
                </td>
                <td width="7%"
                    style="
                    background: #d0d0d0;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    <strong>Scale # </strong>
                </td>
            </tr>
            @php
                $colors = [
                    '#93E3FF',
                    '#FFABFF',
                    '#FFD54F',
                    '#FFFF8F',
                    '#ADC2E5',
                    '#83CCEB',
                    '#FFDE75',
                    '#B5E6A2',
                    '#CE6CBB',
                    '#F2CEEF',
                    '#EC5A2C',
                ];
                $item_index = 0;
                $mix_measure_fst = DB::table('mixing_measures')
                    ->join('users', 'users.id', 'mixing_measures.created_by')
                    ->latest('measure_date')
                    ->first();
                $measure_date = date('m-d-Y', strtotime($mix_measure_fst->measure_date)) ?? 'N/A';
                $created_by = $mix_measure_fst->name ?? 'N/A';

                $mix_measure = DB::table('mixing_measures')
                    ->join('users', 'users.id', 'mixing_measures.reviewed_by')
                    ->latest('reviewed_at')
                    ->first();
                $reviewed_by = $mix_measure->name ?? 'N/A';
                $reviewed_date = date('m-d-Y', strtotime($mix_measure->reviewed_at)) ?? 'N/A';
            @endphp
            @foreach ($mix_measures as $item)
                @php
                    $bg_color = $colors[$item_index % count($colors)];
                    $item_index++;
                @endphp
                <tr>
                    <td rowspan="2"
                        style="
                    background: {{ $bg_color }};
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        <strong>{{ $item->item_name }}</strong>
                    </td>
                    <td rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        {{ floatval($item->weight) }}</td>
                    <td rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        {{ $item->odor }}</td>
                    <td rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        {{ $item->appearance }}</td>
                    <td width="9%"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        1 - {{ floatval($item->weight_1) ?? 'N/A' }}</td>
                    <td width="10%"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: left;
                    border: 1px solid #999 !important;
                    border-collapse: collapse;
                  ">
                        3 - {{ floatval($item->weight_3) ?? 'N/A' }}</td>
                    <td width="8%" rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 1px solid #999 !important;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ floatval($item->temperature_1) ?? 'N/A' }}</td>
                    <td width="7%" rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ floatval($item->temperature_2) ?? 'N/A' }}</td>
                    <td rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ $item->table_line }}</td>
                    <td rowspan="2"
                        style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: center;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                        &nbsp; {{ $item->scale }}</td>
                </tr>

                <td
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: l;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    2 - {{ floatval($item->weight_2) ?? 'N/A' }}</td>
                <td
                    style="
                    background: #ffffff;
                    padding: 5px;
                    margin: 0 auto;
                    font-size: 12px;
                    text-align: l;
                    border: 0.5px solid #999;
                    border-collapse: collapse;
                  ">
                    4 - {{ floatval($item->weight_4) ?? 'N/A' }}</td>
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
            Temperature Limits: The final packaged product must be maintained at or below 40°F during both storage and
            processing</p>

        <p
            style="
      margin: 0;
      padding:5px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 12px; text-align: left;
    ">
            <strong>Notes:</strong>
        </p>

        <h1
            style="
      margin: 0;
      padding: 15px 0;
      font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif';
	  font-size: 14px; text-align: left;
    ">
            COMPLETED AND REVIEWED BY:</h1>

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
