<?php

namespace App\Http\Controllers;

use App\fingerpegawai;
use App\pegawai;
use App\dokter;
use App\perawatruangan;
use App\ruangan;
use App\ruanganuser;
use App\instansi;
use App\atts_tran;
use App\role;
use App\att;
use App\User;
use App\hapusfingerpegawai;
use App\adminpegawai;
use App\rulejadwalpegawai;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class UserRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  role nya belum ditentukan

    public function registerstore(Request $request){

        $rules=array(
            'nama'=>'required|min:3',
            'username'=>'required | min:3 | unique:users',
            'email' => 'required | email | unique:users',
            'password'=>'required | min:8',
            'ruangan'=>'required'
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
            $user->role_id = "7";
            $user->instansi_id = Auth::user()->instansi_id;
            // $user->save();
            if ($user->save()){
                $userdata=User::where('username','=',$request->username)
                        ->first();

                $request->ruangan=decrypt($request->ruangan);

                $userruangan= new ruanganuser;
                $userruangan->user_id=$userdata->id;
                $userruangan->ruangan_id=$request->ruangan;
                $userruangan->instansi_id = Auth::user()->instansi_id;
                $userruangan->save();

                return response()->json("success");
            }
            else{
                return response()->json("failed");
            }
            // return redirect('/user/register');

            
        }
    }
     

    public function index()
    {
        //
        // dd("asd");
        if ($this->notifrekap()=="")
        {

            $inforekap="";
        }
        else
        {
            $inforekap=$this->notifrekap();
        }
        // $instansi=instansi::where('namaInstansi','!=','Admin')->get();
        $ruangans=ruangan::where('instansi_id','=',Auth::user()->instansi_id)->get();
    //    dd($ruangans);
        return view('user.khusus.manajemenuserkhusus',['ruangans'=>$ruangans]);
    }

    public function data(){
        $users=User::leftJoin('ruanganusers','users.id','=','ruanganusers.user_id')
                ->leftJoin('ruangans','ruanganusers.ruangan_id','ruangans.id')
                ->where('users.instansi_id','=',Auth::user()->instansi_id)
                ->where('users.role_id','=','7')
                ->select('users.*','ruangans.nama_ruangan')
                ->get();
       return Datatables::of($users)
           ->addColumn('action', function ($users) {
               return '<button type="button" class="modal_edit btn btn-success btn-sm" data-toggle="modal" data-username="'.$users->username.'" data-email="'.$users->email.'" data-instansi="'.$users->instansi_id.'" data-role="'.$users->role_id.'" data-nama="'.$users->name.'" data-id="'.encrypt($users->id).'" data-target="#modal_edit">Edit</button>
               <button type="button" class="modal_delete btn btn-danger btn-sm" data-toggle="modal" data-username="'.$users->username.'" data-email="'.$users->email.'" data-instansi="'.$users->instansi_id.'" data-role="'.$users->role_id.'" data-nama="'.$users->name.'" data-id="'.encrypt($users->id).'" data-target="#modal_delete">Hapus</button>';
           })
           ->make(true);
    }
    
    public function edit (Request $request){

        dd($request);

        if ($request->password2==""){
            $rules=array(
                'nama2'=>'required|min:3',
                // 'role2'=>'required',
                // 'instansi2'=>'required',
                'ruangan2'=>'required',
            );
        }
        else
        {
            $rules=array(
                'nama2'=>'required|min:3',
                'password2'=>'required | min:8',
                // 'role2'=>'required',
                // 'instansi2'=>'required',
                'ruangan2'=>'required',
            );
        }
    

        $request->deliduser=decrypt($request->deliduser);

        // $request->ruangan2=decrypt($request->ruangan2);

        $validator=Validator::make(Input::all(),$rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        else {
            if ($request->password2==""){
                $updatedata = User::find($request->iduser);
                $updatedata->name = $request->nama2;
                $updatedata->instansi_id = Auth::user()->instansi_id;
                $updatedata->role_id = "7";

                if ($updatedata->save()){
                    
                    $userdata=User::where('id','=',$request->deliduser)
                        ->first();

                    $userruangan=ruanganuser::where('user_id','=',$request->deliduser)->first();
                    $userruangan->user_id=$request->deliduser;
                    $userruangan->ruangan_id=$request->ruangan2;
                    $userruangan->save();
    
                    return response()->json("success");
                }
                else{
                    return response()->json("failed");
                }
                
            }
            else
            {
                $updatedata = User::find($request->iduser);
                $updatedata->name = $request->nama2;
                $updatedata->password = bcrypt($request->password2);
                $updatedata->instansi_id = Auth::user()->instansi_id;
                $updatedata->role_id = "7";
                // $updatedata->save();
                if ($updatedata->save()){
                    $userdata=User::where('id','=',$request->iduser)
                        ->first();


                    $userruangan=ruanganuser::where('user_id','=',$request->iduser)->first();
                    $userruangan->user_id=$request->iduser;
                    $userruangan->ruangan_id=$request->ruangan2;
                    $userruangan->save();
    
                    return response()->json("success");
                }
                else{
                    return response()->json("failed");
                }
            }

            // $userdata=User::where('id','=',$request->iduser)
            //             ->first();


            // $userruangan=ruanganuser::where('user_id','=',$request->iduser)->first();
            // $userruangan->user_id=$request->iduser;
            // $userruangan->ruangan_id=$request->ruangan2;
            // $userruangan->save();

            // return response()->json($updatedata);
        }
    }

    public function delete(Request $request){
        $request->deliduser=decrypt($request->deliduser);
        $updatedata = User::where('id','=',$request->deliduser)->first();
        // dd($updatedata);
        // $updatedata->delete();

        if ($updatedata->delete()){
            // $delete=ruanganuser::where('user_id','=',$request->deliduser)->first();
            // $delete->delete();

            return response()->json("success");
        }
        else{
            return response()->json("failed");
        }

        

        // return response()->json($updatedata);
    }
}
