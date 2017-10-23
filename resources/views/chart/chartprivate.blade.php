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
            @include('layouts.inforekap')

                <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user-times"></i></span>

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
                                <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Sakit</span>
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
                                <span class="info-box-icon bg-green"><i class="fa fa-info"></i></span>
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
                                <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Cuti</span>
                                    <span class="info-box-number">{{$cuti}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-paper-plane"></i></span>

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
                                <span class="info-box-icon bg-yellow "><i class="fa fa-graduation-cap"></i></span>

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
                                <span class="info-box-icon bg-red"><i class="fa fa-bell-slash"></i></span>
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
                                <span class="info-box-icon bg-aqua"><i class="fa fa-suitcase"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Event</span>
                                    <span class="info-box-number">{{$event}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>

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
                            {{--<ul v-for="chat in chats">--}}
                                {{--<div v-if="chat.user_id == '{{Auth::user()->id}}'">--}}
                                    {{--<li>@{{ chat.text }}</li>--}}
                                {{--</div>--}}
                                {{--<div v-else>--}}
                                    {{--<li>asdsad</li>--}}
                                {{--</div>--}}
                            {{--</ul>--}}
                            <!-- /.box-footer-->
                            <div  class="box-body">

                                <div class="direct-chat-messages" id="app" style="overflow-y:scroll;height:300px;">
                                    <div class="" v-for="chat in chats">
                                        <div class="direct-chat-msg right" v-if="chat.user_id== '1'">
                                            <div class="direct-chat-info clearfix">
                                                <span class="direct-chat-name pull-right">@{{ chat.name  }}</span>
                                                <span class="direct-chat-timestamp pull-left">@{{ chat.created_at }}</span>
                                            </div>
                                            <!-- /.direct-chat-info -->
                                            <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
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
                                            <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
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

    <script type="text/javascript">
        var apel = new Array();
        var absen = new Array();

        $(function() {
            $('input[name="periode"]').datepicker({
                format: "yyyy",
                autoclose: true,
                minViewMode: "years"
        });

            var url = "{{url('/home/data')}}";

            $.get(url, function(response) {

                absen.push(response['Absen']);
                apel.push(response['Apel']);

                var ctx = $('#container');
                var stackedLine = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["JAN", "FEB", "MAR", "APR", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                        datasets: [
                            {
                                label: "Persentase Apel",
                                data: apel[0],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)'
                                ],
                                borderWidth: 1
                            },
                            {
                                label: "Persentase Tidak Hadir",
                                data: absen[0],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)'
                                ],
                                borderWidth: 1
                            }
                        ]
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

                var ctx = $('#container');
                var stackedLine = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["JAN", "FEB", "MAR", "APR", "JUN", "JUL", "AGS", "SEPT", "OKT", "NOV", "DES"],
                        datasets: [
                            {
                                label: "Persentase Apel",
                                data: apel[0],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)'
                                ],
                                borderWidth: 1
                            },
                            {
                                label: "Persentase Tidak Hadir",
                                data: absen[0],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)'
                                ],
                                borderWidth: 1
                            }
                        ]
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">

        $(document).ready(function() {

            var page = 1;

            $('#app').on('scroll', function () {

//                alert($('#app').scrollTop());
//                if ($('#app').scrollTop() + $('#app').height() >= $('#app').height()) {
//                    page++;
//                    loadMoreData(page);
//                }

                if ($('#app').scrollTop() + $('#app').height() >= $('#app').height()) {
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
    <script type="text/javascript">
        var socket = io('http://eabsen.dev:3000');
        new Vue({
            el: '#app',
            data: {
                chats: []
            },
            mounted: function() {
                // 'test-channel:UserSignedUp'

                socket.on('chats:App\\Events\\ChatEvent', function(data) {
                    this.chats.append({user_id:data.user_id,name:data.name,created_at:data.created_at,text:data.text})
//                    alert(data.text)
                    console.log(data)
                }.bind(this))
            }
        })
    </script>

    <script type="text/javascript">
        $(document).on('click','#kirim',function (){
            var text=$('#text').val();
            $.ajax({
                type:'post',
                url:'{{route('chatpost')}}',
                data: new FormData($('#formchat')[0]),
                dataType:'json',
                async:false,
                processData: false,
                contentType: false,
                success:function(response){
                    $('#text').val("");
                },
            });
        });
    </script>
    </body>
@endsection
