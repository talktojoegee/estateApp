@extends('layouts.master-layout')
@section('title')
    New Receipt
@endsection
@section('current-page')
    New Receipt
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')
    New Receipt
@endsection

@section('main-content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="modal-header mb-3 text-white">New Receipt</h5>
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
                    <form action="{{ route('process-payment') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Invoice No <sup style="color: #ff0000;">*</sup> </label>
                                    <input type="text" id="invoiceNo" placeholder="Invoice No." name="invoiceNo" class="form-control">
                                    @error('invoiceNo')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 ">
                                <div class="form-group" id="tenant-wrapper">
                                    <label for="">Payment Method</label>
                                    <select name="paymentMethod"  class="form-control select2" >
                                        <option disabled selected>-- Select payment method --</option>
                                        <option value="1">Cash</option>
                                        <option value="2">Cheque</option>
                                        <option value="3">Bank transfer</option>
                                        <option value="4">Internet</option>
                                    </select>
                                    @error('paymentMethod')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 ">
                                <div class="form-group">
                                    <label>Payment Date</label>
                                    <div class="input-group input-group-button">
                                                <span class="input-group-addon btn btn-custom" id="basic-addon9">
                                                    <span class=""> Date</span>
                                                </span>
                                        <input type="date" class="form-control" name="paymentDate" value="{{ date('Y-m-d') }}" placeholder="Issue Date">
                                    </div>
                                    @error('paymentDate') <small class="form-text text-danger">{{$message}}</small> @enderror
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Amount <sup style="color: #ff0000;">*</sup></label>
                                    <input type="number" step="0.01" name="amount" placeholder="Enter amount" class="form-control">
                                    @error('amount') <i class="text-danger">{{ $message }}</i> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="invoiceItems">

                                </div>
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
        let taxRate = 2;
        let total = 0;
        let subTotal = 0;
        $(document).ready(function() {
            $('#invoiceNo').on('blur', function(e){
                e.preventDefault();
                let invoiceNo =  $(this).val();
                axios.post("{{route('get-invoice')}}",{invoiceNo: invoiceNo})
                    .then(res=>{
                        $('#invoiceItems').html(res.data);
                    });
                //calculateTotals();
            });


        });
    </script>

@endsection
