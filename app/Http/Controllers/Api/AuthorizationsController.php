<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function socialStore($social_type,SocialAuthorizationRequest $request){

        if(!in_array($social_type,['weixin'])){
            return $this->response->errorBadRequest();
        }

        $driver = \Socialite::driver($social_type);

        try{
            if($code = $request->code){
                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response,'access_token');
            }else{
                $token = $request->access_token;

                if($social_type == 'weixin'){
                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);

        }catch(\Exception $e){
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($social_type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;
                if($unionid){
                    $user = User::where('weixin_unionid',$unionid)->first();
                }else{
                    $user = User::where('weixin_openid',$oauthUser->getId())->first();
                }

                //没有用户，默认创建一个用户
                if(!$user){
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }
                break;
        }

        //第三方登录获取 user 后，我们可以使用 fromUser 方法为某一个用户模型生成token
        $token = Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function store(AuthorizationRequest $request){
        $username = $request->username;

        //email or phone
        filter_var($username,FILTER_VALIDATE_EMAIL) ? $credentials['email'] = $username :
                                                      $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        //登录后，我们可以使用 fromUser 方法为某一个用户模型生成token
        if(!$token = \Auth::guard('api')->attempt($credentials)){
            return $this->response->errorUnauthorized(trans('auth.failed'));
        }

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function update(){
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy(){
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    public function respondWithToken($token){
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL()*60
        ]);
    }
}
