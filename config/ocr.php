<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ocr config
    |--------------------------------------------------------------------------
    |
    | 目前支持的OCR服务商有 aliyun、baidu、tencent、tencentai 具体配置如下
    |
    */

    'ocrs' => [

        'baidu' => [
            'app_key' => 'XGgkqVif73v8wH6W',
            'secret_key' => '6aHHkz236LOYu0nRuBwn5PwT0x3km7EL'
        ],

        'tencent' => [
            'app_id' => '1254032478',
            'secret_id' => 'AKIDzODdB1nOELz0T8CEjTEkgKJOob3t2Tso',
            'secret_key' => '6aHHkz236LOYu0nRuBwn5PwT0x3km7EL',
            'bucket' => 'bucket'
        ],

        'tencentai' => [
            'app_id' => '1106584682',
            'app_key' => 'XGgkqVif73v8wH6W',
        ],

        'aliyun' => [
            'appcode' => '40bc103c7fe6417b87152f6f68bead2f',
        ]
    ]
];
