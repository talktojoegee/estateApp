@extends('layouts.master-layout')
@section('current-page')
    Journal Voucher
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
                        <div class="h6 text-left pl-5 text-uppercase text-primary"> Journal Voucher Entry</div>
                        <a href="{{route('accounting.chart-of-accounts')}}" class="btn btn-primary mr-3" > <i class="bx bxs-paper-plane"></i>  Journal Voucher Posting</a>
                    </div>

                    <div class="container pb-5 mt-5">
                        <form action="#" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <input type="date" placeholder="Date" value="{{date('Y-m-d')}}" class="form-control" name="issue_date">
                                                @error('issue_date')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="">Entry #</label>
                                                <input type="text"  name="entry_no" value="JV{{ strtoupper(substr(sha1(time()),29,40)) }}" readonly placeholder="Entry #" class="form-control">
                                                @error('entry_no')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="card p-3">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table  invoice-detail-table">
                                                    <thead>
                                                    <tr class="thead-default">
                                                        <th>Account</th>
                                                        <th>Debit Amount</th>
                                                        <th>Debit Narration</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="debitWing">
                                                    <tr class="debit-item">
                                                        <td>
                                                            <div class="form-group">
                                                                <select name="debit_account[]" class="debit_account  select-debit-account form-control">
                                                                    <option disabled selected>Select debit account</option>
                                                                    @foreach($accounts as $account)
                                                                        <option value="{{$account->glcode}}">{{$account->glcode ?? ''}} - {{$account->account_name ?? ''}} </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('debit_account')
                                                                <i class="text-danger mt-2">{{$message}}</i>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" step="0.01" placeholder="Debit Amount" class="form-control debit-amount" value="0"  name="debit_amount[]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <textarea style="resize: none;" type="text" name="debit_narration[]" class="form-control" placeholder="Debit Narration"></textarea>
                                                        </td>
                                                        <td>
                                                            <i class="bx bx bxs-trash badge-soft-danger remove-debit-line" style="cursor: pointer;"></i>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <button class="btn btn-sm btn-primary add-debit-line"> <i class="bx bx-plus-circle mr-2"></i> Add Line</button>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12 col-sm-12 col-lg-12 d-flex justify-content-end">
                                                    <h5 class="text-uppercase">Total: <span id="drTotal">0</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="card p-3">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table  invoice-detail-table">
                                                    <thead>
                                                    <tr class="thead-default">
                                                        <th>Account</th>
                                                        <th>Credit Amount</th>
                                                        <th>Credit Narration</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="creditWing">
                                                    <tr class="credit-item">
                                                        <td>
                                                            <div class="form-group">
                                                                <select name="credit_account[]" class="  credit_account  select-credit-account form-control">
                                                                    <option disabled selected>Select credit account</option>
                                                                    @foreach($accounts as $account)
                                                                        <option value="{{$account->glcode}}">{{$account->glcode ?? ''}} - {{$account->account_name ?? ''}} </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('credit_account')
                                                                <i class="text-danger mt-2">{{$message}}</i>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="number" step="0.01" placeholder="Credit Amount" class="form-control credit-amount" value="0"  name="credit_amount[]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <textarea style="resize: none;" type="text" name="credit_narration[]" class="form-control" placeholder="Credit Narration"></textarea>
                                                        </td>
                                                        <td>
                                                            <i class="bx bx bxs-trash badge-soft-danger remove-credit-line" style="cursor: pointer;"></i>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                    <button class="btn btn-sm btn-primary add-credit-line"> <i class="bx bx-plus-circle mr-2"></i> Add Line</button>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12 col-sm-12 col-lg-12 d-flex justify-content-end">
                                                    <h5 class="text-uppercase">Total: <span id="crTotal">0</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="row mb-3">
                                <div class="col-md-12 col-sm-12 col-lg-12">
                                    <table class="table  invoice-detail-table">
                                        <tr>
                                            <td colspan="3" class="text-right"><strong style="font-size:14px; text-transform:uppercase; text-align: right;">Total:</strong></td>
                                            <td class="text-right drTotal">0.00

                                            </td>
                                            <td class="text-center crTotal"> 00

                                            </td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>--}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="btn-group d-flex justify-content-center">
                                        <input type="hidden" id="drTotalHidden">
                                        <input type="hidden" id="crTotalHidden">
                                        <a href="" class="btn btn-mini btn-danger"><i class="ti-close mr-2"></i>Cancel</a>
                                        <button type="submit" class="btn btn-primary save-entry btn-mini"><i class="ti-check mr-2"> Save</i></button>
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
        let debitRows = 1;
        let creditRows = 1;
        $(document).ready(function(){

            $(document).on('click', '.add-debit-line', function(e){
                e.preventDefault();
                var new_selection = $('.debit-item').first().clone();
                $('#debitWing').append(new_selection);
                sumDebit();
               /* $(".select-account").select2({
                    placeholder: "Select account"
                });
                $(".select-account").select2({ width: '100%' });
                $(".select-account").last().next().next().remove();*/
            });

            //Remove debit line
            $(document).on('click', '.remove-debit-line', function(e){
                e.preventDefault();
                sumDebit();
            });

            $(document).on('click', '.add-credit-line', function(e){
                e.preventDefault();
                var new_selection = $('.credit-item').first().clone();
                $('#creditWing').append(new_selection);
                sumCredit();
                /* $(".select-account").select2({
                     placeholder: "Select account"
                 });
                 $(".select-account").select2({ width: '100%' });
                 $(".select-account").last().next().next().remove();*/
            });

            //Remove credit line
            $(document).on('click', '.remove-credit-line', function(e){
                e.preventDefault();
                $(this).closest('tr').remove();
                sumCredit();
            });
            $('.debit-amount').on('blur',function(){
                sumDebit();
            });
            $('.credit-amount').on('blur',function(){
                sumCredit();
            });
        });

        function sumDebit(){
            let sum = 0;
            $(".debit-amount").each(function(){
                sum += +$(this).val();
            });
            $("#drTotal").text(sum.toLocaleString());
            $('#drTotalHidden').val(sum);
            if($('#drTotalHidden').val() != $('#crTotalHidden').val()){
                $('.save-entry').attr('disabled', true);
            }else{
                $('.save-entry').attr('disabled', false);
            }
        }
        function sumCredit(){
            let sum = 0;
            $(".credit-amount").each(function(){
                sum += +$(this).val();
            });
            $('#crTotalHidden').val(sum);
            $("#crTotal").text(sum.toLocaleString());
            if($('#drTotalHidden').val() != $('#crTotalHidden').val()){
                $('.save-entry').attr('disabled', true);
            }else{
                $('.save-entry').attr('disabled', false);
            }
        }
    </script>
@endsection
