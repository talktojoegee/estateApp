
@extends('layouts.master-layout')
@section('current-page')
    Engagement Dashboard
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .bg-primary{
            background: #01204D !important;
        }
    </style>
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
                       @include('followup.partial._top-navigation')
                            <div class="row mt-5">
                                <div class="col-md-12 col-xxl-12 col-sm-12">
                                    <p>Currently showing performance report of @if($search == 0)<code>3 months ago.</code> Between <code>{{ date('d M, Y', strtotime("-90 days")) }}</code> to <code>{{ date('d M, Y', strtotime(now())) }}</code> @else
                                            <span><strong class="text-success">From:</strong> {{date('d M, Y', strtotime($from))}}</span>
                                            <span><strong class="text-danger">To:</strong> {{date('d M, Y', strtotime($to))}}</span>
                                        @endif</p>
                                </div>
                                <div class="col-xxl-4 col-md-4 col-sm-4">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white" >Select</div>
                                        <div class="card-header m-4">
                                            <form action="{{route('marketing-dashboard-filter')}}" class="" method="get">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="" class="text-muted">Filter</label>
                                                    <select name="filterType" id="filterType" class="form-control">
                                                        <option value="1">All</option>
                                                        <option value="2">Date Range</option>
                                                    </select>
                                                    @error('filterType') <i class="text-danger">{{$message}}</i> @enderror
                                                </div>
                                                <div class="form-group mt-3 dateInputs">
                                                    <label for="" class="text-success">From</label>
                                                    <input type="date" class="form-control" name="from" id="from">
                                                    @error('from') <i class="text-danger">{{$message}}</i> @enderror
                                                </div>
                                                <div class="form-group mt-3 dateInputs">
                                                    <label for="" class="text-danger">To</label>
                                                    <input type="date" class="form-control" name="to" id="to">
                                                    @error('to') <i class="text-danger">{{$message}}</i> @enderror
                                                </div>
                                                <div class="mt-3 form-group d-flex justify-content-center">
                                                    <button class="btn btn-primary btn-sm" id="submit">Submit <i class="bx bx-filter"></i> </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-8 col-md-8 col-sm-8">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white" >Invoice, Sales Performance Chart</div>
                                        <div class="card-body">
                                            <p><strong>Note:</strong> Below you'll find a comparison chart. Plotting the total sum of invoice issued to customers against payment done.</p>
                                            <div id="attendanceMedication" class="apex-charts" dir="ltr"></div>
                                            <div class="table-responsive mt-3" id="invoiceSalesTable">
                                                <table class="table table-bordered mb-0">
                                                    <thead class="table-light" id="headers">
                                                    <tr ></tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class=" text-white" style="background: #0171C1 !important;" id="trInvoice"></tr>
                                                    <tr class="text-white" style="background: #FE3A6A !important;" id="trSales"></tr>
                                                    <tr class="text-white" style="background: #EBBC18 !important;" id="trLead"></tr>
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
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white" >Top 10 Selling Estates</div>
                    <div class="card-body">
                        <p>Showing top 10 selling estates @if($search == 0)<code>3 months ago.</code> Between <code>{{ date('d M, Y', strtotime("-90 days")) }}</code> to <code>{{ date('d M, Y', strtotime(now())) }}</code> @else
                                <span><strong class="text-success">From:</strong> {{date('d M, Y', strtotime($from))}}</span>
                                <span><strong class="text-danger">To:</strong> {{date('d M, Y', strtotime($to))}}</span>
                            @endif</p>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th class="align-middle">S/No.</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Quantity</th>
                                    <th class="align-middle" style="text-align: right;">Amount({{env('APP_CURRENCY')}})</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($topSelling) > 0)
                                @foreach($topSelling as $key => $record)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="{{route('show-estate-view', $record->e_slug)}}" class="text-info fw-bold">{{$record->e_name ?? '' }}</a> </td>
                                        <td>{{ number_format($record->estate_count ?? 0) }}</td>
                                        <td style="text-align: right;">{{ number_format($record->amount ?? 0) }}</td>
                                        <td>
                                            <a href="{{route('show-estate-view', $record->e_slug)}}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" >
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @else

                                    <tr>
                                        <td colspan="5" class="text-center">No data found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header text-white" style="background: #FF0000 !important;" >10 Under-performing Estates in Terms of Sales</div>
                    <div class="card-body">
                        <p>Showing top 10 under-performing estates @if($search == 0)<code>3 months ago.</code> Between <code>{{ date('d M, Y', strtotime("-90 days")) }}</code> to <code>{{ date('d M, Y', strtotime(now())) }}</code> @else
                                <span><strong class="text-success">From:</strong> {{date('d M, Y', strtotime($from))}}</span>
                                <span><strong class="text-danger">To:</strong> {{date('d M, Y', strtotime($to))}}</span>
                            @endif</p>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th class="align-middle">S/No.</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Quantity</th>
                                    <th class="align-middle" style="text-align: right;">Amount({{env('APP_CURRENCY')}})</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($underperforming) > 0)
                                    @foreach($underperforming as $key => $under)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><a href="{{route('show-estate-view', $under->e_slug)}}" class="text-info fw-bold">{{$under->e_name ?? '' }}</a> </td>
                                            <td>{{ number_format($under->estate_count ?? 0) }}</td>
                                            <td style="text-align: right;">{{ number_format($under->amount ?? 0) }}</td>
                                            <td>
                                                <a href="{{route('show-estate-view', $under->e_slug)}}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" >
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                @else

                                    <tr>
                                        <td colspan="5" class="text-center">No data found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white" >Properties Sold in the Last 30 Days</div>
                    <div class="card-body">
                        <p>Showing properties sold in the last <code>30 days.</code> Between <code>{{ date('d M, Y', strtotime("-30 days")) }}</code> to <code>{{ date('d M, Y', strtotime(now())) }}</code>
                            </p>
                        <div class="table-responsive">
                            <table id="datatable" class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th class="align-middle">S/No.</th>
                                    <th class="align-middle">Ref. Code</th>
                                    <th class="align-middle">Name</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($last30Properties) > 0)
                                    @foreach($last30Properties as $key => $prop)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><a href="{{route('show-property-details', $prop->slug)}}" class="text-info fw-bold">{{$prop->property_code ?? '' }}</a> </td>
                                            <td><a href="{{route('show-property-details', $prop->slug)}}" class="text-info fw-bold">{{$prop->property_name ?? '' }}</a> </td>
                                            <td>
                                                <a href="{{route('show-property-details', $prop->slug)}}" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" >
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                @else

                                    <tr>
                                        <td colspan="5" class="text-center">No data found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white" >Top 10 employees with the Most Sales</div>
                    <div class="card-body">
                        <p>Showing top 10 employees with the most sales @if($search == 0)<code>3 months ago.</code> Between <code>{{ date('d M, Y', strtotime("-90 days")) }}</code> to <code>{{ date('d M, Y', strtotime(now())) }}</code> @else
                                <span><strong class="text-success">From:</strong> {{date('d M, Y', strtotime($from))}}</span>
                                <span><strong class="text-danger">To:</strong> {{date('d M, Y', strtotime($to))}}</span>
                            @endif</p>
                        <div class="table-responsive mt-3">
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="wd-15p">Name</th>
                                    <th class="wd-15p">Mobile No.</th>
                                    <th class="wd-15p">Quantity</th>
                                    <th class="wd-15p">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $index = 1; @endphp
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$index++}}</td>
                                        <td>
                                            <img src="{{url('storage/'.$user->image)}}" style="width: 24px; height: 24px;" alt="{{$user->first_name ?? '' }} {{$user->last_name ?? '' }}" class="rounded-circle avatar-sm">
                                            <a href="{{route('user-profile', $user->slug)}}">{{$user->title ?? '' }} {{$user->first_name ?? '' }} {{$user->last_name ?? '' }}</a> </td>
                                        <td>{{$user->cellphone_no ?? '' }} </td>
                                        <td>{{ number_format($user->sales_count ?? 0)  }} </td>

                                        <td>
                                            <div class="btn-group">
                                                <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{route('user-profile', $user->slug)}}"> <i class="bx bxs-user"></i> View Profile</a>
                                                </div>
                                            </div>
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



