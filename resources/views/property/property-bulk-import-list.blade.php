
@extends('layouts.master-layout')
@section('title')
    Manage Imported Properties
@endsection
@section('current-page')
    Manage Imported Properties
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                        <a href="{{route('show-bulk-property-import-form')}}"  class="btn btn-primary"> <i class="bx bxs-plus-circle"></i> New Import  </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="modal-header text-uppercase">Manage Imported Properties</div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-15p">Entry By</th>
                                            <th class="wd-15p">Estate</th>
                                            <th class="wd-15p">Ref. Code</th>
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-15p">Total Records</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $index = 1; @endphp

                                        @foreach($records as $key=> $record)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ date('d M, Y', strtotime($record->created_at)) }}</td>
                                                <td>{{ $record->getImportedBy->first_name ?? '' }} {{ $record->getImportedBy->last_name ?? '' }}</td>
                                                <td>{{ $record->getEstate($record->id)->e_name ?? '' }}</td>
                                                <td>{{ $record->batch_code ?? '' }}</td>
                                                <td>
                                                    @if(($record->getBulkImportDetails->count() - ($record->getBulkImportDetails->where('action_status', 1)->count()  + $record->getBulkImportDetails->where('action_status', 2)->count() )) == 0 )
                                                        <span class="text-success">Done</span>
                                                    @else
                                                        <span class="text-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($record->getBulkImportDetails->count() ?? 0) ?? '' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{route('show-imported-properties-details', $record->batch_code)}}"> <i class="bx bx-book-open"></i> View </a>
                                                        </div>
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
            </div>
        </div>
    </div>


@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>
@endsection
