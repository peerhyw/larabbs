<?php

namespace App\Providers;

use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Dingo\Api\Auth\Provider\Authorization;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

//写法参考 vendor/dingo/api/src/Auth/Provider/JWT.php
class PassportDingoProvider extends Authorization
{
    protected $auth;

    protected $guard = 'api';

    public function __construct(AuthManager $auth){
        $this->auth = $auth->guard($this->guard);
    }

    public function authenticate(Request $request,Route $route){
        if(!$user = $this->auth->user()){
            throw new UnauthorizedHttpException(get_class($this),'Unable to authenticate with invalid API key and token.');
        }

        return $user;
    }

    public function getAuthorizationMethod(){
        return 'Bearer';
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
