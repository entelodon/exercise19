<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
<div id="app">
    <div class="container-fluid">
        <div class="row justify-content-center mt-3 mb-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">Generated Report
                        for {{$retrievedData->getCompany()->name}} for prices in between
                        ({{$retrievedData->getStartDateFormatted()}} and {{$retrievedData->getEndDateFormatted()}})
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-dark">
                            <thead>
                            <tr>
                                <th class="text-center" scope="col">Date</th>
                                <th class="text-center" scope="col">Open</th>
                                <th class="text-center" scope="col">High</th>
                                <th class="text-center" scope="col">Low</th>
                                <th class="text-center" scope="col">Close</th>
                                <th class="text-center" scope="col">Volume values</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($retrievedData->getStructuredData()->getRows() as $row)
                                <tr>
                                    <td class="text-center" scope="col">{{$row['Date']}}</td>
                                    <td class="text-center" scope="col">{{$row['Open']}}</td>
                                    <td class="text-center" scope="col">{{$row['High']}}</td>
                                    <td class="text-center" scope="col">{{$row['Low']}}</td>
                                    <td class="text-center" scope="col">{{$row['Close']}}</td>
                                    <td class="text-center" scope="col">{{$row['Volume']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">Charts</div>
                    <div class="card-body">
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script type="application/ecmascript">
    let ctx = document.getElementById('myChart');
    let priceChart = new Chart(document.getElementById("priceChart"), {
        "type": "line",
        "data": {
            "labels": @json($retrievedData->getReportData()->getDates()),
            "datasets": [
                {
                    "label": "Open Price",
                    "data": @json($retrievedData->getReportData()->getOpenPrices()),
                    "fill": false,
                    //We could use more friendly colors
                    "borderColor": "rgb(182,13,13)",
                },
                {
                    "label": "Close Price",
                    "data": @json($retrievedData->getReportData()->getClosePrices()),
                    "fill": false,
                    //We could use more friendly colors
                    "borderColor": "rgb(73,161,32)",
                }
            ]
        },
        "options": {}
    });
</script>
</body>
</html>
