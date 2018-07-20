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

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
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


                <!-- hari ini -->
                <div class="row">
                        <div class="col-md-12">
                            <div class="box box-default collapsed-box">
                                <div class="box-header with-border">
                                <h3 class="box-title">Hari</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                                <a href="/detail/harian/absent">
                                                    <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>
                                                </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tanpa Kabar</span>
                                                    <span class="info-box-number">{{$tidakhadir}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/harian/sakit">
                                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Sakit</a></span>
                                                    <span class="info-box-number">{{$sakit}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/harian/ijin">
                                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Izin</span>
                                                    <span class="info-box-number">{{$ijin}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/harian/sakit">
                                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cuti</span>
                                                    <span class="info-box-number">{{$cuti}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>

                                    <div class="row">
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/tugasluar">
                                                    <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tugas Luar</span>
                                                        <span class="info-box-number">{{$tl}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/tugasbelajar">
                                                    <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tugas Belajar</span>
                                                        <span class="info-box-number">{{$tb}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->

                                            <!-- fix for small devices only -->
                                            <div class="clearfix visible-sm-block"></div>

                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/terlambat">
                                                    <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Terlambat</span>
                                                        <span class="info-box-number">{{$terlambat}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/rapatundangan">
                                                    <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Rapat/Undangan/Sosialisasi</span>
                                                        <span class="info-box-number">{{$event}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                </div>
                 <!-- hari ini -->                   

                <!-- bulan baru ini -->
                <div class="row">
                    
                        <div class="col-md-12">
                            <div class="box box-default collapsed-box">
                                <div class="box-header with-border">
                                <h3 class="box-title">Bulan</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">

                                            <a href="/detail/bulan/absent">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tanpa Kabar</span>
                                                    <span class="info-box-number">{{$tidakhadirbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                        <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/sakit">
                                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Sakit</span>
                                                    <span class="info-box-number">{{$sakitbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/ijin">
                                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Izin</span>
                                                    <span class="info-box-number">{{$ijinbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/cuti">
                                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cuti</span>
                                                    <span class="info-box-number">{{$cutibulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/tugasluar">
                                                <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Luar</span>
                                                    <span class="info-box-number">{{$tlbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/tugasbelajar">
                                                <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Belajar</span>
                                                    <span class="info-box-number">{{$tbbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
            
                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>
            
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/terlambat">
                                                <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Terlambat</span>
                                                    <span class="info-box-number">{{$terlambatbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/rapatundangan">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Rapat/Undangan/Sosialisasi</span>
                                                    <span class="info-box-number">{{$eventbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                            <!-- /.col -->
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                </div>

                <!-- tahun baru -->
                <div class="row">
                        <div class="col-md-12">
                            <div class="box box-default collapsed-box">
                                <div class="box-header with-border">
                                <h3 class="box-title">Tahun</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/absent">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tanpa Kabar</span>
                                                    <span class="info-box-number">{{$tidakhadirtahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/sakit">
                                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Sakit</span>
                                                    <span class="info-box-number">{{$sakittahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/ijin">
                                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Izin</span>
                                                    <span class="info-box-number">{{$ijintahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/cuti">
                                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cuti</span>
                                                    <span class="info-box-number">{{$cutitahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/tugasluar">
                                                <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Luar</span>
                                                    <span class="info-box-number">{{$tltahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/tugasbelajar">
                                                <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Belajar</span>
                                                    <span class="info-box-number">{{$tbtahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/terlambat">
                                                <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Terlambat</span>
                                                    <span class="info-box-number">{{$terlambattahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/rapatundangan">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Rapat/Undangan/Sosialisasi</span>
                                                    <span class="info-box-number">{{$eventtahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                </div>
                <!-- tahun baru tutup -->

        
        <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header  with-border">
                    <h3 class="box-title">Grafik Absensi Instansi (Tahun)</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                          <div class="col-md-12">
                            <canvas id="containertahun"></canvas>
                          </div>
                    </div>
                </div>
              </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
              <div class="col-md-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Detail Absen</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive">
                    <table class="table table-striped">
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Absen Terlambat</th>
                        <th>Jam Masuk</th>
                        <th>Lokasi Absen Masuk</th>
                        <th>Mulai Istirahat</th>
                        <th>Selesai Istirahat</th>
                        <th>Jam Keluar</th>
                        <th>Lokasi Absen Keluar</th>
                        <th>Akumulasi</th>
                        <th>Keterangan</th>
                        <th>Jadwal Kerja</th>
                      </tr>
                      @foreach ($kehadirans as $key => $kehadiran)
                          <tr>
                            <td>{{$key+1}}</td>
                            <td><a href="/home/pegawai/detail/{{$kehadiran->nip}}">{{$kehadiran->nip}}</a></td>
                            <td>{{$kehadiran->nama}}</td>
                            @if ($kehadiran->terlambat=="00:00:00")
                            <td>{{$kehadiran->terlambat}}</td>
                            @else
                            <td><span class="badge bg-red">{{$kehadiran->terlambat}}</span></td>
                            @endif
                            <td>{{$kehadiran->jam_masuk}}</td>
                            <td>{{$kehadiran->namainstansimasuk}}</td>
                            <td>{{$kehadiran->keluaristirahat}}</td>
                            <td>{{$kehadiran->masukistirahat}}</td>
                            <td>{{$kehadiran->jam_keluar}}</td>
                            <td>{{$kehadiran->namainstansikeluar}}</td>
                            <td>{{$kehadiran->akumulasi_sehari}}</td>
                            @if ($kehadiran->jenis_absen=="Tanpa Kabar")
                            <td><span class="badge bg-red">{{$kehadiran->jenis_absen}}</span></td>
                            @else
                            <td>{{$kehadiran->jenis_absen}}</td>
                            @endif
                            <td>{{$kehadiran->jenis_jadwal}}</td>
                          </tr>
                      @endforeach
                    </table>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer clearfix">
                      <ul class="pagination pagination-sm no-margin pull-right">
                          {{$kehadirans->links()}}
                      </ul>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-6">
              <div class="box">
                  <div class="box-header">
                      <h3 class="box-title">Kehadiran Pegawai</h3>
                  </div>
                          <!-- /.box-header -->
                  <div class="box-body no-padding app" style="overflow:scroll;height:600px;width:100%;" id="app">
                      <ul class="timeline">
                                <!-- timeline item -->
                      <li v-for="att in atts">
                              <i class="fa fa-map-marker bg-blue" v-if="att.statusmasuk== 'hadir'"></i>
                              <i class="fa fa-map-marker bg-orange" v-if="att.statusmasuk== 'hadir terlambat'"></i>
                              <i class="fa fa-map-marker bg-yellow" v-if="att.statusmasuk== 'pulang lebih cepat'"></i>
                              <i class="fa fa-map-marker bg-green" v-if="att.statusmasuk== 'pulang'"></i>

                              {{-- <i class="fa fa-map-marker bg-green"></i> --}}
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> @{{ att.tanggal }}</span>

                                <h3 class="timeline-header">@{{ att.namaPegawai }} <small>dari @{{ att.instansiPegawai }}</small></h3>

                                <div class="timeline-body">
                                    Telah @{{ att.statusmasuk }} di <strong>@{{ att.namaInstansi }}</strong> pada jam @{{ att.jam }}
                                </div>

                            </div>
                        </li>
                        @include('timeline.datatimeline')
                    </ul>
                    <div class="ajax-load text-center" id="ajax-load" style="display:none">
                        <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
                    </div>
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>
              <input id="instansi_id" type="hidden" value="{{Auth::user()->instansi_id}}">

          <div class="col-md-6">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Pegawai Tidak Apel</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body  table-responsive">
                    <table class="table table-striped">
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Lama Terlambat</th>
                      </tr>
                      @foreach ($pegawaitahun as $key => $pegawai3)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td><a href="/home/pegawai/detail/{{$pegawai3->nip}}">{{$pegawai3->nip}}</a></td>
                          <td>{{$pegawai3->nama}}</td>
                          <td><span class="badge bg-light-blue">{{$pegawai3->terlambat}}</span></td>
                        </tr>
                      @endforeach


                    </table>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer clearfix">
                      <ul class="pagination pagination-sm no-margin pull-right">
                          {{$pegawaitahun->links()}}
                      </ul>
                  </div>
                </div>
              </div>
        </div>




      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>

          @include('layouts.footer')
</div>
<!-- ./wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->

<!-- Select2 -->
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- bootstrap color picker -->
<script src="{{asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
<script>
    var lineChartCanvas = document.getElementById('containertahun').getContext('2d')
    $(function () {

          var apel = new Array();
          var absen = new Array();
          var apel2 = new Array();
          var absen2 = new Array();
          var apel3 = new Array();
          var absen3 = new Array();
        //Initialize Select2 Elements
        $('#instansibulan').select2();
        $('#instansitahun').select2();

        $('input[name="periodetahun"]').datepicker({
            format: "yyyy",
            autoclose: true,
            minViewMode: "years"
          });
        var nip=($('#nip').val());

        var instansi_id=$('#instansi_id').val();

        var url = "{{url('/instansi/grafik')}}";
        
        console.log(instansi_id);

        $.get(url,{ instansi_id:instansi_id }, function(response) {
                    window.myBar = new Chart(lineChartCanvas, {
                          type: 'line',
                          data: {
                                  labels: response['datasets'],
                                  datasets: [
                                      {
                                          label: "Tanpa Kabar",
                                          data: response['tanpakabar'],
                                          backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                          borderColor: 'rgba(255,99,132,1)'
                                      },
                                      {
                                          label: "Hari Kerja",
                                          data: response['harikerja'],
                                          backgroundColor: 'rgba(30, 117, 7, 0)',
                                          borderColor: 'rgba(30, 117, 7, 1)'
                                      },
                                      {
                                          label: "Hadir",
                                          data: response['hadir'],
                                          backgroundColor: 'rgba(3, 231, 231, 0.2)',
                                          borderColor: 'rgba(3, 231, 231, 1)'
                                      },

                                      {
                                          label: "Apel",
                                          data: response['apel'],
                                          backgroundColor: 'rgba(160, 221, 207, 0.2)',
                                          borderColor: 'rgba(124, 208, 188, 1)'
                                      },
                                      {
                                          label: "Ijin",
                                          data: response['ijin'],
                                          backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                          borderColor: 'rgba(54, 162, 235, 1)'
                                      },
                                      {
                                          label: "Ijin Terlambat",
                                          data: response['ijinterlambat'],
                                          backgroundColor: 'rgba(95, 35, 28, 0.2)',
                                          borderColor: 'rgba(96, 36, 28, 1)'
                                      },
                                      {
                                          label: "Pulang Cepat",
                                          data: response['pulangcepat'],
                                          backgroundColor: 'rgba(255, 203, 15, 0.2)',
                                          borderColor: 'rgba(255, 203, 15, 1)'
                                      },
                                      {                                      
                                          label: "Sakit",
                                          data: response['sakit'],
                                          backgroundColor: 'rgba(10, 114, 133, 0.2)',
                                          borderColor: 'rgba(10, 114, 133, 1)'
                                      },
                                      {                                      
                                          label: "Tugas Belajar",
                                          data: response['tb'],
                                          backgroundColor: 'rgba(50, 104, 3, 0.2)',
                                          borderColor: 'rgba(50, 104, 3, 1)'
                                      },
                                      {                                      
                                          label: "Tugas Luar",
                                          data: response['tl'],
                                          backgroundColor: 'rgba(48, 8, 150, 0.2)',
                                          borderColor: 'rgba(48, 8, 150, 1)'
                                      },
                                      {                                      
                                          label: "Rapat/Undangan",
                                          data: response['rapat'],
                                          backgroundColor: 'rgba(119, 95, 124, 0.2)',
                                          borderColor: 'rgba(119, 95, 124, 1)'
                                      },
                                      {                                      
                                          label: "Terlambat",
                                          data: response['terlambat'],
                                          backgroundColor: 'rgba(184, 163, 46, 0.2)',
                                          borderColor: 'rgba(184, 163, 46, 1)'
                                      },

                                  ]
                              },
                          options: {
                              responsive: true,
                              legend: {
                                  position: 'top',
                              },
                              title: {
                                  display: true,
                                  text: 'Grafik Absensi = '+response['pegawai']+' orang'
                              },
                              tooltips: {
                                  enabled: true,
                                  mode: 'index',
                                  intersect: false,
                              }
                          }
                      });
  
               });
    });



</script>

<script type="text/javascript">
    var socket = io('http://eabsen.kalselprov.go.id:3000');
    new Vue({
        el: '#app',
        data: {
            atts: [],
        },
        mounted: function() {
            // 'test-channel:UserSignedUp'

            socket.on('test-channel.{{Auth::user()->instansi_id}}:App\\Events\\Timeline', function(data) {
                this.atts.unshift({class:data.class,statusmasuk:data.statusmasuk,namaPegawai:data.namaPegawai,namaInstansi:data.namaInstansi,tanggal:data.tanggal,jam:data.jam,instansiPegawai:data.instansiPegawai})
                 console.log(data)

            }.bind(this))
        }
    })
</script>

<script type="text/javascript">
    var page = 1;
    $('.app').scroll(function() {
      // alert($(document).height());
        if($('.app').scrollTop() + $('.app').height() >= 600) {
            page++;
            loadMoreData(page);
        }
    });

    function loadMoreData(page){
        $.ajax(
                {
                    url: '?page=' + page,
                    type: "get",
                    beforeSend: function()
                    {
                        $('#ajax-load').show();
                    }
                })
                .done(function(data)
                {
                    if(data.html == " "){
                        return;
                    }
                    $('#ajax-load').hide();
                    $(".timeline").append(data.html);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                    loadMoreData(page);
                });
    }
</script>
</body>

@endsection
