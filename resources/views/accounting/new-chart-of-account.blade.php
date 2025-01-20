@extends('layouts.master-layout')
@section('title')
    Add New Account
@endsection
@section('current-page')
    Add New Account
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/assets/css/select2.css">
    <style>
        .background-danger{
            background: #ff0000 !important;
        }
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12 col-lg-12 mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary "> <i
                    class="bx bx bxs-left-arrow"></i> Go back</a>
            <a href="{{ route('chart-of-accounts') }}" class="btn btn-primary ">  Manage Accounts <i
                    class="bx bx-list-ul"></i></a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 offset-md-3 offset-lg-3 offset-sm-3">

            <div class="card">
                <div class="card-body">
                    <div class="modal-header mb-3">New Account</div>
                    @if (session()->has('success'))
                        <div class="alert alert-success background-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="bx bx-check-double"></i>
                            </button>
                            {!! session()->get('success') !!}
                        </div>
                    @endif
                    <form autocomplete="off" action="{{route('new-chart-of-account')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                <div class="form-group">
                                    <label for="">Account Name <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control" id="account_name" placeholder="Account Name" name="account_name" value="{{old('account_name')}}">
                                    @error('account_name')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                <div class="form-group">
                                    <label for="">Account Number  <sup class="text-danger">*</sup></label>
                                    <input type="number" class="form-control" placeholder="Account Number" id="gl_code" name="glcode" value="{{old('glcode')}}">
                                    @error('glcode')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror

                                </div>
                            </div>


                            <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                <div class="form-group">
                                    <label for="">Account Type <sup class="text-danger">*</sup></label>
                                    <select name="account_type" id="account_type" class="form-control ">
                                        <option disabled selected>-- Select account type --</option>
                                        <option value="1">Asset</option>
                                        <option value="2">Liability</option>
                                        <option value="3">Equity</option>
                                        <option value="4">Revenue</option>
                                        <option value="5">Expense</option>
                                    </select>
                                    @error('account_type')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                <div class="form-group">
                                    <label for="">Type <sup class="text-danger">*</sup></label>
                                    <select name="type" id="type" class="form-control ">
                                        <option disabled selected>-- Select type --</option>
                                        <option value="0">General</option>
                                        <option value="1">Detail</option>
                                    </select>
                                    @error('type')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                <div class="form-group">
                                    <label for="">Bank <sup class="text-danger">*</sup></label>
                                    <select name="bank" id="bank" class="form-control ">
                                        <option disabled selected>-- Select --</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                    @error('bank')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <!-- <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                <div class="form-group">
                                    <label for="">Parent Account <sup class="text-danger">*</sup></label>
                                    <div id="parentAccountWrapper"></div>
                                    error('parent_account')
                                        <i class="text-danger mt-2">{$message}}</i>
                                    enderror
                                </div>
                            </div> -->
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="">Note <sup class="text-danger">*</sup></label>
                                    <textarea name="note" id="note" style="resize: none;" class="form-control" placeholder="Leave note">{{old('note')}}</textarea>
                                    @error('note')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="btn-group">
                                    <a href="{{url()->previous()}}" class="btn  btn-danger"><i class="bx bx-trash"></i> Cancel</a>
                                    <button id="addNewAccountBtn" class="btn btn-primary "> Submit <i class="bx bx-check-double"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/assets/js/axios.min.js"></script>

@endsection
