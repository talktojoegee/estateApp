@extends('layouts.master-layout')
@section('title')
{{$user->title ?? ''}} {{$user->first_name ?? '' }} {{ $user->last_name ?? '' }} Salary Structure
@endsection
@section('current-page')
{{$user->title ?? ''}} {{$user->first_name ?? '' }} {{ $user->last_name ?? '' }} Salary Structure
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
                    <form action="{{ route('setup-salary-structure') }}" data-parsley-validate="" method="post" autocomplete="off" id="addPropertyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-header text-uppercase mb-3">{{$user->title ?? ''}} {{$user->first_name ?? '' }} {{ $user->last_name ?? '' }} Salary Structure
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
                                <div class="col-sm-12 col-md-12 lg-12" id="appendList">
                                    <div id="items">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">

                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Definition</th>
                                                    <th style="text-align: right;">Amount({{ env('APP_CURRENCY') }})</th>
                                                    @if($user->salary_structure_category == 0)<th>Action</th> @endif
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @if($user->salary_structure_setup == 1)
                                                        @if($user->salary_structure_category == 0 )<!-- personalized -->
                                                            @foreach($personalized as $key => $allowance)
                                                                <tr>
                                                                    <th scope="row">{{ $key + 1 }}</th>
                                                                    <td>{{$allowance->getPaymentDefinition->payment_name ?? ''}}</td>
                                                                    <td style="text-align: right;">{{number_format($allowance->amount, 2)}}</td>
                                                                    <td>
                                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal_{{$allowance->id}}" class="btn btn-danger btn-sm"><i class="bx bx-x"></i></button>

                                                                    </td>
                                                                </tr>

                                                            @endforeach
                                                        <tr>
                                                            <td style="text-align: right;" colspan="2"><strong>Total:</strong></td>
                                                            <td style="text-align: right;"><strong>{{number_format($personalized->sum('amount'),2)}}</strong></td>
                                                            <td></td>
                                                        </tr>
                                                        @else
                                                            @foreach($salaryStructure->getAllowances as $key => $allow)
                                                                <tr>
                                                                    <th scope="row">{{ $key + 1 }}</th>
                                                                    <td>{{$allow->getPayment->payment_name ?? ''}}</td>
                                                                    <td style="text-align: right;">{{number_format($allow->amount, 2)}}</td>
                                                                </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td style="text-align: right;" colspan="2"><strong>Total:</strong></td>
                                                                <td style="text-align: right;"><strong>{{number_format($salaryStructure->getAllowances->sum('amount'),2)}}</strong></td>
                                                            </tr>
                                                        @endif

                                                    @else
                                                        <tr>
                                                            <td colspan="4" style="text-align: center;">There is no salary structure setup for <code>{{ $user->title ?? '' }} {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->other_names ?? '' }}</code></td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($user->salary_structure_setup == 1)
        @if($user->salary_structure_category == 0 )<!-- personalized -->
        @foreach($personalized as $key => $allowance)
                <div class="modal fade" id="deleteModal_{{$allowance->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" >
                                <h6 class="modal-title text-uppercase" id="myModalLabel2">Delete Payment Definition?</h6>
                                <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form autocomplete="off" autcomplete="off" action="" method="post" id="addBranch" data-parsley-validate="">
                                    @csrf

                                    <hr>
                                    <input type="hidden" name="roleId" value="">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn-primary btn">Save changes</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
        @endif
    @endif
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
