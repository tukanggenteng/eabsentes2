<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username','instansi_id','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function chat(){
        return $this->belongsTo(chat::class);
    }

    public function role(){
        return $this->belongsTo(role::class);

    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function punyaRule($namaRole){
        $modaluser=$this->role->namaRole;
        // dd($this->role->namaRole);
        // dd(Auth::user()->role->namaRole."tes".$this->role->namaRole);
        //
        // if (Auth::user()->role->namaRole==$this->role->namaRole)
        // {
        //   // dd("asd")
        //   return true;
        // }else {
        //   return false;
        // }

        if (count($namaRole)== 1)
        {
            if ($this->role->namaRole == $namaRole[0])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif (count($namaRole)== 2)
        {
            if ($this->role->namaRole == $namaRole[0]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[1]) {
                return true;
            }  else {
                return false;
            }
        }
        elseif (count($namaRole)== 3)
        {
            if ($this->role->namaRole == $namaRole[0]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[1]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[2]) {
                return true;
            } else {
                return false;
            }
        }
        elseif (count($namaRole)== 4)
        {
            if ($this->role->namaRole == $namaRole[0]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[1]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[2]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[3]) {
                return true;
            } else {
                return false;
            }
        }
        elseif (count($namaRole)== 5)
        {
            if ($this->role->namaRole == $namaRole[0]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[1]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[2]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[3]) {
                return true;
            } elseif ($this->role->namaRole == $namaRole[4]) {
                return true;
            } else {
                return false;
            }
        }
    }
}
