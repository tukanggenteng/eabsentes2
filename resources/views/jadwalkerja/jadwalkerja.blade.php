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


<!-- fullCalendar -->
<link rel="stylesheet" href="{{asset('bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">

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
            @if ((session('status')))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-bell"></i> Berhasil!</h4>
                {{session('status')}}
            </div>
            @endif
            @if ($errors->has('jadwalkerja') || $errors->has('singkatan') || $errors->has('instansi_id') || $errors->has('awalmasuk') || $errors->has('bataspulang') || $errors->has('jadwalkerjamasuk'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-bell"></i> Gagal!</h4>
                Gagal menyimpan data!
            </div>
            @endif
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
                                        <label>Singkatan Jadwal</label>
                                        <input id="singkatan" name="singkatan" type="text" class="form-control" placeholder="Singkatan Jadwal">
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Sifat</label>
                                        <select name="sifat" class="form-control select2" id="sifat">
                                            <option value="{{encrypt('TWA')}}">Tidak Wajib Apel</option>
                                            <option value="{{encrypt('WA')}}">Wajib Apel</option>
                                            <option value="{{encrypt('FD')}}">Full Day</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input readonly type="hidden" name="color" id="color">
                                        <input readonly type="hidden" name="classcolor" id="classcolor">
                                        <input readonly type="hidden" name="classdata" id="classdata">
                                        <ul class="fc-color-picker" id="color-chooser">
                                            <li><a class="text-red" data-color="bg-red" id="preview" href="#"><i class="fa fa-check-circle"></i></a></li>
                                            <li><a class="text-aqua" data-color="bg-aqua" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-blue" data-color="bg-blue" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-light-blue" data-color="bg-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-teal" data-color="bg-teal" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-yellow" data-color="bg-yellow" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-orange" data-color="bg-orange" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-green" data-color="bg-green" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-lime" data-color="bg-lime" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-red" data-color="bg-red" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-purple" data-color="bg-purple" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-fuchsia" data-color="bg-fuchisia" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-muted" data-color="bg-muted" href="#"><i class="fa fa-square"></i></a></li>
                                            <li><a class="text-navy" data-color="bg-navy" href="#"><i class="fa fa-square"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
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
                                <div class="table-responsive">
                                        <table class="table table-striped " id="jadwalkerjadatatable">
                                            <thead>
                                                <th>Jam Masuk</th>
                                                <th>Jam Keluar</th>
                                                <th>Jadwal Kerja</th>
                                                <th>Singkatan</th>
                                                <th>Instansi</th>
                                                <th>Warna</th>
                                                <th>Sifat</th>
                                                <th>Aksi</th>
                                            </thead>
                                               
                                        </table>
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
                                <div class="table-responsive">
                                    <table class="table table-striped" id="rulejadwalkerjadatatable">
                                        <thead>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Jenis Jadwal</th>
                                            <th>Instansi</th>
                                            <th>Sifat</th>
                                            <th>Aksi</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
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

        var jadwalkerjadatatable;
        var rulejadwalkerjadatatable;
        $(function() {
            jadwalkerjadatatable = $('#jadwalkerjadatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('jadwalkerjadatatable')}}',
                columns: [
                    { data: 'jam_masukjadwal', name: 'jam_masukjadwal' },
                    { data: 'jam_keluarjadwal', name: 'jam_keluarjadwal' },
                    { data: 'jenis_jadwal', name: 'jenis_jadwal' },
                    { data: 'singkatan', name: 'singkatan' },
                    { data: 'namaInstansi', name: 'namaInstansi' },
                    { data: 'classdata', name: 'classdata' },
                    { data: 'sifat', name: 'sifat' },
                    { data: 'action', name: 'action',orderable: false }
                ]
            });
            
            rulejadwalkerjadatatable = $('#rulejadwalkerjadatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('rulejadwalkerjadatatable')}}',
                columns: [
                    { data: 'jamsebelum_masukkerja', name: 'jamsebelum_masukkerja' },
                    { data: 'jamsebelum_pulangkerja', name: 'jamsebelum_pulangkerja' },
                    { data: 'jenis_jadwal', name: 'jenis_jadwal' },
                    { data: 'namaInstansi', name: 'namaInstansi' },
                    { data: 'sifat', name: 'sifat' },
                    { data: 'action', name: 'action',orderable: false }
                ]
            });

        });
        var currColor = '#3c8dbc' //Red by default
        //Color chooser button
        var colorChooser = $('#color-chooser-btn')
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault()
            //Save color
            currColor = $(this).css('color')
            currClass=$(this).attr('class')
            currData=$(this).data('color')
            //Add color effect to button
            $("#preview").removeAttr('class');
            $("#preview").attr('class', '');
            $('#preview')[0].className = currClass;

            $('#color').val('');
            $('#color').val(currColor);
            $('#classcolor').val('');
            $('#classcolor').val(currClass);
            $('#classdata').val('');
            $('#classdata').val(currData);
            // $('#preview').removeClass()
        })
    </script>

    </body>
@endsection
