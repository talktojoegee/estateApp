@extends('layouts.master-layout')
@section('current-page')
    Schedule Follow-up
@endsection
@section('extra-styles')
    <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <style>
        .text-danger{
            color: #ff0000 !important;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')

    <div class="container-fluid">
        <div class="row">
            @if($search == 0)
            <div class="col-md-6 col-lg-6 offset-lg-3 offset-md-3">
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {!! session()->get('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="modal-header">
                            <h6 class="text-info text-uppercase modal-title"> Period</h6>
                        </div>
                        <form action="{{ route('schedule-follow-up-preview') }}" method="get">
                            @csrf
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group col-6">
                                        <label for="" class="form-label">Month & Year</label>
                                        <input type="month" id="" name="period" class="form-control col-md-3  col-3" placeholder="Month">
                                        @error('period') <i class="text-danger">{{$message}}</i> @enderror
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
            @else
            <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="col-xl-12">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{$error}}</li>
                                @endforeach
                            @endif
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('submit-follow-up-schedule') }}" method="get">
                                        <div class="col-md-6 col-lg-6 ">
                                            <div class="modal-header">
                                                <h6 class="text-uppercase modal-title"> New Schedule</h6>
                                            </div>

                                                @csrf
                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="" class="form-label">Date</label>
                                                            <input type="date" name="date" class="form-control" placeholder="Title" value="{{ date('Y-m-d') }}">
                                                            @error('date') <i class="text-danger">{{$message}}</i> @enderror
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <label for="" class="form-label">Title</label>
                                                            <input type="text" name="title" class="form-control" placeholder="Title" value="{{ old('title') }}">
                                                            @error('title') <i class="text-danger">{{$message}}</i> @enderror
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <label for="" class="form-label">Objective</label>
                                                            <textarea placeholder="What's your objective..?" name="objective" style="resize: none;"
                                                                      class="form-control">{{ old('objective') }}</textarea>
                                                            @error('objective') <i class="text-danger">{{$message}}</i> @enderror
                                                            <input type="hidden" name="periodMonth" value="{{$month}}">
                                                            <input type="hidden" name="periodYear" value="{{$year}}">
                                                        </div>
                                                    </div>

                                                </div>

                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <p class="mt-3">Here is the list of persons for <code>{{ date('F', mktime(0, 0, 0, $month, 10)) }} {{ $year }}</code> </p>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th scope="">
                                                            <div class="form-check form-checkbox-outline form-check-primary mb-3">
                                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                            </div>
                                                        </th>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Mobile No.</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Gender</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(session()->has('whoops'))
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" bis_skin_checked="1">
                                                            <i class="mdi mdi-block-helper me-2"></i>
                                                            {{ session()->get('whoops') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>
                                                    @endif
                                                    @foreach($records as $key => $record)
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="form-check form-checkbox-outline form-check-primary mb-3">
                                                                    <input value="{{ $record->id }}" name="leads[]" class="form-check-input" type="checkbox">
                                                                </div>
                                                            </th>
                                                            <th scope="row">{{$key + 1}}</th>
                                                            <td>{{$record->first_name ?? '' }} {{$record->last_name ?? '' }} </td>
                                                            <td>{{$record->phone ?? '' }}</td>
                                                            <td>{{$record->email ?? '' }}</td>
                                                            <td>{{$record->gender == 1 ? 'Male' : 'Female' }}</td>
                                                        </tr>

                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12 d-flex justify-content-center mt-4">
                                            <button class="btn btn-primary" type="submit">Submit <i class="bx bxs-right-arrow"></i> </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function(){
            $( "#datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'MM yy',
                onClose: function(dateText, inst) {
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                }
            });
            $('#selectAll').click(function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });
        });
    </script>
@endsection
