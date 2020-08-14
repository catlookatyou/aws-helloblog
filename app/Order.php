<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Order extends Model
{
    protected $fillable = ['user_id', 'name', 'email', 'cart', 'paid', 'uuid'];

    public function user(){
		return $this->belongsTo(User::class, 'user_id');
    }
}
