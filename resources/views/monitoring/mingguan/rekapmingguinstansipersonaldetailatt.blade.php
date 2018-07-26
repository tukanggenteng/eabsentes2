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
                        <h3 class="box-title">Monitoring Rekap Harian Instansi Detail Pegawai</h3>

                        <div class="box-tools">
                        </div>

                    </div>
                    <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            NIP : 
                                            <label>{{$nip}}</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            Nama : 
                                            <label>{{$namapegawai}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            Instansi : 
                                            <label>{{$namainstansi}}</label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                        <th>Tanggal</th>
                                        <th>Absen Terlambat</th>
                                        <th>Apel</th>
                                        <th>Jam Masuk</th>
                                        <th>Lokasi Absen Masuk</th>
                                        <th>Mulai Istirahat</th>
                                        <th>Selesai Istirahat</th>
                                        <th>Jam Keluar</th>
                                        <th>Lokasi Absen Keluar</th>
                                        <th>Akumulasi</th>
                                        <th>Keterangan</th>
                                        <th>Jadwal Kerja</th>
                                        <th>Sifat</th>
                                        </tr>
                                        @foreach ($datas as $key => $data)

                                        <tr>
                                            <td>{{$data->tanggal_att}}</td>
                                            @if ($data->terlambat=="00:00:00")
                                            <td>{{$data->terlambat}}</td>
                                            @else
                                                @if ($data->sifat=="WA")
                                                <td><span class="badge bg-red">{{$data->terlambat}}</span></td>
                                                @else
                                                <td>{{$data->terlambat}}</td>
                                                @endif                                    
                                            @endif
                                            @if (($data->apel=="1") )
                                            <td>A</td>
                                            @else
                                                @if ($data->sifat=="WA") 
                                                    <td><span class="badge bg-red">TA</span></td>
                                                @else
                                                    <td>TA</td>
                                                @endif
                                            @endif
                                            <td>{{$data->jam_masuk}}</td>
                                            <td>{{$data->namainstansimasuk}}</td>
                                            <td>{{$data->keluaristirahat}}</td>
                                            <td>{{$data->masukistirahat}}</td>
                                            <td>{{$data->jam_keluar}}</td>
                                            <td>{{$data->namainstansikeluar}}</td>
                                            <td>{{$data->akumulasi_sehari}}</td>
                                            @if ($data->jenis_absen=="Tanpa Kabar")
                                            <td><span class="badge bg-red">{{$data->jenis_absen}}</span></td>
                                            @else
                                            <td>{{$data->jenis_absen}}</td>
                                            @endif
                                            <td>{{$data->jenis_jadwal}}</td>
                                            @if ($data->sifat=="WA")
                                                <td>Wajib Apel</td>
                                            @elseif ($data->sifat=="TWA")
                                                <td>Wajib Apel</td>
                                            @elseif ($data->sifat=="FD")
                                                <td>Full Day</td></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </table>
                                    </div>
                                        
                                </div>
                            </div>
                            
                            </form>
                        </div>
                        <!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{$datas->appends(['jenis_absen'=>($jenis_absen2),'metode'=>($metode),'tanggal'=>$tanggal])->links()}}
                        </ul>
                    </div>
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

    <script type="text/javascript">
        $(function() {
            $('input[name="tanggal"]').datepicker({
                format: "yyyy-mm",
                startView: "months",
                minViewMode: "months"
            });
           

           
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
