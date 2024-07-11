@extends('layouts.master-layout')
@section('title')
    Generate Invoice
@endsection
@section('current-page')
    Generate Invoice
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/assets/libs/owl.carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/libs/owl.carousel/assets/owl.theme.default.min.css">
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('manage-applications') }}" class="btn btn-secondary "> <i
                                class="bx bx bxs-left-arrow"></i> Go back</a>
                        @if(\Illuminate\Support\Facades\Auth::user()->type == 1)
                            @if(in_array(Auth::user()->id, $authIds))
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#declineModal_" class="btn btn-danger ">  Decline <i
                                        class="bx bx-x"></i>
                                </a>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#approveModal_" class="btn btn-success ">  Approve <i
                                        class="bx bx-check"></i>
                                </a>
                            @endif
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
            <form action="{{route('new-invoice')}}" method="POST">
                @csrf
                <div class="col-lg-12"   >
                    <div class="card"   >
                        <div class="card-body"   >
                            <div class="invoice-title"   >
                                <h4 class="float-end font-size-16">Application Ref.: {{ strtoupper($workflow->p_title ?? '') }}</h4>
                                <div class="mb-4"   >
                                    <img src="/assets/drive/logo/logo-dark.png" alt="logo" height="60">
                                </div>
                            </div>
                            <hr>
                            <div class="row"   >
                                <div class="col-sm-6"   >
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        {{$workflow->getCompany->organization_name ?? ''  }}<br>
                                        {{$workflow->getCompany->phone_no ?? ''  }}<br>
                                        {{$workflow->getCompany->email ?? ''  }}<br>
                                        {{$workflow->getCompany->address ?? ''  }}
                                    </address>
                                </div>
                                <div class="col-sm-6 text-sm-end"   >
                                    <address class="mt-2 mt-sm-0">
                                        <strong>From:</strong><br>
                                        {{env('ORG_NAME')}}<br>
                                        {{env('ORG_PHONE')}}<br>
                                        {{env('ORG_EMAIL')}}<br>
                                        {{env('ORG_ADDRESS')}}
                                    </address>
                                </div>
                            </div>
                            <div class="row"   >
                                <div class="col-sm-6 mt-3"   >
                                    <address>
                                        <strong>RC No.:</strong><br>
                                        {{$workflow->getCompany->organization_code ?? '' }}<br>
                                        <strong>Year of Incorporation:</strong><br>
                                        {{ date('d M, Y', strtotime($workflow->getCompany->start_date)) ?? '' }}<br>
                                    </address>
                                </div>
                                <div class="col-sm-6 mt-3 text-sm-end"   >
                                    <address>
                                        <strong>Date Applied:</strong><br>
                                        {{date('d M, Y', strtotime($workflow->created_at))}}<br><br>
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
                                        <th>Station</th>
                                        <th>Mode</th>
                                        <th>Category</th>
                                        <th>Frequency</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Rate({{env('APP_CURRENCY')}})</th>
                                        <th>Amount({{env('APP_CURRENCY')}})</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workflow->getRadioLicenseDetails as $key => $detail)
                                        <tr>
                                            <th>{{ $key +1  }}</th>
                                            <td>{{$detail->getWorkstation->name ?? '' }}</td>
                                            <td>{{$detail->operation_mode == 1 ? 'Simplex' : 'Duplex' }}</td>
                                            <td>{{ $detail->getLicenseCategory->category_name ?? '' }}</td>
                                            <td>
                                                @switch($detail->frequency_band)
                                                    @case(1)
                                                    MF/HF
                                                    @break
                                                    @case(2)
                                                    VHF
                                                    @break
                                                    @case(3)
                                                    UHF
                                                    @break
                                                    @case(4)
                                                    SHF
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($detail->type_of_device)
                                                    @case(1)
                                                    Handheld
                                                    @break
                                                    @case(2)
                                                    Base Station
                                                    @break
                                                    @case(3)
                                                    Repeaters Station
                                                    @break
                                                    @case(4)
                                                    Vehicular Station
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <input type="hidden" value="{{$detail->id}}" name="itemId[]">
                                                <input type="number" onchange="Calc(this);" value="{{ $detail->no_of_device ?? 0 }}" name="quantity[]" readonly class="form-control quantity">
                                            </td>
                                            <td>
                                                <input type="number" onchange="Calc(this);" step="0.01" placeholder="Enter rate" name="rate[]" class="form-control rate">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01"  name="amount" readonly class="form-control amount">
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="8" class="border-0 text-end">
                                            <strong>Total</strong></td>
                                        <td class="border-0 text-end"><h4 class="m-0"> <span>{{env('APP_CURRENCY')}}</span><span id="totalAmount">0.00</span></h4></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-print-none mt-3"   >
                                <div class="float-end"   >
                                    <button type="submit" class="btn btn-primary w-md waves-effect waves-light">Submit <i class="bx bx-send"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="postId" value="{{$workflow->p_id}}" class="form-control">
            </form>
        </div>


    </div>

@endsection

@section('extra-scripts')
    <script>
        function Calc(v)
        {
            /*Detail Calculation Each Row*/
            let index = $(v).parent().parent().index();

            let qty = document.getElementsByClassName("quantity")[index].value;
            let rate = document.getElementsByClassName("rate")[index].value;

            let amt = qty * rate;
            document.getElementsByClassName("amount")[index].value = amt;
            GetTotal();
        }
        function GetTotal()
        {
            /*Footer Calculation*/
            let sum=0;
            let amts =  document.getElementsByClassName("amount");
            for (let index = 0; index < amts.length; index++)
            {
                let amt = amts[index].value;
                sum = +(sum) +  +(amt) ;
            }
            document.getElementById("totalAmount").innerHTML = sum.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        }


    </script>

@endsection



