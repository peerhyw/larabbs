<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    public function store(UserRequest $request){
        $verifyData = \Cache::get($request->verification_key);

        if(!$verifyData){
            return $this->response->error('验证码已失效',422); //422:提交的参数错误
        }

        /*hash_equals 比对验证码是否与缓存中一致
          hash_equals可防止时序攻击的字符串比较
          时序攻击：两个字符串是从第一位开始逐一进行比较的，发现不同就立即返回 false，那么通过计算返回的速度就知道了大概是哪一位开始不同的，这样就实现了电影中经常出现的按位破解密码的场景。而使用 hash_equals 比较两个字符串，无论字符串是否相等，函数的时间消耗是恒定的，这样可以有效的防止时序攻击
        */
        if(!hash_equals($verifyData['code'],$request->verification_code)){
            //返回401:凭证不存在或错误
            return $this->reponse->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        \Cache::forget($request->verification_key);

        //created DingoApi所提供
        return $this->response->item($user,new UserTransformer())
                    ->setMeta([
                        'access_token' => \Auth::guard('api')->fromUser($user),
                        'token_type' => 'Bearer',
                        'expires_in' => \Auth::guard('api')->factory()->getTTL()*60
                    ])->setStatusCode(201);
    }

    //$this->user() 相当于 \Auth::guard('api')->user()
    /*
     *  Auth::user()源码下是没有指定guard，所以会使用默认的guard，
     *  如果你是默认普通的web请求，那么肯定是能够拿到当前用户的。
     *  而 Auth::guard ('API')->user()能拿到用户，
     *  说明你本身是API的请求，所以Auth::user()是拿不到API请求下的用户的，除非你指定默认 guard 为 api
     */
    public function me(){
        return $this->response->item($this->user(),new UserTransformer());
    }

    public function update(UserRequest $request){
        $user = $this->user();

        $attributes = $request->only(['name','email','introduction']);

        if($request->avatar_image_id){
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);
        return $this->response->item($user,new UserTransformer());
    }
}
