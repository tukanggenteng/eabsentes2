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


        <!-- /.navbar-collapse -->
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
        @if (($statuscari==null))

        @else
          <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4><i class="icon fa fa-bell"></i> Alert!</h4>
              {{$statuscari}}
          </div>
        @endif

        <div class="row">
              <div class="col-md-4">
                <div class="box box-widget widget-user">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header bg-red-active">
                        <h3 class="widget-user-username">{{$nama}}</h3>
                        <h5 class="widget-user-desc">{{$nip}}</h5>
                      </div>
                      <div class="widget-user-image">
                        <img class="img-circle" src="{{asset('dist/img/avatarumum.png')}}" alt="User Avatar">
                      </div>
                      <div class="box-footer">
                        <div class="row">
                          <div class="col-sm-4 border-right">
                            <div class="description-block">
                              <h5 class="description-header">{{$persentasehadir}}</h5>
                              <span class="description-text">TK</span>
                              <input type="hidden" id="nip" name="nip" value="{{$nip}}">
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                          <div class="col-sm-4 border-right">
                            <div class="description-block">
                              <h5 class="description-header">{{$persentaseapel}}</h5>
                              <span class="description-text"> Apel</span>
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                          <div class="col-sm-4">
                            <div class="description-block">
                              <h5 class="description-header">{{$totalakumulasi}}</h5>
                              <span class="description-text">Jam</span>
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                        </div>

                        <!-- /.row -->
                      </div>
                </div>
                <!-- /.widget-user -->
              </div>
              <div class="col-md-8">
                <div class="box box-default">
                  <div class="box-body">
                      <div class="row">
                          <div class="col-md-12">
                              <canvas id="container"></canvas>
                          </div>
                      </div>
                      <hr>

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
                                <th>Tanggal</th>
                                <th>Absen Terlambat</th>
                                <th>Jam Masuk</th>
                                <th>Lokasi Absen Masuk</th>
                                <th>Mulai Istirahat</th>
                                <th>Keluar Istirahat</th>
                                <th>Jam Keluar</th>
                                <th>Lokasi Absen Keluar</th>
                                <th>Akumulasi</th>
                                <th>Keterangan</th>
                                <th>Jadwal Kerja</th>
                              </tr>
                              @foreach ($kehadirans as $key => $kehadiran)
                                  <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$kehadiran->nip}}</td>
                                    <td>{{$kehadiran->nama}}</td>
                                    <td>{{$kehadiran->tanggal_att}}</td>
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
                                    @if ($kehadiran->jenis_absen=="Absent")
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

      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>

          @include('layouts.footer')
</div>
<!-- ./wrapper -->

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
    var lineChartCanvas = document.getElementById('container').getContext('2d')
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
        console.log(nip);
        if (nip==""){

            var url = "{{url('/pegawai/grafik')}}";

            $.get(url,{ nip:nip }, function(response) {
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
                                label: "Ijin Kepentingan Lain",
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
                        text: 'Grafik Absensi'
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                    }
                }
            });

     });
        }
        else {
          var nip=($('#nip').val());
          var url = "{{url('/pegawai/grafik')}}";

          $.get(url,{ nip:nip }, function(response) {
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
                                          label: "Ijin Kepentingan Lain",
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
        }

    });

</script>
</body>

@endsection
