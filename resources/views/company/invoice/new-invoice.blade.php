@extends('layouts.master-layout')
@section('title')
    New Invoice
@endsection
@section('current-page')
    New Invoice
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')
    New Invoice
@endsection

@section('main-content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="modal-header mb-3 text-white">New Invoice</h5>
                    @if(session()->has('success'))
                        <div class="alert alert-success alert" role="alert">
                            {!! session()->get('success') !!}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-warning" role="alert">
                            {!! session()->get('error') !!}
                        </div>
                    @endif
                    <form action="{{ route('new-invoice') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Invoice Type</label>
                                    <select name="invoice_type" id="invoice_type" class="form-control js-example-basic-single" value="{{old('invoice_type')}}">
                                        <option disabled selected>-- Select invoice type --</option>
                                        <option value="1">New Lease</option>
                                        <option value="2">Lease Renewal</option>
                                        <option value="3">Sale of Property</option>
                                        <option value="4">Others</option>
                                    </select>
                                    @error('invoice_type')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 ">
                                <div class="form-group" id="tenant-wrapper">
                                    <label for="">Customer</label>
                                    <select name="customer" id="tenant" class="form-control select2" value="{{old('customer')}}">
                                        <option disabled selected>-- Select customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->first_name ?? '' }} {{$customer->last_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 property-wrapper">
                                <div class="form-group">
                                    <label for="">Property</label> <span id="propertyPrice"></span>
                                    <select name="property" id="property" class="form-control select2" value="{{old('property')}}">
                                        <option disabled selected>-- Select property --</option>
                                        @foreach($properties->where('status',0) as $property)
                                            <option data-price="{{$property->price ?? 'Not set'}}" value="{{$property->id}}" {{ $property->status == 2 ? 'disabled' : null }} >{{$property->property_code ?? '' }} - {{$property->property_name ?? '' }}  </option>
                                        @endforeach
                                    </select>
                                    @error('property')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-6 mt-4">
                                <div class="form-group">
                                    <label>Invoice Period</label>
                                    <div class="input-group input-group-button">
                                                <span class="input-group-addon btn btn-custom" id="basic-addon9">
                                                    <span class="">Issue Date</span>
                                                </span>
                                        <input type="date" class="form-control" name="issue_date" placeholder="Issue Date">
                                        <span class="input-group-addon btn btn-custom" id="basic-addon9">
                                                    <span class="">Due Date</span>
                                                </span>
                                        <input type="date" class="form-control" name="due_date" placeholder="Due Date">
                                    </div>
                                    @error('due_date') <small class="form-text text-danger">{{$message}}</small> @enderror
                                    <br>
                                    @error('issue_date') <small class="form-text text-danger">{{$message}}</small> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table  invoice-detail-table">
                                            <thead>
                                            <tr class="thead-default">
                                                <th>Description</th>
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
                                                                    <textarea name="service[]" style="resize: none;"
                                                                              class="form-control" placeholder="Service Description here..."></textarea>
                                                        @error('service')
                                                        <i class="text-danger mt-2">{{$message}}</i>
                                                        @enderror
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
                                    <table class="table mb-0 table-striped">
                                        <tbody>
                                        <tr>
                                            <th scope="row">Estate: &nbsp; &nbsp; <span class="text-info" id="propertyEstate"></span></th>
                                        </tr>
                                        <tr>
                                            <th scope="row">Property Price: &nbsp; &nbsp; <span class="text-info" id="propertyAmount"></span></th>
                                        </tr>
                                        <tr>
                                            <th scope="row">Payment Plan: &nbsp; &nbsp; <span class="text-info"  id="propertyPaymentPlan"></span></th>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group col-12">
                                            <label for="">Discount Type</label>
                                            <select name="discountType" id="discountType" class="form-control col-3">
                                                <option selected>None</option>
                                                <option value="1">Flat</option>
                                                <option value="2">Rate</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group col-12" id="flatWrapper">
                                            <label for="">Discount Amount</label>
                                            <input type="number" step="0.01" id="discountAmount" name="discountAmount" placeholder="Discount Amount" class="form-control">
                                        </div>
                                        <div class="form-group col-12" id="rateWrapper">
                                            <label for="">Discount Rate</label>
                                            <input type="number" step="0.01" id="discountRate" name="discountRate" placeholder="Discount Rate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <p><strong>Sub-total:</strong> <span>{{env('APP_CURRENCY')}}</span> <span class="subTotal">0.00</span></p>
                                <p><strong>TAX/VAT (<span id="taxRate"></span>%) :</strong> <span>{{env('APP_CURRENCY')}}</span> <span class="tax">0.00</span></p>
                                <p><strong>Discount :</strong> <span>{{env('APP_CURRENCY')}}</span> <span class="discount">0.00</span></p>
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
@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/assets/js/axios.min.js"></script>
    <script>
        let taxRate = 0;
        let total = 0;
        let subTotal = 0;
        let discount = 0;
        $(document).ready(function() {
            $('#rateWrapper').hide();
            $('#flatWrapper').hide();
            $('.js-example-basic-single').select2();
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
