@extends('layouts.master-layout')
@section('title')
    All Refunds
@endsection
@section('current-page')
    All Refunds
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')
    All Refunds
@endsection

@section('main-content')
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Total</p>
                            <h5 class="mb-0 number-font text-secondary1">{{env('APP_CURRENCY')}} {{number_format($lastYear->sum('amount_refunded'),2)}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary1">
                                <i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Refund <code>(Last Year)</code></span></span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Total</p>
                            <h5 class="text-orange mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($currentYear->sum('amount_refunded'),2)}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-orange">
                                <i class="bx bxs-book-open"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Refund<code>(This Year)</code></span></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Total</p>
                            <h5 class="text-secondary mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($lastMonth->sum('amount_refunded'),2)}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary">
                                <i class="bx bx-check-double"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Refund<code>(Last Month)</code></span></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Total</p>
                            <h5 class="text-warning mb-0 number-font">{{env('APP_CURRENCY')}} {{number_format($currentMonth->sum('amount_refunded'),2)}}</h5>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-warning">
                                <i class="bx bx-hourglass"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">Refund<code>(This Month)</code> </span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-end ">
            <a href="{{ route('show-new-refund-form') }}" class="btn btn-primary ">Process Refund  <i class="bx bx-loader"></i> </a>
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
                            <p>List of all refunds</p>
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
                                        <th class="sorting" style="text-align: right;">Amount Refunded({{env('APP_CURRENCY')}})</th>
                                        <th class="sorting">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $serial = 1;
                                    @endphp
                                    @foreach($refunds as $refund)
                                        <tr>
                                            <td>{{$serial++}}</td>
                                            <td>{{!is_null($refund->date_actioned) ? date('d M,Y', strtotime($refund->date_actioned)) : '-'}}</td>
                                            <td>
                                                {{$refund->getReceipt->getCustomer->first_name ?? '' }} {{$refund->getReceipt->getCustomer->last_name ?? '' }}
                                            </td>
                                            <td>{{$refund->getReceipt->getInvoice->invoice_no ?? ''}}</td>
                                            <td><a href="{{ route('view-receipt', $refund->getReceipt->trans_ref) }}">{{$refund->getReceipt->receipt_no ?? ''}}</a> </td>
                                            <td class="text-right" style="text-align: right">{{number_format($refund->amount_paid,2)}}</td>
                                            <td class="text-right" style="text-align: right">{{number_format($refund->amount_refunded,2)}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light text-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('view-receipt', $refund->getReceipt->trans_ref) }}"> <i class="bx bx-book-open"></i> View Receipt</a>
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
