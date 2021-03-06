@foreach($attstrans as $attstran)
<li>

    @if ($attstran->status_kedatangan=="0")
    <i class="fa fa-bank bg-blue"></i>
    @else
      <i class="fa fa-bank bg-green"></i>
    @endif
    <div class="timeline-item">
        <span class="time"><i class="fa fa-calendar"></i> {{ date("d-F-Y",strtotime($attstran->tanggal)) }}</span>

        <h3 class="timeline-header">{{ $attstran->nama }} <small>dari {{ $attstran->instansiPegawai }}</small></h3>

        <div class="timeline-body">
            Telah @if( $attstran->status_kedatangan=="0") hadir @else pulang @endif di <strong>{{ $attstran->namaInstansi }}</strong> pada jam {{ $attstran->jam }}
        </div>

    </div>
</li>
@endforeach
