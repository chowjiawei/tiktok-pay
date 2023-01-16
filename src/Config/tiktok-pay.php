<?php

return [
    //抖音的支付 单位全部为分

    //行业交易系统 旧
    'industry-trading-old' => [
        'token' => '', //支付token
        'salt' => '',  //盐值
        'merchant_id' => '',  //商户号
        'app_id' => '',  //app id
        'secret' => '', //secret
        'notify_url' => '',  //支付链接  如果在设置回调链接中设置了支付回调链接 则设置的优先 这个配置不生效
        'private_key_url' => storage_path() . '/pay/tt/private_key.pem',
        'platform_public_key_url' => storage_path() . '/pay/tt/platform_public_key.pem',
        'public_key_url' => storage_path() . '/pay/tt/public_key.pem',
        'version' => '',//支付版本号
        'settle_notify_url' =>  '',//分账回调url
        'refund_notify_url' =>  '',//退款回调url
        'agree_refund_notify_url' =>  '',//同意退款回调url
        'create_order_callback' =>  '',//创建订单回调地址
        'pay_callback' =>  '',//支付回调地址
    ],
    //行业交易系统 新
    'industry-trading' => [

    ],

    //生活服务交易系统
    'life-service' => [

    ],



];
