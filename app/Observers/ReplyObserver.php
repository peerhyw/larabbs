<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;
use Auth;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{

    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content,'user_topic_body');
    }

    public function updating(Reply $reply)
    {
        //
    }

    public function created(Reply $reply){
        $topic = $reply->topic;

        $topic->last_reply_user_id = Auth::user()->id;

        $topic->save();

        $topic->increment('reply_count',1);

        //通知作者话题被回复了
        $topic->user->notify(new TopicReplied($reply));
    }

    public function deleted(Reply $reply){
        $topic = $reply->topic;

        if($topic->reply_count === 1){
            $topic->last_reply_user_id = 0;
        }
        else{
            $result = Reply::select('user_id')->where('topic_id',$topic->id)->orderBy('created_at','desc')->first();

            $topic->last_reply_user_id = $result['user_id'];
        }

        $topic->save();

        $topic->decrement('reply_count',1);

    }
}