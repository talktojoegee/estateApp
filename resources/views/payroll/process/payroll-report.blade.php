@extends('layouts.master-layout')

@section('title')
    Payroll Report
@endsection
@section('current-page')
    Payroll Report
@endsection
@section('extra-styles')
    <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @inject('Utility', 'App\Http\Controllers\Portal\PropertyController')
    <div class="container-fluid">
        <div class="row">
            @if($search == 0)
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {!! session()->get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @include('payroll.process._search-form')
            @else
                @include('payroll.process._search-form')
                <div class="col-xl-12 col-md-12">
                    <div class="card"   >
                        <div class="card-body"   >
                            <div id="printArea">
                                <div class="invoice-title"   >
                                    <div class="mb-4 text-center"   >
                                        <img src="/assets/drive/logo/logo-dark.png" alt="logo" height="60">
                                        <address class="mt-2 mt-sm-0 text-left">
                                            <strong>Mobile No.:</strong> {{env('ORG_PHONE')}}<br>
                                            <strong>Email:</strong> <a href="mailto:{{env('ORG_EMAIL')}}">{{env('ORG_EMAIL')}}</a><br>
                                            <strong>Address: </strong>{!! env('ORG_ADDRESS') !!} <br>
                                            <strong>Website: </strong><a target="_blank" href="http://www.{{env('ORG_WEBSITE')}}">{{env('ORG_WEBSITE')}}</a>
                                        </address>
                                        <h6 class="text-uppercase" style="color: black !important;">Payslip for the month of <span class="text-danger">{{ DateTime::createFromFormat('!m', $month)->format('F') }}, {{ $year }}</span></h6>
                                    </div>
                                </div>
                                <hr>
                                <div class="row"   >
                                    <div class="col-sm-6 "   >
                                        <img src="{{url('storage/'.$user->image)}}" alt="logo" height="60">
                                        <address class="mt-2 mt-sm-0">
                                            <strong>Name: </strong>{{$user->title ?? ''  }} {{$user->first_name ?? ''  }} {{$user->last_name ?? ''  }} {{$user->other_names ?? ''  }}<br>
                                            <strong>Mobile No.: </strong>{{$user->cellphone ?? ''  }}<br>
                                            <strong>Email: </strong>{{$user->email ?? ''  }}<br>
                                            <strong>Address: </strong>{{$user->street ?? ''  }}
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="py-2 mt-3"   >
                                            <h3 class="font-size-15 fw-bold text-muted text-uppercase">Earnings</h3>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">

                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($earnings as $key=> $record)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $record->getPaymentDefinition->payment_name ?? '' }}</td>
                                                        <td style="text-align: right;">{{env('APP_CURRENCY')}}{{ number_format($record->amount ?? 0,2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-end">
                                                       <strong>Total: </strong> {{env('APP_CURRENCY')}}{{ number_format($earnings->sum('amount'),2) }}
                                                    </td>
                                                </tr>



                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="py-2 mt-3"   >
                                            <h3 class="font-size-15 fw-bold text-muted text-uppercase">Deductions</h3>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">

                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($deductions as $key=> $rec)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $rec->getPaymentDefinition->payment_name ?? '' }}</td>
                                                        <td style="text-align: right;">{{env('APP_CURRENCY')}}{{ number_format($rec->amount ?? 0,2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-end">
                                                        <strong>Total: </strong> {{env('APP_CURRENCY')}}{{ number_format($deductions->sum('amount'),2) }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><strong>Gross Pay: </strong>{{ env('APP_CURRENCY') }}{{number_format($records->sum('amount'),2)}}</td>
                                                    <td><strong>Deductions: </strong>{{ env('APP_CURRENCY') }}{{number_format($deductions->sum('amount'),2)}}</td>
                                                    <td><strong>Net Pay: </strong>{{ env('APP_CURRENCY') }}{{number_format(($records->sum('amount') - $deductions->sum('amount')),2)}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                       @if(count($records) > 0) <p class="text-center"><strong>Net pay in words:</strong> {{$Utility->convert_to_words(($records->sum('amount') - $deductions->sum('amount')))}} naira only</p> @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-print-none mt-3"   >
                                <div class="float-end"   >
                                    <button type="button" onclick="generatePDF()" class="btn btn-warning w-md waves-effect waves-light">Print <i class="bx bx-printer"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@section('extra-scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function(){

        });
        function generatePDF(){
            let period = "{{!empty($month) ?  $user->first_name.'_'.DateTime::createFromFormat('!m', $month)->format('F').'_'.$year : '_'  }}"
            let element = document.getElementById('printArea');
            html2pdf(element,{
                margin:       10,
                filename:     period+"_Payslip"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>
@endsection
