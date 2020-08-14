<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserAvatarRequest;
use App\Http\Requests\PostTypeRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\User as UserEloquent;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use \Carbon\Carbon;
use Auth;
use View;
use File;
use Redirect;

class UsersController extends Controller
{
	public function __construct(){
		$this->middleware(['auth'],[
			'except' => [
				'showPosts'
			]
		]);
	}
	
	public function showPosts($id){
		$user = UserEloquent::findOrFail($id);
		$user_id = $id;
		$posts = PostEloquent::where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(5);
        $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
	    $posts_total = PostEloquent::get()->count();
		return View::make('posts.index', compact('posts', 'post_types', 'posts_total', 'user'));
	}

	public function editName(){
		return View::make('users.name');
	}

	public function updateName(PostTypeRequest $request){
		$user = Auth::user();
		$user->fill($request->only('name'));
		$user->save();
		return Redirect::route('posts.index');
	}

	public function editPassword(){
		return View::make('users.editPassword');
	}

	public function updatePassword(ResetPasswordRequest $request){
		$request_email = $request->email;
		$user = UserEloquent::findOrFail($request_email);
		if($request->password != $request->password_confirmation)
			return redirect()->back();
		else{
			$user->fill($request->only('password'));
			$user->save();
			return Redirect::route('posts.index');
		}
	}

	public function showAvatar(){
		return View::make('users.avatar');
	}

	public function uploadAvatar(UserAvatarRequest $request){
		if(!$request->hasFile('avatar')){
			return Redirect::route('index');
		}

		$file = $request->file('avatar');
		$destPath = 'images/avatars';

		if(!file_exists(public_path() . '/' . $destPath)){
			File::makeDirectory(public_path() . '/' . $destPath, 0755, true);
		}

		$ext = $file->getClientOriginalExtension();
		$fileName = (Carbon::now()->timestamp) . '.' . $ext;
		$file->move(public_path() . '/' . $destPath, $fileName);

		$user = Auth::user();
		$user->avatar = $destPath . '/' . $fileName;
		$user->save();
		return Redirect::route('index');
	}
}
