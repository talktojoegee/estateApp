@extends('layouts.master-layout')
@section('title')
    Payment Definition
@endsection
@section('current-page')
    Payment Definition
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
    @if($errors->any())
        <div class="alert alert-warning">
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </div>
    @endif
    @include('payroll.partial._menu')
    <div class="row">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Pay Code</th>
                                <th class="wd-15p">Name</th>
                                <th class="wd-15p">Taxable?</th>
                                <th class="wd-15p">Type</th>
                                <th class="wd-15p">Variance</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($definitions as $key => $definition)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $definition->pay_code ?? '' }}</td>
                                    <td>{{ $definition->payment_name ?? '' }}</td>
                                    <td>{{ $definition->taxable == 1 ? 'Yes' : 'No' }}</td>
                                    <td>{{ $definition->payment_type == 1 ? 'Income' : 'Deduction' }}</td>
                                    <td>{{ $definition->payment_variance == 1 ? 'Standard' : 'Variation' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#editModal_{{$definition->id}}" data-bs-toggle="modal"> <i class="bx bx-pencil text-warning"></i> Edit</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($definitions as $key => $def)
        <div class="modal" id="editModal_{{$def->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Edit Payment Definition</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form autocomplete="off" action="{{route('payment-definition')}}" method="post"  enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group mt-3 col-md-12">
                                    <label for=""> Pay Code <sup style="color: #ff0000 !important;">*</sup></label>
                                    <input type="number" value="{{ old('payCode',$def->pay_code) }}" name="payCode" placeholder="Pay Code" class="form-control">
                                    @error('payCode') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group mt-3 col-md-12">
                                    <label for=""> Payment Name <sup style="color: #ff0000 !important;">*</sup></label>
                                    <input type="text" value="{{ old('paymentName',$def->payment_name) }}" name="paymentName" placeholder="Payment Name" class="form-control">
                                    @error('paymentName') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group mt-3 col-md-12">
                                    <label for=""> Taxable? <sup style="color: #ff0000 !important;">*</sup></label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check form-radio-primary mb-3">
                                                <input value="1" class="form-check-input" type="radio" name="taxable" {{$def->taxable == 1 ? 'checked' : null }}>
                                                <label class="form-check-label" for="formRadioColor1">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-radio-primary mb-3">
                                                <input value="0" class="form-check-input" type="radio" name="taxable" {{$def->taxable == 0 ? 'checked' : null }}>
                                                <label class="form-check-label" for="formRadioColor1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('taxable') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group mt-3 col-md-12">
                                    <label for=""> Payment Type <sup style="color: #ff0000 !important;">*</sup></label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check form-radio-success mb-3">
                                                <input value="1" class="form-check-input" type="radio" name="paymentType" {{$def->payment_type == 1 ? 'checked' : null }}>
                                                <label class="form-check-label" for="formRadioColor1">
                                                    Income
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-radio-danger mb-3">
                                                <input value="2" class="form-check-input" type="radio" name="paymentType" {{$def->payment_type == 2 ? 'checked' : null }}>
                                                <label class="form-check-label" for="formRadioColor1">
                                                    Deduction
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('paymentType') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group mt-3 col-md-12">
                                    <label for=""> Payment Variance <sup style="color: #ff0000 !important;">*</sup></label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check form-radio-primary mb-3">
                                                <input value="1" class="form-check-input" type="radio" name="paymentVariance" {{$def->payment_variance == 1 ? 'checked' : null }}>
                                                <label class="form-check-label" for="formRadioColor1">
                                                    Standard
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-radio-primary mb-3">
                                                <input value="2" class="form-check-input" type="radio" name="paymentVariance" {{$def->payment_variance == 2 ? 'checked' : null }}>
                                                <label class="form-check-label" for="formRadioColor1">
                                                    Variation
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="definition" value="{{$def->id}}">
                                    @error('paymentVariance') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary  waves-effect waves-light">save changes <i class="bx bx-check-double"></i> </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal right fade" id="paymentDefinitionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
        <div class="modal-dialog w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Add Payment Definition</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('payment-definition')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">

                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Pay Code <sup style="color: #ff0000 !important;">*</sup></label>
                                <input type="number" value="{{ old('payCode') }}" name="payCode" placeholder="Pay Code" class="form-control">
                                @error('payCode') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Payment Name <sup style="color: #ff0000 !important;">*</sup></label>
                                <input type="text" value="{{ old('paymentName') }}" name="paymentName" placeholder="Payment Name" class="form-control">
                                @error('paymentName') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Taxable? <sup style="color: #ff0000 !important;">*</sup></label>
                                <div class="row">
                                   <div class="col-md-3">
                                       <div class="form-check form-radio-primary mb-3">
                                           <input value="1" class="form-check-input" type="radio" name="taxable">
                                           <label class="form-check-label" for="formRadioColor1">
                                               Yes
                                           </label>
                                       </div>
                                   </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-radio-primary mb-3">
                                            <input value="0" class="form-check-input" type="radio" name="taxable" checked>
                                            <label class="form-check-label" for="formRadioColor1">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('taxable') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Payment Type <sup style="color: #ff0000 !important;">*</sup></label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check form-radio-success mb-3">
                                            <input value="1" class="form-check-input" type="radio" name="paymentType" checked>
                                            <label class="form-check-label" for="formRadioColor1">
                                                Income
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-radio-danger mb-3">
                                            <input value="2" class="form-check-input" type="radio" name="paymentType">
                                            <label class="form-check-label" for="formRadioColor1">
                                                Deduction
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('paymentType') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Payment Variance <sup style="color: #ff0000 !important;">*</sup></label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check form-radio-primary mb-3">
                                            <input value="1" class="form-check-input" type="radio" name="paymentVariance" checked>
                                            <label class="form-check-label" for="formRadioColor1">
                                                Standard
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-radio-primary mb-3">
                                            <input value="2" class="form-check-input" type="radio" name="paymentVariance">
                                            <label class="form-check-label" for="formRadioColor1">
                                                Variation
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('paymentVariance') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit <i class="bx bx-check-double"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>

    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/js/axios.min.js"></script>
    <script type="text/javascript" src="{{asset('/assets/js/notify.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#notice').hide();

            $('#estate').on('change',function(e){
                e.preventDefault();
                axios.post("{{route('property-list')}}",{estate:$(this).val()})
                    .then(response=>{
                        $('#appendList').html(response.data);
                        $(".select2").select2({
                            placeholder: "Select product or service"
                        });
                        $(".select2").last().next().next().remove();
                        $('#notice').show();
                        //$.notify(response.data.message, "success");
                        //$('#saveThemeChangesBtn').text("Save changes");
                    })
                    .catch(error=>{
                        $('#notice').hide();
                        //console.log(error)
                        $.notify("Whoops! Something went wrong. Try again", "error");
                    });
            });
        });
    </script>
@endsection
