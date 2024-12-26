@extends('layouts.master-layout')
@section('current-page')
 Stock Details
@endsection

@section('title')
 Stock Details
@endsection


@section('extra-styles')

@endsection

@section('breadcrumb-action-btn')

@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="d-flex justify-content-end">
                @if($record->status == 0)
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#actionRequest"  class="btn btn-warning  mb-3">Action Request <i class="bx bx-timer"></i> </a>
                @endif

                @if($record->trans_type == 1)
                        <a href="{{ route('manage-inventory', 'purchases') }}"  class="btn btn-primary  mb-3">Manage Purchases <i class="bx bx-list-ul"></i> </a>
                @else
                    <a href="{{ route('manage-inventory', 'discharge') }}"  class="btn btn-primary  mb-3">Manage Discharge <i class="bx bx-list-ul"></i> </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row"   >
        <div class="col-lg-12"   >
            <div class="card"   >
                <div class="card-body"   >
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>
                            {!! session()->get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-close me-2"></i>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div id="printArea">
                        <h5 class="modal-header text-uppercase text-info">Stock Details</h5>
                        <div class="invoice-title"   >

                            <!-- <div class="mb-4 text-center"   >
                                <img src="/assets/drive/logo/logo-dark.png" alt="logo" height="60">
                            </div> -->
                        </div>
                        <hr>
                        <div class="row"   >
                            <div class="col-sm-6 "   >
                                <address>
                                    <strong>From:</strong><br>
                                    <strong>Name: </strong>{{ $record->getVendor->first_name ?? ''  }} {{$record->getVendor->last_name ?? ''  }}<br>
                                    <strong>Mobile No.: </strong>{{$record->getVendor->mobile_no ?? ''  }}<br>
                                    <strong>Email: </strong>{{$record->getVendor->email ?? ''  }}<br>
                                    <strong>Address: </strong>{{$record->getVendor->address ?? ''  }}
                                </address>
                            </div>

                            <div class="col-sm-6 "   >
                                <address class="mt-2 mt-sm-0">
                                    <strong>To:</strong><br>
                                    <strong>Name:</strong> {{env('ORG_NAME')}}<br>
                                    <strong>Mobile No.:</strong> {{env('ORG_PHONE')}}<br>
                                    <strong>Email:</strong> <a href="mailto:{{env('ORG_EMAIL')}}">{{env('ORG_EMAIL')}}</a><br>
                                    <strong>Address: </strong>{!! env('ORG_ADDRESS') !!} <br>
                                    <strong>Website: </strong><a target="_blank" href="#">{{env('ORG_WEBSITE')}}</a>
                                </address>
                            </div>



                        </div>

                        <div class="row"   >
                            <div class="col-sm-6 mt-3"   >
                                <address>
                                    @if($record->trans_type == 1 )
                                    <strong>Purchased By:</strong><br>
                                        {{$record->getPurchasedBy->title ?? '' }} {{$record->getPurchasedBy->first_name ?? '' }} {{$record->getPurchasedBy->last_name ?? '' }} {{$record->getPurchasedBy->other_names ?? '' }}<br>
                                    @else
                                        <strong>Purchased By:</strong><br>
                                        {{$record->getSoldBy->title ?? '' }} {{$record->getSoldBy->first_name ?? '' }} {{$record->getSoldBy->last_name ?? '' }} {{$record->getSoldBy->other_names ?? '' }}<br>
                                    @endif
                                    <strong>Date:</strong><br>
                                    <span class="text-success"> {{date('d M, Y', strtotime($record->trans_date)) ?? '' }}</span><br>
                                        <strong> Status:</strong> <br>
                                        @switch($record->status)
                                            @case(0)
                                            <span class="text-warning">Pending</span>
                                            @break
                                            @case(1)
                                            <span class="text-secondary">Approved</span>
                                            @break
                                            @case(2)
                                            <span class="text-danger" style="color: #ff0000 !important;">Declined</span>
                                            @break
                                        @endswitch
                                </address>
                            </div>
                            <div class="col-sm-6  text-sm-end"   >

                            </div>
                        </div>
                        <div class="py-2 mt-3"   >
                            <h3 class="font-size-15 fw-bold text-info"> Summary</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th style="text-align: right;">Quantity</th>
                                    <th style="text-align: right;">Amount({{env('APP_CURRENCY')}})</th>
                                    <th style="text-align: right;">Total({{env('APP_CURRENCY')}})</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $index = 1; @endphp
                                @foreach($record->getItems as $item)
                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td>{{ $item->getItemLabel($item->item_id)->product_name ?? '' }}</td>
                                        <td style="text-align: right;">{{ number_format($item->quantity ?? 0) ?? '' }}</td>
                                        <td style="text-align: right;">{{ number_format($item->amount ?? 0) ?? '' }}</td>
                                        <td style="text-align: right;">{{ number_format(($item->amount * $item->quantity) ?? 0) ?? '' }}</td>
                                    </tr>

                                @endforeach



                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-print-none mt-3"   >
                        <div class="float-end"   >

                            @can('can-print-invoice')
                                <button type="button" onclick="generatePDF()" class="btn btn-warning w-md waves-effect waves-light">Print <i class="bx bx-printer"></i> </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal  fade" id="actionRequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog w-100" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Action Request</h6>
                <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form autocomplete="off" action="{{route('action-request')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="form-group mt-1 col-md-12 mt-3 ">
                            <p>This action cannot be undone. Are you sure you want to proceed with this?</p>
                        </div>
                        <div class="form-group">
                            <label for="">Choose an action <sup class="text-danger">*</sup> </label>
                            <select name="action" id="action" class="form-control">
                                <option disabled selected>---Select Action---</option>
                                <option value="1">Approve</option>
                                <option value="2">Decline</option>
                            </select>
                            @error('action') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group mt-1 col-md-12">
                            <input type="hidden" name="record" value="{{$record->id}}">
                            @error('comment') <i class="text-danger" style="color: #ff0000;">{{$message}}</i>@enderror

                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center mt-3">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit  <i class="bx bx-check-double"></i> </button>
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
                filename:     "Stock"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>

@endsection
