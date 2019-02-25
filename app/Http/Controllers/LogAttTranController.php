<?php

namespace App\Http\Controllers;
use App\macaddresse;
use App\log_att_tran;
use Illuminate\Http\Request;

class LogAttTranController extends Controller
{
    //
    public function get(Request $request)
    {
      $datamac=macaddresse::where('macaddress','=',strtoupper($request->json('macaddress')))->first();

      if ($datamac==null)
      {
        return "Failed";
      }
      else
      {
        $data=log_att_tran::where('macaddress_id','=',$datamac->id)
                            ->where('instansi_id','=',$request->json('instansi_id'))
                            ->where('expire','=',false)
                            ->count();
        return $data;
      }
    }

}
