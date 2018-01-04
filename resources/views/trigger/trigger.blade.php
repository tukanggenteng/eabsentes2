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
                @if (!empty(session('success')))
                    <div class="alert alert-success alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Hapus Pegawai</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form action="/trigger/hapus" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Pegawai</label>
                                                <select id="pegawai" name="pegawai[]" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
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
                    </div>
                </div>
                

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Daftar Pegawai Yang DiHapus</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form action="/trigger/hapusdata" method="post">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-danger btn-flat">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-hover">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select_all2" name="select_all2" class="flat-red select_all">
                                                    </th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Instansi</th>
                                                </tr>
                                                <?php $i=1; ?>
                                                @foreach($datas as $data)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="checkbox2[]" value="{{encrypt($data->id)}}" class="flat-red checkbox cekbox2">
                                                    </td>
                                                    <td>{{$data->nip}}</td>
                                                    <td>{{$data->nama}}</td>
                                                    <td>{{$data->namaInstansi}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
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

        $('#select_all2').on('ifChanged', function(event){
            if(!this.changed) {
                this.changed=true;
                $('.cekbox2').iCheck('check');
            } else {
                this.changed=false;
                $('.cekbox2').iCheck('uncheck');
            }
            $('.cekbox2').iCheck('update');
        });

        $('#pegawai').select2(
            {
                placeholder: "Pilih Pegawai",
                minimumInputLength: 1,
                ajax: {
                    url: '/pegawai/cari',
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
