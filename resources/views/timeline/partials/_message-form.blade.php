<div class="modal-header">
    <div class="modal-title text-uppercase">Compose Message </div>
</div>
<div class="card-body">
    <form action="{{ route('publish-timeline-post') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Subject
                            </label>
                            <input type="text" placeholder="Subject" name="subject" value="{{ old('subject') }}" class="form-control">
                            @error('subject') <i class="text-danger">{{ $message }}</i> @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label d-flex justify-content-between">To
                            </label>
                            <select name="to"  class=" form-control memoTo" >
                                <option disabled selected data-select2-id="4">-- Select receiver --</option>
                                <option value="2">Department</option>
                                <option value="4">Individual(s)</option>
                            </select>
                            @error('to') <i class="text-danger">{{ $message }}</i> @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-3 from-branch">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label d-flex justify-content-between"> Branch
                            </label>
                            @include('timeline.partials._from-branch')
                        </div>
                    </div>
                </div>
                <div class="row mt-3 from-persons">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label d-flex justify-content-between"> Person
                            </label>
                            @include('timeline.partials._from-persons')
                        </div>
                    </div>
                </div>
                <div class="row mt-3" >
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label"> Attachment(s) <small>(Optional)</small>
                            </label> <br>
                            <input type="file" name="attachments[]" multiple class="form-control-file">
                        </div>
                    </div>
                </div>
                <div class="row mt-3 from-message">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea name="postContent" rows="5" style="resize: none" placeholder="Type here..." class="form-control content">{{ old('postContent') }}</textarea>
                            @error('postContent') <i class="text-danger">{{ $message }}</i> @enderror
                            <input type="hidden" name="type" value="1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right d-flex justify-content-center">
                <button type="submit" class="btn btn-primary ">Submit <i class="bx bxs-right-arrow"></i> </button>
            </div>
        </div>
    </form>
</div>
