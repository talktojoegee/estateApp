@extends('layouts.master-layout')
@section('title')
{{$title ?? ''}} Properties
@endsection
@section('current-page')
{{$title ?? ''}} Properties
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')
{{$title ?? '' }} Properties
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

    <div class="row">
        <div class="col-md-12">
                <div class="row">
                    @if($title == 'Available'  || $title == 'All')
                    <div class="col-xl-3 col-lg-6 col-sm-6 ">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-0 text-warning">Available</h6>
                                <h5 class="mb-1 text-warning mt-2 number-font">
                                    <span class="counter">{{ number_format($properties->where('status',0)->count() ) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($title == 'Rented'  || $title == 'All')
                    <div class="col-xl-3 col-lg-6 col-sm-6 ">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-0 text-primary">Rented</h6>
                                <h5 class="mb-1 text-primary mt-2 number-font">
                                    <span class="counter">{{ number_format($properties->where('status',1)->count() ) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($title == 'Sold'  || $title == 'All')
                    <div class="col-xl-3 col-lg-6 col-sm-6 ">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-0 text-success">Sold</h6>
                                <h5 class="mb-1 text-success mt-2 number-font">
                                    <span class="counter">{{ number_format($properties->where('status',2)->count() ) }}</span>
                                </h5>
                            </div>
                        </div>

                    </div>
                    @endif
                    @if($title == 'Reserved' || $title == 'All')
                    <div class="col-xl-3 col-lg-6 col-sm-6 ">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="mb-0 text-info">Reserved</h6>
                                <h5 class="mb-1 text-info mt-2 number-font">
                                    <span class="counter">{{ number_format($reservedCounter ) }}</span>
                                </h5>
                                <!-- <p class="mb-0 text-muted">
                                <span class="mb-0 fs-13 ms-1">
                                      $properties->count() > 0 ?  floor( ($properties->count()/$properties->count() )*100 ) : 0 }}%
                                </span> of number_format($properties->count() )}}
                                </p> -->
                            </div>
                        </div>

                    </div>
                    @endif

                </div>

        </div>
    </div>
    @include('property.partial._manage-menu')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Date</th>
                                <th class="wd-15p">Estate</th>
                                <th class="wd-15p">House No.</th>
                                <th class="wd-15p">Code</th>
                                <th class="wd-15p">Property Specification</th>
                                <th class="wd-15p" style="text-align: right;">Price(₦)</th>
                                <th class="wd-15p">Status</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($properties as $key => $property)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{date('d M, Y', strtotime($property->created_at))}}</td>
                                    <td>{{$property->getEstate->e_name ?? '' }}</td>
                                    <td>{{ $property->house_no ?? '' }}</td>
                                    <td>{{$property->property_code ?? '' }}</td>
                                    <td>
                                        <a href="{{route('show-property-details', ['slug'=>$property->slug])}}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 align-self-center me-3">
                                                    <img src="/assets/drive/property/{{$property->getGalleryFeaturedImageByPropertyId($property->id)->attachment ?? 'placeholder.png' }}" alt="{{$property->property_name ?? '' }}" class="rounded-circle avatar-xs">
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="text-truncate text-info font-size-14 mb-1">{{ substr($property->property_name,0,15).'...' ?? ''  }}</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-right" style="text-align: right;">{{ number_format($property->price,2)  }}</td>
                                    <td>
                                        @switch($property->status)
                                            @case(0)
                                            <span class="text-success">Available</span>
                                            @break
                                            @case(1)
                                            <span class="text-warning">Rented</span>
                                            @break
                                            @case(2)
                                            <span class="text-danger" style="color: #ff0000 !important;">Sold</span>
                                            @break
                                            @case(3)
                                            <span class="" style="color: orange !important;">Reserved</span>
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
