
@extends('layouts.master-layout')
@section('title')
    Imported Properties
@endsection
@section('current-page')
    Manage Bulk Imported Properties
@endsection
@section('extra-styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
                <div class="col-xl-12 col-md-12">
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
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        @include('property.partial._manage-menu')
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4 text-info"> Imported Properties</h4>
                                <p class="text-muted mb-4">You'll find below information related to the person that carried out this action among other things.</p>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-nowrap mb-0">
                                                <tbody>
                                                <tr>
                                                    <th scope="row">Full Name:</th>
                                                    <td>{{ $record->getImportedBy->title ?? ''  }}  {{ $record->getImportedBy->first_name ?? ''  }} {{ $record->getImportedBy->last_name ?? ''  }} {{ $record->getImportedBy->other_names ?? ''  }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Email :</th>
                                                    <td>{{ $record->getImportedBy->email ?? ''  }} </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Mobile No.  :</th>
                                                    <td> {{ $record->getImportedBy->cellphone_no ?? ''  }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Date Imported:</th>
                                                    <td>{{ date('d F, Y', strtotime($record->created_at)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Batch Code :</th>
                                                    <td>{{ $record->batch_code ?? ''  }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-nowrap mb-0">
                                                <tbody>
                                                <tr>
                                                    <th scope="row">Status :</th>
                                                    <td>
                                                        @switch($record->status)
                                                            @case(0)
                                                            <span class="text-warning">Pending</span>
                                                            @break
                                                            @case(1)
                                                            <span class="text-success">Approved</span>
                                                            @break
                                                            @case(2)
                                                            <span class="text-danger">Discarded</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-nowrap mb-0">
                                                <tbody>
                                                <tr>
                                                    <th scope="row">Narration :</th>
                                                    <td>{{ $record->narration ?? ''  }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 col-md-12">
                        <div class="card">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">  Review Bulk Import</h4>
                                        <p>Kindly review this bulk action before posting. </p>
                                        <div class="table-responsive mt-3">
                                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th class="">#</th>
                                                    <th class="wd-15p">Property Spec.</th>
                                                    <th class="wd-15p">House No.</th>
                                                    <th class="wd-15p">Street.</th>
                                                    <th class="wd-15p">Customer</th>
                                                    <th class="wd-15p">Price({{env('APP_CURRENCY')}})  </th>
                                                    <th class="wd-15p">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($record->getBulkImportDetails->where('action_status', '!=', 1) as $key=> $item)
                                                    <tr class="">
                                                        <td>{{ $key+1 }}</td>
                                                        <td>
                                                            {{ strlen( $item->property_name) > 30 ? substr( $item->property_name,0,30).'...' :  $item->property_name}}
                                                        </td>
                                                        <td>
                                                            {{ $item->house_no ?? '' }}
                                                        </td>
                                                        <td>
                                                            {{ $item->street ?? '' }}
                                                        </td>
                                                        <td>
                                                            {{$item->getOccupiedBy->first_name ?? '' }} {{$item->getOccupiedBy->last_name ?? '' }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($item->price ?? 0, 2)  }}
                                                        </td>
                                                        @if($item->status == 0)
                                                            <td>
                                                                <a class=" btn btn-success btn-sm" href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#approveRecordModal_{{$item->id}}"><i class="bx bx-book-open " ></i></a>
                                                                <a class=" btn btn-warning btn-sm" href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#editRecordModal_{{$item->id}}"><i class="bx bx-pencil " ></i></a>
                                                                <a class="btn btn-danger btn-sm" href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#deleteRecordModal_{{$item->id}}"><i class="bx bx-trash " ></i></a>
                                                            </td>
                                                        @endif
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
    </div>


    @foreach($record->getBulkImportDetails->where('action_status', '!=', 1) as $key=> $item)

        <div class="modal fade" id="deleteRecordModal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-uppercase text-info">Are You Sure?</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="off" action="#" method="post">
                            @csrf
                            <div class="form-group mt-1">
                                <p>This action cannot be undone. Are you sure you want to delete property with  <span style="color: #ff0000 !important;">{{ $item->property_name ?? '' }}</span> specification located at <span class="text-info">{{ $item->getEstate->e_name ?? '' }}</span>? estate</p>
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <div class="btn-group">
                                    <a href="{{ route('action-property-record', ['recordId'=>$item->id, 'action'=>'delete']) }}" class="btn btn-danger  waves-effect waves-light">Delete <i class="bx bx-check-circle"></i> </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="approveRecordModal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-uppercase text-info">Are You Sure?</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="off" action="#" method="post">
                            @csrf
                            <div class="form-group mt-1">
                                <p>This action cannot be undone. Are you sure you want to <span class="text-success">approve</span> this record?</p>
                                <input type="hidden" name="itemId" value="{{$item->id}}">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                            <tr>
                                                <th scope="row"><strong>Property Specification:</strong></th>
                                                <td>{{ $item->property_name ?? ''}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>House No.:</strong></th>
                                                <td>{{ $item->house_no ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Street:</strong></th>
                                                <td>{{ $item->street ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Customer:</strong></th>
                                                <td>{{$item->getOccupiedBy->first_name ?? '' }} {{$item->getOccupiedBy->last_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Price:</strong></th>
                                                <td>{{env('APP_CURRENCY')}}{{ number_format($item->price ?? 0,2) ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Amount Paid:</strong></th>
                                                <td>{{env('APP_CURRENCY')}}{{ number_format($item->amount_paid ?? 0,2) ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Estate:</strong></th>
                                                <td>{{ $item->getEstate->e_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Property Type:</strong></th>
                                                <td>{{ $item->getBuildingType->bt_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Shop No.:</strong></th>
                                                <td>{{ $item->shop_no ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Plot No.:</strong></th>
                                                <td>{{ $item->plot_no ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> No. of Office Rooms:</strong></th>
                                                <td>{{ $item->no_of_office_rooms ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Office Ensuite Toilet/Bathroom:</strong></th>
                                                <td>{{ $item->office_ensuite_toilet_bathroom ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> No. of Shops:</strong></th>
                                                <td>{{ $item->no_of_shops ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Total No. of Bedrooms:</strong></th>
                                                <td>{{ $item->total_no_bedrooms ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> With BQ Option:</strong></th>
                                                <td>{{ $item->getWithBQOption->bqo_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> No.  of Floors:</strong></th>
                                                <td>{{ $item->no_of_floors ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> No.  of Toilets:</strong></th>
                                                <td>{{ $item->no_of_toilets ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> No.  of Car Parking:</strong></th>
                                                <td>{{ $item->no_of_car_parking ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> No.  of Units:</strong></th>
                                                <td>{{ $item->no_of_units ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Property Condition:</strong></th>
                                                <td>
                                                    @switch($item->property_condition)
                                                        @case(0)
                                                            None
                                                        @break
                                                        @case(1)
                                                            Good
                                                        @break
                                                        @case(2)
                                                            Under Repair
                                                        @break
                                                        @case(3)
                                                            Bad
                                                        @break
                                                        @case(4)
                                                            Fair
                                                        @break
                                                    @endswitch

                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Property Status:</strong></th>
                                                <td>{{ $item->getConstructionStage->cs_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Land Size:</strong></th>
                                                <td>{{ $item->land_size ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Property Title:</strong></th>
                                                <td>{{ $item->getPropertyTitle->pt_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong> Additional Information:</strong></th>
                                                <td>{{ $item->description ?? '' }}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <div class="">
                                    <a href="{{ route('action-property-record', ['recordId'=>$item->id, 'action'=>'approve']) }}" class="btn btn-success btn-sm  waves-effect waves-light">Approve <i class="bx bx-check-circle"></i> </a>
                                    <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#editRecordModal_{{$item->id}}" class="btn btn-warning btn-sm  waves-effect waves-light">Edit <i class="bx bx-pencil"></i> </a>
                                    <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#deleteRecordModal_{{$item->id}}" class="btn btn-danger btn-sm  waves-effect waves-light">Delete <i class="bx bx-trash"></i> </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRecordModal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-uppercase text-warning">Make Changes</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="off" action="{{ route('action-property-record', ['recordId'=>$item->id, 'action'=>'save-changes']) }}" method="get">
                            @csrf
                            <div class="form-group mt-1">
                                <p>Do well to save changes.</p>
                                <input type="hidden" name="itemId" value="{{$item->id}}">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                            <tr>
                                                <td style="width: 200px;"><strong>Property Specification:</strong></td>
                                                <td>
                                                    <input type="text" style="width: 100%;" value="{{ $item->property_name ?? ''}}" placeholder="Property Name" name="property_name" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>House No.:</strong></td>
                                                <td>
                                                    <input type="text" name="house_no" style="width: 100%;" value="{{ $item->house_no ?? '' }}" placeholder="House No" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Street:</strong></td>
                                                <td>
                                                    <input type="text" name="street" style="width: 100%;" value="{{ $item->street ?? '' }}" placeholder="Street" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Customer:</strong></td>
                                                <td>
                                                    <select class="form-control " style="width: 100%;" name="occupied_by"><!-- Serves as customer -->
                                                        <option selected>--Select customer--</option>
                                                        @foreach($customers as $key=> $customer)
                                                            <option value="{{$customer->id}}" {{ $customer->id == $item->occupied_by ? 'selected' : null  }}>{{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Price:</strong></td>
                                                <td>
                                                    <input type="number" step="0.01" name="price" style="width: 100%;" value="{{ $item->price ?? '' }}" placeholder="Price" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Amount Paid:</strong></td>
                                                <td>
                                                    <input type="number" step="0.01" name="amount_paid" style="width: 100%;" value="{{ $item->amount_paid ?? '' }}" placeholder="Amount Paid" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Estate:</strong></td>
                                                <td>
                                                    <select class="form-control " style="width: 100%;" name="estate_id">
                                                        @foreach($estates as $key=> $estate)
                                                            <option value="{{$estate->e_id}}" {{ $estate->e_id == $item->estate_id ? 'selected' : null  }}>{{ $estate->e_name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Property Type:</strong></td>
                                                <td>
                                                    <select class="form-control " style="width: 100%;" name="building_type">
                                                        @foreach($buildingTypes as $key=> $list)
                                                            <option value="{{$list->bt_id}}" {{ $list->bt_id == $item->building_type ? 'selected' : null  }}>{{ $list->bt_name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Shop No.:</strong></td>
                                                <td>
                                                    <input type="text" name="shop_no" style="width: 100%;" value="{{ $item->shop_no ?? '' }}" placeholder="Shop No" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong>Plot No.:</strong></td>
                                                <td>
                                                    <input type="text" name="plot_no" style="width: 100%;" value="{{ $item->plot_no ?? '' }}" placeholder="Plot No" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> No. of Office Rooms:</strong></td>
                                                <td>
                                                    <input type="text" name="no_of_office_rooms" style="width: 100%;" value="{{ $item->no_of_office_rooms ?? '' }}" placeholder="No. of Office rooms" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Office Ensuite Toilet/Bathroom:</strong></td>
                                                <td>
                                                    <input type="text" name="office_ensuite_toilet_bathroom" style="width: 100%;" value="{{ $item->office_ensuite_toilet_bathroom ?? '' }}" placeholder="Office En-suite" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> No. of Shops:</strong></td>
                                                <td>
                                                    <input type="text" name="no_of_shops" style="width: 100%;" value="{{ $item->no_of_shops ?? '' }}" placeholder="No. of shops" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Total No. of Bedrooms:</strong></td>
                                                <td>
                                                    <input type="text" name="total_no_bedrooms" style="width: 100%;" value="{{ $item->total_no_bedrooms ?? '' }}" placeholder="Total number of bedrooms" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> With BQ Option:</strong></td>
                                                <td>
                                                    <select class="form-control" name="with_bq" style="width: 100%;">
                                                        @foreach($bqOptions as $key=> $list)
                                                            <option value="{{$list->bqo_id}}" {{ $list->bqo_id == $item->with_bq ? 'selected' : null  }}>{{ $list->bqo_name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> No.  of Floors:</strong></td>
                                                <td>
                                                    <input type="text" name="no_of_floors" style="width: 100%;" value="{{ $item->no_of_floors ?? '' }}" placeholder="Number of floors" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> No.  of Toilets:</strong></td>
                                                <td>
                                                    <input type="text" name="no_of_toilets" style="width: 100%;" value="{{ $item->no_of_toilets ?? '' }}" placeholder="Number of toilets" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> No.  of Car Parking:</strong></td>
                                                <td>
                                                    <input type="text" name="no_of_car_parking" style="width: 100%;" value="{{ $item->no_of_car_parking ?? '' }}" placeholder="Number of car parking space" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> No.  of Units:</strong></td>
                                                <td>
                                                    <input type="text" name="no_of_units" style="width: 100%;" value="{{ $item->no_of_units ?? '' }}" placeholder="Number of units" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Property Condition:</strong></td>
                                                <td>
                                                    <select class="form-control " name="property_condition" style="width: 100%;">
                                                        <option value="1" {{ $item->property_condition == 1 ? 'selected' : null  }}>Good</option>
                                                        <option value="2" {{ $item->property_condition == 2 ? 'selected' : null  }}>Under Repair</option>
                                                        <option value="3" {{ $item->property_condition == 3 ? 'selected' : null  }}>Bad</option>
                                                        <option value="4" {{ $item->property_condition == 4 ? 'selected' : null  }}>Fair</option>
                                                    </select>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Property Status:</strong></td>
                                                <td>
                                                    <select  required  class="form-control " style="width: 100%;" name="constructionStage">
                                                        @foreach($constructionStages as $key => $stage)
                                                            <option value="{{$stage->cs_id}}" {{ $stage->cs_id == $item->construction_stage ? 'selected' : null }}>{{ $stage->cs_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Land Size:</strong></td>
                                                <td>
                                                    <input type="text"  name="land_size" style="width: 100%;" value="{{ $item->land_size ?? '' }}" placeholder="Land Size" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Property Title:</strong></td>
                                                <td>
                                                    <select  required  class="form-control " style="width: 100%;" name="property_title">
                                                        @foreach($titles as $key => $title)
                                                            <option value="{{$title->pt_id}}" {{ $title->pt_id == $item->property_title ? 'selected' : null }}>{{ $title->pt_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><strong> Additional Information:</strong></td>
                                                <td>
                                                    <textarea name="description" style="width: 100%; resize: none;"
                                                              class="form-control">{{$item->description ?? 'N/A'}}</textarea>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <div class="">
                                    <button type="submit" class="btn btn-success btn-sm  waves-effect waves-light">Save Changes <i class="bx bx-check-circle"></i> </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    @endforeach
@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
@endsection
