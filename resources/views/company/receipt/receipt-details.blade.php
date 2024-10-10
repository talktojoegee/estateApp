@extends('layouts.master-layout')
@section('title')
   Receipt Details
@endsection
@section('current-page')
    Receipt Detail
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
    Receipt Detail
@endsection

@section('main-content')

    <div class="row" >
        <div class="col-lg-12 col-xl-12">
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
            <div class="invoice-box" id="receiptWrapper" style="background: #fff;">

                <h5 class="modal-header text-info mb-3">Receipt</h5>
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="6">
                            <table>
                                <tr>
                                    <td class="">
                                        <img src="/assets/drive/logo/logo-dark.png" alt="logo" height="60"> <br>
                                        <strong>Email: </strong> {{env('ORG_EMAIL')}} <br>
                                        <strong>Mobile No.: </strong>{{env('ORG_PHONE')}} <br>
                                        <strong>Address: </strong>{!! env('ORG_ADDRESS') !!} <br>
                                        <strong>Website: </strong>{{env('ORG_WEBSITE')}} <br>
                                    </td>

                                    <td>
                                        <h5 class="text-muted text-uppercase">Customer</h5>
                                        {{$receipt->getCustomer->first_name ?? '' }} {{$receipt->getCustomer->last_name ?? '' }}<br />
                                        <strong>Email: </strong>{{$receipt->getCustomer->email ?? '' }} <br>
                                        <strong>Mobile No.: </strong>{{$receipt->getCustomer->phone ?? ''}}<br />
                                        <strong>Address: </strong>{{$receipt->getCustomer->street ?? ''}}<br />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Payment Date: </strong>{{!is_null($receipt->payment_date) ? date('d M, Y', strtotime($receipt->payment_date)) : '-' }}</td>
                        <td><strong>Payment Method: </strong>
                            @switch($receipt->payment_method)
                                @case(1)
                                    Case
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
                    <tr>
                        <td><strong>Payment Date: </strong>{{!is_null($receipt->payment_date) ? date('d M, Y', strtotime($receipt->payment_date)) : '-' }}</td>
                        <td><strong>Receipt No.: </strong>{{$receipt->receipt_no ?? '-'}}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><strong>Property Name:</strong> {{ $receipt->getProperty->property_name ?? '' }} </td>
                    </tr>
                    <tr>
                        <td><strong>Property Code:</strong> {{ $receipt->getProperty->property_code ?? '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Estate:</strong> {{ $receipt->getProperty->getEstate->e_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Street:</strong> {{ $receipt->getProperty->street ?? '' }} </td>
                    </tr>
                    <tr>
                        <td><strong>House No.:</strong> {{ $receipt->getProperty->house_no ?? '' }}</td>
                    </tr>
                </table>



                <div class="py-2 mt-3"   >
                    <h3 class="font-size-15 fw-bold">Summary</h3>
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
                        @foreach($receipt->getInvoice->getInvoiceDetail as $key => $detail)
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
                                <strong>Total</strong></td>
                            <td class="border-0 text-end"><h5 class="m-0 text-muted"> <span></span><span id="totalAmount">{{env('APP_CURRENCY')}}{{number_format($receipt->getInvoice->total,2)}}</span></h5></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border-0 text-end">
                                <strong> Paid</strong></td>
                            <td class="border-0 text-end"><span></span><span>{{env('APP_CURRENCY')}}{{number_format($receipt->sub_total,2)}}</span></td>
                        </tr>

                        <tr>
                            <td colspan="4" class="border-0 text-end">
                                <strong>Balance</strong></td>
                            <td class="border-0 text-end"><h5 class="m-0 text-muted"> <span></span><span id="balance">{{env('APP_CURRENCY')}}{{number_format(($receipt->getInvoice->total ?? 0) - ($receipt->sub_total ?? 0) ,2)}}</span></h5></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-12 d-flex justify-content-center">
            <div class="card mt-3">
                <div class="card-block">
                    <div class="btn-group">
                        <a href="{{url()->previous()}}" class="btn btn-secondary"><i class="bx bx-left-arrow mr-2"></i>Go Back</a>
                        @if($receipt->posted == 0)
                            <button type="button" data-bs-target="#declinePayment" data-bs-toggle="modal" class="btn btn-danger w-md waves-effect waves-light">Decline <i class="bx bx-x"></i> </button>
                            <button type="button" data-bs-target="#verifyPayment" data-bs-toggle="modal" class="btn btn-success w-md waves-effect waves-light">Post <i class="bx bx-check-double"></i> </button>
                        @endif
                        <button class="btn btn-primary" onclick="generatePDF()"><i class="bx bx-printer mr-2"></i> Print Receipt</button>
                        <a href="{{route('send-receipt-as-email', ['ref'=>$receipt->trans_ref])}}" class="btn btn-custom"><i class="bx bx-envelope mr-2"></i> Email Receipt</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal  fade" id="verifyPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Post Receipt</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('action-receipt-payment')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12 mt-3 ">
                                <p>This action cannot be undone. Are you sure you want to post this receipt?</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12">
                                <input type="hidden" name="receipt" value="{{$receipt->id}}">
                                <input type="hidden" name="status" value="1">
                                @error('comment') <i class="text-danger" style="color: #ff0000;">{{$message}}</i>@enderror

                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Yes, proceed  <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal  fade" id="declinePayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Decline Receipt</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('action-receipt-payment')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12 mt-3 ">
                                <p>This action cannot be undone. Are you sure you want to <code>decline</code> this receipt?</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12">
                                <input type="hidden" name="receipt" value="{{$receipt->id}}">
                                <input type="hidden" name="status" value="2">
                                @error('comment') <i class="text-danger" style="color: #ff0000;">{{$message}}</i>@enderror

                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Yes, proceed  <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

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
                filename:     "Receipt_No_{{$receipt->receipt_no}}"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>

@endsection
