@extends('layouts.app')

<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<script>var roomId = {{ $room_id }};</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
        <div class="card">
            <div class="card-header">find a user and start to chat!</div>
            <div class="card-body">
                @foreach($users as $user)
                    <li>
                        <a href="{{ route('chat', ['user_b_id' => $user->id]) }}">{{ $user->name }}</a>
                    </li>
                @endforeach
            </div>
        </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">chat box</div>
                <div class="card-body">
                    @if($room_id != 0)
                        <chatbox :roomId = roomId></chatbox>
                    @else
                        <a>select a user</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
@endsection
