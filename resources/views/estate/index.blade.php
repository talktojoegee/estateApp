
@extends('layouts.master-layout')

@section('title')
    Manage Estates
@endsection

@section('current-page')
    Manage Estates
@endsection
@section('extra-styles')

    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                @if(session()->has('success'))
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="card">
                        <div class="card-body">
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
                <div class="row">
                    <div class="col-xl-6 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-1">
                                    <div class="col">
                                        <p class="mb-1">Estates</p>
                                        <h5 class="mb-0 text-info number-font">{{ number_format( $estates->count() ) }}</h5>
                                    </div>
                                    <div class="col-auto mb-0">
                                        <div class="dash-icon text-secondary1">
                                            <i class="bx bxs-building-house"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">Total Estates</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" id="addNewEstate" data-bs-toggle="modal" data-bs-target="#addNew"  class="btn btn-primary"> Add New <i class="bx bxs-add-to-queue"></i> </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="modal-header text-uppercase text-white">Estates</div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive mt-3">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th class="">#</th>
                                                <th class="wd-15p">Date</th>
                                                <th class="wd-15p">Name</th>
                                                <th class="wd-15p">Code</th>
                                                <!-- EFAB Queens Estate Shopping Complex Karsana  -->
                                                <th class="wd-15p">State</th>
                                                <th class="wd-15p">Town</th>
                                                <th class="wd-15p">Properties</th>
                                                <th class="wd-15p">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($estates as $key => $estate)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{date('d M, Y', strtotime($estate->created_at))}}</td>
                                                    <td><a href="{{route('show-estate-view', $estate->e_slug)}}">{{ $estate->e_name ?? ''  }}</a> </td>
                                                    <td>{{ $estate->e_ref_code ?? '' }}</td>
                                                    <td>{{$estate->getState->name ?? '' }}</td>
                                                    <td>{{$estate->e_city ?? ''}}</td>
                                                    <td>
                                                        <span class="badge bg-danger rounded-pill" style="background: #ff0000 !important;">{{ number_format($estate->getProperties->count() ) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{route('show-estate-view', $estate->e_slug)}}" > <i class="bx bxs-book-open text-info"></i> View</a>
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal right fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="width: 900px;">
        <div class="modal-dialog modal-lg w-100" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-white text-uppercase" style="text-align: center;" id="myModalLabel2">Add New Estate</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('estates')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for=""> Name <sup style="color: #ff0000 !important;">*</sup></label>
                                <input type="text" value="{{ old('name') }}" name="name" placeholder="Estate Name" class="form-control">
                                @error('name') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6 ">
                                <label for="">Country <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <select name="country" id="country"  class="form-control select2">
                                    <option value="161">Nigeria</option>
                                    @foreach($countries as $country)
                                        @if($country->id != 161)
                                            <option value="{{$country->id}}">{{$country->name ?? '' }}</option>
                                        @endif
                                    @endforeach

                                </select>
                                @error('state') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6 " id="state">
                                <label for="">State <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <select name="state"  class="form-control select2 ">
                                    <option selected disabled>--Select state --</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->id}}">{{$state->name ?? '' }}</option>
                                    @endforeach

                                </select>
                                @error('state') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Town <span class="text-danger" style="color: #ff0000 !important;">*</span></label> <br>
                                <input type="text" name="city" placeholder="Town" class="form-control">
                                @error('city') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-3 col-md-6">
                                <label for="">Estate Code <span class="text-danger" style="color: #ff0000 !important;">*</span></label> <br>
                                <input type="text" name="referenceCode" placeholder="Enter a unique Reference Code. Example RAY for Raylight" class="form-control">
                                @error('referenceCode') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for="">Address <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <textarea name="address" id="address" placeholder="Type address here..."  style="resize: none;" class="form-control">{{ old('address') }}</textarea>
                                @error('address') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group mt-3 col-md-12">
                                <label for="">Brief Info <span class="text-danger" style="color: #ff0000 !important;">*</span></label>
                                <textarea name="info" id="info" placeholder="Enter a brief info about this estate"  style="resize: none;" class="form-control">{{ old('info') }}</textarea>
                                @error('info') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12 col-lg-12">
                                <div class="modal-header">Estate Amenities</div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mt-2">
                                            @foreach($amenities as $amenity)
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" value="{{$amenity->ea_id}}"    name="amenities[]"  type="checkbox" checked>
                                                        <label class="form-check-label" for="borehole">{{$amenity->ea_name ?? '' }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
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
    <script>
        $(document).ready(function(){
            $('#country').on('change',function(e){
                e.preventDefault();
                if( parseInt($(this).val()) !== 161 ){
                    $('#state').hide();
                }else{
                    $('#state').show();
                }
            });
        });
    </script>

@endsection
