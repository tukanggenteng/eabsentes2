@extends('layouts.app')

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
    <body class="hold-transition skin-blue sidebar-mini">
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
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    {{session('err')}}
                </div>
                @endif
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Monitoring Rekap Mingguan Instansi Detail</h3>

                        <div class="box-tools">
                        </div>

                    </div>
                    <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <form action="/monitoring/instansi/{{encrypt($id)}}/{{encrypt($tanggal)}}" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            Instansi : 
                                            <label>{{$namainstansi}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label>Tanggal berlaku</label>
                                            <input type="text" class="form-control" id="tanggal" readonly value="{{$date}}" name="tanggal"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label>Jenis Absen</label>
                                            <select class="form-control select2" name="jenis_absen" value="{{ $jenis_absen2 }}" data-placeholder="Jenis Absen">
                                                @foreach($jenis_absens as $jenisabsen)
                                                    @if ($jenisabsen->id==($jenis_absen2))
                                                        <option value="{{($jenisabsen->id)}}" selected>{{$jenisabsen->jenis_absen}}</option>
                                                    @else
                                                        <option value="{{($jenisabsen->id)}}">{{$jenisabsen->jenis_absen}}</option>
                                                    @endif
                                                @endforeach
                                                <option value="20">Apel Bulanan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label>Pencarian</label>
                                            <select class="form-control select2" name="metode" value="{{$metode}}" data-placeholder="Jenis Absen">
                                                @if ($metode=='DESC')
                                                <option value="DESC" selected>Terbanyak</option>
                                                @else
                                                <option value="DESC">Terbanyak</option>
                                                @endif
                                                @if ($metode=='ASC')
                                                <option value="ASC" selected>Terkecil</option>
                                                @else
                                                <option value="ASC">Terkecil</option>
                                                @endif                                                
                                            </select>
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
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Tanggal</th>
                                                    <th>Hari Kerja</th>
                                                    <th>Hadir</th>
                                                    <th>Tanpa Kabar</th>
                                                    <th>Ijin</th>
                                                    <th>Ijin Terlambat</th>
                                                    <th>Sakit</th>
                                                    <th>Tugas Luar</th>
                                                    <th>Tugas Belajar</th>
                                                    <th>Terlambat</th>
                                                    <th>Rapat/Undangan</th>
                                                    <th>Pulang Cepat</th>
                                                    <th>Ijin Pulang Cepat</th>
                                                    <th>Apel</th>
                                                    <th>Total Terlambat</th>                                                    
                                                    <th>Total Akumulasi Kerja</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($datas as $data)
                                                    <tr>
                                                        <td><a href="/monitoring/instansi/detail/{{encrypt($data->pegawai_id)}}/{{encrypt($tanggal)}}/{{encrypt($instansi_id)}}">{{$data->nip}}</a></td>
                                                        <td>{{$data->nama}}</td>                                                        
                                                        <td>{{date("m-Y",strtotime($data->periode))}}</td>                                                        
                                                        <td>{{$data->hari_kerja}}</td>
                                                        <td>{{$data->hadir}}</td>
                                                        <td>{{$data->tanpa_kabar}}</td>
                                                        <td>{{$data->ijin}}</td>
                                                        <td>{{$data->ijinterlambat}}</td>
                                                        <td>{{$data->sakit}}</td>
                                                        <td>{{$data->tugas_luar}}</td>
                                                        <td>{{$data->tugas_belajar}}</td>
                                                        <td>{{$data->terlambat}}</td>
                                                        <td>{{$data->rapatundangan}}</td>
                                                        <td>{{$data->pulang_cepat}}</td>
                                                        <td>{{$data->ijinpulangcepat}}</td>
                                                        <td>{{$data->apelbulanan}}</td>
                                                        <td>{{$data->total_terlambat}}</td>
                                                        <td>{{$data->total_akumulasi}}</td>
                                                                                                                
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                        
                                </div>
                            </div>
                            
                            </form>
                        </div>
                        <!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{$datas->appends(['jenis_absen'=>($jenis_absen2),'metode'=>($metode),'tanggal'=>$tanggal])->links()}}
                        </ul>
                    </div>
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
            $('input[name="tanggal"]').datepicker({
                format: "yyyy-mm",
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
