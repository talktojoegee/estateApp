@extends('layouts.master-layout')
@section('title')
    Receive Payment
@endsection
@section('current-page')
    Receive Payment
@endsection
@section('extra-styles')
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: Avenir, Helvetica, Arial, sans-serif;
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
            font-family: Avenir, Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')
    Receive Payment
@endsection

@section('main-content')

    <div class="row">
        <div class="col-md-12 col-lg-12">

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
                        <div class="col-lg-9 col-xl-9">
                            <form action="{{ route('process-payment') }}" method="post" autocomplete="off">
                                @csrf
                                <div class="invoice-box" id="receiptWrapper" style="background: #fff;">

                                    <div class="invoice-title"   >
                                        <h5 class="float-end font-size-16"> Ref.: {{ strtoupper($invoice->ref_no ?? '') }}
                                            <p class="mt-2"><strong>Status:</strong>
                                                @switch($invoice->status)
                                                    @case(0)
                                                    <small class="text-warning">Pending</small>
                                                    @break
                                                    @case(1)
                                                    <small class="text-secondary">Fully Paid</small>
                                                    @break
                                                    @case(2)
                                                    <small class="text-secondary">Partly-paid</small>
                                                    @break
                                                    @case(3)
                                                    <small class="text-danger" style="color: #ff0000 !important;">Declined</small>
                                                    @break
                                                    @case(4)
                                                    <small class="text-success" >Verified</small>
                                                    @break
                                                @endswitch

                                            </p>
                                        </h5>
                                        <div class="mb-4"   >
                                            <img src="/assets/drive/logo/logo-dark.png" alt="logo" height="60">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row"   >
                                        <div class="col-sm-6 "   >
                                            <address class="mt-2 mt-sm-0">
                                                <strong>From:</strong><br>
                                                {{env('ORG_NAME')}}<br>
                                                {{env('ORG_PHONE')}}<br>
                                                {{env('ORG_EMAIL')}}<br>
                                                {!! env('ORG_ADDRESS') !!}
                                            </address>
                                        </div>

                                        <div class="col-sm-6 text-sm-end"   >
                                            <address>
                                                <strong>Billed To:</strong><br>
                                                {{$invoice->getCustomer->first_name ?? ''  }} {{$invoice->getCustomer->last_name ?? ''  }}<br>
                                                {{$invoice->getCustomer->phone ?? ''  }}<br>
                                                {{$invoice->getCustomer->email ?? ''  }}<br>
                                                {{$invoice->getCustomer->street ?? ''  }}
                                            </address>
                                        </div>

                                    </div>
                                    <div class="row"   >
                                        <div class="col-sm-6 mt-3"   >
                                            <address>
                                                <strong>Invoice No.:</strong><br>
                                                {{$invoice->invoice_no ?? '' }}<br>
                                                <strong>Start Date:</strong><br>
                                                <span class="text-success">{{ date('d M, Y', strtotime($invoice->issue_date)) ?? '' }}</span><br>
                                                <strong>Due Date:</strong><br>
                                                <span class="text-danger" style="color: #ff0000 !important;">{{ date('d M, Y', strtotime($invoice->due_date)) ?? '' }}</span><br>
                                            </address>
                                        </div>
                                        <div class="col-sm-6  text-sm-end"   >
                                            <address>
                                                <strong>Date Issued:</strong><br>
                                                {{date('d M, Y', strtotime($invoice->created_at))}}<br><br>
                                            </address>
                                            <address>
                                                <strong>Property:</strong><br>
                                                {{ $invoice->getProperty->property_name ?? '' }}<br><br>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="row " >
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <tbody>
                                                    <tr>
                                                        <td style=""><strong>Estate: </strong> {{ $invoice->getProperty->getEstate->e_name ?? '' }}</td>
                                                        <td style=""><strong>House No.: </strong> {{ $invoice->getProperty->house_no ?? '' }}</td>
                                                        <td style=""><strong> Street: </strong>{{ $invoice->getProperty->street ?? '' }}</td>
                                                        <td style=""><strong>Property Code: </strong> {{ $invoice->getProperty->property_code ?? '' }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-2 mt-3"   >
                                        <h3 class="font-size-15 text-info fw-bold">Summary</h3>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">

                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th style="text-align: right;">Cost({{env('APP_CURRENCY')}})</th>
                                                <th style="text-align: right;">Total({{env('APP_CURRENCY')}})</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <input type="hidden" name="invoice" value="{{$invoice->id}}">
                                            @foreach($invoice->getInvoiceDetail as $key => $detail)
                                                <tr>
                                                    <th>{{ $key +1  }}</th>
                                                    <td style="text-align: left">{{$detail->description ?? '' }}</td>
                                                    <td style="text-align: center;">{{ number_format($detail->quantity ?? 0) }}</td>
                                                    <td style="text-align: right;">{{ number_format($detail->unit_cost ?? 0,2)  }}</td>
                                                    <td style="text-align: right;">{{ number_format(($detail->quantity ?? 0) * ($detail->unit_cost ?? 0),2)  }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>Sub-total</strong></td>
                                                <td class="border-0 text-end text-muted"><h5 class="m-0"> <span></span><span id="subTotal" class="text-muted">{{number_format($invoice->sub_total ?? 0 ,2)}}</span></h5></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>TAX/VAT({{$invoice->vat_rate ?? 0}}%)</strong></td>
                                                <td class="border-0 text-end text-muted"><h5 class="m-0"> <span></span><span id="totalAmount" class="text-muted">{{number_format($invoice->vat ?? 0 ,2)}}</span></h5></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>Discount{{$invoice->discount_type == 2 ? '('.$invoice->discount_rate.'%)' : ''}}</strong></td>
                                                <td class="border-0 text-end"> <span id="discount">{{number_format($invoice->discount_amount ?? 0 ,2)}}</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>Total</strong></td>
                                                <td class="border-0 text-end text-muted"><h5 class="m-0"> <span></span><span id="totalAmount" class="text-muted">{{number_format($invoice->total ?? 0 ,2)}}</span></h5></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong> Paid</strong></td>
                                                <td class="border-0 text-end"><span></span><span>{{number_format($invoice->amount_paid ?? 0 ,2)}}</span></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>Balance</strong></td>
                                                <td class="border-0 text-end"><h5 class="m-0"> <span></span><span id="balance" class="text-muted">{{number_format(($invoice->total ?? 0) - ($invoice->amount_paid ?? 0) ,2)}}</span></h5></td>
                                            </tr>
                                            @if(($invoice->total  - $invoice->amount_paid) > 0)
                                            <tr>
                                                <td colspan="4"></td>
                                                <td>
                                                    <div class="form-group ">
                                                        <div class="d-flex justify-content-start">
                                                            <label for="">Enter Amount</label>
                                                        </div>
                                                        <input type="number" step="0.01" name="amount" placeholder="Enter Amount" class="form-control">
                                                        @error('amount')<i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td colspan="5">
                                                    <p class="text-info text-center">Whoops! This invoice is fully paid.</p>
                                                </td>
                                            </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xl-12 d-flex justify-content-center">
                                        <div class="card mt-3">
                                            <div class="card-block">
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-secondary"><i class="bx bx-left-arrow mr-2"></i> Go Back</a>
                                                    @if(($invoice->total  - $invoice->amount_paid) > 0)
                                                        <button class="btn btn-mini btn-custom" type="submit"> Submit <i class="bx bx-right-arrow mr-2"></i></button>
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
                                <div class="card-header bg-custom text-white">
                                    Invoice Detail
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
                                                            {{$invoice->getAuthor->title ?? ''}} {{$invoice->getAuthor->first_name ?? ''}} {{$invoice->getAuthor->last_name ?? ''}} {{$invoice->getAuthor->other_names ?? ''}}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="bx bx-loader"></i> Status:
                                                </td>
                                                <td class="text-right">
                                                    @if($invoice->status == 0)
                                                        <label for="" class="label label-warning">Pending</label>
                                                    @elseif($invoice->status == 1)
                                                        <label for="" class="label label-secondary">Fully-paid</label>
                                                    @elseif($invoice->status == 2)
                                                        <label for="" class="label label-info">Partly-paid</label>
                                                    @elseif($invoice->status == 3)
                                                        <label for="" class="label label-danger">Declined</label>
                                                    @elseif($invoice->status == 4)
                                                        <label for="" class="label label-success">Verified</label>
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
