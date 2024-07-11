@extends('layouts.master-layout')

@section('title')
    Company Profile
@endsection
@section('current-page')
Company Profile
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                            <img class="wd-70 rounded-circle img-thumbnail avatar-xl" src="{{url('storage/'.$company->logo)}}" alt="profile">
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
                        <h6 class="tx-11 fw-bolder text-uppercase">Company Details</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase"> Name</label>
                        <p class="text-muted">{{$company->organization_name ?? '' }} </p>
                    </div>
                    <div class=" mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">RC. No.</label>
                        <p class="text-muted">{{$company->organization_code ?? '' }}</p>
                    </div>
                    <div class=" mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Mobile No.</label>
                        <p class="text-muted">{{$company->phone_no ?? '' }}</p>
                    </div>
                    <div class=" mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Email Address</label>
                        <p class="text-muted">{{$company->email ?? '' }}</p>
                    </div>

                    <div class=" mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase"> Address</label>
                        <p class="text-muted">{{$company->address ?? '' }}</p>
                    </div>
                    <div class=" mt-1">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase"> Year of Incorporation</label>
                        <p class="text-muted">{{ date('d M, Y', strtotime($company->start_date)) ?? '' }}</p>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#team" role="tab" aria-selected="false" tabindex="-1">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Team</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " data-bs-toggle="tab" href="#certificates" role="tab" aria-selected="false" tabindex="-1">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Certificates</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " data-bs-toggle="tab" href="#doc" role="tab" aria-selected="false" tabindex="-1">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">CAC & TAX Clearance Cert.</span>
                            </a>
                        </li>



                        @if(\Illuminate\Support\Facades\Auth::user()->org_id == $company->id)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link " data-bs-toggle="tab" href="#log" role="tab" aria-selected="false" tabindex="-1">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Activity Log</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab" aria-selected="false" tabindex="-1">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Settings</span>
                                </a>
                            </li>
                        @endif

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">

                        <div class="tab-pane active" id="team" role="tabpanel" >
                            <p> <code>{{$company->organization_name ?? '' }}'s</code> team  <span class="badge rounded-pill bg-danger" style="background: #ff0000 !important;"></span></p>
                            <div class="row">
                                <div class="col-md-12 col-lx-12">
                                    <div class="table-responsive mt-3">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th class="">#</th>
                                                <th class="wd-15p">Name</th>
                                                <th class="wd-15p">Mobile No.</th>
                                                <th class="wd-15p">Type</th>
                                                <th class="wd-15p">Email</th>
                                                <th class="wd-5p">Status</th>
                                                <th class="wd-15p">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($company->getOrganizationUsers as $key => $user)
                                                <tr>
                                                    <td>{{$key + 1}}</td>
                                                    <td>
                                                        <img src="{{url('storage/'.$user->image)}}" style="width: 24px; height: 24px;" alt="{{$user->first_name ?? '' }} {{$user->last_name ?? '' }}" class="rounded-circle avatar-sm">
                                                        <a href="{{route('person-profile', $user->slug)}}">{{$user->title ?? '' }} {{$user->first_name ?? '' }} {{$user->last_name ?? '' }} {{$user->other_names ?? '' }}</a> </td>
                                                    <td>{{$user->cellphone_no ?? '' }} </td>
                                                    <td>{!! $user->type == 2 ? "<span class='badge rounded-pill bg-success'>Director</span>" : "<span class='badge rounded-pill bg-secondary'>Contact Person</span>" !!}</td>
                                                    <td>{{$user->email ?? '' }} </td>
                                                    <td>
                                                        {!! $user->status == 1 ? "<i class='bx bxs-check-circle text-success'></i>" : "<i class='bx bxs-x-circle text-danger' style='color:#ff0000 !important;'></i>" !!}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{route('person-profile', $user->slug)}}"> <i class="bx bxs-user"></i> View Profile</a>
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
                        <div class="tab-pane " id="doc" role="tabpanel" >
                            <p>Your company's certificate of incorporation by the <code>Corporate Affairs Commission(CAC)</code> and <code>Current Tax Clearance Certificate</code> is required. This is needed to verify and keep your account with us verified.</p>
                            <p>Do well to furnish us with your current tax clearance certificate as the previous one expires.</p>
                            <p><strong>Note:</strong> All documents must be uploaded in <code>PDF</code> format.</p>
                            <div class="btn-group">
                                @if(empty($files->where('status',1)->where('type',1)->first()))
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#cac">Upload CAC Certificate <i class="bx bx-certification"></i>  </button>
                                @endif
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tax">Upload Current TAX Clearance Certificate <i class="bx bxs-certification"></i> </button>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                @foreach ($files as $file)
                                    @switch(pathinfo($file->filename, PATHINFO_EXTENSION))
                                        @case('pdf')
                                        <div class="col-md-2 mb-4">
                                            @if($file->type == 2)
                                                <span class="badge rounded-pill bg-danger" style="background: #ff0000 !important;">Expires: {{ date('M, Y', strtotime($file->expires_at)) }}</span>
                                            @endif
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}" style="cursor: pointer;">
                                                <img src="/assets/formats/pdf.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"> <br>
                                                {{$file->type == 1 ? 'CAC Certificate' : 'Tax Clearance Certificate'}}
                                            </a>
                                            @if($file->status == 0)
                                                <span class="badge rounded-pill bg-danger" style="background: #FABC05 !important;">Pending</span>
                                            @elseif($file->status == 1)
                                                <span class="badge rounded-pill bg-danger" style="background: #33A852 !important;">Approved</span>
                                            @elseif($file->status == 2)
                                                <span class="badge rounded-pill bg-danger" style="background: #ff0000 !important;">Expired</span>
                                            @elseif($file->status == 3)
                                                <span class="badge rounded-pill bg-danger" style="background: #4E8CEF !important;">Declined</span>
                                            @endif
                                            @include('storage.partials._doc-menu')
                                        </div>
                                        @break
                                    @endswitch
                                @endforeach
                            </div>

                        </div>

                        <div class="tab-pane" id="certificates" role="tabpanel" >
                            <p> <code>{{$company->organization_name ?? '' }}'s</code> certificates  <span class="badge rounded-pill bg-danger" style="background: #ff0000 !important;"></span></p>
                            <div class="row">
                                <div class="col-md-12 col-lx-12">
                                    <div class="table-responsive mt-3">
                                        <table id="datatable1" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                                            <thead style="position: sticky;top: 0">
                                            <tr role="row">
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0" >S/No.</th>
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Start Date</th>
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Expires</th>
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0"  >Category</th>
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0"  >Licence No.</th>
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0"  >Status</th>
                                                <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($company->getOrganizationCertificates as $key => $cert)
                                                <tr>
                                                    <td>{{$key + 1}}</td>
                                                    <td>{{ date('d M, Y', strtotime($cert->start_date)) }}</td>
                                                    <td style="color: #ff0000 !important;">{{ date('d M, Y', strtotime($cert->expires_at)) }}</td>
                                                    <td>{{ $cert->getCategory->getParentCategory->name }}</td>
                                                    <td>{{ $cert->license_no ?? '' }}/{{$cert->getCategory->getParentCategory->abbr}}/{{date('y', strtotime($cert->start_date))}}</td>
                                                    <td>
                                                        @switch($cert->status)
                                                            @case(1)
                                                            <span class="text-success">Active</span>
                                                            @break
                                                            @case(2)
                                                            <span class="text-danger" style="color: #ff0000 !important;">Expired</span>
                                                            @break
                                                            @case(3)
                                                            <span class="text-warning">Renewed</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('certificate-details', $cert->slug) }}" > <i class="bx bxs-book-open"></i> View</a>
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

                        @if(\Illuminate\Support\Facades\Auth::user()->org_id == $company->id)
                            <div class="tab-pane " id="log" role="tabpanel" >
                                <p>{{$company->organization_name ?? '' }} activity log <span class="badge rounded-pill bg-danger" style="background: #ff0000 !important;">{{number_format($logs->count())}}</span></p>
                                <div class="mt-4" style="height: 660px; overflow-y: scroll;">
                                    <ul class="verti-timeline list-unstyled">
                                        @foreach($logs as $log)
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
                            <div class="tab-pane" id="settings1" role="tabpanel" >
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#personalDetails" aria-expanded="true" aria-controls="multiCollapseExample2">Company Profile <i class="bx bx-briefcase"></i> </button>
                                    </div>
                                </div>

                                <div class="multi-collapse collapse " id="personalDetails">
                                    <div class="modal-header text-uppercase mt-3">Company Details</div>
                                    <form class="mt-5" autocomplete="off" action="{{route('update-company-profile')}}" enctype="multipart/form-data" method="post" id="addNewUser" data-parsley-validate="">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-lg-3 align-content-center">
                                                <img class="rounded me-2" id="avatarPlaceholder" alt="200x200" width="200" src="{{url('storage/'.$company->logo)}}" data-holder-rendered="true">
                                                <p>Company Logo</p>
                                                <input type="file" name="logo" accept="image/png, image/gif, image/jpeg" id="avatarPlaceholderHandler"  class="form-control-file mt-2">
                                            </div>
                                            <div class="col-md-9 col-sm-9 col-lg-9">

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                                        <div class="form-group mt-1">
                                                            <label for="">Company Name <span class="text-danger">*</span></label>
                                                            <input type="text" value="{{old( 'companyName', $company->organization_name)}}" name="companyName" data-parsley-required-message="Company is required" placeholder="Company Name" class="form-control" required="">
                                                            @error('companyName') <i class="text-danger">{{$message}}</i>@enderror
                                                            <input type="hidden" name="companyId" value="{{$company->id}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                                        <div class="form-group mt-1">
                                                            <label for="">RC. No.<span class="text-danger">*</span></label>
                                                            <input type="text" value="{{old( 'rcNo',$company->organization_code)}}" name="rcNo" required placeholder="RC. No." data-parsley-required-message="Enter RC. Number" class="form-control">
                                                            @error('rcNo') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                                        <div class="form-group mt-1">
                                                            <label for="">Mobile No. <span class="text-danger">*</span></label>
                                                            <input type="text" value="{{old( 'mobileNo',$company->phone_no)}}" name="mobileNo" placeholder="Mobile No."  class="form-control">
                                                            @error('mobileNo') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                                        <div class="form-group mt-1">
                                                            <label for=""> Email Address <span class="text-danger">*</span></label>
                                                            <input type="email" disabled value="{{old( 'email',$company->email)}}" name="email" required placeholder="Email address" data-parsley-required-message="Email address" class="form-control">
                                                            @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                                        <div class="form-group mt-1">
                                                            <label for=""> Year of Incorporation <span class="text-danger">*</span></label>
                                                            <input type="month"   name="yoi" required placeholder="Year of Incorporation" data-parsley-required-message="Year of incorporation is required" class="form-control">
                                                            @error('yoi') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <div class="form-group mt-1">
                                                            <label for="">Present Address <span class="text-danger">*</span></label>
                                                            <textarea name="presentAddress" id="presentAddress" style="resize: none;"
                                                                      class="form-control" placeholder="Type present address here...">{{old( 'presentAddress',$company->address)}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                                        <div class="form-group d-flex justify-content-center mt-3">
                                                            <div class="btn-group">
                                                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Save changes <i class="bx bxs-check-circle"></i> </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                            </div>
                        @endif

                    </div>

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
                            <p class="text-wrap">Are you sure you want to {{ $company->account_status == 1 ? 'deactivate' : 'activate' }} <strong class="text-danger">{{$company->organization_name ?? '' }} </strong> from the system?
                                The team members of {{$company->organization_name ?? '' }}  {{ $company->account_status == 1 ? "won't be able to access" : 'regain access to ' }}  their account again.
                            </p>
                        </div>
                        <div class="form-group mt-1">
                            <input type="hidden" name="userId" value="{{$company->id}}"  class="form-control" >
                            <input type="hidden" name="status" value="{{ $company->account_status == 1 ? 2 : 1 }}"  class="form-control" >
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

    <div class="modal fade" id="cac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="border-radius: 0px;">
                    <h5 class="modal-title text-center " id="myModalLabel2">Upload CAC Certificate</h5>
                    <button type="button"  class="btn-close text-white" style="margin: 0px; padding: 0px;" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('upload-document')}}" method="post" enctype="multipart/form-data" data-parsley-validate="">
                        @csrf
                        <div class="form-group">
                            <label for="">Choose Certificate <sup style="color: #ff0000;">*</sup></label> <br>
                            <input accept="application/pdf" type="file" name="attachment" class="form-control-file">
                            @error('attachment') <i class="text-danger">{{$message}}</i>@enderror
                            <input type="hidden" name="type" value="1">
                        </div>
                        <div class="form-group mt-1">
                            <input type="hidden" name="userId" value="{{$company->id}}"  class="form-control" >
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Upload Certificate</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="border-radius: 0px;">
                    <h5 class="modal-title text-center " id="myModalLabel2">Upload Current Tax Clearance Certificate</h5>
                    <button type="button"  class="btn-close text-white" style="margin: 0px; padding: 0px;" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('upload-document')}}" method="post" enctype="multipart/form-data" data-parsley-validate="">
                        @csrf
                        <div class="form-group">
                            <label for="">Choose Certificate <sup style="color: #ff0000;">*</sup></label> <br>
                            <input accept="application/pdf" type="file" name="attachment" class="form-control-file">
                            @error('attachment') <i class="text-danger">{{$message}}</i>@enderror
                            <input type="hidden" name="type" value="2">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Valid Till <sup  style="color: #ff0000;">*</sup></label>
                            <input type="month" name="validTill" class="form-control">
                            @error('validTill') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Upload Certificate</button>
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
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script>
        $(document).ready(function(){
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
        });
    </script>
@endsection
