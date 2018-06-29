@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.css')}}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/pace/pace.min.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />


<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endpush

@section('body')
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
             <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div> 

            <div class="wrapper">

                  @include('layouts.header')

                  @include('layouts.sidebar')

                  <!-- Content Wrapper. Contains page content -->
                  <div class="content-wrapper">

                      <!-- Main content -->
                      <section class="content">

                            @if (!empty(session('err')))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                {{session('err')}}
                            </div>
                            @endif
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Monitoring Grafik Bulanan Instansi</h3>

                                    <div class="box-tools">
                                    </div>

                                </div>
                                <!-- /.box-header -->
                                    <div class="box-body table-responsive">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-md-7">
                                                        <label>Instansi</label>
                                                        <select class="form-control select2" id="instansi_id"  name="instansi_id[]" data-placeholder="Instansi">
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">    
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-md-2">
                                                        <label>Tanggal</label>
                                                        <input type="text" placeholder="Tanggal" class="form-control" id="tanggal" readonly name="tanggal"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <button type="button" id="cari" class="btn btn-primary btn-flat"><i class="fa fa-search"></i>   Cari</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                               <canvas id="lineChart"></canvas>
                                            </div>

                                        </div>


                                    </div>
                                    <!-- /.box-body -->
                            </div>

                            
                            <!-- /.row -->

                      </section>
                        <!-- /.content -->
                  </div>
                <!-- /.content-wrapper -->
                    @include('layouts.footer')
            </div>
    <!-- ./wrapper -->

    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- jQuery 3 -->
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->

    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
                                     
    <!-- Select2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <script src="{{asset('bower_components/PACE/pace.min.js')}}"></script>
    
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

    <script type="text/javascript">
         
        var lineChartCanvas = document.getElementById('lineChart').getContext('2d')

        $(function() {
            
            $('input[name="tanggal"]').datepicker({
                format: "yyyy-mm",
                startView: "months",
                minViewMode: "months"

            });
           

                window.myBar = new Chart(lineChartCanvas, {
                      type: 'line',
                      data: {
                              labels: [],
                              datasets: [
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
        
        $(document).on('click','#cari',function(){
              var tanggal=$('#tanggal').val();
              var instansi=$('#instansi_id').val();
              var token=$('input[name=_token]').val();
                  
              if ((instansi==null) || (tanggal==null))
              {
                  swal("Gagal !","Data instansi atau tanggal kosong.","error");
              }
              else
              {
               $(document).ajaxStart(function () {
                   Pace.restart()
               })
               var url='{{route('monitoringgrafikbulanandata')}}';
               $.get(url,{ tanggal: tanggal,instansi_id:instansi }, function(response) {
                    myBar.destroy();
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

              }
 
        });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        })
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass   : 'iradio_minimal-red'
        })
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass   : 'iradio_flat-green'
        })

        $('#instansi_id').select2(
            {
            placeholder: "Pilih Instansi.",
            minimumInputLength: 1,
            ajax: {
                url: '/instansi/cari',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
                }
            }
        );
    </script>

    </body>
@endsection
