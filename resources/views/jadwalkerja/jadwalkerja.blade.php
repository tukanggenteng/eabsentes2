@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
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
                <!-- Atur Jadwal Kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atur Jam Kerja</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <form action="/jadwalkerja/add" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jam Masuk Kerja</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input id="awal" readonly name="awal" class="form-control pull-right" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jam Pulang Kerja</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input id="pulang" readonly name="pulang" class="form-control pull-right" type="text">
                                        </div>
                                        {{csrf_field()}}
                                    </div>
                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jenis Jadwal</label>
                                        <input id="jenisjadwal" name="jenisjadwal" type="text" class="form-control" placeholder="Shift1/Shift2">
                                        <!-- <input id="instansi_id" name="instansi_id" type="hidden" value="{{ Auth::user()->instansi_id }}"> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Instansi</label>
                                        <select name="instansi_id[]" class="form-control select2" id="instansi_id"></select>
                                    </div>
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
                <!-- /.box -->


                <!-- Table jadwal kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tabel Aturan Jam Kerja</h3>

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
                                                <th>Jam Masuk</th>
                                                <th>Jam Keluar</th>
                                                <th>Jadwal Kerja</th>
                                                <th>Instansi</th>
                                                <th>Aksi</th>
                                            </tr>
                                                @foreach($jadwalkerjas as $jadwalkerja)
                                                    <tr>
                                                    <td>{{ $jadwalkerja->jam_masukjadwal }}</td>
                                                    <td>{{ $jadwalkerja->jam_keluarjadwal }}</td>
                                                    <td>{{ $jadwalkerja->jenis_jadwal }}</td>
                                                    <td>{{ $jadwalkerja->namaInstansi }}</td>
                                                    <td><a class="btn-sm btn-success" href="/jadwalkerja/{{ encrypt($jadwalkerja->id) }}/edit">Edit</a>
                                                        <a class="btn-sm btn-danger" data-method="delete"
                                                           data-token="{{csrf_token()}}" href="/jadwalkerja/{{ encrypt($jadwalkerja->id) }}/hapus">Hapus</a></td>
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


                <!-- Atur Jadwal Sebelum Masuk Kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atur Jam Kerja</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="/rulejadwalkerja" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jam Mulai Masuk Kerja</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input id="awalmasuk" readonly name="awalmasuk" class="form-control pull-right" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Jam Batas Pulang Kerja</label>
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input id="bataspulang" readonly name="bataspulang" class="form-control pull-right" type="text">
                                        </div>
                                        {{csrf_field()}}
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Jadwal</label>
                                            <select class="form-control select2" name="jadwalkerjamasuk[]" id="jadwalkerjamasuk">

                                            </select>
                                        <input id="instansi_id" name="instansi_id" type="hidden" value="{{ Auth::user()->instansi_id }}">
                                    </div>
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
                <!-- /.box -->


                <!-- Table jadwal Sebelum kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tabel Aturan Jam Kerja</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <form action="/jadwalkerja" method="post">
                                        {{csrf_field()}}
                                        @if ($cari=="")
                                        <input type="text" name="cari" placeholder="Jadwal/Instansi">
                                        @else
                                        <input type="text" name="cari" placeholder="Jadwal/Instansi" value="{{$cari}}">
                                        @endif
                                        <button type="submit" name="button"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Jam Masuk</th>
                                                <th>Jam Keluar</th>
                                                <th>Jenis Jadwal</th>
                                                <th>Instansi</th>
                                                <th>Aksi</th>
                                            </tr>

                                                @foreach($rules as $rule)
                                                <tr>
                                                    <td>{{ $rule->jamsebelum_masukkerja }}</td>
                                                    <td>{{ $rule->jamsebelum_pulangkerja }}</td>
                                                    <td>{{ $rule->jenis_jadwal }}</td>
                                                    <td>{{ $rule->namaInstansi }}</td>
                                                    <td><a class="btn-sm btn-success" href="/rulejadwalkerja/{{ encrypt($rule->id) }}/edit">Edit</a>
                                                        <a class="btn-sm btn-danger" data-method="delete"
                                                           data-token="{{csrf_token()}}" href="/rulejadwalkerja/{{ encrypt($rule->id) }}/hapus">Hapus</a></td>
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

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{$rules->appends(['cari'=>($cari)])->links()}}
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

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

        $('#jadwalkerjamasuk').select2(
            {
            placeholder: "Pilih Jadwal Kerja",
            minimumInputLength: 1,
            ajax: {
                url: '/jadwalkerja/cari',
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
