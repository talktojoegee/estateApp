@extends('layouts.master-layout')
@section('title')
    Salary Structures
@endsection
@section('current-page')
    Salary Structures
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @if(session()->has('success'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>

                            {!! session()->get('success') !!}

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-warning">
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </div>
    @endif
    @include('payroll.partial._salary-menu')
    <div class="row">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Employee Name</th>
                                <th class="wd-15p">Department</th>
                                <th class="wd-15p">Salary Structure</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $key=> $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->title ?? '' }} {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->other_names ?? '' }}</td>
                                    <td>{{ $user->getUserChurchBranch->cb_name ?? '' }}</td>
                                    <td>
                                        @if($user->salary_structure_setup == 1)
                                            @if($user->salary_structure_category == 0)
                                                <span class="badge rounded-pill bg-danger" style="background: #ff0000 !important;">Personalized</span>
                                            @else
                                                <span class="badge rounded-pill bg-primary" style="background: #097812 !important;">Categorized</span>
                                            @endif
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                @if($user->salary_structure_setup == 1)
                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#editModal_{{$user->id}}" data-bs-toggle="modal"> <i class="bx bx-pencil text-warning"></i> Edit Structure</a>
                                                <a class="dropdown-item" href="{{route('employee-salary-structure', $user->slug)}}" > <i class="bx bx-show text-info"></i> View Structure</a>
                                                @endif
                                                @if($user->salary_structure_setup == 0)
                                                <a class="dropdown-item" href="{{route('salary-setup-form', $user->slug)}}" > <i class="bx bxs-extension text-danger" style="color: #ff0000 !important;"></i> Setup Structure</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
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
