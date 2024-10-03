@extends('layouts.master-layout')
@section('title')
 Invoice Posting
@endsection
@section('current-page')
    Invoice Posting
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
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col">
                                <p class="mb-1">Total</p>

                                <h5 class="mb-0 number-font text-secondary1">{{env('APP_CURRENCY')}}{{ number_format($invoices->where('posted',0)->sum('total')) }}</h5>
                            </div>
                            <div class="col-auto mb-0">
                                <div class="dash-icon text-secondary1">
                                    <i class="bx bxs-wallet"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall <code>({{ number_format($invoices->where('posted',0)->count()) }})</code></span></span>
                    </div>
                </div>
            </div>
        </div>

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

                            <p><strong>Note:</strong> Posting any of these transactions will affect your account records.</p>



                        <div class="table-responsive mt-3">
                            <table id="datatable1" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="complex-header_info" style="width: 100%; margin:0px auto;">
                                <thead style="position: sticky;top: 0">
                                <tr role="row">
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >S/No.</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Date</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Invoice No.</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Customer</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="text-align: right">Total({{env('APP_CURRENCY')}})</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="text-align: right">Paid({{env('APP_CURRENCY')}})</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" style="text-align: right">Balance({{env('APP_CURRENCY')}})</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Status</th>
                                    <th class="sorting_asc text-left text-uppercase header" tabindex="0" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $a = 1;
                                @endphp
                                @foreach($invoices as $key => $flow)
                                    <tr role="row" class="odd">
                                        <td class="">{{$a++}}</td>
                                        <td class="sorting_1 text-left">{{ date('d M, Y', strtotime($flow->created_at)) }}</td>
                                        <td><a href="{{route('show-invoice-detail', $flow->ref_no)}}">{{$flow->invoice_no ?? '' }}</a></td>
                                        <td class="">
                                            <a target="_blank" href="{{route('lead-profile', $flow->getCustomer->slug)}}">{{$flow->getCustomer->title ?? '' }} {{$flow->getCustomer->first_name ?? '' }} {{$flow->getCustomer->last_name ?? '' }}</a>
                                        </td>
                                        <td class="" style="text-align: right">{{number_format($flow->total,2)}}</td>
                                        <td class="" style="text-align: right">{{number_format($flow->amount_paid ?? 0,2)}}</td>
                                        <td class="" style="text-align: right">{{number_format(($flow->total ?? 0) - ($flow->amount_paid ?? 0),2)}}</td>
                                        <td class="">
                                            @switch($flow->status)
                                                @case(0)
                                                <span class="text-warning">Pending</span>
                                                @break
                                                @case(1)
                                                <span class="text-secondary">Fully Paid</span>
                                                @break
                                                @case(2)
                                                <span class="text-secondary">Partly-paid</span>
                                                @break
                                                @case(3)
                                                <span class="text-danger" style="color: #ff0000 !important;">Declined</span>
                                                @break
                                                @case(4)
                                                <span class="text-success" >Verified</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{route('show-invoice-detail', $flow->ref_no)}}" > <i class="bx bxs-book-open"></i> View</a>
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



@endsection

@section('extra-scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


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
