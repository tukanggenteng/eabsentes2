@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.css')}}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">


<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
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

                @if (!empty(session('err')))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    {{session('err')}}
                </div>
                @endif
                <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Jadwal Kerja</h3>
                        
                        <div class="box-tools">
                            <div class="col-md-12">
                                <form action="/jadwalkerjapegawaiharian" method="post">
                                    {{csrf_field()}}
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        @if ($table_search=="")
                                        <input type="text" id="table_search" readonly name="table_search" class="form-control pull-right" placeholder="Search">
                                        @else
                                        <input type="text" id="table_search" readonly name="table_search" class="form-control pull-right" value="{{$table_search}}" placeholder="Search">
                                        @endif
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            
                        </div>
                    </div>
                    <hr>
                    <!-- /.box-header -->
                    <div class="box-body no-padding table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th style="width: 20px;text-align: center">1</th>
                                <th style="width: 20px;text-align: center">2</th>
                                <th style="width: 20px;text-align: center">3</th>
                                <th style="width: 20px;text-align: center">4</th>
                                <th style="width: 20px;text-align: center">5</th>
                                <th style="width: 20px;text-align: center">6</th>
                                <th style="width: 20px;text-align: center">7</th>
                                <th style="width: 20px;text-align: center">8</th>
                                <th style="width: 20px;text-align: center">9</th>
                                <th style="width: 20px;text-align: center">10</th>
                                <th style="width: 20px;text-align: center">11</th>
                                <th style="width: 20px;text-align: center">12</th>
                                <th style="width: 20px;text-align: center">13</th>
                                <th style="width: 20px;text-align: center">14</th>
                                <th style="width: 20px;text-align: center">15</th>
                                <th style="width: 20px;text-align: center">16</th>
                                <th style="width: 20px;text-align: center">17</th>
                                <th style="width: 20px;text-align: center">18</th>
                                <th style="width: 20px;text-align: center">19</th>
                                <th style="width: 20px;text-align: center">20</th>
                                <th style="width: 20px;text-align: center">21</th>
                                <th style="width: 20px;text-align: center">22</th>
                                <th style="width: 20px;text-align: center">23</th>
                                <th style="width: 20px;text-align: center">24</th>
                                <th style="width: 20px;text-align: center">25</th>
                                <th style="width: 20px;text-align: center">26</th>
                                <th style="width: 20px;text-align: center">27</th>
                                <th style="width: 20px;text-align: center">28</th>
                                <th style="width: 20px;text-align: center">29</th>
                                <th style="width: 20px;text-align: center">30</th>
                                <th style="width: 20px;text-align: center">31</th>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                <tr>
                                    <td><a href="/jadwalkerjapegawaiharian/{{encrypt($result['id'])}}">{{$result['nip']}}</a></td>
                                    <td>{{$result['nama']}}</td>
                                    <td>
                                        <?php
                                            // echo $function->aturanjadwalharian($tanggalawal,$tanggalakhir,$tanggal,$classdata,$singkatan);
                                            $tanggal="1";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                            // $tahun=date('Y');
                                            // $bulan=date('m');
                                            // $tanggal=date('Y-m-d', strtotime($tahun."-".$bulan."-".$tanggal));
                                            // foreach ($result['periode'] as $periode){
                                            //     $hitung=count($periode);

                                            //     if ($hitung > 0){
                                            //         if (($tanggal >= $periode['tanggal_awalrule']) && ($tanggal<=$periode['tanggal_akhirrule'])){

                                            //             echo '<span class="badge '.$periode['classdata'].'">'.$periode['singkatan'].'</span>';
                                                        
                                            //             // echo "Benar".$periode['tanggal_awalrule'].">".$tanggal."<".$periode['tanggal_akhirrule'];
                                            //         }
                                            //     }
                                            //     else
                                            //     {
                                            //         echo "-";
                                            //     }
                                            // }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="2";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="3";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="4";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="5";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="6";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="7";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal2="08";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="9";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="10";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="11";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="12";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="13";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="14";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="15";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="16";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="17";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="18";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="19";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="20";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="21";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="22";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="23";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="24";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="25";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="26";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="27";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="28";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="29";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="30";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tanggal="31";
                                            echo $function->aturanjadwalharian($result['periode'],$tanggal);
                                        ?>
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            {{$results->appends(['table_search'=>($table_search)])->withPath('/jadwalkerjapegawaiharian')->links()}}
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                        <!-- /.row -->

        </section>
                    <!-- /.content -->
    </div>
        <!-- /.content-wrapper -->

                @include('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- jQuery 3 -->
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
    <script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- SlimScroll -->
    <script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>

    <script>
        $(function() {
            $('input[name="table_search"]').datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months"
            });
        });
    </script>
    

    </body>
@endsection
