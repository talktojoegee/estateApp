@extends('layouts.master-layout')
@section('current-page')
    Add New Account
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

                <div class="col-md-12 col-sm-12 mt-4">
                    <div class="d-flex justify-content-between modal-header">
                        <div class="h6 text-left pl-5 text-uppercase text-primary"> Add New Account</div>
                        <a href="{{route('accounting.chart-of-accounts')}}" class="btn btn-primary mr-3" > <i class="bx bx-food-menu"></i> Manage Accounts</a>
                    </div>

                    <div class="container pb-5 mt-5">
                        <form autocomplete="off" action="{{route('add-new-account')}}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="">General Ledger <small>(GL)</small> Code <sup class="text-danger">*</sup></label>
                                        <input type="number" class="form-control" placeholder="General Ledger Code" id="gl_code" name="glcode" value="{{old('glcode')}}">
                                        @error('glcode')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <div  class="text-white background-danger mt-2 p-2" id="gl_code_error">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="">Account Name <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control" id="account_name" placeholder="Account Name" name="account_name" value="{{old('account_name')}}">
                                        @error('account_name')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 col-sm-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="">Account Type <sup class="text-danger">*</sup></label>
                                        <select name="account_type" id="account_type" class="form-control js-example-basic-single">
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
                                        <div  class="highlighter-rouge bg-danger mt-2 p-2" id="account_type_error">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="">Type <sup class="text-danger">*</sup></label>
                                        <select name="type" id="type" class="form-control js-example-basic-single">
                                            <option disabled selected>-- Select type --</option>
                                            <option value="0">General</option>
                                            <option value="1">Detail</option>
                                        </select>
                                        @error('type')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="">Bank <sup class="text-danger">*</sup></label>
                                        <select name="bank" id="bank" class="form-control js-example-basic-single">
                                            <option disabled selected>-- Select --</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                        </select>
                                        @error('bank')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="">Parent Account <sup class="text-danger">*</sup></label>
                                        <div id="parentAccountWrapper"></div>
                                        @error('parent_account')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
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
                                        <a href="{{url()->previous()}}" class="btn  btn-danger"><i class=""></i> Cancel</a>
                                        <button id="addNewAccountBtn" class="btn btn-primary "><i class=""></i> Add Account</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra-scripts')
    <script>
        $(document).ready(function(){
            $('#gl_code_error').hide();
            $('#account_type_error').hide();
            $('#addNewAccountBtn').prop("disabled", true);
            $("#gl_code").blur(function () {
                var gl_code = $(this).val();
                gl_code = String(gl_code);
                var length  = gl_code.length;
                if(length%2 == 0){
                    $('#gl_code_error').show();
                    $('#gl_code_error').html("Length of account number should be odd");
                    $('#addNewAccountBtn').prop("disabled", true);
                }
                else{
                    $('#gl_code_error').hide();
                    $('#addNewAccountBtn').prop("disabled", false);
                }

            });
            //Account type
            $(document).on('change', '#account_type', function(e){
                e.preventDefault();
                var account_type = $(this).val();
                //console.log(account_type);
                switch (account_type) {
                    case "1":
                        if($('#gl_code').val().toString().charAt(0) != 1){
                            $('#account_type_error').show();
                            $("#account_type_error").text("<span class='text-danger'>Invalid GL code for this account type. Hint: First number should start with <strong>1</strong></span>");
                            $('#addNewAccountBtn').prop("disabled", true);
                        }else{
                            $('#account_type_error').hide();
                            $('#addNewAccountBtn').prop("disabled", false);
                            getParentAccount(1, $('#type').val() );
                        }
                        break;
                    case "2":
                        if($('#gl_code').val().toString().charAt(0) != 2){
                            $('#account_type_error').show();
                            $("#account_type_error").text("Invalid GL code for this account type. Hint: First number should start with <strong>2</strong>");
                            $('#addNewAccountBtn').prop("disabled", true);
                        }else{
                            $('#account_type_error').hide();
                            $('#addNewAccountBtn').prop("disabled", false);
                            getParentAccount(2, $('#type').val() );
                        }
                        break;
                    case "3":
                        if($('#gl_code').val().toString().charAt(0) != 3){
                            $('#account_type_error').show();
                            $("#account_type_error").text("Invalid GL code for this account type. Hint: First number should start with <strong>3</strong>");
                            $('#addNewAccountBtn').prop("disabled", true);
                        }else{
                            $('#account_type_error').hide();
                            $('#addNewAccountBtn').prop("disabled", false);
                            getParentAccount(3, $('#type').val() );
                        }
                        break;
                    case "4":
                        if($('#gl_code').val().toString().charAt(0) != 4){
                            $('#account_type_error').show();
                            $("#account_type_error").text("Invalid GL code for this account type. Hint: First number should start with <strong>4</strong>");
                            $('#addNewAccountBtn').prop("disabled", true);
                        }else{
                            $('#account_type_error').hide();
                            $('#addNewAccountBtn').prop("disabled", false);
                            getParentAccount(4, $('#type').val() );
                        }
                        break;
                    case "5":
                        if($('#gl_code').val().toString().charAt(0) != 5){
                            $('#account_type_error').show();
                            $("#account_type_error").text("Invalid GL code for this account type. Hint: First number should start with <strong>5</strong>");
                            $('#addNewAccountBtn').prop("disabled", true);
                        }else{
                            $('#account_type_error').hide();
                            $('#addNewAccountBtn').prop("disabled", false);
                            getParentAccount(5, $('#type').val() );
                        }
                        break;


                }
            });
            //type
            $(document).on('change', '#type', function(e){
                e.preventDefault();
                getParentAccount($('#account_type').val(), $('#type').val() );
            });
        });

        function getParentAccount(account_type, type){

            axios.post("{{route('get-account-type')}}", {account_type:account_type, type:type})
                .then(response=>{
                    $('#parentAccountWrapper').html(response.data);
                });
        }
    </script>
@endsection
