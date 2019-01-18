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

<!-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> -->

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

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

                        <!-- form perawat -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Manajemen Pegawai</h3><small> Ruangan Khusus</small>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" id="tambahruangan" data-target="#modal_addperawat">
                                                Tambah
                                            </button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="tablepegawai" class="table">
                                                        <thead>
                                                            <tr>
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
                                        <h4 class="modal-title">Manajemen Pegawai Ruangan Khusus</h4>
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
                                                    <!-- <div class="form-group">
                                                        <label>Ruangan</label>
                                                        <select class="form-control select2" id="tambah_pegawairuangan" name="tambah_pegawairuangan[]" tabindex="-1" style="width: 100%;">
                                                        </select>
                                                    </div> -->
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
                    url: '{{route('cariruanganspesifik')}}',
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


        
        var oTablepegawai;
        $(function(){
            oTablepegawai = $('#tablepegawai').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('dataspesifikruangan')}}',
                columns: [
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'nama_ruangan', name: 'nama_ruangan' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
        
    </script>

    <!-- modal add -->
    <script type="text/javascript">
        

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


    <!-- modal delete -->
    <script type="text/javascript">

     

        $(document).on('click','.modal_deleteperawat',function () {
            // alert($(this).data('nama'));
            $('#delidpegawaiperawat').val($(this).data('id'));
            $('.labelpegawaiperawat').text($(this).data('nama'));
            console.log($(this).data('id'))
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
                        oTablepegawai.ajax.reload();
                        oTable.ajax.reload();
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

    </body>
@endsection
