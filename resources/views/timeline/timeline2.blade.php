@extends('layouts.app')

@section('title')
Time Line
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">
@endpush

@section('body')
    <div class="wrapper">

      @include('layouts.header')

      @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

          <!-- Main content -->
          <section class="content">

            <!-- Main content -->
            <section class="content">

                <div class="row">
                    <div class="col-md-12" id="app">
                        <!-- The time line -->
                            <!-- timeline time label -->
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                        <ul class="timeline">
                            <!-- timeline item -->
                            <li v-for="att in atts">
                              <i class="fa fa-bank bg-blue" v-if="att.statusmasuk== 'hadir'"></i>
                              <i class="fa fa-bank bg-orange" v-if="att.statusmasuk== 'hadir terlambat'"></i>
                              <i class="fa fa-bank bg-yellow" v-if="att.statusmasuk== 'pulang lebih cepat'"></i>
                              <i class="fa fa-bank bg-green" v-if="att.statusmasuk== 'pulang'"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-calendar"></i> @{{ att.tanggal }}</span>

                                    <h3 class="timeline-header">@{{ att.namaPegawai }} <small>dari @{{ att.instansiPegawai }}</small></h3>

                                    <div class="timeline-body">
                                        Telah @{{ att.statusmasuk }} di <strong>@{{ att.namaInstansi }}</strong> pada jam @{{ att.jam }}
                                    </div>

                                </div>
                            </li>
                            @include('timeline.datatimeline')
                        </ul>
                        <div class="ajax-load text-center" id="ajax-load" style="display:none">

                            <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>

                        </div>
                    </div>
                    <!-- /.col -->
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
    <!-- SlimScroll -->

    <!-- FastClick -->
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>

    <script type="text/javascript">
        var socket = io('http://eabsen.kalselprov.go.id:3000');
        new Vue({
            el: '#app',
            data: {
                atts: [],
            },
            mounted: function() {
                // 'test-channel:UserSignedUp'

                socket.on('test-channel.{{Auth::user()->instansi_id}}:App\\Events\\Timeline', function(data) {
                    this.atts.unshift({class:data.class,statusmasuk:data.statusmasuk,namaPegawai:data.namaPegawai,namaInstansi:data.namaInstansi,tanggal:data.tanggal,jam:data.jam,instansiPegawai:data.instansiPegawai})
                     console.log(data)

                }.bind(this))
            }
        })
    </script>

    <script type="text/javascript">
        var page = 1;
        $(window).scroll(function() {
            if($(window).scrollTop() >= $(document).height() - $(window).height()-30) {

                page++;
                loadMoreData(page);
            }
        });

        function loadMoreData(page){
            $.ajax(
                    {
                        url: '?page=' + page,
                        type: "get",
                        beforeSend: function()
                        {
                            $('#ajax-load').show();
                        }
                    })
                    .done(function(data)
                    {
                        if(data.html == " "){
//                            $('#ajax-load').html("No more records found");
                            return;
                        }
                        $('#ajax-load').hide();
                        $(".timeline").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError)
                    {
                        alert('server not responding...');
                    });
        }
    </script>

@endsection
