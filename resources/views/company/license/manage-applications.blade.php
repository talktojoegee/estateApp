@extends('layouts.master-layout')
@section('title')
     Applications
@endsection
@section('current-page')
     Applications
@endsection
@section('extra-styles')

    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            @if(\Illuminate\Support\Facades\Auth::user()->type == 1)
            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col">
                                    <p class="mb-1">Total</p>

                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($workflows->where('p_type',6)->sum('p_amount')) }}</h5>
                                </div>
                                <div class="col-auto mb-0">
                                    <div class="dash-icon text-secondary1">
                                        <i class="bx bxs-briefcase-alt-2"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall Requests<code>({{ number_format($workflows->where('p_type',6)->count()) }})</code></span></span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Verified</p>
                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format( $workflows->where('p_status', '>=',5)->where('p_type',6)->sum('p_amount') )}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-orange" >
                                        <i class="bx bxs-book-open"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Total Verified<code>({{number_format($workflows->where('p_status','>=',5)->where('p_type',6)->count())}})</code></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Approved</p>
                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format( $workflows->where('p_status',2)->where('p_type',6)->sum('p_amount') )}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-secondary" >
                                        <i class="bx bx-check-double"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Total Approved<code>({{number_format( $workflows->where('p_status',2)->where('p_type',6)->count() )}})</code></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Pending</p>
                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format( $workflows->where('p_status',0)->where('p_type',6)->sum('p_amount') )}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-warning" >
                                        <i class="bx bx-hourglass"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Total Pending<code>({{number_format( $workflows->where('p_status',0)->where('p_type',6)->count() )}})</code> </span></span>
                        </div>
                    </div>
                </div>
            </div>

            @else

                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-1">
                                    <div class="col">
                                        <p class="mb-1">Total</p>
                                        <h5 class="mb-0 number-font">{{ number_format($workflows->where('p_type',6)->count()) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0">
                                        <div class="dash-icon text-secondary1">
                                            <i class="bx bxs-briefcase-alt-2"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall Requests</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Declined</p>
                                        <h5 class="mb-0 number-font">{{number_format($workflows->where('p_status',3)->where('p_type',6)->count())}}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-orange" >
                                            <i class="bx bxs-book-open"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Total Declined</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Approved</p>
                                        <h5 class="mb-0 number-font">{{number_format( $workflows->where('p_status',2)->where('p_type',6)->count() )}}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-secondary" >
                                            <i class="bx bx-check-double"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Total Approved</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Pending</p>
                                        <h3 class="mb-0 number-font">{{number_format( $workflows->where('p_status',0)->where('p_type',6)->count() )}}</h3>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-warning" >
                                            <i class="bx bx-hourglass"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Total Pending </span></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    @if(\Illuminate\Support\Facades\Auth::user()->type != 1)
                    <div class="card-header">
                        <a href="{{route('new-radio-application')}}"  class="btn btn-primary  mb-3">New Request <i class="bx bx bx-highlight"></i> </a>
                    </div>
                    @endif
                    <div class="card-body">
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif



                        <div class="table-responsive mt-3">
                            <table id="datatable1" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                                <thead style="position: sticky;top: 0">
                                <tr role="row">
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >S/No.</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Date</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Applied By</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Ref. Code</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="text-align: right !important;" >Amount({{env('APP_CURRENCY')}})</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Status</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $a = 1;
                                @endphp
                                @foreach($workflows as $key => $flow)
                                    <tr role="row" class="odd">
                                        <td class="">{{$a++}}</td>
                                        <td class="sorting_1 text-left">{{ date('d M, Y', strtotime($flow->created_at)) }}</td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->type != 1)
                                        <td class="">
                                            {{$flow->getAuthor->title ?? '' }} {{$flow->getAuthor->first_name ?? '' }} {{$flow->getAuthor->last_name ?? '' }} {{$flow->getAuthor->other_names ?? '' }}
                                        </td>
                                        @else
                                            <td class="">
                                                {{$flow->getCompany->organization_name ?? '' }}
                                            </td>
                                        @endif
                                        <td class="">{{$flow->p_title ?? ''}}</td>
                                        <td class="" style="text-align: right">
                                            @if(($flow->p_status == 0) || ($flow->p_status == 1) || ($flow->p_status == 2))
                                                @if(!empty($flow->p_invoice_id))
                                                    <span class="text-info">Invoice Issued</span>
                                                @else
                                                    <span class="text-warning">Awaiting Charge</span>
                                                @endif
                                            @elseif(($flow->p_status == 1) && (!empty($flow->p_amount)))
                                                {{$flow->getCurrency->symbol ?? '' }}{{ number_format($flow->p_amount ?? 0, 2) }}

                                            @elseif($flow->p_status == 3)
                                                <span class="text-danger">Not charged</span>
                                            @elseif($flow->p_status >= 5)
                                                {{number_format($flow->p_amount,2)}}
                                            @endif

                                        </td>
                                        <td class="">
                                            @switch($flow->p_status)
                                                @case(0)
                                                <span class="text-info">Pending</span>
                                                @break
                                                @case(1)
                                                <span class="text-info">Processing</span>
                                                @break
                                                @case(2)
                                                <span class="text-success">Approved</span>
                                                @break
                                                @case(3)
                                                <span class="text-danger" style="color: #ff0000 !important;">Declined</span>
                                                @break
                                                @case(4)
                                                <span class="text-secondary">Paid</span>
                                                @break
                                                @case(5)
                                                <span class="text-info" >Verified</span>
                                                @break
                                                @case(6)
                                                <span class="text-warning">Licensed</span>
                                                @break
                                                @case(7)
                                                <span class="text-warning">Assigned</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{route('show-application-details', $flow->p_slug)}}" > <i class="bx bxs-book-open"></i> View</a>
                                                    @if((\Illuminate\Support\Facades\Auth::user()->type == 1)  && ($flow->p_status == 2) && (is_null($flow->p_invoice_id)) )
                                                        <a class="dropdown-item" href="{{route('generate-invoice', $flow->p_slug)}}" > <i class="bx bx-purchase-tag"></i> Generate Invoice</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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
