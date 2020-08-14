@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">編輯:{{ $post->title }}</div>
                <div class="card-body">
                    <div class="container-fluid">
                        <form action="{{ route('posts.update', ['id' => $post->id]) }}" method="POST" class="mt-2" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group row">
                                <label for="title" class="col-md-2 col-form-label-md text-md-right">
                                標題</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-md"
                                    name="title" id="title" value="{{ $post->title?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type" class="col-md-2 col-form-label-md text-md-right">
                                分類</label>
                                <div class="col-md-8">
                                    <select name="type" id="type" class="form-control form-control-md">
                                        <option value="0">請選擇...</option>
                                        @foreach($post_types as $post_type)
                                            <option value="{{ $post_type->id }}" 
                                            {{ ($post->type == $post_type->id)?"selected":"" }}>
                                                {{ $post_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="content" class="col-md-2 col-form-label-md text-md-right">
                                內文</label>
                                <div class="col-md-8">
                                    <textarea name="content" id="article-ckeditor" rows="15" 
                                    class="form-control form-control-md" style="resize: vertical;">
                                        {{ $post->content ?? '' }}
                                    </textarea>
                                </div>
                            </div>

                            <div class="form-group row">
						        <label class="col-md-12 col-form-label text-md-center">目前圖片</label>
								<div class="col-md-8 offset-md-2 text-center">
									<img src="{{ $post->getPhotoUrl() }}" 
                                    style="max-width: 400px; max-height: 400px;">
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

