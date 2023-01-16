<?php

namespace Chowjiawei\TikTokPay\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TikTokIndustryTradingService
{
    //行业交易系统
    public $queryOrderUrl =  "/api/apps/trade/v2/order/query_order"; //查询订单接口
    public $queryOrderMethod =  "POST"; //查询订单接口查询方式
    public $queryOrderFullUrl = 'https://open.douyin.com/api/apps/trade/v2/order/query_order'; //查询订单完整接口

    public $createRefundOrderUrl = '/api/apps/trade/v2/refund/create_refund';  //创建退款接口
    public $createRefundOrderMethod = 'POST'; //创建退款接口查询方式
    public $createRefundOrderFullUrl = 'https://open.douyin.com/api/apps/trade/v2/refund/create_refund'; //创建退款完整接口

    public $agreeRefundOrderUrl = '/api/apps/trade/v2/refund/merchant_audit_callback'; //同意退款接口
    public $agreeRefundOrderMethod = 'POST'; //同意退款接口查询方式
    public $agreeRefundOrderFullUrl = 'https://open.douyin.com/api/apps/trade/v2/refund/merchant_audit_callback'; //同意退款完整接口

    public $queryRefundOrderUrl = '/api/apps/trade/v2/refund/query_refund'; //查询退款接口
    public $queryRefundOrderMethod = 'POST'; //查询退款接口查询方式
    public $queryRefundOrderFullUrl = 'https://open.douyin.com/api/apps/trade/v2/refund/query_refund'; //查询退款接口完整接口


    public $createSettleUrl = '/api/apps/trade/v2/settle/create_settle'; //发起分账接口
    public $createSettleMethod = 'POST'; //发起分账接口查询方式
    public $createSettleFullUrl = 'https://open.douyin.com/api/apps/trade/v2/settle/create_settle'; //发起分账完整接口

    public $querySettleUrl = '/api/apps/trade/v2/settle/create_settle'; //查询分账接口
    public $querySettleMethod = 'POST'; //查询分账接口查询方式
    public $querySettleFullUrl = 'https://open.douyin.com/api/apps/trade/v2/settle/query_settle'; //查询分账完整接口


    public function __construct()
    {
        $this->config = config('tiktok-pay')['industry-trading'];
    }
}
