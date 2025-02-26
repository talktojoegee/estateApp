@extends('layouts.master-layout')
@section('current-page')
    Supervisors
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <style>
        .fs-22{
            font-size: 22px;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @if(session()->has('success'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('success') !!}

                    <button type="button"  class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-outline me-2"></i>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 mt-4">
                <div class="d-flex justify-content-between modal-header">
                    <div class="h6 text-left pl-5 text-uppercase text-white" > Supervisors</div>
                    <a href="{{url()->previous()}}" class="btn btn-secondary mr-3" > <i class="bx bx-arrow-back"></i>  Go back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{route('assign-section-head')}}" method="post" class="form-inline bg-light p-3" autocomplete="off">
                            @csrf
                            <div class="row w-100">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Department</label>
                                        <select name="department"  id="department" class="form-control js-example-theme-single">
                                            <option disabled selected>--Select Department--</option>
                                            @foreach($branches as $depart)
                                                <option value="{{$depart->cb_id}}">{{$depart->cb_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('department')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Employee</label>
                                        <select name="supervisor"  id="supervisor" class="form-control js-example-theme-single">
                                            <option disabled selected>--Select user--</option>
                                            @foreach($users as $emp)
                                                <option value="{{$emp->id}}">{{$emp->title ?? '' }} {{$emp->first_name ?? '' }} {{$emp->last_name ?? '' }} {{$emp->other_names ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('supervisor')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <button class="btn btn-primary ">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="wd-15p">Department</th>
                                    <th class="wd-15p">Supervisor</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($branches as $key=>$branch)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$branch->cb_name ?? '' }}</td>
                                        <td>{{ $branch->getLeadPastor->title ?? ''}} {{ $branch->getLeadPastor->first_name ?? ''}}  {{ $branch->getLeadPastor->last_name  ??  null }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>
@endsection
