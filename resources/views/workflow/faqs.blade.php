@extends('layouts.master-layout')
@section('title')
    Frequently Asked Questions
@endsection
@section('extra-styles')

@endsection
@section('active-page')
    Frequently Asked Questions
@endsection

@section('main-content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Frequently Asked Questions</h4>
                    <div class="btn-group">
                        <button data-toggle="modal" data-target="#directorModal" class="btn btn-sm btn-primary float-right"> <i class="ti-plus mr-2"></i> Add New FAQ</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                    {!! session()->get('success') !!}
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-warning alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                    {!! session()->get('error') !!}
                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                    </button>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Default accordion -->
                                <div id="accordion-one" class="accordion accordion-primary">
                                    @php $index = 1; @endphp
                                    @foreach($faqs as $faq)
                                    <div class="accordion__item">
                                        <div class="accordion__header rounded-lg" data-toggle="collapse" data-target="#default_collapse_{{$faq->id}}">
                                            <span class="accordion__header--text"> <label for="" class="badge badge-danger" style="border-radius: 50%;">{{$index}}</label> {{$faq->question ?? '' }} </span>
                                            <span class="accordion__header--indicator"></span>
                                        </div>
                                        <div id="default_collapse_{{$faq->id}}" class="collapse accordion__body {{$index == 1 ? 'show' : '' }}" data-parent="#accordion-one">
                                            <div class="accordion__body--text">
                                                {!! $faq->answer ?? '' !!}
                                                <p class="text-right" style="cursor: pointer;" data-toggle="modal" data-target="#directorModal_{{$faq->id}}"><i class="ti-pencil text-warning text-right"></i></p>

                                                <div class="modal fade" id="directorModal_{{$faq->id}}">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"> <i class="ti-help mr-3"></i> Edit FAQ</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{route('edit-faq')}}" method="post" autocomplete="off">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="">Question <sup class="text-danger">*</sup></label>
                                                                                <input type="text" name="question" value="{{$faq->question ?? ''}}" placeholder="Question" class="form-control">
                                                                                @error('question')
                                                                                <i class="text-danger">{{$message}}</i>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="">Answer <sup class="text-danger">*</sup></label>
                                                                                <textarea class="form-control content" name="answer" placeholder="Type answer here..." id="" style="resize:none;">{!! $faq->answer ?? '' !!}</textarea>
                                                                                @error('answer')
                                                                                <i class="text-danger">{{$message}}</i>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer d-flex justify-content-center">
                                                                    <input type="hidden" name="faq" value="{{$faq->id}}">
                                                                    <button type="button" class="btn btn-danger light btn-sm" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" value="{{$index++}}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="directorModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> <i class="ti-help mr-3"></i> Add New FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <form action="{{route('faqs')}}" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Question <sup class="text-danger">*</sup></label>
                                    <input type="text" name="question" value="{{old('question')}}" placeholder="Question" class="form-control">
                                    @error('question')
                                    <i class="text-danger">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Answer <sup class="text-danger">*</sup></label>
                                    <textarea class="form-control content" name="answer" placeholder="Type answer here..." id="question" style="resize:none;">{{old('answer')}}</textarea>
                                    @error('answer')
                                    <i class="text-danger">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-danger light btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="/bower_components/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/bower_components/tinymce.js"></script>
@endsection

