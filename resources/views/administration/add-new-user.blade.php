@extends('layouts.master-layout')

@section('title')
    Add New Employee
@endsection
@section('current-page')
    Add New Employee
@endsection
@section('extra-styles')
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
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
                                <p><strong>NOTE:</strong> All fields marked with asterisk <span class="text-danger">(*)</span> are required</p>
                                <div class="modal-header mb-3" >
                                    <h6 class="modal-title text-uppercase text-white" id="myModalLabel2">Add New Employee</h6>
                                </div>
                                <form autocomplete="off" action="{{route('add-new-user')}}" enctype="multipart/form-data" method="post" id="addNewUser" data-parsley-validate="">
                                    @csrf

                                    <div class="row mt-3">
                                         <div class="col-md-12 col-sm-12 col-lg-12">

                                             <div class="row">
                                                 <div class="row">
                                                     <div class="col-md-12">
                                                         <h6 class="text-uppercase modal-header text-white">Personal Info</h6>
                                                     </div>
                                                 </div>
                                                 <div class="row mb-4">
                                                     <div class="col-md-3 col-sm-3 col-lg-3 align-content-center">
                                                         <img class="rounded me-2" alt="200x200" width="200" src="/assets/images/small/img-4.jpg" data-holder-rendered="true">
                                                         <p>Profile Picture</p>
                                                         <input type="file" required name="avatar"  class="form-control-file mt-2">
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
                                                         <input type="text" value="{{old('firstName')}}" name="firstName" data-parsley-required-message="What is employee's first name?" placeholder="First Name" class="form-control" required="">
                                                         @error('firstName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Surname <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('lastName')}}" name="lastName" required placeholder="Surname" data-parsley-required-message="Not forgetting surname. What is employee's surname?" class="form-control">
                                                         @error('lastName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Middle Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('otherNames')}}" name="otherNames" placeholder="Middle Name"  class="form-control">
                                                         @error('otherNames') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Mobile Number <span class="text-danger">*</span></label>
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
                                                         <input type="text" id="datepicker" value="{{date('Y-m-d', strtotime(now()))}}" name="dob" required placeholder="Date of Birth" data-parsley-required-message="Enter date of birth" class="form-control">
                                                         @error('dob') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Position Title <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('occupation')}}"  data-parsley-required-message="Enter Position Title" required="" name="occupation" placeholder="Enter Position Title" class="form-control">
                                                         @error('occupation') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Nationality <span class="text-danger">*</span></label>
                                                         <select name="nationality" id="" data-parsley-required-message="Select nationality" class="form-control select2">
                                                            @foreach($countries as $country)
                                                                 <option {{ $country->id == 161 ? 'selected' : null }} value="{{$country->id}}">{{$country->name ?? '' }}</option>
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
                                                 <div class="col-md-4 col-sm-4 col-lg-4 mb-3 mt-3">
                                                     <div class="form-group">
                                                         <label for="">Gender <span class="text-danger">*</span>  </label>
                                                         <select name="gender" id="" class="form-control">
                                                             <option value="1">Male</option>
                                                             <option value="2">Female</option>
                                                         </select>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-4 col-sm-12 col-lg-4 mt-3">
                                                     <div class="form-group mt-1">
                                                         <label for="">Start Date <span class="text-danger">*</span></label>
                                                         <input type="text" id="datepicker2" placeholder="Start Date" value="{{date('Y-m-d', strtotime(now()))}}" name="startDate" required placeholder="Start Date" data-parsley-required-message="Enter start date" class="form-control">
                                                         @error('startDate') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-4 col-sm-12 col-lg-4 mt-3">
                                                     <div class="form-group mt-1">
                                                         <label for="">Religion <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('religion')}}"  data-parsley-required-message="Enter Religion" required="" name="religion" placeholder="Enter Religion" class="form-control">
                                                         @error('religion') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="row">
                                                     <div class="col-md-6 col-sm-12 col-lg-6 mt-3">
                                                         <div class="form-group mt-1">
                                                             <label for=""> State of Origin <span class="text-danger">*</span></label>
                                                             <select name="stateOrigin" id="stateOrigin" data-parsley-required-message="Select State of Origin" class="form-control select2">
                                                                 @foreach($states as $state)
                                                                     <option value="{{$state->id}}">{{$state->name ?? '' }}</option>
                                                                 @endforeach
                                                             </select>
                                                             @error('stateOrigin') <i class="text-danger">{{$message}}</i>@enderror
                                                         </div>
                                                     </div>
                                                     <div class="col-md-6 col-sm-12 col-lg-6 mt-3">
                                                         <div class="form-group mt-1">
                                                             <label for="">Local Govt. Area <span class="text-danger">*</span></label>
                                                             <div id="lgaPreview">

                                                             </div>

                                                             @error('lga') <i class="text-danger">{{$message}}</i>@enderror
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group mt-1">
                                                         <label for="">Residential Address <span class="text-danger">*</span></label>
                                                         <textarea name="presentAddress" data-parsley-required-message="Enter Residential Address" required="" id="presentAddress" style="resize: none;"
                                                                   class="form-control" placeholder="Type residential address here...">{{old('presentAddress')}}</textarea>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group mt-1">
                                                         <label for="">Permanent Home Address <span class="text-danger">*</span></label>
                                                         <textarea name="homeAddress" data-parsley-required-message="Enter Permanent Home Address" required="" id="homeAddress" style="resize: none;"
                                                                   class="form-control" placeholder="Type permanent home address here...">{{old('homeAddress')}}</textarea>
                                                     </div>
                                                 </div>
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
                                                 <div class="row mt-3">
                                                     <div class="col-md-12">
                                                         <h6 class="text-uppercase modal-header text-white">Next of Kin</h6>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Title <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('nextTitle')}}" name="nextTitle" placeholder="Title"  class="form-control">
                                                         @error('nextTitle') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">First Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('nextFirstName')}}" name="nextFirstName" data-parsley-required-message="Enter First name" placeholder="First Name" class="form-control" required="">
                                                         @error('nextFirstName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Surname <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('nextSurname')}}" name="nextSurname" required placeholder="Surname" data-parsley-required-message="Enter surname" class="form-control">
                                                         @error('nextSurname') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Middle Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('nextMiddleName')}}" name="nextMiddleName" placeholder="Middle Name"  class="form-control">
                                                         @error('nextMiddleName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for=""> Mobile Number <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('nextMobileNo')}}" name="nextMobileNo" required placeholder="Mobile Phone Number" data-parsley-required-message="Enter mobile number" class="form-control">
                                                         <input type="hidden" name="userType" value="1">
                                                         @error('nextMobileNo') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Relationship <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('relationship')}}"  data-parsley-required-message="Enter Relationship" required="" name="relationship" placeholder="Relationship"  class="form-control">
                                                         @error('relationship') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Occupation <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('nextOccupation')}}"  data-parsley-required-message="Enter Occupation" required="" name="nextOccupation" placeholder="Enter Occupation" class="form-control">
                                                         @error('nextOccupation') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Mother's Maiden Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('mothersMaidenName')}}"  data-parsley-required-message="Enter Mother's Maiden Name" required="" name="mothersMaidenName" placeholder="Enter Mother's Maiden Name" class="form-control">
                                                         @error('mothersMaidenName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">National ID/Passport/Driver's License Number <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('meansOfID')}}"  data-parsley-required-message="National ID/Passport/Driver's License Number" required="" name="meansOfID" placeholder="National ID/Passport/Driver's License Number" class="form-control">
                                                         @error('meansOfID') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>


                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group mt-1">
                                                         <label for="">Home Address <span class="text-danger">*</span></label>
                                                         <textarea data-parsley-required-message="Enter Home Address" required="" name="homeAddress" id="homeAddress" style="resize: none;"
                                                                   class="form-control" placeholder="Type home address here...">{{old('homeAddress')}}</textarea>
                                                         @error('homeAddress') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group mt-1">
                                                         <label for="">Office Address <span class="text-danger">*</span></label>
                                                         <textarea data-parsley-required-message="Enter Office Address" required="" name="officeAddress" id="officeAddress" style="resize: none;"
                                                                   class="form-control" placeholder="Type office address here...">{{old('officeAddress')}}</textarea>
                                                         @error('officeAddress') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group mt-1">
                                                         <label for="">Permanent Home Address <span class="text-danger">*</span></label>
                                                         <textarea name="nextPermanentHomeAddress" data-parsley-required-message="Enter Permanent Home Address" required="" id="nextPermanentHomeAddress" style="resize: none;"
                                                                   class="form-control" placeholder="Type permanent home address here...">{{old('nextPermanentHomeAddress')}}</textarea>
                                                     </div>
                                                 </div>
                                                 <div class="row mt-3">
                                                     <div class="col-md-12">
                                                         <h6 class="text-uppercase modal-header text-white">Bank Details</h6>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Bank Name <span class="text-danger">*</span></label>
                                                         <input type="text" data-parsley-required-message="Enter Bank Name" required="" value="{{old('bankName')}}" name="bankName" placeholder="Bank Name"  class="form-control">
                                                         @error('bankName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Branch </label>
                                                         <input type="text" value="{{old('branch')}}" name="branch"  placeholder="Branch" class="form-control" >
                                                         @error('branch') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Account Name <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('accountName')}}" name="accountName" required placeholder="Account Name" data-parsley-required-message="Account Name" class="form-control">
                                                         @error('accountName') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Account Number <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('accountNumber')}}" name="accountNumber" required placeholder="Account Number" data-parsley-required-message="Account Number" class="form-control">
                                                         @error('accountNumber') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">TAX Identification No. </label>
                                                         <input type="text" value="{{old('taxID')}}" name="taxID"  placeholder="TAX Identification Number" data-parsley-required-message="TAX Identification Number" class="form-control">
                                                         @error('taxID') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Retirement Savings Account <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('retirementSavings')}}" name="retirementSavings" placeholder="Retirement Savings Account"  class="form-control">
                                                         @error('retirementSavings') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6 col-sm-12 col-lg-6">
                                                     <div class="form-group mt-1">
                                                         <label for="">Pension Fund Administrator <span class="text-danger">*</span></label>
                                                         <input type="text" value="{{old('pensionFund')}}" name="pensionFund" placeholder="Pension Fund Administrator"  class="form-control">
                                                         @error('pensionFund') <i class="text-danger">{{$message}}</i>@enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-md-12 col-sm-12 col-lg-12">
                                                     <div class="form-group d-flex justify-content-center mt-3">
                                                         <div class="btn-group">
                                                             <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit <i class="bx bx-check-double"></i> </button>
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
    <script src="/assets/js/axios.min.js"></script>
    <script src="/js/parsley.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function(){
            $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
            $( "#datepicker2" ).datepicker({ dateFormat: 'dd-mm-yy' });
            $('#addNewUser').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });
        });

        $('#stateOrigin').on('change', function(e){
            e.preventDefault();
            let stateId =  $(this).val();
            axios.post("{{route('get-lgas')}}",{stateId: stateId})
                .then(res=>{
                    $('#lgaPreview').html(res.data);
                });
        });
    </script>

@endsection
