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

                @if (!empty(session('err')))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    {{session('err')}}
                </div>
                @endif
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Atur Jadwal Kerja Pegawai</h3>

                        <div class="box-tools">
                            <form action="/jadwalkerjapegawai" method="post">
                            <div class="input-group input-group-sm" style="width: 150px;">

                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" value="{{$cari}}">
                                {{csrf_field()}}
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            </form>
                        </div>

                    </div>
                    <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                        <form action="/jadwalkerjapegawaiedit" method="post">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label>Jenis Jadwal Kerja</label>
                                            <select class="form-control" name="jadwalkerjamasuk" data-placeholder="Jenis Jadwal Kerja">
                                                @foreach($jadwalkerjas as $jadwalkerja)
                                                    <option value="{{$jadwalkerja->id}}">{{$jadwalkerja->jenis_jadwal}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Tanggal berlaku</label>
                                            <input type="text" class="form-control" id="daterange" name="daterange"/>
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
                                            <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                </tr>

                                @foreach($rulejadwals as $rulejadwal)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkbox[]" value="{{$rulejadwal->id}}" class="flat-red">
                                    </td>
                                    <td>{{$rulejadwal->nip}}</td>
                                    <td>{{$rulejadwal->nama}}</td>
                                    <td>{{$rulejadwal->jabatan}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </form>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{$rulejadwals->links()}}
                            </ul>
                        </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Manajemen Jadwal Kerja Pegawai</h3>

                        <div class="box-tools">
                            <form action="/jadwalkerjapegawai" method="post">
                                <div class="input-group input-group-sm" style="width: 150px;">

                                    <input type="text" name="table_search2" class="form-control pull-right" placeholder="Search" value="{{$cari2}}">
                                    {{csrf_field()}}
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">

                            <table class="table table-hover">
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Jenis Jadwal</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Aksi</th>
                                </tr>

                                @foreach($rulejadwals2 as $rulejadwal2)
                                    <tr>
                                        <td>{{$rulejadwal2->nip}}</td>
                                        <td>{{$rulejadwal2->nama}}</td>
                                        <td>{{$rulejadwal2->jabatan}}</td>
                                        <td>{{$rulejadwal2->jenis_jadwal}}</td>
                                        <td>{{$rulejadwal2->tanggal_awalrule}}</td>
                                        <td>{{$rulejadwal2->tanggal_akhirrule}}</td>
                                        <td><a class="btn-sm btn-success" href="/jadwalkerjapegawai/{{ $rulejadwal2->id }}/edit">Edit</a>
                                            <a class="btn-sm btn-danger" data-method="delete"
                                               data-token="{{csrf_token()}}" href="/jadwalkerjapegawai/{{ $rulejadwal2->id }}/hapus">Hapus</a></td>
                                    </tr>
                                @endforeach
                            </table>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{$rulejadwals2->links()}}
                        </ul>
                    </div>
                </div>
                <!-- /.row -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </footer>
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
                $('input[name="daterange"]').daterangepicker(
                        {
                            locale: {
                                format: 'YYYY/MM/DD'
                            }
                        }
                );
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
