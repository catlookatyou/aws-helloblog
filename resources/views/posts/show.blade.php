@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="row">
            <div class="col-md-12 pb-2 mt-4 mb-2 border-bottom">
                <div class="row">
                    <h4>{{ $post->title }}</h4>
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::id() == $post->user_id)
                        &nbsp;&nbsp;&nbsp;&nbsp;
                                <form action="{{ route('posts.destroy', ['id' => $post->id]) }}"
                                method="POST">
                                    @csrf
                                    <a href="{{ route('posts.edit', ['id' => $post->id]) }}" 
                                    class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-pencil-alt"></i>
                                        <span class="pl-1">編輯文章</span>
                                    </a>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                        <span class="pl-1">刪除文章</span>
                                    </button>
                                </form>
                           
                        @endif
                    @endauth
                </div>
                <div class="row">
                    <div class="col-md-11">
                        @if($post->hasLike() == 1)
                            <span class="badge badge-danger badge-secondary m1-2">
                                已收藏
                            </span>
                        @endif
                        @if($post->postType != null)
                            <span class="badge badge-secondary ml-2">
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
                    <div calss="col-md-1">
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
            </div>
           
           <div class="row">
                <div class="col-md-12">
                    @if($post->photo != null)
                        <img src="{{ $post->getPhotoUrl() }}" 
                        style="max-width: 400px; max-height: 400px;">
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
        <div class="row" style="padding-top: 30px;">
            @foreach ($comments as $comment)
                <div class="card col-md-12 mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <a href="{{ route('users.showPosts', ['id' => $comment->user->id]) }}">
                                    <img src="{{ $comment->user->getAvatarUrl() }}" style="width: 30px; height: 30px;" 
                                    class="rounded-circle mt-1">
                                </a>
                                {{ $comment->user->name }} 在 {{ $comment->created_at }} 說:
                            </div>
                            <div class="col-md-4">
                                @auth
                                    @if(Auth::id() == $comment->user_id || Auth::user()->isAdmin())
                                        <div class="float-right ml-auto">
                                            <form action="{{ route('posts.comments.destroy', 
                                            ['post' => $post->id, 'comment' => $comment->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="pl-1">刪除回應</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px;">
                            <div class="col-md-12">
                                {{ $comment->content }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-12 my-3">
                <div class="col-md-10 offset-md-1">
                <hr>
                    @auth
                        <h5>發表回應</h5>
                        <form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label-md text-md-right">名稱</label>
                                <div class="col-md-8">
                                    <label class="col-form-label-md">{{ Auth::user()->name }}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="content" class="col-md-2 col-form-label-md text-md-right">
                                回應</label>
                                <div class="col-md-8">
                                    <textarea name="content" id="content" row="5" class="form-control"
                                    style="resize: vertical;"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-2">
                                    <button type="submit" class="btn btn-outline-info btn-block">
                                        發表
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-center">如要發表回應，請先<a href="{{ route('login') }}">登入</a></p>
                    @endauth
                </div>
            </div>
        </div>           
    </div>
</div>
@stop


