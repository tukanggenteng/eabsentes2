<?php

namespace App\Http\Controllers;

use App\att;
use App\cuti;
use App\ijin;
use App\ijinterlambat;
use App\ijinpulangcepat;
use App\masterbulanan;
use App\pegawai;
use App\rapatundangan;
use App\rekapbulanan;
use App\sakit;
use App\tugasbelajar;
use App\tugasluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Facades\Datatables;
use Validator;

class TransferRekapController extends Controller
{
    //
    public function index(){
        return view('rekapabsen.transferrekap');
    }

    public function datagrid (){
        $rekaps=rekapbulanan::leftJoin('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
            ->select(['rekapbulanans.id','rekapbulanans.periode','rekapbulanans.hari_kerja',
                'rekapbulanans.hadir','rekapbulanans.tanpa_kabar','rekapbulanans.ijinterlambat','rekapbulanans.ijin','rekapbulanans.sakit',
                'rekapbulanans.cuti','rekapbulanans.tugas_luar','rekapbulanans.tugas_belajar','rekapbulanans.ijinpulangcepat','rekapbulanans.terlambat',
                'rekapbulanans.rapatundangan','pegawais.nip','pegawais.nama'])
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->where('rekapbulanans.ijin','>','0')
            ->orWhere('rekapbulanans.sakit','>','0')
            ->orWhere('rekapbulanans.cuti','>','0')
            ->orWhere('rekapbulanans.tugas_luar','>','0')
            ->orWhere('rekapbulanans.ijinterlambat','>','0')
            ->orWhere('rekapbulanans.ijinpulangcepat','>','0')
            ->orWhere('rekapbulanans.tugas_belajar','>','0')
            ->orWhere('rekapbulanans.rapatundangan','>','0');

        return Datatables::of($rekaps)
            ->editColumn('ijin',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_ijin"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-ijin="'.$rekaps->ijin.'" data-target="#modal_ijin"><i class="fa fa-download"></i> '.$rekaps->ijin.'</button>';})
            ->editColumn('sakit',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_sakit"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-sakit="'.$rekaps->sakit.'" data-target="#modal_sakit"><i class="fa fa-download"></i> '.$rekaps->sakit.'</button>';})
            ->editColumn('cuti',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_cuti"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-cuti="'.$rekaps->cuti.'" data-target="#modal_cuti"><i class="fa fa-download"></i> '.$rekaps->cuti.'</button>';})
            ->editColumn('tugas_luar',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_tl"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-tl="'.$rekaps->tugas_luar.'" data-target="#modal_tl"><i class="fa fa-download"></i> '.$rekaps->tugas_luar.'</button>';})
            ->editColumn('tugas_belajar',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_tb"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-tb="'.$rekaps->tugas_belajar.'" data-target="#modal_tb"><i class="fa fa-download"></i> '.$rekaps->tugas_belajar.'</button>';})
            ->editColumn('rapatundangan',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_rp"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-rp="'.$rekaps->rapatundangan.'" data-target="#modal_rp"><i class="fa fa-download"></i> '.$rekaps->rapatundangan.'</button>';})
            ->editColumn('ijinterlambat',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_it"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-it="'.$rekaps->ijinterlambat.'" data-target="#modal_it"><i class="fa fa-download"></i> '.$rekaps->ijinterlambat.'</button>';})
            ->editColumn('ijinpulangcepat',function (rekapbulanan $rekaps){
                return '<button type="button" class="btn btn-block btn-primary modal_ipc"  data-toggle="modal" data-pegawaiid="'.encrypt($rekaps->pegawai_id).'" data-nip="'.$rekaps->nip.'" data-nama="'.$rekaps->nama.'" data-id="'.encrypt($rekaps->id).'" data-ipc="'.$rekaps->ijinpulangcepat.'" data-target="#modal_ipc"><i class="fa fa-download"></i> '.$rekaps->ijinpulangcepat.'</button>';})
            ->rawColumns(['ijin','sakit','cuti','tugas_luar','tugas_belajar','rapatundangan','ijinterlambat','ijinpulangcepat'])
            ->make(true);
    }

    public function postijin(Request $request){
        $rules=array(
            'tanggalijin'=>'required',
            'lamaijin'=>'required'
        );
        // return $request->sisalamaijin;

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idijin))->first();
            $updatedata->ijin=$request->sisalamaijin;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('fileijin')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idijin).'+'.$tgl.'.'.$ext;
            // $request->file('fileijin')->storeAs('public/file/ijin',$filename);
            $filename=$request->statusijin;
            $bleave = new ijin();
            $bleave->rekapbulanan_id=decrypt($request->idijin);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamaijin;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggalijin));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function postsakit(Request $request){
        $rules=array(
            'tanggalsakit'=>'required',
            'lamasakit'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idsakit))->first();
            $updatedata->sakit=$request->sisalamasakit;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('filesakit')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idsakit).'+'.$tgl.'.'.$ext;
            // $request->file('filesakit')->storeAs('public/file/sakit',$filename);
            $filename=$request->statussakit;
            $bleave = new sakit();
            $bleave->rekapbulanan_id=decrypt($request->idsakit);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamasakit;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggalsakit));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function postcuti(Request $request){
        $rules=array(
            'tanggalcuti'=>'required',
            'lamacuti'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idcuti))->first();
            $updatedata->cuti=$request->sisalamacuti;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('filecuti')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idcuti).'+'.$tgl.'.'.$ext;
            // $request->file('filecuti')->storeAs('public/file/cuti',$filename);

