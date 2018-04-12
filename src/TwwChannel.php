<?php

namespace NotificationChannels\Tww;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Tww\Exceptions\CouldNotSendNotification;

class TwwChannel
{
    /**
     * @var Tww
     */
    protected $tww;

    /**
     * Channel constructor.
     *
     * @param Tww $Tww
     */
    public function __construct(Tww $tww)
    {
        $this->tww = $tww;
    }

    /**
     * Send the given notification.
     *
     * @param mixed        $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTww($notifiable);

        if (! $to = $notifiable->routeNotificationFor('tww')) {
            $to = $message->to;
        }

        $params  = $message->toArray();

        $this->tww->sendMessage($to, $params);
    }
}
