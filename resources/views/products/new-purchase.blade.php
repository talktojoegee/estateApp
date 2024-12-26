@extends('layouts.master-layout')
@section('current-page')
    New Purchase
@endsection
@section('title')
    New Purchase
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="d-flex justify-content-end">
                    <a href="javascript:void(0);"  class="btn btn-primary  mb-3">Manage Purchases <i class="bx bx-list-ul"></i> </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">New Purchase</h4>
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
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

                        <form action="{{ route('new-stock-purchase') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="input-group input-group-button">
                                                <span class="input-group-addon btn btn-custom" id="basic-addon9">
                                                    <span class=""> Date</span>
                                                </span>
                                            <input autocomplete="off" type="text" id="datepicker" class="form-control" name="trans_date" placeholder="Date">
                                            <input type="hidden" class="form-control" name="type" value="1" >
                                        </div>
                                        @error('trans_date') <small class="form-text text-danger">{{$message}}</small> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4 ">
                                    <div class="form-group" id="tenant-wrapper">
                                        <label for="">Vendor</label>
                                        <select name="vendor" id="vendor" class="form-control select2" value="{{old('vendor')}}">
                                            <option disabled selected>-- Select vendor --</option>
                                            @foreach($vendors as $customer)
                                                <option value="{{$customer->id}}">{{$customer->first_name ?? '' }} {{$customer->last_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-4" >
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                        <h6 class="text-info mt-3 mb-3 text-uppercase">Purchase Items</h6>
                                        <div class="table-responsive">
                                            <table class="table  invoice-detail-table">
                                                <thead>
                                                <tr class="thead-default">
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                    <th>Amount</th>
                                                    <th>Total</th>
                                                    <th class="text-danger">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="products">
                                                <tr class="item">
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="item[]" class="form-control" >
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}"> {{ $product->product_name ?? null  }}</option>

                                                                @endforeach
                                                                @error('item')
                                                                <i class="text-danger mt-2">{{$message}}</i>
                                                                @enderror
                                                            </select>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" placeholder="Quantity" name="quantity[]" class="form-control">
                                                        @error('quantity')
                                                        <i class="text-danger mt-2">{{$message}}</i>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" placeholder="Amount" step="0.01" class="form-control" name="amount[]">
                                                        @error('amount')
                                                        <i class="text-danger mt-2">{{$message}}</i>
                                                        @enderror
                                                    </td>
                                                    <td><input type="text" class="form-control aggregate" name="total[]" readonly style="width: 120px;"></td>
                                                    <td>
                                                        <i class="bx bx-trash text-danger remove-line" style="cursor: pointer; color: #ff0000 !important;"></i>
                                                    </td>

                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <button class="btn  btn-primary btn-sm add-line"> <i class="bx bx-plus mr-2"></i> Add More</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="table-responsive">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-6">
                                        </div>
                                    </div>
                                    <p><strong>Total:</strong> <span>{{env('APP_CURRENCY')}}</span> <span class="total">0.00</span></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                                    <div class="btn-group">
                                        <a href="{{url()->previous()}}" class="btn btn-secondary"><i class="bx bx-left-arrow mr-2"></i> Go Back </a>
                                        <button type="submit" class="btn btn-primary">Submit <i class="bx bx-check-double mr-2"></i></button>
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
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        let taxRate = 0;
        let total = 0;
        let subTotal = 0;
        let discount = 0;
        $(document).ready(function() {
            $('#rateWrapper').hide();
            $('#flatWrapper').hide();
            $('.js-example-basic-single').select2();
            $( "#datepicker" ).datepicker({
                dateFormat: "dd-mm-yy"
            });
            $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());

            $(document).on('change', '#invoice_type', function(e){
                e.preventDefault();
            });
            $(document).on('change', '#discountType', function(e){
                e.preventDefault();
                let select = $(this).val();
                if(parseInt(select) === 1){
                    $('#rateWrapper').hide();
                    $('#flatWrapper').show();
                }else if(parseInt(select) === 2){
                    $('#rateWrapper').show();
                    $('#flatWrapper').hide();
                }else{
                    $('#rateWrapper').hide();
                    $('#flatWrapper').hide();
                }
            });
            $('#discountAmount').on('blur', function(e){
                e.preventDefault();
                discount = $(this).val();
                calculateTotals();

            });
            $('#discountRate').on('blur', function(e){
                e.preventDefault();
                const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
                const total = subTotals.reduce((a, v)=> a + Number(v), 0);
                let tax = (taxRate/100).toFixed(2) * total;
                let discountRate  = $(this).val() || 0;
                let gross = tax + total;
                let discountAmount = (discountRate/100).toFixed(2) * gross;
                discount = discountAmount;
                calculateTotals();

            });
            //estate-info
            $('#property').on('change', function(e){
                e.preventDefault();
                let price =  $(this).find(':selected').data('price')
                $('#propertyPrice').html(`<strong style='color:#ff0000;'><small>(Price: ${price.toLocaleString()})</small></strong>`)
                axios.post("{{route('estate-info')}}",{id:$(this).val()})
                    .then(res=>{
                        taxRate = res.data.estate.tax_rate;
                        $('#propertyEstate').text(res.data.estate.e_name);
                        $('#propertyAmount').html(price.toLocaleString());
                        $('#propertyPaymentPlan').text(`${res.data.paymentPlanName} - ${res.data.paymentPlanDesc}`);
                        $('#taxRate').text(taxRate);
                    });
                calculateTotals();
            });

            $(document).on('click', '.add-line', function(e){
                e.preventDefault();
                let new_selection = $('.item').first().clone();
                $('#products').append(new_selection);

                $(".select-product").select2({
                    placeholder: "Select service"
                });
                $(".select-product").last().next().next().remove();
            });
            //Remove line
            $(document).on('click', '.remove-line', function(e){
                e.preventDefault();
                $(this).closest('tr').remove();
                calculateTotals();
            });

        });

        function setTotal(){
            let sum = 0;
            $(".payment").each(function(){
                sum += +$(this).val().replace(/,/g, '');
                $(".total").text(sum.toLocaleString());
            });
        }
        function setTax(){
            let sum = 0;
            $(".payment").each(function(){
                sum += +$(this).val().replace(/,/g, '');
                //$(".tax").text(123);
            });
        }
        //calculate totals
        function calculateTotals(){
            const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
            const total = subTotals.reduce((a, v)=> a + Number(v), 0);
            grand_total = total;
            let tax = (taxRate/100).toFixed(2) * total;

            $('.sub-total').text(grand_total.toLocaleString());
            $('#subTotal').val(total);
            $('#totalAmount').val(grand_total);
            $('.subTotal').text((total).toLocaleString());
            $('.total').text(( (total+tax) - discount).toLocaleString());
            $('.tax').text(tax.toLocaleString());
            $('.discount').text(parseFloat(discount).toLocaleString());
            $('.balance').text(total.toLocaleString());
        }

        //calculate subtotals
        function calculateSubTotal(row){
            const $row = $(row);
            const inputs = $row.find('input');
            const subtotal = inputs[0].value * inputs[1].value;
            $row.find('td:nth-last-child(2) input[type=text]').val(subtotal);
            return subtotal;
        }

        $('.aggregate').on('change', function(e){
            e.preventDefault();
            //setTotal();
            //setTax();
        });
    </script>
@endsection
