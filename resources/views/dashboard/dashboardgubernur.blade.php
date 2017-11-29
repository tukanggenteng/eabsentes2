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

<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endpush
@section('body')
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="/home/pegawai" class="navbar-brand"><b>e-Absen</b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">

                  <li class="dropdown user user-menu">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <img src="{{asset('dist/img/avatarumum.png')}}" class="user-image" alt="User Image">
                          <span class="hidden-xs">{{Auth::user()->name}}</span>
                      </a>
                      <ul class="dropdown-menu">
                          <!-- User image -->
                          <li class="user-header">
                              <img src="{{asset('dist/img/avatarumum.png')}}" class="img-circle" alt="User Image">

                              <p>
                                  {{Auth::user()->name}}
                                  <small>{{Auth::user()->instansi->namaInstansi}}</small>
                              </p>
                          </li>
                          <!-- Menu Body -->
                          <!-- Menu Footer-->
                          <li class="user-footer">
                              <div class="pull-left">
                                  <a href="/changepassword" class="btn btn-default btn-flat">Ubah Password</a>
                              </div>
                              <div class="pull-right">
                                  <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                              </div>
                          </li>
                      </ul>
                  </li>
              </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">

      <!-- Main content -->
      <section class="content">


        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Data Pegawai</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tableaja" class="table">
                                        <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Data Lengkap Pegawai</h4>
                    </div>
                    <div class="modal-body">
                        <div class="error alert-danger alert-dismissible">
                        </div>
                        <form id="formit" method="post" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">NIP </label>
                                        <label class="col-md-8"><div id="nip"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Gelar Depan </label>
                                        <label class="col-md-8"><div id="gelardepan"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Gelar Belakang </label>
                                        <label class="col-md-8"><div id="gelarbelakang"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Nama </label>
                                        <label class="col-md-8"><div id="nama"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Golongan </label>
                                        <label class="col-md-8"><div id="golongan"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Jabatan </label>
                                        <label class="col-md-8"><div id="jabatan"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Unit Kerja </label>
                                        <label class="col-md-8"><div id="unker"></div></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4">Satuan Kerja</label>
                                        <label class="col-md-8"><div id="satker"></div></label>
                                    </div>
                                </div>
                                    <!-- /.form-group -->
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>

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

<script type="text/javascript">
        var oTable;
        $(function() {
            oTable = $('#tableaja').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datapegawaigub')}}',
                columns: [
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action' },
                ]
            });
        });

        $(document).on('click','.modal_data',function () {
                var nip=$(this).data('nip');
                $.ajax({
                    type:'get',
                    url:'/pegawai/gub/'+nip,
                    dataType:'json',
                    beforeSend:function () {
                        $('.input-group-addon').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success:function(response){
                        $('#nip').html(": "+response[0]['nip']);
                        $('#nama').html(": "+response[0]['nama']);
                        $('#gelardepan').html(": "+response[0]['gelar_depan']);
                        $('#gelarbelakang').html(": "+response[0]['gelar_belakang']);
                        $('#nama').html(": "+response[0]['nama']);
                        $('#golongan').html(": "+response[0]['golongan']);
                        $('#jabatan').html(": "+response[0]['jabatan']);
                        $('#unker').html(": "+response[0]['unker']);
                        $('#satker').html(": "+response[0]['satker']);
                    },
                });
        });
</script>

</body>

@endsection
