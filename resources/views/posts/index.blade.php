@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('posts.index') }}" class="item text-dark
            d-flex justify-content-between align-items-center {{ (isset($type))?'':'active' }} ">
                所有文章
                <span class="badge badge-secondary badge-light">{{ $posts_total }}</span>
            </a>
            <hr>
            @foreach ($post_types as $post_type)
                <a href="{{ route('types.show', ['id' => $post_type->id]) }}" class="item text-dark
                d-flex justify-content-between align-items-center 
                {{ (isset($type))?(($type->id == $post_type->id)?'active':''):'' }} ">
                    {{ $post_type->name }}
                    <span class="badge badge-secondary badge-light">
                        {{ $post_type->posts->count() }}
                    </span>
                </a>
                <hr>
            @endforeach
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('types.create') }}" class="btn btn-sm btn-outline-info ml-2">建立新分類</a>
                    <br>
                    <br>
                @endif
            @endauth 
        </div>
        <div class="col-md-9">
            <h5>   
                @auth
                    <div class="float-right">
                        @isset($user)
                            <a href="{{ route('chat', ['user_b_id' => $user->id]) }}" class="btn btn-sm btn-outline-info ml-2">開始聊聊!</a>
                            <span class="badge badge-info badge-secondary m1-2">
                                {{ count($posts) }} Posts
                            </span>
                        @endisset
                        @isset($index)
                        <a href="{{ route('posts.create') }}" class="btn btn-sm btn-outline-info ml-2">
                            <i class="fas fa-plus"></i>
                            <span class="pl-1">新增文章</span>
                        </a>
                        @endisset
                    </div>
                @endauth

                @isset($type)
                    {{ $type->name }}
                    @auth
                        @if(Auth::user()->isAdmin())
                        <div class="float-right">
                            <form action="{{ route('types.destroy', ['id' => $type->id]) }}" method="POST">
                                <span class="ml-2">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                        <span class="pl-1">刪除分類</span>
                                    </button>
                                </span>
                            </form>
                        </div>
                        <div class="float-right">
                            <a href="{{ route('types.edit', ['id' => $type->id]) }}"
                            class="btn btn-sm btn-outline-primary ml-1">
                                <i class="fas fa-pencil-alt"></i>
                                <span class="pl-1">編輯分類</span>
                            </a>
                        </div>
                        @endif
                    @endauth
                @endisset

                @isset($index)
                    所有文章
                @endisset

                @isset($user)
                    {{ $user->name }}
                @endisset

                @isset($like)
                    收藏
                @endisset

                @isset($keyword)
                    搜尋 : {{ $keyword }}
                @endisset
            </h5>
            <hr />
            @if(count($posts) == 0)
                <p class="text-center">
                    沒有任何文章
                </p>
            @endif
            @foreach($posts as $post)
            <div class="row">
            <div class="col-md-4">
                <div class="card h-100 justify-content-center align-items-center" 
                style="background-color: white; border-color:rgba(245, 245, 245, 0.4);">
                        @if($post->photo != null)
                        <a href="{{ route('posts.show', ['id' => $post->id]) }}" class="text-center">
                            <img src="{{ $post->getPhotoUrl() }}" 
                            style="max-width: 80%; max-height: 80%;">
                        </a>
                        @endif
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3" style="background-color: white; border-color:rgba(245, 245, 245, 0.4);">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('posts.show', ['id' => $post->id]) }}" class="card-title text-dark" style="font-size:18px;">{{ $post->title }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    @if($post->hasLike() == 1)
                                        <span class="badge badge-danger badge-secondary m1-2">
                                            已收藏
                                        </span>
                                    @endif
                                    @if($post->postType != null)
                                        <span class="badge badge-secondary m1-2">
                                            {{ $post->postType->name }}
                                        </span>
                                    @endif
                                    @if($post->photo != null)
                                        <span class="badge badge-secondary m1-2">
                                            圖片
                                        </span>
                                    @endif
                                    <span class="badge badge-secondary m1-2">
                                            {{ $post->user->name }}
                                    </span>
                                    <span class="badge badge-secondary m1-2">
                                            {{ $post->created_at->toDateString() }}
                                    </span>
                                </div>
                                <div calss="col-md-2">
                                    @if($post->hasLike() == 1)
                                        <a href="{{ route('like', ['post_id' => $post->id]) }}" class="text-danger">❤</a>&nbsp;
                                    @else
                                        <a href="{{ route('like', ['post_id' => $post->id]) }}" class="text-secondary">❤</a>&nbsp;
                                    @endif
                                    <a href="{{ route('users.showPosts', ['id' => $post->user->id]) }}">
                                        <img src="{{ $post->user->getAvatarUrl() }}" style="width: 30px; height: 30px;" class="rounded-circle mt-1">
                                    </a>
                                </div>
                            </div>
                            <hr class="my-2 mx-0">
                            <div class="row">
                                <div class="col-md-12" style="height:100px; overflow:hidden;">
                                    <p class="card-text">
                                        {!! $post->content !!}
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <span class="badge badge-secondary badge-light">
                                        {{ $post->likes }}&nbsp;喜歡
                                    </span>
                                    <span class="badge badge-secondary badge-light">
                                        &nbsp;{{ $post->comments->count() }}&nbsp;回應
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            </div>
            @endforeach
        </div>
    </div>
        <div class="col-md-10 offset-md-2">
            @isset($keyword)
                {{ $posts->appends(['keyword' => $keyword])->render() }}
            @else
                {{ $posts->render() }}
            @endisset
        </div>
    </div>
</div>

<style>
</style>

@stop
                              

