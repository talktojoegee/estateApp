@extends('layouts.master-layout')
@section('current-page')
    Chart of Accounts
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .header {
            position: sticky;
            top:0;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @if(session()->has('success'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('success') !!}

                    <button type="button"  class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-outline me-2"></i>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body" style="padding: 2px;">
            <div class="row">

                <div class="col-md-12 col-sm-12 mt-4">
                    <div class="d-flex justify-content-between modal-header">
                        <div class="h6 text-left pl-5 text-uppercase text-primary">Chart of Accounts</div>
                        <a href="{{route('accounting.add-new-account')}}" class="btn btn-primary mr-3" > <i class="bx bx-plus-circle"></i> Add New Account</a>
                    </div>

                    <div class="container pb-5">
                        <div class="table-responsive mt-3">
                            <table id="complex-header" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                                    <thead style="position: sticky;top: 0">
                                    <tr role="row">
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="width: 50px;">S/No.</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="width: 50px;">ACCOUNT CODE</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="width: 150px;">ACCOUNT NAME</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >PARENT</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >TYPE</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Bank</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $a = 1;
                                    @endphp
                                    <tr role="row" class="odd">
                                        <td class="sorting_1" colspan="6"><strong style="font-size:16px; text-transform:uppercase;">Assets</strong></td>
                                    </tr>
                                    @foreach($accounts as $report)
                                        @switch($report->account_type)
                                            @case(1)
                                            @if ($report->glcode != 1)
                                                <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                                    <td class="text-left">{{$a++}}</td>
                                                    <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                                    <td class="text-left">{{$report->account_name ?? ''}}</td>
                                                    <td class="text-left">{{$report->getParentAccountByGlcode($report->parent_account)->account_name ?? null }} <small>({{$report->parent_account}})</small></td>
                                                    <td class="text-left">{{$report->type == 0 ? 'General' : 'Detail'}}</td>
                                                    <td>{!! $report->bank == 1 ? 'Yes' : 'No' !!}</td>
                                                </tr>
                                            @endif
                                            @break
                                        @endswitch
                                    @endforeach

                                    <tr role="row" class="odd">
                                        <td class="sorting_1"  colspan="6">
                                            <strong style="font-size:16px; text-transform:uppercase;">Liability</strong>
                                        </td>
                                    </tr>
                                    @foreach($accounts as $report)
                                        @switch($report->account_type)
                                            @case(2)
                                            @if ($report->glcode != 2)
                                                <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                                    <td class="text-left">{{$a++}}</td>
                                                    <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                                    <td class="text-left">{{$report->account_name ?? ''}}</td>
                                                    <td class="text-left">{{$report->getParentAccountByGlcode($report->parent_account)->account_name ?? null }} <small>({{$report->parent_account}})</small></td>
                                                    <td class="text-left">{{$report->type == 0 ? 'General' : 'Detail'}}</td>
                                                    <td>{{$report->bank == 1 ? 'Yes' : 'No'}}</td>
                                                </tr>

                                            @endif
                                            @break
                                        @endswitch
                                    @endforeach
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"  colspan="6"><strong style="font-size:16px; text-transform:uppercase;">Equity</strong></td>
                                    </tr>
                                    @foreach($accounts as $report)
                                        @switch($report->account_type)
                                            @case(3)
                                            @if ($report->glcode != 3)
                                                <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                                    <td class="text-left">{{$a++}}</td>
                                                    <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                                    <td class="text-left">{{$report->account_name ?? ''}}</td>
                                                    <td class="text-left">{{$report->getParentAccountByGlcode($report->parent_account)->account_name ?? null }} <small>({{$report->parent_account}})</small></td>
                                                    <td class="text-left">{{$report->type == 0 ? 'General' : 'Detail'}}</td>
                                                    <td>{{$report->bank == 1 ? 'Yes' : 'No'}}</td>
                                                </tr>

                                            @endif
                                            @break
                                        @endswitch
                                    @endforeach
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"  colspan="6"><strong style="font-size:16px; text-transform:uppercase;">Revenue</strong></td>
                                    </tr>
                                    @foreach($accounts as $report)
                                        @switch($report->account_type)
                                            @case(4)
                                            @if ($report->glcode != 4)
                                                <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                                    <td class="text-left">{{$a++}}</td>
                                                    <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                                    <td class="text-left">{{$report->account_name ?? ''}}</td>
                                                    <td class="text-left">{{$report->getParentAccountByGlcode($report->parent_account)->account_name ?? null }} <small>({{$report->parent_account}})</small></td>
                                                    <td class="text-left">{{$report->type == 0 ? 'General' : 'Detail'}}</td>
                                                    <td>{{$report->bank == 1 ? 'Yes' : 'No'}}</td>
                                                </tr>

                                            @endif
                                            @break
                                        @endswitch
                                    @endforeach
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"  colspan="6"><strong style="font-size:16px; text-transform:uppercase;">Expenses</strong></td>
                                    </tr>
                                    @foreach($accounts as $report)
                                        @switch($report->account_type)
                                        @case(5)
                                        @if ($report->glcode != 5)
                                        <tr role="row" class="odd {{ $report->type == 0 ? 'bg-secondary text-white' : '' }}">
                                            <td class="text-left">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{$report->glcode ?? ''}}</td>
                                            <td class="text-left">{{$report->account_name ?? ''}}</td>
                                            <td class="text-left">{{$report->getParentAccountByGlcode($report->parent_account)->account_name ?? null }} <small>({{$report->parent_account}})</small></td>
                                            <td class="text-left">{{$report->type == 0 ? 'General' : 'Detail'}}</td>
                                            <td>{{$report->bank == 1 ? 'Yes' : 'No'}}</td>
                                        </tr>
                                    @endif
                                    @break
                                    @endswitch
                                    @endforeach
                                </table>
                        </div>
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
