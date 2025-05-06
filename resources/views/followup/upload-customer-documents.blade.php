
@extends('layouts.master-layout')

@section('title')
Upload {{$client->first_name ?? ''}}'s Documents
@endsection
@section('current-page')
    Upload {{$client->first_name ?? ''}}'s Documents
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    Upload {{$client->first_name ?? ''}}'s Documents
@endsection

@section('main-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-10 offset-1 col-md-10 offset-1">
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

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="modal-header text-uppercase mb-3" >Document(s) Upload
                                </div>
                                <form action="{{route('upload-files')}}" autocomplete="off" method="post" enctype="multipart/form-data">

                                    <div class="form-group mb-3">
                                        <label for="">File Name</label>
                                        <input type="text" name="fileName" id="fileName" placeholder="File Name" class="form-control">
                                        @error('fileName')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="">Select Property</label>
                                        <select name="property"  id="property" class="form-control">
                                            <option value="0">None</option>
                                            @foreach($client->getCustomerListOfProperties($client->id) as $property)
                                                <option value="{{$property->id}}">{{$property->property_name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        @error('property')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Attachment</label> <br>

                                        <input type="file" id="fileList" name="attachments" class="form-control-file" multiple>
                                        @error('attachment')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <input type="hidden" id="folder" name="folder" value="0">
                                        <input type="hidden" id="lead" name="lead" value="{{ $client->id }}">
                                    </div>
                                    <progress id="progressBar" value="0" max="100" style="width: 300px; margin-top: 10px"></progress>
                                    <br>
                                    <div class="form-group">
                                        <h5 id="fileUploadStatus" class="text-info"></h5>
                                        <p id="loaded_n_total"></p>
                                    </div>
                                    <hr>
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{url()->previous()}}" class="btn btn-warning btn-mini"><i class="bx bx-left-arrow mr-2"></i> Go Back</a>
                                            <button type="button" onclick="uploadFiles()"  class="btn btn-primary"><i class="bx bx-cloud-upload mr-2"></i> Upload File(s)</button>
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



@endsection

@section('extra-scripts')
    <script>

        function _(id){
            return document.getElementById(id);
        }

        function progressHandler(event) {
            _("loaded_n_total").innerHTML = "Uploaded "+event.loaded + " bytes of "+event.total;
            let percent = (event.loaded / event.total) * 100;
            _("progressBar").value = Math.round(percent);
            _("fileUploadStatus").innerHTML = Math.round(percent) + "% uploaded ...please wait";
        }

   /*     function completeHandler(event) {
            _("status").innerHTML = event.target.message;
            _("progressBar").value = 0;
        }*/
        function completeHandler(event) {
            try {
                // Parse the JSON response from the server
                const response = JSON.parse(event.target.responseText);

                // Access the 'message' property and display it
                _("fileUploadStatus").innerHTML = response.message || "Upload completed successfully!";
            } catch (error) {
                // Handle cases where the response is not valid JSON
                _("fileUploadStatus").innerHTML = "An error occurred while processing the server response.";
                console.error("Error parsing server response:", error);
            }

            // Reset the progress bar
            _("progressBar").value = 0;
        }

        function uploadFiles() {
            let formData = new FormData();
            let userFiles = _("fileList").files;
            let property = _("property").value;
            for(let i = 0; i<userFiles.length; i++){
                formData.append("file_"+i, userFiles[i])
            }
            let fileName = _("fileName");
            let lead = _("lead");
            let folder = _("folder");
            formData.append("fileName", fileName.value);
            formData.append("lead", lead.value);
            formData.append("property", property);
            formData.append("folder", folder.value);
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content')); // Include CSRF token
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content')); // Add CSRF token

            let ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler)
            ajax.addEventListener("load", completeHandler, false)
            ajax.open("POST", "{{route('upload-multiple-documents')}}");
            ajax.send(formData);
        }




    </script>
@endsection
