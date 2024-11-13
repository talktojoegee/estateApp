<div class="col-xl-12 col-md-12 mb-2 mt-2 d-flex justify-content-end">
    @can('can-post-discard-import')
        @if($record->status == 0)
            <div class="btn-group">
                <a href="{{ route("post-property-record", $record->batch_code) }}" class="btn btn-primary ">Post Record <i class="bx bxs-check-circle"></i> </a>
                <a href="{{ route("discard-property-record", $record->batch_code) }}" class="btn btn-danger ">Discard Record <i class="bx bxs-trash"></i> </a>
            </div>
        @endif
    @endcan
</div>


<form action="{{route('update-imported-properties')}}" method="POST">
    @csrf
    <div class="table-responsive mt-3">
        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
            <thead>
            <tr>
                <th class="">#</th>
                <th class="wd-15p">Property Spec.</th>
                <th class="wd-15p">House No.</th>
                <th class="wd-15p">Street.</th>
                <th class="wd-15p">Customer</th>
                <th class="wd-15p">Price</th>
                <th class="wd-15p">Amount Paid</th>
                <th class="wd-15p">Estate</th>
                <th class="wd-15p">Property Type</th>
                <th class="wd-15p">Shop No.</th>
                <th class="wd-15p">Plot No.</th>
                <th class="wd-15p">No. of Office Rooms</th>
                <th class="wd-15p">Office/Shop En suite with toilet/bathroom</th>
                <th class="wd-15p">No. of Shops</th>
                <th class="wd-15p">Total Number of Bedrooms</th>
                <th class="wd-15p">With BQ</th>
                <th class="wd-15p">No. of Floors</th>
                <th class="wd-15p">No. of Toilets/bathrooms</th>
                <th class="wd-15p">No. of Car Parking Space</th>
                <th class="wd-15p">No. of Units</th>
                <th class="wd-15p">Property Condition</th>
                <th class="wd-15p">Property Status</th>
                <th class="wd-15p">Land size</th>
                <th class="wd-15p">Property Title</th>
                <th class="wd-15p">Description</th>
                @if($record->status == 0)
                    <th class="wd-15p">Action</th>
                @endif
            </tr>
            </thead>
            <tbody>

            @foreach($record->getBulkImportDetails as $key=> $item)
                <tr class="">
                    <td>{{ $key+1 }}</td>
                    <td>
                        <input type="hidden" name="records[]" value="{{$item->id}}">
                        <input type="text" style="width: 150px;" value="{{ $item->property_name ?? ''}}" placeholder="Property Name" name="property_name[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="house_no[]" style="width: 150px;" value="{{ $item->house_no ?? '' }}" placeholder="House No" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="street[]" style="width: 150px;" value="{{ $item->street ?? '' }}" placeholder="Street" class="form-control">
                    </td>
                    <td>
                        <select class="form-control select2" style="width: 150px;" name="occupied_by[]"><!-- Serves as customer -->
                            <option selected>--Select customer--</option>
                            @foreach($customers as $key=> $customer)
                                <option value="{{$customer->id}}" {{ $customer->id == $item->occupied_by ? 'selected' : null  }}>{{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="price[]" style="width: 150px;" value="{{ $item->price ?? '' }}" placeholder="Price" class="form-control">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="amount_paid[]" style="width: 150px;" value="{{ $item->amount_paid ?? '' }}" placeholder="Amount Paid" class="form-control">
                    </td>
                    <td>
                        <select class="form-control select2" style="width: 150px;" name="estate_id[]" id="">
                            @foreach($estates as $key=> $estate)
                                <option value="{{$estate->e_id}}" {{ $estate->e_id == $item->estate_id ? 'selected' : null  }}>{{ $estate->e_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control select2" style="width: 150px;" name="building_type[]">
                            @foreach($buildingTypes as $key=> $list)
                                <option value="{{$list->bt_id}}" {{ $list->bt_id == $item->building_type ? 'selected' : null  }}>{{ $list->bt_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="shop_no[]" style="width: 150px;" value="{{ $item->shop_no ?? '' }}" placeholder="Shop No" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="plot_no[]" style="width: 150px;" value="{{ $item->plot_no ?? '' }}" placeholder="Plot No" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="no_of_office_rooms[]" style="width: 150px;" value="{{ $item->no_of_office_rooms ?? '' }}" placeholder="No. of Office rooms" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="office_ensuite_toilet_bathroom[]" style="width: 150px;" value="{{ $item->office_ensuite_toilet_bathroom ?? '' }}" placeholder="Office En-suite" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="no_of_shops[]" style="width: 150px;" value="{{ $item->no_of_shops ?? '' }}" placeholder="No. of shops" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="total_no_bedrooms[]" style="width: 150px;" value="{{ $item->total_no_bedrooms ?? '' }}" placeholder="Total number of bedrooms" class="form-control">
                    </td>
                    <td>
                        <select class="form-control" name="with_bq[]" style="width: 150px;">
                            @foreach($bqOptions as $key=> $list)
                                <option value="{{$list->bqo_id}}" {{ $list->bqo_id == $item->with_bq ? 'selected' : null  }}>{{ $list->bqo_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="no_of_floors[]" style="width: 150px;" value="{{ $item->no_of_floors ?? '' }}" placeholder="Number of floors" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="no_of_toilets[]" style="width: 150px;" value="{{ $item->no_of_toilets ?? '' }}" placeholder="Number of toilets" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="no_of_car_parking[]" style="width: 150px;" value="{{ $item->no_of_car_parking ?? '' }}" placeholder="Number of car parking space" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="no_of_units[]" style="width: 150px;" value="{{ $item->no_of_units ?? '' }}" placeholder="Number of units" class="form-control">
                    </td>
                    <td>
                        <select class="form-control select2" name="property_condition[]" style="width: 150px;">
                            <option value="1" {{ $item->property_condition == 1 ? 'selected' : null  }}>Good</option>
                            <option value="2" {{ $item->property_condition == 2 ? 'selected' : null  }}>Under Repair</option>
                            <option value="3" {{ $item->property_condition == 3 ? 'selected' : null  }}>Bad</option>
                            <option value="4" {{ $item->property_condition == 4 ? 'selected' : null  }}>Fair</option>
                        </select>
                    </td>
                    <td>
                        <select  required  class="form-control select2" style="width: 150px;" name="constructionStage">
                            @foreach($constructionStages as $key => $stage)
                                <option value="{{$stage->cs_id}}" {{ $stage->cs_id == $item->construction_stage ? 'selected' : null }}>{{ $stage->cs_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"  name="land_size[]" style="width: 150px;" value="{{ $item->land_size ?? '' }}" placeholder="Land Size" class="form-control">
                    </td>
                    <td>
                        <select  required  class="form-control select2" style="width: 150px;" name="property_title">
                            @foreach($titles as $key => $title)
                                <option value="{{$title->pt_id}}" {{ $title->pt_id == $item->property_title ? 'selected' : null }}>{{ $title->pt_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                                                            <textarea name="description[]" style="width: 150px;" style="resize: none;"
                                                                      class="form-control">{{$item->description ?? 'N/A'}}</textarea>
                    </td>
                    @if($record->status == 0)
                        <td>
                            <a class="text-danger" href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#deleteRecordModal_{{$item->id}}"><i class="bx bx-trash text-danger"></i></a>
                            <div class="modal fade" id="deleteRecordModal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" >
                                            <h4 class="modal-title">Are You Sure?</h4>
                                            <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form autocomplete="off" action="#" method="post">
                                                @csrf
                                                <div class="form-group mt-1">
                                                    <p>This action cannot be undone. Are you sure you want to delete this record?</p>
                                                </div>
                                                <div class="form-group d-flex justify-content-center mt-3">
                                                    <div class="btn-group">
                                                        <a href="{{ route('delete-property-record', $item->id) }}" class="btn btn-danger  waves-effect waves-light">Delete <i class="bx bx-check-circle"></i> </a>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr>
    @if($record->status == 0)
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-lg">Save changes</button>
        </div>
    @endif
</form>
