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
          <a href="/" class="navbar-brand"><b>e-Absen</b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <form class="navbar-form navbar-left" action="/" method="post" role="search">
            <div class="form-group">
              {{csrf_field()}}
              <input type="text" class="form-control" id="search" name="search" placeholder="Search NIP Pegawai">
            </div>
          </form>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <!-- User Account Menu -->
            <li class="dropdown">
              <!-- Menu Toggle Button -->
              <a href="/login" class="dropdown-toggle">
                Login
              </a>
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
                  @if (isset($nama))
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
                              <h5 class="description-header">{{$persentasehadir}}%</h5>
                              <span class="description-text">Hadir</span>
                              <input type="hidden" id="nip" name="nip" value="{{$nip}}">
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
                  @else
                      <div class="widget-user-header bg-red-active">
                        <h3 class="widget-user-username">Pegawai</h3>
                        <h5 class="widget-user-desc">NIP</h5>
                        <input type="hidden" id="nip" name="nip">
                      </div>
                      <div class="widget-user-image">
                        <img class="img-circle" src="{{asset('dist/img/avatarumum.png')}}" alt="User Avatar">
                      </div>
                      <div class="box-footer">
                        <div class="row">
                          <div class="col-sm-4 border-right">
                            <div class="description-block">
                              <h5 class="description-header">100%</h5>
                              <span class="description-text">Hadir</span>
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                          <div class="col-sm-4 border-right">
                            <div class="description-block">
                              <h5 class="description-header">100%</h5>
                              <span class="description-text">Apel</span>
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                          <div class="col-sm-4">
                            <div class="description-block">
                              <h5 class="description-header">00:00:00</h5>
                              <span class="description-text">Jam</span>
                            </div>
                            <!-- /.description-block -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </div>
                  @endif
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
          <div class="col-md-6">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Pegawai Terbaik (Bulan)</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <table class="table table-striped">
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th style="width: 20px">Absent</th>
                    <th style="width: 20px">Apel</th>
                    <th style="width: 30px">Jam</th>
                  </tr>
                  @foreach ($pegawaibulan as $key => $pegawai2)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$pegawai2->nip}}</td>
                      <td>{{$pegawai2->nama}}</td>
                      <td><span class="badge bg-red">{{round($pegawai2->persentasehadir,2)}}%</span></td>
                      <td><span class="badge bg-green">{{round($pegawai2->persentaseapel,2)}}%</span></td>
                      <td><span class="badge bg-light-blue">{{$pegawai2->total}}</span></td>
                    </tr>
                  @endforeach
                </table>
              </div>
              <!-- /.box-body -->
            </div>
          </div>
          <div class="col-md-6">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Pegawai Terbaik (Tahun)</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding">
                <table class="table table-striped">
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th style="width: 20px">Absent</th>
                    <th style="width: 20px">Apel</th>
                    <th style="width: 30px">Jam</th>
                  </tr>
                  @foreach ($pegawaitahun as $key => $pegawai3)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$pegawai3->nip}}</td>
                      <td>{{$pegawai3->nama}}</td>
                      <td><span class="badge bg-red">{{round($pegawai3->persentasehadir,2)}}%</span></td>
                      <td><span class="badge bg-green">{{round($pegawai3->persentaseapel,2)}}%</span></td>
                      <td><span class="badge bg-light-blue">{{$pegawai3->total}}</span></td>
                    </tr>
                  @endforeach


                </table>
              </div>
              <!-- /.box-body -->
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Instansi Terdisiplin (Tahun)</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Instansi</th>
                  <th style="width: 30px">Apel</th>
                  <th style="width: 30px">Absent</th>
                </tr>
                @foreach ($instansitahun as $key => $instansi)
                  <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$instansi->namaInstansi}}</td>
                  <td><span class="badge bg-green">{{round($instansi->persentaseapel,2)}}%</span></td>
                  <td><span class="badge bg-green">{{round($instansi->persentasehadir,2)}}%</span></td>
                  </tr>
                @endforeach


              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>

        <div class="col-md-6">
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





        var url = "{{url('/instansi/grafik/public')}}";

        $.get(url, function(response) {

            absen.push(response['Absen']);
            apel.push(response['Apel']);

            var ctx = $('#containertahun');
            var stackedLine = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["JAN", "FEB", "MAR", "APR", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                    datasets: [
                        {
                            label: "Persentase Apel",
                            data: apel[0],
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
                            data: absen[0],
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
    });

    $("#caritahun").click(function () {

      var apel3 = new Array();
      var absen3 = new Array();

        $("#containertahun").html("");
        var instansi = $("#instansitahun").val();
        var tahun = $("#periodetahun").val();

        var url = "{{url('/instansi/grafik')}}";

        $.get(url,{ periodetahun: tahun,instansitahun:instansi }, function(response) {

            absen3.push(response['Absen']);
            apel3.push(response['Apel']);

            var ctx = $('#containertahun');
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
    });
</script>
</body>

@endsection
