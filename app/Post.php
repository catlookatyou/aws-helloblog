<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User as UserEloquent;
use App\PostType as PostTypeEloquent;
use App\Comment as CommentEloquent;
use App\Like as LikeEloquent;
use Auth;
use URL;

class Post extends Model
{
	protected $fillable = [
		'title', 'type', 'content', 'user_id'
	];

	public function user(){
		return $this->belongsTo(UserEloquent::class);
	}

	public function postType(){
		return $this->belongsTo(PostTypeEloquent::class, 'type');
	}

    public function comments(){
		return $this->hasMany(CommentEloquent::class);
	}

	public function hasLike(){
		if(Auth::user()){
			$user_id = Auth::user()->id;
			$user_like = LikeEloquent::where('post_id', $this->id)
			->find($user_id);
			if($user_like == null)
				return 0;
			else
				return 1;
		}
		return 0;
	}

	public function getPhotoUrl(){ 
	    if(!empty($this->photo)){
		    if(!preg_match("/^[a-zA-z]+:\/\//", $this->photo)){
			    return URL::asset($this->photo);
		    }else{
			    return $this->photo;
		    }
	    }
    }
}
