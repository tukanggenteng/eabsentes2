@if (!empty(($inforekap)))
  <form action="/rekapbulanans" method="post">
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-bell"></i> Peringatan!</h4>
        {{csrf_field()}}
        {{$inforekap}}<button class="btn-sm btn-success" type="submit">link ini.</button>
         (Apabila pelaksanaan absensi belum satu minggu, maka instansi bersangkutan dilarang mengklik tombol ini)
    </div>
  </form>
@endif
