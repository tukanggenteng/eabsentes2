@foreach($attstrans as $attstran)
<li>

    @if ($attstran->status_kedatangan=="0")
    <i class="fa fa-map-marker bg-blue"></i>
    @else
      <i class="fa fa-map-marker bg-green"></i>
    @endif
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> {{ $attstran->tanggal }}</span>

        <h3 class="timeline-header">{{ $attstran->nama }} <small>dari {{ $attstran->instansiPegawai }}</small></h3>

        <div class="timeline-body">
            Telah @if( $attstran->status_kedatangan=="0") hadir @else pulang @endif di <strong>{{ $attstran->namaInstansi }}</strong> pada jam {{ $attstran->jam }}
        </div>


        {{--<div class="timeline-footer">--}}
        {{--<a class="btn btn-primary btn-xs">Read more</a>--}}
        {{--<a class="btn btn-danger btn-xs">Delete</a>--}}
        {{--</div>--}}
    </div>
</li>
@endforeach
