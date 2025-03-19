@extends('layouts.master-layout')
@section('current-page')
    Payroll Report
@endsection
@section('title')
    Payroll Report
@endsection
@section('extra-styles')
    <style>
        .red-highlight{
            background: #FBCCCC !important;
        }
        .blue-highlight{
            background: #01204D !important;
        }
        .dark-green-highlight{
            background: #002E1F !important;
        }
        .dark-red-highlight{
            background: #280003 !important;
        }
        th{
            font-size: 10px;
            text-transform: uppercase;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')

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
                @include('reports.partials._payroll-search-form')
            @else
                @include('reports.partials._payroll-search-form')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title text-uppercase text-info modal-title"> {{ date('F, Y', strtotime($period)) }} Payroll Report</h4>
                                <p>Showing payroll report for <code>{{date('F, Y', strtotime($period))}}</code>. </p>
                                <p>All values are in the Nigerian naira({{config('app.APP_CURRENCY')}})</p>
                                <form action="{{route('update-imported-properties')}}" method="POST">
                                    @csrf
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Designation</th>
                                                @foreach($incomeHeaders as $income)
                                                    <th >{{ $income }}</th>
                                                @endforeach
                                                <th class="dark-green-highlight text-white">Gross Salary</th>
                                                @foreach($deductionHeaders as $deduction)
                                                    <th>{{ $deduction }}</th>
                                                @endforeach
                                                <th class="dark-red-highlight text-white">Total Deduction</th>
                                                <th>Net Pay</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tableData as $row)
                                                <tr>
                                                    <td>{{ $row['name'] }}</td>
                                                    <td>{{ $row['design'] }}</td>
                                                    @foreach($incomeHeaders as $income)
                                                        <td class="text-right" style="text-align: right;">{{ number_format($row['income'][$income] ?? 0, 2) }}</td>
                                                    @endforeach
                                                    <td class="dark-green-highlight text-white" style="text-align: right;">{{ number_format($row['total_income'], 2) }}</td>
                                                    @foreach($deductionHeaders as $deduction)
                                                        <td class="" style="text-align: right;">{{ number_format($row['deductions'][$deduction] ?? 0, 2) }}</td>
                                                    @endforeach
                                                    <td class="dark-red-highlight text-white" style="text-align: right;">{{ number_format($row['total_deduction'], 2) }}</td>
                                                    <td style="text-align: right;">{{ number_format($row['net_pay'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="2">Totals</th>
                                                @foreach($incomeHeaders as $income)
                                                    <th></th>
                                                @endforeach
                                                <th class="dark-green-highlight text-white" style="text-align: right;">{{ number_format($totalIncome, 2) }}</th>
                                                @foreach($deductionHeaders as $deduction)
                                                    <th></th>
                                                @endforeach
                                                <th class="dark-red-highlight text-white" style="text-align: right;">{{ number_format($totalDeduction, 2) }}</th>
                                                <th style="text-align: right;">{{ number_format($totalNet, 2) }}</th>
                                            </tr>
                                            </tfoot>
                                        </table>



                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>

@endsection

@section('extra-scripts')

@endsection
