<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request,CaptchaBuilder $captchaBuilder){
        $key = 'captcha-' . str_random(15);
        $phone = $request->phone;

        //通过build()创建验证码图片
        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);

        //getPhrase获取验证码文本，跟手机号一同存入缓存
        \Cache::put($key,['phone' => $phone, 'code' => $captcha->getPhrase()],$expiredAt);

        //返回captcha_key 过期时间 以及inline()方法获取base64图片验证码
        $result = [
            'captcha_key' => $key,
            'expired _at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
