<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use Redirect;
use View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware(['auth', 'verified']);
        $this->middleware(['verified'], [
		    'only' => [
			    'verify'
		    ]
	    ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return Redirect::action('PostsController@index');
    }

    public function verify(){
        //use to redirect to send verify email
        return View::make('verify');
    }

    public function search(Request $request){
	    if(!$request->has('keyword')){
		    return Redirect::back();
	    }
	    $keyword = $request->keyword;
	    $posts = PostEloquent::where('title', 'LIKE', "%".$keyword."%")
		    ->orderBy('created_at', 'DESC')->paginate(5);
	    $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
	    $posts_total = PostEloquent::get()->count();
	    return View::make('posts.index', compact('posts', 'post_types', 'posts_total', 'keyword'));
    }
}
