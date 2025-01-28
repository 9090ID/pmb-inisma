<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('inisma/logo.ico')}}" />
  <link rel="stylesheet" href="{{asset('mahasiswa/css/styles.min.css')}}" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="/register" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="{{asset('inisma/logo.webp')}}" width="80" alt="">
                </a>
                <p class="text-center">Halaman Register</p>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" id="exampleInputtext1" aria-describedby="textHelp">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email Address</label>
                    <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter new password">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="ulangi password">
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign Up</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                    <a class="text-primary fw-bold ms-2" href="/login">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{asset('mahasiswa/libs/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('mahasiswa/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
</body>

</html>