<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title')</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('landingpage.template.metadata')
  <!-- Favicons -->
  <link href="{{asset('inisma/logo.ico')}}" rel="icon">
  <link href="{{asset('home/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!--head-->
  @include('landingpage.template.head')
  
</head>
<body class="index-page">

    <!-- ======= Header ======= -->
    @include('landingpage.template.header')
    <!-- End Header -->
    <!--Main-->
    @yield('content')
    <!--end main-->

  <!--footer-->
  @include('landingpage.template.footer')
    <!--end footer-->

   
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>
  <!-- Vendor JS Files -->
  @include('landingpage.template.scripts')
  @stack('scripts')

</body>
</html>