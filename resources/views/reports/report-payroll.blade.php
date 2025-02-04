@extends('layouts.master-layout')
@section('current-page')
    Payroll Report
@endsection
@section('title')
    Payroll Report
@endsection
@section('extra-styles')

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
                                <p>Showing payroll report for <code>{{date('F, Y', strtotime($period))}}</code>. Total amount in payment <span class="text-info">{{config('app.APP_CURRENCY')}}{{number_format($total,2)}}</span></p>
                                <p>All values are in the Nigerian naira({{config('app.APP_CURRENCY')}})</p>
                                <form action="{{route('update-imported-properties')}}" method="POST">
                                    @csrf
                                    <div class="table-responsive mt-3">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="wd-15p">Name</th>
                                                @foreach($headers as $header)
                                                    <th style="text-align: right !important;">{{ $header }}</th>
                                                @endforeach
                                                <th style="text-align: right !important;">Total</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tableData as $key => $row)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $row['name'] }}</td>
                                                    @foreach($headers as $header)
                                                        <td style="text-align: right !important;">{{ number_format($row[$header], 2) }}</td>
                                                    @endforeach
                                                    <td style="text-align: right !important;"><strong>{{ number_format($row['Total'],2) }}</strong></td>
                                                </tr>
                                            @endforeach

                                            </tbody>
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
