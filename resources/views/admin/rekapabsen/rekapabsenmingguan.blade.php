@extends('layouts.app')

@section('title')
Rekap Bulanan
@endsection

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

    <div class="wrapper">

        <!-- Left side column. contains the logo and sidebar -->
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
                                <h3 class="box-title">Surat dukung yang tidak dilaporkan </h3>
                            </div>
                            <div class="box-body">
                                <hr>
                                <div class="table-responsive">
                                    <table id="tableaja" class="table table-striped table-bordered table-hover table-align">
                                        <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Periode</th>
                                            <th>Hari Kerja</th>
                                            <th>Hadir</th>
                                            <th>Absent</th>
                                            <th>Ijin</th>
                                            <th>Cuti</th>
                                            <th>Sakit</th>
                                            <th>Tugas Luar</th>
                                            <th>Tugas Belajar</th>
                                            <th>Ijin Kepentingan Lain</th>
                                            <th>Ijin Terlambat</th>
                                            <th>Terlambat</th>
                                            <th>Pulang Cepat</th>
                                            <th>Ijin Pulang Cepat</th>
                                            <th>Akumulasi Jam Kerja</th>
                                            <th>Akumulasi Jam Terlambat</th>
                                            <th>Instansi</th>
                                        </tr>
                                        </thead>
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
                ajax: '{{route('datarekapadminmingguan')}}',
                columns: [
                    { data: 'nip', name: 'nip' },
                    { data: 'nama', name: 'nama' },
                    { data: 'periode', name: 'periode' },
                    { data: 'hari_kerja', name: 'hari_kerja',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'hadir', name: 'hadir',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'tanpa_kabar', name: 'tanpa_kabar',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'ijin', name: 'ijin',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'cuti', name: 'cuti',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'sakit', name: 'sakit',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'tugas_luar', name: 'tugas_luar',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'tugas_belajar', name: 'tugas_belajar',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'rapatundangan', name: 'rapatundangan',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'ijinterlambat', name: 'ijinterlambat',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'terlambat', name: 'terlambat',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'pulang_cepat', name: 'pulang_cepat',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'ijinpulangcepat', name: 'ijinpulangcepat',
                        createdCell: function (td, cellData, rowData, row, col) {
                           $(td).css('text-align', 'right');
                        }
                    },
                    { data: 'total_akumulasi', name: 'total_akumulasi' },
                    { data: 'total_terlambat', name: 'total_terlambat' },
                    { data: 'namaInstansi', name: 'namaInstansi' }
                ]
            });
        });
    </script>

@endsection
