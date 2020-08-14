<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Message;
use Auth;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $roomId;
    public $user;
    public $name;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->user = Auth::user();
        $this->roomId = $message->room_id;
        $this->name = Auth::user()->name;
        //$this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastOn()
    {
        //return new PrivateChannel('chat.'.$this->message->room_id);
        return new PresenceChannel('chat.'.$this->message->room_id);
    }

    public function broadcastWith()
    {
        return [
            'content' => $this->message->content,
            'name'  => $this->name
        ];
    }
}
