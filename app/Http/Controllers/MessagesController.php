<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use App\Room;
use App\Events\MessageSent;
use Auth;
use View;

class MessagesController extends Controller
{
    public function getRoomId(){
        (int)$room_id = Auth::user()->room_id;
        return $room_id;
    }

    public function chat($user_b_id){
        if($user_b_id == 0){  //default page
            $room_id = 0;
            //get user all rooms
            $user_a_id = Auth::id();
            $rooms_a = Room::where('user_a_id', $user_a_id)->get();
            $rooms_b = Room::where('user_b_id', $user_a_id)->get();
            $arr = [];  
            foreach($rooms_a as $room){
                $arr[] = $room->user_b_id;
            } 
            foreach($rooms_b as $room){
                $arr[] = $room->user_a_id;
            } 
            $users = User::whereIn('id', $arr)->get();
        }else{
            $user_a_id = Auth::id();

            //check if already had room
            $room = Room::where('user_a_id', $user_a_id)
                        ->where('user_b_id', $user_b_id)
                        ->first();

            if($room == null){
                $room = Room::where('user_a_id', $user_b_id)
                        ->where('user_b_id', $user_a_id)
                        ->first();
            }

            //if no, create room
            if($room == null)
            {   
                $room = new Room();
                $room->user_a_id = $user_a_id;
                $room->user_b_id = $user_b_id;
                $room->save();
            }

            $user = Auth::user();
            $user->room_id = $room->id;
            $user->save();

            //get users
            $rooms_a = Room::where('user_a_id', $user_a_id)->get();
            $rooms_b = Room::where('user_b_id', $user_a_id)->get();
            $arr = [];  
            foreach($rooms_a as $room){
                $arr[] = $room->user_b_id;
            } 
            foreach($rooms_b as $room){
                $arr[] = $room->user_a_id;
            } 
            $users = User::whereIn('id', $arr)->get();

            $room_id = Auth::user()->room_id;
        }
        
        return View::make('chat', compact('room_id', 'users'));
    }

    public function getAll(Request $request) {
        //get all messages in the room
        $room_id = $request->get('roomId');
        $messages = Message::where('room_id', $room_id)->get();
        foreach($messages as $message){
            $message_form_id = $message->user_id;
            $user = User::where('id', $message_form_id)->first();
            $message->name = $user->name;
        }
        return $messages;
    }
    
    // Allows us to post new message
    public function post() {
        $message = new Message();
        //content
        $content = request('message');
        $message->content = $content;
        //sent_from
        $message->user_id = Auth::id();
        //room_id
        $message->room_id = Auth::user()->room_id;
        $message->save();

        //user
        //$user = Auth::user();
        //$user->room_id = 1;
        //$user->save();
        
        event(new MessageSent($message));

        $message->name = auth::user()->name;
        
        //return $content;
        return $message;
    }
}
