@extends('layouts.master-layout')
@section('title')
    Payroll Month/Year
@endsection
@section('current-page')
    Payroll Month/Year
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('error') !!}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-warning">
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </div>
    @endif
    @include('payroll.partial._menu')
    <div class="row">
        <div class="col-md-6  col-xl-6  col-sm-6 ">
            <div class="card">
                <div class="card-body">
                    <h6 class="modal-header text-white text-uppercase">Payroll Month/Year</h6>
                    <div class="pt-4">
                        <h5 class="text-info text-center">Current Payroll Month/Year is <span style="color: #ff0000;">@if(!empty($record) ){{  DateTime::createFromFormat('!m', $record->payroll_month)->format('F') }}, {{ $record->payroll_year }} @else '-' @endif </span></h5>
                        <div class="text-center mt-3">
                            <i class="fas fa-coins fs-20" style="font-size: 50px; color: #00214D;"></i>
                            <p class="mt-3">Run the Payroll Routine below or set the current payroll month &amp; year.</p>
                            <p><a href="{{route('run-payroll-routine')}}" class="btn btn-primary mt-4">Run Payroll Routine</a></p>
                            <p><a href="{{route('payroll-month-year')}}" class="mt-4 text-info">Set Payroll Month/Year</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra-scripts')

@endsection
