@include('partials._header')

<body data-sidebar="dark" >


<div >
    <div id="layout-wrapper" >
        @include('partials._admin-top-bar')
        <div class="vertical-menu" style="background: #01204D !important;">
            <div data-simplebar class="h-100">
                @include('partials._admin-sidebar')
            </div>
        </div>
        <!--  #1916FC-->
        <div class="main-content" >
            <div class="page-content" style="min-height: 900px !important;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18 text-uppercase">@yield('current-page')</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item "><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">@yield('current-page')</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>

                    @yield('main-content')
                </div>
            </div>
            @include('partials._footer')
        </div>

    </div>
</div>

@yield('right-sidebar')
@include('partials._footer-scripts')
</body>
</html>
