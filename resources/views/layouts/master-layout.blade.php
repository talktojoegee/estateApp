@include('partials._header')
@php
    $ground = '/assets/drive/wallpapers/'.Auth::user()->getUsersWallpaper->filename ?? null;
@endphp
<body data-sidebar="dark" style="background-size:cover; background-image: url('{{$ground}}') !important;  ">
<style>
    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{
        color: {{Auth::user()->getUsersWallpaper->caption_color ?? '#ffffff'}} !important;
        /*color: #ccc !important;*/
    }
    #sidebar-menu ul li a i, body[data-sidebar=dark] #sidebar-menu ul li a{
        color: {{Auth::user()->getUsersWallpaper->text_color ?? '#ffffff'}} !important;
    }
    body[data-sidebar=dark] .menu-title{
        color: {{Auth::user()->getUsersWallpaper->text_color ?? '#ffffff'}} !important;
    }
</style>

<div >
    <div id="layout-wrapper" >
        @include('partials._admin-top-bar')
        <div class="vertical-menu" style="background: transparent !important;">
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
