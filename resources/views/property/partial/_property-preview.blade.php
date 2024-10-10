<div class="col-md-12 col-sm-12">
    @if($property->status != 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-all me-2"></i>
            Whoops! This property is not available for reservation.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card p-3">
        <h5 class="modal-header text-info">
            {{$property->property_name ?? '' }}
        </h5>
        <div class="card-body">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    @if(count($property->getPropertyGalleryImages) > 0)
                        @foreach($property->getPropertyGalleryImages as $key=>$image)
                            <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img class="d-block img-fluid" src="/assets/drive/property/{{$image->attachment ?? '' }}" alt="Property image">
                            </div>
                        @endforeach
                    @else
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="/assets/drive/property/placeholder.png" alt="Property image">
                        </div>
                    @endif
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <h6 class="mt-4 text-uppercase modal-header p-4  mb-3 text-info">Details</h6>
            <div class="p-3">{!! $property->description ?? '' !!}</div>
            <h6 class="mt-4 text-uppercase modal-header p-4 text-info">Summary</h6>
            <div class="table-responsive">
                <table class="table mb-0 table-striped">
                    <tbody>
                    <tr>
                        <th scope="row" >Estate: &nbsp; &nbsp; <span class="text-info">{{$property->getEstate->e_name ?? '' }} </span></th>
                        <th scope="row">Building Type: &nbsp; &nbsp; <span class="text-info">{{$property->getBuildingType->bt_name ?? '' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >Property Title: &nbsp; &nbsp; <span class="text-info">{{$property->getPropertyTitle->pt_name ?? '' }} </span></th>
                        <th scope="row" >Price: &nbsp; &nbsp; <span class="text-info">{{ env('APP_CURRENCY')  }}{{ number_format($property->price ?? 0,2) }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >House No.: &nbsp; &nbsp; <span class="text-info">{{$property->house_no ?? '-' }} </span></th>
                        <th scope="row" >Shop No.: &nbsp; &nbsp; <span class="text-info">{{ $property->shop_no ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >Plot No.: &nbsp; &nbsp; <span class="text-info">{{$property->plot_no ?? '-' }} </span></th>
                        <th scope="row" >No. of Office Rooms: &nbsp; &nbsp; <span class="text-info">{{ $property->no_of_office_rooms ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >No. of shops: &nbsp; &nbsp; <span class="text-info">{{$property->no_of_shops ?? '-' }} </span></th>
                        <th scope="row" >Office Ensuite Toilet/Bathroom: &nbsp; &nbsp; <span class="text-info">
                                        @switch($property->office_ensuite_toilet_bathroom)
                                    @case(0)
                                    None
                                    @break
                                    @case(1)
                                    Yes
                                    @break
                                    @case(2)
                                    No
                                    @break
                                @endswitch
                                    </span></th>
                    </tr>
                    <tr>
                        <th scope="row" >Property Code: &nbsp; &nbsp; <span class="text-info">{{$property->property_code ?? '-' }} </span></th>
                        <th scope="row" >Street: &nbsp; &nbsp; <span class="text-info">{{ $property->street ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >Total No. of Bedrooms: &nbsp; &nbsp; <span class="text-info">{{$property->total_no_bedrooms ?? '-' }} </span></th>
                        <th scope="row" >With BQ.: &nbsp; &nbsp; <span class="text-info">{{ $property->getWithBQOption->bqo_name ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >No. of Floors: &nbsp; &nbsp; <span class="text-info">{{$property->no_of_floors ?? '-' }} </span></th>
                        <th scope="row" >No. of Toilets: &nbsp; &nbsp; <span class="text-info">{{ $property->no_of_toilets ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >No. of Car Parking: &nbsp; &nbsp; <span class="text-info">{{$property->no_of_car_parking ?? '-' }} </span></th>
                        <th scope="row" >No. of Units: &nbsp; &nbsp; <span class="text-info">{{ $property->no_of_units ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >Property Condition: &nbsp; &nbsp;

                            @switch($property->property_condition)
                                @case(1)
                                <span class="text-success">Good</span>
                                @break
                                @case(2)
                                <span class="text-warning">Under Repair</span>
                                @break
                                @case(3)
                                <span class="text-danger" style="color: #ff0000 !important;">Bad</span>
                                @break
                                @case(4)
                                <span class="text-info"> Fair</span>
                                @break
                            @endswitch
                        </th>
                        <th scope="row" >Property Status: &nbsp; &nbsp; <span class="text-info">{{ $property->getConstructionStage->cs_name ?? '-' }}</span></th>
                    </tr>
                    <tr>
                        <th scope="row" >Land Size: &nbsp; &nbsp; <span class="text-info">{{$property->land_size ?? '-' }} </span></th>
                        <th scope="row" >Ledger Account: &nbsp; &nbsp; <span class="text-warning">{{ $property->getGlAccount->account_name ?? '-' }} - {{ $property->getGlAccount->glcode ?? '-' }}</span></th>
                    </tr>
                    <tr>

                        <th scope="row" >Status: &nbsp; &nbsp;
                            @switch($property->status)
                                @case(0)
                                <span class="text-success">Available</span>
                                @break
                                @case(1)
                                <span class="text-warning">Rented</span>
                                @break
                                @case(2)
                                <span class="text-danger" style="color: #ff0000 !important;">Sold</span>
                                @break
                                @case(3)
                                <span class="" style="color: orange !important;">Reserved</span>
                                @break
                            @endswitch
                        </th>
                        <th scope="row" >Date Added: &nbsp; &nbsp; <span class="text-info"> {{ date('d M, Y h:ia', strtotime($property->created_at)) }}  </span></th>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2" >Payment Plan: &nbsp; &nbsp; <span class="text-info"> {{ $property->getPaymentPlan->pp_name ?? '' }} <br> <small>({{ $property->getPaymentPlan->pp_description ?? '-' }})</small>  </span></th>
                    </tr>

                    </tbody>
                </table>
            </div>
            <h6 class="modal-header p-4 text-uppercase">Other Details</h6>
            <div class="table-responsive">
                <table class="table mb-0 table-striped">
                    <tbody>
                    <tr>
                        <th scope="row" > Added By: &nbsp; &nbsp; <span class="text-info"> {{$property->getAddedBy->title ?? '' }} {{$property->getAddedBy->first_name ?? '' }} {{$property->getAddedBy->last_name ?? '' }} {{$property->getAddedBy->other_names ?? '' }} </span></th>

                    </tr>
                    <tr>
                        <th scope="row" > Sold To: &nbsp; &nbsp; <span class="text-info">  {{$property->getSoldTo->first_name ?? '' }} {{$property->getSoldTo->last_name ?? '' }} {{$property->getSoldTo->other_names ?? '' }} </span></th>
                        <th scope="row" >Date Sold: &nbsp; &nbsp;
                            <span class="text-info">{{ !is_null($property->date_sold) ? date('d M, Y h:ia', strtotime($property->date_sold)) : '-' }}</span>
                        </th>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
