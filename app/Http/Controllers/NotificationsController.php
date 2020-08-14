<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;

class NotificationsController extends Controller
{
	public function __construct(){
		$this->middleware(['auth']);
	}

	public function index(){
		$notifications = Auth::user()->notifications()->paginate(10);
		$user = Auth::user();
		$user->notification_count = 0;
		$user->save();
		return view('notifications.index', compact('notifications'));
	}

	public function destroy($id)
    {
	    $notification = Auth::user()->notifications()->findOrFail($id);
		$notification->delete();
		$notifications = Auth::user()->notifications()->paginate(10);
		return Redirect::route('notifications.index');
    }
}
