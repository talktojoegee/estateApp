@extends('layouts.master-layout')
@section('title')
    Application Details
@endsection
@section('current-page')
    Application Details
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/assets/libs/owl.carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/libs/owl.carousel/assets/owl.theme.default.min.css">
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('manage-applications') }}" class="btn btn-secondary "> <i
                                class="bx bx bxs-left-arrow"></i> Go back </a>
                        @if(\Illuminate\Support\Facades\Auth::user()->type == 1)
                            @if((in_array(Auth::user()->id, $authIds)) && ($userAction->ap_status == 0))
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#declineModal_" class="btn btn-danger ">  Decline <i
                                        class="bx bx-x"></i>
                                </a>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#approveModal_" class="btn btn-success ">  Approve <i
                                        class="bx bx-check"></i>
                                </a>
                            @endif
                        @endif

                    </div>

                    @if(session()->has('success'))
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="modal-header">

                        <div class="modal-title text-uppercase">Workflow Timeline
                            @if($workflow->p_status == 0)
                                <span class="badge badge-soft-warning">Pending</span>
                            @elseif($workflow->p_status == 1)
                                <span class="badge badge-soft-info">Processing</span>
                            @elseif($workflow->p_status == 2)
                                <span class="badge badge-soft-success">Approved</span>
                            @elseif($workflow->p_status == 3)
                                <span class="badge badge-soft-danger">Declined</span>
                            @elseif($workflow->p_status == 4)
                                <span class="badge badge-soft-success">Paid</span>
                            @elseif($workflow->p_status == 5)
                                <span class="badge badge-soft-success">Verified</span>
                            @elseif($workflow->p_status == 6)
                                <span class="badge badge-soft-warning">Licensed</span>
                            @elseif($workflow->p_status == 7)
                                <span class="badge badge-soft-warning">Assigned</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="hori-timeline">
                            <div class="owl-carousel owl-theme navs-carousel events owl-loaded owl-drag"
                                 id="timeline-carousel">


                                <div class="owl-stage-outer">
                                    <div class="owl-stage"
                                         style="transform: translate3d(-587px, 0px, 0px); transition: all 0.25s ease 0s; width: 1761px;">
                                        <div class="owl-item" style="width: 293.5px;">
                                            <div class="item event-list">
                                                <div>
                                                    <div class="event-date">
                                                        <div class="text-primary mb-1">Initiator</div>
                                                        <h5 class="mb-4">
                                                            {{$workflow->getAuthor->title ?? ''}} {{$workflow->getAuthor->first_name ?? ''}} {{$workflow->getAuthor->last_name ?? ''}}
                                                        </h5>
                                                    </div>
                                                    <div class="d-flex justify-content-center">
                                                        <img src="{{url('storage/'.$workflow->getAuthor->image)}}"
                                                             style="height: 64px; width: 64px;" alt=""
                                                             class="rounded-circle avatar-sm">
                                                        <i class="bx bx-right-arrow-circle font-size-22"
                                                           style="margin-top: 15px; margin-left: 10px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach($workflow->getAuthorizingPersons as $auth)
                                            @if($loop->last)
                                                <div class="owl-item " style="width: 293.5px;">
                                                    <div class="item event-list {{ $workflow->p_status == 0 ? 'active' : null }}">
                                                        <div>
                                                            <div class="event-date">
                                                                <div class="text-primary mb-1">{{date('d M, Y h:ia', strtotime($auth->created_at))}}</div>
                                                                <h5 class="mb-4">
                                                                    {{$auth->getUser->title ?? ''}} {{$auth->getUser->first_name ?? ''}} {{$auth->getUser->last_name ?? ''}}
                                                                </h5>
                                                            </div>
                                                            <div class="event-down-icon">
                                                                @if($auth->ap_status == 0)
                                                                    <i class="bx bxs-hourglass-top h1 text-secondary down-arrow-icon"></i>
                                                                    <?php $pendingId = $auth->ap_id ?>
                                                                @elseif($auth->ap_status == 1)
                                                                    <i class="bx bx-check-circle h1 text-success down-arrow-icon"></i>
                                                                @else
                                                                    <i class="bx bx-x-circle h1 text-warning down-arrow-icon"></i>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex justify-content-center">
                                                                <img src="{{url('storage/'.$auth->getUser->image)}}"
                                                                     style="height: 64px; width: 64px;" alt=""
                                                                     class="rounded-circle avatar-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="owl-item " style="width: 293.5px;">
                                                    <div class="item event-list">
                                                        <div>
                                                            <div class="event-date">
                                                                <div class="text-primary mb-1">{{date('d M, Y h:ia', strtotime($auth->created_at))}}</div>
                                                                <h5 class="mb-4">
                                                                    {{$auth->getUser->title ?? ''}} {{$auth->getUser->first_name ?? ''}} {{$auth->getUser->last_name ?? ''}}
                                                                </h5>
                                                            </div>
                                                            <div class="event-down-icon">
                                                                @if($auth->ap_status == 0)
                                                                    <i class="bx bxs-hourglass-top h1 text-secondary down-arrow-icon"></i>
                                                                    <?php $pendingId = $auth->ap_id ?>
                                                                @elseif($auth->ap_status == 1)
                                                                    <i class="bx bx-check-circle h1 text-success down-arrow-icon"></i>
                                                                @else
                                                                    <i class="bx bx-x-circle h1 text-warning down-arrow-icon"></i>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex justify-content-center">
                                                                <img src="{{url('storage/'.$auth->getUser->image)}}"
                                                                     style="height: 64px; width: 64px;" alt=""
                                                                     class="rounded-circle avatar-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="row">

                    <div class="col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body border-bottom">
                                <div class="d-flex">
                                    <img src="{{url('storage/'.$workflow->getCompany->logo ?? 'logos/logo.png')}}" alt="{{ env("APP_NAME") }}" height="74" width="74">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-semibold">{{$workflow->getCompany->organization_name ?? ''  }}</h5>
                                        <ul class="list-unstyled hstack gap-2 mb-0">
                                            <li>
                                                <i class="bx bx-message"></i> <span class="text-muted">{{$workflow->getCompany->email ?? ''  }}</span>
                                            </li>
                                            <li>
                                                <i class="bx bx-phone-call"></i> <span class="text-muted">{{$workflow->getCompany->phone_no ?? ''  }}</span>
                                            </li>
                                            <li>
                                                <i class="bx bx-building-house"></i> <span class="text-muted">{{$workflow->getCompany->address ?? ''  }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr class="table-light">
                                            <th scope="row">Date:</th>
                                            <td><span class="badge badge-soft-success">{{date('d M, Y h:i a', strtotime($workflow->created_at))}}</span></td>
                                        </tr>
                                        <tr class="table-light">
                                            <th scope="row">Ref. Code:</th>
                                            <td>{{ strtoupper($workflow->p_title) ?? ''  }}</td>
                                        </tr>
                                        <tr class="table-light">
                                            <th scope="row">Status:</th>
                                            <td>
                                                @if($workflow->p_status == 0)
                                                    <span class="badge badge-soft-warning">Pending</span>
                                                @elseif($workflow->p_status == 1)
                                                    <span class="badge badge-soft-info">Processing</span>
                                                @elseif($workflow->p_status == 2)
                                                    <span class="badge badge-soft-success">Approved</span>
                                                @elseif($workflow->p_status == 3)
                                                    <span class="badge badge-soft-danger">Declined</span>
                                                @endif


                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12 pl-4">
                                        {!! $workflow->p_content ?? '' !!}
                                    </div>
                                    @if($workflow->getAttachments->count() > 0)
                                        <div class="col-md-12 col-lg-12 col-sm-12 mt-3 mb-5">
                                            <div class="row">
                                                @foreach($workflow->getAttachments as $attachment)
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="avatar-sm">
                                                            <span
                                                                class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                                                <i class="bx bxs-file-pdf"></i>
                                                            </span>
                                                        </div>

                                                        <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{ strlen($attachment->pa_name) > 25 ?  substr($attachment->pa_name,0,22).'...' : $attachment->pa_name   }}</a>
                                                        </h5>
                                                        <small>Size
                                                            : {{ \App\Models\PostAttachment::formatFileSize($attachment->pa_size)  ?? '' }}
                                                        </small>

                                                        <div class="text-center">
                                                            <a href="{{ route('download-attachment', $attachment->pa_attachments) }}"
                                                               class="text-dark"><i class="bx bx-download h3 m-0"></i></a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="modal-header text-uppercase">Device Information</div>
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">

                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Station</th>
                                                    <th>Mode</th>
                                                    <th>Category</th>
                                                    <th>Freq. Band</th>
                                                    <th>Type</th>
                                                    <th>Quantity</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($workflow->getRadioLicenseDetails as $key => $detail)
                                                    <tr>
                                                        <th>{{ $key +1  }}</th>
                                                        <td>{{$detail->getWorkstation->name ?? '' }}</td>
                                                        <td>{{$detail->operation_mode == 1 ? 'Simplex' : 'Duplex' }}</td>
                                                        <td>{{ $detail->getLicenseCategory->category_name ?? '' }}</td>
                                                        <td>
                                                            @switch($detail->frequency_band)
                                                                @case(1)
                                                                MF/HF
                                                                @break
                                                                @case(2)
                                                                VHF
                                                                @break
                                                                @case(3)
                                                                UHF
                                                                @break
                                                                @case(4)
                                                                SHF
                                                                @break
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            @switch($detail->type_of_device)
                                                                @case(1)
                                                                Handheld
                                                                @break
                                                                @case(2)
                                                                Base Station
                                                                @break
                                                                @case(3)
                                                                Repeaters Station
                                                                @break
                                                                @case(4)
                                                                Vehicular Station
                                                                @break
                                                            @endswitch
                                                        </td>
                                                        <td>{{ number_format($detail->no_of_device ?? 0) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row task-dates">
                                        <div class="col-sm-6 col-6">
                                            <div class="mt-4">
                                                <h5 class="font-size-14"><i class="bx bxs-bank me-1 text-primary"></i>
                                                    Amount
                                                </h5>
                                                <p class="text-muted mb-0">{{$workflow->getCurrency->code ?? '' }} {{$workflow->getCurrency->symbol ?? '' }}{{number_format($workflow->p_amount,2)}}</p>
                                                <p>(
                                                    <?php
                                                    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                    $f->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-numbering-verbose");
                                                    echo ucfirst($f->format($workflow->p_amount)) . ' ' . strtolower($workflow->getCurrency->name);
                                                    ?>)
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-6">
                                            <div class="mt-4">
                                                <h5 class="font-size-14"><i class="bx bxs-user-badge me-1 text-primary"></i>
                                                    Initiated By</h5>
                                                <p class="text-muted mb-0">{{$workflow->getAuthor->title ?? '' }} {{$workflow->getAuthor->first_name ?? '' }} {{$workflow->getAuthor->last_name ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-4 ">
                                    <div class="col-md-12 col-lg-12 d-flex justify-content-end">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item mt-1">
                                                <a href="javascript:void(0)" id="printContent" class="btn btn-outline-danger btn-hover"><i class="uil uil-google"></i> Print </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">Workflow Trail</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">Comment(s)</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="home1" role="tabpanel">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="commentWrapper" class="simplebar-content-wrapper" style="height: 300px; padding-right: 20px; padding-bottom: 0px; overflow: hidden scroll; background: #FFFFFF !important;">
                                                        @foreach($workflow->getAuthorizingPersons as $trail)
                                                            <div class="d-flex py-3 border-top">
                                                                <div class="flex-shrink-0 me-3">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{url('storage/'.$trail->getUser->image)}}" alt=""
                                                                             class="img-fluid d-block rounded-circle">
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="font-size-14 mb-1">{{$trail->getUser->title ?? '' }} {{$trail->getUser->first_name ?? '' }} {{$trail->getUser->last_name ?? '' }} <small
                                                                            class="text-muted float-end">{{ date('d M, Y h:ia', strtotime($trail->updated_at)) }}</small></h5>
                                                                    <p class="text-muted">
                                                                        {{$trail->ap_comment ?? '' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile1" role="tabpanel">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div id="commentWrapper" class="simplebar-content-wrapper" style="background: #FFFFFF !important; height: 300px; padding-right: 20px; padding-bottom: 0px; overflow: hidden scroll;">
                                                        @foreach($workflow->getPostComments as $comment)
                                                            <div class="d-flex py-3 border-top">
                                                                <div class="flex-shrink-0 me-3">
                                                                    <div class="avatar-xs">
                                                                        <img src="{{url('storage/'.$comment->getUser->image)}}" alt=""
                                                                             class="img-fluid d-block rounded-circle">
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="font-size-14 mb-1">{{$comment->getUser->title ?? '' }} {{$comment->getUser->first_name ?? '' }} {{$comment->getUser->last_name ?? '' }}
                                                                        @if(($comment->getUser->type > 1) && ($comment->getUser->org_id == \Illuminate\Support\Facades\Auth::user()->org_id ))
                                                                            <sup><span class="badge badge-soft-danger">Applicant</span></sup>
                                                                        @else
                                                                            <sup><span class="badge badge-soft-success">Administrator</span></sup>
                                                                        @endif

                                                                        <small
                                                                            class="text-muted float-end">{{\Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</small>
                                                                    </h5>
                                                                    <p class="text-muted">
                                                                        {{$comment->pc_comment ?? '' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="mt-4">
                                                        <h5 class="font-size-16 mb-3">Leave a comment</h5>
                                                        <form id="commentForm" data-parsley-validate="">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="comment" class="form-label">Comment</label>
                                                                <textarea data-parsley-required-message="Type your comment in the field provided" required name="comment" class="form-control" id="comment"
                                                                          placeholder="Your comment..." style="resize: none;" rows="3"></textarea>
                                                                <input type="hidden" name="postId" id="postId" value="{{$workflow->p_id}}">
                                                            </div>

                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-primary w-sm">Submit <i class="bxs-comment-add bx "></i> </button>
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
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="modal-header">
                        <div class="modal-title text-uppercase">Company Profile</div>
                    </div>
                    <div class="card-body">
                        <div class="mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase"> Name</label>
                            <p class="text-muted">{{$workflow->getCompany->organization_name ?? '' }} </p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">RC. No.</label>
                            <p class="text-muted">{{$workflow->getCompany->organization_code ?? '' }}</p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Mobile No.</label>
                            <p class="text-muted">{{$workflow->getCompany->phone_no ?? '' }}</p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Email Address</label>
                            <p class="text-muted">{{$workflow->getCompany->email ?? '' }}</p>
                        </div>

                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase"> Address</label>
                            <p class="text-muted">{{$workflow->getCompany->address ?? '' }}</p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase"> Year of Incorporation</label>
                            <p class="text-muted">{{ date('d M, Y', strtotime($workflow->getCompany->start_date)) ?? '' }}</p>
                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="modal-header">
                        <div class="modal-title text-uppercase">CAC & TAX Documents</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle table-hover mb-0">
                                <tbody>
                                @if(count($workflow->getCompany->getCompanyDocuments->where('status',1)) > 0)
                                    @foreach($workflow->getCompany->getCompanyDocuments->where('status',1) as $attachment)
                                        <tr>
                                            <td style="width: 45px;">
                                                <div class="avatar-sm">
                                                <span
                                                    class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                                    <i class="bx bxs-file-pdf"></i>
                                                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-14 mb-1">
                                                    <a href="javascript: void(0);" class="text-dark">
                                                        {!! $attachment->type == 1 ? "<span class='text-success'>CAC Certificate</span>" : "<span class='text-danger' style='color:#ff0000 !important;'>Current Tax Clearance Certificate</span>" !!}

                                                    </a>
                                                </h5>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <a href="{{ route('download-attachment', $attachment->filename) }}"
                                                       class="text-dark"><i
                                                            class="bx bx-download h3 m-0"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3"> No document(s) uploaded.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="modal-header">
                        <div class="modal-title text-uppercase">Employees</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($workflow->getCompany->getOrganizationUsers->where('status', 1)) > 0)
                                @foreach($workflow->getCompany->getOrganizationUsers->where('status', 1) as $key => $user)
                                    <tr>
                                        <th>{{ $key +1  }}</th>
                                        <td>
                                            <img src="{{url('storage/'.$user->image)}}" style="width: 24px; height: 24px;" alt="{{$user->first_name ?? '' }} {{$user->last_name ?? '' }}" class="rounded-circle avatar-sm">
                                            <a target="_blank" href="{{route('person-profile', $user->slug)}}">{{$user->title ?? '' }} {{$user->first_name ?? '' }} {{$user->last_name ?? '' }} {{$user->other_names ?? '' }}</a>
                                        </td>
                                        <td>{!! $user->type == 2 ? "<span class='badge rounded-pill bg-success'>Director</span>" : "<span class='badge rounded-pill bg-secondary'>Contact Person</span>" !!}</td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3">No record found.</td>
                                </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal right fade" id="approveModal_" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="width: 900px;">
        <div class="modal-dialog w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase" style="text-align: center;" id="myModalLabel2">Are you sure you want to approve this?</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong class="text-danger">Note:</strong> This action cannot be undone. Are you sure you want to approve this?</p>
                    <form autocomplete="off" action="{{route('update-workflow')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12 col-sm-12 col-lg-12 mb-3">
                                <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                                    <input class="form-check-input" type="checkbox" id="markedAsFinal" name="final">
                                    <label class="form-check-label" for="markedAsFinal">Should your action be marked as final?</label>
                                </div>
                                <input type="hidden" name="workflowId" value="{{$workflow->p_id}}">
                            </div>
                            <div class="form-group mt-1 col-md-12 mt-3 " id="nextAuthWrapper">
                                <label for="">Next Authorizing Person</label>
                                <select name="nextAuth"  class="form-control">
                                    <option disabled selected>-- Select next auth person --</option>
                                    @foreach($persons as $person)
                                        <option value="{{$person->id}}">{{ $person->title ?? '' }} {{ $person->first_name ?? '' }} {{ $person->last_name ?? '' }}</option>
                                    @endforeach
                                </select>
                                @error('nextAuth') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12">
                                <label for="">Comment</label> <br>
                                <input type="hidden" name="status" value="1">
                                <textarea name="comment" style="resize: none;" placeholder="Leave your comment here..." class="form-control">{{old('comment')}}</textarea>
                                @error('comment') <i class="text-danger">{{$message}}</i>@enderror
                                <input type="hidden" name="authId" value="{{ $pendingId }}">
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit  <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="declineModal_" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="width: 900px;">
        <div class="modal-dialog w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase" style="text-align: center;" id="declinemyModalLabel2">Are you sure you want to decline this?</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong class="text-danger">Note:</strong> This action cannot be undone. Are you sure you want to decline this?</p>
                    <form autocomplete="off" action="{{route('update-workflow')}}" id="declinecreateIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12 col-sm-12 col-lg-12 mb-3">
                                <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                                    <input class="form-check-input" type="checkbox" id="declinemarkedAsFinal" name="final">
                                    <label class="form-check-label" for="markedAsFinal">Should your action be marked as final?</label>
                                </div>
                                <input type="hidden" name="workflowId" value="{{$workflow->p_id}}">
                            </div>
                            <div class="form-group mt-1 col-md-12 mt-3 " id="declinenextAuthWrapper">
                                <label for="">Next Authorizing Person</label>
                                <select name="nextAuth"  class="form-control">
                                    <option disabled selected>-- Select next auth person --</option>
                                    @foreach($persons as $person)
                                        <option value="{{$person->id}}">{{ $person->title ?? '' }} {{ $person->first_name ?? '' }} {{ $person->last_name ?? '' }}</option>
                                    @endforeach
                                </select>
                                @error('nextAuth') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12">
                                <label for="">Comment</label> <br>
                                <input type="hidden" name="status" value="2">
                                <textarea name="comment" style="resize: none;" placeholder="Leave your comment here..." class="form-control">{{old('comment')}}</textarea>
                                @error('comment') <i class="text-danger">{{$message}}</i>@enderror
                                <input type="hidden" name="authId" value="{{ $pendingId }}">
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit  <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="/assets/libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="/assets/js/pages/timeline.init.js"></script>
    <script src="/js/parsley.js"></script>
    <script src="/js/axios.min.js"></script>
    <script>
        $(document).ready(function(){
            let isChecked = false;


            $('#markedAsFinal').on('click', function(){
                isChecked = $(this).is(':checked')

                if(isChecked){
                    $('#nextAuthWrapper').hide();
                }
                else{
                    $('#nextAuthWrapper').show();
                }
            });


            $('#declinemarkedAsFinal').on('click', function(){
                isChecked = $(this).is(':checked')

                if(isChecked){
                    $('#declinenextAuthWrapper').hide();
                }
                else{
                    $('#declinenextAuthWrapper').show();
                }
            });



            $('#commentForm').parsley().on('field:validated', function() {
                let ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
                let comment = $('#comment').val();
                let postId = $('#postId').val();
                let url = "{{ route("comment-on-post") }}";
                axios.post(url, {
                    comment,
                    postId
                })
                .then(res=>{
                    $('#comment').val('');
                    $('#commentWrapper').html(res.data);
                });
            })
                .on('form:submit', function() {
                    return false;
                });
        });
    </script>
@endsection



