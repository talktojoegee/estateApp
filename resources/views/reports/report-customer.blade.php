@extends('layouts.master-layout')
@section('current-page')
    Customer Report
@endsection
@section('title')
    Customer Report
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')

    <div class="container-fluid">
        <div class="row">
            @if($search == 0)
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {!! session()->get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @include('reports.partials._customer-search-form')
            @else
                @include('reports.partials._customer-search-form')
                <div class="row" >
                    <div class="col-xl-4 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Organization</p>
                                        <h5 class="text-orange mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($organizationValue,2) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-orange" >
                                            <i class="bx bxs-briefcase"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Head Count<code>({{ number_format($leads->where('customer_type',3)->count()) }})</code></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Partnership</p>
                                        <h5 class="text-secondary mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($partnershipValue,2) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-secondary" >
                                            <i class="bx bx-pencil"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Head Count<code>({{ number_format($leads->where('customer_type',2)->count()) }})</code></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Individuals</p>
                                        <h5 class="text-warning mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($individualValue,2) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-warning" >
                                            <i class="bx bx-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Head Count<code>({{ number_format($leads->where('customer_type',1)->count() )}})</code> </span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">


                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p class="mb-3 ">Showing customer report from <span class="text-info">{{ date('d M, Y', strtotime($from))  }}</span> to <code>{{date('d M, Y', strtotime($to)) }}</code> </p>
                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-15p">Name</th>
                                            <th class="wd-15p">Mobile No.</th>
                                            <th class="wd-15p">No. of Properties</th>
                                            <th class="wd-15p" style="text-align: right;">Valuation({{env('APP_CURRENCY')}})</th>
                                            <th class="wd-15p">Type</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $index = 1; @endphp
                                        @foreach($leads as $lead)
                                            <tr>
                                                <td>{{$index++}}</td>
                                                <td>{{date('M d, Y', strtotime($lead->entry_date))}}</td>
                                                <td>
                                                    @if($lead->customer_type != 3)
                                                        <a href="{{route('lead-profile', $lead->slug)}}">{{$lead->first_name ?? '' }} {{$lead->last_name ?? '' }}</a>

                                                    @else
                                                        <a href="{{route('lead-profile', $lead->slug)}}">{{$lead->company_name ?? '' }}</a>

                                                    @endif

                                                </td>
                                                <td>
                                                    @if($lead->customer_type != 3)
                                                        {{$lead->phone ?? '' }}
                                                    @else
                                                        {{$lead->company_mobile_no ?? '' }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($lead->getNumberOfProperties($lead->id) ?? 0) ?? '' }}</td>
                                                <td style="text-align: right;">{{ number_format($lead->getCustomerValuation($lead->id) ?? 0,2) }}</td>
                                                <td>
                                                    @if($lead->customer_type == 1)
                                                        <span class="badge rounded-pill bg-info"> Individual </span>
                                                    @elseif($lead->customer_type == 2)
                                                        <span class="badge rounded-pill bg-secondary"> Partnership </span>
                                                    @else
                                                        <span class="badge rounded-pill bg-primary"> Organization </span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{route('lead-profile', $lead->slug)}}"> <i class="bx bxs-user"></i> View Profile</a>
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

            @endif

        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
            $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});

        });
    </script>
@endsection
