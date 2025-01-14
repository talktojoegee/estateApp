
@extends('layouts.master-layout')
@section('current-page')
    Leads
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                        @include('income.partial._top-navigation')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#lead" class="btn btn-primary"> Create Lead <i class="bx bxs-briefcase-alt-2"></i> </a>
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
                                            <th class="wd-15p">Email</th>
                                            <th class="wd-15p">Phone No.</th>
                                            <th class="wd-15p">Source</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $index = 1; @endphp
                                        @foreach($leads as $lead)
                                            <tr>
                                                <td>{{$index++}}</td>
                                                <td>{{date('M d, Y', strtotime($lead->created_at))}} <u class="text-info">{{date('h:ia', strtotime($lead->created_at))}}</u></td>
                                                <td>{{$lead->first_name ?? '' }} {{$lead->last_name ?? '' }}
                                                    <sup class="badge rounded-pill bg-success">{{$lead->getStatus->status ?? '' }}</sup>
                                                </td>
                                                <td>{{$lead->email ?? '' }}</td>
                                                <td>{{$lead->phone ?? '' }}</td>
                                                <td> <span class="badge rounded-pill bg-info"> {{$lead->getSource->source ?? '' }} </span> </td>
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
                    <h4 class="modal-title" id="myModalLabel2">Create Lead</h4>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('leads')}}" method="post" id="leadForm">
                        @csrf
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
                            <input type="email" name="email" data-parsley-required-message="Email address is very much important. Enter email address" required value="{{old('email')}}" placeholder="Email Address" class="form-control">
                            @error('email') <i class="text-danger">{{$message}}</i>@enderror
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
                                   </select>
                               </div>
                           </div>
                       </div>
                        <div class="form-group mt-1">
                            <label for="">Street</label>
                            <input type="text" name="street" placeholder="Street Address" value="{{old('street')}}" class="form-control">
                            @error('street') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">City</label>
                            <input type="text" name="city" placeholder="City" value="{{old('city')}}" class="form-control">
                            @error('city') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">State</label>
                            <input type="text" name="state" placeholder="State" value="{{old('state')}}" class="form-control">
                            @error('state') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Postal Code</label>
                            <input type="text" name="code" placeholder="City" value="{{old('code')}}" class="form-control">
                            @error('code') <i class="text-danger">{{$message}}</i>@enderror
                        </div>

                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit"  class="btn btn-primary  waves-effect waves-light">Create Lead <i class="bx bxs-right-arrow"></i> </button>
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
