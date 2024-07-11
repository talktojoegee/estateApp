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


    @include('manager.property.partial._add-menu')
    <div class="row viewPropertyWindow">
        <div class="col-md-8 col-sm-8">
            <div class="card p-3">
                <h5 class="card-header">
                    {{$property->property_name ?? '' }}
                </h5>
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-custom" id="editProperty">Edit Property Details<i class="bx bxs-eyedropper"></i> </button></div>
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @foreach($property->getPropertyGalleryImages as $key=>$image)
                            <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img class="d-block img-fluid" src="/assets/drive/property/{{$image->attachment ?? '' }}" alt="Property image">
                            </div>
                            @endforeach
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
                    <h5 class="mt-4 text-uppercase text-custom">Details:</h5>
                    {!! $property->description ?? '' !!}
                    <h5 class="mt-4 text-uppercase text-custom">Summary:</h5>
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <tbody>
                            <tr>
                                <th scope="row" style="width: 400px;">Added By</th>
                                <td>{{$property->getManager->first_name ?? '' }} {{$property->getManager->last_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Value Estimate</th>
                                <td>{{ '₦'.number_format($property->price,2)  }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Location</th>
                                <td>{{$property->getLocation->state_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Address</th>
                                <td>{{$property->address ?? '' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Property Size</th>
                                <td>{{$property->property_size ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
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
                                </td>
                            </tr>
                            <tr>
                                <th>Type of Property</th>
                                <td>
                                    {{$property->getPropertyType->type_name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Unit No.</th>
                                <td>
                                    {{$property->unit_no ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Occupied By</th>
                                <td>
                                    {{$property->getOccupant->first_name ?? '-' }} {{$property->getOccupant->last_name ?? '-' }}
                                </td>
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
                    <div class="card-header bg-custom text-white">Features</div>
                    <div class="row mt-4">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="">No. of Rooms</label>
                                <input type="text" value="{{$property->no_of_rooms}}" readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="">No. of Sitting Rooms</label>
                                <input type="text" value="{{$property->no_of_sitting_rooms}}" readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->kitchen == 1 ? 'checked' : '' }} name="kitchen"  type="checkbox" id="kitchen">
                                <label class="form-check-label" for="kitchen">Kitchen?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->borehole == 1 ? 'checked' : '' }} name="borehole"  type="checkbox" id="borehole">
                                <label class="form-check-label" for="borehole">Borehole?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->pool == 1 ? 'checked' : '' }} name="pool"  type="checkbox" id="pool">
                                <label class="form-check-label" for="pool">Pool?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->security == 1 ? 'checked' : '' }} name="security"  type="checkbox" id="security">
                                <label class="form-check-label" for="security">Security?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->car_park == 1 ? 'checked' : '' }} name="carPark" type="checkbox" id="carPark">
                                <label class="form-check-label" for="carPark">Car park?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->garage == 1 ? 'checked' : '' }} name="garage" type="checkbox" id="garage">
                                <label class="form-check-label" for="garage">Garage?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->laundry == 1 ? 'checked' : '' }} name="laundry" type="checkbox" id="laundry">
                                <label class="form-check-label" for="laundry">Laundry?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->store_room == 1 ? 'checked' : '' }} name="storeRoom"  type="checkbox" id="storeRoom">
                                <label class="form-check-label" for="storeRoom">Store Room?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->balcony == 1 ? 'checked' : '' }} name="balcony" type="checkbox" id="balcony">
                                <label class="form-check-label" for="balcony">Balcony?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->elevator == 1 ? 'checked' : '' }} name="elevator" type="checkbox" id="elevator">
                                <label class="form-check-label" for="elevator">Elevator?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->play_ground == 1 ? 'checked' : '' }} name="playGround" type="checkbox" id="playGround">
                                <label class="form-check-label" for="playGround">Play ground?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->lounge == 1 ? 'checked' : '' }} name="lounge"  type="checkbox" id="lounge">
                                <label class="form-check-label" for="lounge">Lounge?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->tv == 1 ? 'checked' : '' }} name="tv"  type="checkbox" >
                                <label class="form-check-label" for="lounge">Television?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->wifi == 1 ? 'checked' : '' }} name="wifi"  type="checkbox" >
                                <label class="form-check-label" for="lounge">Wifi?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->dryer == 1 ? 'checked' : '' }} name="dryer"  type="checkbox" >
                                <label class="form-check-label" for="dryer">Dryer?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->carbon_monoxide_alarm == 1 ? 'checked' : '' }} name="carbon_monoxide_alarm"  type="checkbox" >
                                <label class="form-check-label" for="carbon_monoxide_alarm">Smoke Alarm?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->air_conditioning == 1 ? 'checked' : '' }} name="air_conditioning"  type="checkbox" >
                                <label class="form-check-label" for="air_conditioning">Air Conditioning?</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" disabled {{$property->washer == 1 ? 'checked' : '' }} name="washer"  type="checkbox" >
                                <label class="form-check-label" for="washer">Washer?</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($property->status == 0)
            <div class="card">
                <div class="card-body">
                    <div class="card-header bg-custom text-white">Listing</div>
                    <div class="row mt-4">
                        <form action="{{route('show-add-new-listing-form', ['account'=>$account])}}" method="post">
                            @csrf
                            <div class="col-md-12 col-sm-12">
                                <p><strong class="text-danger">Note:</strong> This property will be listed in the marketplace</p>
                                <div class="form-group">
                                    <label for="">Listing Amount</label>
                                    <input type="number" placeholder="Listing Amount" name="listingAmount" value="{{old('listingAmount')}}" class="form-control">
                                    @error('listingAmount')
                                    <i class="text-danger">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 mt-3">
                                <div class="form-group">
                                    <label for="">Listing Type</label>
                                    <select name="listingType" id="listingType" class="form-control">
                                        <option disabled selected>--Select Listing Type--</option>
                                        <option value="1">For Lease</option>
                                        <option value="2">For Sale</option>
                                    </select>
                                    @error('listingType')
                                    <i class="text-danger">{{$message}}</i>
                                    @enderror
                                    <input type="hidden" name="propertyId" value="{{$property->id}}">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 mt-3">
                                <div class="form-group">
                                    <label for="">Leasing Cycle(Frequency)</label>
                                    <select class="form-control select2" name="frequency">
                                        @foreach($frequencies as $frequency)
                                            <option value="{{$frequency->id}}" >{{$frequency->frequency_name ?? ''}}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('frequency')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 mt-3">
                                <div class="form-group d-flex justify-content-center">
                                    <button class="btn btn-custom">Submit <i class="bx bx-right-arrow"></i> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @if( $property->status != 1 || $property->status != 3 ) <!-- Not occupied or sold -->
            <div class="card">
                <div class="card-body">
                    <div class="card-header bg-custom text-white">Administration</div>
                    <div class="row mt-4">

                            <form action="{{route('update-occupancy-status', ['account'=>$account])}}" method="post">
                                @csrf
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="">Property Occupancy Status</label>
                                    <input type="hidden" name="propertyId" value="{{$property->id}}">
                                    <div class="input-group">
                                        <select class="form-control" name="occupancyStatus">
                                            <option {{$property->status == 0 ? 'selected' : ''}} value="0">Vacant</option>
                                            <option {{$property->status == 1 ? 'selected' : ''}} value="1">Occupied</option>
                                            <option {{$property->status == 2 ? 'selected' : ''}} value="2">Maintenance</option>
                                            <option {{$property->status == 3 ? 'selected' : ''}} value="3">Sold</option>
                                            <option {{$property->status == 4 ? 'selected' : ''}} value="4">Listed</option>
                                        </select>
                                        <button type="submit" class="btn btn-custom" type="button" id="occupancyStatus">Save changes</button>
                                    </div>
                                    <br> @error('occupancyStatus')
                                    <i class="text-danger">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="row editPropertyWindow">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('update-property-details', ['account'=>$account])}}" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="card-header text-white bg-custom mb-2">Edit Property Details
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Property Name <sup class="text-danger">*</sup></label>
                                    <input type="text" placeholder="Property Name" name="propertyName" id="propertyName" value="{{old('propertyName', $property->property_name)}}" class="form-control">
                                    <br> @error('propertyName')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Unit No.</label>
                                    <input type="text" placeholder="Unit No." name="unitNo" id="unitNo" value="{{old('unitNo', $property->unit_no)}}" class="form-control">
                                    <br> @error('unitNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Type of Property<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="typeOfProperty">
                                        @foreach($type_of_properties as $type)
                                        <option value="{{$type->id}}" {{$property->type_of_property == $type->id ? 'selected' : ''}}>{{$type->type_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('unitNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6 mt-2">
                                <div class="form-group">
                                    <label for="">Property Estimated Value<sup class="text-danger">*</sup></label>
                                    <input type="number" step="0.01" placeholder="Price" name="price" id="price" value="{{old('price', $property->price)}}" class="form-control">
                                    <br> @error('price')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 lg-6 mt-2">
                                <div class="form-group">
                                    <input type="hidden" name="propertyId" value="{{$property->id}}">
                                    <label for="">Location<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="location">
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}" {{$property->location_id == $state->id ? 'selected' : ''}}>{{$state->state_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('location')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Address <small >(Optional)</small></label>
                                    <textarea name="address" style="resize: none;" placeholder="Enter address here..." class="form-control">{{old('address', $property->address)}}</textarea>
                                    <br> @error('address')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Property Size <small >(Optional)</small></label>
                                    <input type="text" placeholder="Property Size" name="propertySize" id="propertySize" value="{{old('propertySize', $property->property_size)}}" class="form-control">
                                    <br> @error('propertySize')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="row">
                                    @foreach($property->getPropertyGalleryImages as $img)
                                    <div class="col-md-2">
                                        <i class="bx bx-trash text-danger" data-bs-toggle="modal" data-bs-target="#imageModal_{{$img->id}}"  style="cursor: pointer"></i>
                                        <div id="imageModal_{{$img->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Delete Image</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('delete-property-image', ['account'=>$account])}}" method="post" autocomplete="off">
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
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Gallery <sup class="text-danger">*</sup></label> <br>
                                    <input type="file" multiple name="gallery[]" id="gallery"  class="form-control-file">
                                    <br> @error('gallery')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-8 col-sm-8 col-lg-8">
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <div id="editor" style="height: 250px;">{!! $property->description ?? '' !!}</div>
                                        <textarea name="propertyDescription" id="hiddenContent" style="display: none">{{old('hiddenContent')}}</textarea>
                                        @error('description') <i class="text-danger">{{$message}}</i> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <div class="card-header bg-custom text-white">Features</div>
                                    <div class="row mt-4">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="">No. of Rooms</label>
                                                <input type="text" value="{{$property->no_of_rooms}}" name="noOfRooms" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="">No. of Sitting Rooms</label>
                                                <input type="text" value="{{$property->no_of_sitting_rooms}}" name="noOfSittingRooms" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->kitchen == 1 ? 'checked' : '' }} name="kitchen"  type="checkbox" id="kitchen">
                                                <label class="form-check-label" for="kitchen">Kitchen?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->borehole == 1 ? 'checked' : '' }} name="borehole"  type="checkbox" id="borehole">
                                                <label class="form-check-label" for="borehole">Borehole?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->pool == 1 ? 'checked' : '' }} name="pool"  type="checkbox" id="pool">
                                                <label class="form-check-label" for="pool">Pool?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->security == 1 ? 'checked' : '' }} name="security"  type="checkbox" id="security">
                                                <label class="form-check-label" for="security">Security?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->car_park == 1 ? 'checked' : '' }} name="carPark" type="checkbox" id="carPark">
                                                <label class="form-check-label" for="carPark">Car park?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->garage == 1 ? 'checked' : '' }} name="garage" type="checkbox" id="garage">
                                                <label class="form-check-label" for="garage">Garage?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->laundry == 1 ? 'checked' : '' }} name="laundry" type="checkbox" id="laundry">
                                                <label class="form-check-label" for="laundry">Laundry?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->store_room == 1 ? 'checked' : '' }} name="storeRoom"  type="checkbox" id="storeRoom">
                                                <label class="form-check-label" for="storeRoom">Store Room?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->balcony == 1 ? 'checked' : '' }} name="balcony" type="checkbox" id="balcony">
                                                <label class="form-check-label" for="balcony">Balcony?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->elevator == 1 ? 'checked' : '' }} name="elevator" type="checkbox" id="elevator">
                                                <label class="form-check-label" for="elevator">Elevator?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->play_ground == 1 ? 'checked' : '' }} name="playGround" type="checkbox" id="playGround">
                                                <label class="form-check-label" for="playGround">Play ground?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input"  {{$property->lounge == 1 ? 'checked' : '' }} name="lounge"  type="checkbox" id="lounge">
                                                <label class="form-check-label" for="lounge">Lounge?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" {{$property->tv == 1 ? 'checked' : '' }} name="tv"  type="checkbox" >
                                                <label class="form-check-label" for="lounge">Television?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" {{$property->wifi == 1 ? 'checked' : '' }} name="wifi"  type="checkbox" >
                                                <label class="form-check-label" for="lounge">Wifi?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" {{$property->tv == 1 ? 'checked' : '' }} name="dryer"  type="checkbox" >
                                                <label class="form-check-label" for="dryer">Dryer?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" {{$property->carbon_monoxide_alarm == 1 ? 'checked' : '' }} name="smokeAlarm"  type="checkbox" >
                                                <label class="form-check-label" for="carbon_monoxide_alarm">Smoke Alarm?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" {{$property->air_conditioning == 1 ? 'checked' : '' }} name="airConditioning"  type="checkbox" >
                                                <label class="form-check-label" for="air_conditioning">Air Conditioning?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" {{$property->washer == 1 ? 'checked' : '' }} name="washer"  type="checkbox" >
                                                <label class="form-check-label" for="washer">Washer?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                    <button class=" btn btn-secondary waves-effect waves-light mr-2" type="button" id="cancelPropertyEdit">Cancel</button>
                                    <button type="submit" class="btn btn-custom btn-lg waves-effect waves-light"> Save changes <i class="bx bx-save ml-2"></i></button>
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
    <script>
        $(document).ready(function(){
            $('.editPropertyWindow').hide();
            let options = {
                placeholder: 'Enter property description here...',
                theme: 'snow'
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
