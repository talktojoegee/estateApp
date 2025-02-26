
@extends('layouts.master-layout')
@section('current-page')
    Manage Schedule
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
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
                        <div class="row">

                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <p class="mb-1">Total</p>
                                                <h3 class="mb-0 number-font">{{number_format($records->count())}}</h3>
                                            </div>
                                            <div class="col-auto mb-0">
                                                <div class="dash-icon text-secondary1">
                                                    <i class="bx bxs-user-badge"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">This Year<code>({{ date('Y') }})</code></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <p class="mb-1">New</p>
                                                <h3 class="mb-0 number-font">{{ number_format($records->where('status',0)->count()) }}</h3>
                                            </div>
                                            <div class="col-auto mb-0">
                                                <div class="dash-icon text-orange">
                                                    <i class="bx bxs-book-open"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">This Year<code>({{ date('Y') }})</code></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <p class="mb-1">Open</p>
                                                <h3 class="mb-0 number-font">{{ number_format($records->where('status',1)->count()) }}</h3>
                                            </div>
                                            <div class="col-auto mb-0">
                                                <div class="dash-icon text-secondary">
                                                    <i class="bx bx-notepad"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">This Year<code>({{ date('Y') }})</code></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <p class="mb-1">Closed</p>
                                                <h3 class="mb-0 number-font">{{ number_format($records->where('status',2)->count()) }}</h3>
                                            </div>
                                            <div class="col-auto mb-0">
                                                <div class="dash-icon text-warning">
                                                    <i class="bx bx-hide"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">This Year<code>({{ date('Y') }})</code> </span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="row mt-3">
                    <div class="modal-header text-uppercase mb-4">
                        Follow-up Schedule
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="table-responsive mt-3">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th class="">#</th>
                                                <th class="wd-15p">Date</th>
                                                <th class="wd-15p">Title</th>
                                                <th class="wd-15p">Month</th>
                                                <th class="wd-15p">Year</th>
                                                <th class="wd-15p">Status</th>
                                                <th class="wd-15p">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($records as $key => $record)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{date('d M, Y h:ia', strtotime($record->entry_date))}}</td>
                                                    <td>{{$record->title ?? '' }}</td>
                                                    <td>{{ date('F', mktime(0, 0, 0, $record->period_month, 10)) }}</td>
                                                    <td>{{$record->period_year ?? '' }}</td>
                                                    <td>
                                                        @switch($record->status)
                                                            @case(0)
                                                            <span class="text-warning">New</span>
                                                            @break
                                                            @case(1)
                                                            <span class="text-success">Open</span>
                                                            @break
                                                            @case(2)
                                                            <span class="text-danger">Closed</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('view-followup-details', $record->ref_code) }}"> <i class="bx bxs-chart"></i> View</a>
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
