<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request,EasySms $easySms){

        $phone = $request->phone;

        if(!app()->environment('production')){
            $code = '1234';
        }else{
            //生成4位随机数，左侧补0 str_pad() random_int() php内置方法
            $code = str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);

            try {
                $result = $easySms->send($phone,[
                    'template' => env('QCLOUD_SMS_TAMPLATE_REGISTER'),
                    'data' => [
                        'code' => $code
                    ]
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('qcloud')->getMessage();

                //null 合并运算符  c=a??b 如果a存在且不为null则c=a 否则c=b 7.0新语法糖
                return $this->response->errorInternal($message ?? '短信发送异常');
            }
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        //缓存验证码 10分钟过期
        \Cache::put($key,['phone' => $phone,'code' => $code],$expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString()
        ])->setStatusCode(201);
    }
}
