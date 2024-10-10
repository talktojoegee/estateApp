@extends('layouts.master-layout')
@section('title')
    Manage Refund Requests
@endsection
@section('current-page')
    Manage Refund Requests
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')
    Manage Refund Requests
@endsection

@section('main-content')


    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-end ">
            <a href="{{ route('show-new-refund-form') }}" class="btn btn-primary ">Process Refund  <i class="bx bx-loader"></i> </a>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert" role="alert">
                            {!! session()->get('success') !!}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-warning" role="alert">
                            {!! session()->get('error') !!}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <p>List of all refund requests</p>
                            <div class="table-responsive">

                                <table id="datatable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="focus-key_info" style="position: relative;">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc">S/No.</th>
                                        <th class="sorting_asc">Date</th>
                                        <th class="sorting_asc">Receipt No.</th>
                                        <th class="sorting" style="text-align: right;">Amount Paid({{env('APP_CURRENCY')}})</th>
                                        <th class="sorting" style="text-align: right;">Amount Requested({{env('APP_CURRENCY')}})</th>
                                        <th class="sorting" style="text-align: right;">Amount Refunded({{env('APP_CURRENCY')}})</th>
                                        <th class="sorting">Status</th>
                                        <th class="sorting">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($refunds as $key => $refund)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{!is_null($refund->created_at) ? date('d M,Y', strtotime($refund->created_at)) : '-'}}</td>
                                            <td>
                                                {{$refund->getReceipt->receipt_no ?? '' }}
                                            </td>
                                            <td class="text-right" style="text-align: right">{{number_format($refund->amount_paid,2)}}</td>
                                            <td class="text-right" style="text-align: right">{{number_format($refund->actual_amount,2)}}</td>
                                            <td class="text-right" style="text-align: right">{{number_format($refund->amount_refunded,2)}}</td>
                                            <td>
                                                @switch($refund->status)
                                                    @case(0)
                                                    <span class="text-warning">Pending</span>
                                                    @break
                                                    @case(1)
                                                    <span class="text-secondary">Approved</span>
                                                    @break
                                                    @case(2)
                                                    <span class="text-secondary">Declined</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light text-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-target="#viewModal_{{$refund->id}}" data-bs-toggle="modal"> <i class="bx bx-book-open"></i> View</a>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @foreach($refunds as $ref)
        <div class="modal" id="viewModal_{{$ref->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Details</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                            <div class="row mb-3">
                                <div class="form-group mt-3 col-md-12">
                                    <input type="hidden" value="{{$ref->id}}" name="refund">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <tbody>
                                        <tr>
                                            <th>Customer:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->getCustomer->first_name ?? ''}} {{$ref->getReceipt->getCustomer->last_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Property Name:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->getProperty->property_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Property Code:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->getProperty->property_code ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Estate:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->getProperty->getEstate->e_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>House No.:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->getProperty->house_no ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Street:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->getProperty->street ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Receipt No.:</th>
                                            <td style="text-align: left">{{$ref->getReceipt->receipt_no ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date:</th>
                                            <td style="text-align: left">{{ date('d M, Y h:ia', strtotime($ref->date_requested)) ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td style="text-align: left">
                                                @switch($ref->status)
                                                    @case(0)
                                                    <span class="text-warning">Pending</span>
                                                    @break
                                                    @case(1)
                                                    <span class="text-secondary">Approved</span>
                                                    @break
                                                    @case(2)
                                                    <span class="text-secondary">Declined</span>
                                                    @break
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Amount Paid:</th>
                                            <td style="text-align: left">{{env('APP_CURRENCY')}}{{ number_format($refund->amount_paid,2) ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Refund Amount:</th>
                                            <td style="text-align: left">{{env('APP_CURRENCY')}}{{ number_format($refund->amount_refunded,2) ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Requested By:</th>
                                            <td style="text-align: left">{{$ref->getRequestedBy->title ?? ''}} {{$ref->getRequestedBy->first_name ?? ''}} {{$ref->getRequestedBy->last_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Approved/Declined By:</th>
                                            <td style="text-align: left">{{$ref->getActionedBy->title ?? ''}} {{$ref->getActionedBy->first_name ?? ''}} {{$ref->getActionedBy->last_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date Approved/Declined:</th>
                                            <td style="text-align: left">{{ !is_null($ref->date_actioned) ? date('d M, Y', strtotime($ref->date_actioned)) : '-' }} </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                @if($ref->status == 0)
                                    <div class="btn-group">
                                        <a href="{{route('action-refund',['type'=>'approve','id'=>$ref->id])}}" class="btn btn-success  waves-effect waves-light">Approve <i class="bx bx-check-double"></i> </a>
                                        <a href="{{route('action-refund',['type'=>'decline','id'=>$ref->id])}}" class="btn btn-danger  waves-effect waves-light">Decline <i class="bx bx-x-circle"></i> </a>
                                    </div>
                                @endif
                            </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>



@endsection
