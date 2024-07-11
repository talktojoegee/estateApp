@extends('layouts.master-layout')
@section('title')
    Add New Property
@endsection
@section('current-page')
    Add New Property
@endsection
@section('extra-styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

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
    @if($errors->any())
        <div class="alert alert-warning">
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </div>
    @endif
    @include('manager.property.partial._add-menu')
    <div class="row">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('add-new-property', ['account'=>$account])}}" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="card-header text-white bg-custom mb-2">Add New Property
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Property Name <sup class="text-danger">*</sup></label>
                                    <input type="text" placeholder="Property Name" name="propertyName" id="propertyName" value="{{old('propertyName')}}" class="form-control">
                                    <br> @error('propertyName')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Unit No.</label>
                                    <input type="text" placeholder="Unit No." name="unitNo" id="unitNo" value="{{old('unitNo')}}" class="form-control">
                                    <br> @error('unitNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Type of Property<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="typeOfProperty">
                                        <option disabled selected>Select Type</option>
                                        @foreach($types as $type)
                                        <option value="{{$type->id}}">{{$type->type_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('unitNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6 mt-2">
                                <div class="form-group">
                                    <label for="">Property Estimated Value<sup class="text-danger">*</sup></label>
                                    <input type="number" step="0.01" placeholder="Price" name="price" id="price" value="{{old('price')}}" class="form-control">
                                    <br> @error('price')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Location<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2" name="location">
                                        <option disabled selected>Select Location</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->state_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('location')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Address <small >(Optional)</small></label>
                                    <textarea name="address" style="resize: none;" placeholder="Enter address here..." class="form-control">{{old('address')}}</textarea>
                                    <br> @error('address')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 lg-6">
                                <div class="form-group">
                                    <label for="">Property Size <small >(Optional)</small></label>
                                    <input type="text" placeholder="Property Size" name="propertySize" id="propertySize" value="{{old('propertySize')}}" class="form-control">
                                    <br> @error('propertySize')<i class="text-danger">{{$message}}</i>@enderror
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
                                        <div id="editor" style="height: 250px;"></div>
                                        <textarea name="propertyDescription" id="hiddenContent" style="display: none">{{old('hiddenContent')}}</textarea>
                                        @error('description') <i class="text-danger">{{$message}}</i> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <div class="card-header bg-custom text-white">Features</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">No. of Rooms</label>
                                                <input type="number" name="noOfRooms" value="{{old('noOfRooms',0)}}" placeholder="No. of Rooms"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">No. of Sitting Rooms</label>
                                                <input type="number" name="noOfSittingRooms" value="{{old('noOfSittingRooms',0)}}" placeholder="No. of Sitting Rooms"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="kitchen"  type="checkbox" id="kitchen">
                                                        <label class="form-check-label" for="kitchen">Kitchen?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="borehole"  type="checkbox" id="borehole">
                                                        <label class="form-check-label" for="borehole">Borehole?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="pool"  type="checkbox" id="pool">
                                                        <label class="form-check-label" for="pool">Pool?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="security"  type="checkbox" id="security">
                                                        <label class="form-check-label" for="security">Security?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="carPark" type="checkbox" id="carPark">
                                                        <label class="form-check-label" for="carPark">Car park?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="garage" type="checkbox" id="garage">
                                                        <label class="form-check-label" for="garage">Garage?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="laundry" type="checkbox" id="laundry">
                                                        <label class="form-check-label" for="laundry">Laundry?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="storeRoom"  type="checkbox" id="storeRoom">
                                                        <label class="form-check-label" for="storeRoom">Store Room?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="balcony" type="checkbox" id="balcony">
                                                        <label class="form-check-label" for="balcony">Balcony?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="elevator" type="checkbox" id="elevator">
                                                        <label class="form-check-label" for="elevator">Elevator?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="playGround" type="checkbox" id="playGround">
                                                        <label class="form-check-label" for="playGround">Play ground?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="lounge"  type="checkbox" id="lounge">
                                                        <label class="form-check-label" for="lounge">Lounge?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="wifi"  type="checkbox" id="wifi">
                                                        <label class="form-check-label" for="wifi">Wifi?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="tv"  type="checkbox" id="tv">
                                                        <label class="form-check-label" for="tv">Television?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="dryer"  type="checkbox" id="dryer">
                                                        <label class="form-check-label" for="dryer">Dryer?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="smokeAlarm"  type="checkbox" id="smokeAlarm">
                                                        <label class="form-check-label" for="smokeAlarm">Smoke Alarm?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="airConditioning"  type="checkbox" id="airConditioning">
                                                        <label class="form-check-label" for="airConditioning">Air Conditioning?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="washer"  type="checkbox" id="washer">
                                                        <label class="form-check-label" for="washer">Washer?</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                    <button type="submit" class="btn btn-custom btn-lg waves-effect waves-light"> Add New Property <i class="bx bx-plus ml-2"></i></button>
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
            let options = {
                placeholder: 'Enter property description here...',
                theme: 'snow'
            };
            let quill = new Quill('#editor', options);
            $('#addPropertyForm').on('submit',function(){
                $('#hiddenContent').val(quill.root.innerHTML);
            })
        });
    </script>
@endsection
