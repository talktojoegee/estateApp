@extends('layouts.master-layout')
@section('title')
    Documents
@endsection
@section('current-page')
    Documents > {{$folder->folder ?? ''}}
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    Documents > {{$folder->folder ?? ''}}
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
    <div class="row">
        <div class="col-md-12 mb-2 d-flex justify-content-end">
            <div class="btn-group">
                <a href="{{route('cloud-storage')}}" class="btn btn-primary">Documents <i class="bx bx-home"></i> </a>
                <a href="{{url()->previous()}}" class="btn btn-warning">Go Back <i class="bx bx-left-arrow"></i> </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-4">
            <div class="card">
                <div class="modal-header">

                    <div class="modal-title text-uppercase">New Attachments </div>
                </div>
                <div class="card-body">
                    <div class="col-xl-12 col-md-12 col-lg-12">
                        <h4 class="card-title">Documents</h4>
                        <p class="card-title-desc">Create folder structures to organize your files or choose a certain directory to upload files</p>
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">File</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#profile1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Folder</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <form action="{{route('upload-files')}}" autocomplete="off" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="">File Name</label>
                                        <input type="text" name="fileName" placeholder="File Name" class="form-control">
                                        @error('fileName')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Attachment</label>
                                        <input type="file" name="attachments[]" class="form-control-file" multiple>
                                        @error('attachment')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <input type="hidden" name="folder" value="{{$folder->id}}">
                                    </div>
                                    <hr>
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{url()->previous()}}" class="btn btn-warning btn-mini"><i class="bx bx-left-arrow mr-2"></i> Go Back</a>
                                            <button type="submit" class="btn btn-primary"><i class="bx bx-cloud-upload mr-2"></i> Upload File(s)</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="profile1" role="tabpanel">
                                <form action="{{route('create-folder')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">Folder Name</label>
                                        <input type="text" name="folderName" placeholder="Folder Name" class="form-control">
                                        @error('folderName')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Parent Folder</label>
                                        <input type="text" class="form-control" readonly value="{{$folder->folder ?? ''}}">
                                        <input type="hidden" name="parentFolder" value="{{$folder->id}}">
                                        @error('parentFolder')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Visibility</label>
                                        <select name="visibility" id="visibility" class="form-control">
                                            <option disabled selected>--Select visibility--</option>
                                            <option value="1">Private</option>
                                            <option value="2">Public</option>
                                        </select>
                                        @error('visibility')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <hr>
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{url()->previous()}}" class="btn btn-warning btn-mini"><i class="bx bx-left-arrow mr-2"></i> Go Back</a>
                                            <button type="submit" class="btn btn-primary"><i class="bx bx-folder mr-2"></i> Create Folder</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-8">
            <div class="card">
                <div class="modal-header">
                    <div class="modal-title text-uppercase">Browse Files & Folders</div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12 col-lg-12">
                            <div class="card-header">
                                Documents <code>></code> {{$folder->folder ?? ''}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($subFolders as $folder)
                                <div class="col-md-2">
                                    <a href="{{route('open-folder', ['slug'=>$folder->slug])}}" title="{{$folder->folder ?? 'No name'}}" data-original-title="{{$folder->folder ?? 'No name'}}" style="cursor: pointer;">
                                        <img src="/assets/formats/folder.png" height="64" width="64" alt="{{$folder->folder ?? 'No name'}}"><br>
                                        {{strlen($folder->folder ?? 'No name') > 20 ? substr($folder->folder ?? 'No name',0,17).'...' : $folder->folder ?? 'No name'}}
                                    </a>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="dropdown-secondary dropdown float-right">
                                                <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item waves-light waves-effect deleteFolder" data-toggle="modal" data-target="#deleteFolderModal"  data-folder="{{$folder->folder ?? 'File name'}}" data-fid="{{$folder->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        @endforeach
                        @foreach ($files as $file)
                            @include('storage.partials._switch-file-format')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')



@endsection
