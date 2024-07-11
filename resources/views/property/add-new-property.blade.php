@extends('layouts.master-layout')
@section('title')
    Add New Property
@endsection
@section('current-page')
    Add New Property
@endsection
@section('extra-styles')
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
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
    @include('property.partial._add-menu')
    <div class="row">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('add-new-property') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">Add New Property
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Estate<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="Select the estate to which this property belongs to" required  class="form-control p-3 select2" name="estate">
                                        <option disabled selected>Select Estate</option>
                                        @foreach($estates as $estate)
                                            <option value="{{$estate->e_id}}">{{ $estate->e_name ?? '' }}</option>
                                        @endforeach

                                    </select>
                                    <br> @error('estate')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">House No. <small>(Optional)</small></label>
                                    <input type="text" value="{{old('houseNo')}}" placeholder="House No." name="houseNo" class="form-control">
                                    <br> @error('houseNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Shop No. <small>(Optional)</small></label>
                                    <input type="text" value="{{old('shopNo')}}" placeholder="Shop No." name="shopNo" class="form-control">
                                    <br> @error('shopNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Plot No. <small>(Optional)</small></label>
                                    <input type="text" value="{{old('plotNo')}}" placeholder="Plot No." name="plotNo" class="form-control">
                                    <br> @error('plotNo')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Office Rooms <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Office Rooms" name="noOfOfficeRooms" id="noOfOfficeRooms" value="{{old('noOfOfficeRooms')}}" class="form-control">
                                    <br> @error('noOfOfficeRooms')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Office/Shop En suite with toilet/bathroom</label>
                                    <select name="officeShopEnsuite" id="officeShopEnsuite" class="form-control">
                                        <option value="0">None</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                    <br> @error('officeShopEnsuite')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Shops <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Shops" name="noOfShops" id="noOfShops" value="{{old('noOfShops')}}" class="form-control">
                                    <br> @error('noOfShops')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Building Type<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="What kind of building is this?" required  class="form-control select2" name="buildingType">
                                        @foreach($buildingTypes as $key=> $bType)
                                            <option value="{{$bType->bt_id}}" {{ $key == 0 ? 'selected' : null }}>{{ $bType->bt_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('buildingType')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Total Number of Bedrooms <small>(Optional)</small></label>
                                    <input type="number" data-parsley-required-message="Indicate the number of bedrooms this property has" required  placeholder="Total Number of Bedrooms" name="totalBedrooms" id="totalBedrooms" value="{{old('totalBedrooms')}}" class="form-control">
                                    <br> @error('totalBedrooms')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">With BQ<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="Does this property has BQ? Choose the option that best describes it." required  class="form-control select2" name="withBQ">
                                        @foreach($bqOptions as $key => $option)
                                            <option value="{{$option->bqo_id}}" {{ $key == 0 ? 'selected' : null }}>{{ $option->bqo_name ?? '' }}</option>
                                        @endforeach

                                    </select>
                                    <br> @error('withBQ')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for=""> Number of Floors <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Floors" name="noOfFloors" id="noOfFloors" value="{{old('noOfFloors')}}" class="form-control">
                                    <br> @error('noOfFloors')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Toilets/bathrooms <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Toilets/bathrooms" name="noOfToilets" id="noOfToilets" value="{{old('noOfToilets')}}" class="form-control">
                                    <br> @error('noOfToilets')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Car Parking Space <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Car Parking Space" name="noOfCarParking" id="noOfCarParking" value="{{old('noOfCarParking')}}" class="form-control">
                                    <br> @error('noOfCarParking')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Number of Units <small>(Optional)</small></label>
                                    <input type="number" placeholder="Number of Units" name="noOfUnits" id="noOfUnits" value="{{old('noOfUnits')}}" class="form-control">
                                    <br> @error('noOfUnits')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" step="0.01" data-parsley-required-message="Certainly this is not intended to be sold for FREE. Enter the price" required  placeholder="Price" name="price" id="price" value="{{old('price')}}" class="form-control">
                                    <br> @error('price')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Property Condition<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="What's the condition of this property?" required  class="form-control select2" name="propertyCondition">
                                        <option disabled selected>Select condition </option>
                                        <option value="1">Good</option>
                                        <option value="2">Under Repair</option>
                                        <option value="3">Bad</option>
                                        <option value="4">Fair</option>

                                    </select>
                                    <br> @error('propertyCondition')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-6">
                                <div class="form-group">
                                    <label for="">Construction Stage<sup class="text-danger">*</sup></label>
                                    <select data-parsley-required-message="At what stage of construction are you?" required  class="form-control select2" name="constructionStage">
                                        @foreach($constructionStages as $key => $stage)
                                            <option value="{{$stage->cs_id}}" {{ $key == 0 ? 'selected' : null }}>{{ $stage->cs_name }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('constructionStage')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 lg-4">
                                <div class="form-group">
                                    <label for="">Land size</label>
                                    <input data-parsley-required-message="Help us indicate the land size" required  type="text" placeholder="Number of Office Rooms" name="landSize" id="landSize" value="{{old('landSize')}}" class="form-control">
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
                                            <option value="{{$account->glcode}}">{{ $account->account_name ?? '' }}</option>
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
                                            <option value="{{$title->pt_id}}" {{ $key == 0 ? 'selected' : null }}>{{ $title->pt_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <br> @error('propertyTitle')<i class="text-danger">{{$message}}</i>@enderror
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
                                        <label for="">Property Name <sup>*</sup></label>
                                        <input type="text" data-parsley-required-message="What name would you give to this property?" required  placeholder="Property Name" name="propertyName" id="propertyName" value="{{old('propertyName')}}" class="form-control">
                                        @error('propertyName') <i class="text-danger">{{$message}}</i> @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">Description</label>
                                        <div id="editor" style="height: 250px;"></div>
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
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="gen_house"  type="checkbox" id="gen_house">
                                                        <label class="form-check-label" for="gen_house">Generator House?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="fitted_wardrobe"  type="checkbox" id="fitted_wardrobe">
                                                        <label class="form-check-label" for="fitted_wardrobe">Fitted Wardrobe?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="gate_house"  type="checkbox" id="gate_house">
                                                        <label class="form-check-label" for="gate_house">Gate House?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="penthouse"  type="checkbox" id="penthouse">
                                                        <label class="form-check-label" for="penthouse">Penthouse?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="bq"  type="checkbox" id="bq">
                                                        <label class="form-check-label" for="bq">BQ?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="guest_toilet"  type="checkbox" id="guest_toilet">
                                                        <label class="form-check-label" for="guest_toilet">Guest Toilet?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" name="anteroom"  type="checkbox" id="anteroom">
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
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit <i class="bx bx-check-double ml-2"></i></button>
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
