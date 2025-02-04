<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>{{config('app.name')}} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Your effective tool for property management and other key assignments" name="description" />
    <meta content="{{config('app.name')}}" name="PoweredBy" />
    <link rel="shortcut icon" href="/assets/drive/logo/favicon.png">
    <link href="{{asset('css/parsley.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        #sidebar-menu ul li a i, body[data-sidebar=dark] #sidebar-menu ul li a{
            color: #ffffff !important;
        }
    </style>

    @yield('extra-styles')

</head>
