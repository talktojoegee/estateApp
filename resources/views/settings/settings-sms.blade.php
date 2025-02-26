@extends('layouts.master-layout')
@section('current-page')
    SMS Settings
@endsection
@section('title')
    SMS Settings
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
                                <h6 class="modal-header text-white text-uppercase">SMS Settings</h6>
                                <div class="pt-4">
                                    <p><strong class="text-danger">Note: </strong> These messages will be sent automatically for the various scheduled operations.</p>
                                    <form action="{{ route('sms-settings') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">SMS for <strong>payment receipt</strong></label>
                                                    <textarea name="new_licence_sms" id="new_licence_sms" maxlength="160" placeholder="Compose SMS message for new licence application (Acknowledgement)" style="resize: none;" rows="5"
                                                              class="form-control">{{old('new_licence_sms', $app_sms_setting->new_licence_sms ?? '')}}</textarea>
                                                    @error('new_licence_sms')
                                                    <i class="text-danger mt-2">{{$message}}</i>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">SMS for payment <strong>(Reminder)</strong></label>
                                                    <textarea name="licence_renewal_sms" id="licence_renewal_sms" maxlength="160" placeholder="Compose SMS message for licence renewal (Reminder)" style="resize: none;" rows="5"
                                                              class="form-control">{{old('licence_renewal_sms', $app_sms_setting->licence_renewal_reminder_sms ?? '')}}</textarea>
                                                    @error('licence_renewal_sms')
                                                    <i class="text-danger mt-2">{{$message}}</i>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">SMS for payment <strong>(Acknowledgment)</strong></label>
                                                    <textarea name="licence_renewal_sms_ack" id="licence_renewal_sms_ack" maxlength="160" placeholder="Compose SMS message for licence renewal (Acknowledgement)" style="resize: none;" rows="5"
                                                              class="form-control">{{old('licence_renewal_sms_ack', $app_sms_setting->licence_renewal_sms ?? '')}}</textarea>
                                                    @error('licence_renewal_sms_ack')
                                                    <i class="text-danger mt-2">{{$message}}</i>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-center">
                                                <button class="btn btn-primary btn-sm" type="submit">Submit</button>
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
    </div>

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
        });
    </script>

@endsection
