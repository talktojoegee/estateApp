@extends('layouts.master-layout')
@section('title')
    Our Pricing
@endsection

@section('current-page')

@endsection
@section('current-page-brief')

@endsection

@section('event-area')
    <div class="btn-group">
        <a class="btn btn-primary btn-mini" href="{{url()->previous()}}"><i class="ti-back-left"></i>Go Back</a>
    </div>
@endsection
@section('extra-styles')

@endsection
@section('main-content')
    <div class="pricing-section no-color text-center mb-3" id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="pricing-intro">
                        <h5 class="wow fadeInUp" data-wow-delay="0s" style="visibility: visible; animation-delay: 0s; animation-name: fadeInUp;">Pricing Table</h5>
                        <p class="wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                            Explore our pricing and choose the one that best suites you.
                        </p>
                        @if($errors->any())
                            {!! implode('', $errors->all("<div class='alert alert-warning' role='alert'>:message</div>")) !!}
                        @endif
                        @if(session()->has('error'))
                            <div class='alert alert-warning' role='alert'>{!! session()->get('error') !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
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
            <div class="row">
               <div class="col-sm-4">
                   <form action="{{route('subscription')}}" method="post" autocomplete="off">
                       @csrf
                       <div class="card">
                           <div class="card-block">
                               <div class="">
                                   <div class="icon d-flex justify-content-center">
                                       <img src="/assets/logos/feature_icon_3.png" alt="Icon">
                                   </div>
                                   <div class="pricing-details " style="margin: 0px auto;">
                                       <h4 style="text-align: center;">Essential</h4>
                                       <span class="d-flex justify-content-center" style="font-weight: 700; font-size: 18px;">₦3,000/month</span>
                                       <span class="d-flex justify-content-center text-warning">Extra units ₦1,300</span>
                                       <p style="text-align: center;">
                                           You have a sizable number of properties in your portfolio.
                                           All you need is a platform that will help you manage all these in one space
                                       </p>
                                       <div class="input-group input-group-button">
                                        <span class="input-group-addon btn btn-secondary" id="basic-addon11">
                                            <span class="">I manage</span>
                                        </span>
                                           <input type="number" value="1" id="essential_units" name="no_of_units" min="1" class="form-control" placeholder="No. of units">
                                           <span class="input-group-addon btn btn-primary" id="basic-addon12">
                                            <span class="">units</span>
                                        </span>
                                       </div>
                                       <div class="d-flex justify-content-center">
                                           <div class="radio radiofill radio-primary radio-inline">
                                               <label>
                                                   <input type="radio" value="3" name="plan_duration">
                                                   <i class="helper"></i>Biannually
                                               </label>
                                           </div>
                                           <div class="radio radiofill radio-primary radio-inline">
                                               <label>
                                                   <input type="radio" value="4" name="plan_duration" checked="checked">
                                                   <i class="helper"></i>Annually
                                               </label>
                                           </div>
                                           <input type="hidden" id="essential_amount" name="amount" value="300000">
                                           <input type="hidden" name="pl" value="1">
                                           <input type="hidden" name="currency" value="NGN">
                                           <input type="hidden" name="user" value="{{Auth::user()->id}}">
                                           <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                                           <input type="hidden" name="email" value="{{Auth::user()->email ?? ''}} "/>
                                       </div>
                                       <ul>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i> Tenant Portal</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Document Storage</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Lease Management</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Property Listing</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Financial Reports</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>eInvoice Generation </li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Online Rent Payment</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Income/Expense Tracking</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Online Lease Application</li>
                                           <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Maintenance Request Tracking</li>
                                       </ul>
                                       <span class="d-flex justify-content-center mt-2" style="font-weight: 700; font-size: 18px;">Total: <span class="" style="color: #000000;" id="essential_total">₦3,000</span></span>
                                       <div class="form-group d-flex justify-content-center mt-4">
                                           <button type="submit" class="btn btn-primary btn-action btn-fill text-center">Subscribe</button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </form>
                    </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-block">
                            <div class="">
                                <div class="icon d-flex justify-content-center">
                                    <img src="/assets/logos/feature_icon_2.png" alt="Growth">
                                </div>
                                <div class="pricing-details " style="margin: 0px auto;">
                                    <h4 style="text-align: center;">Growth</h4>
                                    <span class="d-flex justify-content-center" style="font-weight: 700; font-size: 18px;">₦5,500/month</span>
                                    <span class="d-flex justify-content-center text-warning">Extra units ₦2,550</span>
                                    <p style="text-align: center;">
                                        You have a sizable number of properties in your portfolio.
                                        All you need is a platform that will help you manage all these in one space
                                    </p>
                                    <div class="input-group input-group-button">
                                        <span class="input-group-addon btn btn-secondary" id="basic-addon11">
                                            <span class="">I manage</span>
                                        </span>
                                        <input type="number" value="1" min="1" class="form-control" placeholder="No. of units">
                                        <span class="input-group-addon btn btn-primary" id="basic-addon12">
                                            <span class="">units</span>
                                        </span>
                                    </div>
                                    <ul>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i> Tenant Portal</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Document Storage</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Lease Management</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Property Listing</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Financial Reports</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>eInvoice Generation </li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Online Rent Payment</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Income/Expense Tracking</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Online Lease Application</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Maintenance Request Tracking</li>
                                    </ul>
                                    <span class="d-flex justify-content-center mt-2" style="font-weight: 700; font-size: 18px;">Total: <span class="" style="color: #000000;" id="essential-amount">₦3,000</span></span>
                                    <div class="form-group d-flex justify-content-center mt-4">
                                        <button class="btn btn-primary btn-action btn-fill text-center">Subscribe</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-block">
                            <div class="">
                                <div class="icon d-flex justify-content-center">
                                    <img src="/assets/logos/icon2.png" alt="Growth">
                                </div>
                                <div class="pricing-details " style="margin: 0px auto;">
                                    <h4 style="text-align: center;">Premium</h4>
                                    <span class="d-flex justify-content-center" style="font-weight: 700; font-size: 18px;">₦9,900/month</span>
                                    <span class="d-flex justify-content-center text-warning">Extra units ₦4,750</span>
                                    <p style="text-align: center;">
                                        You have a sizable number of properties in your portfolio.
                                        All you need is a platform that will help you manage all these in one space
                                    </p>
                                    <div class="input-group input-group-button">
                                        <span class="input-group-addon btn btn-secondary" id="basic-addon11">
                                            <span class="">I manage</span>
                                        </span>
                                        <input type="number" value="1" min="1" class="form-control" placeholder="No. of units">
                                        <span class="input-group-addon btn btn-primary" id="basic-addon12">
                                            <span class="">units</span>
                                        </span>
                                    </div>
                                    <ul>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i> Tenant Portal</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Document Storage</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Lease Management</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Property Listing</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Financial Reports</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>eInvoice Generation </li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Online Rent Payment</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Income/Expense Tracking</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Online Lease Application</li>
                                        <li style="text-align: center;"><i class="ti-check mr-2 text-success"></i>Maintenance Request Tracking</li>
                                    </ul>
                                    <span class="d-flex justify-content-center mt-2" style="font-weight: 700; font-size: 18px;">Total: <span class="" style="color: #000000;" id="essential-amount">₦3,000</span></span>
                                    <div class="form-group d-flex justify-content-center mt-4">
                                        <button class="btn btn-primary btn-action btn-fill text-center">Subscribe</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dialog-section')

@endsection

@section('extra-scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var units = $('#no_of_u')
            $(document).on('blur', '#essential_units',function (e) {
                var units = $(this).val();
                const per_unit = 3000;
                var metadata = [];
                var total = 0;
                if(units > 0){
                    total = per_unit * units;
                    $('#essential_total').text('₦'+total.toLocaleString());
                    $('#essential_amount').val(total*100);
                    var param = {
                        'no_of_units':$('#no_of_units')
                    }
                }else{
                    alert('Invalid number of units entered.');
                }
            });
        });
    </script>
@endsection
