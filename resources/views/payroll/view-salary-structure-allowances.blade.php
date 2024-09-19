@extends('layouts.master-layout')
@section('title')
    Salary Structure Allowance
@endsection
@section('current-page')
    Salary Structure Allowance
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
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
                                <div class="modal-header text-uppercase mb-3">Salary Structure Allowance
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6 lg-6">
                                    <div class="form-group">
                                        <label for="">Salary Structure Category <sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{ $structure->ss_name ?? null }}" class="form-control" readonly placeholder="Salary Structure Category">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 lg-12" id="appendList">
                                    <div id="items">
                                        <div class="table-responsive" bis_skin_checked="1">
                                            <table class="table table-striped mb-0">

                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Definition</th>
                                                    <th style="text-align: right;">Amount({{ env('APP_CURRENCY') }})</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($structure->getAllowances) > 0)
                                                    @foreach($structure->getAllowances as $key => $allowance)
                                                    <tr>
                                                        <th scope="row">{{ $key + 1 }}</th>
                                                        <td>{{$allowance->getPayment->payment_name ?? ''}}</td>
                                                        <td style="text-align: right;">{{number_format($allowance->amount, 2)}}</td>
                                                        <td>
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal_{{$allowance->id}}" class="btn btn-warning btn-sm"><i class="bx bx-pencil"></i></button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;"></td>
                                                        <td colspan="1" style="text-align: right;"><strong>Total:</strong> &nbsp;<span class="text-info">{{ number_format($structure->getAllowances->sum('amount'),2) }}</span></td>
                                                        <td></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="4">
                                                        <p class="text-center">Whoops! No salary structure defined for <strong><code>{{ $structure->ss_name ?? '' }}</code></strong></p>
                                                        </td>
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

    @if(count($structure->getAllowances) > 0)
        @foreach($structure->getAllowances as $key => $allow)
            <div class="modal" id="deleteModal_{{$allow->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" >
                            <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Edit Allowance</h6>
                            <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form autocomplete="off" action="{{route('salary-allowances')}}" method="post"  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="form-group mt-3 col-md-12">
                                        <input type="hidden" value="{{$allow->id}}" name="allowance">
                                        <input type="hidden" value="{{$structure->id}}" name="category">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Payment Definition <sup class="text-danger">*</sup></label>
                                        <select name="paymentDefinition"  class="form-control">
                                            @foreach($definitions as $definition)
                                                <option value="{{ $definition->id }}" {{ $definition->id == $allow->payment_definition_id ? 'selected' : null  }}>{{ $definition->payment_name ?? '' }} ({{$definition->pay_code ?? ''}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">Amount <sup class="text-danger">*</sup></label>
                                        <input type="number" name="amount" step="0.01" placeholder="Amount" value="{{ $allow->amount }}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-center mt-3">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-primary  waves-effect waves-light">Save changes <i class="bx bx-check-double"></i> </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

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