            $filename=$request->statuscuti;
            $bleave = new cuti();
            $bleave->rekapbulanan_id=decrypt($request->idcuti);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamacuti;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggalcuti));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function posttb(Request $request){
        $rules=array(
            'tanggaltb'=>'required',
            'lamatb'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idtb))->first();
            $updatedata->tugas_belajar=$request->sisalamatb;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('filetb')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idtb).'+'.$tgl.'.'.$ext;
            // $request->file('filetb')->storeAs('public/file/tugasbelajar',$filename);
            $filename=$request->statustb;
            $bleave = new tugasbelajar();
            $bleave->rekapbulanan_id=decrypt($request->idtb);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamatb;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggaltb));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function posttl(Request $request){
        $rules=array(
            'tanggaltl'=>'required',
            'lamatl'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idtl))->first();
            $updatedata->tugas_luar=$request->sisalamatl;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('filetl')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idtl).'+'.$tgl.'.'.$ext;
            // $request->file('filetl')->storeAs('public/file/tugasluar',$filename);

            $filename=$request->statustl;
            $bleave = new tugasluar();
            $bleave->rekapbulanan_id=decrypt($request->idtl);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamatl;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggaltl));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function postrp(Request $request){
        $rules=array(
            'tanggalrp'=>'required',
            'lamarp'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idrp))->first();
            $updatedata->rapatundangan=$request->sisalamarp;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('filerp')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idrp).'+'.$tgl.'.'.$ext;
            // $request->file('filerp')->storeAs('public/file/rapatundangan',$filename);

            $filename=$request->statusrp;
            $bleave = new rapatundangan();
            $bleave->rekapbulanan_id=decrypt($request->idrp);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamarp;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggalrp));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function postit(Request $request){
        $rules=array(
            'tanggalit'=>'required',
            'lamait'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idit))->first();
            $updatedata->ijinterlambat=$request->sisalamait;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('fileit')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idit).'+'.$tgl.'.'.$ext;
            // $request->file('fileit')->storeAs('public/file/ijinterlambat',$filename);

            $filename=$request->statusit;
            $bleave = new ijinterlambat();
            $bleave->rekapbulanan_id=decrypt($request->idit);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamait;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggalit));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function postipc(Request $request){
        $rules=array(
            'tanggalipc'=>'required',
            'lamaipc'=>'required'
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else
        {
            $updatedata=rekapbulanan::where('id','=',decrypt($request->idipc))->first();
            $updatedata->ijinpulangcepat=$request->sisalamaipc;
            $updatedata->save();
            $t=time();
            $tgl=date('Y-m-d-H-i-s',$t);
            // $ext=$request->file('fileit')->getClientOriginalExtension();
            // $filename= Auth::user()->username.'+'.Auth::user()->instansi_id.'+'.decrypt($request->idit).'+'.$tgl.'.'.$ext;
            // $request->file('fileit')->storeAs('public/file/ijinterlambat',$filename);

            $filename=$request->statusipc;
            $bleave = new ijinpulangcepat();
            $bleave->rekapbulanan_id=decrypt($request->idipc);
            $bleave->namafile=$filename;
            $bleave->lama=$request->lamaipc;
            $bleave->instansi_id=Auth::user()->instansi_id;
            $bleave->mulaitanggal=date('Y/m/d',strtotime($request->tanggalipc));
            $bleave->save();
            return response()->json($updatedata);
        }
    }

    public function downloadsuratijin(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
                ->where("rekapbulanans.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('ijins.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('ijins.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
//                ->select('rekapbulanans.periode','pegawais.nip','pegawais.nama','ijins.namafile as namafileijin'
//                    ,'sakits.namafile as namafilesakit','cutis.namafile as namafilecuti','tugasluars.namafile as namafiletugasluar'
//                    ,'tugasbelajars.namafile as namafiletugasbelajar','rapatundangans.namafile as namafilerapatundangan'
//                    ,'ijinterlambats.namafile as namafileijinterlambat')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('ijins.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijins','ijins.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
//            $table=rekapbulanan::all();
        }
//        dd($table);
        return view('rekapabsen.downloadsuratijin',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadijin($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/ijin/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsuratsakit(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('sakits.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('sakits.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
//                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
//                ->select('rekapbulanans.periode','pegawais.nip','pegawais.nama','ijins.namafile as namafileijin'
//                    ,'sakits.namafile as namafilesakit','cutis.namafile as namafilecuti','tugasluars.namafile as namafiletugasluar'
//                    ,'tugasbelajars.namafile as namafiletugasbelajar','rapatundangans.namafile as namafilerapatundangan'
//                    ,'ijinterlambats.namafile as namafileijinterlambat')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('sakits.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('sakits','sakits.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsuratsakit',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadsakit($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/sakit/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsuratcuti(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('cutis.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('cutis.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('cutis.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('cutis','cutis.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsuratcuti',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadcuti($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/cuti/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsurattl(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('tugasluars.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('tugasluars.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('tugasluars.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasluars','tugasluars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsurattugasluar',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadtl($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/tugasluar/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsurattb(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('tugasbelajars.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('pegawais','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('tugasbelajars.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('tugasbelajars.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('tugasbelajars','tugasbelajars.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsurattugasbelajar',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadtb($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/tugasbelajar/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsuratru(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('rapatundangans.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('rapatundangans.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('rapatundangans.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('rapatundangans','rapatundangans.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsuratrapatundangan',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadru($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/rapatundangan/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsuratit(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('ijinterlambats.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('ijinterlambats.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('ijinterlambats.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsuratijinterlambat',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }

    public function downloadit($id){
        $id=decrypt($id);
        $file= public_path(). "/storage/file/ijinterlambat/".$id;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/jpg',
            'Content-Type: application/png',
            'Content-Type: application/jpeg',
        );

        return Response::download($file, $id, $headers);
    }

    public function downloadsuratipc(Request $request){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }


        if (isset($request->nip) && isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->where('ijinterlambats.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip) && isset($request->nama)) {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->where('pegawais.nama','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama) && isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nama','=',$request->nip)
                ->where('ijinterlambats.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        elseif (isset($request->nip)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('pegawais.nip','=',$request->nip)
                ->paginate(50);
        }
        elseif (isset($request->nama)){
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where("pegawais.nama",'=',$request->nama)
                ->paginate(50);
        }
        elseif (isset($request->periode))
        {
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->where('ijinterlambats.mulaitanggal','=',$request->periode)
                ->paginate(50);
        }
        else{
            $table=rekapbulanan::join('pegawais','rekapbulanans.pegawai_id','=','pegawais.id')
                ->join('ijinterlambats','ijinterlambats.rekapbulanan_id','=','rekapbulanans.id')
                ->where("pegawais.instansi_id",'=',Auth::user()->instansi_id)
                ->paginate(50);
        }

        return view('rekapabsen.downloadsuratijinterlambat',['rekaps'=>$table,'inforekap'=>$inforekap,'nip'=>$request->nip,'nama'=>$request->nama,'periode'=>$request->periode]);
    }
}
