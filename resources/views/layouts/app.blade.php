<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>好部落格</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>        
        CKEDITOR.replace( 'article-ckeditor' );
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.flash-msg')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    好部落格
                </a>
                <form action="{{ route('search') }}" method="GET" class="form-inline" role="search">
                    <input type="search" class="form-control form-control-sm mr-md-2" 
                    name="keyword" placeholder="搜尋文章" aria-label="Search" style="width: 300px;">
                    <button type="submit" class="btn btn-sm btn-outline-primary my-2 my-md-0">
                        <i class="fas fa-search"></i>
                        搜尋
                    </button>
                </form>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                            <a href="{{ route('items') }}" class="btn btn-sm btn-outline-info my-2">商品
                            </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('chat', ['user_b_id' => 0]) }}" class="btn btn-sm btn-outline-info my-2">聊聊!
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-info my-2">通知:{{ Auth::user()->notification_count }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.showAvatar') }}" class="px-1">
                                    <img src="{{ Auth::user()->getAvatarUrl() }}"
                                    style="width: 30px; height: 30px;" class="rounded-circle mt-1">
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('users.showPosts', ['id' => Auth::user()->id]) }}">
                                    我的文章
                                </a>
                                <a class="dropdown-item" href="{{ route('showLike') }}">
                                    喜歡的文章
                                </a>
                                <a class="dropdown-item" href="{{ route('cart') }}">
                                    購物車
                                </a>
                                <a class="dropdown-item" href="{{ route('orders') }}">
                                    訂單紀錄
                                </a>
                                <a class="dropdown-item" href="{{ route('users.editName') }}">
                                    修改名稱
                                </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                       登出
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
