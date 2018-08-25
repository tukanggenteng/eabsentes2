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

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Manajemen User</h3>
                            </div>
                            <div class="box-body">
                                <button type="button" id="modal_add2" class="btn btn-primary" data-toggle="modal" data-target="#modal_add">
                                    Tambah
                                </button>
                                <hr>
                                <div class="table-responsive">
                                    <table id="tableaja" class="table">
                                        <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Nama</th>
                                            <th>Nama Ruangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal_add">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Manajemen User</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formuseradd" action="" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input id="username" name="username" class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input id="email" name="email" class="form-control pull-right" type="email">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input id="password" name="password" class="form-control pull-right" type="password">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="nama" name="nama" class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Ruangan</label>
                                                <select class="form-control select2" id="ruangan" name="ruangan" style="width: 100%;">
                                                    @foreach($ruangans as $ruangan)
                                                        <option value="{{encrypt($ruangan->id)}}">{{$ruangan->nama_ruangan}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpanadduser" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <div class="modal modal-warning fade" id="modal_edit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Edit User</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formedituser" action="" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input id="username2" readonly name="username2" class="form-control pull-right" type="text">
                                                <input class="form-control" id="iduser" name="iduser" type="hidden">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input id="email2" readonly name="email2" class="form-control pull-right" type="email">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input id="password2" name="password2" class="form-control pull-right" type="password">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="nama2" name="nama2" class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Ruangan</label>
                                                <select class="form-control select2" id="ruangan2" name="ruangan2" style="width: 100%;">
                                                    @foreach($ruangans as $ruangan)
                                                        <option value="{{encrypt($ruangan->id)}}">{{$ruangan->nama_ruangan}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpanedituser" class="btn btn-outline">Save changes</button>
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
                                <h4 class="modal-title">Hapus User</h4>
                            </div>
                            <div class="modal-body">
                                <form id="formdeleteuser" action="" method="post" role="form" enctype="multipart/form-data">
                                    <h4>
                                        <i class="icon fa fa-ban"></i>
                                        Peringatan
                                    </h4>
                                    {{csrf_field()}}
                                    Yakin ingin menghapus user <span class="labelusername"></span>?
                                    <input id="deliduser" name="deliduser" type="hidden">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpandeluser" class="btn btn-outline">Save changes</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    

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
                ajax: '{{route('datauserkhusus')}}',
                columns: [
                    { data: 'username', name: 'username' },
                    { data: 'email', name: 'email' },
                    { data: 'name', name: 'name' },
                    { data: 'nama_ruangan', name: 'nama_ruangan' },
                    {data:'action'}
                ]
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','#modal_add2',function () {
            $('#username').val("");
            $('#username').removeAttr('disabled');
            $('#email').removeAttr('disabled');
            $('#email').val("");
            $('#nama').val("");
            $('#password').val("");
            $('#instansi').val("");
            $('#modal_add').modal("show");
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','.modal_edit',function () {
            $('#username2').val($(this).data('username'));
            $('#username2').attr('disabled','true');
            $('#email2').attr('disabled','true');
            $('#email2').val($(this).data('email'));
            $('#nama2').val($(this).data('nama'));
            $('#password2').val("");
            $('#role2').val($(this).data('role'));
            $('#instansi2').val($(this).data('instansi'));
            $('#iduser').val($(this).data('id'));
            console.log($(this).data('id'));
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','.modal_delete',function () {
            $('#deliduser').val($(this).data('id'));
            $('.labelusername').text($(this).data('username'));
        });
    </script>

    <script type="text/javascript">
        $(document).on('click','#simpanadduser',function (){
            $.ajax({
                type:'post',
                url:'{{route('adduserkhusus')}}',
                data: new FormData($('#formuseradd')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                success:function(response){
                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menambah user.", "", "success");
                        $('#modal_add').modal('hide');
                        oTable.ajax.reload();
                    }
                    else
                    {
                        swal("Gagal menambahkan user.", "", "error");
                    }

                    console.log(response)

                    if((response.errors)){
                        if (response.errors.username){
                            swal("Username", ""+response.errors.username+"", "error");
                        }
                        else if (response.errors.email){
                            swal("Email", ""+response.errors.email+"", "error");
                        }
                        else if (response.errors.password){
                            swal("Password", ""+response.errors.password+"", "error");
                        }
                        else if (response.errors.nama){
                            swal("Nama", ""+response.errors.nama+"", "error");
                        }
                        else if (response.errors.ruangan){
                            swal("Nama", ""+response.errors.ruangan+"", "error");
                        }
                    }
                },
            });
        });
    </script>
    <script type="text/javascript">
        $(document).on('click','#simpanedituser',function (){
            $.ajax({
                type:'post',
                url:'{{route('edituserkhusus')}}',
                data: new FormData($('#formedituser')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                success:function(response){
                    console.log(response)
                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil mengubah user.", "", "success");
                        $('#modal_edit').modal('hide');
                        oTable.ajax.reload();
                    }
                    else
                    {
                        swal("Gagal mengubah user.", "", "error");
                    }

                    if((response.errors)){
                        if (response.errors.username2){
                            swal("Username", ""+response.errors.username2+"", "error");
                        }
                        else if (response.errors.email2){
                            swal("Email", ""+response.errors.email2+"", "error");
                        }
                        else if (response.errors.password2){
                            swal("Password", ""+response.errors.password2+"", "error");
                        }
                        else if (response.errors.nama2){
                            swal("Nama", ""+response.errors.nama2+"", "error");
                        }
                        else if (response.errors.ruangan2){
                            swal("Nama", ""+response.errors.ruangan2+"", "error");
                        }
                    }
                },
            });
        });
    </script>
    <script type="text/javascript">
        $(document).on('click','#simpandeluser',function (){
            $.ajax({
                type:'post',
                url:'{{route('deleteuserkhusus')}}',
                data: new FormData($('#formdeleteuser')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                success:function(response){
                    if((response== 'Success') || (response== 'success')){
                        swal("Berhasil menghapus user.", "", "success");
                        $('#modal_delete').modal('hide');
                        oTable.ajax.reload();
                    }
                    else
                    {
                        swal("Gagal menghapus user.", "", "error");
                    }
                },
            });
        });
    </script>
    </body>
@endsection
