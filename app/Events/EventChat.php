<?php

namespace App\Events;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\BroadcastingShouldBroadcast;

class EventChat extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $message;
    
    public function __construct()
    {
        //
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['message'];
    }
}
