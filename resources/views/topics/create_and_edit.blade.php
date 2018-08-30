@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-body">
                <h2 class="text-center">
                    <i class="glyphicon glyphicon-edit"></i>
                    @if($topic->id)
                        编辑话题
                    @else
                        新建话题
                    @endif
                </h2>

                <hr>

                @include('common.error')

                @if($topic->id)
                    <form action="{{ route('topics.update',$topic->id) }}" method="post" accept-charset="UTF-8">
                        <input type="hidden" name="_method" value="PUT">
                @else
                    <form action="{{ route('topics.store') }}" method="post" accept-charset="UTF-8">
                @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <input class="form-control" type="text" name="title" value="{{ old('title',$topic->title) }}" placeholder="请填写标题" required>
                        </div>

                        <div class="form-group">
                            <select name="category_id" class="form-control" required>
                                <option value="" hidden disabled selected>请选择分类</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <textarea name="body" id="editor" rows="3" class="form-control" placeholder="请填入至少三个字符的内容" required>
                                {{ old('body',$topic->body) }}
                            </textarea>
                        </div>

                        <div class="well well-sm">
                            <button class="btn btn-primary" type="submit">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                保存
                            </button>
                        </div>
                    </form>
            </div>

        </div>
    </div>
</div>

@endsection