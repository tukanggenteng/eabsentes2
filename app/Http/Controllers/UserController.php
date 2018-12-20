<?php

namespace App\Http\Controllers;

use App\instansi;
use App\masterbulanan;
use App\att;
use App\role;
use App\atts_tran;
use App\pegawai;
use Illuminate\Http\Request;
use App\User;
use Symfony\Component\VarDumper\Cloner\Data;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;

class UserController extends Controller
{
    //
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->middleware('rule:admin');
//    }

    public function register(){
        $instansi=instansi::where('namaInstansi','!=','Admin');
        return view('user.userregister',['instansis'=>$instansi]);
    }


    public function index(){
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        $instansi=instansi::where('namaInstansi','!=','Admin')->get();
        $role=role::all();
//        dd($role);
        return view('user.manajuser',['inforekap'=>$inforekap,'instansis'=>$instansi,'roles'=>$role]);
    }

    public function data(){
        $users=User::leftJoin('instansis','users.instansi_id','=','instansis.id')->leftJoin('roles','users.role_id','=','roles.id')->get();
       return Datatables::of($users)
           ->addColumn('action', function ($users) {
               return '<button type="button" class="modal_edit btn btn-success btn-sm" data-toggle="modal" data-username="'.$users->username.'" data-email="'.$users->email.'" data-instansi="'.$users->instansi_id.'" data-role="'.$users->role_id.'" data-nama="'.$users->name.'" data-id="'.$users->id.'" data-target="#modal_edit">Edit</button>
               <button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-username="'.$users->username.'" data-email="'.$users->email.'" data-instansi="'.$users->instansi_id.'" data-role="'.$users->role_id.'" data-nama="'.$users->name.'" data-id="'.$users->id.'" data-target="#modal_delete">Hapus</button>';
           })
           ->make(true);
    }

    public function registerstore(Request $request){

        $this->validate($request, [
            'name'=>'required|min:3',
            'username'=>'required | min:3 | unique:users',
            'email' => 'required | email | unique:users',
            'password'=>'required | min:8',
            'selectrole'=>'required',
            'selectinstansi'=>'required',
        ]);

            $user = new User();
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role_id = $request->selectrole;
            $user->instansi_id = $request->selectinstansi;
            $user->save();
            return redirect('/user/register');
    }

