<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        table{
          width:100%;
          border-collapse: collapse;
        }
        td,th{
          border:1px solid;
                    text-align: center;
            font-size: 15px;
        }
        .garis{
          border:1px solid;
        }
        .tabledata{
          padding-top: 10px;
          padding-bottom: 10px;
          padding-left: 5px;
          padding-right: 5px;
        }
        .header{

          padding-left: 5px;
          padding-right: 5px;
          background-color: rgb(184, 184, 184);
        }
        .title{
          max-width: 100%;
          text-align: center;
          font-size: 24px;
        }

        .tabel{
          max-width=585.276pt;
        }

        .subtitle{

            max-width: 100%;
            text-align: center;
        }

        .data{
          font-size: 5px;
        }

        footer { position: fixed; bottom: 0px; }

        .pagenum:before { content: counter(page); }

    </style>
    <title>Laporan Harian Pegawai</title>
  </head>
  <body>
    <div class="title">
      <h3>LAPORAN ABSENSI HARIAN</h3>
    </div>
    <div class="subtitle">
      <h4>Oleh <br>{{$instansi}}</h4>
    </div>
    <div>
      <table class="tabel">
        <tr>
          <th class="header garis">NIP</th>
          <th class="header garis">Nama</th>
          <th class="header garis">Tanggal</th>
          <th class="header garis">Jam Masuk</th>
          <th class="header garis">Instansi Hadir</th>
          <th class="header garis">Terlambat</th>
          <th class="header garis">Jam Pulang</th>
          <th class="header garis">Instansi Pulang</th>
          <th class="header garis">Akumulasi Kerja</th>
          <th class="header garis">Keterangan</th>
        </tr>
        @foreach ($atts as $key => $att)
          <tr>
            <td class="garis tabledata">{{$att->nip}}</td>
            <td class="garis tabledata">{{$att->nama}}</td>
            <td class="garis tabledata">{{$att->tanggal_att}}</td>
            <td class="garis tabledata">{{$att->jam_masuk}}</td>
            <td class="garis tabledata">{{$att->namainstansimasuk}}</td>
            <td class="garis tabledata">{{$att->terlambat}}</td>
            <td class="garis tabledata">{{$att->jam_keluar}}</td>
            <td class="garis tabledata">{{$att->namainstansikeluar}}</td>
            <td class="garis tabledata">{{$att->akumulasi_sehari}}</td>
            <td class="garis tabledata">{{$att->jenis_absen}}</td>
          </tr>
        @endforeach
      </table>
    </div>
    <script type="text/php">
    if ( isset($pdf) ) {
        $x = 72;
        $y = 580;
        $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT} - "."{{$instansi}}";
        $font = $fontMetrics->get_font("helvetica", "bold");
        $size = 6;
        $color = array(0 ,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
    </script>
  </body>

  <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>

</html>
