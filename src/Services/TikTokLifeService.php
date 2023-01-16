<?php

namespace Chowjiawei\TikTokPay\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TikTokLifeService
{
    //生活服务交易系统


    public function __construct()
    {
        $this->config = config('tiktok-pay')['life-service'];
    }
}