    public function store(Request $request){

        $rules=array(
            'nama'=>'required|min:3',
            'username'=>'required | min:3 | unique:users',
            'email' => 'required | email | unique:users',
            'password'=>'required | min:8',
            'role'=>'required',
            'instansi'=>'required',
        );

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            $user = new User();
            $user->username = $request->username;
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role_id = $request->role;
            $user->instansi_id = $request->instansi;
            $user->save();
            return response()->json($user);
        }
    }

    public function edit (Request $request){

        if ($request->password2==""){
            $rules=array(
                'nama2'=>'required|min:3',
                'role2'=>'required',
                'instansi2'=>'required',
            );
        }
        else
        {
            $rules=array(
                'nama2'=>'required|min:3',
                'password2'=>'required | min:8',
                'role2'=>'required',
                'instansi2'=>'required',
            );
        }



        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            if ($request->password2==""){
                $updatedata = User::find($request->iduser);
                $updatedata->name = $request->nama2;
                $updatedata->instansi_id = $request->instansi2;
                $updatedata->role_id = $request->role2;
                $updatedata->save();
            }
            else
            {
                $updatedata = User::find($request->iduser);
                $updatedata->name = $request->nama2;
                $updatedata->password = $request->password2;
                $updatedata->instansi_id = $request->instansi2;
                $updatedata->role_id = $request->role2;
                $updatedata->save();
            }

            return response()->json($updatedata);
        }
    }

    public function delete(Request $request){
        $updatedata = User::find($request->deliduser);
        $updatedata->delete();
        return response()->json($updatedata);
    }

    public function indexchange(){
      if (Auth::user()->role->namaRole=="gubernur")
      {
        return view('user.changepasswordtop'); 
      }
      else
      {
        return view('user.changepassword');
      }
      
    }

    public function changepassword(Request $request){

      // dd("asdads");
          $this->validate($request, [
              'password' => 'required',
              'passwordbaru' => 'required|string|min:8',
              'konfirmasipassword' => 'required|string|min:8|same:passwordbaru'
          ]);

        if ($request->email=="")
        {
            if (Hash::check($request->password,Hash::make($request->password))) {
                // dd("berubah");
                request()->user()->fill([
                    'password' => Hash::make(request()->input('passwordbaru'))
                ])->save();

                return redirect()->back()->with('statussucces','Password berhasil di ubah.');
            }
            else{
                return redirect()->back()->with('statuserror','Password Salah');
            }
        }
        else
        {
            if (Hash::check($request->password,Hash::make($request->password))) {
                // dd("berubah");
                request()->user()->fill([
                    'password' => Hash::make(request()->input('passwordbaru')),
                    'email' => request()->input('email')
                ])->save();

                return redirect()->back()->with('statussucces','Password dan Email berhasil di ubah.');
            }
            else{
                return redirect()->back()->with('statuserror','Password Salah');
            }
        }
          

          // return redirect()->route('password.change');

    }

    public function indexpegawai(){

        // dd($totalakumulasi);
        $instansi2=instansi::all();
        $bulan=date("m");
        $tahun=date("Y");
        $tahun2=date("Y");

        $attstrans=atts_tran::join('pegawais','atts_trans.pegawai_id','=','pegawais.id')
            ->join('instansis','atts_trans.lokasi_alat', '=', 'instansis.id')
            ->join('instansis as pegawaiinstansis','pegawais.instansi_id', '=', 'pegawaiinstansis.id')
            ->orderBy('atts_trans.id','atts_trans.tanggal', 'desc')
            ->orderBy('atts_trans.jam','atts_trans.tanggal', 'desc')
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->paginate(7, array('pegawaiinstansis.namaInstansi as instansiPegawai','pegawais.nama','atts_trans.jam','atts_trans.tanggal','atts_trans.status_kedatangan','instansis.namaInstansi'));

            $now=date("Y-m-d");
            $kehadiran=att::leftJoin('pegawais','atts.pegawai_id','=','pegawais.id')
            ->leftJoin('jadwalkerjas','jadwalkerjas.id','=','atts.jadwalkerja_id')
            ->leftJoin('instansis as instansismasuk', 'instansismasuk.id','=','atts.masukinstansi_id')
            ->leftJoin('instansis as instansiskeluar', 'instansiskeluar.id','=','atts.keluarinstansi_id')
            ->leftJoin('jenisabsens','atts.jenisabsen_id','=','jenisabsens.id')
            ->where('pegawais.nip','=',Auth::user()->username)
            ->where('pegawais.instansi_id','=',Auth::user()->instansi_id)
            ->where('atts.tanggal_att','=',$now)
            ->select('atts.*','jadwalkerjas.jenis_jadwal','instansismasuk.namaInstansi as namainstansimasuk',
                'instansiskeluar.namaInstansi as namainstansikeluar','jenisabsens.jenis_absen','pegawais.nip','pegawais.nama')
            ->orderBy('pegawais.nama','desc')
            ->paginate(30);

            $pegawai=pegawai::where('nip','=',Auth::user()->username)
              ->count();
            if ($pegawai>0){

              $pegawais=pegawai::where('nip','=',Auth::user()->username)
                ->first();
                $nip=$pegawais->nip;
                $nama=$pegawais->nama;

                $tidakhadir = masterbulanan::whereMonth('periode', '=', $bulan)
                    ->whereYear('periode', '=', $tahun)
                    ->where('pegawai_id','=',$pegawais->id)
                    ->avg('persentase_tidakhadir');

                    $apel = masterbulanan::whereMonth('periode', '=', $bulan)
                        ->whereYear('periode', '=', $tahun)
                        ->where('pegawai_id','=',$pegawais->id)
                        ->avg('persentase_apel');
                $totalakumulasi = masterbulanan::
                whereMonth('periode','=',$bulan)
                ->whereYear('periode','=',$tahun)
                ->where('pegawai_id','=',$pegawais->id)
                ->select(DB::raw('SEC_TO_TIME( SUM(time_to_sec(total_akumulasi))) as total'))
                ->first();
                $total=$totalakumulasi->total;
                return view('user.pegawaiuser',['kehadirans'=>$kehadiran,'instansis'=>$instansi2,'statuscari'=>null,'nip'=>$nip,'persentaseapel'=>round($apel,2),'tahun'=>$tahun2,'nama'=>$nama,'persentasehadir'=>round($tidakhadir,2),'totalakumulasi'=>$total],compact('attstrans'));
            }

    }
}
