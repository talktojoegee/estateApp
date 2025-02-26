@extends('layouts.master-layout')
@section('title')
    Approve Payroll Routine
@endsection
@section('current-page')
    Approve Payroll Routine
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
    @include('payroll.partial._salary-menu')
    @if(count($records) > 0)
    <div class="row">
        <div class="col-md-12 mb-2 d-flex justify-content-start">
            <div class="btn-group">
                <a href="{{ route('action-routine', ['action'=>'approve', 'batch_code'=>$records->first()->batch_code]) }}" class="btn btn-primary btn-sm"> <i class="bx bx-check-double"></i> Approve Routine</a>
                <a href="{{ route('action-routine', ['action'=>'undo', 'batch_code'=>$records->first()->batch_code]) }}" class="btn btn-danger btn-sm"> <i class="bx bx-undo"></i> Undo Routine</a>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="modal-header text-uppercase">Approve Payroll Routine</div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Employee Name</th>
                                <th class="wd-15p" style="text-align: right;">Gross Pay({{env('APP_CURRENCY')}})</th>
                                <th class="wd-15p" style="text-align: right;">Deduction({{env('APP_CURRENCY')}})</th>
                                <th class="wd-15p" style="text-align: right;">Net Pay({{env('APP_CURRENCY')}})</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($records as $key => $record)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{$record->getEmployee->title ?? ''}} {{$record->getEmployee->first_name ?? ''}} {{$record->getEmployee->last_name ?? ''}} {{$record->getEmployee->other_names ?? ''}}</td>
                                    <td style="text-align: right;">{{ number_format($record->getGrossPay($record->employee_id),2) }}</td>
                                    <td style="text-align: right;">{{ number_format($record->getDeduction($record->employee_id),2) }}</td>
                                    <td style="text-align: right;">{{ number_format( ($record->getGrossPay($record->employee_id) - $record->getDeduction($record->employee_id) ),2) }}</td>

                                </tr>
                            @endforeach



                            </tbody>
                        </table>
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
