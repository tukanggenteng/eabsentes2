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
                @if (!empty(session('success')))
                    <div class="alert alert-success alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atur Trigger</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="/trigger" method="post">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Trigger</label>
                                    <select id="triger" name="triger" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        @if ($status=='0')
                                            <option selected="selected" value="0">None</option>
                                        @else
                                            <option value="0">None</option>
                                        @endif

                                        @if ($status=='1')
                                            <option selected="selected" value="1">Add Pegawai - Admin</option>
                                        @else
                                            <option value="1">Add Pegawai - Admin</option>
                                        @endif

                                        @if ($status=='2')
                                            <option selected="selected" value="2">Hapus Pegawai</option>
                                        @else
                                            <option value="2">Hapus Pegawai</option>
                                        @endif

                                        @if ($status=='3')
                                            <option selected="selected" value="3">Update Software</option>
                                        @else
                                            <option value="3">Update Software</option>
                                        @endif
                                        
                                    </select>
                                </div>

                                {{csrf_field()}}
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
    </script>

    </body>
@endsection