<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        // store in DB

        broadcast(new MessageSent($request->message , Auth::user()));
        // event(new MessageSent($request->message , Auth::user()));

        // return redirect()
    }
}
