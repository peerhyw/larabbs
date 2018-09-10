<?php

return[

    //HTTP 请求超时时间（秒）
    'timeout' => 5.0,

    //默认发送配置
    'default' => [
        //网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        //默认可用的发送网关
        'gateways'=> [
            'qcloud',
        ],
    ],

    //可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'qcloud' => [
            'sdk_app_id' => env('QCLOUD_SMS_SDK_APP_ID'),
            'app_key' => env('QCLOUD_SMS_APP_KEY'),
            'sign_name' => env('QCLOUD_SMS_SIGN_NAME'),
        ],
    ],
];

/*$easySms->send(13188888888, [
'template' => 'xxxxxx', //你在腾讯云配置的"短信正文”的模板ID
'data' => [
'code' => 6379 //data数组的内容对应于腾讯云“短信正文“里的变量
],
]);*/