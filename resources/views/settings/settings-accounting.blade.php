@extends('layouts.master-layout')
@section('current-page')
    Accounting Settings
@endsection
@section('title')
    Accounting Settings
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
                                <h5 class="modal-header">Accounting Settings</h5>

                                <p class="p-4">
                                <form action="{{route('accounting-settings')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Note: </strong> Here we'll set-up some default accounts that the system should use in the event that no account was selected or use for a particular transaction. In such situation, the default account selected here will be used instead.</p>
                                            <div class="form-group">
                                                <label for="">Which should be used for  <strong>Property</strong>?</label>
                                                <select name="property_account" id="property_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->property_account) ? ($account->glcode == $defaults->property_account ? "selected" : '')  : 'selected'}}>{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('property_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->property_account)->account_name : '' }} - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->property_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>Customers</strong>?</label>
                                                <select name="customer_account" id="customer_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->customer_account) ? ($account->glcode == $defaults->customer_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('customer_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->customer_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->customer_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>Vendors</strong>?</label>
                                                <select name="vendor_account" id="vendor_account" class="form-control select2">
                                                    <option disabled selected>-- Select section/unit --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->vendor_account) ? ($account->glcode == $defaults->vendor_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('vendor_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->vendor_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->vendor_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>Tax</strong> collection?</label>
                                                <select name="tax_account" id="tax_vat_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->tax_account) ? ($account->glcode == $defaults->tax_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tax_vat_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->tax_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->tax_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used to handle <strong>Refund</strong> ?</label>
                                                <select name="refund_account" id="refund_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->refund_account) ? ($account->glcode == $defaults->refund_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('refund_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->refund_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->refund_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>Charges/fees</strong> ?</label>
                                                <select name="charges_account" id="charge_fee_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->charges_account) ? ($account->glcode == $defaults->charges_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('charge_fee_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->charges_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->charges_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>salaries</strong> ?</label>
                                                <select name="salary_account" id="salary_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->salary_account) ? ($account->glcode == $defaults->salary_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('salary_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->salary_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->salary_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be assigned to <strong>employees</strong> ?</label>
                                                <select name="employee_account" id="employee_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->employee_account) ? ($account->glcode == $defaults->employee_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('employee_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->employee_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->employee_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>Workflow/Requisitions</strong> ?</label>
                                                <select name="workflow_account" id="workflow_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->workflow_account) ? ($account->glcode == $defaults->workflow_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('workflow_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->workflow_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->workflow_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Which should be used for <strong>General purpose</strong> ?</label>
                                                <select name="general_account" id="general_account" class="form-control select2">
                                                    <option disabled selected>-- Select account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->glcode}}" {{ !empty($defaults->general_account) ? ($account->glcode == $defaults->general_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('general_account')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                                <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($defaults) ?  $defaults->getAccountByGLCode($defaults->general_account)->account_name : '' }}  - {{ !empty($defaults) ? $defaults->getAccountByGLCode($defaults->general_account)->glcode : '' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary ">Submit <i class="bx bx-check-double"></i> </button>
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
