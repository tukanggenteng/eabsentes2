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

          <!-- Main content -->
          <section class="content">
            @include('layouts.inforekap')
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
                                            <table id="tableaja" class="table">
                                                <thead>
                                                <tr>
                                                    <th>Id</th>
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

                                {{--modal tambah--}}
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
                                                                <input id="instansi" value="{{Auth::user()->instansi->id}}" name="instansi" hidden readonly type="hidden">
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
                                <!-- /.modal -->


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

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


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
    <script type="text/javascript">
        var oTable;
        $(function() {
            oTable = $('#tableaja').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datapegawaiuser')}}',
                columns: [

                    { data: 'id', name: 'id' },
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'namaInstansi', name: 'namaInstansi' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>

    <script type="text/javascript">
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
                    if((response.errors)){

                    }
                    else
                    {
                        $('.error').addClass('hidden');
                        $('#modal_add').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','.modal_delete',function () {
            $('#delidpegawai').val($(this).data('nip'));
            $('.labelpegawai').text($(this).data('nama'));
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','#tambah',function () {
          $('#nip').attr('disabled',false);
          $('#instansi').attr('disabled',false);
          $('#nip').val("");
          $('#nama').val("");
          //$('#instansi').val("");
            $('.input-group-addon').html('<i class="fa fa-search"></i>');
        });
    </script>

    <script type="text/javascript">
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
                // data: new FormData($('#formdeletepegawai')[0]),
                // dataType:'json',
                // async:false,
                // processData: false,
                // contentType: false,
                success:function(response){
                    $('.error').addClass('hidden');
                    $('#modal_delete').modal('hide');
                    oTable.ajax.reload();
                },
            });
        });
    </script>

    <script type="text/javascript">
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
                if (response['status']=='1')
                {
                  $('#nip').attr('disabled',true);
                  $('#nama').attr('disabled',true);
                  $('#instansi').attr('disabled',true);
                  $('#instansi').val(response['instansi_id']);
                  $('#nama').val(response['nama']);
                  $('.input-group-addon').html('<i class="fa fa-times"></i>');
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
    </script>

    </body>
@endsection
