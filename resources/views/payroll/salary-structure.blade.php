@extends('layouts.master-layout')
@section('title')
    Salary Structure
@endsection
@section('current-page')
    Salary Structure
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @if(session()->has('success'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>

                            {!! session()->get('success') !!}

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-warning">
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </div>
    @endif
    @include('payroll.partial._salary-menu')
    <div class="row">
        <div class="col-md-12 col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Salary Category</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($records as $key => $record)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $record->ss_name ?? '' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#editModal_{{$record->id}}" data-bs-toggle="modal"> <i class="bx bx-pencil text-warning"></i> Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#editModal_{{$record->id}}" data-bs-toggle="modal"> <i class="bx bx-street-view text-info"></i> View Allowances</a>
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

    @foreach($records as $key => $rec)
        <div class="modal" id="editModal_{{$rec->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-info text-uppercase" style="text-align: center;" id="myModalLabel2">Edit Payment Definition</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form autocomplete="off" action="{{route('salary-structure')}}" method="post"  enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group mt-3 col-md-12">
                                    <label for=""> Name <sup style="color: #ff0000 !important;">*</sup></label>
                                    <input type="text" value="{{ old('name',$rec->ss_name) }}" name="name" placeholder="Name" class="form-control">
                                    @error('name') <i class="text-danger">{{$message}}</i>@enderror
                                    <input type="hidden" value="{{$rec->id}}" name="structure">
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary  waves-effect waves-light">save changes <i class="bx bx-check-double"></i> </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach




@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>

    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/js/axios.min.js"></script>
    <script type="text/javascript" src="{{asset('/assets/js/notify.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#notice').hide();

            $('#estate').on('change',function(e){
                e.preventDefault();
                axios.post("{{route('property-list')}}",{estate:$(this).val()})
                    .then(response=>{
                        $('#appendList').html(response.data);
                        $(".select2").select2({
                            placeholder: "Select product or service"
                        });
                        $(".select2").last().next().next().remove();
                        $('#notice').show();
                        //$.notify(response.data.message, "success");
                        //$('#saveThemeChangesBtn').text("Save changes");
                    })
                    .catch(error=>{
                        $('#notice').hide();
                        //console.log(error)
                        $.notify("Whoops! Something went wrong. Try again", "error");
                    });
            });
        });
    </script>
@endsection
