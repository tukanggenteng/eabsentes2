@extends('layouts.app')

@section('title')
Manajemen Pegawai Rumah Sakit
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

<!-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> -->

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

@endpush

@section('body')
    <div class="wrapper">

      @include('layouts.header')

      @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

          <!-- Main content -->
          <section class="content">
                        <!-- form ruangan -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Table Ruangan</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" id="tambahruangan" data-target="#modal_addruangan">
                                                Tambah
                                            </button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="tableruangan" class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Nama Ruangan</th>
                                                            <th>Action</th>
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
                        <!-- form ruangan end -->
                        <!-- modal add ruangan -->
                        <div class="modal fade" id="modal_addruangan">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Tambah Ruangan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formaddruangan" role="form">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                            <div class="form-group">
                                                              <label>Ruangan</label>
                                                              <input id="ruangan" name="ruangan" class="form-control pull-right" type="text">
                                                              {{csrf_field()}}
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpanaddruangan" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal add ruangan end -->
                        <!-- modal edit ruangan -->
                        <div class="modal fade" id="modal_editruangan">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Edit Ruangan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formeditruangan" method="post" role="form" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                            <div class="form-group">
                                                              <label>Ruangan</label>
                                                              <input type="hidden" id="id_ruangan" name="id_ruangan">
                                                              <input id="edit_ruangan" name="edit_ruangan" class="form-control pull-right" type="text">
                                                              {{csrf_field()}}
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpaneditruangan" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal edit ruangan end -->
                        <!-- modal delete ruangan -->
                        <div class="modal modal-danger fade" id="modal_deleteruangan">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Peringatan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdeletepegawai" action="" method="post" role="form" enctype="multipart/form-data">
                                            <h4>
                                                <i class="icon fa fa-ban"></i>
                                                Peringatan
                                            </h4>
                                            {{csrf_field()}}
                                            Yakin ingin menghapus ruangan <span class="labelruangan"></span> ?
                                            <input id="delidruangan" name="delidruangan" type="hidden">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpandelruangan" class="btn btn-outline">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                                    <!-- /.modal-dialog -->
                        </div>
                        <!-- modal delete ruangan end -->

                        <!-- form pegawai biasa -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Manajemen Pegawai</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" id="tambah" data-target="#modal_add">
                                                Tambah
                                            </button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="tableaja" class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>NIP</th>
                                                            <th>Nama</th>
                                                            <th>Instansi</th>
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
                        <!-- form pegawai biasa end -->
                        <!-- modal add pegawai -->
                        <div class="modal fade" id="modal_add">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Manajemen Pegawai</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="error alert-danger alert-dismissible">
                                        </div>
                                        <form id="formpegawaiadd" method="post" role="form" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input class="form-control timepicker" id="nip" name="nip" type="text">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-search"></i>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <!-- /.form-group -->
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Nama</label>
                                                        <input id="nama" name="nama" readonly class="form-control pull-right" type="text">
                                                        {{csrf_field()}}
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <div class="form-group" >
                                                        <label>Instansi</label>
                                                        <input id="instansi" value="{{Auth::user()->instansi_id}}" name="instansi" hidden readonly type="hidden">
                                                        <input readonly class="form-control pull-right" type="text" value="{{Auth::user()->instansi->namaInstansi}}">

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
                        <!-- modal add pegawai end -->
                        <!-- modal delete -->
                        <div class="modal modal-danger fade" id="modal_delete">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Peringatan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdeletepegawai" action="" method="post" role="form" enctype="multipart/form-data">
                                            <h4>
                                                <i class="icon fa fa-ban"></i>
                                                Peringatan
                                            </h4>
                                            {{csrf_field()}}
                                            Yakin ingin menghapus pegawai <span class="labelpegawai"></span> dari instansi anda ?
                                            <input id="delidpegawai" name="delidpegawai" type="hidden">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpandelpegawai" class="btn btn-outline">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal delete end -->

                        <!-- form pegawai 24 -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Manajemen Pegawai</h3><small><strong> 24 Jam</strong></small>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" id="tambah24" data-target="#modal_add24">
                                                Tambah
                                            </button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="tableaja24" class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>NIP</th>
                                                            <th>Nama</th>
                                                            <th>Instansi</th>
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
                        <!-- form pegawai 24 end -->
                        <!-- modal add pegawai 24 -->
                        <div class="modal fade" id="modal_add24">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Manajemen Pegawai 24 Jam</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="error alert-danger alert-dismissible">
                                        </div>
                                        <form id="formpegawaiadd24" method="post" role="form" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        <!-- <div class="input-group">
                                                            <input class="form-control timepicker" id="nip" name="nip" type="text">
                                                            <div class="input-group-addon">
                                                            <i class="fa fa-search"></i>
                                                            </div>
                                                        </div> -->
                                                        <select id="pegawaidokter" name="pegawaidokter[]" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                        </select>
                                                    </div>
                                                    <!-- /.form-group -->
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpanaddpegawai24" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal add pegawai 24 end -->
                        <!-- modal delete pegawai 24 -->
                        <div class="modal modal-danger fade" id="modal_delete24">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Peringatan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdeletepegawai24" action="" method="post" role="form" enctype="multipart/form-data">
                                            <h4>
                                                <i class="icon fa fa-ban"></i>
                                                Peringatan
                                            </h4>
                                            {{csrf_field()}}
                                            Yakin ingin menghapus pegawai <span class="labelpegawai24"></span> dari daftar 24 jam anda ?
                                            <input id="delidpegawai24" name="delidpegawai24" type="hidden">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpandelpegawai24" class="btn btn-outline">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal delete pegawai 24 end -->

                        <!-- form perawat -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Manajemen Pegawai</h3><small><strong> Ruangan Khusus </strong></small>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" id="tambahruanganpegawai" data-target="#modal_addperawat">
                                                Tambah
                                            </button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="tablepegawai" class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>NIP</th>
                                                                <th>Nama</th>
                                                                <th>Ruangan</th>
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
                        <!-- form perawat end -->
                        <!-- modal add pegawai ruangan -->
                        <div class="modal fade" id="modal_addperawat">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Manajemen Pegawai Perruangan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="error alert-danger alert-dismissible">
                                        </div>
                                        <form id="formpegawaiaddruangan" method="post" role="form" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <select id="pegawaiperawat" name="pegawaiperawat[]" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Ruangan</label>
                                                        <select class="form-control select2" id="tambah_pegawairuangan" name="tambah_pegawairuangan[]" tabindex="-1" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpanaddpegawairuangan" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal add pegawai ruangan end -->
                        <!-- modal delete pegawai 24 -->
                        <div class="modal modal-danger fade" id="modal_deleteperawat">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Peringatan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdeletepegawai24" action="" method="post" role="form" enctype="multipart/form-data">
                                            <h4>
                                                <i class="icon fa fa-ban"></i>
                                                Peringatan
                                            </h4>
                                            {{csrf_field()}}
                                            Yakin ingin menghapus pegawai <span class="labelpegawaiperawat"></span> ?
                                            <input id="delidpegawaiperawat" name="delidpegawaiperawat" type="hidden">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Keluar</button>
                                        <button type="button" id="simpandelpegawaiperawat" class="btn btn-outline">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- modal delete pegawai 24 end -->


            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{asset('bower_components/jquery/dist/jquery.js')}}"></script>
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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- Page script -->
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#pegawaidokter').select2(
            {
                placeholder: "Pilih Pegawai",
                minimumInputLength: 1,
                dropdownParent: $("#modal_add24"),
                ajax: {
                    url: '/datapegawai/caridokter',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }
            );

            $('#pegawaiperawat').select2(
            {
                placeholder: "Pilih Pegawai",
                minimumInputLength: 1,
                dropdownParent: $("#modal_addperawat"),
                ajax: {
                    url: '/datapegawai/caridokter',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }
            );

            $('#tambah_pegawairuangan').select2(
            {
                placeholder: "Pilih Ruangan",
                minimumInputLength: 1,
                dropdownParent: $("#modal_addperawat"),
                ajax: {
                    url: '{{route('cariruangan')}}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }
            );


        })
    </script>

    <script type="text/javascript">

        // script24jam
        var oTable24;
        $(function() {
            oTable24 = $('#tableaja24').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datadokter')}}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'namaInstansi', name: 'namaInstansi' },
                    { data: 'action', name: 'action' }
                ]
            });
        });

        var oTable;
        $(function() {
            oTable = $('#tableaja').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datapegawaikhusus')}}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'namaInstansi', name: 'namaInstansi' },
                    { data: 'action', name: 'action' }
                ]
            });
        });


        var oTablepegawai;
        $(function(){
            oTablepegawai = $('#tablepegawai').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('dataallperawat')}}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'nama_ruangan', name: 'nama_ruangan' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
        var oTableruangan;
        $(function() {
            oTableruangan = $('#tableruangan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('dataruangan')}}',
                columns: [
                    { data: 'nama_ruangan', name: 'nama_ruangan' },
                    { data: 'action', name: 'action' }
                ]
            });


        });
    </script>

    <!-- modal add -->
    <script type="text/javascript">

        $(document).on('click','#tambahruanganpegawai',function () {
            $('#pegawaiperawat').val('').trigger('change');
            $('#pegawairuangan').val('').trigger('change');
        });
        $(document).on('click','#tambahruangan',function () {
            $('#ruangan').val("");
        });

        $(document).on('click','#simpanaddruangan',function (){
            var ruangan=$('#ruangan').val();
            var _token=$("input[name=_token]").val();
            $.ajax({
                type:'POST',
                url:'{{route('storeruangan')}}',
                data : {
                        ruangan:ruangan,
                        _token:_token
                        },
                success:function(response){
                    if((response.errors)){
                        if((response.errors.ruangan)){
                            swal("Ruangan", ""+response.errors.ruangan+"", "error");
                        }
                    }

                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menambah ruangan.", "", "success");
                        oTableruangan.ajax.reload();
                        // oTable.ajax.reload();
                        $('#modal_addruangan').modal('hide');
                    }
                    else
                    {
                        swal("Gagal menambahkan ruangan.", "", "error");
                    }

                },
                error:function(){
                    swal("Gagal menambahkan pegawai.", "", "error");
                }
            });
        });

        // manajemen pegawai biasa
        $(document).on('click','#tambah',function () {
          $('#nip').attr('disabled',false);
          $('#instansi').attr('disabled',false);
          $('#nip').val("");
          $('#nama').val("");
          $('#simpanaddpegawai').attr('disabled',false);
          $('.input-group-addon').html('<i class="fa fa-search"></i>');
        });

        $(document).on('click','#tambah24',function () {
            $('#pegawaidokter').val('').trigger('change');;
        });

        $(document).on('click','#simpanaddpegawai',function (){
            var nip=$('#nip').val();
            var nama=$('#nama').val();
            var instansi=$('#instansi').val();
            var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('editpegawai')}}',
                data : {
                        nip:nip,
                        nama:nama,
                        instansi:instansi,
                        _token:_token
                        },
                success:function(response){
                    if((response== 'Success') || (response== 'success')){

                    }
                    else
                    {
                        $('.error').addClass('hidden');
                        $('#modal_add').modal('hide');
                        oTable.ajax.reload();
                        oTable24.ajax.reload();
                        oTablepegawai.ajax.reload();

                    }
                },
            });
        });

        $('#nip').focusout( function (){
          var nip=$('#nip').val();
          $.ajax({
              type:'get',
              url:'/pegawai/cek/'+nip,
              dataType:'json',
              beforeSend:function () {
                $('.input-group-addon').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success:function(response){
                // alert(response['namaInstansi']);
                if (response['status']=='1')
                {
                  $('#nip').attr('disabled',true);
                  $('#nama').attr('disabled',true);
                  $('#instansi').attr('disabled',true);
                  $('#instansi').val("");
                  $('#instansi').val(response['instansi_id']);
                  $('#nama').val(response['nama']);
                  $('#simpanaddpegawai').attr('disabled',true);
                  $('.input-group-addon').html('<i class="fa fa-times"></i>');
                    swal("Pegawai terdaftar di instansi "+response['namaInstansi']+".", "", "error");
                    $('#modal_add').modal('hide');
                }
                else if (response['status']=='0') {
                  $('#nip').attr('disabled',true);
                  $('#instansi').attr('disabled',false);
                  $('#nama').val(response['nama']);
                  $('.input-group-addon').html('<i class="fa fa-check"></i>');
                }
                else {
                  $('#nip').attr('disabled',true);
                  $('#nama').attr('disabled',true);
                  $('#instansi').attr('disabled',true);
                      swal("NIP Pegawai Tidak Terdaftar", "", "error");
                      $('#modal_add').modal('hide');
                }

                  // $('.error').addClass('hidden');
                  // $('#modal_delete').modal('hide');
                  // oTable.ajax.reload();
              },

          });
        });

        $(document).on('click','#simpanaddpegawai24',function (){
            var nip=$('#pegawaidokter').val();
            var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('storedokter')}}',
                data : {
                        nip:nip,
                        _token:_token
                        },
                success:function(response){

                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menambah pegawai.", "", "success");
                        oTable24.ajax.reload();
                        oTable.ajax.reload();
                        $('#modal_add24').modal('hide');
                    }
                    else
                    {
                        swal("Gagal menambahkan pegawai.", "", "error");
                    }

                },
                error:function(){
                    swal("Gagal menambahkan pegawai.", "", "error");
                }
            });
        });

        $(document).on('click','#simpanaddpegawairuangan',function (){
            var idpegawai=$('#pegawaiperawat').val();
            var idruangan=$('#tambah_pegawairuangan').val();
            var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('storeperawat')}}',
                data : {
                        idpegawai:idpegawai,
                        idruangan:idruangan,
                        _token:_token
                        },
                success:function(response){

                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menambah pegawai.", "", "success");
                        oTablepegawai.ajax.reload();
                        // oTable.ajax.reload();
                        $('#modal_addperawat').modal('hide');
                    }
                    else
                    {
                        swal("Gagal menambahkan pegawai.", "", "error");
                    }

                },
                error:function(){
                    swal("Gagal menambahkan pegawai.", "", "error");
                }
            });
        });

    </script>
    <!-- modal add end -->

    <!-- modal edit -->
    <script type="text/javascript">
        $(document).on('click','.modal_editruangan',function () {
            // alert($(this).data('telepon'));
            $('#edit_ruangan').val($(this).data('ruangan'));
            $('#id_ruangan').val($(this).data('id'));
        });

        $(document).on('click','#simpaneditruangan',function (){
            var edit_ruangan=$('#edit_ruangan').val();
            var id_ruangan=$('#id_ruangan').val();
            var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('updateruangan')}}',
                data : {
                        id_ruangan:id_ruangan,
                        edit_ruangan:edit_ruangan,
                        _token:_token
                        },
                success:function(response){
                    if((response.errors)){
                        if((response.errors.edit_ruangan)){
                            swal("Nama", ""+response.errors.edit_ruangan+"", "error");
                        }
                    }

                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil mengubah ruangan.", "", "success");
                        oTableruangan.ajax.reload();
                        // oTable.ajax.reload();
                        $('#modal_editruangan').modal('hide');
                    }
                    else
                    {
                        swal("Gagal mengubah ruangan.", "", "error");
                    }

                },
                error:function(){
                    swal("Gagal mengubah pegawai.", "", "error");
                }
            });
        });



    </script>
    <!-- modal edit end -->


    <!-- modal delete -->
    <script type="text/javascript">
        $(document).on('click','.modal_delete',function () {
            $('#delidpegawai').val($(this).data('id'));
            $('.labelpegawai').text($(this).data('nama'));
        });
        $(document).on('click','#simpandelpegawai',function (){
          var nip=$('#delidpegawai').val();
          var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('deletepegawai')}}',
                data : {
                        delidpegawai:nip,
                        _token:_token
                        },

                success:function(response){
                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menghapus pegawai.", "", "success");
                        $('#modal_delete').modal('hide');

                        oTable.ajax.reload();
                        oTable24.ajax.reload();
                        oTablepegawai.ajax.reload();
                    }
                    else
                    {
                        swal("Gagal menghapus pegawai.", "", "error");
                    }
                },
                error:function(){
                    swal("Gagal menghapus pegawai.", "", "error");
                }
            });
        });

        $(document).on('click','.modal_deleteruangan',function () {
            $('#delidruangan').val($(this).data('id'));
            $('.labelruangan').text($(this).data('ruangan'));
        });
        $(document).on('click','#simpandelruangan',function (){
          var delidruangan=$('#delidruangan').val();
          var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('destroyruangan')}}',
                data : {
                        delidruangan:delidruangan,
                        _token:_token
                        },
                success:function(response){

                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menghapus ruangan.", "", "success");
                        oTableruangan.ajax.reload();
                        oTable.ajax.reload();
                        oTable24.ajax.reload();
                        oTablepegawai.ajax.reload();
                        // oTable.ajax.reload();
                        $('#modal_deleteruangan').modal('hide');
                    }
                    else
                    {
                        swal("Gagal menghapus ruangan.", "", "error");
                    }
                },
                error:function(){
                    swal("Gagal menghapus ruangan.", "", "error");
                }
            });
        });

        $(document).on('click','.modal_delete24',function () {
            // alert($(this).data('nama'));
            $('#delidpegawai24').val($(this).data('id'));
            $('.labelpegawai24').text($(this).data('nama'));
        });
        $(document).on('click','#simpandelpegawai24',function (){
          var nip=$('#delidpegawai24').val();
          var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('destroydokter')}}',
                data : {
                        delidpegawai:nip,
                        _token:_token
                        },
                success:function(response){
                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menghapus pegawai.", "", "success");
                        $('#modal_delete24').modal('hide');
                        oTable.ajax.reload();
                        oTable24.ajax.reload();
                        oTablepegawai.ajax.reload();
                    }
                    else
                    {
                        swal("Gagal menghapus pegawai.", "", "error");
                    }
                },
                error:function(){
                    swal("Gagal menghapus pegawai.", "", "error");
                }
            });
        });

        $(document).on('click','.modal_deleteperawat',function () {
            // alert($(this).data('nama'));
            $('#delidpegawaiperawat').val($(this).data('id'));
            $('.labelpegawaiperawat').text($(this).data('nama'));
        });
        $(document).on('click','#simpandelpegawaiperawat',function (){
          var nip=$('#delidpegawaiperawat').val();
          var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('destroyperawat')}}',
                data : {
                        delidpegawaiperawat:nip,
                        _token:_token
                        },
                success:function(response){
                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menghapus pegawai.", "", "success");
                        $('#modal_deleteperawat').modal('hide');

                        oTable.ajax.reload();
                        oTable24.ajax.reload();
                        oTablepegawai.ajax.reload();
                    }
                    else
                    {
                        swal("Gagal menghapus pegawai.", "", "error");
                    }
                },
                error:function(){
                    swal("Gagal menghapus pegawai.", "", "error");
                }
            });
        });
    </script>
    <!-- modal delete end -->

@endsection
