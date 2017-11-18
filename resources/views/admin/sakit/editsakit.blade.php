@extends('layouts.app')
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
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">


        @include('layouts.header')

        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Revisi Ijin</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">

                                      <form id="formijin" action="/sakit/admin/update/{{$sakit->id}}" method="post" role="form" enctype="multipart/form-data">
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div class="form-group">
                                                      <label>NIP</label>
                                                      <input id="nip" name="nip" value="{{$sakit->nip}}" readonly class="form-control pull-right" type="text">
                                                  </div>
                                                  <div class="form-group">
                                                      <label>Nama</label>
                                                      <input id="nama" name="nama" value="{{$sakit->nama}}" readonly class="form-control pull-right" type="text">
                                                      {{csrf_field()}}
                                                  </div>
                                                  <div class="form-group">
                                                      <label>Lama Hari</label>
                                                      <input class="form-control" id="lama" type="text" readonly value="{{$sakit->lama}}" name="lama" >
                                                      <input class="form-control" id="id" type="hidden" readonly value="{{$sakit->id}}" name="id" >
                                                  </div>
                                                  <div class="form-group" >
                                                      <label>Sejak Tanggal</label>
                                                      <input id="tanggal" readonly name="tanggal" readonly value="{{$sakit->mulaitanggal}}" class="form-control datepicker pull-right" type="text">
                                                  </div>
                                                  <div class="form-group" >
                                                      <label>Upload File</label>
                                                      <input id="file" name="file" class="filestyle" data-btnClass="btn-primary" type="file">
                                                  </div>
                                                  <!-- /.form-group -->
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div class="form-group">
                                                      <div class="col-md-6">
                                                          <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </form>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- /.modal -->
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
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    {{--<script src="{{asset('dist/js/demo.js')}}"></script>--}}
    <!-- Page script -->
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

        })
    </script>
    </body>
@endsection
