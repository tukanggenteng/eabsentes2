<?php

namespace App\Http\Controllers;

use App\instansi;
use App\role;
use Illuminate\Http\Request;
use App\User;
use Symfony\Component\VarDumper\Cloner\Data;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
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
        $instansi=instansi::all();
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
        $instansi=instansi::all();
        $role=role::all();
//        dd($role);
        return view('user.manajuser',['inforekap'=>$inforekap,'instansis'=>$instansi,'roles'=>$role]);
    }

    public function data(){
        $users=User::with('instansi')->with('role')->get();
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
            return redirect('/register');
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
}
