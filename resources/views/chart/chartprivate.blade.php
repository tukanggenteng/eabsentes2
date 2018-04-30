@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>

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

                <!-- hari ini -->
                <div class="row">
                        <div class="col-md-12">
                            <div class="box box-default collapsed-box">
                                <div class="box-header with-border">
                                <h3 class="box-title">Hari</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                                <a href="/detail/harian/absent">
                                                    <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>
                                                </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tanpa Kabar</span>
                                                    <span class="info-box-number">{{$tidakhadir}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/harian/sakit">
                                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Sakit</a></span>
                                                    <span class="info-box-number">{{$sakit}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/harian/ijin">
                                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Izin</span>
                                                    <span class="info-box-number">{{$ijin}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/harian/sakit">
                                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cuti</span>
                                                    <span class="info-box-number">{{$cuti}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>

                                    <div class="row">
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/tugasluar">
                                                    <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tugas Luar</span>
                                                        <span class="info-box-number">{{$tl}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/tugasbelajar">
                                                    <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tugas Belajar</span>
                                                        <span class="info-box-number">{{$tb}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->

                                            <!-- fix for small devices only -->
                                            <div class="clearfix visible-sm-block"></div>

                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/terlambat">
                                                    <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Terlambat</span>
                                                        <span class="info-box-number">{{$terlambat}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                <a href="/detail/harian/rapatundangan">
                                                    <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>
                                                </a>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Rapat/Undangan/Sosialisasi</span>
                                                        <span class="info-box-number">{{$event}}</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                </div>
                 <!-- hari ini -->                   

                <!-- bulan baru ini -->
                <div class="row">
                    
                        <div class="col-md-12">
                            <div class="box box-default collapsed-box">
                                <div class="box-header with-border">
                                <h3 class="box-title">Bulan</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">

                                            <a href="/detail/bulan/absent">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tanpa Kabar</span>
                                                    <span class="info-box-number">{{$tidakhadirbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                        <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/sakit">
                                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Sakit</span>
                                                    <span class="info-box-number">{{$sakitbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/ijin">
                                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Izin</span>
                                                    <span class="info-box-number">{{$ijinbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/cuti">
                                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cuti</span>
                                                    <span class="info-box-number">{{$cutibulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/tugasluar">
                                                <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Luar</span>
                                                    <span class="info-box-number">{{$tlbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/tugasbelajar">
                                                <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Belajar</span>
                                                    <span class="info-box-number">{{$tbbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
            
                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>
            
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/terlambat">
                                                <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Terlambat</span>
                                                    <span class="info-box-number">{{$terlambatbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/bulan/rapatundangan">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Rapat/Undangan/Sosialisasi</span>
                                                    <span class="info-box-number">{{$eventbulan}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                            <!-- /.col -->
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                </div>

                <!-- tahun baru -->
                <div class="row">
                    
                        <div class="col-md-12">
                            <div class="box box-default collapsed-box">
                                <div class="box-header with-border">
                                <h3 class="box-title">Tahun</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/absent">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tanpa Kabar</span>
                                                    <span class="info-box-number">{{$tidakhadirtahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/sakit">
                                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Sakit</span>
                                                    <span class="info-box-number">{{$sakittahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/ijin">
                                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Izin</span>
                                                    <span class="info-box-number">{{$ijintahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/cuti">
                                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Cuti</span>
                                                    <span class="info-box-number">{{$cutitahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/tugasluar">
                                                <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Luar</span>
                                                    <span class="info-box-number">{{$tltahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/tugasbelajar">
                                                <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tugas Belajar</span>
                                                    <span class="info-box-number">{{$tbtahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- fix for small devices only -->
                                        <div class="clearfix visible-sm-block"></div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/terlambat">
                                                <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Terlambat</span>
                                                    <span class="info-box-number">{{$terlambattahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">
                                            <a href="/detail/tahun/rapatundangan">
                                                <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>
                                            </a>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Rapat/Undangan/Sosialisasi</span>
                                                    <span class="info-box-number">{{$eventtahun}}</span>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                </div>
                <!-- tahun baru tutup -->


                <div class="row">
                    <div class="col-md-8">
                        <div class="box">
                            <div class="box-header  with-border">
                                <h3 class="box-title">Rekap Absensi Pegawai</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-wrench"></i></button>
                                    </div>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <input type="text" id="periode" readonly name="periode" class="form-control pull-right" value="{{$tahun}}" placeholder="Periode">
                                                <div class="input-group-btn">
                                                    <button type="button" id="cari" class="btn btn-default"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <canvas id="container"></canvas>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer clearfix">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="box box-warning direct-chat direct-chat-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Chat</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <form id="formchat" action="" method="post" role="form" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <input type="text" id="text" name="text" placeholder="Type Message ..." class="form-control">
                                        <input type="text" hidden readonly name="name" value="{{Auth::user()->name}}">
                                        <input type="text" hidden readonly name="user_id" value="{{Auth::user()->id}}">
                                        {{csrf_field()}}
                                        <span class="input-group-btn">
                                            <button type="button" id="kirim" class="btn btn-warning btn-flat">Send</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                            <!-- /.box-footer-->
                            <div  class="box-body">

                                <div class="direct-chat-messages" id="app" style="overflow-y:scroll;height:300px;">
                                    <div class="" v-for="chat in chats">
                                        <div class="direct-chat-msg right" v-if="chat.user_id== {{Auth::user()->id}}">
                                            <div class="direct-chat-info clearfix">
                                                <span class="direct-chat-name pull-right">@{{ chat.name  }}</span>
                                                <span class="direct-chat-timestamp pull-left">@{{ chat.created_at }}</span>
                                            </div>
                                            <!-- /.direct-chat-info -->
                                            <img class="direct-chat-img" src="{{asset('dist/img/avatarumum.png')}}" alt="message user image">
                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                @{{ chat.text }}
                                            </div>
                                        </div>
                                        <div class="direct-chat-msg" v-else>
                                            <div class="direct-chat-info clearfix">
                                                <span class="direct-chat-name pull-left">@{{ chat.name }}</span>
                                                <span class="direct-chat-timestamp pull-right" >@{{ chat.created_at }}</span>
                                            </div>
                                            <!-- /.direct-chat-info -->
                                            <img class="direct-chat-img" src="{{asset('dist/img/avatarumum.png')}}" alt="message user image">
                                            <!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                @{{ chat.text }}
                                            </div>
                                            <!-- /.direct-chat-text -->
                                    <!-- /.direct-chat-msg -->
                                        </div>
                                    </div>
                                    @include('chart.datachat')

                                    <div class="ajax-load text-center" id="ajax-load" style="display:none">
                                        <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
                                    </div>


                                </div>

                                <!--/.direct-chat-messages-->

                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Jadwal Kerja Pegawai <small>yang hampir habis masa berlakunya</small></h3>

                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                    <h4></h4>
                                    <table class="table table-hover table-bordered">
                                        <tr>

                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jenis Jadwal</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Aksi</th>
                                        </tr>
                                        <?php $i=1;
                                              $baris=0;
                                         ?>
                                        @foreach($rulejadwals2 as $rulejadwal2)
                                            <?php 
                                            $tanggalsekarang=date("Y-m-d");
                                            $minimal=date("Y-m-d",strtotime("-4 day",strtotime($rulejadwal2->tanggal_akhirrule)));
                                            $minimal=strtotime($minimal);
                                            $sekarangi=date("Y-m-d",strtotime("-4 day",strtotime($rulejadwal2->tanggal_akhirrule))); ?>
                                            @if (($minimal >= strtotime($tanggalsekarang)) && (strtotime($tanggalsekarang) <= strtotime($rulejadwal2->tanggal_akhirrule)))
                                                    
                                                    @if ($baris==0)
                                                    <tr>
                                                        <td colspan="6" ><center>Tidak ada data.</center></td>
                                                    </tr>
                                                    @endif

                                                    <?php $baris++; ?>
                                                    
                                                     <!-- @if (($i >=1) && ($i <= 10))
                                                    
                                                    @else
                                                    <tr>
                                                        <td>{{$rulejadwal2->nip}}</td>
                                                        <td>{{$rulejadwal2->nama}}</td>
                                                        <td>{{$rulejadwal2->jenis_jadwal}}</td>
                                                        <td>{{$rulejadwal2->tanggal_awalrule}}</td>
                                                        <td>{{$rulejadwal2->tanggal_akhirrule}}</td>
                                                        <td><a class="btn-sm btn-success" href="/jadwalkerjapegawai/{{ encrypt($rulejadwal2->id) }}/edit">Edit</a>
                                                            <a class="btn-sm btn-danger" data-method="delete"
                                                            data-token="{{csrf_token()}}" href="/jadwalkerjapegawai/{{ encrypt($rulejadwal2->id) }}/hapus">Hapus</a></td>
                                                    </tr>
                                                    @endif   -->
                                            @else
                                                    <!-- @if (($i >=1) && ($i <= 10))
                                                    <tr>
                                                        <td>{{$rulejadwal2->nip}}</td>
                                                        <td>{{$rulejadwal2->nama}}</td>
                                                        <td>{{$rulejadwal2->jenis_jadwal}}</td>
                                                        <td><span class="badge bg-red">{{$rulejadwal2->tanggal_awalrule}}</span></td>
                                                        <td><span class="badge bg-red">{{$rulejadwal2->tanggal_akhirrule}}</span></td>
                                                        <td><a class="btn-sm btn-success" href="/jadwalkerjapegawai/{{ encrypt($rulejadwal2->id) }}/edit">Edit</a>
                                                            <a class="btn-sm btn-danger" data-method="delete"
                                                            data-token="{{csrf_token()}}" href="/jadwalkerjapegawai/{{ encrypt($rulejadwal2->id) }}/hapus">Hapus</a></td>
                                                    </tr>
                                                    @else
                                                    
                                                    @endif  -->

                                                    <tr>
                                                        <td>{{$rulejadwal2->nip}} </td>
                                                        <td>{{$rulejadwal2->nama}}</td>
                                                        <td>{{$rulejadwal2->jenis_jadwal}}</td>
                                                        <td><span class="badge bg-red">{{$rulejadwal2->tanggal_awalrule}}</span></td>
                                                        <td><span class="badge bg-red">{{$rulejadwal2->tanggal_akhirrule}}</span></td>
                                                        <td><a class="btn-sm btn-success" href="/jadwalkerjapegawai/{{ encrypt($rulejadwal2->id) }}/edit">Edit</a>
                                                            <a class="btn-sm btn-danger" data-method="delete"
                                                            data-token="{{csrf_token()}}" href="/jadwalkerjapegawai/{{ encrypt($rulejadwal2->id) }}/hapus">Hapus</a></td>
                                                    </tr>

                                                    <?php $baris++; ?>
                                            @endif
                                            <?php $i++; ?>
                                        @endforeach
                                    </table>
                            </div>
                            <!-- /.box-body -->

                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                      <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">Detail Absen <small>Hari Ini</small></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                          <table class="table table-striped table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>NIP</th>
                              <th>Nama</th>
                              <th>Absen Terlambat</th>
                              <th>Apel</th>
                              <th>Jam Masuk</th>
                              <th>Lokasi Absen Masuk</th>
                              <th>Mulai Istirahat</th>
                              <th>Selesai Istirahat</th>
                              <th>Jam Keluar</th>
                              <th>Lokasi Absen Keluar</th>
                              <th>Akumulasi</th>
                              <th>Keterangan</th>
                              <th>Jadwal Kerja</th>
                              <th>Sifat</th>
                            </tr>
                            @foreach ($kehadirans as $key => $kehadiran)

                              <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$kehadiran->nip}}</td>
                                <td>{{$kehadiran->nama}}</td>
                                @if ($kehadiran->terlambat=="00:00:00")
                                <td>{{$kehadiran->terlambat}}</td>
                                @else
                                    @if ($kehadiran->sifat=="WA")
                                    <td><span class="badge bg-red">{{$kehadiran->terlambat}}</span></td>
                                    @else
                                    <td>{{$kehadiran->terlambat}}</td>
                                    @endif                                    
                                @endif
                                @if (($kehadiran->apel=="1") )
                                <td>A</td>
                                @else
                                    @if ($kehadiran->sifat=="WA") 
                                        <td><span class="badge bg-red">TA</span></td>
                                    @else
                                        <td>TA</td>
                                    @endif
                                @endif
                                <td>{{$kehadiran->jam_masuk}}</td>
                                <td>{{$kehadiran->namainstansimasuk}}</td>
                                <td>{{$kehadiran->keluaristirahat}}</td>
                                <td>{{$kehadiran->masukistirahat}}</td>
                                <td>{{$kehadiran->jam_keluar}}</td>
                                <td>{{$kehadiran->namainstansikeluar}}</td>
                                <td>{{$kehadiran->akumulasi_sehari}}</td>
                                @if ($kehadiran->jenis_absen=="Absent")
                                <td><span class="badge bg-red">{{$kehadiran->jenis_absen}}</span></td>
                                @else
                                <td>{{$kehadiran->jenis_absen}}</td>
                                @endif
                                <td>{{$kehadiran->jenis_jadwal}}</td>
                                @if ($kehadiran->sifat=="WA")
                                    <td>Wajib Apel</td>
                                @elseif ($kehadiran->sifat=="TWA")
                                    <td>Wajib Apel</td>
                                @elseif ($kehadiran->sifat=="FD")
                                    <td>Full Day</td></td>
                                @endif
                              </tr>
                            @endforeach
                          </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{$kehadirans->links()}}
                            </ul>
                        </div>
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                      <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">Data Kehadiran</h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="/home" method="post">
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <input type="text" id="periodeabsen" readonly name="periodeabsen" class="form-control pull-right" placeholder="Tanggal">
                                                    <div class="input-group-btn">
                                                        <button type="submit" id="cariabsen" name="cariabsen" class="btn btn-default"><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr>
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                        <th style="width: 10px">#</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Absen Terlambat</th>
                                        <th>Apel</th>
                                        <th>Jam Masuk</th>
                                        <th>Lokasi Absen Masuk</th>
                                        <th>Mulai Istirahat</th>
                                        <th>Selesai Istirahat</th>
                                        <th>Jam Keluar</th>
                                        <th>Lokasi Absen Keluar</th>
                                        <th>Akumulasi</th>
                                        <th>Keterangan</th>
                                        <th>Jadwal Kerja</th>
                                        <th>Sifat</th>
                                        </tr>
                                        @foreach ($kehadiranlalus as $key => $kehadiranlalu)

                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$kehadiranlalu->nip}}</td>
                                            <td>{{$kehadiranlalu->nama}}</td>
                                            <td>{{$kehadiranlalu->tanggal_att}}</td>
                                            @if (($kehadiranlalu->terlambat=="00:00:00"))
                                            <td>{{$kehadiranlalu->terlambat}}</td>
                                            @else
                                                @if ($kehadiranlalu->sifat=="WA")
                                                <td><span class="badge bg-red">{{$kehadiranlalu->terlambat}}</span></td>
                                                @else
                                                <td>{{$kehadiranlalu->terlambat}}</td>
                                                @endif
                                            @endif
                                            @if ($kehadiranlalu->apel=="1")
                                            <td>A</td>
                                            @else
                                                @if ($kehadiranlalu->sifat=="WA") 
                                                    <td><span class="badge bg-red">TA</span></td>
                                                @else
                                                    <td>TA</td>
                                                @endif
                                            @endif
                                            <td>{{$kehadiranlalu->jam_masuk}}</td>
                                            <td>{{$kehadiranlalu->namainstansimasuk}}</td>
                                            <td>{{$kehadiranlalu->keluaristirahat}}</td>
                                            <td>{{$kehadiranlalu->masukistirahat}}</td>
                                            <td>{{$kehadiranlalu->jam_keluar}}</td>
                                            <td>{{$kehadiranlalu->namainstansikeluar}}</td>
                                            <td>{{$kehadiranlalu->akumulasi_sehari}}</td>
                                            @if ($kehadiranlalu->jenis_absen=="Absent")
                                            <td><span class="badge bg-red">{{$kehadiranlalu->jenis_absen}}</span></td>
                                            @else
                                            <td>{{$kehadiranlalu->jenis_absen}}</td>
                                            @endif
                                            <td>{{$kehadiranlalu->jenis_jadwal}}</td>
                                            @if ($kehadiranlalu->sifat=="WA")
                                                <td>Wajib Apel</td>
                                            @elseif ($kehadiranlalu->sifat=="TWA")
                                                <td>Wajib Apel</td>
                                            @elseif ($kehadiranlalu->sifat=="FD")
                                                <td>Full Day</td></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </table>
                                
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{$kehadiranlalus->appends(['periodeabsen'=>($periodeabsen)])->links()}}
                            </ul>
                        </div>
                      </div>
                    </div>
                </div>


            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

                @include('layouts.footer')

        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- jQuery 3 -->
    {{--<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>--}}

    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>

    <script>
        var apel = new Array();
        var absen = new Array();

        $(function() {
            $('input[name="periode"]').datepicker({
                format: "yyyy",
                autoclose: true,
                minViewMode: "years"
            });

            $('input[name="periodeabsen"]').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            var url = "{{url('/home/data')}}";

            $.get(url, function(response) {

                absen.push(response['Absen']);
                apel.push(response['Apel']);

                // var ctx = $('#container').getContext("2d");
                var ctx = document.getElementById("container").getContext("2d");
                var color = Chart.helpers.color;
                  window.myBar = new Chart(ctx, {
                      type: 'bar',
                      data: {
                              labels: ["JAN", "FEB", "MAR", "APR","MEI", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                              datasets: [
                                  {
                                      label: "Apel",
                                      data: apel[0],
                                      backgroundColor: [
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                      ],
                                      borderColor: [
                                          'rgba(255,99,132,1)'
                                      ],
                                      borderWidth: 1,
                                  },
                                  {
                                      label: "Tanpa Kabar",
                                      data: absen[0],
                                      backgroundColor: [
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                      ],
                                      borderColor: [
                                          'rgba(54, 162, 235, 1)'
                                      ],
                                      borderWidth: 1,
                                  }
                              ]
                          },
                      options: {
                          responsive: true,
                          legend: {
                              position: 'top',
                          },
                          title: {
                              display: true,
                              text: 'Grafik Absensi'
                          }
                      }
                  });

            });
        });


        $("#cari").click(function () {
            apel=[];
            absen=[];
            $("#container").html("");

            var tahun = $("#periode").val();

            var url = "{{url('/home/datacari')}}";

            $.get(url,{ tahun: tahun }, function(response) {

                absen.push(response['Absen']);
                apel.push(response['Apel']);

                var ctx = document.getElementById("container").getContext("2d");
                var color = Chart.helpers.color;
                  window.myBar = new Chart(ctx, {
                      type: 'bar',
                      data: {
                              labels: ["JAN", "FEB", "MAR", "APR","MEI", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                              datasets: [
                                  {
                                      label: "Apel",
                                      data: apel[0],
                                      backgroundColor: [
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                          'rgba(255, 99, 132, 0.2)',
                                      ],
                                      borderColor: [
                                          'rgba(255,99,132,1)'
                                      ],
                                      borderWidth: 1,
                                  },
                                  {
                                      label: "Tanpa Kabar",
                                      data: absen[0],
                                      backgroundColor: [
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                          'rgba(54, 162, 235, 0.2)',
                                      ],
                                      borderColor: [
                                          'rgba(54, 162, 235, 1)'
                                      ],
                                      borderWidth: 1,
                                  }
                              ]
                          },
                      options: {
                          responsive: true,
                          legend: {
                              position: 'top',
                          },
                          title: {
                              display: true,
                              text: 'Grafik Absensi'
                          }
                      }
                  });
            });
        });
    </script>
    <script>

        $(document).ready(function() {

            var page = 1;

            $('#app').on('scroll', function () {
                if ($('#app').scrollTop() >= $('#app').height() - $('#app').height()-5) {
                    page++;
                    loadMoreData(page);
                }

                function loadMoreData(page) {
                    $.ajax(
                            {
                                url: '?page=' + page,
                                type: "get",
                                beforeSend: function () {
                                    $('#ajax-load').show();
                                }
                            })
                            .done(function (data) {
                                if (data.html == " ") {
                                    //                            $('#ajax-load').html("No more records found");
                                    return;
                                }
                                $('#ajax-load').hide();

                                console.log(data.html);
                                $(".direct-chat-messages").append(data.html);
//                                $(data.html).insertAfter("#ajax-load");
                            })
                            .fail(function (jqXHR, ajaxOptions, thrownError) {
                                console.log(thrownError);
                                console.log(ajaxOptions);
                                alert('server not responding...');
                            });
                }

            });
        });
    </script>
    <script>
        var socket = io('http://eabsen.kalselprov.go.id:3000');
        new Vue({
            el: '#app',
            data: {
                chats: []
            },
            mounted: function() {
                // 'test-channel:UserSignedUp'
                socket.on('chats:App\\Events\\ChatEvent', function(data) {
                    this.chats.unshift({user_id:data.user_id,name:data.name,created_at:data.created_at,text:data.text})
                    //alert(data.text)
                    console.log(data)
                }.bind(this))
            }
        })
    </script>

    <script>
        $(document).on('click','#kirim',function (){
            var text=$('#text').val();
            $.ajax({
                type:'post',
                url:'{{route('chatpost')}}',
                data: new FormData($('#formchat')[0]),
                dataType:'json',
                async:true,
                processData: false,
                contentType: false,
                success:function(response){
                    $('#text').val("");
                },
            });
            $('#text').val("");
        });
    </script>
    </body>
@endsection
