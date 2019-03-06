@extends('layouts.app')

@section('title')
Manajemen Keterangan Absensi
@endsection

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
            @if ((session('status')))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-bell"></i> Perhatian!</h4>
                {{session('status')}}
            </div>
            @endif
            @if ((session('error')))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-bell"></i> Perhatian!</h4>
                {{session('error')}}
            </div>
            @endif
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Keterangan Absensi Pegawai</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <form action="/rekapabsensipegawai/edit" method="post">
                        <div class="row">
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
                                                <option value="{{encrypt($jenisabsen->id)}}">{{$jenisabsen->jenis_absen}}</option>
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
                                        <input type="checkbox" name="checkbox[]" value="{{encrypt($jadwalkerja->id)}}" class="flat-red"> {{$jadwalkerja->jenis_jadwal}}  ({{$jadwalkerja->jam_masukjadwal}} - {{$jadwalkerja->jam_keluarjadwal}}) [{{$jadwalkerja->sifat}}] [{{$jadwalkerja->singkatan}}]
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
                    <hr>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <form action="/rekapabsensipegawai" method="post">
                                        {{csrf_field()}}
                                        <input type="text" id="caridata" name="caridata" placeholder="NIP/Nama" value="{{$cari}}">
                                        <button type="submit" name="button"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tableaja" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select_all" name="select_all" class="flat-red select_all">
                                                </th>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Tanggal</th>
                                                <th>Jam Masuk</th>
                                                <th>Keterangan Masuk</th>
                                                <th>Mulai Istirahat</th>
                                                <th>Selesai Istirahat</th>
                                                <th>Jam Keluar</th>
                                                <th>Keterangan Keluar</th>
                                                <th>Jadwal Kerja</th>
                                                <th>Akumulasi</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <!-- @foreach($atts as $att)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="checkboxnip[]" value="{{$att->id}}" class="flat-red checkbox">
                                                </td>
                                                <td>{{$att->nip}}</td>
                                                <td>{{$att->nama}}</td>
                                                <td>{{$att->tanggal_att}}</td>
                                                <td>{{$att->jam_masuk}}</td>
                                                <td>{{$att->namainstansimasuk}}</td>
                                                <td>{{$att->jam_keluar}}</td>
                                                <td>{{$att->namainstansikeluar}}</td>
                                                <td>{{$att->jenis_jadwal}}</td>
                                                <td>{{$att->akumulasi_sehari}}</td>
                                                <td>{{$att->jenis_absen}}</td>
                                            </tr>
                                        @endforeach -->
                                    </table>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- /.box-body -->

                <!-- <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        {{$atts->links()}}
                    </ul>
                </div> -->
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


    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

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
        // $(function() {
        //     $('input[name="periode"]').datepicker({
        //         format: "mm-yyyy",
        //         startView: "months",
        //         startDate:"-1m",
        //         endDate:"-1m",
        //         minViewMode: "months"
        //     });
        // });
        $(function() {


            var minDate="{{$akhir}}";
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
                ajax: '{{route('dataatts')}}',
                columns: [
                    { data: 'action', name: 'action',orderable: false },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'tanggal_att', name: 'tanggal_att' },
                    { data: 'jam_masuk', name: 'jam_masuk' },
                    { data: 'keteranganmasuk_id', name: 'keteranganmasuk_id' },
                    { data: 'keluaristirahat', name: 'keluaristirahat' },
                    { data: 'masukistirahat', name: 'masukistirahat' },
                    { data: 'jam_keluar', name: 'jam_keluar' },
                    { data: 'keterangankeluar_id', name: 'keterangankeluar_id' },
                    { data: 'jenis_jadwal', name: 'jenis_jadwal' },
                    { data: 'akumulasi_sehari', name: 'akumulasi_sehari' },
                    { data: 'jenis_absen', name: 'jenis_absen' },
                ]
            });
        });
    </script>

@endsection
