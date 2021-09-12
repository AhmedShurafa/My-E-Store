<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderInvoice;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendInvoiceListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($order)
    {
        // $order = $event->order;
        $user = User::where('type','super-admin')->first();
        $user->notify(new OrderCreatedNotification($order));

        // To send meny user you can use it
        // $users = User::whereIn('type',['admin','super-admin'])->get();
        // #S1
        // foreach ($users as $user){
        //     $user->notify( new OrderCreatedNotification($order));
        // }

        // #S2
        // Notification::send($users , new OrderCreatedNotification($order));


        // Notification::route('mail',['Test@test.com','person@person.com'])
        //     // ->route('nexmo','12345789')
        //     ->notify( new OrderCreatedNotification($order));

        // Mail::to($order->billing_email)->send(new OrderInvoice($order));
    }
}
