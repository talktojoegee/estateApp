@extends('layouts.master-layout')
@section('current-page')
    Customer Profile
@endsection
@section('extra-styles')
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/bootstrap-rating/bootstrap-rating.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
        .checked {
            color: orange;
        }
    </style>

    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />


@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
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

    <div class="card">

        <div class="row">
            <div class="col-xl-4">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">Customer Details</h5>
                                    <p>Explore customer profile</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="/assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="avatar-md profile-user-wid mb-4 align-content-center" style="width: 120px; height: 120px;" >
                                    <img style="width: 120px; height: 120px;" src="{{url('storage/avatars/avatar.png')}}" alt="" class="img-thumbnail rounded-circle">
                                </div>
                                <h5 class="font-size-15">{{$client->first_name ?? '' }} {{$client->last_name ?? '' }}</h5>
                            </div>
                            <div class="col-md-12 col-sm-12" style="display: none;">
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm"> Convert to client <i class="bx bxs-check-circle"></i></button>
                                    <button class="btn btn-danger btn-sm"> Delete <i class="bx bxs-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Personal Information</h4>
                        <div class="table-responsive">
                            <table class="table table-nowrap mb-0">
                                <tbody>
                                <tr>
                                    <th scope="row">Full Name :</th>
                                    <td>{{$client->first_name ?? '' }} {{$client->last_name ?? '' }} <span style="cursor: pointer;" data-bs-target="#editClientModal" data-bs-toggle="modal"> <i class="bx bx-pencil text-warning"></i> </span> </td>
                                </tr>
                                <tr>
                                    <th scope="row">Mobile :</th>
                                    <td>{{$client->phone ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">E-mail :</th>
                                    <td>{{$client->email ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address :</th>
                                    <td>{{$client->street ?? '' }}, {{$client->city ?? '' }} {{$client->state ?? '' }} {{$client->code ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Valuation :</th>
                                    <td>{{env('APP_CURRENCY')}}{{ number_format($client->getCustomerValuation($client->id) ?? 0,2) ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"># of Properties :</th>
                                    <td>{{ number_format($client->getNumberOfProperties($client->id) ?? 0) ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Added By :</th>
                                    <td>{{$client->getAddedBy->first_name  ?? '' }} {{$client->getAddedBy->last_name  ?? '' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#profile1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Notes</span>
                                </a>
                            </li>
                            <li class="nav-item" >
                                <a class="nav-link" data-bs-toggle="tab" href="#properties" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Properties</span>
                                </a>
                            </li>
                            <li class="nav-item" >
                                <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Documents</span>
                                </a>
                            </li>
                            <li class="nav-item" >
                                <a class="nav-link" data-bs-toggle="tab" href="#sms" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">SMS</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-bs-toggle="tab" href="#home1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Activity</span>
                                </a>
                            </li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane" id="home1" role="tabpanel">
                                <div class="mt-3" style="height: 500px; overflow-y: scroll;">
                                    <ul class="verti-timeline list-unstyled">
                                        @foreach($client->getLogs as $log)
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
                            <div class="tab-pane active" id="profile1" role="tabpanel">
                                <form action="{{route('leave-lead-note')}}" id="leadNoteForm" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">Rating <sup class="text-danger" style="color: #ff0000;">*</sup> </label>
                                        <div class="rating-star">
                                            <input type="hidden" name="rating" class="rating-tooltip" data-filled="mdi mdi-star text-primary" data-empty="mdi mdi-star-outline text-muted"/>
                                        </div>
                                        @error('rating') <i class="text-danger">{{$message}}</i> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Add Note <sup class="text-danger" style="color: #ff0000;">*</sup></label>
                                        <textarea name="addNote" data-parsley-required-message="Leave a note in the box provided above" required style="resize: none;" cols="30" rows="4" placeholder="Type Note here..." class="form-control">{{old('addNote')}}</textarea>
                                        @error('addNote') <i class="text-danger">{{$message}}</i> @enderror
                                        <input type="hidden" name="type" value="1">
                                    </div>
                                    <input type="hidden" value="{{$client->id}}" name="leadId">
                                    <div class="form-group mt-1 float-end">
                                        <button type="submit" class="btn btn-info btn-lg">Add Note <i class="bx bxs-note"></i> </button>
                                    </div>
                                </form>
                                <div class="mt-5" style="height: 330px; overflow-y: scroll;">
                                    @if($client->getLeadNotes->count() > 0)
                                        @foreach($client->getLeadNotes as $note)
                                            <div class="d-flex py-3">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                            <i class="bx bxs-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="font-size-14 mb-1">{{$note->getAddedBy->first_name ?? '' }} {{$note->getAddedBy->last_name ?? '' }} <small class="text-muted float-end">{{date('d M, Y h:ia', strtotime($note->created_at))}}</small></h6>
                                                    <p class="text-muted">{{$note->note ?? '' }}</p>
                                                    <div class="">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="fa fa-star {{ $i <= $note->rating ? 'checked' : '' }}"></span>
                                                        @endfor
                                                    </div>
                                                    <div>
                                                        <a href="javascript: void(0);" data-bs-target="#deleteNote_{{$note->id}}" data-bs-toggle="modal" style="cursor:pointer;" class="text-danger"><i class="mdi mdi-trash-can"></i> Trash</a>
                                                        <a href="javascript: void(0);" data-bs-target="#editNote_{{$note->id}}" data-bs-toggle="modal"  style="cursor:pointer; margin-left: 15px;" class="text-warning ml-3"><i class="mdi mdi-lead-pencil"></i> Edit</a>
                                                    </div>
                                                    <div class="modal fade" id="editNote_{{$note->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" >
                                                                    <h4 class="modal-title" id="myModalLabel2">Edit Note</h4>
                                                                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{route('edit-lead-note')}}" id="leadNoteForm" method="post">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for=""> Note</label>
                                                                            <textarea name="addNote" data-parsley-required-message="Leave a note in the box provided above" required style="resize: none;" cols="30" rows="4" placeholder="Type Note here..." class="form-control">{{$note->note ?? '' }}</textarea>
                                                                            @error('addNote') <i class="text-danger">{{$message}}</i> @enderror
                                                                        </div>
                                                                        <input type="hidden" value="{{$note->id}}" name="noteId">
                                                                        <input type="hidden" value="{{$client->id}}" name="leadId">
                                                                        <div class="form-group mt-1 float-end">
                                                                            <button type="submit" class="btn btn-info btn-lg">Save changes <i class="bx bxs-note"></i> </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="deleteNote_{{$note->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" >
                                                                    <h4 class="modal-title" id="myModalLabel2">Delete Note</h4>
                                                                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{route('delete-lead-note')}}" id="leadNoteForm" method="post">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <p>Are you sure you want to delete this note?</p>
                                                                        </div>
                                                                        <input type="hidden" value="{{$note->id}}" name="noteId">
                                                                        <input type="hidden" value="{{$client->id}}" name="leadId">
                                                                        <div class="form-group mt-1 float-end">
                                                                            <button type="submit" class="btn btn-danger btn-lg">Delete <i class="bx bxs-trash"></i> </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <h6 class="text-center">Be the first to leave a note.</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="documents" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-12 col-md-12 col-lg-12 d-flex justify-content-end">
                                               <button class="btn btn-primary" data-bs-target="#newFileModal" data-bs-toggle="modal"> <i class="bx bx-plus-circle"></i> New File(s)</button>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card">
                                                <div class="modal-header">
                                                    <div class="modal-title text-uppercase">Browse <code>{{ $client->first_name ?? ''  }} {{ $client->last_name ?? ''  }}</code> documents </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @if(count($client->getCustomerFiles) > 0)
                                                            @foreach ($client->getCustomerFiles as $file)
                                                                @include('storage.partials._lead-switch-format')
                                                            @endforeach
                                                        @else
                                                            <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                                                                <p>No files or documents uploaded for <code>{{$client->first_name ?? '' }} {{$client->last_name ?? '' }}</code>. </p>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="properties" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="col-xl-12 col-md-12 col-lg-12">
                                            <p>{{ $client->first_name ?? ''  }} {{ $client->last_name ?? '' }} has a total of <code>{{ number_format(count($client->getCustomerListOfProperties($client->id) ?? 0)) }}</code> with <strong>{{env('APP_NAME')}}</strong></p>
                                            <div class="table-responsive">
                                                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                                    <thead>
                                                    <tr>
                                                        <th class="">#</th>
                                                        <th class="wd-15p">Estate</th>
                                                        <th class="wd-15p">House No.</th>
                                                        <th class="wd-15p">Property Name</th>
                                                        <th class="wd-15p" style="text-align: right;">Price(₦)</th>
                                                        <th class="wd-15p">Status</th>
                                                        <th class="wd-15p">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($client->getCustomerListOfProperties($client->id) as $key => $property)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{$property->getEstate->e_name ?? '' }}</td>
                                                            <td>{{ $property->house_no ?? '' }}</td>
                                                            <td>
                                                                <a href="{{route('show-property-details', ['slug'=>$property->slug])}}">
                                                                    <div class="d-flex">
                                                                        <div class="flex-shrink-0 align-self-center me-3">
                                                                            <img src="/assets/drive/property/{{$property->getGalleryFeaturedImageByPropertyId($property->id)->attachment ?? 'placeholder.png' }}" alt="{{$property->property_name ?? '' }}" class="rounded-circle avatar-xs">
                                                                        </div>
                                                                        <div class="flex-grow-1 overflow-hidden">
                                                                            <h6 class="text-truncate font-size-14 mb-1">{{ substr($property->property_name,0,35).'...' ?? ''  }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                            <td class="text-right" style="text-align: right;">{{ number_format($property->price,2)  }}</td>

                                                            <td>
                                                                @switch($property->status)
                                                                    @case(0)
                                                                    <label class='text-primary'>Available</label>
                                                                    @break
                                                                    @case(1)
                                                                    <label class='text-info'>Rented</label>
                                                                    @break
                                                                    @case(2)
                                                                    <label class='text-warning'>Sold</label>
                                                            @break
                                                            @endswitch
                                                            <td>
                                                                <div class="btn-group">
                                                                    <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" target="_blank" href="{{route('show-property-details', [ 'slug'=>$property->slug])}}" > <i class="bx bxs-book-open"></i> View</a>
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
                            <div class="tab-pane" id="sms" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="col-xl-12 col-md-12 col-lg-12">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#sendSMS" class="btn btn-sm btn-primary">Send SMS <i class="bx bx-envelope"></i> </button>
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

    <div class="modal right fade" id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h4 class="modal-title" id="myModalLabel2">Edit Lead Profile</h4>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('edit-client-profile')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-1">
                            <label for="">Profile Photo</label> <br>
                            <input type="file" name="profilePhoto" class="form-control-file">
                            @error('profilePhoto') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <input type="hidden" name="clientId" value="{{$client->id}}">
                        <div class="form-group mt-3">
                            <label for="">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="firstName" placeholder="First Name" value="{{$client->first_name ?? '' }}" class="form-control">
                            @error('firstName') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lastName" value="{{$client->last_name ?? '' }}" placeholder="Last Name" class="form-control">
                            @error('lastName') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Date of Birth </label>
                            <input type="date" value="{{date('Y-m-d', strtotime($client->birth_date))  }}" name="birthDate"  placeholder="Date of Birth" class="form-control">
                            @error('birthDate') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Mobile Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobileNo" value="{{$client->phone ?? '' }}" placeholder="Mobile Phone Number" class="form-control">
                            @error('mobileNo') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for="">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" readonly value="{{$client->email ?? '' }}" placeholder="Email Address" class="form-control">
                            @error('email') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-1">
                            <label for=""> Address</label>
                            <textarea name="address" style="resize: none;" placeholder="Type address here..." class="form-control">{{$client->street ?? '' }}</textarea>
                            @error('address') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button class="btn btn-primary  waves-effect waves-light">Save changes <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="sendSMS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h4 class="modal-title" id="myModalLabel2">Send SMS</h4>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="mainForm" >
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label d-flex justify-content-between">Sender ID
                                            </label>
                                            <input required data-parsley-required-message="Indicate the sender ID" type="text" id="senderId" value="{{env('SENDER_ID')}}" disabled class="form-control">
                                            @error('senderId') <i class="text-danger">{{$message}}</i>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label class="form-label d-flex justify-content-between">To:
                                            </label>
                                            <input required data-parsley-required-message="Enter the phone number of who you want to message" type="text" value="{{$client->phone ?? ''}}" name="phone_numbers"  id="phone_numbers" class="form-control">
                                            @error('phone_numbers') <i class="text-danger">{{$message}}</i>@enderror
                                            <input type="hidden" name="client" id="client" value="{{ $client->id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="from-message">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Compose message</label>
                                            <textarea data-parsley-required-message="Type your message in this field..." required onkeyup="getCharacterLength()" name="message" rows="5" id="message" style="resize: none" placeholder="Compose message here..."
                                                      class="form-control">{{old('message')}}</textarea>
                                            @error('message') <i class="text-danger">{{$message}}</i>@enderror
                                            <p class="text-right text-danger" id="character-counter">0</p>
                                            <span><strong class="text-danger">Note: </strong> One page of message consists of <code>160</code> characters.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right d-flex justify-content-center">
                                <button type="button" id="previewMessage" class="btn btn-primary w-50">Preview Message <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                    <div id="previewWrapper"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="newFileModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <div class="modal-header">
                            <div class="modal-title text-uppercase">New Attachments</div>
                        </div>
                        <p class="card-title-desc mt-3">Upload documents for <code>{{$client->first_name ?? '' }} {{ $client->last_name ?? '' }}</code>  </p>

                        <form action="{{route('upload-files')}}" autocomplete="off" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="">File Name</label>
                                <input type="text" name="fileName" placeholder="File Name" class="form-control">
                                @error('fileName')
                                <i class="text-danger mt-2">{{$message}}</i>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Attachment</label>
                                <input type="file" name="attachments[]" class="form-control-file" multiple>
                                @error('attachment')
                                <i class="text-danger mt-2">{{$message}}</i>
                                @enderror
                                <input type="hidden" name="folder" value="0">
                                <input type="hidden" name="lead" value="{{ $client->id }}">
                            </div>
                            <hr>
                            <div class="form-group d-flex justify-content-center">
                                <div class="btn-group">
                                    <a href="{{url()->previous()}}" class="btn btn-warning btn-mini"><i class="bx bx-left-arrow mr-2"></i> Go Back</a>
                                    <button type="submit" class="btn btn-primary"><i class="bx bx-cloud-upload mr-2"></i> Upload File(s)</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/libs/bootstrap-rating/bootstrap-rating.min.js"></script>
    <script src="/assets/js/pages/rating-init.js"></script>
    <script src="/js/parsley.js"></script>
    <script src="/js/axios.min.js"></script>

    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>

    <script>
        $(document).ready(function(){
            let route = "{{route('inline-preview-message')}}";

            $('#previewMessage').on('click', function(){
                let senderId = $('#senderId').val();
                let client = $('#client').val();
                let message = $('#message').val();
                let phoneNumbers = $('#phone_numbers').val();
                axios.post(route, {
                    senderId:senderId,
                    client:client,
                    message:message,
                    phone_numbers:phoneNumbers,
                })
                    .then(res=>{
                        $('#mainForm').hide();
                        $('#previewWrapper').html(res.data);
                    })
                    .catch(err=>{
                    });
            });
            $('#cancelBtn').on('click',function(){
                $('#mainForm').show();
                $('#previewWrapper').hide();
            })

            $('#mainForm').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:button', function() {
                    return true;
                });

        });
        function getCharacterLength() {
            x =  document.getElementById("message").value;
            y = x.length;
            document.getElementById("character-counter").innerHTML = y;
        }
    </script>
@endsection
