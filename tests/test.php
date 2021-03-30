<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$sms = new \XinFox\Sms\XinFoxSms(
    [
        'gateways' => [
            'aliyun' => [
                'access_key_id' => 'LTAI4G2SpRh79KZEjVwBiGNh',
                'access_key_secret' => 'bbuYKOjITXemdSkB9pkPdfThVSsTcO',
                'sign_name' => '诺森教育'
            ]
        ]
    ]
);
$result = $sms->gateway('aliyun')->send(
    17376006101,
    [
        'template' => function(\XinFox\Sms\Contracts\GatewayInterface $gateway) {
            return 'SMS_205139885';
        },
        'data' => function(\XinFox\Sms\Contracts\GatewayInterface $gateway) {
            return [
                'name' => "小艾"
            ];
        }
    ]
);
var_dump($result);