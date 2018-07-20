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

<!-- fullCalendar -->
<link rel="stylesheet" href="{{asset('bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">


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
            <!-- Content Header (Page header) -->
            <section class="content-header">
            <h1>
                Jadwal Kerja
                <small>Harian</small>
            </h1>
            </section>

            <!-- Main content -->
            <section class="content">
            <div class="row">
                <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Data Pegawai</h3>
                    </div>
                    <div class="box-body">
                      NIP : {{$pegawai->nip}}
                      <br>
                      Nama : {{$pegawai->nama}}
                      <br>
                      <hr>
                      <a class="btn btn-block btn-success btn-sm" href="{{route('indexjadwalkerjapegawaiharian')}}"><span class="fa fa-chevron-circle-left"></span> Kembali</a>
                    </div>
                  </div>
                  <div class="box box-solid">
                      <div class="box-header with-border">
                      <h4 class="box-title">Jadwal Kerja</h4>
                      </div>
                      <div class="box-body">
                      <!-- the events -->
                      <div id="external-events">
                          @foreach ($jadwals as $jadwal)
                            <div class="external-event {{$jadwal->classdata}}" data-id="{{encrypt($jadwal->id)}}" data-jammasuk="{{$jadwal->jam_masukjadwal}}" data-jamkeluar="{{$jadwal->jam_keluarjadwal}}" data-jenisjadwal="{{$jadwal->jenis_jadwal}}">{{$jadwal->jenis_jadwal}}</div>
                          @endforeach
                      </div>
                      </div>
                      <!-- /.box-body -->
                  </div>
                  
                <!-- /. box -->
                
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                <div class="box box-primary">

                    <div class="box-body no-padding">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="idemploye" id="idemploye" value="{{$idemploye}}">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /. box -->
                </div>
                <!-- /.col -->
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

    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->

    <!-- fullCalendar -->
    <script src="{{asset('bower_components/moment/moment.js')}}"></script>
    <script src="{{asset('bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>

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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
  $(function () {

    eventdata=[];

    var idemploye=$('#idemploye').val();
    var token=$('#token').val();

    var url = "{{url('/eventcalendar')}}";
    var urldelete = "{{url('/eventcalendar/delete')}}";
    var urlpost = "{{url('/eventcalendar/post')}}";

    // $.get(url,{ idemploye : idemploye }, function(response) {
    //   eventdata.push(response);
    // });

    // $('#calendar').fullCalendar({
    //     events: "{{url('/eventcalendar')}}"
    //   });
    // alert(eventdata);

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $(this).data("jenisjadwal"), // use the element's text as the event title
          id: $(this).data("id"),
          jammasuk: $(this).data("jammasuk"),
          jamkeluar: $(this).data("jamkeluar")
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear();
    var dateend = new Date();
    var dend    = dateend.getDate(),
        mend    = dateend.getMonth(),
        yend    = dateend.getFullYear();
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      eventSources: [
      {
        url: url,
        type: 'POST',
        data: {
          idemploye: idemploye,
          _token:token
        },
        error: function() {
          alert('there was an error while fetching events!');
        }
      }

      // any other sources...

      ]
      ,
      editable  : true,
      eventDurationEditable: false,
      eventStartEditable: false,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date ,dateend, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)
        
          // alert(Object.keys(eventObject).map(function(key){ return eventObject[key] }));

        // assign it the date that was reported
        var date2=date;

        var timemasuk= copiedEventObject["jammasuk"];
        var datatimemasuk= timemasuk.split(':')
        var timekeluar= copiedEventObject["jamkeluar"]
        var datatimekeluar=timekeluar.split(':')
        var date = new Date(date);
        date.setHours(datatimemasuk[0],datatimemasuk[1],datatimemasuk[2]);
        var dateend = new Date(date);
        dateend.setHours(datatimekeluar[0],datatimekeluar[1],datatimekeluar[2])

        copiedEventObject.start           = date
        copiedEventObject.end             = dateend
        // copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

          $.ajax({
            url: urlpost,
            data: { _token:token,
                    idemploye:idemploye,
                    awalrule:date2.toISOString().slice(0,10),
                    akhirrule:date2.toISOString().slice(0,10),
                    jadwalid:copiedEventObject.id,
                  },
            type: 'POST',
            dataType: 'json',
            success: function(response){
              swal("Berhasil menambah jadwal kerja pegawai.", "", "success");
              // console.log(response);
              // if(response == 'success')
              // $('#calendar').fullCalendar('updateEvent',event);
              $('#calendar').fullCalendar('refetchEvents');
            },
            error: function(e){
              swal("Gagal menambah jadwal  kerja pegawai.", "", "error");
            }
          });

      },
      eventClick: function(event, jsEvent) {
        console.log(event.start._i);
        var title = confirm('Hapus jadwal '+event.title+' ?', { buttons: { Ok: true, Cancel: false} });
        // alert(event.id);
        if (title){
          $.ajax({
            url: urldelete,
            data: { _token:token,
                  id:event.id,
                  tanggal:event.start._i
                  },
            type: 'POST',
            dataType: 'json',
            success: function(response){
              swal("Berhasil menghapus jadwal kerja pegawai.", "", "success");
              // console.log(response);
              // if(response == 'success')
              // $('#calendar').fullCalendar('updateEvent',event);
              $('#calendar').fullCalendar('refetchEvents');
            },
            error: function(e){
              swal("Gagal menghapus jadwal kerja pegawai.", "", "error");
            }
          });
        }
      }
    })
  })
</script>


    </body>
@endsection
