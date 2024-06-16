@extends('adminmodule::layouts.master')

@section('title',translate('Loyalty_Point_Transaction_Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Loyalty_Point_Report')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 fz-16">{{translate('Filter_Data')}}</div>

                            <form action="{{route('admin.customer.loyalty-point.report', ['transaction_type'=>$query_params['transaction_type']])}}" method<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>

       {{-- Laravel Mix - CSS File --}}
       {{-- <link rel="stylesheet" href="{{ mix('css/customermodule.css') }}"> --}}

    </head>
    <body>
        <script>
            localStorage.theme && document.querySelector('body').setAttribute("theme", localStorage.theme);
            localStorage.dir && document.querySelector('html').setAttribute("dir", localStorage.dir);
        </script>
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/customermodule.js') }}"></script> --}}
    </body>
</html>
