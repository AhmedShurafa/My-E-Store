<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.notifications',[
            'notifications' => $user->notifications()->paginate(),
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();   // read it

        if(isset($notification->data['url']) && $notification->data['url'])
        {
            return redirect($notification->data['url']);
        }

        return redirect('http://127.0.0.1:8000/en/admin/dashboard');
    }
}