@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

    <script src="/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="/vectormap/jquery-jvectormap-in-mill.js"></script>
    <script src="/vectormap/jquery-jvectormap-us-aea-en.js"></script>
    <script src="/vectormap/jquery-jvectormap-uk-mill-en.js"></script>
    <script src="/vectormap/jquery-jvectormap-au-mill.js"></script>

    <script src="/assets/js/axios.min.js"></script>
    <script src="/js/chart.js"></script>
    <script>
        const incomeData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const expenseData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const smsData = [0,0,0,0,0,0,0,0,0,0,0,0];

        //const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const leadsData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const salesData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const invoiceData = [0,0,0,0,0,0,0,0,0,0,0,0];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const search = "{{$search}}";
        //const url = parseInt(search) === 0 ? "{{route('revenue-statistics') }}" : "{{route('revenue-statistics-range')}}";
        let url2 = "{{route('followup-dashboard-chart') }}";
        $(document).ready(function(){
            $('.dateInputs').hide();
            $('#filterType').on('change', function(e){
                e.preventDefault();
                if($(this).val() == 1){
                    $('.dateInputs').hide();
                }else{
                    $('.dateInputs').show();
                }
            });



            /** Comparison chart[Invoice,Sales,Customers] **/
            const urlParams = new URLSearchParams(window.location.search);
            // Extract specific parameters
            let from = urlParams.get('from'); // Get the 'from' parameter
            let to = urlParams.get('to');
            const params = {
                from,
                to,
            };
            handleRequest(url2, params)

        });


        function handleRequest(url, params){
            axios.get(url2, {params:params})
                .then(res=> {
                    //console.log(res.data);
                    const invoice = res.data.invoice;
                    const sales = res.data.sales;
                    const leads = res.data.leads;


                    invoice.map((m) => {
                        switch (m.month) {
                            case 1:
                                plotAttendanceMedicationGraph(1, 1, m.total);
                                break;
                            case 2:
                                plotAttendanceMedicationGraph(2, 1, m.total);
                                break;
                            case 3:
                                plotAttendanceMedicationGraph(3, 1, m.total);
                                break;
                            case 4:
                                plotAttendanceMedicationGraph(4, 1, m.total);
                                break;
                            case 5:
                                plotAttendanceMedicationGraph(5, 1, m.total);
                                break;
                            case 6:
                                plotAttendanceMedicationGraph(6, 1, m.total);
                                break;
                            case 7:
                                plotAttendanceMedicationGraph(7, 1, m.total);
                                break;
                            case 8:
                                plotAttendanceMedicationGraph(8, 1, m.total);
                                break;
                            case 9:
                                plotAttendanceMedicationGraph(9, 1, m.total);
                                break;
                            case 10:
                                plotAttendanceMedicationGraph(10, 1, m.total);
                                break;
                            case 11:
                                plotAttendanceMedicationGraph(11, 1, m.total);
                                break;
                            case 12:
                                plotAttendanceMedicationGraph(12, 1, m.total);
                                break;
                        }

                    });

                    leads.map((w) => {
                        switch (w.month) {
                            case 1:
                                plotAttendanceMedicationGraph(1, 2, w.total);
                                break;
                            case 2:
                                plotAttendanceMedicationGraph(2, 2, w.total);
                                break;
                            case 3:
                                plotAttendanceMedicationGraph(3, 2, w.total);
                                break;
                            case 4:
                                plotAttendanceMedicationGraph(4, 2, w.total);
                                break;
                            case 5:
                                plotAttendanceMedicationGraph(5, 2, w.total);
                                break;
                            case 6:
                                plotAttendanceMedicationGraph(6, 2, w.total);
                                break;
                            case 7:
                                plotAttendanceMedicationGraph(7, 2, w.total);
                                break;
                            case 8:
                                plotAttendanceMedicationGraph(8, 2, w.total);
                                break;
                            case 9:
                                plotAttendanceMedicationGraph(9, 2, w.total);
                                break;
                            case 10:
                                plotAttendanceMedicationGraph(10, 2, w.total);
                                break;
                            case 11:
                                plotAttendanceMedicationGraph(11, 2, w.total);
                                break;
                            case 12:
                                plotAttendanceMedicationGraph(12, 2, w.total);
                                break;
                        }

                    });

                    sales.map((c) => {
                        switch (c.month) {
                            case 1:
                                plotAttendanceMedicationGraph(1, 3, c.total);
                                break;
                            case 2:
                                plotAttendanceMedicationGraph(2, 3, c.total);
                                break;
                            case 3:
                                plotAttendanceMedicationGraph(3, 3, c.total);
                                break;
                            case 4:
                                plotAttendanceMedicationGraph(4, 3, c.total);
                                break;
                            case 5:
                                plotAttendanceMedicationGraph(5, 3, c.total);
                                break;
                            case 6:
                                plotAttendanceMedicationGraph(6, 3, c.total);
                                break;
                            case 7:
                                plotAttendanceMedicationGraph(7, 3, c.total);
                                break;
                            case 8:
                                plotAttendanceMedicationGraph(8, 3, c.total);
                                break;
                            case 9:
                                plotAttendanceMedicationGraph(9, 3, c.total);
                                break;
                            case 10:
                                plotAttendanceMedicationGraph(10, 3, c.total);
                                break;
                            case 11:
                                plotAttendanceMedicationGraph(11, 3, c.total);
                                break;
                            case 12:
                                plotAttendanceMedicationGraph(12, 3, c.total);
                                break;
                        }

                    });

                    populateTable();
                    //then
                    const options2 = {
                            chart: { height: 360, type: "bar", toolbar: { show: !1 }, zoom: { enabled: !0 } },
                            plotOptions: { bar: { horizontal: false, columnWidth: "55%", endingShape: "rounded" } },
                            dataLabels: { enabled: !1 },
                            stroke: {
                                show: true,
                                width: 2,
                                colors: ['transparent']
                            },
                            series: [
                                { name: "Invoice", data: invoiceData },
                                { name: "Sales", data: salesData },
                                 { name: "Customers", data: leadsData },
                            ],
                            xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"] },
                            yaxis: {
                                title: {
                                    text: 'Invoice vs Sales'
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: function (val) {
                                        return val.toLocaleString()
                                    }
                                }
                            },
                            colors: ["#0071C1", "#FE3A6B", '#EBBC1A'],
                            legend: { position: "bottom" },
                            fill: { opacity: 1 },
                        },
                        chart = new ApexCharts(document.querySelector("#attendanceMedication"), options2);
                    chart.render();

                });
        }

        function plotGraph(index,type, value){
            if(parseInt(type) === 1){
                incomeData[index-1] = value;
            }
        }

        function populateTable() {
            // Clear any existing rows
            $('#dynamic-table tbody').empty();
            $('#headers tr').empty();
            $.each(months, function(index, val){
                let tr = `<td style="text-align: right;">${val}</th>`;
                $('#headers tr').append(tr);
            });

            // Loop through data and append rows to the table body
            $.each(invoiceData, function(index, item) {
                let row = `<td style="text-align: right;">${item.toLocaleString()}</td>`;
                $('#trInvoice').append(row);
            });
            $.each(salesData, function(index, item) {
                let salesRow = `<td style="text-align: right;">${item.toLocaleString()}</td>`;
                $('#trSales').append(salesRow);
            });

            $.each(leadsData, function(index, lead) {
                let leadRow = `<td style="text-align: right;">${lead.toLocaleString()}</td>`;
                $('#trLead').append(leadRow);
            });
        }

        function plotAttendanceMedicationGraph(index,type, value){
            if(parseInt(type) === 1){
                invoiceData[index-1] = value;
            }else if(parseInt(type) === 2){
                leadsData[index-1] = value;
            }else{
                salesData[index-1] = value;
            }
        }
    </script>
@endsection
