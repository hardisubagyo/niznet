<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Niznet</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- <link rel="icon" href="{{ asset('public/login/img/logo.ico') }}"> --}}
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/login/img/niznet2.png') }}"/>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/temp/dist/css/adminlte.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/summernote/summernote-bs4.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('public/temp/plugins/toastr/toastr.min.css') }}">

  <!-- jQuery -->
  <script src="{{ asset('public/temp/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('public/temp/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('public/temp/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('public/temp/plugins/summernote/summernote-bs4.css') }}">

  <link rel="stylesheet" href="{{ asset('public/temp/plugins/ekko-lightbox/ekko-lightbox.css') }}">

  <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

  <script src="{{ asset('public/temp/plugins/chart.js/Chart.min.js') }}"></script>

  <script src="{{ asset('public/temp/plugins/apexcharts/apexcharts.js') }}"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/home') }}" class="brand-link">
      <img src="{{ asset('public/login/img/niznet2.png') }}"
           alt="HappySelling.id"
           class="brand-image elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Niznet</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ url('/home') }}" class="nav-link">
              <i class="nav-icon fas fa-home"></i><p>Beranda</p>
            </a>
          </li>
          @if(Auth::user()->id_level == 1)
          <li class="nav-item">
            <a href="{{ route('User.index') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i><p>Pengguna</p>
            </a>
          </li>
          @else
          @endif
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if(Auth::user()->id_level == 1)
              <li class="nav-item">
                <a href="{{ route('Kategori.index') }}" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Master Kategori</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('Variasi.index') }}" class="nav-link">
                  <i class="fas fa-list-ol nav-icon"></i>
                  <p>Master Variasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('Merk.index') }}" class="nav-link">
                  <i class="fas fa-th-list nav-icon"></i>
                  <p>Master Merk</p>
                </a>
              </li>
              @else
              @endif
              <li class="nav-item">
                <a href="{{ route('Produk.index') }}" class="nav-link">
                  <i class="fas fa-clipboard-list nav-icon"></i>
                  <p>Master Produk</p>
                </a>
              </li>
              @if(Auth::user()->id_level == 1)
              <li class="nav-item">
                <a href="{{ route('Toko.index') }}" class="nav-link">
                  <i class="fas fa-store nav-icon"></i>
                  <p>Master Toko</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('Bank.index') }}" class="nav-link">
                  <i class="fas fa-university nav-icon"></i>
                  <p>Master Bank</p>
                </a>
              </li>
              @else
              @endif
            </ul>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ route('Cart.index') }}" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i><p>Keranjang</p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="{{ route('Pesanan.index') }}" class="nav-link">
              <i class="nav-icon fas fa-clipboard-check"></i><p>Pesanan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('Laporan.index') }}" class="nav-link">
              <i class="nav-icon far fa-chart-bar"></i><p>Laporan</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        @yield('content')
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Niznet &copy; 2020</strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- SweetAlert2 -->
<script src="{{ asset('public/temp/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('public/temp/plugins/toastr/toastr.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('public/temp/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('public/temp/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/temp/dist/js/adminlte.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('public/temp/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('public/temp/plugins/sparklines/sparkline.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('public/temp/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('public/temp/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('public/temp/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('public/temp/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('public/temp/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('public/temp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<script src="{{ asset('public/temp/plugins/summernote/summernote-bs4.min.js') }}"></script>

<script src="{{ asset('public/temp/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>

<!-- bs-custom-file-input -->
<script src="{{ asset('public/temp/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script>
  $(document).ready(function () {
    bsCustomFileInput.init();
  });
  
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });
  })
</script>

</body>
</html>
