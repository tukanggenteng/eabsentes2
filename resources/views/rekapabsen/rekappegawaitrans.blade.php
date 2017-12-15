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
            @include('layouts.inforekap')

            @if ((session('status')))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-times"></i> Perhatian!</h4>
                {{session('status')}}
            </div>
            @endif
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Keterangan Absensi Pegawai</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <form action="/rekapabsensipegawai/{{encrypt($pegawai[0]['id'])}}" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label>NIP</label>
                                        <input type="text" id="nip" name="nip" class="form-control" disabled readonly value="{{$pegawai[0]['nip']}}">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Nama</label>
                                        <input type="text" id="nama" name="nama" class="form-control" disabled readonly value="{{$pegawai[0]['nama']}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Periode</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control" type="text" id="periode" name="periode" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jenis Absen</label>
                                            <select id="jenisabsen" name="jenisabsen" class="form-control">
                                                @foreach($jenisabsens as $jenisabsen)
                                                    <option value="{{$jenisabsen->id}}">{{$jenisabsen->jenis_absen}} ({{$jenisabsen->jam_masukjadwal}} - {{$jenisabsen->jamkeluarjadwal}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-7">
                                        <label>Jadwal Kerja</label>
                                        @foreach($jadwalkerjas as $jadwalkerja)
                                        <div class="form-group">
                                            <input type="checkbox" name="checkbox[]" value="{{$jadwalkerja->jadwalkerja_id}}" class="flat-red"> {{$jadwalkerja->jenis_jadwal}} ({{$jadwalkerja->jam_masukjadwal}} - {{$jadwalkerja->jam_keluarjadwal}})
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                    </div>
                                </div>
                            </div>
                            {{csrf_field()}}
                        </form>
                        <hr>
                            <table class="table table-hover">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Lokasi Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Lokasi Keluar</th>
                                    <th>Jadwal Kerja</th>
                                    <th>Akumulasi</th>
                                    <th>Status</th>
                                </tr>

                                @foreach($atts as $att)
                                    <tr>
                                        <td>{{$att->tanggal_att}}</td>
                                        <td>{{$att->jam_masuk}}</td>
                                        <td>{{$att->namainstansimasuk}}</td>
                                        <td>{{$att->jam_keluar}}</td>
                                        <td>{{$att->namainstansikeluar}}</td>
                                        <td>{{$att->jenis_jadwal}}</td>
                                        <td>{{$att->akumulasi_sehari}}</td>
                                        <td>{{$att->jenis_absen}}</td>
                                    </tr>
                                @endforeach
                            </table>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{--{{$pegawais->links()}}--}}
                        </ul>
                    </div>
                </div>
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


            var minDate="{{$awal}}";
            var maxDate="{{$akhir}}";

            $('input[name="periode"]').daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                },
                startDate:minDate,
                endDate:maxDate,
                minDate:minDate,
                maxDate:maxDate
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
    </script>

    </body>
@endsection
