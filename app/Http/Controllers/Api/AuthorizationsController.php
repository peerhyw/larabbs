<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationServer;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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

    public function store(AuthorizationRequest $originRequest,AuthorizationServer $server,ServerRequestInterface $serverRequest){
        /*
         *  respondToAccessTokenRequest 会依次处理：
         *  检测 client 参数是否正确；
         *  检测 scope 参数是否正确；
         *  通过用户名查找用户；
         *  验证用户密码是否正确；
         *  生成 Response 并返回；
         *  最终返回的 Response 是 Zend\Diactoros\Respnose 的实例，
         *  代码位置在 vendor/zendframework/zend-diactoros/src/Response.php，
         *  查看代码我们可以使用 withStatus 方法设置该 Response 的状态码，最后直接返回 Response 即可。
         */
        try {
            //new Psr7Response 如果构造函数有参数就必须要括号，如果没有参数，加不加括号效果都相同。
            return $server->respondToAccessTokenRequest($serverRequest,new Psr7Response)->withStatus(201);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function update(AuthorizationServer $server,ServerRequestInterface $serverRequest){
        try {
            return $server->respondToAccessTokenRequest($serverRequest,new Psr7Response);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function destroy(){
        if(!Auth::guard('api')->check()){
            throw new UnauthorizedHttpException(get_class($this),'Unable to authenticate with invalid API key and token.');
        }
        $this->user()->token()->revoke();
        return $this->response->noContent();
    }

    public function respondWithToken($token){
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL()*60
        ]);
    }
}
