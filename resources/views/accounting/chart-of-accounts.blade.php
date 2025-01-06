@extends('layouts.master-layout')
@section('title')
    Chart of Accounts
@endsection
@section('current-page')
    Chart of Accounts
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .odd{
            background: #01204D !important;
        }
        .table-head{
            background: #FF0000 !important;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')
 Chart of Accounts
@endsection

@section('main-content')

    <div class="row ">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <h5 class="modal-header text-info text-uppercase">Chart of Accounts</h5></div>
            <div class="card-header mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary "> <i
                        class="bx bx bxs-left-arrow"></i> Go back</a>
                @can('can-add-coa')
                <a href="{{ route('new-chart-of-account') }}" class="btn btn-primary ">  Add New Account <i
                        class="bx bx-list-plus"></i></a>
                @endcan

                <div class="card-body ">
                    <div class="col-xs-12 col-sm-12 mb-4 ">
                        @if(count($charts) > 0)
                            <table id="complex-header" class="table table-striped text-white table-bordered nowrap dataTable" id="chartOfAccountsTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                            <thead>
                            <tr role="row" class="table-head">
                                <th class="sorting_asc text-left" tabindex="0" style="width: 50px;">S/No.</th>
                                <th class="sorting_asc text-left" tabindex="0" style="width: 50px;">ACCOUNT CODE</th>
                                <th class="sorting_asc text-left" tabindex="0" style="width: 150px;">ACCOUNT NAME</th>
                                <th class="sorting_asc text-left" tabindex="0" >PARENT</th>
                                <th class="sorting_asc text-left" tabindex="0" >TYPE</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $a = 1;
                            @endphp
                            <tr role="row" class="odd">
                                <td class="sorting_1" colspan="5"><strong style="font-size:16px; text-transform:uppercase;">Assets</strong></td>
                            </tr>
                            @foreach($charts as $report)
                                @switch($report->account_type)
                                    @case(1)
                                    @if ($report->glcode != 1)
                                        <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                            <td class="text-left">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                            <td class="text-left">{{$report->account_name ?? ''}}</td>
                                            <td class="text-left">{{ $report->getAccountByGlCode($report->parent_account)->account_name ?? null }} ({{ $report->parent_account }})</td>
                                            <td class="text-left">{{$report->type == 1 ? 'General' : 'Detail'}}</td>
                                        </tr>
                                    @endif
                                    @break
                                @endswitch
                            @endforeach

                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="5">
                                    <strong style="font-size:16px; text-transform:uppercase;">Liability</strong>
                                </td>
                            </tr>
                            @foreach($charts as $report)
                                @switch($report->account_type)
                                    @case(2)
                                    @if ($report->glcode != 2)
                                        <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                            <td class="text-left">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                            <td class="text-left">{{$report->account_name ?? ''}}</td>
                                            <td class="text-left">{{ $report->getAccountByGlCode($report->parent_account)->account_name ?? null }} ({{ $report->parent_account }})</td>
                                            <td class="text-left">{{$report->type == 1 ? 'General' : 'Detail'}}</td>
                                        </tr>

                                    @endif
                                    @break
                                @endswitch
                            @endforeach
                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="5"><strong style="font-size:16px; text-transform:uppercase;">Equity</strong></td>
                            </tr>
                            @foreach($charts as $report)
                                @switch($report->account_type)
                                    @case(3)
                                    @if ($report->glcode != 3)
                                        <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                            <td class="text-left">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                            <td class="text-left">{{$report->account_name ?? ''}}</td>
                                            <td class="text-left">{{ $report->getAccountByGlCode($report->parent_account)->account_name ?? null }} ({{ $report->parent_account }})</td>
                                            <td class="text-left">{{$report->type == 1 ? 'General' : 'Detail'}}</td>
                                        </tr>

                                    @endif
                                    @break
                                @endswitch
                            @endforeach
                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="5"><strong style="font-size:16px; text-transform:uppercase;">Revenue</strong></td>
                            </tr>
                            @foreach($charts as $report)
                                @switch($report->account_type)
                                    @case(4)
                                    @if ($report->glcode != 4)
                                        <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                            <td class="text-left">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                            <td class="text-left">{{$report->account_name ?? ''}}</td>
                                            <td class="text-left">{{ $report->getAccountByGlCode($report->parent_account)->account_name ?? null }} ({{ $report->parent_account }})</td>
                                            <td class="text-left">{{$report->type == 1 ? 'General' : 'Detail'}}</td>
                                        </tr>

                                    @endif
                                    @break
                                @endswitch
                            @endforeach
                            <tr role="row" class="odd">
                                <td class="sorting_1"  colspan="5"><strong style="font-size:16px; text-transform:uppercase;">Expenses</strong></td>
                            </tr>
                            @foreach($charts as $report)
                                @switch($report->account_type)
                                    @case(5)
                                    @if ($report->glcode != 5)
                                        <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                            <td class="text-left">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                            <td class="text-left">{{$report->account_name ?? ''}}</td>
                                            <td class="text-left">{{ $report->getAccountByGlCode($report->parent_account)->account_name ?? null }} ({{ $report->parent_account }})</td>
                                            <td class="text-left">{{$report->type == 1 ? 'General' : 'Detail'}}</td>
                                        </tr>

                            @endif
                            @break
                            @endswitch
                            @endforeach
                        </table>
                        @else
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <a href="{{route('create-major-transaction-accounts')}}" class="btn btn-primary">Create The Default 5 Accounts</a> <br>
                                </div>
                                <div class="col-md-12 d-flex justify-content-center">
                                    <p>
                                        <strong>Note: </strong>
                                        This covers Assets, Liability, Equity, Revenue & Expenses.
                                    </p>
                                </div>

                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>
@endsection
