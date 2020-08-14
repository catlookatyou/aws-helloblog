<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use App\Like as LikeEloquent;
use Auth;
use View;
use Redirect;
use DB;


class LikeController extends Controller
{
	public function __construct(){
		$this->middleware(['auth']);
	}

	public function like($post_id){
		$post = PostEloquent::findOrFail($post_id);
		$user_id = Auth::user()->id;
		//查詢Like表，若找不到user會回傳null
		$user_like = LikeEloquent::where('post_id', $post_id)
		->find($user_id);

		//點讚成功並存入like表
		if($user_like == null)
		{
			$post->likes+=1;
			$post->save();
			$like = new LikeEloquent;
			$like->user_id = Auth::id();
			$like->post_id = $post_id;
			$like->save();
			return Redirect::back();
		}else{
			//取消點讚並刪除資料
			if($post->likes != 0)
				$post->likes-=1;
			else
			    $post->likes = 0;
			$post->save();
			//$user_like->delete();
			DB::table('user_likes')->where('user_id', $user_id)->where('post_id', $post_id)->delete();
			return Redirect::back();
		}
	}

	public function show(){
		$user_id = Auth::user()->id;
		$like_posts = LikeEloquent::where('user_id', $user_id)->get();
		//$like_posts = LikeEloquent::find($user_id);
		$like_total = $like_posts->count();
		if($like_total != 0){
			//$posts = PostEloquent::where('id', $like_posts->post_id)->orderBy('created_at', 'DESC')->paginate(5);
			foreach($like_posts as $post){
				$arr[] = $post->post_id;
			}
			$posts = PostEloquent::whereIn('id', $arr)->orderBy('created_at', 'DESC')->paginate(5);
		}
		else 
			$posts = PostEloquent::where('id', 'null')->orderBy('created_at', 'DESC')->paginate(5);
		$post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
		$posts_total = PostEloquent::get()->count();
		$like = 1;	
        return View::make('posts.index', compact('posts', 'like', 'post_types', 'posts_total'));
	}
}
