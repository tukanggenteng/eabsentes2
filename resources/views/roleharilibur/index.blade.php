@extends('layouts.app')

@section('title')
Hari Libur 
@endsection

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
                Hari Libur
            </h1>
            </section>

            <!-- Main content -->
            <section class="content">
            <div class="row">
                @if (Auth::user()->role_id==2)
                <div class="col-md-3">
                  <div class="box box-solid">
                        <div class="box-header with-border">
                        <h4 class="box-title">Hari Libur</h4>
                        </div>
                        <div class="box-body">
                        <!-- the events -->
                        <!-- <div style="max-height:375px;overflow:auto;"> -->
                          <div id="external-events">
                              @foreach ($datahariliburs as $dataharilibur)
                                <div class="external-event bg-red" data-id="{{encrypt($dataharilibur->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-harilibur="{{$dataharilibur->nama_hari_libur}}">
                                  {{$dataharilibur->nama_hari_libur}}
                                </div>
                              @endforeach
                          </div>
                        <!-- </div> -->
                        </div>
                        <!-- /.box-body -->
                  </div>
                </div>
                @endif
                <!-- /.col -->
                @if (Auth::user()->role_id==2)
                  <div class="col-md-9">
                @else
                  <div class="col-md-12">
                @endif
                <div class="box box-primary">

                    <div class="box-body no-padding">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

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
    <script src="{{asset('bower_components/jquery-touch-punch/jquery.ui.touch-punch.min.js')}}"></script>
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

    var token=$('#token').val();

    var url = "{{url('/role/harilibur/calendar')}}";
    var urldelete = "{{url('/role/harilibur/calendar/delete')}}";
    var urlpost = "{{url('/role/harilibur/calendar/store')}}";

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
          title: $(this).data("harilibur"), // use the element's text as the event title
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
                    tanggalberlakuharilibur:date2.toISOString().slice(0,10),
                    tanggalberlakuharilibur:date2.toISOString().slice(0,10),
                    harilibur_id:copiedEventObject.id,
                  },
            type: 'POST',
            dataType: 'json',
            success: function(response){
              if (response=='success')
              {
                swal("Berhasil menambah hari libur.", "", "success");
              }
              else
              {
                swal("Gagal menambah hari libur.", "", "error");
              }
              
              // console.log(response);
              // if(response == 'success')
              // $('#calendar').fullCalendar('updateEvent',event);
              $('#calendar').fullCalendar('refetchEvents');
            },
            error: function(e){
              swal("Gagal menambah hari libur.", "", "error");
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
              swal("Berhasil menghapus hari libur.", "", "success");
              // console.log(response);
              // if(response == 'success')
              // $('#calendar').fullCalendar('updateEvent',event);
              $('#calendar').fullCalendar('refetchEvents');
            },
            error: function(e){
              swal("Gagal menghapus hari libur.", "", "error");
            }
          });
        }
      }
    })

  })
</script>


    </body>
@endsection
