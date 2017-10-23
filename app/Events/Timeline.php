<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;
use App\pegawai;

class Timeline  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $tanggal;
    public $jam;
    public $instansi_id;
    public $status;
    public $namaPegawai;
    public $namaInstansi;
    public $instansiPegawai;
    public $statusmasuk;
    public $class;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id,$tanggal,$jam,$instansi_id,$status,$namaPegawai,$instansiPegawai,$namaInstansi,$statusmasuk,$class)
    {
        $this->instansiPegawai=$instansiPegawai;
        $this->instansi_id=$instansi_id;
        $this->namaInstansi=$namaInstansi;
        $this->jam=$jam;
        $this->tanggal=$tanggal;
        $this->namaPegawai=$namaPegawai;
        $this->statusmasuk=$statusmasuk;
        $this->class=$class;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test-channel.'.$this->instansi_id);
    }
}
