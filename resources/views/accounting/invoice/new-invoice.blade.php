@extends('layouts.master-layout')
@section('title')
    Generate New Invoice
@endsection

@section('current-page')
    Generate New Invoice
@endsection
@section('current-page-brief')

@endsection

@section('extra-styles')
    <link rel="stylesheet" href="/assets/css/select2.css">
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group float-right mb-3">
                <a href="{{route('manage-invoices')}}" class="btn btn-secondary btn-mini"><i class="icofont icofont-tags"></i>Manage Invoices</a>
                <a href="{{route('generate-new-invoice')}}" class="btn btn-primary btn-mini"><i class="icofont icofont-tasks"></i>Generate New Invoice</a>
                <button class="btn btn-danger btn-mini"><i class="icofont icofont-megaphone"></i>Reports</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Generate New Invoice</h5>
                    <p>Generate new invoice</p>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-block">
                                @if(session()->has('success'))
                                    <div class="alert alert-success background-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="icofont icofont-close-line-circled text-white"></i>
                                        </button>
                                        {!! session()->get('success') !!}
                                    </div>
                                @endif
                                    @if(session()->has('error'))
                                    <div class="alert alert-warning background-warning">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="icofont icofont-close-line-circled text-white"></i>
                                        </button>
                                        {!! session()->get('error') !!}
                                    </div>
                                @endif
                                <form action="{{route('generate-new-invoice')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
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
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group" id="tenant-wrapper">
                                                <label for="">Tenant</label>
                                                <select name="tenant" id="tenant" class="form-control js-example-basic-single" value="{{old('tenant')}}">
                                                    <option disabled selected>-- Select tenant --</option>
                                                    @foreach($tenants as $tenant)
                                                        <option value="{{$tenant->id}}">{{$tenant->getApplicant->first_name ?? ''}} {{$tenant->getApplicant->surname ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                                @error('tenant')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                            <div class="form-group" id="applicant-wrapper">
                                                <label for="">Applicant <small>(Potential Tenant)</small></label>
                                                <select name="applicant" id="applicant" class="form-control js-example-basic-single" value="{{old('applicant')}}">
                                                    <option disabled selected>-- Select applicant --</option>
                                                    @foreach($potential_tenants as $applicant)
                                                        <option value="{{$applicant->id}}">{{$applicant->first_name ?? ''}} {{$applicant->surname ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                                @error('applicant')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-lg-6 property-wrapper">
                                            <div class="form-group">
                                                <label for="">Property</label>
                                                <select name="property" id="property" class="form-control js-example-basic-single" value="{{old('property')}}">
                                                    <option disabled selected>-- Select property --</option>
                                                    @foreach($properties as $property)
                                                        <option value="{{$property->id}}">{{$property->property_name ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                                @error('property')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Invoice Period</label>
                                                <div class="input-group input-group-button">
                                                <span class="input-group-addon btn btn-primary" id="basic-addon9">
                                                    <span class="">Issue Date</span>
                                                </span>
                                                    <input type="date" class="form-control" name="issue_date" placeholder="Issue Date">
                                                    <span class="input-group-addon btn btn-primary" id="basic-addon9">
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
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table  invoice-detail-table">
                                                        <thead>
                                                        <tr class="thead-default">
                                                            <th>Service/Product</th>
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
                                                                    <select name="service[]" value="{{old('service[]')}}" class="js-example-basic-single select-product">
                                                                        <option selected disabled>Select service</option>
                                                                        @foreach($services as $service)
                                                                            <option value="{{$service->id}}" data-charge-type="{{$service->charge_type ?? 0}}" data-charge-value="{{$service->charge_value}}">{{$service->service_name ?? ''}} ({{$service->charge_type == 1 ? number_format($service->charge_value,2) : $service->charge_value.'%'}})</option>
                                                                        @endforeach
                                                                    </select>
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
                                                                <i class="ti-trash text-danger remove-line" style="cursor: pointer;"></i>
                                                            </td>

                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                <button class="btn btn-mini btn-warning add-line"> <i class="ti-plus mr-2"></i> Add Line</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <table class="table table-responsive invoice-table invoice-total">
                                                <tbody>
                                                <tr>
                                                    <th>Sub Total :</th>
                                                    <td> <span  class="sub-total">0.00</span> </td>
                                                </tr>
                                                <tr>
                                                    <th>Taxes (%) :</th>
                                                    <td>
                                                        <input type="text" placeholder="Tax Rate" step="0.01" class="form-control" id="tax_rate" name="tax_rate">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tax amount</th>
                                                    <td>
                                                        <input type="text" readonly placeholder="Tax Amount" class="form-control" id="tax_amount" name="tax_amount">
                                                    </td>
                                                </tr>
                                                <tr class="text-info">
                                                    <td>
                                                        <hr>
                                                        <h5 class="text-primary">Total :</h5>
                                                    </td>
                                                    <td>
                                                        <hr>
                                                        <h5 class="text-primary"> <span></span> <span class="total">0.00</span></h5>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tbody class="float-left pl-3">
                                                <tr>
                                                    <th class="text-left"> <strong>Account Name:</strong> </th>
                                                    <td>{{Auth::user()->getUserCompany->account_name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left"><strong>Sort Code:</strong> </th>
                                                    <td>{{Auth::user()->getUserCompany->sort_code ?? '-' }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-left"><strong>Account Number:</strong> </th>
                                                    <td>{{Auth::user()->getUserCompany->account_no ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left"><strong>Bank:</strong> </th>
                                                    <td>{{Auth::user()->getUserCompany->bank ?? '-' }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                                            <div class="btn-group">
                                                <a href="{{url()->previous()}}" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i>Cancel</a>
                                                <button type="submit" class="btn btn-primary btn-mini"><i class="ti-check mr-2"></i>Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/js/select2.js"></script>
    <script src="/assets/js/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tenant-wrapper').hide();
            $('#applicant-wrapper').hide();
            $('.property-wrapper').hide();
            $('.js-example-basic-single').select2();
            $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());
            $(document).on('change', '#invoice_type', function(e){
                e.preventDefault();
                console.log($(this).val());
                switch($(this).val()){
                    case '1':
                        $('#tenant-wrapper').hide();
                        $('#applicant-wrapper').show();
                        $('.property-wrapper').hide();
                        $('.js-example-basic-single').select2();
                        break;
                    case '2':
                        $('#tenant-wrapper').show();
                        $('#applicant-wrapper').hide();
                        $('.property-wrapper').hide();
                        $('.js-example-basic-single').select2();
                        break;
                    case '3':
                        $('#tenant-wrapper').show();
                        $('#applicant-wrapper').hide();
                        $('.property-wrapper').show();
                        $('.js-example-basic-single').select2();
                        break;
                    case '4':
                        $('#tenant-wrapper').show();
                        $('#applicant-wrapper').hide();
                        $('.property-wrapper').hide();
                        $('.js-example-basic-single').select2();
                        break;
                }
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
