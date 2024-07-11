@extends('layouts.master-layout')
@section('title')
    Workflow
@endsection
@section('current-page')
    Workflow
@endsection
@section('extra-styles')
    <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="row">

                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col">
                                    <p class="mb-1">Total</p>
                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{ number_format($workflows->where('p_type',6)->sum('p_amount')) }}</h5>
                                </div>
                                <div class="col-auto mb-0">
                                    <div class="dash-icon text-secondary1">
                                        <i class="bx bxs-briefcase-alt-2"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">My Requests<code>({{ number_format($workflows->where('p_type',6)->count()) }})</code></span></span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Declined</p>
                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format( $workflows->where('p_status',3)->where('p_type',6)->sum('p_amount') )}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-orange" >
                                        <i class="bx bxs-book-open"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Total Declined<code>({{number_format($workflows->where('p_status',3)->where('p_type',6)->count())}})</code></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Approved</p>
                                    <h5 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format( $workflows->where('p_status',2)->where('p_type',6)->sum('p_amount') )}}</h5>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-secondary" >
                                        <i class="bx bx-check-double"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Total Approved<code>({{number_format( $workflows->where('p_status',2)->where('p_type',6)->count() )}})</code></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6" >
                    <div class="card" >
                        <div class="card-body" >
                            <div class="row mb-1" >
                                <div class="col" >
                                    <p class="mb-1">Pending</p>
                                    <h3 class="mb-0 number-font">{{env('APP_CURRENCY')}}{{number_format( $workflows->where('p_status',0)->where('p_type',6)->sum('p_amount') )}}</h3>
                                </div>
                                <div class="col-auto mb-0" >
                                    <div class="dash-icon text-warning" >
                                        <i class="bx bx-hourglass"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Total Pending<code>({{number_format( $workflows->where('p_status',0)->where('p_type',6)->count() )}})</code> </span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addNewProduct" class="btn btn-primary  mb-3">New Request <i class="bx bx bx-highlight"></i> </a>
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



                            <div class="table-responsive mt-3">
                                <table id="datatable1" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                                    <thead style="position: sticky;top: 0">
                                    <tr role="row">
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >S/No.</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Date</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Subject</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Amount</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Status</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Type</th>
                                        <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $a = 1;
                                    @endphp
                                    @foreach($workflows as $key => $flow)
                                        <tr role="row" class="odd">
                                            <td class="">{{$a++}}</td>
                                            <td class="sorting_1 text-left">{{ date('d M, Y', strtotime($flow->created_at)) }}</td>
                                            <td class="">{{$flow->p_title ?? ''}}</td>
                                            <td class="" style="text-align: right">{{$flow->getCurrency->symbol ?? '' }}{{ number_format($flow->p_amount ?? 0, 2) }}</td>
                                            <td class="">
                                                @switch($flow->p_status)
                                                    @case(0)
                                                        <span class="text-info">Pending</span>
                                                    @break
                                                    @case(1)
                                                    <span class="text-info">Processing</span>
                                                    @break
                                                    @case(2)
                                                        <span class="text-success">Approved</span>
                                                    @break
                                                    @case(3)
                                                        <span class="text-danger">Declined</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="">
                                                @if($flow->p_type == 6)
                                                    <span class="text-danger">Expense Request</span>
                                                @else
                                                    <span class="text-info">Expense Report</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{route('view-workflow', $flow->p_slug)}}" > <i class="bx bxs-book-open"></i> View</a>
                                                    </div>
                                                </div>
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

    <div class="modal right fade" id="addNewProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="width: 900px;">
        <div class="modal-dialog modal-lg w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase" style="text-align: center;" id="myModalLabel2">New Workflow Request</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('workflow')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Subject</label>
                                <input type="text" value="{{ old('subject') }}" name="subject" placeholder="Subject" class="form-control">
                                @error('subject') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6 ">
                                <label for="">Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" placeholder="Amount" step="0.01" value="{{ old('amount') }}" class="form-control">
                                @error('amount') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Currency <span class="text-danger">*</span></label> <br>
                                <select name="currency"  class="form-control ">
                                    <option selected disabled>--Select currency--</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{$currency->code ?? '' }} {{$currency->symbol ?? '' }}</option>
                                    @endforeach
                                </select>
                                @error('currency') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Authorizing Person <span class="text-danger">*</span></label> <br>
                                <select name="authPerson"  class="form-control ">
                                    <option disabled selected>--Select person--</option>
                                    @foreach($persons as $person)
                                    <option value="{{$person->id }}">{{ $person->title ?? ''  }}{{$person->first_name ?? '' }} {{$person->last_name ?? '' }}</option>
                                    @endforeach
                                </select>
                                @error('authPerson') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Type <span class="text-danger">*</span></label> <br>
                                <select name="type"  class="form-control ">
                                    <option value="6">Expense Request</option>
                                    <option value="7">Expense Report</option>
                                </select>
                                @error('type') <i class="text-danger">{{$message}}</i>@enderror
                            </div>

                            <div class="form-group mt-3 col-md-6">
                                <label for="">Attachment <small>(Optional)</small></label> <br>
                                <input type="file" multiple name="attachments[]" class="form-control-file">
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Category <span class="text-danger">*</span></label> <br>
                                <select name="category"  class="form-control ">
                                    <option selected disabled>--Select category--</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->tc_id }}">{{$category->tc_name ?? '' }} </option>
                                    @endforeach
                                </select>
                                @error('category') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for="">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" placeholder="Type request details here..."  style="resize: none;" class="form-control">{{ old('description') }}</textarea>
                                @error('description') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit Request <i class="bx bxs-right-arrow"></i> </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
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
