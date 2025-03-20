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
        .td-width{
            width: 100px !important;
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
                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif
                @include('reports.partials._payroll-search-form')
            @else
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {!! session()->get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif

                @include('reports.partials._payroll-search-form')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title text-uppercase text-info modal-title"> {{ date('F, Y', strtotime($period)) }} Payroll Report</h4>
                                <p>Showing payroll report for <code>{{date('F, Y', strtotime($period))}}</code>. </p>
                                <p>All values are in the Nigerian naira({{config('app.APP_CURRENCY')}})</p>
                                <form action="{{route('update-salary')}}" method="POST">
                                    @csrf
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered ">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Designation</th>
                                                @foreach($incomeHeaders as $income)
                                                    <th  class="td-width">{{ $income }}</th>
                                                @endforeach
                                                <th class="dark-green-highlight text-white">Gross Salary</th>
                                                @foreach($deductionHeaders as $deduction)
                                                    <th class="td-width">{{ $deduction }}</th>
                                                @endforeach
                                                <th class="dark-red-highlight text-white">Total Deduction</th>
                                                <th>Net Pay</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tableData as $key => $row)
                                                <tr>

                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $row['name'] }}</td>
                                                    <td>{{ $row['design'] }}</td>
                                                    <input type="hidden" name="batchCode" value="{{$row['batchCode'] ?? '' }}">
                                                    @foreach($incomeHeaders as $income)
                                                        <td class="text-right" style="text-align: right;">
                                                            {{--{ number_format($row['income'][$income] ?? 0, 2) }}--}}
                                                            <input  name="salary[]" type="number" step="0.01" min="0" value="{{ $row['income'][$income]['amount'] ?? 0 }}" class="form-control td-width"/>
                                                            <input type="hidden" name="salaryHandler[]" value="{{ $row['income'][$income]['salaryId'] ?? 0 }}"/>
                                                            <input type="hidden" name="user[]" value="{{ $row['userId'] ?? 0 }}"/>
                                                            <input type="hidden" name="paymentDefinition[]" value="{{ $row['income'][$income]['paymentDefinition'] ?? 0 }}"/>
                                                        </td>
                                                    @endforeach
                                                    <td class="dark-green-highlight text-white" style="text-align: right;">{{ number_format($row['total_income'], 2) }}</td>
                                                    @foreach($deductionHeaders as $deduction)
                                                        <td class="" style="text-align: right;">
                                                            {{--{ number_format($row['deductions'][$deduction] ?? 0, 2) }}--}}
                                                            <input  name="salary[]" type="number" step="0.01" min="0" value="{{ $row['deductions'][$deduction]['amount'] ?? 0 }}" class="form-control td-width">
                                                            <input type="hidden" name="salaryHandler[]" value="{{ $row['deductions'][$deduction]['salaryId'] ?? 0 }}"/>
                                                            <input type="hidden" name="user[]" value="{{ $row['userId'] ?? 0 }}"/>
                                                            <input type="hidden" name="paymentDefinition[]" value="{{ $row['deductions'][$deduction]['paymentDefinition'] ?? 0 }}"/>
                                                        </td>
                                                    @endforeach
                                                    <td class="dark-red-highlight text-white" style="text-align: right;">{{ number_format($row['total_deduction'], 2) }}</td>
                                                    <td style="text-align: right;">{{ number_format($row['net_pay'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3">Totals</th>
                                                @foreach($incomeHeaders as $income)
                                                    <th></th>
                                                @endforeach
                                                <th class="dark-green-highlight text-white" style="text-align: right;">
                                                    {{ number_format($totalIncome, 2) }}
                                                </th>
                                                @foreach($deductionHeaders as $deduction)
                                                    <th></th>
                                                @endforeach
                                                <th class="dark-red-highlight text-white" style="text-align: right;">{{ number_format($totalDeduction, 2) }}</th>
                                                <th style="text-align: right;">{{ number_format($totalNet, 2) }}</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <button style="border-radius: 0px !important; width: 300px;" type="submit" class="btn btn-primary btn-lg waves-effect waves-light">Save Changes</button>
                                        </div>
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
