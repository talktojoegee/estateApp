@extends('layouts.master-layout')
@section('current-page')
    Sales Report
@endsection

@section('title')
    Sales Report
@endsection
@section('extra-styles')
    <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
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
            @if($search == 0)
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {!! session()->get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @include('reports.partials._sales-search-form')
            @else
                @include('reports.partials._sales-search-form')
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-1">
                                    <div class="col">
                                        <p class="mb-1">Total</p>
                                        <h5 class="mb-0 number-font text-secondary1">{{env('APP_CURRENCY')}} {{number_format($receipts->sum('total'),2)}}</h5>
                                    </div>
                                    <div class="col-auto mb-0">
                                        <div class="dash-icon text-secondary1">
                                            <i class="bx bxs-wallet"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall <code>({{number_format($receipts->count())}})</code></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title text-uppercase text-info modal-title"> Sales Report</h4>



                                    <div class="row">
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            <p>Showing sales report between <code>{{date('d M, Y', strtotime($from))}}</code> to <code>{{date('d M, Y', strtotime($to))}}</code></p>
                                            <div class="table-responsive">

                                                <table id="datatable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="focus-key_info" style="position: relative;">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc">S/No.</th>
                                                        <th class="sorting_asc">Date</th>
                                                        <th class="sorting_asc">Name</th>
                                                        <th class="sorting">Invoice No.</th>
                                                        <th class="sorting">Receipt No.</th>
                                                        <th class="sorting" style="text-align: right;">Amount({{env('APP_CURRENCY')}})</th>
                                                        <th class="sorting">Trans. Ref</th>
                                                        <th class="sorting">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $serial = 1;
                                                    @endphp
                                                    @foreach($receipts as $receipt)
                                                        <tr>
                                                            <td>{{$serial++}}</td>
                                                            <td>{{!is_null($receipt->created_at) ? date('d M,Y', strtotime($receipt->created_at)) : '-'}}</td>
                                                            <td>
                                                                {{$receipt->getCustomer->first_name ?? '' }} {{$receipt->getCustomer->last_name ?? '' }}
                                                            </td>
                                                            <td><a href="{{ route('view-receipt', $receipt->trans_ref) }}" target="_blank">{{$receipt->getInvoice->invoice_no ?? ''}}</a></td>
                                                            <td>{{$receipt->receipt_no ?? ''}}</td>
                                                            <td class="text-right" style="text-align: right">{{number_format($receipt->total,2)}}</td>
                                                            <td>{{$receipt->trans_ref ?? ''}}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-light text-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical"></i></button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="{{ route('view-receipt', $receipt->trans_ref) }}"> <i class="bx bx-book-open"></i> View Receipt</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr role="row">
                                                        <th>S/No.</th>
                                                        <th>Date</th>
                                                        <th>Name</th>
                                                        <th>Invoice No.</th>
                                                        <th>Receipt No.</th>
                                                        <th>Amount</th>
                                                        <th>Trans. Ref</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <script src="/assets/js/pages/datatables.init.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function(){
            $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
            $( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
            $('.js-example-basic-single').select2();
            $('#createIncomeForm').parsley().on('field:validated', function() {
                let ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });
        });
        function generatePDF(){
            var element = document.getElementById('printArea');
            html2pdf(element,{
                margin:       10,
                filename:     "Inflow_"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>
@endsection
