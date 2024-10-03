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
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>

                            {!! session()->get('success') !!}

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
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
                    <h5 class="modal-header text-info">Payroll Month/Year</h5>
                    <div class="pt-4">
                        <p><strong class="text-danger">Note: </strong> Current Payroll Month/Year: @if(!empty($record))<code>{{ DateTime::createFromFormat('!m', $record->payroll_month)->format('F') }}, {{ $record->payroll_year }}</code> @else  <small style="color: #ff0000;">Set current payroll month & year</small> @endif</p>
                        <form action="{{ route('payroll-month-year') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col-md-8">
                                        <label for="">Payroll Month & Year <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <input type="month" name="monthYear" class="form-control ">
                                        @error('monthYear')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <button class="btn btn-primary btn-lg btn-block mt-3" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra-scripts')

@endsection
