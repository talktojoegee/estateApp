@extends('layouts.master-layout')
@section('title')
    All Receipts
@endsection

@section('current-page')
    All Receipts
@endsection
@section('current-page-brief')

@endsection

@section('event-area')
    <div class="btn-group">
        <a href="{{route('manage-receipts')}}" class="btn btn-secondary btn-mini"><i class="icofont icofont-tags"></i>Manage Receipt</a>
    </div>
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="\assets\css\component.css">
@endsection
@section('main-content')
    <div class="row">
        <div class="col-sm-4">
            <div class="card bg-c-pink text-white widget-visitor-card">
                <div class="card-block-small text-center">
                    <h2>{{'₦'.number_format($receipts->where('posted',2)->sum('total'))}}</h2>
                    <h6>Declined</h6>
                    <i class="icofont icofont-ban"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-c-blue text-white widget-visitor-card">
                <div class="card-block-small text-center">
                    <h2>{{'₦'.number_format($receipts->where('posted',1)->sum('total'))}}</h2>
                    <h6>Approved</h6>
                    <i class="icofont icofont-ui-check"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-c-yellow text-white widget-visitor-card">
                <div class="card-block-small text-center">
                    <h2>{{'₦'.number_format($receipts->where('posted',0)->sum('total'))}}</h2>
                    <h6>Pending</h6>
                    <i class="icofont icofont-spinner-alt-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <p>List of all receipts</p>
                            @if (session()->has('success'))
                                <div class="alert alert-success background-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="icofont icofont-close-line-circled text-white"></i>
                                    </button>
                                    {!! session()->get('success') !!}
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-warning background-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="icofont icofont-close-line-circled text-white"></i>
                                    </button>
                                    {!! session()->get('error') !!}
                                </div>
                            @endif
                            <div class="table-responsive">

                                <table id="focus-key" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="focus-key_info" style="position: relative;">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc">S/No.</th>
                                        <th class="sorting_asc">Date</th>
                                        <th class="sorting_asc">Receipt No.</th>
                                        <th class="sorting">Amount</th>
                                        <th class="sorting_asc">Name</th>
                                        <th class="sorting">Trans. Ref</th>
                                        <th class="sorting">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $serial = 1;
                                    @endphp
                                    @foreach($receipts as $receipt)
                                        <tr role="row" class="odd">
                                            <td>{{$serial++}}</td>
                                            <td>{{!is_null($receipt->payment_date) ? date('d M, Y', strtotime($receipt->payment_date)) : '-' }}</td>
                                            <td>{{$receipt->receipt_no ?? ''}}</td>
                                            <td class="text-right">{{number_format($receipt->total,2)}}</td>
                                            <td>
                                                @if($receipt->receipt_type == 2 || $receipt->receipt_type == 3)
                                                    {{$receipt->getInvoice->getTenant->getApplicant->title ?? ''}} {{$receipt->getInvoice->getTenant->getApplicant->first_name ?? '-'}} {{$receipt->getInvoice->getTenant->getApplicant->surname ?? '-'}}
                                                @else
                                                    {{$receipt->getInvoice->getApplicant->title ?? ''}} {{$receipt->getInvoice->getApplicant->first_name ?? ''}} {{$receipt->getInvoice->getApplicant->surname ?? '-'}}
                                                @endif
                                            </td>
                                            <td>{{strtoupper($receipt->trans_ref)}}</td>
                                            <td>
                                                <div class="dropdown-secondary dropdown">
                                                    <button class="btn btn-info btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdown14" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a class="dropdown-item waves-light waves-effect" href="{{route('view-receipt-detail', $receipt->trans_ref)}}"><i class="ti-printer"></i> View Receipt</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th >S/No.</th>
                                        <th >Date</th>
                                        <th>Receipt No.</th>
                                        <th >Amount</th>
                                        <th>Name</th>
                                        <th >Trans. Ref.</th>
                                        <th >Action</th>
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
    <script src="\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="\assets\pages\data-table\extensions\key-table\js\key-table-custom.js"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.approve', function(event){
                event.preventDefault();
                $('#applicant').text($(this).data('applicant'));
                $('#applicantId').val($(this).data('appid'));
                $('#propertyId').val($(this).data('property'));
            });
        });
    </script>
@endsection
