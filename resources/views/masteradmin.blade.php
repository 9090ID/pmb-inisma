<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>INISMA - Page Admin</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{asset('inisma/logo.ico')}}"
      type="image/x-icon"
    />
@include('pageadmin.template.head')
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
     @include('pageadmin.template.sidebar')
      <!-- End Sidebar -->

      <div class="main-panel">
        @include('pageadmin.template.header')
        </div>

        @yield('content')

        @include('pageadmin.template.footer')
      </div>

      <!-- Custom template | don't include it in your project! -->
      @include('pageadmin.template.custom')
      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    @include('pageadmin.template.scripts')
  </body>
</html>
