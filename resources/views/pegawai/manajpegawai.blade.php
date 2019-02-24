@extends('layouts.app')

@section('title')
Manajemen Pegawai
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
    <div class="wrapper">

      @include('layouts.header')

      @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

          <!-- Main content -->
          <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Manajemen Pegawai</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="formsinkronpegawai" action="" method="post" role="form" enctype="multipart/form-data">
                                            <div class="form-group checkbox">
                                                <label>
                                                    Sinkron Data Pegawai
                                                </label>
                                                {{csrf_field()}}
                                                <input data-toggle="toggle" id="sinkron" name="sinkron" type="checkbox">
                                                <span><i class="fa fa-spin"></i></span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4">
                                        <form action="/pegawai" method="post">
                                          {{csrf_field()}}
                                          <input type="text" name="cari" placeholder="NIP/Nama/Instansi" value="{{$pegawaisearch}}">
                                          <button type="submit" name="button"><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="tableaja" class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Instansi</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                  @foreach($pegawais as $pegawai)
                                                      <tr>
                                                          <td>{{$pegawai->nip}}</td>
                                                          <td>{{$pegawai->nama}}</td>
                                                          <td id="{{$pegawai->id}}">{{$pegawai->namaInstansi}}</td>
                                                          <td><button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-idrow="{{$pegawai->id}}" data-nip="{{$pegawai->nip}}"  data-nama="{{$pegawai->nama}}" data-id="{{encrypt($pegawai->id)}}" data-target="#modal_delete">Hapus</button></td>
                                                      </tr>
                                                  @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="box-footer clearfix">
                                <p>Banyak data pegawai {{$hitungs}}</p>
                                <ul class="pagination pagination-sm no-margin pull-right">
                                    {{$pegawais->links()}}
                                </ul>
                            </div>
                        </div>
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
    <script type="text/javascript">
        var idbaris=0;
        var oTable;
        $(function() {
            // oTable = $('#tableaja').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: '{{route('datapegawai')}}',
            //     columns: [
            //         { data: 'nip', name: 'nip' },
            //         { data: 'nama', name: 'nama' },
            //         { data: 'namaInstansi', name: 'namaInstansi' },
            //         { data: 'action', name: 'action' },
            //     ]
            // });
        });
    </script>

    <script type="text/javascript">
        $('#sinkron').change(function(){
            if ($(this).is(':checked')){
                $("#sinkron").bootstrapToggle('disable');
                $('#sinkron').bootstrapToggle('on');
                $('.fa-spin').addClass('fa-refresh');
                $.ajax({
                    type:'post',
                    url:'{{route('sinkronpegawai')}}',
                    data: new FormData($('#formsinkronpegawai')[0]),
                    dataType:'json',
                    async:false,
                    processData: false,
                    contentType: false,
                    success:function(response){
                        $('.fa-spin').removeClass('fa-refresh');
                        $('#sinkron').bootstrapToggle('off');
                        $("#sinkron").bootstrapToggle('enable');
                        oTable.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert(textStatus);
                    }
                });
            }
            else
            {

            }

        });


        </script>

        <script type="text/javascript">
            $(document).on('click','.modal_delete',function () {
                $('#delidpegawai').val($(this).data('id'));
                $('.labelpegawai').text($(this).data('nama'));
                idrow=$(this).data('idrow');
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
                success:function(response){
                    $('.error').addClass('hidden');
                    $('#modal_delete').modal('hide');
                    // oTable.ajax.reload();
                    $('#'+idrow).text("");
                },
            });
        });
    </script>

@endsection
