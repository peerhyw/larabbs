<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\FollowRequest;
use App\Transformers\UserTransformer;

class FollowersController extends Controller
{
    public function store(FollowRequest $request){
        if($this->user()->id == $request->user_id){
            return $this->response->array(['message' => '不能关注自己'])->setStatusCode(403);
        }
        try {
            if(!User::find($request->user_id)){
                return $this->response->array(['message' => '此用户不存在'])->setStatusCode(403);
            }elseif($this->user()->isFollowing($request->user_id)){
                return $this->response->array(['message' => '已经关注此用户'])->setStatusCode(403);
            }elseif($this->user()->follow($request->user_id)){
                return $this->response->array(['message' => '关注成功'])->setStatusCode(201);
            }else{
                return $this->response->array(['message' => '关注失败'])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->error('error',400);
        }
    }

    public function destroy(FollowRequest $request){
        if($this->user()->id == $request->user_id){
            return $this->response->array(['message' => '不能取消关注自己'])->setStatusCode(403);
        }
        try {
            if(!User::find($request->user_id)){
                return $this->response->array(['message' => '此用户不存在'])->setStatusCode(403);
            }elseif(!$this->user()->isFollowing($request->user_id)){
                return $this->response->array(['message' => '没有关注此用户'])->setStatusCode(403);
            }elseif($this->user()->unfollow($request->user_id)){
                return $this->response->array(['message' => '取消关注成功'])->setStatusCode(201);
            }else{
                return $this->response->array(['message' => '取消关注失败'])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->error('error',400);
        }
    }

    public function followersIndex(User $user){
        $followers = $user->followers()->paginate(10);
        return $this->response->paginator($followers,new UserTransformer());
    }

    public function followingsIndex(User $user){
        $followings = $user->followings()->paginate(10);
        return $this->response->paginator($followings,new UserTransformer());
    }
}
