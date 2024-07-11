@extends('layouts.master-layout')
@section('title')
    Ticket Details
@endsection
@section('current-page')
    Ticket Details
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">

                        <div class="card-header">
                           <div class="btn-group">
                               <a href="{{ url()->previous() }}"  class="btn btn-primary  mb-3">Go Back <i class="bx bx-arrow-back"></i> </a>
                               @if($ticket->status == 0)
                               <a href="javascript:void(0);" data-bs-target="#closeTicket" data-bs-toggle="modal"  class="btn btn-secondary  mb-3">Close Ticket <i class="bx bx-check-circle"></i> </a>
                               @endif
                           </div>
                        </div>
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


                            <div class="row" >
                                <div class="col-12" >
                                    <div class="right-box-padding" >
                                        <div class="read-content" >
                                            <div class="media pt-3 d-sm-flex d-block justify-content-between" >
                                                <div class="clearfix mb-3 d-flex" >
                                                    <img class="me-3 rounded" width="70" height="70" alt="image" src="{{url('storage/'.$ticket->getAuthor->getUserOrganization->logo) }}">
                                                    <div class="media-body me-2 ml-3" >
                                                        <h5 class="text-primary mb-0 mt-1">{{$ticket->getCompany->organization_name ?? '' }}</h5>
                                                        <p class="mb-0"><strong>Email: </strong>{{$ticket->getCompany->email ?? '' }}</p>
                                                        <p class="mb-0"><strong>Mobile No.: </strong>{{$ticket->getCompany->phone_no ?? '' }}</p>
                                                        <p class="mb-0"><strong>Address: </strong>{{$ticket->getCompany->address ?? '' }}</p>
                                                    </div>
                                                </div>
                                                <div class="clearfix mb-3 d-flex" >
                                                    <img class="me-3 rounded" width="70" height="70" alt="image" src="{{url('storage/'.$ticket->getAuthor->image)}}">
                                                    <div class="media-body me-2 ml-3" >
                                                        <h5 class="text-primary mb-0 mt-1">{{$ticket->getAuthor->title ?? '' }} {{$ticket->getAuthor->first_name ?? '' }} {{$ticket->getAuthor->last_name ?? '' }} {{$ticket->getAuthor->other_names ?? '' }}</h5>
                                                        <p class="mb-0"><strong>Email: </strong>{{$ticket->getAuthor->email ?? '' }}</p>
                                                        <p class="mb-0"><strong>Mobile No.: </strong>{{$ticket->getAuthor->cellphone_no ?? '' }}</p>
                                                        <p class="mb-0"><strong>Occupation: </strong>{{$ticket->getAuthor->occupation ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="media mb-2 mt-3">
                                                <div class="media-body">
                                                    <h5 class="my-1 text-primary">{{$ticket->subject ?? '' }}</h5>
                                                    <p class="read-content-email">
                                                        <strong>Status:</strong> {!! $ticket->status == 0 ? "<span class='text-warning'>Open</span>" : "<span class='text-success'>Closed</span>" !!} <span class="pull-end">
                                                         &nbsp; &nbsp; &nbsp;   <strong>Date:</strong> {{date('d M, Y h:iA', strtotime($ticket->created_at))}}</span>
                                                    </p>

                                                </div>
                                            </div>
                                            <div class="read-content-body">
                                                {!! $ticket->content ?? '' !!}
                                                <hr>
                                            </div>
                                            <div class="read-content-attachment">
                                                <h6><i class="fa fa-download mb-2"></i> Attachment</h6>
                                                @if(!empty($ticket->attachment))
                                                    <div class="row attachment">
                                                        <div class="col-auto">
                                                            <a class="btn btn-primary btn-xxs" href="{{route('download-attachment',$ticket->attachment)}}" >Download Attachment <i class="bx bx-download"></i> </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row attachment">
                                                        <div class="col-auto">
                                                            <p class="text-warning">No Attachment</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <h5 class="mt-3 mb-3 text-uppercase">Response/Reply</h5>
                                            <div id="DZ_W_Todo3" class="widget-media dz-scroll height370 ps ps--active-y" style="overflow-y: scroll; height: 400px;">
                                                <ul class="timeline">
                                                    @if(count($responses) > 0)
                                                        @foreach($responses as $response)
                                                            @if($response->user_type == 0)
                                                                <li class="d-flex justify-content-end mr-3">
                                                                    <div class="timeline-panel" >
                                                                        <div class="d-flex">
                                                                            <div class="flex-shrink-0 me-3">
                                                                                <img class="rounded avatar-sm" src="{{url('storage/'.$response->getRepliedBy->image)}}" alt="{{$response->getRepliedBy->first_name ?? '' }}">
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <h5>{{ $response->getRepliedBy->title ?? '' }} {{ $response->getRepliedBy->first_name ?? '' }} {{ $response->getRepliedBy->last_name ?? '' }} {{ $response->getRepliedBy->other_names ?? '' }}
                                                                                    <sup class="badge badge-soft-danger"><small>Customer</small></sup>
                                                                                </h5>
                                                                                <p class="mb-0">{{$response->response ?? '' }}</p>
                                                                                <p><small class="text-info">{{date('d M, Y h:ia', strtotime($response->created_at))}}</small></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @else
                                                                <li class="d-flex justify-content-start">
                                                                    <div class="timeline-panel" >
                                                                        <div class="d-flex">
                                                                            <div class="flex-shrink-0 me-3">
                                                                                <img class="rounded avatar-sm" src="{{url('storage/'.$response->getRepliedBy->image)}}" alt="{{$response->getRepliedBy->first_name ?? '' }}">
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <h5>{{ $response->getRepliedBy->title ?? '' }} {{ $response->getRepliedBy->first_name ?? '' }} {{ $response->getRepliedBy->last_name ?? '' }} {{ $response->getRepliedBy->other_names ?? '' }}
                                                                                    <sup class="badge badge-soft-success"><small>Admin</small></sup>
                                                                                </h5>
                                                                                <p class="mb-0">{{$response->response ?? '' }}</p>
                                                                                <p><small class="text-info">{{date('d M, Y h:ia', strtotime($response->created_at))}}</small></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <li>
                                                            <div class="timeline-panel" >
                                                                <div class="media-body">
                                                                    <p class="mb-1">No response yet.</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                </ul>
                                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
                                                    </div>
                                                </div>
                                                <div class="ps__rail-y" style="top: 0px; height: 370px; right: 0px;" >
                                                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 222px;" >
                                                    </div>
                                                </div>
                                            </div>
                                            @if($ticket->status == 0)
                                                <hr>
                                                <form action="{{route('ticket-reply')}}" method="post">
                                                    @csrf
                                                    <div class="mb-3 pt-3">
                                                        <textarea name="response" class="form-control" placeholder="Type reply here..." spellcheck="false">{{old('response')}}</textarea>
                                                        @error('response') <i>{{$message}}</i> @enderror
                                                        <input type="hidden" name="ticketId" value="{{$ticket->id}}">
                                                        <input type="hidden" name="userType" value="0">
                                                    </div>
                                                    <div class="text-end">
                                                        <button class="btn btn-primary " type="submit">Submit</button>
                                                    </div>
                                                </form>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>






                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closeTicket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
        <div class="modal-dialog modal-lg w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase" style="text-align: center;" id="myModalLabel2">Close Ticket</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('close-ticket')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <p><strong class="text-danger">Note:</strong> This action cannot be undone. Are you sure you want to close this ticket?</p>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <input type="hidden" name="ticketID" value="{{$ticket->id}}">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Close Ticket <i class="bx bxs-check-circle"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')

@endsection
