@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">認證成功</div>
                <div class="card-body">
                    <div class="container-fluid p-0">
                        你好! {{ Auth::user()->name }}
                    </div>
                </div>
            </div>  
        </div>  
    </div>  
</div>  
@stop
