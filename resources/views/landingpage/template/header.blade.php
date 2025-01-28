<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="/" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
      <img src="{{asset('inisma/logo.webp')}}" alt="inisma">   
      <h1 class="sitename d-none d-sm-block">PMB Insitut Islam Muaro Jambi</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/#hero') }}" class="active">Home</a></li>
          <li><a href="{{ url('/#about') }}">Jadwal</a></li>
          <li><a href="{{ url('/#services') }}">Jalur Seleksi</a></li>
          <li><a href="{{ url('/#portofolio') }}">Biaya Studi</a></li>
          <li><a href="{{ url('/#team') }}">Fakultas</a></li>
          {{-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> --}}
          {{-- <li><a href="#contact">Daftar</a></li> --}}
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="cta-btn" href="/daftar-pmb">Daftar</a>

    </div>
  </header>