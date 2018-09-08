@if(count($followlist))

<ul class="list-group">
    @foreach($followlist as $user)
        <li class="list-group-item">
            <a href="{{ route('users.show',$user->id) }}">
                <div class="img-follow">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="img-circle" width="24px" height="24px">
                </div>

                <div class="name-follow">
                    <span>{{ $user->name }}</span>
                </div>
            </a>
        </li>
    @endforeach
</ul>

@else
    <div class="empty-block">暂无关注 ~_~</div>
@endif

{{-- 分页 使用了 URL 中的 tab 请求参数对话题列表进行区分，分页中 appends() 方法可以使 URI 中的请求参数得到继承。--}}
{!! $followlist->appends(Request::except('page'))->render() !!}