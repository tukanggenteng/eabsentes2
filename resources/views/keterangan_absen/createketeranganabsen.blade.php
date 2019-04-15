@extends('layouts.app')

@section('title')
Keterangan Absen
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
                Keterangan Absen
            </h1>
            </section>

            <!-- Main content -->
            <section class="content">
            <div class="row">

                <div class="col-md-3">
                  <div class="box box-solid">

                        <div class="box-header with-border">
                          <h4 class="box-title">{{$jadwalkerjadata->jenis_jadwal}}</h4>
                        </div>
                        <div class="box-body">
                          NIP : {{$pegawai->nip}}
                          <br>
                          Nama : {{$pegawai->nama}}
                          <br>
                          <hr>
                          <a class="btn btn-block btn-success btn-sm" href="/keteranganabsen"><span class="fa fa-chevron-circle-left"></span> Kembali</a>
                        </div>
                        <div class="box-body">
                          <div id="external-events">
                              @foreach ($jenisabsens as $jenisabsen)
                              @if ((Auth::user()->role->namaRole=="user") && (($jenisabsen->id==13) || ($jenisabsen->id==11)))
                              
                              @else

                                  @if ($jenisabsen->id==2)
                                  <div class="external-event bg-red" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif ($jenisabsen->id==3)
                                  <div class="external-event bg-blue" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==4))
                                  <div class="external-event bg-navy" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==5))
                                  <div class="external-event bg-orange" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==6))
                                  <div class="external-event bg-maroon" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==7))
                                  <div class="external-event bg-purple" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==8))
                                  <div class="external-event bg-olive" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==9))
                                  <div class="external-event bg-black" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==10))
                                  <div class="external-event bg-blue" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==11))
                                  <div class="external-event bg-blue" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==12))
                                  <div class="external-event bg-blue" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @elseif (($jenisabsen->id==13))
                                  <div class="external-event bg-blue" data-id="{{encrypt($jenisabsen->id)}}" data-jammasuk="00:00" data-jamkeluar="23:59:59" data-jenisabsen="{{$jenisabsen->jenis_absen}}">
                                  @endif
                                  
                                    {{$jenisabsen->jenis_absen}}
                                  </div>
                                @endif
                                
                              @endforeach
                          </div>
                        <!-- </div> -->
                        </div>

                        <!-- /.box-body -->
                  </div>
                </div>

                <!-- /.col -->
               
                <div class="col-md-9">
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
    var url = "{{url('/keteranganabsen/calendar/data')}}";
    var urldelete = "{{url('/keteranganabsen/calendar/destroy')}}";
    var urlpost = "{{url('/keteranganabsen/calendar/store')}}";

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
          title: $(this).data("jenisabsen"), // use the element's text as the event title
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
          jadwalkerja_id: "{{$jadwalkerja_id}}",
          pegawai_id: "{{$pegawai_id}}",
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
                    awalrule:date2.toISOString().slice(0,10),
                    tanggalberlakuharilibur:date2.toISOString().slice(0,10),
                    jadwalkerja_id: "{{$jadwalkerja_id}}",
                    pegawai_id: "{{$pegawai_id}}",
                    jenisabsen_id:copiedEventObject.id,
                  },
            type: 'POST',
            dataType: 'json',
            success: function(response){
              if (response=='success')
              {
                swal("Berhasil mengubah keterangan absen.", "", "success");
              }
              else
              {
                swal("Gagal mengubah keterangan absen.", "", "error");
              }
              
              // console.log(response);
              // if(response == 'success')
              // $('#calendar').fullCalendar('updateEvent',event);
              $('#calendar').fullCalendar('refetchEvents');
            },
            error: function(e){
              swal("Gagal mengubah keterangan absen.", "", "error");
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
                  },
            type: 'POST',
            dataType: 'json',
            success: function(response){
              if (response=='success')
              {
                swal("Berhasil menghapus keterangan absen.", "", "success");
              }
              else
              {
                swal("Gagal menghapus keterangan absen.", "", "error");
              }
              // console.log(response);
              // if(response == 'success')
              // $('#calendar').fullCalendar('updateEvent',event);
              $('#calendar').fullCalendar('refetchEvents');
            },
            error: function(e){
              swal("Gagal menghapus keterangan absen.", "", "error");
            }
          });
        }
      }
    })

  })
</script>


    </body>
@endsection
