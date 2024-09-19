@extends('layouts.master-layout')
@section('title')
    Invoice Detail
@endsection
@section('current-page')
    Invoice Detail
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('manage-invoices', 'invoices')}}" class="btn btn-secondary "> <i
                                class="bx bx bxs-left-arrow"></i> Go back</a>

                        @if(($invoice->status == 1) && \Illuminate\Support\Facades\Auth::user()->type == 1)
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#declinePayment" class="btn btn-danger ">  Decline <i
                                    class="bx bx-x"></i>
                            </a>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#verifyPayment" class="btn btn-success ">  Verify <i
                                    class="bx bx-check"></i>
                            </a>
                        @endif
                    </div>

                    @if(session()->has('success'))
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="row"   >
                <div class="col-lg-12"   >
                    <div class="card"   >
                        <div class="card-body"   >
                            <div id="printArea">
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
                                            <strong>Name:</strong> {{env('ORG_NAME')}}<br>
                                            <strong>Mobile No.:</strong> {{env('ORG_PHONE')}}<br>
                                            <strong>Email:</strong> <a href="mailto:{{env('ORG_EMAIL')}}">{{env('ORG_EMAIL')}}</a><br>
                                            <strong>Address: </strong>{!! env('ORG_ADDRESS') !!} <br>
                                            <strong>Website: </strong><a target="_blank" href="http://www.{{env('ORG_WEBSITE')}}">{{env('ORG_WEBSITE')}}</a>
                                        </address>
                                    </div>

                                    <div class="col-sm-6 text-sm-end"   >
                                        <address>
                                            <strong>Billed To:</strong><br>
                                            <strong>Name: </strong>{{$invoice->getCustomer->first_name ?? ''  }} {{$invoice->getCustomer->last_name ?? ''  }}<br>
                                            <strong>Mobile No.: </strong>{{$invoice->getCustomer->phone ?? ''  }}<br>
                                            <strong>Email: </strong>{{$invoice->getCustomer->email ?? ''  }}<br>
                                            <strong>Address: </strong>{{$invoice->getCustomer->street ?? ''  }}
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
                                <div class="py-2 mt-3"   >
                                    <h3 class="font-size-15 fw-bold">Order summary</h3>
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
                                        @foreach($invoice->getInvoiceDetail as $key => $detail)
                                            <tr>
                                                <th>{{ $key +1  }}</th>
                                                <td>{{$detail->description ?? '' }}</td>
                                                <td>{{ number_format($detail->quantity ?? 0) }}</td>
                                                <td style="text-align: right;">{{ number_format($detail->unit_cost ?? 0,2)  }}</td>
                                                <td style="text-align: right;">{{ number_format(($detail->quantity ?? 0) * ($detail->unit_cost ?? 0),2)  }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="border-0 text-end">
                                                <strong>Total</strong></td>
                                            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="totalAmount">{{number_format($invoice->total ?? 0 ,2)}}</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="border-0 text-end">
                                                <strong> Paid</strong></td>
                                            <td class="border-0 text-end"><span>{{env('APP_CURRENCY')}}</span><span>{{number_format($invoice->amount_paid ?? 0 ,2)}}</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="border-0 text-end">
                                                <strong>Balance</strong></td>
                                            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="balance">{{number_format(($invoice->total ?? 0) - ($invoice->amount_paid ?? 0) ,2)}}</span></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-print-none mt-3"   >
                                <div class="float-end"   >
                                    @if($invoice->posted == 0)
                                    <button type="button" data-bs-target="#declinePayment" data-bs-toggle="modal" class="btn btn-danger w-md waves-effect waves-light">Decline <i class="bx bx-x"></i> </button>
                                    <button type="button" data-bs-target="#verifyPayment" data-bs-toggle="modal" class="btn btn-success w-md waves-effect waves-light">Post <i class="bx bx-check-double"></i> </button>
                                    @endif
                                    <button type="button" onclick="generatePDF()" class="btn btn-warning w-md waves-effect waves-light">Print <i class="bx bx-printer"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        @if($invoice->status >= 1) <!-- Paid,Verified,Declined -->
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="modal-header text-muted">Payment History</h5>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Invoice # {{$invoice->invoice_no ?? ''}}
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="text-muted">
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <thead class="table-light">
                                                    <tr class="text-uppercase">
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Receipt No.</th>
                                                        <th>Issued By</th>
                                                        <th style="text-align: right;">Amount({{env('APP_CURRENCY')}})</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php $serial = 1; @endphp
                                                    @foreach($invoice->getAllInvoiceReceipts as $log)
                                                        <tr>
                                                            <th scope="row">{{$serial++}}</th>
                                                            <td>{{date('d M, Y h:ia', strtotime($log->created_at))}}</td>
                                                            <td><a href="#" target="_blank">{{$log->receipt_no ?? '' }}</a></td>
                                                            <td>{{$log->getIssuedBy->title ?? '' }} {{$log->getIssuedBy->first_name ?? '' }} {{$log->getIssuedBy->last_name ?? '' }} {{$log->getIssuedBy->other_names ?? '' }}</td>
                                                            <td style="text-align: right;">{{number_format(($log->sub_total) ?? 0 ,2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <th colspan="3">Total</th>
                                                        <td colspan="3" style="text-align: right;"><strong>{{env('APP_CURRENCY')}}{{number_format($invoice->getAllInvoiceReceipts->sum('sub_total'),2)}}</strong></td>
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
                </div>
            </div>
        </div>
        @endif
    </div>



    <div class="modal  fade" id="verifyPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Post Invoice</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('action-payment')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12 mt-3 ">
                                <p>This action cannot be undone. Are you sure you want to post this invoice?</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12">
                                <input type="hidden" name="invoiceId" value="{{$invoice->id}}">
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
                    <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Decline Invoice</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('action-payment')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12 mt-3 ">
                                <p>This action cannot be undone. Are you sure you want to <code>decline</code> this invoice?</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-1 col-md-12">
                                <input type="hidden" name="invoiceId" value="{{$invoice->id}}">
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
            let element = document.getElementById('printArea');
            html2pdf(element,{
                margin:       10,
                filename:     "Invoice"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>


@endsection



