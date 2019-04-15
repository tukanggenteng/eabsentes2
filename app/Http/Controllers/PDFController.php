<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\att;
use App\finalrekapbulanan;
use App\masterbulanan;
use Illuminate\Support\Facades\DB;
use App\pegawai;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use PHPExcel_Style_Fill, PHPExcel_Style_Border;
use PHPExcel_Worksheet_Drawing;

class PDFController extends Controller
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  LAPORAN HARIAN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function index(Request $request)
    {
      $tanggalhariini=date('Y-m-d');

      if (isset($request->nip) && isset($request->tanggal))
      {
        // $tanggal=explode('-',$request->tanggal);
        $tanggal=$request->tanggal;
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        ->where('atts.tanggal_att','=',$tanggal)
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','atts.tanggal_att as hari', 'jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        for($i=0;$i<count($atts);$i++) { $this->konversiHari($atts, $i); }

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        // dd("sd");

        // $tanggal=explode('-',$request->tanggal);
        $tanggal=$request->tanggal;
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        ->where('atts.tanggal_att','=',$tanggal)
        // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','atts.tanggal_att as hari', 'jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        for($i=0;$i<count($atts);$i++) { $this->konversiHari($atts, $i); }

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        // $tanggal=explode('-',$request->tanggal);
        $tanggal=$request->tanggal;
        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        ->where('pegawais.nip','=',$request->nip)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','atts.tanggal_att as hari', 'jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);
        $request->tanggal=null;

        for($i=0;$i<count($atts);$i++) { $this->konversiHari($atts, $i); }

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select('atts.*','atts.tanggal_att as hari','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','desc')
        ->paginate(30);

        $nip=null;
        $tanggal=null;

        for($i=0;$i<count($atts);$i++) { $this->konversiHari($atts, $i); }

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);

      }

    }

    public function pdfExcelHari($namaFile, $instansi, $periodeH, $atts, $jumlahbaris){
      //for not repeat function in another function
      //excel for days report
      //data header
      $headerdata_1 = array('NIP','Nama','Hari','Tanggal','Apel','Terlambat',
                            'Jam Masuk', 'Mulai Istirahat', 'Selesai Istirahat', 'Jam Pulang',
                            'Akumulasi Jam Kerja', 'Keterangan', 'Jenis Jadwal', 'Sifat Jadwal');

      return Excel::create($namaFile,function($excel) use ($instansi, $periodeH, $headerdata_1, $atts, $jumlahbaris ){ // create('namaFilenya',function($excel) use ($atts)

              $excel->sheet('Laporan',function($sheet) use ($instansi, $periodeH, $headerdata_1, $atts, $jumlahbaris){

                      $sheet->protect('b1k1n4pl1k451');
                      $sheet->setPrintArea('A1:N'.$jumlahbaris); //set printing area
                      $sheet->setOrientation('landscape');
                      $sheet->setFitToPage(1);
                      $sheet->setFitToWidth(1);  // fit allcolumn in one page
                      $sheet->setFitToHeight(0);
                      $sheet->setRowsToRepeatAtTop(1,5);
                      $sheet->setFreeze('A6');

                      $sheet->setAutoSize(array('A','B','C','D'));
                      $sheet->setWidth(array( 'E' => 14.29, 'F' => 14.29, 'G' => 14.29, 'H' => 14.29, 'I'=>14.29, 'J' => 14.29, 'K' => 14.29, 'L' => 14.29, 'M' => 14.29 ));
                      $sheet->getStyle('D2:N2')->getAlignment()->setWrapText(true);
                      //$sheet->getStyle('D6:O6')->getAlignment()->setWrapText(true); //wrap text nama instansi

                      //HEADER UTAMA//
                      $sheet->mergeCells('B1:N1');
                      $sheet->cell('B1',function ($cell){
                        $cell->setAlignment('center');
                        $cell->setValue('REKAPITULASI DAFTAR HADIR PEGAWAI NEGERI SIPIL');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(25);
                      });

                      $sheet->cell('B2',function ($cell) { $cell->setAlignment('left'); $cell->setValignment('center'); $cell->setValue('UNIT'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->cell('C2',function ($cell) { $cell->setAlignment('left'); $cell->setValignment('center'); $cell->setValue(':'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->mergeCells('D2:N2');
                      $sheet->cell('D2',function ($cell) use ($instansi){
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setValue($instansi.' PROVINSI KALIMANTAN SELATAN');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(20);
                      });

                      $sheet->cell('B3',function ($cell) { $cell->setAlignment('left');$cell->setValignment('top'); $cell->setValue('PERIODE'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->cell('C3',function ($cell) { $cell->setAlignment('left');$cell->setValignment('top'); $cell->setValue(':'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->mergeCells('D3:N3');
                      $sheet->cell('D3',function ($cell) use ($periodeH){
                        $cell->setAlignment('left');
                        $cell->setValignment('top');
                        $cell->setValue($periodeH);
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(22);
                      });

                      $objDrawing = new PHPExcel_Worksheet_Drawing;
                      $objDrawing->setPath(public_path('img/logoProvKalSellg.png')); //your image path
                      $objDrawing->setCoordinates('A1');
                      $objDrawing->setResizeProportional(false);
                      $objDrawing->setWidth(65);
                      $objDrawing->setHeight(200);
                      $objDrawing->setWorksheet($sheet);
                      $sheet->setHeight(array(1=>45,2=>62,3=>45)); //pas
                      $sheet->mergeCells('A1:A3');
                      //$sheet->cell('A3',function($cell){$cell->setBorder('none','none','thick','none');});
                      $sheet->cell('A4:N4',function($cell){$cell->setBorder('thick','none','none','none');});
                      //HEADER UTAMA//

                      $sheet->getStyle('A5:N5')->getAlignment()->setWrapText(true);
                      $sheet->cell('A5:N5',function ($cell){
                        $cell->setBackground('#a9abb1');
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(12);
                      });
                      $sheet->cell('C:N',function ($cell){ $cell->setAlignment('center'); });

                      //styling isi data
                      $sheet->setBorder('A5:N'.$jumlahbaris, 'thin'); //styling border isi data

                      //data nama header, data akan ditambahkan pada baris array data berikutnya
                      //$sheet->cell('A5:O5',function ($cell) use ($headerdata_3){ $cell->setAlignment('center');$cell->setValignment('center');$cell->setValue($headerdata_3[0]);$cell->setFontWeight('bold');});
                      $sheet->cell('A5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[0]);});
                      $sheet->cell('B5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[1]);});
                      $sheet->cell('C5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[2]);});
                      $sheet->cell('D5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[3]);});
                      $sheet->cell('E5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[4]);});
                      $sheet->cell('F5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[5]);});
                      $sheet->cell('G5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[6]);});
                      $sheet->cell('H5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[7]);});
                      $sheet->cell('I5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[8]);});
                      $sheet->cell('J5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[9]);});
                      $sheet->cell('K5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[10]);});
                      $sheet->cell('L5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[11]);});
                      $sheet->cell('M5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[12]);});
                      $sheet->cell('N5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[13]);});
                      //$sheet->cell('Q5',function ($cell) use ($headerdata_3){ $cell->setAlignment('center');$cell->setValignment('center');$cell->setValue($headerdata_3[1]);$cell->setFontWeight('bold');});
                      $sheet->fromArray($atts, null, 'A6', true, false); //data jumlah absensi. data header ditambahkan kesini
                      //dd($sheet->fromArray()); //for check data from sheet array

                      //paremeter ke 4 untuk mengubah nilai 0 ditulis sebagai 0, bukan sebagai null data
                      //array use ->fromArray($source, $nullValue, $startCell, $strictNullComparison, $headingGeneration) inside the sheet closure.
                  });
          })->download('xls');
    }

    public function konversiHari($atts, $i){
      $atts[$i]->hari = date('w', strtotime($atts[$i]->hari));
      switch ($atts[$i]->hari) {
        case 0: $atts[$i]->hari = 'MINGGU'; break;
        case 1: $atts[$i]->hari = 'SENIN'; break;
        case 2: $atts[$i]->hari = 'SELASA'; break;
        case 3: $atts[$i]->hari = 'RABU'; break;
        case 4: $atts[$i]->hari = 'KAMIS'; break;
        case 5: $atts[$i]->hari = "JUM'AT"; break;
        case 6: $atts[$i]->hari = 'SABTU'; break;
      }
    }

    public function konversiDataQuery($atts){  //fungsi untuk mengkonversi data beberapa kolom sebelum di konversi ke excel
      for($i=0;$i<count($atts);$i++)
        {
          $atts[$i]->tanggal_att= date('d-m-Y', strtotime($atts[$i]->hari));
          if($atts[$i]->apel=='0') { $atts[$i]->apel = 'TIDAK APEL';  } else { $atts[$i]->apel = 'APEL';  }

          $this->konversiHari($atts, $i);
        }
    }


    public function pdfharian(){

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select(
                  'pegawais.nip','pegawais.nama','atts.tanggal_att as hari','atts.tanggal_att', 'atts.apel',
                  'atts.terlambat', 'atts.jam_masuk', 'atts.keluaristirahat', 'atts.masukistirahat', 'atts.jam_keluar','atts.akumulasi_sehari',
                  'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat'
                )
        ->orderBy('atts.tanggal_att','desc')
        ->limit(5000)
        ->get();

        $this->konversiDataQuery($atts);

        $jumlahbaris=count($atts)+5; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
        $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
        $periodeH='(HARIAN)';
        $namaFile = 'lh_'.$instansi; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelHari($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }

    public function pdfharianfull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);
        //untuk membedakan input dari yang perhari dan data perbulan
        if( empty($tanggal[0]) ) {
          $tanggal_p = '';
          $opsiWhere1 = 'atts.tanggal_att';
          $opsiWhere2 = 'LIKE';
          $opsiWhere3 = '';
        }
        else if( empty($tanggal[2]) ) {
          $tanggal_p = $tanggal[1].'-'.$tanggal[0];
          $opsiWhere1 = 'atts.tanggal_att';
          $opsiWhere2 = 'LIKE';
          $opsiWhere3 = $tanggal[0].'-'.$tanggal[1].'%';
        }
        else {
          $tanggal_p = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
          $opsiWhere1 = 'atts.tanggal_att';
          $opsiWhere2 = '=';
          $opsiWhere3 = $tanggal[0].'-'.$tanggal[1].'-'.$tanggal[2];
         }

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where($opsiWhere1, $opsiWhere2, $opsiWhere3)
        ->where('pegawais.nip','=',$id2)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select(
                  'pegawais.nip','pegawais.nama','atts.tanggal_att as hari','atts.tanggal_att', 'atts.apel',
                  'atts.terlambat', 'atts.jam_masuk', 'atts.keluaristirahat', 'atts.masukistirahat', 'atts.jam_keluar','atts.akumulasi_sehari',
                  'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat'
                )
        ->orderBy('atts.tanggal_att','desc')
        ->limit(5000)
        ->get();

        $this->konversiDataQuery($atts);

        $jumlahbaris=count($atts)+5; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
        $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
        $periodeH=$tanggal_p.' (HARIAN)';
        $namaFile = 'lh_'.$instansi.'_'.$id; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelHari($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }

    public function pdfhariantanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
        // ->whereYear('atts.tanggal_att','=',$tanggal[1])
        ->where('atts.tanggal_att','=',$id)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select(
                  'pegawais.nip','pegawais.nama','atts.tanggal_att as hari','atts.tanggal_att', 'atts.apel',
                  'atts.terlambat', 'atts.jam_masuk', 'atts.keluaristirahat', 'atts.masukistirahat', 'atts.jam_keluar','atts.akumulasi_sehari',
                  'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat'
                )
        ->orderBy('atts.tanggal_att','desc')
        ->limit(5000)
        ->get();

        $this->konversiDataQuery($atts);

        $jumlahbaris=count($atts)+5; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
        $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
        $periodeH=$tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0].' (HARIAN)';
        $namaFile = 'lh_'.$instansi.'_'.$id; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelHari($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);

    }

    public function pdfhariannip($id){

        $id=decrypt($id);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->leftJoin('jenisabsens as keteranganmasuk', 'keteranganmasuk.id','=','atts.keteranganmasuk_id')
        ->leftJoin('jenisabsens as keterangankeluar', 'keterangankeluar.id','=','atts.keterangankeluar_id')
        ->where('pegawais.nip','=',$id)
        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
        ->select(
                  'pegawais.nip','pegawais.nama','atts.tanggal_att as hari','atts.tanggal_att', 'atts.apel',
                  'atts.terlambat', 'atts.jam_masuk', 'atts.keluaristirahat', 'atts.masukistirahat', 'atts.jam_keluar','atts.akumulasi_sehari',
                  'jenisabsens.jenis_absen','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat'
                )
        ->orderBy('atts.tanggal_att','desc')
        ->get();

        $this->konversiDataQuery($atts);

        $jumlahbaris=count($atts)+5; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
        $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
        $periodeH='(HARIAN)';
        $namaFile = 'lh_'.$instansi.'_'.$id; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelHari($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);

    }

    public function indexNipBulan($id,$id2){ //detail harian pegawai dalam sebulan

        //$tanggal=date('Y-m',strtotime(decrypt($id)));
        $tanggal=decrypt($id);
        $tanggal=explode('-',$tanggal);
        if(empty($tanggal[0])) { $tanggal=''; }
        else { $tanggal=$tanggal[1].'-'.$tanggal[0]; }

        $nip=decrypt($id2);

        $atts=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
        ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
        ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
        ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
        ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
        ->where('atts.tanggal_att','LIKE', $tanggal.'%')
        ->where('pegawais.nip','=',$nip)
        ->select('atts.*','atts.tanggal_att as hari','jadwalkerjas.jenis_jadwal','jadwalkerjas.sifat','instansismasuk.namaInstansi as namainstansimasuk',
            'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
        ->orderBy('atts.tanggal_att','asc')
        ->paginate(30);

        for($i=0;$i<count($atts);$i++) { $this->konversiHari($atts, $i); }

        return view('laporan.laporanharian',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // END. LAPORAN HARIAN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LAPORAN BULANAN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function pdfbulanindex(Request $request){

      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

                $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                        ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                        ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                        ->select(
                                'pegawais.id',
                                'pegawais.nip',
                                'pegawais.nama',
                                DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                                DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                                DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                                DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                                DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                                DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                                DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                                DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                                DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                                DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                                DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                                DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                                DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                                DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                                DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                                DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                                DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                                'instansis.namaInstansi',
                                'pegawais.instansi_id'
                        )
                        ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                        ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                        ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                        ->where('pegawais.nip','=',$request->nip)
                        ->whereYear('atts.tanggal_att','=',$tanggal[1])
                        ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                        ->paginate(50);

        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
                $tanggal=explode('-',$request->tanggal);

                $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.id',
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        'instansis.namaInstansi',
                        'pegawais.instansi_id'
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);

                // $atts=finalrekapbulanan::leftJoin('pegawais','finalrekapbulanans.pegawai_id','=','pegawais.id')
                // ->leftJoin('instansis','instansis.id','=','finalrekapbulanans.pegawai_id')
                // ->whereMonth('finalrekapbulanans.periode','=',$tanggal[0])
                // ->whereYear('finalrekapbulanans.periode','=',$tanggal[1])
                // ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                // ->select('finalrekapbulanans.*','instansis.namaInstansi','pegawais.nip','pegawais.nama')
                // ->orderBy('finalrekapbulanans.periode','desc')
                // ->paginate(30);
        $request->nip=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);
        // dd($request->nip);
        if ($request->tanggal=="")
        {
                $tanggal=date('Y-m');
                $tanggal=explode('-',$tanggal);
                $tanggal=null;
        }

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.id',
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        'instansis.namaInstansi',
                        'pegawais.instansi_id'
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->where('pegawais.nip','=',$request->nip)
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);

        // dd($atts);

        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=date('Y-m');
        $tanggal=explode('-',$tanggal);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        'instansis.namaInstansi',
                        'pegawais.instansi_id'
                )
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);

        $nip=null;
        $tanggal=null;
        return view('laporan.laporanbulan',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);

      }
    }

    public function pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris){
      //for not repeat function in another function
      //excel for month report
      //data header
      $headerdata_1 = array('NIP','Nama','Periode','Hari Kerja','Hadir','Apel', 'Akumulasi Jam Kerja');
      $headerdata_2 = array('Tanpa Kabar','Izin','Izin Terlambat','Ijin Pulang Cepat',
                          'Sakit','Cuti','Tugas Luar','Tugas Belajar','Ijin Kepentingan Lain',
                          'Terlambat','Akumulasi Jam Terlambat','Pulang Cepat');
      $headerdata_3 = array('Tidak Masuk Kerja','Melanggar Ketentuan Jam Kerja');

      return Excel::create($namaFile,function($excel) use ($instansi, $periodeH, $headerdata_1, $headerdata_2, $headerdata_3, $atts, $jumlahbaris ){ // create('namaFilenya',function($excel) use ($atts)
              //dd(); cek ouput
              $excel->sheet('Laporan',function($sheet) use ($instansi, $periodeH, $headerdata_1, $headerdata_2, $headerdata_3, $atts, $jumlahbaris){
                      //dd($atts);
                      $sheet->protect('b1k1n4pl1k451');
                      $sheet->setPrintArea('A1:S'.$jumlahbaris); //set printing area
                      $sheet->setOrientation('landscape');
                      $sheet->setFitToPage(1);
                      $sheet->setFitToWidth(1);  // fit allcolumn in one page
                      $sheet->setFitToHeight(0);
                      $sheet->setRowsToRepeatAtTop(1,5);
                      $sheet->setFreeze('A7');

                      $sheet->setAutoSize(array('A','B','C'));
                      $sheet->setWidth(array( 'D' => 8.43, 'F' => 8.43, 'G' => 14.29, 'H' => 8.43, 'J' => 11, 'K' => 8.43, 'N' => 8.43, 'O' => 8.43, 'P' => 14.29, 'Q' => 8.43, 'R' => 13.57, 'S' => 14.57 ));
                      $sheet->getStyle('D2:S2')->getAlignment()->setWrapText(true);
                      $sheet->getStyle('D6:S6')->getAlignment()->setWrapText(true); //wrap text nama instansi

                      //HEADER UTAMA//
                      $sheet->mergeCells('B1:S1');
                      $sheet->cell('B1',function ($cell){
                        $cell->setAlignment('center');
                        $cell->setValue('REKAPITULASI DAFTAR HADIR PEGAWAI NEGERI SIPIL');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(25);
                      });

                      $sheet->cell('B2',function ($cell) { $cell->setAlignment('left'); $cell->setValignment('center'); $cell->setValue('UNIT'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->cell('C2',function ($cell) { $cell->setAlignment('left'); $cell->setValignment('center'); $cell->setValue(':'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->mergeCells('D2:S2');
                      $sheet->cell('D2',function ($cell) use ($instansi){
                        $cell->setAlignment('left');
                        $cell->setValignment('center');
                        $cell->setValue($instansi.' PROVINSI KALIMANTAN SELATAN');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(20);
                      });

                      $sheet->cell('B3',function ($cell) { $cell->setAlignment('left');$cell->setValignment('top'); $cell->setValue('PERIODE'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->cell('C3',function ($cell) { $cell->setAlignment('left');$cell->setValignment('top'); $cell->setValue(':'); $cell->setFontWeight('bold'); $cell->setFontSize(20); });
                      $sheet->mergeCells('D3:S3');
                      $sheet->cell('D3',function ($cell) use ($periodeH){
                        $cell->setAlignment('left');
                        $cell->setValignment('top');
                        $cell->setValue($periodeH);
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(22);
                      });

                      $objDrawing = new PHPExcel_Worksheet_Drawing;
                      $objDrawing->setPath(public_path('img/logoProvKalSellg.png')); //your image path
                      $objDrawing->setCoordinates('A1');
                      $objDrawing->setResizeProportional(false);
                      $objDrawing->setWidth(65);
                      $objDrawing->setHeight(200);
                      $objDrawing->setWorksheet($sheet);
                      $sheet->setHeight(array(1=>45,2=>62,3=>45)); //pas
                      $sheet->mergeCells('A1:A3');
                      //$sheet->cell('A3',function($cell){$cell->setBorder('none','none','thick','none');});
                      $sheet->cell('A4:S4',function($cell){$cell->setBorder('thick','none','none','none');});
                      //HEADER UTAMA//

                      $sheet->getStyle('A5:S5')->getAlignment()->setWrapText(true);
                      $sheet->cell('A5:S6',function ($cell){
                        $cell->setBackground('#a9abb1');
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(12);
                      });
                      $sheet->cell('C',function ($cell){ $cell->setAlignment('center'); });
                      $sheet->cell('G',function ($cell){ $cell->setAlignment('center'); });
                      $sheet->cell('R',function ($cell){ $cell->setAlignment('center'); });
                      //Merge for header data
                      $sheet->setMergeColumn(array(
                          'columns' => array('A','B','C','D','E','F','G'),
                          'rows' => array(
                              array(5,6)
                          )
                      ));
                      $sheet->mergeCells('H5:P5');
                      $sheet->mergeCells('Q5:S5');
                      //styling isi data
                      $sheet->setBorder('A5:S'.$jumlahbaris, 'thin'); //styling border isi data

                      //data nama header, data akan ditambahkan pada baris array data berikutnya
                      $sheet->cell('A5:S6',function ($cell) use ($headerdata_3){ $cell->setAlignment('center');$cell->setValignment('center');$cell->setValue($headerdata_3[0]);$cell->setFontWeight('bold');});
                      $sheet->cell('A5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[0]);});
                      $sheet->cell('B5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[1]);});
                      $sheet->cell('C5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[2]);});
                      $sheet->cell('D5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[3]);});
                      $sheet->cell('E5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[4]);});
                      $sheet->cell('F5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[5]);});
                      $sheet->cell('G5',function ($cell) use ($headerdata_1){ $cell->setValue($headerdata_1[6]);});
                      $sheet->cell('H5',function ($cell) use ($headerdata_3){ $cell->setValue($headerdata_3[0]);});
                      $sheet->cell('Q5',function ($cell) use ($headerdata_3){ $cell->setValue($headerdata_3[1]);});
                      $sheet->cell('H6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[0]);});
                      $sheet->cell('I6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[1]);});
                      $sheet->cell('J6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[2]);});
                      $sheet->cell('K6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[3]);});
                      $sheet->cell('L6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[4]);});
                      $sheet->cell('M6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[5]);});
                      $sheet->cell('N6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[6]);});
                      $sheet->cell('O6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[7]);});
                      $sheet->cell('P6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[8]);});
                      $sheet->cell('Q6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[9]);});
                      $sheet->cell('R6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[10]);});
                      $sheet->cell('S6',function ($cell) use ($headerdata_2){ $cell->setValue($headerdata_2[11]);});
                      //$sheet->cell('Q5',function ($cell) use ($headerdata_3){ $cell->setAlignment('center');$cell->setValignment('center');$cell->setValue($headerdata_3[1]);$cell->setFontWeight('bold');});
                      $sheet->fromArray($atts, null, 'A7', true, false); //data jumlah absensi. data header ditambahkan kesini
                      //dd($sheet->fromArray()); //for check data from sheet array

                      //paremeter ke 4 untuk mengubah nilai 0 ditulis sebagai 0, bukan sebagai null data
                      //array use ->fromArray($source, $nullValue, $startCell, $strictNullComparison, $headingGeneration) inside the sheet closure.

                  });
          })->download('xls');
    }
    public function pdfbulan(){

        $tanggal=date('Y-m');
        $tanggal=explode('-',$tanggal);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(*) hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1" ,1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();


        $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
        $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
        $periodeH=$tanggal[0].'-'.$tanggal[1];
        $namaFile = 'lb_'.$instansi.'_'.$tanggal[0].'-'.$tanggal[1]; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }

    public function pdfbulanfull($id,$id2){ //bagian yang milih Periode per pegawai

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(*) hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1" ,1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.nip','=',$id2)
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();

                $jumlahbaris=count($atts)+5; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
                $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
                $periodeH=$tanggal[0].'-'.$tanggal[1];
                $namaFile = 'lb_'.$instansi.'_'.$tanggal[0].'-'.$tanggal[1]; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }


    public function pdfbulantanggal($id){ //memilih periode seluruh pegawai instansi
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(*) hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1" ,1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')

                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();

                $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
                $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
                $periodeH=$tanggal[0].'-'.$tanggal[1];
                $namaFile = 'lb_'.$instansi.'_'.$tanggal[0].'-'.$tanggal[1].'_'.$id; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }

    public function pdfbulannip($id){ //memilih laporan bulanan berdasarkan nip

        $id=decrypt($id);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(
                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(*) hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1" ,1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->orderBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),'DESC')
                ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM atts.tanggal_att)'),DB::raw('pegawais.id'))
                ->where('pegawais.nip','=',$id)
                // ->whereMonth('atts.tanggal_att','=',$tanggal[0])
                // ->whereYear('atts.tanggal_att','=',$tanggal[1])
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();

                $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
                $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
                $periodeH=$tanggal[0].'-'.$tanggal[1];
                $namaFile = 'lb_'.$instansi.'_'.$id; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // END. LAPORAN BULANAN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LAPORAN MINGGUAN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function pdfmingguanindex(Request $request){
        // dd("asd");
      if (isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$request->tanggal)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);

        // dd("dsa");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);

      }
      elseif (!isset($request->nip) && isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$request->tanggal)
                ->paginate(50);


        // dd("asd");
        $request->nip=null;
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (isset($request->nip) && !isset($request->tanggal))
      {
        $tanggal=explode('-',$request->tanggal);
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);

        $tanggal=null;
        // dd("as");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$request->nip,'tanggal'=>$request->tanggal]);
      }
      elseif (!isset($request->nip) && !isset($request->tanggal))
      {
        // dd("asd");
        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%d-%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apelbulanan'),
                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.keteranganmasuk_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat'),
                        DB::raw('count(if (atts.keterangankeluar_id = "12",1,null)) as ijinpulangcepat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy('atts.tanggal_att','DESC')
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->paginate(50);
        $nip=null;
        $tanggal=null;
        // dd("asdvd");
        return view('laporan.laporanminggu',['atts'=>$atts,'nip'=>$nip,'tanggal'=>$tanggal]);
      }
    }

    public function pdfminggu(){

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->get();

        //laporan menggunakan format yang sama dengan laporan bulanan
        $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
        $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
        $periodeH='(MINGGUAN)';
        $namaFile = 'lm_'.$instansi; // nama file ketika didownload
        // ini_set('memory_limit', '30MB');
        set_time_limit(600);
        $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }

    public function pdfminggufull($id,$id2){

        $id=decrypt($id);
        $id2=decrypt($id2);

        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$id)
                ->where('pegawais.nip','=',$id2)
                ->get();

                $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
                $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
                $periodeH=$id.' (MINGGUAN)';
                $namaFile = 'lm_'.$instansi.'_'.$id.'_'.$id2; // nama file ketika didownload
                // ini_set('memory_limit', '30MB');
                set_time_limit(600);
                $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }


    public function pdfminggutanggal($id){
        $id=decrypt($id);
        $tanggal=explode('-',$id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('atts.tanggal_att','=',$id)
                ->get();

                $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
                $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
                $periodeH=$id.' (MINGGUAN)';
                $namaFile = 'lm_'.$instansi.'_'.$id; // nama file ketika didownload
                // ini_set('memory_limit', '30MB');
                set_time_limit(600);
                $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);
    }

    public function pdfminggunip($id){

        $id=decrypt($id);

        $atts=pegawai::leftJoin('atts','atts.pegawai_id','=','pegawais.id')
                ->leftJoin('jadwalkerjas','atts.jadwalkerja_id','=','jadwalkerjas.id')
                ->leftJoin('instansis','pegawais.instansi_id','=','instansis.id')
                ->select(

                        'pegawais.nip',
                        'pegawais.nama',
                        DB::raw('DATE_FORMAT( tanggal_att, "%m-%Y" ) as periode'),
                        DB::raw('count(if(atts.jenisabsen_id!="9" && atts.jenisabsen_id != "11" && atts.jenisabsen_id!="13",1,null)) as hari_kerja'),
                        DB::raw('count(if (atts.jenisabsen_id = "1" && atts.jam_keluar is not null,1,null)) as hadir'),
                        DB::raw('count(if (atts.apel = "1",1,null)) as apel_bulanan'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.akumulasi_sehari))) as total_akumulasi'),

                        DB::raw('count(if (atts.jenisabsen_id = "2" || (atts.jam_keluar is null && atts.jenisabsen_id="1"),1,null)) as tanpa_kabar'),
                        DB::raw('count(if (atts.jenisabsen_id = "3",1,null)) as ijin'),
                        DB::raw('count(if (atts.jenisabsen_id = "10",1,null)) as ijinterlambat'),
                        DB::raw('count(if (atts.jenisabsen_id = "12",1,null)) as ijinpulangcepat'),
                      //  DB::raw('count(if ((atts.apel = "0" && jadwalkerjas.sifat="FD") || ((atts.apel = "0" && jadwalkerjas.sifat="TWA")),1,null)) as tidakapelwajibapel'),
                        DB::raw('count(if (atts.jenisabsen_id = "5",1,null)) as sakit'),
                        DB::raw('count(if (atts.jenisabsen_id = "4",1,null)) as cuti'),
                        DB::raw('count(if (atts.jenisabsen_id = "7",1,null)) as tugas_luar'),
                        DB::raw('count(if (atts.jenisabsen_id = "6",1,null)) as tugas_belajar'),
                        DB::raw('count(if (atts.jenisabsen_id = "8",1,null)) as rapatundangan'),

                        DB::raw('count(if (atts.terlambat != "00:00:00",1,null)) as terlambat'),
                        DB::raw('SEC_TO_TIME(SUM(time_to_sec(atts.terlambat))) as total_terlambat'),
                        DB::raw('count(if (atts.jenisabsen_id < jadwalkerjas.jam_keluarjadwal && atts.jam_masuk is not null && jam_keluar is null,1,null)) as pulang_cepat')
                )
                ->groupBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))'),DB::raw('pegawais.id'))
                ->orderBy(DB::raw('FROM_DAYS(TO_DAYS(atts.tanggal_att) -MOD(TO_DAYS(atts.tanggal_att) -1, 7))','ASC'))
                ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$id)
                ->get();

                $jumlahbaris=count($atts)+6; //menghitung jumlah baris untuk digunakan sebagai excell coordinate
                $instansi=Auth::user()->instansi->namaInstansi; //digunakan sebagai header utama
                $periodeH=' (MINGGUAN)';
                $namaFile = 'lm_'.$instansi.'_'.$id; // nama file ketika didownload
                // ini_set('memory_limit', '30MB');
                set_time_limit(600);
                $this->pdfExcelBulan($namaFile, $instansi, $periodeH, $atts, $jumlahbaris);

    }

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// END. LAPORAN MINGGUAN
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
