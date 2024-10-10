@extends('layouts.master-layout')
@section('title')
{{$user->title ?? ''}} {{$user->first_name ?? '' }}'s Profile
@endsection
@section('current-page')
    {{$user->title ?? ''}} {{$user->first_name ?? '' }}'s Profile
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/css/toastify.min.css')}}">
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row mb-10" style="margin-bottom: 70px;">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="position-relative">
                    <div class="d-flex justify-content-between align-items-center position-absolute top-90 w-100 px-2 px-md-4 mt-n4">
                        <div>
                            <img class="wd-70 rounded-circle img-thumbnail avatar-xl" src="{{url('storage/'.$user->image)}}" alt="profile">
                            <span class="h4 ms-3 text-dark">{{$user->title ?? '' }} {{$user->first_name ?? '' }}</span>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="btn-group">
                                @if($user->status == 1)
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deletePractitionerModal" class="btn btn-danger btn-sm btn-icon-text">
                                  <i class="bx bx-x-circle"></i>  Deactivate Account
                                </a>
                                @else
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deletePractitionerModal" class="btn btn-success btn-sm btn-icon-text">
                                        <i class="bx bx-check-circle"></i>  Activate Account
                                    </a>
                                @endif
                                <a href="javascript:void(0);" data-bs-target="#permissionModal" data-bs-toggle="modal" class="btn btn-secondary btn-sm btn-icon-text">
                                    <i class="bx bx-lock-alt"></i>  Access Level
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row profile-body">
        <div class="col-md-12 col-lg-12">
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
        </div>
        <div class="d-none d-md-block col-md-4 col-xl-3 left-wrapper">
            <div class="card rounded">
                <div class="">
                    <div class="modal-header">
                        <h6 class="tx-11 fw-bolder text-uppercase">Personal Info</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Full Name</label>
                        <p class="text-muted">{{$user->title ?? '' }} {{$user->first_name ?? '' }} {{$user->last_name ?? '' }} {{$user->other_names ?? '' }}</p>
                    </div>
                    <div class=" mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Mobile No.</label>
                        <p class="text-muted">{{$user->cellphone_no ?? '' }}</p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Email</label>
                        <p class="text-muted"><a href="mailto:{{$user->email ?? '#'}}">{{$user->email ?? '#'}}</a></p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Marital Status</label>
                        <p class="text-muted">{{$user->getUserMaritalStatus->ms_name ?? '' }}</p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Gender</label>
                        <p class="text-muted">{{$user->gender == 0 ? 'Female' : 'Male' }}</p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Birth Date</label>
                        <p class="text-muted">{{!is_null($user->birth_date) ? date('d M', strtotime($user->birth_date)) : '' }}</p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Nationality</label>
                        <p class="text-muted">{{$user->getUserCountry->name ?? '-'}}</p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Occupation</label>
                        <p class="text-muted">{{$user->occupation ?? '-'}}</p>
                    </div>
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Address</label>
                        <p class="text-muted">{{$user->address_1 ?? '' }}</p>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        @if(\Illuminate\Support\Facades\Auth::user()->id == $user->id)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab" aria-selected="false" tabindex="-1">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Settings</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#wallpaper" role="tab" aria-selected="false" tabindex="-1">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Wallpapers</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#log" role="tab" aria-selected="false" tabindex="-1">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Activity Log</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        @if(\Illuminate\Support\Facades\Auth::user()->id == $user->id)
                        <div class="tab-pane" id="settings1" role="tabpanel" >
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#personalDetails" aria-expanded="true" aria-controls="multiCollapseExample2">Personal Profile <i class="bx bx-user"></i> </button>
                                   @if(Auth::user()->id == $user->id) <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#security" aria-expanded="true" aria-controls="multiCollapseExample2">Security <i class="bx bx-lock"></i></button> @endif
                                </div>
                            </div>

                            <div class="multi-collapse collapse " id="personalDetails">
                                <div class="modal-header text-uppercase mt-3">Personal Details</div>
                                <form class="mt-5" autocomplete="off" action="{{route('update-person-profile')}}" enctype="multipart/form-data" method="post" id="addNewUser" data-parsley-validate="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-lg-3 align-content-center">
                                            <img class="rounded me-2" id="avatarPlaceholder" alt="200x200" width="200" src="{{url('storage/'.$user->image)}}" data-holder-rendered="true">
                                            <p>Profile Picture</p>
                                            <input type="file" name="avatar" accept="image/png, image/gif, image/jpeg" id="avatarPlaceholderHandler"  class="form-control-file mt-2">
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-lg-9">

                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for="">Title <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{old('title', $user->title,)}}" name="title" placeholder="Title"  class="form-control">
                                                        @error('title') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for="">First Name <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{old( 'firstName', $user->first_name)}}" name="firstName" data-parsley-required-message="What's the practitioner's first name?" placeholder="First Name" class="form-control" required="">
                                                        @error('firstName') <i class="text-danger">{{$message}}</i>@enderror
                                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for="">Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{old( 'lastName',$user->last_name)}}" name="lastName" required placeholder="Last Name" data-parsley-required-message="Not forgetting last name. What's the practitioner's last name?" class="form-control">
                                                        @error('lastName') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for="">Other Names <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{old( 'otherNames',$user->other_names)}}" name="otherNames" placeholder="Other Names"  class="form-control">
                                                        @error('otherNames') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for=""> Phone Number <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{old( 'mobileNo',$user->cellphone_no)}}" name="mobileNo" required placeholder="Mobile Phone Number" data-parsley-required-message="Enter phone number" class="form-control">
                                                        <input type="hidden" name="userType" value="1">
                                                        @error('mobileNo') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for="">Email Address <span class="text-danger">*</span></label>
                                                        <input type="email" disabled value="{{old( 'email',$user->email)}}" data-parsley-trigger="change" data-parsley-required-message="Enter a valid email address" required="" name="email" placeholder="Email Address" class="form-control">
                                                        @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for=""> Date of Birth <span class="text-danger">*</span></label>
                                                        <input type="date"  value="{{date('Y-m-d', strtotime($user->birth_date))}}" name="dob" required placeholder="Date of Birth" data-parsley-required-message="Enter date of birth" class="form-control">
                                                        @error('dob') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for="">Occupation <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{old( 'occupation',$user->occupation)}}"  data-parsley-required-message="Enter occupation" required="" name="occupation" placeholder="Enter Occupation" class="form-control">
                                                        @error('occupation') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-group mt-1">
                                                        <label for=""> Nationality <span class="text-danger">*</span></label>
                                                        <select name="nationality" id="" data-parsley-required-message="Select nationality" class="form-control select2">
                                                            @foreach($countries as $country)
                                                                <option {{ $country->id == $user->country_id ? 'selected' : null  }} value="{{$country->id}}">{{$country->name ?? '' }}</option>
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
                                                                <option {{ $status->id == $user->marital_status ? 'selected' : null }} value="{{$status->ms_id}}">{{$status->ms_name ?? '' }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('maritalStatus') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="type"
                                                            {$user->type == 2 ? 'checked' : null  }}>
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Is this person a director?</label>
                                                    </div>
                                                </div> -->
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" id="genderSwitchCheck" name="gender" {{$user->gender == 1 ? 'checked' : null  }}>
                                                        <label class="form-check-label" for="genderSwitchCheck">Male?</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <div class="form-group mt-1">
                                                        <label for="">Present Address <span class="text-danger">*</span></label>
                                                        <textarea name="presentAddress" id="presentAddress" style="resize: none;"
                                                                  class="form-control" placeholder="Type present address here...">{{old( 'presentAddress',$user->address_1)}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <div class="form-group d-flex justify-content-center mt-3">
                                                        <div class="btn-group">
                                                            <button type="submit" class="btn btn-primary  waves-effect waves-light" >Save changes <i class="bx bxs-check-circle"></i> </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            @if(Auth::user()->id == $user->id)
                            <div class="multi-collapse collapse" id="security">
                                <div class="modal-header text-uppercase mt-3">Change Password</div>
                                <form action="{{route('change-password')}}" method="post" enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <div class="form-group mt-4">
                                        <label for="">Current Password <span class="text-danger">*</span></label>
                                        <input type="password"  name="currentPassword" placeholder="Current Password"  class="form-control">
                                        @error('currentPassword') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" placeholder="New Password" class="form-control">
                                        @error('password') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">Re-type Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" placeholder="Re-type Password" class="form-control">
                                        @error('password_confirmation') <i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">Change Password <i class="bx bx-lock-alt"></i> </button>
                                    </div>
                                </form>
                            </div>
                            @endif


                        </div>
                            <div class="tab-pane" id="wallpaper" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                        <div class="btn-group">
                                            <button class="btn btn-primary" id="saveWallpaperChanges" type="button">Save changes <i class="bx bx-check-double"></i> </button>
                                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#wallpaperUpload">Upload Wallpaper <i class="bx bx-upload"></i> </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="height: 500px; overflow-y: scroll;" id="theme-collection">
                                    @foreach ($wallpapers as $theme)
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-check form-radio-outline form-radio-primary mb-3" >
                                                <input class="form-check-input" type="radio" name="backgroundTheme" value="{{$theme->id}}" data-background="{{$theme->filename}}" {{ $theme->id == Auth::user()->wallpaper ? "checked" : null }}>
                                                <label class="form-check-label" for="formRadio1">
                                                    {{$theme->wallpaper_name ?? ''}} @if($theme->id == Auth::user()->wallpaper) <span class="badge rounded-pill bg-danger" style="background: #FF0000 !important;">Active</span> @endif
                                                </label>
                                            </div>

                                            <div class="thumbnail">
                                                <div class="thumb" style="cursor: pointer;">
                                                    <img src="/assets/drive/wallpapers/{{$theme->filename ?? ''}}" alt="{{$theme->wallpaper_name ?? ''}}" class="img-fluid img-thumbnail theme-wrapper" data-themeid="{{$theme->id}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane active" id="log" role="tabpanel" >
                            <p>Here's a record of @if(Auth::user()->id != $user->id )  <code>{{$user->title ?? '' }} {{$user->first_name ?? '' }} {{$user->last_name ?? '' }} {{$user->other_names ?? '' }}'s</code> @else <code>your</code> @endif activities across board.</p>
                            <div class="mt-4" style="height: 660px; overflow-y: scroll;">
                                <ul class="verti-timeline list-unstyled">
                                    @foreach($user->getUserActivityLogs as $log)
                                        <li class="event-list">
                                            <div class="event-timeline-dot">
                                                <i class="bx bx-right-arrow-circle"></i>
                                            </div>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="bx bx-code h4 text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div>
                                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark">{{$log->title ?? '' }}</a></h5>
                                                        <p>{{$log->log ?? '' }}</p>
                                                        <span class="text-primary">{{date('d M, Y h:ia', strtotime($log->created_at))}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase text-info" id="myModalLabel2">Access Level</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" autcomplete="off" action="{{route('add-permission')}}" method="post" id="addBranch" data-parsley-validate="">
                        @csrf
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="flush-heading_">
                                <button class="accordion-button fw-medium " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_" aria-expanded="false" aria-controls="flush-collapse_">
                                    {{$user->roles->first()->name ?? '' }}
                                </button>
                            </h2>
                            <div id="flush-collapse_" class="accordion-collapse collapse show" aria-labelledby="flush-heading_" data-bs-parent="#accordionFlushExample_" style="">
                                <div class="accordion-body text-muted">
                                    <form action="">
                                        <div class="row">
                                            @if(count($user->roles) > 0)
                                                @foreach($user->roles->first()->permissions as $p)
                                                    <div class="col-md-3 col-lg-3">
                                                        <div class="form-check form-checkbox-outline form-check-primary mb-3">
                                                            <input class="form-check-input" type="checkbox"  checked="">
                                                            <label class="form-check-label" >
                                                                {{$p->name ?? ''}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deletePractitionerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="border-radius: 0px;">
                    <h4 class="modal-title text-center " id="myModalLabel2">Are you sure?</h4>
                    <button type="button"  class="btn-close text-white" style="margin: 0px; padding: 0px;" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('delete-user')}}" method="post" id="addNewUser" data-parsley-validate="">
                        @csrf
                        <div class="form-group">
                            <p class="text-wrap">Are you sure you want to {{ $user->status == 1 ? 'deactivate' : 'activate' }} <strong class="text-danger">{{$user->first_name ?? '' }} {{$user->last_name ?? '' }}</strong> from the system?
                                {{$user->first_name ?? '' }} {{$user->last_name ?? '' }} {{ $user->status == 1 ? "won't be able to access" : 'regain access to ' }}  {{ $user->gender == 1 ? 'his' : 'her' }} account again.
                            </p>
                        </div>
                        <div class="form-group mt-1">
                            <input type="hidden" name="userId" value="{{$user->id}}"  class="form-control" >
                            <input type="hidden" name="status" value="{{ $user->status == 1 ? 2 : 1 }}"  class="form-control" >
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">No, cancel</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Yes, proceed</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wallpaperUpload" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="card">
                                <div class="card-block" style="background: #EEF2F4;">
                                    <h6 class="sub-title">Use Your Own Theme</h6>
                                    <form id="customeThemeForm" data-parsley-validate>
                                        <div class="form-group">
                                            <label for="">Background Image</label> <br>
                                            <img src="/assets/drive/wallpapers/{{Auth::user()->getUsersWallpaper->filename ?? ''}}" class="mb-2" height="48" width="48" alt="">
                                            <input type="file" required name="custom_background_image" id="custom_background_image" class="form-control-file">
                                        </div>
                                        <div class="checkbox-fade fade-in-primary">
                                            <label>
                                                <input type="checkbox"  name="custom_color_scheme" id="custom_color_scheme">
                                                <span class="cr">
                                                        <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                </span>
                                                <span>Dark Color Scheme</span>
                                            </label>
                                        </div>
                                        <p><strong class="text-danger">Note:</strong> The default color scheme is light.</p>
                                        <div class="btn-group d-flex justify-content-center">
                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="bx bx-stop text-white mr-2"></i> Close</button>
                                            <button type="submit" class="btn btn-primary btn-sm waves-light" id="customBackgroundBtn"> <i class="bx bx-check-circle text-white mr-2"></i> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script src="/js/axios.min.js"></script>
    <script type="text/javascript" src="{{asset('/assets/js/notify.js')}}"></script>
    <script type="text/javascript" src="{{asset('/assets/js/toastify.min.js')}}"></script>
    <script>
        let theme = null;
        let scheme = null;
        let themeId = null;
        $(document).ready(function(){
            let currentImagePath = "{{url('storage/'.$user->image)}}";
            $('#clientAssignmentWrapper').hide();
            $("#clientAssignmentToggler").click(function(){
                $("#clientAssignmentWrapper").toggle();
            });
            $("#avatarPlaceholderHandler").on("change", function(e){
                e.preventDefault();
                let file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $("#avatarPlaceholder")
                            .attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });


            $('input[name="backgroundTheme"]').click(function(){
                if($(this).prop("checked") == true){
                    theme = $(this).data('background');
                    scheme = $(this).data('scheme');
                    themeId = $(this).val();
                    let location = "/assets/drive/wallpapers/"+theme;
                    $('#layout-wrapper').css("background","url(" + location + ")");
                    $('#layout-wrapper').css("background-size","cover");
                    $('#layout-wrapper').css("background-repeat","no-repeat");
                    //$('.pcoded-mtext').css("color",scheme);
                    //$('.pcoded-navigatio-lavel').css("color",scheme);
                }

            });

            $(document).on('click', '#saveWallpaperChanges', function(e){
                e.preventDefault();
                if(theme == null){
                    $.notify("Whoops!! You haven't chosen a theme.", "error");
                }else{
                    $('#saveWallpaperChanges').text("Processing...");
                    axios.post("{{route('switch-wallpaper')}}",{wallpaper:themeId})
                        .then(response=>{
                            //$('#themeModal').modal('hide');
                            $.notify(response.data.message, "success");
                            $('#saveThemeChangesBtn').text("Save changes");
                        })
                        .catch(error=>{
                            $.notify("Whoops! Something went wrong. Try again", "error");
                        });
                }
            });

        });
    </script>
@endsection
