@extends('layouts.master-layout')
@section('current-page')
   Account Summary
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#client" class="btn btn-primary  mb-3">Add Account <i class="bx bxs-plus-circle"></i> </a>
                    </div>
                    <div class="card-body">

                        <h4 class="card-title">Account Summary</h4>
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert me-2"></i>
                                {!! session()->get('error') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12 col-lx-12">
                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-15p">Added By</th>
                                            <th class="wd-15p">Account Name</th>
                                            <th class="wd-15p">Account No.</th>
                                            <th class="wd-15p">Type</th>
                                            <th class="wd-15p">Currency</th>
                                            <th class="wd-15p">Scope</th>
                                            <th class="wd-15p">Balance</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($accounts as $key => $account)
                                            <tr>
                                                <td>{{ $key+1  }}</td>
                                                <td>{{ date('d M, Y h:ia', strtotime($account->created_at)) }}</td>
                                                <td>{{ $account->getCashbookCreatedBy->title ?? '' }} {{ $account->getCashbookCreatedBy->first_name ?? '' }} {{ $account->getCashbookCreatedBy->last_name ?? '' }} {{ $account->getCashbookCreatedBy->other_name ?? '' }}</td>
                                                <td>{{ $account->cba_name ?? ''  }}</td>
                                                <td>{{ $account->cba_account_no ?? ''  }}</td>
                                                <td>
                                                    @if($account->cba_type == 1)
                                                        <span class="badge badge-pill badge-soft-success font-size-11">Normal</span>
                                                    @elseif($account->cba_type == 2)
                                                        <span class="badge badge-pill badge-soft-danger font-size-11">Virtual</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$account->getCurrency->name ?? '' }}
                                                </td>
                                                <td>
                                                    @if($account->cba_scope == 0)
                                                        <span class="badge badge-pill badge-soft-info font-size-11">Branch</span>
                                                    @elseif($account->cba_scope == 1)
                                                        <span class="badge badge-pill badge-soft-secondary font-size-11">Region</span>
                                                    @else
                                                        <span class="badge badge-pill badge-soft-success font-size-11">Global</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: right;">
                                                   {{ number_format(($account->getAccountCashbookRecords($account->cba_id)->sum('cashbook_credit') -  $account->getAccountCashbookRecords($account->cba_id)->sum('cashbook_debit')),2) }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#categoryModal_{{$account->cba_id}}"> <i class="bx bxs-book-open text-warning"></i> View</a>
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
            </div>
        </div>
    </div>

    <div class="modal right fade" id="client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase">Add Account</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('accounting.accounts')}}" autocomplete="off" data-parsley-validate="" method="post" id="individualSessionForm">
                        @csrf
                        <div class="form-group mt-3">
                            <label for="">Name</label>
                            <input name="name" value="{{old('name')}}" placeholder="Account Name" id="" class="form-control" data-parsley-required-message="What should we call this account?" required>
                            @error('name') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Type</label>
                            <select name="type" class="form-control" data-parsley-required-message="Normal or virtual account?" required>
                                <option selected disabled>-- Select type --</option>
                                <option value="1">Normal</option>
                                <option value="2">Virtual</option>
                            </select>
                            @error('type') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Currency</label>
                            <select name="currency" class="form-control" data-parsley-required-message="Assign a currency to this account" required>
                                <option selected disabled>-- Select currency --</option>
                                @foreach($currencies as $currency)
                                 <option value="{{ $currency->id }}">{{ $currency->name ?? '' }}({{ $currency->symbol ?? '' }})</option>
                                @endforeach
                            </select>
                            @error('currency') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Account No. <small>(Optional if account type is virtual)</small> </label>
                            <input name="accountNo" value="{{old('accountNo')}}" placeholder="Account No." id="" class="form-control" data-parsley-required-message="Enter account no." required>
                            @error('accountNo') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Scope</label>
                            <select name="scope" class="form-control" data-parsley-required-message="At what level should this account be used?" required>
                                <option selected disabled>-- Select scope --</option>
                                <option value="0">Branch Account</option>
                                <option value="1">Regional Account</option>
                                <option value="2">Global Account</option>
                            </select>
                            @error('scope') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Opening Balance <small>(Optional)</small> </label>
                            <input type="number" min="0" value="{{old('openingBalance')}}" name="openingBalance" placeholder="Opening Balance" class="form-control">
                            @error('openingBalance') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Note <small>(Optional)</small></label>
                            <textarea style="resize: none;"  name="note" placeholder="Type note here..." class="form-control">{{old('note')}}</textarea>
                            @error('note') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit <i class="bx bx-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

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
    <script src="/js/parsley.js"></script>

@endsection
