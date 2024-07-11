@extends('layouts.master-layout')
@section('title')
    Manage Properties
@endsection
@section('current-page')
    Manage Properties
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')
    Manage Properties
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
            <div class="card">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0 border-end">
                        <div class="card-body text-center">
                            <h6 class="mb-0 text-warning">Vacant</h6>
                            <h5 class="mb-1 mt-2 number-font">
                                <span class="counter">{{ number_format($properties->where('status',0)->count() ) }}</span>
                            </h5>
                            <p class="mb-0 text-muted">
                                <span class="mb-0 fs-13 ms-1">
                                     {{ $properties->count() > 0 ?  floor( ($properties->where('status',0)->count()/$properties->count() )*100 ) : 0 }}%
                                </span> of {{number_format($properties->count() )}}
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0 border-end">
                        <div class="card-body text-center">
                            <h6 class="mb-0 text-primary">Taken</h6>
                            <h5 class="mb-1 mt-2 number-font">
                                <span class="counter">{{ number_format($properties->where('status',1)->count() ) }}</span>
                            </h5>
                            <p class="mb-0 text-muted">
                                <span class="mb-0 fs-13 ms-1">
                                     {{ $properties->count() > 0 ?  floor( ($properties->where('status',1)->count()/$properties->count() )*100 ) : 0 }}%
                                </span> of {{number_format($properties->count() )}}
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0 border-end">
                        <div class="card-body text-center">
                            <h6 class="mb-0 text-secondary">Maintenance | Renovation</h6>
                            <h5 class="mb-1 mt-2 number-font">
                                <span class="counter">{{ number_format($properties->where('status',3)->count() ) }}</span>
                            </h5>
                            <p class="mb-0 text-muted">
                                <span class="mb-0 fs-13 ms-1">
                                     {{ $properties->count() > 0 ?  floor( ($properties->where('status',3)->count()/$properties->count() )*100 ) : 0 }}%
                                </span> of {{number_format($properties->count() )}}
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0">
                        <div class="card-body text-center">
                            <h6 class="mb-0 text-success">Sold</h6>
                            <h5 class="mb-1 mt-2 number-font">
                                <span class="counter">{{ number_format($properties->where('status',4)->count() ) }}</span>
                            </h5>
                            <p class="mb-0 text-muted">
                                <span class="mb-0 fs-13 ms-1">
                                     {{ $properties->count() > 0 ?  floor( ($properties->where('status',4)->count()/$properties->count() )*100 ) : 0 }}%
                                </span> of {{number_format($properties->count() )}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('manager.property.partial._manage-menu')
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
                                <th class="wd-15p">Property Name</th>
                                <th class="wd-15p">Value(₦)</th>
                                <th class="wd-15p">Location</th>
                                <th class="wd-15p">Type</th>
                                <th class="wd-15p">Status</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($properties as $property)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{date('d M, Y', strtotime($property->created_at))}}</td>
                                    <td>
                                        <a href="{{route('show-property-details', ['account'=>$account, 'slug'=>$property->slug])}}">
                                            <img class="rounded avatar-sm" src="/assets/drive/property/{{$property->getGalleryFeaturedImageByPropertyId($property->id)->attachment ?? '' }}" alt="{{$property->property_name ?? '' }}">
                                            {{ $property->property_name ?? ''  }}
                                        </a>
                                    </td>
                                    <td class="text-right" style="text-align: right;">{{ number_format($property->price,2)  }}</td>
                                    <td>{{$property->getLocation->state_name ?? '' }}</td>
                                    <td>{{$property->getPropertyType->type_name ?? '' }}</td>
                                    <td>
                                        @switch($property->status)
                                            @case(0)
                                                <label class='text-primary'>Vacant</label>
                                                @break
                                            @case(1)
                                                <label class='text-info'>Occupied</label>
                                                @break
                                            @case(2)
                                                <label class='text-warning'>Maintenance</label>
                                                @break
                                            @case(3)
                                                <label class='text-warning'>Sold</label>
                                                @break
                                            @case(4)
                                                <label class='text-success'>Listed</label>
                                                @break
                                        @endswitch
                                    <td>
                                        <a href="{{route('show-property-details', ['account'=>$account, 'slug'=>$property->slug])}}" class="btn btn-light text-custom">View <i class="bx bx-home-circle"></i> </a>
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
