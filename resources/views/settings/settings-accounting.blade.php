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
                                <h5 class="modal-header text-info">Accounting Settings</h5>

                                <p class="p-4">
                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Estate</th>
                                            <th class="wd-15p">Property</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($records as $key => $record)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$record->e_name ?? '' }}</td>
                                                <td>{{$record->getChartOfAccountById($record->property_account)->glcode ?? '' }} - {{$record->getChartOfAccountById($record->property_account)->account_name ?? '' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#learnMoreModal_{{$record->e_id}}">Learn more</button>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($records as $rec)

        <div class="modal" id="learnMoreModal_{{$rec->e_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">
                            {{ $rec->e_name ?? '' }} Account Settings</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{route('estate-accounting-settings')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Note: </strong>  These settings will be used across board for the selected estate to automate some accounting transactions.</p>
                                    <div class="form-group">
                                        <label for="">Which should be used for  <strong>Property</strong>?</label>
                                        <select name="property_account" id="property_account" class="form-control select2">
                                            <option disabled selected>-- Select account --</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}" {{ !empty($rec->property_account) ? ($account->id == $rec->property_account ? "selected" : '')  : 'selected'}}>{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('property_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->property_account) ? $rec->getChartOfAccountById($rec->property_account)->account_name : '' }} - {{ !empty($rec->property_account) ? $rec->getChartOfAccountById($rec->property_account)->glcode : '' }}</span></p>
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
                                                <option value="{{$account->id}}" {{ !empty($rec->customer_account) ? ($account->id == $rec->customer_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('customer_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->customer_account) ? $rec->getChartOfAccountById($rec->customer_account)->account_name : '' }} - {{ !empty($rec->customer_account) ? $rec->getChartOfAccountById($rec->customer_account)->glcode : '' }}</span></p>
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
                                                <option value="{{$account->id}}" {{ !empty($rec->vendor_account) ? ($account->id == $rec->vendor_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->vendor_account) ? $rec->getChartOfAccountById($rec->vendor_account)->account_name : '' }} - {{ !empty($rec->vendor_account) ? $rec->getChartOfAccountById($rec->vendor_account)->glcode : '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Which account should be used for <strong>Discount</strong>?</label>
                                        <select name="discount_account" id="discount_account" class="form-control select2">
                                            <option disabled selected>-- Select section/unit --</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}" {{ !empty($rec->discount_account) ? ($account->id == $rec->discount_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('discount_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->discount_account) ? $rec->getChartOfAccountById($rec->discount_account)->account_name : '' }} - {{ !empty($rec->discount_account) ? $rec->getChartOfAccountById($rec->discount_account)->glcode : '' }}</span></p>
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
                                                <option value="{{$account->id}}" {{ !empty($rec->tax_account) ? ($account->id == $rec->tax_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('tax_vat_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->tax_account) ? $rec->getChartOfAccountById($rec->tax_account)->account_name : '' }} - {{ !empty($rec->tax_account) ? $rec->getChartOfAccountById($rec->tax_account)->glcode : '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for=""><strong>TAX Rate</strong></label>
                                        <input type="number" step="0.01" name="taxRate" value="{{ $rec->tax_rate ?? 0 }}" placeholder="TAX Rate" class="form-control">
                                        @error('taxRate')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
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
                                                <option value="{{$account->id}}" {{ !empty($rec->refund_account) ? ($account->id == $rec->refund_account ? "selected" : '')  : ''}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('refund_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->refund_account) ? $rec->getChartOfAccountById($rec->refund_account)->account_name : '' }} - {{ !empty($rec->refund_account) ? $rec->getChartOfAccountById($rec->refund_account)->glcode : '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for=""><strong>Refund Rate</strong></label>
                                        <input type="number" step="0.01" value="{{ $rec->refund_rate ?? 0 }}" name="refundRate" placeholder="Refund Rate" class="form-control">
                                        @error('refundRate')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
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
                                                <option value="{{$account->id}}" {{ !empty($rec->charges_account) ? ($account->id == $rec->charges_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('charge_fee_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->charges_account) ? $rec->getChartOfAccountById($rec->charges_account)->account_name : '' }} - {{ !empty($rec->charges_account) ? $rec->getChartOfAccountById($rec->charges_account)->glcode : '' }}</span></p>
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
                                                <option value="{{$account->id}}" {{ !empty($rec->salary_account) ? ($account->id == $rec->salary_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('salary_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->salary_account) ? $rec->getChartOfAccountById($rec->salary_account)->account_name : '' }} - {{ !empty($rec->salary_account) ? $rec->getChartOfAccountById($rec->salary_account)->glcode : '' }}</span></p>
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
                                                <option value="{{$account->id}}" {{ !empty($rec->employee_account) ? ($account->id == $rec->employee_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->employee_account) ? $rec->getChartOfAccountById($rec->employee_account)->account_name : '' }} - {{ !empty($rec->employee_account) ? $rec->getChartOfAccountById($rec->employee_account)->glcode : '' }}</span></p>
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
                                                <option value="{{$account->id}}" {{ !empty($rec->workflow_account) ? ($account->id == $rec->workflow_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('workflow_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->workflow_account) ? $rec->getChartOfAccountById($rec->workflow_account)->account_name : '' }} - {{ !empty($rec->workflow_account) ? $rec->getChartOfAccountById($rec->workflow_account)->glcode : '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <input type="hidden" name="estate" value="{{ $rec->e_id }}">
                                    <div class="form-group">
                                        <label for="">Which should be used for <strong>General purpose</strong> ?</label>
                                        <select name="general_account" id="general_account" class="form-control select2">
                                            <option disabled selected>-- Select account --</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}" {{ !empty($rec->general_account) ? ($account->id == $rec->general_account ? "selected" : '')  : 'selected'}} >{{$account->account_name ?? '' }} - {{$account->glcode ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('general_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <p class="mt-1"> <span class="badge badge-soft-success">Current Selection: </span> <span>{{ !empty($rec->general_account) ? $rec->getChartOfAccountById($rec->general_account)->account_name : '' }} - {{ !empty($rec->general_account) ? $rec->getChartOfAccountById($rec->general_account)->glcode : '' }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary ">Save changes <i class="bx bx-check-double"></i> </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
