

<div class="row">
    <div class="col-md-12 mb-2 d-flex justify-content-end">
        <div class="btn-group">
            <div class="dropdown mt-sm-0">
                <a href="#" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    More Actions <i class="bx bx-list-ul"></i>
                </a>
                <div class="dropdown-menu" style="">
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#paymentDefinitionModal">Add Salary Structure</a>
                    <a class="dropdown-item" href="{{ route('new-salary-allowance') }}">Add Salary Allowance</a>
                    <a class="dropdown-item" href="{{ route('salary-allowances') }}">Salary Allowances</a>
                </div>
            </div>
            <a href="{{url()->previous()}}" class="btn btn-secondary">Go Back <i class="bx bx-right-arrow"></i> </a>
        </div>
    </div>
</div>


<div class="modal " id="paymentDefinitionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Add Salary Structure</h6>
                <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form autocomplete="off" action="{{route('salary-structure')}}" id="createIncomeForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">

                        <div class="form-group mt-3 col-md-12">
                            <label for=""> Name <sup style="color: #ff0000 !important;">*</sup></label>
                            <input type="text" value="{{ old('name') }}" name="name" placeholder="Name" class="form-control">
                            @error('name') <i class="text-danger">{{$message}}</i>@enderror
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
