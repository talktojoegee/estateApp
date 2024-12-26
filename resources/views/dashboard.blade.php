@extends('layouts.master-layout')
@section('current-page')
    Dashboard
@endsection

@section('title')
    Dashboard
@endsection

@section('extra-styles')
    <link href="/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
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
    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">Analytics</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="home1" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <h3 class=""><span>Appointments</span> <small class="text-info ml-5" style="font-size: 14px;"><a href="{{route('show-appointments')}}">View Calendar</a></small></h3>
                                    <div class="col-xl-12 col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#client" class="btn btn-primary  mb-3">Add New Appointment <i class="bx bxs-timer"></i> </a>
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#Today" role="tab">
                                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                            <span class="d-none d-sm-block">Today ({{$today->count()}})</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#Week" role="tab">
                                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                            <span class="d-none d-sm-block">Week ({{$week->count()}})</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#Unconfirmed" role="tab">
                                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                                            <span class="d-none d-sm-block">Unconfirmed ({{$unconfirmed->count()}})</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!-- Tab panes -->
                                                <div class="tab-content p-3 text-muted">
                                                    <div class="tab-pane active" id="Today" role="tabpanel">
                                                       <div class="row">
                                                           <div class="col-md-12 col-sm-12">
                                                               <div class="table-responsive">
                                                                   <table class="table align-middle mb-0">
                                                                       <thead>
                                                                       <tr>
                                                                           <th class="">#</th>
                                                                           <th class="wd-15p">Client</th>
                                                                           <th class="wd-15p">Type</th>
                                                                           <th class="wd-15p">Status</th>
                                                                           <th class="wd-15p">Action</th>
                                                                       </tr>
                                                                       </thead>
                                                                       <tbody>
                                                                       @php $index = 1; @endphp
                                                                       @foreach($today->take(5) as $key => $td)
                                                                           <tr>
                                                                               <td>{{$key+1}}</td>
                                                                               <td>
                                                                                   @if($td->session_type != 3)
                                                                                       @foreach($td->getInvitees->take(1) as $in)
                                                                                           {{$in->getClient->first_name ?? ''  }}  {{$in->getClient->last_name ?? ''  }}
                                                                                       @endforeach
                                                                                       @if($td->getInvitees->count() > 1)
                                                                                           <small class="badge rounded-pill bg-info">+{{$td->getInvitees->count() - 1}} others</small>
                                                                                       @endif
                                                                                   @else
                                                                                       <span class="text-warning">Block Session</span>
                                                                                   @endif

                                                                               </td>
                                                                               <td>
                                                                                   @if($td->session_type != 3)
                                                                                       {{$td->getAppointmentType->name ?? '' }}
                                                                                   @else
                                                                                       <span class="text-warning">Block Session</span>
                                                                                   @endif
                                                                               </td>
                                                                               <td>
                                                                                   @if($td->session_type != 3)
                                                                                       @switch($td->status)
                                                                                           @case(1)
                                                                                           <label for="" class="badge badge-soft-primary">Booked</label>
                                                                                           @break
                                                                                           @case(2)
                                                                                           <label for="" class="badge badge-soft-success">Confirmed</label>
                                                                                           @break
                                                                                           @case(3)
                                                                                           <label for="" class="badge-soft-danger badge">Unconfirmed</label>
                                                                                           @break
                                                                                           @case(4)
                                                                                           <label for="" class="badge badge-soft-warning">Repeat</label>
                                                                                           @break
                                                                                       @endswitch
                                                                                   @else
                                                                                       <span class="text-warning">Block Session</span>
                                                                                   @endif
                                                                               </td>
                                                                               <td>
                                                                                   <div class="btn-group">
                                                                                       <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                                       <div class="dropdown-menu">
                                                                                           <a class="dropdown-item" href="{{route('show-appointment-details', $td->slug)}}"> <i class="bx bxs-book-open"></i> View</a>
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
                                                    <div class="tab-pane" id="Week" role="tabpanel">
                                                        <div class="table-responsive">
                                                            <table class="table align-middle mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th class="">#</th>
                                                                    <th class="wd-15p">Client</th>
                                                                    <th class="wd-15p">Type</th>
                                                                    <th class="wd-15p">Status</th>
                                                                    <th class="wd-15p">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php $index = 1; @endphp
                                                                @foreach($week->take(5) as $kkey => $wk)
                                                                    <tr>
                                                                        <td>{{$kkey+1}}</td>
                                                                        <td>
                                                                            @if($wk->session_type != 3)
                                                                                @foreach($wk->getInvitees->take(1) as $inv)
                                                                                    {{$inv->getClient->first_name ?? ''  }}  {{$inv->getClient->last_name ?? ''  }}
                                                                                @endforeach
                                                                                @if($wk->getInvitees->count() > 1)
                                                                                    <small class="badge rounded-pill bg-info">+{{$wk->getInvitees->count() - 1}} others</small>
                                                                                @endif
                                                                            @else
                                                                                <span class="text-warning">Block Session</span>
                                                                            @endif

                                                                        </td>
                                                                        <td>
                                                                            @if($wk->session_type != 3)
                                                                                {{$wk->getAppointmentType->name ?? '' }}
                                                                            @else
                                                                                <span class="text-warning">Block Session</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($wk->session_type != 3)
                                                                                @switch($wk->status)
                                                                                    @case(1)
                                                                                    <label for="" class="badge badge-soft-primary">Booked</label>
                                                                                    @break
                                                                                    @case(2)
                                                                                    <label for="" class="badge badge-soft-success">Confirmed</label>
                                                                                    @break
                                                                                    @case(3)
                                                                                    <label for="" class="badge-soft-danger badge">Unconfirmed</label>
                                                                                    @break
                                                                                    @case(4)
                                                                                    <label for="" class="badge badge-soft-warning">Repeat</label>
                                                                                    @break
                                                                                @endswitch
                                                                            @else
                                                                                <span class="text-warning">Block Session</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="{{route('show-appointment-details', $wk->slug)}}"> <i class="bx bxs-book-open"></i> View</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="Unconfirmed" role="tabpanel">
                                                        <div class="table-responsive">
                                                            <table class="table align-middle mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th class="">#</th>
                                                                    <th class="wd-15p">Client</th>
                                                                    <th class="wd-15p">Type</th>
                                                                    <th class="wd-15p">Status</th>
                                                                    <th class="wd-15p">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($unconfirmed->take(5) as $loo => $uncon)
                                                                    <tr>
                                                                        <td>{{$loo+1}}</td>
                                                                        <td>
                                                                            @if($uncon->session_type != 3)
                                                                                @foreach($uncon->getInvitees->take(1) as $invite)
                                                                                    {{$invite->getClient->first_name ?? ''  }}  {{$invite->getClient->last_name ?? ''  }}
                                                                                @endforeach
                                                                                @if($uncon->getInvitees->count() > 1)
                                                                                    <small class="badge rounded-pill bg-info">+{{$uncon->getInvitees->count() - 1}} others</small>
                                                                                @endif
                                                                            @else
                                                                                <span class="text-warning">Block Session</span>
                                                                            @endif

                                                                        </td>
                                                                        <td>
                                                                            @if($uncon->session_type != 3)
                                                                                {{$uncon->getAppointmentType->name ?? '' }}
                                                                            @else
                                                                                <span class="text-warning">Block Session</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($uncon->session_type != 3)
                                                                                @switch($uncon->status)
                                                                                    @case(1)
                                                                                    <label for="" class="badge badge-soft-primary">Booked</label>
                                                                                    @break
                                                                                    @case(2)
                                                                                    <label for="" class="badge badge-soft-success">Confirmed</label>
                                                                                    @break
                                                                                    @case(3)
                                                                                    <label for="" class="badge-soft-danger badge">Unconfirmed</label>
                                                                                    @break
                                                                                    @case(4)
                                                                                    <label for="" class="badge badge-soft-warning">Repeat</label>
                                                                                    @break
                                                                                @endswitch
                                                                            @else
                                                                                <span class="text-warning">Block Session</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="{{route('show-appointment-details', $uncon->slug)}}"> <i class="bx bxs-book-open"></i> View</a>
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
                                <div class="col-md-6 col-sm-6">
                                    <h3 class=""><span>Tasks</span> <small class="text-info ml-5" style="font-size: 14px;"><a href="{{route('manage-tasks')}}">View All Tasks</a></small></h3>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{route('create-task')}}" class="btn btn-primary float-end"> Add Task <i class="bx bx-task"></i> </a>
                                            </div>
                                            <div class="table-responsive">
                                                <table  class="table align-middle mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="">#</th>
                                                        <th class="wd-15p">Due Date</th>
                                                        <th class="wd-15p">Title</th>
                                                        <th class="wd-15p">Assigned To</th>
                                                        <th class="wd-15p">Clients</th>
                                                        <th class="wd-15p">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($tasks->take(5) as $taskKey=> $task)
                                                        <tr>
                                                            <td>{{$taskKey+1}}</td>
                                                            <td>{{date('d M, Y h:ia', strtotime($task->due_date))}}</td>
                                                            <td>{{$task->title ?? '' }}</td>
                                                            <td>
                                                                <div class="d-flex justify-content-center">
                                                                    <span class="badge rounded-pill bg-danger ">{{number_format(count(json_decode($task->clients)))}}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex justify-content-center">
                                                                    <span class="badge rounded-pill bg-info ">{{number_format(count(json_decode($task->assigned_to)))}}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" data-bs-target="#messageDetail_{{$task->id}}" data-bs-toggle="modal" href="javascript:void(0);"> <i class="bx bxs-book-open"></i> View</a>
                                                                    </div>
                                                                </div>
                                                                <div class="modal right fade" id="messageDetail_{{$task->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header" >
                                                                                <h4 class="modal-title" id="myModalLabel2">Task Details</h4>
                                                                                @if($task->complete == 0)
                                                                                    <button onclick="return updateStatus({{$task->id}}, '{{route('mark-as')}}')" class="btn btn-success  btn-sm">Mark As Complete <i class="bx bx-check"></i> </button>
                                                                                @else
                                                                                    <button onclick="return updateStatus({{$task->id}}, '{{route('mark-as')}}')" class="btn btn-warning btn-sm">Mark As Incomplete <i class="bx bx-stop"></i> </button>
                                                                                @endif

                                                                                <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>

                                                                            <div class="modal-body text-wrap">
                                                                                <h5 class="font-size-15 mt-4">{{$task->title ?? '' }} @if($task->complete == 0) <sup class="text-white p-1 bg-warning">Incomplete</sup>@else <sup class="text-white p-1 bg-success">Complete</sup> @endif </h5>
                                                                                <hr>
                                                                                {!! $task->description ?? ''  !!}
                                                                                <div class="text-muted mt-4 bg-light p-2">
                                                                                    <h6 class="font-size-12 mt-4">Clients <span class="badge rounded-pill bg-info">{{count(json_decode($task->clients))}}</span></h6>
                                                                                    @foreach($task->getClients(json_decode($task->clients))  as $client)
                                                                                        <p><i class="mdi mdi-chevron-right text-primary me-1"></i>
                                                                                            {{$client->first_name ?? '' }} {{$client->last_name ?? '' }}
                                                                                        </p>
                                                                                    @endforeach
                                                                                </div>
                                                                                <div class="text-muted mt-4 bg-light p-2">
                                                                                    <h6 class="font-size-12 mt-4">Assigned To <span class="badge rounded-pill bg-primary">{{count(json_decode($task->assigned_to))}}</span> </h6>
                                                                                    @foreach($task->getAssignedPersons(json_decode($task->assigned_to))  as $assigned)
                                                                                        <p><i class="mdi mdi-chevron-right text-primary me-1"></i>
                                                                                            {{$assigned->first_name ?? '' }} {{$assigned->last_name ?? '' }}
                                                                                        </p>
                                                                                    @endforeach
                                                                                </div>
                                                                                <div class="mt-4">
                                                                                    <h6 class="font-size-12 mt-4">Created By</h6>
                                                                                    <p class="text-muted mb-0">{{$task->getCreatedBy->first_name ?? '' }} {{$task->getCreatedBy->last_name ?? '' }}</p>
                                                                                    <h6 class="font-size-12 mt-4">Date & Time</h6>
                                                                                    <p class="text-muted mb-0">{{date('d M, Y h:ia', strtotime($task->created_at))}}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                        <div class="tab-pane" id="profile1" role="tabpanel">

                            <div class="row">
                                <div class="col-12 col-lg-12 col-xl-6">
                                    <div class="card-deck flex-column flex-lg-row">
                                        <div class="card radius-15 bg-info">
                                            <div class="card-body text-center">
                                                <div class="widgets-icons mx-auto rounded-circle bg-white"><i class='bx bx-time'></i>
                                                </div>
                                                <h4 class="mb-0 font-weight-bold mt-3 text-white">2m 28s</h4>
                                                <p class="mb-0 text-white">Avg. Time on Site</p>
                                            </div>
                                        </div>
                                        <div class="card radius-15 bg-wall">
                                            <div class="card-body text-center">
                                                <div class="widgets-icons mx-auto bg-white rounded-circle"><i class='bx bx-bookmark-alt'></i>
                                                </div>
                                                <h4 class="mb-0 font-weight-bold mt-3 text-white">4.68</h4>
                                                <p class="mb-0 text-white">Pages/Session</p>
                                            </div>
                                        </div>
                                        <div class="card radius-15 bg-rose">
                                            <div class="card-body text-center">
                                                <div class="widgets-icons mx-auto bg-white rounded-circle"><i class='bx bx-bulb'></i>
                                                </div>
                                                <h4 class="mb-0 font-weight-bold mt-3 text-white">78%</h4>
                                                <p class="mb-0 text-white">New Sessions</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-deck flex-column flex-lg-row">
                                        <div class="card radius-15 bg-danger">
                                            <div class="card-body text-center">
                                                <div class="widgets-icons mx-auto rounded-circle bg-white"><i class='bx bx-line-chart'></i>
                                                </div>
                                                <h4 class="mb-0 font-weight-bold mt-3 text-white">23.4%</h4>
                                                <p class="mb-0 text-white">Bounce Rate</p>
                                            </div>
                                        </div>
                                        <div class="card radius-15 bg-primary">
                                            <div class="card-body text-center">
                                                <div class="widgets-icons mx-auto bg-white rounded-circle"><i class='bx bx-group'></i>
                                                </div>
                                                <h4 class="mb-0 font-weight-bold mt-3 text-white">4,286</h4>
                                                <p class="mb-0 text-white">New Users</p>
                                            </div>
                                        </div>
                                        <div class="card radius-15 bg-success">
                                            <div class="card-body text-center">
                                                <div class="widgets-icons mx-auto bg-white rounded-circle"><i class='bx bx-cloud-download'></i>
                                                </div>
                                                <h4 class="mb-0 font-weight-bold mt-3 text-white">78%</h4>
                                                <p class="mb-0 text-white">Downloads</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12 col-xl-6">
                                    <div class="card radius-15">
                                        <div class="card-body">
                                            <div class="d-lg-flex align-items-center mb-4">
                                                <div>
                                                    <h5 class="mb-0">Social Media Traffic</h5>
                                                </div>
                                                <div class="ml-auto">
                                                    <h3 class="mb-0"><span class="font-14">Total Visits:</span> 874</h3>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="dashboard-social-list">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="widgets-social bg-youtube rounded-circle text-white">
                                                                <i class='bx bxl-youtube'></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">YouTube</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">298</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="widgets-social bg-facebook rounded-circle text-white"><i class='bx bxl-facebook'></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Facebook</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">324</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="widgets-social bg-linkedin rounded-circle text-white"><i class='bx bxl-linkedin'></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Linkedin</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">127</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="widgets-social bg-twitter rounded-circle text-white"><i class='bx bxl-twitter'></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Twitter</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">325</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="widgets-social bg-tumblr rounded-circle text-white"><i class='bx bxl-tumblr'></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Tumblr</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">287</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="widgets-social bg-dribbble rounded-circle text-white"><i class='bx bxl-dribbble'></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Dribbble</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">154</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end row-->
                            <div class="row">
                                <div class="col-12 col-lg-12 col-xl-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5 class="mb-0">Geographic</h5>
                                            </div>
                                            <hr/>
                                            <div id="geographic-map"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-12 col-xl-4">
                                    <div class="card radius-15">
                                        <div class="card-body">
                                            <div class="d-lg-flex align-items-center mb-4">
                                                <div>
                                                    <h5 class="mb-0">Top countries</h5>
                                                </div>
                                                <div class="ml-auto">
                                                    <h3 class="mb-0"><span class="font-14">Total Visits:</span> 9587</h3>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="dashboard-top-countries">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-in"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">India</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">647</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-us"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">United States</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">435</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-vn"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Vietnam</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">287</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-au"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Australia</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">432</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-dz"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Angola</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">345</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-ax"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Aland Islands</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">134</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-ar"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Argentina</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">147</div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <div class="media align-items-center">
                                                            <div class="font-20"><i class="flag-icon flag-icon-be"></i>
                                                            </div>
                                                            <div class="media-body ml-2">
                                                                <h6 class="mb-0">Belgium</h6>
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto">210</div>
                                                    </li>
                                                </ul>
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
    <div class="row">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-2">Inflow</p>
                            <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format($sales->sum('total'),2)}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-orange"> <i class="bx bxs-receipt text-success fs-22"></i></div>
                        </div>
                    </div>
                    <span class="fs-12 text-success"> <strong>This Month's</strong> </span>
                    <span class="text-muted fs-12 ms-0 mt-1">Sales </span>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col"> <p class="mb-2">Practitioners</p><h5 class="mb-0 number-font">{{number_format($practitioners->count())}}</h5> </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary1"> <i class="bx bxs-briefcase-alt-2 text-warning fs-22"></i> </div>
                        </div>
                    </div>
                    <span class="fs-12 text-warning"> <strong>Total</strong>  </span>
                    <span class="text-muted fs-12 ms-0 mt-1">Practitioners </span>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col"> <p class="mb-2">Clients</p>
                            <h5 class="mb-0 number-font">{{number_format($clients->count())}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary"> <i class="bx bxs-user text-primary fs-22"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-primary"> <strong>Total </strong>  </span>
                    <span class="text-muted fs-12 ms-0 mt-1">Clients </span>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-2">Appointments</p>
                            <h5 class="mb-0 number-font">{{number_format($appointments->count())}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-warning"> <i class="bx bxs-calendar text-info fs-22"></i> </div>
                        </div>
                    </div>
                    <span class="fs-12 text-info"> <strong>Total </strong>  </span>
                    <span class="text-muted fs-12 ms-0 mt-1">Appointments </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-6 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-header bg-primary text-white">Revenue</div>
                <div class="card-body">
                    <p>Kindly note that the information shown in the chart below is for the current year.</p>
                    <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-header bg-primary text-white">Appointment/Medication</div>
                <div class="card-body">
                    <p>Kindly note that the information shown in the chart below is for the current year.</p>
                    <div id="attendanceMedication" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Latest Appointments</h4>
                    <div class="table-responsive mt-3">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Client</th>
                                <th class="wd-15p">Date & Time</th>
                                <th class="wd-15p">Type</th>
                                <th class="wd-15p">Contact</th>
                                <th class="wd-15p">Status</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $index = 1; @endphp
                            @foreach($appointments->take(10) as $appoint)
                                <tr>
                                    <td>{{$index++}}</td>
                                    <td>
                                        @if($appoint->session_type != 3)
                                            @foreach($appoint->getInvitees->take(1) as $invite)
                                                {{$invite->getClient->first_name ?? ''  }}  {{$invite->getClient->last_name ?? ''  }}
                                            @endforeach
                                            @if($appoint->getInvitees->count() > 1)
                                                <small class="badge rounded-pill bg-info">+{{$appoint->getInvitees->count() - 1}} others</small>
                                            @endif
                                        @else
                                            <span class="text-warning">Block Session</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if($appoint->session_type != 3)
                                            {{date('d M, Y', strtotime($appoint->event_date))}} <u class="text-info">{{date('h:ia', strtotime($appoint->event_date))}}</u>
                                        @else
                                            <strong>From: </strong>{{date('d M, Y', strtotime($appoint->event_date))}} <u class="text-info">{{date('h:ia', strtotime($appoint->event_date))}}</u>
                                            <br>
                                            <strong>To: </strong>{{date('d M, Y', strtotime($appoint->end_date))}} <u class="text-info">{{date('h:ia', strtotime($appoint->end_date))}}</u>
                                        @endif

                                    </td>
                                    <td>
                                        @if($appoint->session_type != 3)
                                            {{$appoint->getAppointmentType->name ?? '' }}
                                        @else
                                            <span class="text-warning">Block Session</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appoint->session_type != 3)
                                            @switch($appoint->contact_type)
                                                @case(1)
                                                Video Call
                                                @break
                                                @case(2)
                                                In Person
                                                @break
                                                @case(3)
                                                Phone Call
                                                @break
                                            @endswitch
                                        @else
                                            <span class="text-warning">Block Session</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appoint->session_type != 3)
                                            @switch($appoint->status)
                                                @case(1)
                                                <label for="" class="badge badge-soft-primary">Booked</label>
                                                @break
                                                @case(2)
                                                <label for="" class="badge badge-soft-success">Confirmed</label>
                                                @break
                                                @case(3)
                                                <label for="" class="badge-soft-danger badge">Unconfirmed</label>
                                                @break
                                                @case(4)
                                                <label for="" class="badge badge-soft-warning">Repeat</label>
                                                @break
                                            @endswitch
                                        @else
                                            <span class="text-warning">Block Session</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('show-appointment-details', $appoint->slug)}}"> <i class="bx bxs-book-open"></i> View</a>
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
    <div class="modal right fade" id="client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel2">Add New Appointment</h4>
                </div>

                <div class="modal-body">
                    <form action="{{route('add-calendar-event')}}" data-parsley-validate="" method="post" id="individualSessionForm">
                        @csrf
                        <div class="form-group">
                            <label for="">Invitee</label>
                            <select name="invitee" id="" class="form-control" data-parsley-required-message="Who are you inviting?" required>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->first_name ?? '' }} {{$client->last_name ?? '' }}</option>
                                @endforeach
                            </select>
                            @error('invitee') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Appointment Type</label>
                            <select name="appointmentType" id="" class="form-control" data-parsley-required-message="Choose an appointment type" required>
                                @foreach($appointmentTypes as $type)
                                    <option value="{{$type->id}}">{{$type->name ?? '' }} - {{$type->length ?? '' }} minutes </option>
                                @endforeach
                            </select>
                            @error('appointmentType') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Contact Type</label>
                            <select name="contactType" id="" class="form-control" data-parsley-required-message="Choose contact type" required>
                                <option value="1">Video Call</option>
                                <option value="2">In Person</option>
                                <option value="3">Phone Call</option>
                            </select>
                            <input type="hidden" name="sessionType" value="1">
                            @error('contactType') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">When</label>
                            <input type="datetime-local" name="when" class="form-control" data-parsley-required-message="When is this event taking place?" required>
                            @error('when') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Note</label>
                            <textarea name="note" id="note" data-parsley-required-message="Leave a note that best describes it." required placeholder="Leave a note here..." rows="5" style="resize: none" class="form-control"></textarea>
                            @error('note') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Add Individual Session <i class="bx bx-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="/vectormap/jquery-jvectormap-in-mill.js"></script>
    <script src="/vectormap/jquery-jvectormap-us-aea-en.js"></script>
    <script src="/vectormap/jquery-jvectormap-uk-mill-en.js"></script>
    <script src="/vectormap/jquery-jvectormap-au-mill.js"></script>
    <script src="/assets/js/axios.min.js"></script>
    <script src="/js/toastify.js"></script>
    <script src="/js/nprogress.js"></script>
    <script src="/js/task.js"></script>

    <script src="/js/chart.js"></script>
    <script>
        const incomeData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const attendanceData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const medicationData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const url = "{{route('revenue-statistics') }}";
        $(document).ready(function(){
            const viewId = 113491207;
            const gaUrl = `https://www.googleapis.com/analytics/v3/data/ga?ids=ga%${viewId}
&start-date=7daysAgo
&end-date=yesterday
&metrics=ga%3Apageviews
&dimensions=ga%3ApagePath%2Cga%3ApageTitle
&sort=-ga%3Apageviews
&max-results=10`;
            axios.get(gaUrl)
            .then(res=>{
                console.log(res)
            })
            .catch(err=>{
                console.log(err);
            });
            $('.dateInputs').hide();
            $('#filterType').on('change', function(e){
                e.preventDefault();
                if($(this).val() == 1){
                    $('.dateInputs').hide();
                }else{
                    $('.dateInputs').show();
                }
            });

            /*Revenue chart*/
            axios.get(url)
                .then(res=> {
                    const income = res.data.income;
                    income.map((inc) => {
                        switch (inc.month) {
                            case 1:
                                plotGraph(1, 1, inc.amount);
                                break;
                            case 2:
                                plotGraph(2, 1, inc.amount);
                                break;
                            case 3:
                                plotGraph(3, 1, inc.amount);
                                break;
                            case 4:
                                plotGraph(4, 1, inc.amount);
                                break;
                            case 5:
                                plotGraph(5, 1, inc.amount);
                                break;
                            case 6:
                                plotGraph(6, 1, inc.amount);
                                break;
                            case 7:
                                plotGraph(7, 1, inc.amount);
                                break;
                            case 8:
                                plotGraph(8, 1, inc.amount);
                                break;
                            case 9:
                                plotGraph(9, 1, inc.amount);
                                break;
                            case 10:
                                plotGraph(10, 1, inc.amount);
                                break;
                            case 11:
                                plotGraph(11, 1, inc.amount);
                                break;
                            case 12:
                                plotGraph(12, 1, inc.amount);
                                break;
                        }

                    });
                    //then
                    var options = {
                            chart: { height: 360, type: "bar", stacked: !0, toolbar: { show: !1 }, zoom: { enabled: !0 } },
                            plotOptions: { bar: { horizontal: !1, columnWidth: "15%", endingShape: "rounded" } },
                            dataLabels: { enabled: !1 },
                            series: [
                                { name: "Cashflow", data: incomeData },
                            ],
                            xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"] },
                            colors: ["#34c38f", "#f1b44c"],
                            legend: { position: "bottom" },
                            fill: { opacity: 1 },
                        },
                        chart = new ApexCharts(document.querySelector("#stacked-column-chart"), options);
                    chart.render();

                });

            /*Attendance vs Medication*/
            const atttendacnceUrl = "{{route('attendance-medication-chart')}}";
            axios.get(atttendacnceUrl)
                .then(res=> {
                    const attendance = res.data.attendance;
                    const medication = res.data.medication;
                    attendance.map((attend) => {
                        switch (attend.month) {
                            case 1:
                                plotAttendanceMedicationGraph(1, 1, attend.amount);
                                break;
                            case 2:
                                plotAttendanceMedicationGraph(2, 1, attend.amount);
                                break;
                            case 3:
                                plotAttendanceMedicationGraph(3, 1, attend.amount);
                                break;
                            case 4:
                                plotAttendanceMedicationGraph(4, 1, attend.amount);
                                break;
                            case 5:
                                plotAttendanceMedicationGraph(5, 1, attend.amount);
                                break;
                            case 6:
                                plotAttendanceMedicationGraph(6, 1, attend.amount);
                                break;
                            case 7:
                                plotAttendanceMedicationGraph(7, 1, attend.amount);
                                break;
                            case 8:
                                plotAttendanceMedicationGraph(8, 1, attend.amount);
                                break;
                            case 9:
                                plotAttendanceMedicationGraph(9, 1, attend.amount);
                                break;
                            case 10:
                                plotAttendanceMedicationGraph(10, 1, attend.amount);
                                break;
                            case 11:
                                plotAttendanceMedicationGraph(11, 1, attend.amount);
                                break;
                            case 12:
                                plotAttendanceMedicationGraph(12, 1, attend.amount);
                                break;
                        }

                    });
                    medication.map((med) => {
                        switch (med.month) {
                            case 1:
                                plotAttendanceMedicationGraph(1, 2, med.amount);
                                break;
                            case 2:
                                plotAttendanceMedicationGraph(2, 2, med.amount);
                                break;
                            case 3:
                                plotAttendanceMedicationGraph(3, 2, med.amount);
                                break;
                            case 4:
                                plotAttendanceMedicationGraph(4, 2, med.amount);
                                break;
                            case 5:
                                plotAttendanceMedicationGraph(5, 2, med.amount);
                                break;
                            case 6:
                                plotAttendanceMedicationGraph(6, 2, med.amount);
                                break;
                            case 7:
                                plotAttendanceMedicationGraph(7, 2, med.amount);
                                break;
                            case 8:
                                plotAttendanceMedicationGraph(8, 2, med.amount);
                                break;
                            case 9:
                                plotAttendanceMedicationGraph(9, 2, med.amount);
                                break;
                            case 10:
                                plotAttendanceMedicationGraph(10, 2, med.amount);
                                break;
                            case 11:
                                plotAttendanceMedicationGraph(11, 2, med.amount);
                                break;
                            case 12:
                                plotAttendanceMedicationGraph(12, 2, med.amount);
                                break;
                        }

                    });
                    //then
                    const options2 = {
                            chart: { height: 360, type: "bar", stacked: !0, toolbar: { show: !1 }, zoom: { enabled: !0 } },
                            plotOptions: { bar: { horizontal: !1, columnWidth: "15%", endingShape: "rounded" } },
                            dataLabels: { enabled: !1 },
                            series: [
                                { name: "Attendance", data: attendanceData },
                                { name: "Medication", data: medicationData },
                            ],
                            xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"] },
                            colors: ["#C70B31", "#34485F"],
                            legend: { position: "bottom" },
                            fill: { opacity: 1 },
                        },
                        chart = new ApexCharts(document.querySelector("#attendanceMedication"), options2);
                    chart.render();

                });
        });

        function plotGraph(index,type, value){
            if(parseInt(type) === 1){
                incomeData[index-1] = value;
            }
        }
        function plotAttendanceMedicationGraph(index,type, value){
            if(parseInt(type) === 1){
                attendanceData[index-1] = value;
            }else{
                medicationData[index-1] = value;
            }
        }
    </script>
@endsection
