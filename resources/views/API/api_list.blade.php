<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <title>All API for Atom Boxing (Atom onsite Printing)</title>
    <style>
        tr,
        td,
        th {
            font-size: 18px;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 15px !important;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <section class="container" style="margin-top: 10%">
        <div class="row">
            <h2 class="text-center p-5"> All API for Atom Boxing (Atom onsite Printing) </h2>
            <div class="col-12 col-md-12" style="margin-top: 20px">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">API Name</th>
                            <th scope="col">URL</th>
                            <th scope="col">Paramiters</th>
                            <th scope="col">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($get_all_data)
                            <?php $sl = 1; ?>
                            @foreach ($get_all_data as $api_data)
                                <tr>
                                    <th scope="row">{{ $sl }}</th>
                                    <td>{{ $api_data->name }}</td>
                                    <td>{{ $api_data->api_url }}</td>
                                    <td>{{ $api_data->paramiter }}</td>
                                    <td>{{ $api_data->method }}</td>
                                </tr>
                                <?php $sl++;?>
                            @endforeach
                        @else
                            <tr>
                                <th scope="row">
                                    <p class="text-center text-danger h3"> No Data Found! </p>
                                </th>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</body>

</html>
