@extends('layouts.app')

@section('title')
Manajemen Hari Libur 
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

        @if ((session('success')!=null))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-bell"></i> Berhasil!</h4>
            {{session('success')}}
        </div>
        @endif
        @if  ((session('error')))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-bell"></i> Gagal!</h4>
            Gagal menyimpan data!
        </div>
        @endif

            <div class="row">

                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Tambah Hari Libur</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <form action="/harilibur/store" method="post">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="namaharilibur" name="namaharilibur" placeholder="Nama Hari Libur">
                                    </div>
                                </div>
                            </div>
                            </div>

                            {{csrf_field()}}
                            <!-- /.box-body -->
                            <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Submit <i class="fa fa-chevron-circle-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                

            </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cari Hari Libur</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <form action="{{route('carinamaharilibur')}}" method="post">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="box-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="namaharilibur" name="namaharilibur" placeholder="Nama Hari Libur">
                                    </div>
                                </div>
                            </div>
                            </div>

                            {{csrf_field()}}
                            <!-- /.box-body -->
                            <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Search <i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Hari Libur Nasional</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nama Hari Libur</th>
                                        <th>Tool</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($hariliburs as $key=>$harilibur)
                                    <tr>
                                        <td>{{$harilibur->nama_hari_libur}}</td>
                                        <td>
                                                <a class="btn-sm btn-success" href="/harilibur/show/{{encrypt($harilibur->id)}}"><i class="fa fa-edit"></i></a>
                                                <a class="btn-warning btn-sm" href="/harilibur/delete/{{encrypt($harilibur->id)}}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

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
