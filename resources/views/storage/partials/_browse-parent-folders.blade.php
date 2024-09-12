@if($folder->parent_id == 0)
    <div class="col-md-2">
        <a href="{{route('open-folder', ['slug'=>$folder->slug])}}" title="{{$folder->folder ?? 'No name'}}" data-original-title="{{$folder->folder ?? 'No name'}}" style="cursor: pointer;">
            <img src="/assets/formats/folder.png" height="64" width="64" alt="{{$folder->folder ?? 'No name'}}"><br>
            {{strlen($folder->folder ?? 'No name') > 20 ? substr($folder->folder ?? 'No name',0,17).'...' : $folder->folder ?? 'No name'}}
        </a>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="dropdown mb-2">
                    <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-dots-horizontal"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a class="dropdown-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#folderModal_{{$folder->id}}"><i class="bx bx-trash mr-2 text-danger"></i> Delete</a>
                    </div>
                </div>
                <div id="folderModal_{{$folder->id}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="card-header bg-custom">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Warning</h5>
                            </div>
                            <form action="{{route('delete-folder')}}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">This action cannot be undone. Are you sure you want to delete <strong>{{$folder->folder ?? '' }}</strong>?</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="folder_key"  value="{{$folder->id}}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="btn-group">
                                        <button data-bs-dismiss="modal" type="button" class="btn btn-secondary btn-mini"><i class="bx bx-block mr-2"></i>No, cancel</button>
                                        <button type="submit" class="btn btn-danger btn-mini"><i class="bx bx-check mr-2"></i>Yes, delete</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
