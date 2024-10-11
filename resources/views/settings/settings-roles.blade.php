@extends('layouts.master-layout')
@section('current-page')
    Manage Roles
@endsection
@section('title')
    Manage Roles
@endsection
@section('extra-styles')
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    @if(session()->has('success'))
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>

                    {!! session()->get('success') !!}

                    <button type="button"  class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="row" role="alert">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-outline me-2"></i>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body" style="padding: 2px;">
            <div class="row">
                <div class="col-md-3">
                    @include('settings.partial._sidebar-menu')
                </div>
                <div class="col-md-9 mt-4">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">Add New Role</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">Manage Roles</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="home1" role="tabpanel">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="modal-header mb-4">
                                                    <div class="modal-title text-uppercase">Add New Role</div>
                                                </div>
                                                <form action="{{ route('add-role') }}" method="post">
                                                    @csrf
                                                    <div class="form-group col-md-6 col-lg-6">
                                                        <label for="">Role Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" value="{{old('name')}}" placeholder="Role Name" class="form-control ">
                                                        @error('name') <i>{{ $message }}</i> @enderror
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                                        <div class="form-check form-switch mt-3">
                                                            <input class="form-check-input" type="checkbox" id="grantAll" name="grantAll">
                                                            <label class="form-check-label" for="grantAll">Grant all permissions?</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-4" id="permissionWrapper">
                                                        <label for="">Assign Permission(s) <span class="text-danger">*</span></label>
                                                        <select name="permission[]" class="select2 form-control select2-multiple" multiple="multiple">
                                                            @foreach($permissions as $per)
                                                                <option value="{{ $per->id }}">{{ $per->name ?? ''  }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('permission') <i>{{ $message }}</i> @enderror
                                                    </div>
                                                    <div class="form-group d-flex justify-content-center mt-5">
                                                        <button class="btn btn-primary">Submit <i class="bx bx-check-circle"></i> </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile1" role="tabpanel">
                                        <div class="modal-header mb-4">
                                            <div class="modal-title text-uppercase">Existing Roles</div>
                                        </div>
                                        <div class="table-responsive mt-3">
                                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th class="">#</th>
                                                    <th class="wd-15p">Name</th>
                                                    <th class="wd-15p">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($roles as $key=> $role)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$role->name ?? '' }}</td>
                                                        <td>
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#showMoreModal_{{$role->id}}" data-bs-toggle="modal"> <i class="bx bx-pencil text-warning"></i> </a>



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
        </div>
    </div>


    @foreach($roles as $key=> $role)
        <div class="modal fade" id="showMoreModal_{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-uppercase" id="myModalLabel2">Manage Role</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form autocomplete="off" autcomplete="off" action="{{route('update-role-permissions')}}" method="post" id="addBranch" data-parsley-validate="">
                            @csrf
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="flush-heading_{{$role->id}}">
                                    <button class="accordion-button fw-medium " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_{{$role->id}}" aria-expanded="false" aria-controls="flush-collapse_{{$role->id}}">
                                        {{$role->name ?? '' }}
                                    </button>
                                </h2>
                                <div id="flush-collapse_{{$role->id}}" class="accordion-collapse collapse show" aria-labelledby="flush-heading_{{$role->id}}" data-bs-parent="#accordionFlushExample_{{$role->id}}" style="">
                                    <div class="accordion-body text-muted">
                                        <div class="row">
                                            <div class="col-md-12 bg-dark p-2 text-white">
                                                <div class="form-check">
                                                    <input class="form-check-input selectAllPermissions" type="checkbox"  name="selectAllPermissions"  >
                                                    <label class="form-check-label">Select All</label>
                                                </div>
                                            </div>
                                            <hr>
                                            @foreach($permissions as $p)
                                                <div class="col-md-3 col-lg-3">
                                                    <div class="form-check form-checkbox-outline form-check-primary mb-3 text-wrap">
                                                        <input class="form-check-input" value="{{ $p->id  }}" {{  in_array($p->id,$role->getPermissionIdsByRoleId($role->id))  ? 'checked' : '' }}  name="permission[]" type="checkbox" >
                                                        {{$p->name ?? ''}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="roleId" value="{{ $role->id ?? '' }}">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn-primary btn">Save changes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('extra-scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script>
        $(document).ready(function(){
            $('#grantAll').on('change', function(){
                if ($(this).is(':checked'))
                    $('#permissionWrapper').hide();
                else
                    $('#permissionWrapper').show();
            });

            $('.selectAllPermissions').click(function(event) {
                if(this.checked) {
                    // Iterate each checkbox
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
