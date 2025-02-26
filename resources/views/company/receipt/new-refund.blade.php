@extends('layouts.master-layout')
@section('title')
    New Refund Request
@endsection
@section('current-page')
    New Refund Request
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')
    New Refund Request
@endsection

@section('main-content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="modal-header mb-3 text-white text-uppercase">New Refund Request</h6>
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
                    @if($errors->any())
                        <div class="row" role="alert">
                            <div class="col-md-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-close me-2"></i>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('show-new-refund-form') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <div class="form-group">
                                    <label for="">Receipt No <sup style="color: #ff0000;">*</sup> </label>
                                    <input type="text" id="receiptNo" placeholder="Receipt No." name="receiptNo" class="form-control">
                                    @error('receiptNo')
                                    <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 ">
                                <div class="form-group">
                                    <label> Date Requested</label>
                                    <div class="input-group input-group-button">
                                                <span class="input-group-addon btn btn-custom" id="basic-addon9">
                                                    <span class=""> Date</span>
                                                </span>
                                        <input type="date" class="form-control" name="dateRequested" value="{{ date('Y-m-d') }}" placeholder="Issue Date">
                                    </div>
                                    @error('dateRequested') <small class="form-text text-danger">{{$message}}</small> @enderror
                                    <br>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" id="receiptItems">

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
            $('#receiptNo').on('blur', function(e){
                e.preventDefault();
                let receiptNo =  $(this).val();
                axios.post("{{route('get-receipt')}}",{receiptNo: receiptNo})
                    .then(res=>{
                        $('#receiptItems').html(res.data);
                    });
            });


        });
    </script>

@endsection
