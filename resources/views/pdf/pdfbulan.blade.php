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
            font-size: 10px;
        }
        .garis{
          border:1px solid;
        }
        .tabledata{
          padding-top: 10px;
          padding-bottom: 10px;
        }
        .header{
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
      <h3>LAPORAN ABSENSI BULAN</h3>
    </div>
    <div class="subtitle">
      <h4>Oleh <br>{{$instansi}} PROV. KALSEL</h4>
    </div>
    <div>
      <table class="tabel">
          <tr>
            <th class="header garis">NIP</th>
            <th class="header garis">Nama</th>
            <th class="header garis">Periode</th>
            <th class="header garis">Hari Kerja</th>
            <th class="header garis">Hadir</th>
            <th class="header garis">Absent</th>
            <th class="header garis">Izin</th>
            <th class="header garis">Izin Terlambat</th>
            <th class="header garis">Terlambat</th>
            <th class="header garis">Sakit</th>
            <th class="header garis">Cuti</th>
            <th class="header garis">Tugas Luar</th>
            <th class="header garis">Tugas Belajar</th>
            <th class="header garis">Rapat/Undangan</th>
            <th class="header garis">Pulang Cepat</th>
            <th class="header garis">Tidak Apel</th>
            <th class="header garis">Tanpa Kabar</th>
            <th class="header garis">Akumulasi Terlambat</th>
            <th class="header garis">Akumulasi Jam Kerja</th>
          </tr>

          @foreach($atts as $att)
              <tr>
                  <td class="garis tabledata">{{$att->nip}}</td>
                  <td class="garis tabledata">{{$att->nama}}</td>
                  <td class="garis tabledata">{{$att->periode}}</td>
                  <td class="garis tabledata">{{$att->hari_kerja}}</td>
                  <td class="garis tabledata">{{$att->hadir}}</td>
                  <td class="garis tabledata">{{$att->tanpa_kabar}}</td>
                  <td class="garis tabledata">{{$att->ijin}}</td>
                  <td class="garis tabledata">{{$att->ijinterlambat}}</td>

                  <td class="garis tabledata">{{$att->terlambat}}</td>
                  <td class="garis tabledata">{{$att->sakit}}</td>
                  <td class="garis tabledata">{{$att->cuti}}</td>
                  <td class="garis tabledata">{{$att->tugas_luar}}</td>
                  <td class="garis tabledata">{{$att->tugas_belajar}}</td>
                  <td class="garis tabledata">{{$att->rapatundangan}}</td>
                  <td class="garis tabledata">{{$att->pulang_cepat}}</td>
                  <td class="garis tabledata">{{$att->persentase_apel}}</td>
                  <td class="garis tabledata">{{$att->persentase_tidakhadir}}</td>
                  <td class="garis tabledata">{{$att->total_terlambat}}</td>
                  <td class="garis tabledata">{{$att->total_akumulasi}}</td>
              </tr>
          @endforeach
      </table>
    </div>
    <script type="text/php">
    if ( isset($pdf) ) {
        $x = 72;
        $y = 580;
        $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT} - "."{{$instansi}} PROV. KALSEL";
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
