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
          <a href="/data/pegawai" class="navbar-brand"><b>e-Absen</b></a>
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
                        <h3 class="widget-user-username">{{Auth::user()->username}}</h3>
                        <h5 class="widget-user-desc">{{Auth::user()->name}}</h5>
                      </div>
                      <div class="widget-user-image">
                        <img class="img-circle" src="{{asset('dist/img/avatarumum.png')}}" alt="User Avatar">
                      </div>
                      <div class="box-footer">
                        <div class="row">
                          <div class="col-sm-4 border-right">
                            <div class="description-block">
                              <h5 class="description-header">{{$persentasehadir}}%</h5>
                              <span class="description-text">Absent</span>
                              <input type="hidden" id="nip" name="nip" value="{{Auth::user()->nip}}">
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                          <div class="col-sm-4 border-right">
                            <div class="description-block">
                              <h5 class="description-header">{{$persentaseapel}}%</h5>
                              <span class="description-text">Apel</span>
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
                  <div class="box-body">
                    <table class="table table-striped">
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Lokasi Absen Masuk</th>
                        <th>Absen Terlambat</th>
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
                            <td>{{$kehadiran->jam_masuk}}</td>
                            <td>{{$kehadiran->namainstansimasuk}}</td>
                            @if ($kehadiran->terlambat=="00:00:00")
                            <td>{{$kehadiran->terlambat}}</td>
                            @else
                            <td><span class="badge bg-red">{{$kehadiran->terlambat}}</span></td>
                            @endif
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
    $(function () {

          var apel = new Array();
          var absen = new Array();
          var apel2 = new Array();
          var absen2 = new Array();
          var apel3 = new Array();
          var absen3 = new Array();

        var nip=($('#nip').val());

        if (nip==""){
            var url = "{{url('/instansi/grafik/public')}}";

            $.get(url, function(response) {

                absen2.push(response['Absen']);
                apel2.push(response['Apel']);

                var ctx = $('#container');
                var stackedLine = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["JAN", "FEB", "MAR", "APR", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                        datasets: [
                            {
                                label: "Persentase Apel",
                                data: apel3[0],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)'
                                ],
                                borderWidth: 1
                            },
                            {
                                label: "Persentase Tidak Hadir",
                                data: absen3[0],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)'
                                ],
                                borderWidth: 1
                            }
                        ]
                    }
                });
            });
        }
        else {
          var nip=($('#nip').val());
          var url = "{{url('/pegawai/grafik')}}";

          $.get(url,{nip:nip}, function(response) {

              absen2.push(response['Absen']);
              apel2.push(response['Apel']);

              var ctx = $('#container');
              var stackedLine = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: ["JAN", "FEB", "MAR", "APR", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                      datasets: [
                          {
                              label: "Persentase Apel",
                              data: apel2[0],
                              backgroundColor: [
                                  'rgba(255, 99, 132, 0.2)'
                              ],
                              borderColor: [
                                  'rgba(255,99,132,1)'
                              ],
                              borderWidth: 1
                          },
                          {
                              label: "Persentase Tidak Hadir",
                              data: absen2[0],
                              backgroundColor: [
                                  'rgba(54, 162, 235, 0.2)'
                              ],
                              borderColor: [
                                  'rgba(54, 162, 235, 1)'
                              ],
                              borderWidth: 1
                          }
                      ]
                  }
              });
          });
        }

    });

</script>
</body>

@endsection
