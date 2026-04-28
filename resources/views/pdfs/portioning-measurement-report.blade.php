<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredient Portioning Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            padding: 15px;
            background: #f5f5f5;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            color: #d32f2f;
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .date-section {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .procedure-box {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 10px;
            background: #fff;
        }

        .procedure-box p {
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .equipment-section {
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 5px;
            display: table;
            width: 100%;
            background: #fff;
        }

        .equipment-section>div {
            display: table-cell;
            vertical-align: middle;
            padding: 3px;
        }

        .equipment-list {
            width: 50%;
        }

        .operator-info {
            width: 50%;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 10px;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        td.left-align {
            text-align: left;
        }

        .footer-section {
            margin-top: 10px;
            border: 1px solid #000;
            padding: 8px;
            background: #fff;
        }

        .footer-section p {
            margin-bottom: 5px;
        }

        .signature-line {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .form-code {
            text-align: center;
            margin-top: 10px;
            font-size: 8px;
        }

        .confidential {
            float: right;
            font-style: italic;
        }
    </style>
</head>

<body>


    @foreach ($dataset as $dataset_data)
    <div class="date-section">
        DATE: {{ $reportDate }}
    </div>

    <div class="header">
        <h2>FOOD SAFETY</h2>
        <h3>Ingredient Portioning Form</h3>
    </div>

    <div class="procedure-box">
        <p>
            <strong>Procedure:</strong> Conduct an inspection of the product prior to start being portioned and every
            hour since then correct product, lot number, declare any allergen ingredients. Inspect 3 samples every hour,
            weight, seal, temperature, and Correct Allergen.
        </p>
        <p>
            <strong>IMPORTANT:</strong> Cleaning is required after running an allergen ingredient and an Allergen Swab
            test must be performed and must be record below indication P (Pass) or F (Fail)
        </p>
    </div>
    <div class="equipment-section">
        <div class="equipment-list">
            <strong>Equipment:</strong> {{ $dataset_data['report_head']['equipment'] }} <br>
            <strong>Table:</strong> {{ $dataset_data['report_head']['table_name'] }}
        </div>
        <div class="operator-info">
            <strong>Operator name:</strong> {{ $dataset_data['report_head']['measure_by'] }}<br>
            <strong>People Qty:</strong> {{ $dataset_data['report_head']['people_qty'] }} &nbsp;&nbsp;&nbsp;
            <strong>Scale
                #:</strong> {{
            $dataset_data['report_head']['scale'] }} &nbsp;&nbsp;&nbsp;
            <strong>Pre-Op Complete:</strong> {{ $dataset_data['report_head']['equipment'] }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">Time</th>
                <th style="width: 15%;">Product Description</th>
                <th style="width: 10%;">Lot number</th>
                <th style="width: 5%;">Temp °F</th>
                <th style="width: 8%;">Allergen<br>(If applicable)</th>
                <th style="width: 5%;">Allergen Test Result</th>
                <th style="width: 7%;">Pack Size</th>
                <th style="width: 6%;">Sample 1</th>
                <th style="width: 6%;">Sample 2</th>
                <th style="width: 6%;">Sample 3</th>
                <th style="width: 5%;">Kit Letter</th>
                <th style="width: 8%;">Qty Produced (Final)</th>
                <th style="width: 5%;">FS Initial</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataset_data['report_line_items'] as $row)
            <tr>
                <td>{{ $row['time'] }}</td>
                <td class="left-align">{{ strtoupper($row['product_description']) }}</td>
                <td>{{ $row['lot_number'] }}</td>
                <td>{{ $row['temp'] }}</td>
                <td>{{ $row['allergen'] }}</td>
                <td>{{ $row['allergen_test_result'] }}</td>
                <td>{{ $row['pack_size'] }}</td>
                <td>{{ $row['sample_1'] }}</td>
                <td>{{ $row['sample_2'] }}</td>
                <td>{{ $row['sample_3'] }}</td>
                <td>{{ $row['kit_letter'] }}</td>
                <td>{{ $row['qty_produced'] }}</td>
                <td>{{ $row['fs_initial'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-section">
        <p><strong>Notes/Comments:</strong> ____________________________________________________</p>
        <div class="signature-line">
            <div>
                <strong>Reviewed by (printed name):</strong> ____________________
            </div>
            <div>
                <strong>Date:</strong> {{ $generatedDate }}
            </div>
        </div>
    </div>

    <div class="form-code">
        FRM-PR-B-04.2 Portioning Form
        <span class="confidential">Confidential and Proprietary</span>
    </div>
    <div style="page-break-after: always;"></div>
    @endforeach


</body>

</html>
