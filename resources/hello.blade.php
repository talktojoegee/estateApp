@extends('layouts.app')

@section('title')
    Invoice
@endsection

@section('extra-styles')

    <style>
        /* The heart of the matter */

        .horizontal-scrollable > .row {
            overflow-x: auto;
            white-space: nowrap;
        }

        .horizontal-scrollable {
            overflow-x: scroll;
            overflow-y: hidden;
            white-space: nowrap;
        }
        @media print  {
            body{
                visibility: hidden;
            }
            #invoiceContainer, #invoiceContainer * {
                visibility: visible;
            }
            #invoiceContainer{
                position: absolute;
                left: 0px;
                top: 0px;
            }

            /*	.invoice-box table tr.information table td {
                    width: 100%;
                    display: block;
                    text-align: center;
                }*/
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-block">
                    @include('livewire.backend.crm.common._slab-menu')
                </div>
            </div>
        </div>
    </div>
    <div class="card" id="invoiceContainer">
        <div class="row invoice-contact">
            <div class="col-md-8">
                <div class="invoice-box row">
                    <div class="col-sm-12">
                        <table class="table table-responsive invoice-table table-borderless">
                            <tbody>
                            <tr>
                                <td><img src="{{asset('/assets/images/company-assets/logos/'.Auth::user()->tenant->logo ?? 'logo.png')}}" class="m-b-10" alt="{{Auth::user()->tenant->company_name ?? 'CNX247 ERP Solution'}}" height="52" width="82"></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>{{ Auth::user()->tenant->company_name ?? 'Company Name here'}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>{{Auth::user()->tenant->street_1 ?? 'Street here'}} {{ Auth::user()->tenant->city ?? ''}} {{Auth::user()->tenant->postal_code ?? 'Postal code here'}}</td>
                            </tr>
                            <tr>
                                <td><a href="mailto:{{Auth::user()->tenant->email ?? ''}}" target="_top"><span class="__cf_email__" data-cfemail="">[ {{Auth::user()->tenant->email ?? 'Email here'}} ]</span></a>
                                </td>
                            </tr>
                            <tr>
                                <td>{{Auth::user()->tenant->phone ?? 'Phone Number here'}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>
        <div class="card-block">
            <div class="row invoive-info">
                <div class="col-md-4 col-sm-4 invoice-client-info">
                    <h6>Client Information :</h6>
                    <h6 class="m-0">{{$invoice->client->company_name ?? ''}}</h6>
                    <p class="m-0 m-t-10">{{$invoice->client->street_1 ?? ''}}, {{$invoice->client->postal_code ?? ''}} {{$invoice->client->city ?? 'City here'}}</p>
                    <p class="m-0">{{$invoice->client->mobile_no ?? 'Client mobile no. here'}}</p>
                    <p><a href="mailto:{{$invoice->client->email ?? ''}}" class="__cf_email__">[{{$invoice->client->email ?? 'Email here'}}]</a></p>
                </div>
                <div class="col-md-4 col-sm-4">
                    <h6>Order Information :</h6>
                    <table class="table table-responsive invoice-table invoice-order table-borderless">
                        <tbody>
                        <tr>
                            <th>Issue Date :</th>
                            <td>{{date('d F, Y', strtotime($invoice->issue_date))}}</td>
                        </tr>
                        <tr>
                            <th>Due Date :</th>
                            <td>{{!is_null($invoice->due_date) ? date('d F, Y', strtotime($invoice->due_date)) : '-'}}</td>
                        </tr>
                        <tr>
                            <th>Status :</th>
                            <td>
                                @if ($invoice->status == 0)
                                    <span class="label label-warning">Pending</span>
                                @else
                                    <span class="label label-success">Completed</span>

                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ref. No.</th>
                            <td>
                                {{$invoice->ref_no ?? ''}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-sm-4">
                    <h6 class="m-b-20">Invoice Number <span>#{{$invoice->invoice_no}}</span></h6>
                    <h6 class="text-uppercase text-primary">Balance Due :
                        <span>{{Auth::user()->tenant->currency->id != $invoice->currency_id ? $invoice->getCurrency->symbol : Auth::user()->tenant->currency->symbol }}{{number_format(($invoice->total - $invoice->paid_amount)/$invoice->exchange_rate,2)}}</span>
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table  invoice-detail-table">
                            <thead>
                            <tr class="thead-default">
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Amount <small>({{Auth::user()->tenant->currency->id != $invoice->currency_id ? $invoice->getCurrency->symbol : Auth::user()->tenant->currency->symbol }})</small> </th>
                                <th>Total <small>({{Auth::user()->tenant->currency->id != $invoice->currency_id ? $invoice->getCurrency->symbol : Auth::user()->tenant->currency->symbol }})</small> </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoice->invoiceItem as $item)
                                <tr>
                                    <td>
                                        <p>{{$item->description ?? ''}}</p>
                                    </td>
                                    <td>{{number_format($item->quantity)}}</td>
                                    <td style="text-align: right">{{number_format($item->unit_cost, 2)}}</td>
                                    <td style="text-align: right">{{number_format($item->total/$invoice->exchange_rate, 2)}}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <table class="table table-responsive invoice-table invoice-tota" style="padding:10px!important;">
                        <tbody class="float-left pl-3">
                        <tr>
                            <th class="text-left"> <strong>Account Name:</strong> </th>
                            <td class="text-left">{{Auth::user()->tenantBankDetails->account_name ?? ''}}</td>
                        </tr>
                        @if(!is_null(Auth::user()->tenantBankDetails->sort_code))<tr>
                            <th class="text-left"><strong>Sort Code:</strong> </th>
                            <td class="text-left">{{Auth::user()->tenantBankDetails->sort_code ?? ''}}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="text-left"><strong>Account Number:</strong> </th>
                            <td class="text-left">{{Auth::user()->tenantBankDetails->account_number ?? ''}}</td>
                        </tr>
                        <tr>
                            <th class="text-left"><strong>Bank:</strong> </th>
                            <td class="text-left">{{Auth::user()->tenantBankDetails->bank_name ?? ''}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6 col-md-6">
                    <table class="table table-responsive invoice-table invoice-tota" style="padding:10px!important;">
                        <tbody>
                        <tr>
                            <th>Sub Total :</th>
                            <td style="text-align: right;">{{number_format(($invoice->sub_total)/$invoice->exchange_rate,2)}}</td>
                        </tr>
                        <tr style="border-bottom: 2px solid #ccc;">
                            <th>Taxes ({{$invoice->tax_rate}}%) :</th>
                            <td style="text-align: right;">{{number_format(($invoice->tax_value)/$invoice->exchange_rate,2) ?? 0}}</td>
                        </tr>
                        <tr style="border-bottom: 2px solid #ccc;">
                            <th>Total :</th>
                            <td style="text-align: right;">{{number_format(($invoice->total)/$invoice->exchange_rate,2)}}</td>

                        </tr>
                        <tr>
                            <th class="text-success">Paid :</th>
                            <td style="text-align: right;">{{number_format(( $invoice->paid_amount),2)}}</td>
                        </tr>
                        <tr>
                            <th class="text-warning">Balance :</th>
                            <td style="text-align: right;">{{number_format((($invoice->total)/$invoice->exchange_rate - $invoice->paid_amount),2)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6>Terms And Condition :</h6>
                    <p>{!! Auth::user()->tenant->invoice_terms !!}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:-25px;">
        <div class="card-block">
            <div class="row text-center">
                <div class="col-sm-12 invoice-btn-group text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-mini btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20" value="{{$invoice->id}}" id="sendInvoiceViaEmail"> <i class="icofont icofont-email mr-2"></i> <span id="sendEmailAddon">Send as Email</span> </button>
                        <button type="button" class="btn btn-primary btn-mini btn-print-invoice m-b-10 btn-sm waves-effect waves-light m-r-20" type="button" onclick="generatePDF()" id=""><i class="icofont icofont-printer mr-2"></i> Print</button>
                        <a href="{{url()->previous()}}" class="btn btn-secondary btn-mini waves-effect m-b-10 btn-sm waves-light"><i class="ti-arrow-left mr-2"></i> Back</a>
                        <a href="{{ route('export-invoice', $invoice->slug) }}" class="btn btn-warning btn-mini waves-effect m-b-10 btn-sm waves-light"><i class="ti-export mr-2"></i> Export Word</a>
                        @if ($invoice->trash == 0)
                            @if($invoice->paid_amount < 0)
                                <a href="{{route('decline-invoice', $invoice->slug)}}" class="btn btn-danger btn-mini waves-effect m-b-10 btn-sm waves-light"><i class="ti-trash mr-2"></i> Decline Invoice</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dialog-section')

@endsection
@section('extra-scripts')
    <script src="{{asset('/assets/js/cus/printThis.js')}}"></script>
    <script src="{{asset('/assets/js/cus/axios.min.js')}}"></script>
    <script src="{{asset('/assets/js/cus/axios-progress.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>

        var body = document.body
        var html = document.documentElement
        var height = Math.max(body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight)
        var element = document.querySelector('#content')
        var heightCM = height / 35.35

        /*
             var opt = {
               margin: 1,
               filename: file_name + '.pdf',
               html2canvas: { dpi: 192, letterRendering: true },
               jsPDF: {
                   orientation: 'portrait',
                   unit: 'cm',
                   //format: [heightCM, 60]
                   format: [heightCM, 33]
                 }
             };*/

        function generatePDF(){
            var element = document.getElementById('invoiceContainer');
            html2pdf(element,{
                margin:       1,
                filename:     "Invoice_No_{{$invoice->invoice_no}}"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { /*scale: 2, logging: true, */ dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'cm', format: [heightCM, 33], orientation: 'portrait' }
            });
        }
        $(document).ready(function(){
            //print without commission
            /*			$(document).on('click', '#printInvoice', function(event){
                            $('#invoiceContainer').printThis({
                                header:"<p></p>",
                                footer:"<p></p>",
                            });
                        });*/

            //send invoice
            $(document).on('click', '#sendInvoiceViaEmail', function(e){
                $('#sendEmailAddon').text('Processing...');
                axios.post('/send/invoice/email/',{id:$(this).val()})
                    .then(response=>{
                        $('#sendEmailAddon').text('Done!');
                        Toastify({
                            text: "Invoice sent via mail.",
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: 'right', // `left`, `center` or `right`
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        }).showToast();
                    })
                    .catch(error=>{
                        $('#sendEmailAddon').text('Error!');
                        Toastify({
                            text: "Ooops! Something went wrong. Try again.",
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: 'right', // `left`, `center` or `right`
                            backgroundColor: "linear-gradient(to right, #FF0000, #DE0000)",
                        }).showToast();
                    });
            });
        });
    </script>
@endsection
