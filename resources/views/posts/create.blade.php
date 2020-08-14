@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">新增文章</div>
                <div class="card-body">
                    <div class="container-fluid">
                        <form action="{{ route('posts.store') }}" method="POST" class="mt-2" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="title" class="col-md-2 col-form-label-md text-md-right">
                                標題</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-md
                                    {{ $errors->has('title') ? 'is-invalid' : '' }}" name="title" id="title">
                                        @if($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type" class="col-md-2 col-form-label-md text-md-right">
                                分類</label>
                                <div class="col-md-8">
                                    <select name="type" id="type" class="form-control form-control-md
                                    {{ $errors->has('type') ? 'is-invalid' : '' }}">
                                        <option value="0">請選擇...</option>
                                        @foreach($post_types as $post_type)
                                            <option value="{{ $post_type->id }}">
                                                {{ $post_type->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('type'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="content" class="col-md-2 col-form-label-md text-md-right">
                                內文</label>
                                <div class="col-md-8">
                                    <textarea name="content" id="article-ckeditor" rows="15"
                                    class="form-control form-control-md 
                                    {{ $errors->has('content') ? 'is-invalid' : '' }}" 
                                    style="resize: vertical;"></textarea>
                                    @if($errors->has('content'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('content') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>       

                            <div class="form-group row">
                                <label for="photo" class="col-md-2 col-form-label-md text-md-right">
                                    圖片</label>
                                <div class="col-md-6">
                                    <input type="file" id="photo" name="photo"
                                    class="form-control-file" accept="image/*">
                                </div>
                                <p class="form-text text-muted col-md-12 offset-md-2">
                                    圖片檔(jpeg, png, bmp, gif, svg)
                                </p>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-md btn-outline-info">儲存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<%=ResolveClientUrl('/vendor/unisharp/laravel-ckeditor/ckeditor.js')%>"></script>
<script>CKEDITOR.replace( 'article-ckeditor' );</script>
@stop

