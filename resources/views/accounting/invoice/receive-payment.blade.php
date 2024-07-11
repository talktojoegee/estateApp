@extends('layouts.master-layout')
@section('title')
    Receive Payment | Invoice No: {{$invoice->invoice_no ?? ''}}
@endsection

@section('current-page')
    Invoice No.: <span class="">{{$invoice->invoice_no ?? ''}}</span>
@endsection
@section('current-page-brief')

@endsection

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="\assets\css\component.css">
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            @if(session()->has('success'))
                <div class="alert alert-success background-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    {!! session()->get('success') !!}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-warning background-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    {!! session()->get('error') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group float-right mb-3">
                <a href="{{route('manage-invoices')}}" class="btn btn-secondary btn-mini"><i class="icofont icofont-tags"></i>Manage Invoices</a>
                <a href="{{route('generate-new-invoice')}}" class="btn btn-primary btn-mini"><i class="icofont icofont-tasks"></i>Generate New Invoice</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-xl-9">
            <form action="{{route('process-offline-payment')}}" method="post" autocomplete="off">
                @csrf
                <div class="invoice-box" id="receiptWrapper" style="background: #fff;">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="6">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img src="/assets/drive/{{$invoice->getCompany->logo ?? 'logo.png'}}" style="width: 64px; max-width: 300px" />
                                        </td>

                                        <td>
                                            Invoice #: {{$invoice->invoice_no ?? ''}}<br />
                                            Date Issued: {{!is_null($invoice->issue_date) ? date('d M, Y', strtotime($invoice->issue_date)) : '-' }}<br />
                                            Due Date: {{!is_null($invoice->due_date) ? date('d M, Y', strtotime($invoice->due_date)) : '-' }}<br />

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="information">
                            <td colspan="6">
                                <table>
                                    <tr>
                                        <td>
                                            Connexxion Group<br />
                                            12345 Sunny Road<br />
                                            2A Iller Crescent Maitama, Abuja.
                                        </td>

                                        <td>
                                            @if($invoice->invoice_type == 2 || $invoice->invoice_type == 3)
                                                {{$invoice->getTenant->getApplicant->title ?? ''}} {{$invoice->getTenant->getApplicant->first_name ?? '-'}} {{$invoice->getTenant->getApplicant->surname ?? '-'}}
                                            @else
                                                {{$invoice->getApplicant->title ?? ''}} {{$invoice->getApplicant->first_name ?? ''}} {{$invoice->getApplicant->surname ?? '-'}}
                                            @endif<br />
                                            @if($invoice->invoice_type == 2 || $invoice->invoice_type == 3)
                                                {{$invoice->getTenant->getApplicant->mobile_no ?? ''}}
                                            @else
                                                {{$invoice->getApplicant->mobile_no ?? ''}}
                                            @endif
                                            <br />
                                            @if($invoice->invoice_type == 2 || $invoice->invoice_type == 3)
                                                {{$invoice->getTenant->getApplicant->email ?? ''}}
                                            @else
                                                {{$invoice->getApplicant->email ?? ''}}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="heading">
                            <td>Payment Method</td>
                            <td colspan="5">
                                @switch($invoice->payment_method)
                                    @case(1)
                                    Cash
                                    @break
                                    @case(2)
                                    Cheque
                                    @break
                                    @case(3)
                                    Bank Transfer
                                    @break
                                    @case(4)
                                    Internet Transfer
                                    @break
                                @endswitch
                            </td>
                        </tr>
                        <tr class="heading">
                            <td>#</td>
                            <td>Service</td>
                            <td>Quantity</td>
                            <td>Amount</td>
                            <td>Total</td>
                            <td></td>
                        </tr>
                        @php
                            $serial = 1;
                        @endphp
                        @foreach($invoice->getInvoiceItems as $item)
                            <tr class="item">
                                <td>{{$serial++}}</td>
                                <td>{{$item->getService->service_name ?? '' }}</td>
                                <td>{{$item->quantity ?? '' }}</td>
                                <td class="">{{number_format($item->unit_cost,2) ?? '' }}</td>
                                <td class="">{{number_format($item->amount,2) ?? '' }}</td>
                            </tr>
                        @endforeach
                        <input type="hidden" name="invoice" value="{{$invoice->id}}">
                        <tr class="total">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total: {{'₦'.number_format($invoice->total,2)}}</td>
                        </tr>
                        <tr class="total">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Balance: {{'₦'.number_format(($invoice->total) - ($invoice->paid_amount) ,2)}}</td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                @if(($invoice->total) - ($invoice->paid_amount) <= 0)
                                    <p class="text-center text-danger">Payment completed</p>
                                @else
                                    <div class="form-group">
                                        <label for="">Amount</label>
                                        <input type="number" step="0.01" name="amount" placeholder="Enter Amount" class="form-control">
                                        @error('amount')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-xl-12 d-flex justify-content-center">
                        <div class="card mt-3">
                            <div class="card-block">
                                <div class="btn-group">
                                    <a href="{{route('manage-invoices')}}" class="btn btn-mini btn-warning"><i class="icofont icofont-square-left mr-2"></i> Back</a>
                                    @if(($invoice->total) - ($invoice->paid_amount) <= 0)

                                    @else
                                        <button class="btn btn-mini btn-primary" type="submit"><i class="ti-check mr-2"></i> Submit</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="card card-border-primary">
                <div class="card-header">
                    <h5 class="card-header-text text-uppercase">
                        <i class="icofont icofont-ui-note m-r-10"></i> Invoice Detail
                    </h5>
                </div>
                <div class="card-block task-details">
                    <div class="table-responsive">
                        <table class="table table-border table-xs">

                            <tbody>
                            <tr>
                                <td>
                                    <i class="ti-timer"></i> Created:
                                </td>
                                <td class="text-right">{{!is_null($invoice->created_at) ? date('d M, Y', strtotime($invoice->created_at)) : '-'}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="ti-user"></i> Issued By:
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="javascript:void(0);">
                                            {{$invoice->getIssuedBy->first_name ?? ''}} {{$invoice->getIssuedBy->surname ?? ''}}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="ti-announcement"></i> Posting:
                                </td>
                                <td class="text-right">
                                    <a href="javascript:void(0);">
                                        @if($invoice->posted == 0 && $invoice->trashed == 0)
                                            <label for="" class="label label-warning">Pending</label>
                                        @elseif($invoice->trashed == 1)
                                            <label for="" class="label label-danger">Declined</label>
                                        @elseif($invoice->posted == 1)
                                            <label for="" class="label label-success">Posted</label>
                                        @endif
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="icofont icofont-washing-machine"></i> Status:
                                </td>
                                <td class="text-right">
                                    @if($invoice->status == 0)
                                        <label for="" class="label label-warning">Pending</label>
                                    @elseif($invoice->status == 1)
                                        <label for="" class="label label-success">Fully-paid</label>
                                    @elseif($invoice->status == 2)
                                        <label for="" class="label label-info">Partly-paid</label>
                                    @elseif($invoice->status == 3)
                                        <label for="" class="label label-danger">Declined</label>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('extra-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF(){
            var element = document.getElementById('receiptWrapper');
            html2pdf(element,{
                margin:       10,
                filename:     "Invoice_No_{{$invoice->invoice_no}}"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>
@endsection
