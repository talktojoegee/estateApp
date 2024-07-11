@extends('layouts.master-layout')
@section('title')
    Certificate
@endsection
@section('current-page')
    Certificate
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
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

                            <div class="mb-5" id="printArea">
                                <div class="row">
                                    <div class="row mt-2">
                                        <div class="col-xl-10 col-sm-10 col-md-10 text-center text-black">
                                            <div style="margin-left: 200px !important;">
                                                <p class="text-uppercase "><strong>Wireless Telegraphy Act, 1961</strong></p>
                                                <a href="#" class="brand-logo">
                                                    <img style="width: 92px; height: 92px; margin: 0px auto" class="logo-abbr" src="/assets/images/arm_.png" alt="">
                                                </a>
                                                <p class="text-uppercase mt-2"><strong>Federal Republic of Nigeria</strong></p>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-sm-2 text-center text-black d-flex justify-content-end">
                                            <p class="text-uppercase mt-5"><strong>NO:{{$certificate->license_no ?? '' }}/{{strtoupper($certificate->getCategory->getParentCategory->abbr)}}/{{date('Y', strtotime($certificate->start_date))}}</strong></p>
                                        </div>
                                        <div class="col-xl-12 col-sm-12 text-center text-black d-flex justify-content-end">
                                            <p class="text-uppercase"><strong>NO:{{$certificate->form_serial_no ?? '' }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 text-black">
                                        <h6 class="text-center mb-2"> <span class="text-uppercase font-w800">{{$certificate->getCategory->getParentCategory->name ?? '' }} Station License</span></h6>
                                        <p class="text-center font-w800">Form No. 19(B)</p>
                                        <h6 class="text-center text-uppercase">"{{$certificate->make ?? '' }}" ({{$certificate->call_sign ?? '' }})</h6>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <p><strong>Date: </strong> <span style="display:inline-block; border-bottom:1px solid black;padding-bottom:2px;">{{date('d/m/Y', strtotime($certificate->start_date))}} - {{date('d/m/Y', strtotime($certificate->expires_at))}}</span></p>
                                        <p class="" style="margin-top: -5px;"><strong>Renewal: </strong>{{env('APP_CURRENCY')}}{{number_format($certificate->getPost->p_amount ?? 0,2)}}</p>
                                        <p class="" style="margin-top: -15px;"><strong>Fee on Issue: </strong>{{env('APP_CURRENCY')}}{{number_format($certificate->getPost->p_amount ?? 0,2)}}</p>
                                        <ol >
                                            <li>Licensee <strong>{{strtoupper($certificate->getCompany->organization_name ?? '')}}</strong> of <strong>{{strtoupper($certificate->getCompany->address ?? '')}}</strong> is hereby licensed in accordance with particulars on the attached conditions.</li>
                                            <li>The special conditions governing the licence are Regulations 8,10,11,15,19,20 and 21</li>
                                        </ol>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                    @if(!empty($finalPerson->signature))
                                                    <p class="d-flex justify-content-center">
                                                        <img src="{{url('storage/'.$finalPerson->signature) }}" height="75" width="150" alt="signature">
                                                    </p>
                                                    <p class="d-flex justify-content-center">
                                                        <strong>Digitally signed by: </strong> &nbsp;  {{$finalPerson->title ?? null}} {{$finalPerson->first_name ?? null}} {{$finalPerson->last_name ?? null }} {{$finalPerson->other_names ?? null }}
                                                        <br> for. Honourable Minister
                                                    </p>
                                                    @else
                                                        <p class="text-center" style="color: #ff0000;">Missing signature</p>
                                                    @endif

                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                @if(empty($lastForwarder))
                                                    @if(!empty($finalPerson->signature))
                                                        <p class="d-flex justify-content-center">
                                                            <img src="{{url('storage/'.$finalPerson->signature) }}" height="75" width="150" alt="signature">
                                                        </p>
                                                        <p class="d-flex justify-content-center">
                                                            {{$finalPerson->title ?? null}} {{$finalPerson->first_name ?? null}} {{$finalPerson->last_name ?? null }} {{$finalPerson->other_names ?? null }}
                                                            an officer of the Ministry of Communications duly authorised in that behalf
                                                        </p>
                                                    @else
                                                        <p class="text-center" style="color: #ff0000;">Missing signature</p>
                                                    @endif

                                                @else
                                                    @if(!empty($lastForwarder->signature))
                                                        <p class="d-flex justify-content-center">
                                                            <img src="{{url('storage/'.$lastForwarder->signature) }}" height="75" width="150" alt="signature">
                                                        </p>
                                                        <p class="d-flex justify-content-center">
                                                            <strong>{{$lastForwarder->title ?? null}} {{$lastForwarder->first_name ?? null}} {{$lastForwarder->last_name ?? null }} {{$lastForwarder->other_names ?? null }}</strong>
                                                            &nbsp; an officer of the Ministry of Communications duly authorised in that behalf
                                                        </p>
                                                    @else
                                                        <p class="d-flex justify-content-center" style="color: #ff0000;">Missing signature</p>
                                                    @endif

                                                @endif



                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <h6 class="text-center">{{ strtoupper($certificate->getCategory->getParentCategory->name) ?? '' }} STATION LICENCE</h6>
                                        <h6 class="text-center">THE CONDITIONS</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered p-0">
                                                <tr>
                                                    <td><strong>State:</strong> REG. NO. 1062</td>
                                                    <td><strong>Area:</strong>
                                                        @switch($certificate->band)
                                                            @case(1)
                                                            MF/HF
                                                            @break
                                                            @case(2)
                                                            VHF
                                                            @break
                                                            @case(3)
                                                            UHF
                                                            @break
                                                            @case(4)
                                                            SHF
                                                            @break
                                                            @endswitch
                                                        {{strtoupper($certificate->getCategory->getParentCategory->name)}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><strong>Name and Address of Licensee:</strong> {{strtoupper($certificate->getCompany->organization_name ?? '')}}, {{strtoupper($certificate->getCompany->address ?? '')}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"> <strong>Category:</strong> {{$certificate->getCategory->category_name ?? '' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                         <div class="table-responsive">
                                             <table class="table table-bordered mb-0">

                                                 <thead>
                                                 <tr>
                                                     <th>Call Sign</th>
                                                     <th>Max. Freq. & Tolerance{{--(Paragraph D applies)--}}</th>
                                                     <th>Bandwidth of Emission{{--(Paragraph D applies)--}}</th>
                                                     <th>Max. Effective Radiated Power(Watts){{--(Paragraphs A & B apply)--}}</th>
                                                     <th>Aerial Characteristics{{--(Paragraphs A & B apply)--}}</th>
                                                 </tr>
                                                 </thead>
                                                 <tbody>
                                                 <tr>
                                                     <td><strong class="text-uppercase">{{$certificate->call_sign ?? '' }}</strong></td>
                                                     <td><strong class="text-uppercase">{{$certificate->max_freq_tolerance ?? '' }}</strong></td>
                                                     <td><strong class="text-uppercase">{{$certificate->emission_bandwidth ?? '' }}</strong></td>
                                                     <td><strong class="text-uppercase">{{$certificate->max_effective_rad ?? '' }}</strong></td>
                                                     <td>{{$certificate->aerial_xtics ?? '' }}</td>
                                                 </tr>

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

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 d-flex justify-content-end pr-5">
            <button class="btn btn-warning" onclick="generatePDF()" type="button"> <i class="bx bx-printer text-white mr-2"></i> Print</button>
        </div>
    </div>


@endsection

@section('extra-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF(){
            var element = document.getElementById('printArea');
            html2pdf(element,{
                margin:       10,
                filename:     "Licence"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>


@endsection
