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
                <!-- atur hari -->
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Hari Kerja Pegawai</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="/harikerja" method="post">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="checkbox" name="checkbox[]" value="Senin" class="flat-red"> Senin
                                        <input type="checkbox" name="checkbox[]" value="Selasa" class="flat-red"> Selasa
                                        <input type="checkbox" name="checkbox[]" value="Rabu" class="flat-red"> Rabu
                                        <input type="checkbox" name="checkbox[]" value="Kamis" class="flat-red"> Kamis
                                        <input type="checkbox" name="checkbox[]" value="Jumat" class="flat-red"> Jumat
                                        <input type="checkbox" name="checkbox[]" value="Sabtu" class="flat-red"> Sabtu
                                        <input type="checkbox" name="checkbox[]" value="Minggu" class="flat-red"> Minggu
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Jadwal</label>
                                        <select class="form-control select2" name="jadwalkerjamasuk" data-placeholder="Jenis Jadwal Kerja">
                                            @if (isset($jadwalkerjas))
                                                @foreach($jadwalkerjas as $jadwalkerja)
                                                    <option value="{{$jadwalkerja['id']}}">{{$jadwalkerja['jenis_jadwal']}} ({{$jadwalkerja['jam_masukjadwal']}} - {{$jadwalkerja['jam_keluarjadwal']}})</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Hari kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tabel Hari Jadwal Kerja</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>#</th>
                                                <th>Jadwal Kerja</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach($hasildatas as $key =>$hasildata)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$hasildata['jenis_jadwal']}}</td>
                                                    <td>
                                                    <a class="btn-sm btn-danger" data-method="delete"
                                                    data-token="{{csrf_token()}}" href="/harikerja/hapus/{{ encrypt($hasildata['jadwalkerja_id']) }}">Hapus</a></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2">
                                                @foreach($hasildata[0] as $key)
                                                        {{($key['hari']).","}}
                                                @endforeach
                                                </td>
                                                </tr>

                                            @endforeach

                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                </div>


                <!-- minggukerja -->
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Minggu Jadwal Kerja</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="/minggukerja" method="post">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="checkbox" name="checkbox2[]" value="1" class="flat-red"> Minggu Ke-1
                                        <input type="checkbox" name="checkbox2[]" value="2" class="flat-red"> Minggu Ke-2
                                        <input type="checkbox" name="checkbox2[]" value="3" class="flat-red"> Minggu Ke-3
                                        <input type="checkbox" name="checkbox2[]" value="4" class="flat-red"> Minggu Ke-4
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Jadwal</label>
                                        <select class="form-control select2" name="jadwalkerjaminggu" data-placeholder="Jenis Jadwal Kerja">
                                            @foreach($jadwalminggus as $jadwalminggu)
                                                <option value="{{$jadwalminggu['id']}}">{{$jadwalminggu['jenis_jadwal']}} ({{$jadwalminggu['jam_masukjadwal']}} - {{$jadwalkerja['jam_keluarjadwal']}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Hari kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tabel Hari Jadwal Kerja</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>#</th>
                                                <th>Jadwal Kerja</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach($jadwalminggudatas as $key =>$jadwalminggudata)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$jadwalminggudata['jenis_jadwal']}}</td>
                                                    <td>
                                                    <a class="btn-sm btn-danger" data-method="delete"
                                                    data-token="{{csrf_token()}}" href="/minggukerja/{{ encrypt($jadwalminggudata['jadwalkerja_id']) }}">Hapus</a></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2">
                                                @foreach($jadwalminggudata[0] as $key)
                                                        {{"Minggu Ke-".($key['minggu']).","}}
                                                @endforeach
                                                </td>
                                                </tr>

                                            @endforeach

                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

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
        $('#awal').timepicker({
            showMeridian:false
        });
        $('#awalmasuk').timepicker({
            showMeridian:false
        });
    </script>
    <script type="text/javascript">
        $('#bataspulang').timepicker({
            showMeridian:false
        });
        $('#pulang').timepicker({
            showMeridian:false
        });


        $('.select2').select2();

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
