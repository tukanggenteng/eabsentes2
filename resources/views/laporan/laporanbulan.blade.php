@extends('layouts.app')

@section('title')
Rekap Absensi Pegawai Bulanan
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

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                        <h3 class="box-title">Rekap Absensi Pegawai Bulanan</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        @if (isset($nip) && isset($tanggal))
                          <div class="row">
                              <div class="col-md-12">
                                <form action="/laporanbulan" method="post">
                                    <div class="form-group">
                                        <div class="col-md-5">
                                            <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP" value="{{$nip}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-5">
                                            <input type="text" id="tanggal" name="tanggal" readonly class="form-control" value="{{$tanggal}}" placeholder="Periode">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
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
                                        <div class="col-md-5">
                                            <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-5">
                                            <input type="text" id="tanggal" name="tanggal" readonly class="form-control" value="{{$tanggal}}" placeholder="Periode">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
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
                                        <div class="col-md-5">
                                            <input type="text" id="nip" name="nip" class="form-control" value="{{$nip}}"  placeholder="NIP">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-5">
                                            <input type="text" id="tanggal" name="tanggal" readonly class="form-control" placeholder="Periode">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
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
                                        <div class="col-md-5">
                                            <input type="text" id="nip" name="nip" class="form-control" placeholder="NIP">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-5">
                                            <input type="text" id="tanggal" name="tanggal" readonly class="form-control" placeholder="Periode">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-1">
                                            <a class="btn btn-block btn-success" href="/laporanbulan/pdf"><i class="fa fa-print"></i></a>
                                        </div>
                                    </div>
                                  </div>
                                  {{csrf_field()}}
                                </form>
                              </div>
                          </div>
                        @endif
                        <hr>

                        <div class="alert alert-info">
                          <strong>Info!</strong> Untuk mengecek jumlah keterangan pegawai dalam detil harian, dapat dilakukan dengan mengklik tombol <i class='fa fa-sticky-note' style='font-size:20px'></i>.
                          pada sudut sebelah kanan !
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        </div>

                        <hr>
                        <div class="row">
                          <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-align" id="tabelrekap">
                              <thead class="thead-dark table-eabsen">
                                <tr>
                                  <th rowspan="2">NIP</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Periode</th>
                                  <th rowspan="2">Hari Kerja</th>
                                  <th rowspan="2">Hadir</th>
                                  <th rowspan="2">Apel</th>
                                  <th rowspan="2">Akumulasi Jam Kerja</th>
                                  <th colspan="9">Tidak Masuk Kerja</th>
                                  <th colspan="3">Melanggar Ketentuan Jam Kerja</th>
                                  <th rowspan="2"></th>
                                </tr>
                                <tr>
                                  <th>Tanpa Kabar</th>
                                  <th>Ijin</th>
                                  <th>Ijin Terlambat</th>
                                  <th>Ijin Pulang Cepat</th>
                                  <th>Sakit</th>
                                  <th>Cuti</th>
                                  <th>Tugas Luar</th>
                                  <th>Tugas Belajar</th>
                                  <th>Ijin Kepentingan Lain</th>
                                  <th>Terlambat Masuk Kerja</th>
                                  <th>Akumulasi Terlambat</th>
                                  <th>Pulang Cepat</th>
                                </tr>
                              </thead>
                                @foreach($atts as $att)
                                    <tr>
                                        <td>{{$att->nip}}</td>
                                        <td>{{$att->nama}}</td>
                                        <td>{{$att->periode}}</td>
                                        <td style="text-align:right;">{{$att->hari_kerja}}</td>
                                        <td style="text-align:right;">{{$att->hadir}}</td>
                                        <td style="text-align:right;">{{$att->apelbulanan}}</td>
                                        <td style="text-align:center;">{{$att->total_akumulasi}}</td>
                                        <td style="text-align:right;">{{$att->tanpa_kabar}}</td>
                                        <td style="text-align:right;">{{$att->ijin}}</td>
                                        <td style="text-align:right;">{{$att->ijinterlambat}}</td>
                                        <td style="text-align:right;">{{$att->ijinpulangcepat}}</td>
                                        <td style="text-align:right;">{{$att->sakit}}</td>
                                        <td style="text-align:right;">{{$att->cuti}}</td>
                                        <td style="text-align:right;">{{$att->tugas_luar}}</td>
                                        <td style="text-align:right;">{{$att->tugas_belajar}}</td>
                                        <td style="text-align:right;">{{$att->rapatundangan}}</td>
                                        <td style="text-align:right;">{{$att->terlambat}}</td>
                                        <td style="text-align:center;">{{$att->total_terlambat}}</td>
                                        <td style="text-align:right;">{{$att->pulang_cepat}}</td>
                                        <td>
                                          <a href="/laporanharian/bulan/{{encrypt($att->periode)}}/nip/{{encrypt($att->nip)}}">
                                            <i class='fa fa-sticky-note' data-toggle="tooltip" data-placement="left" title="klik, untuk lihat detail harian {{$att->nama}}!" style='font-size:20px'></i>
                                          </a>
                                          <a href="#" data-toggle="" id="backupMantra" data-target="" class="modal_backupmantra">
                                            <i class="material-icons" data-toggle="tooltip" data-placement="left" title="klik, untuk backup rekap {{$att->nama}}!">backup</i>
                                          </a>
                                        </td>
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

        <!-- modal backup ke Mantra-->
        <div class="modal fade" id="modal_backupmantra">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Data Rekap Absensi Pegawai</h4>
                    </div>
                    <div class="modal-body">
                        <div class="error alert-danger alert-dismissible">
                        </div>
                        <form id="formbackupmantra" method="post" role="form" enctype="multipart/form-data">
                            <div class="row form-horizontal">
                                <div class="col-md-12">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      {{csrf_field()}}
                                      <label class="control-label col-sm-4" for="nip">NIP</label>
                                      <div class="col-sm-8">
                                          <input class="form-control input-sm" id="nip_m" name="nip" type="text">
                                      </div>
                                    </div>
                                  </div>
                                    <!-- /.form-group -->
                                </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="periode">Periode</label>
                                      <div class="col-sm-8">
                                        <input id="periode" name="periode" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="harikerja">Hari Kerja</label>
                                      <div class="col-sm-8">
                                        <input id="harikerja" name="harikerja" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="hadir">Hadir</label>
                                      <div class="col-sm-8">
                                        <input id="hadir" name="hadir" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="apel">Apel</label>
                                      <div class="col-sm-8">
                                        <input id="apel" name="apel" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="akumulasi_jk">Akumulasi Jam Kerja</label>
                                      <div class="col-sm-8">
                                        <input id="akumulasi_jk" name="akumulasi_jk" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="tanpakabar">Tanpa Kabar</label>
                                      <div class="col-sm-8">
                                        <input id="tanpakabar" name="tanpakabar" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="ijin">Ijin</label>
                                      <div class="col-sm-8">
                                        <input id="ijin" name="ijin" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="ijin_t">Ijin Terlambat</label>
                                      <div class="col-sm-8">
                                        <input id="ijin_t" name="ijin_t" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="ijin_pc">Ijin Pulang Cepat</label>
                                      <div class="col-sm-8">
                                        <input id="ijin_pc" name="ijin_pc" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="sakit">Sakit</label>
                                      <div class="col-sm-8">
                                        <input id="sakit" name="sakit" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="cuti">Cuti</label>
                                      <div class="col-sm-8">
                                        <input id="cuti" name="cuti" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="tugasluar">Tugas Luar</label>
                                      <div class="col-sm-8">
                                        <input id="tugasluar" name="tugasluar" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="tugasbelajar">Tugas Belajar</label>
                                      <div class="col-sm-8">
                                        <input id="tugasbelajar" name="tugasbelajar" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="ijin_kl">Ijin Kepentingan Lain</label>
                                      <div class="col-sm-8">
                                        <input id="ijin_kl" name="ijin_kl" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="terlambat_mk">Terlambat Masuk Kerja</label>
                                      <div class="col-sm-8">
                                        <input id="terlambat_mk" name="terlambat_mk" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="akumulasi_t">Akumulasi Terlambat</label>
                                      <div class="col-sm-8">
                                        <input id="akumulasi_t" name="akumulasi_t" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row form-horizontal">
                              <div class="col-md-12">
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <label class="control-label col-sm-4" for="pulangcepat">Pulang Cepat</label>
                                      <div class="col-sm-8">
                                        <input id="pulangcepat" name="pulangcepat" class="form-control input-sm" type="text">
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
                        <button type="button" id="simpanaddpegawai" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();
        });
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

        $(document).ready(function(){
          $("#tabelrekap").on('click','.modal_backupmantra', function(){
            //get current row
            var currentRow = $(this).closest('tr');

            var nip = currentRow.find('td:eq(0)').text();
            var periode = currentRow.find('td:eq(2)').text();
            var harikerja = currentRow.find('td:eq(3)').text();
            var hadir = currentRow.find('td:eq(4)').text();
            var apel = currentRow.find('td:eq(5)').text();
            var akumulasi_jk = currentRow.find('td:eq(6)').text();
            var tanpakabar = currentRow.find('td:eq(7)').text();
            var ijin = currentRow.find('td:eq(8)').text();
            var ijin_t = currentRow.find('td:eq(9)').text();
            var ijin_pc = currentRow.find('td:eq(10)').text();
            var sakit = currentRow.find('td:eq(11)').text();
            var cuti = currentRow.find('td:eq(12)').text();
            var tugasluar = currentRow.find('td:eq(13)').text();
            var tugasbelajar = currentRow.find('td:eq(14)').text();
            var ijin_kl = currentRow.find('td:eq(15)').text();
            var terlambat_mk = currentRow.find('td:eq(16)').text();
            var akumulasi_t = currentRow.find('td:eq(17)').text();
            var pulangcepat = currentRow.find('td:eq(18)').text();
            //console.log(periode);

            var _token=$("input[name=_token]").val();

            $.ajax({
                type:'post',
                url: '{{route('saveToMantra')}}',
                data : {
                        nip:nip, periode:periode, harikerja:harikerja, hadir:hadir, apel:apel, akumulasi_jk:akumulasi_jk, tanpakabar:tanpakabar,
                        ijin:ijin, ijin_t:ijin_t, ijin_pc:ijin_pc, sakit:sakit, cuti:cuti, tugasluar:tugasluar, tugasbelajar:tugasbelajar,
                        ijin_kl:ijin_kl, terlambat_mk:terlambat_mk, akumulasi_t:akumulasi_t, pulangcepat:pulangcepat,
                        _token:_token
                        },
                beforeSend:function () {
                  $('.material-icons').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success:function(response){
                  // alert(response['namaInstansi']);
                  if (response['status']=='0') {
                    swal(response.message, "", "error");
                    $('.material-icons').html('<i class="material-icons">backup</i>');
                    //console.log(response);
                  }
                  else {
                    swal(response.message+" Menyimpan Data", "", "success");
                    $('.material-icons').html('<i class="material-icons">backup</i>');
                    //console.log(response);
                  }

                },

            });

          });
        });


    </script>

@endsection
