<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User as UserEloquent;
use App\Post as PostEloquent;

class Like extends Model
{
    protected $table = 'user_likes';
    protected $primaryKey = 'user_id'; 

	public function user() {
		return $this->belongsTo(UserEloquent::class);
	}

	public function post() {
		return $this->belongsTo(PostEloquent::class);
	}
        
	public $timestamps = false;
}
