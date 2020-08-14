<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use URL;
use Auth;
use App\Post as PostEloquent;
use App\SocialUser as SocialUserEloquent;
use App\Message;
use App\Order;

class User extends Authenticatable implements MustVerifyEmail
{
    use notifiable {
        notify as protected laravelNotify;
    }

    public function sendEmailVerificationNotification(){
        $this->notify(new \App\Notifications\VerifyEmailQueued);
    }

    public function notify($instance) {
        // 如果不是当前用户，就不必通知了
        if($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
	    return $this->hasMany(PostEloquent::class);
    }

    public function socialuser(){
	    return $this->hasOne(SocialUserEloquent::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function isAdmin(){
	    if($this->type == "1")
		    return true;
	    else
		    return false;
    }
   
    public function orders(){
        return $this->hasMany(Order::class);
    }
    
    public function getAvatarUrl(){ 
	    if(empty($this->avatar)){
		    return URL::asset('https://via.placeholder.com/150');
	    }else{
		    if(!preg_match("/^[a-zA-z]+:\/\//", $this->avatar)){
			    return URL::asset($this->avatar);
		    }else{
			    return $this->avatar;
		    }
	    }
    }
}
