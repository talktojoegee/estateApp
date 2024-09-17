@extends('layouts.master-layout')
@section('title')
    New Salary Allowance
@endsection
@section('current-page')
    New Salary Allowance
@endsection
@section('extra-styles')
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

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <form action="{{ route('salary-allowances') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">New Salary Allowance
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Salary Structure Category <sup class="text-danger">*</sup></label>
                                        <select id="category" data-parsley-required-message="Select salary structure" required  class="form-control p-3 select2" name="category">
                                            <option disabled selected>Select salary structure</option>
                                            @foreach($salaryCategories as $record)
                                                <option value="{{$record->id}}"> {{ $record->ss_name ?? '' }} </option>
                                            @endforeach
                                        </select>
                                        <br> @error('category')<i class="text-danger">{{$message}}</i>@enderror
                                        <small>Add a payment definition, and it's corresponding amount below for the selected salary structure</small>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 lg-6 d-flex justify-content-end">
                                    <div class="form-group">
                                        <button class="btn btn-primary " type="submit">Save changes <i class="bx bx-check-double"></i> </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 lg-12" id="appendList">
                                    <div id="items">
                                        <div class="row item mt-3">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="">Payment Definition <sup style="color: #ff0000;">*</sup> </label>
                                                    <select name="paymentDefinition[]" id="" class="form-control ">
                                                        @foreach($definitions as $option)
                                                            <option value="{{$option->id}}">{{ $option->payment_name ?? ''  }} ({{ $option->pay_code ?? '' }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('paymentDefinition')
                                                    <i class="text-danger mt-2">{{$message}}</i>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="">Amount</label>
                                                    <input type="number" step="0.01" placeholder="Amount" name="amount[]" class="form-control">
                                                    @error('amount')
                                                    <i class="text-danger mt-2">{{$message}}</i>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <i class="bx bx-trash text-danger remove-line" style="cursor: pointer; color: #ff0000 !important;"></i>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-lg-12 mt-3">
                                    <button class="btn  btn-primary btn-sm add-line"> <i class="bx bx-plus mr-2"></i> Add Item</button>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit <i class="bx bx-check-double ml-2"></i></button>
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

    <script>
        $(document).ready(function(){

            $(document).on('click', '.add-line', function(e){
                e.preventDefault();
                let new_selection = $('.item').first().clone();
                $('#items').append(new_selection);

            });

            //Remove line
            $(document).on('click', '.remove-line', function(e){
                e.preventDefault();
                $(this).closest('.item').remove();

            });
        });
    </script>
@endsection
