<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('Notification.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});

Broadcast::channel('orders', function($user){
    if($user->type = 'admin' || $user->type=='super-admin'){
        return true;
    }
    return false;
    // $order = Order::findOrFail($id);
    // return $order->user_id == $user->id;
});

Broadcast::channel('chat', function($user){
    if($user->type = 'admin' || $user->type=='super-admin'){
        return $user;
    }
});
