<?php

namespace Chowjiawei\TikTokPay\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TikTokIndustryTradingService
{
    //行业交易系统


    public function __construct()
    {
        $this->config = config('tiktok-pay')['industry-trading'];
    }
}
