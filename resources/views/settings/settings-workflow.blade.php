@extends('layouts.master-layout')
@section('current-page')
    Workflow Settings
@endsection
@section('title')
    Workflow Settings
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
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
    <div class="card">
        <div class="card-body" style="padding: 2px;">
            <div class="row">
                <div class="col-md-3">
                    @include('settings.partial._sidebar-menu')
                </div>
                <div class="col-md-9 mt-4">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="modal-header">Workflow Settings</h5>

                                <p class="p-4">
                                <form action="{{route('workflow-settings')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which section/unit should first attend to <strong>New Licence Application</strong>?</label>
                                                <select name="new_app_section" id="new_app_section" class="form-control js-example-theme-single">
                                                    <option disabled selected>-- Select section/unit --</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->cb_id}}" {{ !empty($app_licence_setting->new_app_section_handler) ? ($department->cb_id == $app_licence_setting->new_app_section_handler ? "selected" : '')  : 'selected'}}>{{$department->cb_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('new_app_section')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($app_licence_setting) ? $app_licence_setting->getDepartmentById($app_licence_setting->new_app_section_handler)->cb_name : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which section/unit should attend to <strong>Frequency Assignment</strong>?</label>
                                                <select name="frequency_assignment_handler" id="frequency_assignment_handler" class="form-control js-example-theme-single">
                                                    <option disabled selected>-- Select section/unit --</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->cb_id}}" {{ !empty($app_licence_setting->frequency_assignment_handler) ? ($department->cb_id == $app_licence_setting->frequency_assignment_handler ? "selected" : '')  : 'selected'}} >{{$department->cb_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('frequency_assignment_handler')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($app_licence_setting) ?  $app_licence_setting->getDepartmentById($app_licence_setting->frequency_assignment_handler)->cb_name : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which section/unit should attend to <strong>Licence Renewal</strong>?</label>
                                                <select name="licence_renewal" id="licence_renewal" class="form-control js-example-theme-single">
                                                    <option disabled selected>-- Select section/unit --</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->cb_id}}" {{ !empty($app_licence_setting->licence_renewal_handler) ? ($department->cb_id == $app_licence_setting->licence_renewal_handler ? "selected" : '')  : 'selected'}} >{{$department->cb_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('licence_renewal')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($app_licence_setting) ?  $app_licence_setting->getDepartmentById($app_licence_setting->licence_renewal_handler)->cb_name : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which section/unit can engage the <strong>Customer</strong>?</label>
                                                <select name="engage_customer" id="engage_customer" class="form-control js-example-theme-single">
                                                    <option disabled selected>-- Select section/unit --</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->cb_id}}" {{ !empty($app_licence_setting->engage_customer) ? ($department->cb_id == $app_licence_setting->engage_customer ? "selected" : '')  : 'selected'}}  >{{$department->cb_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('engage_customer')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($app_licence_setting) ?  $app_licence_setting->getDepartmentById($app_licence_setting->engage_customer)->cb_name : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                        </div>
                                    </div>
                                </form>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script>
        $(document).ready(function(){
            $('#grantAll').on('change', function(){
                if ($(this).is(':checked'))
                    $('#permissionWrapper').hide();
                else
                    $('#permissionWrapper').show();
            });
        });
    </script>

@endsection
