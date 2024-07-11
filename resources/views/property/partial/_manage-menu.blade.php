<div class="row">
    <div class="col-md-12 mb-2 d-flex justify-content-end">
        <div class="btn-group">
            <a href="{{url()->previous()}}" class="btn btn-secondary">Go Back <i class="bx bx-left-arrow"></i> </a>
            <a href="{{route('add-new-property', ['account'=>$account])}}" class="btn btn-custom ">Add New Property <i class="bx bx-plus"></i> </a>
        </div>
    </div>
</div>
