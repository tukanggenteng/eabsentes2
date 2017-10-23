@extends('layouts.app')
@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- daterange picker -->

<link rel="stylesheet" href="{{asset('bower_components/loading/loading.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">

<link rel="stylesheet" href="{{asset('plugins/pace/pace.min.css')}}">

<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">


@endpush

@section('body')
    <body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">

        <!-- Left side column. contains the logo and sidebar -->
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
                                <h3 class="box-title">Manajemen User</h3>
                            </div>
                            <div class="box-body">
                                <hr>
                                <div class="table-responsive">
                                    <table id="tableaja" class="table">
                                        <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Periode</th>
                                            <th>Ijin</th>
                                            <th>Cuti</th>
                                            <th>Sakit</th>
                                            <th>Tugas Luar</th>
                                            <th>Tugas Belajar</th>
                                            <th>Rapat/ Undangan</th>
                                            <th>Ijin Terlambat</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{--modal ijin--}}
                <div class="modal fade" id="modal_ijin">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Ijin</h4>
                            </div>
                            <div class="modal-body">

                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formijin" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="nipijin" name="nipijin" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namaijin" name="namaijin" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamaijin" name="lamaijin" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggalijin" readonly name="tanggalijin" class="form-control datepicker pull-right" type="text">
                                                <input id="idijin" readonly hidden name="idijin" type="text">
                                                <input id="sisalamaijin" readonly hidden name="sisalamaijin" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="fileijin" name="fileijin" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpanijin" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
        {{--<div class="cssload-thecube">--}}
            {{--<div class="cssload-cube cssload-c1"></div>--}}
            {{--<div class="cssload-cube cssload-c2"></div>--}}
            {{--<div class="cssload-cube cssload-c4"></div>--}}
            {{--<div class="cssload-cube cssload-c3"></div>--}}
        {{--</div>--}}
                <!-- /.modal -->
                {{--modal sakit--}}
                <div class="modal fade" id="modal_sakit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Sakit</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formsakit" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="nipsakit" name="nipsakit" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namasakit" name="namasakit" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamasakit" name="lamasakit" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggalsakit" readonly name="tanggalsakit" class="form-control datepicker pull-right" type="text">
                                                <input id="idsakit" readonly hidden name="idsakit" type="text">
                                                <input id="sisalamasakit" readonly hidden name="sisalamasakit" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="filesakit" name="filesakit" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpansakit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                {{--modal cuti--}}
                <div class="modal fade" id="modal_cuti">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Cuti</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formcuti" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="nipcuti" name="nipcuti" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namacuti" name="namacuti" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamacuti" name="lamacuti" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggalcuti" readonly name="tanggalcuti" class="form-control datepicker pull-right" type="text">
                                                <input id="idcuti" readonly hidden name="idcuti" type="text">
                                                <input id="sisalamacuti" readonly hidden name="sisalamacuti" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="filecuti" name="filecuti" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpancuti" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                {{--modal tb--}}
                <div class="modal fade" id="modal_tb">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Tugas Belajar</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formtb" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="niptb" name="niptb" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namatb" name="namatb" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamatb" name="lamatb" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggaltb" readonly name="tanggaltb" class="form-control datepicker pull-right" type="text">
                                                <input id="idtb" readonly hidden name="idtb" type="text">
                                                <input id="sisalamatb" readonly hidden name="sisalamatb" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="filetb" name="filetb" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpantb" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                {{--modal tl--}}
                <div class="modal fade" id="modal_tl">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Tugas Luar</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formtl" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="niptl" name="niptl" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namatl" name="namatl" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamatl" name="lamatl" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggaltl" readonly name="tanggaltl" class="form-control datepicker pull-right" type="text">
                                                <input id="idtl" readonly hidden name="idtl" type="text">
                                                <input id="sisalamatl" readonly hidden name="sisalamatl" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="filetl" name="filetl" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpantl" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                {{--modal rapatundangan--}}
                <div class="modal fade" id="modal_rp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Rapat/ Undangan</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formrp" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="niprp" name="niprp" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namarp" name="namarp" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamarp" name="lamarp" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggalrp" readonly name="tanggalrp" class="form-control datepicker pull-right" type="text">
                                                <input id="idrp" readonly hidden name="idrp" type="text">
                                                <input id="sisalamarp" readonly hidden name="sisalamarp" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="filerp" name="filerp" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpanrp" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                {{--modal ijinterlambat--}}
                <div class="modal fade" id="modal_it">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Upload Surat Ijin Terlambat</h4>
                            </div>
                            <div class="modal-body">
                                <div class="error alert-danger alert-dismissible">
                                </div>
                                <form id="formit" method="post" role="form" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>NIP</label>
                                                <input id="nipit" name="nipit" readonly class="form-control pull-right" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input id="namait" name="namait" readonly class="form-control pull-right" type="text">
                                                {{csrf_field()}}
                                            </div>
                                            <div class="form-group">
                                                <label>Lama Hari</label>
                                                <select class="form-control select2" id="lamait" name="lamait" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group" >
                                                <label>Sejak Tanggal</label>
                                                <input id="tanggalit" readonly name="tanggalit" class="form-control datepicker pull-right" type="text">
                                                <input id="idit" readonly hidden name="idit" type="text">
                                                <input id="sisalamait" readonly hidden name="sisalamait" type="text">
                                            </div>
                                            <div class="form-group" >
                                                <label>Upload File</label>
                                                <input id="fileit" name="fileit" class="filestyle" data-btnClass="btn-primary" type="file">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="simpanit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- /.modal -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    {{--<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>--}}
    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- FastClick -->


    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="{{asset('bower_components/bootstrap-filestyle/bootstrap-filestyle.js')}}"></script>
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    {{--<script src="{{asset('dist/js/demo.js')}}"></script>--}}
    <!-- Page script -->
    {{--<script>--}}
        {{--$(function () {--}}
            {{--//Initialize Select2 Elements--}}
            {{--$('.select2').select2()--}}

        {{--})--}}
    {{--</script>--}}
    <script type="text/javascript">

        $(function() {
            $('input[name="tanggalijin"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
            $('input[name="tanggalsakit"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
            $('input[name="tanggalcuti"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
            $('input[name="tanggaltb"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
            $('input[name="tanggaltl"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
            $('input[name="tanggalrp"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
            $('input[name="tanggalit"]').datepicker({
                firstDay: 1,
                format: "yyyy-mm-dd",
                startDate:"-1w",
                endDate:"-1d",
            });
        });

        var oTable;
        $(function() {
            oTable = $('#tableaja').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('datatransrekap')}}',
                columns: [
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'periode', name: 'periode' },
                    { data: 'ijin', name: 'ijin' },
                    { data: 'cuti', name: 'cuti' },
                    { data: 'sakit', name: 'sakit' },
                    { data: 'tugas_luar', name: 'tugas_luar' },
                    { data: 'tugas_belajar', name: 'tugas_belajar' },
                    { data: 'rapatundangan', name: 'rapatundangan' },
                    { data: 'ijinterlambat', name: 'ijinterlambat' }
                ]
            });
        });
    </script>
    <script type="text/javascript">
        $(document).on('click','.modal_ijin',function () {
                $('#lamaijin').attr('disabled',false);
                $('#tanggalijin').attr('disabled',false);
                $('#fileijin').attr('disabled',false);
                $('#simpanijin').attr('disabled',false);
                $('#simpanijin').html('Save');
                $('#nipijin').val($(this).data('nip'));
                $('#namaijin').val($(this).data('nama'));
                $('#tanggalijin').val('');
                $('#fileijin').val('');
                $('#sisalamaijin').val($(this).data('ijin'));
                $('#idijin').val($(this).data('id'));
                var select=parseInt($(this).data('ijin'));
                if(select=="0" && select==""){
                    $('#tanggalijin').attr('disabled','true');
                    $('#fileijin').attr('disabled','true');
                    $('#lamaijin').attr('disabled','true');
                    $('#simpanijin').attr('disabled','true');
                }
                else {
                    var select=select+1;
                    for (i = 1; i < select; i++) {
                        $('#lamaijin').append('<option value="'+i+'">'+i+'</option>');
                    }
                    $('#tanggalijin').removeAttr('disabled');
                    $('#fileijin').removeAttr('disabled');
                    $('#lamaijin').removeAttr('disabled');
                    $('#simpanijin').removeAttr('disabled');
                }
        });

        $(document).on('click','.modal_sakit',function () {
            $('#lamasakit').attr('disabled',false);
            $('#tanggalsakit').attr('disabled',false);
            $('#filesakit').attr('disabled',false);
            $('#simpansakit').attr('disabled',false);
            $('#simpansakit').html('Save');
            $('#nipsakit').val($(this).data('nip'));
            $('#namasakit').val($(this).data('nama'));
            $('#tanggalsakit').val('');
            $('#sisalamasakit').val($(this).data('sakit'));
            $('#filesakit').val('');
            $('#idsakit').val($(this).data('id'));
            var select=parseInt($(this).data('sakit'));
            if(select=="0" && select==""){
                $('#tanggalsakit').attr('disabled','true');
                $('#filesakit').attr('disabled','true');
                $('#lamasakit').attr('disabled','true');
                $('#simpansakit').attr('disabled','true');
            }
            else {
                var select=select+1;
                for (i = 1; i < select; i++) {
                    $('#lamasakit').append('<option value="'+i+'">'+i+'</option>');
                }
                $('#tanggalsakit').removeAttr('disabled');
                $('#filesakit').removeAttr('disabled');
                $('#lamasakit').removeAttr('disabled');
                $('#simpansakit').removeAttr('disabled');
            }
        });

        $(document).on('click','.modal_cuti',function () {
            $('#lamacuti').attr('disabled',false);
            $('#tanggalcuti').attr('disabled',false);
            $('#filecuti').attr('disabled',false);
            $('#simpancuti').attr('disabled',false);
            $('#simpancuti').html('Save');
            $('#nipcuti').val($(this).data('nip'));
            $('#namacuti').val($(this).data('nama'));
            $('#tanggalcuti').val('');
            $('#filecuti').val('');
            $('#sisalamacuti').val($(this).data('cuti'));
            $('#idcuti').val($(this).data('id'));
            var select=parseInt($(this).data('cuti'));
            if(select=="0" && select==""){
                $('#tanggalcuti').attr('disabled','true');
                $('#filecuti').attr('disabled','true');
                $('#lamacuti').attr('disabled','true');
                $('#simpancuti').attr('disabled','true');
            }
            else {
                var select=select+1;
                for (i = 1; i < select; i++) {
                    $('#lamacuti').append('<option value="'+i+'">'+i+'</option>');
                }
                $('#tanggalcuti').removeAttr('disabled');
                $('#filecuti').removeAttr('disabled');
                $('#lamacuti').removeAttr('disabled');
                $('#simpancuti').removeAttr('disabled');
            }
        });

        $(document).on('click','.modal_tb',function () {
            $('#lamatb').attr('disabled',false);
            $('#tanggaltb').attr('disabled',false);
            $('#filetb').attr('disabled',false);
            $('#simpantb').attr('disabled',false);
            $('#simpantb').html('Save');
            $('#niptb').val($(this).data('nip'));
            $('#namatb').val($(this).data('nama'));
            $('#tanggaltb').val('');
            $('#sisalamatb').val($(this).data('tb'));
            $('#filetb').val('');
            $('#idtb').val($(this).data('id'));
            var select=parseInt($(this).data('tb'));
            if(select=="0" && select==""){
                $('#tanggaltb').attr('disabled','true');
                $('#filetp').attr('disabled','true');
                $('#lamatb').attr('disabled','true');
                $('#simpantb').attr('disabled','true');
            }
            else {
                var select=select+1;
                for (i = 1; i < select; i++) {
                    $('#lamatb').append('<option value="'+i+'">'+i+'</option>');
                }
                $('#tanggaltb').removeAttr('disabled');
                $('#filetb').removeAttr('disabled');
                $('#lamatb').removeAttr('disabled');
                $('#simpantb').removeAttr('disabled');
            }
        });

        $(document).on('click','.modal_tl',function () {
            $('#lamatl').attr('disabled',false);
            $('#tanggaltl').attr('disabled',false);
            $('#filetl').attr('disabled',false);
            $('#simpantl').attr('disabled',false);
            $('#simpantl').html('Save');
            $('#niptl').val($(this).data('nip'));
            $('#namatl').val($(this).data('nama'));
            $('#tanggaltl').val('');
            $('#sisalamatl').val($(this).data('tl'));
            $('#filetl').val('');
            $('#idtl').val($(this).data('id'));
            var select=parseInt($(this).data('tl'));
            if(select=="0" && select==""){
                $('#tanggaltl').attr('disabled','true');
                $('#filetl').attr('disabled','true');
                $('#lamatl').attr('disabled','true');
                $('#simpantl').attr('disabled','true');
            }
            else {
                var select=select+1;
                for (i = 1; i < select; i++) {
                    $('#lamatl').append('<option value="'+i+'">'+i+'</option>');
                }
                $('#tanggaltl').removeAttr('disabled');
                $('#filetl').removeAttr('disabled');
                $('#lamatl').removeAttr('disabled');
                $('#simpantl').removeAttr('disabled');
            }
        });

        $(document).on('click','.modal_rp',function () {
            $('#lamarp').attr('disabled',false);
            $('#tanggalrp').attr('disabled',false);
            $('#filerp').attr('disabled',false);
            $('#simpanrp').attr('disabled',false);
            $('#simpanrp').html('Save');
            $('#niprp').val($(this).data('nip'));
            $('#namarp').val($(this).data('nama'));
            $('#tanggalrp').val('');
            $('#filerp').val('');
            $('#sisalamarp').val($(this).data('rp'));
            $('#idrp').val($(this).data('id'));
            var select=parseInt($(this).data('rp'));
            if(select=="0" && select==""){
                $('#tanggalrp').attr('disabled','true');
                $('#filerp').attr('disabled','true');
                $('#lamarp').attr('disabled','true');
                $('#simpanrp').attr('disabled','true');
            }
            else {
                var select=select+1;
                for (i = 1; i < select; i++) {
                    $('#lamarp').append('<option value="'+i+'">'+i+'</option>');
                }
                $('#tanggalrp').removeAttr('disabled');
                $('#filerp').removeAttr('disabled');
                $('#lamarp').removeAttr('disabled');
                $('#simpanrp').removeAttr('disabled');
            }
        });

        $(document).on('click','.modal_it',function () {
            $('#lamait').attr('disabled',false);
            $('#tanggalit').attr('disabled',false);
            $('#fileit').attr('disabled',false);
            $('#simpanit').attr('disabled',false);
            $('#simpanit').html('Save');
            $('#nipit').val($(this).data('nip'));
            $('#namait').val($(this).data('nama'));
            $('#tanggalit').val('');
            $('#fileit').val('');
            $('#sisalamait').val($(this).data('it'));
            $('#idit').val($(this).data('id'));
            var select=parseInt($(this).data('it'));
            if(select=="0" && select==""){
                $('#tanggalit').attr('disabled','true');
                $('#fileit').attr('disabled','true');
                $('#lamait').attr('disabled','true');
                $('#simpanit').attr('disabled','true');
            }
            else {
                var select=select+1;
                for (i = 1; i < select; i++) {
                    $('#lamait').append('<option value="'+i+'">'+i+'</option>');
                }
                $('#tanggalit').removeAttr('disabled');
                $('#fileit').removeAttr('disabled');
                $('#lamait').removeAttr('disabled');
                $('#simpanit').removeAttr('disabled');
            }
        });

//        $(document).ajaxStart(function () {
//            Pace.restart()
//        })

        $(document).on('click','#simpanijin',function (){
            var sisaijin=$('#sisalamaijin').val()-$('#lamaijin').val();
            $('#sisalamaijin').val(sisaijin);

            $.ajax({
                type:'post',
                url:'{{url('transrekap/postijin')}}',
                data: new FormData($('#formijin')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamaijin').attr('disabled',true);
                    $('#tanggalijin').attr('disabled',true);
                    $('#fileijin').attr('disabled',true);
                    $('#simpanijin').attr('disabled',true);
                    $('#simpanijin').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    $('#lamaijin').attr('disabled',false);
                    $('#tanggalijin').attr('disabled',false);
                    $('#fileijin').attr('disabled',false);
                    $('#simpanijin').attr('disabled',false);
                    $('#simpanijin').html('Save');

                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_ijin').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_ijin').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });

        $(document).on('click','#simpansakit',function (){
            var sisaijin=$('#sisalamasakit').val()-$('#lamasakit').val();
            $('#sisalamasakit').val(sisaijin);
            $.ajax({
                type:'post',
                url:'{{url('transrekap/postsakit')}}',
                data: new FormData($('#formsakit')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamasakit').attr('disabled',true);
                    $('#tanggalsakit').attr('disabled',true);
                    $('#filesakit').attr('disabled',true);
                    $('#simpansakit').attr('disabled',true);
                    $('#simpansakit').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_sakit').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_sakit').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });

        $(document).on('click','#simpancuti',function (){
            var sisaijin=$('#sisalamacuti').val()-$('#lamacuti').val();
            $('#sisalamacuti').val(sisaijin);
            $.ajax({
                type:'post',
                url:'{{url('transrekap/postcuti')}}',
                data: new FormData($('#formcuti')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamacuti').attr('disabled',true);
                    $('#tanggalcuti').attr('disabled',true);
                    $('#filecuti').attr('disabled',true);
                    $('#simpancuti').attr('disabled',true);
                    $('#simpancuti').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_cuti').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_cuti').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });

        $(document).on('click','#simpantb',function (){
            var sisaijin=$('#sisalamatb').val()-$('#lamatb').val();
            $('#sisalamatb').val(sisaijin);
            $.ajax({
                type:'post',
                url:'{{url('transrekap/posttb')}}',
                data: new FormData($('#formtb')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamatb').attr('disabled',true);
                    $('#tanggaltb').attr('disabled',true);
                    $('#filetb').attr('disabled',true);
                    $('#simpantb').attr('disabled',true);
                    $('#simpantb').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_tb').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_tb').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });

        $(document).on('click','#simpantl',function (){
            var sisaijin=$('#sisalamatl').val()-$('#lamatl').val();
            $('#sisalamatl').val(sisaijin);
            $.ajax({
                type:'post',
                url:'{{url('transrekap/posttl')}}',
                data: new FormData($('#formtl')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamatl').attr('disabled',true);
                    $('#tanggaltl').attr('disabled',true);
                    $('#filetl').attr('disabled',true);
                    $('#simpantl').attr('disabled',true);
                    $('#simpantl').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_tl').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_tl').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });

        $(document).on('click','#simpanrp',function (){
            var sisaijin=$('#sisalamarp').val()-$('#lamarp').val();
            $('#sisalamarp').val(sisaijin);
            $.ajax({
                type:'post',
                url:'{{url('transrekap/postrp')}}',
                data: new FormData($('#formrp')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamarp').attr('disabled',true);
                    $('#tanggalrp').attr('disabled',true);
                    $('#filerp').attr('disabled',true);
                    $('#simpanrp').attr('disabled',true);
                    $('#simpanrp').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_rp').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_rp').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });

        $(document).on('click','#simpanit',function (){
            var sisaijin=$('#sisalamait').val()-$('#lamait').val();
            $('#sisalamait').val(sisaijin);
            $.ajax({
                type:'post',
                url:'{{url('transrekap/postit')}}',
                data: new FormData($('#formit')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                beforeSend:function () {
                    $('#lamait').attr('disabled',true);
                    $('#tanggalit').attr('disabled',true);
                    $('#fileit').attr('disabled',true);
                    $('#simpanit').attr('disabled',true);
                    $('#simpanit').html('<i class="fa fa-spin fa-circle-o-notch"></i> Loading');
                },
                success:function(response){
                    if((response.errors)){
                        swal("Simpan Gagal !", "", "error");
                        $('#modal_it').modal('hide');
                    }
                    else
                    {
                        swal("Simpan Sukses !", "", "success");
                        $('#modal_it').modal('hide');
                        oTable.ajax.reload();
                    }
                },
            });
        });
    </script>


    </body>
@endsection
