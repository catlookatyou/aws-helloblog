<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $current_user = Auth::user();
	    $id = $request->post;
	    if(!empty($current_user) && !empty($id)){
		    $post = $current_user->posts()->find($request->post);
		    if($current_user->isAdmin() || !empty($post)){
			    return $next($request);
		    }
	    }
	    if($request->ajax() || $request->wantsJson()){
		    return response('您沒有權限操作此項目.', 401);
	    }else{
		    return redirect()->route('posts.index');
	    }
    }
}
