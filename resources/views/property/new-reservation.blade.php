@extends('layouts.master-layout')
@section('title')
    New Reservation Request
@endsection
@section('current-page')
    New Reservation Request
@endsection
@section('extra-styles')
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">New Reservation Request
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-4 col-md-4 lg-4">
                                <form action="{{ route('property-reservation') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Property Code <sup style="color: #ff0000;">*</sup> </label>
                                                <input type="text" id="propertyCode" placeholder="Enter Property Code" name="propertyCode" class="form-control">
                                                @error('propertyCode')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 lg-12 mt-3">
                                            <div class="form-group">
                                                <label for="">Customer<sup class="text-danger">*</sup></label>
                                                <select data-parsley-required-message="Select customer" required  class="form-control p-3 select2" name="customer">
                                                    <option disabled selected>Select customer</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">{{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                <br> @error('customer')<i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 lg-12 mt-3">
                                            <div class="form-group">
                                                <label for="">Note<small class="text-danger">(Optional)</small></label>
                                                <textarea style="resize: none;" name="note" id="note" placeholder="Type note here..."
                                                          class="form-control">{{old('note')}}</textarea>
                                                <br> @error('note')<i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit <i class="bx bx-check-double ml-2"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-8 col-md-8 lg-8" id="propertyPreview">

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/assets/js/axios.min.js"></script>
    <script>
        $(document).ready(function(){

            $('#propertyCode').on('blur', function(e){
                e.preventDefault();
                let propertyCode =  $(this).val();
                axios.post("{{route('get-property')}}",{propertyCode: propertyCode})
                    .then(res=>{
                        $('#propertyPreview').html(res.data);
                    });
            });
        });
    </script>
@endsection
