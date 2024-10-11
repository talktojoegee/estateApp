<div class="dropdown mb-2">
    <a class="font-size-16 text-muted " role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-dots-horizontal"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end" style="">
        <a class="dropdown-item" style="cursor: pointer;" href="{{route('download-attachment',['slug'=>$file->filename])}}"><i class="bx bx-cloud-download mr-2 text-success"></i> Download</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#renameModal_{{$file->id}}"><i class="bx bx-pencil mr-2 text-warning"></i> Rename</a>
        <a class="dropdown-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#infoModal_{{$file->id}}"><i class="bx bx-info-circle mr-2 text-info"></i> Info</a>
        @can('can-delete-document')<a class="dropdown-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#fileModal_{{$file->id}}"><i class="bx bx-trash mr-2 text-danger"></i> Delete</a> @endcan
    </div>
</div>




