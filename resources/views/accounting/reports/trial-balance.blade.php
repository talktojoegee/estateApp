@extends('layouts.master-layout')
@section('title')
    Trial Balance
@endsection

@section('current-page')
    Trial Balance
@endsection
@section('current-page-brief')
    Trial Balance
@endsection

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\component.css">
@endsection
@section('main-content')
    <div class="row">
        <div class="col-lg-6 col-xl-6 offset-lg-3 offset-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <h5 class="modal-header mb-4 text-info text-uppercase">Accounting Period</h5>
                            @if (session()->has('success'))
                                <div class="alert alert-success background-success">

                                    {!! session()->get('success') !!}
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-warning background-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="bx bx-x-circle text-white"></i>
                                    </button>
                                    {!! session()->get('error') !!}
                                </div>
                            @endif
                            <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12">
                                    <form action="{{route('trial-balance')}}" method="post">
                                        @csrf
                                    <div class="form-group">
                                        <div class="input-group ">
                                                <button class=" btn btn-primary" id="basic-addon9">
                                                    From
                                                </button>
                                            <input type="date" value="{{ date('Y-m-d', strtotime("-90 days")) }}" class="form-control" name="start_date" placeholder="Start Date">
                                            <button class=" btn btn-primary" id="basic-addon9">
                                                    To
                                                </button>
                                            <input type="date" value="{{date('Y-m-d', strtotime("+1 days"))}}" class="form-control" name="end_date" placeholder="End Date">
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
            <div class="card-body">
                <div class="row invoice-contact">
                    <div class="col-md-12">
                        <div class="invoice-box row">

                            <div class="row">
                                <div class="col-sm-12 d-flex justify-content-center "   >
                                    <address class="mt-2 mt-sm-0">
                                        <img src="/assets/drive/logo/logo-dark.png" alt="logo" height="60"> <br>
                                        <strong>Name:</strong> {{env('ORG_NAME')}}<br>
                                        <strong>Mobile No.:</strong> {{env('ORG_PHONE')}}<br>
                                        <strong>Email:</strong> <a href="mailto:{{env('ORG_EMAIL')}}">{{env('ORG_EMAIL')}}</a><br>
                                        <strong>Address: </strong>{!! env('ORG_ADDRESS') !!} <br>
                                        <strong>Website: </strong><a target="_blank" href="http://www.{{env('ORG_WEBSITE')}}">{{env('ORG_WEBSITE')}}</a>
                                    </address>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div class="row invoive-info">
                    <div class="col-md-4 col-xs-12 invoice-client-info">
                        <h6 class="text-muted">Account Period:</h6>
                        <h6 class="m-0 text-muted"><strong class="label text-info">From:</strong> {{date('d F, Y', strtotime($from))}} <strong class="label text-danger">To:</strong> {{date('d F, Y', strtotime($to))}}</h6>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <h6 class="text-muted">Trial Balance</h6>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <h6 class="m-b-20 text-muted">Date & Time</h6>
                        <h6 class="text-uppercase text-muted">{{date('d F, Y h:ia', strtotime(now()))}}
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
                                <th colspan="2" rowspan="1" class="text-center">OPENING PERIOD</th>
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 227px;">DR</th>
                                <th rowspan="2" class="sorting_asc text-center" tabindex="0" aria-controls="complex-header" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 227px;">CR</th>
                                <th colspan="2" rowspan="1" class="text-center">CLOSING PERIOD</th>
                            </tr>
                            <tr role="row">
                                <th class="sorting text-center" tabindex="0" aria-controls="complex-header" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 336px;">DR</th>
                                <th class="sorting text-center" tabindex="0" aria-controls="complex-header" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 167px;">CR</th>
                                <th class="sorting text-center" tabindex="0" aria-controls="complex-header" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 116px;">DR</th>
                                <th class="sorting text-center" tabindex="0" aria-controls="complex-header" rowspan="1" colspan="1" aria-label="Extn.: activate to sort column ascending" style="width: 142px;">CR.</th>
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
                            @endphp
                            @foreach($reports as $report)
                                @switch($report->account_type)
                                    @case(1)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{$a++}}</td>
                                        <td class="sorting_1 text-center">{{$report->glcode ?? ''}}</td>
                                        <td class="text-center">{{$report->account_name ?? ''}}</td>
                                        <td class="text-center">{{$bfDr - $bfCr > 0 ? number_format($bfDr - $bfCr,2) : 0}}
                                            <small style="display: none;">  {{ $aOPDrTotal += ($bfDr - $bfCr) > 0 ? ($bfDr - $bfCr) : 0 }}</small>
                                        </td>
                                        <td class="text-center">{{$bfDr - $bfCr < 0 ? number_format($bfCr - $bfDr,2) : 0}}
                                            <small style="display: none;">  {{ $aOPCrTotal += ($bfDr - $bfCr) < 0 ? ($bfCr - $bfDr) : 0 }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumDebit ,2)?? 0}}
                                            <small style="display: none;">  {{ $aPDrTotal += $report->sumDebit }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumCredit,2) ?? 0 }}
                                            <small style="display: none;">  {{ $aPCrTotal += $report->sumCredit }}</small>
                                        </td>
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
                                        <td class="text-center">{{$bfDr - $bfCr > 0 ? number_format($bfDr - $bfCr,2) : 0}}
                                            <small style="display: none;">  {{ $aOPDrTotal += ($bfDr - $bfCr) > 0 ? ($bfDr - $bfCr) : 0 }}</small>
                                        </td>
                                        <td class="text-center">{{$bfDr - $bfCr < 0 ? number_format($bfCr - $bfDr,2) : 0}}
                                            <small style="display: none;">  {{ $aOPCrTotal += ($bfDr - $bfCr) < 0 ? ($bfCr - $bfDr) : 0 }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumDebit ,2)?? 0}}
                                            <small style="display: none;">  {{ $aPDrTotal += $report->sumDebit }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumCredit,2) ?? 0 }}
                                            <small style="display: none;">  {{ $aPCrTotal += $report->sumCredit }}</small>
                                        </td>
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
                                        <td class="text-center">{{$bfDr - $bfCr > 0 ? number_format($bfDr - $bfCr,2) : 0}}
                                            <small style="display: none;">  {{ $aOPDrTotal += ($bfDr - $bfCr) > 0 ? ($bfDr - $bfCr) : 0 }}</small>
                                        </td>
                                        <td class="text-center">{{$bfDr - $bfCr < 0 ? number_format($bfDr - $bfCr,2) : 0}}
                                            <small style="display: none;">  {{ $aOPCrTotal += ($bfDr - $bfCr) < 0 ? ($bfCr - $bfDr) : 0 }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumDebit ,2)?? 0}}
                                            <small style="display: none;">  {{ $aPDrTotal += $report->sumDebit }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumCredit,2) ?? 0 }}
                                            <small style="display: none;">  {{ $aPCrTotal += $report->sumCredit }}</small>
                                        </td>
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
                                <td class="sorting_1"  colspan="9"><strong style="font-size:16px; text-transform:uppercase;">Revenue</strong></td>
                            </tr>
                            @foreach($reports as $report)
                                @switch($report->account_type)
                                    @case(4)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{$a++}}</td>
                                        <td class="sorting_1 text-center">{{$report->glcode ?? ''}}</td>
                                        <td class="text-center">{{$report->account_name ?? ''}}</td>
                                        <td class="text-center">{{0}}</td>
                                        <td class="text-center">{{0}}</td>
                                        <td class="text-center">{{number_format($report->sumDebit ,2)?? 0}}
                                            <small style="display: none;">  {{ $aPDrTotal += $report->sumDebit }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumCredit,2) ?? 0 }}
                                            <small style="display: none;">  {{ $aPCrTotal += $report->sumCredit }}</small>
                                        </td>
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
                                <td class="sorting_1"  colspan="9"><strong style="font-size:16px; text-transform:uppercase;">Expenses</strong></td>
                            </tr>
                            @foreach($reports as $report)
                                @switch($report->account_type)
                                    @case(5)
                                    <tr role="row" class="odd">
                                        <td class="text-center">{{$a++}}</td>
                                        <td class="sorting_1 text-center">{{$report->glcode ?? ''}}</td>
                                        <td class="text-center">{{$report->account_name ?? ''}}</td>
                                        <td class="text-center">{{0}}</td>
                                        <td class="text-center">{{0}}</td>
                                        <td class="text-center">{{number_format($report->sumDebit ,2)?? 0}}
                                            <small style="display: none;">  {{ $aPDrTotal += $report->sumDebit }}</small>
                                        </td>
                                        <td class="text-center">{{number_format($report->sumCredit,2) ?? 0 }}
                                            <small style="display: none;">  {{ $aPCrTotal += $report->sumCredit }}</small>
                                        </td>
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
                            <tr>
                                <td colspan="3" class="text-right"><strong style="font-size:14px; text-transform:uppercase; text-align: right;">Total:</strong></td>
                                <td class="text-center">{{ number_format($aOPDrTotal,2) }} </td>
                                <td class="text-center"> {{ number_format($aOPCrTotal,2) }} </td>
                                <td class="text-center"> {{ number_format($aPDrTotal,2) }} </td>
                                <td class="text-center"> {{ number_format($aPCrTotal,2) }} </td>
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
