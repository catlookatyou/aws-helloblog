<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use App\Comment as CommentEloquent;
use Carbon\Carbon;
use Auth;
use View;
use File;
use Redirect;

class PostsController extends Controller
{
    public function __construct(){
	    $this->middleware(['auth'], [ //, 'verified'
		    'except' => [
			    'index', 'show'
		    ]
	    ]);

	    $this->middleware(['admin'], [
		    'only' => [
			    'edit', 'update', 'destroy'
		    ]
	    ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $index = 1;
	    $posts = PostEloquent::orderBy('created_at', 'DESC')->paginate(5);
	    $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
	    $posts_total = PostEloquent::get()->count();
	    return View::make('posts.index', compact('posts', 'post_types', 'posts_total', 'index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
	    return View::make('posts.create', compact('post_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        if(!$request->hasFile('photo')){
	        $post = new PostEloquent($request->except('photo'));
	        $post->user_id = Auth::user()->id;
	        $post->save();
            return Redirect::route('posts.index');
        }else{
            $post = new PostEloquent($request->except('photo'));
            $post->user_id = Auth::user()->id;
            
            //has photo
            $file = $request->file('photo');
            $destPath = 'images/photos';
            //make dir
            if(!file_exists(public_path() . '/' . $destPath)){
                File::makeDirectory(public_path() . '/' . $destPath, 0755, true);
            }
            //change file name
            $ext = $file->getClientOriginalExtension();
            $fileName = (Carbon::now()->timestamp) . '.' . $ext;
            $file->move(public_path() . '/' . $destPath, $fileName);
            //save
            $post->photo = $destPath . '/' . $fileName;

            $post->save();
            return Redirect::route('posts.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $post = PostEloquent::findOrFail($id);
	    $comments = CommentEloquent::where('post_id', $post->id)
		    ->orderBy('created_at', 'DESC')->paginate(5);
	    return View::make('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $post = PostEloquent::findOrFail($id);
	    $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
	    return View::make('posts.edit', compact('post', 'post_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        if(!$request->hasFile('photo')){
	        $post = PostEloquent::findOrFail($id);
	        $post->fill($request->except('photo'));
	        $post->save();
            return Redirect::route('posts.index');
        }else{
            $post = PostEloquent::findOrFail($id);
            $post->fill($request->except('photo'));
            
            //has photo
            $file = $request->file('photo');
            $destPath = 'images/photos';
            //make dir
            if(!file_exists(public_path() . '/' . $destPath)){
                File::makeDirectory(public_path() . '/' . $destPath, 0755, true);
            }
            //change file name
            $ext = $file->getClientOriginalExtension();
            $fileName = (Carbon::now()->timestamp) . '.' . $ext;
            $file->move(public_path() . '/' . $destPath, $fileName);
            //save
            $post->photo = $destPath . '/' . $fileName;

            $post->save();
            return Redirect::route('posts.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $post = PostEloquent::findOrFail($id);
        $post->delete();
        $comment = CommentEloquent::where('post_id', $id);
        $comment->delete();
	    return Redirect::route('posts.index');
    }
}
