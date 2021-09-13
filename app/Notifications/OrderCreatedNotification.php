<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;
use TweetSmsChannle;

class OrderCreatedNotification extends Notification
{
    use Queueable;
    protected $order;
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        // $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // mail , database , nexmo (SMS), broadcast , slack , [custom channel]
        // return ['database','broadcast'];
        // return ['nexmo','mail'];
        return [TweetSmsChannle::class];
        // $notifiable->notify_email
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('New Order #:number' ,['numer ' => $this->order->number]))
                    ->from('invoices@localhost.com','GSG Billing')
                    ->greeting(__('Hello, :name',['name' => $notifiable->name ?? null]))
                    ->line(__('A new order has been created (Order #:number).',[
                        'number' => $this->order->number,
                    ]))
                    ->action(__('view Order'), url('/'))
                    ->line('Thank you for shopping with us')
                    //->view('',[
                    //    'order'=> $this->order
                    // ]) // to change layout
                     ;
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => __('New Order #:number' , ['number'=> $this->order->number]),
            'body'  => __('A new order has been created (Order #:number).',[
                'number' => $this->order->number,
            ]),
            'icon'  => '',
            'url'   => url('/'),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => __('New Order #:number' , ['number'=> $this->order->number]),
            'body'  => __('A new order has been created (Order #:number).',[
                'number' => $this->order->number,
            ]),
            'icon'  => '',
            'url'   => url('/'),
            'time'   => Carbon::now()->diffForHumans(),
        ]);
    }
    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        $message = new \Illuminate\Notifications\Messages\NexmoMessage();
        $message->content('A new order has been created (Order #:number).',[
            'number' => $this->order->number,
        ]);
        return $message;
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toTweetSms($notifiable)
    {
        return __('A new order has been created (Order #:number).',[
            'number' => $this->order->number,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
