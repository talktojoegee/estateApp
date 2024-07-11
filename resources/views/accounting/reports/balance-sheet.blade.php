@extends('layouts.master-layout')
@section('title')
    Balance Sheet
@endsection

@section('current-page')
    Balance Sheet
@endsection
@section('current-page-brief')
    Balance Sheet
@endsection

@section('event-area')
    @include('manager.accounting.reports.partials._menu')
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\component.css">
@endsection
@section('main-content')
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <h5 class="sub-title">Accounting Period</h5>
                            <p>Enter date to generate balance sheet up to that time.</p>
                            @if (session()->has('success'))
                                <div class="alert alert-success background-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="icofont icofont-close-line-circled text-white"></i>
                                    </button>
                                    {!! session()->get('success') !!}
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-warning background-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="icofont icofont-close-line-circled text-white"></i>
                                    </button>
                                    {!! session()->get('error') !!}
                                </div>
                            @endif
                            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-6 offset-md-3 offset-lg-3 ">
                                    <form action="{{route('balance-sheet')}}" method="post">
                                        @csrf
                                    <div class="form-group">
                                        <div class="input-group input-group-button">
                                                <span class="input-group-addon btn btn-primary" id="basic-addon9">
                                                    <span class="">Date</span>
                                                </span>
                                            <input type="date" class="form-control" name="date" placeholder="Date">
                                            <span class="input-group-addon btn btn-primary" id="basic-addon9">
                                                    <button class="btn btn-primary btn-mini" type="submit">Submit</button>
                                                </span>
                                        </div>
                                        <br>
                                    </div>
                                    </form>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($status == 1)
        <div class="card">
            <div class="row invoice-contact">

                <div class="col-md-8">
                    <div class="invoice-box row">
                        <div class="col-sm-12">
                            <table class="table table-responsive invoice-table table-borderless">
                                <tbody>
                                <tr>
                                    <td><img src="/assets/images/logo.png" class="m-b-10" width="82" height="52" alt=""></td>
                                </tr>
                                <tr>
                                    <td>Connexxion Telecom</td>
                                </tr>
                                <tr>
                                    <td>2A Iller Crescent Maitama, Abuja</td>
                                </tr>
                                <tr>
                                    <td><a href="..\..\..\cdn-cgi\l\email-protection.htm#99fdfcf4f6d9fef4f8f0f5b7faf6f4" target="_top"><span class="__cf_email__" data-cfemail="690d0c0406290e04080005470a0604">[email&nbsp;protected]</span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>+234...</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="card-block">
                <div class="row invoive-info">
                    <div class="col-md-4 col-xs-12 invoice-client-info">
                        <h6>Account Period:</h6>
                        <h6 class="m-0"><strong class="label label-info">Date:</strong> {{date('d F, Y', strtotime($date))}} </h6>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <h6>Balance Sheet</h6>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <h6 class="m-b-20">Date & Time</h6>
                        <h6 class="text-uppercase">{{date('d F, Y h:ia', strtotime(now()))}}
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <table id="complex-header" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                            <thead>
                            <tr role="row">
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">S/No.</th>
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 227px;">ACCOUNT CODE</th>
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 227px;">ACCOUNT NAME</th>
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 227px;">DR</th>
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 227px;">CR</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $a = 1;
                            @endphp
                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="9"><strong style="font-size:16px; text-transform:uppercase;">Assets</strong></td>
                            </tr>
                            @php
                                $aOPDrTotal = 0;
                                $aOPCrTotal = 0;
                                $aPDrTotal = 0;
                                $aPCrTotal = 0;
                                $aCPCrTotal = 0;
                                $aCPDrTotal = 0;
                                $rCb = 0;
                                $eCb = 0;
                            @endphp
                            @foreach($reports as $report)
                                @switch($report->account_type)
                                    @case(1)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{$a++}}</td>
                                        <td class="sorting_1 text-center">{{$report->glcode ?? ''}}</td>
                                        <td class="text-center">{{$report->account_name ?? ''}}</td>
                                        <td class="text-center">{{(($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) > 0 ?  number_format((($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)),2) : 0}}
                                            <small style="display: none;">  {{ $aCPDrTotal +=  (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) > 0 ? (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) : 0  }}</small>
                                        </td>
                                        <td class="text-center">{{(($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) < 0 ? number_format((($bfCr + $report->sumCredit) - ($bfDr + $report->sumDebit)),2) : 0}}
                                            <small style="display: none;">  {{ $aCPCrTotal += (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) < 0 ? (($bfCr + $report->sumCredit) - ($bfDr + $report->sumDebit)) : 0 }}</small>
                                        </td>
                                    </tr>
                                    @break
                                @endswitch
                            @endforeach

                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="9">
                                    <strong style="font-size:16px; text-transform:uppercase;">Liability</strong>
                                </td>
                            </tr>
                            @foreach($reports as $report)
                                @switch($report->account_type)
                                    @case(2)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{$a++}}</td>
                                        <td class="sorting_1 text-center">{{$report->glcode ?? ''}}</td>
                                        <td class="text-center">{{$report->account_name ?? ''}}</td>
                                        <td class="text-center">{{(($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) > 0 ?  number_format((($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)),2) : 0}}
                                            <small style="display: none;">  {{ $aCPDrTotal +=  (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) > 0 ? (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) : 0  }}</small>
                                        </td>
                                        <td class="text-center">{{(($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) < 0 ? number_format((($bfCr + $report->sumCredit) - ($bfDr + $report->sumDebit)),2) : 0}}
                                            <small style="display: none;">  {{ $aCPCrTotal += (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) < 0 ? (($bfCr + $report->sumCredit) - ($bfDr + $report->sumDebit)) : 0 }}</small>
                                        </td>
                                    </tr>
                                    @break
                                @endswitch
                            @endforeach
                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="9"><strong style="font-size:16px; text-transform:uppercase;">Equity</strong></td>
                            </tr>
                            @foreach($reports as $report)
                                @switch($report->account_type)
                                    @case(3)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{$a++}}</td>
                                        <td class="sorting_1 text-center">{{$report->glcode ?? ''}}</td>
                                        <td class="text-center">{{$report->account_name ?? ''}}</td>
                                        <td class="text-center">{{(($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) > 0 ?  number_format((($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)),2) : 0}}
                                            <small style="display: none;">  {{ $aCPDrTotal +=  (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) > 0 ? (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) : 0  }}</small>
                                        </td>
                                        <td class="text-center">{{(($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) < 0 ? number_format((($bfCr + $report->sumCredit) - ($bfDr + $report->sumDebit)),2) : 0}}
                                            <small style="display: none;">  {{ $aCPCrTotal += (($bfDr + $report->sumDebit) - ($bfCr + $report->sumCredit)) < 0 ? (($bfCr + $report->sumCredit) - ($bfDr + $report->sumDebit)) : 0 }}</small>
                                        </td>
                                    </tr>
                                    @break
                                @endswitch
                            @endforeach
                            <tr role="row" class="odd">
                                <td class="text-center">{{$a++}}</td>
                                <td class="sorting_1 text-center">301</td>
                                <td class="text-center">Retained Earning</td>
                                <small style="display: none;">  {{ $rCb +=  $revenue->sum('dr_amount') - $revenue->sum('cr_amount') > 0 ? ($revenue->sum('dr_amount') - $revenue->sum('cr_amount')) : ($revenue->sum('cr_amount') - $revenue->sum('dr_amount'))}}</small>
                                <small style="display: none;">  {{ $eCb +=  $expense->sum('dr_amount') - $expense->sum('cr_amount') }}</small>

                                <td class="text-center">{{ $rCb - $eCb < 0 ? number_format($rCb - $eCb,2) : ''}}
                                </td>
                                <td class="text-center">{{ $rCb - $eCb > 0 ? number_format($rCb - $eCb,2) : ''}}
                                    <small style="display: none;">  {{ $aCPCrTotal +=  $rCb - $eCb }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong style="font-size:14px; text-transform:uppercase; text-align: right;">Total:</strong></td>
                                <td class="text-center"> {{number_format($aCPDrTotal,2)}}</td>
                                <td class="text-center"> {{number_format($aCPCrTotal,2)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    @endif

@endsection

@section('extra-scripts')
    <script src="\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="\assets\pages\data-table\extensions\key-table\js\key-table-custom.js"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.approve', function(event){
                event.preventDefault();
                $('#applicant').text($(this).data('applicant'));
                $('#applicantId').val($(this).data('appid'));
                $('#propertyId').val($(this).data('property'));
            });
        });
    </script>
@endsection
