<div class="col-md-6 col-lg-6 offset-lg-3 offset-md-3">
    <div class="card">
        <div class="card-body">
            <div class="modal-header">
                <h6 class="text-uppercase modal-title text-info"> Payroll Period</h6>
            </div>
            <form action="{{ route('generate-general-payroll-report') }}" method="get">
                @csrf
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="" class="form-label">Choose Payroll Period <sup style="color: #ff0000 !important;">*</sup> </label>
                            <input type="month"  name="payrollPeriod" class="form-control" placeholder="From">
                            @error('payrollPeriod') <i class="text-danger">{{$message}}</i> @enderror
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
