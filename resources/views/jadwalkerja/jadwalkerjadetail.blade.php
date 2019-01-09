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


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jam Masuk Kerja</label>
                                        {{$jadwalkerja->jam_masukjadwal}}
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jam Pulang Kerja</label>
                                        {{$jadwalkerja->jam_keluarjadwal}}
                                    </div>
                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jenis Jadwal</label>
                                        {{$jadwalkerja->jenis_jadwal}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Singkatan Jadwal</label>
                                        {{$jadwalkerja->singkatan}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Instansi</label>
                                        {{$jadwalkerja->namaInstansi}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Sifat</label>
                                        {{$jadwalkerja->sifat}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Lewat Hari</label>
                                        {{$jadwalkerja->lewathari}}
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
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            
                        <!-- /.row -->
                    </div>
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
            showMeridian:false,
            showSeconds:true
        });
        $('#awalmasuk').timepicker({
            showMeridian:false,
            showSeconds:true
        });
    </script>
    <script type="text/javascript">
        $('#bataspulang').timepicker({
            showMeridian:false,
            showSeconds:true
        });
        $('#pulang').timepicker({
            showMeridian:false,
            showSeconds:true
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
            
            
           
        });
        var currColor = '{{$jadwalkerja->color}}' //Red by default
        //Color chooser button
        $('#preview')[0].className = '{{$jadwalkerja->classdata}}';
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
