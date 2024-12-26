@extends('layouts.master-layout')

@section('title', 'Inventory Sales')

@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
@endsection

@section('main-content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-info text-uppercase">Inventory Discharge Form</h5>
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
                        <form action="{{ route('new-stock-purchase') }}" method="POST" id="salesForm">
                            @csrf
                            <div class="row mb-4 mt-4">
                                <div class="col-md-4 col-lg-4 col-xl-4 col-sm-4">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="input-group input-group-button">

                                            <input autocomplete="off" type="text" id="datepicker" class="form-control" name="trans_date" placeholder="Date">
                                            <input type="hidden" class="form-control" name="type" value="2" >
                                        </div>
                                        @error('trans_date') <small class="form-text text-danger">{{$message}}</small> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4 ">
                                    <div class="form-group" id="tenant-wrapper">
                                        <label for="">Discharged To</label>
                                        <select name="vendor" id="vendor" class="form-control select2" value="{{old('vendor')}}">
                                            <option disabled selected>-- Select user --</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->title ?? '' }} {{$user->first_name ?? '' }} {{$user->last_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Stock Available</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="salesItems">
                                    <tr class="sales-row">
                                        <td>
                                            <select name="item[]" class="form-control select2 item-select" required>
                                                <option value="" disabled selected>Select Item</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-price="{{ $product->price }}">
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control stock" name="stock[]" readonly></td>
                                        <td><input type="number" class="form-control quantity" name="quantity[]" min="1" required></td>
                                        <td><input type="text" class="form-control price" name="amount[]" readonly></td>
                                        <td><input type="text" class="form-control total" name="total[]" readonly></td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" id="addRow">Add Item</button>
                            </div>
                            <div class="mt-3 ">
                                <h5 class="text-info text-uppercase d-flex justify-content-end"> Total: {{env('APP_CURRENCY')}}<span id="grandTotal">0.00</span></h5>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{url()->previous()}}" class="btn btn-secondary"><i class="bx bx-left-arrow mr-2"></i> Go Back </a>
                                            <button type="submit" class="btn btn-primary">Submit <i class="bx bx-check-double mr-2"></i></button>
                                        </div>
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
        $(document).ready(function () {
            $( "#datepicker" ).datepicker({
                dateFormat: "dd-mm-yy"
            });
            // Add new row to the sales table
            $('#addRow').click(function () {
                let newRow = `
        <tr class="sales-row">
            <td>
                <select name="item[]" class="form-control item-select" required>
                    <option value="" disabled selected>Select Item</option>
                    @foreach($products as $product)
                <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-price="{{ $product->price }}">
                            {{ $product->product_name }}
                </option>
@endforeach
                </select>
            </td>
            <td><input type="text" class="form-control stock" name="stock[]" readonly></td>
            <td><input type="number" class="form-control quantity" name="quantity[]" min="1" required></td>
            <td><input type="text" class="form-control price" name="price[]" readonly></td>
            <td><input type="text" class="form-control total" name="total[]" readonly></td>
            <td>
                <button type="button" class="btn btn-danger remove-row">Remove</button>
            </td>
        </tr>`;
                $('#salesItems').append(newRow);
                updateAvailableOptions();
            });

            // Remove a row from the sales table
            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
                updateAvailableOptions();
                calculateGrandTotal();
            });

            // Update stock, price, and validate quantity when item is selected
            $(document).on('change', '.item-select', function () {
                let selectedItem = $(this).find('option:selected');
                let stock = selectedItem.data('stock');
                let price = selectedItem.data('price');

                let row = $(this).closest('tr');
                row.find('.stock').val(stock);
                row.find('.price').val(price);
                row.find('.quantity').val('');
                row.find('.total').val('');
                updateAvailableOptions();
            });

            // Validate quantity and calculate total
            $(document).on('input', '.quantity', function () {
                let quantity = parseInt($(this).val());
                let row = $(this).closest('tr');
                let stock = parseInt(row.find('.stock').val());
                let price = parseFloat(row.find('.price').val());

                if (quantity > stock) {
                    alert(`Quantity exceeds available stock! Maximum is ${stock}.`);
                    $(this).val(stock);
                    quantity = stock;
                }

                let total = quantity * price;
                row.find('.total').val(total.toFixed(2));
                calculateGrandTotal();
            });

            // Calculate grand total
            function calculateGrandTotal() {
                let grandTotal = 0;
                $('.total').each(function () {
                    let total = parseFloat($(this).val());
                    if (!isNaN(total)) {
                        grandTotal += total;
                    }
                });
                $('#grandTotal').text(grandTotal.toLocaleString());
            }

            // Update available options to prevent duplicate selections
            function updateAvailableOptions() {
                let selectedItems = [];
                $('.item-select').each(function () {
                    let selectedValue = $(this).val();
                    if (selectedValue) {
                        selectedItems.push(selectedValue);
                    }
                });

                $('.item-select').each(function () {
                    let currentValue = $(this).val();
                    $(this).find('option').each(function () {
                        if (selectedItems.includes($(this).val()) && $(this).val() != currentValue) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                });
            }
        });
    </script>
@endsection
