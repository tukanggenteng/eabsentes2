@extends('layouts.app')

@section('title')
Antrian Distribusi Pegawai
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- daterange picker -->
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

@endpush


@section('body')
    <body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">

      @include('layouts.header')

      @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

          <!-- Main content -->
        <section class="content">
            <!-- form addrole         -->

        <div class="row">
          <!-- left column -->
          <form id="formtrans">
          <div class="col-md-3">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Cari Antrian</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="box-body">
                        <form action="{{route('queuepegawaisearchpost')}}" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" id="nip" name="nip" value="{{$request_nip}}" placeholder="NIP">
                            </div>
                            @if (Auth::user()->role_id==2)
                            <div class="form-group">
                                <select id="instansi" name="instansi" class="form-control select2" style="width:100%;" type="text">
                                    @foreach ($instansis as $key => $instansi)
                                        @if ($instansi->id== $request_instansi)
                                            <option value="{{$instansi->id}}" selected>{{$instansi->namaInstansi}}</option>
                                        @else
                                            <option value="{{$instansi->id}}">{{$instansi->namaInstansi}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-group">
                                <input type="text" class="form-control" id="fingerprint_ip" name="fingerprint_ip" value="{{$request_fingerprint_ip}}" placeholder="Fingerprint IP">
                            </div>
                        </form>



                    </div>
                  </div>
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                  <button type="submit" id="submitpelanggan" name="submitpelanggan" value="cari" class="btn btn-primary btn-sm">Submit <i class="fa fa-chevron-circle-right"></i></button>
                </div>
            </div>
          </div>
          </form>

          <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Antrian Distribusi Pegawai</h3>
              </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Fingerprint</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Perintah</th>
                                <th>Status</th>
                                @if (Auth::user()->role_id==2)
                                    <th>Instansi</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($dataqueuepegawais as $key=>$dataqueuepegawai)
                            <tr>
                                <td>{{$dataqueuepegawai->fingerprint_ip}}</td>
                                <td>{{$dataqueuepegawai->nip}}</td> 
                                <td>{{$dataqueuepegawai->nama}}</td>
                                <td>{{strtoupper($dataqueuepegawai->command)}}</td>
                                @if ($dataqueuepegawai->status)
                                    <td><i class="fa fa-check-circle" aria-hidden="true"></i></td>
                                @else
                                    <td ><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                @endif 
                                @if (Auth::user()->role_id==2)
                                    <td>{{$dataqueuepegawai->namaInstansi}}</td> 
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        {{$dataqueuepegawais->appends(['nip'=>$request_nip,'instansi_id'=>$request_instansi,'fingerprint_ip'=>$request_fingerprint_ip])->links()}}
                    </ul>
                </div>
            </div>
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
    <!-- Select2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- FastClick -->

    <script src="{{asset('bower_components/jquery-ui/jquery-ui-new.js')}}"></script>

    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>


    <script src="{{asset('bower_components/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
    <!-- <script src="{{asset('bower_components/jquery-number/jquery.number.js')}}"></script> -->

    <!-- sweet alert -->
    <script src="{{asset('bower_components/sweetalert/sweetalert.min.js')}}"></script>

    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script>
        $('.select2').select2()
    </script>
    </body>
@endsection
