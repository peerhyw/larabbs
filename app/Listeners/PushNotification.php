<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\DatabaseNotification;
use JPush\Client;

class PushNotification
{
    protected $client;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    /*
        逻辑很简单，当通知存入数据库后(table:notifications)，也就是监听 eloquent.created: Illuminate\Notifications\DatabaseNotification 这个事件，如果用户已经有了 Jpush 的 registration_id，则使用 Jpush SDK 将消息内容推送到目标用户的 APP 中，注意我们使用了 strip_tags 去除了 notificaiton 数据中的 HTML 标签。
    */
    public function handle(DatabaseNotification $notification)
    {
        if(app()->environment('local')){
            return;
        }

        $user = $notification->notifiable;

        //没有registration_id的不推送
        if(!$user->registration_id){
            return;
        }

        //推送消息
        $this->client->push()
                    ->setPlatform('all')
                    ->addRegistrationId($user->registration_id)
                    ->setNotificationAlert(strip_tags($notification->data['reply_content']))
                    ->send();
    }
}
