@extends('layouts.master-layout')
@section('current-page')
    Inventory Report
@endsection
@section('title')
    Inventory Report
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
