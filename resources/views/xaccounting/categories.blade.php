@extends('layouts.master-layout')
@section('current-page')
    Account Categories
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
    <style>
        .fs-22{
            font-size: 22px;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#client" class="btn btn-primary  mb-3">Add Category <i class="bx bxs-plus-circle"></i> </a>
                    </div>
                    <div class="card-body">

                        <h4 class="card-title">Manage Categories</h4>
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert me-2"></i>
                                {!! session()->get('error') !!}
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
                            <div class="col-md-12 col-lx-12">
                                <div class="table-responsive mt-3">
                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-15p">Created By</th>
                                            <th class="wd-15p">Name</th>
                                            <th class="wd-15p">Type</th>
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-15p">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($categories as $key => $cat)
                                            <tr>
                                                <td>{{ $key+1  }}</td>
                                                <td>{{ date('d M, Y h:ia', strtotime($cat->created_at)) }}</td>
                                                <td>{{ $cat->getCreatedBy->title ?? '' }} {{ $cat->getCreatedBy->first_name ?? '' }} {{ $cat->getCreatedBy->last_name ?? '' }} {{ $cat->getCreatedBy->other_name ?? '' }}</td>
                                                <td>{{ $cat->tc_name ?? ''  }}
                                                    @if($cat->tc_remittable == 1)
                                                        <sup class='text-success '>Remit-table</sup>
                                                    @else
                                                        <sup class='text-warning'>Non-remit-table</sup>
                                                    @endif

                                                </td>
                                                <td>
                                                    @if($cat->tc_type == 1)
                                                        <span class="badge badge-pill badge-soft-success font-size-11">Income</span>
                                                    @else
                                                        <span class="badge badge-pill badge-soft-danger font-size-11">Expense</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($cat->tc_status == 1)
                                                        <span class="badge badge-pill badge-soft-success font-size-11">Active</span>
                                                    @else
                                                        <span class="badge badge-pill badge-soft-danger font-size-11">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#categoryModal_{{$cat->tc_id}}"> <i class="bx bxs-pencil text-warning"></i> View</a>
                                                        </div>
                                                    </div>
                                                    <div class="modal right fade" id="categoryModal_{{$cat->tc_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel_{{$cat->tc_id}}">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" >
                                                                    <h6 class="modal-title text-uppercase">Edit Category</h6>
                                                                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <form action="{{route('accounting.edit-category')}}" autocomplete="off" data-parsley-validate="" method="post" id="individualSessionForm">
                                                                        @csrf
                                                                        <div class="form-group mt-3">
                                                                            <label for="">Name</label>
                                                                            <input name="name" placeholder="Category Name" value="{{$cat->tc_name ?? '' }}" class="form-control" data-parsley-required-message="Enter a name for this category." required>
                                                                            @error('name') <i class="text-danger">{{$message}}</i>@enderror
                                                                        </div>
                                                                        <div class="form-group mt-3">
                                                                            <label for="">Type</label>
                                                                            <select name="type" id="" class="form-control" data-parsley-required-message="What will this account be used to track? Income or expense?" required>
                                                                                <option selected disabled>-- Select type --</option>
                                                                                <option value="1" {{ $cat->tc_type == 1 ? 'selected' : null }}>Income</option>
                                                                                <option value="2" {{ $cat->tc_type == 2 ? 'selected' : null }}>Expense</option>
                                                                            </select>
                                                                            @error('type') <i class="text-danger">{{$message}}</i>@enderror
                                                                        </div>
                                                                        <div class="form-group mt-3 ">
                                                                            <label for="">Remit-table?</label>
                                                                            <select name="remittance" id="remittance" class="form-control" data-parsley-required-message="indicate whether this category should pay remittance or not" required>
                                                                                <option value="1" {{ $cat->tc_remittable == 1 ? 'selected' : null  }}>Yes</option>
                                                                                <option value="0" {{ $cat->tc_remittable == 0 ? 'selected' : null  }}>No</option>
                                                                            </select>
                                                                            @error('remittance') <i class="text-danger">{{$message}}</i>@enderror
                                                                        </div>
                                                                        <div class="form-group mt-3 ">
                                                                            <label for="">Propose Remittance Rate</label>
                                                                            <input name="remittance_rate" value="{{ $cat->tc_proposed_rate ?? 0 }}" type="number" step="0.01" placeholder="Propose Remittance Rate" id="remittance_rate" class="form-control" >
                                                                            @error('remittance_rate') <i class="text-danger">{{$message}}</i>@enderror
                                                                        </div>
                                                                        <div class="form-group mt-3">
                                                                            <label for="">Status</label>
                                                                            <select name="status" id="" class="form-control" data-parsley-required-message="Active or inactive?" required>
                                                                                <option selected disabled>-- Select status --</option>
                                                                                <option value="1" {{ $cat->tc_status == 1 ? 'selected' : null }}>Active</option>
                                                                                <option value="2" {{ $cat->tc_status == 2 ? 'selected' : null }}>Inactive</option>
                                                                            </select>
                                                                            @error('status') <i class="text-danger">{{$message}}</i>@enderror
                                                                        </div>
                                                                        <div class="form-group d-flex justify-content-center mt-3">
                                                                            <div class="btn-group">
                                                                                <input type="hidden" name="catId" value="{{$cat->tc_id}}">
                                                                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Save changes <i class="bx bx-right-arrow"></i> </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
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

    <div class="modal right fade" id="client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-uppercase">Add Category</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{route('accounting.categories')}}" autocomplete="off" data-parsley-validate="" method="post" id="individualSessionForm">
                        @csrf
                        <div class="form-group mt-3">
                            <label for="">Name</label>
                            <input name="name" placeholder="Category Name" id="" class="form-control" data-parsley-required-message="Enter a name for this category." required>
                            @error('name') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Type</label>
                            <select name="type" id="categoryType" class="form-control" data-parsley-required-message="What will this account be used to track? Income or expense?" required>
                                <option selected disabled>-- Select type --</option>
                                <option value="1">Income</option>
                                <option value="2">Expense</option>
                            </select>
                            @error('type') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3 remittanceWrapper">
                            <label for="">Remit-table?</label>
                            <select name="remittance" id="remittance" class="form-control" data-parsley-required-message="indicate whether this category should pay remittance or not" >
                                <option selected disabled>-- Remit-table? --</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('remittance') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3 remittanceWrapper">
                            <label for="">Propose Remittance Rate</label>
                            <input name="remittance_rate" value="0" type="number" step="0.01" placeholder="Propose Remittance Rate" id="remittance_rate" class="form-control" >
                            @error('remittance_rate') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Status</label>
                            <select name="status" id="" class="form-control" data-parsley-required-message="Active or inactive?" required>
                                <option selected disabled>-- Select status --</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            @error('status') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Submit <i class="bx bx-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

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
    <script src="/js/parsley.js"></script>
    <script>
        $(document).ready(function(){
            //$('.remittanceWrapper').hide();
            $('#categoryType').on('change', function(){
                let selectedOption = $(this).val();
                if(parseInt(selectedOption) === 1){
                    $('.remittanceWrapper').show();
                }else{
                    $('.remittanceWrapper').hide();
                }
            });
        });
    </script>
@endsection
