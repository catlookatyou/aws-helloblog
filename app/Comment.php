<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User as UserEloquent;
use App\Post as PostEloquent;

class Comment extends Model
{
	protected $fillable = [
		'post_id', 'user_id', 'content'
	];

	public function user() {
		return $this->belongsTo(UserEloquent::class);
	}

	public function post() {
		return $this->belongsTo(PostEloquent::class);
	}
}
