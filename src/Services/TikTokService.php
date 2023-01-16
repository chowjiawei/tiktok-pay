<?php

namespace Chowjiawei\TikTokPay\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TikTokService
{
    public $queryOrderUrl =  "/api/apps/trade/v2/query_order"; //查询订单接口
    public $queryOrderMethod =  "POST"; //查询订单接口查询方式
    public $queryOrderFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/query_order'; //查询订单完整接口

    public $createRefundOrderUrl = '/api/apps/trade/v2/create_refund';  //创建退款接口
    public $createRefundOrderMethod = 'POST'; //创建退款接口查询方式
    public $createRefundOrderFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/create_refund'; //创建退款完整接口

    public $agreeRefundOrderUrl = '/api/apps/trade/v2/merchant_audit_callback'; //同意退款接口
    public $agreeRefundOrderMethod = 'POST'; //同意退款接口查询方式
    public $agreeRefundOrderFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/merchant_audit_callback'; //同意退款完整接口

    public $queryRefundOrderUrl = '/api/apps/trade/v2/query_refund'; //查询退款接口
    public $queryRefundOrderMethod = 'POST'; //查询退款接口查询方式
    public $queryRefundOrderFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/query_refund'; //查询退款接口完整接口

    public $setCallBackConfigUrl = '/api/apps/trade/v2/settings'; //设置回调信息接口
    public $setCallBackConfigMethod = 'POST'; //设置回调信息接口查询方式
    public $setCallBackConfigFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/settings'; //设置回调信息接口完整接口

    public $queryCallBackConfigUrl = '/api/apps/trade/v2/query_settings'; //查询当前回调设置接口
    public $queryCallBackConfigMethod = 'POST'; //查询当前回调设置接口查询方式
    public $queryCallBackConfigFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/query_settings'; //查询当前回调设置完整接口

    public $createSettleUrl = '/api/apps/trade/v2/create_settle'; //发起分账接口
    public $createSettleMethod = 'POST'; //发起分账接口查询方式
    public $createSettleFullUrl = 'https://developer.toutiao.com/api/apps/trade/v2/create_settle'; //发起分账完整接口


    public function __construct()
    {
        $tikTokPay = app('TikTokPay');
        $this->config = $tikTokPay->getConfig();
    }
}
