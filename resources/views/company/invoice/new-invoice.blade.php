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
                    <h5 class="modal-header mb-3">New Invoice</h5>
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
                                    <label for="">Property</label>
                                    <select name="property" id="property" class="form-control select2" value="{{old('property')}}">
                                        <option disabled selected>-- Select property --</option>
                                        @foreach($properties->where('status',0) as $property)
                                            <option value="{{$property->id}}" {{ $property->status == 2 ? 'disabled' : null }} >{{$property->property_name ?? '' }} - {{$property->property_code ?? '' }}  </option>
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
                            <div class="col-md-12">
                                <table class="table table-responsive invoice-table invoice-total">
                                    <tbody>
                                    <tr class="text-info">
                                        <td>
                                            <hr>
                                            <h5 class="text-primary">Total :</h5>
                                        </td>
                                        <td>
                                            <hr>
                                            <h5 class="text-primary"> <span>{{env('APP_CURRENCY')}}</span> <span class="total">0.00</span></h5>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody class="float-left pl-3">
                                    <tr>
                                        <th class="text-left"> <strong>Account Name:</strong> </th>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left"><strong>Sort Code:</strong> </th>
                                        <td>-</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left"><strong>Account Number:</strong> </th>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left"><strong>Bank:</strong> </th>
                                        <td>-</td>
                                    </tr>
                                    </tbody>
                                </table>
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
        $(document).ready(function() {
            //$('#tenant-wrapper').hide();
            //$('#applicant-wrapper').hide();
            //$('.property-wrapper').hide();
            $('.js-example-basic-single').select2();
            $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());
            $(document).on('change', '#invoice_type', function(e){
                e.preventDefault();
                //console.log($(this).val());
            });
            $(document).on('click', '.add-line', function(e){
                e.preventDefault();
                var new_selection = $('.item').first().clone();
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
            var sum = 0;
            $(".payment").each(function(){
                sum += +$(this).val().replace(/,/g, '');
                $(".total").text(sum.toLocaleString());
            });
        }
        //calculate totals
        function calculateTotals(){
            const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
            const total = subTotals.reduce((a, v)=> a + Number(v), 0);
            grand_total = total;
            $('.sub-total').text(grand_total.toLocaleString());
            $('#subTotal').val(total);
            $('#totalAmount').val(grand_total);
            $('.total').text(total.toLocaleString());
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
            setTotal();
        });
    </script>

@endsection
