@extends('layouts.guest-layout')
@section('title')
    View Invoice | Invoice No: {{$invoice->invoice_no ?? ''}}
@endsection

@section('current-page')
    Invoice No: {{$invoice->invoice_no ?? ''}}
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

            @if($invoice->total == $invoice->paid_amount)
                <div class="alert alert-success background-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    Good news! This invoice has no pending payment.
                </div>
            @endif
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
    <form action="{{route('make-online-payment')}}" method="post">
        @csrf
        <div class="row" >
            <div class="col-lg-12 col-xl-12">
                <div class="invoice-box" id="receiptWrapper">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="6">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img src="/assets/drive/{{$company->logo ?? 'logo.png'}}" style="width: 64px; max-width: 300px" />
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
                                            {{$company->company_name ?? '' }}<br />
                                            {{$company->address_1 ?? '' }}
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
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Sub total: {{'₦'.number_format($invoice->sub_total,2)}}</td>
                        </tr>
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
                            <td>Balance: {{'₦'.number_format($invoice->total - $invoice->paid_amount,2)}}</td>
                        </tr>
                    </table>
                    <input type="hidden" name="email" value="@if($invoice->invoice_type == 2 || $invoice->invoice_type == 3)
                    {{$invoice->getTenant->getApplicant->email ?? ''}}
                    @else
                    {{$invoice->getApplicant->email ?? ''}}
                    @endif">
                    <input type="hidden" name="amount" value="{{ (($invoice->total - $invoice->paid_amount) * 100) ?? 0}}">
                    <input type="hidden" name="invoice" value="{{ $invoice->id}}">
                </div>
            </div>
            @if($invoice->total > $invoice->paid_amount)
                <div class="col-lg-12 col-xl-12 d-flex justify-content-center">
                    <div class="card mt-3">
                        <div class="card-block">
                            <div class="btn-group">
                                <a href="{{route('property-listing')}}" class="btn btn-sm btn-secondary"><i class="ti-close mr-2"></i> Cancel</a>
                                <button class="btn btn-sm btn-primary" type="submit"><i class="icofont icofont-wallet mr-2"></i> Pay {{'₦'.number_format($invoice->total - $invoice->paid_amount,2)}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
@endsection

@section('extra-scripts')

@endsection
