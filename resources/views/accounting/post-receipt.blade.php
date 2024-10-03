@extends('layouts.master-layout')
@section('title')
    Manage Receipts
@endsection
@section('current-page')
    Manage Receipts
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')
    Manage Receipts
@endsection

@section('main-content')
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Total</p>

                            <h5 class="mb-0 number-font text-secondary1">{{env('APP_CURRENCY')}}{{ number_format($receipts->where('posted',0)->sum('total')) }}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary1">
                                <i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Overall <code>({{ number_format($receipts->where('posted',0)->count()) }})</code></span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert" role="alert">
                            {!! session()->get('success') !!}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-warning" role="alert">
                            {!! session()->get('error') !!}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <p><strong>Note:</strong> Posting any of these transactions will affect your account.</p>
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
                                            <td>{{$receipt->getInvoice->invoice_no ?? ''}}</td>
                                            <td><a href="{{ route('view-receipt', $receipt->trans_ref) }}">{{$receipt->receipt_no ?? ''}}</a> </td>
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
@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>



@endsection
