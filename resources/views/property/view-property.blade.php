@extends('layouts.master-layout')
@section('title')
    Property Details
@endsection
@section('current-page')
    Property Details
@endsection
@section('extra-styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')
    Property Details
@endsection

@section('main-content')
    @inject('Utility', 'App\Http\Controllers\Portal\PropertyController')
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


    @include('property.partial._add-menu')
    <div class="row viewPropertyWindow">
        <div class="col-md-8 col-sm-8">
            <div class="card p-3">
                <h5 class="modal-header text-info">
                    {{$property->property_name ?? '' }}
                </h5>
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-2">
                        <span class="btn btn-primary" id="editProperty"><i class="bx bx-pencil"></i> </span>
                    </div>
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @if(count($property->getPropertyGalleryImages) > 0)
                                @foreach($property->getPropertyGalleryImages as $key=>$image)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                    <img class="d-block img-fluid" src="/assets/drive/property/{{$image->attachment ?? '' }}" alt="Property image">
                                </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <img class="d-block img-fluid" src="/assets/drive/property/placeholder.png" alt="Property image">
                                </div>
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <h6 class="mt-4 text-uppercase modal-header p-4  mb-3 text-info">Details</h6>
                    <div class="p-3">{!! $property->description ?? '' !!}</div>
                    <h6 class="mt-4 text-uppercase modal-header p-4 text-info">Summary</h6>
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped">
                            <tbody>
                            <tr>
                                <th scope="row" >Estate: &nbsp; &nbsp; <span class="text-info">{{$property->getEstate->e_name ?? '' }} </span></th>
                                <th scope="row">Property Type: &nbsp; &nbsp; <span class="text-info">{{$property->getBuildingType->bt_name ?? '' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >Property Title: &nbsp; &nbsp; <span class="text-info">{{$property->getPropertyTitle->pt_name ?? '' }} </span></th>
                                <th scope="row" >Price: &nbsp; &nbsp; <span class="text-info">{{ env('APP_CURRENCY')  }}{{ number_format($property->price ?? 0,2) }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >House No.: &nbsp; &nbsp; <span class="text-info">{{$property->house_no ?? '-' }} </span></th>
                                <th scope="row" >Shop No.: &nbsp; &nbsp; <span class="text-info">{{ $property->shop_no ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >Plot No.: &nbsp; &nbsp; <span class="text-info">{{$property->plot_no ?? '-' }} </span></th>
                                <th scope="row" >No. of Office Rooms: &nbsp; &nbsp; <span class="text-info">{{ $property->no_of_office_rooms ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >No. of shops: &nbsp; &nbsp; <span class="text-info">{{$property->no_of_shops ?? '-' }} </span></th>
                                <th scope="row" >Office Ensuite Toilet/Bathroom: &nbsp; &nbsp; <span class="text-info">
                                        @switch($property->office_ensuite_toilet_bathroom)
                                            @case(0)
                                            None
                                            @break
                                            @case(1)
                                            Yes
                                            @break
                                            @case(2)
                                            No
                                            @break
                                        @endswitch
                                    </span></th>
                            </tr>
                            <tr>
                                <th scope="row" >Property Code: &nbsp; &nbsp; <span class="text-info">{{$property->property_code ?? '-' }} </span></th>
                                <th scope="row" >Street: &nbsp; &nbsp; <span class="text-info">{{ $property->street ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >Total No. of Bedrooms: &nbsp; &nbsp; <span class="text-info">{{$property->total_no_bedrooms ?? '-' }} </span></th>
                                <th scope="row" >With BQ.: &nbsp; &nbsp; <span class="text-info">{{ $property->getWithBQOption->bqo_name ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >No. of Floors: &nbsp; &nbsp; <span class="text-info">{{$property->no_of_floors ?? '-' }} </span></th>
                                <th scope="row" >No. of Toilets: &nbsp; &nbsp; <span class="text-info">{{ $property->no_of_toilets ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >No. of Car Parking: &nbsp; &nbsp; <span class="text-info">{{$property->no_of_car_parking ?? '-' }} </span></th>
                                <th scope="row" >No. of Units: &nbsp; &nbsp; <span class="text-info">{{ $property->no_of_units ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >Property Condition: &nbsp; &nbsp;

                                    @switch($property->property_condition)
                                        @case(1)
                                        <span class="text-success">Good</span>
                                        @break
                                        @case(2)
                                        <span class="text-warning">Under Repair</span>
                                        @break
                                        @case(3)
                                        <span class="text-danger" style="color: #ff0000 !important;">Bad</span>
                                        @break
                                        @case(4)
                                        <span class="text-info"> Fair</span>
                                        @break
                                    @endswitch
                                    </th>
                                <th scope="row" >Property Status: &nbsp; &nbsp; <span class="text-info">{{ $property->getConstructionStage->cs_name ?? '-' }}</span></th>
                            </tr>
                            <tr>
                                <th scope="row" >Land Size: &nbsp; &nbsp; <span class="text-info">{{$property->land_size ?? '-' }} </span></th>
                                <th scope="row" >Ledger Account: &nbsp; &nbsp; <span class="text-warning">{{ $property->getGlAccount->account_name ?? '-' }} - {{ $property->getGlAccount->glcode ?? '-' }}</span></th>
                            </tr>
                             <tr>

                                <th scope="row" >Status: &nbsp; &nbsp;
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
                                </th>
                                 <th scope="row" >Date Added: &nbsp; &nbsp; <span class="text-info"> {{ date('d M, Y h:ia', strtotime($property->created_at)) }}  </span></th>
                            </tr>
                            <tr>
                                <th scope="row" colspan="2" >Payment Plan: &nbsp; &nbsp; <span class="text-info"> {{ $property->getPaymentPlan->pp_name ?? '' }} <br> <small>({{ $property->getPaymentPlan->pp_description ?? '-' }})</small>  </span></th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <h6 class="modal-header p-4 text-uppercase">Other Details</h6>
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped">
                            <tbody>
                            <tr>
                                <th scope="row" > Added By: &nbsp; &nbsp; <span class="text-info"> {{$property->getAddedBy->title ?? '' }} {{$property->getAddedBy->first_name ?? '' }} {{$property->getAddedBy->last_name ?? '' }} {{$property->getAddedBy->other_names ?? '' }} </span></th>

                            </tr>
                            <tr>
                                <th scope="row" > Sold To: &nbsp; &nbsp; <span class="text-info">  {{$property->getSoldTo->first_name ?? '' }} {{$property->getSoldTo->last_name ?? '' }} {{$property->getSoldTo->other_names ?? '' }} </span></th>
                                <th scope="row" >Date Sold: &nbsp; &nbsp;
                                    <span class="text-info">{{ !is_null($property->date_sold) ? date('d M, Y h:ia', strtotime($property->date_sold)) : '-' }}</span>
                                </th>

                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="modal-header text-uppercase ">Features</div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Kitchen: {!! $property->kitchen == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Borehole: {!! $property->borehole == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Pool: {!! $property->pool == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Security: {!! $property->security == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Car Park: {!! $property->car_park == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Garage: {!! $property->garage == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Laundry: {!! $property->laundry == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Store Room: {!! $property->store_room == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Balcony: {!! $property->balcony == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Elevator: {!! $property->elevator == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Play Ground: {!! $property->play_ground == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Lounge: {!! $property->lounge == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Television: {!! $property->tv == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Wifi: {!! $property->wifi == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Dryer: {!! $property->dryer == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Smoke Alarm: {!! $property->c_oxide_alarm == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Air Conditioning: {!! $property->air_conditioning == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Washer: {!! $property->washer == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Penthouse: {!! $property->penthouse == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Gate House: {!! $property->gate_house == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Generator House: {!! $property->gen_house == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">BQ: {!! $property->bq == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Fitted Wardrobe: {!! $property->fitted_wardrobe == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Anteroom: {!! $property->anteroom == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class=" mb-3 ">
                                <label class="form-check-label">Guest Toilet: {!! $property->guest_toilet == 1 ? "<i class='bx bx-check-double text-success' style='font-size:18 !important;'></i>" : "<i class='bx bx-x text-danger' style='color:#ff0000 !important; font-size:18 !important;'></i>" !!}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <div class="modal-header text-uppercase ">Property Allocation</div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table class="table table-striped mb-0">

                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Allotment</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!is_null($property->sold_to))
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>{{$property->getSoldTo->first_name ?? '' }} {{$property->getSoldTo->last_name ?? '' }} {{$property->getSoldTo->other_names ?? '' }}</td>
                                            <td><small><span class="text-success">First Allottee</span></small></td>
                                        </tr>
                                    @endif
                                    @foreach($property->getAllocations as $key =>  $allocation)
                                        <tr>
                                            <td>{{ $key + 2 }}</td>
                                            <td>{{$allocation->getCustomer->first_name ?? '' }} {{$allocation->getCustomer->last_name ?? '' }} {{$allocation->getCustomer->other_names ?? '' }}</td>
                                            <td><small><code>{{$Utility->numToOrdinalWord($allocation->level ?? 0)}} Allottee</code></small></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="modal-header text-uppercase ">Map View</div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <iframe width="100%"  frameborder="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q='{{str_replace(",", "", str_replace(" ", "+", $property->getEstate->e_name)) }}'&z=14&output=embed"  height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row editPropertyWindow">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('update-property-details') }}" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">Edit Property
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Estate<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="Select the estate to which this property belongs to" required  class="form-control p-3 select2" name="estate">
                                        <option disabled selected>Select Estate</option>
                                        @foreach($estates as $estate)
                                            <option value="{{$estate->e_id}}" {{ $property->estate_id == $estate->e_id ? 'selected' : null }}>{{ $estate->e_name ?? '' }}</option>
                                        @endforeach

                                    </select>
                                    <br> @error('estate')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">House No. <small>(Optional)</small></label>
                                    <input type="text" value="{{old('houseNo', $property->house_no)}}" placeholder="House No." name="houseNo" class="form-control">
                                    <br> @error('houseNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Shop No. <small>(Optional)</small></label>
                                    <input type="text" value="{{old('shopNo',$property->shop_no)}}" placeholder="Shop No." name="shopNo" class="form-control">
                                    <br> @error('shopNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Plot No. <small>(Optional)</small></label>
                                    <input type="text" value="{{old('plotNo',$property->plot_no)}}" placeholder="Plot No." name="plotNo" class="form-control">
                                    <br> @error('plotNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Office Rooms <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Office Rooms" name="noOfOfficeRooms" id="noOfOfficeRooms" value="{{old('noOfOfficeRooms',$property->no_of_office_rooms)}}" class="form-control">
                                    <br> @error('noOfOfficeRooms')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Office/Shop En suite with toilet/bathroom</label>
                                    <select name="officeShopEnsuite" id="officeShopEnsuite" class="form-control">
                                        <option value="0" {{$property->office_ensuite_toilet_bathroom == 0 ? 'selected' : null }}>None</option>
                                        <option value="1" {{$property->office_ensuite_toilet_bathroom == 1 ? 'selected' : null }}>Yes</option>
                                        <option value="2" {{$property->office_ensuite_toilet_bathroom == 2 ? 'selected' : null }}>No</option>
                                    </select>
                                    <br> @error('officeShopEnsuite')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Shops <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Shops" name="noOfShops" id="noOfShops" value="{{old('noOfShops',$property->no_of_shops)}}" class="form-control">
                                    <br> @error('noOfShops')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Property Type<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="What kind of building is this?" required  class="form-control select2" name="buildingType">
                                        @foreach($buildingTypes as $key=> $bType)
                                            <option value="{{$bType->bt_id}}" {{ $property->building_type == $bType->bt_id ? 'selected' : null }}>{{ $bType->bt_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('buildingType')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Total Number of Bedrooms <small>(Optional)</small></label>
                                    <input type="number" data-parsley-required-message="Indicate the number of bedrooms this property has" required  placeholder="Total Number of Bedrooms" name="totalBedrooms" id="totalBedrooms" value="{{old('totalBedrooms',$property->total_no_bedrooms)}}" class="form-control">
                                    <br> @error('totalBedrooms')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">With BQ<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="Does this property has BQ? Choose the option that best describes it." required  class="form-control select2" name="withBQ">
                                        @foreach($bqOptions as $key => $option)
                                            <option value="{{$option->bqo_id}}" {{ $property->with_bq == $option->bqo_id ? 'selected' : null }}>{{ $option->bqo_name ?? '' }}</option>
                                        @endforeach

                                    </select>
                                    <br> @error('withBQ')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for=""> Number of Floors <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Floors" name="noOfFloors" id="noOfFloors" value="{{old('noOfFloors',$property->no_of_floors)}}" class="form-control">
                                    <br> @error('noOfFloors')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Toilets/bathrooms <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Toilets/bathrooms" name="noOfToilets" id="noOfToilets" value="{{old('noOfToilets',$property->no_of_toilets)}}" class="form-control">
                                    <br> @error('noOfToilets')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Car Parking Space <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Car Parking Space" name="noOfCarParking" id="noOfCarParking" value="{{old('noOfCarParking',$property->no_of_car_parking)}}" class="form-control">
                                    <br> @error('noOfCarParking')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Units <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Units" name="noOfUnits" id="noOfUnits" value="{{old('noOfUnits',$property->no_of_units)}}" class="form-control">
                                    <br> @error('noOfUnits')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" step="0.01" data-parsley-required-message="Certainly this is not intended to be sold for FREE. Enter the price" required  placeholder="Price" name="price" id="price" value="{{old('price',$property->price)}}" class="form-control">
                                    <br> @error('price')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Property Condition<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="What's the condition of this property?" required  class="form-control select2" name="propertyCondition">
                                        <option value="1" {{ $property->property_condition == 1 ? 'selected' : null  }}>Good</option>
                                        <option value="2" {{ $property->property_condition == 2 ? 'selected' : null  }}>Under Repair</option>
                                        <option value="3" {{ $property->property_condition == 3 ? 'selected' : null  }}>Bad</option>
                                        <option value="4" {{ $property->property_condition == 4 ? 'selected' : null  }}>Fair</option>

                                    </select>
                                    <br> @error('propertyCondition')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Property Status<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="At what stage of construction are you?" required  class="form-control select2" name="constructionStage">
                                        @foreach($constructionStages as $key => $stage)
                                            <option value="{{$stage->cs_id}}" {{ $stage->cs_id == $property->construction_stage ? 'selected' : null }}>{{ $stage->cs_name }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('constructionStage')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Land size</label>
                                    <input data-parsley-required-message="Help us indicate the land size" required  type="text" placeholder="Number of Office Rooms" name="landSize" id="landSize" value="{{old('landSize',$property->land_size)}}" class="form-control">
                                    <br> @error('landSize')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Account<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="Choose the ledger account that should be used for this property." required  class="form-control select2" name="account">
                                        <option disabled selected>-- Select account -- </option>
                                        <option value="0">Default Account</option>
                                        @foreach($accounts as $account)
                                            <option value="{{$account->glcode}}" {{ $property->gl_id == $account->glcode ? 'selected' : null  }}>{{ $account->account_name ?? '' }}</option>
                                        @endforeach

                                    </select>
                                    <br> @error('account')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Property Title <sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="Choose property title" required  class="form-control select2" name="propertyTitle">
                                        @foreach($titles as $key => $title)
                                            <option value="{{$title->pt_id}}" {{ $title->pt_id == $property->property_title ? 'selected' : null }}>{{ $title->pt_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('propertyTitle')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 mt-3 mb-3">
                                <div class="row">
                                    @foreach($property->getPropertyGalleryImages as $img)
                                        <div class="col-md-2">
                                            <i class="bx bx-trash text-danger" style="color: #ff0000 !important; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal_{{$img->id}}"  style="cursor: pointer"></i>
                                            <div id="imageModal_{{$img->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Delete Image</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('delete-property-image') }}" method="post" autocomplete="off">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p><strong class="text-danger">Note:</strong> This image will be deleted. Are you sure that's what you want to do?</p>
                                                                        </div>
                                                                        <div class="form-group d-flex justify-content-center mt-2">
                                                                            <input type="hidden" name="propertyId" value="{{$property->id}}">
                                                                            <input type="hidden" name="imageId" value="{{$img->id}}">
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-sm btn-custom"><i class="bx bx-trash mr-2"></i> Delete</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <img class="rounded me-2" alt="100x100" width="100" src="/assets/drive/property/{{$img->attachment ?? ''}}" data-holder-rendered="true">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Gallery <sup class="text-danger">*</sup></label> <br>
                                    <input data-parsley-required-message="Upload at least one image" required  type="file" multiple name="gallery[]" id="gallery"  class="form-control-file">
                                    <br> @error('gallery')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8 col-sm-8 col-lg-8 mt-3">
                                    <div class="form-group">
                                        <label for="">Property Specification <sup>*</sup></label>
                                        <input type="text" data-parsley-required-message="Enter property specification?" required  placeholder="Property Specification" name="propertyName" id="propertyName" value="{{old('propertyName',$property->property_name)}}" class="form-control">
                                        @error('propertyName') <i class="text-danger">{{$message}}</i> @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">Additional Information</label>
                                        <div id="editor" style="height: 250px;">{!! $property->description ?? '' !!}</div>
                                        <textarea data-parsley-required-message="Give us brief information about this property."   name="propertyDescription" id="hiddenContent" style="display: none">{{old('hiddenContent')}}</textarea>
                                        @error('propertyDescription') <i class="text-danger">{{$message}}</i> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <div class="card-header bg-custom text-white mb-3">Features</div>
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="kitchen" {{$property->kitchen == 1 ? 'checked' : '' }}  type="checkbox" id="kitchen">
                                                        <label class="form-check-label" for="kitchen">Kitchen?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="borehole" {{$property->borehole == 1 ? 'checked' : '' }}  type="checkbox" id="borehole">
                                                        <label class="form-check-label" for="borehole">Borehole?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="pool" {{$property->pool == 1 ? 'checked' : '' }}  type="checkbox" id="pool">
                                                        <label class="form-check-label" for="pool">Pool?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="security" {{$property->security == 1 ? 'checked' : '' }}  type="checkbox" id="security">
                                                        <label class="form-check-label" for="security">Security?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="carPark" {{$property->car_park == 1 ? 'checked' : '' }} type="checkbox" id="carPark">
                                                        <label class="form-check-label" for="carPark">Car park?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="garage" {{$property->garage == 1 ? 'checked' : '' }} type="checkbox" id="garage">
                                                        <label class="form-check-label" for="garage">Garage?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="laundry" {{$property->laundry == 1 ? 'checked' : '' }} type="checkbox" id="laundry">
                                                        <label class="form-check-label" for="laundry">Laundry?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="storeRoom" {{$property->store_room == 1 ? 'checked' : '' }}  type="checkbox" id="storeRoom">
                                                        <label class="form-check-label" for="storeRoom">Store Room?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="balcony" {{$property->balcony == 1 ? 'checked' : '' }} type="checkbox" id="balcony">
                                                        <label class="form-check-label" for="balcony">Balcony?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="elevator" {{$property->elevator == 1 ? 'checked' : '' }} type="checkbox" id="elevator">
                                                        <label class="form-check-label" for="elevator">Elevator?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="playGround" {{$property->play_ground == 1 ? 'checked' : '' }} type="checkbox" id="playGround">
                                                        <label class="form-check-label" for="playGround">Play ground?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="lounge" {{$property->lounge == 1 ? 'checked' : '' }}  type="checkbox" id="lounge">
                                                        <label class="form-check-label" for="lounge">Lounge?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="wifi" {{$property->wifi == 1 ? 'checked' : '' }}  type="checkbox" id="wifi">
                                                        <label class="form-check-label" for="wifi">Wifi?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="tv" {{$property->tv == 1 ? 'checked' : '' }}  type="checkbox" id="tv">
                                                        <label class="form-check-label" for="tv">Television?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="dryer" {{$property->dryer == 1 ? 'checked' : '' }}  type="checkbox" id="dryer">
                                                        <label class="form-check-label" for="dryer">Dryer?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="smokeAlarm" {{$property->c_oxide_alarm == 1 ? 'checked' : '' }}  type="checkbox" id="smokeAlarm">
                                                        <label class="form-check-label" for="smokeAlarm">Smoke Alarm?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="airConditioning" {{$property->air_conditioning == 1 ? 'checked' : '' }}  type="checkbox" id="airConditioning">
                                                        <label class="form-check-label" for="airConditioning">Air Conditioning?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="washer" {{$property->washer == 1 ? 'checked' : '' }}  type="checkbox" id="washer">
                                                        <label class="form-check-label" for="washer">Washer?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="gen_house" {{$property->gen_house == 1 ? 'checked' : '' }}  type="checkbox" id="gen_house">
                                                        <label class="form-check-label" for="gen_house">Generator House?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="fitted_wardrobe" {{$property->fitted_wardrobe == 1 ? 'checked' : '' }}  type="checkbox" id="fitted_wardrobe">
                                                        <label class="form-check-label" for="fitted_wardrobe">Fitted Wardrobe?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="gate_house" {{$property->gate_house == 1 ? 'checked' : '' }}  type="checkbox" id="gate_house">
                                                        <label class="form-check-label" for="gate_house">Gate House?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="penthouse"  {{$property->penthouse == 1 ? 'checked' : '' }} type="checkbox" id="penthouse">
                                                        <label class="form-check-label" for="penthouse">Penthouse?</label>
                                                        <input type="hidden" name="propertyId" value="{{$property->id}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="bq" {{$property->bq == 1 ? 'checked' : '' }}  type="checkbox" id="bq">
                                                        <label class="form-check-label" for="bq">BQ?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="guest_toilet" {{$property->guest_toilet == 1 ? 'checked' : '' }}  type="checkbox" id="guest_toilet">
                                                        <label class="form-check-label" for="guest_toilet">Guest Toilet?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="anteroom" {{$property->anteroom == 1 ? 'checked' : '' }}  type="checkbox" id="anteroom">
                                                        <label class="form-check-label" for="anteroom">Anteroom?</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                    <div class="btn-group">
                                        <button class=" btn btn-warning mr-2" type="button" id="cancelPropertyEdit">Cancel <i class="bx bx-x ml-2"></i></button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit <i class="bx bx-check-double ml-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="/js/parsley.js"></script>
    <script>
        $(document).ready(function(){
            $('#addPropertyForm').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });

            $('.editPropertyWindow').hide();
            let options = {
                placeholder: 'Enter additional information here...',
                theme: 'snow' // | no-reply@efabpropertiesdatabase.com
            };
            let quill = new Quill('#editor', options);
            $('#addPropertyForm').on('submit',function(){
                $('#hiddenContent').val(quill.root.innerHTML);
            })

            $(document).on('click', '#editProperty', function(){
                $('.editPropertyWindow').show();
                $('.viewPropertyWindow').hide();
            });
            $(document).on('click', '#cancelPropertyEdit', function(){
                $('.editPropertyWindow').hide();
                $('.viewPropertyWindow').show();
            });
        });
    </script>


@endsection
