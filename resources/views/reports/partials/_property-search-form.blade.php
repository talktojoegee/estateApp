<div class="col-md-6 col-lg-6 offset-lg-3 offset-md-3">
    <div class="card">
        <div class="card-body">
            <div class="modal-header">
                <h6 class="text-uppercase text-info modal-title"> Property Report</h6>
            </div>
            <form action="{{ route('generate-general-property-report') }}" method="get">
                @csrf
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Status <sup style="color: #ff0000;">*</sup> </label> <br>
                            <select name="status" id="status" class="form-control select2 w-75">
                                <option disabled selected>-- Select status --</option>
                                <option value="0">Available</option>
                                <option value="2">Sold</option>
                                <option value="1">Rented</option>
                                <option value="3">Reserved</option>
                            </select>
                            @error('status') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <label for="">Choose Location <sup style="color: #ff0000;">*</sup></label> <br>
                            <select name="location" id="location" class="form-control w-75 select2">
                                <option disabled selected>-- Select location --</option>
                                <option value="0">All Locations</option>
                                @foreach($estates as $estate)
                                    <option value="{{$estate->e_id}}">{{ $estate->e_name ?? '' }}</option>
                                @endforeach
                            </select>
                            @error('location') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12 d-flex justify-content-center mt-4">
                        <button class="btn btn-primary" type="submit">Submit <i class="bx bxs-right-arrow"></i> </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
