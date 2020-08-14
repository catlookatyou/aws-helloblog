<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
use Illuminate\Support\Facades\Auth as Auth;

Route::post('/broadcasting/auth', function (Illuminate\Http\Request $req) {
    $user =Auth::user();

    request()->setUserResolver(function () use ($user) {
        return $user;
    });
    return Broadcast::auth($req);
});
*/

Route::get('/test', function(Illuminate\Http\Request $request){
	$orders = App\Order::all();
	return $request->user();
});

Route::get('/', 'HomeController@index')->name('index');
Route::get('search', 'HomeController@search')->name('search');
Route::get('verifyEmail', 'HomeController@verify')->name('verify');

Auth::routes(['verify' => true]);
//Auth::routes();

//Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('login', 'Auth\LoginController@login');
//Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::resource('posts', 'PostsController');
Route::resource('posts/types', 'PostTypesController', ['except' => ['index']]);
Route::resource('posts.comments', 'PostCommentsController', ['only' => ['store', 'destroy']]);

Route::prefix('users')->name('users.')->group(function(){
	Route::get('posts/{id}', 'UsersController@showPosts')->name('showPosts');
	Route::get('name', 'UsersController@editName')->name('editName');
	Route::post('name', 'UsersController@updateName')->name('updateName');
	Route::get('password', 'UsersController@editPassword')->name('editPassword');
	Route::post('password', 'UsersController@updatePassword')->name('updatePassword');
	Route::get('avatar', 'UsersController@showAvatar')->name('showAvatar');
	Route::post('avatar', 'UsersController@uploadAvatar')->name('uploadAvatar');
});

Route::prefix('login/social')->name('social.')->group(function(){
	Route::get('{provider}/redirect', 'Auth\SocialController@getSocialRedirect')->name('redirect');
	Route::get('{provider}/callback', 'Auth\SocialController@getSocialCallback')->name('callback');
});

Route::get('likes/{post_id}', 'LikeController@like')->name('like');
Route::get('likes', 'LikeController@show')->name('showLike');

Route::resource('notifications', 'NotificationsController', ['only' => ['index', 'destroy']]);

Route::get('/getRoomId', 'MessagesController@getRoomId')->name('getRoomId');
Route::get('/chat/{user_b_id}', 'MessagesController@chat')->name('chat');
Route::post('/post', 'MessagesController@post')->name('post');
Route::get('/getAll', 'MessagesController@getAll')->name('getAll');

Route::get('/items', 'ItemsController@index')->name('items');
Route::get('/cart', 'ItemsController@cart')->name('cart');
Route::get('/increase-one-item/{id}', 'ItemsController@increaseByOne');
Route::get('/decrease-one-item/{id}', 'ItemsController@decreaseByOne');
Route::get('/remove-item/{id}', 'ItemsController@removeItem');
Route::get('/add-to-cart/{id}', 'ItemsController@AddToCart')->name('addcart');
Route::get('/clear-cart', 'ItemsController@clearCart');

Route::get('/all_orders', 'OrdersController@orders');
Route::get('/orders', 'OrdersController@index')->name('orders');
Route::get('/order/new', 'OrdersController@new');
Route::post('/order/store', 'OrdersController@store');
Route::post('/order/callback', 'OrdersController@callback');
Route::get('/success', 'OrdersController@redirectFromECpay');
