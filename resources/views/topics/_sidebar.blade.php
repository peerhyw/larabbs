<div class="panel panel-default">
    <div class="panel-body">
        <a href="{{ route('topics.create') }}" class="btn btn-success btn-block" aria-label="Left Align">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            发布帖子
        </a>
    </div>
</div>

@if(count($active_users))
    <div class="panel panel-default">
        <div class="panel-body active-users">

            <div class="text-center">活跃用户</div>
            <hr>
            @foreach($active_users as $active_user)
                <a href="{{ route('users.show',$active_user->id) }}" class="media">
                    <div class="media-left media-middle">
                        <img src="{{ $active_user->avatar }}" alt="{{ $active_user->name }}" class="img-circle media-object" width="24px" height="24px">
                    </div>

                    <div class="media-body">
                        <span class="media-heading">{{ $active_user->name }}</span>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
@endif


@if(count($links))
    <div class="panel panel-default">
        <div class="panel-body active-users">

            <div class="text-center">资源推荐</div>
            <hr>
            @foreach($links as $link)
                <a href="{{ $link->link }}" class="media">
                    <div class="media-body">
                        <span class="media-heading">{{ $link->title }}</span>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
@endif