
@extends('layouts.master-layout')
@section('current-page')
    Messages
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
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
                @if(session()->has('success'))
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{route('compose-sms')}}"  class="btn btn-primary"> Compose Message <i class="bx bxs-envelope"></i> </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header">
                                        Messages
                                    </div>
                                    <div class="table-responsive mt-3">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th class="">#</th>
                                                <th class="wd-15p">Date</th>
                                                <th class="wd-15p">Frequency</th>
                                                <th class="wd-15p">Status</th>
                                                <th class="wd-15p">Excerpt</th>
                                                <th class="wd-15p"># of Receiver(s)</th>
                                                <th class="wd-15p">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $index = 1; @endphp
                                            @foreach($messages as $message)
                                                <tr>
                                                    <td>{{$index++}}</td>
                                                    <td>{{date('d M, Y h:ia', strtotime($message->created_at))}}</td>
                                                    <td>
                                                        @if(!is_null($message->bulk_frequency))
                                                            {{ $message->getFrequency->label ?? '' }}
                                                        @else
                                                            @if($message->recurring == 2)
                                                                <span class="text-warning">Time-bond</span>
                                                            @else
                                                                <span class="text-primary">Instant</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($message->recurring == 1)
                                                            @if($message->recurring_active == 1)
                                                                <span class="text-success">Active</span>
                                                            @else
                                                                <span class="text-danger">Deactivated</span>
                                                            @endif
                                                        @elseif($message->recurring == 2)
                                                            @if($message->recurring_active == 1)
                                                                <span class="text-warning">Processing</span>
                                                            @else
                                                                <span class="text-primary">Done</span>
                                                            @endif
                                                        @else
                                                            <span class="text-primary">Done</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ strlen(strip_tags($message->message)) > 20 ? substr(strip_tags($message->message),0,20).'...' : strip_tags($message->message) }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <span class="table-danger ">{{ number_format(count(explode(',', $message->sent_to))) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" data-bs-target="#messageDetail_{{$message->id}}" data-bs-toggle="modal"> <i class="bx bxs-chart"></i> View</a>
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
    </div>




    @foreach($messages as $message)

                <div class="modal right fade" id="messageDetail_{{$message->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header" >
                                <h6 class="modal-title text-info text-uppercase" id="myModalLabel2">Message Details</h6>
                                <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body text-wrap">
                                @if($message->recurring == 1)
                                    <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                                        <input class="form-check-input" type="checkbox" data-bs-target="#promptModal_{{$message->id}}" data-bs-toggle="modal" {{ $message->recurring_active == 1 ? 'checked' : null }}>
                                        <label class="form-check-label" for="activateDeactivate">Toggle to Activate or Deactivate</label>
                                    </div>
                                @endif

                                <p><strong>Sender ID:</strong> {{$message->sender_id ?? '' }} </p>
                                <p><strong>Ref. Code:</strong> {{$message->slug ?? '' }} </p>
                                <p><strong>Message Type:</strong>
                                    @if($message->recurring == 1)
                                        <span class="text-success">Recurring</span>
                                    @elseif($message->recurring == 2)
                                        <span class="text-warning">Time-bond</span>
                                    @else
                                        <span class="text-primary">Instant</span>
                                    @endif
                                </p>
                                <p><strong>Status:</strong>
                                    @if($message->recurring_active == 1)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Deactivated</span>
                                    @endif
                                </p>
                                @if(!is_null($message->bulk_frequency))
                                    <p>
                                        <strong>Frequency:</strong> {{ $message->getFrequency->label ?? '' }}
                                    </p>
                                @endif
                                @if(!is_null($message->phone_group))
                                    <p>
                                        <strong>Phone Group:</strong> {{ $message->getPhoneGroup->group_name ?? '' }}
                                    </p>
                                @endif
                                <p> <strong>Date & Time:</strong> {{date('d M, Y h:ia', strtotime($message->created_at))}}</p>
                                @if($message->recurring_active == 1)
                                    <p>
                                        <strong>Next Schedule:</strong> {{ date('d M, Y h:ia', strtotime($message->next_schedule)) }}
                                    </p>
                                @endif
                                <p><strong>Message:</strong> {!! $message->message ?? ''  !!}</p>

                                <div class="text-muted mt-4 bg-light p-2">
                                    <h6 class="font-size-12 mt-4 text-info">Sent to {{ count(explode(',', $message->sent_to)) ?? 0 }} contacts</h6>
                                    <div style="overflow-y: scroll; height: 300px;">
                                        @for($i = 0; $i < count(explode(',', $message->sent_to)); $i++)
                                            <p><i class="mdi mdi-chevron-right text-primary me-1"></i>
                                                {{ explode(',', $message->sent_to)[$i] ?? '' }},
                                            </p>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="promptModal_{{$message->id}}" class="modal fade bs-example-modal-sm" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm" >
                        <div class="modal-content" >
                            <div class="modal-header" >
                                <h6 class="modal-title text-info" id="mySmallModalLabel">Are you sure?</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" >
                                <form action="{{ route('update-message-status') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <p class="text-wrap">Are you sure you want to {!!  $message->recurring_active == 0 ? "<span class='text-success'> activate </span>" : "<span class='text-danger'> deactivate </span>" !!} this message?</p>
                                    </div>
                                    <input type="hidden" name="messageId" value="{{$message->id}}">
                                    <input type="hidden" name="status" value="{{ $message->recurring_active == 0 ? 1 : 0 }}">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Yes, proceed</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

    @endforeach

@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <script src="/assets/js/pages/datatables.init.js"></script>

@endsection
