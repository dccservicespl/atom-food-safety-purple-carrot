<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>Guacamole Measure</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
</head>
@php
    $guacamole_measure_fst = DB::table('guacamole_measures')
        ->join('users', 'users.id', 'guacamole_measures.created_by')
        ->latest('measure_date')
        ->first();
    $measure_date = date('m-d-Y', strtotime($guacamole_measure_fst->measure_date)) ?? 'N/A';
    $created_by = $guacamole_measure_fst->name ?? 'N/A';
    $guacamole_measure = DB::table('guacamole_measures')
        ->join('users', 'users.id', 'guacamole_measures.reviewed_by')
        ->latest('reviewed_at')
        ->first();
    $reviewed_by = $guacamole_measure->name ?? 'N/A';
    $reviewed_date = date('m-d-Y', strtotime($guacamole_measure->reviewed_at)) ?? 'N/A';
    $production_date = date('m-d-Y', strtotime($guacamole_measure_fst->measure_date)) ?? 'N/A';
    $operator_name = $guacamole_measure_fst->name ?? 'N/A';
@endphp

<body width="100%"
    style=" margin: 0; padding: 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; ">
    <div style="max-width: 1170px; margin: 0 auto">
        <h1
            style=" margin: 0; padding: 15px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 17px; text-align: center; color:red; ">
            FOOD SAFETY </h1>

        <table style="border: 0.5px solid #999; padding: 15px 0;margin: 0 auto 15px" role="presentation" border="0"
            cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    Production Date:</td>
                <td style=" background: #ffffff; padding: 15px 0; margin: 0 auto; font-size: 12px; text-align: left; ">
                    <p
                        style=" margin: 0; padding:5px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                        {{ $production_date }}
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td width="12%"
                    style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    Operator Name:</td>
                <td width="20%"
                    style=" background: #ffffff; padding: 15px 0; margin: 0 auto; font-size: 12px; text-align: left; ">
                    <p
                        style=" margin: 0; padding:5px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                        {{ $get_created_and_review_data['daily_measure']->guacamole_operator_name }}
                    </p>
                </td>
                <td width="60%"></td>

            </tr>
        </table>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="7%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Product </strong>
                </td>
                <td width="7%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Batch #</strong>
                </td>
                <td width="4%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Temp °F </strong>
                </td>
                <td width="6%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Mix Lot Number</strong>
                </td>
                <td width="4%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Time</strong>
                </td>
                <td width="18%"
                    style=" background: #90ee90; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Metal Detector Check</strong>
                </td>
                <td width="13%"
                    style=" background: #ffc0cb; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Seal Check</strong>
                </td>
                <td width="16%"
                    style=" background: #add8e6; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Oxygen Levels <br>
                        (Less than 1.5%)</strong>
                </td>
                <td width="16%"
                    style=" background: #ffff99; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Weight Verification</strong>
                </td>
                <td width="7%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Initials</strong>
                </td>
                <td width="9%"
                    style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                    <strong>Metal Detector Model</strong>
                </td>
            </tr>
            @foreach ($grouped_measures as $item_name => $measures)
                @php
                    $rowspan = count($measures);
                @endphp
                @foreach ($measures as $index => $item)
                    <tr>
                        @if ($index === 0)
                            <td rowspan="{{ $rowspan }}"
                                style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                {{ $item->item_name . ' ' . ($item->weight > 0 ? $item->weight . ' oz' : '') }}
                            </td>
                        @endif
                        <td
                            style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                            {{ $item->batch_no }} </td>
                        <td
                            style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                            {{ floatval($item->temperature) }} °F</td>
                        <td
                            style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                            {{ $item->lot_number ?? 'N/A' }}</td>
                        <td
                            style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                            {{ $item->updated_at->format('g:i A') }}
                        </td>
                        <td style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: left; border: 0.5px solid #999; border-collapse: collapse; "
                            valign="top">
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>Fe
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                                    {{ $item->md_fe ?? 'N/A' }} </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>NFe
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                                    {{ $item->md_nfe ?? 'N/A' }} </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>St
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                                    {{ $item->md_st ?? 'N/A' }} </span>
                            </p>
                        </td>
                        <td style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: left; border: 0.5px solid #999; border-collapse: collapse; "
                            valign="top">
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>1
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                                    {{ $item->sc_batch_1 ?? 'N/A' }} </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>2
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ $item->sc_batch_2 ?? 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>3
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ $item->sc_batch_3 ?? 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>4
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ $item->sc_batch_4 ?? 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>5
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ $item->sc_batch_5 ?? 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>6
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ $item->sc_batch_6 ?? 'N/A' }}
                                </span>
                            </p>
                        </td>
                        <td style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: left; border: 0.5px solid #999; border-collapse: collapse; "
                            valign="top">
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>1
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->oxygen_levels_1) > 0 ? floatval($item->oxygen_levels_1) . '%' : 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>2
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->oxygen_levels_2) > 0 ? floatval($item->oxygen_levels_2) . '%' : 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>3
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->oxygen_levels_3) > 0 ? floatval($item->oxygen_levels_3) . '%' : 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>4
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->oxygen_levels_4) > 0 ? floatval($item->oxygen_levels_4) . '%' : 'N/A' }}
                                </span>
                            </p>
                        </td>
                        <td style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: left; border: 0.5px solid #999; border-collapse: collapse; "
                            valign="top">
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>1
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->weight_checks_1) > 0 ? floatval($item->weight_checks_1) : 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>2
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->weight_checks_2) > 0 ? floatval($item->weight_checks_2) : 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>3
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->weight_checks_3) > 0 ? floatval($item->weight_checks_3) : 'N/A' }}
                                </span>
                            </p>
                            <p style="display: inline-block; padding: 0 0 5px 0; margin:5px 0;font-size: 12px;"> <b>4
                                </b>
                                <span
                                    style=" margin: 0 5px 0 0; padding:5px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">{{ floatval($item->weight_checks_4) > 0 ? floatval($item->weight_checks_4) : 'N/A' }}
                                </span>
                            </p>
                        </td>
                        <td
                            style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                            {{ $item->initial ?? 'N/A' }}</td>

                        <td
                            style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                            {{ $item->md_model_result }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </table>
        <br>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
            style=" padding: 0; margin:0px auto 5px; ">
            <tr>
                <td width="25%" valign="top">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td colspan="3"
                                style=" background: #fff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <h1
                                    style=" margin: 0; padding: 0 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 14px; text-align:center; font-weight: 400; ">
                                    <strong>Packaging Lot Numbers</strong>
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Size </strong>
                            </td>
                            <td width="33%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>CUPS</strong>
                            </td>
                            <td width="33%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>LIDS</strong>
                            </td>
                        </tr>
                        @foreach ($packaging_lot_numbers as $lot_number)
                            <tr>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $lot_number->weight }}oz</strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $lot_number->cups }}</strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $lot_number->lids }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td width="1%" valign="top"></td>
                <td width="82%" valign="top">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="17%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Product </strong>
                            </td>
                            <td width="20%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Total Containers Produced</strong>
                            </td>
                            <td width="16%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Retains Collected</strong>
                            </td>
                            <td width="15%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Best By Date</strong>
                            </td>
                            <td width="11%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Initials</strong>
                            </td>
                            <td width="21%"
                                style=" background: #d0d0d0; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                <strong>Batch Size (Total Weight)</strong>
                            </td>
                        </tr>
                        @foreach ($guacamole_item_measures as $item)
                            <tr>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $item->item_name }} </strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $item->total_containers }} </strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $item->retains_collected }} </strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ \Carbon\Carbon::parse($item->best_by_date)->format('m-d-Y') }}</strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $item->initial ?? 'N/A' }} </strong>
                                </td>
                                <td
                                    style=" background: #ffffff; padding: 5px; margin: 0 auto; font-size: 12px; text-align: center; border: 0.5px solid #999; border-collapse: collapse; ">
                                    <strong>{{ $item->weight }} oz</strong>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
        <p
            style=" margin: 0; padding:15px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; ">
            <strong>Notes / Comments:</strong>
        </p>
        <table style="border: 0.5px solid #999; padding: 15px 0;margin: 0 auto 15px" role="presentation"
            border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="20%"
                    style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    Food Safety Technician:</td>
                <td width="60%"
                    style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    <p
                        style=" margin: 0; padding:5px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                        {{ $get_created_and_review_data['created_by_name'] }}
                    </p>
                </td>
                <td width=""
                    style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    Date:</td>
                <td width="15%"
                    style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    <p
                        style=" margin: 0; padding:5px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                        {{ $get_created_and_review_data['created_date'] }}
                    </p>
                </td>
            </tr>
            <tr>
                <td style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    Reviewed by :</td>
                <td style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    <p
                        style=" margin: 0; padding:5px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                        {{ $get_created_and_review_data['review_by_name'] }}
                    </p>
                </td>
                <td style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    Date:</td>
                <td style=" background: #ffffff; padding: 15px; margin: 0 auto; font-size: 12px; text-align: left; ">
                    <p
                        style=" margin: 0; padding:5px 0; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size: 12px; text-align: left; border-bottom: 1px solid #999999; ">
                        {{ $get_created_and_review_data['reviewed_date'] }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
