@extends('layouts.master-layout')
@section('current-page')
    Top up
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @if(session()->has('success'))
    <div class="row" role="alert">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>

                           {!! session()->get('success') !!}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-6 ">
            <div class="card bg-primary text-white text-center p-3">
                <div class="card-body">
                    <blockquote class="blockquote font-size-14 mb-0">
                        <h2 class="text-white"><sup>₦</sup> {{ number_format($balance, 2)  }}</h2>
                        <p>Current Balance</p>
                        <footer class="blockquote-footer mt-0 font-size-12 text-white">
                            <a href="{{route('top-up-transactions')}}" class="text-white">View Transactions</a>
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="modal-header mb-4">
                    <div class="modal-title text-uppercase">Top-up</div>
                </div>
                <div class="card-body">
                    <form action="{{route('top-up')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Amount <small>(Naira)</small></label>
                                    <input type="number" placeholder="Enter Amount (Naira)" name="amount" id="amount" value="{{old('amount')}}" required class="form-control">
                                    <br> @error('amount')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <input type="hidden" disabled placeholder="Full Name" name="fullName" id="fullName" value="{{ Auth::user()->first_name ?? '' }} {{Auth::user()->surname ?? '' }}" class="form-control">
                                    <br> @error('fullName')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <input type="hidden" disabled placeholder="Email" name="email" id="email" value="{{ Auth::user()->email ?? '' }} " class="form-control">
                                    <br> @error('email')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <hr style="margin: 0; padding: 0">
                                <div class="form-group d-flex justify-content-center mb-3 mt-2">
                                    <button type="button"  class="btn btn-primary btn-lg waves-effect waves-light" id="makePaymentBtn"> Make Payment <i class="bx bx-right-arrow ml-2"></i></button>
                                </div>
                                <img src="/assets/images/secured-by-paystack.png" alt="Secured Payment" class="opacity-30" width="100%">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="/assets/js/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        //document.addEventListener('contextmenu', event => event.preventDefault());
        $(document).ready(function(){
            $('#makePaymentBtn').on('click', function(e){
                e.preventDefault();
                let amount = $('#amount').val();
                if(amount <= 0 || amount == null  || amount == undefined){
                    alert("Enter an amount");
                }else{
                    payWithPaystack(parseInt(amount));
                }
            });
        });
        function payWithPaystack(amount){
             let charge = amount < 2500 ? (amount * (1.7/100)) : (amount * (1.7/100)) + 100;
             let total = charge + amount;
             let handler = PaystackPop.setup({
                key: "{{env('PAYSTACK_PUBLIC_KEY')}}",
                email: "{{\Illuminate\Support\Facades\Auth::user()->email ?? 'info@efabproperty.com'}}",
                amount: total * 100,
                currency: "NGN",
                ref: ''+Math.floor((Math.random() * 1000000000) + 1),
                metadata: {
                    custom_fields: [
                        {
                            display_name: "",
                            variable_name: "",
                            value: ""
                        }
                    ]
                },
                callback: function(response){
                    axios.post("{{route('top-up')}}",
                        {
                            amount:amount,
                            charge:charge,
                            trans:response.trans
                        }
                    ).then(res=>{
                        Toastify({
                            text: res.data.message,
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            },
                            onClick: function(){}
                        }).showToast();
                        location.reload();
                    }).catch(error=>{
                        Toastify({
                            text: 'Whoops! Something went wrong. Try again later.',
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff0000)",
                            },
                            onClick: function(){}
                        }).showToast();
                    });
                },
                onClose: function(){
                    alert('Are you sure you want to terminate this transaction?');
                }
            });
            handler.openIframe();
        }
    </script>
@endsection
