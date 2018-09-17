<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\FollowRequest;

class FollowersController extends Controller
{
    public function store(FollowRequest $request){
        if($this->user()->id == $request->user_id){
            return $this->response->errorBadRequest('不能关注自己');
        }
        try {
            if(!$this->user()->isFollowing($request->user_id)){
                $this->user()->follow($request->user_id);
            }
            return $this->response->array(['message' => '关注成功'])-setStatusCode(201);
        } catch (\Exception $e) {
            return $this->response->error('关注失败，请重试',400);
        }
    }
}
