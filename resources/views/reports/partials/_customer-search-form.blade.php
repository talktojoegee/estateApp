<div class="col-md-6 col-lg-6 offset-lg-3 offset-md-3">
    <div class="card">
        <div class="card-body">
            <div class="modal-header">
                <h6 class="text-uppercase text-info modal-title"> Customer Report</h6>
            </div>
            <form action="{{ route('generate-customer-report') }}" method="get">
                @csrf
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="form-label">From</label>
                            <input autocomplete="off" type="text" id="datepicker" value="{{ $from }}" name="from" class="form-control" placeholder="From">
                            <input type="hidden" name="type" value="cashbook">
                            @error('from') <i class="text-danger">{{$message}}</i> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="form-label">To</label>
                            <input autocomplete="off" type="text" id="datepicker2" value="{{ $to }}" name="to" class="form-control" placeholder="To">
                            @error('to') <i class="text-danger">{{$message}}</i> @enderror
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
