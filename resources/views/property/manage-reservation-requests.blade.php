@extends('layouts.master-layout')
@section('title')
    Manage Reservation Requests
@endsection
@section('current-page')
    Manage Reservation Requests
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')
    Manage Reservation Requests
@endsection

@section('main-content')


    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-end ">
            <a href="{{ route('property-reservation') }}" class="btn btn-primary ">New Request  <i class="bx bx-plus"></i> </a>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="modal-header text-uppercase">Manage Reservation Requests</div>
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

                            <p>List of all reservation requests</p>
                            <div class="table-responsive">

                                <table id="datatable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="focus-key_info" style="position: relative;">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc">S/No.</th>
                                        <th class="sorting_asc">Date</th>
                                        <th class="sorting_asc">Customer</th>
                                        <th class="sorting_asc">Property</th>
                                        <th class="sorting">Status</th>
                                        <th class="sorting">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($requests as $key => $request)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{date('d M, Y', strtotime($request->created_at))}}</td>
                                            <td><a target="_blank" href="{{route('lead-profile', $request->getCustomer->slug)}}">{{ $request->getCustomer->first_name ?? '' }} {{ $request->getCustomer->last_name ?? '' }}</a></td>
                                            <td>
                                                <a target="_blank" href="{{route('show-property-details',  $request->getProperty->slug)}}">{{ $request->getProperty->property_name ?? '' }} ({{ $request->getProperty->property_code ?? '' }} )</a>
                                            </td>
                                            <td>
                                                @switch($request->status)
                                                    @case(0)
                                                    <span class="text-warning">Pending</span>
                                                    @break
                                                    @case(1)
                                                    <span class="text-success">Approved</span>
                                                    @break
                                                    @case(2)
                                                    <span class="text-danger" style="color: #ff0000 !important;">Declined</span>
                                                    @break
                                                    @case(3)
                                                    <span class="text-info">Archive</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#viewRequest_{{$request->id}}" class="btn btn-sm btn-primary">View</a>
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

    @foreach($requests as $ref)
        <div class="modal" id="viewRequest_{{$ref->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
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
                                        <td style="text-align: left">{{$ref->getCustomer->first_name ?? ''}} {{$ref->getCustomer->last_name ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Property Name:</th>
                                        <td style="text-align: left">{{$ref->getProperty->property_name ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Property Code:</th>
                                        <td style="text-align: left">{{$ref->getProperty->property_code ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Estate:</th>
                                        <td style="text-align: left">{{$ref->getProperty->getEstate->e_name ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>House No.:</th>
                                        <td style="text-align: left">{{$ref->getProperty->house_no ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Street:</th>
                                        <td style="text-align: left">{{$ref->getProperty->street ?? ''}}</td>
                                    </tr>
                                    @if(!is_null($ref->receipt_id))
                                    <tr>
                                        <th>Receipt No.:</th>
                                        <td style="text-align: left">{{$ref->getReceipt->receipt_no ?? ''}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Date:</th>
                                        <td style="text-align: left">{{ date('d M, Y h:ia', strtotime($ref->created_at)) ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Note:</th>
                                        <td style="text-align: left">{{ $ref->note ?? '' }}</td>
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
                                                @case(3)
                                                <span class="text-info">Archived</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reserved By:</th>
                                        <td style="text-align: left">{{$ref->getReservedBy->title ?? ''}} {{$ref->getReservedBy->first_name ?? ''}} {{$ref->getReservedBy->last_name ?? ''}}</td>
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
                                    <a href="{{route('action-reservation',['type'=>'approve','id'=>$ref->id])}}" class="btn btn-success  waves-effect waves-light">Approve <i class="bx bx-check-double"></i> </a>
                                    <a href="{{route('action-reservation',['type'=>'decline','id'=>$ref->id])}}" class="btn btn-danger  waves-effect waves-light">Decline <i class="bx bx-x-circle"></i> </a>
                                </div>
                            @endif
                            @if($ref->status == 1)
                                <div class="btn-group">
                                    <a href="{{route('action-reservation',['type'=>'undo','id'=>$ref->id])}}" class="btn btn-danger  waves-effect waves-light">Archive Request <i class="bx bx-x-circle"></i> </a>
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
