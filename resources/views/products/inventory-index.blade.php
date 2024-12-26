@extends('layouts.master-layout')
@section('current-page')
 {{$type ?? '-'}}
@endsection

@section('title')
    {{$type ?? '-'}}
@endsection


@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="d-flex justify-content-end">
                    @if($typeIndex == 1)
                    <a href="{{route("new-stock-purchase")}}"  class="btn btn-primary  mb-3">New Purchase <i class="bx bxs-plus-circle"></i> </a>
                    @else
                    <a href="{{route("new-stock-discharge")}}"  class="btn btn-primary  mb-3">New Discharge <i class="bx bxs-plus-circle"></i> </a>
                    @endif
                </div>
            </div>
        </div>

        @if($typeIndex == 1)
        <div class="row" >
            <div class="col-xl-3 col-sm-6" >
                <div class="card" >
                    <div class="card-body" >
                        <div class="row mb-1" >
                            <div class="col" >
                                <p class="mb-1">Total</p>
                                <h5 class="mb-0 number-font text-secondary1">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 1)->sum('total') ?? 0,2)}}</h5>
                            </div>
                            <div class="col-auto mb-0" >
                                <div class="dash-icon text-secondary1" >
                                    <i class="bx bx-list-ul"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall <code>({{number_format($records->where('trans_type', 1)->count() ?? 0)}})</code></span></span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6" >
                <div class="card" >
                    <div class="card-body" >
                        <div class="row mb-1" >
                            <div class="col" >
                                <p class="mb-1">Total</p>
                                <h5 class="text-orange mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 1)->where('status',2)->sum('total') ?? 0,2)}}</h5>
                            </div>
                            <div class="col-auto mb-0" >
                                <div class="dash-icon text-orange" >
                                    <i class="bx bx-x"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Declined<code>({{number_format($records->where('trans_type', 1)->where('status',2)->count() ?? 0)}})</code></span></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6" >
                <div class="card" >
                    <div class="card-body" >
                        <div class="row mb-1" >
                            <div class="col" >
                                <p class="mb-1">Total</p>
                                <h5 class="text-secondary mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 1)->where('status',1)->sum('total') ?? 0,2)}}</h5>
                            </div>
                            <div class="col-auto mb-0" >
                                <div class="dash-icon text-secondary" >
                                    <i class="bx bx-check-double"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Approved<code>({{number_format($records->where('trans_type', 1)->where('status',1)->count() ?? 0)}})</code></span></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6" >
                <div class="card" >
                    <div class="card-body" >
                        <div class="row mb-1" >
                            <div class="col" >
                                <p class="mb-1">Total</p>
                                <h5 class="text-warning mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 1)->where('status',0)->sum('total') ?? 0,2)}}</h5>
                            </div>
                            <div class="col-auto mb-0" >
                                <div class="dash-icon text-warning" >
                                    <i class="bx bx-hourglass"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Pending<code>({{number_format($records->where('trans_type', 1)->where('status',0)->count() ?? 0)}})</code> </span></span>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="row" >
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Total</p>
                                    <h5 class="mb-0 number-font text-secondary1">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 2)->sum('total') ?? 0,2)}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-secondary1" >
                                        <i class="bx bx-list-ul"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall <code>({{number_format($records->where('trans_type', 2)->count() ?? 0)}})</code></span></span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Total</p>
                                    <h5 class="text-orange mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 2)->where('status',2)->sum('total') ?? 0,2)}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-orange" >
                                        <i class="bx bx-x"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Declined<code>({{number_format($records->where('trans_type', 2)->where('status',2)->count() ?? 0)}})</code></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Total</p>
                                    <h5 class="text-secondary mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 2)->where('status',1)->sum('total') ?? 0,2)}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-secondary" >
                                        <i class="bx bx-check-double"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Approved<code>({{number_format($records->where('trans_type', 2)->where('status',1)->count() ?? 0)}})</code></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Total</p>
                                    <h5 class="text-warning mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($records->where('trans_type', 2)->where('status',0)->sum('total') ?? 0,2)}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-warning" >
                                        <i class="bx bx-hourglass"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Pending<code>({{number_format($records->where('trans_type', 2)->where('status',0)->count() ?? 0)}})</code> </span></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">All {{$type ?? '-'}} </h4>
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



                        <div class="row">
                            <div class="col-md-12 col-lx-12">
                                <h5 class="modal-header text-uppercase mb-4 text-info">{{$type ?? ''}}</h5>

                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-15p">{{$typeIndex == 1 ? 'Vendor' : 'Discharged To'}}</th>
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $index = 1; @endphp
                                        @foreach($records as $key => $record)
                                            <tr>
                                                <td>{{ $index++ }}</td>
                                                <td>{{ date('d M, Y', strtotime($record->trans_date)) }}</td>
                                                <td>
                                                    @if($typeIndex == 1)
                                                    {{ $record->getVendor->first_name ?? '' }} {{ $record->getVendor->last_name ?? '' }}
                                                    @else
                                                        {{ $record->getDischargedTo->title ?? '' }} {{ $record->getDischargedTo->first_name ?? '' }} {{ $record->getDischargedTo->last_name ?? '' }} {{ $record->getDischargedTo->other_names ?? '' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($record->status)
                                                        @case(0)
                                                        <span class="text-warning">Pending</span>
                                                        @break
                                                        @case(1)
                                                        <span class="text-secondary">Approved</span>
                                                        @break
                                                        @case(2)
                                                        <span class="text-danger" style="color: #ff0000 !important;">Declined</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <a href="{{ route('view-inventory', $record->slug) }}">View</a>
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
    </div>


@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>

@endsection
