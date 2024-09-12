
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
                                            <p class="mb-1">Estimated Valuation</p>
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
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addNew"  class="btn btn-primary"> Add New <i class="bx bxs-add-to-queue"></i> </a>
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
                                                        <h6 class="mt-4 text-uppercase text-info modal-header p-4">Detail</h6>
                                                        <div class="table-responsive" >
                                                            <table class="table mb-0 table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">Estate Name: &nbsp; &nbsp; <span class="text-info">{{$record->e_name ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">State: &nbsp; &nbsp; <span class="text-info">{{$record->getState->name ?? ''}}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">City: &nbsp; &nbsp; <span class="text-info">{{$record->e_city ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Mobile No.: &nbsp; &nbsp; <span class="text-info">{{$record->e_mobile_no ?? '' }}</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Address: &nbsp; &nbsp; <span class="text-info">{{$record->e_address ?? '' }} </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">About: &nbsp; &nbsp; <br> <span class="text-muted">
                                                                            {{$record->e_info ?? '' }}</span>
                                                                    </th>
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
                                                        <h6 class="mt-4 text-info text-uppercase modal-header p-4">Other Info.</h6>
                                                        <div class="table-responsive" >
                                                            <table class="table mb-0 table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">No. of Properties:<span class="text-info">EFAB Verizon Estate, Karsana </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">No. of Customers:<span class="text-info">Detached Duplex</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Email: &nbsp; &nbsp; <span class="text-info"> </span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Address: &nbsp; &nbsp; <span class="text-info">₦13,250,000.00</span></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Gender: &nbsp; &nbsp; <span class="text-info">1348 </span></th>
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
                                                <div class="table-responsive">
                                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th class="">#</th>
                                                            <th class="wd-15p">Date</th>
                                                            <th class="wd-15p">Estate</th>
                                                            <th class="wd-15p">House No.</th>
                                                            <th class="wd-15p">Property Name</th>
                                                            <th class="wd-15p" style="text-align: right;">Price(₦)</th>
                                                            <th class="wd-15p">Building Type</th>
                                                            <th class="wd-15p">Status</th>
                                                            <th class="wd-15p">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($record->getProperties as $key => $property)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{date('d M, Y', strtotime($property->created_at))}}</td>
                                                                <td>{{$property->getEstate->e_name ?? '' }}</td>
                                                                <td>{{ $property->house_no ?? '' }}</td>
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
                                                                <td>{{$property->getBuildingType->bt_name ?? '' }}</td>
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
                                        <p class="mb-0">
                                            Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                            sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                            farm-to-table readymade. Messenger bag gentrify pitchfork
                                            tattooed craft beer, iphone skateboard locavore carles etsy
                                            salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                            Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                            mi whatever gluten-free carles.
                                        </p>
                                    </div>
                                    <div class="tab-pane" id="settings1" role="tabpanel" >
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


@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>
@endsection
