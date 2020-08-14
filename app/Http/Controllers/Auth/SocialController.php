<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as UserEloquent;
use App\SocialUser as SocialUserEloquent;
use App;
use Auth;
use Config;
use Redirect;
use Socialite;

class SocialController extends Controller
{
	public function __construct(){
		$this->middleware('guest');
	}

	public function getSocialRedirect($provider){
		$providerKey = Config::get('services.' . $provider);
		if(empty($providerKey)){
			return App::abort(404);
		}
		return Socialite::driver($provider)->redirect();
	}

	public function getSocialCallback($provider, Request $request){
		if($request->exists('error_code')){
			return Redirect::Route('login')->withErrors([
				'msg' => $provider . '登入或綁定失敗，請重新再試'
			]);
		}

	        $socialite_user = Socialite::with($provider)->user();
	        $login_user = null;
	        $s_u = SocialUserEloquent::where('provider_user_id', 
		$socialite_user->id)->where('provider', $provider)->first();
                
		if(!empty($s_u)){
			$login_user = $s_u->user;
		}else{
			if(empty($socialite_user->email)){
				return Redirect::route('login')->withErrors([
					'msg' => '很抱歉，無法從您的' . $provider .
                                         '帳號抓到信箱，請用其他方式註冊帳號!'
				 ]);
			}

			$user = UserEloquent::where('email', $socialite_user->email)->first();

			if(!empty($user)){
				$login_user = $user;
				$s_user = $login_user->socialUser;

				if(!empty($s_user)){
					return Redirect::route('login')->withErrors([
						'msg' => '此email已被其他帳號綁定'
					]);
				}else{
					$login_user->socialUser = SocialUserEloquent::create([
						'provider_user_id' => $socialite_user->id,
						'provider' => $provider,
						'user_id' => $login_user->id
					]);
				}
			}else{
				$login_user = UserEloquent::create([
					'email' => $socialite_user->email,
					'password' => bcrypt(str_random(8)),
					'name' => $socialite_user->name,
					'avatar' => $socialite_user->avatar
				]);

				$login_user->socialUser = SocialUserEloquent::create([
					'provider_user_id' => $socialite_user->id,
					'provider' => $provider,
					'user_id' => $login_user->id
				]);
			}
		}

		if(!is_null($login_user)){
			Auth::login($login_user);
			return Redirect::route('index');
		}
		return App::abort(500);
	}
                

}
