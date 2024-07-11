@extends('layouts.master-layout')
@section('title')
    Chart of Accounts
@endsection

@section('current-page')
    Chart of Accounts
@endsection
@section('current-page-brief')
    The table below shows you all registered accounts on <strong>{{config('app.name')}}.</strong> You can as well add to this list by clicking <code>add new account</code> button.
@endsection

@section('event-area')
    <div class="btn-group">
        <a class="btn btn-secondary btn-mini" href="{{route('chart-of-accounts')}}"><i class="icofont icofont-tags"></i>Manage Accounts</a>
        <a class="btn btn-primary btn-mini" href="{{route('new-chart-of-account')}}"><i class="icofont icofont-tasks"></i>Add New Account</a>
    </div>
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
@endsection
@section('main-content')

    <div class="row ">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header mb-4">
                    <h5 class="card-header-text text-uppercase">Chart of Accounts</h5></div>
                <div class="card-block accordion-block">
                    <div class="col-xs-12 col-sm-12 mb-4 ">
                        @if(count($charts) > 0)
                            <table id="complex-header" class="table table-striped table-bordered nowrap dataTable" id="chartOfAccountsTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                            <thead>
                            <tr role="row">
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
                                            <td class="text-left">{{$report->parent_account ?? ''}}</td>
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
                                            <td class="text-left">{{$report->parent_account ?? ''}}</td>
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
                                            <td class="text-left">{{$report->parent_account ?? ''}}</td>
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
                                            <td class="text-left">{{$report->parent_account ?? ''}}</td>
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
                                            <td class="text-left">{{$report->parent_account ?? ''}}</td>
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
    <script src="\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="\assets\pages\data-table\extensions\key-table\js\key-table-custom.js"></script>
@endsection
