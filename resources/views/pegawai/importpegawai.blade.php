@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.css')}}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">



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
            @if ((session('berhasil')))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-bell"></i> Berhasil!</h4>
                {{session('berhasil')}}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-bell"></i> Gagal!</h4>
                {{session('error')}}
            </div>
            @endif

                <!-- Atur Jadwal Kerja -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Import Pegawai Instansi</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <form action="{{route('apiconfig')}}" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>File</label>
                                        <input type="file" id="file" name="file" class="filestyle" data-input="false">
                                    </div>
                                </div>
                            </div>
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Instansi</label>
                                        <select name="instansi_id" class="form-control select2" id="instansi_id">
                                            @foreach ($datas as $data)
                                                <option value="{{$data->id}}">{{$data->namaInstansi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                </div>
                            </div>
                        </form>
                            <!-- /.col -->
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.box -->
                <!-- /.row -->

            </section>
            <!-- /.content -->

            <!-- Main content -->
            <section class="content">

                  <!-- Atur Jadwal Kerja -->
                  <div class="box box-default">
                      <div class="box-header with-border">
                          <h3 class="box-title">Import Pegawai via API</h3>

                          <div class="box-tools pull-right">
                              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                          </div>
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">

                          <form action="/pegawai/apiconfig" method="post">
                              {{csrf_field()}}
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <label>Sumber API</label>
                                          <select name="id" class="form-control select2 bg-green" id="instansi_id">
                                            @foreach ($sumbers as $sumber)
                                                <option value="{{$sumber->id}}" {{$sumber->active==1?'selected=selected':''}}>{{$sumber->nama_api}}</option>
                                                @if($sumber->active==1)
                                                  @php $aktif=$sumber->path @endphp
                                                @endif
                                            @endforeach
                                          </select>

                                          <input type="hidden" name="sumber" id="sumber" value="{{$aktif}}">
                                      </div>
                                  </div>
                              </div>

                              <hr>
                              <div class="row">
                                  <div class="col-xs-12">
                                      <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                                  </div>
                              </div>
                          </form>
                              <!-- /.col -->
                          <!-- /.row -->
                      </div>
                      <hr>
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title">Manajemen Pegawai</h4>
                          </div>
                          <div class="modal-body">
                              <div class="error alert-danger alert-dismissible">
                              </div>
                              <form id="formpegawaiadd" method="post" role="form" enctype="multipart/form-data">
                                  <div class="row">
                                      <div class="col-md-12">
                                        <div class="col-md-12">
                                          <label>NIP</label>
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
                              </form>
                          </div>
                          <div class="modal-footer">
                              <button type="button" id="simpanaddpegawai" class="btn btn-primary pull-left">Simpan</button>
                          </div>
                      </div>
                  </div>
                  <!-- /.box -->
                  <!-- /.row -->

              </section>
              <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->






                @include('layouts.footer')
    </div>
    <!-- ./wrapper -->
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
    <!-- date-range-picker -->
    <script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="{{asset('bower_components/bootstrap-filestyle/bootstrap-filestyle.min.js')}}"></script>

    <script>
        $("#file").filestyle({input: false});
        $("#instansi_id").select2();
    </script>

    <script type="text/javascript">
        $('#nip').focusout( function (){
          var nip=$('#nip').val();
          var sumber=$('#sumber').val();
          $.ajax({
              type:'get',
              url: sumber+nip,
              dataType:'json',
              beforeSend:function () {
                $('.input-group-addon').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success:function(response){
                // alert(response['namaInstansi']);
                if (response['status']=='0') {
                  $('#nama').val(response['nama']);
                  $('.input-group-addon').html('<i class="fa fa-check"></i>');
                }
                else {
                  swal("NIP Pegawai Tidak Terdaftar", "", "error");
                  $('#modal_add').modal('hide');
                }

              },

          });
        });

        // Simpan Data
        $(document).on('click','#simpanaddpegawai',function (){
            var nip=$('#nip').val();
            var nama=$('#nama').val();
            var _token=$("input[name=_token]").val();
            $.ajax({
                type:'post',
                url:'{{route('simpanDataApi')}}',
                data : {
                        nip:nip,
                        nama:nama,
                        _token:_token
                        },
                success:function(response){

                    if((response.error)){
                        $('.error').addClass('hidden');
                        swal(response.error, "", "error");
                        $('#modal_add').modal('hide');
                        //console.log(response);
                    }
                    else
                    {
                        $('.error').addClass('hidden');
                        swal("Sukses Menyimpan Data "+response.nama, "", "success");
                        $('#modal_add').modal('hide');
                        //console.log(response.nama);
                    }
                },
            });
        });


    </script>

    </body>
@endsection
