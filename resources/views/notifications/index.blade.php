@extends('layouts.app')

@section('title')
我的通知
@stop

@section('content')
    <div class="container">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center">
                        <span class="glyphicon glyphicon-bell" aria-hidden="true"></span> 我的通知
                    </h3>
                    <hr>

                    @if($notifications->count())
                        <div class="notification-list">
                            @foreach($notifications as $notification)

                                {{-- 通知数据库表的type字段保存的是通知类全称
                                    如 ：App\Notifications\TopicReplied 。
                                    snake_case(class_basename($notification->type)) 渲染以后会是 —— topic_replied。
                                    class_basename() 方法会取到 TopicReplied，
                                    Laravel 的辅助方法 snake_case() 会字符串格式化为下划线命名。
                                --}}

                                @include('notifications.types._' . snake_case(class_basename($notification->type)))
                            @endforeach

                            {!! $notifications->render() !!}
                        </div>
                    @else
                        <div class="empty-block">没有消息通知！</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@stop