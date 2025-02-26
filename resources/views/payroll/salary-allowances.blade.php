@extends('layouts.master-layout')
@section('title')
    Salary Allowances
@endsection
@section('current-page')
    Salary Allowances
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
            <div class="modal-header text-uppercase">Salary Allowances</div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Payment Name</th>
                                <th class="wd-15p">Category</th>
                                <th class="wd-15p">Pay Code</th>
                                <th class="wd-15p" style="text-align: right;">Amount({{ env('APP_CURRENCY') }})</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allowances as $key => $allowance)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $allowance->getPayment->payment_name ?? '' }}</td>
                                    <td>{{ $allowance->getCategory->ss_name ?? '' }}</td>
                                    <td>{{ $allowance->getPayment->pay_code ?? '' }}</td>
                                    <td style="text-align: right;">{{ number_format($allowance->amount,2) ?? '' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#editModal_{{$allowance->id}}" data-bs-toggle="modal" > <i class="bx bx-pencil text-warning"></i> Edit</a>
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

    @foreach($allowances as $all)
        <div class="modal" id="editModal_{{$all->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Edit Salary Allowance</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('salary-allowances') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="modal-header text-uppercase mb-3">Edit Salary Allowance
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="">Salary Structure Category <sup class="text-danger">*</sup></label>
                                            <select  data-parsley-required-message="Select salary structure" required  class="form-control p-3 select2" name="category">
                                                <option disabled selected>Select salary structure</option>
                                                @foreach($salaryCategories as $record)
                                                    <option value="{{$record->id}}" {{ $record->id == $all->salary_structure_id ? 'selected' : null  }}> {{ $record->ss_name ?? '' }} </option>
                                                @endforeach
                                            </select>
                                            <br> @error('category')<i class="text-danger">{{$message}}</i>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 lg-12" >
                                        <div id="items">
                                            <div class="row item mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Payment Definition <sup style="color: #ff0000;">*</sup> </label>
                                                        <select name="paymentDefinition" id="" class="form-control ">
                                                            @foreach($definitions as $option)
                                                                <option value="{{$option->id}}" {{ $option->id == $all->payment_definition_id ? 'selected' : null  }}>{{ $option->payment_name ?? ''  }} ({{ $option->pay_code ?? '' }})</option>
                                                            @endforeach
                                                        </select>
                                                        @error('paymentDefinition')
                                                        <i class="text-danger mt-2">{{$message}}</i>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <input type="hidden" name="allowance" value="{{ $all->id }}">
                                                    <div class="form-group">
                                                        <label for="">Amount <sup style="color: #ff0000;">*</sup></label>
                                                        <input type="number" step="0.01" value="{{ old('amount', $all->amount) }}" placeholder="Amount" name="amount" class="form-control">
                                                        @error('amount')
                                                        <i class="text-danger mt-2">{{$message}}</i>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"> Save changes <i class="bx bx-check-double ml-2"></i></button>
                                    </div>
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
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>
@endsection
