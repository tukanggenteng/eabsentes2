@extends('layouts.app')

@section('title')
Rekap Absensi Pegawai Harian
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

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Rekap Absensi Pegawai Harian</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        @if (isset($nip) && isset($tanggal))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanharian" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" placeholder="NIP" value="{{$nip}}">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" class="form-control pull-right" value="{{$tanggal}}" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanharian/pdf/tanggal/{{encrypt($tanggal)}}/nip/{{encrypt($nip)}}"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @elseif (isset($tanggal) && !isset($nip))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanharian" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" placeholder="NIP">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" class="form-control pull-right" value="{{$tanggal}}" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanharian/pdf/tanggal/{{encrypt($tanggal)}}"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @elseif (isset($nip) && !isset($tanggal))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanharian" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" value="{{$nip}}"  placeholder="NIP">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" class="form-control pull-right" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanharian/pdf/nip/{{encrypt($nip)}}"><i class="fa fa-print"></i></a>
                                      </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @else
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanharian" method="post">
                                  <div class="form-group">
                                      <div class="col-md-4">
                                          <input type="text" id="nip" name="nip" class="form-control pull-right" placeholder="NIP">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" id="tanggal" name="tanggal" class="form-control pull-right" placeholder="Periode">
                                      </div>
                                      <div class="col-md-1">
                                          <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                      </div>
                                      <div class="col-md-1">
                                          <a class="btn btn-block btn-success" href="/laporanharian/pdf"><i class="fa fa-print"></i></a>
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
                            <table class="table table-striped table-bordered table-hover table-align">
                              <thead class="thead-dark table-eabsen">
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Hari</th>
                                    <th style="width: 85px;">Tanggal</th>
                                    <th>Apel</th>
                                    <th>Terlambat</th>
                                    <th>Jam Masuk</th>
                                    <th>Mulai Istirahat</th>
                                    <th>Selesai Istirahat</th>
                                    <th>Jam Pulang</th>
                                    <th>Akumulasi Kerja</th>
                                    <th>Keterangan</th>
                                    <th>Jenis Jadwal</th>
                                    <th>Sifat Jadwal</th>
                                    <th>Instansi</th>
                                  </tr>
                                </thead>

                                @foreach($atts as $att)
                                    <tr>
                                        <td>{{$att->nip}}</td>
                                        <td>{{$att->nama}}</td>
                                        <td>{{$att->hari}}</td>
                                        <td>{{$hari = date("d-m-Y", strtotime($att->tanggal_att)) }}</td>
                                        <td style="text-align:center;">
                                          @if ($att->apel=="0")
                                            <p class="text-danger bg-danger">Tidak Apel</p>
                                          @else
                                            <p class="text-success bg-success">Apel</p>
                                          @endif
                                        </td>
                                        <td style="text-align:center;">
                                          @if ($att->terlambat>0)
                                            <p class="text-danger bg-danger">{{$att->terlambat}}</p>
                                          @else
                                            {{$att->terlambat}}
                                          @endif
                                        </td>
                                        <td style="text-align:center;">{{$att->jam_masuk}}</td>
                                        <td style="text-align:center;">{{$att->keluaristirahat}}</td>
                                        <td style="text-align:center;">{{$att->masukistirahat}}</td>
                                        <td style="text-align:center;">{{$att->jam_keluar}}</td>
                                        <td style="text-align:center;">{{$att->akumulasi_sehari}}</td>
                                        <td style="text-align:center;">
                                          @if ($att->jenis_absen=="Hadir")
                                            <p class="text-success bg-success">{{$att->jenis_absen}}</p>
                                          @elseif ($att->jenis_absen=="Tanpa Kabar")
                                            <p class="text-danger bg-danger">{{$att->jenis_absen}}</p>
                                          @else
                                            <p class="text-warning bg-warning">{{$att->jenis_absen}}</p>
                                          @endif
                                        </td>
                                        <td style="text-align:center;">{{$att->jenis_jadwal}}</td>
                                        <td style="text-align:center;">
                                          @if ($att->sifat=="WA")
                                            Wajib Apel
                                          @elseif ($att->sifat=="TWA")
                                            Tidak Wajib Apel
                                          @elseif ($att->sifat=="FD")
                                            Full Day
                                          @endif
                                        </td>
                                        <td style="text-align:center;">{{$att->namainstansimasuk}}</td>
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

    <!-- jQuery 3 -->
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->

    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
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
                format: "yyyy-mm-dd",
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

@endsection
