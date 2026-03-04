<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>LABEL INSPECTION FORM</title>
    <style>
        @page {
            margin-top: 100px;
            margin-bottom: 70px;
            margin-left: 50px;
            margin-right: 50px;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 100px;
            font-family: sans-serif;
        }

        footer {
            position: fixed;
            bottom: -70px;
            left: 0;
            right: 0;
            height: 90px;
            font-family: sans-serif;
        }

        .content {
            margin-top: 20px;
            font-family: sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #212020;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 12px;
        }


        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif !important;
        }
    </style>
</head>

<body>
    <header>
        <table width="100%" style="border-bottom: 1px solid #ccc; margin-bottom: 20px !important">
            <tr>
                <td style="width: 33%; font-size: 13px; border-color:#fff !important;">
                    <strong>Operator Name:</strong>
                    <span style="border-bottom: 1px solid #000; display: inline-block; width: 100px;">&nbsp;
                        {{ $get_operators_name }}
                    </span>
                </td>
                <th style="border-color:#fff !important; ">
                    <h4 style="text-align: center;border-color:#fff !important;font-size:16px !important; ">LABEL
                        INSPECTION FORM</h4>
                </th>
                <td style="width: 33%; text-align: right; font-size: 13px; border-color:#fff !important;">
                    <strong>Date:</strong>
                    <span style="border-bottom: 1px solid #000; display: inline-block; width: 100px;">&nbsp;
                        {{ date('m-d-Y', strtotime($get_daily_measure_date)) }}
                    </span>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table width="100%" style="border-top: 1px solid #ccc; padding-top: 8px;">
            <tr>
                <td style="width: 40%; font-size: 12px; border-color:#fff !important;">
                    <strong>FRM QC-E-14.0</strong><br>
                    Labeling Verification Log
                </td>

                <td style="width: 20%; text-align: center; border-color:#fff !important;">
                    <p style="margin: 0; font-size: 13px; font-weight: bold; color: #1c4587;">ATOM BANANA INC</p>
                    <p style="margin: 0; font-size: 12px; font-weight: bold;">2404 S Wolcott Ave STE 10 Chicago, IL
                        60608</p>
                </td>
            </tr>
        </table>

    </footer>


    <div class="content">
        <table style="border: 0.5px solid #999; padding: 10px; margin: 0 auto 15px" role="presentation" border="0"
            cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>
                    <p
                        style="
                margin: 0;
                padding: 00;
                font-family: Gotham, 'Helvetica Neue', Helvetica, Arial,
                  'sans-serif';
                font-size: 15px;
                text-align: left;
                line-height: 20px;
              ">
                        <strong>INSTRUCTIONS: </strong> All product labels must be reviewed
                        by QA/Food Safety personnel prior to each production run to ensure
                        accuracy and compliance. The label must include a clearly visible
                        and legible Country of Origin (COO) statement, best by date,
                        nutritional facts, allergen statement, product description, net
                        weight, ingredient statement, and barcode. All information must be
                        correct and within the die line. Any errors or omissions must be
                        reported and corrected prior to use. Completed verification logs
                        should be retained as part of our food safety documentation.
                    </p>
                </td>
            </tr>
        </table>
        <table style="padding-top:15px !important; padding-bottom:15px !important">
            <thead>
                <th style="width: 6%;"></th>
                <th style="width: 20%;">Product Description</th>
                <th style="width: 6%;">COO Accurate Y / N / N/A</th>
                <th style="width: 6%;">Best By Accurate Y / N / N/A</th>
                <th style="width: 6%;">Nutritional Facts Y / N / N/A</th>
                <th style="width: 7%;">Allergen Statement Y / N / N/A</th>
                <th style="width: 7%;">Ingredient Statement Y / N / N/A</th>
                <th style="width: 6%;">Barcode clear <br> Y / N / N/A</th>
                <th style="width: 8%;">Operator Initials</th>
                <th style="width: 8%;">FS Initials</th>
                <th style="width: 20%;">Comments / Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blending_items as $index => $row)
                    <tr>
                        <td>
                            {{-- @if ($get_daily_measure_date <= '2025-09-03')
                            @else --}}
                            @if (!empty($row['inspection_img']) && file_exists(public_path($row['inspection_img'])))
                                <img src="{{ public_path($row['inspection_img']) }}"
                                    style="text-align:center; height:40px; max-width:100%" alt="">
                            @endif
                            {{-- @endif --}}
                        </td>
                        <td>
                            {{-- {{ $row['item_name'] . ' ' . $row['weight'] . ' ' . $row['item_unit'] }} --}}
                            {{ $row['item_name'] .
                                ' - ' .
                                (fmod($row['weight'], 1) == 0 ? number_format($row['weight'], 0) : number_format($row['weight'], 2)) .
                                ' ' .
                                $row['item_unit'] }}
                        </td>
                        <td style="text-align:center">
                            {{ $row['coo_present'] == 'P' ? 'Y' : ($row['coo_present'] == 'F' ? 'N' : 'N/A') }}</td>
                        <td style="text-align:center">
                            {{ $row['best_by_accurate'] == 'P' ? 'Y' : ($row['best_by_accurate'] == 'F' ? 'N' : 'N/A') }}
                        </td>
                        <td style="text-align:center">
                            {{ $row['nutritional_acts'] == 'P' ? 'Y' : ($row['nutritional_acts'] == 'F' ? 'N' : 'N/A') }}
                        </td>
                        <td style="text-align:center">
                            {{ $row['allergen_statement'] == 'P' ? 'Y' : ($row['allergen_statement'] == 'F' ? 'N' : 'N/A') }}
                        </td>
                        <td style="text-align:center">
                            {{ $row['ingredient_statement'] == 'P' ? 'Y' : ($row['ingredient_statement'] == 'F' ? 'N' : 'N/A') }}
                        </td>
                        <td style="text-align:center">
                            {{ $row['barcode_clear'] == 'P' ? 'Y' : ($row['barcode_clear'] == 'F' ? 'N' : 'N/A') }}
                        </td>
                        <td>{{ $row['initials'] }}</td>
                        <td>{{ $row['fs_initials'] }}</td>
                        <td>{{ $row['note'] }}</td>
                    </tr>
                @endforeach
                {{-- @foreach ($guacamole_items as $index => $row)
                <tr>
                    <td>
                        @if ($get_daily_measure_date <= '2025-09-03')
                            @else
                            @if (!empty($row['inspection_img']) && file_exists(public_path($row['inspection_img'])))
                            <img src="{{ public_path($row['inspection_img']) }}"
                            style="text-align:center; height:40px; max-width:100%" alt="">
                            @endif
                            @endif
                    </td>
                    <td>{{ $row['item_name'] . ' ' . $row['weight'] . 'oz' }}</td>
                    <td style="text-align:center">{{ $row['coo_present'] == 'P' ? 'Y' : ($row['coo_present'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['best_by_accurate'] == 'P' ? 'Y' : ($row['best_by_accurate'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['nutritional_acts'] == 'P' ? 'Y' : ($row['nutritional_acts'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['allergen_statement'] == 'P' ? 'Y' : ($row['allergen_statement'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['ingredient_statement'] == 'P' ? 'Y' : ($row['ingredient_statement'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['barcode_clear'] == 'P' ? 'Y' : ($row['barcode_clear'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td>{{ $row['initials'] }}</td>
                    <td>{{$row['fs_initials']}}</td>
                    <td>{{ $row['note'] }}</td>
                </tr>
                @endforeach
                @foreach ($mixing_items as $index => $row)
                <tr>
                    <td>
                        @if ($get_daily_measure_date <= '2025-09-03')
                            @else
                            @if (!empty($row['inspection_img']) && file_exists(public_path($row['inspection_img'])))
                            <img src="{{ public_path($row['inspection_img']) }}"
                            style="text-align:center; height:40px; max-width:100%" alt="">
                            @endif
                            @endif
                    </td>
                    <td>{{ $row['item_name'] . ' ' . $row['weight'] . 'oz' }}</td>
                    <td style="text-align:center">{{ $row['coo_present'] == 'P' ? 'Y' : ($row['coo_present'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['best_by_accurate'] == 'P' ? 'Y' : ($row['best_by_accurate'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['nutritional_acts'] == 'P' ? 'Y' : ($row['nutritional_acts'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['allergen_statement'] == 'P' ? 'Y' : ($row['allergen_statement'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['ingredient_statement'] == 'P' ? 'Y' : ($row['ingredient_statement'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['barcode_clear'] == 'P' ? 'Y' : ($row['barcode_clear'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td>{{ $row['initials'] }}</td>
                    <td>{{ $row['fs_initials'] }}</td>
                    <td>{{ $row['note'] }}</td>
                </tr>
                @endforeach
                @foreach ($metal_detector_items as $index => $row)
                <tr>
                    <td>
                        @if ($get_daily_measure_date <= '2025-09-03')
                            @else
                            @if (!empty($row['inspection_img']) && file_exists(public_path($row['inspection_img'])))
                            <img src="{{ public_path($row['inspection_img']) }}"
                            style="text-align:center; height:40px; max-width:100%" alt="">
                            @endif
                            @endif
                    </td>
                    <td>{{ $row['item_name'] . ' ' . $row['weight'] . $row['item_unit'] }}</td>
                    <td style="text-align:center">{{ $row['coo_present'] == 'P' ? 'Y' : ($row['coo_present'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['best_by_accurate'] == 'P' ? 'Y' : ($row['best_by_accurate'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['nutritional_acts'] == 'P' ? 'Y' : ($row['nutritional_acts'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['allergen_statement'] == 'P' ? 'Y' : ($row['allergen_statement'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['ingredient_statement'] == 'P' ? 'Y' : ($row['ingredient_statement'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td style="text-align:center">{{ $row['barcode_clear'] == 'P' ? 'Y' : ($row['barcode_clear'] == 'F' ? 'N' : 'N/A') }}</td>
                    <td>{{ $row['initials'] }}</td>
                    <td>{{ $row['fs_initials'] }}</td>
                    <td>{{ $row['note'] }}</td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>
        <table width="100%" style="border-top: 1px solid #ccc; padding-top: 8px;">
            <tr>
                <td style="width: 75%; font-size: 12px;border-color:#fff !important; ">
                    <strong>Reviewed by PCQI </strong>
                    <span style="display: inline-block; border-bottom: 1px solid #000; width: 160px;">&nbsp;
                        {{ $get_approver_name }}
                    </span>
                </td>

                <td style="width: 25%; text-align: right; font-size: 12px;border-color:#fff !important; ">
                    <strong>Date:</strong>
                    <span style="display: inline-block; border-bottom: 1px solid #000; width: 100px;">&nbsp;
                        {{ date('m-d-Y', strtotime($get_daily_measure_date)) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
