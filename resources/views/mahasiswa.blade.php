<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page Mahasiswa</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('inisma/logo.ico')}}" />
  @include('pagemhs.template.head')
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
   @include('pagemhs.template.sidebar')
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
    @include('pagemhs.template.header')
      <div class="container-fluid">
        <!--  Row 1 -->
        @yield('content')

        @include('pagemhs.template.footer')
      </div>
    </div>
  </div>
  @include('pagemhs.template.scripts')
</body>

</html>