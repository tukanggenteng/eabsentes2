@extends('layouts.app')

@section('title')
Manajemen Jadwal Kerja Pegawai
@endsection

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
                    <h4><i class="icon fa fa-ban"></i> Perhatian!</h4>
                    {!!session('err')!!}
                </div>
                @endif

                @if (!empty(session('success')))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                    {!!session('success')!!}
                </div>
                @endif
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Perhatian !</h4>
                    Untuk penginputan jadwal kerja struktural ramadhan, harus dipastikan data kehadiran terkirim terlebih dahulu sebelum mengatur jadwal kerja ramadhan.
                </div>

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Atur Jadwal Kerja Pegawai</h3>

                        <div class="box-tools">
                        </div>

                    </div>
                    <!-- /.box-header -->
                        <div class="box-body table-responsive">
                        <form action="/jadwalkerjapegawaiedit" method="post">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label>Jenis Jadwal Kerja</label>
                                            <select class="form-control select2" name="jadwalkerjamasuk" data-placeholder="Jenis Jadwal Kerja">
                                                @foreach($jadwalkerjas as $jadwalkerja)
                                                    <option value="{{$jadwalkerja->id}}">{{$jadwalkerja->jenis_jadwal}} ({{$jadwalkerja->jamsebelum_masukkerja}} - {{$jadwalkerja->jam_masukjadwal}} >> {{$jadwalkerja->jam_keluarjadwal}} - {{$jadwalkerja->jamsebelum_pulangkerja}}) [{{$jadwalkerja->sifat}}] [{{$jadwalkerja->singkatan}}]</option>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="tableaja">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select_all" name="select_all" class="flat-red select_all">
                                                    </th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </form>
                        </div>
                        <!-- /.box-body -->

                </div>

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Manajemen Jadwal Kerja Pegawai</h3>



                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                            <form action="/hapusjadwalkerjapegawai" method="post">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-danger btn-flat">Hapus Jadwal</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="tablein">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="select_all2" name="select_all2" class="flat-red select_all">
                                                        </th>
                                                        <th>NIP</th>
                                                        <th>Nama</th>
                                                        <th>Jenis Jadwal</th>
                                                        <th>Tanggal Mulai</th>
                                                        <th>Tanggal Akhir</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>


                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </form>

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

                $('#select_all').on('ifChanged', function(event){
                    if(!this.changed) {
                        this.changed=true;
                        $('.checkbox').iCheck('check');
                    } else {
                        this.changed=false;
                        $('.checkbox').iCheck('uncheck');
                    }
                    $('.checkbox').iCheck('update');
                });

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

    <script type="text/javascript">
        var oTable;
        $(function() {
            oTable = $('#tableaja').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datapegawaijadwalkerja')}}',
                columns: [
                    { data: 'action', name: 'action',orderable: false },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'keterangan', name: 'keterangan' },
                ]
            });
        });
    </script>

    <script type="text/javascript">
        var oTable;
        $(function() {
            oTable = $('#tablein').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datapegawairulejadwalkerja')}}',
                columns: [
                    { data: 'action', name: 'action',orderable: false },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'jenis_jadwal', name: 'jenis_jadwal'},
                    { data: 'tanggal_awalrule', name: 'tanggal_awalrule'},
                    { data: 'tanggal_akhirrule', name: 'tanggal_akhirrule'},
                    { data: 'aksi', name: 'aksi'}
                ]
            });
        });
    </script>

@endsection
