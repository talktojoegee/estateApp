
@extends('layouts.master-layout')
@section('title')
    Import Properties
@endsection
@section('current-page')
     Import Properties
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>
                            {!! session()->get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-close me-2"></i>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        @include('property.partial._manage-menu')
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <p><strong class="text-danger">Note:</strong> You're advised to use this feature to upload/import properties.
                                                    Use  <a href="">this template/sample</a> to avoid issues that may pop up from mis-representation. Anything other than this may result in wrong entries to the system. </p>
                                                <p>Kindly download the template to ensure that the file/attachment you intend to upload/import complies with it.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="modal-header mb-3">
                                                    <h6 class="text-uppercase modal-title">  Import Properties</h6>
                                                </div>
                                                <form action="{{ route('show-bulk-property-import-form') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row mt-4">
                                                        <div class="col-md-12 col-12 col-sm-12 mt-4">
                                                            <div class="form-group">
                                                                <label for="">Attachment <small>(.xlsx, .xls format only)</small> <sup class="text-danger">*</sup></label> <br>
                                                                <input type="file" name="attachment" class="form-control-file">
                                                                @error('attachment') <i class="text-danger">{{$message}}</i> @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mt-4">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">Header<sup class="text-danger">*</sup></label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="firstRowHeader">
                                                                    <label class="form-check-label" for="remember">
                                                                        Check this box if the first row in your Excel document contains headers.
                                                                    </label>
                                                                </div>
                                                                @error('firstRowHeader') <i class="text-danger">{{$message}}</i> @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mt-4">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">Narration <small>(Optional)</small></label>
                                                                <textarea name="narration" placeholder="Enter your narration here..." class="form-control">{{ old('narration') }}</textarea>
                                                                @error('narration') <i class="text-danger">{{$message}}</i> @enderror
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

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra-scripts')

@endsection
