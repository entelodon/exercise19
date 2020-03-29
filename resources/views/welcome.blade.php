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
    <div class="container">
        <div class="row justify-content-center mt-3 mb-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        Generate Report
                    </div>
                    <div class="card-body">
                        @if($errors && $errors->count() > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="list-group">
                                        @foreach ($errors->all() as $error)
                                            <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <form id="report-form" method="POST" action="{{route('report.generate')}}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="symbol">Company Symbol:</label>
                                        <input name="symbol" id="symbol" required>
                                        <span id="symbolError" class="hidden-error text-danger">Please select a valid company symbol.</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email">Email Address:</label>
                                        <input name="email" type="email" class="form-control" id="email" required>
                                        <span id="emailError" class="hidden-error text-danger">Please insert a valid email address.</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="dateFrom">Date From:</label>
                                        <input class="form-control" name="dateFrom" id="dateFrom" required>
                                        <span id="dateFromError" class="hidden-error text-danger">Please select a valid date.</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dateTo">Date To:</label>
                                        <input class="form-control" name="dateTo" id="dateTo" disabled required>
                                        <span id="dateToError" class="hidden-error text-danger">Please select a valid date.</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <button class="btn btn-block btn-success" type="submit">Get report</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-block btn-danger" type="reset">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

<script type="application/ecmascript" defer>
    window.companies = @json($companies);

    function validateEmail(email) {
        let emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return emailRegex.test(email);
    }

    $(window).ready(function () {
        window.dateFormat = 'yy-mm-dd';

        $('#report-form').on('submit', function (event) {
            event.preventDefault();
            let momentDateFormat = 'YYYY-MM-DD';

            /**
             * FORM VALIDATION
             */

            let errorsFound = false;

            /**
             * Email validation
             */

            let formEmail = $('#email');
            if (formEmail.val() === '' || !validateEmail(formEmail.val())) {
                errorsFound = true;
                $('#emailError').removeClass('hidden-error');
            } else {
                $('#emailError').addClass('hidden-error');
            }

            /**
             * Symbol validation
             */

            let formSymbol = $('#symbol');
            if (formSymbol.val() === '') {
                errorsFound = true;
                $('#symbolError').removeClass('hidden-error');
            } else {
                $('#symbolError').addClass('hidden-error');
            }

            /**
             * Date From validation
             */

            let dateFrom = $('#dateFrom');
            if (dateFrom.val() === '' || !moment(dateFrom.val(), momentDateFormat).isValid() ||
                moment(dateFrom.val(), momentDateFormat).isAfter(moment.now())) {
                errorsFound = true;
                $('#dateFromError').removeClass('hidden-error');
            } else {
                $('#dateFromError').addClass('hidden-error');
            }

            /**
             * Date To validation
             */

            let dateTo = $('#dateTo');
            if (dateTo.val() === '' ||
                !moment(dateTo.val(), momentDateFormat).isValid() ||
                moment(dateTo.val(), momentDateFormat).isAfter(moment.now())
            ) {
                errorsFound = true;
                $('#dateToError').removeClass('hidden-error');
            } else {
                if (
                    dateFrom.val() !== '' && moment(dateFrom.val(), momentDateFormat).isValid() &&
                    moment(dateTo.val()).isSameOrAfter(dateFrom.val())
                ) {
                    $('#dateToError').addClass('hidden-error');
                } else {
                    errorsFound = true;
                    $('#dateToError').removeClass('hidden-error');
                }
            }

            if (!errorsFound) {
                this.submit();
            }

        });


        $('#dateFrom').datepicker({
            maxDate: '0',
            dateFormat: window.dateFormat
        }).on('change', function () {
            let dateFromElem = $('#dateFrom');
            let selectedDateFrom = dateFromElem.val();
            let dateToElement = $('#dateTo');
            if (selectedDateFrom !== '') {
                dateToElement.prop("disabled", false);
            } else {
                dateToElement.prop("disabled", true);
            }
            dateToElement.datepicker("destroy");
            dateToElement.val(dateFromElem.val())
            dateToElement.datepicker({
                minDate: dateFromElem.val(),
                maxDate: '0',
                dateFormat: window.dateFormat
            });
        });

        $('#dateTo').datepicker();

        $('#symbol').selectize({
            create: false,
            maxItems: 1,
            sortField: 'text',
            valueField: 'symbol',
            labelField: 'symbol',
            searchField: 'symbol',
            options: window.companies,
        });
    });
</script>
</body>
</html>
