<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Sendmessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $reciever;
    public $message;
    public $sender;
    public $s_key;
    public $auth_id;
    public $user_id;



    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reciever,$message,$sender,$s_key,$auth_id,$user_id)
    {

             $this->reciever = $reciever;
             $this->message = $message;
             $this->sender = $sender;
             $this->s_key = $s_key;
             $this->auth_id = $auth_id;
             $this->user_id = $user_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pushpa');
    }
    public function broadcastAs(){
        return 'chat';


    }
}
