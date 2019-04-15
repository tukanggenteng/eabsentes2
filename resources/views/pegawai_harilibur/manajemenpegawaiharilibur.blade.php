@extends('layouts.app')

@section('title')
Manajemen Pegawai Hari Libur Nasional
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- daterange picker -->
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">
<script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>


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
                @if (!empty(session('err')))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Perhatian!</h4>
                    {!!session('err')!!}
                </div>
                @endif

                @if (!empty(session('success')))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                    {!!session('success')!!}
                </div>
                @endif
                    <div class="col-xs-12">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Pegawai Hari Libur Nasional</h3>
                            </div>
                            <div class="box-body">
                                <form action="/harilibur/pegawai/store" method="post">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <button type="submit" value="tambah" class="btn btn-primary" id="tombol" name="tombol">
                                                    Tambah
                                                </button>
                                                <button type="submit" value="hapus" class="btn btn-danger" id="tombol" name="tombol">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    {{csrf_field()}}
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="tableaja" class="table table-striped table-bordered table-hover table-align">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select_all2" name="select_all2" class="flat-red select_all">
                                                    </th>
                                                    <th>Id</th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Instansi</th>
                                                    <th>Libur Nasional</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                </form>
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
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- FastClick -->

    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>

    <script type="text/javascript">
            $(function() {
                $('input[name="daterange"]').daterangepicker(
                        {
                            locale: {
                                format: 'YYYY/MM/DD'
                            }
                        }
                );

                $('#select_all').on('ifChanged', function(event){
                    if(!this.changed) {
                        this.changed=true;
                        $('.checkbox').iCheck('check');
                    } else {
                        this.changed=false;
                        $('.checkbox').iCheck('uncheck');
                    }
                    $('.checkbox').iCheck('update');
                });

                $('#select_all2').on('ifChanged', function(event){
                    if(!this.changed) {
                        this.changed=true;
                        $('.cekbox2').iCheck('check');
                    } else {
                        this.changed=false;
                        $('.cekbox2').iCheck('uncheck');
                    }
                    $('.cekbox2').iCheck('update');
                });

            });


            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            })
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass   : 'iradio_minimal-red'
            })
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass   : 'iradio_flat-green'
            })
    </script>

    <script type="text/javascript">
        var oTable;
        $(function() {
            oTable = $('#tableaja').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('getpegawaiharilibur')}}',
                columns: [
                    { data: 'action', name: 'action',orderable: false },
                    { data: 'id', name: 'id',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        //    $(td).class('flat-red cekbox2');
                        }
                    },
                    { data: 'nip', name: 'nip',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'center');
                        }
                    },
                    { data: 'nama', name: 'nama' },
                    { data: 'namaInstansi', name: 'namaInstansi',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'center');
                        }
                     },
                    { data: 'pegawai_hari_liburs', name: 'pegawai_hari_liburs',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'center');
                        }
                    }
                ]
            });
        });
    </script>

   


@endsection
