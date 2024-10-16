@extends('layouts.master-layout')
@section('current-page')
    Add New Employee
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
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
        @if($errors->any())
            <div class="row" role="alert">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <a href="{{ route('pastors') }}"  class="btn btn-secondary  mb-3"><i class="bx bx-arrow-back"></i> Go Back  </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lx-12">
                                <div class="modal-header mb-3" >
                                    <h6 class="modal-title text-uppercase text-info" id="myModalLabel2">Add New Employee</h6>
                                </div>
                                <form autocomplete="off" action="{{route('add-new-user')}}" enctype="multipart/form-data" method="post" id="addNewUser" data-parsley-validate="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-lg-3 align-content-center">
                                            <img class="rounded me-2" alt="200x200" width="200" src="/assets/images/small/img-4.jpg" data-holder-rendered="true">
                                            <p>Profile Picture</p>
                                            <input type="file" name="avatar"  class="form-control-file mt-2">
                                        </div>
                                         <div class="col-md-9 col-sm-9 col-lg-9">

                                             <div class="row">
                                                 <div class="row">
                                                     <div class="col-md-12">
                                                         <h6 class="text-uppercase text-primary">Personal Info</h6>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Title <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('title')}}" name="title" placeholder="Title"  class="form-control">
                                                         @error('title') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">First Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('firstName')}}" name="firstName" data-parsley-required-message="What's the practitioner's first name?" placeholder="First Name" class="form-control" required="">
                                                         @error('firstName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Last Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('lastName')}}" name="lastName" required placeholder="Last Name" data-parsley-required-message="Not forgetting last name. What's the practitioner's last name?" class="form-control">
                                                         @error('lastName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Other Names <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('otherNames')}}" name="otherNames" placeholder="Other Names"  class="form-control">
                                                         @error('otherNames') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Phone Number <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('mobileNo')}}" name="mobileNo" required placeholder="Mobile Phone Number" data-parsley-required-message="Enter phone number" class="form-control">
                                                         <input type="hidden" name="userType" value="1">
                                                         @error('mobileNo') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Email Address <span class="text-danger">*</span></label>
                                                         <input type="email" value="{{old('email')}}" data-parsley-trigger="change" data-parsley-required-message="Enter a valid email address" required="" name="email" placeholder="Email Address" class="form-control">
                                                         @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Date of Birth <span class="text-danger">*</span></label>
                                                         <input type="date" value="{{date('Y-m-d', strtotime(now()))}}" name="dob" required placeholder="Date of Birth" data-parsley-required-message="Enter date of birth" class="form-control">
                                                         @error('dob') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Occupation <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('occupation')}}"  data-parsley-required-message="Enter occupation" required="" name="occupation" placeholder="Enter Occupation" class="form-control">
                                                         @error('occupation') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Nationality <span class="text-danger">*</span></label>
                                                         <select name="nationality" id="" data-parsley-required-message="Select nationality" class="form-control select2">
                                                            @foreach($countries as $country)
                                                                 <option value="{{$country->id}}">{{$country->name ?? '' }}</option>
                                                             @endforeach
                                                         </select>
                                                         @error('nationality') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Marital Status <span class="text-danger">*</span></label>
                                                         <select name="maritalStatus" data-parsley-required-message="Select marital status" id="maritalStatus" class="form-control select2">
                                                             @foreach($maritalstatus as $status)
                                                                 <option value="{{$status->ms_id}}">{{$status->ms_name ?? '' }}</option>
                                                             @endforeach
                                                         </select>
                                                         @error('maritalStatus') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                <!-- <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-check form-switch mt-3">
                                                         <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="pastor" checked="">
                                                         <label class="form-check-label" for="flexSwitchCheckChecked">Is this person a pastor?</label>
                                                     </div>
                                                 </div> -->
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-check form-switch mt-3">
                                                         <input class="form-check-input" type="checkbox" id="genderSwitchCheck" name="gender" checked="">
                                                         <label class="form-check-label" for="genderSwitchCheck">Male?</label>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group mt-1">
                                                         <label for="">Present Address <span class="text-danger">*</span></label>
                                                         <textarea name="presentAddress" id="presentAddress" style="resize: none;"
                                                                   class="form-control" placeholder="Type present address here...">{{old('presentAddress')}}</textarea>
                                                     </div>
                                                 </div>
                                                 {{--<div class="row mt-3">
                                                     <div class="col-md-12">
                                                         <h6 class="text-uppercase text-primary">Location</h6>
                                                     </div>
                                                 </div>--}}
                                                 <div class="col-md-6 col-sm-12 col-lg-6 mt-3">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Department <span class="text-danger">*</span></label>
                                                         <select name="branch" id="" data-parsley-required-message="Select department" class="form-control select2">
                                                             @foreach($branches as $branch)
                                                                 <option value="{{$branch->cb_id}}">{{$branch->cb_name ?? '' }}</option>
                                                             @endforeach
                                                         </select>
                                                         @error('branch') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6 mt-3">
                                                     <div class="form-group mt-1">
                                                         <label for="">Assign Role <span class="text-danger">*</span></label>
                                                         <select name="role" data-parsley-required-message="Select role" id="role" class="form-control select2">
                                                             @foreach($roles as $role)
                                                                 <option value="{{$role->id}}">{{$role->name ?? '' }}</option>
                                                             @endforeach
                                                         </select>
                                                         @error('role') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group d-flex justify-content-center mt-3">
                                                         <div class="btn-group">
                                                             <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit <i class="bx bxs-plus-circle"></i> </button>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase" id="myModalLabel2">Add New Administrators</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')

    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script>
        $(document).ready(function(){
            $('#addNewUser').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });
        });
    </script>
@endsection
