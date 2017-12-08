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
          <a href="/dashboard" class="navbar-brand"><b>e-Absen</b></a>
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

        <div class="row">

        <div class="col-md-12">
          <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title">Grafik Absensi Instansi (Tahun)</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <select id="instansitahun" name="instansitahun" class="form-control select2" value="" placeholder="Periode">
                                      @foreach ($instansis as $key => $instansi)
                                            <option value="{{$instansi->id}}">{{$instansi->namaInstansi}}</option>
                                      @endforeach

                                    </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                    <input type="text" id="periodetahun" readonly name="periodetahun" class="form-control pull-right" value="{{$tahun}}" placeholder="Periode">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                    <button type="button" id="caritahun" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                      </div>

                    </div>
                <div class="row">
                      <div class="col-md-12">
                        <canvas id="containertahun"></canvas>
                      </div>
                </div>
        </div>
      </div>

        </div>
      </div>
        <!-- /.box -->
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
        //Initialize Select2 Elements
        $('#instansibulan').select2();
        $('#instansitahun').select2();

        $('input[name="periodetahun"]').datepicker({
            format: "yyyy",
            autoclose: true,
            minViewMode: "years"
          });




        var url = "{{url('/instansi/grafik/public')}}";

        $.get(url, function(response) {

            absen.push(response['Absen']);
            apel.push(response['Apel']);

            var ctx = document.getElementById("containertahun").getContext("2d");
            var color = Chart.helpers.color;
              window.myBar = new Chart(ctx, {
                  type: 'bar',
                  data: {
                          labels: ["JAN", "FEB", "MAR", "APR","MEI", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                          datasets: [
                              {
                                  label: "Persentase Apel",
                                  data: apel[0],
                                  backgroundColor: [
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                  ],
                                  borderColor: [
                                      'rgba(255,99,132,1)'
                                  ],
                                  borderWidth: 1,
                              },
                              {
                                  label: "Persentase Tidak Hadir",
                                  data: absen[0],
                                  backgroundColor: [
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                  ],
                                  borderColor: [
                                      'rgba(54, 162, 235, 1)'
                                  ],
                                  borderWidth: 1,
                              }
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
                      }
                  }
              });
        });
    });

    $("#caritahun").click(function () {

      var apel3 = new Array();
      var absen3 = new Array();

        $("#containertahun").html("");
        var instansi = $("#instansitahun").val();
        var tahun = $("#periodetahun").val();

        var url = "{{url('/instansi/grafik/semua')}}";

        $.get(url,{ periodetahun: tahun,instansitahun:instansi }, function(response) {

            absen3.push(response['Absen']);
            apel3.push(response['Apel']);

            var ctx = document.getElementById("containertahun").getContext("2d");
            var color = Chart.helpers.color;
              window.myBar = new Chart(ctx, {
                  type: 'bar',
                  data: {
                          labels: ["JAN", "FEB", "MAR", "APR","MEI", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                          datasets: [
                              {
                                  label: "Persentase Apel",
                                  data: apel3[0],
                                  backgroundColor: [
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                      'rgba(255, 99, 132, 0.2)',
                                  ],
                                  borderColor: [
                                      'rgba(255,99,132,1)'
                                  ],
                                  borderWidth: 1,
                              },
                              {
                                  label: "Persentase Tidak Hadir",
                                  data: absen3[0],
                                  backgroundColor: [
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                      'rgba(54, 162, 235, 0.2)',
                                  ],
                                  borderColor: [
                                      'rgba(54, 162, 235, 1)'
                                  ],
                                  borderWidth: 1,
                              }
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
                      }
                  }
              });
        });
    });
</script>
</body>

@endsection
