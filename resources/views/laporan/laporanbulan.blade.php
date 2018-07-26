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

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Rekap Absensi Pegawai Bulanan</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        @if (isset($nip) && isset($tanggal))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanbulan" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" placeholder="NIP" value="{{$nip}}">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" readonly class="form-control pull-right" value="{{$tanggal}}" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanbulan/pdf/tanggal/{{encrypt($tanggal)}}/nip/{{encrypt($nip)}}"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @elseif (isset($tanggal) && !isset($nip))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanbulan" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" placeholder="NIP">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" readonly class="form-control pull-right" value="{{$tanggal}}" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanbulan/pdf/tanggal/{{encrypt($tanggal)}}"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @elseif (isset($nip) && !isset($tanggal))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanbulan" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" value="{{$nip}}"  placeholder="NIP">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" readonly class="form-control pull-right" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanbulan/pdf/nip/{{encrypt($nip)}}"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @else
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanbulan" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" placeholder="NIP">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" readonly class="form-control pull-right" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanbulan/pdf"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @endif
                        <hr>
                        <div class="row">
                          <div class="col-md-12">
                            <table class="table table-hover">
                                <tr>
                                  <th>NIP</th>
                                  <th>Nama</th>
                                  <th>Periode</th>
                                  <th>Hari Kerja</th>
                                  <th>Hadir</th>
                                  <th>Tanpa Kabar</th>
                                  <th>Ijin</th>
                                  <th>Ijin Terlambat</th>
                                  <th>Ijin Pulang Cepat</th>
                                  <th>Sakit</th>
                                  <th>Cuti</th>
                                  <th>Tugas Luar</th>
                                  <th>Tugas Belajar</th>
                                  <th>Rapat/Undangan</th>
                                  <th>Pulang Cepat</th>
                                  <th>Apel</th>
                                  <th>Akumulasi Terlambat</th>
                                  <th>Akumulasi Jam Kerja</th>
                                </tr>

                                @foreach($atts as $att)
                                    <tr>
                                        <td>{{$att->nip}}</td>
                                        <td>{{$att->nama}}</td>
                                        <td>{{$att->periode}}</td>
                                        <td>{{$att->hari_kerja}}</td>
                                        <td>{{$att->hadir}}</td>
                                        <td>{{$att->tanpa_kabar}}</td>
                                        <td>{{$att->ijin}}</td>
                                        <td>{{$att->ijinterlambat}}</td>
                                        <td>{{$att->ijinpulangcepat}}</td>
                                        <td>{{$att->sakit}}</td>
                                        <td>{{$att->cuti}}</td>
                                        <td>{{$att->tugas_luar}}</td>
                                        <td>{{$att->tugas_belajar}}</td>
                                        <td>{{$att->rapatundangan}}</td>
                                        <td>{{$att->pulang_cepat}}</td>
                                        <td>{{$att->apelbulanan}}</td>
                                        <td>{{$att->total_terlambat}}</td>
                                        <td>{{$att->total_akumulasi}}</td>
                                    </tr>
                                @endforeach
                            </table>
                          </div>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{$atts->appends(['nip'=>($nip),'tanggal'=>($tanggal)])->links()}}
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
            $('input[name="tanggal"]').datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months"
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
