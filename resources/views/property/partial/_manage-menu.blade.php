<div class="row">
    <div class="col-md-12 mb-2 d-flex justify-content-end">


        <div class="btn-group">
            <div class="dropdown mt-sm-0">
                <a href="#" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Bulk Action <i class="bx bx-upload"></i>
                </a>
                <div class="dropdown-menu" style="">
                    @can('can-import-properties')<a class="dropdown-item" href="{{ route("show-bulk-property-import-form") }}">Bulk Import Properties</a>@endcan
                    @can('access-import-properties')<a class="dropdown-item" href="{{ route('show-imported-properties') }}">Manage List</a>@endcan
                </div>
            </div>
            <div class="dropdown mt-sm-0">
                <a href="#" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    More Actions <i class="bx bx-align-justify"></i>
                </a>
                <div class="dropdown-menu" style="">
                    @can('can-add-property')<a class="dropdown-item" href="{{route('add-new-property')}}">Add New Property</a>@endcan
                    @can('access-all-properties')<a class="dropdown-item" href="{{route('manage-properties','all')}}">All Properties</a>@endcan
                    <a class="dropdown-item" href="{{route('property-reservation')}}">New Reservation</a>
                    <a class="dropdown-item" href="{{route('manage-properties', 'reserved')}}">All Reservations</a>
                </div>
            </div>
            <a href="{{url()->previous()}}" class="btn btn-secondary">Go Back <i class="bx bx-right-arrow"></i> </a>
        </div>
    </div>
</div>
