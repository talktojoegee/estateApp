@extends('layouts.master-layout')
@section('title')
    Setup Salary Structure
@endsection
@section('current-page')
    Setup Salary Structure
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
            <div class="modal-header text-uppercase">Setup Salary Structure</div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('setup-salary-structure') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">Setup Salary Structure
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Employee Name <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <input type="text" readonly placeholder="Employee Name" value="{{ $user->title ?? '' }} {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->other_names ?? '' }}" class="form-control">
                                        <br> @error('employeeName')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Department <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <input type="text" readonly placeholder="Department" value="{{ $user->getUserChurchBranch->cb_name ?? '' }}" class="form-control">
                                        <br> @error('department')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Employee Email <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <input type="text" readonly placeholder="Employee Email" value="{{ $user->email ?? '' }} " class="form-control">
                                        <br> @error('employeeEmail')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Employee Mobile No. <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <input type="text" readonly placeholder="Employee Mobile No." value="{{ $user->cellphone_no ?? '' }}" class="form-control">
                                        <br> @error('employeeMobileNo')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Salary Structure Type <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <select name="salaryStructureType" id="salaryStructureType" class="form-control">
                                            <option disabled selected>---Select---</option>
                                            <option value="0">Personalized</option>
                                            <option value="1">Categorized</option>
                                        </select>
                                        <input type="hidden" name="employee" value="{{ $user->id }}">
                                        <br> @error('salaryStructureType')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 lg-6" id="category">
                                    <div class="form-group">
                                        <label for="">Salary Structure Category <sup class="text-danger" style="color: #ff0000 !important;">*</sup></label>
                                        <select id="category" data-parsley-required-message="Select salary structure" required  class="form-control p-3 select2" name="category">
                                            <option disabled selected>Select salary category</option>
                                            @foreach($categories as $record)
                                                <option value="{{$record->id}}"> {{ $record->ss_name ?? '' }} </option>
                                            @endforeach
                                        </select>
                                        <br> @error('category')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="guard">
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
            $('#guard').hide();
            $('#category').hide();

            $('#salaryStructureType').on('change', function(e){
                e.preventDefault();
                let selection = $(this).val();
                if(parseInt(selection) === 0){
                    $('#guard').show()
                    $('#category').hide()
                }else{
                    $('#guard').hide()
                    $('#category').show()
                }
            });

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
