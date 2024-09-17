<div class="row">
    <div class="col-md-12 mb-2 d-flex justify-content-end">


        <div class="btn-group">
            <div class="dropdown mt-sm-0">
                <a href="#" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Bulk Action <i class="bx bx-upload"></i>
                </a>
                <div class="dropdown-menu" style="">
                    <a class="dropdown-item" href="{{ route("show-bulk-property-import-form") }}">Bulk Import Properties</a>
                    <a class="dropdown-item" href="{{ route('show-imported-properties') }}">Manage List</a>
                </div>
            </div>
            <a href="{{route('add-new-property', ['account'=>$account])}}" class="btn btn-primary ">Add New Property <i class="bx bx-plus"></i> </a>
            <a href="{{url()->previous()}}" class="btn btn-secondary">Go Back <i class="bx bx-right-arrow"></i> </a>
        </div>
    </div>
</div>
