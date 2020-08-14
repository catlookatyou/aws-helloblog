<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{
    protected $fillable = ['content', 'user_id', 'room_id'];

    public function messages(){
        return $this->belongsTo(User::class);
    }
}
