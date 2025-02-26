@extends('layouts.master-layout')
@section('title')
    {{$record->e_name ?? '' }}
@endsection

@section('current-page')
    {{$record->e_name ?? '' }}
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
            <div class="col-xl-12 col-md-12">
                @if(session()->has('success'))
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-xl-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-1">
                                    <div class="col">
                                        <p class="mb-1">Estimated Value</p>
                                        <h5 class="mb-0 text-info number-font">{{env('APP_CURRENCY')}}{{ number_format( $record->getProperties->sum('price'),2 ) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0">
                                        <div class="dash-icon text-secondary1">
                                            <i class="bx bx-analyse"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Valuation</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Total Inflow</p>
                                        <h5 class="mb-0 text-info number-font">{{env('APP_CURRENCY')}} {{number_format($receipts->sum('total'),2)}}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-orange" >
                                            <i class="bx bxs-book-open"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall Inflow</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        @can('can-edit-estate-info') <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addNew"  class="btn btn-warning"> Edit Details <i class="bx bxs-pencil"></i> </a> @endcan
                        <a href="{{route('estates')}}"   class=" ml-2 btn btn-secondary"> Go Back <i class="bx bx-arrow-back"></i> </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-xl-12 col-md-12" >
                        <div class="card" >
                            <div class="card-body" >
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab" aria-selected="true">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">Estate</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">Properties</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#messages1" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                            <span class="d-none d-sm-block">Customers</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Sales</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted" >
                                    <div class="tab-pane active" id="home1" role="tabpanel" >
                                        <div class="row">
                                            <div class="col-md-8 col-sm-8" >
                                                <div class="car p-3" >
                                                    <div class="card-body" >
                                                        <h6 class="mt-4 text-uppercase text-white modal-header p-4">Detail</h6>
                                                        <div class="table-responsive" >
                                                            <table class="table mb-0 table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">Estate Name:<span class="text-info"> {{$record->e_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Country:<span class="text-info"> {{$record->getCountry->name ?? ''}}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">State:<span class="text-info"> {{$record->getState->name ?? ''}}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Town:<span class="text-info"> {{$record->e_city ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Estate Code:<span class="text-info"> {{$record->e_ref_code ?? '' }}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Address:<span class="text-info"> {{$record->e_address ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">About:<br> <span class="text-muted">
                                                                            {{$record->e_info ?? '' }}</span>
                                                                    </th>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="car p-3" >
                                                    <div class="card-body" >
                                                        <h6 class="mt-4 text-uppercase text-white modal-header p-4">Account Setup Details</h6>
                                                        <div class="table-responsive" >
                                                            <table class="table mb-0 table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">Property Account:<span class="text-info">{{$record->getChartOfAccountById($record->property_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->property_account)->account_name ?? '' }}  </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Customer Account:<span class="text-info"> {{$record->getChartOfAccountById($record->customer_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->customer_account)->account_name ?? '' }}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Vendor Account:<span class="text-info"> {{$record->getChartOfAccountById($record->vendor_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->vendor_account)->account_name ?? '' }}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">TAX Account:<span class="text-info"> {{$record->getChartOfAccountById($record->tax_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->tax_account)->account_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">TAX Rate:<span class="text-info"> {{$record->tax_rate }}% </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Refund Account:<span class="text-info"> {{$record->getChartOfAccountById($record->refund_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->refund_account)->account_name ?? '' }}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Refund Rate:<span class="text-info"> {{$record->refund_rate }}% </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Charges Account:<span class="text-info"> {{$record->getChartOfAccountById($record->charges_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->charges_account)->account_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Salary Account:<span class="text-info"> {{$record->getChartOfAccountById($record->salary_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->salary_account)->account_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Employee Account:<span class="text-info"> {{$record->getChartOfAccountById($record->employee_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->employee_account)->account_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Workflow Account:<span class="text-info"> {{$record->getChartOfAccountById($record->workflow_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->workflow_account)->account_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">General Account:<span class="text-info"> {{$record->getChartOfAccountById($record->general_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->general_account)->account_name ?? '' }} </span></th>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4" >
                                                <div class="car p-3" >
                                                    <div class="card-body" >
                                                        <h6 class="mt-4 text-white text-uppercase modal-header p-4">Other Info.</h6>
                                                        <div class="table-responsive" >
                                                            <table class="table mb-0 table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">No. of Properties:<span class="text-info">{{number_format($record->getProperties->count())}} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">No. of Customers:<span class="text-info">{{number_format(count($customers))}}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Est. Value:<span class="text-info"> {{env('APP_CURRENCY')}}{{ number_format( $record->getProperties->sum('price'),2 ) }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Total Inflow:<span class="text-info"> {{env('APP_CURRENCY')}} {{number_format($receipts->sum('total'),2)}}</span></th>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile1" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xl-12">
                                                <div class="modal-header text-uppercase text-white">Properties</div>
                                                <p class="mt-3">There are <code>{{number_format($record->getProperties->count())}}</code> properties in this <span class="text-info">{{$record->e_name ?? ''}}</span></p>
                                                <div class="table-responsive">
                                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th class="">#</th>
                                                            <th class="wd-15p">Date</th>
                                                            <th class="wd-15p">House No.</th>
                                                            <th class="wd-15p">Code</th>
                                                            <th class="wd-15p">Property Name</th>
                                                            <th class="wd-15p" style="text-align: right;">Price(₦)</th>
                                                            <th class="wd-15p">Status</th>
                                                            <th class="wd-15p">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($record->getProperties as $key => $property)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{date('d M, Y', strtotime($property->created_at))}}</td>
                                                                <td>{{ $property->house_no ?? '' }}</td>
                                                                <td>{{$property->property_code ?? '' }}</td>
                                                                <td>
                                                                    <a href="{{route('show-property-details', ['slug'=>$property->slug])}}">
                                                                        <div class="d-flex">
                                                                            <div class="flex-shrink-0 align-self-center me-3">
                                                                                <img src="/assets/drive/property/{{$property->getGalleryFeaturedImageByPropertyId($property->id)->attachment ?? 'placeholder.png' }}" alt="{{$property->property_name ?? '' }}" class="rounded-circle avatar-xs">
                                                                            </div>
                                                                            <div class="flex-grow-1 overflow-hidden">
                                                                                <h6 class="text-truncate text-info font-size-14 mb-1">{{ substr($property->property_name,0,35).'...' ?? ''  }}</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </td>
                                                                <td class="text-right" style="text-align: right;">{{ number_format($property->price,2)  }}</td>
                                                                <td>
                                                                    @switch($property->status)
                                                                        @case(0)
                                                                        <label class='text-primary'>Available</label>
                                                                        @break
                                                                        @case(1)
                                                                        <label class='text-info'>Rented</label>
                                                                        @break
                                                                        @case(2)
                                                                        <label class='text-warning'>Sold</label>
                                                                @break
                                                                @endswitch
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="{{route('show-property-details', [ 'slug'=>$property->slug])}}" > <i class="bx bxs-book-open"></i> View</a>
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
                                    <div class="tab-pane" id="messages1" role="tabpanel" >
                                        <div class="modal-header text-uppercase text-white">Customers</div>
                                        <p class="mt-3">{{$record->e_name ?? '' }} has a total of <code>{{number_format(count($customers))}}</code> customers.</p>
                                        <div class="table-responsive mt-3">
                                            <table id="datatable2" class="table table-bordered dt-responsive  nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th class="">#</th>
                                                    <th class="wd-15p">Date</th>
                                                    <th class="wd-15p">Name</th>
                                                    <th class="wd-15p">Phone No.</th>
                                                    <th class="wd-15p"># of Properties</th>
                                                    <th class="wd-15p" style="text-align: right;">Valuation({{env('APP_CURRENCY')}})</th>
                                                    <th class="wd-15p">Source</th>
                                                    <th class="wd-15p">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $index = 1; @endphp
                                                @foreach($customers as $lead)
                                                    <tr>
                                                        <td>{{$index++}}</td>
                                                        <td>{{date('M d, Y', strtotime($lead->entry_date))}}</td>
                                                        <td><a href="{{route('lead-profile', $lead->slug)}}">{{$lead->first_name ?? '' }} {{$lead->last_name ?? '' }}</a>
                                                            <sup class="badge rounded-pill bg-success">{{$lead->getStatus->status ?? '' }}</sup>
                                                        </td>
                                                        <td>{{$lead->phone ?? '' }}</td>
                                                        <td>{{ number_format($lead->getNumberOfProperties($lead->id) ?? 0) ?? '' }}</td>
                                                        <td style="text-align: right;">{{ number_format($lead->getCustomerValuation($lead->id) ?? 0,2) }}</td>
                                                        <td> <span class="badge rounded-pill bg-info"> {{$lead->getSource->source ?? '' }} </span> </td>
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
                                    <div class="tab-pane" id="settings1" role="tabpanel" >
                                        <div class="modal-header text-uppercase text-white">Sales</div>
                                        <p class="mt-3">A total of <code>{{number_format(count($receipts))}}</code> receipts issued. Turning into <span class="text-info">{{env('APP_CURRENCY')}}{{number_format($receipts->sum('total'),2)}}</span> in revenue.</p>
                                        <div class="table-responsive">

                                            <table id="datatable1" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="focus-key_info" style="position: relative;">
                                                <thead>
                                                <tr role="row">
                                                    <th class="sorting_asc">S/No.qw</th>
                                                    <th class="sorting_asc">Date</th>
                                                    <th class="sorting_asc">Name</th>
                                                    <th class="sorting">Invoice No.</th>
                                                    <th class="sorting">Receipt No.</th>
                                                    <th class="sorting" style="text-align: right;">Amount({{env('APP_CURRENCY')}})</th>
                                                    <th class="sorting">Trans. Ref</th>
                                                    <th class="sorting">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $serial = 1;
                                                @endphp
                                                @foreach($receipts as $receipt)
                                                    <tr>
                                                        <td>{{$serial++}}</td>
                                                        <td>{{!is_null($receipt->created_at) ? date('d M,Y', strtotime($receipt->created_at)) : '-'}}</td>
                                                        <td>
                                                            {{$receipt->getCustomer->first_name ?? '' }} {{$receipt->getCustomer->last_name ?? '' }}
                                                        </td>
                                                        <td>{{$receipt->getInvoice->invoice_no ?? ''}}</td>
                                                        <td>{{$receipt->receipt_no ?? ''}}</td>
                                                        <td class="text-right" style="text-align: right">{{number_format($receipt->total,2)}}</td>
                                                        <td>{{$receipt->trans_ref ?? ''}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-light text-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical"></i></button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" target="_blank" href="{{ route('view-receipt', $receipt->trans_ref) }}"> <i class="bx bx-book-open"></i> View Receipt</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr role="row">
                                                    <th>S/No.</th>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Invoice No.</th>
                                                    <th>Receipt No.</th>
                                                    <th>Amount</th>
                                                    <th>Trans. Ref</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
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
    </div>

    <div class="modal right fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="width: 900px;">
        <div class="modal-dialog modal-lg w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase text-white" style="text-align: center;" id="myModalLabel2">Edit Estate Details</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('estates')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Name <sup style="color: #ff0000 !important;">*</sup></label>
                                <input type="text" value="{{ old('name', $record->e_name) }}" name="name" placeholder="Estate Name" class="form-control">
                                @error('name') <i class="text-danger">{{$message}}</i>@enderror
                                <input type="hidden" value="{{$record->e_id}}" name="estate">
                            </div>
                            <div class="form-group mt-3 col-md-6 ">
                                <label for="">Country <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <select name="country" id="country"  class="form-control select2">
                                    <option value="161" {{$record->e_country_id == 161 ? 'selected' : null }}>Nigeria</option>
                                    @foreach($countries as $country)
                                        @if($country->id != 161)
                                            <option value="{{$country->id}}" {{$record->e_country_id == $country->id ? 'selected' : null }}>{{$country->name ?? '' }}</option>
                                        @endif
                                    @endforeach

                                </select>
                                @error('state') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6 " id="state">
                                <label for="">State <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <select name="state"  class="form-control select2 ">
                                    <option selected disabled>--Select state --</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->id}}" {{$record->e_state_id == $state->id ? 'selected' : null }}>{{$state->name ?? '' }}</option>
                                    @endforeach

                                </select>
                                @error('state') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">City <span class="text-danger" style="color: #ff0000 !important;">*</span></label> <br>
                                <input type="text" name="city" value="{{ old('city', $record->e_city) }}" placeholder="City" class="form-control">
                                @error('city') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Estate Code <span class="text-danger" style="color: #ff0000 !important;">*</span></label> <br>
                                <input type="text" value="{{ old('referenceCode', $record->e_ref_code) }}" name="referenceCode" placeholder="Enter a unique Reference Code. Example RAY for Raylight" class="form-control">
                                @error('referenceCode') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for="">Address <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <textarea name="address" id="address" placeholder="Type address here..."  style="resize: none;" class="form-control">{{ old('address', $record->e_address) }}</textarea>
                                @error('address') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for="">Brief Info <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <textarea name="info" id="info" placeholder="Enter a brief info about this estate"  style="resize: none;" class="form-control">{{ old('info', $record->e_info) }}</textarea>
                                @error('info') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12 col-lg-12">
                                <div class="modal-header">Estate Amenities  </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mt-2">
                                            @foreach($amenities as $amenity)
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" value="{{$amenity->ea_id}}"    name="amenities[]"  type="checkbox" checked>
                                                        <label class="form-check-label" for="borehole">{{$amenity->ea_name ?? '' }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Save changes <i class="bx bx-check-double"></i> </button>
                            </div>
                        </div>
                    </form>

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
