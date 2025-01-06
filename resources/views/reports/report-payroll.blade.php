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
                                <form action="{{route('update-imported-properties')}}" method="POST">
                                    @csrf
                                    <div class="table-responsive mt-3">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th class="">#</th>
                                                <th class="wd-15p">Name</th>
                                                @foreach($columns as  $col)
                                                    <th class="wd-15p">{{ $col->payment_name ?? '' }}</th>
                                                @endforeach

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($salaries as $key => $sal)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{$sal->getEmployee->title ?? '' }} {{$sal->getEmployee->first_name ?? '' }} {{$sal->getEmployee->last_name ?? '' }} {{$sal->getEmployee->other_names ?? '' }}</td>
                                                    @foreach($columns as $column)
                                                        @if($column->id == $sal->payment_definition_id)
                                                            <td>
                                                                <input type="text" value="{{ $sal->amount ?? 0 }}" class="form-control">
                                                            </td>
                                                        @else
                                                            <td>
                                                                <input type="text" value="0" class="form-control">
                                                            </td>
                                                        @endif
                                                    @endforeach
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
