@extends('layouts.master-layout')
@section('title')
    Manage Tickets
@endsection
@section('current-page')
    Manage Tickets
@endsection
@section('extra-styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col">
                                <p class="mb-1">Open</p>

                                <h5 class="mb-0 number-font">{{ number_format($tickets->where('status', '=',0)->count() ) }}</h5>
                            </div>
                            <div class="col-auto mb-0">
                                <div class="dash-icon text-secondary1">
                                    <i class="bx bxs-briefcase-alt-2"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Total Open Tickets </span></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6" >
                <div class="card" >
                    <div class="card-body" >
                        <div class="row mb-1" >
                            <div class="col" >
                                <p class="mb-1">Closed</p>
                                <h5 class="mb-0 number-font">{{number_format( $tickets->where('status', '=',1)->count() )}}</h5>
                            </div>
                            <div class="col-auto mb-0" >
                                <div class="dash-icon text-orange" >
                                    <i class="bx bxs-book-open"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Total Closed Tickets</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    @if(\Illuminate\Support\Facades\Auth::user()->type > 1)
                    <div class="card-header">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addNewProduct" class="btn btn-primary  mb-3">New Ticket <i class="bx bx bx-highlight"></i> </a>
                    </div>
                    @endif
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



                        <div class="table-responsive mt-3">
                            <table id="datatable1" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                                <thead style="position: sticky;top: 0">
                                <tr role="row">
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >S/No.</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Date</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Ticket No.</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Subject</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Status</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $key => $ticket)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{date('d M, Y h:ia', strtotime($ticket->created_at))}}</td>
                                        <td>{{ strtoupper($ticket->ref_no) ?? '' }}</td>
                                        <td>{{ $ticket->subject ?? '' }}</td>
                                        <td>
                                            {!! $ticket->status == 0 ? "<span class='text-warning'>Open</span>" : "<span class='text-success'>Closed</span>" !!}
                                        </td>
                                        <td>
                                            <a href="{{route('view-ticket', $ticket->ref_no)}}">View</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
        <div class="modal-dialog modal-lg w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase" style="text-align: center;" id="myModalLabel2">Submit New Ticket</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('tickets')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Subject</label>
                                <input type="text" value="{{ old('subject') }}" name="subject" placeholder="Subject" class="form-control">
                                @error('subject') <i class="text-danger">{{$message}}</i>@enderror
                            </div>

                            <div class="form-group mt-3 col-md-6">
                                <label for="">Attachment <small>(Optional)</small></label> <br>
                                <input type="file" accept="application/pdf" multiple name="attachment" class="form-control-file">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for="">Message <span class="text-danger">*</span></label>
                                <textarea name="supportContent" id="description" placeholder="Type request details here..."  style="resize: none;" class="form-control">{{ old('supportContent') }}</textarea>
                                @error('supportContent') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit Ticket <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>

    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <script src="/assets/js/pages/datatables.init.js"></script>

    <script>
        $(document).ready(function(){
            $('#description').summernote({
                height:200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]

            });
        });
    </script>
@endsection
