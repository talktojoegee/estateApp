
@extends('layouts.master-layout')

@section('title')
    Add New Customer
@endsection
@section('current-page')
    Add New Customer
@endsection
@section('extra-styles')

    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">

                    <div class="card-body">
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @include('followup.partial._top-navigation')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <div class="dropdown mt-4 mt-sm-0">
                                <a href="#" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Bulk Action <i class="bx bx-upload"></i>
                                </a>
                                <div class="dropdown-menu" style="">
                                    @can('can-import-customers')<a class="dropdown-item" href="{{ route("bulk-import-leads") }}">Bulk Import Customers</a>@endcan
                                    @can('can-approve-decline-customer-import') <a class="dropdown-item" href="{{ route('manage-bulk-lead-list') }}">Manage List</a> @endcan
                                </div>
                            </div>
                            @can('can-add-customer')<a href="{{route('leads')}}"  class="btn btn-primary ml-2"> Manage Customers <i class="bx bx-list-plus"></i> </a>@endcan
                        </div>

                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="modal-header">Add New Customer</div>
                                <form autocomplete="off" action="{{route('leads')}}" method="post" id="leadForm">
                                    @csrf
                                   <div class="row mt-4">
                                       <div class="col-md-6">
                                           <div class="form-group mt-1">
                                               <label for="">Type <span class="text-danger">*</span></label>
                                               <select name="customerType" id="customerType" class="form-control">
                                                   <option value="1">Individual</option>
                                                   <option value="2">Partnership</option>
                                                   <option value="3">Organization</option>
                                               </select>
                                               @error('customerType') <i class="text-danger">{{$message}}</i>@enderror
                                           </div>

                                           <div class="form-group mt-1">
                                               <label for="">Date <span class="text-danger">*</span></label>
                                               <input type="date" value="{{ old('date') }}" data-parsley-required-message="Enter a valid date"    name="date"  class="form-control">
                                               @error('date') <i class="text-danger">{{$message}}</i>@enderror
                                           </div>
                                       </div>

                                       <div class="col-md-6 individual">
                                           <div class="form-group mt-1">
                                               <label for="">First Name <span class="text-danger">*</span></label>
                                               <input type="text" data-parsley-  -message="Enter first name"    name="firstName" value="{{old('firstName')}}" placeholder="First Name" class="form-control">
                                               @error('firstName') <i class="text-danger">{{$message}}</i>@enderror
                                           </div>

                                           <div class="form-group mt-1">
                                               <label for="">Last Name <span class="text-danger">*</span></label>
                                               <input type="text" name="lastName" data-parsley-required-message="Enter last name"    value="{{old('lastName')}}" placeholder="Last Name" class="form-control">
                                               @error('lastName') <i class="text-danger">{{$message}}</i>@enderror
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row individual">
                                        <div class="col-md-6">
                                            <div class="form-group mt-1">
                                                <label for="">Phone Number <span class="text-danger">*</span></label>
                                                <input type="text" name="mobileNo" data-parsley-required-message="We'll like to contact this lead. Enter phone number"    value="{{old('mobileNo')}}" placeholder="Mobile Phone Number" class="form-control">
                                                @error('mobileNo') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>

                                            <div class="form-group mt-1">
                                                <label for="">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email" data-parsley-required-message="Email address is very much important. Enter email address"    value="{{old('email', 'placeholder@email.com')}}" placeholder="Email Address" class="form-control">
                                                @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-1">
                                                <label for="">Occupation<span class="text-danger">*</span></label>
                                                <input type="text" name="occupation" data-parsley-required-message="This field is required"    value="{{old('occupation')}}" placeholder="Occupation" class="form-control">
                                                @error('occupation') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group mt-1">
                                                <label for="">Source</label>
                                                <select name="source" data-parsley-required-message="How did this person get to hear about us? Select one of the options below"    id="" class="form-control">
                                                    @foreach($sources as $source)
                                                        <option value="{{$source->id}}">{{$source->source ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row individual">
                                        <div class="col-md-6">
                                            <div class="form-group mt-1">
                                                <label for="">Status</label>
                                                <select name="status" data-parsley-required-message="On what stage is this person? Kindly select..."     class="form-control">
                                                    @foreach($statuses as $status)
                                                        <option value="{{$status->id}}">{{$status->status ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-1">
                                                <label for="">Gender</label>
                                                <select name="gender" data-parsley-required-message="Against all parameters; what's this persons gender?"    class="form-control">
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                    <option value="3">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group individual mt-1">
                                        <label for="">Address <small>(Optional)</small></label>
                                        <textarea name="street" placeholder="Type address here..." style="resize: none;" class="form-control">{{old('street')}}</textarea>
                                        @error('street') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <h4 class="card-title mb-1 mt-3 text-info individual">Next of Kin</h4>
                                    <div class="form-group mt-3 individual">
                                        <label for="">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="fullName" placeholder="Full Name" value="{{ old('fullName')  }}" class="form-control">
                                        @error('fullName') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3 individual">
                                        <label for="">Primary Phone No. <span class="text-danger">*</span></label>
                                        <input type="text" name="primaryPhoneNo" placeholder="Primary Phone No." value="{{ old('primaryPhoneNo')  }}" class="form-control">
                                        @error('primaryPhoneNo') <i class="text-danger">{{$message}}</i>@enderror

                                    </div>
                                    <div class="form-group mt-3 individual">
                                        <label for="">Alternative Phone No. <span class="text-danger">*</span></label>
                                        <input type="text" name="altPhoneNo" placeholder="Alternative Phone No." value="{{ old('altPhoneNo')  }}" class="form-control">
                                        @error('altPhoneNo') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3 individual">
                                        <label for="">Email Address <span class="text-danger">*</span></label>
                                        <input type="text" name="nextEmail" placeholder="Email Address" value="{{ old('nextEmail')  }}" class="form-control">
                                        @error('nextEmail') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3 individual">
                                        <label for="">Relationship <span class="text-danger">*</span></label>
                                        <input type="text" name="relationship" placeholder="Relationship" value="{{ old('relationship') }}" class="form-control">
                                        @error('relationship') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>

                                    <div class="row mt-4 partnership">
                                        <div id="dynamicArea">
                                            <div class="next-of-kin-section" id="nextOfKinContainer">
                                                <h6 class="mt-4 text-info text-uppercase">Partnership</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="fullName" class="form-label">Full Name</label>
                                                            <input type="text" class="form-control"  name="partnerFullName[]" placeholder="Enter your full name"   >
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email Address</label>
                                                            <input type="email" class="form-control"  name="partnerEmail[]" placeholder="Enter your email"   >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="mobile" class="form-label">Mobile Number</label>
                                                            <input type="tel" class="form-control"  name="partnerMobileNo[]" placeholder="Enter your mobile number"   >
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="address" class="form-label">Address</label>
                                                            <textarea class="form-control"  name="partnerAddress[]" rows="2" placeholder="Enter your address"   ></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                               <div class="row">
                                                   <div class="col-md-12">
                                                       <h6 class="mt-4 text-info text-uppercase">Next of Kin</h6>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <div class="mb-3">
                                                           <label for="kinFullName" class="form-label">Full Name</label>
                                                           <input type="text" class="form-control"  name="partnerKinFullName[]" placeholder="Enter next of kin's full name"   >
                                                       </div>
                                                       <div class="mb-3">
                                                           <label for="kinMobile" class="form-label">Mobile Number</label>
                                                           <input type="tel" class="form-control"  name="partnerKinMobile[]" placeholder="Enter next of kin's mobile number"   >
                                                       </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <div class="mb-3">
                                                           <label for="kinEmail" class="form-label">Email Address</label>
                                                           <input type="email" class="form-control"  name="partnerKinEmail[]" placeholder="Enter next of kin's email"   >
                                                       </div>
                                                       <div class="mb-3">
                                                           <label for="relationship" class="form-label">Relationship</label>
                                                           <input type="text" class="form-control"  name="partnerKinRelationship[]" placeholder="Enter relationship with next of kin"   >
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-primary" id="addKin">Add Next of Kin</button>
                                            </div>
                                        </div>





                                    </div>

                                    <div class="row organization">
                                        <h4 class="card-title text-uppercase mb-0 mt-2 text-info ">Organization Details</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mt-3 organization">
                                                    <label for="">Organization Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="organizationName" placeholder="Organization Name" value="{{ old('organizationName')  }}" class="form-control">
                                                    @error('organizationName') <i class="text-danger">{{$message}}</i>@enderror
                                                </div>
                                                <div class="form-group mt-3 organization">
                                                    <label for=""> Email <span class="text-danger">*</span></label>
                                                    <input type="text" name="organizationEmail" placeholder="Organization Email" value="{{ old('organizationEmail')  }}" class="form-control">
                                                    @error('organizationEmail') <i class="text-danger">{{$message}}</i>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mt-3 organization">
                                                    <label for="">Mobile No. <span class="text-danger">*</span></label>
                                                    <input type="text" name="organizationMobileNo" placeholder="Organization Mobile No." value="{{ old('organizationMobileNo')  }}" class="form-control">
                                                    @error('organizationMobileNo') <i class="text-danger">{{$message}}</i>@enderror
                                                </div>
                                                <div class="form-group mt-3 organization">
                                                    <label for="">Address <span class="text-danger">*</span></label>
                                                    <input type="text" name="organizationAddress" placeholder="Organization Address" value="{{ old('organizationAddress')  }}" class="form-control">
                                                    @error('organizationAddress') <i class="text-danger">{{$message}}</i>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="card-title text-uppercase mb-0 mt-2 text-info ">Resource Person</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Full Name <sup style="color: red">*</sup></label>
                                                    <input type="text" name="resourcePersonFullName" placeholder="Full Name" value="{{ old('resourcePersonFullName') }}" class="form-control">
                                                    @error('resourcePersonFullName') <i>{{ $message }}</i> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Mobile No. <sup style="color: red">*</sup></label>
                                                    <input type="text" name="resourcePersonMobileNo" placeholder="Mobile No." value="{{ old('resourcePersonMobileNo') }}" class="form-control">
                                                    @error('resourcePersonMobileNo') <i>{{ $message }}</i> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Email. <sup style="color: red">*</sup></label>
                                                    <input type="text" name="resourcePersonEmail" placeholder="Email Address" value="{{ old('resourcePersonEmail') }}" class="form-control">
                                                    @error('resourcePersonEmail') <i>{{ $message }}</i> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group d-flex justify-content-center mt-3">
                                        <div class="btn-group">
                                            <button type="submit"  class="btn btn-primary  waves-effect waves-light">Submit <i class="bx bxs-right-arrow"></i> </button>
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



@endsection

@section('extra-scripts')

    <script src="/js/parsley.js"></script>
    <script>
        $(document).ready(function(){
            $('.individual').show();
            $('.organization').hide();
            $('.partnership').hide();
            $('#customerType').on('change', function(e){
                e.preventDefault();
                let val = $(this).val();
                switch(parseInt(val)){
                    case 1:
                        $('.individual').show();
                        $('.organization').hide();
                        $('.partnership').hide();
                    break;
                    case 2:
                        $('.individual').hide();
                        $('.organization').hide();
                        $('.partnership').show();
                    break;
                    case 3:
                        $('.individual').hide();
                        $('.organization').show();
                        $('.partnership').hide();
                    break;

                }
            });




            let kinCount = 1;

            $('#addKin').click(function () {
                kinCount++;
                const kinTemplate = `
                 <div class="next-of-kin-section" data-id="${kinCount}">

                    <h6 class="mt-4 text-info text-uppercase">Partner #${kinCount}</h6>
                    <button type="button" class="btn btn-danger btn-sm removeKin">Remove</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control"  name="partnerFullName[]" placeholder="Enter your full name"   >
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control"  name="partnerEmail[]" placeholder="Enter your email"   >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control"  name="partnerMobileNo[]" placeholder="Enter your mobile number"   >
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control"  name="partnerAddress[]" rows="2" placeholder="Enter your address"   ></textarea>
                            </div>
                        </div>
                    </div>

                   <div class="row">
                       <div class="col-md-12">
                           <h6 class="mt-4 text-info text-uppercase">Next of Kin for Partner ${kinCount}</h6>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="kinFullName" class="form-label">Full Name</label>
                               <input type="text" class="form-control"  name="partnerKinFullName[]" placeholder="Enter next of kin's full name"   >
                           </div>
                           <div class="mb-3">
                               <label for="kinMobile" class="form-label">Mobile Number</label>
                               <input type="tel" class="form-control"  name="partnerKinMobile[]" placeholder="Enter next of kin's mobile number"   >
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="kinEmail" class="form-label">Email Address</label>
                               <input type="email" class="form-control"  name="partnerKinEmail[]" placeholder="Enter next of kin's email"   >
                           </div>
                           <div class="mb-3">
                               <label for="relationship" class="form-label">Relationship</label>
                               <input type="text" class="form-control"  name="partnerKinRelationship[]" placeholder="Enter relationship with next of kin"   >
                           </div>
                       </div>
                   </div>
                </div>`;
                $('#dynamicArea .next-of-kin-section:last').after(kinTemplate);
            });

            $(document).on('click', '.removeKin', function (e) {
                $(this).closest('.next-of-kin-section').remove();
                updateKinTitles();
            });

            function updateKinTitles() {
                $('.next-of-kin-section').each(function (index) {
                    $(this).attr('data-id', index + 1);
                    $(this).find('h6').text(`Next of Kin ${index + 1}`);
                });
                kinCount = $('.next-of-kin-section').length;
            }
/*
            $('#dynamicForm').on('submit', function (e) {
                e.preventDefault();
                alert('Form submitted successfully!');
            });*/


          /*  $('#leadForm').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });*/
        })


    </script>
@endsection