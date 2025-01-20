
@extends('layouts.master-layout')

@section('title')
    Customers
@endsection
@section('current-page')
    Customers
@endsection
@section('extra-styles')

    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
                <div class="row" >
                    <div class="col-xl-4 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Organization</p>
                                        <h5 class="text-orange mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($organizationValue,2) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-orange" >
                                            <i class="bx bxs-briefcase"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Head Count<code>({{ number_format($leads->where('customer_type',3)->count()) }})</code></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Partnership</p>
                                        <h5 class="text-secondary mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($partnershipValue,2) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-secondary" >
                                            <i class="bx bx-pencil"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Head Count<code>({{ number_format($leads->where('customer_type',2)->count()) }})</code></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6" >
                        <div class="card" >
                            <div class="card-body" >
                                <div class="row mb-1" >
                                    <div class="col" >
                                        <p class="mb-1">Individuals</p>
                                        <h5 class="text-warning mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($individualValue,2) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0" >
                                        <div class="dash-icon text-warning" >
                                            <i class="bx bx-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Head Count<code>({{ number_format($leads->where('customer_type',1)->count() )}})</code> </span></span>
                            </div>
                        </div>
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
                            @can('can-add-customer')<a href="{{route('show-new-lead-form')}}"  class="btn btn-primary ml-2"> Create Customer <i class="bx bxs-briefcase-alt-2"></i> </a>@endcan
                        </div>

                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-15p">Name</th>
                                            <th class="wd-15p">Mobile No.</th>
                                            <th class="wd-15p"># of Properties</th>
                                            <th class="wd-15p" style="text-align: right;">Valuation({{env('APP_CURRENCY')}})</th>
                                            <th class="wd-15p">Type</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $index = 1; @endphp
                                        @foreach($leads as $lead)
                                            <tr>
                                                <td>{{$index++}}</td>
                                                <td>{{date('M d, Y', strtotime($lead->entry_date))}}</td>
                                                <td>
                                                    @if($lead->customer_type != 3)
                                                    <a href="{{route('lead-profile', $lead->slug)}}">{{$lead->first_name ?? '' }} {{$lead->middle_name ?? '' }} {{$lead->last_name ?? '' }}</a>

                                                    @else
                                                        <a href="{{route('lead-profile', $lead->slug)}}">{{$lead->company_name ?? '' }}</a>

                                                    @endif

                                                </td>
                                                <td>
                                                    @if($lead->customer_type != 3)
                                                        {{$lead->phone ?? '' }}
                                                    @else
                                                        {{$lead->company_mobile_no ?? '' }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($lead->getNumberOfProperties($lead->id) ?? 0) ?? '' }}</td>
                                                <td style="text-align: right;">{{ number_format($lead->getCustomerValuation($lead->id) ?? 0,2) }}</td>
                                                <td>
                                                    @if($lead->customer_type == 1)
                                                        <span class="badge rounded-pill bg-info"> Individual </span>
                                                    @elseif($lead->customer_type == 2)
                                                        <span class="badge rounded-pill bg-secondary"> Partnership </span>
                                                    @else
                                                        <span class="badge rounded-pill bg-primary"> Organization </span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{route('lead-profile', $lead->slug)}}"> <i class="bx bxs-user"></i> View Profile</a>
                                                        </div>
                                                    </div>
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
            </div>
        </div>
    </div>
    <div class="modal right fade" id="lead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h4 class="modal-title text-info" id="myModalLabel2">Create Customer</h4>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('leads')}}" method="post" id="leadForm">
                        @csrf
                        <div class="form-group mt-1">
                            <label for="">Date <span class="text-danger">*</span></label>
                            <input type="date" value="{{ old('date') }}" data-parsley-required-message="Enter a valid date" required name="date"  class="form-control">
                            @error('date') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">First Name <span class="text-danger">*</span></label>
                            <input type="text" data-parsley-required-message="Enter first name" required name="firstName" value="{{old('firstName')}}" placeholder="First Name" class="form-control">
                            @error('firstName') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lastName" data-parsley-required-message="Enter last name" required value="{{old('lastName')}}" placeholder="Last Name" class="form-control">
                            @error('lastName') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobileNo" data-parsley-required-message="We'll like to contact this lead. Enter phone number" required value="{{old('mobileNo')}}" placeholder="Mobile Phone Number" class="form-control">
                            @error('mobileNo') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" data-parsley-required-message="Email address is very much important. Enter email address" required value="{{old('email', 'placeholder@email.com')}}" placeholder="Email Address" class="form-control">
                            @error('email') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Occupation<span class="text-danger">*</span></label>
                            <input type="text" name="occupation" data-parsley-required-message="This field is required" required value="{{old('occupation')}}" placeholder="Occupation" class="form-control">
                            @error('occupation') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Source</label>
                            <select name="source" data-parsley-required-message="How did this person get to hear about us? Select one of the options below" required id="" class="form-control">
                                @foreach($sources as $source)
                                    <option value="{{$source->id}}">{{$source->source ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                       <div class="row">
                           <div class="col-md-6">
                               <div class="form-group mt-1">
                                   <label for="">Status</label>
                                   <select name="status" data-parsley-required-message="On what stage is this person? Kindly select..." required  class="form-control">
                                        @foreach($statuses as $status)
                                           <option value="{{$status->id}}">{{$status->status ?? ''}}</option>
                                       @endforeach
                                   </select>
                               </div>
                           </div>
                           <div class="col-md-6">
                               <div class="form-group mt-1">
                                   <label for="">Gender</label>
                                   <select name="gender" data-parsley-required-message="Against all parameters; what's this persons gender?" required class="form-control">
                                       <option value="1">Male</option>
                                       <option value="2">Female</option>
                                       <option value="3">Others</option>
                                   </select>
                               </div>
                           </div>
                       </div>
                        <div class="form-group mt-1">
                            <label for="">Address <small>(Optional)</small></label>
                            <textarea name="street" placeholder="Type address here..." style="resize: none;" class="form-control">{{old('street')}}</textarea>
                            @error('street') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <h4 class="card-title mb-1 mt-3 text-info">Next of Kin</h4>
                        <div class="form-group mt-3">
                            <label for="">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="fullName" placeholder="Full Name" value="{{ old('fullName')  }}" class="form-control">
                            @error('fullName') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Primary Phone No. <span class="text-danger">*</span></label>
                            <input type="text" name="primaryPhoneNo" placeholder="Primary Phone No." value="{{ old('primaryPhoneNo')  }}" class="form-control">
                            @error('primaryPhoneNo') <i class="text-danger">{{$message}}</i>@enderror
                            <input type="hidden" name="leadId" value="{{$lead->id}}">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Alternative Phone No. <span class="text-danger">*</span></label>
                            <input type="text" name="altPhoneNo" placeholder="Alternative Phone No." value="{{ old('altPhoneNo')  }}" class="form-control">
                            @error('altPhoneNo') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Email Address <span class="text-danger">*</span></label>
                            <input type="text" name="nextEmail" placeholder="Email Address" value="{{ old('nextEmail')  }}" class="form-control">
                            @error('nextEmail') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Relationship <span class="text-danger">*</span></label>
                            <input type="text" name="relationship" placeholder="Relationship" value="{{ old('relationship') }}" class="form-control">
                            @error('relationship') <i class="text-danger">{{$message}}</i>@enderror
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


@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script src="/js/parsley.js"></script>
    <script>
        $(document).ready(function(){
            $('#leadForm').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });
        })


    </script>
@endsection
