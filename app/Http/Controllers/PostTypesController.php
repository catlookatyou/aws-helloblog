<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostTypeRequest;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use View;
use Redirect;

class PostTypesController extends Controller
{
    public function __construct(){
	    $this->middleware(['auth'], [
		    'except'=> [
			    'show'
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('posts.types.createType');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostTypeRequest $request)
    {
	    PostTypeEloquent::create($request->only('name'));
	    return Redirect::route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $type = PostTypeEloquent::findOrFail($id);
	    $posts = PostEloquent::where('type', $id)->orderBy('created_at', 'DESC')->paginate(5);
	    $post_types = PostTypeEloquent::orderBy('name', 'ASC')->get();
	    $posts_total = PostEloquent::get()->count();
	    return View::make('posts.index', compact('posts', 'post_types', 'type', 'posts_total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $post_type = PostTypeEloquent::findOrFail($id);
	    return View::make('posts.types.editType', compact('post_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostTypeRequest $request, $id)
    {
	    $post_type = PostTypeEloquent::findOrFail($id);
	    $post_type->fill($request->only('name'));
	    $post_type->save();
	    return Redirect::route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $post_type = PostTypeEloquent::findOrFail($id);
	    $post_type->posts()->delete();
	    $post_type->delete();
	    return Redirect::route('posts.index');
    }
}
