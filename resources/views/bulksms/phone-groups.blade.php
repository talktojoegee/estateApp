@extends('layouts.master-layout')
@section('current-page')
    Phone Group
@endsection
@section('title')
    Phone Group
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('phone-groups')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Phone Group Name</label>
                                    <input type="text" placeholder="Phone Group Name" name="group_name" value="{{old('group_name')}}" class="form-control">
                                    @error('group_name')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Contact</label>
                                    <textarea name="phone_numbers" id="contact" cols="30" rows="10" style="resize: none" placeholder="Enter a list of phone numbers separated by comma." class="form-control">{{old('phone_numbers')}}</textarea>
                                    @error('phone_numbers') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group d-flex justify-content-center mt-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-50"> Submit <i class="bx bxs-right-arrow mr-2"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-info text-uppercase">Phone Groups</h6>
                    @if(session()->has('success'))
                        <div class="alert alert-success mb-4">
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Phone Group Name</th>
                                <th class="wd-15p">Count</th>
                                <th class="wd-25p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($groups as $group)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$group->group_name ?? '' }}</td>
                                    <td>{{number_format(count(explode(",",$group->phone_numbers) ?? 0))}}</td>
                                    <td>
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#categoryModal_{{$group->id}}" class="btn btn-sm btn-info">View</a>
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


    @foreach($groups as $group)
        <div id="categoryModal_{{$group->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-info text-uppercase" id="myModalLabel">Edit Phone Group</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('edit-phone-group')}}" method="post" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Phone Group Name</label>
                                        <input type="text" placeholder="Phone Group Name" name="group_name" value="{{old('group_name', $group->group_name)}}" class="form-control">
                                        @error('group_name')<i class="text-danger">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">Phone Numbers</label>
                                        <textarea name="phone_numbers" id="contact" cols="30" rows="10" style="resize: none" placeholder="Enter a list of phone numbers separated by comma." class="form-control">{{old('contact',$group->phone_numbers)}}</textarea>
                                        @error('phone_numbers') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                    </div>
                                    <div class="form-group d-flex justify-content-center mt-2">
                                        <input type="hidden" name="group" value="{{$group->id}}">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-sm btn-custom"><i class="ti-check mr-2"></i> Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    @endforeach
@endsection

@section('extra-scripts')

@endsection
