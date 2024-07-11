@extends('layouts.master-layout')
@section('title')
    Assign Frequency
@endsection
@section('current-page')
    Assign Frequency
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('manage-applications') }}" class="btn btn-secondary "> <i
                                class="bx bx bxs-left-arrow"></i> Go back</a>

                    </div>

                    @if(session()->has('success'))
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-8">
                <div class="row">

                    <div class="col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <p><strong>Note:</strong> In the section below, enter frequency value for the number of devices under each section</p>
                                        <p>The validity of each frequency license is <code>one(1) year.</code></p>
                                    </div>
                                    <form action="{{ route('assign-frequency') }}" method="post">
                                        @csrf
                                        <div class="col-md-12 col-lg-12 col-sm-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="">Start Date <sup style="color: #ff0000 !important;">*</sup></label>
                                                        <input type="date" value="{{ date('Y-m-d') }}" name="startDate" class="form-control">
                                                        @error('startDate') <i class="text-danger" style="color: #ff0000 !important;">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="">License No. <sup style="color: #ff0000 !important;">*</sup></label>
                                                        <input type="text" value="{{ old('licenseNo') }}"  name="licenseNo" placeholder="Enter license number" class="form-control">
                                                        @error('licenseNo') <i class="text-danger" style="color: #ff0000 !important;">{{$message}}</i>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            <div class="modal-header text-uppercase">Assign License</div>
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">

                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Station</th>
                                                        <th>Mode</th>
                                                        <th>Category</th>
                                                        <th>Band</th>
                                                        <th>Type</th>
                                                        <th>Quantity</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($workflow->getRadioLicenseDetails as $key => $detail)
                                                        <tr>
                                                            <th>{{ $key +1  }}</th>
                                                            <td>{{$detail->getWorkstation->name ?? '' }}</td>
                                                            <td>{{$detail->operation_mode == 1 ? 'Simplex' : 'Duplex' }}</td>
                                                            <td>{{ $detail->getLicenseCategory->category_name ?? '' }}</td>
                                                            <td>
                                                                @switch($detail->frequency_band)
                                                                    @case(1)
                                                                    MF/HF
                                                                    @break
                                                                    @case(2)
                                                                    VHF
                                                                    @break
                                                                    @case(3)
                                                                    UHF
                                                                    @break
                                                                    @case(4)
                                                                    SHF
                                                                    @break
                                                                @endswitch
                                                            </td>
                                                            <td>
                                                                @switch($detail->type_of_device)
                                                                    @case(1)
                                                                    Handheld
                                                                    @break
                                                                    @case(2)
                                                                    Base Station
                                                                    @break
                                                                    @case(3)
                                                                    Repeaters Station
                                                                    @break
                                                                    @case(4)
                                                                    Vehicular Station
                                                                    @break
                                                                @endswitch
                                                            </td>
                                                            <td><span class="badge bg-danger rounded-pill" style="background: #ff0000 !important;">{{ number_format($detail->no_of_device ?? 0) }}</span> </td>
                                                        </tr>
                                                        @for($i = 0; $i<$detail->no_of_device; $i++)
                                                            <tr class="mt-2">
                                                                <td colspan="2">
                                                                    <div class="form-group">
                                                                        <span class="badge bg-danger rounded-pill" style="background: #ff0000 !important;">{{$i+1}}</span>
                                                                        <label for="">Max. Frequency & Tolerance</label>
                                                                        <input name="frequency[]" type="text" step="0.01" placeholder="Enter Frequency Value" class="form-control">
                                                                        <input name="detailId[]" type="hidden" value="{{ $detail->id }}">
                                                                    </div>
                                                                </td>
                                                                <td colspan="2">
                                                                    <div class="form-group ">
                                                                        <label for="">Emission Bandwidth</label>
                                                                        <input name="emission[]" type="text" step="0.01" placeholder="Emission Bandwidth" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td colspan="3">
                                                                    <div class="form-group">
                                                                        <label for="">Max. Effective Radiated Power</label>
                                                                        <input name="effectiveRadiated[]" type="text" step="0.01" placeholder="Max. Effective Radiated Power" class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="clear mt-3 d-flex justify-content-center">
                                            <button type="submit"  class="btn btn-primary">Submit <i class="bx bx-send"></i> </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="modal-header">
                        <div class="modal-title text-uppercase">Company Profile</div>
                    </div>
                    <div class="card-body">
                        <div class="mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase"> Name</label>
                            <p class="text-muted">{{$workflow->getCompany->organization_name ?? '' }} </p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">RC. No.</label>
                            <p class="text-muted">{{$workflow->getCompany->organization_code ?? '' }}</p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Mobile No.</label>
                            <p class="text-muted">{{$workflow->getCompany->phone_no ?? '' }}</p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Email Address</label>
                            <p class="text-muted">{{$workflow->getCompany->email ?? '' }}</p>
                        </div>

                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase"> Address</label>
                            <p class="text-muted">{{$workflow->getCompany->address ?? '' }}</p>
                        </div>
                        <div class=" mt-1">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase"> Year of Incorporation</label>
                            <p class="text-muted">{{ date('d M, Y', strtotime($workflow->getCompany->start_date)) ?? '' }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('extra-scripts')

@endsection



