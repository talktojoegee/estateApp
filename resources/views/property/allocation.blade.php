@extends('layouts.master-layout')
@section('title')
    Property Allocation
@endsection
@section('current-page')
    Property Allocation
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
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('success') !!}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('error') !!}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <form action="{{ route('property-allocation') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">Property Allocation
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="">Estate <sup class="text-danger">*</sup></label>
                                        <select id="estate" data-parsley-required-message="Select the estate to which this property belongs to" required  class="form-control p-3 select2" name="estate">
                                            <option disabled selected>Select Estate</option>
                                            @foreach($estates as $estate)
                                                <option value="{{$estate->e_id}}">{{ $estate->e_name ?? '' }}</option>
                                            @endforeach

                                        </select>
                                        <br> @error('estate')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <p class="mt-3" id="notice"><strong > <code>Guide:</code> </strong> Carefully go through the list of properties shown below to allocate customers to properties accordingly. Do not forget the level of allocation.
                                        Feel free to skip properties that shouldn't be allocated or were already allocated to someone.</p>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="">Status <sup class="text-danger">*</sup> </label>
                                        <select name="status" id="" class="form-control">
                                            <option disabled selected>Select </option>
                                            <option value="1">Rented</option>
                                            <option value="2">Sold</option>
                                        </select>
                                        <br> @error('status')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 d-flex justify-content-end">
                                    <div class="form-group">
                                        <button class="btn btn-primary " type="submit">Save changes <i class="bx bx-check-double"></i> </button>
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                               <div class="col-sm-12 col-md-12 lg-12" id="appendList">

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
    <script src="/js/axios.min.js"></script>
    <script type="text/javascript" src="{{asset('/assets/js/notify.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#notice').hide();

            $('#estate').on('change',function(e){
                e.preventDefault();
                axios.post("{{route('property-list')}}",{estate:$(this).val()})
                    .then(response=>{
                        $('#appendList').html(response.data);
                        $(".select2").select2({
                            placeholder: "Select product or service"
                        });
                        $(".select2").last().next().next().remove();
                        $('#notice').show();
                        //$.notify(response.data.message, "success");
                        //$('#saveThemeChangesBtn').text("Save changes");
                    })
                    .catch(error=>{
                        $('#notice').hide();
                        //console.log(error)
                        $.notify("Whoops! Something went wrong. Try again", "error");
                    });
            });
        });
    </script>
@endsection
