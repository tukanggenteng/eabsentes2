@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endpush
@section('body')
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="/home/pegawai" class="navbar-brand"><b>e-Absen</b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">

                  <li class="dropdown user user-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <img src="{{asset('dist/img/avatarumum.png')}}" class="user-image" alt="User Image">
                          <span class="hidden-xs">{{Auth::user()->name}}</span>
                      </a>
                      <ul class="dropdown-menu">
                          <!-- User image -->
                          <li class="user-header">
                              <img src="{{asset('dist/img/avatarumum.png')}}" class="img-circle" alt="User Image">

                              <p>
                                  {{Auth::user()->name}}
                                  <small>{{Auth::user()->instansi->namaInstansi}}</small>
                              </p>
                          </li>
                          <!-- Menu Body -->
                          <!-- Menu Footer-->
                          <li class="user-footer">
                              <div class="pull-left">
                                  <a href="/changepassword" class="btn btn-default btn-flat">Ubah Password</a>
                              </div>
                              <div class="pull-right">
                                  <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                              </div>
                          </li>
                      </ul>
                  </li>
              </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">

      <!-- Main content -->
      <section class="content">
      
                        <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ubah Password</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="/changepassword" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label>Email</label>
                                      <div class="input-group bootstrap-timepicker timepicker">
                                          <div class="input-group-addon">
                                              <i class="fa fa-key"></i>
                                          </div>
                                          <input id="email" name="email" class="form-control" type="text" value="{{Auth::user()->email}}">
                                      </div>
                                  </div>
                                    <div class="form-group">
                                        <label>Password Lama</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </div>
                                            <input id="password" name="password" class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Password Baru</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </div>
                                            <input id="passwordbaru" name="passwordbaru" class="form-control pull-right" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Konfirmasi Password</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-key"></i>
                                            </div>
                                            <input id="konfirmasipassword" name="konfirmasipassword" class="form-control pull-right" type="password">
                                        </div>
                                    </div>
                                    {{csrf_field()}}
                                    <!-- /.form-group -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                </div>
                            </div>


                        </form>
                        <!-- /.col -->
                        <!-- /.row -->
                    </div>
                </div>
                @if (count($errors)>0)
                <div class="col-xs-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                        @if($errors->has('password'))
                           Password lama tidak cocok !
                        @elseif($errors->has('konfirmasipassword'))
                            Konfirmasi password gagal !
                        @endif
                    </div>
                </div>
                @endif

                @if (Session::get('statuserror'))
                  <div class="col-xs-12">
                      <div class="alert alert-danger alert-dismissible">
                          <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                          {{ Session::get('statuserror')}}
                      </div>
                  </div>
                @endif

                @if (Session::get('statussucces'))
                  <div class="col-xs-12">
                      <div class="alert alert-success alert-dismissible">
                          <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                          {{ Session::get('statussucces')}}
                      </div>
                  </div>
                @endif
      
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>

          @include('layouts.footer')
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->

    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>


</body>

@endsection
