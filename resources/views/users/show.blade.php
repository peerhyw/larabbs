@extends('layouts.app')
@section('title',$user->name . ' 的个人中心')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="media">
                    <div align="center">
                        <img src="{{ $user->avatar }}" width="300px" height="300px" alt="{{ $user->name }}" class="img-responsive img-circle">
                    </div>
                    <div class="media-body">

                        @if (Auth::check())
                            @include('users._follow_form')
                        @endif

                        <hr>
                        <h4><strong>个人简介</strong></h4>
                        <p>{{ $user->introduction }}</p>
                        <hr>
                        <h4><strong>注册于</strong></h4>
                        <p>{{ $user->created_at->diffForHumans() }}</p>
                        <hr>
                        <h4><strong>最后活跃于</strong></h4>
                        <p title="{{ $user->last_actived_at }}">
                            {{ $user->last_actived_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <span>
                    <h1 class="panel-title pull-left" style="font-size: 30px;">{{ $user->name }} <small>{{ $user->email }}</small></h1>
                </span>
            </div>
        </div>
        <hr>

        {{-- 用户发布的内容 --}}
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="{ active_class(if_query('tab',null)) }">
                        <a href="{{ route('users.show',$user->id) }}">Ta 的话题</a>
                    </li>
                    <li class="{{ active_class(if_query('tab','replies'))}}">
                        <a href="{{ route('users.show',[$user->id,'tab' => 'replies']) }}">Ta 的回复</a>
                    </li>
                    <li class="{{ active_class(if_query('tab','followings'))}}">
                        <a href="{{ route('users.show',[$user->id,'tab' => 'followings']) }}">Ta 的关注</a>
                    </li>
                    <li class="{{ active_class(if_query('tab','followers'))}}">
                        <a href="{{ route('users.show',[$user->id,'tab' => 'followers']) }}">Ta 的粉丝</a>
                    </li>
                </ul>
                @if(if_query('tab','replies'))
                    @include('users._replies',['replies' => $user->replies()->with('topic')->recent()->paginate(5)])
                @elseif(if_query('tab','followings'))
                    @include('users._followlist',['followlist' => $user->followings()->paginate(10)])
                @elseif(if_query('tab','followers'))
                    @include('users._followlist',['followlist' => $user->followers()->paginate(10)])
                @else
                    @include('users._topics',['topics' => $user->topics()->recent()->paginate(5)])
                @endif
            </div>
        </div>

    </div>
</div>
@stop