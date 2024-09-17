@extends('layouts.master-layout')
@section('title')
    Manage Property Allocations
@endsection
@section('current-page')
    Manage Property Allocations
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')
    {{$title ?? '' }} Properties
@endsection

@section('main-content')
    @inject('Utility', 'App\Http\Controllers\Portal\PropertyController')
    @if(session()->has('success'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('success') !!}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('error') !!}

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif


    @include('property.partial._manage-menu')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Estate</th>
                                <th class="wd-15p">House No.</th>
                                <th class="wd-15p">Code</th>
                                <th class="wd-15p" >Customer</th>
                                <th class="wd-15p" >Level</th>
                                <th class="wd-15p">Status</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allocations as $key => $allocation)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><a target="_blank" href="{{ route('show-estate-view', $allocation->getEstate->e_slug) }}">{{$allocation->getEstate->e_name ?? '' }}</a></td>
                                    <td>{{ $allocation->getProperty->house_no ?? '' }}</td>
                                    <td><a href="{{ route('show-property-details', $allocation->getProperty->slug) }}" target="_blank">{{$allocation->getProperty->property_code ?? '' }}</a></td>
                                    <td>{{$allocation->getCustomer->first_name ?? '' }} {{$allocation->getCustomer->last_name ?? '' }}</td>
                                    <td> <span class="text-info">{{$Utility->numToOrdinalWord($allocation->level ?? 0)}} Allottee</span>  </td>
                                    <td>
                                        @switch($allocation->status)
                                            @case(0)
                                            <label class='text-warning'>Pending</label>
                                            @break
                                            @case(1)
                                            <label class='text-success'>Approved</label>
                                            @break
                                            @case(2)
                                            <label class='text-danger'>Declined</label>
                                    @break
                                    @endswitch
                                    <td>
                                        <div class="btn-group">
                                            @if($allocation->status == 0)
                                            <button class="btn btn-success" data-bs-target="#approveModal_{{$allocation->id}}" data-bs-toggle="modal"><i class="bx bx-check-double"></i></button>
                                            <button class="btn btn-danger ml-2" data-bs-target="#declineModal_{{$allocation->id}}" data-bs-toggle="modal"><i class="bx bx-x"></i></button>
                                            @else
                                                <button class="btn btn-info" data-bs-target="#approveModal_{{$allocation->id}}" data-bs-toggle="modal"><i class="bx bx-show"></i></button>
                                            @endif
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
@foreach($allocations as $alloc)
    <div class="modal" id="approveModal_{{$alloc->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2"> {{ $alloc->status == 0 ? 'Approve Allocation' : 'Allocation Details' }}</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Note:</strong> This action cannot be undone. Are you sure you want to approve this property allocation?</p>
                    <form autocomplete="off" action="{{route('manage-property-allocations')}}" method="post"  enctype="multipart/form-data">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <tbody>
                                <tr>
                                    <th scope="row"><strong>Property Name:</strong></th>
                                    <td>{{$alloc->getProperty->property_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Estate:</strong></th>
                                    <td>{{$alloc->getEstate->e_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>House No.:</strong></th>
                                    <td>{{$alloc->getProperty->house_no ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Property Code:</strong></th>
                                    <td>{{$alloc->getProperty->property_code ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Customer:</strong></th>
                                    <td>{{$alloc->getCustomer->first_name ?? '' }} {{$alloc->getCustomer->last_name ?? '' }}</td>
                                </tr>
                                 <tr>
                                    <th scope="row"><strong>Allotment:</strong></th>
                                    <td><code>{{$Utility->numToOrdinalWord($alloc->level ?? 0)}} Allottee</code></td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Allocated By:</strong></th>
                                    <td>{{ $alloc->getAllocatedBy->title ?? ''  }} {{ $alloc->getAllocatedBy->first_name ?? ''  }} {{ $alloc->getAllocatedBy->last_name ?? ''  }} {{ $alloc->getAllocatedBy->other_names ?? ''  }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Date Allocated:</strong></th>
                                    <td>{{ !is_null($alloc->allocated_at) ? date('d M, Y h:ia', strtotime($alloc->allocated_at)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Status:</strong></th>
                                    <td>
                                        @switch($alloc->status)
                                            @case(0)
                                            <label class='text-warning'>Pending</label>
                                            @break
                                            @case(1)
                                            <label class='text-success'>Approved</label>
                                            @break
                                            @case(2)
                                            <label class='text-danger'>Declined</label>
                                            @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Actioned By:</strong></th>
                                    <td>
                                        {{ $alloc->getActionedBy->title ?? ''  }} {{ $alloc->getActionedBy->first_name ?? ''  }} {{ $alloc->getActionedBy->last_name ?? ''  }} {{ $alloc->getActionedBy->other_names ?? ''  }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Date Approved/Declined:</strong></th>
                                    <td>
                                        {{ !is_null($alloc->date_actioned) ? date('d M, Y h:ia', strtotime($alloc->date_actioned)) : '-' }}
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                        @csrf
                        <div class="row mb-3">
                            <input type="hidden" name="allocation" value="{{$alloc->id}}">
                            <input type="hidden" name="type" value="1">
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                @if($alloc->status == 0)
                                <button type="submit" class="btn btn-success  waves-effect waves-light">Approve <i class="bx bx-check-double"></i> </button>
                                @else
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary  waves-effect waves-light">Close <i class="bx bx-window-close"></i> </button>
                                @endif
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="declineModal_{{$alloc->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-danger text-uppercase" style="text-align: center;" id="myModalLabel2"> {{ $alloc->status == 0 ? 'Decline Allocation' : 'Allocation Details' }}</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Note:</strong> This action cannot be undone. Are you sure you want to decline this property allocation?</p>
                    <form autocomplete="off" action="{{route('manage-property-allocations')}}" method="post"  enctype="multipart/form-data">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <tbody>
                                <tr>
                                    <th scope="row"><strong>Property Name:</strong></th>
                                    <td>{{$alloc->getProperty->property_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Estate:</strong></th>
                                    <td>{{$alloc->getEstate->e_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>House No.:</strong></th>
                                    <td>{{$alloc->getProperty->house_no ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Property Code:</strong></th>
                                    <td>{{$alloc->getProperty->property_code ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Customer:</strong></th>
                                    <td>{{$alloc->getCustomer->first_name ?? '' }} {{$alloc->getCustomer->last_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Allotment:</strong></th>
                                    <td><code>{{$Utility->numToOrdinalWord($alloc->level ?? 0)}} Allottee</code></td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Allocated By:</strong></th>
                                    <td>{{ $alloc->getAllocatedBy->title ?? ''  }} {{ $alloc->getAllocatedBy->first_name ?? ''  }} {{ $alloc->getAllocatedBy->last_name ?? ''  }} {{ $alloc->getAllocatedBy->other_names ?? ''  }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Date Allocated:</strong></th>
                                    <td>{{ !is_null($alloc->allocated_at) ? date('d M, Y h:ia', strtotime($alloc->allocated_at)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Status:</strong></th>
                                    <td>
                                        @switch($alloc->status)
                                            @case(0)
                                            <label class='text-warning'>Pending</label>
                                            @break
                                            @case(1)
                                            <label class='text-success'>Approved</label>
                                            @break
                                            @case(2)
                                            <label class='text-danger'>Declined</label>
                                            @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Actioned By:</strong></th>
                                    <td>
                                        {{ $alloc->getActionedBy->title ?? ''  }} {{ $alloc->getActionedBy->first_name ?? ''  }} {{ $alloc->getActionedBy->last_name ?? ''  }} {{ $alloc->getActionedBy->other_names ?? ''  }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><strong>Date Approved/Declined:</strong></th>
                                    <td>
                                        {{ !is_null($alloc->date_actioned) ? date('d M, Y h:ia', strtotime($alloc->date_actioned)) : '-' }}
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                        @csrf
                        <div class="row mb-3">
                            <input type="hidden" name="allocation" value="{{$alloc->id}}">
                            <input type="hidden" name="type" value="2">
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                @if($alloc->status == 0)
                                    <button type="submit" class="btn btn-danger  waves-effect waves-light">Decline <i class="bx bx-check-double"></i> </button>
                                @else
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary  waves-effect waves-light">Close <i class="bx bx-window-close"></i> </button>
                                @endif
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
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>



@endsection
